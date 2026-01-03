<?php

namespace App\Http\Repository;

use App\Http\Interfaces\RepositoryInterfaces\IFindByIdRepository;
use App\Http\Interfaces\RepositoryInterfaces\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository, IFindByIdRepository
{
    protected $model;
    
    public function __construct(User $user)
    {
        $this->model=$user;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    public function findById(string $id): ?User
    {
        return $this->model->findOrFail($id);
    }
}