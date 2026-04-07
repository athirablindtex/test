<?php
class Pricebandmodel extends CI_Model
{
	public $table_name = 'price_band';
	public $table_name_price = 'price_band_price';
	public $table_name_sync = 'synch_record_price_band';
	public $table_name_sync_price = 'synch_record_price_band_price';
	public $primary_key = 'id';
	function __construct()
	{
		parent::__construct();
		//$this->load->database();	
	}
	function gets_all()
	{
		$this->db->order_by($this->primary_key, 'desc');
		return $query = $this->db->get($this->table_name . ' a');
	}
	function gets_data()
	{
		return $query = $this->db->get($this->table_name . ' a');
	}

	function get_parent()
	{
		$this->db->where('parent', 0);
		$this->db->where('is_enabled', 1);
		$this->db->order_by($this->primary_key, 'desc');
		return $query = $this->db->get($this->table_name);
	}

	function gets_all_active()
	{
		$this->db->where('is_enabled', '1');
		$this->db->order_by($this->primary_key, 'desc');
		return $query = $this->db->get($this->table_name . ' a');
	}

	function get_row($id = 0)
	{
		$data = $this->db->get_where($this->table_name, array($this->primary_key => $id))->row();
		return $data;
	}


	function save($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}

	function save_batch($data)
	{

		$this->db->insert_batch($this->table_name, $data);
	}

	function save_custom($data)
	{
		$this->db->update($this->table_name, $data);
	}

