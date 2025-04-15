@extends('layouts.app')

@section('title', __('permissions.title'))

@section('meta_title', __('permissions.meta_title'))
@section('meta_author', __('permissions.meta_author'))
@section('meta_description', __('permissions.meta_description'))
@section('meta_keywords', __('permissions.meta_keywords'))

@section('header')
@endsection

@section('content-header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">@lang('permissions.management')</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">@lang('permissions.home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('permissions.permissions')</li>
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
                    <h3 class="card-title">@lang('permissions.list')</h3>
                    <div class="card-tools">
                        @role(\App\Enums\Role::SUPER_ADMIN)
                            @can($Permission::PERMISSION_CREATE)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#permissionModal">
                                    <i class="fas fa-plus"></i> @lang('permissions.add_new')
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
                                <th>@lang('permissions.name')</th>
                                <th>@lang('permissions.guard_name')</th>
                                <th>@lang('permissions.created_at')</th>
                                <th>@lang('permissions.actions')</th>
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
                    <h5 class="modal-title" id="permissionModalLabel">@lang('permissions.add_permission')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permissionForm" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" id="permission_id" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">@lang('permissions.permission_name')</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('permissions.close')</button>
                        <button type="submit" class="btn btn-primary" id="savePermission">@lang('permissions.save')</button>
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
                        required: "@lang('permissions.validation.required')",
                        minlength: "@lang('permissions.validation.minlength')",
                        maxlength: "@lang('permissions.validation.maxlength')"
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
                            showError('@lang('permissions.errors.general')');
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
                            $('#permissionModalLabel').text('@lang('permissions.edit_permission')');
                            $('#permissionModal').modal('show');
                        } else {
                            showError('@lang('permissions.errors.fetch_failed')');
                        }
                    },
                    error: function() {
                        showError('@lang('permissions.errors.fetch_error')');
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-data', function() {
                var id = $(this).data('id');
                showConfirm("@lang('permissions.delete.warning')", "@lang('permissions.delete.confirm')", {
                    confirmButtonText: '@lang('permissions.delete.confirm_button')'
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
                                showError('@lang('permissions.errors.delete_error')');
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
                $('#permissionModalLabel').text('@lang('permissions.add_permission')');
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');
                // Reset validation
                $("#permissionForm").validate().resetForm();
            }
        });
    </script>
@endsection
