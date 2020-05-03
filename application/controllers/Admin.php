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
		$this->load->model('Settings_model');
		$this->load->view('header');
		$this->load->view('main_menu');
		if ($this->input->post('submit') != NULL) {
			$data['response'] = 'Settings saved!';
			$data['settings'] = $this->Settings_model->getSettings();
			$this->load->view('admin/settings', $data);
		} else {
			// load view
			if ($this->db->table_exists('settings')) {
				$data['settings'] = $this->Settings_model->getSettings();
			}
			$this->load->view('admin/settings', $data);
		}
		$this->load->view('footer');
	}

	function create()
	{
		$this->load->model('Settings_model');
		$data['response'] = '';
		if (!$this->db->table_exists('users')) {
			$this->Settings_model->createUsersDb();
			$data['response'] .= "Table 'users' created!<br>";
		} else {
			$data['response'] .= "Table 'users' exists!<br>";
		}
		if (!$this->db->table_exists('checklists')) {
			$this->Settings_model->createChecklistDb();
			$data['response'] .= "Table 'checklists' created!<br>";
		} else {
			$data['response'] .= "Table 'checklists' exists!<br>";
		}
		if (!$this->db->table_exists('projects')) {
			$this->Settings_model->createProjectsDb();
			$data['response'] .= "Table 'projects' created!<br>";
		} else {
			$data['response'] .= "Table 'projects' exists!<br>";
		}
		if (!$this->db->table_exists('settings')) {
			$this->Settings_model->createSettingsDb();
			$data['settings'] = $this->Settings_model->getSettings();
			$data['response'] .= "Table 'settings' created!<br>";
		} else {
			$data['response'] .= "Table 'settings' exists!<br>";
			$data['settings'] = $this->Settings_model->getSettings();
		}
		$this->load->view('/admin/settings', $data);
		return $data;
	}
}
