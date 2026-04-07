<?php

if (!function_exists('get_company_id_or_null')) {

    function get_company_id_or_null($company_id)
    {
        $CI =& get_instance();

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

        $salesperson_row = $CI->salespersonmodel->get_row($user_id);

        return $salesperson_row->company ?? null;
    }
}