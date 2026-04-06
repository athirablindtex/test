<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Subproducttype extends MY_Controller
	{
		 var $module_id = 'id';
		 var $single_name="subproducttype";
		var $valid_fields=array('name','parent','blind_code','fabricDependentCode');
		public function __construct(){
				parent::__construct();
				$this->module_model='producttypemodel';
				$this->view_folder=$this->single_name;
				$this->redirect='admin/'.$this->single_name.'/list';
				$this->module_caption='Vendor';
				$this->module_active='master';
				$this->module_active_sub=$this->single_name;
				$this->controller=$this->single_name;
				$this->permission='producttype';
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model,'extrasmodel'));
			
				
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Vendor Name','required|trim|alpha_numeric_spaces|min_length[3]|max_length[30]');
				$this->form_validation->set_rules('parent','Product Type','required|trim');
				if($this->form_validation->run()==FALSE){
						$data['page']=$this->module_caption;
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/list';
						$data['redirect']=site_url().$this->redirect;
						$data['edit']=0;
						$data['extras_sel']=array();
						if(@$type=="edit"){
								$this->check_user_privillages($this->permission.'_add');
								if($data['res']=$this->$module_model->get_row($id)){
										$data['edit']=1;
										$this->db->where('product_type',$id)->select('extra');
										$exs=$this->$module_model->gets_data_extras()->result_array();
										$data['extras_sel']=array_column($exs,'extra');
									}
							}
						if(@$this->input->get('sales_person')>0){
								$this->db->where('sales_person',$this->input->get('sales_person'));
							}
						$this->db->where('a.parent !=',0)
								->join('product_type p','p.id=a.parent','left')
								->select('a.*,p.name as parent_name');		
						$data['tabledata']=$this->$module_model->gets_all()->result();
						$this->db->where('parent',0)->select('id,name,blind_code');		
						$data['parent']=$this->$module_model->gets_all()->result();	
						$this->db->where('parent',0)->select('id,name');		
						$data['extras']=$this->extrasmodel->gets_all()->result();	
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
					   	$this->load->view('admin/include/template');
					}
				else{
						$this->check_user_privillages($this->permission.'_add');
						$data=$this->input->post();
				
						//$extras=$data['extras'];
						unset($data['id']);
						if(@$data){
								$valid=TRUE;
								foreach($data as $key=>$value){
										if(!in_array($key,$this->valid_fields)){
												unset($data[$key]);
											}
									}
								if($valid){
										if(@$this->input->post('id')==0){
												$data['is_enabled']=1;
												$data['created_date']=date('Y-m-d');
												$data['version']=1;
											}	
										else{
												if($row=$this->$module_model->get_row($this->input->post('id'))){
														$data['version']=$row->version+1;
													}
											}	
										$id=$this->$module_model->save($data, $this->input->post('id'));
										// $this->db->where('product_type',$id)->select('extra');
										// $exs=$this->$module_model->gets_data_extras()->result_array();
										// $exist_extras=array_column($exs,'extra');
										// $insert_extras=array_diff($extras,$exist_extras);
										// $del_extras=array_diff($exist_extras,$extras);
										// if(count($del_extras)>0){
										// 		foreach($del_extras as $dl){
										// 				$this->db->where(array('extra'=>$dl,'product_type'=>$id));
										// 				$this->$module_model->delete_extras_custom();
										// 			}
										// 	}
										// if(count($insert_extras)>0){
										// 		$insert_dat=array();
										// 		foreach($insert_extras as $in){
										// 				$insert_dat[]=array('extra'=>$in,'product_type'=>$id,'version'=>1);
										// 			}
										// 		$this->$module_model->save_extras_batch($insert_dat);	
										// 	}	
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