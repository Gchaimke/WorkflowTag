<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        // Load model
        $this->load->model('Users_model');
    }

    public function index()
    {
        // get data from model
        $data['users'] = $this->Users_model->getUsers();
        $this->load->view('header');
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('users/index', $data);
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
            $data['response'] = $this->Users_model->insertNewuser($postData);
            // load view
            $this->load->view('users/create', $data);
        } else {
            $data['response'] = '';
            // load view
            $this->load->view('users/create', $data);
        }
        $this->load->view('footer');
    }

    public function edit($id='')
    {
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('users/edit');
        $this->load->view('footer');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Users_model->deleteUser($id);
    }
}
