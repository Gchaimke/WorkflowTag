<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Users_model');
        $this->load->model('Admin_model');
    }

    public function index()
    {
        // get data from model
        $role = ($this->session->userdata['logged_in']['role']);
        $data['users'] = $this->Users_model->getUsers();
        $this->load->view('header');
        $this->load->view('header');
        $this->load->view('main_menu');
        if ($role != "Admin") {
            $data['message_display'] = "You are not Administrator";
            $this->load->view('/pages/error', $data);
        } else {
            $this->load->view('users/manage', $data);
        }
        $this->load->view('footer');
    }

    public function edit($id = '')
    {
        $role = ($this->session->userdata['logged_in']['role']);
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['user'] =  $this->Users_model->getUser($id);
            $data['settings'] = $this->Admin_model->getSettings();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('users/edit', $data);
            $this->load->view('footer');
        } else {
            if ($role == "Admin") {
                $sql = array(
                    'id' => $this->input->post('id'),
                    'role' => $this->input->post('role'),
                    'password' => $this->input->post('password')
                );
                $data['message_display'] = $this->Users_model->editUser($sql);
                $data['message_display'] .= ' User edited Successfully!';
                // get data from model
                $data['users'] = $this->Users_model->getUsers();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('users/manage', $data);
            } else {
                $sql = array(
                    'id' => $this->input->post('id'),
                    'role' => $role,
                    'password' => $this->input->post('password')
                );
                $this->Users_model->editUser($sql);
                $data = $this->Admin_model->getStatistic();
                $data['message_display'] = " Password saved";
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view("/pages/dashboard", $data);
            }
            $this->load->view('footer');
        }
    }

    public function delete()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role != "Admin") {
            $id = $_POST['id'];
            $this->Users_model->deleteUser($id);
        }
    }

    public function login()
    {
        $this->load->view('users/login');
        $this->load->view('footer');
    }

    // Check for user login process
    public function user_login_process()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            if (isset($this->session->userdata['logged_in'])) {
                $data = $this->Admin_model->getStatistic();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('/pages/dashboard', $data);
                $this->load->view('footer');
            } else {
                $this->load->view('users/login');
                $this->load->view('footer');
            }
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'password' => $this->input->post('password')
            );
            $result = $this->Users_model->login($data);
            if ($result == TRUE) {
                $name = $this->input->post('name');
                $result = $this->Users_model->read_user_information($name);
                if ($result != false) {
                    $session_data = array(
                        'id' => $result[0]->id,
                        'name' => $result[0]->name,
                        'role' => $result[0]->role,
                    );
                    // Add user data in session
                    $this->session->set_userdata('logged_in', $session_data);
                    $data = $this->Admin_model->getStatistic();
                    $this->load->view('header');
                    $this->load->view('main_menu');
                    $this->load->view('/pages/dashboard', $data);
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
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['settings'] = $this->Admin_model->getSettings();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('users/create', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'role' => $this->input->post('role'),
                'password' => $this->input->post('password')
            );
            $result = $this->Users_model->registration_insert($data);
            if ($result == TRUE) {
                $data['users'] = $this->Users_model->getUsers();
                $data['message_display'] = 'User Registration Successfully !';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('users/manage', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Username already exist!';
                $data['settings'] = $this->Admin_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('users/create', $data);
                $this->load->view('footer');
            }
        }
    }

    // Logout from admin page
    public function logout()
    {
        // Removing session data
        $sess_array = array(
            'name' => ''
        );
        $this->session->unset_userdata('logged_in', $sess_array);
        $data['message_display'] = 'Successfully Logout';
        $this->load->view('users/login', $data);
        $this->load->view('footer');
    }

    public function get_qc()
    {
        $password = $_POST['pass'];
        $exists = $this->Users_model->get_qc('QC', $password);
        echo $exists[0]['name'];
    }
}
