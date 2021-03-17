<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Qc_model extends CI_Model
{
	function createDb()
    {
        $this->load->dbforge();
        $project = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
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
			'assembler' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
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

        $this->dbforge->add_field($project);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('settings');

        $st = array(
            'roles' => 'Admin,Assembler,QC'
        );
        $this->db->insert('settings', $st);
    }

	public function create_qc($data)
	{
		// Query to check whether serial already exist or not
		$condition = "number LIKE '%" . $data['number'] . "%' AND project='" . $data['project'] . "'";
		$this->db->select('*');
		$this->db->from('qc_forms');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			$data['number'] = $data['number'] . '_' . $query->num_rows();
		}
		$out = $this->db->insert('qc_forms', $data);
		if ($this->db->affected_rows() > 0) {
			echo ' OK: New qc Created!';
		} else {
			echo $out;
		}
	}

	public function update_qc($data)
	{
		$where = "id =" . $data['id'];
		$this->db->update('qc_forms', $data, $where);
		if ($this->db->affected_rows() > 0) {
			echo 'OK: qc Updated!';
		} else {
			echo "ERROR: No new data!";
		}
	}

	function get_qc($id = '')
	{
		if ($this->db->table_exists('qc_forms')) {
			if ($id != "") {
				$id = urldecode($id);
				$condition = "id = $id";
				$this->db->select('*');
				$this->db->from('qc_forms');
				$this->db->where($condition);
				$this->db->limit(1);
				$q = $this->db->get();
				$response = $q->result_array();
				return $response;
			}
		}
	}

	function search_qc($sarch)
	{
		if ($this->db->table_exists('qc_forms')) {
			$condition = "serial LIKE '%$sarch%' OR number LIKE '%$sarch%'";
			$this->db->select('*');
			$this->db->from('qc_forms');
			$this->db->where($condition);
			$this->db->order_by('project');
			$q = $this->db->get();
			$response = $q->result_array();
			return $response;
		}
	}
}
