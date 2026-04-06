<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Appointment extends MY_Controller
	{
		 var $module_id = 'id';
		 var $single_name="appointment";
		var $valid_fields=array('appointment_date','customer','sales_person','company','remarks');
		var $company_id="";
		public function __construct(){
				parent::__construct();
				$this->module_model=$this->single_name.'model';
				$this->view_folder=$this->single_name;
				$this->redirect='admin/'.$this->single_name.'/list';
				$this->module_caption='Appointment';
				$this->module_active=$this->single_name;
				$this->module_active_sub=$this->single_name;
				$this->controller=$this->single_name;
				$this->permission=$this->single_name;
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model,'usersmodel','salespersonmodel','customermodel'));
				$this->company_id=$this->config->item('company_id');
				
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('appointment_date','Appointment Date','required|trim');
				if($this->form_validation->run()==FALSE){
						$data['page']=$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/list';
						$data['redirect']=site_url().$this->redirect;
						$data['edit']=0;
						if(@$type=="edit"){
								$this->check_user_privillages($this->permission.'_add');
								if($data['res']=$this->$module_model->get_row($id)){
										$data['edit']=1;
									}
							}
						if($this->input->get('from')){
								$this->db->where('a.appointment_date >=',date('Y-m-d',strtotime($this->input->get('from'))));
							}
						if($this->input->get('to')){
								$this->db->where('a.appointment_date <=',date('Y-m-d',strtotime($this->input->get('to'))));
							}
						if(@$this->input->get('sales_person')>0){
								$this->db->where('a.sales_person',$this->input->get('sales_person'));
							}		
						if(@$this->input->get('company')>0){
								$this->db->where('a.company',$this->input->get('company'));
							}			
						$this->db->join('admin_users u','u.id=a.company','left')
								->select('a.*,u.name as company_name');	
						$data['tabledata']=$this->$module_model->gets_all()->result();	
						$this->db->where('active',1)->select('id,name');
						$data['sales_person']=$this->salespersonmodel->gets_data()->result();
						$this->db->where('user_group',$this->company_id)->select('id,name');
						$data['company']=$this->usersmodel->gets_data()->result();
						$this->db->select('id,name');
						$data['customer']=$this->customermodel->gets_data()->result();
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
					   	$this->load->view('admin/include/template');
					}
				else{
						$this->check_user_privillages($this->permission.'_add');
						$data=$this->input->post();
						unset($data['id']);
						if(@$data){
								$valid=TRUE;
								foreach($data as $key=>$value){
										if(!in_array($key,$this->valid_fields)){
												unset($data[$key]);
											}
									}
								if($valid){
										if(@$data['id']==0){
												//$data['is_enabled']=1;
												$data['created_date']=date('Y-m-d');
											}	
										if($cs=$this->customermodel->get_row($data['customer'])){
												$data["customer_name"]=$cs->name;
												$data["customer_phone"]=$cs->phone;
											}		
										if($sp=$this->salespersonmodel->get_row($data['sales_person'])){
												$data["sales_person_name"]=$sp->name;
												$data["sales_person_phone"]=$sp->phone;
											}		
										$id=$this->$module_model->save($data, $this->input->post('id'));
										$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
									}
								else{
										$this->session->set_flashdata('error',"Data Error Occured");
									}		
							}
						else{
								$this->session->set_flashdata('error',"Data Error Occured");
							}
						redirect($this->redirect);	
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
						$data['active_sub']=$this->module_active_sub;
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
        		$config['upload_path'] = APPPATH . '../uploads/customer/';
        		$config['allowed_types'] = 'gif|png|jpg|jpeg';
        		$this->load->library('upload', $config);
        		if (!$this->upload->do_upload('image')) {
            			return false;
        			} 
				else {
            			$name = $this->upload->data();
            			$file = $name['file_name'];
            			//$path = APPPATH . '../uploads/users/' . $file;
            			//$this->base64_to_jpeg($this->input->post('cropped_image'), $path);
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