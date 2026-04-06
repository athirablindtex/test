<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Result extends MY_Controller
	{
		var $module_id = 'id';
    	var $module_model='resultmodel';
		var $view_folder='result';
		var $redirect='admin/result';
		var $module_caption='Result';
		var $module_active='result';
		var $module_active_sub='result';
		var $controller='result';
		var $permission='log';
		var $admin_id=0;
		var $admin_group=0;
		public function __construct(){
				parent::__construct();
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model,'usersmodel'));
				$this->admin_id=$this->session->userdata('admin_id');
				$this->admin_group = $this->session->userdata('admin_type');
			}
		public function index(){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$data['page']='Manage '.$this->module_caption;
				$data['module']=$this->module_caption;
				$data['active']=$this->module_active;
				$data['active_sub']=$this->module_active_sub.'_list';
				$data['active_sub_sub']=$this->module_active_sub.'_list';
				$data['content']='admin/'.$view_folder.'/list';
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$data['module_id']=$this->module_id;
				$data['controller']=$this->controller;
				$data['content']='admin/'.$view_folder.'/list';
				$this->load->vars($data);
			   	$this->load->view('admin/include/template');
			}
		public function add($id=0){
				$this->check_user_privillages($this->permission.'_add');
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Name','required|trim');
				if($this->form_validation->run()==FALSE){
						$data['page']='Add '.$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub.'_add';
						$data['active_sub_sub']=$this->module_active_sub.'_add';
						$data['content']='admin/'.$view_folder.'/add';
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						if($id>0){
								$data['res']=$this->$module_model->get_row($id);
							}
						else{
								$data['error']=$this->input->post('');
							}
						$data['parent']=$this->usergroupsmodel->gets_all()->result();
						$this->load->vars($data);
			   			$this->load->view('admin/include/template');
					}
				else{
						$data=$this->input->post();
						unset($data['id']);
						if($this->input->post('result_date')){
								$data['result_date']=date('Y-m-d',strtotime($this->input->post('result_date')));
							}
						if(@$this->input->post('id')==0){
								$data['created_date']=date('Y-m-d');
							}
						$id=$this->$module_model->save($data, $this->input->post('id'));
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect($this->redirect);
					}
			}
		public function sort($or,$id){
				$module_model=$this->module_model;
				$gt=$this->$module_model->sort($or,$id);
			}
		public function delete($id=0){
				$this->check_user_privillages($this->permission.'_delete');
				$module_model=$this->module_model;
				if($id>0){
						$this->$module_model->delete($id);
						$this->session->set_flashdata('success',$this->lang->line('common_delete_success'));
						redirect($this->redirect);
					}
				else{
					echo "ERROR";
				}
			}
		public function view($id=0){
				if($id>0){
						$this->check_previllage_item($id);
					}
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$data['product']=$this->$module_model->get_row($id);
						$data['page']='View '.$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub.'_list';
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/view';
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['content']='admin/'.$view_folder.'/view';
						$this->load->vars($data);
			   			$this->load->view('admin/include/template');
				}
				else{
					echo "ERROR";
				}
			}
		public function disable($id=0){
				if($id>0){
						$this->check_previllage_item($id);
					}
				$this->check_user_privillages($this->permission.'_add');
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$update["active"]=0;
						$this->$module_model->save($update,$id);
						$this->session->set_flashdata('success',$this->lang->line('common_disable_success'));
						redirect($this->redirect);
				}
				else{
					echo "ERROR";
				}
		}
		public function enable($id=0){
			if($id>0){
					$this->check_previllage_item($id);
					$this->check_user_count($id);
				}
			$this->check_user_privillages($this->permission.'_add');
			$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$update["active"]=1;
						$this->$module_model->save($update,$id);
						$this->session->set_flashdata('success',$this->lang->line('common_enable_success'));
						redirect($this->redirect);
				}
				else{
					echo "ERROR";
				}
		}
		public function upload() {
        		$config['upload_path'] = APPPATH . '../uploads/users/';
        		$config['allowed_types'] = 'gif|png|jpg|jpeg';
        		$this->load->library('upload', $config);
        		if (!$this->upload->do_upload('image')) {
            			return false;
        			} 
				else {
            			$name = $this->upload->data();
            			$file = $name['file_name'];
            			$path = APPPATH . '../uploads/users/' . $file;
            			$this->base64_to_jpeg($this->input->post('cropped_image'), $path);
            			return $file;
        			}
    		}
	function check_previllage_item($id=0){
			$module_model=$this->module_model;
			if($this->admin_group != $this->config->item('super_admin_id')){
					$this->db->where(array('id'=>$id,'admin_id'=>$this->admin_id));
					if($this->$module_model->gets_data()->row()){
							return TRUE;
						}
					else{
							redirect($this->redirect);
						}	
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
	}
?>