<?php if (!defined('BASEPATH')) {
	exit('No direct script allowed');
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Quotation extends MY_Controller
{
	var $module_id = 'id';
	var $single_name = "quotation";
	var $valid_fields = array('name', 'parent');
	public function __construct()
	{
		parent::__construct();
		$this->module_model = $this->single_name . 'model';
		$this->view_folder = $this->single_name;
		$this->redirect = 'admin/' . $this->single_name . '/list';
				$this->redirect2 = 'admin/' . $this->single_name . '/list2';
		$this->module_caption = 'Quotation';
		$this->module_active = $this->single_name;
		$this->module_active_sub = $this->single_name;
		$this->controller = $this->single_name;
		$this->permission = $this->single_name;
		$this->check_user_privillages($this->permission . '_list');
		$this->load->model(array($this->module_model, 'salespersonmodel', 'customermodel', 'usersmodel'));
		$this->company_id = $this->config->item('company_id');

			$this->companies = [];
		

			
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
    $view_folder  = $this->view_folder;

    /* ---------------- Validation ---------------- */
    $this->form_validation->set_rules(
        'name',
        'Product Type Name',
        'required|trim|alpha_numeric_spaces|min_length[3]|max_length[20]'
    );

    if ($this->form_validation->run() == FALSE) {

        /* ---------------- Page data ---------------- */
        $data['page']           = $this->module_caption;
        $data['module']         = $this->module_caption;
        $data['active']         = $this->module_active;
        $data['active_sub']     = $this->module_active_sub;
        $data['active_sub_sub'] = $this->module_active_sub . '_list';
        $data['content']        = 'admin/' . $view_folder . '/list2';
        $data['redirect']       = site_url() . $this->redirect;
        $data['edit']           = 0;

        /* ---------------- Edit Mode ---------------- */
        if ($type == "edit") {
            $this->check_user_privillages($this->permission . '_add');
            if ($data['res'] = $this->$module_model->get_row($id)) {
                $data['edit'] = 1;
            }
        }

        /* ---------------- Filters ---------------- */
        $filter = [];
        if (!empty($this->companies)) {
            $filter['company'] = $this->companies;
        }

        /* ---------------- Pagination ---------------- */



   $this->load->library('pagination');

$config['base_url'] = base_url('admin/'.$this->controller . '/list');
$config['total_rows'] = $this->$module_model->get_quotations_admin_count($filter);
$data['count']=$config['total_rows'];
$config['per_page'] = 25;

$config['page_query_string'] = TRUE;
$config['query_string_segment'] = 'page';
$config['use_page_numbers'] = TRUE;
$config['reuse_query_string'] = TRUE;

/* Bootstrap pagination */
$config['num_links'] = 3; // 👈 important

/* Bootstrap pagination */
$config['full_tag_open']  = '<ul class="pagination justify-content-end">';
$config['full_tag_close'] = '</ul>';

$config['first_link'] = 'First';
$config['last_link']  = 'Last';

$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close']= '</li>';

$config['last_tag_open']  = '<li class="page-item">';
$config['last_tag_close'] = '</li>';

$config['prev_link'] = '&laquo;';
$config['next_link'] = '&raquo;';

$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';

$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
$config['cur_tag_close'] = '</span></li>';

$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';

$config['attributes'] = ['class' => 'page-link'];

$this->pagination->initialize($config);

/* page + offset */
$page = (int) $this->input->get('page');
$page = ($page > 0) ? $page : 1;
$offset = ($page - 1) * $config['per_page'];
$data['sl_start'] = $offset + 1;

/* Data */
$data['tabledata'] = $this->$module_model
    ->get_quotations_admin($filter, $config['per_page'], $offset);

$data['pagination'] = $this->pagination->create_links();



        /* ---------------- Extra data ---------------- */
        $this->db->where('active', 1)->select('id,name');
        $data['sales_person'] = $this->salespersonmodel->gets_data()->result();

        if ($this->companies) {
            $this->db->where_in('id', $this->companies);
        }
        $this->db->where('user_group', $this->company_id)->select('id,name');
        $data['company'] = $this->usersmodel->gets_data()->result();

        $data['status_list'] = $this->$module_model->get_status();
        $data['module_id']   = $this->module_id;
        $data['controller']  = $this->controller;
        $data['permissions'] = $this->usergroupsmodel
            ->get_module_privillages($this->permission);

        $this->load->vars($data);
        $this->load->view('admin/include/template');

    } else {

        /* ---------------- Save / Update ---------------- */
        $this->check_user_privillages($this->permission . '_add');

        $data = $this->input->post();
        unset($data['id']);

        if ($data) {
            $valid = TRUE;
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->valid_fields)) {
                    $valid = FALSE;
                    break;
                }
            }

            if ($valid) {
                if ($this->input->post('id') == 0) {
                    $data['is_enabled']   = 1;
                    $data['created_date'] = date('Y-m-d');
                }

                $this->$module_model->save($data, $this->input->post('id'));
                $this->session->set_flashdata(
                    'success',
                    $this->lang->line('common_update_success')
                );
            } else {
                $this->session->set_flashdata('error', 'Data Error Occurred');
            }
        } else {
            $this->session->set_flashdata('error', 'Data Error Occurred');
        }

        redirect($this->redirect2);
    }
}




	public function list2($type = "list", $id = "")
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
						$filter = [];

						if (!empty($this->companies)) {
						$filter['company'] = $this->companies;
						}
			$data['tabledata'] = $this->$module_model->get_quotations_admin($filter);
	
			$this->db->where('active', 1)->select('id,name');
			$data['sales_person'] = $this->salespersonmodel->gets_data()->result();
			if($this->companies){
				$this->db->where_in('id',$this->companies);
			}
			$this->db->where('user_group', $this->company_id)->select('id,name');
			$data['company'] = $this->usersmodel->gets_data()->result();
			$data['status_list'] = $this->$module_model->get_status();
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

	public function sort($or, $id)
	{
		$module_model = $this->module_model;
		$gt = $this->$module_model->sort($or, $id);
	}
	public function delete($id = 0)
	{
		$module_model = $this->module_model;
		if ($id > 0) {
			$this->$module_model->delete($id);
			$this->session->set_flashdata('success', $this->lang->line('common_delete_success'));
			redirect($this->redirect);
		} else {
			echo "ERROR";
		}
	}
	public function view($id = 0)
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		if ($id > 0) {
			$data['product'] = $this->$module_model->get_row_details($id);
			$data['page'] = 'View ' . $this->module_caption;
			$data['module'] = $this->module_caption;
			$data['active'] = $this->module_active;
			$data['active_sub'] = $this->module_active_sub;
			$data['active_sub_sub'] = $this->module_active_sub . '_list';
			$data['content'] = 'admin/' . $view_folder . '/view';
			$data['module_id'] = $this->module_id;
			$data['controller'] = $this->controller;
			$data['content'] = 'admin/' . $view_folder . '/view';
			$this->load->vars($data);
			$this->load->view('admin/include/template');
		} else {
			echo "ERROR";
		}
	}
	public function disable($id = 0)
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		if ($id > 0) {
			$update["active"] = 0;
			$this->$module_model->save($update, $id);
			$this->session->set_flashdata('success', $this->lang->line('common_disable_success'));
			redirect($this->redirect);
		} else {
			echo "ERROR";
		}
	}
	public function enable($id = 0)
	{
		$module_model = $this->module_model;
		$view_folder = $this->view_folder;
		if ($id > 0) {
			$update["active"] = 1;
			$this->$module_model->save($update, $id);
			$this->session->set_flashdata('success', $this->lang->line('common_enable_success'));
			redirect($this->redirect);
		} else {
			echo "ERROR";
		}
	}
	public function upload()
	{
		$config['upload_path'] = APPPATH . '../uploads/customer/';
		$config['allowed_types'] = 'gif|png|jpg|jpeg';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('image')) {
			return false;
		} else {
			$name = $this->upload->data();
			$file = $name['file_name'];
			//$path = APPPATH . '../uploads/users/' . $file;
			//$this->base64_to_jpeg($this->input->post('cropped_image'), $path);
			return $file;
		}
	}

	public function base64_to_jpeg($base64, $path)
	{
		@copy($path, $$path);
		$ifp = @fopen($path, 'wb');
		$data = explode(',', $base64);
		@fwrite($ifp, base64_decode($data[1]));
		@fclose($ifp);
		return true;
	}

	public function print_quotation_invoice($quotation_id = 0)
	{
		$module_model = $this->module_model;
		$this->db->select('a.*,u.name as company_name,sp.name as sp_name')
		->join('sales_person sp', 'sp.id=a.sales_person', 'left')
			->join('admin_users u', 'u.id=sp.company', 'left');
		if ($quotation = $this->$module_model->get_row_detail($quotation_id)) {
			$rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation_id);
			$this->db->where(array('sales_person' => @$quotation->sales_person, 'phone' => @$quotation->customer_phone));
			$customer = $this->customermodel->gets_data()->row_array();
			//Company
			$this->db->where('a.id', @$quotation->sales_person)
				->join('admin_users u', 'u.id=a.company', 'left')
				->select('u.name as company_name,u.image as company_image,u.address as company_address,u.phone as company_phone,u.email as company_email');
			$company = $this->salespersonmodel->gets_data()->row_array();
			$invoice_data = ['quotation' => $quotation, 'rooms' => $rooms, 'customer' => $customer, 'company' => $company];
			$invoice_pdf = $this->generate_pdf_order_invoice($invoice_data);
		}
	}

	public function print_quotation_worksheet($quotation_id = 0)
	{
		$module_model = $this->module_model;
		$this->db->select('a.*,u.name as company_name,sp.name as sp_name')
		->join('sales_person sp', 'sp.id=a.sales_person', 'left')
			->join('admin_users u', 'u.id=sp.company', 'left');
		if ($quotation = $this->$module_model->get_row_detail($quotation_id)) {
			$rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation_id);
			$this->db->where(array('sales_person' => @$quotation->sales_person, 'phone' => @$quotation->customer_phone));
			$customer = $this->customermodel->gets_data()->row_array();
			$invoice_data = ['quotation' => $quotation, 'rooms' => $rooms, 'customer' => $customer];
			$this->generate_pdf_worksheet($invoice_data);
		}
	}

	function generate_pdf_order_invoice($data = array())
	{
		try {
			$this->load->library('pdf');
			$html = $this->load->view('pdf_templates/confirmed_order', $data, true);
			$file_name = 'QTN' . $data['quotation']->id;
			$this->pdf->downloadPDF($html, $file_name, '');
		} catch (Exception $e) {
		}
	}

	function generate_pdf_worksheet($data = array())
	{
		try {
			$this->load->library('pdf');
			$html = $this->load->view('pdf_templates/worksheet_confirmed_order', $data, true);
			$file_name = 'QTN' . $data['quotation']->id;
			$this->pdf->downloadPDF($html, $file_name, '', '', '', 'landscape');
		} catch (Exception $e) {
		}
	}

