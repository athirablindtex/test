<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Customer extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='customermodel';
		var $view_folder='customer';
		var $redirect='admin/customer/list';
		var $module_caption='Customer';
		var $module_active='customer';
		var $module_active_sub='customer';
		var $controller='customer';
		var $permission='customer';
		var $user_type="";
		var $company_id="";
		var $valid_fields=array('name', 'email', 'password', 'phone', 'address', 'remarks','company','sales_person');
		public function __construct(){
				parent::__construct();
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model,'salespersonmodel','usersmodel','appointmentmodel'));
				$this->company_id=$this->config->item('company_id');
				if ($this->session->userdata('admin_type') !== 1) {

			$companySession = $this->session->userdata('admin_companies');

			if (!empty($companySession)) {
				$this->companies = is_array($companySession)
					? $companySession
					: [$companySession];
			}
		}
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Customer Name','required|trim|alpha_numeric_spaces|min_length[3]|max_length[20]');
				$this->form_validation->set_rules('email','Email','valid_email');
				$this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[9]|max_length[13]|callback_field_check[customer.phone.id.'.$this->input->post('id').']');
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
						if(@$this->input->get('sales_person')>0){
								$this->db->where('sales_person',$this->input->get('sales_person'));
							}	
						$data['tabledata']=$this->$module_model->gets_all()->result();	
						$this->db->where('active',1)->select('id,name');
						$this->db->where_in('company',$this->companies);
						 
						$data['sales_person']=$this->salespersonmodel->gets_data()->result();
						$this->db->where('user_group',$this->company_id)->select('id,name');
						
					    $this->db->where_in('id',$this->companies);
						$data['company']=$this->usersmodel->gets_data()->result();
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
					   	$this->load->view('admin/include/template');
					}
				else{
						$this->check_user_privillages($this->permission.'_add');
						$data=$this->input->post();
						$appoint_date=$data['appointment_date'];
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
												$data['active']=1;
												$data['created_date']=date('Y-m-d');
												$data['updated_at']   = date('Y-m-d H:i:s');
												$data['version']=1;
											}	
										else{
												if($cur_row=$this->$module_model->get_row($this->input->post('id'))){
														$data['version']=$cur_row->version+1;
													}
											}	
										$id=$this->$module_model->save($data, $this->input->post('id'));
										
										// Create empty quotation for new customer
										if ($id > 0 && $this->input->post('id') == 0 && !empty($data['sales_person']) && $this->input->post('create_quotation')) {
											$this->load->model('quotationmodel');
											$this->load->library('Firebase');
											
											$customer_data = [
												'name'    => $data['name'] ?? '',
												'phone'   => $data['phone'] ?? '',
												'address' => $data['address'] ?? '',
												'email'   => $data['email'] ?? ''
											];
											
											$quotation_id = $this->quotationmodel->create_empty_quotation(
												$id,
												$data['sales_person'] ?? 0,
												$data['company'] ?? 0,
												$customer_data
											);
											
											// Send notification to sales person
											if ($quotation_id > 0 && !empty($data['sales_person'])) {
												$tokenData = $this->db
													->select('push_token')
													->where('user_id', $data['sales_person'])
													->where('push_token IS NOT NULL', null, false)
													->order_by('id', 'DESC')
													->limit(1)
													->get('login_tokkens')
													->row();
												
												if ($tokenData && !empty($tokenData->push_token)) {
													$title   = "New Quotation Created";
													$message = "New quotation for customer " . ($data['name'] ?? '') . " has been created";
													$this->firebase->sendNotification($tokenData->push_token, $title, $message);
												}
											}
										}
										
										if($appoint_date!=""){
												$appoint=array('customer'=>$id,'appointment_date'=>date('Y-m-d',strtotime($appoint_date)),'sales_person'=>$data['sales_person'],'company'=>$data['company'],'created_date'=>date('Y-m-d'),'remarks'=>$data['remarks']);
												$appoint["customer_name"]=$data['name'];
												$appoint["customer_phone"]=$data['phone'];
												if($sp=$this->salespersonmodel->get_row($data['sales_person'])){
														$appoint["sales_person_name"]=$sp->name;
														$appoint["sales_person_phone"]=$sp->phone;
													}	
												$this->appointmentmodel->save($appoint);
											}
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
        		$config['upload_path'] = APPPATH . '../uploads/category/';
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