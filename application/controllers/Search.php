<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model
        $this->load->model('Search_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $tables = array('checklists', 'rma_forms', 'qc_forms', 'checklists_notes');
        $this->form_validation->set_rules('search', 'search', 'trim|xss_clean');
        $search = $this->input->post('search');
        foreach ($tables as $table) {
            $results = $this->Search_model->search($search, $table);
            echo $this->build_table($results, $table);
        }
    }

    function build_table($results)
    {
        $html = "";
        if ($results) {
            foreach ($results as $result) {
                $html .= "<tr class='text-white'>";
                if (isset($result['number']) && isset($result['parts'])) {
                    $html .= "<td>RMA " . $result['number'] . "</td>";
                } else if (isset($result['number']) && !isset($result['parts'])) {
                    $html .= "<td>QC " . $result['number'] . "</td>";
                } else if (isset($result['row'])) {
                    $html .= "<td>Note " . $result['number'] . "</td>";
                } else {
                    $html .= "<td>" . $result['serial'] . "</td>";
                }
                $html .= "<td>" . urldecode($result["client"]) . " " . urldecode($result["project"]) . "</td>";
                $html .= "<td>";

                if (strpos($result["project"], 'Trash') !== false) {
                    $html .= "No Actions for Trashed items";
                } else if (isset($result['number']) && isset($result['parts'])) {
                    $html .= "<a href='/rma/edit_rma/" . $result["id"] . "' class='btn btn-info fa fa-edit'></a>";
                } else if (isset($result['number']) && !isset($result['parts'])) {
                    $html .= "<a href='/qc/edit_qc/" . $result["id"] . "' class='btn btn-info fa fa-edit'></a>";
                } else if (isset($result['row'])) {
                    $html .= "<a href='/production/edit_note/" . $result["id"] . "' class='btn btn-info fa fa-edit'></a>";
                } else {
                    $html .= "<a href='/production/edit_checklist/" . $result["id"] . "?sn=" . $result["serial"] . "' class='btn btn-info fa fa-edit'></a>";
                }

                $html .= "</td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }
}
