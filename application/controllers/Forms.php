<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Forms_model');
        $this->load->model('Production_model');
        $this->load->model('Clients_model');
        $this->load->library('pagination');
        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $this->lang->load('main', $this->user['language']);
            $this->languages = array("english", "hebrew");
        } else {
            header("location: /users/login");
            exit('User not logedin');
        }
    }

    public function index()
    {
        $limit = 20;
        $type = isset($_GET['type']) ? $_GET['type'] : 'rma';
        $data["forms"] = $this->Forms_model->get($type);
        $start = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
        if (count($data['forms']) > 0) {
            $config['base_url'] = base_url() . 'forms';
            $config['total_rows'] = count($data['forms']);
            $config['per_page'] = $limit;
            $this->pagination->initialize($config);
            $data["forms"] = $this->Forms_model->paginate($type, $start, $limit);
            $data["links"] = $this->pagination->create_links();
        }
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('forms/manage', $data);
        $this->load->view('footer');
    }

    public function add()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        if ($type != '') {
            $data = array();
            $data['type'] = $type;
            $data['client_name'] = isset($_GET['client']) ? $_GET['client'] : 'Avdor';
            $data['project'] = isset($_GET['project']) ? $_GET['project'] : 'All';
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view("/$type/add", $data);
            $this->load->view('footer');
        }
    }

    public function new()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $inserted_id = $this->Forms_model->create($data);
            if ($inserted_id != false) {
                echo "New  " . $this->input->post('type') . " Form created";
            } else {
                echo $inserted_id;
            }
        }
    }

    public function edit()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : 'rma';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $data = array();
        if ($id != '') {
            $data['form'] = $this->Forms_model->get($type, $id)[0];
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view("/$type/edit", $data);
            $this->load->view('footer');
        } else {
            header("location: /forms");
        }
    }

    public function update()
    {
        echo $this->Forms_model->update($this->input->post());
    }

    public function trash()
    {
        $data = $this->input->post();
        $this->Forms_model->trash($data);
        $this->log("form " . $this->input->post('id') . " trashed ", 2);
    }

    function log($msg, $level = 0)
    {
        if (!file_exists('application/logs/admin')) {
            mkdir('application/logs/admin', 0770, true);
        }
        $level_arr = array('INFO', 'CREATE', 'TRASH', 'DELETE');
        $user = $this->session->userdata['logged_in']['name'];
        $log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
        $fp = fopen($log_file, 'a'); //opens file in append mode  
        fwrite($fp, $level_arr[$level] . " - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
        fclose($fp);
    }

    function update_status()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $status =  $this->Forms_model->get($type, $id)[0]->status;
        $status++;
        if ($status > 3) {
            $status = 0;
        }
        $sql = array(
            'id' => $id,
            'status' => $status,
            'type' => $type,
        );
        echo $this->Forms_model->update($sql);
    }
}
