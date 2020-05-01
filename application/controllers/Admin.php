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
		$this->load->view('header');
		$this->load->view('main_menu');
		$data['responseUsers'] = '';
		$data['responseChecklist'] = '';
		if ($this->input->post('submit') != NULL) {
			$this->load->model('Settings_model');
			if (!$this->db->table_exists('users')) {
				 $this->Settings_model->createUsersDb();
				 $data['responseUsers'] = "Table 'users' created!";
			}else{
				$data['responseUsers']="Table 'users' exists!";
			}
			if (!$this->db->table_exists('checklists')) {
				 $this->Settings_model->createChecklistDb();
				 $data['responseChecklist'] ="Table 'checklists' created!";
			}else{
				$data['responseChecklist']="Table 'checklists' exists!";
			}
			$this->load->view('admin/settings', $data);
		} else {
			// load view
			$this->load->view('admin/settings');
		}
		$this->load->view('footer');
	}
}
