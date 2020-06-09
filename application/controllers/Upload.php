<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->load->view('upload/custom_view', array('error' => ' '));
    }
    public function custom_view()
    {
        $this->load->view('upload/custom_view', array('error' => ' '));
    }
    public function do_upload()
    {
        define('UPLOAD_DIR', 'Uploads/');
        $config = array(
            'upload_path' => UPLOAD_DIR,
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
            //'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            //'max_height' => "768",
            //'max_width' => "1024"
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
            $data = array('upload_data' => $this->upload->data());
            $this->load->view('upload/upload_success', $data);
        } else {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('upload/custom_view', $error);
        }
    }
}
