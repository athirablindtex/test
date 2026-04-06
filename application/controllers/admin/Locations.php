<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Locations extends MY_Controller
	{
		 var $module_id = 'id';
    	var $module_model='locationsmodel';
		var $view_folder='locations';
		var $redirect='admin/locations';
		var $module_caption='Locations';
		var $module_active='locations';
		var $module_active_sub='locations';
		var $controller='locations';
		public function __construct(){
				parent::__construct();
				if($this->session->userdata('admin_id') != 1){
						redirect('');
					}
				$this->load->model(array($this->module_model,'feedbackmodel'));
			}
		public function index(){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$data['page']='Manage '.$this->module_caption;
				$data['module']=$this->module_caption;
				$data['active']=$this->module_active;
				//$data['active_sub']=$this->module_active_sub;
				$data['active_sub']=$this->module_active.'_list';
				$data['content']='admin/'.$view_folder.'/list';
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$data['module_id']=$this->module_id;
				$data['controller']=$this->controller;
				$data['content']='admin/'.$view_folder.'/list';
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$this->load->vars($data);
			   	$this->load->view('admin/include/template');
			}
			public function rating(){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$data['page']='Ratings';
				$data['module']=$this->module_caption;
				$data['active']='ratings';
				//$data['active_sub']=$this->module_active_sub;
				$data['active_sub']=$this->module_active.'_list';
				$data['tabledata']=$this->$module_model->gets_all()->result();
				$data['module_id']=$this->module_id;
				$data['controller']=$this->controller;
				$data['content']='admin/'.$view_folder.'/rating';
				if($this->input->get('from')){
						$this->db->where('a.created_date >=',date('Y-m-d',strtotime($this->input->get('from'))));
					}
				if($this->input->get('to')){
						$this->db->where('a.created_date <=',date('Y-m-d H:i:s',strtotime($this->input->get('to').' 23:59:59')));
					}
				if($this->input->get('location')){
						$this->db->where('a.location',$this->input->get('location'));
					}	
				$this->db->select('a.*,l.name as location_name')
						->join('locations l','l.id=a.location','left');	
				$data['tabledata']=$this->feedbackmodel->gets_all()->result();
				$data['locations']=$this->$module_model->gets_all()->result();
				$this->load->vars($data);
			   	$this->load->view('admin/include/template');
			}	
		public function add($id=0){
				$module_model=$this->module_model;
				$view_folder=$this->view_folder;
				$this->form_validation->set_rules('name','Location Name','required|trim');
				$this->form_validation->set_rules('username', 'Username', 'callback_field_check[locations.username.id.'.$this->input->post('id').']');
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
						$this->load->vars($data);
			   			$this->load->view('admin/include/template');
					}
				else{
						$data=$this->input->post();
						unset($data['id']);
						unset($data['cropped_image']);
						unset($data['password']);
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
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
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