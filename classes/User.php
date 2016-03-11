<?php
class User
{
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_dbKey,
			$_isLoggedIn;
	
	public function __construct($user = null)
	{
		// get a DB instance
		$this->_db = DB::getInstance();
		// get session, cookie name and db key
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		$this->_dbKey = Config::get('server_path/db_key');

		if($user)
		{
			// try to find a user if there is one
			$this->find($user);
		}
		else
		{
			if (Session::exsists($this->_sessionName))
			{
				$user = Session::get($this->_sessionName);
				// if there is a user session
				if ($this->find($user))
				{
					// and that user exsists, log the user in
					$this->_isLoggedIn = true;
				}
				else
				{
					// if there is no user with that name, then log it out.
					$this->logout();
				}
			}
		}
	}
	// update user
	public function update($fields = array(), $id = null)
	{
		if(!$id && $this->isLoggedIn())
		{
			// id isn't set and the user is logged in then set the id
			$id = $this->data()->id;
		}

		if(!$this -> _db-> update( $this->_dbKey .'oop_users', $id, $fields))
		{
			// if the user couldn't be updated, throw new Exception to tell the dev/users that something went wrong
			throw new Exception('There was a problem updateing');
		}
	}
	// make user
	public function create($fields = array())
	{
		if(!$this->_db->insert($this->_dbKey . 'oop_users', $fields))
		{
			// try to inser a new user, and if something went worng, then tell the DEV/user that something went worng
			throw new Exception('There was a problem creating an account');
		}
	}
	// find a uers
	public function find($user = null)
	{
		if($user)
		{
			$field = (is_numeric($user)) ? 'id' : 'mail';
			// if the user var numeric or not
			$data = $this ->_db->get($this->_dbKey . 'oop_users', array($field, '=', $user));
			// search the DB for the users information
			if($data ->count())
			{
				// if there is a users set the user data to the data privat var, then return true
				$this -> _data = $data -> first();
				return true;
			}
		}
		return false;
	}
	// log in function
	public function login( $username = null, $password = null, $remember = false)
	{
		//if there is no user name and no password, but the users data exsists, set the user session and then log the user in
		if(!$username && !$password && $this->exists())
		{
			Session::put($this -> _sessionName, $this->data()->id);
			return true;
		}
		else
		{
			// else try to find the user
			$user = $this->find($username);

			if($user)
			{
				if (Hash::checkPassword($password,$this->data()->password))
				{
					// if the entered password hashed and salted, matcher det i databasen, set the user to be logged in
					Session::put($this->_sessionName, $this->data()->id);
					// if the user want to be remembered
					if ($remember)
					{
						// make a unik hash
						$hash = Hash::unique();
						// check if a hash for the users already exsists
						$hashcheck = $this->_db->get($this->_dbKey . 'oop_users_sessions', array('user_id', '=', $this->data()->id));

						if (!$hashcheck->count())
						{
							// if no hash is found make one for the user
							$this->_db->insert($this->_dbKey . 'oop_users_sessions', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						}
						else
						{
							// else set it
							$hash = $hashcheck->first()->hash;
						}
						// give the users a cookie with the hash
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}
		return false;
	}
	// check if user have permission
	public function hasPermission($key)
	{
		// get the spesefic users group information
		$group = $this -> _db->get($this->_dbKey . 'oop_groups', array('id', '=', $this->data()->group));
		if($group->count())
		{
			// if there is group information, json_decode it into an array
			$permissions = json_decode($group->first()->g_permissions, true);

			if($permissions[$key] == true)
			{
				// if the $permissions value is true the user have permission
				return true;
			}
		}
		return false;
	}
	// if the data isn't empty, then the users is logged in
	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}
	// log the user out, by deleteing the session, the cookie and the remember hash
	public function logout()
	{
		$this ->_db->delete($this->_dbKey . 'oop_users_sessions', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}
	// return the users data array
	public function data()
	{
		return $this ->_data;
	}
	// return true if the user is logged in
	public function isLoggedIn()
	{
		return $this -> _isLoggedIn;
	}
}