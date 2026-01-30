<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Label extends Component
{
    /**
     * Create a new component instance.
     */

    public $for;
    public $required;
    public function __construct($for, $required=false)
    {
        $this->for=$for;
        $this->required=$required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.label');
    }
}
