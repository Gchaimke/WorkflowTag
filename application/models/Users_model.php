<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	function getUsers()
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->order_by('name');
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
	}

	public function editUser($data)
	{
		if (isset($data['password'])) {
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		}
		$where = "id =" . $data['id'];
		$this->db->update('users', $data, $where);
		if ($this->db->affected_rows() > 0) {
			return 'User data updated!';
		}
	}

	function deleteUser($id)
	{
		$this->db->delete('Users', array('id' => $id));
	}

	// Insert registration data in database
	public function registration_insert($data)
	{
		// Query to check whether name already exist or not
		$condition = "name =" . "'" . $data['name'] . "'";
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			// Query to insert data in database
			$this->db->insert('Users', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		} else {
			return false;
		}
	}

	// Read data using name and password
	public function login($data)
	{
		$condition = "name ='" . $data['name'] . "'";
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		$row = $query->row_array();
		if ($query->num_rows() == 1) {
			if (password_verify($data['password'], $row['password'])) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Read data from database to show data in admin page
	public function read_user_information($name)
	{
		$this->db->select('*');
		$this->db->from('Users');
		$condition = "name =" . "'" . $name . "'";
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	function getUser($id)
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('users');
		$condition = "id ='" . $id . "'";
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result_array();
		} else {
			return false;
		}
	}
}
