<?php

namespace App\Repository;

use App\Storage\Memory;
use App\Models\Model as ModelEnitity;
use App\Models\Extras\IMultiChild;

class Model 
{
	const ATTR_CLASS_NAME = 'className';

	/**
	 * memory storage
	 * @var App\Storge\Memory
	 */
	protected $storage;

	protected $modelEntityClass;

	/**
	 * @param ModelEnitity|string $table 		table name and model entity class name
	 */
	public function __construct($table)
	{
		$this->modelEntityClass = ($table instanceof ModelEnitity) ? get_class($table) : $table;

		$this->storage = new Memory($this->modelEntityClass);
	}

	/**
	 * @return App\Storge\Memory
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * fill entity models
	 * @param  array  $item
	 * @return App\Models\Model
	 */
	protected function fillEntity(array $item, ModelEnitity $entity)
	{
		$reflect = new \ReflectionClass($entity);

		foreach($item as $prop => $value){
			$prop = $reflect->getProperty($prop);
			$prop->setAccessible(true);
			$prop->setValue($entity, $value);
		}

		return $entity;		
	}

	/**
	 * make entity models
	 * @param  array  $item
	 * @return App\Models\Model
	 */
	protected function makeEntity(array $item)
	{
		$modelEntityClass = isset($item[self::ATTR_CLASS_NAME]) ?
			$item[self::ATTR_CLASS_NAME]
			: $this->modelEntityClass;

		$entity = new $modelEntityClass;

		return $this->fillEntity($item, $entity);
	}


	/**
	 * return array internal data
	 * @param  ModelEnitity $entity
	 * @return array
	 */
	protected function getEntityData(ModelEnitity $entity)
	{
		$reflect = new \ReflectionClass($entity);
		$props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);

		$data = [];
		foreach ($props as $prop) {
			$prop->setAccessible(true);
			if($prop->getName()[0] == '_') 
				continue;
			$data[$prop->getName()] = $prop->getValue($entity);
		}

		return $data;
	}

	/**
	 * @return array<App\Models\Model> all the items
	 */
	public function all() 
	{
		$items = $this->storage->all();
		
		$entities = [];
		foreach($items as $item){
			$entities[] = $this->makeEntity($item);
		}

		return $entities;
	}


	/**
	 * @return array<App\Models\Model> all the items
	 */
	public function search($column, $value) 
	{
		$items = $this->storage->search($column, $value);
		
		$entities = [];
		foreach($items as $item){
			$entities[] = $this->makeEntity($item);
		}

		return $entities;
	}

	/**
	 * find by id
	 * @param  int $id 
	 * @return App\Models\Model|null
	 */
	public function find(int $id) 
	{		
		$item = $this->storage->find($id);

		if(!$item) return null;

		return $this->makeEntity($item);
	}

	/**
	 * save model
	 * @param  App\Models\Model $model
	 * @return App\Models\Model
	 */
	public function create(ModelEnitity $model) 
	{
		if($model->getId()) throw new Exception('model already created');

		$id = $this->storage->add($this->getEntityData($model));

		$this->fillEntity(['id' => $id], $model);

		return $model;
	}

	/**
	 * update model
	 * @param  App\Models\Model $model
	 * @return App\Models\Model
	 */
	public function update(ModelEnitity $model) 
	{
		$id = $this->storage->update($this->getEntityData($model));

		return $model;
	}

	/**
	 * delete model
	 * @param  App\Models\Model|int $model
	 * @return static
	 */
	public function delete($model) 
	{
		$id = $model instanceof ModelEnitity ? $model->getId() : $model;

		$this->storage->delete($id);

		return $this;
	}
}