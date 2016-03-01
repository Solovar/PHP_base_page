<?php
class Token
{
	// generates a md5 token
	public static function generate()
	{
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
	// check for a token
	public static function check($token)
	{
		$tokenName = Config::get('session/token_name');
		// if the token exsists delete it and return true, else return false
		if(Session::exsists($tokenName) && $token === Session::get($tokenName))
		{
			Session::delete($tokenName);
			return true;
		}
		
		return false;
	}
}