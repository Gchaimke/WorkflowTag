<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checklist_model extends CI_Model
{
	function getChecklists()
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$q = $this->db->get('Checklists');
		$response = $q->result_array();
		return $response;
	}

	function deleteChecklist($id)
	{
		$this->db->delete('wft_checklists', array('id' => $id));
	}

	public function insertNewChecklist($data)
	{
		// Query to check whether username already exist or not
		$condition = "serial =" . "'" . $data['serial'] . "'";
		$this->db->select('*');
		$this->db->from('Checklists');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			// Query to insert data in database
			$this->db->insert('Checklists', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		} else {
			return false;
		}
	}
}
