<?php

namespace App\View\Components\Workorders;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DescriptionField extends Component
{
    /**
     * Create a new component instance.
     */

    public $value;
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.workorders.description-field');
    }
}
