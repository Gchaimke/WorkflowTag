<?php
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Load model
		$this->load->model('Users_model');
	}
	function settings()
	{
		$data['settings'] = '';
		$data['response'] = '';
		$this->load->model('Admin_model');
		$this->load->view('header');
		$this->load->view('main_menu');
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

	function create()
	{
		$this->load->model('Admin_model');
		$data['response'] = '';
		if (!$this->db->table_exists('users')) {
			$this->Admin_model->createUsersDb();
			$data['response'] .= "Table 'users' created!<br>";
		} else {
			$data['response'] .= "Table 'users' exists!<br>";
		}
		if (!$this->db->table_exists('clients')) {
			$this->Admin_model->createClientsDb();
			$data['response'] .= "Table 'clients' created!<br>";
		} else {
			$data['response'] .= "Table 'clients' exists!<br>";
		}
		if (!$this->db->table_exists('checklists')) {
			$this->Admin_model->createChecklistDb();
			$data['response'] .= "Table 'checklists' created!<br>";
		} else {
			$data['response'] .= "Table 'checklists' exists!<br>";
		}
		if (!$this->db->table_exists('projects')) {
			$this->Admin_model->createProjectsDb();
			$data['response'] .= "Table 'projects' created!<br>";
		} else {
			$data['response'] .= "Table 'projects' exists!<br>";
		}
		if (!$this->db->table_exists('settings')) {
			$this->Admin_model->createSettingsDb();
			$data['settings'] = $this->Admin_model->getSettings();
			$data['response'] .= "Table 'settings' created!<br>";
		} else {
			$data['response'] .= "Table 'settings' exists!<br>";
			$data['settings'] = $this->Admin_model->getSettings();
		}		
		$this->load->view('/admin/settings', $data);
		return $data;
	}
}
