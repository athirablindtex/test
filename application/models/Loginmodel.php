<?php 
class Loginmodel extends CI_Model{
	 public $table_name = 'admin_users';
    public $primary_key = 'id';
	 function __construct() {
        parent::__construct();
	}
	function checkfields($data){
		$data['active']=1;
		$data['blocked']=0;
		$this->db->where($data);
		return $this->db->get($this->table_name);
	}
	  function save($data, $id = 0) {
		$success = false;
        if ($id > 0) {
            $this->db->where($this->primary_key , $id);
            $success = $this->db->update($this->table_name, $data);
        } else {
            if ($this->db->insert($this->table_name, $data)) {
                $id = $this->db->insert_id();
                $success = true;
            }
        }
	  }
	 function record_ip(){
	 		$upd['ip'] = $this->input->ip_address();
			$upd['username'] = $this->input->post('email');
			$upd['date_attempt']=date('Y-m-d H:i:s');
			$this->db->insert('login_attempts',$upd);
	 }
	 function check_ip(){
		 	$upd['ip'] = $this->input->ip_address();
	 		$this->db->where($upd);
			return count($this->db->get('login_attempts')->result());
	 	}
	function clear_ip(){
		$upd['ip'] = $this->input->ip_address();
		$this->db->where($upd);
		$this->db->delete('login_attempts');
	}
	function gets_company_by_user($user_id){
		
			$this->db->from('admin_companies');
			$this->db->where('user_id',$user_id);
			return $this->db->get();
		}
}