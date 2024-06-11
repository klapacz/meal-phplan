<div class="max-h-[80vh] overflow-auto">
    @foreach ($this->dishes as $dish)
        <div wire:key="dish-{{ $dish->id }}" @class([
            'p-4 flex justify-between items-center gap-4',
            'bg-blue-200' => $dish->is_selected,
        ])>
            <button wire:click="update({{ $dish->id }})" class="truncate flex-grow text-left">
                {{ $dish->name }}
            </button>

            <div class="flex gap-2 min-w-max items-center">
                {{ $dish->kcal }} kcal
                <a href="{{ route('dishes.show', $dish) }}" class="p-2 bg-blue-100 rounded-sm">
                    <x-lucide-edit class="w-4 h-4" />
                </a>
            </div>
        </div>
    @endforeach

    <button class="p-4 flex w-full justify-center items-center bg-red-600 text-white" wire:click="delete">
        Usuń posiłek
    </button>
</div>
