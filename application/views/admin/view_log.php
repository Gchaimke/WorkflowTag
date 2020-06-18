<?php
$project =  'Trash';
?>
<main role="main">
	<div class="jumbotron">
		<div class="container">
			<center>
				<h2 class="display-3">Manage Logs </h2>
			</center>
		</div>
	</div>
	<div class="container">
		<?php
		$dirlistR = getFileList('application/logs/admin');
		foreach ($dirlistR as $file) {
			if ($file['type'] != 'text/plain') {
				continue;
			}
			$log = urldecode( $file['name']);
			echo  '<a href="#" onclick="showLogFile(\''.$log.'\')">'.$log.'</a></br>'; //basename($file['name'])
		}
		?>
	</div>
	<div id='show-log' style='display:none;'>
		<div id="show-log-header">
			<div id="serial-header"></div>Click here to move<button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
		</div>
		<ul class="list-group list-group-flush">
		</ul>
	</div>
</main>
<script>
	function showLogFile(file) {
		$.post("/admin/get_log", {
			file: file
		}).done(function(o) {
			alert(o);
		});

	}
</script>
<?php
//echo file_get_contents( APPPATH . 'logs/admin/log-2020-06-18.php' ); 

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
?>