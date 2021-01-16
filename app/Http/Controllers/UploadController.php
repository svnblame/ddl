<?php

namespace App\Http\Controllers;

use League\Flysystem\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadFileRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

class UploadController extends Controller
{
    /**
     * List all the files uploaded.
     * 
     * @param $request : A standard form request object
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     * @throws BindingResolutionException
     */
    protected function list(Request $request)
    {
        $uploads = Storage::allFiles('uploads');
        return view('list', ['files' => $uploads]);
    }

    /**
     * Return a binary file response.
     * 
     * @param $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws BindingResolutionException
     */
    protected function download($file)
    {
        return response()->download(storage_path('app/' . $file));
    }

    /**
     * Return upload view.
     * 
     * @return \Illuminate\Contracts\View\Factory\Illuminate\View\View
     * @throws BindingResolutionException 
     */
    protected function upload()
    {
        return view('upload');
    }

    /**
     * Handle validated file uploads.
     * 
     * @param UploadFormRequest $request
     * @return array | \Illuminate\Http\UploadedFile | \Illuminate\Http\UploadedFile[] | null
     * @throws BindingResolutionException
     */
    protected function store(UploadFileRequest $request)
    {
        $filename = $request->fileName;
        Util::normalizePath($filename);
        
        $file = $request->file('userFile');
        $extension = $file->getClientOriginalExtension();
        $saveAs = $filename . '.' . $extension;

        $file->storeAs('uploads', $saveAs, 'local');

        return response()->json(['success' => true]);
    }

}
