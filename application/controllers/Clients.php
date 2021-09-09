<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{
    public $clients, $users, $users_names, $clients_names;
    private $system_models = array(
        'Clients' => 'clients',
        'Projects' => 'projects',
    );

    public function __construct()
    {
        parent::__construct();
        // Load models
        foreach ($this->system_models as $model => $table) {
            $this->load->model($model . '_model');
        }

        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $this->lang->load('main', $this->user['language']);
            $this->languages = array("english", "hebrew");
        } else {
            header("location: /users/login");
            exit('User not logedin');
        }
        $this->clients = $this->Clients_model->getClients();
        foreach ($this->clients as $client) {
            $this->clients_names[$client['id']] = $client['name'];
        }
    }

    public function index($msg = '')
    {
        $data = array();
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        // get data from model
        foreach ($this->clients as $client) {
            $data['clients'][$client["name"]]['projects'] = $this->Projects_model->getProjects($client['name']);
            $data['clients'][$client["name"]]['status'] = $client['status'];
            $data['clients'][$client["name"]]['id'] = $client['id'];
        }
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('clients/manage', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function create()
    {
        $data = array();
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        if ($this->form_validation->run()) {
            $data = array(
                'name' => $this->input->post('name'),
                'logo' => $this->input->post('logo'),
                'status' => $this->input->post('status')
            );
            $result = $this->Clients_model->addClient($data);
            if ($result == TRUE) {
                header("location: /clients");
            }else{
                $data['message_display'] = ' Client '.$this->input->post('name').' Exists!';
            }
        }
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('clients/create',$data);
        $this->load->view('footer');
    }

    public function edit($id = '', $msg = '')
    {
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        $data = array();
        // Check validation for user input in form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('logo', 'Logo', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $sql = array(
                'id' => $this->input->post('id'),
                'logo' => $this->input->post('logo'),
                'name' => $this->input->post('name'),
                'projects' => $this->input->post('projects'),
                'status' => $this->input->post('status')
            );
            $this->Clients_model->editClient($sql);
            $data['message_display'] = ' Client updated Successfully !';
        }

        if (isset($this->Clients_model->getClients($id)[0])) {
            $client = $this->Clients_model->getClients($id)[0];
            $data["id"] = $client['id'];
            $data["name"] = $client['name'];
            $data["projects"] = $client['projects'];
            $data["logo"] = $client['logo'];
            $data["status"] = $client['status'];
        }

        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('clients/edit', $data);
        $this->load->view('footer');
    }

    public function logo_upload()
    {
        // requires php5
        define('UPLOAD_DIR', 'Uploads/Clients/');
        $file_name = $_POST['client'] . "_logo";
        $img = $_POST['data'];
        $ext = $_POST['ext'];
        if (preg_match('/^data:image\/(\w+);base64,/', $img, $type)) {
            $img = substr($img, strpos($img, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $img = base64_decode($img);
            if ($img === false) {
                echo 'base64_decode failed';
                throw new \Exception('base64_decode failed');
            }
        } else {
            echo 'did not match data URI with image data';
            throw new \Exception('did not match data URI with image data');
        }
        if (!file_exists(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0770, true);
        }
        $file = UPLOAD_DIR . $file_name . ".$ext";
        $success = file_put_contents($file, $img);
        print $success ? $file : 'Unable to save the file.';
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
