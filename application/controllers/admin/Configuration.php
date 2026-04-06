<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Configuration extends MY_Controller
	{
		 var $module_id = 'id';
		 var $single_name="configuration";
		var $valid_fields=array('vat_addition');
		public function __construct(){
				parent::__construct();
				$this->module_model=$this->single_name.'model';
				$this->view_folder=$this->single_name;
				$this->redirect='admin/'.$this->single_name.'/list';
				$this->module_caption='Configuration';
				$this->module_active=$this->single_name;
				$this->module_active_sub=$this->single_name;
				$this->controller=$this->single_name;
				$this->permission=$this->single_name;
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model,'email_configurationmodel'));
				
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('vat_addition','Vat Addition','required|trim|numeric|callback_check_vat_addition');
				if($this->form_validation->run()==FALSE){
						$data['page']=$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/list';
						$data['redirect']=site_url().$this->redirect;
						$data['res']=$this->$module_model->gets_all()->row();	
						$data['emails']=$this->email_configurationmodel->gets_data()->result();
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
						$post=array('vat_addition'=>$data['vat_addition']);
						$id=$this->$module_model->save($post, $this->input->post('id'));
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect($this->redirect);	
					}	
				
			
			}

		public function add_email(){
				$this->form_validation->set_rules('email','Email Address','required|trim|valid_email');
				if($this->form_validation->run()==FALSE){
						$this->session->set_flashdata('error',@validation_errors());
					}
				else{
						$data=array('email'=>$this->input->post('email'));
						$this->email_configurationmodel->save($data);
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
					}	
				redirect($this->redirect);	
			}	
		
		public function sort($or,$id){
				$module_model=$this->module_model;
				$gt=$this->$module_model->sort($or,$id);
			}
		public function delete($id=0){
				$module_model=$this->module_model;
				if($id>0){
						$this->email_configurationmodel->delete($id);
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
		
	function check_vat_addition($val){
			$min_value=0.1;
			$max_value=10;
			if($val>$min_value && $val<$max_value){
					return TRUE;
				}
			else{
					$this->form_validation->set_message('check_vat_addition', '{field} must between '.$min_value.' and '.$max_value.'');
					return FALSE;
				}		
		}

	}
?>