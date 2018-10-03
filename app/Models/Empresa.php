<?php

namespace App\Models;

class Empresa extends Model
{
	/**
	 * @var string
	 */
	protected $nombre;

	/**
	 * lista de empleados
	 * @var array(App\Models\Empleado)
	 */
	protected $_empleados;


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
	 * @return array(App\Models\Empleado)
	 */
	protected function &_empleados($refresh = false) 
	{
		if($this->_empleados === null || $refresh){
			$this->_empleados = Empleado::repository()->search('empresa_id', $this->id);
		}

		return $this->_empleados;
	}

	public function allEmpleados($refresh = false)
	{
		return $this->_empleados($refresh);
	}

	public function removeEmpleado(Empleado $empleado) 
	{
		$id = $empleado->getId();
		
		Empleado::repository()->delete($empleado);

		foreach($this->_empleados() as $key => $item){
			if($item->getId() == $id){
				unset($this->_empleados[$key]);
				$this->_empleados = array_values($this->_empleados);
				break;
			}
		}
	}

	public function addEmpleado(Empleado $empleado) 
	{
		$empleado->setEmpresa($this);

		if(!$empleado->getId()){
			Empleado::repository()->create($empleado);
		} else {
			Empleado::repository()->update($empleado);
		}

		$this->_empleados[] = $empleado;
	}


	public function getPromedioEdadEmpleados()
	{
		$edades = [];
		foreach($this->allEmpleados() as $empleado){
			$edades[] = $empleado->getEdad() ?: 0;
		}
		
		return array_sum($edades) / count($edades);
	}


	public function findEmpleado($id)
	{
		$empleado = Empleado::repository()->find($id);

		return (!$empleado || $empleado->getEmpresa()->getId() !== $this->id) ?
			null: $empleado;
	}	
}