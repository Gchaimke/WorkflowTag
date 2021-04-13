<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Qc_model extends CI_Model
{
	function createDb()
	{
		$this->load->dbforge();
		$sql = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 9,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'date' => array(
				'type' => 'DATE',
				'null' => FALSE
			),
			'number' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'product_num' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'serial' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'client' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'project' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'user' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
			'problem' => array(
				'type' => 'TEXT'
			),
			'pictures' => array(
				'type' => 'INT',
				'constraint' => 2,
				'unsigned' => TRUE,
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => 1,
				'unsigned' => TRUE,
			),
		);

		$this->dbforge->add_field($sql);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('qc_forms');

		$demo = array(
			"date" => '2020-04-30',
			"number" => '5001',
			"serial" => 'P001-07-20',
			"client" => 'Avdor-HLT',
			"project" => 'Project 1',
			"user" => 'User',
			"problem" => 'client problem',
		);
		$this->db->insert('qc_forms', $demo);
	}
}
