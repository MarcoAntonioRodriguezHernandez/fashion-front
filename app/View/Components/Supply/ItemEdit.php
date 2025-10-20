<?php

namespace App\View\Components\Supply;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemEdit extends Component
{
    /**
     * Create a new component instance.
     */
    public bool $locked;

    public function __construct(bool $locked = false)
    {
        //
        $this->locked = $locked;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.supply.item-edit');
    }
}
