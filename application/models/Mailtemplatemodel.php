<?php
class Mailtemplatemodel extends CI_Model
{
	public $table_name = 'mail_templates';
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
		$this->db->where_in('product_type', $id);
		$this->db->delete($this->table_name_extras);
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name))
			return $this->db->affected_rows();
		return false;
	}

	function get_latest_row()
	{
		$data = $this->db->select('description,name,category')->order_by($this->primary_key, "desc")->limit(1)->get($this->table_name)->row();
		return $data;
	}
}
