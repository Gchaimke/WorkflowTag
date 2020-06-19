<?php
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Load model
		$this->load->model('Users_model');
		$this->load->model('Admin_model');
		$this->load->library('pagination');
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

	function mange_uploads($dir = "Uploads")
	{
		$data = array();
		$data['dir'] = $dir;
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('admin/mange_uploads', $data);
		$this->load->view('footer');
	}

	function create()
	{
		$data = array();
		$data['response'] = '';
		if (!$this->db->table_exists('users')) {
			$this->Admin_model->createUsersDb();
			$data['response'] .= "Table 'users' created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'users' exists!<br>" . PHP_EOL;
		}
		if (!$this->db->table_exists('clients')) {
			$this->Admin_model->createClientsDb();
			$data['response'] .= "Table 'clients' created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'clients' exists!<br>" . PHP_EOL;
		}
		if (!$this->db->table_exists('checklists')) {
			$this->Admin_model->createChecklistDb();
			$data['response'] .= "Table 'checklists' created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'checklists' exists!<br>" . PHP_EOL;
		}
		if (!$this->db->table_exists('projects')) {
			$this->Admin_model->createProjectsDb();
			$data['response'] .= "Table 'projects' created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'projects' exists!<br>" . PHP_EOL;
		}
		if (!$this->db->table_exists('settings')) {
			$this->Admin_model->createSettingsDb();
			$data['settings'] = $this->Admin_model->getSettings();
			$data['response'] .= "Table 'settings' created!<br>" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'settings' exists!<br>" . PHP_EOL;
			$data['settings'] = $this->Admin_model->getSettings();
		}
		echo $data['response'];
	}

	function manage_trash()
	{
		$project = 'Trash';
		$this->load->database();
		// init params
		$params = array();
		$config = array();
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->Admin_model->get_total($project);
		if ($total_records > 0) {
			$params["results"] = $this->Admin_model->get_current_checklists_records($limit_per_page, $start_index, $project);

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

	public function restoreChecklist()
	{
		$this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
		$this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');

		$data = array(
			'id' =>  $this->input->post('id'),
			'project' => $this->input->post('project')
		);
		$this->Admin_model->restore_from_trash($data);
	}

	public function delete_from_trash()
	{
		$this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
		$this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
		$this->form_validation->set_rules('serial', 'Serial', 'trim|xss_clean');
		$id = $this->input->post('id');
		$project = $this->input->post('project');
		$serial = $this->input->post('serial');
		$this->Admin_model->deleteChecklist($id);
		$this->log_data("deleted from '$project' checklist '$serial'",3);
	}

	public function view_log()
	{
		if (!file_exists('application/logs/admin')) {
			mkdir('application/logs/admin', 0770, true);
		}
		$dirlistR = $this->getFileList('application/logs/admin');
		// init params
		$params = array();
		$config = array();
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = count($dirlistR);
		if ($total_records > 0) {
			$params["results"] = array_slice($dirlistR, $start_index, $limit_per_page);

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

    public function log_data($msg,$level=0)
    {
		if (!file_exists('application/logs/admin')) {
			mkdir('application/logs/admin', 0770, true);
		}
		$level_arr = array('INFO','CREATE','TRASH','DELETE');
        $user = $this->session->userdata['logged_in']['name'];
        $log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
        $fp = fopen($log_file, 'a'); //opens file in append mode  
        fwrite($fp, $level_arr[$level]." - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
        fclose($fp);
    }
}
