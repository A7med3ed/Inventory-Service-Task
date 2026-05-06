<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Contracts\RepositoryInterface;
use App\Modules\Core\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService implements ServiceInterface
{
    public function __construct(
        protected RepositoryInterface $repository
    ) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function getById(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function search(array $criteria): Collection
    {
        return $this->repository->findWhere($criteria);
    }
}
