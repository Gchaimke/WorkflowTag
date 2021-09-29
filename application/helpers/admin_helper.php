<?php

function admin_log($msg, $level = 0, $user)
{
    if (!file_exists('application/logs/admin')) {
        mkdir('application/logs/admin', 0770, true);
    }
    $level_arr = array('INFO', 'CREATE', 'TRASH', 'DELETE');
    $log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
    $fp = fopen($log_file, 'a'); //opens file in append mode  
    fwrite($fp, $level_arr[$level] . " - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
    fclose($fp);
}

function create_new_tables($controller)
{
    $system_models = array(
        'Admin' => 'settings',
        'Clients' => 'clients',
        'Checklists_notes' => 'checklists_notes',
        'Production' => 'checklists',
        'Qc' => 'qc_forms',
        'Rma' => 'rma_forms',
        'Projects' => 'projects',
        'Users' => 'users',
    );

    foreach ($system_models as $model => $table) {
        $controller->load->model($model . '_model');
    }
    $data = array();
    $data['response'] = '';
    foreach ($system_models as $model => $table) {
        $model_name = $model . '_model';
        if (!$controller->db->table_exists($table)) {
            $controller->$model_name->createDb();
            $data['response'] .= "$model_name Table: $table created!<br>" . PHP_EOL;
        } else {
            $data['response'] .= "$model_name Table: $table exists!<br>" . PHP_EOL;
        }
    }
    echo $data['response'];
}
