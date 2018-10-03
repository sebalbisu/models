<?php

namespace App\Models;

use App\Repository;

abstract class Model
{
	/**
	 * @var integer
	 */
	protected $id;

	static public function repository()
	{
		return new Repository\Model(static::class);
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}