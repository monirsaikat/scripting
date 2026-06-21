# PHP Scripting Framework

A lightweight, modern PHP framework with built-in CLI tools for database migrations, similar to Laravel's Artisan.

## Features

- 🎯 Simple yet powerful routing system
- 🔐 Built-in authentication & CSRF protection
- 🗄️ ORM with Model support
- 📝 Database migrations (Artisan-style)
- 🎨 Template engine (Plates)
- 📦 Dependency injection container (Pimple)
- 🛠️ Console commands framework
- 📊 Request logging & timing utilities

## Project Structure

```
scripting/
├── bin/
│   └── artisan              # CLI entry point
├── config/
│   └── db.php              # Database configuration
├── database/
│   └── migrations/         # Migration files
├── src/
│   ├── Application.php     # Main app class
│   ├── Console/            # CLI framework
│   │   ├── Command.php
│   │   ├── Kernel.php
│   │   └── Commands/       # Built-in commands
│   ├── Controllers/        # HTTP controllers
│   ├── Models/             # Database models
│   ├── Providers/          # Service providers
│   ├── Middleware/         # HTTP middleware
│   ├── Exceptions/         # Exception handling
│   ├── Helpers/            # Helper functions
│   └── Util/               # Utility classes
├── views/                  # Template files
├── public/                 # Web root
└── vendor/                 # Dependencies
```

## Installation

1. **Clone/setup the project**
   ```bash
   cd scripting
   composer install
   ```

2. **Configure database** in `config/db.php`:
   ```php
   return [
       'schema'   => 'mysql',
       'host'     => 'localhost',
       'dbname'   => 'scripting',
       'username' => 'admin',
       'password' => 'admin1'
   ];
   ```

3. **Run migrations**
   ```bash
   php bin/artisan migrate
   ```

## Console Commands (Artisan)

The framework provides a command-line interface similar to Laravel's Artisan. All commands start with `php bin/artisan`.

### Available Commands

#### `list`
Display all available commands:
```bash
php bin/artisan list
```

#### `make:migration <name>`
Create a new migration file:
```bash
php bin/artisan make:migration create_users_table
php bin/artisan make:migration add_email_to_users
```

Creates a file in `database/migrations/` with timestamp and class:
```php
<?php

class CreateUsersTable
{
    public function up()
    {
        // Write your migration code here
        // $pdo->exec("CREATE TABLE users...");
    }

    public function down()
    {
        // Write rollback code here
        // $pdo->exec("DROP TABLE users");
    }
}
```

#### `migrate`
Run all pending migrations:
```bash
php bin/artisan migrate
```

- Checks for migrations not yet executed
- Creates `migrations` table automatically
- Tracks which migrations have run
- Reports success/failure for each migration

#### `migrate:rollback`
Rollback the last batch of migrations:
```bash
php bin/artisan migrate:rollback
```

- Runs the `down()` method of the last batch
- Removes migration records from the database
- Useful for undoing recent changes

#### `migrate:refresh`
Reset and re-run all migrations (full reset):
```bash
php bin/artisan migrate:refresh
```

- **Warning**: This clears your database!
- Rolls back all migrations in reverse order
- Re-runs all migrations from scratch
- Useful for development/testing

## Writing Migrations

Migrations are PHP files that modify your database schema. Each migration has two methods:

### Migration File Example

```php
<?php

class CreateUsersTable
{
    public function up()
    {
        // Get the PDO connection
        $pdo = (new \Src\Database())->getConnection();
        
        $pdo->exec("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down()
    {
        $pdo = (new \Src\Database())->getConnection();
        $pdo->exec("DROP TABLE users");
    }
}
```

### Migration Best Practices

1. **One logical change per migration**
   - Create tables in separate migrations from adding columns
   
2. **Always provide a `down()` method**
   - Make migrations reversible for development
   
3. **Use descriptive names**
   - `create_users_table` ✅
   - `add_email_to_users` ✅
   - `fix_column` ❌
   
4. **Test rollbacks**
   - Run `migrate`, then `migrate:rollback` to verify

## Extending the Framework

### Creating Custom Commands

Create a new command by extending `Src\Console\Command`:

```php
<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MyCustomCommand extends Command
{
    protected string $name = 'my:command';
    protected string $description = 'My custom command description';

    public function handle(array $args): int
    {
        $this->info('Command executing...');
        
        // Do something
        
        $this->success('Command completed!');
        return 0; // 0 = success, 1 = failure
    }
}
```

Then register it in `src/Console/Kernel.php`:

```php
protected function registerCommands(): void
{
    // ... existing commands
    $this->register(new MyCustomCommand());
}
```

### Command Output Methods

- `$this->info('message')` - Information message
- `$this->success('message')` - Success message (green)
- `$this->error('message')` - Error message (red)
- `$this->warn('message')` - Warning message (yellow)
- `$this->line('message')` - Plain text
- `$this->table($headers, $rows)` - Format tabular data

