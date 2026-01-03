<?php

namespace App\Http\Interfaces\RepositoryInterfaces;

use App\Models\User;
use PhpParser\Node\Scalar\String_;

interface IUserRepository
{
    public function findByEmail(string $email): ?User;
}