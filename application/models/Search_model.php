<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends CI_Model
{
    function search($search = '',$table='checklists')
	{
		if ($search != "") {
				$search = urldecode($search);
				$this->db->select('*');
				$this->db->from($table);
                if ($table == 'checklists') {
                    $this->db->like("serial",$search);
                }
                if ($table == 'rma_forms' || $table == 'qc_forms') {
                    $this->db->like("number",$search);
                    $this->db->or_like("serial",$search);
                }
				$this->db->order_by('project');
				$q = $this->db->get();
				$response = $q->result_array();
				return $response;
			}
	}
}