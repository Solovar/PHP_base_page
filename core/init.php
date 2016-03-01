<?php
//starts session for the entire page
ob_start();
session_start();
// global DB config, cookies, session name
$GLOBALS['config'] = array(
	'mysql' 	=> array(
		'host' 		=> '127.0.0.1',
		'username'	=> 'root',
		'password'	=> 'zaq123',
		'db'		=> 'serch_test'
	),
	'remember'	=> array(
		'cookie_name'	=> 'hash',
		'cookie_expiry'	=> 604800
	),
	'session'	=> array(
		'session_name' 	=> 'user',
		'token_name'	=> 'token',
		'basket_name'	=> 'basket'
	),
	'server_path'=>array(
		'document_root' => $_SERVER['DOCUMENT_ROOT'] . '/PHP_page_base/',
		'host_location' => $_SERVER['HTTP_HOST'],
		'admin_root'	=> 'http://localhost/PHP_page_base/admin',
		'db_key'		=> ''
	),
	'upload'	=> array(
		'upload_path' 	=> $_SERVER['DOCUMENT_ROOT'] . '/PHP_page_base/images/',
		'upload_limet'	=> 2000000,
		'uploade_total' => 2000000 * 7.5,
		'max_file_count'=> 20
	)
);
// loade functions when thay are needed
spl_autoload_register(function($class){
	require_once $GLOBALS['config']['server_path']['document_root'] . 'classes/' . $class . '.php';
});
// sanitize function
require_once $GLOBALS['config']['server_path']['document_root'] . 'functions/sanitize.php';
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exsists(Config::get('session/session_name')))
{
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashcheck = DB::getInstance()->get(Config::get('server_path/db_key') . 'oop_users_sessions', array('hash', '=', $hash));
	if($hashcheck->count())
	{
		$user = new User($hashcheck->first()->user_id);
		$user->login();
	}
}