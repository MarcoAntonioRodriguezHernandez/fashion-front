<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumb extends Component
{
    public $breadcrumb;

    public $ignoredWords = [
        'view',
    ];

    public $transformedWords = [
        'cancellation' => 'notifications',
        'temporary' => 'user',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct(array $elements = null)
    {
        $this->breadcrumb = [
            'Inicio' => route('dashboard'),
        ];

        if ($elements == null) {
            // If no elements are provided, build the breadcrumb based on the current path
            $currentRouteName = Route::currentRouteName();
            $route = explode('.', $currentRouteName);
            $module = $route[0]; //the module will be the first segment of the route

            array_shift($route); // Remove first element

            foreach ($route as $index => $segment) {
                // Ignore words that should not appear in breadcrumb
                if (!in_array($segment, $this->ignoredWords)) {
                    $segment = $this->transformedWords[$segment] ?? $segment; // Transform the segment if it exists in the array otherwise it stays the same
                    // Check if route exists before attempting to generate it
                    $routeName = null;
                    if (Route::has($module . '.' . $segment . '.index')) {
                        $routeName = route($module . '.' .  $segment . '.index');
                    }

                    $this->breadcrumb[trans("routes.$segment")] = $routeName;
                }
            }
        } else {
            // additional elements are provided, add them to the breadcrumb
            $this->breadcrumb = [
                ...$this->breadcrumb,
                ...$elements,
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.breadcrumb');
    }
}
