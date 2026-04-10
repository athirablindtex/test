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
       $result_data = $this->fleetmodel->insert_quotation(
    $result->id,
    $quotation_data
);

if (empty($result_data) || empty($result_data['quotation_id'])) {

    $reason = 'Insert failed';

    $this->logFleet($input, 'failed', $reason, []);

    echo json_encode([
        'status' => false,
        'message' => $reason
    ]);
    return;
}
$quotation_id = $result_data['quotation_id'];
$deal_id      = $result_data['deal_id'];
$action       = $result_data['status']; // inserted / edited

$message_text = ($action == 'inserted')
    ? 'Quotation inserted successfully'
    : 'Quotation updated successfully';

$response = [
    'status' => true,
    'message' => $message_text,
    'quotation_id' => $quotation_id,
    'deal_id' => $deal_id,
    'action' => $action
];


if ($action) {

    $tokenData = $this->db
        ->select('push_token')
        ->where('user_id', $result->id)
        ->where('push_token IS NOT NULL', null, false)
        ->order_by('id', 'DESC')
        ->limit(1)
        ->get('login_tokkens')
        ->row();

    if ($tokenData && !empty($tokenData->push_token)) {

        $token = $tokenData->push_token;

        $title = "Quotation " . ($action == 'inserted' ? "Created" : "Updated");
        $message = $message_text;

        $this->firebase->sendNotification($token, $title, $message);
    }
}


// ✅ Correct status
$this->logFleet($input, 'success', '', $response);
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

 public function processSingleQueue($queue_id = null)
{
    if (!$queue_id) {
        echo "Queue ID missing";
        return;
    }

    $row = $this->db
        ->where('id', $queue_id)
        ->where('synced', 0)
        ->get('api_sync_queue')
        ->row();

    if (!$row) {
        log_message('error', 'Queue not found: '.$queue_id);
        echo "Queue not found";
        return;
    }

    $url = "https://fleet.tradeblindsdirect.com/api/external/integration";

    $payload = [
        "deal_zoho_id" => $row->deal_id,
        "fitting_date" => $row->fitting_date,
        "duration"     => 2
    ];

    // 🔥 Create log folder
    $path = APPPATH . '../uploads/log/fleet-request-response/';
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    // 🔥 Save request
    file_put_contents(
        $path . "request-" . $queue_id . "-" . date("Y-m-d-H-i-s") . ".json",
        json_encode($payload, JSON_PRETTY_PRINT)
    );

    // 🔥 CURL CALL
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);

    curl_close($ch);

    // 🔥 Save response (FIXED)
    file_put_contents(
        $path . "response-" . $queue_id . "-" . date("Y-m-d-H-i-s") . ".txt",
        print_r([
            'http_code' => $info['http_code'],
            'error'     => $error,
            'response'  => $response
        ], true)
    );

    // ✅ CONDITION CHECK
    if (!$error && $info['http_code'] == 200) {
        $this->db->where('id', $queue_id)->update('api_sync_queue', [
            'synced'  => 1,
            'response'=> $response
        ]);
    } else {
        $this->db->where('id', $queue_id)->update('api_sync_queue', [
            'response'=> $error ?: $response
        ]);
    }

    echo "Done"; // optional for testing
}
}
