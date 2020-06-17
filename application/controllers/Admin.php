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
		if ($this->input->post('submit') != NULL) {
			$data['response'] = 'Settings saved!';
			$data['settings'] = $this->Admin_model->getSettings();
			$this->load->view('admin/settings', $data);
		} else {
			// load view
			if ($this->db->table_exists('settings')) {
				$data['settings'] = $this->Admin_model->getSettings();
			}
			$this->load->view('admin/settings', $data);
		}
		$this->load->view('footer');
	}

	function mange_uploads($dir="Uploads")
	{
		$data = array();
		$data['dir'] = $dir;
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('admin/mange_uploads', $data );
		$this->load->view('footer');
	}

	function create()
	{
		$data = array();
		$data['response'] = '';
		if (!$this->db->table_exists('users')) {
			$this->Admin_model->createUsersDb();
			$data['response'] .= "Table 'users' created!" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'users' exists!" . PHP_EOL;
		}
		if (!$this->db->table_exists('clients')) {
			$this->Admin_model->createClientsDb();
			$data['response'] .= "Table 'clients' created!" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'clients' exists!" . PHP_EOL;
		}
		if (!$this->db->table_exists('checklists')) {
			$this->Admin_model->createChecklistDb();
			$data['response'] .= "Table 'checklists' created!" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'checklists' exists!" . PHP_EOL;
		}
		if (!$this->db->table_exists('projects')) {
			$this->Admin_model->createProjectsDb();
			$data['response'] .= "Table 'projects' created!" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'projects' exists!" . PHP_EOL;
		}
		if (!$this->db->table_exists('settings')) {
			$this->Admin_model->createSettingsDb();
			$data['settings'] = $this->Admin_model->getSettings();
			$data['response'] .= "Table 'settings' created!" . PHP_EOL;
		} else {
			$data['response'] .= "Table 'settings' exists!" . PHP_EOL;
			$data['settings'] = $this->Admin_model->getSettings();
		}
		echo $data['response'];
	}

	function manage_trash(){
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
        $id = $_POST['id'];
        $this->Admin_model->deleteChecklist($id);
	}
	
	public function view_log(){
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
        $this->load->view('admin/view_log', $params);
        $this->load->view('footer');
	}
}
