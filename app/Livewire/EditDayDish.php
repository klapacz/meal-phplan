<?php

namespace App\Livewire;

use App\Models\Day;
use App\Models\Dish;
use App\Models\Tag;
use Auth;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

class EditDayDish extends ModalComponent
{
    public Day $day;
    public Tag $tag;

    public function render()
    {
        return view('livewire.edit-day-dish');
    }

    #[Computed]
    public function dishes()
    {
        return Auth::user()->dishes()->whereHas('tags', fn($q) => $q->where('tag_id', $this->tag->id))->get();
    }

    public function update(Dish $dish)
    {
        $this->day
            ->dishes()
            ->wherePivot('tag_id', $this->tag->id)
            ->detach();
        $this->day->dishes()->attach($dish, ['tag_id' => $this->tag->id]);

        $this->closeModal();
        $this->dispatch('day-dish-updated', ['day' => $this->day]);
    }
}
