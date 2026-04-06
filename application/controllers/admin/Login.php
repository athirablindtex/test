<?php if( ! defined('BASEPATH')) { exit('No direct script allowed');}
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
class Login extends CI_Controller
	{
		public function __construct()
			{
			
				parent::__construct();
				//$this->load->config('common');
				$this->load->helper(array('captcha'));
				if($this->session->userdata('logged_in_admin'))
					{
						$this->session->set_flashdata('info','Already Logined!');
						redirect('admin/dashboard');
					}
				$this->load->helper(array('cookie'));
				$this->load->model('loginmodel'); 
			}
		public function index(){
				
	
				//cookie login
				$ck=get_cookie($this->config->item('site_cookie_admin'));
				
				if($ck!=''){
						$d1['tokken']=sha1($ck);
						$us=$this->loginmodel->checkfields($d1)->row();
					
						delete_cookie($this->config->item('site_cookie_admin'));
						if($us){
								$this->set_remember($us->id);
								$this->loginmodel->clear_ip();
								$ar1=array(
										'wrong_ip'=>FALSE,
									);
								$this->session->unset_userdata($ar1);
								$ar=array(
										'logged_in_admin'=>TRUE,
										'admin_id'=>$us->id,
										'admin_type'=>$us->user_group
									);
								$this->session->set_userdata($ar);
								//$this->session->set_flashdata('info','Login success!');
								redirect('admin/dashboard');
							}
					}
				
				$this->form_validation->set_rules('email','E-mail','trim|required');
				$this->form_validation->set_rules('password','Password','trim|required');
				  $this->session->unset_userdata('wrong_ip');
				
				if($this->session->userdata('wrong_ip')){
				
					$this->form_validation->set_rules('captcha','Captcha','required');
					$num=$this->loginmodel->check_ip();
					if($num>10){
							if(($this->input->post('email'))!=''){
									$this->loginmodel->record_ip();
									}
									$this->session->set_flashdata('info','Wrong acccount information!');
									redirect('admin/login');	
						}
					}
				else{
					$num=$this->loginmodel->check_ip();
					if($num>4){
							$ar=array(
											'wrong_ip'=>TRUE,
									);
									$this->session->set_userdata($ar);
							//$this->form_validation->set_rules('captcha','Captcha','required');
							
					}
				}
				if($this->form_validation->run()==FALSE)
					{
					
						if(($this->input->post('email'))!=''){
							//$this->session->set_flashdata('info','Please enter Username,Password and captcha!');
							$this->loginmodel->record_ip();
						}
						$data=array();
						if($this->session->userdata('wrong_ip')){
								$random_number = substr(number_format(time() * rand(),0,'',''),0,6);
			   					$vals = array(
             						'word' => $random_number,
             						'img_path' => './captcha/',
             						'img_url' => base_url().'captcha/',
             						'img_width' => 200,
             						'img_height' => 50,
             						'expiration' => 7200
            						);
      		   					$data['captcha'] = create_captcha($vals);
      		   					$this->session->set_userdata('captchaWord',$data['captcha']['word']);
							}
			   			$this->load->view('admin/login.php',$data);
					}
				
				else
					{
					
						//$captcha=$this->input->post('captcha');
						//$captchaword=$this->session->userdata('captchaWord');
						if($this->session->userdata('wrong_ip')){
								/*if($captcha!=$captchaword)
									{
										$this->loginmodel->record_ip();
										$this->session->set_flashdata('info','Wrong captcha!');
										redirect('admin/login');
									}*/
						}
								$data['email']=$this->input->post('email');
								$password1=$this->input->post('password');
								$password1=sha1($password1);
								$data['password']=crypt($this->config->item('site_salt'),$password1);
								// echo "<pre>";	
								// print_r($data).'input data'.'<br>';
							
							
								//$data['ar']=1;
								$us=$this->loginmodel->checkfields($data)->row();
								// print_r($us);
								
								
// 								if ($us) {
//     echo "User exists";
// } else {
//     echo "No user";
// }
// exit;
							
						

							
							
							
								if($us==TRUE){
								
									$this->loginmodel->clear_ip();
									$ar1=array(
											'wrong_ip'=>FALSE,
									);
									$this->session->unset_userdata($ar1);
								   $companies = $this->loginmodel->gets_company_by_user($us->id)->result_array();
									$ar=array(
											'logged_in_admin'=>TRUE,
											'admin_id'=>$us->id,
											'admin_type'=>$us->user_group,
											'admin_companies'=>array_column($companies, 'company_id')
											);
									$this->session->set_userdata($ar);
							    $this->session->set_userdata('session_start_time', time());
									//setting cookie
									if($this->input->post('remember')==1){
											$this->set_remember($us->id);
										}
									//$this->session->set_flashdata('info','Login success!');
									redirect('admin/dashboard');
								}
								else{
									$this->loginmodel->record_ip();
									$this->session->set_flashdata('info','Wrong acccount information!');
									redirect('admin/login');
								}
					}
			}
		function set_remember($id){
				$token=rand(0,10000);
				set_cookie($this->config->item('site_cookie_admin'),$token,'259200');
				$tk['tokken']=sha1($token);
				$this->loginmodel->save($tk,$id);
			}
		function test(){
				echo $ck=get_cookie($this->config->item('site_cookie_admin'));
			}
		}
?>