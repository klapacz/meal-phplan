<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\computed;
use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
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

$edit = function (string $tagId) {
    $this->editing = $tagId;
};

$cancel = function () {
    $this->editing = null;
};

$setDish = function(int $dishId) {
    if (is_null($this->editing)) {
        return;
    }

    $this->day->dishes()->wherePivot('tag_id', $this->editing)->detach();
    $this->day->dishes()->attach($dishId, ['tag_id' => $this->editing]);

    $this->editing = null;
};

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

$dishesForTag = computed(function () {
    if (is_null($this->editing)) {
        return collect([]);
    }

    return Auth::user()->dishes()->whereHas('tags', fn($q) => $q->where('tag_id', $this->editing))->get();
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

    @if($editing)
    <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" wire:transition>
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto" x-trap="$wire.editing" @click.outside="console.log('click')">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Wybierz danie</h3>
                                <div class="mt-2 divide-y divide-gray-200">
                                    @foreach ($this->dishesForTag as $dish)
                                        <div wire:key="dish-{{$dish->id}}" wire:click="setDish({{$dish->id}})" class="p-4">
                                            {{ $dish->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" wire:click="cancel">Anuluj</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl space-y-4">
                    @foreach ($this->tags as $tag)
                        <div class="flex items-center justify-between" wire:click="edit('{{ $tag->id }}')"
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
