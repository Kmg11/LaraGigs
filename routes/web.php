<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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

// * Common Resources Routes:
// * index - Show all listings
// * show - Show single listing
// * create - Show form to create new listing
// * store - Store new listing
// * edit - Show form to edit listing
// * update - Update listing
// * destroy - Delete listing

// * All listings
Route::get('/', [ListingController::class, 'index']);

// * Create new listing
Route::get(
    '/listings/create',
    [ListingController::class, 'create']
)->middleware('auth');

// * Store new listing
Route::post(
    '/listings',
    [ListingController::class, 'store']
)->middleware('auth');

// * Show edit form
Route::get(
    '/listings/{listing}/edit',
    [ListingController::class, 'edit']
)->middleware('auth');

// * Update listing
Route::put(
    '/listings/{listing}',
    [ListingController::class, 'update']
)->middleware('auth');

// * Delete listing
Route::delete(
    '/listings/{listing}',
    [ListingController::class, 'destroy']
)->middleware('auth');

// * Manage listings
Route::get(
    '/listings/manage',
    [ListingController::class, 'manage']
)->middleware('auth');

// * Single listing
Route::get(
    '/listings/{listing}',
    [ListingController::class, 'show']
);

// * =========================================================

// * Show Register form
Route::get(
    '/register',
    [UserController::class, "create"]
)->middleware('guest');

// * Store new user
Route::post(
    '/users',
    [UserController::class, "store"]
)->middleware('guest');

// * Show Login form
Route::get(
    '/login',
    [UserController::class, "login"]
)->name('login')->middleware('guest');

// * Login user
Route::post(
    '/authenticate',
    [UserController::class, "authenticate"]
)->middleware('guest');

// * Logout
Route::post(
    '/logout',
    [UserController::class, "logout"]
)->middleware('auth');