public function delete_quotation()
{
    $id        = $this->input->post('id');   // single
    $ids       = $this->input->post('ids');  // bulk
    $permanent = (int) $this->input->post('permanent');

    // Normalize IDs
    if (!empty($ids) && is_array($ids)) {
        $quotationIds = $ids;
    } elseif (!empty($id)) {
        $quotationIds = [$id];
    } else {
        echo json_encode(['success' => false, 'message' => 'No quotation selected']);
        return;
    }

    // ===============================
    // PERMANENT DELETE
    // ===============================
    if ($permanent === 1) {

        $this->db->trans_start();

        foreach ($quotationIds as $qid) {

            // Fetch old data for audit
            $oldData = $this->db->get_where('quotation', ['id' => $qid])->row_array();

            // Rooms
            $rooms = $this->db
                ->get_where('quotation_rooms', ['quotation' => $qid])
                ->result();

            foreach ($rooms as $room) {

                // Windows
                $windows = $this->db
                    ->get_where('quotation_rooms_windows', ['room' => $room->id])
                    ->result();

                foreach ($windows as $window) {
                    $this->db
                        ->where('window', $window->id)
                        ->delete('quotation_rooms_windows_extras');
                }

                $this->db
                    ->where('room', $room->id)
                    ->delete('quotation_rooms_windows');
            }

            $this->db
                ->where('quotation', $qid)
                ->delete('quotation_rooms');

            $this->db
                ->where('id', $qid)
                ->delete('quotation');

            // Audit log
            audit_log(
                'Quotation',
                $qid,
                'DELETE_PERMANENT',
                json_encode($oldData),
                null,
                1
            );
        }

        $this->db->trans_complete();

        echo json_encode([
            'success' => $this->db->trans_status()
        ]);
        return;
    }

    // ===============================
    // SOFT DELETE
    // ===============================
    $this->db->trans_start();

    foreach ($quotationIds as $qid) {

        $oldData = $this->db->get_where('quotation', ['id' => $qid])->row_array();

        $this->db
            ->where('id', $qid)
            ->update('quotation', [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

        audit_log(
            'Quotation',
            $qid,
            'DELETE_SOFT',
            json_encode($oldData),
            null,
            1
        );
    }

    $this->db->trans_complete();

    echo json_encode([
        'success' => $this->db->trans_status()
    ]);
}


public function get_sales_person_by_company()
{
    $company_id = $this->input->post('company_id');

    $this->db->where('active', 1);
    $this->db->select('id, name');


    if (!empty($company_id)) {
        $this->db->where('company', $company_id);
    }

    $sales_persons = $this->salespersonmodel->gets_data()->result();

    echo json_encode($sales_persons);
}

public function update_manufacturing_status()
{
    $id     = $this->input->post('id');
    $status = $this->input->post('status');
	$remarks = $this->input->post('remarks');

    // Get old data
    $oldData = $this->db->get_where('quotation', ['id' => $id])->row_array();

    // New data to update
    $data = [
        'manufacturing_status' => $status,
		'sales_remarks' => $remarks,	
        'updated_date'           => date('Y-m-d H:i:s')
    ];

    // Update database
    $this->db->where('id', $id);
    $this->db->update('quotation', $data);

    // Get new data after update
    $newData = $this->db->get_where('quotation', ['id' => $id])->row_array();

    // Audit log
    audit_log(
        'quotation',
        $id,
        'UPDATE',
        $oldData,
        $newData,
        1
    );

    echo json_encode(['success' => true]);
}


public function checkout($token = null)
{
    $this->load->library('encryption');
    // ❌ No token
    if (empty($token)) {
        show_404();
    }


    $quotation = $this->db
        ->where('token', $token) // ✅ use correct field
        ->get('quotation')
        ->row();


    if (!$quotation) {
        show_error('Invalid or expired payment link');
    }

    // 🔹 Payment check
    $advance  = (float) ($quotation->advance ?? 0);
    $subTotal = (float) ($quotation->sub_total ?? 0);
    $remaining = max(0, $subTotal - $advance);

// detect balance mode
$isBalancePayment = ($advance > 0 && $advance < $subTotal);

    if ($advance >= $subTotal && $subTotal > 0) {
        show_error('This quotation is already fully paid');
    }


    $this->load->model('quotationmodel');
    $this->load->model('customermodel');

    $rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation->id);
  

    $this->db->where([
        'sales_person' => $quotation->sales_person,
        'phone'        => $quotation->customer_phone
    ]);
    $customer = $this->customermodel->gets_data()->row_array();

    // =====================================================
    // 🔷 COMPANY
    // =====================================================
    $this->db->from('sales_person a')
        ->where('a.id', $quotation->sales_person)
        ->join('admin_users u', 'u.id = a.company', 'left')
        ->select('
            a.email as sales_person_email,
            a.name as sales_person_name,
            u.id as company_id,
            u.reply_mail,
            u.name as company_name,
            u.image as company_image,
            u.address as company_address,
            u.phone as company_phone,
            u.trn_no,
            u.company_email,
            u.template_logo,
            u.branding_colour_code,
            u.email,
            u.bank_name,
            u.account_no,
            u.account_name,
            u.swift_code,
            u.iban,
            u.branch as bank_branch
        ');

    $company = $this->db->get()->row_array();
      $encryptedId = $this->encryption->encrypt($quotation->id);

    // =====================================================
    // 🔷 PASS DATA TO VIEW
    // =====================================================
    $data = [
        'quotation'    => $quotation,
        'rooms'        => $rooms,
        'payAmount'        => $remaining,
        'isBalancePayment' => $isBalancePayment,
        'customer'     => $customer,

        'totalAmount'      => $subTotal,
        'advanceAmount'    => $advance,
        'company'      => $company,
        'bank_name'    => $company['bank_name'] ?? '',
        'account_no'   => $company['account_no'] ?? '',
        'account_name' => $company['account_name'] ?? '',
        'swift_code'   => $company['swift_code'] ?? '',
        'iban'         => $company['iban'] ?? '',
        'bank_branch'  => $company['bank_branch'] ?? '',
        'encryptedId'  => $encryptedId,
		'branding_colour_code' => $company['branding_colour_code'] ?? '#000000',
    ];

    // =====================================================
    // 🔷 LOAD VIEW
    // =====================================================
    $this->load->view('checkout/checkout', $data);
}
}
