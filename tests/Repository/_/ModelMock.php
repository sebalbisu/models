<?php

namespace App\Models;

class ModelMock extends Model
{
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