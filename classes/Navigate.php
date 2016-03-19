<?php
// $user->is_logged_in() ? $user->get('first_name') : 'Guest'
class Navigate
{
	public static function to($page, $isLoggedIn, $indexTitle = 'Home')
	{
		if ($page) {
			// if there is page information, turn it into an array
			$value = explode('_', $page, 2);
			// figur out what foulder the file is sepposed to go to
			//$permish = ($value[0] == 'admin') ? /*(($user->isLoggedIn()) ?*/ Redirect::to(Config::get('server_path/admin_root')) /*: Redirect::to('404'))*/ :  (($value[0] == 'pri') ? 'privat/' : 'public/');

			$permish = ($value[0] == 'admin') ? (($isLoggedIn) ? Redirect::to(Config::get('server_path/admin_root')) : 'public/') : (($value[0] == 'pri') ? 'privat/' : 'public/');

			// make the content array
			$content = array(
				'pages/' . $permish . $value[1] . '.php',	// create the path to the file
				str_replace('_', ' ', $value[1])			// create the title
			);

			if(substr_count($content[0], '/') != 2)
			{
				return $content = array(
					'',
					''
				);
			}

			return $content;
		}

		return $content = array(
			'pages/public/Front.php',
			$indexTitle
		);
	}
}