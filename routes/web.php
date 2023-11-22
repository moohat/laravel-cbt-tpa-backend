<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('pages.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('home', function () {
        return view('pages.dashboard');
    })->name('home');
    Route::resource('users', UserController::class);

    // Route::get('/users', function () {
    //     return view('pages.users.index');
    // })->name('users');
});

// Route::get('/login', function () {
//     return view('pages.auth.login');
// })->name('login');

// //register
// Route::get('/register', function () {
//     return view('pages.auth.register');
// })->name('register');

// //users
// Route::get('/users', function () {
//     return view('pages.users.index');
// });
