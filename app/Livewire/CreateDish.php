<?php

namespace App\Livewire;

use App\Livewire\Forms\DishForm;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreateDish extends Component
{
    public DishForm $form;

    #[Computed()]
    public function allTags()
    {
        return Auth::user()->tags()->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.create-dish');
    }

    public function store()
    {
        $this->form->store();

        $this->redirect(route("dishes"));
    }
}
