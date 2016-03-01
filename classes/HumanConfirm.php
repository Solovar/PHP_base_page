<?php
class HumanConfirm {
    private static $_instance = null;
    private $_path;

    private function __construct()
    {
        $this -> _path = Config::get('server_path/document_root');
    }
    public static function getInstance()
    {
        if(!isset(self::$_instance))
        {
            self::$_instance = new HumanConfirm();
        }
        return self::$_instance;
    }
    // make the text string
    public function make($length = 5)
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            .'0123456789');
        $rand = '';
        foreach (array_rand($seed, $length) as $k)
        {
            $rand .= $seed[$k];
        }
        return $this->get($rand);
    }

    private function get($text = '')
    {
        // get wide image and the back image and noise image neede for the image
        require_once $this -> _path . 'includes/wideImage/WideImage.php';
        $img = WideImage::load($this -> _path . 'images/back.png');
        $watermark = WideImage::load($this -> _path . 'images/noise.png');
        // write the text onto the instance of the "back" image, merge the transparent noise ontop of that instanced picture and save that instance as a new picture
        $canvas = $img->getCanvas();
        $canvas->useFont($this -> _path . 'includes/font/Slabo13px-Regular.ttf', 16, $img->allocateColor(0, 0, 0));
        $canvas->writeText('center', 'center', $text);
        $imgName = time() . "_test.png";
        $image = $img->merge($watermark, 0, 0, 62);
        $image->saveToFile($imgName);
        // then open that image and save a base64_encode of it, then delete the image
        $temp = fopen($imgName, 'r+');
        $contents = base64_encode(fread($temp, filesize($imgName)));
        fclose($temp);
        unlink($imgName);
        // return an array with the base64_encode and the cooresponding text string
        return array($contents, $text);
    }
}