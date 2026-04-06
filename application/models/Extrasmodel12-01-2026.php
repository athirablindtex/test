<?php

class Extrasmodel extends CI_Model
{
	public $table_name = 'extras';
	public $primary_key = 'id';
	public $table_name_price = 'extras_details_price';
	public $matrix_name_price = 'extras_matrix_price';
	public $table_name_sync = 'synch_record_extras';
	public $table_name_sync_price = 'synch_record_extras_matrix_price';
	function __construct()
	{
		parent::__construct();
		//$this->load->database();	
		$this->load->model(array('salespersonmodel'));
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
	function gets_data_details($extras_id)
	{
		$this->db->select('id, type, price');
		$this->db->where('extras_id', $extras_id);
		return $query = $this->db->get($this->table_name_price . ' a');
	}
	function gets_data_price()
	{
		return $query = $this->db->get($this->matrix_name_price . ' a');
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
	function save_details($data, $id = 0)
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
	public function delete_existing_record($existing_id)
	{
		$this->db->where('extras_id', $existing_id);
		$this->db->delete($this->table_name_price);
		// echo $this->db->last_query();
		// exit; // replace with actual table name

	}

	function save_batch($data)
	{

		$this->db->insert_batch($this->table_name, $data);
	}

	function save_custom($data)
	{
		$this->db->update($this->table_name, $data);
	}

	// function delete($id)
	// {
	// 	$this->delete_subs($id);
	// 	$this->db->where_in($this->primary_key, $id);
	// 	if ($this->db->delete($this->table_name))
	// 		return $this->db->affected_rows();
	// 	return false;
	// }
	function delete($id)
{
    $this->delete_subs($id);

    $this->db->where_in($this->primary_key, (array) $id);
    $this->db->update($this->table_name, [
        'deleted_at' => date('Y-m-d H:i:s')
    ]);

    return $this->db->affected_rows();
}
function delete_subs($id)
{
    $this->db->select('id');
    $this->db->where_in('parent', (array) $id);
    $this->db->where('deleted_at IS NULL', null, false);

    $mast_subs = $this->db->get($this->table_name)->result_array();

    if (!empty($mast_subs)) {
        $mast_subs_delete = array_column($mast_subs, 'id');

        // Recursively soft delete children
        $this->delete($mast_subs_delete);
    }
}


	// function delete_subs($id)
	// {
	// 	$this->db->where_in('parent', $id)->select('id');
	// 	$mast_subs = $this->gets_data()->result_array();
	// 	if (count($mast_subs) > 0) {
	// 		$mast_subs_delete = array_column($mast_subs, 'id');
	// 		$this->delete($mast_subs_delete);
	// 	}
	// }
	function delete_custom()
	{
		$this->db->delete($this->table_name);
	}
	function check_sub($id)
	{
		$this->db->where('parent', $id);
		$q = $this->db->get('category')->result();
		if ($q == true) {
			return $q;
		} else {
			$this->db->where('category', $id);
			$q1 = $this->db->get('product')->result();
			if ($q1 == TRUE) {
				return $q1;
			} else {
				return FALSE;
			}
		}
	}

	function get_higher_order()
	{
		$this->db->select('MAX(order1) as priority');
		$data = $this->db->get($this->table_name)->row();
		return $data;
	}
	function sort($or, $id)
	{
		$primary_key = $this->primary_key;
		$this->db->where($this->primary_key . ' !=', $id);
		$this->db->where('order1 >=', $or);
		$q1 = $this->db->get($this->table_name)->result();
		foreach ($q1 as $q) {
			$or++;
			$upd['order1'] = $or;
			$this->db->where($this->primary_key, $q->$primary_key);
			$this->db->update($this->table_name, $upd);
			$this->db->where($this->primary_key . ' !=', $q->$primary_key);
			$this->db->where('order1', $or);
			$r = $this->db->get($this->table_name)->row();
			if (!$r) {
				break;
			}
		}
	}
	function gets_all_except($id)
	{
		$this->db->where($this->primary_key . ' !=', $id);
		return $query = $this->db->get($this->table_name);
	}
	//new
	function insert_category_image($post)
	{
		$this->db->insert_batch('category_images', $post);
	}
	function get_category_image($id)
	{
		$this->db->where('category', $id);
		//$this->db->order_by('quantity','desc');
		return $this->db->get('category_images');
	}
	function delete_category_image($id)
	{
		$this->db->where_in('id', $id);
		$query = $this->db->get('category_images')->result();
		foreach (@$query as $rmv) {
			@unlink(APPPATH . '../uploads/category/' . @$rmv->image);
		}
		$this->db->where('id', $id);
		$this->db->delete('category_images');
	}

	function gets_data_admin_table_subs($limit, $page)
	{
		if (@$this->input->get('search') != "") {
			$this->db->like('a.name', $this->input->get('search'));
		}
		$this->db->where('parent', 0);
		$this->db->limit($limit, $page);
		$result = $this->gets_data()->result_array();
		$i = 0;
		foreach ($result as $res) {
			$this->db->where('parent', $res['id']);
			$result[$i]['sub'] = $this->gets_data()->result_array();
			$j = 0;
			foreach ($result[$i]['sub'] as $ress) {
				$this->db->where('parent', $ress['id']);
				$result[$i]['sub'][$j]['sub'] = $this->gets_data()->result_array();
				$k = 0;
				foreach ($result[$i]['sub'][$j]['sub'] as $resss) {
					$this->db->where('parent', $resss['id']);
					$result[$i]['sub'][$j]['sub'][$k]['sub'] = $this->gets_data()->result_array();
					$k++;
				}
				$j++;
			}
			$i++;
		}
		return $result;
	}
	function gets_data_admin_table_count()
	{
		if (@$this->input->get('search') != "") {
			$this->db->like('a.name', $this->input->get('search'));
		}
		$this->db->where('parent', 0);
		return $this->gets_data()->num_rows();
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
		$this->db->select('id,parent, name, type, value,version,mandatory');
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
		$salesperson_row = $this->salespersonmodel->get_row($user_id);
		//print_r($salesperson_row);
		$select_company_id = $salesperson_row->company;

		$data_update = [];
		$last_synch_data = get_last_sync_date($user_id, 'extras');


		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}


		$updated_extra_types = [];
		// margin Updation from margin_config table
		if ($last_synch_date !== null) {

			$this->db->select('DISTINCT(extra_type)', false);
			$this->db->from('extras_margin_config');
			$this->db->where('updated_at >', $last_synch_date);
			$this->db->where('company_id', $select_company_id);
			$query = $this->db->get();

			$updated_extra_types = array_column($query->result_array(), 'extra_type');
		}

		$this->db->select('id,parent, name, type, value,version,mandatory');
		if ($last_synch_date !== null) {
			$this->db->group_start();
			$this->db->where('updated_at >', $last_synch_date);

			if (!empty($updated_extra_types)) {
				// Use OR condition
				$this->db->or_where_in('id', $updated_extra_types);
			}

			$this->db->group_end();
		}

		$this->db->where('deleted_at', Null);
		$dt = $this->gets_data()->result_array();


		$this->db->select('margin_type, margin_value');
		$this->db->where('id', $select_company_id);
		$existing_margin = $this->db->get('admin_users')->row();
		$company_margin = $existing_margin->margin_value;

		// $dt["margin_percent"] =20;
		$i = 0;

		$data_update = [];
		if (count($dt) > 0) {
			foreach ($dt as $d) {

				$id = $d['id'];


				$margin_value = null;
						if (is_null($margin_value)) {
			// 1. Check margin_config for product_id
			$this->db->select('margin');
			$this->db->where(['extra' => $id,]);
			$result = $this->db->get('product_type_extras')->row();
			if ($result) {
			$margin_value = $result->margin;
			}			}

					if (is_null($margin_value)) {
				// 1. Check margin_config for product_id
				$this->db->select('value');
				$this->db->where(['extra_type' => $id, 'company_id' => $select_company_id]);
				$result = $this->db->get('extras_margin_config')->row();
				if ($result) {
					$margin_value = $result->value;
				}
			}

				// // 2. If not found, check sub_product_type
				// if (is_null($margin_value)) {
				// 	$this->db->select('value');
				// 	$this->db->where(['extra_type' => $id, 'company_id' => $select_company_id]);
				// 	$result = $this->db->get('extras_margin_config')->row();
				// 	if ($result) {
				// 		$margin_value = $result->value;
				// 	}
				// }

				// // 3. If still not found, check product_type
				// if (is_null($margin_value)) {
				// 	$this->db->select('value');
				// 	$this->db->where(['extra_type' => $id, 'company_id' => $select_company_id]);
				// 	$result = $this->db->get('extras_margin_config')->row();
				// 	if ($result) {
				// 		$margin_value = $result->value;
				// 	}
				// }

				// 4. Default to company margin
				if (is_null($margin_value)) {
					$margin_value = $company_margin;
				}

				$d["margin_percent"] =  $margin_value; //margin product
				$type =  $d["type"];
				$table_db_prices = array();
				if ($type == "Multiple") {


					$tableprices = $this->gets_data_details($id)->result_array();
					//print_r($tableprices);
					foreach ($tableprices as $tb) {
						$table_db_prices[] = array(
							"type" => $tb["type"],
							"price" => $tb["price"]
						);
					}
				}
				$d["prices"] =  $table_db_prices;


				$data_update[] = $d;
			}
		}

		$data['extras'] = $data_update;
		$this->db->select('id as row_id');
		$this->db->where('deleted_at IS NOT NULL', null, false);
		$cur_all = $this->gets_data()->result_array();
		$data['extras_delete'] = array_column($cur_all, 'row_id');


		return $data;
	}
	function get_data_sync_all1($user_id)
	{
			$salesperson_row = $this->salespersonmodel->get_row($user_id);
		//print_r($salesperson_row);
		$select_company_id = $salesperson_row->company;

		$data_update = [];
		$last_synch_data = get_last_sync_date($user_id, 'extras');


		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}


		$updated_extra_types = [];
		// margin Updation from margin_config table
		if ($last_synch_date !== null) {

			$this->db->select('DISTINCT(extra_type)', false);
			$this->db->from('extras_margin_config');
			$this->db->where('updated_at >', $last_synch_date);
			$this->db->where('company_id', $select_company_id);
			$query = $this->db->get();

			$updated_extra_types = array_column($query->result_array(), 'extra_type');
		}

		$this->db->select('id,parent, name, type, value,version,mandatory');
		if ($last_synch_date !== null) {
			$this->db->group_start();
			$this->db->where('updated_at >', $last_synch_date);

			if (!empty($updated_extra_types)) {
				// Use OR condition
				$this->db->or_where_in('id', $updated_extra_types);
			}

			$this->db->group_end();
		}

		$this->db->where('deleted_at', Null);
		$dt = $this->gets_data()->result_array();


		$this->db->select('margin_type, margin_value');
		$this->db->where('id', $select_company_id);
		$existing_margin = $this->db->get('admin_users')->row();
		$company_margin = $existing_margin->margin_value;

		// $dt["margin_percent"] =20;
		$i = 0;

		$data_update = [];
		if (count($dt) > 0) {
			foreach ($dt as $d) {

				$id = $d['id'];


				$margin_value = null;
			if (is_null($margin_value)) {
			// 1. Check margin_config for product_id
			$this->db->select('margin');
			$this->db->where(['extra' => $id,]);
			$result = $this->db->get('product_type_extras')->row();
			if ($result) {
			$margin_value = $result->margin;
			}			}

				// 2. If not found, check sub_product_type
				if (is_null($margin_value)) {
					$this->db->select('value');
					$this->db->where(['extra_type' => $id, 'company_id' => $select_company_id]);
					$result = $this->db->get('extras_margin_config')->row();
					if ($result) {
						$margin_value = $result->value;
					}
				}

				// // 3. If still not found, check product_type
				// if (is_null($margin_value)) {
				// 	$this->db->select('value');
				// 	$this->db->where(['extra_type' => $id, 'company_id' => $select_company_id]);
				// 	$result = $this->db->get('extras_margin_config')->row();
				// 	if ($result) {
				// 		$margin_value = $result->value;
				// 	}
				// }

				// 4. Default to company margin
				if (is_null($margin_value)) {
					$margin_value = $company_margin;
				}

				$d["margin_percent"] =  $margin_value; //margin product
				$type =  $d["type"];
				$table_db_prices = array();
				if ($type == "Multiple") {


					$tableprices = $this->gets_data_details($id)->result_array();
					//print_r($tableprices);
					foreach ($tableprices as $tb) {
						$table_db_prices[] = array(
							"type" => $tb["type"],
							"price" => $tb["price"]
						);
					}
				}
				$d["prices"] =  $table_db_prices;


				$data_update[] = $d;
			}
		}

		$data['extras'] = $data_update;
		$this->db->select('id as row_id');
		$this->db->where('deleted_at IS NOT NULL', null, false);
		$cur_all = $this->gets_data()->result_array();
		$data['extras_delete'] = array_column($cur_all, 'row_id');


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

	function get_parents()
	{
		// Get all where parent_id = 0 (meaning it's a parent)
		return $this->db->where('parent', 0)->get('extras')->result_array();
	}
	function get_children_by_parent($parent_id)
	{
		// Get all children based on parent_id
		return $this->db->where('parent', $parent_id)->get('extras')->result_array();
	}


	function get_data_sync_all_price($user_id)
	{

		$last_synch_data = get_last_sync_date($user_id, 'extras_matrix_price');

		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}





		$this->db->select('id, extras_id, dim_width, dim_drop, price, type, version');
		if ($last_synch_date !== null) {

			$this->db->where('updated_at >', $last_synch_date);
		}

		$this->db->where('deleted_at', Null);
		$dt = $this->gets_data_price()->result_array();

		$this->db->select('id as row_id ');
		$this->db->where('deleted_at IS NOT NULL', null, false);


		$cur_all = $this->gets_data_price()->result_array();

		$data['extrasmatrix_price'] = $dt;
		$data['extrasmatrix_price_delete'] = $cur_all ? array_column($cur_all, 'row_id') : [];








		return $data;
	}

	function get_data_sync_all_price1($user_id)
	{
		$data_update = array();
		$data_delete = array();
		$cur_items = array();
		$this->db->select('id,`extras_id`, `dim_width`, `dim_drop`, `price`,type,version');
		$dt = $this->gets_data_price()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			// print_r ($d);
			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));

			if ($cur_row = $this->db->get($this->table_name_sync_price)->row_array()) {
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
		$cur_all = $this->db->get($this->table_name_sync_price)->result_array();
		$data['extrasmatrix_price'] = $data_update;
		$data['extrasmatrix_price_delete'] = array_column($cur_all, 'row_id');
		return $data;
	}

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
	function delete_sync_data_price($user_id, $data = array())
	{
		if ($data) {
			$this->db->where(array('user' => $user_id))
				->where_in('row_id', $data);
			$this->db->delete($this->table_name_sync_price);
		}
	}
	function search_extras_like_name($name)
	{

		$this->db->like('name', $name);
		$query = $this->db->get('extras');
		return $query->result_array();
	}
}
