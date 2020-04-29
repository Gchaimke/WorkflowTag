<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklist extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('checklist_menu');
        $this->load->view('checklist/index');
        $this->load->view('footer');
    }

    public function create()
    {
        $this->load->view('header');
        $this->load->view('checklist/create');
        $this->load->view('footer');
    }

    public function edit($id = 1)
    {
        $this->load->view('header');
        $this->load->view('checklist/edit');
        $this->load->view('footer');
    }

    public function update($id = 1)
    {
        $this->load->view('header');
        $this->load->view('checklist/update');
        $this->load->view('footer');
    }

    public function delete($id = 1)
    {
        $this->load->view('header');
        $this->load->view('checklist/delete');
        $this->load->view('footer');
    }

    public function save_photo()
    {
        $this->load->view('checklist/save_photo');
    }

    public function delete_photo()
    {
        $this->load->view('checklist/delete_photo');
    }

    public function save_page()
    {
        $this->load->view('checklist/save_page');
    }
}
