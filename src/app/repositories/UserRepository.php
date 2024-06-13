<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\User;

class UserRepository extends Repository
{
    protected static $instance = null;
    //protected static $model = User::class;
    public function model() {
        return User::class;
    }

    public function getByEmail($email)
    {
        $filter = "email = :email";
        $result = self::$queryBuilder->table($this->table())->select($filter, [':email' => $email]);
        if ($result) {
            return new $this->model($result[0]);
        }
        return null;
    }

}