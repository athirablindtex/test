<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Admins extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='usersmodel';
		var $view_folder='admins';
		var $redirect='admin/admins';
		var $module_caption='Site Admins';
		var $module_active='admins';
		var $module_active_sub='admins';
		var $controller='admins';
		public function __construct(){
				parent::__construct();
				if($this->session->userdata('admin_type')==$this->config->item('super_admin_id')){
						redirect('');
					}
				$this->load->model(array($this->module_model,'usergroupsmodel','employeemodel'));
			}
		public function index(){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$data['page']='Manage '.$this->module_caption;
				$data['module']=$this->module_caption;
				$data['active']=$this->module_active;
				//$data['active_sub']=$this->module_active_sub;
				$data['active_sub']=$this->module_active_sub.'_list';
				$data['content']='admin/'.$view_folder.'/list';
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$data['module_id']=$this->module_id;
				$data['controller']=$this->controller;
				$data['content']='admin/'.$view_folder.'/list';
				$this->db->where('user_group',$this->config->item('admin_id'));
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$this->load->vars($data);
			   	$this->load->view('admin/include/template');
			}
		public function add($id=0){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','users Name','required|trim');
				$this->form_validation->set_rules('email', 'Email', 'callback_email_check[admin_users.email.'.$this->input->post('id').']');
				if($this->form_validation->run()==FALSE){
						$data['page']='Add '.$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						
						$data['active_sub']=$this->module_active_sub.'_add';
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
						unset($data['cropped_image']);
						unset($data['password']);
						$data['user_group']=$this->config->item('admin_id');
						if($this->input->post('expiry_date')){
								$data['expiry_date']=date('Y-m-d',strtotime($this->input->post('expiry_date')));
							}
						if($this->input->post('password')!=''){
								$password1=$this->input->post('password');
								$password1=sha1($password1);
								$data['password']=crypt($this->config->item('site_salt'),$password1);
							}
						@$name = $this->upload();
						if(@$name!=''){
								$data['image']=@$name;
							}
						$id=$this->$module_model->save($data, $this->input->post('id'));
						$this->check_employee_count($id);
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect($this->redirect);
					}
			}
		public function check_employee_count($id){
			$module_model=$this->module_model;
				$user=$this->$module_model->get_row($id);	
				if($user){
						if(strtotime($user->expiry_date)>strtotime(date('Y-m-d'))){
								$this->db->where('admin_id',$id)->where('active',1);	
								$emp=$this->employeemodel->gets_data()->result_array();
								if(count($emp)>=$user->employees_limit){
										$rem=count($emp)-$user->employees_limit;
										$dis=array();
										for($i=0;$i<$rem;$i++){
												$dis[]=$emp[$i]['id'];
											}
										if($dis){
												$updad['active']=0;
												$this->db->where_in('id',$dis);
												$this->employeemodel->save_custom($updad);
											}
									}
							}
						else{
								$upd['active']=0;
								$this->$module_model->save($upd, $id);
								$updad['active']=0;
								$this->db->where('admin_id',$id);
								$this->employeemodel->save_custom($updad);
							}
					}
			}
		public function sort($or,$id){
				$module_model=$this->module_model;
				$gt=$this->$module_model->sort($or,$id);
			}
		public function delete($id=0){
				$module_model=$this->module_model;
				if($id>0){
						$this->$module_model->delete($id);
						$updad['active']=0;
						$this->db->where('admin_id',$id);
						$this->employeemodel->save_custom($updad);
						$this->session->set_flashdata('success',$this->lang->line('common_delete_success'));
						redirect($this->redirect);
					}
				else{
					echo "ERROR";
				}
			}
		public function view($id=0){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				if($id>0){
						$data['product']=$this->$module_model->get_row_details($id);
						$data['page']='View '.$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub.'_list';
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