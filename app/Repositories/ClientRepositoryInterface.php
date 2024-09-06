<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ClientRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Model;

    public function update(array $data, Model $client): Model;

    public function delete(Model $client): bool;

    public function find($id): Model;
}

