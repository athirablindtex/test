<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
class Settings extends MY_Controller
	{
		public function __construct()
			{
				parent::__construct();
				$this->load->model(array('loginmodel','usersmodel')); 
			}
		public function index(){
				$this->form_validation->set_rules('cpass','Current Password','trim|required');
				$this->form_validation->set_rules('npass1','New Password','trim|required');
				$this->form_validation->set_rules('npass2','Confirm New Password','trim|required');
				if($this->form_validation->run()==FALSE)
					{
						if(($this->input->post())){
								$this->session->set_flashdata('info','Enter Correct Information');
							}
						$data['page']='Change Password';
						$data['content']='admin/profile/change_password';
						$this->load->vars($data);
			   			$this->load->view('admin/include/template');
					}
				
				else
					{
						$password1=$this->input->post('cpass');
						$password1=sha1($password1);
						$cpass['password']=crypt($this->config->item('site_salt'),$password1);
						$password2=$this->input->post('npass1');
						$password2=sha1($password2);
						$npass['password']=crypt($this->config->item('site_salt'),$password2);
						$cpass['id']=$this->session->userdata('admin_id');
						$us=$this->loginmodel->checkfields($cpass)->row();
						if($us==TRUE){
							$this->loginmodel->save($npass,$this->session->userdata('admin_id'));
							$this->session->set_flashdata('success','Change password success!');
							redirect('admin/settings');
						}
						else{
							$this->session->set_flashdata('info','Wrong Information!');
							redirect('admin/settings');
						}
						
					}
			}
		public function profile(){
				$module_model='usersmodel';
				$this->form_validation->set_rules('name','users Name','required|trim');
				$this->form_validation->set_rules('email', 'Email', 'callback_email_check[admin_users.email.'.$this->input->post('id').']');
				if($this->form_validation->run()==FALSE){
						$data['page']='profile Settings';
						$data['module']='profile Settings';
						$data['content']='admin/profile/profile';
						$data['module_id']='id';
						$data['controller']='settings';
						$data['res']=$this->$module_model->get_row($this->session->userdata('admin_id'));
						$this->load->vars($data);
						$this->load->view('admin/include/template');
					}
				else{
						$data=$this->input->post();
						unset($data['id']);
						unset($data['cropped_image']);
						@$name = $this->upload();
						if(@$name!=''){
								$data['image']=@$name;
							}
						$id=$this->$module_model->save($data, $this->input->post('id'));
						$this->session->set_flashdata('success',$this->lang->line('common_update_success'));
						redirect('admin/settings/profile');
					}
			}
		public function logout(){
				$this->load->helper(array('cookie'));
				$id=$this->session->userdata('admin_id');
				$log=array(
								'logged_in_admin'=>FALSE,
								'admin_id'=>'',
								'admin_type'=>''
							);
				$this->session->unset_userdata($log);
				$this->session->sess_destroy();
				delete_cookie($this->config->item('site_cookie_admin'));
				//set new cookie
				$token=rand(0,10000);
				set_cookie($this->config->item('site_cookie_admin'),$token,'259200');
				$tk['tokken']=sha1(rand(0,10000));
				$this->loginmodel->save($tk,$id);
				//
				$this->session->set_flashdata('success','Logout success!');
				redirect('admin/login');
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

	}
?>