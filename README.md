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

