<?php 
class Tokkenmodel extends CI_Model{
	 public $table_name = 'login_tokkens';
    public $primary_key = 'id';
	public $table_requests = 'request_failed_attempts';
	 function __construct() {
        parent::__construct();
		//$this->load->database();	
    }
	 function gets_all() {
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name.' a');
    }
	 function gets_data() {
		return $query = $this->db->get($this->table_name.' a');
    }
	
	function get_parent(){
		$this->db->where('parent',0);
		$this->db->where('is_enabled',1);
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name);
	}
	
	 function gets_all_active() {
		 $this->db->where('is_enabled','1');
		$this->db->order_by($this->primary_key, 'desc');
        return $query = $this->db->get($this->table_name.' a');
    }
   
	 public function get_row($id = 0) {
                $this->db->select("*");
		 $this->db->where('user_id' , $id);
                $data = $this->db->get($this->table_name);
        
        return $data;
    }
	public function get_token($id = 0) {
		$this->db->select("*");
		$this->db->where('user_id', $id);
		$query = $this->db->get($this->table_name);
		
		return $query->row(); // Returns a single row (or use `row_array()` for an array)
	}
	function get_all_users() {
		$this->db->select("*");
		$this->db->where('user_id' , $id);
		return $this->db->get($this->table_name)->result_array();  // Fetch all users as associative arrays
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
			$this->db->where_in($this->primary_key, $id);
            $query = $this->db->get($this->table_name)->result();
            foreach (@$query as $rmv) {
				@unlink(APPPATH . '../uploads/category/' . @$rmv->image);
               }
            $this->db->where_in($this->primary_key, $id);
            if ($this->db->delete($this->table_name))
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
	 function gets_all_except($id) {
	 	$this->db->where($this->primary_key.' !=',$id);
		return $query = $this->db->get($this->table_name);
    }
	function delete_custom(){
			return $this->db->delete($this->table_name);
		}
	function delete_user_tokkens($id){
			$this->db->where('user_id',$id);
			return $this->db->delete($this->table_name);
		}	
		
	function delete_user_datas($user_id=0){
			if($user_id>0){
					$tables=array('synch_record_vendor','synch_record_salesperson','synch_record_room_type','synch_record_product_type','synch_record_product_type_extras','synch_record_product','synch_record_price_band_version','synch_record_price_band','synch_record_price_band_price','synch_record_extras','synch_record_customer','sync_record_measuring_type','synch_record_extras_matrix_price');
					foreach($tables as $t){	
							$this->db->where('user',$user_id);
							$this->db->delete($t);
						}
				}			
		}	
	///ip
	function record_ip(){
	 		$upd['ip'] = $this->input->ip_address();
			$upd['date_attempt']=date('Y-m-d H:i:s');
			$this->db->insert($this->table_requests,$upd);
	 }
	 function check_ip(){
		 	$upd['ip'] = $this->input->ip_address();
	 		$this->db->where($upd);
			return count($this->db->get($this->table_requests)->result());
	 	}
	function clear_ip(){
		$upd['ip'] = $this->input->ip_address();
		$this->db->where($upd);
		$this->db->delete($this->table_requests);
	}
}