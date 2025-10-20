<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AsideMenu extends Component
{
    public array|string $content;
    public string $mainTitle;
    public bool $isChild;
    public ?string $icon = null;

    /**
     * Create a new component instance.
     */
    public function __construct(string $mainTitle, array|string $content, bool $isChild = false)
    {
        $this->content = $content;
        $this->mainTitle = $mainTitle;
        $this->isChild = $isChild;

        if (gettype($this->content) == 'array') {
            $this->icon = $this->content['_icon'] ?? null;
            unset($this->content['_icon']);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.aside-menu');
    }
}
