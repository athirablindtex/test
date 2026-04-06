<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Company extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='usersmodel';
		var $view_folder='company';
		var $redirect='admin/company/list';
		var $module_caption='Company';
		var $module_active='company';
		var $module_active_sub='company';
		var $controller='company';
		var $permission='company'; 
		var $user_type="";
		var $valid_fields=array('name', 'email', 'password', 'image', 'phone', 'address', 'country','currency','vat','margin_type','reply_mail','company_email', 'margin_value', 'exchange_margin_permission','branding_colour_code','branding_colour_code_secondry');
		public function __construct(){
				parent::__construct();
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model));
				$this->user_type=$this->config->item('company_id');
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Company Name','required|trim|min_length[3]|max_length[100]');
				$this->form_validation->set_rules('email', 'Email', 'callback_email_check[admin_users.email.'.$this->input->post('id').']');
				$this->form_validation->set_rules('phone','Phone Number','required|trim|min_length[9]|max_length[13]');
				$this->form_validation->set_rules('margin_type','Margin Type','required|trim|callback_check_margin_type');
				$this->form_validation->set_rules('margin_value','Margin Value','required|trim|numeric|callback_check_margin_value['.$this->input->post('margin_type').']');
				if(@$this->input->post('id')==0){
						$this->form_validation->set_rules('password','Password','required|trim|callback_form_password_strength_check');
					}
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
								$this->db->where('user_group',$this->user_type);
								if($data['res']=$this->$module_model->get_row($id)){
										$data['edit']=1;
									}
							}
						$this->db->where('user_group',$this->user_type);
						$data['tabledata']=$this->$module_model->gets_all()->result();	
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
					   	$this->load->view('admin/include/template');
					}
				else{
						$this->check_user_privillages($this->permission.'_add');
						$data=$this->input->post();
						if(@$data['id']>0){
								$this->db->where('user_group',$this->user_type);
								if(!$this->$module_model->get_row(@$data['id'])){
										redirect('');
									}
							}
						unset($data['id']);
						unset($data['cropped_image']);
						unset($data['password']);
						$exchange_margin_permission=0;
						if(@$data['exchange_margin_permission'] && @$data['exchange_margin_permission']==1){
								$exchange_margin_permission=1;
							}
						@$data['exchange_margin_permission']=$exchange_margin_permission;
						if($this->input->post('password')!=''){
								$password1=$this->input->post('password');
								$password1=sha1($password1);
								$data['password']=crypt($this->config->item('site_salt'),$password1);
							}
						@$name = $this->upload();
						if(@$name!=''){
								$data['image']=@$name;
							}
						if(@$data){
								$valid=TRUE;
								foreach($data as $key=>$value){
										if(!in_array($key,$this->valid_fields)){
												$valid=FALSE;
												break;
											}
									}
								if($valid){
								
									 $id=$this->input->post('id');
								
								
										if(@$data['id']==0){
												$data['	user_group']=$this->user_type;
												$data['active']=1;
										
											}	
											if($id==0){
													audit_log('admin_users', $id, 'INSERT', 	$data,$new_details=null,1);
													$data['created_at']=date('Y-m-d H:i:s');
											}
											else {
												$old_data= $this->db->get_where('admin_users', ['id' => $this->input->post('id')])->row_array();
	                                              $data['updated_at']=date('Y-m-d H:i:s');

														audit_log('admin_users', $id, 'UPDATE',$old_data,$data,1);

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
		
		
		public function delete($id=0){
				$module_model=$this->module_model;
				if($id>0){
						$this->$module_model->delete($id);
					$old_data= $this->db->get_where('company', ['id' => $id])->row_array();

					   audit_log('company', $id, 'DELETE', 	$old_data,$new_details=null,1);
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
						
				audit_log('admin_users', $id, 'DISABLE',$old_data=null,$data=null,1);
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
							audit_log('admin_users', $id, 'ENABLE',$old_data=null,$data=null,1);
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
		
	////Form Validations
	function check_margin_type($str){
			$margin_type=array("Percentage","Value");
			if(in_array($str,$margin_type)){
					return TRUE;
				}
			else{
					$this->form_validation->set_message('check_margin_type', 'Invalid {field} Value');
					return FALSE;
				}	
		}	
	
	function check_margin_value($val,$margin_type){
			if($margin_type=="Percentage"){
					$min_value=0.1;
					$max_value=1000;
				}
			else if($margin_type=="Value"){
					$min_value=0.1;
					$max_value=10;
				}
			else{
					$this->form_validation->set_message('check_margin_value', 'Invalid Margin Type');
					return FALSE;
				}	
			if($val>$min_value && $val<$max_value){
					return TRUE;
				}
			else{
					$this->form_validation->set_message('check_margin_value', '{field} must between '.$min_value.' and '.$max_value.'');
					return FALSE;
				}			
		}	


		public function update_bank_info()
{

    $productId = $this->input->post('product_id');
		$old_data= $this->db->get_where('admin_users', ['id' => $productId])->row_array();
    $data = array(
        'bank_name'         => $this->input->post('bank'),
        'account_name' => $this->input->post('account_name'),
        'account_no'   => $this->input->post('account_no'),
        'iban'         => $this->input->post('iban'),
        'branch'       => $this->input->post('branch'),
        'swift_code'        => $this->input->post('swift'),
    );

    $this->db->where('id', $productId);
    $this->db->update('admin_users', $data);


    audit_log('admin_users', $productId, 'UPDATE', $old_data, $data, 1);

    $this->session->set_flashdata('success', 'Bank details updated.');
    redirect('admin/company/list'); // or wherever you want
}

public function get_bank_info()
{
    $id = $this->input->post('id');
    $query = $this->db->get_where('admin_users', ['id' => $id]);
    $product = $query->row();

    if ($product) {
        echo json_encode([
            'success' => true,
            'data' => [
                'bank' => $product->bank_name,
                'account_name' => $product->account_name,
                'account_no' => $product->account_no,
                'iban' => $product->iban,
                'branch' => $product->branch,
                'swift' => $product->swift_code,
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}


		
	}
?>