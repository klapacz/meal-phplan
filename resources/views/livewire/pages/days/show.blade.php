<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\computed;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\on;
use function Livewire\Volt\state;

use App\Models\Day;

layout('layouts.app');

state(['day', 'editing']);

mount(function (Day $day) {
    if (!$day->user()->is(Auth::user())) {
        abort(403, 'Access denied');
    }

    $this->day = $day;
});

$tags = computed(function () {
    $dishes = $this->day->dishes()->get();

    return Auth::user()
        ->tags()
        ->get()
        ->map(function ($tag) use ($dishes) {
            $tag->dish = null;
            foreach ($dishes as $dish) {
                if ($tag->id === $dish->pivot->tag_id) {
                    $tag->dish = $dish;
                    break;
                }
            }
            return $tag;
        });
});

on(['day-dish-updated' => function ($day) {
    $this->dispatch('$refresh');
}]);

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
                <div class="max-w-xl space-y-4">
                    @foreach ($this->tags as $tag)
                        <div class="flex items-center justify-between"
                            wire:click="$dispatch('openModal', { component: 'edit-day-dish', arguments: { day: {{ $day->id }}, tag: {{ $tag->id }} } })"
                            wire:key="{{ $tag->id }}">
                            <x-tag-badge :tag="$tag" />

                            @if ($tag->dish)
                                {{ $tag->dish->name }}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
