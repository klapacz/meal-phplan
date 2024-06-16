<?php

use App\Livewire\CreateDish;
use App\Livewire\EditDish;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/dashboard', 301);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Volt::route('dishes', 'pages.dishes.index')
        ->name('dishes');

    Route::get('dishes/create', CreateDish::class)
        ->name('dishes.create');

    Route::get('dishes/{dish}', EditDish::class)
        ->name('dishes.show');

    Volt::route('days/{day}', 'pages.days.show')->name('days.show');
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
