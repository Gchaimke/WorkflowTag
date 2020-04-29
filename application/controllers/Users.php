<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
        // get data from model
        $data['users'] = $this->Main_model->getUsers();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('users/index', $data);
        $this->load->view('footer');
    }

    public function create()
    {
        $this->load->view('header');
        $this->load->view('main_menu');
        // get data from model
        $data['users'] = $this->Main_model->getUsers();

        if ($this->input->post('submit') != NULL) {
            // POST data
            $postData = $this->input->post();
            //load model
            $this->load->model('Main_model');
            // get data
            $data['response'] = $this->Main_model->insertNewuser($postData);
            // load view
            $this->load->view('users/create', $data);
        } else {
            $data['response'] = '';
            // load view
            $this->load->view('users/create', $data);
        }
        $this->load->view('footer');
    }

    public function edit()
    {
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('users/edit');
        $this->load->view('footer');
    }
}
