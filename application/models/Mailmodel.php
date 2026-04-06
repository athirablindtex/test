<?php
class Mailmodel extends CI_Model
{
    public $smtp_config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://mail.tradeblindsdirect.com',
        'smtp_port' => 465,
        'smtp_user' => 'blindtexonce@tradeblindsdirect.com',//test@gmail.com
        'smtp_pass' => 'Intoshade@aRa@2025',//Password
        'mailtype'  => 'html',
        'charset'   => 'iso-8859-1',
        // 'charset' => 'utf-8',
        // 'wordwrap' => TRUE,
    );
    public $smtp_user_name = "blindtexonce@tradeblindsdirect.com";
    public $use_smtp = 1;
    function __construct()
    {
        parent::__construct();
        //$this->load->database();	
    }



    function send_email_html($subject, $message, $to_address, $from_name, $attach_files = array())
    {
        if ($this->use_smtp) {
            return $this->send_email_html_smtp($subject, $message, $to_address, $from_name, $attach_files);
        } else {
            return $this->send_email_html_server($subject, $message, $to_address, $from_name);
        }
    }



    function send_email_html_server($subject, $message, $to_address, $from_name, $attach_files = array())
    {
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($this->config->item('email_reply'), $from_name);
        $this->email->to($to_address);
        $this->email->subject($subject);
        $this->email->message($message);
        foreach ($attach_files as $file_name) {
            ///Attach files appath location
            $this->email->attach($file_name);
        }
        return  $this->email->send();
    }

    function send_email_html_smtp($subject, $message, $to_address, $from_name, $attach_files = array())
    {
        $this->load->library('email');
        $this->email->clear(TRUE);
        $this->email->initialize($this->smtp_config);
        $this->email->set_newline("\r\n");

        $this->email->to($to_address);
        $this->email->from($this->smtp_config['smtp_user'], $from_name);

        $this->email->subject($subject);
        $this->email->message($message);

        foreach ($attach_files as $file_name) {
            ///Attach files appath location
            $this->email->attach($file_name);
        }

        // if ($this->email->send()) {
        //     echo "mail sent";
        // } else {
        //     echo $this->email->print_debugger();
        // }
        return $this->email->send();
    }
}
