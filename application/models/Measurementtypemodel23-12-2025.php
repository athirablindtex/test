<?php 
class Measurementtypemodel extends CI_Model{
	public $table_name = 'measuring_type';
	public $primary_key = 'id';
	public $table_name_sync = 'sync_record_measuring_type';
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
	function delete_extras_custom() {
		$this->db->delete($this->table_name_extras);
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


// 	function get_data_sync_all($user_id)
// {
//     $data_update = array();
//     $cur_items   = array();

//     // Fetch data
//     $this->db->select('id,name,version,product_type');
//     $dt = $this->gets_data()->result_array();

//     foreach ($dt as $d) {

//         // Split product_type into array → [2,3,5]
//         $types = explode(',', $d['product_type']);

//         // Create separate items for each product_type
//         foreach ($types as $t) {

//             $item = array(
//                 "id"           => $d['id'],
//                 "name"         => $d['name'],
//                 "version"      => $d['version'],
//                 "product_type" => (int)$t
//             );

//             // Check sync only by row_id + user
//             $this->db->where(array(
//                 'row_id' => $d['id'],
//                 'user'   => $user_id
//             ));

//             $cur_row = $this->db->get($this->table_name_sync)->row_array();

//             if ($cur_row) {
//                 $cur_items[] = $cur_row['id'];

//                 // Version comparison (same as your logic)
//                 if ($d['version'] > $cur_row['version']) {
//                     $data_update[] = $item;
//                 }

//             } else {
//                 // New item → needs update
//                 $data_update[] = $item;
//             }
//         }
//     }

//     // Find deleted items
//     $this->db->where(array('user' => $user_id))
//              ->select('row_id');

//     if ($cur_items) {
//         $this->db->where_not_in('id', $cur_items);
//     }

//     $cur_all = $this->db->get($this->table_name_sync)->result_array();

//     // Final response
//     $data['measuringtype']         = $data_update;
//     $data['measuringtype_delete']  = array_column($cur_all, 'row_id');

//     return $data;
// }

function get_data_sync_all($user_id)
{
    $data_update = [];
    $cur_items   = [];

    // 1) Get last sync date
    $last_synch_data = get_last_sync_date($user_id, 'measuring_type');
    $last_synch_date = $last_synch_data && !empty($last_synch_data->last_synch_date)
                       ? $last_synch_data->last_synch_date
                       : null;

    // 2) Fetch updated/new rows
    $this->db->select('id, name, version, product_type, updated_at, deleted_at');

    if ($last_synch_date !== null) {
        $this->db->where('updated_at >', $last_synch_date);
    }

    $this->db->where('deleted_at', NULL);
    $rows = $this->gets_data()->result_array();

    // Split product types (like older logic: 2,3,5)
    foreach ($rows as $row) {

        $types = explode(",", $row['product_type']);

        foreach ($types as $t) {

            $item = [
                "id"           => $row['id'],
                "name"         => $row['name'],
                "version"      => $row['version'],
                "product_type" => (int)$t
            ];

            // Check existing sync record
            $this->db->where([
                'row_id' => $row['id'],
                'user'   => $user_id
            ]);

            $cur_row = $this->db->get($this->table_name_sync)->row_array();

            if ($cur_row) {
                $cur_items[] = $cur_row['id'];

                // Compare version
                if ($row['version'] > $cur_row['version']) {
                    $data_update[] = $item;
                }

            } else {
                // New record → send to device
                $data_update[] = $item;
            }
        }
    }

    // 3) Deleted rows list
    $this->db->select('id AS row_id');
    $this->db->where('deleted_at IS NOT NULL', null, false);
    $deleted = $this->gets_data()->result_array();

    // 4) Final response
    return [
        'measuringtype'        => $data_update,
        'measuringtype_delete' => $deleted ? array_column($deleted, 'row_id') : []
    ];
}




	//Get Delete data
	function delete_sync_data($user_id,$data=array()){
			if($data){
					$this->db->where(array('user'=>$user_id))
						->where_in('row_id',$data);
					$this->db->delete($this->table_name_sync);
				}
		}
		
	




	function insert_check_product_type($name="",$parent=0){
			try{
					if($row=$this->check_product_name($name,$parent)){
							return $row['id'];
						}
					else{
							$save_data=[
											'parent'=>$parent, 
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


	function check_product_name($name="",$parent=0){
			try{
					$this->db->where(array('name'=>@$name,'parent'=>$parent));
					return $this->db->get($this->table_name)->row_array();
				}
			catch(Exception $e){}
		}

	
}