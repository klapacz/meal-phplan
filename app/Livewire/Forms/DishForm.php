<?php

namespace App\Livewire\Forms;

use App\Models\Dish;
use Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DishForm extends Form
{
    public ?Dish $dish = null;

    public string $name = '';
    public string $content = '';
    public int $kcal = 0;
    public string $type = 'pcs';
    public int $multiplier = 1;
    public array $tags = [];

    public function rules()
    {
        $uniqueName = match ($this->dish) {
            null => Rule::unique(Dish::class),
            default => Rule::unique(Dish::class)->ignore($this->dish),
        };

        return [
            'name' => ['required', 'string', 'max:255', $uniqueName],
            'content' => ['string'],
            'kcal' => ['int'],
            'type' => ['in:pcs,kcal'],
            'multiplier' => ['int'],
            'tags' => [
                'array',
                'min:1',
                Rule::exists('tags', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id);
                }),
            ],
        ];
    }

    public function updatedType($value)
    {
        if ($value === 'pcs') {
            $this->multiplier = 1;
        } else {
            $this->multiplier = 100;
        }
    }

    public function setDish(Dish $dish)
    {
        $this->dish = $dish;
        $this->name = $dish->name;
        $this->content = $dish->content;
        $this->kcal = $dish->kcal;
        $this->type = $dish->type;
        $this->tags = $dish->tags->pluck('id')->toArray();

        $this->multiplier = match ($this->type) {
            'kcal' => $dish->multiplier * 100,
            'pcs' => $dish->multiplier,
        };
    }

    public function getData()
    {
        $validated = $this->validate();

        $validated['multiplier'] = match ($validated['type']) {
            'kcal' => $validated['multiplier'] / 100,
            'pcs' => $validated['multiplier'],
        };

        return $validated;
    }

    public function store()
    {
        $validated = $this->getData();

        $dish = Auth::user()->dishes()->create($validated);

        $dish->tags()->sync($this->tags);
    }

    public function update()
    {
        $validated = $this->getData();

        $this->dish->update($validated);

        $this->dish->tags()->sync($this->tags);
    }

}
