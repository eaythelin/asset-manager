<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tables extends Component
{
    /**
     * Create a new component instance.
     */

    public $columnNames;
    public $centeredColumns;
    public function __construct($columnNames, $centeredColumns = [])
    {
        $this->columnNames = $columnNames;
        $this->centeredColumns = $centeredColumns;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tables');
    }
}
