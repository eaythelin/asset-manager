<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageDateInput extends Component
{
    /**
     * Create a new component instance.
     */

    public $name;
    public $value;
    public $id;
    public function __construct($name, $value, $id)
    {
        $this->name = $name;
        $this->value = $value;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-date-input');
    }
}
