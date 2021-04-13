<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['page_query_string'] = true;
$config['reuse_query_string'] = true;
$config['enable_query_strings'] = true;

$config['full_tag_open'] = '<ul class="pagination right">';
$config['full_tag_close'] = '</ul>';

$config['cur_tag_open'] = '<li class="page-item active "><a class="page-link">';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li class="page-item num-link">';
$config['num_tag_close'] = '</li>';

$config['first_tag_open'] = '<li class="page-item num-link">';
$config['first_tag_close'] = '</li>';

$config['last_tag_open'] = '<li class="page-item num-link">';
$config['last_tag_close'] = '</li>';

$config['next_tag_open'] = '<li class="page-item num-link">';
$config['next_tag_close'] = '</li>';

$config['prev_tag_open'] = '<li class="page-item num-link">';
$config['prev_tag_close'] = '</li>';
