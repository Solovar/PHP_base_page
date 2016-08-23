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
    public static function putJSON($name, $value, $expiry, $httpOnly = false, $https = null, $domain = null)
    {
        if(setcookie($name, json_encode($value), Cookie::setDateTime($expiry), '/', $https = null, $domain = null, $httpOnly = false))
        {
            return true;
        }
        return false;
    }
    // set a Cookie
    public static function put($name, $value, $expiry, $httpOnly = false, $https = null, $domain = null)
    {
        if(setcookie($name, $value, Cookie::setDateTime($expiry), '/', $https, $domain, $httpOnly))
        {
            return true;
        }
        return false;
    }
    // delete a Cookie
    public static function delete($name)
    {
        self::put($name, '', '-1 Hour');
    }
    // set DateTime
    public static function setDateTime($value)
    {
        $time = new DateTime($value);
        return $time->getTimestamp();
    }
}