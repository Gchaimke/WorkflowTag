<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// requires php5
$photo = $_POST['photo'];

// Use unlink() function to delete a file  
if (!unlink($_SERVER["DOCUMENT_ROOT"].$photo)) {
	echo ($_SERVER["DOCUMENT_ROOT"]."$photo cannot be deleted due to an error");
} else {
	echo ($_SERVER["DOCUMENT_ROOT"]."$photo has been deleted");
}
