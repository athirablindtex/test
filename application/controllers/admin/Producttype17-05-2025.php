<?php if (!defined('BASEPATH')) {
	exit('No direct script allowed');
}
class Producttype extends MY_Controller
{
	var $module_id = 'id';
	var $single_name = "producttype";
	var $valid_fields = array('name', 'parent');
	public function __construct()
	{
		parent::__construct();
		$this->module_model = $this->single_name . 'model';
		$this->view_folder = $this->single_name;
		$this->redirect = 'admin/' . $this->single_name . '/list';
		$this->module_caption = 'Product Type';
		$this->module_active = 'master';
		$this->module_active_sub = $this->single_name;
		$this->controller = $this->single_name;
		$this->permission = $this->single_name;
		$this->check_user_privillages($this->permission . '_list');
		$this->load->model(array($this->module_model, 'salespersonmodel', 'extrasmodel'));
	}
	public function list($type = "list", $id = "")
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$data['selected_extras'] =[];
		$this->form_validation->set_rules('name', 'Product Type Name', 'required');
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
				}
			}
			if (@$this->input->get('sales_person') > 0) {
				$this->db->where('sales_person', $this->input->get('sales_person'));
			}
			$this->db->where('parent', 0);
			$data['tabledata'] = $this->$module_model->gets_all()->result();
			$this->db->where('active', 1)->select('id,name');
			$data['sales_person'] = $this->salespersonmodel->gets_data()->result_array();
		
			$data['extras'] = $this->get_extras_for_all();
			if (@$type == "edit") {
				$data['selected_extras'] =$this->get_selected_extras($id);
			}
		
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
		
		
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$this->check_user_privillages($this->permission . '_add');
			$data = $this->input->post();
			// echo "<pre>";
			// print_r($data);
			// exit;
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
					if ($this->input->post('extras')) {
						$this->save_extras($this->input->post(), $id);
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

	function save_extras($data = [], $ptype_id = 0)
	{
	
		$module_model = $this->module_model;
		if (is_array(@$data['extras'])) {
			$extras = array_map(function ($extra) {
				return [
					'extra' => $extra['extra'],
					'mandatory' => @$extra['mandatory'] ?: 0
				];
			}, $data['extras']);
			$this->delete_removed_extras($extras, $ptype_id);
			$curr_extras = $this->get_existing_extras($ptype_id);
			
			$new_extras = array_diff(array_column($extras, 'extra'), array_column($curr_extras, 'extra'));
			$insert_extras = array();
			if (@$data['id'] == 0) {
				foreach ($extras as $new) {
					$insert_extras[] = array('extra' => $new['extra'], 'mandatory' => $new['mandatory'], 'product_type' => $ptype_id, 'version' => 1);
				}
				$this->$module_model->save_extras_batch($insert_extras);
			} else {
				foreach ($curr_extras as $curr) {
					foreach ($extras as $ex) {
						if ($curr['extra'] == $ex['extra']) {
							$ext = array('mandatory' => $ex['mandatory'],  'version' =>  $curr['version'] + 1);
							$this->$module_model->save_extras($ext, $curr['id']);
						}
					}
				}
				if ($new_extras) {
					foreach ($extras as $new) {
						if (in_array($new['extra'], $new_extras)) {
							$insert_extras[] = array('extra' => $new['extra'], 'mandatory' => $new['mandatory'], 'product_type' => $ptype_id, 'version' => 1);
						}
					}
					$this->$module_model->save_extras_batch($insert_extras);
				}
			}
		}
	}

	function delete_removed_extras($data, $product_type = 0)
	{
		$module_model = $this->module_model;
		$this->db->where('product_type', $product_type);
		if ($data) {
			$this->db->where_not_in('extra', array_column($data, 'extra'));
		}
		$this->$module_model->delete_extras_custom();
	}

	function get_existing_extras($product_type = 0)
	{
		$module_model = $this->module_model;
		$this->db->where('product_type', $product_type)->select('*');
		$data = $this->$module_model->gets_data_extras()->result_array();
		return $data;
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

	public function get_extras_dropdown($id = 0)
	{
		$module_model = $this->module_model;
		$data = array();
		if ($id > 0) {
			$this->db->where('a.product_type', $id)
				->join('extras e', 'e.id=a.extra', 'left')
				->select('e.id,e.name,1 as selected,a.mandatory');
			$data = $this->$module_model->gets_data_extras()->result_array();
		}
		$this->db->where(array('parent' => 0, 'is_enabled' => 1))->select('id,name,0 as selected,0 as mandatory');
		if ($data) {
			$this->db->where_not_in('id', array_column($data, 'id'));
		}
		$extras = $this->extrasmodel->gets_data()->result_array();
		$data = array_merge($data, $extras);


		return $data;
	}
	public function get_selected_extras($product_type_id)
{
    // Join the `product_type_extras` table with the `extras` table
    $this->db->select('pte.extra as id, e.name as name,e.mandatory as mandatory');
    $this->db->from('product_type_extras pte');
    $this->db->join('extras e', 'pte.extra = e.id');
    $this->db->where('pte.product_type', $product_type_id); // Filter by product type ID
    $query = $this->db->get();

    // Return the results as an array
    return $query->result_array();
}



	public function get_extras_for_all()
	{
		$data = array();
	
		// Fetch all available extras that are enabled and have no parent
		$this->db->where(array('parent' => 0, 'is_enabled' => 1))
			->select('id, name, 0 as selected, 0 as mandatory');
	
		// Get the extras
		$data = $this->extrasmodel->gets_data()->result_array();
	
		return $data;
	}
	
	

}
