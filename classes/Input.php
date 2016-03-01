<?php
class Input
{
	// to check if there was anything submitted a form
	public static function exists($type)
	{
		switch($type)
		{
			//figur out the data type
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;
			
			case 'get':
				return (!empty($_GET)) ? true : false;
			break;

			case 'files':
				return (!empty($_FILES)) ? true : false;
				break;
			
			default:
				return false;
			break;
		}
	}
	// return the value reguardless of if it's a GET value or POST
	public static function get($item)
	{
		if(isset($_POST[$item]))
		{
			return $_POST[$item];
		}
		else if(isset($_GET[$item]))
		{
			return $_GET[$item];
		}
		else if(isset($_FILES[$item]))
		{
			return $_FILES[$item];
		}
		return '';
	}
}