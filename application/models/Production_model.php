<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Production_model extends CI_Model
{

	public function addChecklist($data)
	{
		// Query to check whether serial already exist or not
		$condition = "serial ='" . $data['serial'] . "' and project NOT LIKE 'Trash%'";
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

	function getChecklists($id = '', $project = '')
	{
		$response = array();
		if ($this->db->table_exists('checklists')) {
			// Select record
			$this->db->select('*');
			$this->db->from('checklists');
			if ($id != '') {
				if (strpos($id, ':') == false) {
					$condition = "id ='$id'";
					$this->db->where($condition);
					$this->db->limit(1);
				} else {
					$id = str_replace(':', ',', $id);
					$condition = "id IN ($id)";
					$this->db->where($condition);
				}
			}
			if ($project != '') {
				$project = urldecode($project);
				$this->db->where("project =$project");
			}
			$q = $this->db->get();
			$response = $q->result_array();
		}
		return $response;
	}

	public function editChecklist($data)
	{
		$where = "id =" . $data['id'];
		$data = array(
			'data' => $data['data'],
			'log' => $data['log'],
			'progress' => $data['progress'],
			'assembler' => $data['assembler'],
			'qc' => $data['qc'],
			'scans' => $data['scans'],
			'pictures' => $data['pictures'],
			'note' => $data['note']
		);
		return $this->db->update('checklists', $data, $where);
	}

	public function batchEditChecklist($data)
	{
		$where = "id =" . $data['id'];
		$data = array(
			'data' => $data['data'],
			'log' => $data['log'],
			'progress' => $data['progress'],
			'assembler' => $data['assembler'],
			'qc' => $data['qc'],
		);
		return $this->db->update('checklists', $data, $where);
	}

	function move_to_trash($data, $table = 'checklists')
	{
		$where = "id =" . $data['id'];
		$data = array(
			'project' => 'Trash ' . $data['project']
		);
		$this->db->update($table, $data, $where);
		if ($this->db->affected_rows() > 0) {
			echo 'OK: Moved to Trash!';
		} else {
			echo "ERROR: Not moved to Trash!";
		}
	}

	function update_picture_count($data){
		$where = "id =" . $data['id'];
		$this->db->update('checklists', $data, $where);
		if ($this->db->affected_rows() > 0) {
			echo 'OK: Pictures count updated!';
		} else {
			echo "ERROR: Pictures count not updated!";
		}
	}

	function getLastChecklist($project)
	{
		$response = array();
		if ($this->db->table_exists('checklists')) {
			$project = urldecode($project);
			$condition = "project =\"$project\"";
			$this->db->select('*');
			$this->db->from('checklists');
			$this->db->where($condition);
			$this->db->order_by('id', 'DESC');
			$this->db->limit(1);
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
				$response = $q->result_array();
				return $response[0]['serial'];
			} else {
				return '00000000000000000';
			}
		}
	}

	function searchChecklist($sn = '')
	{
		if ($this->db->table_exists('checklists')) {
			if ($sn != "") {
				$sn = urldecode($sn);
				$condition = "serial LIKE '%$sn%'";
				$this->db->select('*');
				$this->db->from('checklists');
				$this->db->where($condition);
				$this->db->order_by('project');
				$q = $this->db->get();
				$response = $q->result_array();
				return $response;
			}
		}
	}

	public function get_current_checklists_records($limit, $start, $project,$table = 'checklists', $client = '')
	{
		$this->db->limit($limit, $start);
		if ($project != '') {
			$project = urldecode($project);
			$this->db->where("project ='$project'");
		}

		if ($client != '') {
			$client = urldecode($client);
			$this->db->where("client ='$client'");
		}
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($table);

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}

		return false;
	}

	public function get_total($project = '', $table = 'checklists', $client = '')
	{
		$this->db->from($table);
		if ($project != '') {
			$project = urldecode($project);
			$this->db->where("project ='$project'");
		}

		if ($client != '') {
			$client = urldecode($client);
			$this->db->where("client ='$client'");
		}
		return $this->db->count_all_results();
	}
}
