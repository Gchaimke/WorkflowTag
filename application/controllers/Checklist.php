<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklist extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $this->load->view('checklist/manage', $data);
        $this->load->view('footer');
    }

    // Validate and store registration data in database
    public function create()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('serial', 'Serial', 'trim|required|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('progress', 'Progress', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'Assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('qc', 'Qc', 'trim|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('checklist/create');
            $this->load->view('footer');
        } else {
            $data = array(
                'project' => $this->input->post('project'),
                'serial' => $this->input->post('serial'),
                'data' => $this->input->post('data'),
                'progress' => $this->input->post('progress'),
                'assembler' => $this->input->post('assembler'),
                'qc' => $this->input->post('qc'),
                'date' => $this->input->post('date')
            );
            $result = $this->Checklist_model->insertNewChecklist($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Checklist added Successfully !';
                // get data from model
                $data['checklists'] = $this->Checklist_model->getChecklists();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklist/manage', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Checklist already exist!';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklist/create', $data);
                $this->load->view('footer');
            }
        }
    }

    public function edit($sn = '', $pr = '')
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
