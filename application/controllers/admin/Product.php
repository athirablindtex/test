<?php
if (!defined('BASEPATH')) {
    exit('No direct script allowed');
}
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
class Product extends MY_Controller
{
    var $module_id = 'id';
    var $single_name = "product";
    var $valid_fields = array('name', 'product_type', 'blind_code', 'fabric_code', 'sub_product_type', 'vendor', 'price_band_type', 'price_band', 'min_width', 'max_width', 'min_drop', 'max_drop');
    public function __construct()
    {
        parent::__construct();
        $this->module_model = $this->single_name . 'model';
        $this->view_folder = $this->single_name;
        $this->redirect = 'admin/' . $this->single_name . '/list';
        $this->module_caption = 'Fabric';
        $this->module_active = $this->single_name;
        $this->module_active_sub = $this->single_name;
        $this->controller = $this->single_name;
        $this->permission = $this->single_name;
        $this->load->library('Excel_reader');;
          $this->load->model(array($this->module_model, 'producttypemodel', 'vendormodel', 'pricebandmodel', "usersmodel", "extrasmodel"));
        $this->load->library('pagination');

        if ($this->session->userdata('admin_type') !== 1) {

            $companySession = $this->session->userdata('admin_companies');

            if (!empty($companySession)) {
                $this->companies = is_array($companySession)
                    ? $companySession
                    : [$companySession];
            }
        }

 $method = $this->router->fetch_method();

/* Map controller methods to permission */
$permission_map = [
    'productmargin'      => 'productmargin_list',
    'get_margin_value'   => 'productmargin_list',
    'updateMarginFabric' => 'productmargin_list',
    'productmargin_export' => 'productmargin_list',
    'pricebandmargin'    => 'pricebandmargin_list',
    'savePricebandMargin'=> 'pricebandmargin_list'
];

/* Super Admin skip permission */
if ($this->session->userdata('admin_type') == 1) {
    return;
}

if (isset($permission_map[$method])) {
    $permission = $permission_map[$method];
} else {
    $permission = $this->permission . '_list';
}

$this->check_user_privillages($permission);
      
    }
    public function list($type = "list", $id = "")
    {
        $module_model = $this->module_model;
        $view_folder = $this->view_folder;
        $this->form_validation->set_rules('name', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            // Prepare common data
            $data['page'] = $this->module_caption;
            $data['module'] = $this->module_caption;
            $data['active'] = $this->module_active;
            $data['active_sub'] = $this->module_active_sub;
            $data['active_sub_sub'] = $this->module_active_sub . '_list';
            $data['content'] = 'admin/' . $view_folder . '/list';
            $data['redirect'] = site_url() . $this->redirect;
            $data['companies'] = $this->usersmodel->gets_company_all()->result();
            $data['edit'] = 0;
            if ($type == "edit") {
                $this->check_user_privillages($this->permission . '_add');
                if ($data['res'] = $this->$module_model->get_row($id)) {
                    $data['edit'] = 1;
                    $this->db->where('parent', $data['res']->product_type)->select('id,name');
                    $data['sub_product_types'] = $this->producttypemodel->gets_data()->result();
                    $this->db->where('type', $data['res']->price_band_type)->select('id,name');
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
            // Get search keyword from GET query string
            $search = $this->input->get('search', TRUE);
            // Pagination config
            $config['base_url'] = base_url('admin/product/list');
            $config['total_rows'] = $this->{$module_model}->get_total_count($search);
            $data['total_rows'] = $config['total_rows'];
            $config['per_page'] = 20;
            $config['uri_segment'] = 4;  // offset is in segment 4
            $config['reuse_query_string'] = TRUE; // keep ?search=term in pagination links
            $config['use_page_numbers'] = True; // offset pagination
            // Pagination HTML customization
            $config['full_tag_open'] = '<ul class="pagination-custom">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            // Get offset from URL segment, default 0
            $page = (int) $this->uri->segment(4);
            $page = ($page > 0) ? $page : 1;
            $offset = ($page - 1) * $config['per_page'];
            // echo $offset;
            // exit;
            $data['offset'] = $offset;
            // Fetch filtered, paginated data
            $data['tabledata'] = $this->$module_model->get_data_admin_table_products_margin($config['per_page'], $offset, $search);
            $data['pagination_links'] = $this->pagination->create_links();
            $data['module_id'] = $this->module_id;
            $data['controller'] = $this->controller;
            $data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
            $this->load->vars($data);
            $this->load->view('admin/include/template');
        } else {
            // Your form submission handling (unchanged)
            $this->check_user_privillages($this->permission . '_add');
            $data = $this->input->post();
            $tags = $data['tags'];
            unset($data['id']);
            if ($data) {
                $valid = TRUE;
                foreach ($data as $key => $value) {
                    if (!in_array($key, $this->valid_fields)) {
                        unset($data[$key]);
                    }
                }
                if ($valid) {
                    if ($this->input->post('id') == 0) {
                        $data['is_enabled'] = 1;
                        $data['created_date'] = date('Y-m-d');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        audit_log('product', $id, 'INSERT', $old_data = null, $data, 1);
                        $data['version'] = 1;
                    } else {
                        if ($row = $this->$module_model->get_row($this->input->post('id'))) {
                            $old_data = $this->db->get_where('product', ['id' => $this->input->post('id')])->row_array();
                            $data['version'] = $row->version + 1;
                            audit_log('product', $id, 'UPDATE',  $old_data, $data, 1);
                            $data['updated_at'] = date('Y-m-d H:i:s');
                        }
                    }
                    $id = $this->$module_model->save($data, $this->input->post('id'));
                    $this->db->where('product', $id);
                    $this->$module_model->delete_tags_custom();
                    $tag_expl = explode(',', $tags);
                    $insert_tag = [];
                    foreach ($tag_expl as $t) {
                        $insert_tag[] = ['tag' => $t, 'product' => $id];
                    }
                    if (count($insert_tag) > 0) {
                        $this->$module_model->save_tags_batch($insert_tag);
                    }
                    $this->session->set_flashdata('success', $this->lang->line('common_update_success'));
                } else {
                    $this->session->set_flashdata('error', "Data Error Occurred");
                }
            } else {
                $this->session->set_flashdata('error', "Data Error Occurred");
            }
            redirect($this->redirect);
        }
    }
    public function sort($or, $id)
    {
        $module_model = $this->module_model;
        $gt = $this->$module_model->sort($or, $id);
    }
    public function delete($id = 0)
    {
        $module_model = $this->module_model;
        if ($id > 0) {
            $old_data = $this->$module_model->get_row($id);
            $this->$module_model->delete($id);
            audit_log('product', $id, 'DELETE', $old_data, $new_data = null, 1);
            $this->session->set_flashdata('success', $this->lang->line('common_delete_success'));
            redirect($this->redirect);
        } else {
            echo "ERROR";
        }
    }
    public function view($id = 0)
    {
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
    public function disable($id = 0)
    {
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
    public function enable($id = 0)
    {
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
    public function upload()
    {
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
    public function base64_to_jpeg($base64, $path)
    {
        @copy($path, $$path);
        $ifp = @fopen($path, 'wb');
        $data = explode(',', $base64);
        @fwrite($ifp, base64_decode($data[1]));
        @fclose($ifp);
        return true;
    }
    function change_product_type()
    {
        $category_id = $this->input->post('category');
        $this->db->where('parent', $category_id)->select('id,name');
        $subs = $this->producttypemodel->gets_data()->result_array();
        $html = "<option value=''>Vendor</option>";
        foreach ($subs as $su) {
            $html .= '<option value="' . $su['id'] . '">' . $su['name'] . '</option>';
        }
        echo $html;
    }
    function change_product_band_type()
    {
        $category_id = $this->input->post('category');
        $product_type = $this->input->post('product_type');
        $this->db->where('type', $category_id)
            ->where('product_type', $product_type)
            ->order_by('name', 'ASC')
            ->select('id, name');
        $subs = $this->pricebandmodel->gets_data()->result_array();
        $html = "<option value=''>Price Band</option>";
        foreach ($subs as $su) {
            $html .= '<option value="' . $su['id'] . '">' . $su['name'] . '</option>';
        }
        echo $html;
    }
    public function excel_import()
    {
        $config['upload_path'] = APPPATH . '../uploads/import/';
        $company_id = $this->input->post('company_id');
    

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

            $path = 'uploads/import/' . $file;
            @$this->load->library('excel_reader');

            @$this->excel_reader->read($path);

            $this->excel_import_set(@$this->excel_reader->sheets[0]['cells'],$company_id );
            unlink($path);
            $this->session->set_flashdata('success', "Excel Uploaded Successfully");
            redirect($this->redirect);
        }
        exit;
    }
    public function excel_import_set($data = [], $company_id = 0)
    {
        try {
            for ($i = 2; $i <= count($data); $i++) {
                $this->insert_import_row($data[$i], $company_id);
            }
        } catch (Exception $e) {
        }
    }
   public function insert_import_row($row = [], $company_id)
{
    try {
   

        $company_id = get_company_id_or_null($company_id);

        echo "Company ID for row: " . ($company_id ?? 'NULL') . "\n";
        exit;


        $product_type = $this->producttypemodel
            ->insert_check_product_type(trim(@$row[1]), 0, $company_id);

        $vendor = $this->producttypemodel
            ->insert_check_product_type(trim(@$row[2]), $product_type, $company_id);

        $price_band = $this->pricebandmodel
            ->insert_check_data(
                trim(@$row[6]),
                trim(@$row[5]),
                $product_type,
                $company_id
            );

        $save_data = [
            'company_id' => $company_id,
            'product_type' => $product_type ?: 0,
            'sub_product_type' => $vendor ?: 0,
            'vendor' => 0,
            'fabric_code' => trim(@$row[3]),
            'name' => trim(@$row[4]),
            'price_band_type' => trim(@$row[5]),
            'price_band' => $price_band,
            'min_width' => trim(@$row[7]) ?: 0,
            'max_width' => trim(@$row[8]) ?: 0,
            'min_drop' => trim(@$row[9]),
            'max_drop' => trim(@$row[10]),
            'turnable' => trim(@$row[11]),
            'is_enabled' => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'version' => 1
        ];

        $id = $this->productmodel->save($save_data);

        // TAGS
        $tag_expl = explode(',', @$row[12]);
        $insert_tag = [];

        foreach ($tag_expl as $t) {
            $clean_tag = preg_replace('/[^A-Za-z0-9\- ]/', '', $t);
            $insert_tag[] = [
                'tag' => trim($clean_tag),
                'product' => $id,
    
            ];
        }

        if (!empty($insert_tag)) {
            $this->productmodel->save_tags_batch($insert_tag);
            audit_log('product', $id, 'INSERT', null, $save_data, 1);
        }

    } catch (Exception $e) {
    }
}
    /* ======  To Add Margin for Each product and Product Type : START ==== */
    public function productmargin($type = "list", $id = "")
    {
        $view_folder = $this->view_folder;
        $data['page'] = $this->module_caption . "| margin";
        $data['module'] = $this->module_caption . "| margin";
        $data['active'] = 'magin';
        $data['active_sub'] = 'magin';
        $data['active_sub_sub'] = 'magin' . '_list';
        $data['content'] = 'admin/' . $view_folder . '/productmargin';
        $data['redirect'] = site_url() . $data['content'];
        $data['edit'] = 0;

        $company_ids = $this->companies ?? [];
        if (!empty($company_ids)) {
            $this->db->where_in('id', $company_ids);
        }
        $data['company'] = $this->usersmodel->gets_company_all()->result();
        // $this->db->where('parent', 0)->select('id,name');
        // $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $module_model = $this->module_model;
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $this->db->where('parent', 0)->select('id,name')->from('extras');
        $data['extras_type'] = $this->db->get()->result();
        $this->db->select('id,name');
        $data['vendor'] = $this->vendormodel->gets_data()->result();
        $search = $this->input->get('search', TRUE);
        $config['base_url'] = base_url('admin/product/productmargin');
        $config['total_rows'] = $this->{$module_model}->get_total_count($search); // Fix: use curly braces for dynamic model
        $config['per_page'] = 50;
        $config['uri_segment'] = 4; // Important: tell CI which segment is the page number
        // Custom HTML structure for pagination
        $config['full_tag_open'] = '<ul class="pagination-custom">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        // Optional: remove # from links (it’s usually not necessary)
        $config['use_page_numbers'] = FALSE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['count'] = $config['total_rows'];
        // $data['tabledata'] = $this->$module_model->get_data_admin_table_products_margin($config['per_page'], $page);
        $products = $this->$module_model->get_data_admin_table_products_margin($config['per_page'], $page, $search); // Fetch all products for margin calculation
        $company_ids = $this->companies ?? [];
        $get_company_id = $this->input->get('company_id', TRUE);




        // fallback default
        $company_id = !empty($company_ids) ? $company_ids[0] : (!empty($get_company_id) ? $get_company_id : 9);
        $user_margin = $this->db->select('margin_value')
            ->where('id', $company_id)
            ->get('admin_users')
            ->row_array();
        // print_r($this->db->last_query());

        if (!empty($user_margin) && $user_margin['margin_value'] != '') {
            $companyMargin = $user_margin['margin_value'];
        }


        foreach ($products as &$product) {

            $this->db->where('product_id', $product['id']);
            $this->db->where('value !=', 0);

            if (!empty($company_ids)) {
                $this->db->where_in('company_id', $company_ids);
            }

            $margin_row = $this->db->get('margin_config_new')->row_array();

            if (empty($margin_row)) {

                $this->db->where('product_type', $product['product_type_id']);
                $this->db->where('price_band_id', $product['price_band_id']);
                $this->db->where('value !=', 0);
                if (!empty($get_company_id)) {
                    $this->db->where('company_id', $get_company_id);
                } else if (!empty($company_ids)) {
                    $this->db->where_in('company_id', $company_ids);
                }

                $margin_row = $this->db->get('margin_config_new')->row_array();
            }

            if (empty($margin_row) || !isset($margin_row['value'])) {
                $margin = $companyMargin;
            } else {
                $margin = $margin_row['value'];
            }

            $product['margin_value'] = $margin;
        }

        unset($product);

        $data['tabledata'] = $products;
        $data['tabledata1'] = $this->$module_model->product_type_margin();
        $data['tabledata2'] = $this->$module_model->extra_type_margin();
        $data['pagination_links'] = $this->pagination->create_links();
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
    public function productmargin_export()
    {
        $module_model = $this->module_model;
        $search = $this->input->get('search', TRUE);
        $company_ids = $this->companies ?? [];

        // Get ALL products (no pagination)
    
$company_id = $this->input->get('company_id', TRUE);

/* Default fallback */
$companyMargin = 120;

/* Get company margin from admin_users */
$user_margin = $this->db->select('margin_value')
    ->where('id', $company_id)
    ->get('admin_users')
    ->row_array();

if (!empty($user_margin['margin_value'])) {
    $companyMargin = $user_margin['margin_value'];
}
    $products = $this->$module_model
            ->get_data_admin_table_products_margin(null, null, $search);
            // print_r($products);
            // exit;
        // CSV headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=product_margin_' . date('Y-m-d') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // CSV column headers
        fputcsv($output, [
            'Sl no',
            'Name',
            'Type',
            'Collection',
            'Priceband',
            'Margin'
        ]);
        $i = 1;
        foreach ($products as $product) {

            // --- SAME margin logic ---
$margin_row = $this->db->get_where(
    'margin_config_new',
    [
        'product_id' => $product['id'],
        'company_id' => $company_id,
        'value !='   => 0
    ]
)->row_array();

if (empty($margin_row)) {
    $margin_row = $this->db->get_where(
        'margin_config_new',
        [
            'product_type'  => $product['product_type_id'],
            'price_band_id' => $product['price_band_id'],
            'company_id'    => $company_id,
            'value !='      => 0
        ]
    )->row_array();
}

$margin = (!empty($margin_row['value'])) ? $margin_row['value'] : $companyMargin;

            // Write CSV row
            fputcsv($output, [
                $i,
                $product['name'],
                $product['product_type_name'],
                $product['sub_product_type_name'],
                $product['priceband_name'],
                $margin
            ]);
            $i++;
        }

        fclose($output);
        exit;
    }

    public function getPricebandByType()
    {
        $type_id = $this->input->post('type_id');
        $this->db->where('product_type', $type_id)
            ->select('*')
            ->order_by('name', 'ASC'); // change 'id' to your column name
        $subs = $this->pricebandmodel->gets_data()->result_array();
        if (!empty($subs)) {
            echo json_encode([
                'status' => 'success',
                'data' => $subs
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No price bands found'
            ]);
        }
    }
    function getSubtype()
    {
        $parent_id = $this->input->post('parent_id');
        $data = $this->producttypemodel->get_child_data($parent_id);
        echo json_encode($data);
    }
    public function get_children()
    {
        $module_model = $this->module_model;
        $parent_id = $this->input->post('parent_id');
        $children = $this->extrasmodel->get_children_by_parent($parent_id);
        echo json_encode($children);
    }
    public function updateMarginFabric()
    {
        // Check if the request is a POST request
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Get the data from the AJAX request
            $type = $this->input->post('type');
            // Fetch the data from the POST request
            $product_id = $this->input->post('product_id');
            $product_type = $this->input->post('product_type');
            $sub_type = $this->input->post('sub_type');
            $type_check = $this->input->post('type_check');
            $margin_value = $this->input->post('margin_value') == 0 ? Null : $this->input->post('margin_value');
            $company_id = $this->input->post('company_id');
            // Determine whether to use product_type or sub_type
            $margin_type = ($type_check == 1) ? $sub_type : $product_type; // Use sub_type if checked, otherwise use product_type
            if ($type == 1) {
                $product_id = NULL;
                $existing_margin = $this->db->get_where('margin_config_new', ['product_type' =>   $margin_type, 'company_id' => $company_id])->row_array();
                $variable = 'product_type';
                $updated_with = $margin_type;
            } else if ($type == 2) {
                $product_type = NULL;
                $existing_margin = $this->db->get_where('margin_config_new', ['product_id' =>   $product_id, 'company_id' => $company_id])->row_array();
                $variable = 'product_id';
                $updated_with = $product_id;
            }
            $response = array('success' => false, 'message' => 'Something went wrong.');
            $current_time = date('Y-m-d H:i:s');
            if ($existing_margin) {
                // Update logic
                $data = array(
                    'product_type' => $type == 1 ? $margin_type : $product_type,  // Store the margin type
                    'product_id' => $product_id,    // Store the product_id
                    'value' => $margin_value,
                    'updated_at' => $current_time
                    // Store the margin value
                );
                // Perform the update based on product_id
                $this->db->where('company_id', $company_id);
                $this->db->where($variable, $updated_with);
                $updated = $this->db->update('margin_config_new', $data);
                if ($updated) {
                    $response = array('success' => true, 'message' => 'Margin updated successfully.');
                    audit_log('Product margin', $existing_margin['id'], 'UPDATE', $existing_margin, $data, 1);
                } else {
                    $response = array('success' => false, 'message' => 'Failed to update margin.');
                }
            } else {
                // Insert logic (if no existing margin found)
                $data = array(
                    'company_id' => $company_id,
                    'product_type' => $margin_type,
                    'product_id' => $product_id,
                    'value' => $margin_value,
                    'created_at' => $current_time
                );
                // Perform the insert operation
                $inserted = $this->db->insert('margin_config_new', $data);
                if ($inserted) {
                    $response = array('success' => true, 'message' => 'Margin added successfully.');
                } else {
                    $response = array('success' => false, 'message' => 'Failed to add margin.');
                }
            }
            echo json_encode($response);
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
        }
    }
    public function updateMarginExtra()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $type = $this->input->post('type');
            $extra_type = $this->input->post('extra_type');
            $sub_extra = $this->input->post('sub_extra');
            $type_check = $this->input->post('type_check');
            $margin_value = $this->input->post('margin_value');
            if (empty($margin_value)) {
                echo json_encode(array('success' => false, 'message' => 'Margin value cannot be empty.'));
                return;
            }
            $company_id = $this->input->post('company_id');
            $margin_type = ($type_check == 1) ? $sub_extra : $extra_type;
            $existing_margin = $this->db->get_where('extras_margin_config', ['extra_type' =>   $margin_type, 'company_id' => $company_id])->row_array();
            $updated_with = $margin_type;
            // Get the company_id
            // Initialize the response array
            $response = array('success' => false, 'message' => 'Something went wrong.');
            // Check if the product_id already exists (update) or not (insert)
            $current_time = date('Y-m-d H:i:s');
            if ($existing_margin) {
                // Update logic
                $data = array(
                    'extra_type' => $margin_type,  // Store the margin type
                    // Store the product_id
                    'value' => $margin_value,
                    'updated_at' => $current_time
                    // Store the margin value
                );
                // Perform the update based on product_id
                $this->db->where('company_id', $company_id);
                $this->db->where('extra_type', $updated_with);
                $updated = $this->db->update('extras_margin_config', $data);
                if ($updated) {
                    $response = array('success' => true, 'message' => 'Margin updated successfully.');
                } else {
                    $response = array('success' => false, 'message' => 'Failed to update margin.');
                }
            } else {
                // Insert logic (if no existing margin found)
                $data = array(
                    'company_id' => $company_id,
                    'extra_type' => $margin_type,
                    'value' => $margin_value,
                    'created_at' => $current_time
                );
                // Perform the insert operation
                $inserted = $this->db->insert('extras_margin_config', $data);
                if ($inserted) {
                    $response = array('success' => true, 'message' => 'Margin added successfully.');
                } else {
                    $response = array('success' => false, 'message' => 'Failed to add margin.');
                }
            }
            // Return the response in JSON format
            echo json_encode($response);
        } else {
            // If the request method is not POST, return an error
            echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
        }
    }
    function get_margin_value()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $company_id = $this->input->post('company_id');
        // Determine the correct column name
        $column_name = ($type == 1) ? 'product_type' : 'product_id';
        // Query the database using the dynamic column name
        $this->db->where($column_name, $id);
        $this->db->where('company_id', $company_id);
        $existing_margin = $this->db->get('margin_config_new')->row();
        // Return the margin value (or null if not found)
        if ($existing_margin) {
            echo json_encode([
                'success' => true,
                'margin' => $existing_margin->value
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'margin' => null
            ]);
        }
    }
    function get_margin_extra_value()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $company_id = $this->input->post('company_id');
        // Determine the correct column name
        // Query the database using the dynamic column name
        $this->db->where('extra_type', $id);
        $this->db->where('company_id', $company_id);
        $existing_margin = $this->db->get('extras_margin_config')->row();
        // Return the margin value (or null if not found)
        if ($existing_margin) {
            echo json_encode([
                'success' => true,
                'margin' => $existing_margin->value
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'margin' => null
            ]);
        }
    }
    public function get_priceband_margin_value()
    {
        $type_id = $this->input->post('product_type');
        $price_band_id = $this->input->post('price_band');
        $company_id = $this->input->post('company_id');
        $this->db->where('price_band_id', $price_band_id);
        $this->db->where('product_type', $type_id);
        $this->db->where('company_id', $company_id);
        $existing_margin = $this->db->get('margin_config_new')->row();
        if ($existing_margin) {
            echo json_encode([
                'success' => true,
                'margin' => $existing_margin->value
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'margin' => null
            ]);
        }
    }
    public function search()
    {
        $search = $this->input->post('search');
        $module_model = $this->module_model;
        $results = $this->$module_model->get_data_admin_table_products_margin(null, null, $search);
        echo json_encode($results);
    }
    public function get_company_product_types()
    {
        $company_id = $this->input->post('company_id');
        // Load model
        $module_model = $this->module_model;
        $data['company_id'] = $company_id;
        // Fetch product types with margin values for this company
        $data['product_types'] = $this->$module_model->get_product_types_with_margin($company_id);
        $data['company'] = $this->usersmodel->gets_company_all()->result();
        // Load partial view
        $this->load->view('admin/product/product_type_table', $data);
    }
    public function get_company_extra_types()
    {
        $company_id = $this->input->post('company_id');
        // Load model
        $module_model = $this->module_model;
        $data['company_id'] = $company_id;
        // Fetch product types with margin values for this company
        $data['extra_types'] = $this->$module_model->get_extra_types_with_margin($company_id);
        $data['company'] = $this->usersmodel->gets_company_all()->result();
        // Load partial view
        $this->load->view('admin/product/extra_type_table', $data);
    }
    public function copy_company_product_types()
    {
        // Get data from AJAX POST
        $source_company   = $this->input->post('source_company');
        $target_companies = $this->input->post('target_companies');
        $product_types    = $this->input->post('product_types');
        // Basic validation to prevent errors
        if (empty($source_company) || empty($target_companies) || empty($product_types)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required data (source, target, or product types).'
            ]);
            return;
        }
        foreach ($target_companies as $target_company_id) {
            foreach ($product_types as $product_type_id) {
                // Delete any existing record for the same target company + product type
                $this->db->where('company_id', $target_company_id)
                    ->where('product_type', $product_type_id)
                    ->delete('margin_config');
                // Get the source company’s product type config
                $source_data = $this->db->where('company_id', $source_company)
                    ->where('product_type', $product_type_id)
                    ->get('margin_config')
                    ->row_array();
                // Copy only if source data exists
                if ($source_data) {
                    unset($source_data['id']); // Avoid duplicate primary key
                    $source_data['company_id'] = $target_company_id;
                    $source_data['created_at'] = date('Y-m-d H:i:s');
                    $source_data['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('margin_config', $source_data);
                }
            }
        }
        echo json_encode([
            'status' => 'success',
            'message' => 'Settings transferred successfully.'
        ]);
    }
    public function copy_company_extra_types()
    {
        // Get data from AJAX POST
        $source_company   = $this->input->post('source_company');
        $target_companies = $this->input->post('target_companies');
        $product_types    = $this->input->post('extra_types');
        // Basic validation to prevent errors
        if (empty($source_company) || empty($target_companies) || empty($product_types)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing required data (source, target, or Extra types).'
            ]);
            return;
        }
        foreach ($target_companies as $target_company_id) {
            foreach ($product_types as $product_type_id) {
                // Delete any existing record for the same target company + product type
                $this->db->where('company_id', $target_company_id)
                    ->where('extra_type', $product_type_id)
                    ->delete('extras_margin_config');
                // Get the source company’s product type config
                $source_data = $this->db->where('company_id', $source_company)
                    ->where('extra_type', $product_type_id)
                    ->get('extras_margin_config')
                    ->row_array();
                // Copy only if source data exists
                if ($source_data) {
                    unset($source_data['id']); // Avoid duplicate primary key
                    $source_data['company_id'] = $target_company_id;
                    $source_data['created_at'] = date('Y-m-d H:i:s');
                    $source_data['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('extras_margin_config', $source_data);
                }
            }
        }
        echo json_encode([
            'status' => 'success',
            'message' => 'Settings transferred successfully.'
        ]);
    }
    public function pricebandmargin($type = "list", $id = "")
    {
        $view_folder = $this->view_folder;
        $data['page'] = $this->module_caption . "| margin";
        $data['module'] = $this->module_caption . "| margin";
        $data['active'] = 'magin';
        $data['active_sub'] = 'magin';
        $data['active_sub_sub'] = 'magin' . '_list';
        $data['content'] = 'admin/' . $view_folder . '/pricebandmargin';
        $data['redirect'] = site_url() . $data['content'];
        $data['edit'] = 0;
        $company_ids = $this->companies ?? [];
        if (!empty($company_ids)) {
            $this->db->where_in('id', $company_ids);
        }
        $data['company'] = $this->usersmodel->gets_company_all()->result();
        // $this->db->where('parent', 0)->select('id,name');
        // $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $module_model = $this->module_model;
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $this->db->where('parent', 0)->select('id,name')->from('extras');
        $data['extras_type'] = $this->db->get()->result();
        $this->db->select('id,name');
        $data['vendor'] = $this->vendormodel->gets_data()->result();
        $config['base_url'] = base_url('admin/product/pricebandmargin');
        $config['total_rows'] = $this->{$module_model}->get_total_count(); // Fix: use curly braces for dynamic model
        $config['per_page'] = 20;
        $config['uri_segment'] = 4; // Important: tell CI which segment is the page number
        // Custom HTML structure for pagination
        $config['full_tag_open'] = '<ul class="pagination-custom">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        // Optional: remove # from links (it’s usually not necessary)
        $config['use_page_numbers'] = FALSE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['tabledata'] = $this->$module_model->get_data_admin_table_products_margin($config['per_page'], $page);
        $data['tabledata1'] = $this->$module_model->product_type_margin();
        $data['tabledata2'] = $this->$module_model->extra_type_margin();
        $data['pagination_links'] = $this->pagination->create_links();
        //print_r($data['tabledata']);
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
    public function product_type_margin($type = "list", $id = "")
    {
        $view_folder = $this->view_folder;
        $data['page'] =   "Product Type| margin";
        $data['module'] = "Product Type| margin";
        $data['active'] = 'magin';
        $data['active_sub'] = 'magin';
        $data['active_sub_sub'] = 'magin' . '_list';
        $data['content'] = 'admin/' . $view_folder . '/product_type_margin';
        $data['redirect'] = site_url() . $data['content'];
        $data['edit'] = 0;
        $data['company'] = $this->usersmodel->gets_company_all()->result();
        // $this->db->where('parent', 0)->select('id,name');
        // $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $module_model = $this->module_model;
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $this->db->where('parent', 0)->select('id,name')->from('extras');
        $data['extras_type'] = $this->db->get()->result();
        $this->db->select('id,name');
        $data['vendor'] = $this->vendormodel->gets_data()->result();
        $config['base_url'] = base_url('admin/product/pricebandmargin');
        $config['total_rows'] = $this->{$module_model}->get_total_count(); // Fix: use curly braces for dynamic model
        $config['per_page'] = 10;
        $config['uri_segment'] = 4; // Important: tell CI which segment is the page number
        // Custom HTML structure for pagination
        $config['full_tag_open'] = '<ul class="pagination-custom">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        // Optional: remove # from links (it’s usually not necessary)
        $config['use_page_numbers'] = FALSE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['tabledata'] = $this->$module_model->get_data_admin_table_products_margin($config['per_page'], $page);
        $data['tabledata1'] = $this->$module_model->product_type_margin();
        $data['tabledata2'] = $this->$module_model->extra_type_margin();
        $data['pagination_links'] = $this->pagination->create_links();
        //print_r($data['tabledata']);
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
    public function savePricebandMargin()
    {
        // Uncomment for debugging if needed
        // echo "<pre>"; print_r($_POST); exit;
        $type_id = $this->input->post('product_type');
        $price_band_id = $this->input->post('price_band');
        $margin_value = $this->input->post('margin');
        $company_id = $this->input->post('company_name');
        $apply_all     = $this->input->post('apply_to_all'); // checkbox
        // Validate inputs
        if (empty($type_id) || empty($price_band_id) || $margin_value === '') {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            return;
        }


        if ($apply_all) {


            $pricebands = $this->db
                ->where('product_type', $type_id)
                ->get('price_band')
                ->result_array();

            if (empty($pricebands)) {
                echo json_encode(['status' => 'error', 'message' => 'No price bands found.']);
                return;
            }

            foreach ($pricebands as $band) {

                $existing = $this->db->get_where('margin_config_new', [
                    'product_type'  => $type_id,
                    'price_band_id' => $band['id'],
                    'company_id'    => $company_id
                ])->row_array();

                if ($existing) {

                    $data = [
                        'value'      => $margin_value,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $this->db->where('id', $existing['id'])->update('margin_config_new', $data);

                    audit_log('Priceband margin', $existing['id'], 'UPDATE', $existing, $data, 1);
                } else {

                    $data = [
                        'product_type'  => $type_id,
                        'price_band_id' => $band['id'],
                        'company_id'    => $company_id,
                        'value'         => $margin_value,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ];

                    $this->db->insert('margin_config_new', $data);

                    audit_log('Priceband margin', $this->db->insert_id(), 'INSERT', null, $data, 1);
                }
            }

            echo json_encode([
                'status'  => 'success',
                'message' => 'Margin applied to all price bands successfully.'
            ]);
            return;
        } else {
            // Check if margin already exists
            $existing_margin = $this->db->get_where('margin_config_new', [
                'product_type' => $type_id,
                'price_band_id' => $price_band_id,
                'company_id' =>   $company_id
            ])->row_array();
            if ($existing_margin) {
                // Update existing record
                $data = [
                    'value' => $margin_value,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $this->db->where('product_type', $type_id);
                $this->db->where('price_band_id', $price_band_id);
                $this->db->where('company_id', $company_id);
                $updated = $this->db->update('margin_config_new', $data);
                audit_log('Priceband margin', $existing_margin['id'], 'UPDATE', $existing_margin, $data, 1);
                if ($updated) {
                    echo json_encode(['status' => 'success', 'message' => 'Margin updated successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update margin.']);
                }
            } else {
                // Insert new record
                $data = [
                    'product_type' => $type_id,
                    'price_band_id' => $price_band_id,
                    'company_id' =>   $company_id,
                    'value' => $margin_value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $inserted = $this->db->insert('margin_config_new', $data);
                if ($inserted) {
                    $insert_id = $this->db->insert_id();
                }
                if ($inserted) {
                    audit_log('Priceband margin', $insert_id, 'INSERT', $old_data = null, $data, 1);
                    echo json_encode(['status' => 'success', 'message' => 'Margin added successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add margin.']);
                }
            }
        }
    }
    public function get_data_sync_all()
    {
        $module_model = $this->module_model;
        $data = $this->$module_model->get_data_sync_all(12);
        echo count($data['product']);
        echo "<pre>";
        print_r($data);
        exit;
    }
    public function export_csv()
    {
        $module_model = $this->module_model;
        $search = $this->input->get('search');
        $products = $this->$module_model->get_data_admin_table_products_margin(null, null, $search);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products.csv"');
        $output = fopen('php://output', 'w');
        // CSV Header
        fputcsv($output, [
            'ID',
            'Name',
            'Fabric_code',
            'Product Type',
            'Sub Product Type',
            'Price Band Type',
            'Price Band',
            'Turnable'
        ]);
        $i = 1;
        foreach ($products as $p) {

            fputcsv($output, [
                $i,
                $p['name'],
                $p['fabric_code'],
                $p['product_type_name'],
                $p['sub_product_type_name'],
                $p['price_band_type'],
                $p['priceband_name'],
                $p['turnable']
            ]);
            $i++;
        }
        fclose($output);
        exit;
    }
    public function download_pdf()
    {
        $module_model = $this->module_model;

        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $mpdf = new \Mpdf\Mpdf([
            'format'       => 'A4-L',
            'simpleTables' => true,
            'tempDir'      => FCPATH . 'uploads/tmp'
        ]);

        $search = $this->input->get('search') ?? '';

        $limit  = 200;
        $offset = 0;
        $srNo   = 1;

        do {
            $products = $this->$module_model
                ->get_data_admin_table_products_margin($limit, $offset, $search);

            if (empty($products)) {
                break;
            }


            if ($offset > 0) {
                $mpdf->AddPage();
            }

            $html = $this->load->view('admin/product/pdf', [
                'products' => $products,
                'start'    => $srNo
            ], true);

            $mpdf->WriteHTML($html);


            $srNo   += count($products);
            $offset += $limit;

            unset($products, $html);
        } while (true);

        if (ob_get_length()) {
            ob_end_clean();
        }

        $mpdf->Output('products_' . date('YmdHis') . '.pdf', 'D');
        exit;
    }

    public function bulk_delete()
    {
        $this->db->where('deleted_at IS NULL', null, false);
        if ($this->input->post('product_type')) {
            $this->db->where('product_type', $this->input->post('product_type'));
        }
        if ($this->input->post('sub_product_type')) {
            $this->db->where('sub_product_type', $this->input->post('sub_product_type'));
        }
        if ($this->input->post('price_band_type')) {
            $this->db->where('price_band_type', $this->input->post('price_band_type'));
        }
        if ($this->input->post('price_band')) {
            $this->db->where('price_band', $this->input->post('price_band'));
        }
        if ($this->input->post('search')) {
            $this->db->like('name', $this->input->post('search'));
        }
        $this->db->update('product', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
        audit_log(
            'product',
            $newId = NULL,
            'DELETE',
            $oldProduct = NULL,
            $newProduct = NULL,
            1
        );
        echo json_encode([
            'status'  => 'success',
            'deleted' => $this->db->affected_rows()
        ]);
    }
    public function bulk_copy()
    {
        $this->db->from('product');
        $this->db->where('deleted_at IS NULL', null, false);
        if ($this->input->post('product_type')) {
            $this->db->where('product_type', $this->input->post('product_type'));
        }
        if ($this->input->post('sub_product_type')) {
            $this->db->where('sub_product_type', $this->input->post('sub_product_type'));
        }
        if ($this->input->post('price_band_type')) {
            $this->db->where('price_band_type', $this->input->post('price_band_type'));
        }
        if ($this->input->post('price_band')) {
            $this->db->where('price_band', $this->input->post('price_band'));
        }
        if ($this->input->post('search')) {
            $this->db->like('name', $this->input->post('search'));
        }
        $products = $this->db->get()->result_array();
        $count = 0;
        foreach ($products as $product) {
            unset($product['id']);
            $product['name']       .= ' (Copy)';
            $product['created_at'] = date('Y-m-d H:i:s');
            $product['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('product', $product);
            $count++;
        }
        echo json_encode([
            'status' => 'success',
            'copied' => $count
        ]);
    }
    public function copy_product()
    {
        $id = (int) $this->input->post('id');
        $oldProduct = $this->db
            ->where('id', $id)
            ->where('deleted_at IS NULL', null, false)
            ->get('product')
            ->row_array();
        if (!$oldProduct) {
            echo json_encode(['status' => 'error']);
            return;
        }
        $newProduct = $oldProduct;
        unset($newProduct['id']);
        $baseName = preg_replace('/\s*\(Copy\)$/', '', $oldProduct['name']);
        $newProduct['name'] = $baseName . ' (Copy)';
        $newProduct['version']    = 1;
        $newProduct['created_date'] = date('Y-m-d H:i:s');
        $newProduct['updated_at'] = date('Y-m-d H:i:s');
        $newProduct['deleted_at'] = null;
        $this->db->insert('product', $newProduct);
        $newId = $this->db->insert_id();
        audit_log(
            'product',
            $newId,
            'COPY',
            $oldProduct,
            $newProduct,
            1
        );
        // Copy tags
        $tags = $this->db
            ->get_where('product_tags', ['product' => $id])
            ->result_array();
        foreach ($tags as $tag) {
            unset($tag['id']);
            $tag['product'] = $newId;
            $this->db->insert('product_tags', $tag);
        }
        echo json_encode([
            'status' => 'success',
            'new_id' => $newId
        ]);
    }
    public function margin_view_all()
    {
        $module_model = $this->module_model;
        $data['page'] = 'View All Margins';
        $company_id = $this->input->get('company_id');
        $data['companies'] = $this->usersmodel->gets_company_all()->result();
        //   echo "<pre>";
        //   print_r( $data['companies']);
        //   exit;
        // load margins
        $data['margins'] = $this->$module_model->get_latest_margins($company_id);
        // content view
        $data['content'] = 'admin/product/margin_view_all';
        // load template
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
    public function export_margin_csv($company_id)
    {
        $module_model = $this->module_model;
        $margins = $this->$module_model->get_latest_margins($company_id);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=latest_margins.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Company', 'Product Type', 'Price Band', 'Band Type', 'Margin', 'Updated At']);
        foreach ($margins as $row) {
            fputcsv($out, [
                $row->company_name,
                $row->product_type,
                $row->price_band,
                $row->band_type,
                $row->margin,
                $row->updated_at
            ]);
        }
        fclose($out);
        exit;
    }
    public function export_to_companies()
    {
        $ids = $this->input->post('margin_ids');
        // Convert comma string to array
        $margin_ids = array_filter(array_map('intval', explode(',', $ids)));
        $exclude_company_id = (int) $this->input->post('exclude_company_id');
        if (empty($margin_ids)) {
            $this->session->set_flashdata('error', 'No margin rows selected');
            redirect('admin/product/margin_view_all?company_id=' . $exclude_company_id);
            return;
        }
        $result = $this->copy_margins_to_other_companies($margin_ids, $exclude_company_id);
        // Exclude source company
        $this->session->set_flashdata(
            'success',
            "Margins copied successfully to {$result} companies"
        );
        redirect('admin/product/margin_view_all?company_id=' . $exclude_company_id);
    }
    public function copy_margins_to_other_companies($margin_ids, $exclude_company_id)
    {
        // Get selected margins
        $margins = $this->db
            ->where_in('id', $margin_ids)
            ->get('margin_config_new')
            ->result();
        if (empty($margins)) {
            return 0;
        }
        // Get target companies
        $companies = $this->db
            ->where('id !=', $exclude_company_id)
            ->where('company_code !=', '')
            ->get('admin_users')
            ->result();
        $affectedCompanies = 0;
        foreach ($companies as $company) {
            foreach ($margins as $row) {
                // Check if already exists
                $exists = $this->db
                    ->where([
                        'company_id'     => $company->id,
                        'product_type'   => $row->product_type,
                        'price_band_id'  => $row->price_band_id
                    ])
                    ->get('margin_config_new')
                    ->row();
                if ($exists) {
                    // UPDATE
                    $this->db->where('id', $exists->id)
                        ->update('margin_config_new', [
                            'value' => $row->value,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    // INSERT
                    $this->db->insert('margin_config_new', [
                        'company_id'    => $company->id,
                        'product_type'  => $row->product_type,
                        'price_band_id' => $row->price_band_id,
                        'value'         => $row->value,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }
            }
            $affectedCompanies++;
        }
        return $affectedCompanies;
    }
}
