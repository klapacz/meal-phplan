<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dodaj danie
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form wire:submit="store" class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Nazwa" />

                            <x-text-input wire:model="form.name" id="name" class="block mt-1 w-full" name="name"
                                required />

                            <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="kcal" value="Kcal" />

                            <div class="flex items-center mt-1 gap-2">
                                <x-text-input wire:model="form.kcal" id="kcal" class="grow" name="kcal"
                                    type="number" />
                                w
                                <select wire:model.live="form.type"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-max">
                                    <option value="kcal">100g</option>
                                    <option value="pcs">szt</option>
                                </select>
                            </div>

                            <x-input-error :messages="$errors->get('form.kcal')" class="mt-2" />
                        </div>

                        @if ($form->type === 'kcal')
                            <div>
                                <x-input-label for="multiplier" value="Ilość gram w porcji" />

                                <x-text-input wire:model="form.multiplier" id="multiplier" class="block mt-1 w-full"
                                    name="multiplier" required />

                                <x-input-error :messages="$errors->get('form.multiplier')" class="mt-2" />
                            </div>
                        @endif


                        <div>
                            <x-input-label value="Tagi" />

                            <div x-data="{ open: false }" class="w-full relative mt-1">
                                <button @click="open = !open" type="button"
                                    class="p-3 rounded-lg flex gap-2 w-full border border-neutral-300 cursor-pointer truncate h-12 bg-white">
                                    @foreach ($this->allTags as $tag)
                                        <div x-show="$wire.form.tags.includes({{ $tag->id }})"
                                            style="display: none;"
                                            @click="$wire.form.tags = $wire.form.tags.filter(t => t !== {{ $tag->id }})">
                                            <x-tag-badge :tag="$tag" />
                                        </div>
                                    @endforeach
                                </button>
                                <div class="p-3 rounded-lg grid gap-3 w-full shadow-lg x-50 absolute bg-white mt-3 border border-gray-300"
                                    style="display: none;" x-show="open" x-trap="open" @click.outside="open = false"
                                    @keydown.escape.window="open = false">
                                    @foreach ($this->allTags as $tag)
                                        <button x-show="!$wire.form.tags.includes({{ $tag->id }})"
                                            @click="$wire.form.tags.push({{ $tag->id }})" type="button"
                                            class="flex">
                                            <x-tag-badge :tag="$tag" />
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('form.tags')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="content" value="Opis" />

                            <x-text-area wire:model="form.content" id="content" class="block mt-1 w-full"
                                name="content" />

                            <x-input-error :messages="$errors->get('form.content')" class="mt-2" />
                        </div>


                        <div class="flex justify-end">
                            <x-primary-button>
                                Dodaj
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
