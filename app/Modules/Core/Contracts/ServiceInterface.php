<?php

namespace App\Modules\Core\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    public function getAll(): Collection;
    
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;
    
    public function getById(int $id): ?Model;
    
    public function search(array $criteria): Collection;
}
