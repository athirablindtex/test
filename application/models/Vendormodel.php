<?php 
class Vendormodel extends CI_Model{
	 public $table_name = 'vendor';
	public $primary_key = 'id';
	public $table_name_sync = 'synch_record_vendor';
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
		$data['vendor']=$data_update;
		$data['vendor_delete']=array_column($cur_all,'row_id');
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



		function insert_check_data($name=""){
			try{
					if($row=$this->check_data_name($name)){
							return $row['id'];
						}
					else{
							$save_data=[
											'name'=>$name, 
											'is_enabled'=>1, 
											'created_date'=>date('Y-m-d'), 
											'version'=>1
										];
							return $this->save($save_data);
						}
				}
			catch(Exception $e){}
			return 0;
		}


	function check_data_name($name=""){
			try{
					$this->db->where(array('name'=>@$name));
					return $this->db->get($this->table_name)->row_array();
				}
			catch(Exception $e){}
		}
}