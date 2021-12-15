<?php

function build_folder_view($dir = "Uploads")
{
	if ($dir == '' || strpos($dir, ".") !== false) { //security directory traversale
		$dir = "Uploads";
	}
	$html_view = '';
	$dirlistR = getFileList($dir);
	$dir = explode('/', $dir);  //string to array
	$last_dir = array_pop($dir);            //remove last element
	$dir = implode('/', $dir);  //array to string
	if ($dir != '') {
		$html_view .=  "<nav class='breadcrumbs'><i class=\"fa fa-folder\"></i> /<a href='?folder=$dir'>$dir<a>/<b>" . $last_dir . "/</b><br></nav>";
	}
	// output file list as HTML table
	$html_view .= "<table class='table files'";
	$html_view .= "<thead>\n";
	$html_view .= "<tr><th>image</th><th>Path</th><th>Type</th><th>Size</th><th>Last Modified</th><th>Delete</th></tr>\n";
	$html_view .= "</thead>\n";
	$html_view .= "<tbody>\n";
	$count = 1;
	foreach ($dirlistR as $file) {
		//filter file types
		$is_image = false;
		if ($file['type'] == 'image/png' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/jpg' || $file['type'] == 'image/gif') {
			$is_image = true;
		}

		$html_view .= "<tr>\n";
		$html_view .=  "<td class='td_file_manager'>";
		if ($is_image) {
			$html_view .= "<a target='_blank' href=\"/{$file['name']}\"><img class='img-thumbnail' src=\"/{$file['name']}\"></a>";
		} else if ($file['type'] == 'dir') {
			$subDir = getFileList($file['name']);
			$count = count(array_filter($subDir, function ($x) {
				return $x['type'] != 'text/html';
			}));
			$html_view .= "<a class='file_manager_folder' href=\"?folder={$file['name']}\"><i class=\"fa fa-folder\"></i> " . basename($file['name']) . "($count)</a>";
		} else {
			$html_view .= var_dump($file);
			$html_view .= "<h2><a target='_blank' href=\"/{$file['name']}\">{$file['type']}</a></h2>";
		}
		$html_view .= "</td>\n";
		$html_view .=  "<td>" . $file['name'] . "</td>\n"; //basename($file['name'])
		$html_view .= "<td>{$file['type']}</td>\n";
		$html_view .= "<td>" . human_filesize($file['size']) . "</td>\n";
		$html_view .= "<td>" . date('d/m/Y h:i:s', $file['lastmod']) . "</td>\n";
		$html_view .= "<td>";
		if ($file['type'] != 'dir') {
			$html_view .= "<span id='file_$count' data-file='/{$file['name']}' onclick='delFile(this.id)' class='btn btn-danger'>delete</span></td>";
		}

		$html_view .= "</tr>\n";
		$count++;
	}
	$html_view .= "</tbody>\n";
	$html_view .= "</table>\n\n";
	return $html_view;
}

function human_filesize($bytes, $decimals = 2)
{
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}


function getFileList($dir, $recurse = FALSE)
{
	$files = [];
	$patterns[0] = '/\:/';
	$patterns[1] = '/\./';
	$dir = preg_replace($patterns, '',  $dir);
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
			$files[] = [
				'name' => "{$dir}{$entry}",
				'type' => filetype("{$dir}{$entry}"),
				'size' => 0,
				'lastmod' => filemtime("{$dir}{$entry}")
			];
			if ($recurse && is_readable("{$dir}{$entry}/")) {
				$files = array_merge($files, getFileList("{$dir}{$entry}/", TRUE));
			}
		} elseif (is_readable("{$dir}{$entry}")) {
			$files[] = [
				'name' => "{$dir}{$entry}",
				'type' => mime_content_type("{$dir}{$entry}"),
				'size' => filesize("{$dir}{$entry}"),
				'lastmod' => filemtime("{$dir}{$entry}")
			];
		}
	}
	$d->close();

	$lastmod = array_column($files, 'lastmod');

	array_multisort($lastmod, SORT_ASC, $files);
	return $files;
}

function admin_log($msg, $level = 0, $user)
{
	if (!file_exists('application/logs/admin')) {
		mkdir('application/logs/admin', 0770, true);
	}
	$level_arr = array('INFO', 'CREATE', 'TRASH', 'DELETE');
	$log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
	$fp = fopen($log_file, 'a'); //opens file in append mode  
	fwrite($fp, $level_arr[$level] . " - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
	fclose($fp);
}

function create_new_tables($controller)
{
	$system_models = array(
		'Admin' => 'settings',
		'Clients' => 'clients',
		'Checklists_notes' => 'checklists_notes',
		'Production' => 'checklists',
		'Qc' => 'qc_forms',
		'Rma' => 'rma_forms',
		'Projects' => 'projects',
		'Users' => 'users',
	);

	foreach ($system_models as $model => $table) {
		$controller->load->model($model . '_model');
	}
	$data = array();
	$data['response'] = '';
	foreach ($system_models as $model => $table) {
		$model_name = $model . '_model';
		if (!$controller->db->table_exists($table)) {
			$controller->$model_name->createDb();
			$data['response'] .= "$model_name Table: $table created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "$model_name Table: $table exists!<br>" . PHP_EOL;
		}
	}
	echo $data['response'];
}

function add_fields_to_table($controller, $fields, $table_name)
{
	$first_field_name = array_key_first($fields);
	if (!$controller->db->field_exists($first_field_name, $table_name)) {
		$controller->load->dbforge();
		$controller->dbforge->add_column($table_name, $fields);
		return true;
	} else {
		return false;
	}
}

function modify_field_table($controller, $old_field_name, $new_field_name, $table_name)
{
	if ($controller->db->field_exists($old_field_name, $table_name)) {
		$old_field_meta = $controller->db->field_data($table_name);
		foreach ($old_field_meta as $field) {
			if ($field->name == $old_field_name) {
				$field = array(
					$old_field_name => array(
						'name' => $new_field_name,
						'type' => $field->type,
						'constraint' => $field->max_length,
					),
				);
				$controller->load->dbforge();
				$controller->dbforge->modify_column($table_name, $field);
				return true;
			}
		}
	} else {
		return false;
	}
}

function remove_field_from_table($controller, $field_name, $table_name)
{
	if ($controller->db->field_exists($field_name, $table_name)) {
		$controller->load->dbforge();
		$controller->dbforge->drop_column($table_name, $field_name);
		return true;
	} else {
		return false;
	}
}

function sort_users_by_role($a, $b)
{
	return strcmp($a["role"], $b["role"]);
}
