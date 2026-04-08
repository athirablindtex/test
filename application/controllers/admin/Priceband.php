<?php if (! defined('BASEPATH')) {
	exit('No direct script allowed');
}
class Priceband extends MY_Controller
{
	var $module_id = 'id';
	var $single_name = "priceband";
	var $valid_fields = array('name', 'priceband_version', 'type', 'unit_price', 'min_unit', 'no_width', 'no_drop', 'product_type');
	public function __construct()
	{
		parent::__construct();
		$this->module_model = $this->single_name . 'model';
		$this->view_folder = $this->single_name;
		$this->redirect = 'admin/' . $this->single_name . '/list';
		$this->module_caption = 'Price Band';
		$this->module_active = $this->single_name;
		$this->module_active_sub = $this->single_name;
		$this->controller = $this->single_name;
		$this->permission = $this->single_name;
		$this->check_user_privillages($this->permission . '_list');
		$this->load->model(array($this->module_model, 'pricebandversionmodel', 'producttypemodel'));
	}
	public function list($type = "list", $id = "")
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$data = $this->input->post();

		$this->form_validation->set_rules('name', 'Product Band Name', 'required');
		$this->form_validation->set_rules('priceband_version', 'Product Band Version', 'required|trim');
		$this->form_validation->set_rules('type', 'Product Band Type', 'required|trim');
		$this->form_validation->set_rules('ptype', 'Product Type', 'required|trim');
		if (@$this->input->post('type') == "Square Meter" || @$this->input->post('type') == "Meter") {
			$this->form_validation->set_rules('unit_price', 'Unit Price', 'required|trim|numeric');
			$this->form_validation->set_rules('min_unit', 'Minimum Unit', 'required|trim|numeric');
		} else if (@$this->input->post('type') == "Matrix") {
			//$this->form_validation->set_rules('no_width','No of width','required|trim|numeric');
			//$this->form_validation->set_rules('no_drop','No of drop','required|trim|numeric');
		}
		if ($this->form_validation->run() == FALSE) {
			$data['page'] = $this->module_caption;
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_list';
			$data['content'] = 'admin/' . $view_folder . '/list';
			$data['redirect'] = site_url() . $this->redirect;
			$data['edit'] = 0;
			$data['product_type'] = $this->input->post('ptype');
		   $data['companies'] = get_companies_list();
		   $company_id = $this->input->get('company_id');
			
			$data['prices'] = array();
			$this->db->select('id, name');
			$this->db->where('parent', 0);
			$this->db->where('company_id', $company_id);
			$data['product_types'] = $this->producttypemodel->gets_all()->result();
				$productTypeMap = [];
				foreach ($data['product_types'] as $pt) {
				$productTypeMap[$pt->id] = $pt->name;
				}
				echo "<pre>";
				print_r($data['product_types']);
			$data['priceBandnames'] = $this->$module_model->getAllPriceBandNames();

			if (@$type == "edit") {
				$this->check_user_privillages($this->permission . '_add');
				if ($data['res'] = $this->$module_model->get_row($id)) {
					$data['edit'] = 1;
					if ($data['res']->type == "Matrix") {
					}
				}
				print_r($data['res']);
				exit;
			}
			if (@$this->input->get('priceband_version') > 0) {
				$this->db->where('a.priceband_version', $this->input->get('priceband_version'));
			}
			if (@$this->input->get('type') != "") {
				$this->db->where('a.type', $this->input->get('type'));
			}
			if ($this->input->get('from')) {
				$this->db->where('a.created_date >=', date('Y-m-d', strtotime($this->input->get('from'))));
			}
			if ($company_id) {
				$this->db->where('a.company_id', $company_id);
			}
			if ($this->input->get('to')) {
				$this->db->where('a.created_date <=', date('Y-m-d', strtotime($this->input->get('to'))));
			}
			$this->db->join('price_band_version p', 'p.id=a.priceband_version', 'left')
				->select('a.*,p.name as version_name');

			$data['tabledata'] = $this->$module_model->gets_all()->result();

			$this->db->select('id,name');
			$data['priceband'] = $this->pricebandversionmodel->gets_all()->result();



           
			// $data['tabledata'] = $this->$module_model->gets_all()->result();
			foreach ($data['tabledata'] as $row) {
				$row->product_type_name = $productTypeMap[$row->product_type] ?? '';
				}
			
		
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$this->check_user_privillages($this->permission . '_add');
			$data = $this->input->post();
			$set_value = @$data['set_value'];
			$set_id = @$data['set_id'];
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
						$version = 1;
					} else {
						if ($row = $this->$module_model->get_row($this->input->post('id'))) {
							$version = $row->version + 1;
						}
					}
					$data['name'] = $this->input->post('name');
					$data['version'] = $version;
					$data['product_type'] = $this->input->post('ptype');
					$data['company_id'] = $this->input->post('company') ? $this->input->post('company') : NULL;

