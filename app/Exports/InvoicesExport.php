<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InvoicesExport implements FromView
{
    public function view(): View
    {
        return view('admin.financial.table', [
            'invoices' => Invoice::with(['reservation'])->get()
        ]);
    }
}
