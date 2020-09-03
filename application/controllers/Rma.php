<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RMA extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Rma_model');
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
        $this->load->view('rma/view_rma_clients', $data);
        $this->load->view('footer');
    }

    public function add_rma($project = 'Flex2')
    {
        $data = array();
        if ($project != 'Production')  {
            $project = urldecode($project);
            $data['client_name'] = $this->Clients_model->getClients('', $project)[0]['name'];
            $data['project'] = $project;
        }else{
            $data['project'] = 'Production';
        }
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('rma/add_rma', $data);
        $this->load->view('footer');
    }

    public function create_rma()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean');
        $this->form_validation->set_rules('number', 'number', 'trim|xss_clean');
        $this->form_validation->set_rules('product_num', 'product_num', 'trim|xss_clean');
        $this->form_validation->set_rules('serial', 'serial', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'client', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'project', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('problem', 'problem', 'trim|xss_clean');
        $this->form_validation->set_rules('repair', 'repair', 'trim|xss_clean');
        $this->form_validation->set_rules('parts', 'parts', 'trim|xss_clean');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                "date" => $this->input->post('date'),
                "number" => $this->input->post('number'),
                "product_num" => $this->input->post('product_num'),
                "serial" => $this->input->post('serial'),
                "client" => $this->input->post('client'),
                "project" => $this->input->post('project'),
                "assembler" => $this->input->post('assembler'),
                "problem" => $this->input->post('problem'),
                "repair" => $this->input->post('repair'),
                "parts" => $this->input->post('parts')
            );
            echo $this->Rma_model->create_rma($data);
        }
    }

    public function edit_rma($id = '')
    {
        $data = array();
        if ($id != '') {
            $data['rma_form'] = $this->Rma_model->get_rma($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('rma/edit_rma', $data);
            $this->load->view('footer');
        } else {
            header("location: /rma");
        }
    }

    public function update_rma()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('id', 'id', 'trim|xss_clean');
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean');
        $this->form_validation->set_rules('product_num', 'product_num', 'trim|xss_clean');
        $this->form_validation->set_rules('serial', 'serial', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'client', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'project', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('problem', 'problem', 'trim|xss_clean');
        $this->form_validation->set_rules('repair', 'repair', 'trim|xss_clean');
        $this->form_validation->set_rules('parts', 'parts', 'trim|xss_clean');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                "id" => $this->input->post('id'),
                "date" => $this->input->post('date'),
                "product_num" => $this->input->post('product_num'),
                "serial" => $this->input->post('serial'),
                "client" => $this->input->post('client'),
                "project" => $this->input->post('project'),
                "assembler" => $this->input->post('assembler'),
                "problem" => $this->input->post('problem'),
                "repair" => $this->input->post('repair'),
                "parts" => $this->input->post('parts')
            );
            echo $this->Rma_model->update_rma($data);
        }
    }

    public function trash_rma()
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
        $this->Production_model->move_to_trash($data, 'rma_forms');
        $this->log_data("trashed $project RMA #$number", 2);
    }

    public function search_rma()
    {
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $data = $this->Rma_model->search_rma($this->input->post('search'));
        $str = '';
        $count = 0;
        foreach ($data as $result) {
            if (strpos($result["project"], 'Trash') !== false) {
                $str .= "<div class='badge badge-danger' >" . urldecode($result["project"]) . ": " . $result["number"] . "</div>";
            } else {
                $str .= "<a class='badge badge-info' href='/rma/edit_rma/" . $result["id"] . "'>" . urldecode($result["project"]) . ": " . $result["number"] . "</a>";
            }

            $count++;
        }
        echo "<h2>Found " . $count . " serials.</h2>" . $str;
    }

    public function view_project_rma($project = '')
    {
        // init params
        $params = array();
        $config = array();
        $limit_per_page = 20;
        $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $total_records = $this->Production_model->get_total($project, 'rma_forms');
        if ($total_records > 0) {
            $params["results"] = $this->Production_model->get_current_checklists_records($limit_per_page, $start_index, $project, 'rma_forms');

            $config['base_url'] = base_url() . 'rma/' . $project;
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 3;

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
        $params['client'] = $this->Clients_model->getClients('', urldecode($project));
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('rma/manage_rma', $params);
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
}
