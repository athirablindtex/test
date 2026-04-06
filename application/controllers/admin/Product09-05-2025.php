<?php

if (!defined('BASEPATH')) {
    exit('No direct script allowed');
}

class Product extends MY_Controller {

    var $module_id = 'id';
    var $single_name = "product";
    var $valid_fields = array('name', 'product_type', 'sub_product_type', 'vendor', 'price_band_type', 'price_band', 'min_width', 'max_width', 'min_drop', 'max_drop');

    public function __construct() {
        parent::__construct();
        $this->module_model = $this->single_name . 'model';
        $this->view_folder = $this->single_name;
        $this->redirect = 'admin/' . $this->single_name . '/list';
        $this->module_caption = 'Fabric';
        $this->module_active = $this->single_name;
        $this->module_active_sub = $this->single_name;
        $this->controller = $this->single_name;
        $this->permission = $this->single_name;
        $this->check_user_privillages($this->permission . '_list');
        $this->load->model(array($this->module_model, 'producttypemodel', 'vendormodel', 'pricebandmodel', "usersmodel"));
    }

    public function list($type = "list", $id = "") {
        $module_model = $this->module_model;
        $view_folder = $this->view_folder;
        $this->form_validation->set_rules('name', 'Product Type Name', 'required|trim|alpha_numeric_spaces|min_length[3]|max_length[20]');
        if ($this->form_validation->run() == FALSE) {
            $data['page'] = $this->module_caption;
            $data['module'] = $this->module_caption;
            $data['active'] = $this->module_active;
            $data['active_sub'] = $this->module_active_sub;
            $data['active_sub_sub'] = $this->module_active_sub . '_list';
            $data['content'] = 'admin/' . $view_folder . '/list';
            $data['redirect'] = site_url() . $this->redirect;
            $data['edit'] = 0;
            if (@$type == "edit") {
                $this->check_user_privillages($this->permission . '_add');
                if ($data['res'] = $this->$module_model->get_row($id)) {
                    $data['edit'] = 1;
                    $this->db->where('parent', @$data['res']->product_type)->select('id,name');
                    $data['sub_product_types'] = $this->producttypemodel->gets_data()->result();
                    $this->db->where('type', @$data['res']->price_band_type)->select('id,name');
                    $data['pricebands'] = $this->pricebandmodel->gets_data()->result();
                    $this->db->where('product', $id)->select('tag');
                    $ed_tag = $this->$module_model->gets_data_tags()->result_array();
                    $tags_ar = array_column($ed_tag, 'tag');
                    $data['tags'] = implode(',', $tags_ar);
                }
            }
            $this->db->where('parent', 0)->select('id,name');
            $data['product_types'] = $this->producttypemodel->gets_data()->result();
            $this->db->select('id,name');
            $data['vendor'] = $this->vendormodel->gets_data()->result();
            $data['tabledata'] = $this->$module_model->get_data_admin_table();
            $data['module_id'] = $this->module_id;
            $data['controller'] = $this->controller;
            $data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
            $this->load->vars($data);
            $this->load->view('admin/include/template');
        } else {
            $this->check_user_privillages($this->permission . '_add');
            $data = $this->input->post();
            $tags = $data['tags'];
            unset($data['id']);
            if (@$data) {
                $valid = TRUE;
                foreach ($data as $key => $value) {
                    if (!in_array($key, $this->valid_fields)) {
                        unset($data[$key]);
                    }
                }
                if ($valid) {
                    if (@$this->input->post('id') == 0) {
                        $data['is_enabled'] = 1;
                        $data['created_date'] = date('Y-m-d');
                        $data['version'] = 1;
                    } else {
                        if ($row = $this->$module_model->get_row($this->input->post('id'))) {
                            $data['version'] = $row->version + 1;
                        }
                    }
                    $id = $this->$module_model->save($data, $this->input->post('id'));
                    $this->db->where('product', $id);
                    $this->$module_model->delete_tags_custom();
                    $tag_expl = explode(',', $tags);
                    $insert_tag = array();
                    foreach ($tag_expl as $t) {
                        $insert_tag[] = array('tag' => $t, 'product' => $id);
                    }
                    if (count($insert_tag) > 0) {
                        $this->$module_model->save_tags_batch($insert_tag);
                    }
                    $this->session->set_flashdata('success', $this->lang->line('common_update_success'));
                } else {
                    $this->session->set_flashdata('error', "Data Error Occured");
                }
            } else {
                $this->session->set_flashdata('error', "Data Error Occured");
            }
            redirect($this->redirect);
        }
    }

