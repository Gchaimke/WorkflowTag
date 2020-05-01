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

	function insertNewChecklist($postData)
	{
		$response = "";
		if ($postData['project'] != '' || $postData['serial'] != '') {
			// Check entry
			$this->db->select('count(*) as allcount');
			$this->db->where('serial', $postData['serial']);
			$q = $this->db->get('Checklists');
			$result = $q->result_array();
			if ($result[0]['allcount'] == 0) {
				// Insert record
				$newChecklist = array(
					'serial' => trim($postData['serial']),
					'project' => trim($postData['project']),
					'data' => trim($postData['data']),
					'progress' => trim($postData['progress']),
					'date' => trim($postData['date'])
				);
				// $this->db->insert( [table-name], Array )
				$this->db->insert('Checklists', $newChecklist);
				$response = 'Checklist for System '.$postData['serial'].' created successfully.';
			} else {
				$response = "Checklist serial number already exists";
			}
		} else {
			$response = "Form is empty.";
		}
		return $response;
	}
}
