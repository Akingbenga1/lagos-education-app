<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeImport;

class ExcelImport implements ToCollection, WithHeadingRow, WithEvents
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
//        dd($collection);

    }

//    /**
//     * @return array
//     */
//    public function sheets(): array
//    {
//        // TODO: Implement sheets() method.
//    }

    public function registerEvents(): array
    {
        Session::forget("workSheetNameArray" );
        return [
            // Handle by a closure.
            BeforeImport::class => function(BeforeImport $event)
            {
                $AllWorkSheets = $event->reader->getWorksheets($this);
                $WorkSheetNameArray = array_keys($AllWorkSheets);
                Session::put("workSheetNameArray",  $WorkSheetNameArray );
//                dd($WorkSheetNameArray);
                // Do something before the export process starts.
            },
        ];
    }
}
