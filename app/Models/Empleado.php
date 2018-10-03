<?php

namespace App\Models;

use App\Models\Extras\ParentTrait;
use App\Models\Extras\Exception;

class Empleado extends Model
{
	use ParentTrait;

	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * @var string
	 */
	protected $apellido;

	/**
	 * @var integer
	 */
	protected $edad;

	/**
	 * @var int
	 */
	protected $empresa_id;


	/**
	 * @return string
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 * @return static
	 */
	public function setNombre($nombre)
	{
		$this->nombre = ucfirst(trim($nombre));

		return $this;
	}

	/**
	 * @return string
	 */
	public function getApellido()
	{
		return $this->apellido;
	}

	/**
	 * @return static
	 */
	public function setApellido($apellido)
	{
		$this->apellido = ucfirst(trim($apellido));

		return $this;
	}	

	/**
	 * @return integer
	 */
	public function getEdad()
	{
		return $this->edad;
	}

	/**
	 * @return static
	 */
	public function setEdad(int $edad)
	{
		$this->edad = $edad;

		return $this;
	}	

	/**
	 * @return integer
	 */
	public function getEmpresa()
	{
		static $empresa;

		return $this->empresa_id ?
			Empresa::repository()->find($this->empresa_id) : null;
	}

	/**
	 * @return static
	 */
	public function setEmpresa(Empresa $empresa)
	{
		if(!$empresa->getId()) throw new Exception('empresa->id must exists');

		$this->empresa_id = $empresa->getId();

		return $this;
	}

}