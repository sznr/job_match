<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;

foreach (config('fortify.users') as $user) {
    Route::prefix($user)
        ->namespace('\Laravel\Fortify\Http\Controllers')
        ->name($user . '.')
        ->group(function () {

            $enableViews = config('fortify.views', true);

            // Authentication...
            if ($enableViews) {
                Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                    ->middleware(['guest:' . config('fortify.guard')])
                    ->name('login');
            }

            $verificationLimiter = config('fortify.limiters.verification', '6,1');

            Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware(['guest', 'throttle:' . config('fortify.limiters.login')])
                ->name('login');

            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

            // Password Reset...
            if (Features::enabled(Features::resetPasswords())) {
                if ($enableViews) {
                    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                        ->middleware(['guest:' . config('fortify.guard')])
                        ->name('password.request');

                    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                        ->middleware(['guest:' . config('fortify.guard')])
                        ->name('password.reset');
                }

                Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->middleware(['guest:' . config('fortify.guard')])
                    ->name('password.email');

                Route::post('/reset-password', [NewPasswordController::class, 'store'])
                    ->middleware(['guest:' . config('fortify.guard')])
                    ->name('password.update');
            }

            // Registration...
            if (Features::enabled(Features::registration())) {
                if ($enableViews) {
                    Route::get('/register', [RegisteredUserController::class, 'create'])
                        ->middleware(['guest:' . config('fortify.guard')])
                        ->name('register');
                }

                Route::post('/register', [RegisteredUserController::class, 'store'])
                    ->middleware(['guest:' . config('fortify.guard')])
                    ->name('register');
            }

            // Email Verification...
            if (Features::enabled(Features::emailVerification())) {
                if ($enableViews) {
                    Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
                        ->middleware(['auth:' . config('fortify.guard')])
                        ->name('verification.notice');
                }

                Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware(['auth:' . config('fortify.guard'), 'signed', 'throttle:' . $verificationLimiter])
                    ->name('verification.verify');

                Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware(['auth:' . config('fortify.guard'), 'throttle:' . $verificationLimiter])
                    ->name('verification.send');
            }

            // Profile Information...
            if (Features::enabled(Features::updateProfileInformation())) {
                Route::put('/profile-information', [ProfileInformationController::class, 'update'])
                    ->middleware(['auth:' . config('fortify.guard')])
                    ->name('user-profile-information.update');
            }

            // Passwords...
            if (Features::enabled(Features::updatePasswords())) {
                Route::put('/password', [PasswordController::class, 'update'])
                    ->middleware(['auth:' . config('fortify.guard')])
                    ->name('user-password.update');
            }

            // Password Confirmation...
            if ($enableViews) {
                Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->middleware(['auth:' . config('fortify.guard')])
                    ->name('password.confirm');
            }

            Route::get('/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
                ->middleware(['auth:' . config('fortify.guard')])
                ->name('password.confirmation');

            Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware(['auth:' . config('fortify.guard')])
                ->name('confirm-password');
        });
}
foreach (config('fortify.users') as $user) {
    Route::prefix($user)
        ->namespace('\Laravel\Jetstream\Http\Controllers\Livewire')
        ->name($user . '.')
        ->group(function () use ($user) {
            Route::get('/profile', [UserProfileController::class, 'show'])
                ->middleware(['auth:' . Str::plural($user), 'verified'])
                ->name('profile.show');
        });
}

Route::get('company/dashboard', [CompanyController::class, 'dashboard'])
    ->middleware(['auth:companies'])
    ->name('company.dashboard');

Route::get('user/dashboard', [UserController::class, 'dashboard'])
    ->middleware(['auth:users'])
    ->name('user.dashboard');