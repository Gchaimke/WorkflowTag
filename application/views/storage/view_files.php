<?PHP
if (isset($working_dir) && file_exists("./$working_dir")) {
    if ($handle = opendir("./$working_dir")) {
        $count = 1;
        while (false !== ($entry = readdir($handle))) {
            $ext = pathinfo($entry, PATHINFO_EXTENSION);
            $allowed_ext = ["txt", "pdf", "csv", "log"];
            if ($entry != "." && $entry != ".." && PATHINFO_FILENAME != '') {
                if (in_array($ext, $allowed_ext)) {
                    echo "<div class='m-3'><span id='file_$count' onclick='delFile(this.id)'
            data-file='/$working_dir$entry' class='btn btn-danger delete-file d-print-none'>
            <i class='fa fa-trash'></i></span>
            <a target='_blank' href='/$working_dir$entry' class='mx-3' >$entry</a></div>";
                }
            }
            $count++;
        }
    } else {
        echo "Error open dir: $working_dir";
    }
    closedir($handle);
}else{
    echo "var working_dir not set.";
}
