<?php
if (file_exists("./$working_dir")) {
    if ($handle = opendir("./$working_dir")) {
        while (false !== ($entry = readdir($handle))) {
            $ext = pathinfo($entry, PATHINFO_EXTENSION);
            $file_name = pathinfo($entry, PATHINFO_FILENAME);
            if ($entry != "." && $entry != ".." && ($ext == 'jpeg' || $ext == 'png') && PATHINFO_FILENAME != '' && $file_name != 'logo') {
                echo "<span id='$file_name' onclick='delPhoto(this.id)' class='btn btn-danger delete-photo'>
                        <i class='fa fa-trash'></i> $file_name</span>
                        <img id='$file_name' src='/$working_dir$entry' class='respondCanvas' >";
                echo '<script>photoCount++</script>';
            }
        }
        closedir($handle);
    }
}
