<?php

namespace App\Repositories;

use App\Models\File;
use Symfony\Component\Console\Input\Input;

class FileRepository extends BaseRepository
{
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function index(array $relations = []): array
    {
        $totalFilesCount = $this->model->count();

        $files = $this->model
            ->with($relations)
            ->when(
                request('search'),
                function ($query) {
                    return $query->where('name', 'like', '%' . request('search') . '%');
                }
            )
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->paginate)
            ->setPath(route('files.index'))
            ->appends(request()->query());


        $currentPageFilesCount = $files->count();



        return [
            'search' => request('search') ?? '',
            'files' => $files,
            'totalFilesCount' => $totalFilesCount,
            'currentPageFilesCount' => $currentPageFilesCount,
        ];
    }
}
