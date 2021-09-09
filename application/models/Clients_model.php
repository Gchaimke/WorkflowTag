<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients_model extends CI_Model
{
    function createDb()
    {
        $this->load->dbforge();
        $client = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'projects' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'logo' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            )
        );

        $this->dbforge->add_field($client);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('clients');

        $cl = array(
            "name" => 'Avdor-HLT',
            "projects" => 'Project 1,Project 2',
            "logo" => '/assets/img/logo.png',
            'status' => 1
        );
        $this->db->insert('clients', $cl);
    }
    //id,name,projects,status,logo
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

    function getClients()
    {
        $response = array();
        if ($this->db->table_exists('clients')) {
            // Select record
            $this->db->select('*');
            $this->db->from('clients');
            $this->db->order_by('name');
            $q = $this->db->get();
            $response = $q->result_array();
        }
        return $response;
    }

    function get_client_by_id($id = "")
    {
        if ($id != "") {
            $this->db->select('*');
            $this->db->from('clients');
            $this->db->order_by('name');
            $this->db->where("id =$id");
            $q = $this->db->get();
            return $q->row_array();
        }
        return null;
    }

    function get_client_by_name($name = "")
    {
        if ($name != "") {
            $name = urldecode($name);
            $this->db->select('*');
            $this->db->from('clients');
            $this->db->order_by('name');
            $this->db->where("name ='$name'");
            $q = $this->db->get();
            return $q->row_array();
        }
        return null;
    }

    public function editClient($data)
    {
        $where = "id ='" . $data['id'] . "'";
        return $this->db->update('clients', $data, $where);
    }

    function deleteClient($id)
    {
        $this->db->delete('wft_clients', array('id' => $id));
    }
}
