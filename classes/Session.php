<?php
class Session
{
	// check if a Session var exsists
	public static function exsists($name)
	{
		return (isset($_SESSION[$name])) ? true : false;
	}
	// create/put/make/set a session var
	public static function put($name, $value)
	{
		return $_SESSION[$name] = $value;
	}
	// get the session var
	public static function get($name)
	{
		return $_SESSION[$name];
	}
	// if the session var exsists delete it
	public static function delete($name)
	{
		if(self::exsists($name))
		{
			unset($_SESSION[$name]);
		}
	}
	// flash a pice of information using a session var
	public static function flash($name, $string = '')
	{
		if(self::exsists($name))
		{
			$session = self::get($name);
			self::delete($name);
			return $session;
		}
		else
		{
			self::put($name, $string);
		}
	}
}