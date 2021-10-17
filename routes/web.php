<?php

use App\Http\Controllers\{
    UsersController,
    EntriesController,
    AuthController,
    BackupController,
    HomeController,
    SettingsController
};
use Illuminate\Support\Facades\Route;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegisteredUserController;


// ABORTED ROUTES
Route::get('/forgot-password', function () { abort(404); });
Route::get('/reset-password', function () { abort(404); });



Route::get('/locale/{locale}', [HomeController::class, 'setLocale'])->name('set_locale');

Route::get('/', function () { return redirect(app()->getLocale()); });

Route::group([
    'prefix' => '{locale}', 
    'where' => ['locale' => '[a-zA-Z]{2}'], 
    'middleware' => 'setlocale'], function(){

    $limiter = config('fortify.limiters.login');

    // Home Route
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register/disabled', [AuthController::class, 'registerDisabled'])->name('register_disabled');


    Route::post('/register', [FortifyRegisteredUserController::class, 'store'])
        ->middleware(['guest:'.config('fortify.guard')]);

    Route::get('/login', [FortifyAuthenticatedSessionController::class, 'create'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('login');

    Route::post('/login', [FortifyAuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ]));

    Route::get('/entries', [EntriesController::class, 'index'])->name('entries');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/create', [UsersController::class, 'createUser'])->name('users_create');
    Route::get('/users/{user_id}/edit', [UsersController::class, 'editUser'])->name('user_edit');




    Route::post('testing', [EntriesController::class, 'testing'])->name('testing');
});



Route::middleware(['auth:sanctum', 'verified', 'redirect_if_normal_user'])->group(function() {

    // Check and redirect if not admin
    Route::middleware(['check.admin'])->group(function() {
        Route::get('/user-fields', [UsersController::class, 'userFields'])->name('user_fields');
    
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        // Backup
        Route::get('/backup', [BackupController::class, 'backup'])->name('backup');
        Route::post('/restore', [BackupController::class, 'restore'])->name('restore');
    });

    // Data Export
    Route::name('export.')->group(function () {
        Route::get('/entries/export', [EntriesController::class, 'exportCsv'])->name('entries');
    });

    // Data Import
    Route::name('import.')->group(function () {
        Route::get('/entries/import', [EntriesController::class, 'importCsv'])->name('entries');
    });
});































// // Fortify Routes


// use Laravel\Fortify\Features;
// use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
// use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
// use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
// use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
// use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
// use Laravel\Fortify\Http\Controllers\NewPasswordController;
// use Laravel\Fortify\Http\Controllers\PasswordController;
// use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
// use Laravel\Fortify\Http\Controllers\ProfileInformationController;
// use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
// use Laravel\Fortify\Http\Controllers\RegisteredUserController;
// use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
// use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
// use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
// use Laravel\Fortify\Http\Controllers\VerifyEmailController;

// Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
//     $enableViews = config('fortify.views', true);

//     // Authentication...
//     if ($enableViews) {
        
//     }

//     $limiter = config('fortify.limiters.login');
//     $twoFactorLimiter = config('fortify.limiters.two-factor');

//     Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//         ->middleware(array_filter([
//             'guest:'.config('fortify.guard'),
//             $limiter ? 'throttle:'.$limiter : null,
//         ]));

//     Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');

//     // Password Reset...
//     if (Features::enabled(Features::resetPasswords())) {
//         if ($enableViews) {
//             Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
//                 ->middleware(['guest:'.config('fortify.guard')])
//                 ->name('password.request');

//             Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
//                 ->middleware(['guest:'.config('fortify.guard')])
//                 ->name('password.reset');
//         }

//         Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//             ->middleware(['guest:'.config('fortify.guard')])
//             ->name('password.email');

//         Route::post('/reset-password', [NewPasswordController::class, 'store'])
//             ->middleware(['guest:'.config('fortify.guard')])
//             ->name('password.update');
//     }

//     // Registration...
//     if (Features::enabled(Features::registration())) {
//         if ($enableViews) {
//             Route::get('/register', [RegisteredUserController::class, 'create'])
//                 ->middleware(['guest:'.config('fortify.guard')])
//                 ->name('register');
//         }
        
//     }

//     // Email Verification...
//     if (Features::enabled(Features::emailVerification())) {
//         if ($enableViews) {
//             Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
//                 ->middleware(['auth:'.config('fortify.guard')])
//                 ->name('verification.notice');
//         }

//         Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//             ->middleware(['auth:'.config('fortify.guard'), 'signed', 'throttle:6,1'])
//             ->name('verification.verify');

//         Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//             ->middleware(['auth:'.config('fortify.guard'), 'throttle:6,1'])
//             ->name('verification.send');
//     }

//     // Profile Information...
//     if (Features::enabled(Features::updateProfileInformation())) {
//         Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
//             ->middleware(['auth:'.config('fortify.guard')])
//             ->name('user-profile-information.update');
//     }

//     // Passwords...
//     if (Features::enabled(Features::updatePasswords())) {
//         Route::put('/user/password', [PasswordController::class, 'update'])
//             ->middleware(['auth:'.config('fortify.guard')])
//             ->name('user-password.update');
//     }

//     // Password Confirmation...
//     if ($enableViews) {
//         Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
//             ->middleware(['auth:'.config('fortify.guard')])
//             ->name('password.confirm');
//     }

//     Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
//         ->middleware(['auth:'.config('fortify.guard')])
//         ->name('password.confirmation');

//     Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
//         ->middleware(['auth:'.config('fortify.guard')]);

//     // Two Factor Authentication...
//     if (Features::enabled(Features::twoFactorAuthentication())) {
//         if ($enableViews) {
//             Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
//                 ->middleware(['guest:'.config('fortify.guard')])
//                 ->name('two-factor.login');
//         }

//         Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
//             ->middleware(array_filter([
//                 'guest:'.config('fortify.guard'),
//                 $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
//             ]));

//         $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
//             ? ['auth:'.config('fortify.guard'), 'password.confirm']
//             : ['auth:'.config('fortify.guard')];

//         Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
//             ->middleware($twoFactorMiddleware)
//             ->name('two-factor.enable');

//         Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
//             ->middleware($twoFactorMiddleware)
//             ->name('two-factor.disable');

//         Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
//             ->middleware($twoFactorMiddleware)
//             ->name('two-factor.qr-code');

//         Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
//             ->middleware($twoFactorMiddleware)
//             ->name('two-factor.recovery-codes');

//         Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
//             ->middleware($twoFactorMiddleware);
//     }
// });

