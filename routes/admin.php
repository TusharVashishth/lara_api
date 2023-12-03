<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware("adminAuth")->group(function () {
    Route::get('/dashboard', function () {
        echo "admin";
    });
});

Route::get("/login", function () {
    echo "login page";
})->name("admin.login");