<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardStat extends Component
{


    // El constructor recibe las props que usaremos
    public function __construct(public $title, public $value, public $color, public $icon=null)
    {
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.card-stat');
    }
}
