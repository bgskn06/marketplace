<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{

    public function index() {
        return view('seller.chat.index');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}