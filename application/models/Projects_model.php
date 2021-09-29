<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Projects_model extends CI_Model
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
            'client' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'project' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'data' => array(
                'type' => 'TEXT'
            ),
            'checklist_version' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'template' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'scans' => array(
                'type' => 'TEXT'
            ),
            'restart_serial' => array(
                'type' => 'INT',
				'constraint' => 1,
				'unsigned' => TRUE,
            ),
            'assembly' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            )
        );

        $this->dbforge->add_field($project);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('projects');

        $tp = array(
            "client" => 'Avdor-HLT',
            "project" => 'Project 1',
            "data" => "Assembly;Verify;HD\r\n verify V\r\n verify input;I\r\n verify name select;N\r\n QC verify;QC\r\n",
            "template" => 'Pxxx-mm-yy',
			'checklist_version' => "Uploads/Avdor-HLT/Project 1/rev_1.txt"
        );
        $this->db->insert('projects', $tp);
		if (!file_exists("Uploads/Avdor-HLT/Project 1")) {
			mkdir("Uploads/Avdor-HLT/Project 1", 0770, true);
			$assembly = fopen("Uploads/Avdor-HLT/Project 1/rev_1.txt", "w");
			fwrite($assembly,"Assembly;Verify;HD\n verify V\n verify input;I\n verify name select;N\n QC verify;QC\n");
			fclose($assembly);
		}
    }
	
	//id,client,project,data,template,scans
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
		$this->db->order_by('client');
		$q = $this->db->get();
		$response = $q->result_array();
		return $response;
	}

	public function addProjects($data)
	{
		// Query to check whether already exist or not
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where("project ='" . urldecode($data['project']) . "'");
		$this->db->where("client ='" . urldecode($data['client']) . "'");
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
		$response = $q->row_array();
		return $response;
	}

	public function editTemplate($data)
	{
		$where = "id =" . $data['id'];
		return $this->db->update('projects', $data, $where);
	}

	function deleteTemplate($id)
	{
		$this->db->delete('wft_projects', array('id' => $id));
	}

}
