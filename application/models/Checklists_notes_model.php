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
            'checklist_id' => array(
                'type' => 'INT',
                'constraint' => 5,
            ),
            'checklist_sn' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
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
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
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
            "checklist_id" => 1,
            "assembler_id" => 1,
            "qc_id" => 1,
            "client_id" => 1,
            "project" => "test",
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

    public function get(array $where)
    {
        $query = $this->db->get_where('checklists_notes', $where);
        if (count($query->result()) == 1) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function insert($data)
    {
        $data['date'] = date('Y-m-d H:i:s');
        $this->db->insert('checklists_notes', $data);
        return $this->db->insert_id() > 0 ? $this->db->insert_id() : false;
    }

    public function update($data)
    {
        $this->db->update('checklists_notes', $data, array('id' => $data['id']));
        return $this->db->affected_rows() > 0 ? true : false;
    }

    function delete($id)
    {
        $this->db->delete('checklists_notes', array('id' => $id));
    }
}
