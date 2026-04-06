<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	var $token_valid_key='';
	var $token_valid_value="cf#rX4@*aqguhm{dP=PB287zW<(P7,6(HG{)s3>2>e2RQhd;+Zg4K~t@6@zxd%4{b`jfcR=g3!<78UBFf&U;AFGQg{.@pp!#[JP~5Lv5Yg{JzSfaAj2-}a:&8SYS~G]L3*,R2X**y:%=3@}s4dc+wqjCZ<<p8xD7pKtX@^jR@zce_#<JW}c?!>*>&.u:TnFQ-ct{K%Q4Zg)x#z%Ed*>YfKT<<]7t&}p~Fj>;uXTB!tB,cX+HWs-]@gEndT-m#B+U";
	var $user_id=0;
	public function __construct(){
			parent::__construct();
		
			$this->load->model(array('salespersonmodel','tokkenmodel','usersmodel'));
			$this->token_valid_key=$this->config->item('token_valid');
			$num=$this->tokkenmodel->check_ip();
			$headers = apache_request_headers();
			$tokken_value=@$headers[$this->token_valid_key]?:@$headers[ucfirst($this->token_valid_key)];
			if($this->token_valid_value!=$tokken_value){
					$data['status']=false;
					$data['message']='Invalid Token';
					echo json_encode($data);
					exit;
				}
		}


	public function check_server(){
			$data['status']=true;
			$data['message']="";
			echo json_encode($data);
		}	
	
	function mycore_hashing_user($pass=''){
				if($pass!=''){
						$password1=$pass;
						$password1=sha1($password1);
						return $data['password']=crypt($this->config->item('site_salt'),$password1);
					}
			}
	
		public function login(){
			$data['status']=true;
			$data['action_status']=false;
                       //echo "phone =".$this->input->post('phone');
                        //echo "password =".$this->input->post('password');
			$this->form_validation->set_rules('phone','Phone','required|trim');
			
			$this->form_validation->set_rules('password','Password','required|trim');
			//$this->form_validation->set_rules('push_token','Password','required|trim');
			if($this->form_validation->run()==FALSE){
					//$data['status']=0;
					$data['message']='Login Error : ';
				}
			else{
					$upd=array('phone'=>$this->input->post('phone'),'password'=>$this->mycore_hashing_user($this->input->post('password')),'active'=>1);
					$this->db->select('name,id,phone,company')->where($upd);	
					if($dta['user']=$this->salespersonmodel->gets_data()->row_array()){
							$data['user_type']='employee';
						}
					else{
							$data['message']='Invalid Login';
						}
				}
			if(@$dta['user']){
                                        
					$this->user_id=$dta['user']['id'];
				$this->remove_user_existing_data();
					$data['user_name']=@$dta['user']["name"];
					$data['user_phone']=@$dta['user']["phone"];
					$data['user_id']=@$dta['user']["id"];
					$du['user_id']=$dta['user']['id'];
					$company_id=$dta['user']['company'];
					$company=$this->usersmodel->get_details_id($company_id);
						
					// $data['companyName']=$company->name;
			
					$du['login_date']=date('Y-m-d H:i:s');
					$du['access_token_expire'] = strtotime(date('Y-m-d H:i:s', strtotime("+180 days")));
					$du['access_tokken'] = $this->generateRandomString();
					$pushToken=$this->input->post('push_token');
                    $du['push_token'] = 	$pushToken;
						if (!empty($pushToken)) {


						$this->db->where('push_token', $pushToken)
						->delete('login_tokkens');
						}
					
					$this->tokkenmodel->save($du);
					$data['access_tokken']=$du['access_tokken'];
					$data['message']='';
					$data['action_status']=true;
				}
			else{
					$data['user_name']="";
					$data['user_phone']="";
					$data['access_tokken']="";
				}	
			echo json_encode($data);
		}


	function remove_user_existing_data(){
			if($this->user_id>0){
					//Deleting Existing Tokkens
					$this->tokkenmodel->delete_user_tokkens($this->user_id);
					///Delete Existing Data
					$this->tokkenmodel->delete_user_datas($this->user_id);
				}
		}	

	function generateRandomString($length = 128) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				$this->db->where('access_tokken',$randomString);
				if($this->tokkenmodel->gets_data()->row()){
						$this->generateRandomString();
					}
				else{
						return $randomString;
					}
			  }
			  
}
