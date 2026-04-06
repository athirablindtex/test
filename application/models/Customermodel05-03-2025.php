<?php 
class Customermodel extends CI_Model{
	 public $table_name = 'customer';
	 public $table_name_sync = 'synch_record_customer';
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

	  function update_batch($data) {
		
		$this->db->update_batch($this->table_name, $data,'id');
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
	function gets_customers_api_sync($id) {
			$sync_data=array();
			$result=$this->db->get($this->table_name.' a')->result_array();
			$i=0;
			foreach($result as $re){
					$this->db->where(array('user'=>$id,'row_id'=>$re['id']));
					if($cur_row=$this->db->get($this->table_name_sync)->row_array()){
							if(@$re['version']>$cur_row['version']){
									$re["lastAppointment"]="";
									$sync_data[]=$re;
								}
						}
					else{
							$re["lastAppointment"]="";
							$sync_data[]=$re;
						}	
				}
			return $sync_data;
		}
	function sync_data_server($user_id,$data=array()){
			if(@$data){
					$insert_data=array();
					$update_data=array();
					foreach($data as $dt){
							$cur_item=array();
							$this->db->where(array('id'=>@$dt['server_id']));
							$cur_row=$this->db->get($this->table_name)->row_array();
							if(!$cur_row){
									$this->db->where(array('email'=>@$dt['email']));
									$cur_row=$this->db->get($this->table_name)->row_array();
								}
							$ins_row=array('name'=>@$dt['name'],'email'=>@$dt['email'],'phone'=>@$dt['phone'],'address'=>@$dt['address'],'remarks'=>@$dt['remarks']);	
							if(@$cur_row){
									$ins_row['id']=$cur_row->id;
									$update_data[]=$ins_row;
								}
							else{
									$insert_data[]=$ins_row;
								}		
							if(@$update_data){
									$this->db->update_batch($this->table_name,$update_data,'id');
								}	
							if(@$insert_data){
									$this->db->insert_batch($this->table_name,$insert_data);
								}
						}
				}
		}	
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
	function sync_data($user_id,$data=array()){
			$ack_data=array();
			if(@$data){
					$insert_data=array();
					$update_data=array();
					foreach($data as $d){
							$row=array(
										'name'=>@$d['name']?:"",
										'email'=>@$d['email']?:"",
										'phone'=>@$d['phone']?:"",
										'address'=>@$d['address']?:"",
										'remarks'=>@$d['remarks']?:"",
										'sales_person'=>$user_id?:"",
										'reference'=>@$d['reference']?:"",
										'trn_no'=>@$d['trn_no']?:""
									);
							$sid=0;
							$this->db->where(array('phone'=>@$d['phone'],'sales_person'=>$user_id));
							if($cur_row=$this->gets_data()->row()){
									$row['id']=$cur_row->id;
									$update_data[]=$row;
									//$sid=$this->save($row,$cur_row->id);
								}
							else{
									$this->db->where(array('phone'=>@$d['phone'],'sales_person'=>$user_id));
									if($cur_row=$this->gets_data()->row()){
											$row['id']=$cur_row->id;
											$update_data[]=$row;
											//$sid=$this->save($row,$cur_row->id);
										}
									else{
											$row['created_date']=date('Y-m-d');
											$insert_data[]=$row;
											//$sid=$this->save($row,$cur_row->id);
										}	
								}	
							/*if($sid>0 && $d['id']>0){
									$ack_data[]=array('server_id'=>$sid,'id'=>$d['id']);
								}*/
						}
					if(@$insert_data){
							$this->save_batch($insert_data);
						}
					if(@$update_data){
							$this->update_batch($update_data);
						}		
				}
			return $ack_data;
		}	

	function api_customer_insert_row($user_id,$d=array()){
			if(@$d){
					$row=array('name'=>@$d['name'],'email'=>@$d['email'],'phone'=>@$d['phone'],'address'=>@$d['address'],'remarks'=>@$d['remarks'],'sales_person'=>$user_id);
					$this->db->where(array('phone'=>@$d['phone'],'sales_person'=>$user_id));
					if($cur_row=$this->gets_data()->row()){
							$id=$this->save($row,$cur_row->id);
						}
					else{
							$row['created_date']=date('Y-m-d');
							$id=$this->save($row);
						}	
					return $id;	
				}
			return 0;	
		}	

	function api_get_init_sync_data($user_id=0){
			$this->db->where('sales_person',$user_id)
					->select('id, name, email, phone, address,remarks,created_date as created_time,trn_no,reference');
			$data=$this->gets_data()->result_array();
			$i=0;
			foreach($data as $d){
					$data[$i]['created_time']=strtotime($d['created_time'])*1000;
					$i++;
				}
			return $data;
		}	
}