<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklists extends CI_Controller
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
        $this->load->view('checklists/manage_checklists', $data);
        $this->load->view('footer');
    }

    public function manage_templates()
    {
        // get data from model
        $data['templates'] = $this->Checklist_model->getTemplates();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('checklists/manage_templates', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_checklist()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|required|xss_clean');
        $this->form_validation->set_rules('serial', 'Serial', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['projects'] =  $this->Checklist_model->getProjects();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('checklists/add_checklist',$data);
            $this->load->view('footer');
        } else {
            $data = array(
                'project' => $this->input->post('project'),
                'template' => $this->input->post('template'),
                'serial' => $this->input->post('serial'),
                'date' => $this->input->post('date')
            );
            $result = $this->Checklist_model->insertNewChecklist($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Checklist added Successfully !';
                // get data from model
                $data['checklists'] = $this->Checklist_model->getChecklists();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/manage_checklists', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Checklist already exist!';
                $data['projects'] =  $this->Checklist_model->getProjects();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/add_checklist', $data);
                $this->load->view('footer');
            }
        }
    }

    // Validate and store checklist data in database
    public function add_template()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|required|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['projects'] =  $this->Checklist_model->getProjects();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('checklists/add_template',$data);
            $this->load->view('footer');
        } else {
            $data = array(
                'project' => $this->input->post('project'),
                'template' => $this->input->post('template'),
                'data' => $this->input->post('data')
            );
            $result = $this->Checklist_model->addTemplate($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Template added Successfully !';
                // get data from model
                $data['templates'] = $this->Checklist_model->getTemplates();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/manage_templates', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Template already exist!';
                $data['projects'] =  $this->Checklist_model->getProjects();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/add_template', $data);
                $this->load->view('footer');
            }
        }
    }

    public function edit_checklist($pr = '', $sn = '')
    {
        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $this->load->view('header');
        $this->load->view('main_menu');
        // load view
        $data['pr'] = $pr;
        $data['sn'] = $sn;
        $this->load->view('checklists/edit_checklist', $data);
        $this->load->view('footer');
    }

    public function edit_template($id = '')
    {
        $data['projects'] =  $this->Checklist_model->getProjects();
        $this->load->view('header');
        $this->load->view('main_menu');
        // load view
        $this->load->view('checklists/edit_template', $data);
        $this->load->view('footer');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Checklist_model->deleteChecklist($id);
    }

    public function save_photo()
    {
        $this->load->view('checklists/save_photo');
    }

    public function delete_photo()
    {
        $this->load->view('checklists/delete_photo');
    }

    public function save_page()
    {
        $this->load->view('checklists/save_page');
    }
}
