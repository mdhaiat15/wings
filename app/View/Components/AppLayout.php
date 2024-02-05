<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public array $breadCrumbList = [],
        public string $title = '',
        public array $action = [],
        public bool $hideTitle = false,
        public bool $alpineActive = false,
        public bool $alpineMask = false,
    ) {
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
