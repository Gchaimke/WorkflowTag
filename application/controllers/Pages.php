<?php
class Pages extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        // Load model
		$this->load->model('Admin_model');
    }
	public function index($data='')
	{
		$data = $this->Admin_model->getStatistic();
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('pages/dashboard', $data);
		$this->load->view('footer');
	}

	public function error($data='')
	{
		$this->load->view('header');
		$this->load->view('main_menu');
		$this->load->view('pages/error',$data);
		$this->load->view('footer');
	}
}
