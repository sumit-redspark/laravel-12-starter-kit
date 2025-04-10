<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ 'Redspark Admin Panel Login' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Redspark Admin | Login Page" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="Redspark Admin is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />

    <!-- Third-party plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
</head>

<body class="login-page bg-body-secondary">
    <!-- Login Box Container -->
    <div class="login-box">
        <div class="card card-outline card-primary">
            <!-- Header with Logo -->
            <div class="card-header">
                <a href="/" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>User</b> Login</h1>
                </a>
            </div>

            <!-- Login Form -->
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <div id="login-error" class="alert alert-danger d-none"></div>
                <form id="loginForm" method="POST" action="{{ route('custom-authenticate') }}">
                    @csrf
                    <!-- Email Input -->
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="loginEmail" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                placeholder="" required autofocus />
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        <div class="invalid-feedback email-error"></div>
                    </div>

                    <!-- Password Input -->
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="loginPassword" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="" required />
                            <label for="loginPassword">Password</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        <div class="invalid-feedback password-error"></div>
                    </div>

                    <!-- Remember Me & Sign In Button -->
                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label" for="remember"> Remember Me </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="loginButton">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    Sign In
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Social Login Options -->
                {{-- <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-primary">
                        <i class="bi bi-facebook me-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-danger">
                        <i class="bi bi-google me-2"></i> Sign in using Google+
                    </a>
                </div> --}}

                <!-- Forgot Password & Register Links -->
                <p class="mb-1"><a href="#">I forgot my password</a></p>
                <p class="mb-0">
                    <a href="#" class="text-center"> Register a new membership </a>
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <!-- Login Form JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const spinner = loginButton.querySelector('.spinner-border');
            const loginError = document.getElementById('login-error');

            function showError(message, type = 'danger') {
                loginError.className = `alert alert-${type} mb-3`;
                loginError.textContent = message;
                loginError.classList.remove('d-none');

                // Scroll to error message if it's not visible
                if (!isElementInViewport(loginError)) {
                    loginError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            function clearErrors() {
                loginError.classList.add('d-none');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            function showFieldError(field, message) {
                const input = document.querySelector(`[name="${field}"]`);
                const errorDiv = input.closest('.input-group').querySelector('.invalid-feedback');
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
            }

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                clearErrors();

                // Show loading state
                spinner.classList.remove('d-none');
                loginButton.disabled = true;

                // Get form data
                const formData = new FormData(loginForm);

                // Send AJAX request
                fetch(loginForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message before redirect
                            showError('Login successful! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect || '/admin/';
                            }, 1000);
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                // Show general message first if available
                                if (data.message) {
                                    showError(data.message);
                                }

                                // Then show field-specific errors
                                Object.keys(data.errors).forEach(field => {
                                    showFieldError(field, data.errors[field][0]);
                                });
                            } else if (data.message) {
                                // Show general error message
                                showError(data.message);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Login error:', error);
                        showError('An unexpected error occurred. Please try again.');
                    })
                    .finally(() => {
                        // Reset loading state
                        spinner.classList.add('d-none');
                        loginButton.disabled = false;
                    });
            });

            // Clear errors when user starts typing
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');
                        this.closest('.input-group').querySelector('.invalid-feedback')
                            .textContent = '';

                        // If this was the last error, hide the general error message
                        if (document.querySelectorAll('.is-invalid').length === 0) {
                            loginError.classList.add('d-none');
                        }
                    }
                });
            });
        });
    </script>

    <!-- OverlayScrollbars Configuration -->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };

        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
</body>

</html>
