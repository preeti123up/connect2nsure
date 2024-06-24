<?php

namespace App\Exports;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PayrollExportDetails implements FromView, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $request;


     public function __construct($request)
    {
        $this->request = $request;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);

            },
        ];

    }
    
    public function view(): View
    {

        return view('attendances.exportPayrollDetails', [
            'data' => $this->request,
        ]);
    }

}
