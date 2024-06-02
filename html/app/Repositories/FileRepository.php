<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends BaseRepository
{
    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function index(array $relations = [])
    {

        return $this->model
            ->with($relations)
            ->when(
                request('search'),
                function ($query) {
                    return $query->where('name', 'like', '%' . request('search') . '%');
                }
            )
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->paginate);
    }
}
