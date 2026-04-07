<?php
class Productmodel extends CI_Model
{
	public $table_name = 'product';
	public $primary_key = 'id';
	public $table_name_tags = 'product_tags';
	public $table_name_sync = 'synch_record_product';
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
 function base_query()
{
    $this->db->from($this->table_name . ' a');
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
		$this->db->where_in('product', $id);
		$this->db->delete($this->table_name_tags);
		$this->db->where_in($this->primary_key, $id);
		$this->db->update($this->table_name, ['deleted_at' => date('Y-m-d H:i:s')]);
		return $this->db->affected_rows();
	}
	///Extras
	function gets_data_tags()
	{
		return $query = $this->db->get($this->table_name_tags . ' a');
	}
	function delete_tags_custom()
	{
		$this->db->delete($this->table_name_tags);
	}
	function delete_tags($id)
	{
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_tags))
			return $this->db->affected_rows();
		return false;
	}
	function save_tags($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name_tags, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name_tags, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}
	function save_tags_batch($data)
	{
		$this->db->insert_batch($this->table_name_tags, $data);
	}

	////
	function get_data_admin_table()
	{
		return $this->get_data_admin_table_products();
	}
	function get_data_admin_table_products()
	{
		if(@$this->input->get('company_id')){
			$select_company_id = $this->input->get('company_id');
			  $company_id = get_company_id_or_null($select_company_id);
		 
	
			$this->db->where('company_id', $company_id);
		 }
		if (@$this->input->get('product_type')) {
			$this->db->where('a.product_type', @$this->input->get('product_type'));
		}
		if (@$this->input->get('sub_product_type')) {
			$this->db->where('a.sub_product_type', @$this->input->get('sub_product_type'));
		}
		if (@$this->input->get('vendor')) {
			$this->db->where('a.vendor', @$this->input->get('vendor'));
		}
		if (@$this->input->get('price_band_type')) {
			$this->db->where('a.price_band', @$this->input->get('price_band_type'));
		}
		if (@$this->input->get('price_band')) {
			$this->db->where('a.price_band', @$this->input->get('price_band'));
		}
		if (@$this->input->get('tag') != "") {
			$this->db->join('product_tags t', 't.product=a.id', 'inner')
				->where('t.tag', $this->input->get('tag'));
		}
		$this->db->join('product_type p', 'p.id=a.product_type', 'left')
			->join('product_type s', 's.id=a.sub_product_type', 'left')
			->join('vendor v', 'v.id=a.vendor', 'left')
			->join('price_band r', 'r.id=a.price_band', 'left')
			->select('a.id,a.name,a.price_band_type,min_width,min_drop,max_width,max_drop,turnable,company_config,p.name product_type_name,s.name as sub_product_type_name,v.name as vendor_name,r.name as priceband_name');
		$result = $this->gets_data()->result_array();
		return $result;
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
		$this->db->select('id,`product_type`, `sub_product_type`, `vendor`, `name`, `price_band_type`, `price_band`, `min_width`, `max_width`, `min_drop`, `max_drop`,version');
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

	function get_data_sync_all($user_id)
	{
		$data_update = array();
		$data_delete = array();
		$cur_items = array();
		$salesperson_row = $this->salespersonmodel->get_row($user_id);

		$select_company_id = $salesperson_row->company;
	
		$last_synch_data = get_last_sync_date($user_id, 'product');

		$last_synch_date = null;
		if ($last_synch_data && !empty($last_synch_data->last_synch_date)) {
			$last_synch_date = $last_synch_data->last_synch_date;
		}
		$updated_product_types = [];
		// margin Updation from margin_config table
		if ($last_synch_date !== null) {

			$this->db->select('DISTINCT(product_type)', false);
			$this->db->from('margin_config_new');
			$this->db->where('updated_at >', $last_synch_date);
			$this->db->where('company_id', $select_company_id);
			$query = $this->db->get();

			$updated_product_types = array_column($query->result_array(), 'product_type');
		}
		$this->db->select('id,`product_type`, `sub_product_type`, `vendor`, `name`, `price_band_type`, `price_band`, `min_width`, `max_width`, `min_drop`, `max_drop`,turnable,version,company_config,company_id');
		// $this->db->where('deleted_at',NULL)
		if ($last_synch_date !== null) {
			$this->db->group_start();
			$this->db->where('updated_at >', $last_synch_date);

			if (!empty($updated_product_types)) {
				$this->db->or_group_start()
					->where_in('product_type', $updated_product_types)
					->or_where_in('sub_product_type', $updated_product_types)
					->group_end();
			}

			$this->db->group_end();
		}

          $company_id = get_company_id_or_null($select_company_id);
		 
	
			$this->db->where('company_id', $company_id);
		


		$this->db->where('deleted_at', Null);
		$dt = $this->gets_data()->result_array();



		$this->db->select('margin_type, margin_value');
		$this->db->where('id', $select_company_id);
		$existing_margin = $this->db->get('admin_users')->row();
		$company_margin = $existing_margin->margin_value;

		$i = 0;
		foreach ($dt as $d) {


			$product_id = $d['id'];
			$product_type = $d['product_type'];
			$sub_product_type = $d['sub_product_type'];
			$price_band = $d['price_band'];
			$margin_value = null;

			// 1. Check margin_config for product_id
			$this->db->select('value');
			$this->db->where(['product_id' => $product_id, 'company_id' => $select_company_id]);
			$result = $this->db->get('margin_config_new')->row();
			if ($result) {
				$margin_value = $result->value;
			}

			// 2. If not found, check sub_product_type
				if (is_null($margin_value)) {
				$this->db->select('value');
				$this->db->where([
				'product_type'  => $product_type,
				'price_band_id' => $price_band,
				'company_id'    => $select_company_id
				]);
				
				$result = $this->db->get('margin_config_new')->row();
				if ($result) {
				$margin_value = $result->value;
				}
}


			// 2. If not found, check sub_product_type
			if (is_null($margin_value)) {
				$this->db->select('value');
				$this->db->where(['product_type' => $sub_product_type, 'company_id' => $select_company_id]);
				$result = $this->db->get('margin_config_new')->row();
				if ($result) {
					$margin_value = $result->value;
				}
			}

			// 3. If still not found, check product_type
			if (is_null($margin_value)) {
				$this->db->select('value');
				$this->db->where(['product_type' => $product_type, 'company_id' => $select_company_id]);
				$result = $this->db->get('margin_config_new')->row();
				if ($result) {
					$margin_value = $result->value;
				}
			}

			// 4. Default to company margin
			if (is_null($margin_value)) {
				$margin_value = $company_margin;
			}

			$d["margin_percent"] =  $margin_value; //margin product;

			$data_update[] = $d;
		}


		$data['product'] = $data_update;
		$this->db->select('id as row_id ');
		$this->db->where('deleted_at IS NOT NULL', null, false);


		$cur_all = $this->gets_data()->result_array();
		$data['product_delete'] = array_column($cur_all, 'row_id');

		return $data;
	}


	function delete_sync_data($user_id, $data = array())
	{
		if ($data) {
			$this->db->where(array('user' => $user_id))
				->where_in('row_id', $data);
			$this->db->delete($this->table_name_sync);
		}
	}
	function get_total_count($search = '')
{
    $this->db->reset_query(); 

      $this->base_query(); 

    $this->apply_product_filters($search);

    return $this->db->count_all_results();
}



	function get_data_admin_table_products_margin($limit = null, $offset = null, $search = '')
	{
			if(@$this->input->get('company_id')){
			$select_company_id = $this->input->get('company_id');
			  $company_id = get_company_id_or_null($select_company_id);
		 
	
			$this->db->where('a.company_id', $company_id);
		 }
        
		if (@$this->input->get('product_type')) {
			$this->db->where('a.product_type', @$this->input->get('product_type'));
		}
		if (@$this->input->get('sub_product_type')) {
			$this->db->where('a.sub_product_type', @$this->input->get('sub_product_type'));
		}
		if (@$this->input->get('vendor')) {
			$this->db->where('a.vendor', @$this->input->get('vendor'));
		}
		if (@$this->input->get('price_band_type')) {
			$this->db->where('a.price_band_type', @$this->input->get('price_band_type'));
		}
		if (@$this->input->get('price_band')) {
			$this->db->where('a.price_band', @$this->input->get('price_band'));
		}
		if (@$this->input->get('tag') != "") {
			$this->db->join('product_tags t', 't.product=a.id', 'inner')
				->where('t.tag', $this->input->get('tag'));
		}
		$this->db->join('product_type p', 'p.id=a.product_type', 'left')
			->join('product_type s', 's.id=a.sub_product_type', 'left')
			->join('vendor v', 'v.id=a.vendor', 'left')
			->join('price_band r', 'r.id=a.price_band', 'left')

			->select('a.id,a.name,a.price_band as price_band_id, a.product_type as product_type_id,a.fabric_code,a.price_band_type,min_width,min_drop,max_width,max_drop,turnable,company_config,p.name product_type_name,s.name as sub_product_type_name,v.name as vendor_name,r.name as priceband_name,r.unit_price');
		if (!empty($search)) {
			$this->db->like('a.name', $search); // adjust column name as needed
		}
		$this->db->where('deleted_at', NULL);
		if ($limit !== null) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by('a.id', 'desc');
		$result = $this->gets_data()->result_array();

		// echo "<pre>";print_r($this->db->last_query());exit;

		return $result;
	}
	 function apply_product_filters($search = '')
	{

	 $select_company_id = $this->input->get('company_id') ?? null;
	 $company_id =$select_company_id;
	

   
	if ($this->input->get('company_id')) {
			$this->db->where('a.company_id',  $company_id );
		}
        
		if ($this->input->get('product_type')) {
			$this->db->where('a.product_type', $this->input->get('product_type'));
		}

		if ($this->input->get('sub_product_type')) {
			$this->db->where('a.sub_product_type', $this->input->get('sub_product_type'));
		}

		if ($this->input->get('vendor')) {
			$this->db->where('a.vendor', $this->input->get('vendor'));
		}

		if ($this->input->get('price_band_type')) {
			$this->db->where('a.price_band_type', $this->input->get('price_band_type'));
		}

		if ($this->input->get('price_band')) {
			$this->db->where('a.price_band', $this->input->get('price_band'));
		}

		if ($this->input->get('tag')) {
			$this->db->join('product_tags t', 't.product = a.id', 'inner');
			$this->db->where('t.tag', $this->input->get('tag'));
		}

		if (!empty($search)) {
			$this->db->like('a.name', $search);
		}

		$this->db->where('a.deleted_at', NULL);
		$this->db->where('a.is_enabled', 1);
	}

	function product_type_margin() {}
	function extra_type_margin() {}

	public function get_product_types_with_margin($company_id)
	{

		$this->db->select('mc.id as margin_id, mc.company_id, mc.product_type,pt.id as product_type_id, mc.value as margin_value, pt.name as product_type_name');
		$this->db->from('margin_config_new mc');
		$this->db->join('product_type pt', 'pt.id = mc.product_type', 'left');
		$this->db->where('mc.company_id', (int)$company_id);
		$this->db->order_by('pt.name', 'ASC');

		$query = $this->db->get();


		return $query->result();
	}
	public function get_extra_types_with_margin($company_id)
	{

		$this->db->select('mc.id as margin_id, mc.company_id, mc.extra_type,pt.id as extra_type_id, mc.value as margin_value, pt.name as extra_type_name');
		$this->db->from('extras_margin_config mc');
		$this->db->join('extras pt', 'pt.id = mc.extra_type', 'left');
		$this->db->where('mc.company_id', (int)$company_id);
		$this->db->order_by('pt.name', 'ASC');

		$query = $this->db->get();


		return $query->result();
	}
	public function get_products_for_export($filter = [])
	{
		$this->db->select('
        a.*, 
        pt.name as product_type_name,
        spt.name as sub_product_type_name,
        pb.name as priceband_name
    ');
		$this->db->from('products a');
		$this->db->join('product_types pt', 'pt.id = a.product_type', 'left');
		$this->db->join('product_types spt', 'spt.id = a.sub_product_type', 'left');
		$this->db->join('price_bands pb', 'pb.id = a.price_band', 'left');

		if (!empty($filter['search'])) {
			$this->db->like('a.name', $filter['search']);
		}

		if (!empty($filter['product_type'])) {
			$this->db->where('a.product_type', $filter['product_type']);
		}

		return $this->db->get()->result();
	}
	public function get_latest_margins($company_id = null)
	{
		return $this->db
			->select('
			m.id AS id,
            au.name AS company_name,
            pt.name AS product_type,
            pb.name AS price_band,
			pb.unit_price AS unit_price,
            pb.type AS band_type,
            m.value AS margin,
            m.updated_at
        ')
			->from('margin_config_new m')
			->join('admin_users au', 'au.id = m.company_id', 'left')
			->join('price_band pb', 'pb.id = m.price_band_id', 'left')
			->join('product_type pt', 'pt.id= m.product_type', 'left')
			->where('m.price_band_id IS NOT NULL', null, false)
			->where('m.company_id', (int)$company_id)

			->where('pt.name IS NOT NULL', null, false)
			->where('pb.name IS NOT NULL', null, false)
			->where('m.deleted_at', NULL)


			->order_by('pt.name', 'ASC')
			->limit(500)
			->get()
			->result();
	}
}
