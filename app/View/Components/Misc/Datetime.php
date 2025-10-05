<?php

namespace App\View\Components\Misc;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class Datetime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $datetimeAttr = '',
        public string $datetime = ''
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.misc.datetime');
    }
}
