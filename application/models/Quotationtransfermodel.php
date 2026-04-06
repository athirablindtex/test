<?php 
class Quotationtransfermodel extends CI_Model{
	 public $table_name = 'quotation_transfer';
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
            if ($this->db->delete($this->table_name))
                return $this->db->affected_rows();
            return false;
		}
	
		
	function get_quotation_transfers_admin($company_ids = [])
{
    if ($this->input->get('from')) {
        $this->db->where('a.created_date >=', date('Y-m-d', strtotime($this->input->get('from'))));
    } else {
        $default_expiry_from_date = date('Y-m-d', strtotime('-30 day'));
        $this->db->where('a.created_date >=', $default_expiry_from_date);
    }

    if ($this->input->get('to')) {
        $this->db->where('a.created_date <=', date('Y-m-d', strtotime($this->input->get('to'))));
    }

    /* Company filter from dropdown */
    if ($this->input->get('company') > 0) {
        $this->db->where('s.company', $this->input->get('company'));
    }

    /* Company filter from logged user */
    if (!empty($company_ids)) {
        $this->db->where_in('s.company', $company_ids);
    }

    if ($this->input->get('customer_phone') != "") {
        $this->db->where('q.customer_phone', $this->input->get('customer_phone'));
    }

    if ($this->input->get('sales_person_from')) {
        $this->db->where('a.from', $this->input->get('sales_person_from'));
    }

    if ($this->input->get('sales_person_to')) {
        $this->db->where('a.to', $this->input->get('sales_person_to'));
    }

    $this->db->select('a.*, q.total, u.name as company_name, q.customer_name')
        ->from('quotation_transfer a')
        ->join('quotation q', 'q.id = a.quotation', 'left')
        ->join('sales_person s', 's.id = q.sales_person', 'left')
        ->join('admin_users u', 'u.id = s.company', 'left')
        ->join('customer c', 'c.id = q.customer', 'left');

    $this->db->order_by('a.id', 'DESC');

    $data = $this->db->get()->result_array();

    return $data;
}	

	function api_get_init_sync_data($user_id){
			$exp = date("Y-m-d", strtotime("-12 month"));
			$this->db->where(array('from'=>$user_id,'created_date >='=>$exp))
					->select('id,to as spto,to_name,quotation,custName,custPhone,reason,transfer_status,created_date,status_date');
			$data=$this->gets_data()->result_array();
			$i=0;
			foreach($data as $d){
					$data[$i]['created_date']=strtotime($d['created_date'])*1000;
					$data[$i]['status_date']=strtotime($d['status_date'])*1000;
					$i++;
				}
			return $data;
		}


	function get_sync_transfers_subjects($user_id=0){
			$this->db->where(array('from'=>$user_id,'synched'=>0))
				->select('id,transfer_status,status_date');
			$data=$this->gets_data()->result_array();
			$i=0;
			foreach($data as $d){
					$data[$i]['status_date']=$d['status_date']>0?strtotime($d['status_date'])*1000:0;
					$i++;
				}
			return $data;
		}	

	function make_transfer_synced($data=array()){
			if(@$data){
					try{
							$update=array('synched'=>1);
							$this->db->where_in('id',$data);
							$this->db->update($this->table_name,$update);
						}
					catch(Exception $e){}
				}
		}
		
}