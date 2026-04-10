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

		$this->load->model(array('servicemodel'));
		//$this->load->database();	
	}
	function gets_all()
	{
		$this->db->order_by($this->primary_key, 'desc');
		return $query = $this->db->get($this->table_name . ' a');
	}
	function gets_data()
	{
		return $query = $this->db
			->where('a.deleted_at IS NULL', null, false)
			->get($this->table_name . ' a');
	}
	function gets_data_delete()
	{
		return $query = $this->db

			->get($this->table_name . ' a');
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
		$data = $this->db->get_where($this->table_name . ' a', array('a.' . $this->primary_key => $id))->row();
		return $data;
	}


	function save($data, $id = 0)
	{
		try {
			if ($id > 0) {
				// Update case
				$this->db->where($this->primary_key, $id);
				$success = $this->db->update($this->table_name, $data);

				if (!$success) {
					$error = $this->db->error();
					log_message('error', 'Update error: ' . $error['message']);
					//echo 'Update Error: ' . $error['message'];
					//echo "\nQuery: " . $this->db->last_query();
					return 0;
				}

				return $id;
			} else {
				// Insert case
				if ($this->db->insert($this->table_name, $data)) {
					$id = $this->db->insert_id();

					// //Debugging output
					// echo "Inserted ID: " . $id . "\n";
					// echo "Last Query: " . $this->db->last_query();
					return $id;
				} else {
					$error = $this->db->error();
					//    echo "Insert Error: " . $error['message'] . "\n";
					//     echo "Query: " . $this->db->last_query();
					return 0;
				}
			}
		} catch (Exception $e) {
			// log_message('error', 'Exception in save(): ' . $e->getMessage());
			// echo "Exception: " . $e->getMessage();
			return 0;
		}
		exit;
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



	function api_save_quotation_row($user_id = 0, $post = [])
	{


		$insert_id = 0;
		try {
			if (@$post['quotation']) {

				$insert_id = $this->api_quotation_insert_row($user_id, $post['quotation'], false);





				$this->servicemodel->send_quotation_notification_service($insert_id, true);
			}
		} catch (Exception $e) {
		}

		return $insert_id;
	}

	function api_quotation_insert_row($user_id = 0, $d = array(), $mail_send)
	{



		$server_id = 0;
		$this->db->trans_start();   // START
		if (@$d) {
			// ✅ TOKEN LOGIC (SERVER SIDE ONLY)

			if (!empty($d['server_id'])) {


				$existingRow = $this->get_row($d['server_id']);

				if (!empty($existingRow) && !empty($existingRow->token)) {
					$token = $existingRow->token;
				} else {

					$token = 'QTN-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));
				}
			} else {


				$token = 'QTN-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));
			}
			$insert = array(
				'sales_person' => $user_id,
				'invoiceno' => @$d['invoiceno'] ?: "",
				'customer_name' => @$d['customer_name'] ?: "",
				'customer_phone' => @$d['customer_phone'] ?: "",
				'customerEmail' => @$d['customerEmail'] ?: "",
				'customer_address' => @$d['customerAddress'] ?: "",
				'deal_id' => @$d['deal_id'] ?: null,
				'total' => @$d['total'] ?: 0,
				'discount' => @$d['discount'] ?: 0,
				'vat' => @$d['vat'] ?: 0,
				'advance' => @$d['advance'] ?: 0,
				'discountPercentage' => @$d['discountPercentage'] ?: 0,
				'sub_total' => @$d['sub_total'] ?: 0,
				'payment_type' => @$d['payment_type'] ?: "",
				'balancePaymentType' => @$d['balancePaymentType'] ?: "",
				'remarks' => @$d['remarks'] ?: "",
				'priority' => @$d['priority'] ?: '',
				'status' => @$d['status'] ?: '',
				'insDate'     => @$d['insDate'] ?: '',
				'insFromTime' => @$d['insFromTime'] ?: '',
				'insToTime'   => @$d['insToTime'] ?: '',
				'is_remarks' => ($d['is_remarks'] == "1") ? 1 : 0,
				'token' => $token,
				'source' => ($d['source'] ?? null),


				'confirm' => @$d['confirm'] ?: 0,
				'synched' => 1,
				'customerTrn' => @$d['customerTrn'] ?: '',
				'customerRef' => @$d['customerRef'] ?: '',

			);

			if (isset($d['created_time']) && is_numeric($d['created_time'])) {
				$seconds = intval($d['created_time'] / 1000); // convert ms to seconds
				$insert['created_date'] = date('Y-m-d H:i:s', $seconds);
				$insert['created_backend'] = date('Y-m-d H:i:s');
			} else {

				$insert['created_date'] = date('Y-m-d H:i:s');
			}


			if (isset($d['update_time']) && is_numeric($d['update_time'])) {
				$seconds = intval($d['update_time'] / 1000); // convert ms to seconds
				$insert['updated_date'] = date('Y-m-d H:i:s', $seconds);
			}
			$this->db->where([
				'phone'        => str_replace(' ', '', @$d['customer_phone']),
				'sales_person' => $user_id
			]);
			if ($customer = $this->customermodel->gets_data()->row()) {

				@$insert['customer'] = @$customer->id;

				$isFleetWithDeal = ($d['source'] ?? '') === 'Fleet' && !empty($d['deal_id']) && $d['deal_id'] > 0;
				if (!$isFleetWithDeal) {
					$cust_insert_upd = array(

						'name' => @$d['customer_name'] ?: "",
						'email' => @$d['customerEmail'] ?: "",
						'address' => @$d['customerAddress'] ?: "",
						'updated_at' => date('Y-m-d H:i:s')

					);

					@$this->customermodel->save($cust_insert_upd, @$customer->id);
				}
			} else {
				$cust_insert = array(
					'phone' => @$d['customer_phone'] ?: "",
					'sales_person' => $user_id,
					'name' => @$d['customer_name'] ?: "",
					'email' => @$d['customerEmail'] ?: "",
					'address' => @$d['customerAddress'] ?: "",
					'created_date' => date('Y-m-d')
				);
				$insert['customer'] = @$this->customermodel->save($cust_insert);
			}

			if (!empty($d['signature'])) {

				$sign = $this->user_signature_upload($d['signature']);


				if (!empty($sign)) {
					$insert['signature'] = $sign;
				} else {
					// echo "Signature upload failed or returned empty.<br>";
				}
			} else {
				//echo "No signature provided.<br>";
			}

			if ($sp = $this->salespersonmodel->get_row($user_id)) {
				$insert['sales_person_name'] = $sp->name;
				$insert['sales_person_phone'] = $sp->phone;
			}

			if (@$d['server_id'] > 0) {
				$row = $this->get_row($d['server_id']);
				$old_row = null;

				if (!empty($d['server_id']) && is_object($row)) {
					$old_row = clone $row;
				}
				$this->delete_quotation_sub_data($d['server_id']);
			}
			if (@$row) {
				$server_id = $this->save($insert, $row->id);
				$changed_fields = array_diff_assoc($insert, (array) $old_row);

				$this->log_app_quotation($server_id, $d['id'] ?? null, 'edit', $d['invoiceno'], $old_row->status, $d['status'], json_encode($changed_fields), $user_id, 1);
			} else {
				// print_r($insert);
				// exit;
				$server_id = $this->save($insert);
				$ack_quotations[] = array('id' => @$d['id'], 'server_id' => $server_id);
				$this->log_app_quotation($server_id, $d['id'] ?? null, 'create', $d['invoiceno'], null, $d['status'], json_encode($insert), $user_id, 1);
			}
			$this->insert_rooms_quotation(@$d['rooms'], $server_id);
		}

		$this->db->trans_complete();   // END

		if ($this->db->trans_status() === FALSE) {
			return 0;
		}

			$this->triggerDealSync($insert, $server_id, $d);


		return $server_id;
	}


function triggerDealSync($insert, $server_id, $d)
{
    if (empty($insert['deal_id']) || $insert['deal_id'] <= 0) {
        return;
    }

    // Only trigger once (on create)
    if (empty($d['created_time'])) {
        return;
    }

    // Prevent duplicate queue
    $exists = $this->db
        ->where('quotation_id', $server_id)
        ->where('synced', 0)
        ->get('api_sync_queue')
        ->row();

    if ($exists) {
        return;
    }

    $queueData = [
        'deal_id'        => $insert['deal_id'],
        'quotation_id'   => $server_id,
        'invoice_number' => $insert['invoiceno'] ?? '',
        'amount'         => $insert['total'] ?? 0,
        'fitting_date'   => $insert['insFromTime'] ?? null,
        'duration'       => $insert['insToTime'] ?? null,
        'created_at'     => date('Y-m-d H:i:s'),
        'synced'         => 0
    ];

    $this->db->insert('api_sync_queue', $queueData);
    $queue_id = $this->db->insert_id();

    // ✅ CALL WITH RESPONSE (NOT async)
    $url = site_url("index.php/fleetapp/processSingleQueue/" . $queue_id);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ✅ capture response
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);

    curl_close($ch);

    // ✅ SAVE RESPONSE
    $path = APPPATH . '../uploads/log/async-response/';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    $file = $path . "queue-" . $queue_id . "-" . date("Y-m-d-H-i-s") . ".txt";

    file_put_contents($file, print_r([
        'url' => $url,
        'response' => $response,
        'error' => $error,
        'http_code' => $info['http_code']
    ], true));
}
	public function log_app_quotation(
		$quotation_server_id,
		$quotation_app_id,
		$action,
		$invoiceno,
		$old_status,
		$new_status,
		$changed_fields,
		$user_id,

		$synced
	) {
		$this->db->insert('quotation_app_logs', [
			'quotation_server_id' => $quotation_server_id,
			'quotation_app_id'    => $quotation_app_id,
			'action'              => $action,
			'invoice_no'          => $invoiceno,
			'old_status'          => $old_status,
			'new_status'          => $new_status,
			'changed_fields'      => $changed_fields,
			'user_id'             => $user_id,

			'synced'              => $synced,
			'created_at'          => date('Y-m-d H:i:s')
		]);
	}




	//Sync data		
	function sync_data($user_id, $data = array())
	{

		$ack_quotations = array();
		if (@$data) {
			foreach ($data as $d) {
				$mail_send = false;



				try {
					if (@$d['confirm'] == 1 || @$d['status'] == "send quotation") {
						$mail_send = true;
					}

					$server_id = $this->api_quotation_insert_row($user_id, $d, $mail_send);
					if ($server_id > 0) {
						if (@$d['confirm'] == 1) {

							$this->servicemodel->send_order_to_blindata($d, $user_id);
						}

						//$ack_quotations[] = $server_id;
						if ($mail_send == true) {
							$this->servicemodel->send_quotation_notification_service($server_id, false);
						}
						$pdf_url = $this->quotationmodel->getQuotationPdfUrl($server_id);;
						$ack_quotations[] = array('id' => @$d['id'], 'server_id' => $server_id, 'pdfUrl' => $pdf_url);
					}
				} catch (Exception $e) {
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
								'quantity' => @$win['quantity'] ?: "",
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
								'priceBand' => @$win['priceBand'] ?: "",
								'priceBandType' => @$win['priceBandType'] ?: "",
								'priceBandVersion' => @$win['priceBandVersion'] ?: "",
								'subProductType' => @$win['subProductType'] ?: 0,
								'isTurned' => @$win['isTurned'] ?: "",
								'priceEdit' => @$win['priceEdit'] ?: 0
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
							'quantity' => @$ext['quantity'] ?: 0,
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

	function user_signature_upload($image)
	{

		if ($image) {


			$file = uniqid('', true) . '.jpeg';


			$upload_dir = APPPATH . '../uploads/quotation/';



			// Make sure upload directory exists
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0755, true);
			}

			$path = $upload_dir . $file;



			// Save the base64 image data to file
			$saved = $this->base64_to_jpeg('data:image/jpeg;base64,' . $image, $path);


			if ($saved) {
				return $file;
			} else {
				// Log or handle error
				return false;
			}
		} else {
			return false;
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

	function get_quotations_admin($filter = array(), $limit = 0, $offset = 0)
	{

		switch ($this->input->get('range')) {

			/* ========== THIS ========= */

			case 'this_week':
				$filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday this week'));
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;

			case 'this_month':
				$filter['from_date'] = date('Y-m-01 00:00:00');
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;

			case 'this_year':
				$filter['from_date'] = date('Y-01-01 00:00:00');
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;


			/* ========== PAST ========= */

			case 'week':
				// Previous calendar week (Mon–Sun)
				$filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday last week'));
				$filter['to_date']   = date('Y-m-d 23:59:59', strtotime('sunday last week'));
				break;

			case 'month':
				// Previous calendar month
				$filter['from_date'] = date('Y-m-01 00:00:00', strtotime('first day of last month'));
				$filter['to_date']   = date('Y-m-t 23:59:59', strtotime('last day of last month'));
				break;

			case 'year':
				// Previous calendar year
				$lastYear = date('Y') - 1;
				$filter['from_date'] = $lastYear . '-01-01 00:00:00';
				$filter['to_date']   = $lastYear . '-12-31 23:59:59';
				break;
		}

		if ($this->input->get('from')) {
			$this->db->where('a.created_date>=', date('Y-m-d', strtotime($this->input->get('from'))));
		} else {
			//$default_expiry_from_date = date('Y-m-d', strtotime('-20 day'));
			//$this->db->where('a.updated_date >=', $default_expiry_from_date);
		}
		if ($this->input->get('to')) {
			$toDate = date('Y-m-d 23:59:59', strtotime($this->input->get('to')));
			$this->db->where('a.created_date <=', $toDate);
		}
		if (@$this->input->get('sales_person') > 0) {
			$this->db->where('a.sales_person', $this->input->get('sales_person'));
		}

		if ((int) $this->input->get('company') > 0) {
			$this->db->where('u.id', (int) $this->input->get('company'));
		} elseif (!empty($filter['company'])) {
			$this->db->where_in('u.id', (array) $filter['company']);
		}
		if ($this->input->get('customer_phone') != "") {

			$customer_phone = trim($this->input->get('customer_phone'));


			$customer_phone = preg_replace('/\D/', '', $customer_phone);


			if (substr($customer_phone, 0, 3) == '971') {
				$customer_phone = substr($customer_phone, 3);
			}

			$customer_phone = substr($customer_phone, -9);
			if (!empty($customer_phone)) {

				$this->db->where(
					"RIGHT(REPLACE(REPLACE(REPLACE(a.customer_phone,'+',''),' ',''),'-',''), 9) LIKE '%" . $customer_phone . "'",
					NULL,
					false
				);
			}
		}
		if (@$this->input->get('status') != "") {

			$status = strtolower($this->input->get('status'));
			if ($status == 'confirmed order') {
				// Include both confirmed and balance paid
				$this->db->where_in('status', ['confirmed order', 'balance paid']);
			} elseif (!empty($status)) {
				$this->db->where('status', $status);
			}
		}
		if (@$this->input->get('confirm') != "") {


			$this->db->like('a.confirm', $this->input->get('confirm'));
		}
		if (!empty($filter['from_date'])) {
			$this->db->where('a.created_date >=', $filter['from_date']);
		}
		if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
		}
		if ($this->input->get('invoiceno')) {
			$invoiceno = $this->input->get('invoiceno');
			$this->db->like('a.invoiceno', $invoiceno);
		}
		if ($this->input->get('customer_name')) {
			$customer_name = $this->input->get('customer_name');
			$this->db->where('a.customer_name', $customer_name);
		}


		$this->db->select('a.*,c.address as cust_address,c.name as cust_name,c.email as cust_email,c.phone as cust_phone,u.id as company_id,u.name as company_name,u.image as company_image,u.address as company_address,u.phone as company_phone,u.company_email as company_email,sp.name as sp_name')
			->join('sales_person sp', 'sp.id=a.sales_person', 'left')
			->join('admin_users u', 'u.id=sp.company', 'left')
			->join('customer c', 'c.id=a.customer', 'left');
		$this->db->where('a.deleted_at', NULL);
		$this->db->order_by('a.created_date', 'desc');
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}


		$data = $this->gets_all()->result_array();
		// echo "<pre>";
		// print_r($data);exit;
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
	public function get_quotations_admin_count($filter = [])
	{
		/* -------- Range filter -------- */
		switch ($this->input->get('range')) {

			/* ========== THIS ========= */

			case 'this_week':
				$filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday this week'));
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;

			case 'this_month':
				$filter['from_date'] = date('Y-m-01 00:00:00');
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;

			case 'this_year':
				$filter['from_date'] = date('Y-01-01 00:00:00');
				$filter['to_date']   = date('Y-m-d 23:59:59');
				break;


			/* ========== PAST ========= */

			case 'week':
				// Previous calendar week (Mon–Sun)
				$filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday last week'));
				$filter['to_date']   = date('Y-m-d 23:59:59', strtotime('sunday last week'));
				break;

			case 'month':
				// Previous calendar month
				$filter['from_date'] = date('Y-m-01 00:00:00', strtotime('first day of last month'));
				$filter['to_date']   = date('Y-m-t 23:59:59', strtotime('last day of last month'));
				break;

			case 'year':
				// Previous calendar year
				$lastYear = date('Y') - 1;
				$filter['from_date'] = $lastYear . '-01-01 00:00:00';
				$filter['to_date']   = $lastYear . '-12-31 23:59:59';
				break;
		}

		/* -------- Date filters -------- */
		if ($this->input->get('from')) {
			$this->db->where(
				'a.created_date >=',
				date('Y-m-d', strtotime($this->input->get('from')))
			);
		}

		if ($this->input->get('to')) {

			$toDate = date('Y-m-d', strtotime($this->input->get('to') . ' +1 day'));
			$this->db->where('a.created_date <', $toDate);
			// $this->db->where(
			//     'a.created_date <=',
			//     date('Y-m-d', strtotime($this->input->get('to')))
			// );
		}

		/* -------- Sales person -------- */
		if ((int)$this->input->get('sales_person') > 0) {
			$this->db->where('a.sales_person', (int)$this->input->get('sales_person'));
		}

		/* -------- Company -------- */
		if ((int)$this->input->get('company') > 0) {
			$this->db->where('u.id', (int)$this->input->get('company'));
		} elseif (!empty($filter['company'])) {
			$this->db->where_in('u.id', (array)$filter['company']);
		}

		if ($this->input->get('customer_phone') != "") {

			$customer_phone = trim($this->input->get('customer_phone'));


			$customer_phone = preg_replace('/\D/', '', $customer_phone);


			if (substr($customer_phone, 0, 3) == '971') {
				$customer_phone = substr($customer_phone, 3);
			}

			$customer_phone = substr($customer_phone, -9);

			if (!empty($customer_phone)) {

				$this->db->where(
					"RIGHT(REPLACE(REPLACE(REPLACE(a.customer_phone,'+',''),' ',''),'-',''), 9) LIKE '%" . $customer_phone . "'",
					NULL,
					false
				);
			}
		}

		if ($this->input->get('customer_name')) {
			$customer_name = $this->input->get('customer_name');
			$this->db->where('a.customer_name', $customer_name);
		}

		/* -------- Status -------- */
		if ($this->input->get('status') != "") {
			$status = strtolower($this->input->get('status'));

			if ($status === 'confirmed order') {
				$this->db->where_in('status', ['confirmed order', 'balance paid']);
			} else {
				$this->db->where('status', $status);
			}
		}

		/* -------- Confirm -------- */
		if ($this->input->get('confirm') != "") {
			$this->db->like('a.confirm', $this->input->get('confirm'));
		}

		/* -------- Range date -------- */
		if (!empty($filter['from_date'])) {
			$this->db->where('a.created_date >=', $filter['from_date']);
		}
		if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
		}
		if ($this->input->get('invoiceno')) {
			$invoiceno = $this->input->get('invoiceno');
			$this->db->like('a.invoiceno', $invoiceno);
		}


		/* -------- Base joins -------- */
		$this->db
			->from('quotation a')
			->join('sales_person sp', 'sp.id = a.sales_person', 'left')
			->join('admin_users u', 'u.id = sp.company', 'left')
			->join('customer c', 'c.id = a.customer', 'left')
			->where('a.deleted_at', NULL);

		/* -------- Count -------- */
		return $this->db->count_all_results();
	}


	function api_get_transfer_data($user_id = 0)
	{
		$this->db->where(array('sales_person' => $user_id, 'transfer' => 1))
			->select('id,customer,customer_name,customer_phone,customerEmail,customer_address as customerAddress,invoiceno,total,discount,discountPercentage,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,is_remarks,priority,confirm,insDate,insFromTime,insToTime,source,customerRef,customerTrn,deal_id');
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
		$this->load->driver('cache', ['adapter' => 'file']);
		$exp = date("Y-m-d", strtotime("-12 month"));
		$this->db->where(array('sales_person' => $user_id, 'created_date >=' => $exp, 'deleted_at' => NULL))
			->select('id,customer,customer_name,customer_phone,customer_address as customerAddress,source,deal_id,invoiceno,pdfUrl,total,discount,discountPercentage,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,is_remarks,priority,confirm,status,signature, (sub_total - advance) as balance,insDate,insFromTime,insToTime,balancePaymentType,customerRef,customerTrn');
		$data = $this->gets_data()->result_array();
		$i = 0;
		foreach ($data as $d) {
			$signature_path = $d['signature'] ? FCPATH . 'uploads/quotation/' . $d['signature'] : '';

			if (!empty($d['signature']) && file_exists($signature_path)) {
				$cache_key = 'signature_base64_' . md5($d['signature']);
				$cached_signature = $this->cache->get($cache_key);

				if ($cached_signature) {
					$data[$i]['signature'] = $cached_signature;
				} else {
					$image_data = file_get_contents($signature_path);
					$base64 = 'data:image/jpeg,' . base64_encode($image_data);

					$this->cache->save($cache_key, $base64, 86400); // cache for 1 day
					$data[$i]['signature'] = $base64;
				}
			} else {
				$data[$i]['signature'] = NULL;
			}

			$data[$i]['created_time'] = strtotime($d['created_time']) * 1000;
			$data[$i]['update_time'] = strtotime($d['update_time']) * 1000;
			$data[$i]['rooms'] = $this->api_get_sub_rooms_quotation($d['id']);
			$i++;
		}
		return $data;
	}
	function get_data_sync_all($user_id = 0)
	{
		$this->load->driver('cache', ['adapter' => 'file']);

		$syncRow = $this->db
			->where('user_id', $user_id)
			->where('table_name', 'quotation')

			->order_by('last_synch_date', 'DESC')
			// optional but recommended
			->limit(1)
			->get('user_sync_track')
			->row_array();
		if (empty($syncRow) || empty($syncRow['last_synch_date'])) {

			return [
				'quotation' => [],
				'quotation_delete' => []
			];
		}

		$lastSyncDate = $syncRow['last_synch_date'];

		$this->db->where('sales_person', $user_id);
		$this->db->where('updated_date >', $lastSyncDate);


		$this->db->select('
        id,
        customer,
        customer_name,
        customer_phone,
		customerEmail,
		customer_address as customerAddress,
		source,
		deal_id,
		sales_person as user_id,
        invoiceno,
        total,
        discount,
		discountPercentage,
		pdfUrl,
        advance,
        vat,
        sub_total,
        payment_type,
        created_date AS created_time,
        updated_date AS update_time,
        remarks,
        is_remarks,
        priority,
        confirm,
        status,
        signature,

        (sub_total - advance) AS balance,
        insDate,
        insFromTime,
        insToTime,
        balancePaymentType,
        customerRef,
        customerTrn
    ');

		$quotation_update = $this->gets_data()->result_array();
		// print_r($this->db->last_query());
		// exit;

		foreach ($quotation_update as $i => $d) {

			// Signature handling
			if (!empty($d['signature'])) {
				$signature_path = FCPATH . 'uploads/quotation/' . $d['signature'];

				if (file_exists($signature_path)) {
					$cache_key = 'signature_' . md5($d['signature']);
					$cached = $this->cache->get($cache_key);

					if (!$cached) {
						$image_data = file_get_contents($signature_path);
						$cached = 'data:image/jpeg,' . base64_encode($image_data);
						$this->cache->save($cache_key, $cached, 86400);
					}

					$quotation_update[$i]['signature'] = $cached;
				} else {
					$quotation_update[$i]['signature'] = null;
				}
			} else {
				$quotation_update[$i]['signature'] = null;
			}

			// Time → milliseconds
			$quotation_update[$i]['created_time'] = strtotime($d['created_time']) * 1000;
			$quotation_update[$i]['update_time']  = strtotime($d['update_time']) * 1000;

			// Rooms
			$quotation_update[$i]['rooms'] = $this->api_get_sub_rooms_quotation($d['id']);
		}


		$this->db->select('id');
		$this->db->where('deleted_at IS NOT NULL', null, false);
		// $this->db->where('deleted_at >', $lastSyncDate);
		$this->db->where('sales_person', $user_id);

		$deleted_direct = $this->gets_data_delete()->result_array();



		$this->db->select('quotation AS id');
		$this->db->where('`from`', $user_id);
		$this->db->where('created_date >', $lastSyncDate);

		$deleted_transfer = $this->db->get('quotation_transfer')->result_array();

		$all_deleted = array_merge($deleted_direct, $deleted_transfer);


		$quotation_delete = array_values(array_unique(array_column($all_deleted, 'id')));

		return [
			'quotation' => $quotation_update,
			'quotation_delete' => $quotation_delete
		];
	}
	function jpeg_to_base64($file_path)
	{
		if (file_exists($file_path)) {
			$image_data = file_get_contents($file_path);
			$base64 = base64_encode($image_data);
			return 'data:image/jpeg;base64,' . $base64;
		} else {
			return false;
		}
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
			->select('id,customer,customer_name,customer_phone,total,discount,advance,vat,sub_total,payment_type,created_date as created_time,updated_date as update_time,remarks,is_remarks,priority,confirm,status,customerRef,customerTrn');
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
			->select('id,room,window_name,quantity,product,product_name,product_price,total,width,height,chain_drop, unit,remarks,note,discount,priceBand,priceBandType,priceBandVersion,subProductType,customPrice,noteOnline,"1" as activeItem,measurmenttype,isTurned,priceEdit');
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
			->select('id,window,extra,sub_extra,sub_sub_extra,sub_sub_sub_extra,extra_name,sub_extra_name,sub_sub_extra_name,sub_sub_sub_extra_name,price,customPrice,quantity');
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
			->select('a.id,a.room,a.window_name,a.quantity,a.product,a.product_name,a.product_price,a.total,a.width,a.height,a.chain_drop, a.unit,a.remarks,a.note,a.discount,a.priceBand,a.priceBandType,a.priceBandVersion,a.subProductType,a.customPrice,a.noteOnline,m.name as measuring_type,a.measurmenttype,a.activeItem,a.isTurned as isTurned,a.priceEdit')
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
			->select('id,window,extra,sub_extra,sub_sub_extra,sub_sub_sub_extra,extra_name,sub_extra_name,sub_sub_extra_name,sub_sub_sub_extra_name,price,customPrice,quantity');
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
		$this->db->select('COUNT(a.id) AS total');
		$this->db->from($this->table_name . ' a');

		// Company filter
		if (!empty($filter['company'])) {
			if (is_array($filter['company'])) {
				$this->db->where_in('sp.company', $filter['company']);
			} else {
				$this->db->where('sp.company', $filter['company']);
			}
		}


		if (!empty($filter['from_date'])) {
			$this->db->where('a.created_date >=', $filter['from_date']);
		}

		if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
		}



		$this->db->where('a.deleted_at IS NULL', null, false);
		$this->db->where('a.confirm', 0);

		// Joins
		$this->db->join('sales_person sp', 'sp.id = a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id = sp.company', 'left');

		$query = $this->db->get();
		// print_r($this->db->last_query());
		// exit;
		return (int) $query->row()->total;
	}



	function get_confirmed_quotation_count($filter = [])
	{
		if (isset($filter['company']) && !empty($filter['company'])) {
			if (is_array($filter['company'])) {
				$this->db->where_in('sp.company', $filter['company']);
			} else {
				$this->db->where('sp.company', $filter['company']);
			}
		}
		if (!empty($filter['from_date'])) {
			$this->db->where('a.created_date >=', $filter['from_date']);
		}
		if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
		}


		$this->db->where('a.deleted_at IS NULL', null, false);
		$this->db->where('a.confirm', 1);
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$q = $this->db->get($this->table_name . ' a');
		return $q->num_rows();
	}

	function get_sales_amount($filter = [])
	{
		if (isset($filter['company']) && !empty($filter['company'])) {
			if (is_array($filter['company'])) {
				$this->db->where_in('sp.company', $filter['company']);
			} else {
				$this->db->where('sp.company', $filter['company']);
			}
		}
		if (!empty($filter['from_date'])) {
			$this->db->where('a.created_date >=', $filter['from_date']);
		}
		if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
		}

		$this->db->where('a.confirm', 1);
		$this->db->where('a.deleted_at', NULL);

		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$q = $this->db->select_sum("sub_total")->get($this->table_name . ' a');
		$res = $q->row_array();
		return $res['sub_total'];
	}


	function create_quotation_request($post)
	{
		$path = APPPATH . '../uploads/log/quotation-request/';
		$name = "qtation-request-" . date("Y-m-d-H-i-s") . ".json";
		$myfile = fopen($path . $name, "w");
		fwrite($myfile, $post);
		fclose($myfile);
	}
	public function get_status()
	{
		$this->db->distinct();
		$this->db->select('status');
		$this->db->from('quotation');
		$query = $this->db->get();

		return $query->result(); // returns array of objects with 'status'
	}

	public function getQuotationPdfUrl($quotation_id)
	{
		$this->db->select('pdfUrl'); // change column name if different
		$this->db->from('quotation'); // your table name
		$this->db->where('id', $quotation_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->pdfUrl;
		}

		return false;
	}
	public function updateWindowStatus($window_id, $room_id, $product, $status)
	{
		$this->db->where('id', $window_id);
		$this->db->where('room', $room_id);
		$this->db->where('product', $product);

		$this->db->update('quotation_rooms_windows', [
			'activeItem' => $status
		]);
	}
	
}
