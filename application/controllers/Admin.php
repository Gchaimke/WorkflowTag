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
		create_new_tables($this);
	}

	function backupDB()
	{
		$working_dir = 'Uploads/Backups/';
		define('BACKUP_DIR', $working_dir);
		if (!file_exists(BACKUP_DIR)) {
			mkdir(BACKUP_DIR, 0770, true);
		}
		$this->load->dbutil();
		$backup = $this->dbutil->backup();
		$this->load->helper('file');
		$file = BACKUP_DIR . 'db-' . date("Y-m-d") . '.zip';
		$success = file_put_contents($file, $backup);
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
		$filesList = getFileList('application/logs/admin');
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

	function mange_uploads()
	{
		$data = array();
		$folder = $this->security->xss_clean($this->input->get('folder'));
		$data['folders'] = build_folder_view($folder);
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('admin/mange_uploads', $data);
		$this->load->view('footer');
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
		$fields = array(
			'status' => array(
				'type' => 'INT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'null' => TRUE,
			)
		);
		$table_name = 'projects';
		echo add_fields_to_table($this, $fields, $table_name) ? "Table: $table_name now up to date!" : "Table: $table_name is up to date!";
		// echo modify_field_table($this, "projects", "users", "clients") ? "<br>Field now up to date" : "<br>Field is up to date!";
		// echo remove_field_from_table($this, "clients", "users") ? "<br>Field is removed!" : "<br>Field is not exists!";
		// echo $this->Admin_model->add_checklist_client_id();
	}

	function add_checklist_version()
	{
		$clients = $this->Clients_model->getClients();
		foreach ($clients as $client) {
			$projects = $this->Projects_model->getProjects($client['name']);
			foreach ($projects as $project) {
				$folder = "Uploads/{$client['name']}/{$project['project']}/";
				if (!file_exists($folder)) {
					mkdir($folder, 0770, true);
				}

				if (!file_exists($folder . "rev_1.txt")) {
					$assembly = fopen($folder . "rev_1.txt", "w");
					fwrite($assembly, $project['data']);
					fclose($assembly);
					printf("new version created %s checklist: %s<br>", $client['name'], $project['project']);
				}
				$project["checklist_version"] = $folder . "rev_1.txt";
				$this->Projects_model->editProject($project);
			}
		}
	}
}
