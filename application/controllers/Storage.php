<?php
class Storage extends CI_Controller
{
	private $system_models = array(
		'Admin' => 'settings',
		'Clients' => 'clients',
		'Checklists_notes' => 'checklists_notes',
		'Production' => 'checklists',
		'Qc' => 'qc_forms',
		'Rma' => 'rma_forms',
		'Projects' => 'projects',
		'Users' => 'users',
		'Forms' => 'forms',
	);

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('admin');
		// Load models
		foreach ($this->system_models as $model => $table) {
			$this->load->model($model . '_model');
		}
		if (isset($this->session->userdata['logged_in'])) {
			$this->user = $this->session->userdata['logged_in'];
		} else {
			header("location: /users/login");
			exit('User not logedin');
		}
	}

	public function index()
	{
		echo "Storage";
	}

	public function save_file($id = 0)
	{
		if (isset($_GET['type'])) {
			if ($_GET['type'] == "rma") {
				$form = $this->Forms_model->get($_GET['type'], $id)[0];
				$upload_folder = "Uploads/$form->client/$form->project/{$_GET['type']}/$form->number";
			} else if ($_GET['type'] == "checklist") {
				$checklist = (object)$this->Production_model->getChecklists($id)[0];
				$upload_folder = "Uploads/$checklist->client/$checklist->project/$checklist->serial";
				
			} else {
				return;
			}
			if (!file_exists($upload_folder)) {
				mkdir($upload_folder, 0770, true);
			}
			$config = array(
				'upload_path' => $upload_folder,
				'overwrite' => TRUE,
				'allowed_types' => 'txt|pdf|csv|log',
				'max_size' => "4048",
			);
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('files')) {
				$data = array('upload_data' => $this->upload->data());
				echo  $data['upload_data']["file_name"];
			} else {
				$error = "error " . $this->upload->display_errors();
				echo $error;
			}
		}
	}

	public function delete_file()
	{
		$file = $this->input->post('file');
		// Use unlink() function to delete a file  
		if (!unlink($_SERVER["DOCUMENT_ROOT"] . $file)) {
			echo ($_SERVER["DOCUMENT_ROOT"] . $file . " cannot be deleted due to an error");
		} else {
			echo ($_SERVER["DOCUMENT_ROOT"] . $file . " has been deleted");
		}
	}
}
