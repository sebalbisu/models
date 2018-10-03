<?php

namespace Tests\Storage;

use PHPUnit\Framework\TestCase;

use App\Storage\Memory;

class MemoryTest extends TestCase
{

    protected $storage;

    public function setUp()
    {
        Memory::clear();        
        $this->storage = new Memory('my-table');
    }

    public function testAll_when_empty_is_array()
    {
        $this->assertInternalType('array', $this->storage->all());
    }

    public function testAdd()
    {
        $this->storage->add($item = ['nombre' => 'pepe']);

        $this->assertArraySubset([$item], $this->storage->all());
    }

    public function testAdd_multiple()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe']);
        $this->storage->add($item2 = ['nombre' => 'coco']);

        $this->assertArraySubset($item1, $this->storage->all()[0]);
        $this->assertArraySubset($item2, $this->storage->all()[1]);
    } 

    public function testSearch()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe', 'common' => true]);
        $this->storage->add($item2 = ['nombre' => 'coco', 'common' => true]);

        $results1 = $this->storage->search('nombre', 'coco');
        $results2 = $this->storage->search('common', true);

        $this->assertCount(1, $results1);
        $this->assertArraySubset($item1, $this->storage->all()[0]);
        $this->assertCount(2, $results2);
    } 

    public function testSearch_notfound()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe', 'common' => true]);
        $this->storage->add($item2 = ['nombre' => 'coco', 'common' => true]);

        $results1 = $this->storage->search('not_exists_column', '');
        $results2 = $this->storage->search('nombre', 'bad_nombre');

        $this->assertCount(0, $results1);
        $this->assertCount(0, $results2);
    }     

    public function testFind()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe']);
        $this->storage->add($item2 = ['nombre' => 'coco']);

        $results = $this->storage->find(1);

        $this->assertArraySubset($item1, $results);
    }     


    public function testFind_notfound()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe']);

        $results = $this->storage->find(2);

        $this->assertNull($results);
    }     


    public function testDelete()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe']);
        $this->storage->add($item2 = ['nombre' => 'coco']);

        $this->storage->delete(1);

        $this->assertNull($this->storage->find(1));
    } 

    /**
     * @expectedException     App\Storage\Exception
     */
    public function testDelete_notfound()
    {
        $this->storage->add($item1 = ['nombre' => 'pepe']);
        $this->storage->add($item2 = ['nombre' => 'coco']);

        $this->storage->delete(3);
    }         

    public function testUpdate()
    {
        $this->storage->add(['nombre' => 'pepe', 'age' => 25]);

        $item = $this->storage->find(1);
        $item['age'] = 26;

        $this->storage->update($item);

        $result = $this->storage->find(1);

        $this->assertArraySubset(['nombre' => 'pepe', 'age' => 26], $result);
    }

    /**
     * @expectedException     App\Storage\Exception
     */
    public function testUpdate_notfound()
    {
        $this->storage->update(['id'=>3]);
    }


}
