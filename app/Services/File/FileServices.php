<?php

namespace App\Services\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FileExport;
use App\Jobs\DownloadAfterExportJob;
use App\Jobs\ExportFileJob;

class FileServices
{
    protected $user;
    protected $fileExt;
    protected $exportFormat;

    // constructor to set default values
    public function __construct()
    {
        // set default file extension and export format
        $this->fileExt = 'xlsx';
        $this->exportFormat = \Maatwebsite\Excel\Excel::XLSX;
    }

    // get files associated with the logged-in user
    public function getFiles()
    {
        $this->getLoggedUser(); //get logged in user
        // retrieve files created by the logged-in user
        $files = File::where("created_by", $this->user->id)->get();
        // return the files
        return $files;
    }
    // get the logged-in user
    public function getLoggedUser()
    {
        // retrieve the logged-in user
        $this->user = auth()->user();
    }
    //fucntion for uploading the file
    public function uploadFile($request)
    {
        $this->getLoggedUser();

        //find the user by if for creating file , so that created by is filled with this user
        $user = User::find($this->user->id);

        if ($request->file('file')->isValid()) {
            // get the uploaded file
            $file = $request->file('file');
            $randomString = Str::random(15);
            $extension = $file->getClientOriginalExtension();
            // Move the uploaded file to a directory ,this directory can be retreived using url()
            $path = $file->storeAs('public/uploads/jsons', $randomString . "." . $extension);
            //create the file
            $file = $user->files()->create(["name" => $request->name, "url" => "storage/uploads/jsons/" . $randomString . "." . $extension]);
            return $file;
        }
    }
    public function exportFile($fileId)
    {
        //filename for the expoted excel file with extension and current date
        $filename = "file-" . date('d-m-Y') . "." . $this->fileExt;

        //  (new FileExport($fileId))->queue('invoices.xlsx')->onQueue('exports');
        // dispatch(new ExportFileJob($fileId));
        // return redirect();
        //call the FileExport class object for exporting the json to excel

        // (new FileExport($fileId))->queue('users.xlsx');

        // return back()->withSuccess('Export started!');
        return  Excel::download(new FileExport($fileId), $filename, $this->exportFormat);
    }

    public function exportWithQueue($fileId)
    {
        $filename = "file-" . date('d-m-Y') . "." . $this->fileExt;

        $export =  (new FileExport($fileId))->store('users.xlsx');

        $export->chain([
            new DownloadAfterExportJob($fileId),
        ]);

        return back();
    }
}
