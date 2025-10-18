<?php

use Illuminate\Support\Facades\Route;

//Home
Route::get('/', function () {
    return view('index');
})->name('home');

//Authentication
Route::get('/login', function () {
    return view('pages/auth/login');
})->name('login');
Route::get('/register', function () {
    return view('pages/auth/register');
})->name('register');
Route::get('/forgot-password', function () {
    return view('pages/auth/forgot-password');
})->name('forgot-password');


Route::get('/laravel-default', function () {
    return view('welcome');
})->name('laravel-default');