    public function sort($or, $id) {
        $module_model = $this->module_model;
        $gt = $this->$module_model->sort($or, $id);
    }

    public function delete($id = 0) {
        $module_model = $this->module_model;
        if ($id > 0) {
            $this->$module_model->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('common_delete_success'));
            redirect($this->redirect);
        } else {
            echo "ERROR";
        }
    }

    public function view($id = 0) {
        $module_model = $this->module_model;
        $view_folder = $this->view_folder;
        if ($id > 0) {
            $data['product'] = $this->$module_model->get_row_details($id);
            $data['page'] = 'View ' . $this->module_caption;
            $data['module'] = $this->module_caption;
            $data['active'] = $this->module_active;
            $data['active_sub'] = $this->module_active_sub;
            $data['active_sub_sub'] = $this->module_active_sub . '_list';
            $data['content'] = 'admin/' . $view_folder . '/view';
            $data['module_id'] = $this->module_id;
            $data['controller'] = $this->controller;
            $data['content'] = 'admin/' . $view_folder . '/view';
            $this->load->vars($data);
            $this->load->view('admin/include/template');
        } else {
            echo "ERROR";
        }
    }

    public function disable($id = 0) {
        $module_model = $this->module_model;
        $view_folder = $this->view_folder;
        if ($id > 0) {
            $update["active"] = 0;
            $this->$module_model->save($update, $id);
            $this->session->set_flashdata('success', $this->lang->line('common_disable_success'));
            redirect($this->redirect);
        } else {
            echo "ERROR";
        }
    }

    public function enable($id = 0) {
        $module_model = $this->module_model;
        $view_folder = $this->view_folder;
        if ($id > 0) {
            $update["active"] = 1;
            $this->$module_model->save($update, $id);
            $this->session->set_flashdata('success', $this->lang->line('common_enable_success'));
            redirect($this->redirect);
        } else {
            echo "ERROR";
        }
    }

    public function upload() {
        $config['upload_path'] = APPPATH . '../uploads/customer/';
        $config['allowed_types'] = 'gif|png|jpg|jpeg';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) {
            return false;
        } else {
            $name = $this->upload->data();
            $file = $name['file_name'];
            //$path = APPPATH . '../uploads/users/' . $file;
            //$this->base64_to_jpeg($this->input->post('cropped_image'), $path);
            return $file;
        }
    }

    public function base64_to_jpeg($base64, $path) {
        @copy($path, $$path);
        $ifp = @fopen($path, 'wb');
        $data = explode(',', $base64);
        @fwrite($ifp, base64_decode($data[1]));
        @fclose($ifp);
        return true;
    }

    function change_product_type() {
        $category_id = $this->input->post('category');
        $this->db->where('parent', $category_id)->select('id,name');
        $subs = $this->producttypemodel->gets_data()->result_array();
        $html = "<option value=''>Vendor</option>";
        foreach ($subs as $su) {
            $html .= '<option value="' . $su['id'] . '">' . $su['name'] . '</option>';
        }
        echo $html;
    }

    function change_product_band_type() {
        $category_id = $this->input->post('category');
        $this->db->where('type', $category_id)->select('id,name');
        $subs = $this->pricebandmodel->gets_data()->result_array();
        $html = "<option value=''>Product Band</option>";
        foreach ($subs as $su) {
            $html .= '<option value="' . $su['id'] . '">' . $su['name'] . '</option>';
        }
        echo $html;
    }

    public function excel_import() {
        $config['upload_path'] = APPPATH . '../uploads/import/';
        $config['allowed_types'] = 'xls|xlsx';
        $this->load->library('upload', $config);
        if ($_FILES['excel_file']['name'] == "") {
            $this->session->set_flashdata('error', "No file selected!");
            redirect($this->redirect);
        } elseif ((!$this->upload->do_upload('excel_file')) && ($_FILES['excel_file']['name'] != "")) {
            $error = array('error' => $this->upload->display_errors());

            $this->session->set_flashdata('error', "Upload Failed");
            redirect($this->redirect);
        } else {
            $name = $this->upload->data();
            $file = $name['file_name'];
            // ini_set('display_errors', 1);
            // ini_set('display_startup_errors', 1);
            // error_reporting(E_ALL);
            //	$path = FCPATH.'/uploads/import/' . $file;
            //$path = APPPATH . '../uploads/import/' . $file;
            $path = 'uploads/import/' . $file;

            @$this->load->library('excel_reader');
            // Read the spreadsheet via a relative path to the document
            // for example $this->excel_reader->read('./uploads/file.xls');
            @$this->excel_reader->read($path);
            // print_r(@$this->excel_reader->sheets);
            $this->excel_import_set(@$this->excel_reader->sheets[0]['cells']);
            unlink($path);
            $this->session->set_flashdata('success', "Excel Uploaded Successfully");
            redirect($this->redirect);
        }
        exit;
    }

    public function excel_import_set($data = []) {
        try {
            for ($i = 2; $i <= count($data); $i++) {
                $this->insert_import_row($data[$i]);
            }
        } catch (Exception $e) {
            
        }
    }

    public function insert_import_row($row = []) {
        try {
            $product_type = $this->producttypemodel->insert_check_product_type(trim(@$row[1]), 0); // Check the first coloumn for the product type
            $vendor = $this->producttypemodel->insert_check_product_type(trim(@$row[2]), $product_type); // Check the second coloumn for product type vendors (like books etc)
            //$vendor = $this->vendormodel->insert_check_data(trim(@$row[2]));
            $save_data = [
                'product_type' => $product_type ?: 0,
                'sub_product_type' => $vendor ?: 0,
                'vendor' => 0,
                'fabric_code' => trim(@$row[3]),
                'name' => trim(@$row[4]), // check thrid coloumn for name
                'price_band_type' => trim(@$row[5]), // check fourth coloumn for priceband type
                'price_band' => @$this->pricebandmodel->insert_check_data(trim(@$row[6]), trim(@$row[5])), // check fifth  coloumn for priceband type
                'min_width' => trim(@$row[7]) ?: 0,
                'max_width' => trim(@$row[8]) ?: 0,
                'min_drop' => trim(@$row[9]),
                'max_drop' => trim(@$row[10]),
                'turnable' => trim(@$row[11]),
                'is_enabled' => 1,
                'created_date' => date('Y-m-d'),
                'version' => 1
            ];
            $id = $this->productmodel->save($save_data);
            $tag_expl = explode(',', @$row[12]);
            $insert_tag = array();
            foreach ($tag_expl as $t) {
                $clean_tag = preg_replace('/[^A-Za-z0-9\- ]/', '', $t);
       $insert_tag[] = array('tag' => trim($clean_tag), 'product' => $id);
            }
            if (count($insert_tag) > 0) {
                $this->productmodel->save_tags_batch($insert_tag);
            }
        } catch (Exception $e) {
            
        }
    }

    /* ======  To Add Margin for Each product and Product Type : START ==== */

    public function productmargin($type = "list", $id = "") {
        $view_folder = $this->view_folder;
        $data['page'] = $this->module_caption . "| margin";
        $data['module'] = $this->module_caption . "| margin";
        $data['active'] = 'magin';
        $data['active_sub'] = 'magin';
        $data['active_sub_sub'] = 'magin' . '_list';
        $data['content'] = 'admin/' . $view_folder . '/productmargin';
        $data['redirect'] = site_url() . $data['content'];
        $data['edit'] = 0;
        $data['company'] = $this->usersmodel->gets_all()->result();
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();

        $module_model = $this->module_model;
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $this->db->select('id,name');
        $data['vendor'] = $this->vendormodel->gets_data()->result();
        $data['tabledata'] = $this->$module_model->get_data_admin_table();

        //print_r($data['tabledata']);

        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }

    // public function update_company_margin() {
    //     $module_model = $this->module_model;
    //     $margin_new = $this->input->post('margin_new');
    //     $status_new = $this->input->post('status_new');
    //     $company_id = $this->input->post('company_id');
    //     $fabric_id = $this->input->post('fabric_id');
    //     $this->db->where('id', $fabric_id)->select('company_config');
    //     $company_config_string = $this->$module_model->gets_data()->result();
        
    //     //echo $company_config_string[0]->company_config;
        
         
    //     $data["company_config"] = $this->add_update_company_margin($company_config_string[0]->company_config,$company_id,$margin_new,$status_new);
        
    //     if($this->$module_model->save($data, $fabric_id))
    //     {
    //     echo $data["company_config"] ;
    //     }else{
    //         echo "fail";
    //     }
    //     //echo "magrin =" . $margin_new . ":: status= " . $status_new . ":: fabric id=" . $fabric_id;
    // }
    
    
    
    // public function update_product_type_margin(){
    //     $module_model = $this->module_model;
    //     $margin_new = $this->input->post('margin_new');
    //     $company_id = $this->input->post('company_id');
    //     $product_type_id = $this->input->post('product_type_id');
        
    //     $this->db->where('product_type', $product_type_id)->select('id,company_config');
    //     $fabric_company_config = $this->$module_model->gets_data()->result();
    //     $flag=true;
    //     foreach($fabric_company_config as $company_config_item){
    //         $data["company_config"] = $this->add_update_company_margin($company_config_item->company_config, $company_id, $margin_new);
    //         //echo "<br/> id = ".$company_config_item->id.":: config=". $data["company_config"];
    //         $res = $this->$module_model->save($data, $company_config_item->id);
    //         if(!$res){
    //             $flag=false;
    //         }
    //     }
    //     if($flag){
    //         echo "success";
    //     }else{
    //         echo "fail";
    //     }
    // }

    // function add_update_company_margin($company_cofig_string,$company_id,$margin_new,$status_new = 'nil'){
        
    //     $company_config_json = json_decode($company_cofig_string);
    //     $flag = true;
    //     for($i=0;$i< count($company_config_json->company);$i++){
    //         if($company_config_json->company[$i]->companyId == $company_id){
    //             $company_config_json->company[$i]->companyPercent = $margin_new;
                
    //             if($status_new != 'nil'){
    //             $company_config_json->company[$i]->status = $status_new;
    //             }
    //             $flag= false;
    //             break;
    //         }
         
            
    //     }
        
    //     if($flag) {
    //         $company_count = count($company_config_json->company);
    //          $company_config_json->company[$company_count] = new stdClass();
    //          $company_config_json->company[$company_count]->companyId = $company_id;
    //          $company_config_json->company[$company_count]->companyPercent = $margin_new;
    //          if($status_new != 'nil'){
    //          $company_config_json->company[$company_count]->status = $status_new;
    //          }else{
    //          $company_config_json->company[$company_count]->status = "active";
    //          }
    //      } 
         
         
    //     return json_encode($company_config_json);
    // }


    /* ======  To Add Margin for Each product and Product Type : END ==== */
}
