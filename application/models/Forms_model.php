<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forms_model extends CI_Model
{
    public function create($data)
    {
        // Query to check whether serial already exist or not
        $condition = "number LIKE '%" . $data['number'] . "%' AND project='" . $data['project'] . "'";
        $this->db->select('*');
        $this->db->from($data['type'].'_forms');
        $this->db->where($condition);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $data['number'] = $data['number'] . '_' . $query->num_rows();
        }
        $out = $this->db->insert($data['type'].'_forms', $data);
        if ($this->db->affected_rows() > 0) {
            echo ' OK: New RMA Created!';
        } else {
            echo $out;
        }
    }

    public function update($data)
    {
        if ($this->db->table_exists($data['type'] . '_forms')) {
            $where = "id =" . $data['id'];
            $this->db->update($data['type'].'_forms', $data, $where);
            if ($this->db->affected_rows() > 0) {
                echo 'OK: RMA Updated!';
            } else {
                echo "ERROR: No new data!";
            }
        }
    }

    function get($type = 'rma', $id = '')
    {
        if ($this->db->table_exists($type . '_forms')) {
            if ($id != "") {
                $id = urldecode($id);
                $this->db->where("id = $id");
                $this->db->limit(1);
            }
            //$this->db->select('*');
            $this->db->from($type . '_forms');
            $query = $this->db->get();
            $response = $query->result_object();
            return $response;
        }
    }
}
