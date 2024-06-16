<?php

namespace App\Livewire;

use App\Livewire\Forms\DishForm;
use App\Models\Dish;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditDish extends Component
{
    public Dish $dish;
    public DishForm $form;

    public function mount(Dish $dish)
    {
        $this->dish = $dish;
        $this->form->setDish($dish);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.edit-dish');
    }

    #[Computed()]
    public function allTags()
    {
        return Auth::user()->tags()->get();
    }

    public function update()
    {
        $this->form->update();

        $this->redirect(route("dishes"));
    }

    public function delete()
    {
        $this->dish->delete();
        $this->redirect(route("dishes"));
    }
}