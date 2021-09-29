<?php
class Admin extends CI_Controller
{
	private $languages;
	private $user;
	private $system_models = array(
		'Admin' => 'settings',
		'Clients' => 'clients',
		'Checklists_notes' => 'checklists_notes',
		'Production' => 'checklists',
		'Qc' => 'qc_forms',
		'Rma' => 'rma_forms',
		'Projects' => 'projects',
		'Users' => 'users',
	);
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('admin');
		// Load models
		foreach ($this->system_models as $model => $table) {
			$this->load->model($model . '_model');
		}
		if (isset($this->session->userdata['logged_in'])) {
			$this->user = $this->session->userdata['logged_in'];
			$this->lang->load('main', $this->user['language']);
			$this->languages = array("english", "hebrew");
		} else {
			header("location: /users/login");
			exit('User not logedin');
		}
		$this->load->library('pagination');
	}

	function view_page($page_name = 'admin', $page_data = '', $menu_parameters = '')
	{
		$this->load->view('header');
		$this->load->view('main_menu', $menu_parameters);
		$this->load->view($page_name, $page_data);
		$this->load->view('footer');
	}

	function settings()
	{
		$data = array();
		$data['settings'] = '';
		$data['response'] = '';
		$this->load->view('header');
		$this->load->view('main_menu');
		$data = $this->Admin_model->getStatistic();
		if ($this->db->table_exists('settings')) {
			$data['settings'] = $this->Admin_model->getSettings();
		}
		$data['languages'] = $this->languages;
		$this->load->view('admin/settings', $data);
		$this->load->view('footer');
	}

	public function save_settings()
	{
		// Check validation for user input in SignUp form
		$this->form_validation->set_rules('roles', 'Roles', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$this->settings();
		} else {
			$data = array(
				'user_roles' => $this->input->post('user_roles'),
				'language' => $this->input->post('language'),
			);
			$this->Admin_model->save_settings($data);
			echo 'Settings saved successfully!';
		}
	}

	function create_tables()
	{
		$data = array();
		$data['response'] = '';
		foreach ($this->system_models as $model => $table) {
			$model_name = $model . '_model';
			if (!$this->db->table_exists($table)) {
				$this->$model_name->createDb();
				$data['response'] .= "$model_name Table: $table created!<br>" . PHP_EOL;
			} else {
				$data['response'] .= "$model_name Table: $table exists!<br>" . PHP_EOL;
			}
		}
		$data['settings'] = $this->Admin_model->getSettings();
		echo $data['response'];
	}

	function backupDB()
	{
		$working_dir = 'Uploads/Backups/';
		define('BACKUP_DIR', $working_dir);
		if (!file_exists(BACKUP_DIR)) {
			mkdir(BACKUP_DIR, 0770, true);
		}
		// Load the DB utility class
		$this->load->dbutil();
		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup();
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$file = BACKUP_DIR . 'db-' . date("Y-m-d") . '.zip';
		$success = file_put_contents($file, $backup);
		// Load the download helper and send the file to your desktop
		echo $success ? $file : 'Unable to save the file: ' . $file;
	}

	function manage_trash()
	{
		$type = $this->input->post_get('type');
		$project = 'Trash';
		$this->load->database();
		// init params
		$params = array();
		$config = array();
		$limit_per_page = 50;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		if ($type == 'checklist') {
			$total_records = $this->Admin_model->get_total($project);
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project);
		} else if ($type == 'qc') {
			$total_records = $this->Admin_model->get_total($project, 'qc_forms');
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'qc_forms');
		} else {
			$total_records = $this->Admin_model->get_total($project, 'rma_forms');
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'rma_forms');
		}
		if ($total_records > 0) {
			$params["results"] = $in_trash;
			$params["type"] = $type;
			$config['base_url'] = base_url() . 'admin/manage_trash';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;

			$this->pagination->initialize($config);

			// build paging links
			$params["links"] = $this->pagination->create_links();
		}
		$this->load->view('header');
		$this->load->view('main_menu', $params);
		$this->load->view('admin/manage_trash', $params);
		$this->load->view('footer');
	}

	public function restore_item()
	{
		$data = array(
			'id' =>  $this->input->post('id'),
			'serial' => $this->input->post('serial'),
			'project' => $this->input->post('project')
		);
		if ($this->input->post('type') == 'checklist') {
			$this->Admin_model->restore_from_trash($data);
		} else {
			$this->Admin_model->restore_from_trash($data, $this->input->post('type') . '_forms');
		}
	}

	public function delete_from_trash()
	{
		$id = $this->input->post('id');
		$serial = $this->input->post('serial');
		$type = $this->input->post('type');
		$data = array(
			'id' => $id,
			'type' => $type,
			'serial' => $serial
		);
		$this->delete($data);
	}

	public function delete_batch()
	{
		$data = array();
		if ($this->input->post()) {
			$ids = explode(",", $this->input->post('ids'));
			$type = $this->input->post('type');
			foreach ($ids as $cid) {
				$data = array(
					'id' => $cid,
					'type' => $type,
					'serial' => '0'
				);
				$this->delete($data);
			}
		}
	}

	private function delete($data)
	{
		if (ucfirst($data['type']) == 'Checklist') {
			$this->Admin_model->deleteChecklist($data['id']);
		} else {
			$this->Admin_model->deleteChecklist($data['id'], $data['type'] . '_forms');
		}
		admin_log("deleted " . $data['type'] . " from trash : " . $data['serial'], 3, $this->user['name']);
	}

	public function view_log()
	{
		if (!file_exists('application/logs/admin')) {
			mkdir('application/logs/admin', 0770, true);
		}
		$filesList = $this->getFileList('application/logs/admin');
		$reversedList = array_reverse($filesList);
		// init params
		$params = array();
		$config = array();
		$limit_per_page = 10;
		$start = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
		$total_records = count($filesList);
		if ($total_records > 0) {
			$params["results"] = array_slice($reversedList, $start, $limit_per_page);

			$config['base_url'] = base_url() . 'admin/view_log';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;
			$this->pagination->initialize($config);
			// build paging links
			$params["links"] = $this->pagination->create_links();
		}
		$this->load->view('header');
		$this->load->view('main_menu', $params);
		$this->load->view('admin/view_log', $params);
		$this->load->view('footer');
	}

	public function get_log()
	{
		$this->form_validation->set_rules('file', 'File', 'trim|xss_clean');
		echo file_get_contents(APPPATH . 'logs/admin/' . $this->input->post('file'));
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

	function human_filesize($bytes, $decimals = 2)
	{
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

	function mange_uploads()
	{
		$data = array();
		$folder = $this->security->xss_clean($this->input->get('folder'));
		$data['folders'] = $this->build_folder_view($folder);
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('admin/mange_uploads', $data);
		$this->load->view('footer');
	}

	function build_folder_view($dir = "Uploads")
	{
		if ($dir == '') {
			$dir = "Uploads";
		}
		$html_view = '';
		$dirlistR = $this->getFileList($dir);
		$dir = explode('/', $dir);  //string to array
		$last_dir = array_pop($dir);            //remove last element
		$dir = implode('/', $dir);  //array to string
		if ($dir != '') {
			$html_view .=  "<a href='?folder=$dir'>$dir/<a><b>" . $last_dir . "/</b><br>";
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
			if ($file['type'] != 'image/png' && $file['type'] != 'image/jpeg' && $file['type'] != 'image/jpg' && $file['type'] != 'dir') {
				continue;
			}

			if ($file['type'] == 'dir') {
				$subDir = $this->getFileList($file['name']);
				$count = count(array_filter($subDir, function ($x) {
					return $x['type'] != 'text/html';
				})); //count all files, filter html
				$html_view .= '<a class="btn btn-primary folder" href="?folder=' . $file['name'] .
					'" role="button"><i class="fa fa-folder"></i> ' .
					basename($file['name']) . ' (' .  $count . ')</a>';
			} else {
				$html_view .= "<tr>\n";
				$html_view .=  "<td class='td_file_manager'><a target='_blank' href=\"/{$file['name']}\"><img class='img-thumbnail' src=\"/{$file['name']}\"></a>" .
					"</td>\n"; //basename($file['name'])
				$html_view .=  "<td>" . $file['name'] . "</td>\n"; //basename($file['name'])
				$html_view .= "<td>{$file['type']}</td>\n";
				$html_view .= "<td>" . $this->human_filesize($file['size']) . "</td>\n";
				$html_view .= "<td>" . date('d/m/Y h:i:s', $file['lastmod']) . "</td>\n";
				$html_view .= "<td><span id='file_$count' data-file='/{$file['name']}' onclick='delFile(this.id)' class='btn btn-danger'>delete</span></td>";
				$html_view .= "</tr>\n";
			}
			$count++;
		}
		$html_view .= "</tbody>\n";
		$html_view .= "</table>\n\n";
		return $html_view;
	}

	function RemoveEmptySubFolders($path = 'Uploads', $msg = "")
	{
		$msg .= "Cleaning folder: " . $path . "<br>";
		$empty = true;
		foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
			$empty &= is_dir($file) && $this->RemoveEmptySubFolders($file);
		}
		echo $msg;
		return $empty && rmdir($path);
	}

	function upgrade_db()
	{
		//$this->Admin_model->remove_column('rma_forms','recive_pictures');
		$field_name = 'user_roles';
		$table_name = 'settings';
		$fields = array(
			$field_name => array(
				'type' => 'TEXT',
			)
		);
		if (!$this->db->field_exists($field_name, $table_name)) {
			$this->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		}else{
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'assembly';
		$table_name = 'projects';
		$fields = array(
			$field_name => array(
				'type' => 'VARCHAR',
                'constraint' => 500
			),
			'checklist_version' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            )
		);
		if (!$this->db->field_exists($field_name, $table_name)) {
			$this->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		}else{
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'version';
		$table_name = 'checklists';
		$fields = array(
			$field_name => array(
				'type' => 'VARCHAR',
                'constraint' => 50
			)
		);
		if (!$this->db->field_exists($field_name, $table_name)) {
			$this->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		}else{
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'language';
		$table_name = 'users';
		$fields = array(
			'language' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			),
			'projects' => array(
				'type' => 'VARCHAR',
				'constraint' => 60
			)
		);
		if (!$this->db->field_exists($field_name, $table_name)) {
			$this->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		}else{
			echo "$field_name exists in $table_name.";
		}
	}
}
