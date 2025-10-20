<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LocationMap extends Component
{
    public string $header;
    public string $title;
    public string $description;
    public array $locations;
    /**
     * Create a new component instance.
     */ 
    public function __construct(array $locations, string $header, string $title, string $description)
    {
        $this->locations = $locations;
        $this->title = $title;
        $this->description = $description;
        $this->header = $header;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.location-map');
    }

}
