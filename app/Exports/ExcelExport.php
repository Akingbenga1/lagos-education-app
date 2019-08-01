<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ExcelExport implements FromCollection, WithStrictNullComparison, WithHeadings
{

    protected $BulkRegistrationResponse;

    public function __construct(Collection $BulkRegistrationResponse)
    {
        $this->BulkRegistrationResponse = $BulkRegistrationResponse;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return  $this->BulkRegistrationResponse;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.

        return [
            'SN',
            'Student Name',
            'Admission Number',
            'School Name',
            'Error Message',
            'Error Trace'
        ];
    }
}
