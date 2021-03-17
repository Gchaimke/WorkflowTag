<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rma_model extends CI_Model
{
	function createRMADb()
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

	public function create_rma($data)
	{
		// Query to check whether serial already exist or not
		$condition = "number LIKE '%" . $data['number'] . "%' AND project='" . $data['project'] . "'";
		$this->db->select('*');
		$this->db->from('rma_forms');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			$data['number'] = $data['number'] . '_' . $query->num_rows();
		}
		$out = $this->db->insert('rma_forms', $data);
		if ($this->db->affected_rows() > 0) {
			echo ' OK: New RMA Created!';
		} else {
			echo $out;
		}
	}

	public function update_rma($data)
	{
		$where = "id =" . $data['id'];
		$this->db->update('rma_forms', $data, $where);
		if ($this->db->affected_rows() > 0) {
			echo 'OK: RMA Updated!';
		} else {
			echo "ERROR: No new data!";
		}
	}

	function get_rma($id = '')
	{
		if ($this->db->table_exists('rma_forms')) {
			if ($id != "") {
				$id = urldecode($id);
				$condition = "id = $id";
				$this->db->select('*');
				$this->db->from('rma_forms');
				$this->db->where($condition);
				$this->db->limit(1);
				$q = $this->db->get();
				$response = $q->result_array();
				return $response;
			}
		}
	}

	function search_rma($sarch)
	{
		if ($this->db->table_exists('rma_forms')) {
			$condition = "serial LIKE '%$sarch%' OR number LIKE '%$sarch%'";
			$this->db->select('*');
			$this->db->from('rma_forms');
			$this->db->where($condition);
			$this->db->order_by('project');
			$q = $this->db->get();
			$response = $q->result_array();
			return $response;
		}
	}
}
