<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestAssetSelect extends Component
{
    /**
     * Create a new component instance.
     */

    public $assets;
    public $selected;
    public function __construct($assets,$selected = null)
    {
        $this->assets = $assets;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.request-asset-select');
    }
}
