<?php

namespace App\Http\Controllers\File;


use App\Http\Controllers\Controller;
use App\Http\Requests\File\StoreFileRequest;
use App\Http\Resources\File\FileCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\File\FileServices;
use GuzzleHttp\Psr7\Request;

class FileController extends Controller
{

    //setting up the variable for injecting the file service
    protected $fileService;

    //constructor to inject all the instances of file service class
    public function __construct(FileServices $fileService)
    {
        $this->fileService = $fileService;
    }

    //returning the collection of files for showing in the datatable at frontend
    public function index()
    {
        //calling the api resource colletion for sending all the files of logged in user using json
        return (new FileCollection($this->fileService->getFiles()));
    }

    //for storing the files with the validation in StoreFileRequest
    public function store(StoreFileRequest $request)
    {
        //calling the method of fileService to handle file upload login
        $file = $this->fileService->uploadFile($request);
        return response()->json(['message' => 'File uploaded successfully', 'file' => $file], 201);
    }
    //for handling the export using laravel-excel
    public function exportAsExcel($fileId)
    {
        //calling the method of export to excel from file service
        return $this->fileService->exportFile($fileId);
    }
}
