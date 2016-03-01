<?php
class Cookie
{
    // check if a Cookie is set
    public static function exists($name)
    {
      return (isset($_COOKIE[$name])) ? true : false;
    }
    // get the value of a Cookie
    public static function get($name)
    {
        return $_COOKIE[$name];
    }
    // get JSON based cookie
    public static function getJSON ($name)
    {
        return json_decode($_COOKIE[$name], true);
    }
    // set a JSON based Cookie
    public static function putJSON($name, $value, $expiry)
    {
        if(setcookie($name, json_encode($value), time() + $expiry, '/'))
        {
            return true;
        }
        return false;
    }
    // set a Cookie
    public static function put($name, $value, $expiry)
    {
        if(setcookie($name, $value, time() + $expiry, '/'))
        {
            return true;
        }
        return false;
    }
    // delete a Cookie
    public static function delete($name)
    {
        self::put($name, '', time() -1);
    }
}