<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends CI_Model
{
	function search($search = '', $table = 'checklists')
	{
		if ($search != "") {
			$search = urldecode($search);
			$search = trim($search);
			$this->db->select('*');
			$this->db->from($table);
			$type = "checklist";
			if ($table == 'checklists') {
				$this->db->like("serial", $search);
				$type = "checklist";
			}
			if ($table == 'rma_forms') {
				$this->db->like("number", $search);
				$this->db->or_like("serial", $search);
				$type = "rma";
			}
			if ($table == 'qc_forms') {
				$this->db->like("number", $search);
				$this->db->or_like("serial", $search);
				$type = "qc";
			}
			if ($table == 'checklists_notes') {
				$this->db->like("checklist_sn", $search);
				$this->db->or_like("note", $search);
				$type = "note";
			}
			$this->db->order_by('project');
			$q = $this->db->get();
			$response['data'] = $q->result_array();
			$response['type'] = $type;
			return $response;
		}
	}
}
