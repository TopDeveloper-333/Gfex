<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\CoreyController;
use App\Http\Controllers\Project\SepController;
use App\Http\Controllers\Project\PlotController;

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);
    Route::post('adduser', [UserController::class, 'addUser']);
    Route::patch('updateuser', [UserController::class, 'updateUser']);
    Route::post('removeuser', [UserController::class, 'removeUser']);
    Route::post('getUsers', [UserController::class, 'getUsers']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

    Route::post('listProjects', [ProjectController::class, 'listProjects']);
    Route::post('createProject', [ProjectController::class, 'createProject']);
    Route::post('openProject', [ProjectController::class, 'openProject']);
    Route::post('saveProject', [ProjectController::class, 'saveProject']);
    Route::post('runDryGas', [ProjectController::class, 'runDryGas']);
    Route::post('runGasCondensate', [ProjectController::class, 'runGasCondensate']);
    Route::post('runMonitoring', [ProjectController::class, 'runMonitoring']);

    Route::post('requestKGKO', [CoreyController::class, 'requestKGKO']);
    Route::post('requestCvdOut', [ProjectController::class, 'requestCvdOut']);
    Route::post('requestOPT', [SepController::class, 'requestOPT']);

    Route::post('generateLicense', [ProjectController::class, 'generateLicense']);
    Route::post('fetchLicenses', [ProjectController::class, 'fetchLicenses']);
    Route::post('deleteLicense', [ProjectController::class, 'deleteLicense']);

    Route::post('runSavePlot', [PlotController::class, 'runSavePlot']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});
