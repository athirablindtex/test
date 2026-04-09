<?php 
class Producttypemodel extends CI_Model{
	public $table_name = 'product_type';
	public $primary_key = 'id';
	public $table_name_extras = 'product_type_extras';
	public $table_name_sync = 'synch_record_product_type';
	public $table_name_sync_extras = 'synch_record_product_type_extras';
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
			$this->db->where_in('product_type',$id);
			$this->db->delete($this->table_name_extras);
            $this->db->where_in($this->primary_key, $id);
            if ($this->db->delete($this->table_name))
                return $this->db->affected_rows();
            return false;
		}
		
	///Extras
	function gets_data_extras() {
		return $query = $this->db->get($this->table_name_extras.' a');
    }
	function delete_extras_custom()
{
    return $this->db->update($this->table_name_extras, [
      
        'deleted_at' => date('Y-m-d H:i:s')
    ]);
}

	function delete_extras($id) {
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_extras))
			return $this->db->affected_rows();
		return false;
	}
	function save_extras($data, $id = 0) {
		$success = false;
        if ($id > 0) {
            $this->db->where($this->primary_key , $id);
            $success = $this->db->update($this->table_name_extras, $data);
			return $id;
        } else {
            if ($this->db->insert($this->table_name_extras, $data)) {
                $id = $this->db->insert_id();
                return $id;
            }
        }
	  }
	function save_extras_batch($data){
			$this->db->insert_batch($this->table_name_extras, $data);
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
		$this->db->select('id,name,parent,version,pdfUrl');
		$company_id = get_salesperson_company($user_id);
		 $company_id = get_company_id_or_null( $company_id)  ;
		  if(!$company_id){
				 $company_id = Null;
			 }
		$this->db->where('company_id', $company_id);   

		$dt=$this->gets_data()->result_array();
		$i=0;
		foreach($dt as $d){
				$this->db->where(array('row_id'=>@$d['id'],'user'=>$user_id));
				if($cur_row=$this->db->get($this->table_name_sync)->row_array()){
						$cur_items[]=$cur_row['id'];
						if($d['version']>$cur_row['version'] ){// ADDED true to bypass versioning now temprorly by Mujeeb on 22/12/2023
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
		$data['producttype']=$data_update;
		$data['producttype_delete']=array_column($cur_all,'row_id');
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
		
	////Acknowledge Sync data from local to server
	function acknowledge_sync_data_extras($user_id,$data=array()){
		if(@$data){
				$insert_data=array();
				$update_data=array();
				foreach($data as $dt){
						$this->db->where(array('row_id'=>@$dt['server_id'],'user'=>$user_id));
						if($cur_row=$this->db->get($this->table_name_sync_extras)->row_array()){
								$update_data[]=array('version'=>@$dt['version'],'last_synch_date'=>date('Y-m-d H:i:s'),'id'=>$cur_row['id']);
							}
						else{
								$insert_data[]=array('version'=>@$dt['version'],'last_synch_date'=>date('Y-m-d H:i:s'),'row_id'=>@$dt['server_id'],'user'=>$user_id);
							}	
					}
				if(@$update_data){
						$this->db->update_batch($this->table_name_sync_extras,$update_data,'id');
					}	
				if(@$insert_data){
						$this->db->insert_batch($this->table_name_sync_extras,$insert_data);
					}	
			}
	}
	///Send data from server to local
	function get_data_sync_extras($user_id){
			$data=array();
			$this->db->select('id,product_type,extra,mandatory,version');
			$dt=$this->gets_data_extras()->result_array();
			$i=0;
			foreach($dt as $d){
					$this->db->where(array('row_id'=>@$d['id'],'user'=>$user_id));
					if($cur_row=$this->db->get($this->table_name_sync_extras)->row_array()){
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


	function get_data_sync_all_extras($user_id){
		$data_update=array();
		$data_delete=array();
		$cur_items=array();
		
			$company_id = get_salesperson_company($user_id);
			$select_company_id =$company_id;

			 $company_id = get_company_id_or_null( $company_id)  ;
			 if(!$company_id){
				 $company_id = Null;
			 }
		    
				
			$last_synch_data = get_last_sync_date($user_id, 'product_type_extras');
		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}
		$this->db->select('id,product_type,extra,mandatory,version');
		if ($last_synch_date !== null) {
			$this->db->where('updated_at >', $last_synch_date);
		}
		$this->db->where('company_id', $company_id); 
		$this->db->where('deleted_at ', Null);
		$dt=$this->gets_data_extras()->result_array();

	
		        $this->db->select('margin_type, margin_value');
				$this->db->where('id', $select_company_id);
				$existing_margin = $this->db->get('admin_users')->row();
				$company_margin =$existing_margin->margin_value;
		$i=0;
		foreach($dt as $d){
                	$id= $d['extra'];
     
 
        $margin_value = Null;
         if($select_company_id !=27)
			{
		  // 1. Check margin_config for product_id
        $this->db->select('margin as value');
        $this->db->where(['extra' => $id, 'product_type' =>  $d['product_type']]);
        $result = $this->db->get('product_type_extras')->row();
        if ($result) {
            $margin_value = $result->value;
        }
		}

   
     
        // 4. Default to company margin
        if (is_null($margin_value)) {
            $margin_value = $company_margin;
        }
                              
                        $d["margin_percent"] =  $margin_value ; //margin product;

					
			
						$data_update[]=$d;
					
			}
	$this->db->select('id as row_id')
       
         ->where('deleted_at IS NOT NULL', null, false);

          $cur_all = $this->db->get('product_type_extras')->result_array();

		$data['producttypeextras']=$data_update;
		$data['producttypeextras_delete']=array_column($cur_all,'row_id');
		// print_r($data);
		// exit;
		return $data;	
	}	
	//Get Delete data
	function delete_sync_data_extras($user_id,$data=array()){
			if($data){
					$this->db->where(array('user'=>$user_id))
						->where_in('row_id',$data);
					$this->db->delete($this->table_name_sync_extras);
				}
		}	




	function insert_check_product_type($name="",$parent=0,$company_id){
			try{
					if($row=$this->check_product_name($name,$parent,$company_id)){
							return $row['id'];
						}
					else{
							$save_data=[
											'parent'=>$parent, 
											'name'=>$name, 
											'company_id'=>$company_id,
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


	function check_product_name($name="",$parent=0,$company_id){
			try{
					$this->db->where(array('name'=>@$name,'parent'=>$parent,'company_id'=>$company_id));
					return $this->db->get($this->table_name)->row_array();
				}
			catch(Exception $e){}
		}
		function get_child_data($parent)
{
    $this->db->select('id, name');
    $this->db->from($this->table_name); // Replace with actual table name
    $this->db->where([
        'parent' => $parent,
        'is_enabled' => 1
    ]);

    $query = $this->db->get();
    return $query->result(); // returns an array of objects
}


	
}