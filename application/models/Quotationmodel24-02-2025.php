<?php
class Quotationmodel extends CI_Model
{
	public $table_name = 'quotation';
	public $primary_key = 'id';
	public $table_name_room = 'quotation_rooms';
	public $table_name_window = 'quotation_rooms_windows';
	public $table_name_extras = 'quotation_rooms_windows_extras';
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

	function get_row_detail($id = 0)
	{
		$data = $this->db->get_where($this->table_name . ' a', array('a.'.$this->primary_key => $id))->row();
		return $data;
	}


	function save($data, $id = 0)
	{
		$success = false;
		try {
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
		} catch (Exception $e) {
			log_message('error', $e);
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
		if ($this->db->delete($this->table_name))
			return $this->db->affected_rows();
		return false;
	}

	//room
	function gets_data_room()
	{
		return $query = $this->db->get($this->table_name_room . ' a');
	}
	function delete_room_custom()
	{
		$this->db->delete($this->table_name_room);
	}
	function delete_room($id)
	{
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_room))
			return $this->db->affected_rows();
		return false;
	}
	function save_room($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name_room, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name_room, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}
	function save_room_batch($data)
	{
		$this->db->insert_batch($this->table_name_room, $data);
	}

	function update_room_batch($data)
	{
		$this->db->update_batch($this->table_name_room, $data, 'id');
	}

	function gets_data_window()
	{
		return $query = $this->db->get($this->table_name_window . ' a');
	}
	function delete_window_custom()
	{
		$this->db->delete($this->table_name_window);
	}
	function delete_window($id)
	{
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_window))
			return $this->db->affected_rows();
		return false;
	}
	function save_window($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name_window, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name_window, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}
	function save_window_batch($data)
	{
		$this->db->insert_batch($this->table_name_window, $data);
	}

	function update_window_batch($data)
	{
		$this->db->update_batch($this->table_name_window, $data, 'id');
	}


	function gets_data_extra()
	{
		return $query = $this->db->get($this->table_name_extras . ' a');
	}
	function delete_extra_custom()
	{
		$this->db->delete($this->table_name_extras);
	}
	function delete_extra($id)
	{
		$this->db->where_in($this->primary_key, $id);
		if ($this->db->delete($this->table_name_extras))
			return $this->db->affected_rows();
		return false;
	}
	function save_extra($data, $id = 0)
	{
		$success = false;
		if ($id > 0) {
			$this->db->where($this->primary_key, $id);
			$success = $this->db->update($this->table_name_extras, $data);
			return $id;
		} else {
			if ($this->db->insert($this->table_name_extras, $data)) {
				$id = $this->db->insert_id();
				return $id;
			}
		}
	}
	function save_extra_batch($data)
	{
		$this->db->insert_batch($this->table_name_extras, $data);
	}

	function update_extra_batch($data)
	{
		$this->db->update_batch($this->table_name_extras, $data, 'id');
	}



	function api_save_quotation_row($user_id = 0, $post=[])
	{
		$insert_id = 0;
		try {
			if (@$post['quotation']) {
				$insert_id = $this->api_quotation_insert_row($user_id, $post['quotation']);
			}
		} catch (Exception $e) {
		}
		return $insert_id;
	}

	function api_quotation_insert_row($user_id = 0, $d = array())
	{
		$server_id = 0;
		if (@$d) {
			$insert = array(
				'sales_person' => $user_id,
				'customer_name' => @$d['customer_name'] ?: "",
				'customer_phone' => @$d['customer_phone'] ?: "",
				'customerEmail' => @$d['customerEmail'] ?: "",
				'total' => @$d['total'] ?: 0,
				'discount' => @$d['discount'] ?: 0,
				'vat' => @$d['vat'] ?: 0,
				'advance' => @$d['advance'] ?: 0,
				'sub_total' => @$d['sub_total'] ?: 0,
				'payment_type' => @$d['payment_type'] ?: "",
				'remarks' => @$d['remarks'] ?: "",
				'priority' => @$d['priority'] ?: '',
				'status' => @$d['status'] ?: '',
				'confirm' => @$d['confirm'] ?: 0,
				'synched' => 1
			);
			if (@$d['created_time'] > 0) {
				$seconds = ceil((int)$d['created_time'] / 1000);
				$insert['created_date'] = @date('Y/m/d', $seconds);
			}
			if (@$d['update_time'] > 0) {
				$seconds = ceil((int)$d['update_time'] / 1000);
				$insert['updated_date'] = @date('Y/m/d', $seconds);
			}
			$this->db->where(array('phone' => @$d['customer_phone'], 'sales_person' => $user_id));
			if ($customer = $this->customermodel->gets_data()->row()) {
				@$insert['customer'] = @$customer->id;
			} else {
				$cust_insert = array(
					'phone' => @$d['customer_phone'] ?: "",
					'sales_person' => $user_id,
					'name' => @$d['customer_name'] ?: "",
					'email' => @$d['customerEmail'] ?: "",
					'created_date' => date('Y-m-d')
				);
				$insert['customer'] = @$this->customermodel->save($cust_insert);
			}
			if ($sign = @$this->user_signature_upload(@$d['signature'])) {
				if (@$sign != "") {
					$insert['signature'] = $sign;
				}
			}
			if ($sp = $this->salespersonmodel->get_row($user_id)) {
				$insert['sales_person_name'] = $sp->name;
				$insert['sales_person_phone'] = $sp->phone;
			}
			if (@$d['server_id'] > 0) {
				$row = $this->get_row($d['server_id']);
				$this->delete_quotation_sub_data($d['server_id']);
			}
			if (@$row) {
				$server_id = $this->save($insert, $row->id);
			} else {
				$server_id = $this->save($insert);
				$ack_quotations[] = array('id' => @$d['id'], 'server_id' => $server_id);
			}
			$this->insert_rooms_quotation(@$d['rooms'], $server_id);
		}
		return $server_id;
	}






	//Sync data		
	function sync_data($user_id, $data = array())
	{
		$ack_quotations = array();
		if (@$data) {
			foreach ($data as $d) {
				if (@$d['confirm']) {
					try {
						$server_id = $this->api_quotation_insert_row($user_id, $d);
						if ($server_id > 0) {
							$ack_quotations[] = $server_id;
						}
					} catch (Exception $e) {
					}
				}
			}
		}
		$dt = array('ack_quotations' => $ack_quotations);
		return $dt;
	}


	function insert_rooms_quotation($rooms = array(), $quotation_id = 0)
	{
		try {
			if (@$quotation_id > 0) {
				$room_insert_ids = array();
				///Rooms Insert
				if (@$rooms) {
					if (@count(@$rooms) > 0) {
						foreach (@$rooms as $room) {
							$room_insert = array(
								'quotation' => $quotation_id,
								'room_name' => @$room["room_name"] ?: "",
								'room_type' => @$room["room_type"] ?: ""
							);
							if (@$room["server_id"] > 0) {
								$this->db->where(array('id' => @$room["server_id"]));
								$roomRow = $this->gets_data_room()->row();
							}
							if (@$roomRow) {
								$room_id = $this->save_room($room_insert, $roomRow->id);
							} else {
								$room_id = $this->save_room($room_insert);
								$ack_rooms[] = array('id' => @$room['id'], 'server_id' => $room_id);
							}
							$room_insert_ids[] = $room_id;
							$this->insert_windows_room(@$room['windows'], $room_id);
						}
					}
				}
				///	
				//delete room ids old	
				$this->db->where('quotation', $quotation_id)->select('id');
				$quotRooms = $this->gets_data_room()->result_array();
				$existRoomIds = array_column($quotRooms, 'id');
				if (count($existRoomIds) > 0) {
					$deleteRoomIds = array_diff($existRoomIds, $room_insert_ids);
					if (count($deleteRoomIds) > 0) {
						$this->delete_room($deleteRoomIds);
					}
				}
				////	

			}
		} catch (Exception $e) {
		}
	}


	function insert_windows_room($windows = array(), $room_id = 0)
	{
		try {
			////Windows
			$window_insert_ids = array();
			if (@$windows && $room_id > 0) {
				if (@count(@$windows) > 0) {
					foreach (@$windows as $win) {
						if (@$win["activeItem"]) {
							$window_insert = array(
								'room' => $room_id,
								'window_name' => @$win['window_name'] ?: "",
								'product' => @$win['product'] ?: 0,
								'product_name' => @$win['product_name'] ?: "",
								'product_price' => @$win['product_price'] ?: 0,
								'total' => @$win['total'] ?: 0,
								'remarks' => @$win['remarks'] ?: "",
								'width' => @$win['width'] ?: 0,
								'height' => @$win['height'] ?: 0,
								'chain_drop' => @$win['chain_drop'] ?: 0,
								'unit' => @$win['unit'] ?: "cm",
								'note' => @$win['note'] ?: "",
								'noteOnline' => @$win['noteOnline'] ?: 0,
								'discount' => @$win['discount'] ?: "",
								'measurmenttype' => @$win['measurmenttype'] ?: 0,
								'priceBand' => @$win['priceBand'] ?: 0
							);
							if (@$win['server_id'] > 0) {
								$this->db->where('id', @$win['server_id']);
								$windowRow = $this->gets_data_window()->row();
							}
							if (@$windowRow) {
								$window_id = $this->save_window($window_insert, $windowRow->id);
							} else {
								$window_id = $this->save_window($window_insert);
								$ack_windows[] = array('id' => @$win['id'], 'server_id' => $window_id);
							}
							$window_insert_ids[] = $window_id;
							///extra insert	
							$this->insert_extras_window(@$win["extras"], $window_id);
						}
					}
				}
			}
			///delete old windows
			$this->db->where('room', $room_id)->select('id');
			$quotWins = $this->gets_data_window()->result_array();
			$existWinIds = array_column($quotWins, 'id');
			if (count($existWinIds) > 0) {
				$deleteWinIds = array_diff($existWinIds, $window_insert_ids);
				if (count($deleteWinIds) > 0) {
					$this->delete_window($deleteWinIds);
				}
			}
			///	
			///
		} catch (Exception $e) {
		}
	}


	function insert_extras_window($extras = array(), $window_id = 0)
	{
		try {
			///extra insert	
			$extra_insert_ids = array();
			if (@$extras && $window_id > 0) {
				if (@count(@$extras) > 0) {
					foreach ($extras as $ext) {
						$extra_insert = array(
							'window' => $window_id,
							'extra' => @$ext['extra'] ?: 0,
							'sub_extra' => @$ext['sub_extra'] ?: 0,
							'sub_sub_extra' => @$ext['sub_sub_extra'] ?: 0,
							'sub_sub_sub_extra' => @$ext['sub_sub_sub_extra'] ?: 0,
							'extra_name' => @$ext['extra_name'] ?: "",
							'sub_extra_name' => @$ext['sub_extra_name'] ?: "",
							'sub_sub_extra_name' => @$ext['sub_sub_extra_name'] ?: "",
							'sub_sub_sub_extra_name' => @$ext['sub_sub_sub_extra_name'] ?: "",
							'price' => @$ext['price'] ?: 0,
							'customPrice' => @$ext['customPrice'] ?: 0
						);
						if (@$ext['server_id'] > 0) {
							$this->db->where('id', @$ext['server_id']);
							$extRow = $this->gets_data_extra()->row();
						}
						if (@$extRow) {
							$ext_id = $this->save_extra($extra_insert, $extRow->id);
						} else {
							$ext_id = $this->save_extra($extra_insert);
							$ack_extras[] = array('server_id' => $ext_id, 'id' => @$ext['id']);
						}
						$extra_insert_ids[] = $ext_id;
					}
				}
			}
			//delete old extras	
			$this->db->where('window', $window_id)->select('id');
			$quotExts = $this->gets_data_extra()->result_array();
			$existExtIds = array_column($quotExts, 'id');
			if (count($existExtIds) > 0) {
				$deleteExtIds = array_diff($existExtIds, $extra_insert_ids);
				if (count($deleteExtIds) > 0) {
					$this->delete_extra($deleteExtIds);
				}
			}
			///	
		} catch (Exception $e) {
		}
	}


	function delete_quotation_data($quot_id = 0)
	{
		try {
			$this->delete($quot_id);
			$this->delete_quotation_sub_data($quot_id);
		} catch (Exception $e) {
		}
	}


	function delete_quotation_sub_data($quot_id = 0)
	{
		try {
			$this->db->where(array('quotation' => $quot_id))
				->select('id');
			$rooms = $this->gets_data_room()->result_array();
			foreach ($rooms as $rm) {
				$windows = $this->api_get_sub_windows_rooms($rm['id']);
				foreach ($windows as $w) {
					$this->db->where('window', $w['id']);
					$this->delete_extra_custom();
				}
				$this->db->where('room', $rm['id']);
				$this->delete_window_custom();
			}
			$this->db->where(array('quotation' => $quot_id));
			$this->delete_room_custom();
		} catch (Exception $e) {
		}
	}

	function user_signature_upload($image = "")
	{
		if ($image != "") {
			$imgdata = base64_decode($image);
			$file = rand(1, 1000000000000000000000000) . uniqid() . '.jpeg';
			$path = APPPATH . '../uploads/quotation/' . $file;
			$fp = fopen($path, "w");
			$this->base64_to_jpeg('data:image/jpeg;base64, ' . $image, $path);
			return $file;
		} else {
			return FALSE;
		}
	}

	function base64_to_jpeg($base64, $path)
	{
		@copy($path, $$path);
		$ifp = @fopen($path, 'wb');
		$data = explode(',', $base64);
		@fwrite($ifp, base64_decode($data[1]));
		@fclose($ifp);
		return true;
	}

	function get_quotations_admin()
	{
		if ($this->input->get('from')) {
			$this->db->where('a.updated_date >=', date('Y-m-d', strtotime($this->input->get('from'))));
		} else {
			$default_expiry_from_date = date('Y-m-d', strtotime('-7 day'));
			$this->db->where('a.updated_date >=', $default_expiry_from_date);
		}
		if ($this->input->get('to')) {
			$this->db->where('a.updated_date <=', date('Y-m-d', strtotime($this->input->get('to'))));
		}
		if (@$this->input->get('sales_person') > 0) {
			$this->db->where('a.sales_person', $this->input->get('sales_person'));
		}
		if (@$this->input->get('company') > 0) {
			$this->db->where('a.company', $this->input->get('company'));
		}
		if (@$this->input->get('customer_phone') != "") {
			$this->db->where('a.customer_phone', $this->input->get('customer_phone'));
		}
		$this->db->select('a.*,c.address as cust_address,c.name as cust_name,c.email as cust_email,c.phone as cust_phone,u.name as company_name,u.image as company_image,u.address as company_address,u.phone as company_phone,u.email as company_email,sp.name as sp_name')
			->join('sales_person sp', 'sp.id=a.sales_person', 'left')
			->join('admin_users u', 'u.id=sp.company', 'left')
			->join('customer c', 'c.id=a.customer', 'left');
		$data = $this->gets_all()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$this->db->where(array('quotation' => $d['id']));
			$data[$i]['rooms'] = $this->gets_data_room()->result_array();
			$window_count = 0;
			$products = array();
			$j = 0;
			foreach ($data[$i]['rooms'] as $rm) {
				$this->db->select('a.*,t.name as product_type,s.name as sub_product_type,m.name as measuring_type')
					->where(array('a.room' => $rm['id']))
					->join('product p', 'p.id=a.product', 'left')
					->join('product_type t', 't.id=p.product_type', 'left')
					->join('product_type s', 's.id=p.sub_product_type', 'left')
					->join('measuring_type m', 'm.id=a.measurmenttype', 'left');
				$data[$i]['rooms'][$j]['windows'] = $this->gets_data_window()->result_array();
				$k = 0;
				foreach ($data[$i]['rooms'][$j]['windows'] as $win) {
					$this->db->where(array('window' => $win['id']))
						->select('id,window,extra,sub_extra,sub_sub_extra,sub_sub_sub_extra,extra_name,sub_extra_name,sub_sub_extra_name,sub_sub_sub_extra_name,price,customPrice');
					$data[$i]['rooms'][$j]['windows'][$k]['extras'] = $this->gets_data_extra()->result_array();
					if ($win['product'] > 0) {
						$pro_index = -1;
						if (count($products) > 0) {
							$pro_index = @array_search($win['product'], @array_column($products, 'item'));
						}
						if ($pro_index >= 0) {
							$products[$pro_index]['quantity'] += 1;
							$products[$pro_index]['total'] += $win['total'];
						} else {
							$products[count($products)] = array('item' => $win['product'], 'item_name' => $win['product_name'], 'price' => $win['product_price'], 'quantity' => 1, 'total' => $win['total']);
						}
					}
					$k++;
				}
				$window_count += count($data[$i]['rooms'][$j]['windows']);
				$j++;
			}
			$data[$i]['window_count'] = $window_count;
			$data[$i]['products'] = $products;
			$i++;
		}
		return $data;
	}

	function api_get_transfer_data($user_id = 0)
	{
		$this->db->where(array('sales_person' => $user_id, 'transfer' => 1))
			->select('id,customer,customer_name,customer_phone,customerEmail,total,discount,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,priority,confirm');
		$data = $this->gets_data()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['created_time'] = strtotime($d['created_time']) * 1000;
			$data[$i]['update_time'] = strtotime($d['update_time']) * 1000;
			$data[$i]['rooms'] = $this->api_get_sub_rooms_quotation($d['id']);
			$i++;
		}
		return $data;
	}

	function api_get_init_sync_data($user_id = 0)
	{
		$exp = date("Y-m-d", strtotime("-12 month"));
		$this->db->where(array('sales_person' => $user_id, 'created_date >=' => $exp))
			->select('id,customer,customer_name,customer_phone,total,discount,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,priority,confirm,status');
		$data = $this->gets_data()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['created_time'] = strtotime($d['created_time']) * 1000;
			$data[$i]['update_time'] = strtotime($d['update_time']) * 1000;
			$data[$i]['rooms'] = $this->api_get_sub_rooms_quotation($d['id']);
			$i++;
		}
		return $data;
	}



	function api_get_quotation_sync_data_return($user_id = 0)
	{
		$exp = date("Y-m-d", strtotime("-12 month"));
		$this->db->where(array('sales_person' => $user_id, 'created_date >=' => $exp, 'synched' => 0))
			->select('id,status,updated_date as update_time');
		$data = $this->gets_data()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['update_time'] = strtotime($d['update_time']) * 1000;
			$i++;
		}
		return $data;
	}

	function api_get_quotation_transferred_data($user_id = 0)
	{
		$this->db->where(array('sales_person' => $user_id, 'transfer' => 1))
			->select('id,customer,customer_name,customer_phone,total,discount,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,priority,confirm,status');
		$data = $this->gets_data()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['update_time'] = strtotime($d['update_time']) * 1000;
			$data[$i]['created_time'] = strtotime($d['created_time']) * 1000;
			$i++;
		}
		return $data;
	}


	function api_get_sub_rooms_quotation($id = 0)
	{
		$this->db->where(array('quotation' => $id))
			->select('id,quotation,room_type,room_name');
		$data = $this->gets_data_room()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['windows'] = $this->api_get_sub_windows_rooms($d['id']);
			$i++;
		}
		return $data;
	}


	function api_get_sub_windows_rooms($id = 0)
	{
		$this->db->where(array('room' => $id))
			->select('id,room,window_name,product,product_name,product_price,total,width,height,chain_drop, unit,remarks,note,discount,priceBand,priceBandType,priceBandVersion,customPrice,noteOnline,"1" as activeItem,measurmenttype');
		$data = $this->gets_data_window()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['extras'] = $this->api_get_sub_extras_window($d['id']);
			$i++;
		}
		return $data;
	}

	function api_get_sub_extras_window($id = 0)
	{
		$this->db->where(array('window' => $id))
			->select('id,window,extra,sub_extra,sub_sub_extra,sub_sub_sub_extra,extra_name,sub_extra_name,sub_sub_extra_name,sub_sub_sub_extra_name,price,customPrice');
		return $this->gets_data_extra()->result_array();
	}

	function get_sub_rooms_quotation($id = 0)
	{
		$this->db->where(array('quotation' => $id))
			->select('id,quotation,room_type,room_name');
		$data = $this->gets_data_room()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['windows'] = $this->get_sub_windows_rooms($d['id']);
			$i++;
		}
		return $data;
	}

	function get_sub_windows_rooms($id = 0)
	{
		$this->db->where(array('a.room' => $id))
			->select('a.id,a.room,a.window_name,a.product,a.product_name,a.product_price,a.total,a.width,a.height,a.chain_drop, a.unit,a.remarks,a.note,a.discount,a.priceBand,a.priceBandType,a.priceBandVersion,a.customPrice,a.noteOnline,m.name as measuring_type,a.measurmenttype,"1" as activeItem')
			->join('measuring_type m', 'm.id=a.measurmenttype', 'left');
		$data = $this->gets_data_window()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$data[$i]['extras'] = $this->get_sub_extras_window($d['id']);
			$i++;
		}
		return $data;
	}

	function get_sub_extras_window($id = 0)
	{
		$this->db->where(array('window' => $id))
			->select('id,window,extra,sub_extra,sub_sub_extra,sub_sub_sub_extra,extra_name,sub_extra_name,sub_sub_extra_name,sub_sub_sub_extra_name,price,customPrice');
		return $this->gets_data_extra()->result_array();
	}

	function api_make_transfered($user_id, $data)
	{
		$upd['transfer'] = 0;
		foreach ($data as $d) {
			$this->save($upd, $d['server_id']);
		}
	}


	function sync_ack_return_quotation($data)
	{
		try {
			$upd = array('synched' => 1);
			$this->db->where_in($this->primary_key, $data);
			$this->db->update($this->table_name, $upd);
		} catch (Exception $e) {
		}
	}


	function sync_ack_make_quotations_transfered($user_id = 0)
	{
		try {
			$upd = array('transfer' => 0);
			$this->db->where('sales_person', $user_id);
			$this->db->update($this->table_name, $upd);
		} catch (Exception $e) {
		}
	}


	function get_products_from_quotation_array($rooms = array())
	{
		$product_total = false;
		$product_dt = array();
		try {
			foreach ($rooms as $room) {
				foreach ($room['windows'] as $window) {
					$index = array_search($window['product'], array_column($product_dt, 'product'));
					$price = $window['product_price'];
					if ($product_total) {
						$price = $window['total'];
					}
					if ($index >= 0) {
						$row = $product_dt[$index];
						$row['total'] += $price;
						$row['count']++;
					} else {
						$index = count($product_dt);
						$row = array('product' => $window['product'], 'product_name' => $window['product_name'], 'total' => $price, 'count' => 1);
					}
					$product_dt[$index] = $row;
				}
			}
		} catch (Exception $e) {
		}
		return $product_dt;
	}


	function get_sent_quotation_count($filter = [])
	{
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->where('a.confirm', 0);
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$q = $this->db->get($this->table_name . ' a');
		return $q->num_rows();
	}

	function get_confirmed_quotation_count($filter = [])
	{
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->where('a.confirm', 1);
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$q = $this->db->get($this->table_name . ' a');
		return $q->num_rows();
	}

	function get_sales_amount($filter = [])
	{
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->where('a.confirm', 1);
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$q = $this->db->select_sum("sub_total")->get($this->table_name . ' a');
		$res = $q->row_array();
		return $res['sub_total'];
	}

        
        function create_quotation_request($post){
             $path = APPPATH . '../uploads/log/quotation-request/';
             $name = "qtation-request-".date("Y-m-d-H-i-s").".json";
              $myfile = fopen($path.$name, "w") ;
           fwrite($myfile, $post);
            fclose($myfile);
             
        }


        /*	
		function sync_data($user_id,$data=array()){
			$ack_quotations=array();
			$ack_rooms=array();
			$ack_windows=array();
			$ack_extras=array();
			if(@$data){
					foreach($data as $d){
							$server_id=0;
							if(@$d['confirm']){
									$insert=array(	
													'sales_person'=>$user_id,
													'customer_name'=>@$d['customer_name']?:"",
													'customer_phone'=>@$d['customer_phone']?:"",
													'customerEmail'=>@$d['customerEmail']?:"",
													'total'=>@$d['total']?:0,
													'discount'=>@$d['discount']?:0,
													'vat'=>@$d['vat']?:0,
													'advance'=>@$d['advance']?:0,
													'sub_total'=>@$d['sub_total']?:0,
													'payment_type'=>@$d['payment_type']?:"",
													'remarks'=>@$d['remarks']?:"",
													'priority'=>@$d['priority']?:'',
													'confirm'=>@$d['confirm']?:0,
												);
									if(@$d['created_time']>0){
											$seconds = ceil((int)$d['created_time'] / 1000);
											$insert['created_date']=@date('Y/m/d',$seconds);
										}
									if(@$d['update_time']>0){
											$seconds = ceil((int)$d['update_time'] / 1000);
											$insert['updated_date']=@date('Y/m/d',$seconds);
										}	
									$this->db->where(array('phone'=>@$d['customer_phone'],'sales_person'=>$user_id));
									if($customer=$this->customermodel->gets_data()->row()){
											@$insert['customer']=@$customer->id;
										}
									else{
											@$insert['customer']=0;
										}	
									if($sign=@$this->user_signature_upload(@$d['signature'])){
											if(@$sign!=""){
													$insert['signature']=$sign;
												}
										}	
									if($sp=$this->salespersonmodel->get_row($user_id)){
											$insert['sales_person_name']=$sp->name;
											$insert['sales_person_phone']=$sp->phone;
										}				
									if(@$d['server_id']>0){
											$row=$this->gets_data()->row($d['server_id']);
										}
									if(@$row){
											$server_id=$this->save($insert,$row->id);
										}
									else{
											$server_id=$this->save($insert);
											$ack_quotations[]=array('id'=>@$d['id'],'server_id'=>$server_id);
										}
								}			
							if(@$server_id>0){
									$room_insert_ids=array();	
									///Rooms Insert
									if(@$d['rooms']){
											if(@count(@$d['rooms'])>0){
													foreach(@$d['rooms'] as $room){
															$room_insert=array('quotation'=>$server_id,
																				'room_name'=>@$room["room_name"]?:"",
																				'room_type'=>@$room["room_type"]?:""
																	);
															if(@$room["server_id"]>0){
																	$this->db->where(array('id'=>@$room["server_id"]));
																	$roomRow=$this->gets_data_room()->row();
																}
															if(@$roomRow){
																	$room_id=$this->save_room($room_insert,$roomRow->id);
																}
															else{
																	$room_id=$this->save_room($room_insert);
																	$ack_rooms[]=array('id'=>@$room['id'],'server_id'=>$room_id);
																}
															$room_insert_ids[]=$room_id;	
															////Windows
															$window_insert_ids=array();
															if(@$room['windows'] && $room_id>0){
																	if(@count(@$room['windows'])>0){
																			foreach(@$room['windows'] as $win){
																					if(@$win["activeItem"]){
																								$window_insert=array(
																									'room'=>$room_id,
																									'window_name'=>@$win['window_name']?:"",
																									'product'=>@$win['product']?:0,
																									'product_name'=>@$win['product_name']?:"",
																									'product_price'=>@$win['product_price']?:0,
																									'total'=>@$win['total']?:0,
																									'remarks'=>@$win['remarks']?:"",
																									'width'=>@$win['width']?:0,
																									'height'=>@$win['height']?:0,
																									'chain_drop'=>@$win['chain_drop']?:0,
																									'unit'=>@$win['unit']?:"cm",
																									'note'=>@$win['noteOnline']?(@$win['note']?:""):"",
																									'discount'=>@$win['discount']?:""
																								);
																							if(@$win['server_id']>0){
																									$this->db->where('id',@$win['server_id']);
																									$windowRow=$this->gets_data_window()->row();
																								}	
																							if(@$windowRow){
																									$window_id=$this->save_window($window_insert,$windowRow->id);
																								}
																							else{
																									$window_id=$this->save_window($window_insert);
																									$ack_windows[]=array('id'=>@$win['id'],'server_id'=>$window_id);
																								}	
																							$window_insert_ids[]=$window_id;	
																							///extra insert	
																							$extra_insert_ids=array();
																							if(@$win["extras"] && $window_id>0){
																									if(@count(@$win["extras"])>0){
																											foreach($win["extras"] as $ext){
																													$extra_insert=array(
																																	'window'=>$window_id,
																																	'extra'=>@$ext['extra'],
																																	'sub_extra'=>@$ext['sub_extra'],
																																	'sub_sub_extra'=>@$ext['sub_sub_extra'],
																																	'sub_sub_sub_extra'=>@$ext['sub_sub_sub_extra'],
																																	'extra_name'=>@$ext['extra_name'],
																																	'sub_extra_name'=>@$ext['sub_extra_name'],
																																	'sub_sub_extra_name'=>@$ext['sub_sub_extra_name'],
																																	'sub_sub_sub_extra_name'=>@$ext['sub_sub_sub_extra_name'],
																																	'price'=>@$ext['price']
																																);
																													if(@$ext['server_id']>0){
																															$this->db->where('id',@$ext['server_id']);
																															$extRow=$this->gets_data_extra()->row();
																														}	
																													if(@$extRow){
																															$ext_id=$this->save_extra($extra_insert,$extRow->id);
																														}
																													else{
																															$ext_id=$this->save_extra($extra_insert);
																															$ack_extras[]=array('server_id'=>$ext_id,'id'=>@$ext['id']);
																														}
																													$extra_insert_ids[]=$ext_id;				
																												}
																										}
																								}
																							//delete old extras	
																							$this->db->where('window',$window_id)->select('id');	
																							$quotExts=$this->gets_data_extra()->result_array();	
																							$existExtIds=array_column($quotExts,'id');
																							if(count($existExtIds)>0){
																									$deleteExtIds=array_diff($existExtIds,$extra_insert_ids);
																									if(count($deleteExtIds)>0){
																											$this->delete_extra($deleteExtIds);
																										}
																								}
																							///	
																						}
																					
																				}
																		}
																}
															///delete old windows
															$this->db->where('room',$room_id)->select('id');	
															$quotWins=$this->gets_data_window()->result_array();	
															$existWinIds=array_column($quotWins,'id');
															if(count($existWinIds)>0){
																	$deleteWinIds=array_diff($existWinIds,$window_insert_ids);
																	if(count($deleteWinIds)>0){
																			$this->delete_window($deleteWinIds);
																		}
																}
															///	
															///		
														}
												}
										}
									///	
									//delete room ids old	
									$this->db->where('quotation',$server_id)->select('id');	
									$quotRooms=$this->gets_data_room()->result_array();	
									$existRoomIds=array_column($quotRooms,'id');
									if(count($existRoomIds)>0){
											$deleteRoomIds=array_diff($existRoomIds,$room_insert_ids);
											if(count($deleteRoomIds)>0){
													$this->delete_room($deleteRoomIds);
												}
										}
									////	
								}		
						}
				}
			$dt=array('ack_quotations'=>$ack_quotations,'ack_rooms'=>$ack_rooms,'ack_windows'=>$ack_windows,'ack_extras'=>$ack_extras);
			return $dt;	
		}	
		function api_quotation_insert_row($user_id,$d=array()){
				$server_id=0;
				if(@$d){
						$insert=array(	
										'sales_person'=>$user_id,
										'customer_name'=>@$d['customer_name'],
										'customer_phone'=>@$d['customer_phone'],
										'total'=>@$d['total'],
										'discount'=>@$d['discount'],
										'vat'=>@$d['vat'],
										'advance'=>@$d['advance'],
										'sub_total'=>@$d['sub_total'],
										'payment_type'=>@$d['payment_type'],
										'remarks'=>@$d['remarks'],
										'priority'=>@$d['priority']?:'',
										'confirm'=>@$d['confirmed']?:0,
									);
						if(@$d['created_time']>0){
								$seconds = ceil((int)$d['created_time'] / 1000);
								$insert['created_date']=@date('Y/m/d',$seconds);
							}
						if(@$d['update_time']>0){
								$seconds = ceil((int)$d['update_time'] / 1000);
								$insert['updated_date']=@date('Y/m/d',$seconds);
							}	
						if(@$d['customer']==0){
								$this->db->where(array('phone'=>@$d['customer_phone'],'sales_person'=>$user_id));
								if($customer=$this->customermodel->gets_data()->row()){
										@$insert['customer']=$customer->id;
									}	
							}
						else{
								@$insert['customer']=@$d['customer'];
							}		
						if($sign=@$this->user_signature_upload(@$d['signature'])){
								if(@$sign!=""){
										$insert['signature']=$sign;
									}
							}	
						if($sp=$this->salespersonmodel->get_row($user_id)){
								$insert['sales_person_name']=$sp->name;
								$insert['sales_person_phone']=$sp->phone;
							}				
						if(@$d['server_id']>0){
								$row=$this->gets_data()->row($d['server_id']);
							}
						if(@$row){
								$server_id=$this->save($insert,$row->id);
							}
						else{
								$server_id=$this->save($insert);
								$ack_quotations[]=array('id'=>@$d['id'],'server_id'=>$server_id);
							}			
						if($server_id>0){
								$room_insert_ids=array();	
								///Rooms Insert
								if(@$d['rooms']){
										if(@count(@$d['rooms'])>0){
												foreach(@$d['rooms'] as $room){
														$room_insert=array('quotation'=>$server_id,
																			'room_name'=>@$room["room_name"],
																			'room_type'=>@$room["room_type"]
																);
														if(@$room["server_id"]>0){
																$this->db->where(array('id'=>@$room["server_id"]));
																$roomRow=$this->gets_data_room()->row();
															}
														if(@$roomRow){
																$room_id=$this->save_room($room_insert,$roomRow->id);
															}
														else{
																$room_id=$this->save_room($room_insert);
																$ack_rooms[]=array('id'=>@$room['id'],'server_id'=>$room_id);
															}
														$room_insert_ids[]=$room_id;	
														////Windows
														$window_insert_ids=array();
														if(@$room['windows'] && $room_id>0){
																if(@count(@$room['windows'])>0){
																		foreach(@$room['windows'] as $win){
																				$window_insert=array(
																						'room'=>$room_id,
																						'window_name'=>@$win['window_name'],
																						'product'=>@$win['product'],
																						'product_name'=>@$win['product_name'],
																						'product_price'=>@$win['product_price'],
																						'total'=>@$win['total'],
																						'remarks'=>@$win['remarks'],
																						'width'=>@$win['width'],
																						'height'=>@$win['height'],
																						'chain_drop'=>@$win['chain_drop'],
																						'unit'=>@$win['unit']?:"cm",
																						'note'=>@$win['note']?:"",
																						'discount'=>@$win['discount']?:"",
																						'customPrice'=>@$win['customPrice']?:0
																					);
																				if(@$win['server_id']>0){
																						$this->db->where('id',@$win['server_id']);
																						$windowRow=$this->gets_data_window()->row();
																					}	
																				if(@$windowRow){
																						$window_id=$this->save_window($window_insert,$windowRow->id);
																					}
																				else{
																						$window_id=$this->save_window($window_insert);
																						$ack_windows[]=array('id'=>@$win['id'],'server_id'=>$window_id);
																					}	
																				$window_insert_ids[]=$window_id;	
																				///extra insert	
																				$extra_insert_ids=array();
																				if(@$win["extras"] && $window_id>0){
																						if(@count(@$win["extras"])>0){
																								foreach($win["extras"] as $ext){
																										$extra_insert=array(
																														'window'=>$window_id,
																														'extra'=>@$ext['extra'],
																														'sub_extra'=>@$ext['sub_extra'],
																														'sub_sub_extra'=>@$ext['sub_sub_extra'],
																														'sub_sub_sub_extra'=>@$ext['sub_sub_sub_extra'],
																														'extra_name'=>@$ext['extra_name'],
																														'sub_extra_name'=>@$ext['sub_extra_name'],
																														'sub_sub_extra_name'=>@$ext['sub_sub_extra_name'],
																														'sub_sub_sub_extra_name'=>@$ext['sub_sub_sub_extra_name'],
																														'price'=>@$ext['price'],
																														'customPrice'=>@$ext['customPrice']?:0
																													);
																										if(@$ext['server_id']>0){
																												$this->db->where('id',@$ext['server_id']);
																												$extRow=$this->gets_data_extra()->row();
																											}	
																										if(@$extRow){
																												$ext_id=$this->save_extra($extra_insert,$extRow->id);
																											}
																										else{
																												$ext_id=$this->save_extra($extra_insert);
																												$ack_extras[]=array('server_id'=>$ext_id,'id'=>@$ext['id']);
																											}
																										$extra_insert_ids[]=$ext_id;				
																									}
																							}
																					}
																				//delete old extras	
																				$this->db->where('window',$window_id)->select('id');	
																				$quotExts=$this->gets_data_extra()->result_array();	
																				$existExtIds=array_column($quotExts,'id');
																				if(count($existExtIds)>0){
																						$deleteExtIds=array_diff($existExtIds,$extra_insert_ids);
																						if(count($deleteExtIds)>0){
																								$this->delete_extra($deleteExtIds);
																							}
																					}
																				///	
																			}
																	}
															}
														///delete old windows
														$this->db->where('room',$room_id)->select('id');	
														$quotWins=$this->gets_data_window()->result_array();	
														$existWinIds=array_column($quotWins,'id');
														if(count($existWinIds)>0){
																$deleteWinIds=array_diff($existWinIds,$window_insert_ids);
																if(count($deleteWinIds)>0){
																		$this->delete_window($deleteWinIds);
																	}
															}
														///	
														///		
													}
											}
									}
								///	
								//delete room ids old	
								$this->db->where('quotation',$server_id)->select('id');	
								$quotRooms=$this->gets_data_room()->result_array();	
								$existRoomIds=array_column($quotRooms,'id');
								if(count($existRoomIds)>0){
										$deleteRoomIds=array_diff($existRoomIds,$room_insert_ids);
										if(count($deleteRoomIds)>0){
												$this->delete_room($deleteRoomIds);
											}
									}
								////	
							}
					}
				return $server_id;	
			}
		function sync_data($user_id,$data=array()){
				$ack_quotations=array();
				$ack_rooms=array();
				$ack_windows=array();
				$ack_extras=array();
				if(@$data){
						foreach($data as $d){
								$server_id=0;
								if(@$d['confirm']){
										$insert=array(	
														'sales_person'=>$user_id,
														'customer_name'=>@$d['customer_name']?:"",
														'customer_phone'=>@$d['customer_phone']?:"",
														'customerEmail'=>@$d['customerEmail']?:"",
														'total'=>@$d['total']?:0,
														'discount'=>@$d['discount']?:0,
														'vat'=>@$d['vat']?:0,
														'advance'=>@$d['advance']?:0,
														'sub_total'=>@$d['sub_total']?:0,
														'payment_type'=>@$d['payment_type']?:"",
														'remarks'=>@$d['remarks']?:"",
														'priority'=>@$d['priority']?:'',
														'status'=>@$d['status']?:'',
														'confirm'=>@$d['confirm']?:0,
														'synched'=>1
													);
										if(@$d['created_time']>0){
												$seconds = ceil((int)$d['created_time'] / 1000);
												$insert['created_date']=@date('Y/m/d',$seconds);
											}
										if(@$d['update_time']>0){
												$seconds = ceil((int)$d['update_time'] / 1000);
												$insert['updated_date']=@date('Y/m/d',$seconds);
											}	
										$this->db->where(array('phone'=>@$d['customer_phone'],'sales_person'=>$user_id));
										if($customer=$this->customermodel->gets_data()->row()){
												@$insert['customer']=@$customer->id;
											}
										else{
												@$insert['customer']=0;
											}	
										if($sign=@$this->user_signature_upload(@$d['signature'])){
												if(@$sign!=""){
														$insert['signature']=$sign;
													}
											}	
										if($sp=$this->salespersonmodel->get_row($user_id)){
												$insert['sales_person_name']=$sp->name;
												$insert['sales_person_phone']=$sp->phone;
											}				
										if(@$d['server_id']>0){
												$row=$this->get_row($d['server_id']);
											}
										if(@$row){
												$server_id=$this->save($insert,$row->id);
											}
										else{
												$server_id=$this->save($insert);
												$ack_quotations[]=array('id'=>@$d['id'],'server_id'=>$server_id);
											}
										$this->insert_rooms_quotation(@$d['rooms'],$server_id);
									}			
								
							}
					}
				$dt=array('ack_quotations'=>$ack_quotations,'ack_rooms'=>$ack_rooms,'ack_windows'=>$ack_windows,'ack_extras'=>$ack_extras);
				return $dt;	
			}
		
		*/
}
