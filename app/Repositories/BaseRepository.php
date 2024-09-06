<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    public function __construct(protected Model $model)
    {
        //
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data): bool
    {
        return $this->model->update($data);
    }

    public function destroy(): bool
    {
        return $this->model->delete();
    }
}
