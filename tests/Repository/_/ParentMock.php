<?php

namespace App\Models;

use App\Models\Extras\ParentTrait;

class ParentMock extends Model
{
    use ParentTrait;

    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        return $this->name = $name;
    }        
}