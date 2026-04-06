<?php
/* created by Lone wolf */
defined('BASEPATH') OR exit('No direct script access allowed');
class Commonfs{
	public $CI;
		 public function __construct(){
                $this->CI =& get_instance();
        	}
		/*check for valid db date format */
		function check_db_date($dt){
				$exp="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
				return preg_match($exp,$dt);
			}
		/*return valid human date format */
		function print_db_to_human_date($dt='',$format=''){
				$CI=$this->CI;
				return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dt)?date($format!=''?$format:($CI->config->item('site_date_format')!=''?$CI->config->item('site_date_format'):($CI->config->item('site_hard_date_format')!=''?$CI->config->item('site_hard_date_format'):'d-m-Y')),strtotime($dt)):'';
			}
		/*check for valid db date time format */
		function check_db_date_time($dt){
				$exp="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[1-9]|1[0-9]|2[0-4]):(0[1-9]|[1-5][0-9]):(0[1-9]|[1-5][0-9])$/";
				return preg_match($exp,$dt);
			}
		/*return valid human date time format */
		function print_db_to_human_date_time($dt='',$format=''){
				$CI=$this->CI;
				return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[1-9]|1[0-9]|2[0-4]):(0[1-9]|[1-5][0-9]):(0[1-9]|[1-5][0-9])$/",$dt)?date($format!=''?$format:($CI->config->item('site_date_time_format')!=''?$CI->config->item('site_date_time_format'):($CI->config->item('site_hard_date_time_format')!=''?$CI->config->item('site_hard_date_time_format'):'d-m-Y h:i a')),strtotime($dt)):'';
			}
	}