<?php 
class Usergroupsmodel extends CI_Model{
	 public $table_name = 'users_groups';
    public $primary_key = 'id';
	 function __construct() {
        parent::__construct();
		//$this->load->database();	
    }
	 function gets_all() {
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name);
    }
	
	function gets_data() {
        return $query = $this->db->get($this->table_name);
    }

    function gets_all_active() {
        $this->db->order_by('order1', 'asc');
        $this->db->where('is_enabled', 1);
        return $query = $this->db->get($this->table_name);
    }
	  function get_row($id = 0) {
		$data = $this->db->get_where($this->table_name, array($this->primary_key => $id))->row();
        return $data;
    }
	 
	  function save($data, $id = 0) {
		$success = false;
        if ($id > 0) {
            $this->db->where($this->primary_key , $id);
            $success = $this->db->update($this->table_name, $data);
			return $id;
        } else {
            if ($this->db->insert($this->table_name, $data)) {
                $id = $this->db->insert_id();
                return $id;
            }
        }
	  }
	
	function delete($id) {
			$this->db->where('user',$id);
			$this->db->delete('user_modules');
			$this->db->where_in($this->primary_key, $id);
            if ($this->db->delete($this->table_name))
                return $this->db->affected_rows();
            return false;
    	}
	function get_privillages_user(){
			$this->db->select('module');
			$this->db->where('user',$this->session->userdata('admin_type'));
			return $this->db->get('user_modules')->result_array();
		}
	function get_privillages_user_ar(){
			$this->db->select('module');
			$this->db->where('user',$this->session->userdata('admin_type'));
			$re=$this->db->get('user_modules')->result_array();
			$ar=array_column($re,'module');
			return $ar;
		}		
	function get_privillages_user_edit(){
			return $this->db->get('user_modules');
		}
	function get_modules(){
			return $this->db->get('modules');
		}
	function delete_userroles(){
			$this->db->delete('user_modules');
		}
	function insert_permissions($post){
			$this->db->insert_batch('user_modules',$post);
		}
	function save_violation($data){
			$this->db->insert('permission_violations',$data);
		}
	function get_violations(){
			$this->db->select('user.*,permission_violations.*');
			$this->db->order_by('permission_violations.id','DESC');
			$this->db->join('user','user.id=permission_violations.user','left');
			return $this->db->get('permission_violations');
		}
	function delete_violation_log($id){
			$this->db->where('id',$id)->delete('permission_violations');
		}

	function get_module_privillages($module){
			$data=array('add'=>0,'delete'=>0);
			$this->db->select('module');
			$this->db->where('user',$this->session->userdata('admin_type'));
			$re=$this->db->get('user_modules')->result_array();
			$ar=array_column($re,'module');
			if(in_array($module.'_add',$ar)){
					$data['add']=1;
				}
			if(in_array($module.'_delete',$ar)){
					$data['delete']=1;
				}	
			return $data;	
		}		
		
	
}