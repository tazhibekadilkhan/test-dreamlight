<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File as FileFacade;

class FileService
{

    protected FileRepository $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): Response
    {
        return Inertia::render('Index', $this->repository->index());
    }

    public function fetch(): array
    {
        return $this->repository->index();
    }

    public function store(array $data): JsonResponse {
        $file = $this->makeImage($data['file']);

        if (isset($data['name'])) {
            $file['name'] = $data['name'];
        }

        $data = $file;

        return response()->json(['file' => $this->repository->create($data)]);
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
        if (Storage::exists('/public/'. str_replace('/storage/', '', $item->path))) {
            Storage::delete('/public/' . str_replace('/storage/', '', $item->path));
        }

        if (Storage::exists('/public/'. str_replace('/storage/', '', $item->thumbnail))) {
            Storage::delete('/public/' . str_replace('/storage/', '', $item->thumbnail));
        }

        $item->delete();

        return response()->json(['data' => 'successfully deleted a file.']);
    }

    private function makeImage($file): array
    {
        return [
            'name' => $file->getClientOriginalName(),
            'name_original' => $file->getClientOriginalName(),
            'size' => round($file->getSize() / 1048576, 1),
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
