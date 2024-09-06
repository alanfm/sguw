<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function find(int $id): Model;

    public function create(array $data): Model;

    public function update(array $data): bool;

    public function destroy(): bool;
}
