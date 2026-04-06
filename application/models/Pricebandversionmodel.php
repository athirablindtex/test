<?php 
class Pricebandversionmodel extends CI_Model{
	 public $table_name = 'price_band_version';
	public $primary_key = 'id';
	public $table_name_sync = 'synch_record_price_band_version';
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

	////Acknowledge Sync data from local to server
	function acknowledge_sync_data($user_id,$data=array()){
		if(@$data){
				$insert_data=array();
				$update_data=array();
				foreach($data as $dt){
						$this->db->where(array('row_id'=>@$dt['server_id'],'user'=>$user_id));
						if($cur_row=$this->db->get($this->table_name_sync)->row_array()){
								$update_data[]=array('version'=>@$dt['version'],'last_synch_date'=>date('Y-m-d H:i:s'),'id'=>$cur_row['id']);
							}
						else{
								$insert_data[]=array('version'=>@$dt['version'],'last_synch_date'=>date('Y-m-d H:i:s'),'row_id'=>@$dt['server_id'],'user'=>$user_id);
							}	
					}
				if(@$update_data){
						$this->db->update_batch($this->table_name_sync,$update_data,'id');
					}	
				if(@$insert_data){
						$this->db->insert_batch($this->table_name_sync,$insert_data);
					}	
			}
	}
///Send data from server to local
function get_data_sync($user_id){
		$data=array();
		$this->db->select('id,name,version');
		$dt=$this->gets_data()->result_array();
		$i=0;
		foreach($dt as $d){
				$this->db->where(array('row_id'=>@$d['id'],'user'=>$user_id));
				if($cur_row=$this->db->get($this->table_name_sync)->row_array()){
						if($d['version']>$cur_row['version']){
								$data[]=$d;
							}
					}
				else{
						$data[]=$d;
					}	
			}
		return $data;	
	}	

///Send data from server to local
function get_data_sync_all($user_id){
	$data_update=array();
	$data_delete=array();
	$cur_items=array();
	$this->db->select('id,name,version');
	$dt=$this->gets_data()->result_array();
	$i=0;
	foreach($dt as $d){
			$this->db->where(array('row_id'=>@$d['id'],'user'=>$user_id));
			if($cur_row=$this->db->get($this->table_name_sync)->row_array()){
					$cur_items[]=$cur_row['id'];
					if($d['version']>$cur_row['version'] || true){// ADDED true to bypass versioning now temprorly by Mujeeb on 22/12/2023
							$data_update[]=$d;
						}
				}
			else{
					$data_update[]=$d;
				}	
		}
	$this->db->where(array('user'=>$user_id))
			->select('row_id');
	if($cur_items){
				$this->db->where_not_in('id',$cur_items);
			}		
	$cur_all=$this->db->get($this->table_name_sync)->result_array();
	$data['pricebandversion']=$data_update;
	$data['pricebandversion_delete']=array_column($cur_all,'row_id');
	return $data;	
}		


//Get Delete data
function delete_sync_data($user_id,$data=array()){
		if($data){
				$this->db->where(array('user'=>$user_id))
					->where_in('row_id',$data);
				$this->db->delete($this->table_name_sync);
			}
	}	
}