<?php if (! defined('BASEPATH')) {
	exit('No direct script allowed');
}
// ini_set(('display_errors'), 1);
// error_reporting(E_ALL);
class Extras extends MY_Controller
{
	var $module_id = 'id';
	var $single_name = "extras";
	var $valid_fields = array('name', 'parent');
	public function __construct()
	{
		parent::__construct();
		$this->module_model = $this->single_name . 'model';
		$this->view_folder = $this->single_name;
		$this->redirect = 'admin/' . $this->single_name . '/list';
		$this->module_caption = 'Extras';
		$this->module_active = $this->single_name;
		$this->module_active_sub = $this->single_name;
		$this->controller = $this->single_name;
		$this->permission = $this->single_name;
		 $method = $this->router->fetch_method();
		 if ($method == 'extrasmargin') {
    $this->check_user_privillages('extras_margin_list');
} else {
		$this->check_user_privillages($this->permission . '_list');
}
	if ($this->session->userdata('admin_type') !== 1) {

			$companySession = $this->session->userdata('admin_companies');

			if (!empty($companySession)) {
			$this->companies = is_array($companySession)
			? $companySession
			: [$companySession];
			}
			}
		$this->load->model(array($this->module_model,'producttypemodel', 'vendormodel','usersmodel','salespersonmodel','usergroupsmodel'));
		$this->load->library("pagination");
	}
	public function list($type = "list", $id = "")
	{
		// echo "<pre>";
		// print_r($_POST);exit;
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$company_id = $this->input->get('company');
		$company_id = get_company_id_or_null($company_id);
		$this->form_validation->set_rules('name', 'Product Name', 'required|trim|min_length[3]|max_length[200]');
		if ($this->form_validation->run() == FALSE) {
			$limit = 30;
			$data['page'] = $this->module_caption;
			$data['extras_parent'] = $this->$module_model->get_parents();
			 $data['companies'] = $this->usersmodel->gets_company_all()->result();
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_list';
			$data['content'] = 'admin/' . $view_folder . '/list';
			$data['redirect'] = site_url() . $this->redirect;
			$data['edit'] = 0;
			$config = array();
			$config["base_url"] = site_url() . "admin/extras/list/";
			                   $this->db->where('company_id', $company_id);
			$config["total_rows"] = $this->$module_model->gets_data_admin_table_count();
			$config["per_page"] = $limit;
			$config["uri_segment"] = 4;
			$config['reuse_query_string'] = true;
			$config['full_tag_open'] = '<ul class="pagination mt-4 mx-auto text-center">';
			$config['full_tag_close'] = '</ul>';
			$config['num_tag_open'] = ' <li class="page-item">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li class="page-item">';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tag_close'] = '</li>';
			$config['prev_link'] = 'Previous';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li class="page-item">';
			$config['next_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data["links"] = $this->pagination->create_links();
			                  $this->db->where('company_id', $company_id);
			                   
			$data['tabledata'] = $this->$module_model->gets_data_admin_table_subs($limit, $page);
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$this->check_user_privillages($this->permission . '_add');
			$this->session->set_flashdata('success', $this->lang->line('common_update_success'));
			redirect($this->redirect);
		}
	}
	public function edit($id)
	{
		$module_model = $this->module_model;
		$data = [];
		$data['content'] = 'admin/extras/edit';
		$data['companies'] = $this->usersmodel->gets_company_all()->result();
		$data['tree'] = $this->getExtraTree($id);
		$data['edit'] = 1;
		$this->load->view('admin/include/template', $data);
	}
	private function getExtraTree($id)
	{
		$extra = $this->db
			->where('id', $id)
			->where('deleted_at IS NULL')
			->get('extras')
			->row_array();
		if (!$extra) {
			return [];
		}
		$extra['options'] = $this->db
			->where('extras_id', $id)
			->get('extras_details_price')
			->result_array();
		$children = $this->db
			->where('parent', $id)
			->where('deleted_at IS NULL')
			->get('extras')
			->result_array();
		$count = count($children);
		$extra['children'] = [];
		if ($count > 0) {
			foreach ($children as $child) {
				$extra['children'][] = $this->getExtraTree($child['id']);
			}
		}
		return $extra;
	}
public function getextras()
{
    $product_type_id = $this->input->post('product_type_id');

    if (!$product_type_id) {
        echo json_encode(['status' => false, 'data' => []]);
        return;
    }

    $this->db->select('e.id, e.name,pte.margin,e.value as value');
    $this->db->from('product_type_extras pte');
    $this->db->join('extras e', 'e.id = pte.extra', 'inner');
    $this->db->where('pte.product_type', $product_type_id);
	$this->db->where('pte.deleted_at IS NULL', null, false);

    $query  = $this->db->get();
    $extras = $query->result_array();



    echo json_encode(['status' => true, 'data' => $extras]);
}
public function saveExtraMargin()
{
    $product_type = $this->input->post('product_type');
    $extra_id     = $this->input->post('extra_id');   
    $margin       = $this->input->post('margin');
 

    if (!$product_type || !$extra_id || $margin === '') {
        echo json_encode([
            'status' => false,
            'message' => 'Missing required data'
        ]);
        return;
    }

  
    $this->db->where([
        'product_type' => $product_type,
        'extra'        => $extra_id,
    
    ]);
    $existing = $this->db->get('product_type_extras')->row_array();

    if ($existing) {

      
        $oldData = $existing;

        $this->db->where('id', $existing['id']);
        $this->db->update('product_type_extras', [
            'margin'     => $margin,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $newData = array_merge($oldData, ['margin' => $margin]);

        audit_log(
            'extras_margin',
            $extra_id,
            'UPDATE',
          $oldData,
            $newData,
            1
        );

    } else {

        // INSERT
        $insertData = [
            'product_type_id' => $product_type,
            'extra_id'        => $extra_id,
   
            'margin'          => $margin,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $this->db->insert('margin_config_new', $insertData);
        $insertId = $this->db->insert_id();

        audit_log(
            'extras_margin',
            $insertId,
            'INSERT',
            null,
           $insertData,
            1
        );
    }

    echo json_encode([
        'status' => true,
		'margin'=>$margin,
	
        'message' => 'Margin saved successfully'
    ]);
}
public function get_margin_excel_data()
{
    $list = $this->db
        ->select('pt.name as product_type, e.name as extra, m.margin')
        ->from('product_type_extras m')
        ->join('product_type pt', 'pt.id = m.product_type')
        ->join('extras e', 'e.id = m.extra')
        ->order_by('pt.name', 'ASC')
		->where('m.deleted_at IS NULL', null, false)
        ->get()
        ->result_array();

    $headers = ['Product Type', 'Extra', 'Margin (%)'];
    $data = [];

    foreach ($list as $row) {
        $data[] = [
            $row['product_type'],
            $row['extra'],
            $row['margin']
        ];
    }

    echo json_encode([
        'headers' => $headers,
        'data'    => $data
    ]);
}


	public function update_extras()
	{
		$post = $this->input->post();
		$version = 2;
		$this->db->trans_start();
		if (!empty($post['extras'])) {
			foreach ($post['extras'] as $node) {
				$this->saveExtraNode($node, 0, 0, $version);
			}
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Save failed');
		} else {
			$this->session->set_flashdata('success', 'Extras updated successfully');
		}
		redirect($this->redirect);
	}
	private function saveExtraNode($node, $parentId = 0, $level = 0, $version = 1)
	{
		$module_model = $this->module_model;
		$hasOptions = !empty($node['options']);
		$extraData = [
			'parent'     => $parentId,
			'name'       => $node['name'] ?? '',
			'extra_code' => $node['code'] ?? null,
			'type'       => $hasOptions ? 'Multiple' : ($node['type'] ?? null),
			'value'      => $hasOptions ? null : ($node['value'] ?? null),
			'level'      => $level,
			'is_enabled' => 1,
			'version'    => $version,
			'updated_at' => date('Y-m-d H:i:s'),
		];
		$oldExtra = null;
		if (!empty($node['id'])) {
			   $oldExtra = $this->db
        ->where('id', $node['id'])
        ->get('extras')
        ->row_array();
			$extraId = $this->$module_model->save($extraData, $node['id']);
			audit_log('extras',$extraId,'UPDATE',$oldExtra,$extraData,1);
		} else {
			$extraData['created_date'] = date('Y-m-d H:i:s');
			$extraId = $this->$module_model->save($extraData, 0);
		}
		$this->$module_model->delete_existing_record($extraId);
		if ($hasOptions) {
			foreach ($node['options'] as $opt) {
				if (empty($opt['type'])) continue;
				$this->$module_model->save_details([
					'extras_id' => $extraId,
					'type'      => $opt['type'],
					'price'     => $opt['price'] ?? 0,
					'version'   => $version,
				]);
			}
		}
		$existingChildren = $this->db
			->select('id')
			->where('parent', $extraId)
			->where('version', $version)
			->get('extras')
			->result_array();
		$existingIds = array_column($existingChildren, 'id');
		$postedIds = [];
		if (!empty($node['children'])) {
			foreach ($node['children'] as $child) {
				if (!empty($child['id'])) {
					$postedIds[] = $child['id'];
				}
			}
		}
		$toDelete = array_diff($existingIds, $postedIds);
		if (!empty($toDelete)) {
			$this->db->where_in('id', $toDelete)->delete('extras');
		}
		if (!empty($node['children'])) {
			foreach ($node['children'] as $child) {
				$this->saveExtraNode(
					$child,
					$extraId,
					$level + 1,
					$version
				);
			}
		}
		return $extraId;
	}
	public function matrix($id = 0)
	{
		$data['content'] = 'admin/extras/matrix_list';
		$this->load->view('admin/include/template', $data);
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
			$this->$module_model->delete($id);
			$old_extra = $this->db->get_where('extras', ['id' => $id])->row_array();
			audit_log('extras', $id, 'DELETE', $old_extra,$new_details=null,1);
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
	public function excel_import()
	{
		$config['upload_path'] = APPPATH . '../uploads/extras_import/';
		$config['allowed_types'] = 'xls';
		  $company_id = $this->input->post('company_id');
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
			$path = 'uploads/extras_import/' . $file;
			@$this->load->library('excel_reader');
			// Read the spreadsheet via a relative path to the document
			// for example $this->excel_reader->read('./uploads/file.xls');
			@$this->excel_reader->read($path);
			// print_r(@$this->excel_reader->sheets);
			$this->excel_import_set(@$this->excel_reader->sheets[0]['cells'], $company_id);
			unlink($path);
			$this->session->set_flashdata('success', "Excel Uploaded Successfully");
			redirect($this->redirect);
		}
		exit;
	}
	public function excel_import_set($data = [], $company_id)
	{
		try {
			for ($i = 2; $i <= count($data); $i++) {
				$this->insert_import_row($data[$i], $company_id);
			}
		} catch (Exception $e) {
		}
		// echo "<pre>";
		// print_r($data);
		// exit;
	}
	public function insert_import_row($row = [], $company_id)
	{
		try {
			
        $company_id = get_company_id_or_null($company_id);
			// Skip empty rows - adjust index to match 1-based keys
			$main = trim($row[1] ?? '');
			$sub = trim($row[2] ?? '');
			if (empty($main) || empty($sub)) {
				return;
			}
			// Columns (also 1-based)
			$extra_code = trim($row[3] ?? '');
			$type       = trim($row[4] ?? '');
			$value      = trim($row[5] ?? '');
			// Check and insert parent
			$parent = $this->db->get_where('extras', [
				'name'   => $main,
				'parent' => 0,
				'company_id' => $company_id
			])->row();
			if (!$parent) {
				$this->db->insert('extras', [
					'name'         => $main,
					'parent'       => 0,
					'is_enabled'   => 1,
					'created_date' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
					'company_id'   => $company_id,
					'version'      => 1
				]);
				$parent_id = $this->db->insert_id();
			} else {
				$parent_id = $parent->id;
			}
			// Check and insert child
			$child = $this->db->get_where('extras', [
				'name'   => $sub,
				'parent' => $parent_id,
				'company_id' => $company_id
			])->row();
			if (!$child) {
				$this->db->insert('extras', [
					'name'         => $sub,
					'parent'       => $parent_id,
					'extra_code'   => $extra_code,
					'type'         => $type,
					'value'        => $value,
					'company_id'   => $company_id,
					'is_enabled'   => 1,
					'created_date' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
					'version'      => 1
				]);
			}
		} catch (Exception $e) {
			log_message('error', 'Import Row Error: ' . $e->getMessage());
		}
	}
	public function matrix_import()
	{
		// Load the excel_reader library
		@$this->load->library('excel_reader');
		// Check if a file has been uploaded
		if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
			// Set upload configuration
			$config['upload_path']   = APPPATH . '../uploads/extras_import/matrix';
			$config['allowed_types'] = 'xls|xlsx';
			$config['file_name']     = time() . '_' . $_FILES['upload_file']['name'];
			// Load and initialize the upload library
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			// Attempt to upload the file
			if ($this->upload->do_upload('upload_file')) {
				// Get uploaded file data
				$fileData = $this->upload->data();
				$filePath = $fileData['full_path'];
				// Read the Excel file
				$this->excel_reader->read($filePath);
				$worksheet = $this->excel_reader->sheets[0];
				$cells = $worksheet['cells'];
				$extrasId = $this->input->post('extras_id');
				if ($extrasId) {
					$this->db->where('extras_id', $extrasId);
					$count = $this->db->count_all_results('extras_matrix_price');
					if ($count > 0) {
						$this->db->where('extras_id', $extrasId);
						$this->db->delete('extras_matrix_price');
					}
				}
				// Prepare data for price_band_price table
				$extrasPrices = array();
				for ($i = 2; $i <= $worksheet['numRows']; $i++) {
					$height = isset($cells[$i][1]) ? $cells[$i][1] : null;
					$width  = isset($cells[$i][2]) ? $cells[$i][2] : null;
					$price  = isset($cells[$i][3]) ? $cells[$i][3] : null;
					if ($height !== null && $width !== null && $price !== null) {
						$extrasPrices[] = array(
							'extras_id' => $extrasId,
							'dim_drop'    => $height,
							'dim_width'     => $width,
							'price'         => $price,
							'created_date' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						);
					}
				}
				// Insert into price_band_price table
				if (!empty($extrasPrices)) {
					$this->db->insert_batch('extras_matrix_price', $extrasPrices);
				}
				// Return success response
				echo json_encode(array('status' => 'success', 'message' => 'Data imported successfully.'));
			} else {
				// Return error response
				echo json_encode(array('status' => 'error', 'message' => $this->upload->display_errors()));
			}
		} else {
			// Return error response
			echo json_encode(array('status' => 'error', 'message' => 'No file uploaded.'));
		}
	}
	public function get_children()
	{
		$module_model = $this->module_model;
		$parent_id = $this->input->post('parent_id');
		$children = $this->$module_model->get_children_by_parent($parent_id);
		echo json_encode($children);
	}
	public function search_extra_by_name()
	{
		$module_model = $this->module_model;
		$name = $this->input->post('name');
		$results = $this->$module_model->search_extras_like_name($name);
		echo json_encode($results);
	}
	public function save_extras()
	{
		$extras = $this->input->post('extras');
		$company = $this->input->post('company_extra');
		$company_id = get_company_id_or_null($company);
		$main_id = $this->input->post('id');
		foreach ($extras as $extra) {
			$this->save_extra_recursive($extra, $main_id, 0, 0,$company_id); // level 0 for root
		}
		 audit_log('extras', null, 'INSERT', [],$new_details=null, 1);
		redirect($this->redirect);
	}
	/**
	 * Recursively save extras and their children/options
	 */
	private function save_extra_recursive($extra, $main_id, $parent_id = 0, $level = 0,$company_id)
	{
		$hasOptions  = isset($extra['options']) && count($extra['options']) > 0;
		$hasChildren = isset($extra['children']) && count($extra['children']) > 0;
		// Determine value type and value
		$value_type = $hasOptions ? 'Multiple' : ($extra['value_type'] ?? '');
		$value = $hasOptions ? 0 : ($extra['value'] ?? 0);
		// Prepare data for `extras` table
		$data = [
			'parent'   => $parent_id,
			'name'        => $extra['name'] ?? '',
			'extra_code' => $extra['code'] ?? '',
			'type'  => $value_type,
			'value'       => $value,
			'level'       => $level,
			'created_date'  => date('Y-m-d H:i:s'),
			'updated_at'  => date('Y-m-d H:i:s'),
			'is_enabled'  => 1,
			'version'     => 1,
			'company_id'  => $company_id,

		];
		// Insert into main extras table
		$this->db->insert('extras', $data);
		$extra_id = $this->db->insert_id();
		// If options exist, insert into extras_details_price
		if ($hasOptions) {
			foreach ($extra['options'] as $opt) {
				$optData = [
					'extras_id' => $extra_id,
					'type'     => $opt['type'] ?? '',
					'price'    => $opt['value'] ?? 0,
					'created_date' => date('Y-m-d H:i:s'),
				];
				$this->db->insert('extras_details_price', $optData);
			}
		}
		// If children exist, recursively insert them
		if ($hasChildren) {
			foreach ($extra['children'] as $child) {
				$this->save_extra_recursive($child, $main_id, $extra_id, $level + 1);
			}
		}
	}
	public function get_price_matrix()
	{
		$extras_id = $this->input->get('id');
		$data = $this->db
			->where('extras_id', $extras_id)
			->get('extras_matrix_price')
			->result_array();
		$widths  = array_unique(array_column($data, 'dim_width'));
		$heights = array_unique(array_column($data, 'dim_drop'));
		sort($widths);
		sort($heights);
		// Build price matrix
		$matrix = [];
		foreach ($heights as $h) {
			$row = ['height' => $h];
			foreach ($widths as $w) {
				$price = null;
				foreach ($data as $item) {
					if ($item['dim_width'] == $w && $item['dim_drop'] == $h) {
						$price = $item['price'];
						break;
					}
				}
				$row[$w] = $price;
			}
			$matrix[] = $row;
		}
		// Output as JSON
		echo json_encode([
			'widths' => $widths,
			'heights' => $heights,
			'matrix' => $matrix
		]);
	}
	public function update_matrix()
	{
		$id = $this->input->post('extras_id');
		$matrix_json = $this->input->post('matrix');
		$matrix_data = json_decode($matrix_json, true);
		if (!empty($matrix_data)) {
			$batch_data = [];
			foreach ($matrix_data as $row) {
				if (empty($row['dim_width']) && empty($row['dim_drop']) && empty($row['price'])) continue;
				$batch_data[] = [
					'extras_id' => $id,
					'dim_width'  => $row['dim_width'] ?? null,
					'dim_drop'   => $row['dim_drop'] ?? null,
					'type'       => 'Matrix',
					'price'      => $row['price'] ?? 0,
					'updated_at' => date('Y-m-d H:i:s'),
				];
			}
			if (!empty($batch_data)) {
				$this->db->where('extras_id', $id)->delete('extras_matrix_price');
				$this->db->insert_batch('extras_matrix_price', $batch_data);
			}
		}
	}
	public function delete_extra($id)
	{
		$now = date('Y-m-d H:i:s');
		$userId = $this->session->userdata('admin_id') ?? null;
		$this->db->trans_start();
		$old_extra = $this->db->get_where('extras', ['id' => $id])->row_array();
		$old_details = $this->db->get_where('extras_details_price', ['extras_id' => $id])->result_array();
		$old_matrix  = $this->db->get_where('extras_matrix_price', ['extras_id' => $id])->result_array();
		if (!$old_extra) {
			$this->db->trans_complete();
			echo json_encode(['status' => 'error', 'message' => 'Extra not found']);
			return;
		}
		audit_log('extras', $id, 'DELETE', $old_extra,1);
		if (!empty($old_details)) {
			audit_log('extras_details_price', $id, 'DELETE', $old_details,$new_details=null,0);
		}
		if (!empty($old_matrix)) {
			audit_log('extras_matrix_price', $id, 'DELETE', $old_matrix,$new_details=null,0);
		}
		$this->db->where('id', $id)
			->update('extras', ['deleted_at' => $now]);
		$this->db->where('extras_id', $id)->delete('extras_details_price');
		$this->db->where('extras_id', $id)->delete('extras_matrix_price');
		$this->db->trans_complete();
		echo json_encode(['status' => 'success']);
	}

	    public function extrasmargin($type = "list", $id = "")
    {
        $view_folder = $this->view_folder;
        $data['page'] = $this->module_caption . "| margin";
        $data['module'] = $this->module_caption . "| margin";
        $data['active'] = 'magin';
        $data['active_sub'] = 'magin';
        $data['active_sub_sub'] = 'magin' . '_list';
        $data['content'] = 'admin/' . $view_folder . '/extras_margin';
        $data['redirect'] = site_url() . $data['content'];
        $data['edit'] = 0;
		$company_ids = $this->companies ?? [];
         if (!empty($company_ids)) {
        $this->db->where_in('id', $company_ids);
    }
        $data['companies'] = get_companies_list();
        // $this->db->where('parent', 0)->select('id,name');
        // $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $module_model = $this->module_model;
        $this->db->where('parent', 0)->select('id,name');
        $data['product_types'] = $this->producttypemodel->gets_data()->result();
        $this->db->where('parent', 0)->select('id,name')->from('extras');
        $data['extras_type'] = $this->db->get()->result();
        $this->db->select('id,name');
        $data['vendor'] = $this->vendormodel->gets_data()->result();
        $config['base_url'] = base_url('admin/extras/extrasmargin');
        $config['total_rows'] = 10; // Fix: use curly braces for dynamic model
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
        $data['tabledata'] = array();
        // $data['tabledata1'] = $this->$module_model->product_type_margin();
        // $data['tabledata2'] = $this->$module_model->extra_type_margin();
        $data['pagination_links'] = $this->pagination->create_links();
        //print_r($data['tabledata']);
        $this->load->vars($data);
        $this->load->view('admin/include/template');
    }
}
