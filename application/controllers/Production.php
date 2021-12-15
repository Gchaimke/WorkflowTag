<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production extends CI_Controller
{
    private $user;
    private $system_models = array(
        'Admin' => 'settings',
        'Clients' => 'clients',
        'Checklists_notes' => 'checklists_notes',
        'Production' => 'checklists',
        'Qc' => 'qc_forms',
        'Rma' => 'rma_forms',
        'Projects' => 'projects',
        'Users' => 'users',
    );

    public $clients, $users, $users_names, $clients_names;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('admin');

        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            $this->lang->load('main', $this->user['language']);
            $this->languages = array("english", "hebrew");
        } else {
            header("location: /users/login");
            exit('User not logedin');
        }

        // Load models
        foreach ($this->system_models as $model => $table) {
            $this->load->model($model . '_model');
        }
        $this->load->library('pagination');

        $this->clients = $this->Clients_model->getClients();
        $this->users = $this->Users_model->getUsers();
        foreach ($this->clients as $client) {
            $this->clients_names[$client['id']] = $client['name'];
        }
        foreach ($this->users as $user) {
            $this->users_names[$user['id']] = $user['name'];
        }
    }

    public function index()
    {
        $data = array();
        $clients = $this->clients;
        foreach ($clients as $client) {
            $client_users = explode(",", $client['users']);
            if (!in_array($this->user['id'], $client_users)) continue;
            $data['clients'][$client["name"]]['projects'] = $this->Projects_model->getProjects($client['name']);
            $data['clients'][$client["name"]]['status'] = $client['status'];
            $data['clients'][$client["name"]]['id'] = $client['id'];
            $data['clients'][$client["name"]]['logo'] = $client['logo'];
        }
        $this->view_page('production/view_clients', $data);
    }

    function view_page($page_name = 'view_clients', $page_data = '', $menu_parameters = '')
    {
        $this->load->view('header');
        $this->load->view('main_menu', $menu_parameters);
        $this->load->view($page_name, $page_data);
        $this->load->view('footer');
    }

    public function checklists($limit_per_page = 20)
    {
        // init params
        $params = array();
        $config = array();
        $client_id = isset($_GET["client"]) ? $_GET["client"] : null;
        $project = isset($_GET["project"]) ? $_GET["project"] : null;
        $start = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
        $total_records = $this->Production_model->get_total($project);
        $params['users'] = array_column($this->users, 'name');
        $params['project'] = $project;
        $params['client'] = $this->Clients_model->get_client_by_id($client_id);
        $params['client'] = $params['client'] ? $params['client'] : array("name" => "error");
        if (isset($this->Projects_model->getProject('', $project)['template'])) {
            $params['template'] = $this->Projects_model->getProject('', $project)['template'];
        } else {
            $params['template'] = " - not set!";
        }

        if ($total_records > 0) {
            $params["results"] = $this->Production_model->get_current_checklists_records($limit_per_page, $start, $project);
            $config['base_url'] = base_url() . "production/checklists";
            $config['total_rows'] = $total_records;
            $config['per_page'] = $limit_per_page;
            $this->pagination->initialize($config);
            $params["links"] = $this->pagination->create_links();
        }
        $this->view_page('production/manage_checklists', $params, $params);
    }

    public function add_checklist($project = '', $data = '')
    {
        $data = array();
        // Check validation for user input in SignUp form
        $zero_str = implode(",", array_fill(0, 400, ""));
        $project = $this->Projects_model->getProject('', $this->input->post('project'));
        $serial = trim($this->input->post('serial'));
        $data = array(
            'client' => $this->input->post('client'),
            'client_id' => $this->input->post('client_id'),
            'project' => $project['project'],
            'serial' => $serial,
            'version' => $project['checklist_version'],
            'paka' =>  $this->input->post('paka'),
            'data' =>  $zero_str,
            'date' => $this->input->post('date')
        );
        $result = $this->Production_model->addChecklist($data);
        if ($result == TRUE) {
            echo '1';
            admin_log("created {$project['project']} checklist with serial '$serial'", 1, $this->user['name']);
        } else {
            echo 'Checklist ' . $data['serial'] . ' exists!';
        }
    }

    // Validate and store checklist data in database 
    public function gen_checklists()
    {
        $result = 'Serial template not set!';
        $project = $this->Projects_model->getProject('', $this->input->post('project'));
        $date = $this->input->post('date');
        $serials = $this->build_serials($project['project'], $date, $this->input->post('count'));
        if ($serials) {
            foreach ($serials as $serial) {
                $data = array(
                    'client_id' => $this->input->post('client_id'),
                    'client' => $this->input->post('client'),
                    'project' => $project['project'],
                    'serial' => $serial,
                    'version' => $project['checklist_version'],
                    'paka' =>  $this->input->post('paka'),
                    'data' =>  implode(",", array_fill(0, 400, "")),
                    'date' =>  $date
                );
                $result = $this->Production_model->addChecklist($data);
                if ($result != 1) {
                    echo 'Checklist ' . $data['serial'] . ' exists!';
                }
                if ($result == true) {
                    admin_log("created {$project['project']} checklist with serial '$serial'", 1, $this->user['name']);
                }
            }
        }
        echo $result;
    }

    private function build_serials($project, $date, $count)
    {
        $serials = array();
        $dfend_month = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => 'A', '11' => 'B', '12' => 'C');
        $xcount_arr = array("xxxx", "xxx", "xx");
        $serial_project = $this->Projects_model->getProject('', $project);
        $last_serial = $this->Production_model->getLastChecklist($project);
        $month = date('m', strtotime($date));
        $year = date('y', strtotime($date));
        $week = date('W', strtotime($date));
        if (isset($serial_project['template']) &&  $serial_project['template'] != "") {
            $serial = $serial_project['template']; //Get serial template
            $prev_month = substr($last_serial, strpos($serial, 'm'), substr_count($serial, 'm'));
            if ($serial_project["restart_serial"] != null && $prev_month !=  $month) {
                $last_serial = "00000000000000000";
            }
            $serial = str_replace("yy", $year, $serial); //add year
            $serial = str_replace("mm", $month, $serial); //add month with zero
            $serial = str_replace("dm", $dfend_month[$month], $serial); //add month from dfend array
            $serial = str_replace("ww", $week, $serial); //add week number
            $serial_end = substr($last_serial, strpos($serial, 'x'), substr_count($serial, 'x')) + 0; // false = 00000000000000000
            for ($i = 1; $i <= $count; $i++) {
                $serial_end++;
                $zero_count = $this->zero_count(substr_count($serial, 'x'), $serial_end);
                $serials[] = str_replace($xcount_arr, $zero_count, $serial);
            }
            return $serials;
        }
        return false;
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

    public function edit_checklist($id = '')
    {
        $data = array();
        $data['js_to_load'] = array("edit_checklist.js?" . filemtime('assets/js/edit_checklist.js'));
        $data['checklist'] =  $this->Production_model->getChecklists($id);
        if ($data['checklist']) {
            $data['checklist'] = $data['checklist'][0];
            $project = $this->Projects_model->getProject('', $data['checklist']['project']);
            $data['checklist']['version'] = $data['checklist']['version'] ? $data['checklist']['version'] : $project['checklist_version'];
            if (isset($project['project'])) {
                $data['project'] =  urldecode($project['project']);
                $data['checklist_rows'] = $this->build_checklist($project['project'], $data['checklist']);
                $data['scans_rows'] = $this->build_scans($project['project'], $data['checklist']['scans']);
                $data['client'] = $this->Clients_model->get_client_by_id($data['checklist']['client_id']);
                $data['users'] = $this->users;
                $data['notes'] = $this->get_qc_notes($id);
                $this->view_page('production/edit_checklist', '', $data);
            } else {
                exit("Checklist not found, Deleted?");
            }
        } else {
            echo "Checklist not found, Deleted?";
        }
    }

    public function edit_batch($msg = '')
    {
        $ids = isset($_GET['checklists']) ? $_GET['checklists'] : '';
        $data = array();
        if ($msg != '') {
            $data['message_display'] = $msg;
        }
        $data['ids'] = $ids;
        $data['js_to_load'] = array("edit_checklist.js?" . filemtime('assets/js/edit_checklist.js'));
        $data['checklists'] =  $this->Production_model->getChecklists($ids);

        if ($data['checklists']) {
            $data['checklist'] = $data['checklists'][0];
            $project = $this->Projects_model->getProject('', $data['checklist']['project']);
            $data['checklist']['version'] = $data['checklist']['version'] ? $data['checklist']['version'] : $project['checklist_version'];
            $data['project'] =  urldecode($project['project']);
            $data['checklist_rows'] = $this->build_checklist($project['project'], $data['checklist']);
            $data['client'] = $this->Clients_model->get_client_by_id($data['checklist']['client_id']);
            $this->view_page('production/edit_batch', '', $data);
        }
    }

    function get_checklist_version($version)
    {
        if ($version != "") {
            if (file_exists($version)) {
                return file_get_contents($version);
            }
        }
        return false;
    }

    private function build_checklist($project, $checklist)
    {
        $prefix_count = 0;
        $checked = "";
        $table = '';
        $select_users = '';

        if (is_array($this->Projects_model->getProject('', $project))) {
            if ($checklist['version'] != "") {
                $checklist_rows = $this->get_checklist_version($checklist['version']);
            } else {
                $checklist_rows =  $this->Projects_model->getProject('', $project)['data'];
            }
            $rows = explode(PHP_EOL, $checklist_rows);
            $status = explode(",", $checklist['data']);
            $index = 0;
            $id = 0;
            foreach ($this->users as $user) {
                if ($user['role'] != 'Wearhouse') {
                    $select_users .= "<option value=" . $user['name'] . ">" . $user['name'] . "</option>";
                }
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
                        $tr = '<table id="checklist" class="table table-striped table-hover"><thead class="table-dark">' . '<tr><th scope="col">#</th><th id="result" scope="col">' . $col[0] . '</th>';
                        for ($j = 1; $j < count($col) - 1; $j++) {
                            $tr .= '<th scope="col">' . $col[$j] . '</th>';
                        }
                        $tr .= '</tr></thead><tbody>';
                        $index = 1;
                        $prefix_count++;
                    } else if (end($col) == "QC") {
                        $tr .= "<tr class='qc_row table-warning'><th scope='row' style=\"width: 10%;\">$prefix$index</th><td class='description' colspan='2'>" . $col[0];
                        $tr .= "<select class='form-select review' id='" . ($id + count($rows)) . "'><option value='0'>Select</option>";
                        $tr .= $select_users . "</select></td></tr>" . PHP_EOL;
                        $index++;
                        $id++;
                    } else if (end($col) == "I") {
                        $tr .= "<tr class='input_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0];
                        $tr .= "</td><td style=\"width: 20%;\"><input type='text' class='form-control input' id='" . ($id + count($rows)) . "'></td></tr>" . PHP_EOL;
                        $index++;
                        $id++;
                    } else if (end($col) == "N") {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td>";
                        $tr .= "<td class='row m-0'><div class='checkbox col-md-6'><input type='checkbox' class='verify'  id='$id' $checked></div>";
                        $tr .= "<div class='col-md-6'><select class='form-select review' id='" . ($id + count($rows)) . "'><option value='0'>Select</option>";
                        $tr .= $select_users . "</select></div></td></tr>" . PHP_EOL;
                        $index++;
                        $id++;
                    } else {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td><td>" .
                            "<div class='checkbox'><input type='checkbox' class='verify form-check-input' id='$id' $checked></div></td></tr>" . PHP_EOL;
                        $index++;
                        $id++;
                    }
                } else {
                    if ($col[0] != "") {
                        $tr = "<tr class='check_row'><th scope='row'>$prefix$index</th><td class='description'>" . $col[0] . "</td><td>" .
                            "<div class='checkbox'><input type='checkbox' class='verify form-check-input' id='$id' $checked></div></td></tr>" . PHP_EOL;
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

    public function save_checklist($id = '')
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_checklist($id);
        } else {
            $data = array(
                'id' =>  $id,
                'data' =>  $this->input->post('data'),
                'version' => $this->input->post('version'),
                'log' =>  $this->input->post('log'),
                'progress' => $this->input->post('progress'),
                'assembler' => $this->input->post('assembler'),
                'qc' => $this->input->post('qc'),
                'note' => $this->input->post('note'),
                'pictures' => $this->input->post('pictures')
            );
            $this->Production_model->editChecklist($data);
            if ($this->input->post('progress') == 100) {
                $data['serial'] = $this->input->post('serial');
                $data['client'] = $this->input->post('client');
                $data['project'] = $this->input->post('project');
                $data['date'] = $this->input->post('date');
                $data['version'] = $this->input->post('version');
                $data['logo'] = $this->input->post('logo');
                $data['scans'] = $this->input->post('scans');
                $this->generate_offline_files($data);
            }
            $this->Users_model->update_user_log($id, $this->input->post('serial'), $this->input->post('client_id'));
            echo 'Checklist saved successfully!';
        }
    }

    public function save_batch_checklists($ids = '')
    {
        // Check validation for user input in SignUp form
        $this->form_validation->set_rules('data', 'Data', 'trim|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_batch($ids);
        } else {
            $ids_arr = explode(':', $ids);
            foreach ($ids_arr as $id) {
                $data = array(
                    'id' =>  $id,
                    'data' =>  $this->input->post('data'),
                    'version' => $this->input->post('version'),
                    'log' =>  $this->input->post('log'),
                    'progress' => $this->input->post('progress'),
                    'assembler' => $this->input->post('assembler'),
                    'qc' => $this->input->post('qc')
                );
                $this->Production_model->batchEditChecklist($data);
            }
            echo 'Checklists saved successfully!', $ids;
            $log_label = "Batch " . $this->input->post('project') . " " . date("d_m") . " (" . count($ids_arr) . ")";
            $this->Users_model->update_user_log($ids, $log_label, $this->input->post('client_id'));
            //$this->edit_batch($ids, $message_display);
        }
    }

    public function trashChecklist()
    {
        $project = $this->input->post('project');
        $serial = $this->input->post('serial');
        $data = array(
            'id' =>  $this->input->post('id'),
            'project' => $project
        );
        $this->Production_model->move_to_trash($data);
        admin_log("trashed '$project' checklist with serial '$serial'", 2, $this->user['name']);
    }

    public function save_photo()
    {
        // requires php5
        $serial = $_POST['serial'];
        $num = $_POST['num'];
        $upload_folder = $_POST['working_dir'];
        $img = $_POST['data'];
        if (preg_match('/^data:image\/(\w+);base64,/', $img, $type)) {
            $img = substr($img, strpos($img, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                print 'invalid image type';
                throw new \Exception('invalid image type');
            }
            $img = base64_decode($img);
            if ($img === false) {
                print 'base64_decode failed';
                throw new \Exception('base64_decode failed');
            }
        } else {
            print 'did not match data URI with image data';
            throw new \Exception('did not match data URI with image data');
        }

        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0770, true);
        }
        $file = $upload_folder  . $serial . "_" . $num . ".$type";
        if (!file_exists($file)) {
            $success = file_put_contents($file, $img);
        } else {
            $num++;
            $file = $upload_folder  . $serial . "_" . $num . ".$type";
            $success = file_put_contents($file, $img);
        }

        if ($success) {
            $this->compressImage($file, $file, 60);
        }
        print $success ? $file : 'Unable to save the file.';
    }

    // Compress image
    function compressImage($source, $destination, $quality)
    {
        $max_w = 1920;
        $max_h = 1080;

        list($orig_width, $orig_height) = getimagesize($source);
        $width = $orig_width;
        $height = $orig_height;

        # taller
        if ($height > $max_h) {
            $width = ($max_h / $height) * $width;
            $height = $max_h;
        }

        # wider
        if ($width > $max_w) {
            $height = ($max_w / $width) * $height;
            $width = $max_w;
        }

        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }

        $resized = imagecreatetruecolor($width, $height);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0,  0, 0, $width, $height, $orig_width, $orig_height);
        if ($info['mime'] == 'image/jpeg') {
            imagejpeg($resized, $destination, $quality);
        } elseif ($info['mime'] == 'image/gif') {
            imagegif($resized, $destination);
        } elseif ($info['mime'] == 'image/png') {
            imagepng($resized, $destination, 7);
        }
        //unlink($source);
    }


    public function delete_photo()
    {
        $this->form_validation->set_rules('photo', 'Photo', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $photo = $this->input->post('photo');
            // Use unlink() function to delete a file  
            if (!unlink($_SERVER["DOCUMENT_ROOT"] . $photo)) {
                echo ($_SERVER["DOCUMENT_ROOT"] . $photo . " cannot be deleted due to an error");
            } else {
                echo ($_SERVER["DOCUMENT_ROOT"] . $photo . " has been deleted");
                admin_log('deleted ' . $photo, 3, $this->user['name']);
            }
        }
    }

    function update_picture_count($id)
    {
        $this->form_validation->set_rules('picture_count', 'picture count', 'trim|xss_clean');
        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'id' =>  $id,
                'pictures' =>  $this->input->post('count')
            );
            echo $this->Production_model->update_picture_count($data);
        } else {
            echo "Can't update pictures count!";
        }
    }

    //** OFFLINE FILES */
    function generate_offline_files(array $checklist)
    {
        $folder_path = "Uploads" .
            DIRECTORY_SEPARATOR . $checklist['client'] .
            DIRECTORY_SEPARATOR . $checklist['project'] .
            DIRECTORY_SEPARATOR . $checklist['serial'];
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0770, true);
        }
        copy('assets/css/offline.css', $folder_path . DIRECTORY_SEPARATOR . "offline.css");
        if ($checklist['logo'] != "") {
            copy("." . $checklist['logo'], $folder_path . DIRECTORY_SEPARATOR . "logo.png");
        }
        $js = "
        var checkRows = $('.check_row');
        var inputRows = $('input.input');
        var selectRows = $('select.review');
        var scanRows = $('.scan_row');
        var chArray = data.split(',');
        var scansArray = [];
        scansArray = scans.split(\";\").map(function (e) {
            return e.split(\",\");
        });
        checkRows.each(function () {
            if ($(this).find('input').prop('checked')) {
                $(this).find('input').after(\"<div class='badge badge-secondary check-lable'>\" + chArray[$(this).find('input').attr('id')] + '</div>');
            }
        });
        inputRows.each(function () {
            $(this).val(chArray[this.id]).prop( 'disabled', true );
        });
    
        selectRows.each(function () {
            if (chArray[this.id]) {
                $(this).val(chArray[this.id]).prop( 'disabled', true );
            } else {
                $(this).val('Select');
            }
        });
        scanRows.each(function () {
            var id = $(this).closest('tr').attr('id');
            if (scansArray[id]) {
                $(this).find(\"input:eq(0)\").val(scansArray[id][0]);
                $(this).find(\"input:eq(1)\").val(scansArray[id][1]);
            } else {
                scansArray.splice(id, 0, [\"\", \"\"]);;
            }
        });";
        $html_file = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $folder_path . DIRECTORY_SEPARATOR . "index.html";
        $checklist_table = $this->build_checklist($checklist['project'], $checklist);
        $scans_table = $this->build_scans($checklist['project'], $checklist['scans']);
        $fp = fopen($html_file, 'w');
        fwrite($fp, "<!DOCTYPE html><html lang='en' xml:lang='en' xmlns='http://www.w3.org/1999/xhtml'>" . PHP_EOL);
        fwrite($fp, "<head><title>SN:" . $checklist['serial'] . " - " . $checklist['project'] . "</title>" . PHP_EOL);
        fwrite($fp, "<link href='offline.css' rel='stylesheet'>" . PHP_EOL);
        fwrite($fp, "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>" . PHP_EOL);
        fwrite($fp, "</head><body>" . PHP_EOL);
        fwrite($fp, "<img id='logo' src='logo.png'>" . PHP_EOL);
        fwrite($fp, "<div class='header'>" . PHP_EOL);
        fwrite($fp, "<span id='project'>Project: " . $checklist['client'] . " - " . $checklist['project'] . "</span>" . PHP_EOL);
        fwrite($fp, "<span id='serial'>SN: " . $checklist['serial'] . "</span>" . PHP_EOL);
        fwrite($fp, "<span id='date'>DATE: " . $checklist['date'] . "</span>" . PHP_EOL);
        fwrite($fp, "</div>" . PHP_EOL);
        fwrite($fp, "<div class='content'>" . $checklist_table . "</div>" . PHP_EOL);
        fwrite($fp, "<div class='content'>" . $scans_table . "</div>" . PHP_EOL);
        fwrite($fp, "<h2>Pictures</h2>" . PHP_EOL);
        fwrite($fp, "<div class='gallery'>" . $this->get_photos_as_html($folder_path) . "</div>" . PHP_EOL);
        fwrite($fp, "<script>" . PHP_EOL);
        fwrite($fp, "var client='" . $checklist['client'] . "';" . PHP_EOL);
        fwrite($fp, "var project='" . $checklist['project'] . "';" . PHP_EOL);
        fwrite($fp, "var serial='" . $checklist['serial'] . "';" . PHP_EOL);
        fwrite($fp, "var serial='" . $checklist['assembler'] . "';" . PHP_EOL);
        fwrite($fp, "var data=`" . $checklist['data'] . "`;" . PHP_EOL);
        fwrite($fp, "var scans=`" . $checklist['scans'] . "`;" . PHP_EOL);
        fwrite($fp, $js . PHP_EOL);
        fwrite($fp, "</script>" . PHP_EOL);
        fwrite($fp, "</body></html>" . PHP_EOL);
        fclose($fp);
    }

    function get_photos_as_html($dir)
    {
        $files = array_diff(scandir($dir), array('..', '.', 'index.html', 'offline.css', 'logo.png'));;
        $html = '';
        if ($files) {
            foreach ($files as $file) {
                $html .= "<img src='$file'/>" . PHP_EOL;
            }
        }
        return $html;
    }

    public function generate_all_offline_files()
    {
        $all_checklists = $this->Production_model->getChecklists();
        $clients = $this->clients;
        $logos = array();
        foreach ($clients as $client) {
            $logos[$client['name']] = $client['logo'];
        }
        echo "<h2>Total checklists: " . count($all_checklists) . "</h2><br/>";
        $count_progress_100 = 0;
        foreach ($all_checklists as $checklist) {
            if (strpos($checklist['project'], "Trash") === false) {
                if ($checklist['progress'] > 90) {
                    $checklist['logo'] = $logos[$checklist['client']];
                    echo $checklist['serial'] . "<br>";
                    $this->generate_offline_files($checklist);
                    $count_progress_100++;
                }
            }
        }
        echo "<h2>Generated files for checklists with more than 90%: " . $count_progress_100 . "</h2><br/>";
    }


    //** QC NOTES */
    public function notes()
    {
        $limit = 20;
        $data = array();
        $data['notes'] = $this->Checklists_notes_model->get_all();
        $data['users'] = $this->users_names;
        $data['clients'] = $this->clients_names;
        $start = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
        if (count($data['notes']) > 0) {
            $config['base_url'] = base_url() . 'production/notes';
            $config['total_rows'] = count($data['notes']);
            $config['per_page'] = $limit;
            $this->pagination->initialize($config);
            $data["notes"] = $this->Checklists_notes_model->paginate($start, $limit);
            $data["links"] = $this->pagination->create_links();
        }
        $this->view_page('production/notes', $data);
    }

    public function edit_note($id = 0)
    {
        if ($id > 0) {
            $data = array();
            $data['note'] = $this->Checklists_notes_model->get(array('id' => $id));
            $data['users'] = $this->users_names;
            $this->view_page('production/edit_note', $data);
        } else {
            redirect('/production/notes');
        }
    }

    public function add_qc_note()
    {
        $result = $this->Checklists_notes_model->insert($this->input->post());
        if ($result) {
            $msg = "New note inserted with id: $result";
        } else {
            $msg = "Error: can't insert new data!";
        }
        echo $msg;
    }

    public function get_qc_notes($checklist_id)
    {
        return $this->Checklists_notes_model->get(array('checklist_id' => $checklist_id));
    }

    public function edit_qc_note()
    {
        $result = $this->Checklists_notes_model->update($this->input->post());
        if ($result) {
            $msg = "Note updated!";
        } else {
            $msg = "No new data!";
        }
        echo $msg;
    }

    public function trash_qc_note()
    {
        return $this->Checklists_notes_model->delete($this->input->post('id'));
    }


    //** Checklist scans */

    private function build_scans($project, $data)
    {
        $table = '';
        $tr = '';
        $columns = 0;
        $id = 0;
        $scans_arr = explode(',', $data);
        if (is_array($this->Projects_model->getProject('', $project))) {
            $project_scans = $this->Projects_model->getProject('', $project)['scans'];
            $rows = explode(PHP_EOL, $project_scans);
            if (count($rows) > 1) {
                $table .= '<center><h2> Scans Table</h2></center><table id="scans" class="table"><thead class="table-dark">';
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
                            if (isset($scans_arr[$i - 1])) {
                                $tr .= "<td><input type='text' class='form-control scans' name='scans[]' value='" . $scans_arr[$i - 1] . "'></td>";
                            } else {
                                $tr .= "<td><input type='text' class='form-control scans' name='scans[]'></td>";
                            }
                        }

                        $tr .=  "</tr>";
                        $table .= $tr;
                        $id++;
                    }
                }
                $table .= "</tbody></table>";
            }
        }
        return $table;
    }

    public function save_scans($id = 0)
    {
        if ($id != 0) {
            $scans_str = implode(',', $this->input->post('scans'));
            $data = array(
                'id' =>  $id,
                'scans' => $scans_str,
            );
            $this->Production_model->editChecklist($data);
            echo 'Scans saved successfully!';
        } else {
            echo 'ERROR: checklist id 0';
        }
    }

    public function export_csv($month = 1)
    {
        $file_name = date('d_m_y') . "_notes.csv";
        $notes = $this->Checklists_notes_model->get_all();
        $users = $this->users_names;
        $clients = $this->clients_names;
        header('Content-Encoding: UTF-8');
        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Pragma: no-cache");
        header("Expires: 0");
        $fp = fopen('php://output', 'w');
        fprintf($fp, "\xEF\xBB\xBF");
        $tmp_arr = array(array("Date", "Client", "Project", "Checklist SN", "Row", "Note", "Fault", "Action", "Assembler", "QC"));
        foreach ($notes as  $note) {
            if ($month != 13) {
                if (intval(date('m', strtotime($note->date))) != intval($month)) continue;
            }
            array_push($tmp_arr, array(
                $note->date,
                $clients[$note->client_id],
                $note->project,
                $note->checklist_sn,
                $note->row,
                $note->note,
                $note->fault,
                $note->action,
                $users[$note->assembler_id],
                $users[$note->qc_id]
            ));
        }
        foreach ($tmp_arr as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function test()
    {
    }
}
