<?php

namespace App\View\Components\Workorders;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SupplierField extends Component
{
    /**
     * Create a new component instance.
     */

    public $suppliers;
    public $workorder;
    public function __construct($suppliers, $workorder)
    {
        $this->suppliers = $suppliers;
        $this->workorder = $workorder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.workorders.supplier-field');
    }
}
