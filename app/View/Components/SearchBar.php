<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchBar extends Component
{
    /**
     * Create a new component instance.
     */

    public $route;
    public $placeholder;
    public function __construct($route, $placeholder)
    {
        $this->route=$route;
        $this->placeholder=$placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search-bar');
    }
}
