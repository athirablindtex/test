<?php
defined('BASEPATH') or exit('No direct script access allowed');
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->config('payment');
        $this->load->model('Quotationmodel');
        $this->load->model('customermodel');
        $this->load->model('Salespersonmodel');
    }

    public function createPaymentLink()
    {
        $mswipe = $this->config->item('mswipe');
        $this->load->library('encryption');
        $invoiceId = $this->input->post('quotation_id');
        $mode = $this->input->post('mode');
        $balance_mode = $this->input->post('balance') == '1' ? true : false;
        $payment_type = $balance_mode 
    ? 'balance' 
    : ($mode == 'half' ? 'half' : 'full');

        $windows = json_decode($this->input->post('windows'), true);
        if (!empty($windows)) {
            foreach ($windows as $win) {

                $this->db->where('id', $win['window_id']);
                $this->db->where('room', $win['room_id']);

                $this->db->update('quotation_rooms_windows', [
                    'activeItem' => $win['status']
                ]);
            }
        }


        $decryptedId = $this->encryption->decrypt($invoiceId);


        $invoice = $this->db->get_where('quotation', ['id' => $decryptedId])->row();


        if ($balance_mode) {
            $advance =  $invoice->advance ?? 0;
            $finalAmount  = $invoice->sub_total - $advance ?? 0; // make sure this is correct field
        } else {
            $subTotal = (float) ($invoice->sub_total ?? 0);
            $discountAmount = $invoice->discount ?? 0;
            $totalAmount = $invoice->total ?? 0; // make sure this is correct field

$discountPercent = 0;

                if (!empty($invoice->discountPercentage) && $invoice->discountPercentage > 0) {

                $discountPercent = (float) $invoice->discountPercentage;

                } else {




                if ($totalAmount > 0 && $discountAmount > 0) {

                $discountPercent = round(($discountAmount /  $totalAmount ) * 100, 2);

                $this->db->where('id', $invoice->id);
                $this->db->update('quotation', [
                    'discountPercentage' => $discountPercent
                ]);
                }
                }

      

            $this->db->select('id');
            $this->db->where('quotation', $invoice->id);
            $rooms = $this->db->get('quotation_rooms')->result();

            $roomIds = array_column($rooms, 'id');


            if (!empty($roomIds)) {
                $this->db->select_sum('total'); // change if needed
                $this->db->where_in('room', $roomIds);
                $this->db->where('activeItem', 1);

                $totalRow = $this->db->get('quotation_rooms_windows')->row();
                $activeTotal = round($totalRow->total ?? 0, 2);
            } else {
                $activeTotal = 0;
            }
            $total = $activeTotal;


            $amountAfterDiscount = $activeTotal;

            if ($discountPercent > 0) {
                $discountValue = round((($discountPercent / 100) * $activeTotal), 2);
                $amountAfterDiscount = $activeTotal - $discountValue;
            }
            $vatPercent = 5;
            $vatAmount = round((($vatPercent / 100) * $amountAfterDiscount), 2);


            $finalAmounts = $amountAfterDiscount + $vatAmount;
            $subTotal = round($finalAmounts, 2);

          

            $versionNo = $this->db
                ->where('quotation_id', $invoice->id)
                ->count_all_results('quotation_payment_versions') + 1;

            $this->db->insert('quotation_payment_versions', [
                'quotation_id'   => $invoice->id,
                'version_no'     => $versionNo,
                'sub_total'      => $invoice->sub_total,
                'discount'       => $invoice->discount,
                'vat'            => $invoice->vat,
                'paid_amount'    => $invoice->advance ?? 0,
                'balance_amount' => $invoice->total - ($invoice->advance ?? 0),
                'mode'           => $mode,
                'created_at'     => date('Y-m-d H:i:s')
            ]);


            $this->db->where('id', $invoice->id);
            $this->db->update('quotation', [
                'sub_total'  => $subTotal,
                'discount'   => $discountValue,
                'total'      => $total,
                'vat'        => $vatAmount,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
            $quotation_id = $invoice->id;
            $invoice_no = $invoice->invoiceno;
            $salesPersonId = $invoice->sales_person;
            $notificationType = 'Quotation Change in calculation';


            sendQuotationNotification($quotation_id, $invoice_no, $salesPersonId, $notificationType);



            if (!$invoice) {
                show_error("Invoice not found");
            }

            // Apply payment mode
            if ($mode == 'half') {
                $finalAmount =   $subTotal   / 2;
            } else {
                $finalAmount =  $subTotal ;
            }
        }






        $token = $this->getToken($invoice->id, $invoice->no);

        if (!$token) {
            echo "Token generation failed";
            return;
        }


        $url = $mswipe['payment_url'];

        $payload = [
            "versionNo"    => "VER4.0.0",
            "invoice_id"   => $invoice->invoiceno,
            "refid"        => $mswipe['username'],
            "SessionToken" => $token,
            "mobileNo"     => $invoice->phone ?? "9999999999",
             "amount"       => number_format($finalAmount, 2, '.', ''),
            "custCode"     => $mswipe['custCode'],
            "emailId"      => $invoice->email ?? "test@mail.com",

            "addlNote1"    =>  $payment_type,
            "addlNote2"    => "",
            "addlNote3"    => "",
            "addlNote4"    => "",

            "callBackUrl"  => "https://staging.tradeblindsdirect.com/blindtex-app/payment/webhook",
            "requestId"    => date('YmdHis'),
            "linkValidity" => "20"
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_error($ch);
        }



        $result = json_decode($response, true);




        $this->db->insert('payment_requests', [
            'quotation_id' => $invoice->id,
            'amount'       => $finalAmount,
            'mode'         => $mode,
            'request_payload'      => json_encode($payload),
            'status'       => 'initiated',
            'response'     => json_encode($result),
            'created_at'   => date('Y-m-d H:i:s')
        ]);
        if (!empty($result['Status']) && $result['Status'] == 'True' && !empty($result['SMSLink'])) {

            $this->db->update('payment_requests', [
                'transaction_id' => $result['Txn_ID'] ?? null,
                'payment_link'   => $result['SMSLink'],
                'response'       => json_encode($result),
                'status'         => 'pending'
            ], [
                'quotation_id' => $invoice->id
            ]);
            echo json_encode([
                'status' => 'success',
                'link' => $result['SMSLink']
            ]);
        } else {

            // ❌ Save failure response
            $this->db->update('payment_requests', [
                'response' => json_encode($result),
                'status'   => 'FAILED'
            ], [
                'quotation_id' => $invoice->id
            ]);
        }
    }

    public function getToken($quotation_id, $invoice_no)
    {
        $mswipe = $this->config->item('mswipe');

        $payload = [
            "user_name" => trim($mswipe['username']),
            "user_pwd" => trim($mswipe['password'])
        ];

        $ch = curl_init($mswipe['login_url']);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);
        $response = curl_exec($ch);

        // Prepare request log
        $requestLog = json_encode($payload);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            // ❌ Store curl error
            $this->db->update('payment_requests', [
                'request_payload' => $requestLog,
                'response' => $error,
                'status' => 'FAILED'
            ], [
                'quotation_id' => $quotation_id // or your condition
            ]);

            return false;
        }

        curl_close($ch);

        // Decode response
        $result = json_decode($response, true);


        if (json_last_error() !== JSON_ERROR_NONE) {

            $this->db->update('payment_requests', [
                'request_payload' => $requestLog,
                'response' => $response,
                'status' => 'FAILED'
            ], [
                'quotation_id' => $quotation_id
            ]);

            return false;
        }

        // Extract token
        $token = $result['token'] ?? $result['token'] ?? null;


        if (!$token) {
            $this->db->update('payment_requests', [
                'request_payload' => $requestLog,
                'response' => json_encode($result),
                'status' => 'FAILED'
            ], [
                'quotation_id' => $quotation_id
            ]);

            return false;
        }


        $this->db->update('payment_requests', [
            'request_payload' => $requestLog,
            'response' => json_encode($result),
            'status' => 'SUCCESS'
        ], [
            'quotation_id' => $quotation_id
        ]);

        return $token;



        // return $result['token'] ?? $result['access_token'] ?? false;
    }



    public function webhook()
    {

        $data = json_decode(file_get_contents("php://input"), true);


        if (empty($data)) {
            $data = $this->input->post();
        }


        file_put_contents('webhook_log.txt', json_encode($data) . PHP_EOL, FILE_APPEND);


        $status        = $data['status'] ?? '';
        $transactionId = $data['transactionId'] ?? '';
        $invoiceId     = $data['invoice_id'] ?? '';


        $this->db->where('quotation_id', $invoiceId);
        $this->db->update('payment_requests', [
            'status' => ($status == 'success') ? 'success' : 'failed',
            'transaction_id' => $transactionId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

  
        if ($status == 'success') {
            $this->db->insert('quotation_payments', [
                'quotation_id'   => $invoiceId,
                'amount'         => $data['amount'] ?? 0,
                'payment_type'   => 'Utap',
                'transaction_id' => $transactionId,
                'status'         => 'success',
                'created_at'     => date('Y-m-d H:i:s')
            ]);
        }
    

        // MUST respond
        echo json_encode(['status' => 'ok']);
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

    public function truncate2($number)
{
    return floor($number * 100) / 100;
}
}
