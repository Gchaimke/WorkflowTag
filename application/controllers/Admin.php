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
		$this->load->model('Settings_model');
		$this->load->view('header');
		$this->load->view('main_menu');
		$data['response'] = '';
		$data['clients'] = '';
		if ($this->input->post('submit') != NULL) {
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
			if (!$this->db->table_exists('templates')) {
				$this->Settings_model->createTemplatesDb();
				$data['response'] .= "Table 'templates' created!<br>";
			} else {
				$data['response'] .= "Table 'templates' exists!<br>";
			}
			if (!$this->db->table_exists('settings')) {
				$this->Settings_model->createSettingsDb();
				$data['clients'] = $this->Settings_model->getClients();
				$data['response'] .= "Table 'settings' created!<br>";
			} else {
				$data['response'] .= "Table 'settings' exists!<br>";
				$data['clients'] = $this->Settings_model->getClients();
			}
			$this->load->view('admin/settings', $data);
		} else {
			// load view
			if ($this->db->table_exists('settings')) {
				$data['clients'] = $this->Settings_model->getClients();
			}
			$this->load->view('admin/settings', $data);
		}
		$this->load->view('footer');
	}
}
