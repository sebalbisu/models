<?php

namespace Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Storage\Memory;
use App\Repository\Model as Repository;
use App\Models\ParentMock;
use App\Models\ChildMock;

class ModelChildTest extends TestCase
{
    protected $storage;

    protected $repo;

    protected $model;

    static public function setUpBeforeClass()
    {
        require('_/ParentMock.php');
        require('_/ChildMock.php');
    }

    public function setUp()
    {
        Memory::clear();
        $this->parent = new ParentMock();
        $this->child = new ChildMock();
        $this->repo = new Repository($this->parent);
        $this->storage = new Memory(get_class($this->parent));
    }    


    public function test_same_repo_parent_child()
    {
        $this->assertEquals($this->parent::repository(), $this->child::repository());
    }    

    public function test_get_child_from_child_repo()
    {
        $repo = $this->child::repository();

        $this->child->setName('pepe');
        $repo->create($this->child);

        $result = $repo->find($this->child->getId());

        $this->assertSame($result->getId(), $this->child->getId());
    }

    public function test_get_child_from_parent_repo()
    {
        $repo = $this->parent::repository();

        $this->child->setName('pepe');
        $repo->create($this->child);

        $result = $repo->find($this->child->getId());

        $this->assertSame($result->getId(), $this->child->getId());
    }
}

