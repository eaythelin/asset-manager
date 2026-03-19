<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestDisposalInput extends Component
{
    /**
     * Create a new component instance.
     */

    public $requestModel;
    public function __construct($requestModel = null)
    {
        $this->requestModel = $requestModel;    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.request-disposal-input');
    }
}
