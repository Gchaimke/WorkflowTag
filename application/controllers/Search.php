<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends CI_Controller
{
    private $user;
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Search_model');
        $this->load->model('Clients_model');
        $this->load->library('pagination');

        if (isset($this->session->userdata['logged_in'])) {
            $this->user = $this->session->userdata['logged_in'];
            if ($this->user['role'] == "") {
                exit;
            }
        }
    }

    public function index()
    {
        $tables = array('checklists', 'rma_forms', 'qc_forms', 'checklists_notes');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $search = $this->input->post('search');
        foreach ($tables as $table) {
            $results = $this->Search_model->search($search, $table);
            echo $this->build_table($results['data'], $results['type']);
        }
    }

    function build_table($results, $type)
    {
        $html = "";
        if ($results) {
            foreach ($results as $result) {
                if (strpos($result["project"], 'Trash') !== false) {
                    continue;
                }
                if (isset($result["client"])) {
                    $client = $this->Clients_model->get_client_by_name($result["client"]);
                    $client_users = isset($client['users']) ? explode(",", $client['users']) : array();

                    $client['id'] = isset($client) ? $client['id'] : "";
                    if (!in_array($this->user['id'], $client_users)) continue;
                }
                $html .= "<tr class='text-white'>";
                if ($type == "rma") {
                    $html .= "<td class='text-left'>{$result['number']}</td>";
                    $html .= "<td>" . urldecode($result["project"]) . "</td>";
                    $html .= "<td>RMA</td>";
                    $html .= "<td><a href='/forms/edit?type=rma&id={$result["id"]}&client={$client['id']}' class='btn btn-info fa fa-edit'></a></td>";
                }
                if ($type == "qc" && $this->user['role'] != "Assembler") {
                    $html .= "<td class='text-left'>{$result['number']}</td>";
                    $html .= "<td>QC</td>";
                    $html .= "<td>" . urldecode($result["project"]) . "</td>";
                    $html .= "<td><a href='/forms/edit?type=qc&id={$result["id"]}&client={$client['id']}' class='btn btn-info fa fa-edit'></a></td>";
                }
                if ($type == "note" && $this->user['role'] != "Assembler") {
                    $html .= "<td class='text-left'>{$result['checklist_sn']}</td>";
                    $html .= "<td>" . urldecode($result["project"]) . "</td>";
                    $html .= "<td>NOTE</td>";
                    $html .= "<td><a href='/production/edit_note/{$result["id"]}' class='btn btn-info fa fa-edit'></a></td>";
                }
                if (isset($client['id']) && $client['id'] != "" && $type == "checklist") {
                    $html .= "<td class='text-left'>" . $result['serial'] . "</td>";
                    $html .= "<td>" . urldecode($result["project"]) . "</td>";
                    $html .= "<td>CHECKLIST</td>";
                    if (strpos($result["project"], 'Trash') !== false) {
                        $html .= "<td>No Actions for Trashed items</td>";
                    } else {
                        $html .= "<td><a href='/production/edit_checklist/{$result["id"]}' class='btn btn-info fa fa-edit'></a></td>";
                    }
                }

                $html .= "</tr>";
            }
        }
        return $html;
    }
}
