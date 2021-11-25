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

function add_fields_to_table($controller,$fields,$table_name)
	{
		//$controller->Admin_model->remove_column('rma_forms','recive_pictures');
		$field_name = 'user_roles';
		$table_name = 'settings';
		$fields = array(
			$field_name => array(
				'type' => 'TEXT',
			)
		);
		if (!$controller->db->field_exists($field_name, $table_name)) {
			$controller->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		} else {
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'fault';
		$table_name = 'checklists_notes';
		$fields = array(
			$field_name => array(
				'type' => 'TEXT'
			)
		);
		if (!$controller->db->field_exists($field_name, $table_name)) {
			$controller->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		} else {
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'checklist_version';
		$table_name = 'projects';
		$fields = array(
			'assembly' => array(
				'type' => 'VARCHAR',
				'constraint' => 500
			),
			'checklist_version' => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			)
		);

		if (!$controller->db->field_exists($field_name, $table_name)) {
			$controller->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
			$clients = $controller->Clients_model->getClients();
			foreach ($clients as $client) {
				$projects = $controller->Projects_model->getProjects($client['name']);
				foreach ($projects as $project) {
					$folder = "Uploads/{$client['name']}/{$project['project']}/";
					if (!file_exists($folder)) {
						mkdir($folder, 0770, true);
					}

					if (!file_exists($folder . "rev_1.txt")) {
						$assembly = fopen($folder . "rev_1.txt", "w");
						fwrite($assembly, $project['data']);
						fclose($assembly);
						printf("new version created %s checklist: %s<br>", $client['name'], $project['project']);
					}
					$project["checklist_version"] = $folder . "rev_1.txt";
					$controller->Projects_model->editProject($project);
				}
			}
		} else {
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'version';
		$table_name = 'checklists';
		$fields = array(
			$field_name => array(
				'type' => 'VARCHAR',
				'constraint' => 50
			)
		);
		if (!$controller->db->field_exists($field_name, $table_name)) {
			$controller->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		} else {
			echo "$field_name exists in $table_name.";
		}

		$field_name = 'language';
		$table_name = 'users';
		$fields = array(
			'language' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => TRUE
			),
			'projects' => array(
				'type' => 'VARCHAR',
				'constraint' => 60
			)
		);
		if (!$controller->db->field_exists($field_name, $table_name)) {
			$controller->Admin_model->add_column($table_name, $fields);
			echo "$field_name add to $table_name.";
		} else {
			echo "$field_name exists in $table_name.";
		}

		if ($controller->db->field_exists("projects", "users")) {
			$fields = array(
				'projects' => array(
					'name' => 'clients',
					'type' => 'TEXT',
				),
			);
			$controller->load->dbforge();
			$controller->dbforge->modify_column('users', $fields);
			echo "projects field now named clients";
		} else {
			echo "projects field is clients";
		}
	}
