# Laravel Starter Kit

A comprehensive Laravel starter kit with pre-configured authentication, permissions, and essential features to kickstart your Laravel projects.

## 🚀 Tech Stack

### Backend

-   Laravel 12.x
-   PHP 8.2+
-   MySQL 8.0+ / PostgreSQL 13+

### Frontend

-   Bootstrap 5.3.3
-   jQuery 3.7.1
-   DataTables 1.13.7
-   SweetAlert2
-   jQuery Validation
-   OverlayScrollbars
-   Bootstrap Icons
-   Source Sans 3 Font

## 📦 Key Packages

### Core Dependencies

-   `laravel/framework` ^12.0
-   `spatie/laravel-permission` ^6.16
-   `yajra/laravel-datatables` 12.0

### Development Tools

-   `laravel/breeze` ^2.3
-   `laravel/pint` ^1.13
-   `laravel/sail` ^1.41
-   `nunomaduro/collision` ^8.6
-   `phpunit/phpunit` ^11.5.3

### Testing & Development

-   `fakerphp/faker` ^1.23
-   `mockery/mockery` ^1.6
-   `laravel/pail` ^1.2.2

### Frontend Dependencies

-   Bootstrap 5.3.3
-   jQuery 3.7.1
-   DataTables 1.13.7
-   SweetAlert2
-   jQuery Validation 1.20.0
-   OverlayScrollbars 2.4.5
-   Bootstrap Icons 1.11.3
-   Source Sans 3 Font 5.0.12

### Authentication & Authorization

-   `laravel/sanctum` - API Authentication
-   `laravel/passport` - OAuth2 Server Implementation

### Database & ORM

-   `laravel/eloquent` - Active Record ORM
-   `laravel/migrations` - Database migrations

## 📋 Prerequisites

-   PHP >= 8.2
-   Composer >= 2.0
-   MySQL >= 8.0 or PostgreSQL >= 13
-   Node.js >= 16.x & NPM >= 8.x
-   Git

## 🛠️ Installation

1. Clone the repository:

```bash
git clone https://github.com/sumit-redspark/laravel-12-starter-kit.git
cd laravel-12-starter-kit
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

## 🗄️ Database Setup

1. Configure your database in `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_starter
DB_USERNAME=root
DB_PASSWORD=
```

2. Run migrations:

```bash
php artisan migrate
```

3. Run seeders:

```bash
php artisan db:seed
```

The seeders will create the following data:

### Roles and Permissions

-   **Super Admin Role**: Has all permissions
-   **Admin Role**: Has permissions for user and role management
-   **User Role**: Has basic view permissions

### Default Users

Three default users are created with the following credentials:

1. **Super Admin**

    - Email: superadmin@superadmin.com
    - Password: 123456
    - Role: Super Admin (Full access)

2. **Admin**

    - Email: admin@admin.com
    - Password: 123456
    - Role: Admin (User and Role management access)

3. **Regular User**
    - Email: user@user.com
    - Password: 123456
    - Role: User (Basic view access)

You can use any of these accounts to log in and test the system. It's recommended to change these passwords after your first login.

## 📋 Permissions Setup

1. The permissions are defined in `app/Enums/PermissionEnum.php`. You can add new permissions by extending this enum:

```php
namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_USERS = 'view-users';
    case CREATE_USERS = 'create-users';
    case EDIT_USERS = 'edit-users';
    case DELETE_USERS = 'delete-users';
    // Add more permissions as needed
}
```

2. After adding new permissions, run:

```bash
php artisan permission:cache-reset
```

## 💻 Development Workflow

### Starting Development

1. Start Laravel development server:

```bash
php artisan serve
```

2. Start Vite for frontend development:

```bash
npm run dev
```

### Creating a New CRUD Module

1. Generate Model, Migration, and Controller:

```bash
php artisan make:model Product -mcr
```

2. Define your migration in `database/migrations/[timestamp]_create_products_table.php`:

```php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->decimal('price', 8, 2);
        $table->timestamps();
    });
}
```

3. Create a Form Request for validation:

```bash
php artisan make:request ProductRequest
```

4. Define validation rules in `app/Http/Requests/ProductRequest.php`:

```php
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
    ];
}
```

5. Implement CRUD operations in your controller:

```php
public function index()
{
    $products = Product::paginate(10);
    return view('products.index', compact('products'));
}

public function store(ProductRequest $request)
{
    Product::create($request->validated());
    return redirect()->route('products.index');
}
```

6. Create views in `resources/views/products/`:

-   `index.blade.php` - List all products
-   `create.blade.php` - Create form
-   `edit.blade.php` - Edit form
-   `show.blade.php` - Show details

7. Add routes in `routes/web.php`:

```php
Route::resource('products', ProductController::class);
```

## 📁 Directory Structure

```
app/
├── Enums/           # Permission and other enums
├── Http/
│   ├── Controllers/ # Application controllers
│   ├── Middleware/  # Custom middleware
│   └── Requests/    # Form requests
├── Models/          # Eloquent models
├── Policies/        # Authorization policies
└── Services/        # Business logic services

database/
├── migrations/      # Database migrations
└── seeders/        # Database seeders

resources/
├── css/            # Global styles
├── js/             # JavaScript files
└── views/          # Blade templates
```

## 🔧 Features

-   User Authentication
-   Role-based Access Control (RBAC)
-   Permission Management
-   User Management
-   API Authentication
-   Database Migrations
-   Seeders for Initial Data
-   Modern UI with Tailwind CSS
-   Responsive Design
-   Dark Mode Support
-   Form Validation
-   File Upload Handling
-   Email Notifications
-   Task Scheduling
-   Queue Management

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🔒 Security

If you discover any security related issues, please email sumit.redspark@gmail.com instead of using the issue tracker.

## 🔐 API Authentication Setup

### Laravel Passport Setup

1. Install Passport:

```bash
composer require laravel/passport
```

2. Run Passport migrations:

```bash
php artisan migrate
```

3. Install Passport encryption keys:

```bash
php artisan passport:install
```

4. Add the Passport service provider to `config/app.php`:

```php
'providers' => [
    // ...
    Laravel\Passport\PassportServiceProvider::class,
],
```

5. In your `User` model, add the `HasApiTokens` trait:

```php
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ...
}
```

6. In `app/Providers/AuthServiceProvider.php`, register Passport routes:

```php
use Laravel\Passport\Passport;

public function boot()
{
    $this->registerPolicies();
    Passport::routes();
}
```

7. In `config/auth.php`, set the API guard driver to passport:

```php
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

### Using Passport for API Authentication

1. Create a new client:

```bash
php artisan passport:client --password
```

2. Use the client credentials to authenticate API requests:

```bash
curl -X POST http://your-app.com/oauth/token \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{
        "grant_type": "password",
        "client_id": "your-client-id",
        "client_secret": "your-client-secret",
        "username": "user@example.com",
        "password": "password",
        "scope": ""
    }'
```

3. Include the access token in subsequent API requests:

```bash
curl -X GET http://your-app.com/api/user \
    -H "Accept: application/json" \
    -H "Authorization: Bearer your-access-token"
```

For more detailed information about Passport, refer to the [official Laravel Passport documentation](https://laravel.com/docs/passport).
