<?php

if(!$user_id = Input::get('id'))
{
    Redirect::to('?');
}
else
{

    $user = new User($user_id);
    if(!$user->exists())
    {
        Redirect::to('404');
    }
    else
    {
        $data = $user -> data();
    }
    ?>
        <h3><?php echo escape($data->username); ?></h3>
        <p><?php echo escape($data->name); ?></p>
    <?php
}