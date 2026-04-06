<?php if (! defined('BASEPATH')) {
	exit('No direct script allowed');
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class Salesperson extends MY_Controller
{
	var $module_id = 'id';
	var $module_model = 'salespersonmodel';
	var $view_folder = 'salesperson';
	var $redirect = 'admin/salesperson/list';
	var $module_caption = 'Sales person';
	var $module_active = 'salesperson';
	var $module_active_sub = 'salesperson';
	var $controller = 'salesperson';
	var $permission = 'salesperson';
	var $company_id = "";
	var $valid_fields = array('name', 'company', 'email', 'password', 'image', 'phone', 'address');
	public function __construct()
	{
		parent::__construct();
		$this->check_user_privillages($this->permission . '_list');
		$this->load->model(array($this->module_model, 'usersmodel'));
		$this->company_id = $this->config->item('company_id');


		if ($this->session->userdata('admin_type') !== 1) {

			$companySession = $this->session->userdata('admin_companies');

			if (!empty($companySession)) {
				$this->companies = is_array($companySession)
					? $companySession
					: [$companySession];
			}
		}
	}
	public function list($type = "list", $id = "")
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$this->form_validation->set_rules('name', 'Sales person Name', 'required|trim|alpha_numeric_spaces|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[9]|max_length[13]|callback_field_check[sales_person.phone.id.' . $this->input->post('id') . ']');
		if (@$this->input->post('id') == 0) {
			$this->form_validation->set_rules('password', 'Password', 'required|trim');
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
			if (@$type == "edit") {
				$this->check_user_privillages($this->permission . '_add');
				if ($data['res'] = $this->$module_model->get_row($id)) {
					$data['edit'] = 1;
				}
			}
			$company = $this->input->get('company');
			$filter = [];

			if (!empty($company) && $company > 0) {
				$filter['a.company'] = $company;
			} else {
				if (!empty($this->companies)) {
					$filter['a.company'] = $this->companies;
				}
			}

			$data['tabledata'] = $this->$module_model->gets_all($filter)->result();



			$this->db->where_in('id', $this->companies);
			$this->db->where('user_group', $this->company_id);

			$data['company'] = $this->usersmodel->gets_data()->result();
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$this->check_user_privillages($this->permission . '_add');
			$data = $this->input->post();
			// echo "<pre>";print_r($data);echo "</pre>";
			unset($data['id']);


			if ($this->input->post('password') != '') {
				$password1 = $this->input->post('password');
				$password1 = sha1($password1);
				$data['password'] = crypt($this->config->item('site_salt'), $password1);
			} else if ($this->input->post('new_password') != '') {
				$password1 = $this->input->post('new_password');
				$password1 = sha1($password1);
				$data['password'] = crypt($this->config->item('site_salt'), $password1);
			}






			$name = @$this->upload();
			if (@$name != '') {
				$data['image'] = @$name;
			}
			if (@$data) {
				$valid = TRUE;
				foreach ($data as $key => $value) {
					if (!in_array($key, $this->valid_fields)) {
						unset($data[$key]);
					}
				}
				if ($valid) {
					if (@$this->input->post('id') == 0) {
						$data['active'] = 1;
						$data['created_date'] = date('Y-m-d');
						$data['version'] = 1;
					} else {
						if ($row = $this->$module_model->get_row($this->input->post('id'))) {
							$data['version'] = $row->version + 1;
						}
					}


					$id = $this->$module_model->save($data, $this->input->post('id'));
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
			if ($row = $this->$module_model->get_row($id)) {
				$update['version'] = $row->version + 1;
			}
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
			if ($row = $this->$module_model->get_row($id)) {
				$update['version'] = $row->version + 1;
			}
			$this->$module_model->save($update, $id);
			$this->session->set_flashdata('success', $this->lang->line('common_enable_success'));
			redirect($this->redirect);
		} else {
			echo "ERROR";
		}
	}
	public function upload()
	{
		$config['upload_path'] = APPPATH . '../uploads/salesperson/';
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
}
