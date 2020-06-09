<html>
<head>
    <title>File Upload In Codeigniter</title>
</head>
<body>
<?php echo $error;?> 
<?php echo form_open_multipart('upload/do_upload');?>
<?php echo "<input type='file' name='file' size='20' />"; ?>
<?php echo "<input type='submit' name='submit' value='upload' /> ";?>
<?php echo "</form>"?>
</body>
</html>