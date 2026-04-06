<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_last_sync_date'))
{
    function get_last_sync_date($user_id, $table_name)
    {
        $CI =& get_instance(); // get CI super object
        $query = $CI->db->select('last_synch_date')
                        ->from('user_sync_track')
                        ->where('user_id', $user_id)
                        ->where('table_name', $table_name)
                        ->get();

        if ($query->num_rows() > 0) {
            return $query->row(); // returns object with last_synch_date
        } else {
            return null; // no record found
        }
    }
}
