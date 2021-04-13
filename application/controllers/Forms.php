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
    }

    public function index()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : 'rma';
        $data['forms'] = $this->Forms_model->get($type);
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
            $data['form'] = $this->Forms_model->get($type,$id)[0];
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


    public function view($client = '', $type = 'rma')
    {
        $data = array();
        $project = $this->uri->segment(4);
        $uri_segment = 5;
        $limit_per_page = 50;
        $base_url = base_url() . $type . '/' . $project;

        $start_index = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
        $total_records = $this->Production_model->get_total($project, $type . '_forms', $client);
        $data["results"] = $this->Production_model->get_current_checklists_records($limit_per_page, $start_index, $project, $type . '_forms', $client);
        if ($total_records > 0) {
            $this->pagination($base_url, $total_records, $limit_per_page, $uri_segment);
            $data["links"] = $this->pagination->create_links();
        }
        $data['project'] = urldecode($project);
        $data['client'] = $client;
        $this->load->view('header');
        $this->load->view('main_menu', $data);
        $this->load->view($type . '/manage', $data);
        $this->load->view('footer');
    }

    function pagination($base_url, $total_records, $limit_per_page, $uri_segment)
    {
        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $limit_per_page;
        $config["uri_segment"] = $uri_segment;

        $config['full_tag_open'] = '<ul class="pagination right">';
        $config['full_tag_close'] = '</ul>';

        $config['cur_tag_open'] = '<li class="page-item active "><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item num-link">';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li class="page-item num-link">';
        $config['first_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li class="page-item num-link">';
        $config['last_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="page-item num-link">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="page-item num-link">';
        $config['prev_tag_close'] = '</li>';

        $this->pagination->initialize($config);
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
        $status =  $this->Forms_model->get($type,$id)[0]->status;
        $status++;
        if ($status > 2) {
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