### Database Migrations Table

Migrations are tracked in the `migrations` table:

```
id | migration | batch | executed_at
---|-----------|-------|-------------
1  | 2024_01_15_100000_create_users_table.php | 1 | 2024-01-15 10:00:00
2  | 2024_01_15_100100_add_email_to_users.php | 1 | 2024-01-15 10:01:00
```

## Database Configuration

Edit `config/db.php`:

```php
return [
    'schema'   => 'mysql',      // 'mysql' or 'couchdb'
    'host'     => 'localhost',
    'dbname'   => 'scripting',
    'username' => 'admin',
    'password' => 'admin1'
];
```

Get a PDO connection:

```php
$db = new \Src\Database();
$pdo = $db->getConnection();
$pdo->exec("SELECT * FROM users");
```

## Models

Define models in `src/Models/`:

```php
<?php

namespace Src\Models;

class User extends Model
{
    protected string $table = 'users';

    public function isAdmin(): bool
    {
        return $this->is_admin ?? false;
    }
}
```

## Controllers

Define controllers in `src/Controllers/`:

```php
<?php

namespace Src\Controllers;

class UserController extends Controller
{
    public function index()
    {
        $users = \Src\Models\User::all();
        return view('users.index', ['users' => $users]);
    }
}
```

## Authentication Guards

Authentication is guard-aware, so client apps can keep separate sessions for users,
admins, vendors, staff, or any other auth area. The default guard is `user`, so
old code using `#[Auth]`, `#[Guest]`, `auth()`, and `user()` keeps working.

### Guard API

```php
// Log in a normal user on the default "user" guard
auth()->login($user);

// Log in an admin on a separate "admin" guard
auth('admin')->login($admin);

// Read the authenticated account
$user = user(); // same as auth('user')->user()
$admin = user('admin'); // same as auth('admin')->user()

// Check and logout
auth('admin')->check();
auth('admin')->logout();
\Src\Auth::logoutAll();
```

The object passed to `login()` must have an `id` property because the guard stores
that ID in the session and caches the full object.

### Route Attributes

Route attributes accept a guard name and optional redirect path:

```php
use Src\Attributes\Auth;
use Src\Attributes\Guest;
use Src\Attributes\Route;

#[Auth('admin', '/admin/login')]
#[Route('GET', '/admin/dashboard')]
public function dashboard()
{
    $admin = user('admin');
}

#[Guest('admin', '/admin/dashboard')]
#[Route('GET', '/admin/login')]
public function adminLogin()
{
    // ...
}
```

### Example Admin Login

```php
#[Guest('admin', '/admin/dashboard')]
#[Route('POST', '/admin/login')]
public function postAdminLogin()
{
    $email = $this->post('email');
    $admin = $this->db()->from('admins')->where('email', '=', $email)->first();

    if (!$admin) {
        flash('error', 'Invalid credentials');
        redirect('/admin/login');
    }

    auth('admin')->login($admin);

    flash('success', 'Admin logged in');
    redirect('/admin/dashboard');
}

#[Auth('admin', '/admin/login')]
#[Route('GET', '/admin/logout')]
public function adminLogout()
{
    auth('admin')->logout();
    redirect('/admin/login');
}
```

## Admin Panel

The framework includes a guarded admin panel using the `admin` auth guard.

- `GET /admin/login` - admin login form
- `POST /admin/login` - admin login submit
- `GET /admin` - admin dashboard, protected by `#[Auth('admin', '/admin/login')]`
- `GET /admin/logout` - logout only the `admin` guard
- `GET /staffs` - user/staff management, protected by the `admin` guard

Run migrations to create the `admins` table and seed the default admin:

```bash
php bin/artisan migrate
```

Default development credentials:

```text
Email: admin@example.com
Password: admin123
```

## Unpoly Frontend Layer

Unpoly is integrated as the framework's first-class progressive enhancement layer.
Views are still plain server-rendered PHP, but navigation and forms feel like a
single-page app without a build step.

### What Is Built In

- `layouts/main.php` and `layouts/admin.php` load local Unpoly files from `public/vendor/unpoly`.
- Both layouts expose `<div id="page" up-main>` as the default swappable shell.
- Page content still lives in `<main id="app">` for smaller manual targets.
- Same-origin links are followed by Unpoly automatically.
- Forms are submitted by Unpoly automatically.
- Flash messages live inside `#page`, so redirects and validation responses update them.
- Failed form submissions should re-render with HTTP `422`.
- Redirects still use normal `Location` headers and also send `X-Up-Location` for Unpoly.

### Helper API

