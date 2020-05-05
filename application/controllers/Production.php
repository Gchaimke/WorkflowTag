<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Checklist_model');
        $this->load->model('Settings_model');
    }

    public function index()
    {
        // get data from model
        $data['checklists'] = $this->Checklist_model->getChecklists();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/manage_checklists', $data);
        $this->load->view('footer');
    }

    public function manage_projects()
    {
        // get data from model
        $data['projects'] = $this->Checklist_model->getprojects();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/manage_projects', $data);
        $this->load->view('footer');
    }

    // Validate and store checklist data in database
    public function add_checklist()
    {
        // Check validation for user input in SignUp form
        $zero_str = implode(", ", array_fill(0, 400, 0));
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('serial', 'Serial', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['settings'] = $this->Settings_model->getSettings();
            $data['projects'] = $this->Checklist_model->getprojects();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/add_checklist', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'serial' => $this->input->post('serial'),
                'data' =>  $zero_str,
                'date' => $this->input->post('date')
            );
            $result = $this->Checklist_model->insertNewChecklist($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Checklist added Successfully !';
                // get data from model
                $data['checklists'] = $this->Checklist_model->getChecklists();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/manage_checklists', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Checklist already exist!';
                $data['settings'] = $this->Settings_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/add_checklist', $data);
                $this->load->view('footer');
            }
        }
    }

    // Validate and store checklist data in database
    public function add_project()
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['settings'] = $this->Settings_model->getSettings();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/add_project', $data);
            $this->load->view('footer');
        } else {
            $data = array(
                'client' => $this->input->post('client'),
                'project' => $this->input->post('project'),
                'data' => $this->input->post('data')
            );
            $result = $this->Checklist_model->addproject($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Project added Successfully !';
                // get data from model
                $data['projects'] = $this->Checklist_model->getprojects();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/manage_projects', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'project already exist!';
                $data['settings'] = $this->Settings_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('production/add_project', $data);
                $this->load->view('footer');
            }
        }
    }

    private function build_checklist($data)
    {
        $prefix_count = 0;
        $checked = "";
        $project = $data['checklist'][0]['project'];
        $checklist_data = $data['checklist'][0]['data'];
        $project_data = $this->Checklist_model->getProject('', $project)[0]['data'];
        $rows = explode(PHP_EOL, $project_data);
        $status = explode(",", $checklist_data);

        $table = '';
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

        $table .= '</tbody></table>';
        return $table;
    }


    public function edit_checklist($id = '')
    {
        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $data['checklist'] =  $this->Checklist_model->getChecklist($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $data['data'] = $this->build_checklist($data);
        $this->load->view('production/edit_checklist', $data);
        $this->load->view('footer');
    }

    public function edit_project($id = '')
    {
        // Check validation for user input in form
        $this->form_validation->set_rules('id', 'Id', 'trim|xss_clean');
        $this->form_validation->set_rules('client', 'Client', 'trim|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|xss_clean');
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['settings'] = $this->Settings_model->getSettings();
            $data['project'] =  $this->Checklist_model->getProject($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/edit_project', $data);
            $this->load->view('footer');
        } else {
            $sql = array(
                'id' => $this->input->post('id'),
                'data' => $this->input->post('data')
            );
            $data['message_display'] = '';
            $data['message_display'] .= $this->Checklist_model->editProject($sql);
            $data['message_display'] .= ' Project edited Successfully !';
            // get data from model
            $data['projects'] = $this->Checklist_model->getprojects();
            $this->load->view('header');
            $this->load->view('main_menu');
            $this->load->view('production/manage_projects', $data);
            $this->load->view('footer');
        }
    }

    public function save_checklist($id = '')
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('progress', 'Progress', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('qc', 'Qc', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_checklist($id);
        } else {
            $data = array(
                'id' =>  $id,
                'data' =>  $this->input->post('data'),
                'progress' => $this->input->post('progress'),
                'assembler' => $this->input->post('assembler'),
                'qc' => $this->input->post('qc')
            );
            $this->Checklist_model->updateChecklist($data);
            $data['message_display'] = 'Checklist saved successfully!';
            $data['js_to_load'] = array("checklist_create.js", "camera.js");
            $data['checklist'] =  $this->Checklist_model->getChecklist($id);
            $this->load->view('header');
            $this->load->view('main_menu');
            $data['data'] = $this->build_checklist($data);
            $this->load->view('production/edit_checklist', $data);
            $this->load->view('footer');
        }
    }

    public function save_page2pdf($id = '')
    {
        $data['message_display'] = exec('python "' . getcwd() . '/test.py"');
        $data['message_display'] .= $html2pdf = '"' . getcwd() . '\assets\exec\html2pdf\wkhtmltopdf.exe" ';
        $data['message_display'] .= exec($html2pdf . ' https://localhost/production/edit_checklist/1 "' . getcwd() . '\test.pdf"');

        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $data['checklist'] =  $this->Checklist_model->getChecklist($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $data['data'] = $this->build_checklist($data);
        $this->load->view('production/edit_checklist', $data);
        $this->load->view('footer');
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Checklist_model->deleteChecklist($id);
    }

    public function delete_project()
    {
        $id = $_POST['id'];
        $this->Checklist_model->deleteProject($id);
    }

    public function save_photo()
    {
        $this->load->view('production/save_photo');
    }

    public function delete_photo()
    {
        $this->load->view('production/delete_photo');
    }
}
