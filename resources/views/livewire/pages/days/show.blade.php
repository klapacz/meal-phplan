<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\computed;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\on;
use function Livewire\Volt\state;

use App\Models\Day;

layout('layouts.app');

state(['day']);

mount(function (Day $day) {
    if (!$day->user()->is(Auth::user())) {
        abort(403, 'Access denied');
    }

    $this->day = $day;
});

$dishes = computed(function () {
    return $this->day->dishes()->get();
});

$kcal_sum = computed(function () {
    return $this->dishes->sum('kcal');
});

$tags = computed(function () {
    return Auth::user()
        ->tags()
        ->get()
        ->map(function ($tag) {
            $tag->dish = null;
            foreach ($this->dishes as $dish) {
                if ($tag->id === $dish->pivot->tag_id) {
                    $tag->dish = $dish;
                    break;
                }
            }
            return $tag;
        });
});

on([
    'day-dish-updated' => function ($day) {
        unset($this->tags);
    },
]);

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
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-4">
                <div class="max-w-xl divide-y divide-gray-200">
                    @foreach ($this->tags as $tag)
                        <div wire:key="{{ $tag->id }}"
                            wire:click="$dispatch('openModal', { component: 'edit-day-dish', arguments: { day: {{ $day->id }}, tag: {{ $tag->id }} } })"
                            @class(['space-y-2 py-4', 'opacity-50' => !$tag->dish])>

                            <div class="flex justify-between gap-2">
                                <x-tag-badge :tag="$tag" />

                                <button class="p-2 bg-gray-100 rounded">
                                    <x-lucide-edit class="w-4 h-4" />
                                    <span class="sr-only">Edytuj</span>
                                </button>
                            </div>

                            @if ($tag->dish)
                                <div class="flex justify-between">
                                    <span>
                                        {{ $tag->dish->name }}
                                    </span>
                                    <div class="min-w-max">
                                        {{ $tag->dish->kcal }} kcal
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-500">
                                    Brak dania
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="flex justify-end py-4 font-semibold">
                        <span>Suma {{ $this->kcal_sum }} kcal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
