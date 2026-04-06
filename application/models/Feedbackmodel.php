<?php 
class Feedbackmodel extends CI_Model{
	 public $table_name = 'feedback';
    public $primary_key = 'id';
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

	  function save_batch($data) {
		
           $this->db->insert_batch($this->table_name, $data);
	  }

	function save_custom($data){
			$this->db->update($this->table_name, $data);
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
	//new
	function insert_category_image($post){
			$this->db->insert_batch('category_images',$post);
		}
	function get_category_image($id){
			$this->db->where('category',$id);
			//$this->db->order_by('quantity','desc');
			return $this->db->get('category_images');
		}
	function delete_category_image($id){
			$this->db->where_in('id', $id);
            $query = $this->db->get('category_images')->result();
            foreach (@$query as $rmv) {
				@unlink(APPPATH . '../uploads/category/' . @$rmv->image);
               }
			$this->db->where('id',$id);
			$this->db->delete('category_images');
		}
}