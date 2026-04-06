<?php if (! defined('BASEPATH')) {
	exit('No direct script allowed');
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Quotationtransfer extends MY_Controller
{
	var $module_id = 'id';
	var $single_name = "quotationtransfer";
	var $valid_fields = array('name', 'parent');
	public function __construct()
	{
		parent::__construct();
		$this->module_model = $this->single_name . 'model';
		$this->view_folder = $this->single_name;
		$this->redirect = 'admin/' . $this->single_name . '/list';
		$this->module_caption = 'Quotation';
		$this->module_active = $this->single_name;
		$this->module_active_sub = $this->single_name;
		$this->controller = $this->single_name;
		$this->permission = $this->single_name;
		$this->check_user_privillages($this->permission . '_list');
		$this->load->model(array($this->module_model, 'salespersonmodel', 'customermodel', 'usersmodel', 'quotationmodel'));
		$this->company_id = $this->config->item('company_id');
		if ($this->session->userdata('admin_type') !== 1) {

			$companySession = $this->session->userdata('admin_companies');

			if (!empty($companySession)) {
				$this->companies = is_array($companySession)
					? $companySession
					: [$companySession];
			}
		}
	}
	public function list($type = "list", $id = "")
	{

		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		$this->form_validation->set_rules('name', 'Product Type Name', 'required|trim|alpha_numeric_spaces|min_length[3]|max_length[20]');
		if ($this->form_validation->run() == FALSE) {
			$data['page'] = $this->module_caption;
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_list';
			$data['content'] = 'admin/' . $view_folder . '/list';
			$data['redirect'] = site_url() . $this->redirect;
			$data['edit'] = 0;
			if (@$type == "edit") {
				$this->check_user_privillages($this->permission . '_add');
				if ($data['res'] = $this->$module_model->get_row($id)) {
					$data['edit'] = 1;
				}
			}
			$company_ids = $this->companies ?? [];
			$data['tabledata'] = $this->$module_model->get_quotation_transfers_admin($company_ids);

			$this->db->where('active', 1)->select('id,name');
			$data['sales_person'] = $this->salespersonmodel->gets_data()->result();
			$this->db->where('user_group', $this->company_id)->select('id,name');

			if (!empty($company_ids)) {
				$this->db->where_in('id', $company_ids);
			}

			$data['company'] = $this->usersmodel->gets_data()->result();
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['permissions'] = $this->usergroupsmodel->get_module_privillages($this->permission);
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			$this->check_user_privillages($this->permission . '_add');
			$data = $this->input->post();
			unset($data['id']);
			if (@$data) {
				$valid = TRUE;
				foreach ($data as $key => $value) {
					if (!in_array($key, $this->valid_fields)) {
						$valid = FALSE;
						break;
					}
				}
				if ($valid) {
					if (@$data['id'] == 0) {
						$data['is_enabled'] = 1;
						$data['created_date'] = date('Y-m-d');
					}
					$id = $this->$module_model->save($data, $this->input->post('id'));
					$this->session->set_flashdata('success', $this->lang->line('common_update_success'));
				} else {
					$this->session->set_flashdata('error', "Data Error Occured");
				}
			} else {
				$this->session->set_flashdata('error', "Data Error Occured");
			}
			redirect($this->redirect);
		}
	}
	function status_approve($id = 0)
	{
		$module_model = $this->module_model;
		if ($quotation = $this->$module_model->get_row($id)) {
			if ($quotation->transfer_status == "Pending") {
				if ($user = $this->salespersonmodel->get_row(@$quotation->to)) {
					$customer_id = 0;
					try {
						$this->db->where(array('phone' => @$quotation->customer_phone, 'sales_person' => $user->id));
						if ($customer = $this->customermodel->gets_data()->row()) {
							$customer_id = $customer->id;
						} else {
							if ($quotRow = $this->quotationmodel->get_row($quotation->quotation)) {
								$cust_insert = array(
									'phone' => @$quotRow->customer_phone ?: "",
									'sales_person' => $user->id,
									'name' => @$quotRow->customer_name ?: "",
									'email' => @$quotRow->customer_email ?: "",
									'created_date' => date('Y-m-d')
								);
								$customer_id = @$this->customermodel->save($cust_insert);
							}
						}
					} catch (Exception $e) {
					}
					$upd = array('transfer_status' => 'Approved', 'status_date' => date('Y-m-d h:i:s'), 'synched' => 0);
					$this->$module_model->save($upd, $id);
					$upd_quotation = array('customer' => 0, 'transfer' => 1, 'sales_person' => $user->id, 'sales_person_name' => $user->name);
					$this->quotationmodel->save($upd_quotation, $quotation->quotation);
					$this->session->set_flashdata('success', 'Quotation Transfer Request Approved');
				} else {
				}
				redirect($this->redirect);
			} else {
				redirect('');
			}
		} else {
			redirect('');
		}
	}


	function status_reject($id = 0)
	{
		$module_model = $this->module_model;
		if ($quotation = $this->$module_model->get_row($id)) {
			if ($quotation->transfer_status == "Pending") {
				$upd = array('transfer_status' => 'Rejected', 'status_date' => date('Y-m-d h:i:s'), 'synched' => 0);
				$this->$module_model->save($upd, $id);
				$this->session->set_flashdata('success', 'Quotation Transfer Request Rejected');
				redirect($this->redirect);
			} else {
				redirect('');
			}
		} else {
			redirect('');
		}
	}


	public function transfer_quotation()
	{
		$quotationId = $this->input->post('quotation_id');
		$newSalesId  = $this->input->post('sales_person_id');
		$reason      = $this->input->post('reason');
		$toSales = $this->db
			->get_where('sales_person', ['id' => $newSalesId])
			->row_array();

		$to_name = $toSales['name'] ?? null;

		$transferredBy = $this->session->userdata('admin_id') ?? null;


		if (!$quotationId || !$newSalesId) {
			echo json_encode(['success' => false, 'message' => 'Missing data']);
			return;
		}

		$this->db->trans_start();


		$quotation = $this->db
			->query("SELECT * FROM quotation WHERE id = ? FOR UPDATE", [$quotationId])
			->row_array();

		if (!$quotation) {
			$this->db->trans_rollback();
			echo json_encode(['success' => false, 'message' => 'Quotation not found']);
			return;
		}

		$oldSalesId    = $quotation['sales_person'];
		$oldCustomerId = $quotation['customer'];
		$from_name = $quotation['sales_person_name'];
		$invoice_no = $quotation['invoiceno'];



		if ($oldSalesId == $newSalesId) {
			$this->db->trans_rollback();
			echo json_encode(['success' => false, 'message' => 'Quotation already under this sales person', 'oldSalesId' => $oldSalesId]);
			return;
		}


		$customer = $this->db
			->get_where('customer', ['id' => $oldCustomerId])
			->row_array();

		if (!$customer) {
			$this->db->trans_rollback();
			echo json_encode(['success' => false, 'message' => 'Customer not found']);
			return;
		}

		$phone = trim($customer['phone']);


		$existingCustomer = $this->db
			->where('phone', $phone)
			->where('sales_person', $newSalesId)
			->get('customer')
			->row_array();

		if ($existingCustomer) {
			$newCustomerId = $existingCustomer['id'];
		} else {

			$this->db->insert('customer', [
				'name'         => $customer['name'],
				'phone'        => $customer['phone'],
				'email'        => $customer['email'],
				'address'      => $customer['address'],
				'sales_person' => $newSalesId,
				'created_date' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			]);
			$newCustomerId = $this->db->insert_id();
		}


		$this->db->where('id', $quotationId)->update('quotation', [
			'sales_person' => $newSalesId,
			'sales_person_name' => $to_name,
			'customer'     => $newCustomerId,

			'updated_date' => date('Y-m-d H:i:s')
		]);


		$this->db->insert('quotation_transfer', [
			'quotation'   => $quotationId,
			'invoice_no' => $invoice_no,
			'from'  => $oldSalesId,
			'to'    => $newSalesId,
			'from_name'        => $from_name,
			'to_name'          => $to_name,
			'custName'       => $customer['name'],
			'custPhone'      => $customer['phone'],
			'custEmail'      => $customer['email'],
			'reason'         => $reason,
			'transfer_status' => 'completed',
			'transferred_by' => $transferredBy,
			'created_date'   => date('Y-m-d H:i:s'),
			'synched'        => 0
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode(['success' => false, 'message' => 'Transfer failed']);
		} else {
			// Send to new sales person
			$this->sendQuotationNotification($quotationId, $invoice_no, $newSalesId, 'assigned');

			// Send to old sales person
			$this->sendQuotationNotification($quotationId, $invoice_no, $oldSalesId, 'removed');

			echo json_encode([
				'success' => true,
				'redirect' => site_url('admin/quotationtransfer/list')
			]);
		}
	}
	private function sendQuotationNotification($quotationId, $invoice_no, $salesPersonId, $type)
	{
		$this->load->library('Firebase');

		// Get token (only 1 token per user)
		$tokenRow = $this->db
			->select('push_token')
			->where('user_id', $salesPersonId) // use correct column name
			->get('login_tokkens')
			->row_array();

		$token = $tokenRow['push_token'] ?? null;

		if (!$token) {
			return false;
		}
		$adminId = $this->session->userdata('admin_id');

		$transferredByName = $this->db
			->select('name')
			->where('id', $adminId)
			->get('admin_users')
			->row()
			->name ?? '';

		// Set title & message based on type
		if ($type == 'assigned') {

			$title = "New Quotation Assigned";
			$message = "Quotation #{$invoice_no} has been transferred to you by {$transferredByName}. Please sync your app to view the quotation.";
		} else {

			$title = "Quotation Removed";
			$message = "Quotation #{$invoice_no} has been transferred by {$transferredByName}. Please sync your app to view the quotation.";
		}
		// Send notification
		$response = $this->firebase->sendNotification($token, $title, $message);

		// Save notification history
		$this->db->insert('notification_history', [
			'quotation_id'     => $quotationId,
			'invoice_no'   => $invoice_no,
			'sales_person_id'  => $salesPersonId,
			'push_token'       => $token,
			'title'            => $title,
			'message'          => $message,
			'notification_type' => $type,
			'status'           => $response ? 'success' : 'failed',
			'response'         => json_encode($response),
			'created_date'     => date('Y-m-d H:i:s')
		]);

		return true;
	}
}
