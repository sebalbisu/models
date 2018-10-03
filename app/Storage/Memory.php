<?php

namespace App\Storage;

class Memory 
{
	static protected $data = [];

	const COLUMN_ID = 'id';

	protected $table;

	public function __construct(string $table)
	{
		$this->table = $table;
	}

	static public function clear($table = null)
	{
		if($table) {
			static::$data[$this->table] = [];
		} else {
			static::$data = [];
		}
	}

	/**
	 * get the table storage
	 * @return array  data of of that table
	 */
	protected function &table()
	{
		if(!isset(static::$data[$this->table])){
			static::$data[$this->table] = [];
		}
		return static::$data[$this->table];
	}

	/**
	 * get all the items in a table
	 * @return array 
	 */
	public function all()
	{
		return array_values($this->table());
	}

	/**
	 * search for items get position
	 * @param  string    $column
	 * @param  mixed    $value
	 * @return array of pos
	 */
	protected function searchPos(string $column, $value) 
	{
		return array_keys(array_column($this->table(), $column), $value);
	}
	
	/**
	 * search for items
	 * @param  string    $column
	 * @param  mixed    $value
	 * @return array of items
	 */
	public function search(string $column, $value) 
	{
		$keys = $this->searchPos($column, $value);

		return array_values(array_intersect_key($this->table(), array_flip($keys)));
	}

	/**
	 * find by id
	 * @param  int $id
	 * @return array|null
	 */
	public function find(int $id)
	{
		$results = $this->search(self::COLUMN_ID, $id);

		return $results ? $results[0] : null;
	}

	/**
	 * get the last item id
	 * @return int|null
	 */
	protected function getLastId()
	{
		if(empty($this->table())) return null;

		return max(array_column($this->table(), self::COLUMN_ID));
	}	

	/**
	 * add an item
	 * @param array $data
	 */
	public function add(array $data) 
	{
		$id = $data[self::COLUMN_ID] = ($this->getLastId() ?: 0) + 1;

		array_push($this->table(), $data);

		return $id;
	}

	/**
	 * update an item
	 * @param array $data
	 */
	public function update(array $data) 
	{
		if(!isset($data[self::COLUMN_ID])) throw new Exception('id prop is required');

		$posKeys = $this->searchPos(self::COLUMN_ID, $data[self::COLUMN_ID]);

		if(empty($posKeys)) throw new Exception('Item not found');

		$this->table()[$posKeys[0]] = $data;

		return $this;
	}

	/**
	 * delete an item
	 * @param  int    $id
	 * @throws  \Exception
	 */
	public function delete(int $id)
	{
		$pos = $this->searchPos(self::COLUMN_ID, $id);

		if(empty($pos)) throw new Exception('Item not found');

		array_splice($this->table(), $pos[0], 1);
		array_values($this->table());

		return $this;
	}

}