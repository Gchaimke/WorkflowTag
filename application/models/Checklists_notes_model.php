<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checklists_notes_model extends CI_Model
{
    function createDb()
    {
        $this->load->dbforge();
        $data = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'assembler_id' => array(
                'type' => 'INT',
                'constraint' => 3,
            ),
            'qc_id' => array(
                'type' => 'INT',
                'constraint' => 3
            ),
            'client_id' => array(
                'type' => 'INT',
                'constraint' => 3
            ),
            'project_id' => array(
                'type' => 'INT',
                'constraint' => 3
            ),
            'row' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'note' => array(
                'type' => 'TEXT'
            ),
            'date' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
        );
        $this->dbforge->add_field($data);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('checklists_notes');

        $demoData = array(
            "assembler_id" => 1,
            "qc_id" => 1,
            "client_id" => 1,
            "project_id" => 1,
            "row" => '1.03',
            "note" => 'Test note',
        );
        $this->db->insert('checklists_notes', $demoData);
    }
}
