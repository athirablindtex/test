<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Extras extends MY_Controller
	{
		 var $module_id = 'id';
		 var $single_name="extras";
		var $valid_fields=array('name','parent');
		public function __construct(){
				parent::__construct();
				$this->module_model=$this->single_name.'model';
				$this->view_folder=$this->single_name;
				$this->redirect='admin/'.$this->single_name.'/list';
				$this->module_caption='Price Band';
				$this->module_active=$this->single_name;
				$this->module_active_sub=$this->single_name;
				$this->controller=$this->single_name;
				$this->permission=$this->single_name;
				$this->check_user_privillages($this->permission.'_list');
				$this->load->model(array($this->module_model));
				$this->load->library("pagination");
				
			}
		public function list($type="list",$id=""){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Product Name','required|trim|min_length[3]|max_length[200]');
				if($this->form_validation->run()==FALSE){
						$limit=5;
						$data['page']=$this->module_caption;
						$data['extras_parent'] = $this->$module_model->get_parents();
						$data['module']=$this->module_caption;
						$data['active']=$this->module_active;
						$data['active_sub']=$this->module_active_sub;
						$data['active_sub_sub']=$this->module_active_sub.'_list';
						$data['content']='admin/'.$view_folder.'/list';
						$data['redirect']=site_url().$this->redirect;
						$data['edit']=0;
						if(@$type=="edit"){
								$this->check_user_privillages($this->permission.'_add');
								/*if($data['res']=$this->$module_model->get_row($id)){
										$data['edit']=1;
									}*/
								$this->db->where(array('id'=>$id,'parent'=>0));
								if($data['res']=$this->$module_model->gets_data()->row_array()){
										$data['edit']=1;
										$i=0;
										$this->db->where('parent',$data['res']['id']);
										$data['sub']=$this->$module_model->gets_data()->result_array();
										foreach($data['sub'] as $s){
												$this->db->where('parent',$s['id']);
												$data['sub'][$i]['sub_sub']=$this->$module_model->gets_data()->result_array();
												$j=0;
												foreach($data['sub'][$i]['sub_sub'] as $ss){
														$this->db->where('parent',$ss['id']);
														$data['sub'][$i]['sub_sub'][$j]['sub_sub_sub']=$this->$module_model->gets_data()->result_array();
														$j++;
													}
												$i++;
											}
										
									}	
							}
							$config = array();
							$config["base_url"] = site_url() . "admin/extras/list/";
							$config["total_rows"] = $this->$module_model->gets_data_admin_table_count();
							$config["per_page"] = $limit;
							$config["uri_segment"] = 4;
							$config['reuse_query_string'] = true;

							$config['full_tag_open'] = '<ul class="pagination mt-4 mx-auto text-center">';
							$config['full_tag_close'] = '</ul>';
							$config['num_tag_open'] = ' <li class="page-item">';
							$config['num_tag_close'] = '</li>';
							$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
							$config['cur_tag_close'] = '</a></li>';
							$config['prev_tag_open'] = '<li class="page-item">';
							$config['prev_tag_close'] = '</li>';
							$config['first_tag_open'] = '<li class="page-item">';
							$config['first_tag_close'] = '</li>';
							$config['last_tag_open'] = '<li class="page-item">';
							$config['last_tag_close'] = '</li>';
						
						
						
							$config['prev_link'] = 'Previous';
							$config['prev_tag_open'] = '<li class="page-item">';
							$config['prev_tag_close'] = '</li>';
						
						
							$config['next_link'] = 'Next';
							$config['next_tag_open'] = '<li class="page-item">';
							$config['next_tag_close'] = '</li>';
					
							$this->pagination->initialize($config);
					
							$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
					
							$data["links"] = $this->pagination->create_links();	
						$data['tabledata']=$this->$module_model->gets_data_admin_table_subs($limit,$page);	
						//echo '<pre>',print_r($data['tabledata'],1),'</pre>';
						//exit;
						$data['module_id']=$this->module_id;
						$data['controller']=$this->controller;
						$data['permissions']=$this->usergroupsmodel->get_module_privillages($this->permission);
						$this->load->vars($data);
					   	$this->load->view('admin/include/template');
					}
				else{
						$this->check_user_privillages($this->permission.'_add');
						$data=$this->input->post();
						//print_r($data);
						//echo '<pre>',print_r($data,1),'</pre>';
						//exit;
						//unset($data['id']);
						$post=array('name'=>@$data['name'],'type'=>@$data['type'],'value'=>@$data['value'],'mandatory'=>@$data['mandatory']?:0);
						
						if(@$this->input->post('id')==0){
								$post['is_enabled']=1;
								$post['created_date']=date('Y-m-d');
								$version=1;
							}	
						else{
								if($row=$this->$module_model->get_row($this->input->post('id'))){
										$version=$row->version+1;
									}
							}	
						$post['version']=$version;
						$id=$this->$module_model->save($post, $this->input->post('id'));	
						if(@$data['sub_name']){
								$subs_inserted=array();
								foreach(@$data['sub_name'] as $key=>$sub){
										if(@$sub!=""){
												$sub_insert=array('parent'=>$id,'name'=>$sub,'type'=>@$data['sub_type'][$key],'value'=>@$data['sub_value'][$key],'is_enabled'=>1,'version'=>$version);
												if(@$data['sub_id'][$key]>0){
														$sub_id=$this->$module_model->save($sub_insert,@$data['sub_id'][$key]);
													}
												else{
														$sub_id=$this->$module_model->save($sub_insert,0);
													}	
												$subs_inserted[]=$sub_id;	
												if(@$data['sub_sub_name'][$key]){
														$sub_sub_inserted=array();
														foreach(@$data['sub_sub_name'][$key] as $sub_key=>$sub_sub){
																if(@$sub_sub!=""){
																		$sub_sub_insert=array('parent'=>$sub_id,'name'=>$sub_sub,'type'=>@$data['sub_sub_type'][$key][$sub_key],'value'=>@$data['sub_sub_value'][$key][$sub_key],'is_enabled'=>1,'version'=>$version);
																		if(@$data['sub_sub_id'][$key][$sub_key]>0){
																				$sub_sub_id=$this->$module_model->save($sub_sub_insert,@$data['sub_sub_id'][$key][$sub_key]);
																			}
																		else{
																				$sub_sub_id=$this->$module_model->save($sub_sub_insert,0);
																			}	
																		$sub_sub_inserted[]=$sub_sub_id;	
																		if(@$data['sub_sub_sub_name'][$key][$sub_key]){
																				$sub_sub_sub_inserted=array();
																				foreach(@$data['sub_sub_sub_name'][$key][$sub_key] as $sub_sub_key=>$sub_sub_sub){
																						$sub_sub_sub_insert=array('parent'=>$sub_sub_id,'name'=>$sub_sub_sub,'type'=>@$data['sub_sub_sub_type'][$key][$sub_key][$sub_sub_key],'value'=>@$data['sub_sub_sub_value'][$key][$sub_key][$sub_sub_key],'is_enabled'=>1,'version'=>$version);
																						if(@$data['sub_sub_sub_id'][$key][$sub_key][$sub_sub_key]>0){
																								$sub_sub_sub_id=$this->$module_model->save($sub_sub_sub_insert,@$data['sub_sub_sub_id'][$key][$sub_key][$sub_sub_key]);
																							}
																						else{
																								$sub_sub_sub_id=$this->$module_model->save($sub_sub_sub_insert,0);
																							}	
																						$sub_sub_sub_inserted[]=$sub_sub_sub_id;	
																					}
																				$this->db->where('parent',$sub_sub_id)->select('id');
																				$mast_sub_sub_subs=$this->$module_model->gets_data()->result_array();	
																				if(count($mast_sub_sub_subs)>0){
																						$mast_sub_sub_subs_ids=array_column($mast_sub_sub_subs,'id');
																						$mast_sub_sub_subs_delete=array_diff($mast_sub_sub_subs_ids,$sub_sub_sub_inserted);
																						if(count($mast_sub_sub_subs_delete)>0){
																								$this->$module_model->delete($mast_sub_sub_subs_delete);
																							}
																					}	
																			}
																	}
															}
														$this->db->where('parent',$sub_id)->select('id');
														$mast_sub_subs=$this->$module_model->gets_data()->result_array();	
														if(count($mast_sub_subs)>0){
																$mast_sub_subs_ids=array_column($mast_sub_subs,'id');
																$mast_sub_subs_delete=array_diff($mast_sub_subs_ids,$sub_sub_inserted);
																if(count($mast_sub_subs_delete)>0){
																		$delete_ids=array();
																		$this->db->where_in('parent',$mast_sub_subs_delete);
																		$this->$module_model->delete_custom();	
																		$this->$module_model->delete($mast_sub_subs_delete);
																	}
																	
															}
													}
											}
									}
								$this->db->where('parent',$id)->select('id');
								$mast_subs=$this->$module_model->gets_data()->result_array();
								if(count($mast_subs)>0){
										$mast_subs_ids=array_column($mast_subs,'id');
										$mast_subs_delete=array_diff($mast_subs_ids,$subs_inserted);
										if(count($mast_subs_delete)>0){
												$this->db->where_in('parent',$mast_subs_delete)->select('id');
												$mast_subs_rows=$this->$module_model->gets_data()->result_array();
												$mast_subs_rows_ids=array_column($mast_subs_rows,'id');
												if(count($mast_subs_rows_ids)>0){
														$this->db->where_in('parent',$mast_subs_rows_ids);
														$this->$module_model->delete_custom();	
													}
												$this->db->where_in('parent',$mast_subs_delete);
												$this->$module_model->delete_custom();	
												$this->$module_model->delete($mast_subs_delete);
											}
										$delete_ids=array();

									}
							}
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));	
						/*if(@$data){
								$valid=TRUE;
								foreach($data as $key=>$value){
										if(!in_array($key,$this->valid_fields)){
												$valid=FALSE;
												break;
											}
									}
								if($valid){
										if(@$data['id']==0){
												$data['is_enabled']=1;
												$data['created_date']=date('Y-m-d');
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
							}*/
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

		
		public function excel_import() {
			$config['upload_path'] = APPPATH . '../uploads/extras_import/';
			$config['allowed_types'] = 'xls';
			$this->load->library('upload', $config);
			if ($_FILES['excel_file']['name'] == "") {
				$this->session->set_flashdata('error', "No file selected!");
				redirect($this->redirect);
			} elseif ((!$this->upload->do_upload('excel_file')) && ($_FILES['excel_file']['name'] != "")) {
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('error', "Upload Failed");
				redirect($this->redirect);
			} else {
				$name = $this->upload->data();
				$file = $name['file_name'];
				// ini_set('display_errors', 1);
				// ini_set('display_startup_errors', 1);
				// error_reporting(E_ALL);
				//	$path = FCPATH.'/uploads/import/' . $file;
				//$path = APPPATH . '../uploads/import/' . $file;
				$path = 'uploads/extras_import/' . $file;
	
				@$this->load->library('excel_reader');
				// Read the spreadsheet via a relative path to the document
				// for example $this->excel_reader->read('./uploads/file.xls');
				@$this->excel_reader->read($path);
				// print_r(@$this->excel_reader->sheets);
				$this->excel_import_set(@$this->excel_reader->sheets[0]['cells']);
				unlink($path);
				$this->session->set_flashdata('success', "Excel Uploaded Successfully");
				redirect($this->redirect);
			}
			exit;
		}
		public function excel_import_set($data = []) {

		
			
			try {
				for ($i = 2; $i <= count($data); $i++) {
					$this->insert_import_row($data[$i]);
				}
			} catch (Exception $e) {
				
			}
		
			// echo "<pre>";
			// print_r($data);
			// exit;
		}
		public function insert_import_row($row = []) {
			try {
				// Skip empty rows - adjust index to match 1-based keys
				$main = trim($row[1] ?? '');
				$sub = trim($row[2] ?? '');
		
				if (empty($main) || empty($sub)) {
					return;
				}
		
				// Columns (also 1-based)
				$extra_code = trim($row[3] ?? '');
				$type       = trim($row[4] ?? '');
				$value      = trim($row[5] ?? '');
		
			
		
				// Check and insert parent
				$parent = $this->db->get_where('extras', [
					'name'   => $main,
					'parent' => 0
				])->row();
		
				if (!$parent) {
					$this->db->insert('extras', [
						'name'         => $main,
						'parent'       => 0,
						'is_enabled'   => 1,
						'created_date' => date('Y-m-d'),
						'version'      => 1
					]);
					$parent_id = $this->db->insert_id();
				} else {
					$parent_id = $parent->id;
				}
		
				// Check and insert child
				$child = $this->db->get_where('extras', [
					'name'   => $sub,
					'parent' => $parent_id
				])->row();
		
				if (!$child) {
					$this->db->insert('extras', [
						'name'         => $sub,
						'parent'       => $parent_id,
						'extra_code'   => $extra_code,
						'type'         => $type,
						'value'        => $value,
						'is_enabled'   => 1,
						'created_date' => date('Y-m-d'),
						'version'      => 1
					]);
				}
			
		
			} catch (Exception $e) {
				log_message('error', 'Import Row Error: ' . $e->getMessage());
			}
		}
		
		public function matrix_import()
		{
			// Load the excel_reader library
			@$this->load->library('excel_reader');
		
			// Check if a file has been uploaded
			if (isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
				// Set upload configuration
				$config['upload_path']   = APPPATH . '../uploads/extras_import/matrix';
		        $config['allowed_types'] = 'xls|xlsx';
				$config['file_name']     = time() . '_' . $_FILES['upload_file']['name'];
		
				// Load and initialize the upload library
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
		
				// Attempt to upload the file
				if ($this->upload->do_upload('upload_file')) {
					// Get uploaded file data
					$fileData = $this->upload->data();
					$filePath = $fileData['full_path'];
		
					// Read the Excel file
					$this->excel_reader->read($filePath);
					$worksheet = $this->excel_reader->sheets[0];
					$cells = $worksheet['cells'];
		
				
				
					$extrasId = $this->input->post('extras_id');
		
					// Prepare data for price_band_price table
					$extrasPrices = array();
					for ($i = 2; $i <= $worksheet['numRows']; $i++) {
						$height = isset($cells[$i][1]) ? $cells[$i][1] : null;
						$width  = isset($cells[$i][2]) ? $cells[$i][2] : null;
						$price  = isset($cells[$i][3]) ? $cells[$i][3] : null;
		
						if ($height !== null && $width !== null && $price !== null) {
							$extrasPrices[] = array(
								'extras_id' => $extrasId, 
								'dim_drop'    => $height,
								'dim_width'     => $width,
								'price'         => $price
							);
						}
					}
		
					// Insert into price_band_price table
					if (!empty($extrasPrices)) {
						$this->db->insert_batch('extras_details_price', $extrasPrices);
					}
		
					// Return success response
					echo json_encode(array('status' => 'success', 'message' => 'Data imported successfully.'));
				} else {
					// Return error response
					echo json_encode(array('status' => 'error', 'message' => $this->upload->display_errors()));
				}
			} else {
				// Return error response
				echo json_encode(array('status' => 'error', 'message' => 'No file uploaded.'));
			}
		}

		public function get_children()
		{
			$module_model=$this->module_model;
			$parent_id = $this->input->post('parent_id');
		
			$children = $this->$module_model->get_children_by_parent($parent_id);
			echo json_encode($children);
		}
		
	}
?>