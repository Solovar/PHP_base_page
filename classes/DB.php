<?php
// database handleing
class DB
{
	private static $_instance = null;
	private $_pdo, $_query, $_error = false, $_results, $_count = 0;
// construct PDO(PHP Data Objects) for database handleing or die is there is an error
	private function __construct()
	{
		try
		{
			$this -> _pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db') ,Config::get('mysql/username'),Config::get('mysql/password'));
		}
		catch(PDOException $e)
		{
			die($e -> getMessage());
		}
	}
// if an instance isn't created then created it, then return the instance
	public static function getInstance()
	{
		if(!isset(self::$_instance))
		{
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	// set the current instance's char set to utf8 !!!! WILL BRAKE LOG IN FUNCTION IF CALLED IN LOG IN FUNCTION!!!!
	public function queryAndSet ($query,$fields = array())
	{
		$this -> _pdo -> exec("set names utf8");
		return $this->query($query,$fields);
	}
// prepare the sql statement and excecute it
	public function query($sql, $params = array())
	{
		$this -> _error = false;
		if($this -> _query = $this -> _pdo -> prepare($sql))
		{
			$x = 1;
			if(count($params))
			{
				foreach($params as $param)
				{
					// bind the Values from the array to the ? of the query
					$this -> _query -> bindValue($x, $param);
					$x++;
				}
			}
		}
		if($this -> _query -> execute())
		{
			//execute statement
			$this -> _results = $this -> _query -> fetchAll(PDO::FETCH_OBJ);
			$this -> _count = $this -> _query -> rowCount();
		}
		else
		{
			// set error to true
			$this -> _error = true;
		}
		
		return $this;
	}
// create the sql statement	
	public function action($action, $table, $where = array())
	{
		if(count($where) === 3)
		{
			$operators = array('=', '>', '<', '>=', '<=');
			
			$field 		= $where[0];
			$operator	= $where[1];
			$value		= $where[2];

			if(in_array($operator, $operators))
			{
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this -> query($sql, array($value)) -> error())
				{
					return $this;
				}
			}
		}
		return false;
	}
// 	premade SELECT all statement
	public function get($table, $where)
	{
		return  $this -> action('SELECT *', $table, $where);
	}
// premade DELETE statement
	public function delete($table, $where)
	{
		return $this -> action('DELETE', $table, $where);
	}
	public function insertAndSet($table, $fields = array())
	{
		$this -> _pdo -> exec("set names utf8");
		return $this->insert($table, $fields);
	}
// premade insert user statement
	public function insert($table, $fields = array())
	{
		$keys	= array_keys($fields);
		$values = '';
		$x = 1;
		
		foreach($fields as $field)
		{
			$values .= '?';
			if($x < count($fields))
			{
				$values .= ', ';
			}
			$x++;
		}
		
		$sql = "INSERT INTO {$table}(`" . implode('`, `', $keys) . "`) VALUES ({$values})";
		
		if(!$this -> query($sql, $fields)->error())
		{
			return true;
		}
		return false;
	}
	public function updateAndSet($table, $id, $fields)
	{
		$this -> _pdo -> exec("set names utf8");
		return $this->update($table, $id, $fields);
	}
// premade update statement	
	public function update($table, $id, $fields)
	{
		$set 	= '';
		$x		= 1;
		
		foreach($fields as $name => $value)
		{
			$set .= "{$name} = ?";
			if($x < count($fields))
			{
				$set .= ', ';
			}
			$x++;
		}
		
		
		$sql	= "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if(!$this -> query($sql, $fields)->error())
		{
			return true;
		}
		
		return false;
	}
	// return all the results
	public function results()
	{
		return $this -> _results;
	}
	// return only the first result
	public function first()
	{
		return $this ->results()[0];
	}
	
// return the error	
	public function error()
	{
		return $this -> _error;
	}
// return the count
	public function count()
	{
		return $this-> _count;
	}	
}