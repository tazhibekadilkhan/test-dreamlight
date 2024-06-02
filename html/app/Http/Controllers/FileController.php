<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected FileService $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(FileRequest $request)
    {
        return $this->service->store($request->validated());
    }

    public function show(File $file)
    {
        return $this->service->show($file);
    }

    public function update(UpdateFileRequest $request, File $file)
    {
        return $this->service->update($request->validated(), $file);
    }

    public function destroy(File $file)
    {
        return $this->service->destroy($file);
    }
}
