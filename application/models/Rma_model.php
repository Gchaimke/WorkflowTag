<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rma_model extends CI_Model
{
    function createDb()
    {
        $this->load->dbforge();
        $rma = array(
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
            'serial' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'product_num' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'client' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'user' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'problem' => array(
                'type' => 'TEXT'
            ),
            'repair' => array(
                'type' => 'TEXT'
            ),
            'parts' => array(
                'type' => 'TEXT'
            ),
            'pictures' => array(
                'type' => 'TEXT'
            ),
            'status' => array(
                'type' => 'INT',
                'constraint' => 1,
                'unsigned' => TRUE,
            ), //new 
            'receive_comments' => array(
                'type' => 'TEXT',
            ),
            'accessories_list' => array(
                'type' => 'TEXT',
            ),
            'package' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'accessories' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'receive_pictures' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'failure_veriffication' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'other_failure' => array(
                'type' => 'TEXT',
            ),
            'qa_pics' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'closing_the_unit' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'full_unit_test' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'pack_unit_pics' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'pack_accessories' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'pack_accessories_pics' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'final_user' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
        );
        $this->dbforge->add_field($rma);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('rma_forms');

        $demoRMA = array(
            "date" => '2020-04-30',
            "number" => '5001',
            "serial" => 'P001-07-20',
            "client" => 'Avdor-HLT',
            "project" => 'Project 1',
            "user" => 'User',
            "problem" => 'client problem',
            "repair" => 'repair comment',
            "parts" => 'CPU:i7;RAM:8gb'
        );
        $this->db->insert('rma_forms', $demoRMA);
    }
}
