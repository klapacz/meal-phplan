<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('layouts.app');

$getDishes = fn() => ($this->dishes = Auth::user()->dishes()->with("tags")->get());

state(['dishes' => $getDishes]);
?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dania
            </h2>

            <x-primary-button :href="route('dishes.create')" type="link">Dodaj</x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nazwa
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tagi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dishes as $dish)
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <a href="{{ route('dishes.show', $dish) }}">
                                            {{ $dish->name }}
                                        </a>
                                    </th>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            @foreach ($dish->tags as $tag)
                                                <x-tag-badge :tag="$tag" />
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
