<?php

namespace App\View\Components\Product;

use App\Models\Base\PaymentType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class InvoiceSelection extends Component
{
    public $users;
    public $paymentTypes;

    /**
     * Create a new component instance.
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
        $this->paymentTypes = PaymentType::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product.invoice-selection');
    }
}
