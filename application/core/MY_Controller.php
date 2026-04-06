<?php 
class MY_Controller extends CI_Controller {
	public $benchmark;
    public $session;
    public $input;
    public $config;
    public $load;
    public $output;
	public $utf8;
	public $uri;
	public $lang;
	public $router;
	public $security;
	public $db;
	public $form_validation;
	public $failover;
	public $commonfs;
	public $usergroupsmodel;
	public $configmodel ;
	public	 $usersmodel;
function __construct() {
	require_once APPPATH . '../vendor/autoload.php';
        parent::__construct();
		//$this->load->config('common');
    	if(!$this->session->userdata('logged_in_admin')){
				$this->session->set_flashdata('info','Login First!');
				redirect('admin/login');
			}
    }

	public function email_check($str,$fields)
        {
			$q=FALSE;
			$this->form_validation->set_message('email_check', 'The {field} field must be unique');
            sscanf($fields, '%[^.].%[^.].%[^.]', $table, $field, $id);
			$this->db->where('id !=',$id);
			$this->db->where('email',$str);
			$q=$this->db->get($table)->row();
			return $q ? FALSE : TRUE;  
        }
	public function field_check($str,$fields)
        {
			$q=FALSE;
			$this->form_validation->set_message('field_check', 'The {field} field must be unique');
            sscanf($fields, '%[^.].%[^.].%[^.].%[^.]', $table, $field,$primary_key, $id);
			$this->db->where($primary_key.' !=',$id);
			$this->db->where($field,$str);
			$q=$this->db->get($table)->row();
			return $q ? FALSE : TRUE;  
		}
	
	public function form_password_strength_check($pwd) {
			
			if (strlen($pwd) < 8) {
				$this->form_validation->set_message('form_password_strength_check', 'Password too short!');
				return FALSE;
			}
		
			if (!preg_match("#[0-9]+#", $pwd)) {
				$this->form_validation->set_message('form_password_strength_check', 'Password must include at least one number!');
				return FALSE;
			}
		
			if (!preg_match("#[a-zA-Z]+#", $pwd)) {
				$this->form_validation->set_message('form_password_strength_check', 'Password must include at least one letter!');
				return FALSE;
			}     

			if(!preg_match('/[^a-zA-Z\d]/', $pwd)){
					$this->form_validation->set_message('form_password_strength_check', 'Password must include at least Special Characters!');
					return FALSE;
				}
			
			return TRUE;	
		
			
		}	
	function check_user_privillages($module){
				$pr=$this->usergroupsmodel->get_privillages_user_ar();
				if(in_array($module,$pr)){
						return TRUE;
					}
				else{
						$data['url']=uri_string();
						$data['user']=$this->session->userdata('admin_id');
						$data['times']=date('Y-m-d H:i:s');
						$data['ip']=$this->input->ip_address();
						$this->usergroupsmodel->save_violation($data);
						//redirect('admin/dashboard/wrong_turn');
						redirect('');
					}
			}
	function return_user_privillages($module){
				$pr=$this->usergroupsmodel->get_privillages_user_ar();
				if(in_array($module,$pr)){
						return TRUE;
					}
				else{
						return FALSE;
					}
			}
	function mycore_hashing_user($pass=''){
				if($pass!=''){
						return crypt($this->config->item('user_salt'),hash('sha256', $pass));
					}
		}
}