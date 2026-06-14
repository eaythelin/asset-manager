<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BackLink extends Component
{
    /**
     * Create a new component instance.
     */

    public $route;
    public $params;
    public function __construct($route, $params = null)
    {
        $this->route=$route;
        $this->params=$params;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.back-link');
    }
}
