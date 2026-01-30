<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

//name this as Login since Laravel will default to login route if a user tries to access protected routes if the user isnt login
Route::group(['middleware' => ["guest", "throttle:100,1"]], function(){
  Route::get('/', [LoginController::class, "getLogin"]) -> name("login");
  Route::post('/login', [LoginController::class, "loginUser"]) -> name("loginUser");
  
  //forgot password link
  Route::get('/forgot-password', [ForgotPasswordController::class, "getForgotPage"]) -> name("password.request");
  Route::post('/forgot-password', [ForgotPasswordController::class, "sendResetLink"]) -> name("password.email");

  //reset password
  Route::get('/reset-password/{token}', [ResetPasswordController::class, "getResetPage"]) -> name("password.reset");
  Route::post('/reset-password', [ResetPasswordController::class, "resetPassword"]) -> name("password.update");
});