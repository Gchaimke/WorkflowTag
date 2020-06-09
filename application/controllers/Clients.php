<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Clients_model');
    }

    public function index($msg = '')
    {
        $data = array();
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        // get data from model
        $data['clients'] = $this->Clients_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('clients/manage', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function create()
    {
        $msg = array();
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('projects', 'Projects', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('clients/create');
            $this->load->view('footer');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'projects' => $this->input->post('projects')
            );
            $result = $this->Clients_model->addClient($data);
            if ($result == TRUE) {
                $msg = 'Client added Successfully !';
                $this->index($msg);
            } else {
                $msg['message_display'] = 'Client already exist!';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('clients/create', $msg);
                $this->load->view('footer');
            }
        }
    }

    public function edit($id = '',$msg='')
    {
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        $data = array();
        // Check validation for user input in form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('projects', 'Projects', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $sql = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'projects' => $this->input->post('projects')
            );
            $this->Clients_model->editClient($sql);
            $data['message_display'] = ' Client updated Successfully !';
        }
        $data['clients'] = $this->Clients_model->getClients($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('clients/edit', $data);
        $this->load->view('footer');
    }

    public function logo_upload($id = '')
    {
        define('UPLOAD_DIR', 'Uploads/');
        $data = array();
        $config = array(
            'upload_path' => UPLOAD_DIR,
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
            //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            //'max_height' => "768",
            //'max_width' => "1024"
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('logo')) {
            $data['message_display'] = array('upload_data' => $this->upload->data());
            $this->edit($id, $data);
        } else {
            $data['message_display'] = array('error' => $this->upload->display_errors());
            $this->edit($id, $data);
        }
    }

    public function delete()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role == "Admin") {
            $id = $_POST['id'];
            $this->Clients_model->deleteClient($id);
        }
    }
}
