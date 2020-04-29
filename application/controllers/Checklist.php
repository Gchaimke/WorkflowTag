<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklist extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        // Load model
        $this->load->model('Main_model');
        //$this->Main_model->dbCreate();
    }

    public function index()
    {
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('checklist/index');
        $this->load->view('footer');
    }

    public function create($pr = 'Flex2', $sn = 'FL-0420-001')
    {

        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $this->load->view('header');
        $this->load->view('main_menu');
        if ($this->input->post('submit') != NULL) {
            // POST data
            $data['pr'] = $this->input->post('pr');
            $data['sn'] = $this->input->post('sn');
            $this->load->view('checklist/create', $data);
        } else {
            // load view
            $data['pr'] = $pr;
            $data['sn'] = $sn;
            $this->load->view('checklist/create', $data);
        }
        $this->load->view('footer');
    }

    public function edit($id = 1)
    {
        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('checklist/edit', $data);
        $this->load->view('footer');
    }

    public function delete($id = 1)
    {
        $this->load->view('header');
        $this->load->view('main_menu');
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