	function delete($id)
	{
		$this->db->where('price_band', $id);
		$this->db->delete($this->table_name_price);
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name))
			return $this->db->affected_rows();
		return false;
	}

	///
	//new
	function gets_data_price()
	{
		return $query = $this->db->get($this->table_name_price . ' a');
	}
	function delete_price()
	{
		$this->db->delete($this->table_name_price);
	}
	function delete_price_id($id)
	{
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_price))
			return $this->db->affected_rows();
		return false;
	}
	function save_price($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name_price, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name_price, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}
	function save_price_batch($data)
	{
		$this->db->insert_batch($this->table_name_price, $data);
	}

	function update_price_batch($data)
	{
		$this->db->update_batch($this->table_name_price, $data, 'id');
	}

	////Acknowledge Sync data from local to server
	function acknowledge_sync_data($user_id, $data = array())
	{
		if (@$data) {
			$insert_data = array();
			$update_data = array();
			foreach ($data as $dt) {
				$this->db->where(array('row_id' => @$dt['server_id'], 'user' => $user_id));
				if ($cur_row = $this->db->get($this->table_name_sync)->row_array()) {
					$update_data[] = array('version' => @$dt['version'], 'last_synch_date' => date('Y-m-d H:i:s'), 'id' => $cur_row['id']);
				} else {
					$insert_data[] = array('version' => @$dt['version'], 'last_synch_date' => date('Y-m-d H:i:s'), 'row_id' => @$dt['server_id'], 'user' => $user_id);
				}
			}
			if (@$update_data) {
				$this->db->update_batch($this->table_name_sync, $update_data, 'id');
			}
			if (@$insert_data) {
				$this->db->insert_batch($this->table_name_sync, $insert_data);
			}
		}
	}
	///Send data from server to local
	function get_data_sync($user_id)
	{
		$data = array();
		$this->db->select('*');
		$dt = $this->gets_data()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));
			if ($cur_row = $this->db->get($this->table_name_sync)->row_array()) {
				if ($d['version'] > $cur_row['version']) {
					$data[] = $d;
				}
			} else {
				$data[] = $d;
			}
		}
		return $data;
	}

	///Send data from server to local
	function get_data_sync_all($user_id)
	{
		$data_update = array();
		$data_delete = array();
		$cur_items = array();
		$this->db->select('*');
		 $company_id = get_salesperson_company($user_id);
		 $company_id = get_company_id_or_null($company_id)  ;
		   if($company_id !== null){
			$this->db->where('company_id', $company_id);
		   }
		$dt = $this->gets_data()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));
			if ($cur_row = $this->db->get($this->table_name_sync)->row_array()) {
				$cur_items[] = $cur_row['id'];
				if ($d['version'] > $cur_row['version']) { // ADDED true to bypass versioning now temprorly by Mujeeb on 22/12/2023
					$data_update[] = $d;
				}
			} else {
				$data_update[] = $d;
			}
		}
		$this->db->where(array('user' => $user_id))
			->select('row_id');
		if ($cur_items) {
			$this->db->where_not_in('id', $cur_items);
		}
		$cur_all = $this->db->get($this->table_name_sync)->result_array();
		$data['priceband'] = $data_update;
		$data['priceband_delete'] = array_column($cur_all, 'row_id');
		return $data;
	}


	//Get Delete data
	function delete_sync_data($user_id, $data = array())
	{
		if ($data) {
			$this->db->where(array('user' => $user_id))
				->where_in('row_id', $data);
			$this->db->delete($this->table_name_sync);
		}
	}

	////Acknowledge Sync data from local to server
	function acknowledge_sync_data_price($user_id, $data = array())
	{
		if (@$data) {
			$insert_data = array();
			$update_data = array();
			foreach ($data as $dt) {
				$this->db->where(array('row_id' => @$dt['server_id'], 'user' => $user_id));
				if ($cur_row = $this->db->get($this->table_name_sync_price)->row_array()) {
					$update_data[] = array('version' => @$dt['version'], 'last_synch_date' => date('Y-m-d H:i:s'), 'id' => $cur_row['id']);
				} else {
					$insert_data[] = array('version' => @$dt['version'], 'last_synch_date' => date('Y-m-d H:i:s'), 'row_id' => @$dt['server_id'], 'user' => $user_id);
				}
			}
			if (@$update_data) {
				$this->db->update_batch($this->table_name_sync_price, $update_data, 'id');
			}
			if (@$insert_data) {
				$this->db->insert_batch($this->table_name_sync_price, $insert_data);
			}
		}
	}
	///Send data from server to local
	function get_data_sync_price($user_id)
	{
		$data = array();
		$this->db->select('id,`price_band`, `dim_width`, `dim_drop`, `price`,version');
		$dt = $this->gets_data_price()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));
			if ($cur_row = $this->db->get($this->table_name_sync_price)->row_array()) {
				if ($d['version'] > $cur_row['version']) {
					$data[] = $d;
				}
			} else {
				$data[] = $d;
			}
		}
		return $data;
	}



	function get_data_sync_all_price($user_id)
	{


		$last_synch_data = get_last_sync_date($user_id, 'price_band_price');

		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}
	

		$this->db->select('id,`price_band`, `dim_width`, `dim_drop`, `price`,version');
		if ($last_synch_date !== null) {

			$this->db->where('updated_at >', $last_synch_date);
		}

		$this->db->where('deleted_at', Null);
		$dt = $this->gets_data_price()->result_array();


		$this->db->select('id as row_id ');
		$this->db->where('deleted_at IS NOT NULL', null, false);
	


		$cur_all = $this->gets_data_price()->result_array();

		$data['priceband_price'] = $dt;
		$data['priceband_price_delete'] = $cur_all ? array_column($cur_all, 'row_id') : [];
		
        return $data;
	}


	//Get Delete data
	function delete_sync_data_price($user_id, $data = array())
	{
		if ($data) {
			$this->db->where(array('user' => $user_id))
				->where_in('row_id', $data);
			$this->db->delete($this->table_name_sync_price);
		}
	}



	function insert_check_data($name = "", $type = "", $product_type = "",$company_id)
	{
		try {
			if ($row = $this->check_data_name($name, $type,$product_type,$company_id)) {
				return $row['id'];
			} else {
				$save_data = [
					'priceband_version' => 0,
					'type' => $type,
					'name' => $name,
					'unit_price' => 0,
					'min_unit' => 0,
					'no_width' => 0,
					'no_drop' => 0,
					'product_type'=>$product_type,
					'is_enabled' => 1,
					'created_date' => date('Y-m-d'),
					'company_id'=>$company_id,
					'version' => 1
				];
				return $this->save($save_data);
			}
		} catch (Exception $e) {
		}
	}


	function check_data_name($name = "", $type = "",$product_type = "",$company_id)
	{
		try {
			$this->db->where(array('name' => @$name, 'type' => $type,'product_type' => $product_type,'company_id' => $company_id));
			return $this->db->get($this->table_name)->row_array();
		} catch (Exception $e) {
		}
	}
	function getAllPriceBandNames()
	{


		// Query to fetch 'id' and 'name' from the 'price_band' table
		$query = $this->db->select('id, name')
			->from('price_band')
			->where('type', 'Matrix')
			->get();

		// Check if the query was successful and return the result
		if ($query->num_rows() > 0) {
			return $query->result_array();  // Returns an array of rows
		} else {
			return [];  // Return an empty array if no data is found
		}
	}
}
