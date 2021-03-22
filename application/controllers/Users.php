<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    private $role;
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Users_model');
        $this->load->model('Admin_model');
        if (isset($this->session->userdata['logged_in'])) {
            $this->role = $this->session->userdata['logged_in']['role'];
        }
    }

    public function index()
    {
        $data = array();
        // get data from model
        $data['users'] = $this->Users_model->getUsers();
        $this->load->view('header');
        $this->load->view('main_menu');
        if ($this->role != "Admin") {
            header("location: /");
        } else {
            $this->load->view('users/manage', $data);
        }
        $this->load->view('footer');
    }

    // Validate and store registration data in database
    public function create()
    {
        $data = array();
        if ($this->role == "Admin") {
            // Check validation for user input in SignUp form
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('view_name', 'view_name', 'trim|xss_clean');
            $this->form_validation->set_rules('email', 'email', 'trim|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $data['settings'] = $this->Admin_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('users/create', $data);
                $this->load->view('footer');
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'view_name' => $this->input->post('view_name'),
                    'role' => $this->input->post('role'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'email' => $this->input->post('email')
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
        } else {
            header("location: /");
        }
    }

    public function delete()
    {
        if ($this->role == "Admin") {
            $id = $_POST['id'];
            $this->Users_model->deleteUser($id);
        }
    }

    public function edit($id = '')
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('view_name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['user'] =  $this->Users_model->getUser($id);
            $data['settings'] = $this->Admin_model->getSettings();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('users/edit', $data);
            $this->load->view('footer');
        } else {
            if ($this->role == "Admin") {
                $sql = array(
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'view_name' => $this->input->post('view_name'),
                    'role' => $this->input->post('role'),
                    'email' => $this->input->post('email')
                );
                if ($this->input->post('password') != '') {
                    $sql += array('password' => $this->input->post('password'));
                }
                print_r($this->Users_model->editUser($sql));
            } else {
                $sql = array(
                    'id' => $this->input->post('id'),
                    'view_name' => $this->input->post('view_name'),
                    'email' => $this->input->post('email')
                );
                if ($this->input->post('password') != '') {
                    $sql += array('password' => $this->input->post('password'));
                }
                print_r($this->Users_model->editUser($sql));
            }
        }
    }

    public function login()
    {
        $data = array();
        $data['response'] = '';
        $this->load->model('Admin_model');
        if (!$this->db->table_exists('users')) {
            $this->Users_model->createUsersDb();
            $this->Admin_model->createSettingsDb();
            $data['response'] .= "All Tables created!<br> username:Admin <br> Password:Admin.";
        }
        $this->load->view('users/login', $data);
        $this->load->view('footer');
    }

    // Check for user login process
    public function user_login_process()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            if (isset($this->session->userdata['logged_in'])) {
                header("location: /");
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
            if ($result == true) {
                $name = $this->input->post('name');
                $result = $this->Users_model->read_user_information($name);
                if ($result != false) {
                    $session_data = array(
                        'id' => $result[0]->id,
                        'name' => $result[0]->name,
                        'view_name' => $result[0]->view_name,
                        'role' => $result[0]->role,
                        'email' => $result[0]->email
                    );
                    // Add user data in session
                    $this->session->set_userdata('logged_in', $session_data);
                    header("location: /");
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

    // Logout from admin page
    public function logout()
    {
        $data = array();
        // Removing session data
        $sess_array = array(
            'name' => ''
        );
        $this->session->unset_userdata('logged_in', $sess_array);
        $data['message_display'] = 'Successfully Logout';
        $this->load->view('users/login', $data);
        $this->load->view('footer');
    }

    public function get_verify()
    {
        $current_user = ($this->session->userdata['logged_in']['name']);
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        if ($this->role == 'Admin') {
            echo true;
            return;
        }
        if ($current_user == $this->input->post('name')) {
            echo true;
            return;
        }
        $data = array(
            'name' => $this->input->post('name'),
            'password' => $this->input->post('password')
        );
        $result = $this->Users_model->login($data);
        if ($result == TRUE) {
            echo true;
        } else {
            echo false;
        }
    }
}
