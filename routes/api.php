<?php

use App\Http\Controllers\Api\{
    AuthColtroller,
    EntriesController,
    UsersController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth
Route::post('/login', [AuthColtroller::class, 'login']);
Route::get('/register', [AuthColtroller::class, 'registrationResources']);
Route::get('/general-specializations/{general_id}/specializations', [AuthColtroller::class, 'specializations']);
Route::post('/register', [AuthColtroller::class, 'register']);

// Locations
Route::get('/countries/{country}/states', [AuthColtroller::class, 'states']);
Route::get('countries/{country}/states/{state}/cities', [AuthColtroller::class, 'cities']);

Route::get('/entries', [EntriesController::class, 'index']);
Route::get('/entries/search/{keyword?}', [EntriesController::class, 'search']);

// Authenticated
Route::middleware(['auth.api'])->group(function () {
    Route::post('/users/{user_id}/update', [UsersController::class, 'updateUser']);

    Route::get('/profile', [AuthColtroller::class, 'profile']);
    Route::post('/profile', [AuthColtroller::class, 'updateProfile']);
    Route::post('/profile/password', [AuthColtroller::class, 'updatePassword']);
});