<?php

$user = new User();
if(Input::exists('post'))
{
    if(Token::check(Input::get('token')))
    {
        $valdate = new Validate();
        $validation = $valdate->check($_POST, array(
            'name' => array(
                'required'  => true,
                'min'       => 2,
                'max'       => 50
            )
        ));

        if($validation->passed())
        {
            try
            {
                $user->update(array(
                    'name' => Input::get('name')
                ));
                Session::flash('success', 'Your details have been updated');
                Redirect::to('?page=');
            }
            catch(Exception $e)
            {
                die($e->getMessage());
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
<form  action="" method="post">
    <div class="field">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo escape($user->data()->name); ?>">

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="update">
    </div>
</form>