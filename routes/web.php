<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




    # Disabled route register (custom register user), verify and reset password
    Auth::routes([
        'register' => false,
        'verify' => true,
        'reset' => false
    ]);

    Route::group(['middleware' => 'auth'], function () {

        # Welcome route
        Route::get('/', function () {
            return view('welcome');
        });

        # Home route
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        # Sites routes
        Route::group(['prefix' => 'sites'], function () {

            Route::get('index', [App\Http\Controllers\SiteController::class, 'index'])->name('sites-index');

            Route::get('scraping', [App\Http\Controllers\SiteController::class, 'scraping'])->name('sites-scraping');

        });

        # Emails routes
        Route::group(['prefix' => 'emails'], function () {

            Route::get('index', [App\Http\Controllers\EmailController::class, 'index'])->name('emails-index');

        });

        # Customers routes
        Route::group(['prefix' => 'customers'], function () {

            Route::get('index', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers-index');

        });

    });
