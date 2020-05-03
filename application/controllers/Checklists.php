<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklists extends CI_Controller
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
        $this->load->view('checklists/manage_checklists', $data);
        $this->load->view('footer');
    }

    public function manage_projects()
    {
        // get data from model
        $data['projects'] = $this->Checklist_model->getprojects();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('checklists/manage_projects', $data);
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
            $this->load->view('checklists/add_checklist', $data);
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
                $this->load->view('checklists/manage_checklists', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Checklist already exist!';
                $data['settings'] = $this->Settings_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/add_checklist', $data);
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
            $this->load->view('checklists/add_project', $data);
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
                $this->load->view('checklists/manage_projects', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'project already exist!';
                $data['settings'] = $this->Settings_model->getSettings();
                $this->load->view('header');
                $this->load->view('main_menu');
                $this->load->view('checklists/add_project', $data);
                $this->load->view('footer');
            }
        }
    }

    private function build_checklist($data)
    {
        $prefix = "1.0";
        $verify = "";
        $checked = "";
        $onClick = "";
        $project = $data['checklist'][0]['project'];
        $checklist_data = $data['checklist'][0]['data'];
        $project_data = $this->Checklist_model->getProject('', $project)[0]['data'];
        $rows = explode(PHP_EOL, $project_data);
        $header = explode(";", $rows[0]);
        $status = explode(",", $checklist_data);
        $table = '<table id="checklist"   class="table"><thead class="thead-dark"><tr>
            <th scope="col" onclick="saveData()">#</th>
            <th id="result"  scope="col">' . $header[0] . '</th>
            <th scope="col" onclick="toggleAllCheckboxs()">' . $header[1] . '</th>
            </tr></thead><tbody>';
        for ($i = 1; $i < count($rows); $i++) {
            $checked = "";
            if ($status[$i] == 1) {
                $checked = "Checked";
            }
            if ($i >= 10) {
                $prefix = '1.';
            }
            $col = explode(";", $rows[$i]);
            if ($col[1] == "QC") {
                $onClick = ' onclick="getQCCode(this.id)"';
            } else {
                $onClick = ' onclick="toggleOne()"';
            }
            $verify = '<div class="checkbox">
                    <input type="checkbox"  id="check_' . $i . '" name="check' . $i . '" ' .
                $onClick . ' ' . $checked . '></div>';
            $table .= '<tr><th scope="row">' . $prefix . $i . '</th>';
            $table .= '<td class="description">' . $col[0] . '</td>';
            $table .= '<td onclick="restore()">' . $verify . '</td></tr>';
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
        $this->load->view('checklists/edit_checklist', $data);
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
            $this->load->view('checklists/edit_project', $data);
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
            $this->load->view('checklists/manage_projects', $data);
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
            $result = $this->Checklist_model->updateChecklist($data);
            if ($result == TRUE) {
                $data['message_display'] = 'Checklist saved successfully!';
                $data['js_to_load'] = array("checklist_create.js", "camera.js");
                $data['checklist'] =  $this->Checklist_model->getChecklist($id);
                $this->load->view('header');
                $this->load->view('main_menu');
                $data['data'] = $this->build_checklist($data);
                $this->load->view('checklists/edit_checklist', $data);
                $this->load->view('footer');
            } else {
                $data['message_display'] = 'Checklist already exist!';
                $data['js_to_load'] = array("checklist_create.js", "camera.js");
                $data['checklist'] =  $this->Checklist_model->getChecklist($id);
                $this->load->view('header');
                $this->load->view('main_menu');
                $data['data'] = $this->build_checklist($data);
                $this->load->view('checklists/edit_checklist', $data);
                $this->load->view('footer');
            }
        }
    }

    public function save_page2pdf($id = '')
    {
        $data['message_display'] = exec('python "' . getcwd() . '/test.py"');
        $data['message_display'] .= $html2pdf = '"' . getcwd() . '\assets\exec\html2pdf\wkhtmltopdf.exe" ';
        $data['message_display'] .= exec($html2pdf . ' https://localhost/checklists/edit_checklist/1 "' . getcwd() . '\test.pdf"');

        $data['js_to_load'] = array("checklist_create.js", "camera.js");
        $data['checklist'] =  $this->Checklist_model->getChecklist($id);
        $this->load->view('header');
        $this->load->view('main_menu');
        $data['data'] = $this->build_checklist($data);
        $this->load->view('checklists/edit_checklist', $data);
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
        $this->load->view('checklists/save_photo');
    }

    public function delete_photo()
    {
        $this->load->view('checklists/delete_photo');
    }
}
