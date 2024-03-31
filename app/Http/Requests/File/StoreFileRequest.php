<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            //name can be nullable and must be of string
            'name' => 'nullable|string',
            //validation rule for json file , it must be type of .json and it is required field
            'file' => 'required|mimes:json,txt',
        ];
    }
}
