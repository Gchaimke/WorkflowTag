<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rma_model extends CI_Model
{

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
