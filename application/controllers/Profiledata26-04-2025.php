<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
defined('BASEPATH') or exit('No direct script access allowed');

class Profiledata extends CI_Controller
{
	var $token;
	var $user_id;
	var $user_type;
	var $user_name = '';
	var $admin_id;
	var $access_token_key = '';
	var $token_valid_key = '';
	var $token_valid_value = "5UNF+;B62Jn[eNq+65bQ.T?#P7@:+@%?)>wr3yrt:YS!sp=jb>*Hvg<*<zA*@AuF>y3*!@-)#]f9C>+ug@[@y#f2UA>:ntNA7nmMqaXZr,b%7d)C@y@ga(25Jum)z{Peg{tFh#A!VW!&{a%ZR#z@!;d6AcbjXP2**<G;U:>fS}Zs!z4Le5@(M@%fUQ>GY<,N@;@#@dqGx5Gr#8fAka35Rev;7={T#;e&s=&`fzg}~CkHW7eBL)#UuzpH;vGgH=GB";

	public function __construct()
	{
		parent::__construct();

		$this->token_valid_key = $this->config->item('token_valid');
		$this->access_token_key = $this->config->item('access_tokken');
	
		$this->load->model(array('tokkenmodel', 'salespersonmodel', 'customermodel', 'vendormodel', 'producttypemodel', 'roomtypemodel', 'extrasmodel', 'pricebandversionmodel', 'pricebandmodel', 'productmodel', 'quotationmodel', 'configurationmodel', 'quotationtransfermodel', 'servicemodel', 'usersmodel', 'measurementtypemodel'));
		$headers = apache_request_headers();
		$token = @$headers[$this->access_token_key] ?: @$headers[ucfirst($this->access_token_key)];
		$cur = strtotime(date('Y-m-d H:i:s'));
		$num = $this->tokkenmodel->check_ip();
						// echo json_encode($headers);
						// exit;
		if ($num > $this->config->item('limit_request_attempts')) {
			$this->tokkenmodel->record_ip();
			$this->load->view('welcome_message');
			exit;
		} else {
			$tokken_value = @$headers[$this->token_valid_key] ?: @$headers[ucfirst($this->token_valid_key)];
	

			
			if ($this->token_valid_value == $tokken_value) {
				if ($token != '') {
				
					$chk = array('access_tokken' => $token, 'access_token_expire >=' => $cur);
					$this->db->where($chk);
					$cuser = $this->tokkenmodel->gets_data()->row();
					if ($cuser) {
						$this->db->where('id', $cuser->user_id)->where('active', 1);
						$user = $this->salespersonmodel->gets_data()->row_array();
						if ($user) {
							$this->admin_id = $user['company'];
							$data['status'] = true;
							$data['message'] = 'OK';
							$du['access_token_expire'] = strtotime(date('Y-m-d H:i:s', strtotime("+180 days")));
							$this->token = $token;
							$whr['id'] = $cuser->id;
							$this->tokkenmodel->save($du, $cuser->id);
							$this->user_id = $cuser->user_id;
							$this->user_type = 'employee';
							$this->user_name = @$user['name'];
						} else {
							$this->tokkenmodel->record_ip();
							$data['status'] = false;
							$data['message'] = 'Contact Admin';
						}
					} else {
						$this->tokkenmodel->record_ip();
						$data['status'] = false;
						$data['message'] = 'Invalid Token';
					}
				} else {
					$this->tokkenmodel->record_ip();
					$data['status'] = false;
					$data['message'] = 'Invalid Token';
				}
				if (!@$data['status']) {
					echo json_encode($data);
					exit;
				}
			} else {
				$this->tokkenmodel->record_ip();
				$this->load->view('welcome_message');
				exit;
			}
		}
	
	}



	function check_login()
	{
		$data['user_id'] = $this->user_id;
		$data['user'] = $this->salespersonmodel->get_current_user($this->user_id);
		$data['status'] = true;
		$data['action_status'] = true;
		echo json_encode($data);
	}


