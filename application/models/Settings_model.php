<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
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
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
                'unique' => TRUE
            ),
            'userrole' => array(
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
            "username" => 'Admin',
            "userrole" => 'Admin',
            "password" => 'rom12345'
        );
        $this->db->insert('users', $admin);
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
                'unique' => TRUE
            ),
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
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
            "project" => 'Flex2',
            "data" => '',
            "progress" => '0',
            "assembler" => 'Chaim',
            "qc" => 'Michael',
            "date" => '2020-04-30'
        );
        $this->db->insert('checklists', $demoChecklist);
    }
}
