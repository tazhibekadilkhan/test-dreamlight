<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class FileService
{

    protected FileRepository $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): JsonResponse {
        return response()->json(['data' => ['items' => $this->repository->index()]]);
    }

    public function store(array $data): JsonResponse {
        $file = $this->makeImage($data['file']);

        if (isset($data['name'])) {
            $file['name'] = $data['name'];
        }

        $data = $file;

        return response()->json(['data' => $this->repository->create($data)]);
    }

    public function show($item): JsonResponse
    {
        return response()->json(['data' => $item]);
    }

    public function update(array $data, $item): JsonResponse
    {
        if (isset($data['file'])) {
            Storage::delete($item->path);
            Storage::delete($item->thumbnail);

            $file = $this->makeImage($data['file']);

            if (isset($data['name'])) {
                $file['name'] = $data['name'];
            }

            $data = $file;
        }

        return response()->json(['data' => $this->repository->update($item, $data)]);
    }

    public function destroy($item): JsonResponse
    {
        Storage::delete($item->path);
        Storage::delete($item->thumbnail);

        return response()->json(['data' => 'successfully deleted a file.']);
    }

    private function makeImage($file): array
    {
        return [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'path' => $file->store('files', 'public'),
            'thumbnail' => $this->makeThumbnail($file)
        ];
    }

    private function makeThumbnail($file): string
    {
        $manager = new ImageManager(new Driver());
        $generatedName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
        $image = $manager->read($file);

        $image = $image->resize(100, 100);
        $encoded = $image->encodeByMediaType('image/jpeg');

        $path = 'thumbnails/' . $generatedName;

        Storage::put('public/' . $path, $encoded);
        return $path;
    }
}
