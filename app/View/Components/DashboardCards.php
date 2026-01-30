<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardCards extends Component
{
    /**
     * Create a new component instance.
     */

    public $bgColor;
    public $title;
    public $number;
    public function __construct($bgColor, $title, $number)
    {
        $this->bgColor = $bgColor;
        $this->title = $title;
        $this->number = $number;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-cards');
    }
}
