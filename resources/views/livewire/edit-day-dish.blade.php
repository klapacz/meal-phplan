<div>
    @foreach ($this->dishes as $dish)
        <div wire:key="dish-{{ $dish->id }}" wire:click="update({{ $dish->id }})" class="p-4">
            {{ $dish->name }}
        </div>
    @endforeach
</div>
