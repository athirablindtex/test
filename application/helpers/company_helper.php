<?php

if (!function_exists('get_company_id_or_null')) {

 function get_company_id_or_null($company_id)
{
    $CI =& get_instance();

    if (!$company_id) return NULL;

 
    $db2 = $CI->load->database('default', TRUE);

    $company = $db2
        ->select('id, is_global')
        ->where('id', $company_id)
        ->get('admin_users')
        ->row();

    if ($company && $company->is_global == 1) {
        return NULL;
    }

    return $company_id;
}
}

if (!function_exists('get_salesperson_company')) {
    function get_salesperson_company($user_id)
    {
        $CI =& get_instance();
                $CI->db->reset_query();


        $row = $CI->db
            ->select('company')
            ->where('id', $user_id)
            ->get('sales_person') // your table name
            ->row();

        return $row->company ?? null;
    }
}
if (!function_exists('get_companies_list')) {

    function get_companies_list()
    {
        $CI =& get_instance();
        $CI->db->reset_query();

        $result = $CI->db
            ->select('id, name, company_code, is_global')
            ->from('admin_users')
            ->where('company_code IS NOT NULL', null, false)
            ->get()
            ->result_array();

        $companies = [];
        $globalNames = [];
        $globalIds = [];

        foreach ($result as $row) {

            if ($row['is_global'] == 1) {
                $globalNames[] = $row['name'];
                $globalIds[] = $row['id'];
            } else {
                $companies[] = $row;
            }
        }

        if (!empty($globalNames)) {

            $label = implode(', ', $globalNames);

        
            $label .= ' (Blindtex)';

            $companies[] = [
                'id' => implode(',', $globalIds),
                'name' => $label,
                'is_global' => 1
            ];
        }

        return $companies;
    }
}