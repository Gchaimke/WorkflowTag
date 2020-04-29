<?php
$file = $_POST['file'];
$page = $_POST['page'];
if ($file) {
    file_put_contents($file, $page);
}else{
    print "No file name.";
}

$command = escapeshellcmd(base_url(''));
$output = shell_exec('python C:\Ampps\www\cgi-bin\test.py');
print $output."tested";

