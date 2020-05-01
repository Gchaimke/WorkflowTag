<?php
class Pages extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Settings_model');
    }
	public function index()
	{
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('pages/dashboard');
		$this->load->view('footer');
	}

	function view($page = 'home')
	{
		if (!file_exists('application/views/pages/' . $page . '.php')) {
			show_404();
		}
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('pages/' . $page);
		$this->load->view('footer');
	}
}
