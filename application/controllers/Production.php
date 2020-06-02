<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Production_model');
        $this->load->model('Clients_model');
        $this->load->model('Templates_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data = array();
        // get data from model
        $data['clients'] = $this->Clients_model->getClients();
        $this->load->view('header');
        $this->load->view('main_menu');
        $this->load->view('production/view_clients', $data);
        $this->load->view('footer');
    }

    public function checklists($project = '')
    {
        $this->load->database();
        // init params
        $params = array();
        $config = array();
        $limit_per_page = 10;
        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $total_records = $this->Production_model->get_total($project);
        if ($total_records > 0) {
            $params["results"] = $this->Production_model->get_current_checklists_records($limit_per_page, $start_index, $project);

            $config['base_url'] = base_url() . 'production/checklists/' . $project;
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $config["uri_segment"] = 4;

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
        $this->load->view('main_menu', $params);
        $this->load->view('production/manage_checklists', $params);
        $this->load->view('footer');
    }

    public function serial_search()
    {
        $this->form_validation->set_rules('sn', 'Sn', 'trim|xss_clean');
        $data = $this->Production_model->searchChecklist($this->input->post('sn'));
        $str = '';
        $count = 0;
        foreach ($data as $result) {
            $str .= "<a class='badge badge-info' href='/production/edit_checklist/" . $result["id"] . "?sn=" . $result["serial"] . "'>" . urldecode($result["project"]) . ": " . $result["serial"] . "</a>";
            $count++;
        }
        echo "<h2>Found " . $count . " serials.</h2>" . $str;
    }


    // Validate and store checklist data in database
    public function add_checklist($project = '', $data = '')
    {
        $data = array();
        // Check validation for user input in SignUp form
        $zero_str = implode(",", array_fill(0, 400, ""));
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('serial', 'Serial', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['client'] = $this->Clients_model->getClients('', $project);
            $data['project'] = urldecode($project);
            if (isset($this->Templates_model->getTemplate('', $project)[0]['template'])) {
                $data['template'] = $this->Templates_model->getTemplate('', $project)[0]['template'];
            } else {
                $data['template'] = " - not set!";
            }
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
                header("location: /production/checklists/" . $project);
            } else {
                if (isset($this->Templates_model->getTemplate('', $project)[0]['template'])) {
                    $data['template'] = $this->Templates_model->getTemplate('', $project)[0]['template'];
                } else {
                    $data['template'] = " - not set!";
                }
                $data['message_display'] = 'Checklist ' . $this->input->post('serial') . ' already exist!';
                $data['client'] = $this->Clients_model->getClients('', $project);
                $data['project'] = urldecode($this->input->post('project'));
                if (isset($this->Templates_model->getTemplate('', $project)[0]['template'])) {
                    $data['template'] = $this->Templates_model->getTemplate('', $project)[0]['template'];
                } else {
                    $data['template'] = " - not set!";
                }
                $this->load->view('header');
                $this->load->view('main_menu', $data);
                $this->load->view('production/add_checklist', $data);
                $this->load->view('footer');
            }
        }
    }

    // Validate and store checklist data in database 
    public function gen_checklists()
    {
        $result = 'Serial template not set!';
        $dfend_month = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => 'A', '11' => 'B', '12' => 'C');
        $zero_str = implode(",", array_fill(0, 400, ""));
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('client', 'Client', 'trim|required|xss_clean');
        $this->form_validation->set_rules('project', 'Project', 'trim|required|xss_clean');
        $this->form_validation->set_rules('count', 'Count', 'trim|required|xss_clean');
        $last_serial = $this->Production_model->getLastChecklist($this->input->post('project'));
        $serial_project = $this->Templates_model->getTemplate('', $this->input->post('project'));
        if (isset($serial_project[0]['template']) &&  $serial_project[0]['template'] != "") {
            $serial = $serial_project[0]['template']; //Get serial template
            $serial = str_replace("yy", date("y"), $serial); //add year
            $serial = str_replace("mm", date("m"), $serial); //add month with zero
            $serial = str_replace("dm", $dfend_month[date("m")], $serial); //add month from dfend array
            $serial_end = substr($last_serial, strpos($serial, 'x'), substr_count($serial, 'x')) + 0;
            $zero_count = $this->zero_count(substr_count($serial, 'x'), $serial_end);
            $arr = array("xxxx", "xxx", "xx");
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
                if ($result != 1) {
                    echo 'Checklist ' . $data['serial'] . ' exists!';
                    return;
                }
            }
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

    public function edit_checklist($id = '',  $msg = '')
    {
        $data = array();
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        $data['js_to_load'] = array("edit_checklist.js", "camera.js");
        $data['checklist'] =  $this->Production_model->getChecklists($id);
        if ($data['checklist']) {
            $data['project'] =  urldecode($data['checklist'][0]['project']);
            $data['checklist_rows'] = $this->build_checklist($data);
            $data['scans_rows'] = $this->build_scans($data);
        }
        $this->load->view('header');
        $this->load->view('main_menu', $data);
        if ($data['checklist']) {
            $this->load->view('production/edit_checklist');
        }
        $this->load->view('footer');
    }

    private function build_checklist($data)
    {
        $this->load->model('Users_model');
        $users = $this->Users_model->getUsers();
        $prefix_count = 0;
        $checked = "";
        $table = '';
        $options = '';
        $project = $data['checklist'][0]['project'];
        $checklist_data = $data['checklist'][0]['data'];
        if (count($this->Templates_model->getTemplate('', $project)) > 0) {
            $project_data = $this->Templates_model->getTemplate('', $project)[0]['data'];
            $rows = explode(PHP_EOL, $project_data);
            $status = explode(",", $checklist_data);
            //$table .= $checklist_data;
            $index = 0;
            $id = 0;
            foreach ($users as $user) {
                $options .= "<option >" . $user['name'] . "</option>";
            }
            for ($i = 0; $i < count($rows); $i++) {
                $tr = '';
                $checked = '';
                if (isset($status[$id]) && $status[$id] != '') {
                    $checked = "Checked name-data='" . $status[$id] . "'";
                }
                if ($index < 10) {
                    $prefix = $prefix_count . '.0';
                } else {
                    $prefix = $prefix_count . '.';
                }
                $col = explode(";", $rows[$i]);
                if (count($col) > 1) {
                    if (end($col) == "HD") {
                        $tr = '<table id="checklist" class="table"><thead class="thead-dark">' . '<tr><th scope="col">#</th><th id="result" scope="col">' . $col[0] . '</th>';
                        for ($j = 1; $j < count($col) - 1; $j++) {
                            $tr .= '<th scope="col">' . $col[$j] . '</th>';
                        }
                        $tr .= '</tr></thead><tbody>';
                        $index = 1;
                        $prefix_count++;
                    } else if (end($col) == "QC") {
                        $tr .= "<tr class='qc_row'><th scope='row'>$prefix$index</th><td class='description' colspan='2'>" . $col[0];
                        $tr .= "<select class='form-control review' id='" . ($id + count($rows)) . "'><option>Select</option>";
                        $tr .= $options . "</select></td></tr>";
                        $index++;
                        $id++;
                    } else if (end($col) == "N") {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td>";
                        $tr .= "<td><div class='checkbox'><input type='checkbox' class='verify'  id='$id' $checked></div></td>";
                        $tr .= "<td><select class='form-control review' id='" . ($id + count($rows)) . "'><option>Select</option>";
                        $tr .= $options . "</select></td></tr>";
                        $index++;
                        $id++;
                    } else {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td><td>" .
                            "<div class='checkbox'><input type='checkbox' class='verify' id='$id' $checked></div></td></tr>";
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

    private function build_scans($data)
    {
        $table = '';
        $tr = '';
        $columns = 0;
        $id = 0;
        $project = $data['checklist'][0]['project'];
        if (count($this->Templates_model->getTemplate('', $project)) > 0) {
            $project_scans = $this->Templates_model->getTemplate('', $project)[0]['scans'];
            $rows = explode(PHP_EOL, $project_scans);
            if (count($rows) > 1) {
                $table .= '<center><h2> Scans Table</h2></center><table id="scans" class="table"><thead class="thead-dark">';
                for ($i = 0; $i < count($rows); $i++) {
                    $col = explode(";", $rows[$i]);
                    if (end($col) == "HD") {
                        $columns = count($col);
                        $tr = '<tr><th scope="col">#</th><th scope="col">' . $col[0] . '</th>';
                        for ($j = 1; $j < count($col) - 1; $j++) {
                            $tr .= '<th scope="col">' . $col[$j] . '</th>';
                        }
                        $tr .= '</tr></thead>';
                        $table .= $tr;
                    } else {
                        $tr = "<tr id='$id' class='scan_row'><th scope='row'>$i</th><td class='description'>" . $col[0] . "</td>";
                        for ($j = 2; $j < $columns; $j++) {
                            $tr .= "<td><input type='text' class='form-control scans'></td>";
                        }
                        $tr .=  "</tr>";
                        $table .= $tr;
                        $id++;
                    }
                }
            }
        }
        $table .= '</tbody></table>';
        return $table;
    }

    public function edit_batch($ids = '', $msg = '')
    {
        $data = array();
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        $data['ids'] = $ids;
        $data['js_to_load'] = array("edit_checklist.js", "camera.js");
        $data['checklists'] =  $this->Production_model->getChecklists($ids);
        if ($data['checklists']) {
            $data['project'] =  urldecode($data['checklists'][0]['project']);
            $data['checklist_rows'] = $this->build_batch_checklist($data);
            $data['scans_rows'] = $this->build_batch_scans($data);
        }
        $this->load->view('header');
        $this->load->view('main_menu', $data);
        if ($data['checklists']) {
            $this->load->view('production/edit_batch');
        }
        $this->load->view('footer');
    }

    private function build_batch_checklist($data)
    {
        $this->load->model('Users_model');
        $users = $this->Users_model->getUsers();
        $prefix_count = 0;
        $checked = "";
        $table = '';
        $options = '';
        $project = $data['checklists'][0]['project'];
        $checklist_data = $data['checklists'][0]['data'];
        if (count($this->Templates_model->getTemplate('', $project)) > 0) {
            $project_data = $this->Templates_model->getTemplate('', $project)[0]['data'];
            $rows = explode(PHP_EOL, $project_data);
            $status_arr=array();
            //foreach ($data['checklists'] as $index => $status) {

               $status = explode(",", $checklist_data);
            //}
            
            //$table .= $checklist_data;
            $index = 0;
            $id = 0;
            foreach ($users as $user) {
                $options .= "<option >" . $user['name'] . "</option>";
            }
            for ($i = 0; $i < count($rows); $i++) {
                $tr = '';
                if ($index < 10) {
                    $prefix = $prefix_count . '.0';
                } else {
                    $prefix = $prefix_count . '.';
                }
                $col = explode(";", $rows[$i]);
                if (count($col) > 1) {
                    if (end($col) == "HD") {
                        $tr = '<table id="checklist" class="table"><thead class="thead-dark">' . '<tr><th scope="col">#</th><th id="result" scope="col">' . $col[0] . '</th>';
                        for ($j = 1; $j < count($col) - 1; $j++) {
                            foreach ($data['checklists'] as $column) {
                                $tr .= '<th scope="col">' . $col[$j] . '<div class="th_serial">' . $column['serial'] . '</div>' . '</th>';
                            }
                        }
                        $tr .= '</tr></thead><tbody>';
                        $index = 1;
                        $prefix_count++;
                    } else if (end($col) == "QC") {
                        $tr .= "<tr class='qc_row'><th scope='row'>$prefix$index</th><td class='description' colspan=' " . (count($data['checklists']) + 1) . "'>" . $col[0];
                        $tr .= "<select class='form-control review' id='$id'><option>Select</option>";
                        $tr .= $options . "</select></td></tr>";
                        $index++;
                        $id++;
                    } else if (end($col) == "N") {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td>";
                        foreach ($data['checklists'] as $column) {
                            $tr .= "<td><div class='checkbox'><input type='checkbox' class='verify'  id='$id'";
                            if (isset($status[$id]) && $status[$id] != '') {
                                $tr .= "Checked name-data='" . $status[$id] . "'>";
                            }
                            $tr .= "</div></td>";
                            $id++;
                        }

                        foreach ($data['checklists'] as $column) {
                            $tr .= "<td><select class='form-control review' id='$id'><option>Select</option>";
                            $tr .= $options . "</select></td>";
                            $id++;
                        }
                        $tr .= "</tr>";
                        $index++;
                    } else {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td>";
                        foreach ($data['checklists'] as $column) {
                            $tr .= "<td><div class='checkbox'><input type='checkbox' class='verify' id='$id'";
                            if (isset($status[$id]) && $status[$id] != '') {
                                $tr .= "Checked name-data='" . $status[$id] . "'>";
                            }
                            $tr .= "</div></td>";
                            $id++;
                        }
                        $tr .= "</tr>";

                        $index++;
                    }
                }
                $table .= $tr;
            }
        }

        $table .= '</tbody></table>';
        return $table;
    }

    private function build_batch_scans($data)
    {
        $table = '';
        $tr = '';
        $columns = 0;
        $id = 0;
        $project = $data['checklists'][0]['project'];
        if (count($this->Templates_model->getTemplate('', $project)) > 0) {
            $project_scans = $this->Templates_model->getTemplate('', $project)[0]['scans'];
            $rows = explode(PHP_EOL, $project_scans);
            if (count($rows) > 1) {
                $table .= '<center><h2> Scans Table</h2></center><table id="scans" class="table"><thead class="thead-dark">';
                for ($i = 0; $i < count($rows); $i++) {
                    $col = explode(";", $rows[$i]);
                    if (end($col) == "HD") {
                        $columns = count($col);
                        $tr = '<tr><th scope="col">#</th><th scope="col">' . $col[0] . '</th>';
                        for ($j = 1; $j < count($col) - 1; $j++) {
                            $tr .= '<th scope="col">' . $col[$j] . '</th>';
                        }
                        $tr .= '</tr></thead>';
                        $table .= $tr;
                    } else {
                        $tr = "<tr id='$id' class='scan_row'><th scope='row'>$i</th><td class='description'>" . $col[0] . "</td>";
                        for ($j = 2; $j < $columns; $j++) {
                            $tr .= "<td><input type='text' class='form-control scans'></td>";
                        }
                        $tr .=  "</tr>";
                        $table .= $tr;
                        $id++;
                    }
                }
            }
        }
        $table .= '</tbody></table>';
        return $table;
    }


    public function save_batch_checklists($id = '')
    {
        // Check validation for user input in SignUp form
        $message_display = '';
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('log', 'Log', 'trim|xss_clean');
        $this->form_validation->set_rules('progress', 'Progress', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('qc', 'Qc', 'trim|xss_clean');
        $this->form_validation->set_rules('scans', 'Scans', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_batch($id);
        } else {
            $ids = explode(':', $id);
            $batch_arr = explode(',', $this->input->post('data'));

            foreach ($ids as $index => $current_id) {
                $checklist_data = array();
                $test = '';
                for ($i = $index; $i < count($batch_arr); $i += count($ids)) {
                    $test .= " " . $i;
                    array_push($checklist_data, $batch_arr[$i]);
                }

                $data = array(
                    'id' =>  $current_id,
                    'data' =>  implode(',', $checklist_data),
                    'log' =>  $this->input->post('log'),
                    'progress' => $this->input->post('progress'),
                    'assembler' => $this->input->post('assembler'),
                    'qc' => $this->input->post('qc'),
                    'scans' => $this->input->post('scans')
                );

                $message_display .=   $test . ' ' . implode(',', $checklist_data) . $current_id . " ";
            }

            $this->Production_model->editChecklist($data);
            $message_display .= 'Checklists saved successfully!' . $id;
            $this->edit_batch($id, $message_display);
        }
    }
    public function save_checklist($id = '')
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        $this->form_validation->set_rules('log', 'Log', 'trim|xss_clean');
        $this->form_validation->set_rules('progress', 'Progress', 'trim|xss_clean');
        $this->form_validation->set_rules('assembler', 'assembler', 'trim|xss_clean');
        $this->form_validation->set_rules('qc', 'Qc', 'trim|xss_clean');
        $this->form_validation->set_rules('scans', 'Scans', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_checklist($id);
        } else {
            $data = array(
                'id' =>  $id,
                'data' =>  $this->input->post('data'),
                'log' =>  $this->input->post('log'),
                'progress' => $this->input->post('progress'),
                'assembler' => $this->input->post('assembler'),
                'qc' => $this->input->post('qc'),
                'scans' => $this->input->post('scans')
            );
            $this->Production_model->editChecklist($data);
            $message_display = 'Checklist saved successfully!';
            $this->edit_checklist($id, $message_display);
        }
    }

    public function delete()
    {
        $id = $_POST['id'];
        $this->Production_model->deleteChecklist($id);
    }

    public function save_photo()
    {
        defined('BASEPATH') or exit('No direct script access allowed');
        // requires php5
        define('UPLOAD_DIR', 'Uploads/');
        $folder = $_POST['pr'];
        $name = $_POST['sn'];
        $num = $_POST['num'];
        $file_name = $name . "_" . $num;
        $img = $_POST['data'];
        if (preg_match('/^data:image\/(\w+);base64,/', $img, $type)) {
            $img = substr($img, strpos($img, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $img = base64_decode($img);

            if ($img === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        if (!file_exists(UPLOAD_DIR . $folder . "/" . $name)) {
            mkdir(UPLOAD_DIR . $folder . "/" . $name, 0770, true);
        }
        $file = UPLOAD_DIR . $folder . "/" . $name . "/" . $file_name . ".$type";
        $success = file_put_contents($file, $img);
        if (!file_exists("C:\Program Files\Ampps\www\assets\exec\pngquanti.exe")) {
            shell_exec('"C:\Program Files\Ampps\www\assets\exec\pngquanti.exe" --ext .png --speed 10 --nofs --force ' . escapeshellarg($file));
        }
        print $success ? $file : 'Unable to save the file.';
    }


    public function delete_photo()
    {
        $this->form_validation->set_rules('scans', 'Scans', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            // Use unlink() function to delete a file  
            if (!unlink($_SERVER["DOCUMENT_ROOT"] . $this->input->post('photo'))) {
                echo ($_SERVER["DOCUMENT_ROOT"] . $this->input->post('photo') . " cannot be deleted due to an error");
            } else {
                echo ($_SERVER["DOCUMENT_ROOT"] . $this->input->post('photo') . " has been deleted");
            }
        }
    }

    public function save_page2pdf()
    {
        $url = $_POST['url'];
        $cookie = $_POST['cookie'];
        $html2pdf = '"' . getcwd() . '\assets\exec\html2pdf\wkhtmltopdf.exe" ';
        exec($html2pdf . ' ' . $url . ' --cookie "ci_session" ' . $cookie . ' "' . getcwd() . '\test.pdf"');
        echo "ok";
    }
}
