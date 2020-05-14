<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Production_model extends CI_Model
{

	public function addClient($data)
	{
		if ($this->db->table_exists('clients')) {
			// Query to check whether username already exist or not
			$condition = "name ='" . $data['name'] . "'";
			$this->db->select('*');
			$this->db->from('clients');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() == 0) {
				// Query to insert data in database
				$this->db->insert('clients', $data);
				if ($this->db->affected_rows() > 0) {
					return true;
				}
			} else {
				return false;
			}
		}
	}

	function getClients($id = '', $projects = '')
	{
		$response = array();
		if ($this->db->table_exists('clients')) {
			// Select record
			$this->db->select('*');
			$this->db->from('clients');
			if ($id != '') {
				$condition = "id ='$id'";
				$this->db->where($condition);
				$this->db->limit(1);
			}
			if ($projects != '') {
				$condition = "projects LIKE '%$projects%'";
				$this->db->where($condition);
				$this->db->limit(1);
			}
			$q = $this->db->get();
			$response = $q->result_array();
		}
		return $response;
	}

	public function editClient($data)
	{
		$where = "id ='" . $data['id'] . "'";
		$data = array('projects' => $data['projects']);
		return $this->db->update('clients', $data, $where);
	}

	function deleteClient($id)
	{
		$this->db->delete('wft_clients', array('id' => $id));
	}

	function getProjects($client_name = '')
	{
		$response = array();
		// Select record
		$this->db->select('*');
		$this->db->from('projects');
		if (!$client_name == '') {
			$condition = "client ='$client_name'";
			$this->db->where($condition);
		}
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
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

	function getProject($id = '', $name = '')
	{
		$response = array();
		$condition = "";
		// Select record
		$this->db->select('*');
		$this->db->from('projects');
		$condition = "id ='$id'";
		if (!$id == '') {
			$condition = "id ='$id'";
		}

		if (!$name == '') {
			$name = urldecode($name);
			$condition = "project ='" . $name . "'";
		}
		$this->db->where($condition);
		$this->db->limit(1);
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
	}

	public function editProject($data)
	{
		$where = "id =" . $data['id'];
		$data = array('data' => $data['data'],'template' => $data['template']);
		return $this->db->update('projects', $data, $where);
	}

	function deleteProject($id)
	{
		$this->db->delete('wft_projects', array('id' => $id));
	}

	public function addChecklist($data)
	{
		// Query to check whether serial already exist or not
		$condition = "serial ='" . $data['serial'] . "' AND project='" . $data['project'] . "'";
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
				$condition = "id ='$id'";
				$this->db->where($condition);
				$this->db->limit(1);
			}
			if ($project != '') {
				$project = urldecode($project);
				$condition = "project =\"$project\"";
				$this->db->where($condition);
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
			'qc' => $data['qc']
		);
		return $this->db->update('checklists', $data, $where);
	}

	function deleteChecklist($id)
	{
		$this->db->delete('wft_checklists', array('id' => $id));
	}

	function getLastChecklist($project)
	{
		if ($this->db->table_exists('checklists')) {
			$project = urldecode($project);
			$condition = "project =\"$project\"";
			$this->db->select('*');
			$this->db->from('checklists');
			$this->db->where($condition);
			$this->db->order_by('id', 'DESC');
			$this->db->limit(1);
			$q =$this->db->get();
			$response = $q->result_array();
			return $response[0]['serial'];
		}
	}

	public function get_current_checklists_records($limit, $start,$project) 
    {
		$this->db->limit($limit, $start);
		if ($project != '') {
			$project = urldecode($project);
			$condition = "project =\"$project\"";
			$this->db->where($condition);
		}
		$this->db->order_by('id', 'DESC');
        $query = $this->db->get("checklists");
 
        if ($query->num_rows() > 0) 
        {
            foreach ($query->result() as $row) 
            {
                $data[] = $row;
            }
             
            return $data;
        }
 
        return false;
    }
     
    public function get_total($project='') 
    {
		if ($project != '') {
			$this->db->from('checklists');
			$project = urldecode($project);
			$this->db->where('project',$project);
		}
        return $this->db->count_all_results();
    }
}
