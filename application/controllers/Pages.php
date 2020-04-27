<?php
class Pages extends CI_Controller
{
	public function index()
	{
		$this->load->view('header');
		$this->load->view('pages/home');
		$this->load->view('footer');
	}

	function view($page = 'home')
	{
		if (!file_exists('application/views/pages/' . $page . '.php')) {
			show_404();
		}
		$this->load->view('header');
		$this->load->view('pages/' . $page);
		$this->load->view('footer');
	}
}
