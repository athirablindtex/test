<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Site_config extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='site_configmodel';
		var $view_folder='site_config';
		var $redirect='admin/site_config/list';
		var $module_caption='Website Configuration';
		var $module_active='users';
		var $module_active_sub='config';
		var $controller='site_config';
		var $permission='';
		public function __construct(){
				parent::__construct();
				if($this->session->userdata('admin_type')!=$this->config->item('super_admin_id')){
						redirect('');
					}
				$this->load->model(array($this->module_model));
			}
			public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('key_config','Key','required|trim');
				$this->form_validation->set_rules('value','Value','required|trim');
				if($this->form_validation->run()==FALSE){
						$data['error']=@validation_errors();
						$data['page']=$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/list';
						$data['edit']=0;
						if($type=="edit"){
								if($data['res']=$this->$module_model->get_row($id)){
										$data['edit']=1;
									}
							}
						if($data['edit']==0){
								$data['tabledata']=$this->$module_model->gets_all()->result();
							}
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['content']='admin/'.$view_folder.'/list';
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
						$this->load->view('admin/include/template');
					}
				else{
						$data=$this->input->post();
						unset($data['id']);
						$id=$this->$module_model->save($data, $this->input->post('id'));
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect($this->redirect);
					}	
			}
		public function add($id=0){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('key_config','Key','required|trim');
				$this->form_validation->set_rules('value','Value','required|trim');
				if($this->form_validation->run()==FALSE){
						$data['page']='Add '.$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
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
						$this->load->vars($data);
			   			$this->load->view('admin/include/template');
					}
				else{
						$data=$this->input->post();
						unset($data['id']);
						$id=$this->$module_model->save($data, $this->input->post('id'));
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect($this->redirect);
					}
			}
		public function delete($id=0){
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
		
		public function disable($id=0){
			$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$update["is_enabled"]=0;
						$this->$module_model->save($update,$id);
						$this->session->set_flashdata('success',$this->lang->line('common_disable_success'));
						redirect($this->redirect);
				}
				else{
					echo "ERROR";
				}
		}
		public function enable($id=0){
			$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$update["is_enabled"]=1;
						$this->$module_model->save($update,$id);
						$this->session->set_flashdata('success',$this->lang->line('common_enable_success'));
						redirect($this->redirect);
				}
				else{
					echo "ERROR";
				}
		}
		
	}
?>