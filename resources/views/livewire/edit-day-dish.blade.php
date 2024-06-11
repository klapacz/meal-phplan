<div class="max-h-[80vh] overflow-auto">
    @foreach ($this->dishes as $dish)
        <div wire:key="dish-{{ $dish->id }}" @class([
            'p-4 flex justify-between gap-4',
            'bg-blue-200' => $dish->is_selected,
        ])>
            <div wire:click="update({{ $dish->id }})" class="truncate">
                {{ $dish->name }}
            </div>

            <div class="flex gap-2 min-w-max">
                {{ $dish->kcal }} kcal
                <a href="{{ route('dishes.show', $dish) }}">
                    Edit
                </a>
            </div>
        </div>
    @endforeach
</div>
