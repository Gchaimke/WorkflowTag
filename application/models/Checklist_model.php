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

	function getChecklist($id = '')
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('checklists');
		$condition = "id ='" . $id . "'";
		$this->db->where($condition);
		$this->db->limit(1);
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
	}

	function getProjects($client_name = '')
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('projects');
		if (!$client_name == '') {
			$condition = "client =" . "'" . $client_name . "'";
			$this->db->where($condition);
		}
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
	}

	function getProject($id = '', $name = '')
	{
		$response = array();
		$condition = "";
		// Select record
		$this->db->select('*');
		$this->db->from('projects');
		$condition = "id ='" . $id . "'";
		if (!$id == '') {
			$condition = "id ='" . $id . "'";
		}

		if (!$name == '') {
			$condition = "project ='" . $name . "'";
		}
		$this->db->where($condition);
		$this->db->limit(1);
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
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

	public function addProject($data)
	{
		// Query to check whether username already exist or not
		$condition = "project ='" . $data['project'] . "'";
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			// Query to insert data in database
			$this->db->insert('projects', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		} else {
			return false;
		}
	}

	public function editProject($data)
	{
		$where = "id =" . $data['id'];
		$data = array('data' => $data['data']);
		return $this->db->update('projects', $data, $where);
	}

	public function updateChecklist($data)
	{
		$where = "id =" . $data['id'];
		$data = array(
			'data' => $data['data'],
			'progress' => $data['progress']
		);
		return $this->db->update('checklists', $data, $where);
	}

	function deleteChecklist($id)
	{
		$this->db->delete('wft_checklists', array('id' => $id));
	}

	function deleteProject($id)
	{
		$this->db->delete('wft_projects', array('id' => $id));
	}
}
