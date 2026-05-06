<?php

namespace App\Modules\Core\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    public function list(int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): ?Model;
    public function create(array $data): Model;
    public function update(Model $model, array $data): Model;
    public function delete(Model $model): void;
}
