<?php
class Hash
{
	// make a sha256 hash out the the entered data and the salt
	public static function make($string, $salt = '')
	{
		return hash('sha256', $string . $salt);
	}
	// make a salt out of gibrish
	public static function salt($length)
	{
		return mcrypt_create_iv($length);
	}
	// make a uniqie id...
	public static function unique()
	{
		return self::make(uniqid());
	}
}