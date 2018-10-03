<?php

namespace App\Models;

use App\Models\Extras\Exception;
use App\Models\Extras\ChildTrait;

class Programador extends Empleado
{
	use ChildTrait;

	/**
	 * lenguajes en que programa
	 * @var string
	 */
	protected $lenguajes;

	const LENGUAJES = [
		'PHP' => 'php',
		'NET' => 'net',
		'PHYTON' => 'phyton',
	];


	/**
	 * @return array
	 */
	public function getLenguajes()
	{
		return $this->lenguajes;
	}

	/**
	 * @throws App\Models\Exception  si no es del lenguaje valido
	 * @return static
	 */
	public function setLenguajes(array $lenguajes)
	{
		foreach($lenguajes as $lenguaje)
			if(!in_array($lenguaje, array_keys(array_flip(static::LENGUAJES))))
				throw new Exception("lenguaje invalido: " . $lenguaje);

		$this->lenguajes = $lenguajes;

		return $this;
	}	


	/**
	 * check if know program in a lang
	 * @param  string  $lang
	 * @return boolean
	 */
	public function sabeLenguaje(string $lenguaje)
	{
		return in_array($lenguaje, $this->lenguajes);
	}	
}