					$id = $this->$module_model->save($data, $this->input->post('id'));
					$cur_price_ids = array();
					if ($id > 0) {
						$this->db->where('price_band', $id)
							->select('id');
						$cur_price_db = $this->$module_model->gets_data_price()->result_array();
						$cur_price_ids = array_column($cur_price_db, 'id');
					}

				if ($this->input->post('type') === "Matrix") {

    $matrix_json = $this->input->post('matrix_data');
    $matrix_data = json_decode($matrix_json, true);

    if (!empty($matrix_data)) {

        $batch_data = [];

        foreach ($matrix_data as $row) {
            if (empty($row['width']) && empty($row['height']) && empty($row['price'])) continue;

            $batch_data[] = [
                'price_band' => $id,
                'dim_width'  => $row['width'] ?? null,
                'dim_drop'   => $row['height'] ?? null,
                'price'      => $row['price'] ?? 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($batch_data)) {
          
            $this->db->where('price_band', $id)->delete('price_band_price');

            $this->db->insert_batch('price_band_price', $batch_data);
        }
    }
}


				
					$insert_ids = array();
					$i = 0;
					
					$delete_price = array_diff($cur_price_ids, $insert_ids);
					if (count($delete_price) > 0) {
						$this->$module_model->delete_price_id($delete_price);
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
	# File: c:\Users\athira\AppData\Local\Temp\fz3temp-2\Priceband.php
	public function import()
	{
		// Load the excel_reader library
		@$this->load->library('excel_reader');

		// Check if a file has been uploaded
		if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
			// Set upload configuration
			$config['upload_path']   = APPPATH . '../uploads/priceband_import/';
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



				$priceBandId = $this->input->post('type');

				// Check if price band exists and delete existing records
				if ($priceBandId) {
					$this->db->where('price_band', $priceBandId);
					$count = $this->db->count_all_results('price_band_price');

					if ($count > 0) {
						$this->db->where('price_band', $priceBandId);
						$this->db->delete('price_band_price');
					}
				}


				// Prepare data for price_band_price table
				$priceBandPrices = array();
				for ($i = 2; $i <= $worksheet['numRows']; $i++) {
					$height = isset($cells[$i][1]) ? $cells[$i][1] : null;
					$width  = isset($cells[$i][2]) ? $cells[$i][2] : null;
					$price  = isset($cells[$i][3]) ? $cells[$i][3] : null;

					if ($height !== null && $width !== null && $price !== null) {
						$priceBandPrices[] = array(
							'price_band' => $priceBandId,
							'dim_drop'    => $height,
							'dim_width'     => $width,
							'price'         => $price,
							'created_date' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						);
					}
				}

				// Insert into price_band_price table
				if (!empty($priceBandPrices)) {
					$this->db->insert_batch('price_band_price', $priceBandPrices);
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
	public function live_excel()
	{


		$this->load->view('admin/priceband/list-test');
	}



	public function get_price_matrix()
	{
		// Fetch price band data
		$id = $this->input->get('id');
		$this->db->where('price_band', $id);
		$query = $this->db->get('price_band_price');
		$data = $query->result_array();

		if (empty($data)) {
			echo json_encode(['error' => 'No data found']);
			return;
		}

		// Extract unique widths and drops (height)
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
}
