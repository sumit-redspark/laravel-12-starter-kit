@extends('layouts.app')

@section('title', 'Roles Management')

@section('meta_title', 'Roles Management')
@section('meta_author', 'Admin')
@section('meta_description', 'Manage roles for your application')
@section('meta_keywords', 'roles, management, admin')

@section('header')
@endsection

@section('content-header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Roles Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Roles</li>
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
                    <h3 class="card-title">Roles List</h3>
                    <div class="card-tools">
                        @can($Permission::ROLE_CREATE)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                                <i class="fas fa-plus"></i> Add New Role
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="rolesTable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
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

    <!-- Role Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="roleForm" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" id="role_id" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="permissions" class="form-label">Permissions</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="permissionSearch"
                                    placeholder="Search permissions...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">Clear</button>
                            </div>
                            <div class="permissions-container"
                                style="max-height: 300px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 0.25rem; padding: 10px;">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                    <label class="form-check-label" for="selectAllPermissions">
                                        Select All Permissions
                                    </label>
                                </div>
                                <div id="permissionsList" class="row">
                                    <!-- Permissions will be loaded here -->
                                </div>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveRole">Save</button>
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
            var table = $('#rolesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.roles.list') }}",
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
                        width: '30%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '20%'
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
                $('#role_id').val('');
                $('#name').val('');
                $('#roleModalLabel').text('Add Role');
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');
                $('.permission-checkbox').prop('checked', false);
                $('#permissionSearch').val('');
                $('.permission-item').show();
                // Reset validation
                $("#roleForm").validate().resetForm();
            }

            // Load permissions
            function loadPermissions(callback = null) {
                $.ajax({
                    url: "{{ route('admin.roles.permissions') }}",
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var permissions = response.data;
                            var permissionsHtml = '';

                            // Clear existing permissions first
                            $('#permissionsList').empty();

                            permissions.forEach(function(permission) {
                                permissionsHtml += `
                                    <div class="col-md-6 permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                name="permissions[]" value="${permission.id}" id="permission_${permission.id}">
                                            <label class="form-check-label" for="permission_${permission.id}">
                                                ${permission.name}
                                            </label>
                                        </div>
                                    </div>
                                `;
                            });

                            $('#permissionsList').html(permissionsHtml);
                            initializePermissionCheckboxes();

                            // Execute callback if provided
                            if (typeof callback === 'function') {
                                callback();
                            }
                        } else {
                            showError(response.message || 'Failed to load permissions');
                        }
                    },
                    error: function(xhr) {
                        showError('An error occurred while loading permissions');
                        console.error('Error loading permissions:', xhr);
                    }
                });
            }

            // Select permissions
            function selectPermissions(permissionIds) {
                // Uncheck all permissions first
                $('.permission-checkbox').prop('checked', false);

                // Check the selected permissions
                permissionIds.forEach(function(permissionId) {
                    $(`#permission_${permissionId}`).prop('checked', true);
                });

                // Update select all checkbox
                var allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox').length;
                $('#selectAllPermissions').prop('checked', allChecked);
            }

            // Initialize permission checkboxes event handlers
            function initializePermissionCheckboxes() {
                // Handle Select All checkbox
                $('#selectAllPermissions').off('change').on('change', function() {
                    $('.permission-checkbox').prop('checked', $(this).prop('checked'));
                });

                // Handle individual permission checkboxes
                $('.permission-checkbox').off('change').on('change', function() {
                    var allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox')
                        .length;
                    $('#selectAllPermissions').prop('checked', allChecked);
                });
            }

            // Load permissions when modal is opened (for new role)
            $('#roleModal').on('show.bs.modal', function() {
                if (!$('#role_id').val()) {
                    loadPermissions();
                }
            });

            // Handle modal close
            $('#roleModal').on('hidden.bs.modal', function() {
                resetForm();
            });

            // Handle edit button click
            $(document).on('click', '.edit-data', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var guard = $(this).data('guard');

                // Reset form first
                resetForm();

                // Set the form values
                $('#role_id').val(id);
                $('#name').val(name || '');
                $('#roleModalLabel').text('Edit Role');

                // First load permissions, then get role data
                loadPermissions(function() {
                    // After permissions are loaded, fetch role data
                    $.ajax({
                        url: "{{ route('admin.roles.edit', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                // Update name from latest data
                                $('#name').val(response.data.name || '');
                                // Select the permissions
                                selectPermissions(response.data.permissions);
                            } else {
                                showError(response.message ||
                                    'Failed to fetch role data');
                            }
                        },
                        error: function(xhr) {
                            showError('An error occurred while fetching role data');
                            console.error('Error fetching role data:', xhr);
                        }
                    });
                });

                $('#roleModal').modal('show');
            });

            // Permission search functionality
            $('#permissionSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.permission-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#permissionSearch').val('');
                $('.permission-item').show();
            });

            // Initialize form validation
            $("#roleForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a role name",
                        minlength: "Role name must be at least 3 characters long",
                        maxlength: "Role name cannot exceed 50 characters"
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
                    saveRole();
                    return false; // Prevent default form submission
                }
            });

            // Function to save role
            function saveRole() {
                var id = $('#role_id').val();
                var url = id ? "{{ route('admin.roles.update', ':id') }}".replace(':id', id) :
                    "{{ route('admin.roles.store') }}";
                var method = id ? 'PUT' : 'POST';

                // Get selected permissions
                var permissions = [];
                $('input[name="permissions[]"]:checked').each(function() {
                    permissions.push($(this).val());
                });

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        name: $('#name').val(),
                        permissions: permissions,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Remove focus from the save button before hiding modal
                        $('#saveRole').blur();
                        $('#roleModal').modal('hide');
                        table.ajax.reload();
                        showSuccess(response.message);
                        resetForm();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}`).siblings('.invalid-feedback').text(errors[
                                    key][0]);
                            });
                        } else {
                            showError('An error occurred');
                        }
                    }
                });
            }

            // Handle delete button click
            $(document).on('click', '.delete-data', function() {
                var id = $(this).data('id');
                showConfirm("You won't be able to revert this!", "Are you sure?", {
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.roles.destroy', ':id') }}".replace(
                                ':id', id),
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
                                    'An error occurred while deleting the role'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
