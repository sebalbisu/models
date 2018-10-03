<?php

namespace App\Models;

use App\Models\Extras\Exception;
use App\Models\Extras\ChildTrait;

class Diseñador extends Empleado
{	
	use ChildTrait;

	/**
	 * tipo de diseñador
	 * @var string
	 */
	protected $tipos;

	const TIPOS = [
		'WEB' => 'web',
		'GRAFICO' => 'grafico',
	];


	/**
	 * @return array
	 */
	public function getTipos()
	{
		return $this->tipos;
	}

	/**
	 * @param  $tipo
	 * @throws App\Models\Exception  si no es del tipo valido
	 * @return static
	 */
	public function setTipos(array $tipos)
	{
		foreach($tipos as $tipo)
			if(!in_array($tipo, array_keys(array_flip(static::TIPOS))))
				throw new Exception("tipo invalido: " . $tipo);

		$this->tipos = $tipos;

		return $this;
	}	

	/**
	 * check if is some tipo
	 * @param  string  $tipo
	 * @return boolean
	 */
	public function isTipo(string $tipo)
	{
		return in_array($tipo, $this->tipos);
	}
}