<?php
define('UPLOAD_DIR', 'Uploads/');
if (isset($this->session->userdata['logged_in'])) {
    if ($this->session->userdata['logged_in']['role'] != "Admin") {
        header("location: /");
    }
}
?>

<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">File Manager</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }

        if (isset($_GET['folder']) && $_GET['folder'] != '') {
            $dir = $_GET['folder'];
        }

        if (isset($_GET['r'])) {
            $r = $_GET['r'];
        } else {
            $r = false;
        }

        $dirlistR = getFileList($dir, $r);
        // output file list as HTML table
        echo "<table class='table files'";
        echo "<thead>\n";
        echo "<tr><th>image</th><th>Path</th><th>Type</th><th>Size</th><th>Last Modified</th><th>Delete</th></tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";

        $dir = explode('/', $dir);
        array_pop($dir);
        $dir = implode('/', $dir);
        echo  "<a href='?folder=$dir'>$dir /<a>\n";


        foreach ($dirlistR as $file) {
            if ($file['type'] != 'image/png' && $file['type'] != 'image/jpeg' && $file['type'] != 'image/jpg' && $file['type'] != 'dir') {
                continue;
            }

            if ($file['type'] == 'dir') {
                $subDir = getFileList($file['name']);
                echo '<a class="btn btn-primary folder" href="?folder=' .
                    $file['name'] . '" role="button"><i class="fa fa-folder"></i> ',
                    basename($file['name']),
                    ' (' . count($subDir) . ')</a>';
            } else {
                echo "<tr>\n";
                echo  "<td class='td_file_manager'><a target='_blank' href=\"/{$file['name']}\"><img class='img-thumbnail' src=\"/{$file['name']}\"></a>",
                  "</td>\n"; //basename($file['name'])
                echo  "<td>", $file['name'], "</td>\n"; //basename($file['name'])
                echo "<td>{$file['type']}</td>\n";
                echo "<td>" . human_filesize($file['size']) . "</td>\n";
                echo "<td>", date('d/m/Y h:i:s', $file['lastmod']), "</td>\n";
                echo '<td><span id="/' . $file['name'] . '" onclick="delFile(this.id)" class="btn btn-danger delete-photo">delete</span></td>';
                echo "</tr>\n";
            }
        }
        echo "</tbody>\n";
        echo "</table>\n\n";

        ?>
    </div>
</main>

<script>
</script>

<?php

function getFileList($dir, $recurse = FALSE)
{
    $retval = [];

    // add trailing slash if missing
    if (substr($dir, -1) != "/") {
        $dir .= "/";
    }

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
    while (FALSE !== ($entry = $d->read())) {
        // skip hidden files
        if ($entry[0] == ".") continue;
        if (is_dir("{$dir}{$entry}")) {
            $retval[] = [
                'name' => "{$dir}{$entry}",
                'type' => filetype("{$dir}{$entry}"),
                'size' => 0,
                'lastmod' => filemtime("{$dir}{$entry}")
            ];
            if ($recurse && is_readable("{$dir}{$entry}/")) {
                $retval = array_merge($retval, getFileList("{$dir}{$entry}/", TRUE));
            }
        } elseif (is_readable("{$dir}{$entry}")) {
            $retval[] = [
                'name' => "{$dir}{$entry}",
                'type' => mime_content_type("{$dir}{$entry}"),
                'size' => filesize("{$dir}{$entry}"),
                'lastmod' => filemtime("{$dir}{$entry}")
            ];
        }
    }
    $d->close();

    return $retval;
}

function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>