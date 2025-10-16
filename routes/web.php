<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/laravel-default', function () {
    return view('welcome');
})->name('laravel-default');
