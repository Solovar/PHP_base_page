<?php
$user = new User();

if(!$user->isLoggedIn())
{
    Redirect::to('index.php');
}

if(Input::exists('post'))
{
    if(Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_current'  => array(
                'required'  => true,
                'min'       => 6
            ),
            'password_new'      => array(
                'password'  => true,
                'min'       => 6
            ),
            'password_new_again'=> array(
                'password'  => true,
                'min'       => 6,
                'matches'   => 'password_new'
            ),
        ));

        if($validation->passed())
        {
            if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password)
            {
                echo '<p>Your current password is wrong</p>';
            }
            else
            {
                $salt = Hash::salt(32);
                $user -> update(array(
                    'password'  => Hash::make(Input::get('password_new'), $salt),
                    'salt'      => $salt
                ));

                Session::flash('success', 'Your password has been chanced!');
                Redirect::to('?page=');
            }
        }
        else
        {
            foreach($validation->errors() as $error)
            {
                echo '<p>' . $error . '</p>';
            }
        }
    }
}
?>
<div class="columns twelve">
    <form action="" method="post">
        <div class="field">
            <label for="password_current">Current Password:</label>
            <input type="password" name="password_current" id="password_current">
        </div>

        <div class="field">
            <label for="password_new"> New Password</label>
            <input type="password" name="password_new" id="password_new">
        </div>

        <div class="field">
            <label for="password_new_again">New password again:</label>
            <input type="password" name="password_new_again" id="password_new_again">
        </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="submit" value="Change">
    </form>
</div>