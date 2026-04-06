<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

class Userhistory extends MY_Controller
{
    var $module_id        = 'id';
    var $module_model     = 'userhistorymodel';
    var $view_folder      = 'user_history';
    var $redirect         = 'admin/user_history/list';
    var $module_caption   = 'User History';
    var $module_active    = 'users';
    var $module_active_sub= 'config';
    var $controller       = 'user_history';
    var $permission       = '';

    public function __construct()
    {
        parent::__construct();

        // Load required models
        $this->load->model([
            $this->module_model,
            'usergroupsmodel'
        ]);
    }

    public function list($type = "list", $id = "")
    {
        // Page & module details
        $data['page']            = $this->module_caption;
        $data['module']          = $this->module_caption;
        $data['active']          = $this->module_active;
        $data['active_sub']      = $this->module_active_sub;
        $data['active_sub_sub']  = $this->module_active_sub . '_list';
        $data['content']         = 'admin/' . $this->view_folder . '/list';

        // Additional data
        $data['parent']      = $this->usergroupsmodel->gets_all()->result();
        $data['module_id']   = $this->module_id;
        $data['controller']  = $this->controller;
          $data['logs'] = $this->db
        ->order_by('created_at', 'DESC')
        ->get('userhistory')
        ->result_array();

        // Load view
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
}
