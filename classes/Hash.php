<?php
class Hash
{
	// make a sha256 hash out the the entered data and the salt
	public static function make($string, $salt = '')
	{
		return hash('sha256', $string . $salt);
	}
	// new password hashing function
	public static function makeHashPass($password, $cost)
	{
		return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
	}
	// check password
	public static function checkPassword($submittedPass, $storedInfo)
	{
		return password_verify($submittedPass, $storedInfo);
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