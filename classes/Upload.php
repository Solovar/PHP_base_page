<?php
class Upload
{
    private static $_instance = null;
    private
        $_uploadPath,
        $_errors = false;


    private function __construct()
    {
        $this ->_uploadPath    = Config::get('upload/upload_path');
    }
    // singleton for uploade
    public static function getInstance()
    {
        if(!isset(self::$_instance))
        {
            self::$_instance = new Upload();
        }
        return self::$_instance;
    }
    // uploade the file
    private function action($temp_name, $target, $file_name)
    {
        move_uploaded_file($temp_name,$target);
        return $file_name;
    }
    // prepare file name and path for uploade, !!! must recive both a file name and the temp_name
    public function insert($file = array(), $maintain_name = false)
    {
        $file_extend = explode('.', $file['name']);
        $file_name = ($maintain_name)? date('his_jmY') . '_' . rand(10, 99) . '_' . $file['name'] : date('his_jmY') . '_' . rand(10000, 99999) . '.' . end($file_extend);
        $path = $this->_uploadPath . $file_name;
        return $this->action($file['tmp_name'],$path, $file_name);
    }
    // delete the file
    public function delete($file_name)
    {
        @unlink($this ->_uploadPath . $file_name);
        return $file_name;
    }
    // delete a file then uploade a new one
    public function update($old_file, $new_file = array())
    {
        @unlink($this ->_uploadPath . $old_file);
        return $this->insert($new_file);
    }
    // return of error
    public function error()
    {
        return $this ->_errors;
    }
}