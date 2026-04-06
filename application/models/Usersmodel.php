<?php 
class Usersmodel extends CI_Model{
	 public $table_name = 'admin_users';
    public $primary_key = 'id';
	 function __construct() {
        parent::__construct();
		//$this->load->database();	
    }
	 function gets_all() {
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name.' a');
	 }
	  function gets_company_all() {
 $this->db->where('a.company_code !=', '');
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name.' a');
	 }


	 function gets_all_details() {
		$this->db->select('a.*,b.name as group');
	  	$this->db->join('users_groups b','b.id=a.user_group','left');
		return $query = $this->db->get($this->table_name.' a');
	 }
	public function delete_user_companies($user_id)
{
    return $this->db->where('user_id', $user_id)
                    ->delete('admin_companies');
}


    function gets_all_active() {
        $this->db->order_by('order1', 'asc');
        $this->db->where('is_enabled', 1);
        return $query = $this->db->get($this->table_name);
    }
	function gets_data(){
			 return $query = $this->db->get($this->table_name);
		}
	  function get_row($id = 0) {
		$data = $this->db->get_where($this->table_name.' a', array('a.'.$this->primary_key => $id))->row();
        return $data;
    }
	
	 function get_row_slug($id) {
		$data = $this->db->get_where($this->table_name, array('slug' => $id))->row();
        return $data;
    }
	function get_details_id($id) {
		$data = $this->db->get_where($this->table_name, array('id' => $id))->row();
        return $data;
    }
	

	 function get_row_details($id) {
		$this->db->select('a.*,b.name as group');
	  	$this->db->join('users_groups b','b.id=a.user_group','left');
		$this->db->where('a.'.$this->primary_key,$id);
        return $query = $this->db->get($this->table_name.' a')->row();
    }
	function get_parent(){
		$this->db->where('parent',0);
		$this->db->where('is_enabled',1);
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name);
	}
	function get_sub($id){
		$this->db->where('parent',$id);
		$this->db->where('is_enabled',1);
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name);
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

	 function gets_all_except($id) {
	 	$this->db->where($this->primary_key.' !=',$id);
		return $query = $this->db->get($this->table_name);
    }
	function delete($id) {
			$this->db->where_in($this->primary_key, $id);
            $query = $this->db->get($this->table_name)->result();
            foreach (@$query as $rmv) {
				@unlink(APPPATH . '../uploads/users/' . @$rmv->image);
               }
            $this->db->where_in($this->primary_key, $id);
          $this->db->update($this->table_name, [
        'deleted_at' => date('Y-m-d H:i:s')
    ]);
                return $this->db->affected_rows();
            return false;
    	}
	function check_sub($id){
			$this->db->where('parent',$id);
			$q=$this->db->get('category')->result();
			if($q==true){
				return $q;
				}
			else{
				$this->db->where('category',$id);
				$q1=$this->db->get('product')->result();
				if($q1==TRUE){
					return $q1;
				}
				else{
					return FALSE;
				}
			}
		}
	function gets_all_active_page() {
        $this->db->order_by('order1', 'asc');
		$this->db->where('parent',0);
        $this->db->where('is_enabled', 1);
        return $query = $this->db->get($this->table_name);
    }
	 function select_all($limit, $start) {
       	$this->db->order_by('order1', 'asc');
		$this->db->where('parent',0);
        $this->db->where('is_enabled', 1);
		$this->db->limit($limit, $start);
        return $query = $this->db->get($this->table_name);
    }
	function data_count(){
		$this->db->where('parent',0);
		$this->db->where('is_enabled', 1);
        return count($this->db->get($this->table_name)->result());
		}
	function get_higher_order(){
			$this->db->select('MAX(order1) as priority');
			$data = $this->db->get($this->table_name)->row();
        return $data;
		}
	function sort($or,$id){
			$primary_key=$this->primary_key;
			$this->db->where($this->primary_key.' !=', $id);
			$this->db->where('order1 >=',$or);
			$q1=$this->db->get($this->table_name)->result();
			foreach($q1 as $q){
				$or++;
				$upd['order1']=$or;
				$this->db->where($this->primary_key,$q->$primary_key);
				$this->db->update($this->table_name,$upd);
				$this->db->where($this->primary_key.' !=',$q->$primary_key);
				$this->db->where('order1',$or);
				$r=$this->db->get($this->table_name)->row();
				if(!$r){
						break;	
					}
			}
		}

	function get_total_companies_count(){
		$session = $this->session->userdata();
		    $this->db->where('active', 1);
     if (isset($session['admin_type']) && $session['admin_type'] == 1) {
	
        $this->db->where('user_group', $this->config->item('company_id'));

		
	 return $this->usersmodel->gets_all()->num_rows();


	

	

		} else {
        $adminCompanies = $session['admin_companies'] ?? [];
		$this->db->where_in('id', $adminCompanies);
		}

      $q = $this->db->get($this->table_name); 
		return $q->num_rows();
		}	
}