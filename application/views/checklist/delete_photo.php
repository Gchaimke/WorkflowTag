<?php
// requires php5
$photo = $_POST['photo'];

// Use unlink() function to delete a file  
if (!unlink($_SERVER["DOCUMENT_ROOT"].$photo)) {
	echo ("$photo cannot be deleted due to an error");
} else {
	echo ("$photo has been deleted");
}
