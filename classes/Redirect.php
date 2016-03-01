<?php
class Redirect
{
	public static function to($location = null)
	{

		// if there is a location defined, carry on to redirect
		if($location)
		{
			//if the location is a numeric only value find the matching error code and redirect to it's location
			if(is_numeric($location))
			{
				switch($location)
				{
					case '404':
						header('HTTP/1.0 404 Not Found');
						include Config::get('server_path/document_root') . 'includes/error/404.php';
						exit();
					break;
				}
			}
			// end and clear the output buffering
			ob_end_clean();
			// if the location is any ohter value, redirect to that
			header('Location: ' . $location);
			exit();
		}
	}
}