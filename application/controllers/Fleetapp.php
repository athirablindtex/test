<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 1);
class FleetApp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Firebase');
    }
    public function insert_lead()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $path = APPPATH . '../uploads/log/fleet-request/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $name = "fleet-request-" . date("Y-m-d-H-i-s") . ".json";
        file_put_contents($path . $name, json_encode($input, JSON_PRETTY_PRINT));
        $this->load->model('Fleetsmodel', 'fleetmodel');
        $response = ['status' => false, 'message' => 'Something went wrong'];
        $status   = 'failed';
        $reason   = '';
        if (empty($input)) {
            $reason = 'No data received';
            $this->logFleet($input, $status, $reason, $response);
            echo json_encode(['status' => false, 'message' => $reason]);
            return;
        }
        // =====================================================
        // 🔷 Get Sales Person + Company
        // =====================================================
        $result = $this->db
            ->select('sales_person.id, sales_person.company, sales_person.phone, sales_person.name')
            ->from('sales_person')
            ->join('admin_users', 'admin_users.id = sales_person.company')
            ->where('admin_users.name', $input['company_name'])
            ->where('admin_users.company_code IS NOT NULL', null, false)
            ->where('sales_person.name', $input['sales_person'])
            ->get()
            ->row();
        if (!$result) {
            $reason = 'Sales person or company not found';
            $this->logFleet($input, $status, $reason, $response);
            echo json_encode([
                'status' => false,
                'message' => $reason
            ]);
            return;
        }
        $quotation_data = [
            'customer_name'        => $input['customer_name'] ?? '',
            'customer_phone'       => $input['customer_phone'] ?? '',
            'customerEmail'        => $input['customerEmail'] ?? '',
            'customerAddress'      => $input['customer_address'] ?? '',
            'company_id'           => $result->company,
            'sales_person'         => $result->id,
            'sales_person_name'    => $result->name,
            'sales_person_phone'   => $result->phone,
            'deal_id'              => $input['deal_id'] ?? null,
            'total'                => $input['total'] ?? 0,
            'remarks'              => $input['remarks'] ?? '',
            'status'               => 'Save for Later',
            'source'               => 'Fleet'
        ];
        $quotation_id = $this->fleetmodel->insert_quotation(
            $result->id,
            $quotation_data
        );
        if (!$quotation_id) {
            $reason = 'Insert failed';
            $this->logFleet($input, 'failed', $reason, $response);
            echo json_encode([
                'status' => false,
                'message' => $reason
            ]);
            return;
        }
        $status = 'success';
        $response = [
            'status' => true,
            'message' => 'Quotation inserted successfully',
            'quotation_id' => $quotation_id
        ];
        $tokenData = $this->db
            ->select('push_token')
            ->where('user_id', $result->id)
            ->where('push_token IS NOT NULL', null, false)
            ->order_by('id', 'DESC') // latest token
            ->limit(1)
            ->get('login_tokkens')
            ->row();
        $title   = "New Quotation Assigned";
        $message = "A new quotation has been assigned to you.";
        if ($tokenData && !empty($tokenData->push_token)) {
            $token   = $tokenData->push_token;
            $title   = "New Quotation Assigned";
            $message = "A new quotation has been assigned to you.";
            $this->firebase->sendNotification($token, $title, $message);
        }
        $this->logFleet($input, $status, '', $response);
        echo json_encode($response);
    }
    private function logFleet($input, $status, $reason = '', $response = [])
    {
        $this->db->insert('fleet_table', [
            'deal_id'   => $input['deal_id'] ?? '',
            'input'     => json_encode($input),
            'response'  => json_encode($response),
            'synch'     => $status,
            'reason'    => $reason,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
