<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Fleetsmodel extends CI_Model
{
    // =====================================================
    // ðŸ”· Insert Quotation with Customer Handling
    // =====================================================
    public function insert_quotation($user_id = 0, $d = [])
    {
        if (empty($d)) {
            return 0;
        }
        $this->db->trans_start();
        $phone = preg_replace('/\D/', '', $d['customer_phone'] ?? ''); // remove non-numbers
        $phone = substr($phone, -9); // take last 9 digits
        $phone = '0' . $phone; // add 0 in front
        $this->db->where([
            'phone'        => $phone,
            'sales_person' => $user_id
        ]);
        $customer = $this->db->get('customer')->row();
        if ($customer) {
         $customer_id = $customer->id;
            // $update_data = [
            //     'name'       => $d['customer_name'] ?? $customer->name,
            //     'email'      => $d['customerEmail'] ?? $customer->email,
            //     'address'    => $d['customerAddress'] ?? $customer->address,
            //     'updated_at' => date('Y-m-d H:i:s')
            // ];
            // $this->db->where('id', $customer_id);
            // $this->db->update('customer', $update_data);
        } else {
            $insert_customer = [
                'phone'        => $phone,
                'sales_person' => $user_id,
                'name'         => $d['customer_name'] ?? '',
                'email'        => $d['customerEmail'] ?? '',
                'address'      => $d['customerAddress'] ?? '',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s')
            ];
            $this->db->insert('customer', $insert_customer);
            $customer_id = $this->db->insert_id();
        }
        $deal_id = trim($d['deal_id'] ?? '');
        $existing_list = $this->db
            ->where('deal_id', $deal_id)
            ->where('sales_person', $user_id)
            ->where('customer', $customer_id)
            ->where('source', 'Fleet') // VERY IMPORTANT
            ->where('deleted_at IS NULL', null, false)
            ->get('quotation')
            ->result();
        if ($existing_list) {
            $data = [
                'sales_person_phone'  => $d['sales_person_phone'] ?? '',
                'sales_person_name'   => $d['sales_person_name'] ?? '',
                'customer_address'    => $d['customer_address'] ?? '',
                'customer_name'       => $d['customer_name'] ?? '',
                'customer_phone'      => $phone,
                'customerEmail'       => $d['customerEmail'] ?? '',
                'company'             => $d['company_id'] ?? 0,
                'source'              => 'Fleet',
                'deal_id'             => $deal_id ?: null,
                'synched'             => 1,
                'updated_date'        => date('Y-m-d H:i:s')
            ];
            foreach ($existing_list as $existing) {
                $this->db->where('id', $existing->id);
                $this->db->update('quotation', $data);
            }
        } else {
            $data = [
                'sales_person'        => $user_id,
                'sales_person_phone'  => $d['sales_person_phone'] ?? '',
                'sales_person_name'   => $d['sales_person_name'] ?? '',
                'customer'            => $customer_id,
                'customer_name'       => $d['customer_name'] ?? '',
                'customer_address'    => $d['customer_address'] ?? '',
                'customer_phone'      => $phone,
                'customerEmail'       => $d['customerEmail'] ?? '',
                'company'             => $d['company_id'] ?? 0,
                'source'              => 'Fleet',
                'deal_id'             => $deal_id ?: null,
                'status'              => "Save for Later",
                'total'               => $d['total'] ?? 0,
                'discount'            => $d['discount'] ?? 0,
                'vat'                 => $d['vat'] ?? 0,
                'advance'             => $d['advance'] ?? 0,
                'sub_total'           => $d['sub_total'] ?? 0,
                'payment_type'        => $d['payment_type'] ?? "",
                'balancePaymentType'  => $d['balancePaymentType'] ?? "",
                'remarks'             => $d['remarks'] ?? "",
                'priority'            => $d['priority'] ?? '',
                'insDate'             => $d['insDate'] ?? '',
                'insFromTime'         => $d['insFromTime'] ?? '',
                'insToTime'           => $d['insToTime'] ?? '',
                'is_remarks'          => ($d['is_remarks'] ?? 0) == "1" ? 1 : 0,
                'confirm'             => $d['confirm'] ?? 0,
                'synched'             => 1,
                'customerTrn'         => $d['customerTrn'] ?? '',
                'customerRef'         => $d['customerRef'] ?? '',
                'updated_date'        => date('Y-m-d H:i:s')
            ];
            $data['created_date'] = date('Y-m-d H:i:s');
            $this->db->insert('quotation', $data);
            $quotation_id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return 0;
        }
        return $quotation_id;
    }
}
