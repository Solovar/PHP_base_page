<?php
class Token
{
	// generates a md5 token
	public static function generate()
	{
		return Session::put(Config::get('session/token_name'),bin2hex(openssl_random_pseudo_bytes(16)));
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