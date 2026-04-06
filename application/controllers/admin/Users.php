<?php if (! defined('BASEPATH')) {
	exit('No direct script allowed');
}
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
class Users extends MY_Controller
{
	var $module_id = 'id';
	var $module_model = 'usersmodel';
	var $view_folder = 'users';
	var $redirect = 'admin/users/list';
	var $module_caption = 'Users';
	var $module_active = 'users';
	var $module_active_sub = 'users';
	var $controller = 'users';
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('admin_type') != $this->config->item('super_admin_id')) {
			redirect('');
		}
		$this->load->model(array($this->module_model, 'usergroupsmodel'));
	}
	public function list($type = "list", $id = "")
	{
		
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$data['selected_companies'] = array();
		$this->form_validation->set_rules('name', 'users Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'callback_email_check[admin_users.email.' . $this->input->post('id') . ']');
		if ($this->form_validation->run() == FALSE) {
			$data['page'] = $this->module_caption;
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_list';
			$data['content'] = 'admin/' . $view_folder . '/list';

			$data['companies'] = $this->$module_model->gets_company_all()->result();


			$data['edit'] = 0;
			if (@$type == "edit") {
				if ($data['res'] = $this->$module_model->get_row($id)) {
					$data['edit'] = 1;
					$selected_companies = $this->db->select('company_id')
						->from('admin_companies')
						->where('user_id', $id)
						->get()
						->result_array();
					$data['selected_companies'] = array_column($selected_companies, 'company_id');
				}
			}
			if ($data['edit'] == 0) {
				$data['tabledata'] = $this->$module_model->gets_all_details()->result();
			}
			$data['parent'] = $this->usergroupsmodel->gets_all()->result();
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$data = $this->input->post();

			unset($data['id']);
			unset($data['cropped_image']);
			unset($data['password']);
			unset($data['companies']);
			if ($this->input->post('password') != '') {
				$password1 = $this->input->post('password');
				$password1 = sha1($password1);
				$data['password'] = crypt($this->config->item('site_salt'), $password1);
			}
			@$name = $this->upload();
			if (@$name != '') {
				$data['image'] = @$name;
			}

			$id = $this->$module_model->save($data, $this->input->post('id'));
			$companies = $this->input->post('companies');

			if (!empty($companies)) {

				// Remove "Select All" value
				$companies = array_filter($companies, function ($v) {
					return $v !== 'all';
				});


				$this->$module_model->delete_user_companies($id);


				$company_data = [];

				foreach ($companies as $company_id) {
					$company_data[] = [
						'user_id'    => $id,
						'company_id' => $company_id
					];
				}


				if (!empty($company_data)) {
					$this->db->insert_batch('admin_companies', $company_data);
				}
				$this->session->set_flashdata('success', $this->lang->line('common_update_success'));
				redirect($this->redirect);
			}
		}
	}
	public function add($id = 0)
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$this->form_validation->set_rules('name', 'users Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'callback_email_check[admin_users.email.' . $this->input->post('id') . ']');
		if ($this->form_validation->run() == FALSE) {
			$data['page'] = 'Add ' . $this->module_caption;
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_add';
			$data['content'] = 'admin/' . $view_folder . '/add';
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['companies'] = $this->$module_model->gets_company_all()->result();
			if ($id > 0) {
				$data['res'] = $this->$module_model->get_row($id);
			} else {
				$data['error'] = $this->input->post('');
			}
			$data['parent'] = $this->usergroupsmodel->gets_all()->result();
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$data = $this->input->post();
			unset($data['id']);
			unset($data['cropped_image']);
			unset($data['password']);
			unset($data['companies']);
			if ($this->input->post('password') != '') {
				$password1 = $this->input->post('password');
				$password1 = sha1($password1);
				$data['password'] = crypt($this->config->item('site_salt'), $password1);
			}
			@$name = $this->upload();
			if (@$name != '') {
				$data['image'] = @$name;
			}
			$id = $this->$module_model->save($data, $this->input->post('id'));


			$this->session->set_flashdata('success', $this->lang->line('common_update_success'));
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
		$config['upload_path'] = APPPATH . '../uploads/users/';
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
