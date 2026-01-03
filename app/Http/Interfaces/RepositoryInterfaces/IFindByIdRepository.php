<?php

namespace App\Http\Interfaces\RepositoryInterfaces;

use App\Models\User;

interface IFindByIdRepository
{
    public function findById(string $id): ?User;
}