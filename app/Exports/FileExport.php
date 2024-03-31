<?php

namespace App\Exports;

use App\Models\File;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Bus\Queueable;


class FileExport implements FromCollection, WithHeadings, ShouldQueue
{
    //using queable for using queue of the excel file download
    use Exportable, Queueable;

    protected $fileId;

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
