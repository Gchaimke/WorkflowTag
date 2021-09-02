<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends CI_Controller
{
    private $user;
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Projects_model');
        $this->load->model('Clients_model');
        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $this->lang->load('main', $this->user['language']);
            $this->languages = array("english", "hebrew");
        } else {
            header("location: /users/login");
            exit('User not logedin');
        }
    }

    public function index($data = '')
    {
        $data = array();
        // get data from model
        $data['projects'] = $this->Projects_model->getProjects();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('projects/manage_projects', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_project()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean|is_unique[projects.project]');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|xss_clean');
        $this->form_validation->set_rules('scans', 'Scans', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['js_to_load'] = array("add_project.js");
            $data['clients'] = $this->Clients_model->getClients();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('projects/add_project', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'data' => $this->input->post('data'),
                'template' => $this->input->post('template'),
                'restart_serial' => $this->input->post('restart_serial'),
                'scans' => $this->input->post('scans')
            );
            $result = $this->Projects_model->addProjects($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Project added Successfully !';
                $this->index($data);
            } else {
                $data['js_to_load'] = array("add_project.js");
                $data['message_display'] = 'Project already exist!';
                $data['clients'] = $this->Clients_model->getClients();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('projects/add_project', $data);
                $this->load->view('footer');
            }
        }
    }

    public function edit_project($id = '')
    {
        $data = array();
        // Check validation for user input in form
        $data['project'] =  $this->Projects_model->getProject($id)[0];

        $this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
        if ($this->form_validation->run() != FALSE) {
            $sql = array(
                'id' => $this->input->post('id'),
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'data' => $this->input->post('data'),
                'template' => $this->input->post('template'),
                'restart_serial' => $this->input->post('restart_serial'),
                'scans' => $this->input->post('scans')
            );
            if ($data['project']['project'] == $sql['project']) {
                unset($sql['project']);
            }
            $data['message_display'] = $this->Projects_model->editTemplate($sql);
            $data['message_display'] .= ' Project edited Successfully !';
        }

        $data['js_to_load'] = array("add_project.js");
        $data['clients'] = $this->Clients_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('projects/edit_project', $data);
        $this->load->view('footer');
        //$this->index($data);
    }

    public function delete_project()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role == "Admin") {
            $id = $_POST['id'];
            $this->Projects_model->deleteTemplate($id);
        }
    }
}
