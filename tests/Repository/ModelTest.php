<?php

namespace Tests\Repository;

use PHPUnit\Framework\TestCase;

use App\Storage\Memory;
use App\Repository\Model as Repository;
use App\Models\ModelMock;

class ModelTest extends TestCase
{
	protected $storage;

	protected $repo;

	protected $model;

	static public function setUpBeforeClass()
	{
		require('_/ModelMock.php');
	}

	public function setUp()
	{
		Memory::clear();		
		$this->model = new ModelMock();
		$this->repo = new Repository($this->model);
		$this->storage = new Memory(get_class($this->model));
		$this->storage->clear();
	}	


	public function testAll_empty_is_array()
	{
		$this->assertEmpty($this->repo->all());
	}	

	public function testAll_array_of_models_default_entity()
	{
		$storage = new Memory(get_class($this->model));
		$storage->add(['name' => 'pepe']);
		$storage->add(['name' => 'coco']);

		$repo = new Repository($this->model); 
		$result = $repo->all();

		$this->assertContainsOnlyInstancesOf(ModelMock::class, $result);
		$this->assertCount(2, $result);
	}	

	public function testFind_valid()
	{
		$storage = new Memory(get_class($this->model));
		$id = $storage->add($item = ['name' => 'pepe']);

		$repo = new Repository($this->model);
		$result = $repo->find($id);

		$this->assertSame($item['name'], $result->getName());
	}

	public function testFind_notfound()
	{
		$storage = new Memory(get_class($this->model));
		$id = $storage->add($item = ['name' => 'pepe']);

		$repo = new Repository($this->model);
		$result = $repo->find($id + 1);

		$this->assertNull($result);
	}

	public function testCreate_valid()
	{
		$this->model->setName('pepe');

		$this->repo->create($this->model);

		$this->assertNotNull($this->model->getId());
	}	

	/**
     * @expectedException 	App\Repository\Exception
	 */
	public function testCreate_already_exists()
	{
		$model = $this->repo->create($this->model);
		$model2 = $this->repo->create($model);
	}

	public function testUpdate()
	{
		$this->repo->create($this->model);

		$this->model->setName('coco');

		$this->repo->update($this->model);

		$model = $this->repo->find($this->model->getId());

		$this->assertEquals('coco', $model->getName());
	}	

	public function testDelete_id()
	{
		$this->repo->create($this->model);

		$id = $this->model->getId();

		$this->repo->delete($id);

		$result = $this->repo->find($id);

		$this->assertNull($result);
	}	

	public function testDelete_entity()
	{
		$this->repo->create($this->model);

		$id = $this->model->getId();

		$this->repo->delete($this->model);

		$result = $this->repo->find($id);

		$this->assertNull($result);
	}	
}
