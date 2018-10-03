<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\Programador;
use App\Models\Diseñador;
use App\Storage\Memory;

class EmpresaTest extends TestCase
{
	protected $model;

	protected $repo;

	protected $storage;

	public function setUp()
	{
		Memory::clear();
		$this->empresa = new Empresa();
		$this->repo = Empresa::repository();
		$this->storage = $this->repo->getStorage();
	}

	/**
     * @expectedException 	App\Models\Extras\Exception
	 */
	public function testAddEmpleados_not_stored_empresa()
	{
		$this->empresa->addEmpleado($empleado1 = new Programador());
	}

	public function testAddEmpleados()
	{
		$this->repo->create($this->empresa);

		$this->empresa->addEmpleado($empleado1 = new Programador());
		$this->empresa->addEmpleado($empleado2 = new Diseñador());
		$this->empresa->addEmpleado($empleado3 = new Programador());

		$this->assertCount(3, $this->empresa->allEmpleados());		
	}

	public function testAllEmpleados_from_storage()
	{
		$this->repo->create($this->empresa);

		$this->empresa->addEmpleado($empleado1 = new Programador());
		$this->empresa->addEmpleado($empleado2 = new Diseñador());
		$this->empresa->addEmpleado($empleado3 = new Programador());

		$empresa = $this->repo->find($this->empresa->getId());

		$this->assertCount(3, $empresa->allEmpleados());
	}

	public function testRemoveEmpleado()
	{
		$this->repo->create($this->empresa);

		$this->empresa->addEmpleado($empleado1 = new Programador());
		$this->empresa->addEmpleado($empleado2 = new Diseñador());

		$this->empresa->removeEmpleado($empleado1);

		$this->assertCount(1, $this->empresa->allEmpleados());
		$this->assertCount(1, $this->empresa->allEmpleados($refresh = true));
	}	

	public function testPromedioEdadEmpleados()
	{
		$this->repo->create($this->empresa);

		$edades = [];
		foreach(range(1,rand(4, 9)) as $i){
			$edades[] = $last = rand(18, 65);
			$empleado = new Programador();
			$empleado->setEdad($last);
			$this->empresa->addEmpleado($empleado);
		}

		$this->assertEquals(
			array_sum($edades)/count($edades), 
			$this->empresa->getPromedioEdadEmpleados()
		);
	}

	public function testFindEmpleado()
	{
		$this->repo->create($this->empresa);

		$this->empresa->addEmpleado($empleado1 = new Programador());
		$this->empresa->addEmpleado($empleado2 = new Diseñador());

		$this->assertEquals($empleado1, $this->empresa->findEmpleado($empleado1->getId()));
	}			
}
