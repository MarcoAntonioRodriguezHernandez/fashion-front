<?php

namespace App\View\Components\Common;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommonBadge extends Component
{
    public string $textColor;
    public string $backgroundColor;

    /**
     * Create a new component instance.
     */
    public function __construct($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $this->getTextColor($backgroundColor);
    }

    private function getTextColor($backgroundColor)
    {
        $r = hexdec(substr($backgroundColor, 0, 2));
        $g = hexdec(substr($backgroundColor, 2, 2));
        $b = hexdec(substr($backgroundColor, 4, 2));

        $luminosity = (0.2126 * $r + 0.7152 * $g + 0.0722 * $b) / 255;

        return $luminosity > 0.5 ? '#000' : '#FFF';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.common.common-badge');
    }
}
