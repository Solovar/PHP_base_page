<?php
	require_once 'core/init.php';
//var_dump(Token::check(Input::get('token')));	
	if(Input::exists('post'))
	{
		if(Token::check(Input::get('token')))
		{
			//echo Input::get('username');
			// validate rules and values
			$validate = new Validate();
			$validation = $validate -> check ($_POST, array(
				'mail' 		=> array(
					'required'		=> true,
					'min'			=> 2,
					'max'			=> 255,
					'mail'			=> true,
				),
				'username' 		=> array(
					'required'		=> true,
					'min'			=> 2,
					'max'			=> 20,
					'sumbols'		=> true,
					'number'		=> false,
					'exclude'		=> true
				),
				
				'password' 		=> array(
					'required' 		=>true,
					'min'			=> 6
				),
				
				'password_again'=> array(
					'required' 		=> true,
					'matches'		=> 'password'
				),
				
				'name'			=> array(
					'required' 		=> true,
					'min'			=> 2,
					'max'			=> 50
				)
			));
			// check if the validation passed or not
			if($validation -> passed())
			{
				// set a new user and make the salt and it's length
				$user = new User();
				$salt = Hash::salt(32);
				// try to create the user array
				try
				{
					$user->create(array(
						'username'  => Input::get('username'),
						'password'  => Hash::make(Input::get('password'), $salt),
						'salt'		=> $salt,
						'name'		=> Input::get('name'),
						'joined'	=> date('Y-m-d H:i:s'),
						'group'		=> 1
					));
				}
				catch(Exception $e) //catch and die if try failes, display msg
				{
					die($e->getMessage());
				}
				// flash exsample
				Session::flash('success', 'you registered successfully');
				// redirect exsample
				Redirect::to('?page=');
			}
			else // post errors when there are errors
			{
				foreach($validation->errors() as $error)
				{
					echo '<p>' . $error . '</p>';
				}
			}
		}
	}
?>
<form action="" method="post">
	<div class="field">
		<label for="mail">mail:</label>
		<input type="text" name="mail" id="mail" value="<?php echo escape(Input::get('mail')); ?>" >
	</div>
	<div class="field">
    	<label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" >
    </div>
    <div class="field">
    	<label for="password">Chooses your password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
    	<label for="password_again">Chooses your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    <div class="field">
    	<label for="name">your name</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
    </div>
    
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" name="submit" value="Register">
</form>