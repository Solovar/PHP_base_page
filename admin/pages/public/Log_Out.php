<?php

$user = new User();
$user -> logout();
Session::flash('error', 'you are now logged out');
Redirect::to('?page=');