<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	function getUsers()
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$q = $this->db->get('Users');
		$response = $q->result_array();
		return $response;
	}

	function deleteUser($id)
	{
		$this->db->delete('Users', array('id' => $id));
	}

	function insertNewuser($postData)
	{
		$response = "";
		if ($postData['txt_name'] != '' || $postData['txt_role'] != '' || $postData['txt_pass'] != '') {
			// Check entry
			$this->db->select('count(*) as allcount');
			$this->db->where('username', $postData['txt_name']);
			$q = $this->db->get('Users');
			$result = $q->result_array();
			if ($result[0]['allcount'] == 0) {
				// Insert record
				$newuser = array(
					"username" => trim($postData['txt_name']),
					"userrole" => trim($postData['txt_role']),
					"password" => trim($postData['txt_pass'])
				);
				// $this->db->insert( [table-name], Array )
				$this->db->insert('Users', $newuser);
				$response = 'User ' . $postData['txt_name'] . ' created successfully.';
			} else {
				$response = "Username already in use";
			}
		} else {
			$response = "Form is empty.";
		}
		return $response;
	}

	// Read data using username and password
	public function login($data)
	{
		$condition = "username =" . "'" . $data['username'] . "' AND " . "password =" . "'" . $data['password'] . "'";
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	// Read data from database to show data in admin page
	public function read_user_information($username)
	{
		$condition = "username =" . "'" . $username . "'";
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
}
