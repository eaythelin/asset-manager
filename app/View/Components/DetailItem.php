<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DetailItem extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $value;
    public function __construct($label, $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.detail-item');
    }
}
