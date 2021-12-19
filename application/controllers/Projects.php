<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends CI_Controller
{
    private $user;
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Projects_model');
        $this->load->model('Users_model');
        $this->load->model('Clients_model');
        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $this->lang->load('main', $this->user['language']);
            $this->languages = array("english", "hebrew");
        } else {
            header("location: /users/login");
            exit('User not logedin');
        }
    }

    public function index($data = '')
    {
        $data = array();
        // get data from model
        $data['projects'] = $this->Projects_model->getProjects();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('projects/manage_projects', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_project($id)
    {
        $data['js_to_load'] = array("add_project.js");

        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean|is_unique[projects.project]');
        $this->form_validation->set_rules('project_num', 'Project Number', 'trim|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|xss_clean');
        $this->form_validation->set_rules('scans', 'Scans', 'trim|xss_clean');
        if ($this->form_validation->run()) {
            $data['client'] = $this->Clients_model->get_client_by_id($id);
            $sql = array(
                'client' => $data['client']['name'],
                'project' => $this->input->post('project'),
                'project_num' => $this->input->post('project_num'),
                'data' => $this->input->post('data'),
                'template' => $this->input->post('template'),
                'restart_serial' => $this->input->post('restart_serial'),
                'scans' => $this->input->post('scans')
            );
            $result = $this->Projects_model->addProjects($sql);
            if ($result) {
                $this->create_checklist_version($sql['client'], $sql['project']);
                header("location: /clients");
            }
        }
        $data['client'] = $this->Clients_model->get_client_by_id($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('projects/add_project', $data);
        $this->load->view('footer');
    }

    public function edit_project($id = '')
    {
        $data = array();
        $data['project'] =  $this->Projects_model->getProject($id);
        // Check validation for user input in form
        $this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
        $this->form_validation->set_rules('project_num', 'Project Number', 'trim|xss_clean');
        if ($id != '' && $this->form_validation->run()) {
            $sql = array(
                'id' => $id,
                'data' => $this->input->post('data'),
                'project_num' => $this->input->post('project_num'),
                'checklist_version' => $this->input->post('checklist_version'),
                'template' => $this->input->post('template'),
                'restart_serial' => $this->input->post('restart_serial'),
                'scans' => $this->input->post('scans')
            );
            file_put_contents($this->input->post('checklist_version'), $this->input->post('data'));
            $data['message_display'] = $this->Projects_model->editProject($sql);
            $data['message_display'] .= ' Project edited Successfully !';
        }
        $data['project'] =  $this->Projects_model->getProject($id);
        $data['project']['data'] = $this->get_checklist_version($id);
        $data['checklists'] = $this->get_checklists($id);
        $data['clients'] = $this->Clients_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('projects/edit_project', $data);
        $this->load->view('footer');
    }

    function get_checklists($id)
    {
        $project =  $this->Projects_model->getProject($id);
        $folder = "Uploads/{$project['client']}/{$project['project']}";
        if (!file_exists($folder)) {
            mkdir($folder, 0770, true);
        }
        return glob($folder . "/*.txt");
    }

    function get_checklist_version($id)
    {
        if ($id != "") {
            $file = "";
            $project =  $this->Projects_model->getProject($id);
            $version = $this->input->post('version');
            $version = isset($version) ? $version : $project['checklist_version'];
            if (file_exists($version)) {
                $file = file_get_contents($version);
            }
            if ($this->input->post('version')) {
                echo $file;
            }
            return $file;
        }
        echo "id not set!";
        return false;
    }

    function create_checklist_version($client = "", $project = "")
    {
        if ($this->input->post('project_id') !== null) {
            $id = $this->input->post('project_id');
        }
        if ($this->input->post('version') !== null) {
            $version = $this->input->post('version');
            $version = str_replace(".", "_", $version);
        } else {
            $version = 1;
        }
        if ($client == "" && $project == "") {
            $project =  $this->Projects_model->getProject($id);
            $folder = "Uploads/{$project['client']}/{$project['project']}";
        } else {
            $folder = "Uploads/$client/$project";
        }
        $file = $folder . DIRECTORY_SEPARATOR . "rev_$version.txt";
        if (!file_exists($folder)) {
            mkdir($folder, 0770, true);
        }
        if (!file_exists($file)) {
            echo $assembly = fopen($file, "w");
            fwrite($assembly, "Assembly;Verify;HD\n verify V\n verify input;I\n verify name select;N\n QC verify;QC\n");
            fclose($assembly);
            return true;
        } else {
            echo "file exists!";
            return false;
        }

        echo "id not set!";
        return false;
    }

    public function delete_project()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role == "Admin") {
            $id = $_POST['id'];
            $this->Projects_model->deleteProject($id);
        }
    }

    public function assembly_upload()
    {
        $upload_folder = "Uploads/{$_GET['client']}/{$_GET['project']}/";
        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0770, true);
        }
        $config = array(
            'upload_path' => $upload_folder,
            'overwrite' => TRUE,
            'allowed_types' => 'pdf',
            'file_name' => 'assembly',
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('files')) {
            $data = array('upload_data' => $this->upload->data());
            echo  $data['upload_data']["file_name"];
        } else {
            $error = "error " . $this->upload->display_errors();
            echo $error;
        }
    }
}
