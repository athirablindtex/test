<?php
class Userhistorymodel extends CI_Model{
	 public $table_name = 'userhistory';
    public $primary_key = 'id';
    
    function __construct() {
        parent::__construct();
		//$this->load->database();	
    }
}

?>