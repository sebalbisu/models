<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Empleado;
use App\Models\Diseñador;
use App\Models\Programador;
use App\Models\Empresa;
use App\Storage\Memory;

class EmpleadoTest extends TestCase
{
    protected $empleado;

    protected $repo;

    protected $storage;

    public function setUp()
    {
        Memory::clear();
        $this->empleado = new Programador();
        $this->repo = $this->empleado::repository();
        $this->storage = $this->repo->getStorage();
    }

    public function test_same_repo_abstract_concrete()
    {
        $this->assertEquals(Empleado::repository(), Programador::repository());
    }

    public function testGetEmpresa_notfound()
    {
        $this->assertNull($this->empleado->getEmpresa());
    }

    public function testGetSetEmpresa()
    {
        $empresa = new Empresa();

        $empresa::repository()->create($empresa);

        $this->empleado->setEmpresa($empresa);

        $this->assertEquals($empresa->getId(), $this->empleado->getEmpresa()->getId());
    }    
}