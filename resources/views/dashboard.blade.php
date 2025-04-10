@extends('layouts.app')

@section('title', 'Home Page')

@section('meta_title', 'Welcome to Redspark Admin Dashboard')
@section('meta_author', 'Redspark Admin')
@section('meta_description',
    'Welcome to our powerful Redspark Admin dashboard built with Bootstrap 5. Manage your
    application with ease.')
@section('meta_keywords', 'laravel, admin dashboard, bootstrap 5, responsive, management, dashboard')

@section('header')
@endsection

@section('content-header')
    <!-- Breadcrumb navigation -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Welcome to AdminLTE</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Welcome to AdminLTE</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="text-center">
                <h1 class="display-4">Welcome to AdminLTE</h1>
                <p class="lead">A fully responsive administration template built with Bootstrap 5</p>

                <!-- Feature highlights -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Modern Design</h5>
                                <p class="card-text">Clean and intuitive dashboard interface built with the latest Bootstrap
                                    framework.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Powerful Features</h5>
                                <p class="card-text">Packed with features like charts, tables, forms and many UI components.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Easy Customization</h5>
                                <p class="card-text">Highly customizable template that can be adapted to your needs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to action buttons -->
                <div class="mt-5">
                    <a href="#" class="btn btn-lg btn-primary me-3">Get Started</a>
                    <a href="#" class="btn btn-lg btn-outline-secondary">Learn More</a>
                </div>

                <!-- Logout Button -->
                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Initialize home page functionality
        console.log('Home page script running...');
    </script>
@endsection
