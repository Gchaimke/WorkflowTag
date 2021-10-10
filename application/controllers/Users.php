<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    private $languages;
    private $user;
    private $clients;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('admin');
        // Load model
        $this->load->model('Users_model');
        $this->load->model('Admin_model');
        $this->load->model('Clients_model');
        $this->clients = $this->Clients_model->getClients();
        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $lang = isset($this->user['language']) ? $this->user['language'] : 'english';
            $this->lang->load('main', $lang);
            $this->languages = array("english", "hebrew");
        }
    }

    public function index()
    {
        $data = array();
        // get data from model
        $data['users'] = $this->Users_model->getUsers();
        $data['settings'] = $this->Admin_model->getSettings();
        $data['languages'] = $this->languages;
        $data['clients'] = $this->clients;
        $this->load->view('header');
        $this->load->view('main_menu');
        if ($this->user['role'] != "Admin") {
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
        if ($this->user['role'] == "Admin") {
            // Check validation for user input in SignUp form
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $data['users'] = $this->Users_model->getUsers();
            $data['settings'] = $this->Admin_model->getSettings();
            $data['languages'] = $this->languages;
            $data['clients'] = $this->clients;

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('users/create', $data);
                $this->load->view('footer');
            } else {
                $clients = $this->input->post('clients') != "" ? $this->input->post('clients') : array(0);
                $sql = array(
                    'name' => $this->input->post('name'),
                    'view_name' => $this->input->post('view_name'),
                    'role' => $this->input->post('role'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'language' => $this->input->post('language'),
                    'email' => $this->input->post('email'),
                    'clients' => implode(",", $clients),
                );
                $result = $this->Users_model->registration_insert($sql);
                if ($result == TRUE) {
                    $data['message_display'] = 'User Registration Successfully !';
                    redirect("users/");
                } else {
                    $data['message_display'] = 'Username already exist!';
                    redirect("users/");
                }
            }
        } else {
            header("location: /");
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

        $data['user'] =  $this->Users_model->getUser($id);
        $data['languages'] = $this->languages;
        $data['settings'] = $this->Admin_model->getSettings();
        $data['clients'] = $this->clients;

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('users/edit', $data);
            $this->load->view('footer');
        } else {
            if ($this->user['role'] == "Admin") {
                $clients = $this->input->post('clients') != "" ? $this->input->post('clients') : array(0);
                $sql = array(
                    'id' => $this->input->post('id'),
                    'name' => $this->input->post('name'),
                    'view_name' => $this->input->post('view_name'),
                    'role' => $this->input->post('role'),
                    'language' => $this->input->post('language'),
                    'email' => $this->input->post('email'),
                    'clients' => implode(",",  $clients),
                );
                if ($this->input->post('password') != '') {
                    $sql += array('password' => $this->input->post('password'));
                }
                print_r($this->Users_model->editUser($sql));
            } else {
                $sql = array(
                    'id' => $this->input->post('id'),
                    'view_name' => $this->input->post('view_name'),
                    'language' => $this->input->post('language'),
                    'email' => $this->input->post('email'),
                );
                if ($this->input->post('password') != '') {
                    $sql += array('password' => $this->input->post('password'));
                }
                print_r($this->Users_model->editUser($sql));
            }
        }
        $this->set_session_data($this->user['name']);
    }

    public function delete()
    {
        if ($this->user['role'] == "Admin") {
            $id = $_POST['id'];
            $this->Users_model->deleteUser($id);
        }
    }

    public function login()
    {
        $data = array();
        $data['response'] = '';
        if (!$this->db->table_exists('users')) {
            create_new_tables($this);
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
                    $this->set_session_data($this->input->post('name'));
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

    function set_session_data($user_name = '')
    {
        if ($this->Admin_model->getSettings()['language'] != '') {
            $sys_lang = $this->Admin_model->getSettings()['language'];
        } else {
            $sys_lang = $this->config->item('language');
        }
        $result = $this->Users_model->read_user_information($user_name);
        if ($result != false) {
            $language = ($result[0]->language == 'system') ? $sys_lang : $result[0]->language;
            $session_data = array(
                'id' => $result[0]->id,
                'name' => $result[0]->name,
                'view_name' => $result[0]->view_name,
                'role' => $result[0]->role,
                'email' => $result[0]->email,
                'clients' => $result[0]->clients,
                'language' => $language
            );
            $this->session->set_userdata('logged_in', $session_data);
            session_write_close();
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
        if ($this->user['role'] == 'Admin') {
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
