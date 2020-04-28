<?php
	// requires php5
	define('UPLOAD_DIR', 'images/');
	$folder = $_POST['pr']."/";
	$name = $_POST['sn'];
	$num = $_POST['num'];
	$file_name = $name."_".$num;
	$img = $_POST['data'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	if (!file_exists(UPLOAD_DIR.$folder."/".$name)) {
		mkdir(UPLOAD_DIR.$folder."/".$name, 0777, true);
	}
	$file = UPLOAD_DIR.$folder."/".$name."/". $file_name . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';
