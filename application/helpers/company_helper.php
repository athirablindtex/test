<?php

if (!function_exists('get_company_id_or_null')) {

    function get_company_id_or_null($company_id)
    {
        $CI =& get_instance();
                $CI->db->reset_query();


        if (!$company_id) return NULL;

        $company = $CI->db
            ->select('id, is_global')
            ->where('id', $company_id)
            ->get('admin_users')
            ->row();

        if ($company && $company->is_global == 1) {
            return NULL; // GLOBAL
        }

        return $company_id; // NORMAL COMPANY
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