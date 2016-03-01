<?php
class TempDir {
    public static function tempdir($dir=false,$prefix='php') {
        $tempfile=tempnam(sys_get_temp_dir(),'');
        if (file_exists($tempfile)) { unlink($tempfile); }
        mkdir($tempfile);
        if (is_dir($tempfile)) { return $tempfile; }
    }
}