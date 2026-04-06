<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Usergroups extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='usergroupsmodel';
		var $view_folder='usergroups';
		var $redirect='admin/usergroups/list';
		var $module_caption='User Groups';
		var $module_active='users';
		var $module_active_sub='usergroups';
		var $controller='usergroups';
		public function __construct(){
				parent::__construct();
				if($this->session->userdata('admin_type')!=$this->config->item('super_admin_id')){
						redirect('');
					}
				$this->load->model(array($this->module_model,'usergroupsmodel'));
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','usergroups Name','required|trim');
				if($this->form_validation->run()==FALSE){
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
				$this->form_validation->set_rules('name','usergroups Name','required|trim');
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
		public function permissions($id=0){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('user','user','required|trim');
				if($this->form_validation->run()==FALSE){
						if($id>0){
									if($data['user_group']=$this->$module_model->get_row($id)){
											$module_model=$this->module_model;
											$view_folder=$this->view_folder;
											$data['page']='Permissions '.$this->module_caption;
											$data['module']=$this->module_caption;
											$data['active']=$this->module_active;
											$data['active_sub']=$this->module_active_sub;
											$data['active_sub_sub']=$this->module_active_sub.'_add';
											$data['content']='admin/'.$view_folder.'/permissions';
											$data['module_id']=$this->module_id;
											$data['controller']=$this->controller;
											$this->db->select('module');
											$this->db->where('user',$this->uri->segment('4'));
											$arr=$this->usergroupsmodel->get_privillages_user_edit()->result_array();
											$pr=array();
											foreach($arr as $a){
													$pr[]=$a['module'];
												}
											$data['pr']=$pr;
											$this->db->where('parent !=','');
											$data['all']=$this->usergroupsmodel->get_modules()->result();
											$this->db->where('parent',' ');
											$data['modules']=$this->usergroupsmodel->get_modules()->result();
											$this->load->vars($data);
											$this->load->view('admin/include/template');
										}
																		
							}
					}
				else{
						$per=$this->input->post('permission');
						$user=$this->input->post('user');
						$this->db->where('user',$user);
						$this->usergroupsmodel->delete_userroles();
						$post=array();
						$i=0;
						if(count($per)>0){
								foreach($per as $p){
										$i++;
										$post[$i]['user']=$user;
										$post[$i]['module']=$p;
									}
								$this->usergroupsmodel->insert_permissions($post);
							}
						$this->session->set_flashdata('success','Item Updated Succesfully');
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