<?php

namespace App\Exports;

use App\Models\File;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;



class FileExport implements FromCollection, WithHeadings, ShouldQueue
{
    //using queable for using queue of the excel file download
    use Exportable, Queueable;

    protected $fileId;


    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'invoices.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    //initializing the required variable for this class
    public function __construct($fileId)
    {

        $this->fileId = $fileId;
    }
    public function collection()
    {
        //retrive  the file for filedId which is comming from url params
        $file = File::where("id", $this->fileId)->first();

        // Load data from JSON file or array
        $data = json_decode(file_get_contents(url($file->url)), true);

        // Transform data as needed
        $collection = collect($data);

        //return the collection for exporting to the excel
        return $collection;
    }
    public function headings(): array
    {
        //heading for the excel file
        return [
            'Name',
            'Email',
            'Phone',
            'Address'
        ];
    }
}