	function quotation_sync()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		$data['post'] = $post;
		$data['user_id'] = $this->user_id;
		$data['quotation_ack'] = $this->quotationmodel->sync_data($this->user_id, $post['sync_quotations']);
		$data['status'] = true;
		$data['action_status'] = true;
		echo json_encode($data);
	}

	function initial_sync_data()
	{
		//echo "DFDFDf";die();
		$data['user_id'] = $this->user_id;
		//Customers
		$data['customers'] = $this->customermodel->api_get_init_sync_data($this->user_id);


		//Customers
		//Quotations
		$data['quotations'] = $this->quotationmodel->api_get_init_sync_data($this->user_id);
		//Quotations
		//Transfers
		$data['quotation_transfers'] = $this->quotationtransfermodel->api_get_init_sync_data($this->user_id);
		$data['status'] = true;
		$data['action_status'] = true;
		echo json_encode($data);
	}






	function sync_data()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		// $log_file = APPPATH  . 'application/logs/sync_log.txt'; 
		// log_message('info', json_encode($post));
		$data = array();
		$data['user_id'] = $this->user_id;
		$data['user'] = $this->salespersonmodel->get_current_user($this->user_id);
		$data['vat_addtion'] = 0.0;
		$this->db->order_by('id', 'desc')->select('vat_addition');
		if ($vat = $this->configurationmodel->gets_data()->row_array()) {
			$data['vat_addtion'] = (int)$vat['vat_addition'];
		}
		$data['company_address'] = "";
		$data['logo'] = "";
		$data['branding_colour_code'] = "";
		$data['branding_colour_code_secondary'] = "";
		if ($company = $this->usersmodel->get_row(@$data['user']['company'])) {
			$data['logo'] = @$company->image ?: "";
			$data['branding_colour_code'] = @$company->branding_colour_code ?: "";
			$data['company_address'] = $company->address ?: "";
			$data['branding_colour_code_secondary'] = @$company->branding_colour_code_secondry ?: "";
		}
		$data['logo_base_url'] = base_url() . 'uploads/users/';
		/****** 
			Meta Data
		 ****/
		//Extras			
		$exdt = $this->extrasmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $exdt);
		//Extras
		//Product type Extras
		$ptdt = $this->producttypemodel->get_data_sync_all_extras($this->user_id);
		$data = array_merge($data, $ptdt);
		//Product type Extras
		///Room Type
		$rmdt = $this->roomtypemodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $rmdt);
		//Room type

		//Price Band	
		$pbbdt = $this->pricebandmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $pbbdt);
		//Priceband
		//Priceband Prices
		$pbpdt = $this->pricebandmodel->get_data_sync_all_price($this->user_id);
		$data = array_merge($data, $pbpdt);
		//Priceband Prices	
		///product
		$psddt = $this->productmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $psddt);
		//Product
		///Sales Person
		$spdt = $this->salespersonmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $spdt);
		//Sales Person

		//Vendor	
		$vdt = $this->vendormodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $vdt);
		//Vendor

		//Product type ,sub type
		$ptdt = $this->producttypemodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $ptdt);
		//Product type ,sub type
		//Pricebandversion	
		$pbdt = $this->pricebandversionmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $pbdt);
		//Measurement type
		$mtdt = $this->measurementtypemodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $mtdt);

		/****** 
			Meta Data
		 ****/

		//Customer
		if (@$post['sync_customer']) {
			$cdt=$this->customermodel->sync_data($this->user_id, $post['sync_customer']);
			$data = array_merge($data, $cdt);
		}
		//Transfer quotation ack
		if (@$post['ack_quotations']) {
			$this->quotationmodel->api_make_transfered($this->user_id, $post['ack_quotations']);
		}
		//Transfer Quotations
		$data['quotation_transfer'] = $this->quotationmodel->api_get_transfer_data($this->user_id);
		//Quotations
		if (@$post['sync_quotations']) {
			$dt = $this->quotationmodel->sync_data($this->user_id, $post['sync_quotations']);
			$data = array_merge($data, $dt);
		}
		//Quotation Transfer Return Ack
		if (@$post['sync_quotation_transfer']) {
			$this->quotationtransfermodel->make_transfer_synced($post['sync_quotation_transfer']);
		}
		//Return sync quotation
		$data['quotation_return_sync'] = $this->quotationmodel->api_get_quotation_sync_data_return($this->user_id);
		//Quotation Transfer Status Change
		$data['quotation_transfers_sync'] = $this->quotationtransfermodel->get_sync_transfers_subjects($this->user_id);
		//Quotation Transferred Data
		//$data['quotation_transferred_data']=$this->quotationmodel->api_get_quotation_transferred_data($this->user_id);
		//Pricebandversion
		//$data['customers']= $this->customermodel->gets_customers_api_sync($this->user_id);
		//$data['sales_mans']=$this->salespersonmodel->get_other_users($this->user_id);
		$data['status'] = true;
	  // Convert data to JSON and write to log file
	//   $log_message = "[" . date("Y-m-d H:i:s") . "] " . json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
	//   file_put_contents($log_file, $log_message, FILE_APPEND);
	
		echo json_encode($data);
	}

	function transfer_request()
	{

		$post = json_decode(file_get_contents('php://input'), true);
		//log_message('info', json_encode($post));
		$data['transfer_status'] = false;
		$quotation_id = 0;
		$customer_id = 0;
		$data['transfer_id'] = 0;
		$data['sales_person_name'] = "";
		$custName = "";
		$custPhone = "";
		$custEmail = "";
		if (@$post['customer']) {
			$customer_id = $this->customermodel->api_customer_insert_row($this->user_id, $post['customer']);
		}
		if (@$post['quotation']) {
			$quotation_id = $this->quotationmodel->api_quotation_insert_row($this->user_id, $post['quotation']);
		}
		if ($quotation_id > 0) {
			$this->db->where(array('from' => $this->user_id, 'quotation' => $quotation_id, 'transfer_status !=' => 'Rejected'));
			if (!$this->quotationtransfermodel->gets_data()->row()) {
				if ($user = $this->salespersonmodel->get_row(@$post['sales_person'])) {
					if ($user->active == 1) {
						$insert = array(
							'from' => $this->user_id,
							'from_name' => $this->user_name,
							'to' => @$post['sales_person'],
							'to_name' => $user->name,
							'quotation' => $quotation_id,
							'transfer_status' => 'Pending',
							'created_date' => date('Y-m-d H:i:s'),
							'status_date' => date('Y-m-d H:i:s'),
							'reason' => @$post['reason'],
							'synched' => 1,
							'custName' => @$post['quotation']['customer_name'] ?: "",
							'custPhone' => @$post['quotation']['customer_phone'] ?: "",
							'custEmail' => @$post['quotation']['custEmail'] ?: ""
						);
						if ($cur_user = $this->salespersonmodel->get_row($this->user_id)) {
							$insert['from_name'] = $cur_user->name;
						}
						$data['transfer_id'] = $this->quotationtransfermodel->save($insert);
						$data['transfer_status'] = true;
					
						// $data['from_user_id']=$this->user_id;

						$data['sales_person_name'] = $user->name;
					} else {
						$data['message'] = "Sales person not active";
					}
				} else {
				
			
					$data['message'] = "Sales person not found";
				}
			} else {
				$data['message'] = "Already transfer requested";
			}
		} else {
			$data['message'] = "quotation insert failed";
		}
		$data['quotation_id'] = $quotation_id;
		$data['customer_id'] = $customer_id;
		$user= $this->tokkenmodel->get_token($this->user_id);
		// print_r($user);
		$data['push_token']	= $user->push_token;
		$data['status'] = true;
		log_message('info', json_encode($data)); 
		echo json_encode($data);
	}






	function acknowledge_sync()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		if (@$post['quotation_return_sync']) {
			////Acknowledge return data quotation
			$this->quotationmodel->sync_ack_return_quotation($post['quotation_return_sync']);
		}
		//Extras	
		if (@$post['sync_acknowledge_extras']) {
			////Acknowledge vendor synced local to server
			$this->extrasmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_extras']);
		}
		if (@$post['sync_delete_extras']) {
			////Delete sync data
			$this->extrasmodel->delete_sync_data($this->user_id, @$post['sync_delete_extras']);
		}
		//Extras
		//Product type Extras
		if (@$post['sync_acknowledge_producttypeextras']) {
			////Acknowledge vendor synced local to server
			$this->producttypemodel->acknowledge_sync_data_extras($this->user_id, @$post['sync_acknowledge_producttypeextras']);
		}
		if (@$post['sync_delete_producttypeextras']) {
			////Delete sync data
			$this->producttypemodel->delete_sync_data_extras($this->user_id, @$post['sync_delete_producttypeextras']);
		}
		//Product type Extras
		///Room Type
		if (@$post['sync_acknowledge_roomtype']) {
			////Acknowledge vendor synced local to server
			$this->roomtypemodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_roomtype']);
		}
		if (@$post['sync_delete_roomtype']) {
			////Delete sync data
			$this->roomtypemodel->delete_sync_data($this->user_id, @$post['sync_delete_roomtype']);
		}
		//Price Band	
		if (@$post['sync_acknowledge_priceband']) {
			////Acknowledge vendor synced local to server
			$this->pricebandmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_priceband']);
		}
		if (@$post['sync_delete_priceband']) {
			////Delete sync data
			$this->pricebandmodel->delete_sync_data($this->user_id, @$post['sync_delete_priceband']);
		}
		//Priceband
		//Priceband Prices
		if (@$post['sync_acknowledge_priceband_price']) {
			////Acknowledge vendor synced local to server
			$this->pricebandmodel->acknowledge_sync_data_price($this->user_id, @$post['sync_acknowledge_priceband_price']);
		}
		if (@$post['sync_delete_priceband_price']) {
			////Delete sync data
			$this->pricebandmodel->delete_sync_data_price($this->user_id, @$post['sync_deletepriceband_price']);
		}
		//Priceband Prices	
		///product
		if (@$post['sync_acknowledge_product']) {
			////Acknowledge Product synced local to server
			$this->productmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_product']);
		}
		if (@$post['sync_delete_product']) {
			////Delete sync data
			$this->productmodel->delete_sync_data($this->user_id, @$post['sync_delete_product']);
		}
		//Product
		///Sales Person
		if (@$post['sync_acknowledge_salesperson']) {
			////Acknowledge Product synced local to server
			$this->salespersonmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_salesperson']);
		}
		if (@$post['sync_delete_salesperson']) {
			////Delete sync data
			$this->salespersonmodel->delete_sync_data($this->user_id, @$post['sync_delete_salesperson']);
		}
		//Vendor	
		if (@$post['sync_acknowledge_vendor']) {
			////Acknowledge vendor synced local to server
			$this->vendormodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_vendor']);
		}
		if (@$post['sync_delete_vendor']) {
			////Delete sync data
			$this->vendormodel->delete_sync_data($this->user_id, @$post['sync_delete_vendor']);
		}
		//Product type ,sub type
		if (@$post['sync_acknowledge_producttype']) {
			////Acknowledge vendor synced local to server
			$this->producttypemodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_producttype']);
		}
		if (@$post['sync_delete_producttype']) {
			////Delete sync data
			$this->producttypemodel->delete_sync_data($this->user_id, @$post['sync_delete_producttype']);
		}
		//Measurement type
		if (@$post['sync_acknowledge_measuringtype']) {
			////Acknowledge synced local to server
			$this->measurementtypemodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_measuringtype']);
		}
		if (@$post['sync_delete_measuringtype']) {
			////Delete sync data
			$this->measurementtypemodel->delete_sync_data($this->user_id, @$post['sync_delete_measuringtype']);
		}
		//Product type ,sub type
		//Pricebandversion	
		if (@$post['sync_acknowledge_pricebandversion']) {
			////Acknowledge vendor synced local to server
			$this->pricebandversionmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_pricebandversion']);
		}
		if (@$post['sync_delete_pricebandversion']) {
			////Delete sync data
			$this->pricebandversionmodel->delete_sync_data($this->user_id, @$post['sync_delete_pricebandversion']);
		}
		$this->quotationmodel->sync_ack_make_quotations_transfered($this->user_id);
		$data['status'] = true;
		$data['action_status'] = true;
		echo json_encode($data);
	}


	function insert_quotation()
	{


		$post = json_decode(file_get_contents('php://input'), true);
		// log_message('info', json_encode($post));
		$this->quotationmodel->create_quotation_request(json_encode($post));

		$quotation_id = $this->quotationmodel->api_save_quotation_row($this->user_id, $post);
		if ($quotation_id > 0) {
			$data['action_status'] = true;
			$data['quotationId'] = $quotation_id;
			if (@$post['quotation']['status'] == "Quotation Send") {
				log_message('info', "quot send");
				//Send quotation send
				try {
					@$this->send_quotation_service_only_send($quotation_id);
				} catch (Exception $e) {
				}
				$this->quotationmodel->delete_quotation_data($quotation_id);
				$data['quotationId'] = "";
			} else {
				try {
					// @$this->send_quotation_service($quotation_id);
					if (@$post['quotation']['status'] == "Confirm order") {
						//$this->servicemodel->send_order_to_blindata(@$post["quotation"],$this->user_id);
					}
				} catch (Exception $e) {
				}
			}
		} else {
			$data['action_status'] = false;
			$data['message'] = "Quotation Insert Failled";
		}
		$data['status'] = true;
		echo json_encode($data);
	}

	function send_email_user()
	{
		$post = $this->input->post();
		$data['action_status'] = false;
		if (@$post['customer_email'] != "" && @$post['subject'] != "" && @$post['message'] != "") {
			$post['sp_id'] = $this->user_id;
			$data['action_status'] = true;
			try {
				@$this->servicemodel->send_mail_customer($post);
			} catch (Exception $e) {
			}
		} else {
			$data['message'] = "Fill all data";
		}
		$data['status'] = true;
		echo json_encode($data);
	}


	//logout user
	function logout_user()
	{
		$this->tokkenmodel->delete_user_tokkens($this->user_id);
		///Delete Existing Data
		$this->tokkenmodel->delete_user_datas($this->user_id);
		$data['status'] = true;
		$data['user_id'] = $this->user_id;
		echo json_encode($data);
	}
	//get user
	function get_user()
	{
		$data['user'] = $this->salespersonmodel->get_current_user($this->user_id);
		$data['status'] = true;
		$data['user_id'] = $this->user_id;
		echo json_encode($data);
	}

	function send_notification_user($title, $message, $user_id, $colour, $store = 0)
	{
		$user = $this->usermodel->get_row($user_id);
		if ($store == 1) {
			$ins = array('title' => $title, 'notification' => $message, 'user' => $user_id, 'created_date' => date('Y-m-d H:i:s'), 'colour' => $colour);
			$this->notificationmodel->save($ins);
		}
		if ($user && $user->firebase_token) {
			$fcmApiKey = $this->config->item('site_firebase_api_key'); //App API Key(This is google cloud messaging api key not web api key)
			$url = 'https://fcm.googleapis.com/fcm/send'; //Google URL
			$message = $message; //Message which you want to send
			$title = $title;
			// prepare the bundle
			$registrationIds[0] = $user->firebase_token;
			$msg = array('body' => $message, 'title' => $title);
			$fields = array('to' => $registrationIds[0], 'notification' => $msg);
			$headers = array(
				'Authorization: key=' . $fcmApiKey,
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			// Execute post
			/*if ($result === FALSE) {
						die('Curl failed: ' . curl_error($ch));
					}*/
			// Close connection
			curl_close($ch);
			return $result;
		}
	}
	function mycore_hashing_user($pass = '')
	{
		if ($pass != '') {
			return crypt($this->config->item('user_salt'), hash('sha256', $pass));
		}
	}


	function sync_data_old()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		$data['user_id'] = $this->user_id;
		/*$data['customer_local_sync_ack']=array();
				if(@$post['sync_customer']){
						///Syncing customer from local to server
						$data['customer_local_sync_ack']=$this->customermodel->sync_data_server($this->user_id,$post['sync_customer']);
					}
				if(@$post['sync_acknowledge_customer']){
						//updating acknowledges from local to server
						$this->customermodel->acknowledge_sync_data($this->user_id,$post['sync_acknowledge_customer']);
					}*/

		//Customer
		if (@$post['sync_customer']) {
			$cdt = $this->customermodel->sync_data($this->user_id, $post['sync_customer']);
			$data = array_merge($data, $cdt);
		}
		//Quotations
		if (@$post['sync_quotations']) {
			$dt = $this->quotationmodel->sync_data($this->user_id, $post['sync_quotations']);
			$data = array_merge($data, $dt);
		}
		//Vendor	
		if (@$post['sync_acknowledge_vendor']) {
			////Acknowledge vendor synced local to server
			$this->vendormodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_vendor']);
		}
		if (@$post['sync_delete_vendor']) {
			////Delete sync data
			$this->vendormodel->delete_sync_data($this->user_id, @$post['sync_delete_vendor']);
		}
		$vdt = $this->vendormodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $vdt);
		//Vendor
		//Product type ,sub type
		if (@$post['sync_acknowledge_producttype']) {
			////Acknowledge vendor synced local to server
			$this->producttypemodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_producttype']);
		}
		if (@$post['sync_delete_producttype']) {
			////Delete sync data
			$this->producttypemodel->delete_sync_data($this->user_id, @$post['sync_delete_producttype']);
		}
		$ptdt = $this->producttypemodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $ptdt);
		//Product type ,sub type
		//Extras	
		if (@$post['sync_acknowledge_extras']) {
			////Acknowledge vendor synced local to server
			$this->extrasmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_extras']);
		}
		if (@$post['sync_delete_extras']) {
			////Delete sync data
			$this->extrasmodel->delete_sync_data($this->user_id, @$post['sync_delete_extras']);
		}
		$exdt = $this->extrasmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $exdt);
		//Extras
		//Product type Extras
		if (@$post['sync_acknowledge_producttypeextras']) {
			////Acknowledge vendor synced local to server
			$this->producttypemodel->acknowledge_sync_data_extras($this->user_id, @$post['sync_acknowledge_producttypeextras']);
		}
		if (@$post['sync_delete_producttypeextras']) {
			////Delete sync data
			$this->producttypemodel->delete_sync_data_extras($this->user_id, @$post['sync_delete_producttypeextras']);
		}
		$ptdt = $this->producttypemodel->get_data_sync_all_extras($this->user_id);
		$data = array_merge($data, $ptdt);
		//Product type Extras
		///Room Type
		if (@$post['sync_acknowledge_roomtype']) {
			////Acknowledge vendor synced local to server
			$this->roomtypemodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_roomtype']);
		}
		if (@$post['sync_delete_roomtype']) {
			////Delete sync data
			$this->roomtypemodel->delete_sync_data($this->user_id, @$post['sync_delete_roomtype']);
		}
		$rmdt = $this->roomtypemodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $rmdt);
		//Room type
		//Pricebandversion	
		if (@$post['sync_acknowledge_pricebandversion']) {
			////Acknowledge vendor synced local to server
			$this->pricebandversionmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_pricebandversion']);
		}
		if (@$post['sync_delete_pricebandversion']) {
			////Delete sync data
			$this->pricebandversionmodel->delete_sync_data($this->user_id, @$post['sync_delete_pricebandversion']);
		}
		$pbdt = $this->pricebandversionmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $pbdt);
		//Pricebandversion
		//Price Band	
		if (@$post['sync_acknowledge_priceband']) {
			////Acknowledge vendor synced local to server
			$this->pricebandmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_priceband']);
		}
		if (@$post['sync_delete_priceband']) {
			////Delete sync data
			$this->pricebandmodel->delete_sync_data($this->user_id, @$post['sync_delete_priceband']);
		}
		$pbbdt = $this->pricebandmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $pbbdt);
		//Priceband
		//Priceband Prices
		if (@$post['sync_acknowledge_priceband_price']) {
			////Acknowledge vendor synced local to server
			$this->pricebandmodel->acknowledge_sync_data_price($this->user_id, @$post['sync_acknowledge_priceband_price']);
		}
		if (@$post['sync_delete_priceband_price']) {
			////Delete sync data
			$this->pricebandmodel->delete_sync_data_price($this->user_id, @$post['sync_deletepriceband_price']);
		}
		$pbpdt = $this->pricebandmodel->get_data_sync_all_price($this->user_id);
		$data = array_merge($data, $pbpdt);
		//Priceband Prices	
		///product
		if (@$post['sync_acknowledge_product']) {
			////Acknowledge Product synced local to server
			$this->productmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_product']);
		}
		if (@$post['sync_delete_product']) {
			////Delete sync data
			$this->productmodel->delete_sync_data($this->user_id, @$post['sync_delete_product']);
		}
		$psddt = $this->productmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $psddt);
		//Product
		///Sales Person
		if (@$post['sync_acknowledge_salesperson']) {
			////Acknowledge Product synced local to server
			$this->salespersonmodel->acknowledge_sync_data($this->user_id, @$post['sync_acknowledge_salesperson']);
		}
		if (@$post['sync_delete_salesperson']) {
			////Delete sync data
			$this->salespersonmodel->delete_sync_data($this->user_id, @$post['sync_delete_salesperson']);
		}
		$spdt = $this->salespersonmodel->get_data_sync_all($this->user_id);
		$data = array_merge($data, $spdt);
		//Sales Person
		$data['user'] = $this->salespersonmodel->get_current_user($this->user_id);
		$data['vat_addtion'] = 0.0;
		$this->db->order_by('id', 'desc')->select('vat_addition');
		if ($vat = $this->configurationmodel->gets_data()->row_array()) {
			$data['vat_addtion'] = (int)$vat['vat_addition'];
		}
		//$data['customers']= $this->customermodel->gets_customers_api_sync($this->user_id);
		//$data['sales_mans']=$this->salespersonmodel->get_other_users($this->user_id);
		$data['status'] = true;
		echo json_encode($data);
	}

	/*
				After send quotation
			*/
	function send_quotation_service($quotation_id = 0)
	{
		$this->servicemodel->send_quotation_notification_service($quotation_id);
	}


	/*
				After send quotation
			*/
	function send_quotation_service_only_send($quotation_id = 0)
	{
		$this->servicemodel->send_quotation_notification_service_send_only($quotation_id);
	}



	/*
				After send quotation
			*/
	function send_quotation_balance_service($quotation_id = 0)
	{
		$this->servicemodel->send_quotation_balance_notification($quotation_id);
	}

	function quotation_set_balance()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		try {
			$quotation_id = $this->quotationmodel->api_save_quotation_row($this->user_id, $post);
			if ($quotation_id > 0) {
				$data['action_status'] = true;
				$data['quotationId'] = $quotation_id;
				try {
					$this->send_quotation_balance_service($quotation_id);
				} catch (Exception $e) {
				}
			} else {
				$data['action_status'] = false;
				$data['message'] = "Quotation Insert Failled";
			}
		} catch (Exception $e) {
		}
		$data['status'] = true;
		echo json_encode($data);
	}

	function quotation_edi_test()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		echo "user=" . $this->user_id;
		echo "<br/><br/>test=";
		//print_r($post);
		$this->servicemodel->send_order_to_blindata($post["quotation"], $this->user_id);
	}
	 function delete_quotation()
{
    $post = json_decode(file_get_contents('php://input'), true);
    $server_id = @$post['server_id'] ?? null;


    if (!$server_id) {
        echo json_encode(['status' => false, 'message' => 'server_id is required']);
        return;
    }

	$this->quotationmodel->delete_quotation_data($server_id );

    echo json_encode([
        'status' => $this->db->affected_rows() > 0,
        'message' => $this->db->affected_rows() > 0 ? 'Quotation deleted successfully' : 'Quotation not found'
    ]);
}
function send_pending_mails()
{
    $this->load->database();

    $emails = $this->db->where('sent_status', 0)
        ->order_by('id', 'DESC')
        ->limit(10)
        ->get('mail_data_customer')
        ->result();

    foreach ($emails as $email) {
        if (!filter_var($email->to_mail, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email: " . $email->to_mail . "<br>";
            continue;
        }

        // Send email without attaching PDF link
        $sent = $this->send_email_html_server(
            $email->subject,
            $email->content,
            $email->to_mail,
            'Blindtex'
        );

        if ($sent) {
            $this->db->where('id', $email->id)->update('mail_data_customer', [
                'sent_status' => 1,
                'sent_date' => date('Y-m-d H:i:s')
            ]);
        } else {
            echo "<pre>Failed to send to: {$email->to_mail}</pre>";
        }
    }
}
function send_email_html_server($subject, $message, $to_address, $from_name, $attach_files = array())
{
	$this->load->library('email');
	$config['mailtype'] = 'html';
	$this->email->initialize($config);
	$this->email->from($this->config->item('email_reply'), $from_name);
	$this->email->to($to_address);
	$this->email->subject($subject);
	$this->email->message($message);
	foreach ($attach_files as $file_name) {
		///Attach files appath location
		$this->email->attach($file_name);
	}
	return  $this->email->send();
}


}
