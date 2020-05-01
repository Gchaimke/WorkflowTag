<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $this->load->view('users/manage', $data);
        $this->load->view('footer');
    }

    public function edit($id = '')
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

    public function login()
    {
        $this->load->view('users/login');
        $this->load->view('footer');
    }

    // Check for user login process
    public function user_login_process()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            if (isset($this->session->userdata['logged_in'])) {
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('/pages/dashboard');
                $this->load->view('footer');
            } else {
                $this->load->view('users/login');
                $this->load->view('footer');
            }
        } else {
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
            );
            $result = $this->Users_model->login($data);
            if ($result == TRUE) {

                $username = $this->input->post('username');
                $result = $this->Users_model->read_user_information($username);
                if ($result != false) {
                    $session_data = array(
                        'username' => $result[0]->username,
                        'userrole' => $result[0]->userrole,
                    );
                    // Add user data in session
                    $this->session->set_userdata('logged_in', $session_data);
                    $this->load->view('header');
                    $this->load->view('main_menu');
                    $this->load->view('/pages/dashboard');
                    $this->load->view('footer');
                }
            } else {
                $data = array(
                    'error_message' => 'Invalid Username or Password'
                );
                $this->load->view('/users/login', $data);
                $this->load->view('footer');
            }
        }
    }

    // Validate and store registration data in database
    public function create()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('userrole', 'Userrole', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('/users/create');
            $this->load->view('footer');
        } else {
            $data = array(
                'username' => $this->input->post('username'),
                'userrole' => $this->input->post('userrole'),
                'password' => $this->input->post('password')
            );
            $result = $this->Users_model->registration_insert($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Registration Successfully !';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('/users/create', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Username already exist!';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('/users/create', $data);
                $this->load->view('footer');
            }
        }
    }

    // Logout from admin page
    public function logout()
    {
        // Removing session data
        $sess_array = array(
            'username' => ''
        );
        $this->session->unset_userdata('logged_in', $sess_array);
        $data['message_display'] = 'Successfully Logout';
        $this->load->view('users/login', $data);
        $this->load->view('footer');
    }
}
