<?php

use App\Models\Dish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

use App\Models\Day;
use Carbon\Carbon;

layout('layouts.app');

state([
    'day'
]);

mount(function (Day $day) {
    if (!$day->user()->is(Auth::user())) {
        abort(403, 'Access denied');
    }

    $this->day = $day;
});

?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ucfirst($day->date->translatedFormat('l, j F Y')) }}
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            </div>
        </div>
    </div>

</div>
