<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    function createUsersDb()
    {
        $this->load->dbforge();
        $users = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
                'unique' => TRUE
            ),
            'role' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 40
            )
        );

        $this->dbforge->add_field($users);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('users');

        $admin = array(
            "name" => 'Admin',
            "role" => 'Admin',
            "password" => 'rom12345'
        );
        $this->db->insert('users', $admin);
    }

    function createClientsDb()
    {
        $this->load->dbforge();
        $client = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 60,
                'unique' => TRUE
            ),
            'projects' => array(
                'type' => 'VARCHAR',
                'constraint' => 500,
                'unique' => TRUE
            )
        );

        $this->dbforge->add_field($client);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('clients');

        $cl = array(
            "name" => 'Simbionix',
            "project" => 'Flex2'
        );
        $this->db->insert('clients', $cl);
    }

    function createChecklistDb()
    {
        $this->load->dbforge();
        $checklist = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'serial' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
            ),
            'client' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'data' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'progress' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE
            ),
            'assembler' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'qc' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'date' => array(
                'type' => 'DATE',
                'null' => FALSE
            )
        );
        $this->dbforge->add_field($checklist);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('checklists');

        $demoChecklist = array(
            "serial" => 'FL-0420-001',
            "client" => 'Simbionix',
            "project" => 'Flex2',
            "data" => '',
            "progress" => '0',
            "assembler" => 'Chaim',
            "qc" => 'Michael',
            "date" => '2020-04-30'
        );
        $this->db->insert('checklists', $demoChecklist);
    }

    function createProjectsDb()
    {
        $this->load->dbforge();
        $project = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'client' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => TRUE
            ),
            'data' => array(
                'type' => 'TEXT'
            )
        );

        $this->dbforge->add_field($project);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('projects');

        $tp = array(
            "client" => 'Simbionix',
            "project" => 'Flex2',
            "data" => '1,2,3,4,5'
        );
        $this->db->insert('projects', $tp);
    }

    function createSettingsDb()
    {
        $this->load->dbforge();
        $project = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'roles' => array(
                'type' => 'TEXT'
            )
        );

        $this->dbforge->add_field($project);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('settings');

        $st = array(
            'roles' => 'Admin,Assember,QC'
        );
        $this->db->insert('settings', $st);
    }

    function getSettings(){
        $response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('settings');
		$query = $this->db->get();
		$response = $query->result_array();
		return $response;
    }

    function getStatistic(){
        $response = array();
        //get users number
		$this->db->select('*');
		$this->db->from('users');
        $query = $this->db->get();
        $count = $query->result_array();
        $response['users'] = count($count);
		//get clients number
		$this->db->select('*');
		$this->db->from('clients');
        $query = $this->db->get();
        $count = $query->result_array();
        $response['clients'] = count($count);
        //get checklists number
		$this->db->select('*');
		$this->db->from('checklists');
        $query = $this->db->get();
        $count = $query->result_array();
        $response['checklists'] = count($count);
		return $response;
    }
}
