<?php
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        // Load model
        $this->load->model('Users_model');
    }
	function settings()
	{
		$this->load->view('header');
		$this->load->view('main_menu');

		if ($this->input->post('submit') != NULL) {
			$this->load->model('Settings_model');
			$data['response'] = $this->Settings_model->createUsersDb();
			$data['response'] += $this->Settings_model->createChecklistDb();
			$this->load->view('admin/settings',$data);
		}else{
			$data['response'] = '';
            // load view
            $this->load->view('admin/settings', $data);
		}
		$this->load->view('footer');
    }
}