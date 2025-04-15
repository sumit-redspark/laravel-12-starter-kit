@extends('layouts.app')

@section('title', 'Products Management')

@section('meta_title', 'Products Management')
@section('meta_author', 'Admin')
@section('meta_description', 'Manage products for your application')
@section('meta_keywords', 'products, management, admin')

@section('header')
@endsection

@section('content-header')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Products Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <!-- Global Language Selector -->
            <div class="mb-4">
                <h5>Global Language: </h5>
                <form action="{{ route('language.switch') }}" method="POST" class="d-inline" id="languageForm">
                    @csrf
                    <input type="hidden" name="type" value="global">
                    <select name="locale" class="form-select" id="globalLanguageSelect">
                        @foreach (config('app.available_locales', ['en' => 'English']) as $locale => $language)
                            <option value="{{ $locale }}" {{ app()->getLocale() === $locale ? 'selected' : '' }}>
                                {{ $language }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Success Message Alert -->
            <div id="successAlert" class="alert alert-success d-none"></div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products List</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#productModal">
                            <i class="fas fa-plus"></i> Add New Product
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="productsTable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
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

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productForm" onsubmit="return false;">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="id">

                        <!-- Language Selection -->
                        <div class="mb-3">
                            <label class="form-label">Select Language</label>
                            <select class="form-select" id="languageSelect" name="language">
                                @foreach (config('app.available_locales', ['en' => 'English']) as $locale => $language)
                                    <option value="{{ $locale }}"
                                        {{ app()->getLocale() === $locale ? 'selected' : '' }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Details -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveProduct">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle global language change
            $('#globalLanguageSelect').change(function() {
                $('#languageForm').submit();
            });

            // Initialize DataTable
            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.products.list') }}",
                    type: 'GET',
                    data: function(d) {
                        d.language = '{{ app()->getLocale() }}';
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable AJAX Error:', error, thrown);
                        showError('Error loading data. Please try again.');
                    }
                },
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
                        data: 'description',
                        name: 'description',
                        width: '30%'
                    },
                    {
                        data: 'price',
                        name: 'price',
                        width: '10%',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
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
                        width: '20%',
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary edit-data" data-id="${row.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-data" data-id="${row.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                error: function(xhr, error, thrown) {
                    console.error('DataTable Error:', error, thrown);
                    showError('Error loading data. Please try again.');
                }
            });

            // Reset form function
            function resetForm() {
                $('#product_id').val('');
                $('#price').val('');
                $('#name').val('');
                $('#description').val('');
                $('#productModalLabel').text('Add Product');
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');
                $('#languageSelect').val('{{ app()->getLocale() }}');
            }

            // Handle language change in modal
            $('#languageSelect').change(function() {
                const productId = $('#product_id').val();
                const selectedLanguage = $(this).val();

                if (productId) {
                    // Show loading state
                    $('#name').val('Loading...');
                    $('#description').val('Loading...');

                    $.ajax({
                        url: "{{ route('admin.products.edit', ':id') }}".replace(':id', productId),
                        type: 'GET',
                        data: {
                            language: selectedLanguage
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#name').val(response.product.name || '');
                                $('#description').val(response.product.description || '');
                                $('#price').val(response.product.price);
                            }
                        },
                        error: function() {
                            showError('An error occurred while loading the product data');
                            // Clear fields on error
                            $('#name').val('');
                            $('#description').val('');
                        }
                    });
                } else {
                    // For new products, just clear the fields
                    $('#name').val('');
                    $('#description').val('');
                }
            });

            // Save product
            $('#saveProduct').click(function() {
                var formData = {
                    id: $('#product_id').val(),
                    language: $('#languageSelect').val(),
                    name: $('#name').val(),
                    description: $('#description').val(),
                    price: $('#price').val()
                };

                var url = formData.id ?
                    "{{ route('admin.products.update', ':id') }}".replace(':id', formData.id) :
                    "{{ route('admin.products.store') }}";
                var method = formData.id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#productModal').modal('hide');
                            resetForm();
                            table.ajax.reload();
                            showSuccessMessage(response.message);
                        } else {
                            showErrors(response.errors);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showErrors(xhr.responseJSON.errors);
                        } else {
                            showError('An error occurred while saving the product');
                        }
                    }
                });
            });

            // Edit product
            $(document).on('click', '.edit-data', function() {
                var productId = $(this).data('id');
                var currentLanguage = '{{ app()->getLocale() }}';

                // Set the modal language selector to current global language
                $('#languageSelect').val(currentLanguage);

                $.ajax({
                    url: "{{ route('admin.products.edit', ':id') }}".replace(':id', productId),
                    type: 'GET',
                    data: {
                        language: currentLanguage
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#product_id').val(response.product.id);
                            $('#price').val(response.product.price);
                            $('#name').val(response.product.name || '');
                            $('#description').val(response.product.description || '');

                            $('#productModalLabel').text('Edit Product');
                            $('#productModal').modal('show');
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function() {
                        showError('An error occurred while loading the product');
                    }
                });
            });

            // Delete product
            $(document).on('click', '.delete-data', function() {
                var productId = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: "{{ route('admin.products.destroy', ':id') }}".replace(':id',
                            productId),
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                showSuccessMessage(response.message);
                            } else {
                                showError(response.message);
                            }
                        },
                        error: function() {
                            showError('An error occurred while deleting the product');
                        }
                    });
                }
            });

            // Show success message
            function showSuccessMessage(message) {
                $('#successAlert').text(message).removeClass('d-none');
                setTimeout(() => {
                    $('#successAlert').addClass('d-none');
                }, 3000);
            }

            // Show error message
            function showError(message) {
                toastr.error(message);
            }

            // Show validation errors
            function showErrors(errors) {
                $('.invalid-feedback').empty();
                $('.is-invalid').removeClass('is-invalid');

                for (var field in errors) {
                    var input = $('[name="' + field + '"]');
                    var feedback = input.next('.invalid-feedback');
                    input.addClass('is-invalid');
                    feedback.text(errors[field][0]);
                }
            }

            // Reset form when modal is closed
            $('#productModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });
    </script>
@endsection
