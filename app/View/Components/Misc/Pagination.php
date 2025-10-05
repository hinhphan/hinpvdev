<?php

namespace App\View\Components\Misc;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $prevUrl = '',
        public string $nextUrl = '',
        public int $currentPage = 1,
        public int $totalPages = 1
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.misc.pagination');
    }
}
