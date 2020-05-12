<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Production_model');
        $this->load->model('Admin_model');
    }

    public function index()
    {
        // get data from model
        $data['clients'] = $this->Production_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/view_clients', $data);
        $this->load->view('footer');
    }

    public function checklists($project = '', $data = '')
    {
        // get data from model
        $data['checklists'] = $this->Production_model->getChecklists('', $project);
        $data['project'] = $project;
        $this->load->view('header');
        $this->load->view('main_menu', $data);
        $this->load->view('production/manage_checklists', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_checklist($project = '', $data = '')
    {
        // Check validation for user input in SignUp form
        $zero_str = implode(", ", array_fill(0, 400, 0));
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('serial', 'Serial', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['client'] = $this->Production_model->getClients('', $project);
            $data['project'] = $project;
            $data['template'] = $this->Production_model->getProject('', $project)[0]['template'];
            $this->load->view('header');
            $this->load->view('main_menu', $data);
            $this->load->view('production/add_checklist', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'serial' => trim($this->input->post('serial')),
                'data' =>  $zero_str,
                'date' => $this->input->post('date')
            );
            $result = $this->Production_model->addChecklist($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Checklist ' . $this->input->post('serial') . ' added Successfully !';
                $this->checklists($this->input->post('project'), $data);
            } else {
                $data['message_display'] = 'Checklist ' . $this->input->post('serial') . ' already exist!';
                $data['client'] = $this->Production_model->getClients('', $project);
                $data['project'] = $this->input->post('project');
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/add_checklist', $data);
                $this->load->view('footer');
            }
        }
    }

    // Validate and store checklist data in database 
    public function gen_checklists()
    {
        $zero_str = implode(", ", array_fill(0, 400, 0));
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('count', 'Count', 'trim|required|xss_clean');
        $last_serial = $this->Production_model->getLastChecklist($this->input->post('project'));
        $serial_template = explode(',', $this->Production_model->getProject('', $this->input->post('project'))[0]['template']); //Get template and explode to array
        $sep = $serial_template[3];
        $serial = $serial_template[0] . $sep . $serial_template[1] . $sep . $serial_template[2];
        $serial = str_replace("yy", date("y"), $serial);
        $serial = str_replace("mm", date("m"), $serial);
        $serial_end = substr($last_serial, strpos($serial, 'x'), substr_count($serial, 'x')) + 0;
        $zero_count = $this->zero_count(substr_count($serial, 'x'), $serial_end);
        $arr = array("xxxx","xxx","xx");     
        $count = $this->input->post('count');
        for ($i = 1; $i <= $count; $i++) {
            $serial_end++;
            $zero_count = $this->zero_count(substr_count($serial, 'x'), $serial_end);
            $current_serial = str_replace($arr, $zero_count, $serial);
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'serial' => $current_serial,
                'data' =>  $zero_str,
                'date' => date("Y-m-d")
            );
            $result = $this->Production_model->addChecklist($data);
        }
        echo $result;
    }

    private function zero_count($x, $num)
    {
        if ($x == 4) {
            if ($num < 10) {
                return "000" . $num;
            } else if ($num < 100) {
                return "00" . $num;
            } else {
                return "0" . $num;
            }
        }

        if ($x == 3) {
            if ($num < 10) {
                return "00" . $num;
            } else if ($num < 100) {
                return "0" . $num;
            } else {
                return $num;
            }
        }

        if ($x == 2) {
            if ($num < 10) {
                return "0" . $num;
            } else {
                return $num;
            }
        }

        return 0;
    }

    private function build_checklist($data)
    {
        $prefix_count = 0;
        $checked = "";
        $table = '';
        $project = $data['checklist'][0]['project'];
        $checklist_data = $data['checklist'][0]['data'];
        if (count($this->Production_model->getProject('', $project)) > 0) {
            $project_data = $this->Production_model->getProject('', $project)[0]['data'];
            $rows = explode(PHP_EOL, $project_data);
            $status = explode(",", $checklist_data);
            $index = 0;
            $id = 0;
            for ($i = 0; $i < count($rows); $i++) {
                $tr = '';
                $checked = "";
                if (isset($status[$id]) && $status[$id] == 1) {
                    $checked = "Checked";
                }
                if ($index < 10) {
                    $prefix = $prefix_count . '.0';
                } else {
                    $prefix = $prefix_count . '.';
                }
                $col = explode(";", $rows[$i]);
                if (count($col) > 1) {
                    if ($col[1] == "HD") {
                        $tr = '<table id="checklist" class="table"><thead class="thead-dark"><tr><th scope="col">#</th><th id="result" scope="col">' . $col[0] .
                            '</th><th id="checkAll" scope="col">Verify</th></tr></thead><tbody>';
                        $index = 1;
                        $prefix_count++;
                    } else if ($col[1] == "QC") {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td><td>" .
                            "<div class='checkbox'><input type='checkbox'  id='$id' onclick='getQCCode(this.id)' $checked></div></td></tr>";
                        $index++;
                        $id++;
                    } else {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td><td>" .
                            "<div class='checkbox'><input type='checkbox' id='$id' $checked></div></td></tr>";
                        $index++;
                        $id++;
                    }
                }
                $table .= $tr;
            }
        }

        $table .= '</tbody></table>';
        return $table;
    }


    public function edit_checklist($id = '', $data = '')
    {
        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $data['checklist'] =  $this->Production_model->getChecklists($id);
        $data['project'] =  $data['checklist'][0]['project'];
        $this->load->view('header');
        $this->load->view('main_menu', $data);
        $data['data'] = $this->build_checklist($data);
        $this->load->view('production/edit_checklist', $data);
        $this->load->view('footer');
    }

    public function save_checklist($id = '')
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('log', 'Log', 'trim|xss_clean');
        $this->form_validation->set_rules('progress', 'Progress', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('qc', 'Qc', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_checklist($id);
        } else {
            $data = array(
                'id' =>  $id,
                'data' =>  $this->input->post('data'),
                'log' =>  $this->input->post('log'),
                'progress' => $this->input->post('progress'),
                'assembler' => $this->input->post('assembler'),
                'qc' => $this->input->post('qc')
            );
            $this->Production_model->editChecklist($data);
            $data['message_display'] = 'Checklist saved successfully!';
            $this->edit_checklist($id, $data);
        }
    }

    public function save_page2pdf($id = '')
    {
        $data['message_display'] = exec('python "' . getcwd() . '/test.py"');
        $data['message_display'] .= $html2pdf = '"' . getcwd() . '\assets\exec\html2pdf\wkhtmltopdf.exe" ';
        $data['message_display'] .= exec($html2pdf . ' https://localhost/production/edit_checklist/1 "' . getcwd() . '\test.pdf"');

        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $data['checklist'] =  $this->Production_model->getChecklists($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $data['data'] = $this->build_checklist($data);
        $this->load->view('production/edit_checklist', $data);
        $this->load->view('footer');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Production_model->deleteChecklist($id);
    }

    public function save_photo()
    {
        $this->load->view('production/save_photo');
    }

    public function delete_photo()
    {
        $this->load->view('production/delete_photo');
    }

    public function manage_templates($data = '')
    {
        // get data from model
        $data['projects'] = $this->Production_model->getprojects();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/manage_templates', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_template()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['js_to_load'] = array("add_template.js");
            $data['clients'] = $this->Production_model->getClients();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/add_template', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'data' => $this->input->post('data'),
                'template' => $this->input->post('template')
            );
            $result = $this->Production_model->addproject($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Project added Successfully !';
                $this->manage_templates($data);
            } else {
                $data['js_to_load'] = array("add_template.js");
                $data['message_display'] = 'project already exist!';
                $data['clients'] = $this->Production_model->getClients();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/add_template', $data);
                $this->load->view('footer');
            }
        }
    }

    public function edit_template($id = '')
    {
        // Check validation for user input in form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'Client', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('template', 'Template', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['clients'] = $this->Production_model->getClients();
            $data['project'] =  $this->Production_model->getProject($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/edit_template', $data);
            $this->load->view('footer');
        } else {
            $sql = array(
                'id' => $this->input->post('id'),
                'data' => $this->input->post('data'),
                'template' => $this->input->post('template')
            );
            $data['message_display'] = $this->Production_model->editProject($sql);
            $data['message_display'] .= ' Project edited Successfully !';
            $this->manage_templates($data);
        }
    }

    public function delete_project()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role == "Admin") {
            $id = $_POST['id'];
            $this->Production_model->deleteProject($id);
        }
    }

    // Validate and store checklist data in database
    public function add_client()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('projects', 'Projects', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/add_client');
            $this->load->view('footer');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'projects' => $this->input->post('projects')
            );
            $result = $this->Production_model->addClient($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Client added Successfully !';
                $this->manage_clients($data);
            } else {
                $data['message_display'] = 'Client already exist!';
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/add_client', $data);
                $this->load->view('footer');
            }
        }
    }

    public function manage_clients($data = '')
    {
        // get data from model
        $data['clients'] = $this->Production_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/manage_clients', $data);
        $this->load->view('footer');
    }

    public function edit_client($id = '')
    {
        // Check validation for user input in form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->form_validation->set_rules('projects', 'Projects', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['clients'] = $this->Production_model->getClients($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/edit_client', $data);
            $this->load->view('footer');
        } else {
            $sql = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'projects' => $this->input->post('projects')
            );
            $data['message_display'] = $this->Production_model->editClient($sql);
            $data['message_display'] .= ' Client edited Successfully !';
            $this->manage_clients($data);
        }
    }

    public function delete_client()
    {
        $role = ($this->session->userdata['logged_in']['role']);
        if ($role == "Admin") {
            $id = $_POST['id'];
            $this->Production_model->deleteClient($id);
        }
    }
}
