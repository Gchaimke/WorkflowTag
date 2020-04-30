<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklist extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        // Load model
        $this->load->model('Checklist_model');
        //$this->load->model('Settings_model');
        //$this->Settings_model->createChecklistDb();
    }

    public function index()
    {
        // get data from model
        $data['checklists'] = $this->Checklist_model->getChecklists();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('checklist/index', $data);
        $this->load->view('footer');
    }

    public function create()
    {
        $this->load->view('header');
        $this->load->view('main_menu');
        if ($this->input->post('submit') != NULL) {
            // POST data
            $postData = $this->input->post();
            // get data
            $data['response'] = $this->Checklist_model->insertNewChecklist($postData);
            // load view
            $this->load->view('checklist/create', $data);
        } else {
            $data['response'] = '';
            // load view
            $this->load->view('checklist/create', $data);
        }
        $this->load->view('footer');
    }

    public function edit($sn = '',$pr='')
    {
        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $this->load->view('header');
        $this->load->view('main_menu');
        // load view
        $data['pr'] = $pr;
        $data['sn'] = $sn;
        $this->load->view('checklist/edit', $data);
        $this->load->view('footer');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Checklist_model->deleteChecklist($id);
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
