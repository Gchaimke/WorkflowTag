<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
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
            'roles' => array(
                'type' => 'TEXT'
            ),
            'language' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
        );

        $this->dbforge->add_field($project);
        // define primary key
        $this->dbforge->add_key('id', TRUE);
        // create table
        $this->dbforge->create_table('settings');

        $st = array(
            'roles' => 'Admin,Assembler,QC'
        );
        $this->db->insert('settings', $st);
    }

    function getSettings()
    {
        $response = array();
        // Select record
        $this->db->select('*');
        $this->db->from('settings');
        $query = $this->db->get();
        $response = $query->result_array();
        return $response;
    }

    public function save_settings($data)
    {
        $where = "id =1";
        return $this->db->update('settings', $data, $where);
    }

    function getStatistic()
    {
        $response = array();
        //get users number
        if ($this->db->table_exists('users')) {
            $response['users']  = $this->db->count_all("users");
        }
        //get clients number
        if ($this->db->table_exists('clients')) {
            $response['clients'] = $this->db->count_all("clients");
        }
        //get checklists number
        if ($this->db->table_exists('checklists')) {
            $response['checklists'] = $this->db->count_all("checklists");
        }
        if ($this->db->table_exists('rma_forms')) {
            $response['rma_forms'] = $this->db->count_all("rma_forms");
        }
        return $response;
    }

    public function get_current_checklists_records($limit, $start, $project, $table = 'checklists')
    {
        $this->db->limit($limit, $start);
        if ($project != '') {
            $project = urldecode($project);
            $condition = "project LIKE \"$project%\"";
            $this->db->where($condition);
        }
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return  false;
    }

    public function get_total($project = '', $table = 'checklists')
    {
        if ($project != '') {
            $this->db->from($table);
            $project = urldecode($project);
            $condition = "project LIKE '$project%'";
            $this->db->where($condition);
        }
        return $this->db->count_all_results();
    }

    function deleteChecklist($id, $table = 'checklists')
    {
        $this->db->delete($table, array('id' => $id));
    }

    function restore_from_trash($data, $table = 'checklists')
    {
        $condition = "serial ='" . $data['serial'] . "' and project NOT LIKE 'Trash%'";
        $this->db->select('*');
        $this->db->from('Checklists');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            $where = "id =" . $data['id'];
            $project = str_replace('Trash ', '', $data['project']);
            $sqldata = array(
                'project' => $project
            );
            $this->db->update($table, $sqldata, $where);
            printf("Checklist %s restored to project %s!", $data['serial'], str_replace('Trash ', '', $data['project']));
        } else {
            printf("Serial number %s exists in project %s!", $data['serial'], str_replace('Trash ', '', $data['project']));
        }
    }

    // $fields can be named array
    public function add_column($table,$fields)
    {
        $this->load->dbforge();
        $this->dbforge->add_column($table, $fields);
    }

    public function remove_column($table,$field)
    {
        $this->load->dbforge();
        $this->dbforge->drop_column($table, $field);
    }
}
