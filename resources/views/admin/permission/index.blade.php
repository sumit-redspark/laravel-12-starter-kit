@extends('layouts.app')

@section('title', 'Permissions Management')

@section('meta_title', 'Permissions Management')
@section('meta_author', 'Admin')
@section('meta_description', 'Manage permissions for your application')
@section('meta_keywords', 'permissions, management, admin')

@section('header')
@endsection

@section('content-header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Permissions Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
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
                    <h3 class="card-title">Permissions List</h3>
                    <div class="card-tools">
                        @role(\App\Enums\Role::SUPER_ADMIN)
                            @can($Permission::PERMISSION_CREATE)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#permissionModal">
                                    <i class="fas fa-plus"></i> Add New Permission
                                </button>
                            @endcan
                        @endrole
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="permissionsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Add Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permissionForm" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" id="permission_id" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="savePermission">Save</button>
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
            var table = $('#permissionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.permissions.list') }}",
                columns: [{
                        data: null,
                        name: 'row_number',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Initialize form validation
            $("#permissionForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a permission name",
                        minlength: "Permission name must be at least 3 characters long",
                        maxlength: "Permission name cannot exceed 50 characters"
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
                    savePermission();
                    return false; // Prevent default form submission
                }
            });

            // Function to save permission
            function savePermission() {
                var id = $('#permission_id').val();
                var url = id ? "{{ route('admin.permissions.update', ':id') }}".replace(':id', id) :
                    "{{ route('admin.permissions.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        name: $('#name').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Remove focus from the save button before hiding modal
                        $('#savePermission').blur();
                        $('#permissionModal').modal('hide');
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

            // Handle edit button click
            $(document).on('click', '.edit-data', function() {
                var id = $(this).data('id');

                // Fetch the latest data from the controller
                $.ajax({
                    url: "{{ route('admin.permissions.edit', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var permission = response.data;
                            $('#permission_id').val(permission.id);
                            $('#name').val(permission.name);
                            $('#permissionModalLabel').text('Edit Permission');
                            $('#permissionModal').modal('show');
                        } else {
                            showError('Failed to fetch permission data');
                        }
                    },
                    error: function() {
                        showError('An error occurred while fetching permission data');
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-data', function() {
                var id = $(this).data('id');
                showConfirm("You won't be able to revert this!", "Are you sure?", {
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.permissions.destroy', ':id') }}".replace(
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
                                    'An error occurred while deleting the permission'
                                );
                            }
                        });
                    }
                });
            });

            // Handle modal events
            $('#permissionModal').on('hidden.bs.modal', function() {
                resetForm();
                // Remove focus from any focused elements
                $(':focus').blur();
            });

            // Reset form function
            function resetForm() {
                $('#permission_id').val('');
                $('#name').val('');
                $('#permissionModalLabel').text('Add Permission');
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');
                // Reset validation
                $("#permissionForm").validate().resetForm();
            }
        });
    </script>
@endsection
