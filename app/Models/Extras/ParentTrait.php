<?php

namespace App\Models\Extras;

use App\Repository\Model as Repo;

trait ParentTrait 
{
    static public function repository()
    {
        return new Repo(self::class);
    }
}