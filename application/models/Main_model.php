<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model
{

    function dbCreate()
    {
        $this->load->dbforge();
        //$this->dbforge->create_database('ignit42', TRUE);
        //$this->db->query('use ignit42');
        // define table fields
        $fields = array(
            'userid' => array(
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
                'unique' => TRUE
            ),
            'userrole' => array(
                'type' => 'VARCHAR',
                'constraint' => 60
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => 40
            )
        );

        $this->dbforge->add_field($fields);
        // define primary key
        $this->dbforge->add_key('userid', TRUE);
        // create table
        $this->dbforge->create_table('Users');
    }

    function getUsers()
    {
        $response = array();

        // Select record
        $this->db->select('*');
        $q = $this->db->get('Users');
        $response = $q->result_array();

        return $response;
    }

    function insertNewuser($postData){
 
        $response = "";
       
        if($postData['txt_name'] !='' || $postData['txt_role'] !='' || $postData['txt_pass'] !='' ){
       
         // Check entry
         $this->db->select('count(*) as allcount');
         $this->db->where('username', $postData['txt_name']);
         $q = $this->db->get('Users');
         $result = $q->result_array();
       
         if($result[0]['allcount'] == 0){
          // Insert record
          $newuser = array(
            "username" => trim($postData['txt_name']),
            "userrole" => trim($postData['txt_role']),
            "password" => trim($postData['txt_pass'])
          );
      
          // $this->db->insert( [table-name], Array )
          $this->db->insert('Users', $newuser);
      
          $response = "Record insert successfully.";
         }else{
          $response = "Username already in use";
         }
        }else{
         $response = "Form is empty.";
        }
       
        return $response;
       }
}
