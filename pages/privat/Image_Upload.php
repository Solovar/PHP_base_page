<?php
include 'includes/wideImage/WideImage.php';
if(Input::exists('post')) {
    if (Input::exists('files')) {
        echo 'test';
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        $validation = $validate->fileCheck(2, Input::get('img'), true, array(
            'image/png',
            'image/jpeg'
        ));

        if ($validation->passed()) {
            $i = 0;
            foreach (Input::get('img')['name'] as $img) {
                $new_file = Upload::getInstance()->insert(array(
                    'name' => $img,
                    'tmp_name' => Input::get('img')['tmp_name'][$i]
                ));
                $path = Config::get('upload/upload_path');
                $img = WideImage::load($path . $new_file);

                $resized = $img->resize(400, 300);

                $resized->saveToFile($path . 'thump_' . $new_file);
                $i++;
            }
            echo 'it passed';
        } else // post errors when there are errors
        {
            foreach ($validation->errors() as $error) {
                echo '<p>' . $error . '</p>';
            }
        }
        echo '<pre>';
        print_r(Input::get('img'));
        echo '</pre>';
    }
}
?>
<form method="post" action="" enctype="multipart/form-data">
    <input type="text" name="name">
    <input type="file" name="img[]" multiple>
    <input type="submit" value="submit">
</form>