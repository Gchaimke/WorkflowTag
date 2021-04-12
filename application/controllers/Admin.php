<?php
class Admin extends CI_Controller
{
	private $system_models = array(
		'Admin' => 'settings',
		'Clients' => 'clients',
		'Checklists_notes' => 'checklists_notes',
		'Production' => 'checklists',
		'Qc' => 'qc_forms',
		'Rma' => 'rma_forms',
		'Templates' => 'projects',
		'Users' => 'users',
	);
	public function __construct()
	{
		parent::__construct();
		// Load models
		foreach ($this->system_models as $model => $table) {
			$this->load->model($model . '_model');
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
				'roles' => $this->input->post('roles')
			);
			$this->Admin_model->save_settings($data);
			echo 'Settings saved successfully!';
		}
	}

	function create()
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
		$kind = $this->input->post_get('kind');
		$project = 'Trash';
		$this->load->database();
		// init params
		$params = array();
		$config = array();
		$limit_per_page = 30;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		if ($kind == 'checklist') {
			$total_records = $this->Admin_model->get_total($project);
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project);
		} else if($kind == 'qc') {
			$total_records = $this->Admin_model->get_total($project, 'qc_forms');
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'qc_forms');
		}else {
			$total_records = $this->Admin_model->get_total($project, 'rma_forms');
			$in_trash = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'rma_forms');
		}
		if ($total_records > 0) {
			$params["results"] = $in_trash;
			$params["kind"] = $kind;
			$config['base_url'] = base_url() . 'admin/manage_trash';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;
			$config["uri_segment"] = 3;

			$config['full_tag_open'] = '<ul class="pagination right">';
			$config['full_tag_close'] = '</ul>';

			$config['cur_tag_open'] = '<li class="page-item active "><a class="page-link">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page-item num-link">';
			$config['num_tag_close'] = '</li>';

			$config['first_tag_open'] = '<li class="page-item num-link">';
			$config['first_tag_close'] = '</li>';

			$config['last_tag_open'] = '<li class="page-item num-link">';
			$config['last_tag_close'] = '</li>';

			$config['next_tag_open'] = '<li class="page-item num-link">';
			$config['next_tag_close'] = '</li>';

			$config['prev_tag_open'] = '<li class="page-item num-link">';
			$config['prev_tag_close'] = '</li>';

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
		$this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
		$this->form_validation->set_rules('serial', 'Serial', 'trim|xss_clean');
		$this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
		$this->form_validation->set_rules('kind', 'kind', 'trim|xss_clean');
		$data = array(
			'id' =>  $this->input->post('id'),
			'serial' => $this->input->post('serial'),
			'project' => $this->input->post('project')
		);
		if ($this->input->post('kind') == 'Checklist') {
			$this->Admin_model->restore_from_trash($data);
		} else {
			$this->Admin_model->restore_from_trash($data, $this->input->post('kind').'_forms');
		}
	}

	public function delete_from_trash()
	{
		$this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
		$this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
		$this->form_validation->set_rules('serial', 'Serial', 'trim|xss_clean');
		$this->form_validation->set_rules('kind', 'kind', 'trim|xss_clean');
		$id = $this->input->post('id');
		$serial = $this->input->post('serial');
		$kind = $this->input->post('kind');
		$data = array(
			'id' => $id,
			'kind' => $kind,
			'serial' => $serial
		);
		$this->delete($data);
	}

	public function delete_batch()
	{
		$data = array();
		if ($this->input->post()) {
			$ids = explode(",", $this->input->post('ids'));
			$kind = $this->input->post('kind');
			foreach ($ids as $cid) {
				$data = array(
					'id' => $cid,
					'kind' => $kind,
					'serial' => '0'
				);
				$this->delete($data);
			}
		}
	}

	private function delete($data)
	{
		if (ucfirst($data['kind']) == 'Checklist') {
			$this->Admin_model->deleteChecklist($data['id']);
		} else {
			$this->Admin_model->deleteChecklist($data['id'], $data['kind'].'_forms');
		}
		$this->log_data("deleted " . $data['kind'] . " from trash : " . $data['serial'], 3);
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
		$limit_per_page = 50;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = count($filesList);
		if ($total_records > 0) {
			$params["results"] = array_slice($reversedList, $start_index, $limit_per_page);

			$config['base_url'] = base_url() . 'admin/view_log';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;
			$config["uri_segment"] = 3;

			$config['full_tag_open'] = '<ul class="pagination right">';
			$config['full_tag_close'] = '</ul>';

			$config['cur_tag_open'] = '<li class="page-item active "><a class="page-link">';
			$config['cur_tag_close'] = '</a></li>';

			$config['num_tag_open'] = '<li class="page-item num-link">';
			$config['num_tag_close'] = '</li>';

			$config['first_tag_open'] = '<li class="page-item num-link">';
			$config['first_tag_close'] = '</li>';

			$config['last_tag_open'] = '<li class="page-item num-link">';
			$config['last_tag_close'] = '</li>';

			$config['next_tag_open'] = '<li class="page-item num-link">';
			$config['next_tag_close'] = '</li>';

			$config['prev_tag_open'] = '<li class="page-item num-link">';
			$config['prev_tag_close'] = '</li>';

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
		$retval = [];
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

	function log_data($msg, $level = 0)
	{
		if (!file_exists('application/logs/admin')) {
			mkdir('application/logs/admin', 0770, true);
		}
		$level_arr = array('INFO', 'CREATE', 'TRASH', 'DELETE');
		$user = $this->session->userdata['logged_in']['name'];
		$log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
		$fp = fopen($log_file, 'a'); //opens file in append mode  
		fwrite($fp, $level_arr[$level] . " - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
		fclose($fp);
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
				$html_view .= '<td><span id="/' . $file['name'] . '" onclick="delFile(this.id)" class="btn btn-danger delete-photo">delete</span></td>';
				$html_view .= "</tr>\n";
			}
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
}
