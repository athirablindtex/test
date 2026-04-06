<?php

use Mpdf\Tag\P;

class Salespersonmodel extends CI_Model
{
	public $table_name = 'sales_person';
	public $primary_key = 'id';
	public $table_name_sync = 'synch_record_salesperson';
	function __construct()
	{
		parent::__construct();
		$this->load->model(array("tokkenmodel"));

		//$this->load->database();	
	}
function gets_all($filter = [])
{
    if (!empty($filter)) {

        foreach ($filter as $key => $value) {

            if (is_array($value)) {
                $this->db->where_in($key, $value);
            } else {
                $this->db->where($key, $value);
            }

        }
    }

    $this->db->order_by($this->primary_key, 'desc');

    $query = $this->db->get($this->table_name . ' a');



    return $query;
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
		$this->db->where_in($this->primary_key, $id);
		$query = $this->db->get($this->table_name)->result();
		foreach (@$query as $rmv) {
			@unlink(APPPATH . '../uploads/salesperson/' . @$rmv->image);
		}
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name))
			return $this->db->affected_rows();
		return false;
	}

	public function get_current_user($id)
	{
		$this->load->helper('url'); // Ensure this is loaded

		$this->db->select('a.id, a.name, a.email, a.phone, a.email, u.name as company_name, a.company, a.image as salesperson_image')
			->where('a.id', $id)
			->join('admin_users u', 'u.id = a.company', 'left');

		$query = $this->db->get($this->table_name . ' a');
		$user = $query->row_array();

		if ($user && !empty($user['salesperson_image'])) {
			$user['user_image'] = base_url('uploads/salesperson/' . $user['salesperson_image']);
		} else {
			// Optionally assign a default image
			$user['user_image'] = '';
		}

		return $user;
	}


	function get_other_users($id)
	{
		$this->db->select('a.id,a.name,a.email,a.phone,a.email,u.name as company_name')
			->where('a.id !=', $id)
			->where('a.active', 1)
			->join('admin_users u', 'u.id=a.company', 'left');
		return $query = $this->db->get($this->table_name . ' a')->result_array();
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
		$this->db->select('id,name,email,phone,active,version');
		$this->db->where('id !=', $user_id);
		$dt = $this->gets_data()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));
			if ($cur_row = $this->db->get($this->table_name_sync)->row_array()) {
				if ($d['version'] > $cur_row['version'] || true) {
					$data[] = $d;
				}
			} else {
				$data[] = $d;
			}
		}
		return $data;
	}

	///Send data from server to local
	function get_data_sync_all($user_id, $company_id)
	{
		$data_update = array();
		$data_delete = array();
		$cur_items = array();
		$this->db->select('id,name,email,phone,active,version');
		$this->db->where('id !=', $user_id);
		$this->db->where('company', $company_id);
		$dt = $this->gets_data()->result_array();
		$i = 0;
		foreach ($dt as $d) {
			//echo "<br/>fdfdfdfid =".$d['id'];

			$this->db->select("push_token");
			$this->db->where('user_id', $d['id']);
			$data = $this->db->get("login_tokkens")->result_array();
			//print_r($data);
			$d["push_token"] = @$data[0]["push_token"];

			$this->db->where(array('row_id' => @$d['id'], 'user' => $user_id));
			if ($cur_row = $this->db->get($this->table_name_sync)->row_array()) {

				//$token_row = $this->tokkenmodel->get_row($d['id']);
				//$d["push_token"] = $token_row->push_token;
				$cur_items[] = $cur_row['id'];
				if ($d['version'] > $cur_row['version'] || true) { // ADDED true to bypass versioning now temprorly by Mujeeb on 22/12/2023
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
		$data['salesperson'] = $data_update;
		$data['salesperson_delete'] = array_column($cur_all, 'row_id');
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

	function get_total_salespersons_count($filter = [])
	{

		if (isset($filter['company']) && !empty($filter['company'])) {
			if (is_array($filter['company'])) {
				$this->db->where_in('company', $filter['company']);
			} else {
				$this->db->where('company', $filter['company']);
			}
		}

		$q = $this->db->get($this->table_name);
		return $q->num_rows();
	}
}
