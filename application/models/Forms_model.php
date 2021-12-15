<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forms_model extends CI_Model
{
    public function create($data)
    {
        $type = $data['type'];
        unset($data['type']);
        $condition = "number LIKE '%" . $data['number'] . "%' AND project='" . $data['project'] . "'";
        $this->db->select('*');
        $this->db->from($type . '_forms');
        $this->db->where($condition);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $data['number'] = $data['number'] . '_' . $query->num_rows();
        }
        $this->db->insert($type . '_forms', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        return null;
    }

    public function update($data)
    {
        $type = $data['type'];
        unset($data['type']);
        if ($this->db->table_exists($type . '_forms')) {
            $where = "id =" . $data['id'];
            $this->db->update($type . '_forms', $data, $where);
            if ($this->db->affected_rows() > 0) {
                echo ': RMA Updated!';
            } else {
                echo "ERROR: No new data!";
            }
        } else {
            echo "ERROR: table not exists! " . $type;
        }
    }

    function get($type = 'rma', $id = '',$project = '')
    {
        if ($this->db->table_exists($type . '_forms')) {
            if ($id != "") {
                $id = urldecode($id);
                $this->db->where("id = $id");
                $this->db->limit(1);
            }
            if ($project != "") {
                $this->db->where("project",$project);
            }
            $this->db->order_by('id', 'DESC');
            $this->db->from($type . '_forms');
            $query = $this->db->get();
            $response = $query->result_object();
            return $response;
        }
    }

    function paginate($type = 'rma', $project = "", $start = 0, $limit = 5)
    {
        if ($this->db->table_exists($type . '_forms')) {
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'DESC');
            if ($project != "") {
                $this->db->where("project",$project);
            }
            $this->db->from($type . '_forms');
            $query = $this->db->get();
            $response = $query->result_object();
            return $response;
        }
    }

    function trash($data)
    {
        $type = $data['type'];
        $where = "id =" . $data['id'];
        $data = array(
            'project' => 'Trash ' . $data['project']
        );
        $this->db->update($type . '_forms', $data, $where);
        if ($this->db->affected_rows() > 0) {
            echo 'OK: Moved to Trash!';
        } else {
            echo "ERROR: Not moved to Trash!";
        }
    }
}
