<?php

use App\Models\Dish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;

layout('layouts.app');

state(['dish', 'name' => '', 'content' => '', 'tags' => [], 'allTags' => []]);

mount(function (Dish $dish) {
    if (!$dish->user()->is(Auth::user())) {
        abort(403, 'Access denied');
    }

    $this->dish = $dish;
    $this->name = $dish->name;
    $this->content = $dish->content;
    $this->tags = $dish->tags->pluck('id');

    $this->allTags = Auth::user()->tags()->get();
});

$addDish = function () {
    $validated = $this->validate([
        'name' => ['required', 'string', 'max:255', Rule::unique(Dish::class)->ignore($this->dish->id)],
        'content' => ['string'],
        'tags' => [
            'array',
            'min:1',
            Rule::exists('tags', 'id')->where(function ($query) {
                $query->where('user_id', Auth::user()->id);
            }),
        ],
    ]);

    $this->dish->update($validated);

    $this->dish->tags()->sync($this->tags);

    $this->redirect(route('dishes'), navigate: true);
};


$deleteDish = function () {
    $this->dish->delete();
}

?>

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edytuj danie
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form wire:submit="addDish" class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Nazwa" />

                            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" name="name"
                                required />

                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label value="Tagi" />

                            <div x-data="{ open: false }" class="w-full relative mt-1">
                                <button @click="open = !open" type="button"
                                    class="p-3 rounded-lg flex gap-2 w-full border border-neutral-300 cursor-pointer truncate h-12 bg-white">
                                    @foreach ($allTags as $tag)
                                        <div x-show="$wire.tags.includes({{ $tag->id }})"
                                            @click="$wire.tags = $wire.tags.filter(t => t !== {{ $tag->id }})">
                                            <x-tag-badge :tag="$tag" />
                                        </div>
                                    @endforeach
                                </button>
                                <div class="p-3 rounded-lg grid gap-3 w-full shadow-lg x-50 absolute bg-white mt-3 border border-gray-300"
                                    x-show="open" x-trap="open" @click.outside="open = false"
                                    @keydown.escape.window="open = false">
                                    @foreach ($allTags as $tag)
                                        <button x-show="!$wire.tags.includes({{ $tag->id }})"
                                            @click="$wire.tags.push({{ $tag->id }})" type="button" class="flex">
                                            <x-tag-badge :tag="$tag" />
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="content" value="Opis" />

                            <x-text-area wire:model="content" id="content" class="block mt-1 w-full" name="content" />

                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>


                        <div class="flex justify-between">
                            <x-danger-button wire:click="deleteDish">
                                Usuń
                            </x-danger-button>
                            <x-primary-button>
                                Edytuj
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