```php
up()->isRequest();              // true for Unpoly fragment requests
up()->target();                 // X-Up-Target request header
up()->failTarget();             // X-Up-Fail-Target request header
up()->mode();                   // root, modal, drawer, popup, ...
up()->isLayer();                // true when rendering inside an overlay
up()->setTarget('#page');       // send X-Up-Target response header
up()->setTitle('Dashboard');    // send X-Up-Title response header
up()->acceptLayer(['id' => 1]); // close/accept current overlay
up()->dismissLayer();           // dismiss current overlay
up()->emit('user:saved');       // emit a browser event after rendering

up_attrs(['up-target' => '#page']);
up_link_attrs();                // up-follow + up-target="#page"
up_form_attrs();                // up-submit + success/fail target "#page"
up_modal_attrs();               // up-layer="new" + target "#app"
```

### View Conventions

Use the default app fragment for full-page interactions:

```php
<a href="<?= url('/staffs') ?>" <?= up_link_attrs() ?>>Users</a>

<form method="POST" action="<?= url('/contact') ?>" <?= up_form_attrs() ?>>
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    ...
</form>
```

Open any server-rendered route in a modal:

```php
<a href="<?= url('/users/create') ?>" <?= up_modal_attrs() ?>>Create user</a>
```

Opt out when a link or form must do a full browser load:

```html
<a href="/download" data-no-up>Download</a>
<form method="POST" action="/export" data-no-up>...</form>
```

### Controller Conventions

Successful POST handlers may keep using `redirect()`:

```php
flash('success', 'Saved');
redirect('/staffs');
```

Validation failures should re-render the same view with `422`:

```php
if ($errors) {
    flash('error', $this->renderErrors());

    return $this->renderUnprocessable('contact', [
        'pageTitle' => 'Contact',
    ]);
}
```

The `renderUnprocessable()` helper makes Unpoly treat the response as a failed
submission and replace the form's `up-fail-target`.

### Client JavaScript Structure

Client-side code uses native ES modules and needs no build step. Keep
`public/js/unpoly-app.js` as the entrypoint and place feature code under
`public/js/app/`.

```text
public/js/
├── unpoly-app.js          # imports and initializes modules
└── app/
    ├── dom.js             # DOM helpers and delegated events
    ├── bootstrap-modals.js # Bootstrap modal wrapper
    ├── unpoly-config.js   # global Unpoly selectors/events
    ├── flashes.js         # flash auto-dismiss behavior
    └── staff.js           # users/staff page interactions
```

New page behavior should use delegated events or Unpoly compilers so it keeps
working after `#page` is swapped. Avoid inline page scripts for behavior that
must survive SPA navigation.

## Routing

Routes are defined using attributes in controllers:

```php
<?php

namespace Src\Controllers;

use Src\Attributes\Route;

class UserController extends Controller
{
    #[Route('/users', 'GET')]
    public function index()
    {
        // ...
    }

    #[Route('/users/{id}', 'GET')]
    public function show($id)
    {
        // ...
    }
}
```

## Middleware

Middleware processes requests before they reach controllers. Built-in middleware:

- `CsrfMiddleware` - CSRF token validation
- Custom middleware in `src/Middleware/`

## Views & Templates

Templates use Plates engine, stored in `views/`:

```php
<!-- views/users/index.php -->
<?php $this->layout('layouts/app') ?>

<h1>Users</h1>

<?php foreach ($users as $user): ?>
    <p><?= $user->name ?></p>
<?php endforeach; ?>
```

## Exceptions & Error Handling

Exception handling is configured in `src/Exceptions/ErrorHandler.php`. Custom exceptions:

```php
<?php

namespace Src\Exceptions;

class AppException extends Exception
{
    // Custom app exception
}
```

## Logging

Uses Monolog for logging:

```php
use Monolog\Logger;
use Monolog\Handlers\StreamHandler;

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler('logs/app.log'));
$logger->info('User login', ['user_id' => 123]);
```

## Testing Migrations

Quick test workflow:

```bash
# Create migration
php bin/artisan make:migration create_products_table

# Edit database/migrations/TIMESTAMP_create_products_table.php

# Test running the migration
php bin/artisan migrate

# Test rollback
php bin/artisan migrate:rollback

# Test refresh
php bin/artisan migrate:refresh

# Final run
php bin/artisan migrate
```

## Troubleshooting

### "Command not found"
```bash
# Make sure you're in the project root
cd /path/to/scripting

# Try running help
php bin/artisan help
```

### "No pending migrations"
- Check if migrations have already been run
- Check if migration files exist in `database/migrations/`

### Database connection errors
- Verify `config/db.php` has correct credentials
- Ensure database exists: `CREATE DATABASE scripting`
- Check MySQL is running

### Migration failed
- Check the error message for SQL syntax errors
- Review the migration file logic
- Verify the `down()` method for rollback

## Development Tips

1. **Always create reversible migrations** - write both `up()` and `down()`
2. **Test migrations** - run migrate, then rollback, then migrate again
3. **Use migrations for schema only** - don't insert test data in migrations
4. **Keep migrations small** - easier to debug if something fails
5. **Use descriptive naming** - future you will appreciate it

## License

This project is open source and available under the MIT License.

## Support

For issues or questions, check the code in `src/` or review the example migrations in `database/migrations/`.
