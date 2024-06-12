<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\User;

class UserRepository extends RepositoryStatic
{
    //protected static $model = User::class;
    public function model() {
        return User::class;
    }

}