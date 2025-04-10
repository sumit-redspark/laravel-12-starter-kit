@extends('layouts.app')

@section('title', 'Users Management')

@section('meta_title', 'Users Management')
@section('meta_author', 'Admin')
@section('meta_description', 'Manage users for your application')
@section('meta_keywords', 'users, management, admin')

@section('header')
@endsection

@section('content-header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Users Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users List</h3>
                    <div class="card-tools">
                        @can($Permission::USER_CREATE)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                                <i class="fas fa-plus"></i> Add New User
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="usersTable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Email Verified</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="mb-3">
                            <label for="roles" class="form-label">Roles</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="roleSearch" placeholder="Search roles...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">Clear</button>
                            </div>
                            <div class="roles-container"
                                style="max-height: 300px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 0.25rem; padding: 10px;">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="selectAllRoles">
                                    <label class="form-check-label" for="selectAllRoles">
                                        Select All Roles
                                    </label>
                                </div>
                                <div id="rolesList" class="row">
                                    <!-- Roles will be loaded here -->
                                </div>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveUser">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.list') }}",
                scrollX: true,
                autoWidth: false,
                columns: [{
                        data: null,
                        name: 'row_number',
                        orderable: false,
                        searchable: false,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        width: '20%'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        width: '20%'
                    },
                    {
                        data: 'email_verified_at',
                        name: 'email_verified_at',
                        width: '15%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%'
                    }
                ]
            });

            // Reset form function
            function resetForm() {
                $('#user_id').val('');
                $('#name').val('');
                $('#email').val('');
                $('#password').val('');
                $('#userModalLabel').text('Add User');
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');
                $('.role-checkbox').prop('checked', false);
                $('#roleSearch').val('');
                $('.role-item').show();
                // Reset validation
                $("#userForm").validate().resetForm();
            }

            // Load roles
            function loadRoles(callback = null) {
                $.ajax({
                    url: "{{ route('admin.users.roles') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var roles = response.data;
                            var rolesHtml = '';

                            // Clear existing roles first
                            $('#rolesList').empty();

                            roles.forEach(function(role) {
                                rolesHtml += `
                                    <div class="col-md-6 role-item">
                                        <div class="form-check">
                                            <input class="form-check-input role-checkbox" type="checkbox"
                                                name="roles[]" value="${role.id}" id="role_${role.id}">
                                            <label class="form-check-label" for="role_${role.id}">
                                                ${role.name}
                                            </label>
                                        </div>
                                    </div>
                                `;
                            });

                            $('#rolesList').html(rolesHtml);
                            initializeRoleCheckboxes();

                            // Execute callback if provided
                            if (typeof callback === 'function') {
                                callback();
                            }
                        } else {
                            showError(response.message || 'Failed to load roles');
                        }
                    },
                    error: function(xhr) {
                        showError('An error occurred while loading roles');
                        console.error('Error loading roles:', xhr);
                    }
                });
            }

            // Select roles
            function selectRoles(roleIds) {
                // Uncheck all roles first
                $('.role-checkbox').prop('checked', false);

                // Check the selected roles
                roleIds.forEach(function(roleId) {
                    $(`#role_${roleId}`).prop('checked', true);
                });

                // Update select all checkbox
                var allChecked = $('.role-checkbox:checked').length === $('.role-checkbox').length;
                $('#selectAllRoles').prop('checked', allChecked);
            }

            // Initialize role checkboxes event handlers
            function initializeRoleCheckboxes() {
                // Handle Select All checkbox
                $('#selectAllRoles').off('change').on('change', function() {
                    $('.role-checkbox').prop('checked', $(this).prop('checked'));
                });

                // Handle individual role checkboxes
                $('.role-checkbox').off('change').on('change', function() {
                    var allChecked = $('.role-checkbox:checked').length === $('.role-checkbox').length;
                    $('#selectAllRoles').prop('checked', allChecked);
                });
            }

            // Load roles when modal is opened (for new user)
            $('#userModal').on('show.bs.modal', function() {
                if (!$('#user_id').val()) {
                    loadRoles();
                }
            });

            // Handle modal close
            $('#userModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // Handle edit button click
            $(document).on('click', '.edit-data', function() {
                @can($Permission::USER_EDIT)
                    var id = $(this).data('id');

                    // Reset form first
                    resetForm();

                    // Set the form values
                    $('#user_id').val(id);
                    $('#userModalLabel').text('Edit User');

                    // First load roles, then get user data
                    loadRoles(function() {
                        // After roles are loaded, fetch user data
                        $.ajax({
                            url: "{{ route('admin.users.edit', ':id') }}".replace(':id',
                                id),
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    var user = response.data;
                                    $('#name').val(user.name);
                                    $('#email').val(user.email);
                                    // Select the roles
                                    selectRoles(user.roles);
                                } else {
                                    showError(response.message ||
                                        'Failed to fetch user data');
                                }
                            },
                            error: function() {
                                showError('An error occurred while fetching user data');
                            }
                        });
                    });

                    $('#userModal').modal('show');
                @else
                    showError('You do not have permission to edit users');
                @endcan
            });

            // Role search functionality
            $('#roleSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.role-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#roleSearch').val('');
                $('.role-item').show();
            });

            // Initialize form validation
            $("#userForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    password: {
                        minlength: 8
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Name must be at least 3 characters long",
                        maxlength: "Name cannot exceed 255 characters"
                    },
                    email: {
                        required: "Please enter an email address",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot exceed 255 characters"
                    },
                    password: {
                        minlength: "Password must be at least 8 characters long"
                    }
                },
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    // This will be called when the form is valid
                    saveUser();
                    return false; // Prevent default form submission
                }
            });

            // Function to save user
            function saveUser() {
                var id = $('#user_id').val();
                var url = id ? "{{ route('admin.users.update', ':id') }}".replace(':id', id) :
                    "{{ route('admin.users.store') }}";
                var method = id ? 'PUT' : 'POST';

                // Get selected roles
                var roles = [];
                $('input[name="roles[]"]:checked').each(function() {
                    roles.push($(this).val());
                });

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        roles: roles,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Remove focus from the save button before hiding modal
                        $('#saveUser').blur();
                        $('#userModal').modal('hide');
                        table.ajax.reload();
                        showSuccess(response.message);
                        resetForm();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}`).siblings('.invalid-feedback').text(errors[key][0]);
                            });
                        } else {
                            showError('An error occurred');
                        }
                    }
                });
            }

            // Handle delete button click
            $(document).on('click', '.delete-data', function() {
                @can($Permission::USER_DELETE)
                    var id = $(this).data('id');
                    showConfirm("You won't be able to revert this!", "Are you sure?", {
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.users.destroy', ':id') }}".replace(
                                    ':id',
                                    id),
                                type: 'DELETE',
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    table.ajax.reload();
                                    showSuccess(response.message);
                                },
                                error: function() {
                                    showError(
                                        'An error occurred while deleting the user');
                                }
                            });
                        }
                    });
                @else
                    showError('You do not have permission to delete users');
                @endcan
            });
        });
    </script>
@endsection
