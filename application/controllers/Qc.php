<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qc extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Qc_model');
        $this->load->model('Production_model');
        $this->load->model('Clients_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data = array();
        // get data from model
        $data['clients'] = $this->Clients_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('qc/view_qc_clients', $data);
        $this->load->view('footer');
    }

    public function add_qc($client = '')
    {
        $data = array();
        if ($client != '') {
            $data['client_name'] = $client;
            $data['project'] = urldecode($this->uri->segment(4));
        }
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('qc/add_qc', $data);
        $this->load->view('footer');
    }

    public function create_qc()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            echo $this->Qc_model->create_qc($data);
        }
    }

    public function edit_qc($id = '')
    {
        $data = array();
        if ($id != '') {
            $data['qc_form'] = $this->Qc_model->get_qc($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('qc/edit_qc', $data);
            $this->load->view('footer');
        } else {
            header("location: /qc");
        }
    }

    public function update_qc()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('id', 'id', 'trim|xss_clean');
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean');
        $this->form_validation->set_rules('product_num', 'product_num', 'trim|xss_clean');
        $this->form_validation->set_rules('serial', 'serial', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'client', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'project', 'trim|xss_clean');
        $this->form_validation->set_rules('user', 'user', 'trim|xss_clean');
        $this->form_validation->set_rules('problem', 'problem', 'trim|xss_clean');
        $this->form_validation->set_rules('pictures', 'pictures', 'trim|xss_clean');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                "id" => $this->input->post('id'),
                "date" => $this->input->post('date'),
                "product_num" => $this->input->post('product_num'),
                "serial" => $this->input->post('serial'),
                "client" => $this->input->post('client'),
                "project" => $this->input->post('project'),
                "user" => $this->input->post('user'),
                "problem" => $this->input->post('problem'),
                'pictures' => $this->input->post('pictures')
            );
            echo $this->Qc_model->update_qc($data);
        }
    }

    public function trash_qc()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
        $this->form_validation->set_rules('number', 'number', 'trim|xss_clean');
        $project = $this->input->post('project');
        $number = $this->input->post('number');
        $data = array(
            'id' =>  $this->input->post('id'),
            'number' =>  $number,
            'project' => $project
        );
        $this->Production_model->move_to_trash($data, 'qc_forms');
        $this->log_data("trashed $project qc #$number", 2);
    }

    public function search_qc()
    {
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $data = $this->Qc_model->search_qc($this->input->post('search'));
        $str = '';
        $count = 0;
        foreach ($data as $result) {
            if (strpos($result["project"], 'Trash') !== false) {
                $str .= "<div class='badge badge-danger' >" . urldecode($result["project"]) . ": " . $result["number"] . "</div>";
            } else {
                $str .= "<a class='badge badge-info' href='/qc/edit_qc/" . $result["id"] . "'>" . urldecode($result["project"]) . ": " . $result["number"] . "</a>";
            }

            $count++;
        }
        echo "<h2>Found " . $count . " serials.</h2>" . $str;
    }

    public function view_project_qc($client = '')
    {
        // init params
        $project = $this->uri->segment(4);
        $params = array();
        $config = array();
        $limit_per_page = 50;
        $uri_segment = 5;
        $start_index = ($this->uri->segment($uri_segment)) ? $this->uri->segment($uri_segment) : 0;
        $total_records = $this->Production_model->get_total($project, 'qc_forms', $client);
        if ($total_records > 0) {
            $params["results"] = $this->Production_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'qc_forms', $client);

            $config['base_url'] = base_url() . 'qc/' . $project;
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

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        $params['project'] = urldecode($project);
        $params['client'] = $client;
        $this->load->view('header');
        $this->load->view('main_menu',$params);
        $this->load->view('qc/manage_qc', $params);
        $this->load->view('footer');
    }

    function log_data($msg, $level = 0)
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
        $status =  $this->Qc_model->get_qc($id)[0]['status'];
        $status++;
        if ($status > 2) {
            $status = 0;
        }
        $sql = array(
            'id' => $id,
            'status' => $status,
        );
        echo $this->Qc_model->update_qc($sql);
    }
}