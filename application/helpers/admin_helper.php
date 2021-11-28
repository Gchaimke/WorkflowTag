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

function add_fields_to_table($controller, $fields, $table_name)
{
	$first_field_name = array_key_first($fields);
	if (!$controller->db->field_exists($first_field_name, $table_name)) {
		$controller->load->dbforge();
		$controller->dbforge->add_column($table_name, $fields);
		return true;
	} else {
		return false;
	}
}

function modify_field_table($controller, $old_field_name, $new_field_name, $table_name)
{
	if ($controller->db->field_exists($old_field_name,)) {
		$field = array(
			$old_field_name => array(
				'name' => $new_field_name,
			),
		);
		$controller->load->dbforge();
		$controller->dbforge->modify_column($table_name, $field);
		return true;
	} else {
		return false;
	}
}

function remove_field_from_table($controller, $field_name, $table_name)
{
	if ($controller->db->field_exists($field_name, $table_name)) {
		$controller->load->dbforge();
		$controller->db->remove_column($table_name, $field_name);
		return true;
	} else {
		return false;
	}
}
