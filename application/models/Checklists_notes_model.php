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

    public function get_all()
    {
        $query = $this->db->get('checklists_notes');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $query = $this->db->get_where('checklists_notes', array('id' => $id));
        return $query->result();
    }

    public function insert($data)
    {
        $this->db->insert('checklists_notes', $data);
        return $this->db->insert_id() > 0 ? $this->db->insert_id() : false;
    }

    public function update($data)
    {
        $this->db->update('checklists_notes', $data, array('id' => $data['id']));
        return $this->db->affected_rows() > 0 ? true : false;
    }
}
