# Class Assignment - Input Validation and Profile Page

## ✅ Enhanced Files

### 1. **RegisterController.php and LoginController.php**
- After registering, the user is automatically logged in and redirected to `/todo`.

### 2. **RegisterRequest.php and LoginRequest.php**
- Add regex-based validation
  Whitelist applied :
- Username: letters and spaces only.
- Email: must be unique with valid email format.
- Password: minimum 8 characters and confirmation required.

### 3. **ProfileController.php**
- hash features for password
Include functions for profile update :
- `edit()`: Load form with current user data.
- `update()`: Save updated profile data.
- `destroy()`: Delete account.
 
### 4. **edit.blade.php**
- `resources/views/profile/edit.blade.php`
- User able to edit profile: nickname, email, phone, city, password, and avatar.
- Avatar that uploaded will display next to nickname.
- Message are displayed after update profile and delete account for confirmation.

### 5. **Migrations**
- create_todos_table.php
- add_profile_fields_to_users_table.php

### Here is my view
- Todo
![image](https://github.com/user-attachments/assets/46cb9f7a-b031-4e9f-880f-38edd53a753b)

- My Profile 
![image](https://github.com/user-attachments/assets/39cdb8d1-d851-41f6-804a-102472ba0559)

# Class Assignment - Aunthentication

## 1. Multi-Factor Authentication (MFA) via Email (Laravel Fortify)
### Steps : 
1. Laravel Fortify is installed using composer require laravel/fortify.
2. The FortifyServiceProvider is registered in config/app.php.
3. Fortify features are enabled via config/fortify.php.
    - Enable email verification and two-factor authentication in config/fortify.php:
    'features' => [
    Features::twoFactorAuthentication([
        'confirmPassword' => true,])
5. When the new user register, user is redirected to /mfa(mfa.blade.php). Then,a 6-digit code is sent to their email.
7. The user must enter the code to complete authentication.

-Verify Page
![image](https://github.com/user-attachments/assets/afd4f0be-d074-4202-a2c7-81ea8449a9bd)

-After submit code
![image](https://github.com/user-attachments/assets/29ce65bd-24c6-43e4-8ea2-9d9bb584bd63)
## 2. Password hashed using Bcrypt or Argon2
1. In env. file, include # HASH_DRIVER=bcyrpt
2. Update `config/hashing.php`
   'bcrypt' => [
 'rounds' => 10, 
 'verify' => true, // Determines if password entered is being verified on entry
],
   
-Bcrypt password
![image](https://github.com/user-attachments/assets/6e148994-756b-4458-a74f-631d802e0806)

## 3. Implement RateLimit only 3 failed attempts
1. Add rate limit features in login.blade
   use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(3)->by($request->ip()); //3 implement in 4 login attempts, user get error “429 — Too Many Requests”
});

## 4. Add salt
- Add salt to register
![image](https://github.com/user-attachments/assets/c7338384-d39a-41b9-b94c-2af6a87e49c6)

# Class Assignment - Laravel To-Do App with Authentication & Role-Based Access Control (RBAC)

This project enhances the Laravel To-Do application by adding a secure **authentication system** and a **Role-Based Access Control (RBAC)** mechanism to differentiate between user and administrator privileges.

## ✅ Features Implemented

### 1. Authentication Layer
- Users must log in before accessing the server
- After successful login:
- Regular users are redirected to `/todo`.
- Admins are redirected to `/admin`.

### 2. Role-Based Access Control (RBAC)
RBAC is implemented using:
- `user_roles` table – assigns roles to users.
- `role_permissions` table – defines what each role can do (CRUD).

### 3. Database Migrations
#### 1) `create_user_roles_table.php` - This table links each user_id to a role_name to ensure each user has one specific role
Defines the `user_roles` table:
Schema::create('user_roles', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->unique();
    $table->string('role_name'); // 'admin' or 'user'
    $table->string('description')->nullable();
    $table->timestamps();
});

#### 2) `create_role_permissions_table.php` - store CRUD permissions for each role
Defines the `role_permissions` table:
Schema::create('role_permissions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('role_id');
    $table->string('description'); // 'create', 'read', 'update', 'delete'
    $table->timestamps();
});

### 4. Models
#### 1) UserRole.php - define a relationship with the users table
class UserRole extends Model {
    protected $fillable = ['user_id', 'role_name', 'description'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}

#### 2) RolePermission.php - links each permission to a specific role
class RolePermission extends Model {
    protected $fillable = ['role_id', 'description'];
    public function role() {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
}

### 5. Middleware
#### RoleMiddleware.php - protects routes so only users with the correct role can access them. 
public function handle($request, Closure $next, $role){
    $userRole = UserRole::where('user_id', auth()->id())->value('role_name');
    if ($userRole !== $role) {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}

### 6. Routes
#### web.php - use middleware to redirect users and admins to their appropriate sections
// Routes for Regular Users
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('/todo', TodoController::class);
});

// Routes for Admins
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'userList']);
    Route::post('/admin/users/{id}/toggle', [AdminController::class, 'toggleActivation']);
    Route::get('/admin/users/{id}/todos', [AdminController::class, 'viewUserTodos']);
});
