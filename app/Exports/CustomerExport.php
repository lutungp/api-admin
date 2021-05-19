<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\Exportable;

class CustomerExport implements FromView
{

    use Exportable;

    public function view(): View
    {
        $customer = Customer::where('customer_aktif', 1)->get();
        return view('exports.customer', [
            'customer' => $customer
        ]);
    }
}
