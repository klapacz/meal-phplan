<div>
    @foreach ($this->dishes as $dish)
        <div wire:key="dish-{{ $dish->id }}" class="p-4 flex justify-between">
            <div wire:click="update({{ $dish->id }})">
                {{ $dish->name }}
            </div>
            <a href="{{ route('dishes.show', $dish) }}">
                Edit
            </a>
        </div>
    @endforeach
</div>
