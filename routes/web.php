<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Volt::route('dishes', 'pages.dishes.index')
        ->name('dishes');

    Volt::route('dishes/create', 'pages.dishes.create')
        ->name('dishes.create');

    Volt::route('dishes/{dish}', 'pages.dishes.show')
        ->name('dishes.show');

    Volt::route('days/{day}', 'pages.days.show')->name('days.show');
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
