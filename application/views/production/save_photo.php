<?php
defined('BASEPATH') or exit('No direct script access allowed');
// requires php5
define('UPLOAD_DIR', 'Uploads/');
$folder = $_POST['pr'];
$name = $_POST['sn'];
$num = $_POST['num'];
$file_name = $name . "_" . $num;
$img = $_POST['data'];
if (preg_match('/^data:image\/(\w+);base64,/', $img, $type)) {
    $img = substr($img, strpos($img, ',') + 1);
    $type = strtolower($type[1]); // jpg, png, gif

    if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
        throw new \Exception('invalid image type');
    }

    $img = base64_decode($img);

    if ($img === false) {
        throw new \Exception('base64_decode failed');
    }
} else {
    throw new \Exception('did not match data URI with image data');
}

if (!file_exists(UPLOAD_DIR . $folder . "/" . $name)) {
    mkdir(UPLOAD_DIR . $folder . "/" . $name, 0770, true);
}
$file = UPLOAD_DIR . $folder . "/" . $name . "/" . $file_name . ".$type";
$success = file_put_contents($file, $img);
if (!file_exists("C:\Program Files\Ampps\www\assets\exec\pngquanti.exe")) {
    shell_exec('"C:\Program Files\Ampps\www\assets\exec\pngquanti.exe" --ext .png --speed 10 --nofs --force ' . escapeshellarg($file));
}
print $success ? $file : 'Unable to save the file.';
