<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL && (~E_WARNING ||~E_NOTICE));

class Mailcron extends CI_Controller {

    public function send_pending_mails()
    {
        $this->load->database();

        $emails = $this->db->where('sent_status',0)
            ->order_by('id', 'asc')
            ->limit(10)
            ->get('mail_data_customer')
            ->result();

        foreach ($emails as $email) {
            $to_email = trim(strtolower($email->to_mail));

  
            // Determine file type
            // $file_type = ($email->subject === 'Confirmed Invoice' || $email->subject === 'Quotation') ? 'invoice' : 'worksheet';
            $filename = $email->pdf_link;
     
            $to_email= $email->to_mail;
          
            $reply_mail=$email->reply_mail ?? '' ;
            $company_name= $email->from_mail;
            $cc_email = $email->cc_mail ?? '';
       

            // Send the email
            $sent = $this->send_email_html_server(
                $email->subject,
                $email->content,
                $to_email,
                $reply_mail,
                 $cc_email,
                $company_name,
                $filename,
           
                $email->id
            );

            if ($sent) {
                $this->db->where('id', $email->id)->update('mail_data_customer', [
                    'sent_status' => 1,
                    'sent_date' => date('Y-m-d H:i:s'),
                    'error_message' => NULL
                ]);
            } else {
                echo "<pre>Failed to send to: {$to_email}</pre>";
            }
        }
    }

//     public function send_email_html_server($subject, $message, $to_address, $from_name, $filename, $file_type, $email_id)
// {
//     $this->load->library('email');

//     // SMTP Configuration
//     // $config = [
//     //            'protocol'  => 'smtp',
//     //     'smtp_host' => 'smtp.gmail.com', // or tls:// for port 587
//     //     'smtp_port' => 587,                         // Use 587 for TLS, 465 for SSL
//     //     'smtp_user' => 'blindtexonce@gmail.com',       // Your full cPanel email
//     //     'smtp_pass' => 'xhmfwxjvtkrmbnik',       // Your email password
//     //     'mailtype'  => 'html',
//     //     'charset'   => 'utf-8',
//     //     'newline'   => "\r\n",
//     //     'wordwrap'  => TRUE,
//     //       'smtp_crypto' => 'tls', 
       
//     // ];
//    // SMTP Configuration
//     $config = [
//     'protocol'  => 'smtp',
//        'smtp_host' => 'mail.tradeblindsdirect.com', // or tls:// for port 587
//        'smtp_port' => 465,                         // Use 587 for TLS, 465 for SSL
//        'smtp_user' => 'blindtexonce@tradeblindsdirect.com',       // Your full cPanel email
//        'smtp_pass' => 'Intoshade@aRa@2025',       // Your email password
//        'mailtype'  => 'html',
//           'charset'     => 'utf-8',
//     'newline'     => "\r\n",
//     'crlf'        => "\r\n",
//   'wordwrap'    => TRUE,

//          'smtp_crypto' => 'ssl',  
//     ]; 
   
    

//     $this->email->clear(TRUE); // clear previous data
//     $this->email->initialize($config);

//     $this->email->from('blindtexonce@tradeblindsdirect.com', $from_name);
//     $this->email->to($to_address);
//     $this->email->subject($subject);
//     $this->email->message($message);

//     // Determine full file path
//     $full_path = APPPATH . '../uploads/' . $file_type . '/' . $filename;

//     if (!empty($filename) && file_exists($full_path)) {
//         $this->email->attach($full_path);
//     } else {
//         $this->db->where('id', $email_id)->update('mail_data_customer', [
//             'error_message' => "Attachment not found: $full_path"
//         ]);
//         echo "<pre>Attachment not found: $full_path</pre>";
//     }

//     // Attempt to send
//     if (!$this->email->send()) {
//         $error = $this->email->print_debugger();
//         $this->db->where('id', $email_id)->update('mail_data_customer', [
//             'error_message' => $error
//         ]);
//         echo "<pre>Email to {$to_address} failed:\n{$error}</pre>";
//         return false;
//     }

//     return true;
// }
public function send_email_html_server($subject, $message, $to_address, $reply_mail=Null, $cc_emails, $from_name, $filename, $email_id)
{

    // echo "to_address: $to_address\n";
    // echo "reply_mail: $reply_mail\n";
    
    $this->load->library('phpmailer_lib');
    $mail = $this->phpmailer_lib->load();

    try {
        $to_address = trim($to_address);
        $reply_mail = trim($reply_mail);

        $mail->isSMTP();
        $mail->Host       = 'mail.tradeblindsdirect.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'blindtexonce@tradeblindsdirect.com';
        $mail->Password   = 'Intoshade@aRa@2025';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->SMTPKeepAlive = true;

  
        $mail->setFrom('blindtexonce@tradeblindsdirect.com', $from_name);
        $mail->addAddress($to_address);
        if((!empty($reply_mail)) &&  ($reply_mail !=NULL)) {
              $mail->addReplyTo($reply_mail, $from_name);
        }

     
        if (!empty($cc_emails)) {
            if (is_string($cc_emails)) {
                $cc_emails = explode(',', $cc_emails);
            }
            foreach ($cc_emails as $cc) {
                $cc = trim($cc);
              
                    $mail->addBCC($cc); // Or use addCC() if visibility is okay
              
            }
        }


        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);  
    
$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
];

   
   $filenames = explode(',', $filename);
foreach ($filenames as $single_filename) {
    $single_filename = trim($single_filename);

    // If it is an absolute path (starts with / or drive letter), use as is
    if (strpos($single_filename, '/') === 0 || preg_match('/^[A-Za-z]:\\\\/', $single_filename)) {
        $filepath = $single_filename; // absolute path
    } else {
        // Otherwise, treat as relative to FCPATH (root folder)
        $filepath = FCPATH . ltrim($single_filename, '/');
    }

    // echo "Processing attachment: $filepath\n";

    if (file_exists($filepath)) {
        $mail->addAttachment($filepath);
    } else {
        $this->db->where('id', $email_id)->update('mail_data_customer', [
            'error_message' => "Attachment not found: $single_filename"
        ]);
        echo "Attachment not found: $single_filename\n";
    }
}


        $mail->send();
        return true;

    } catch (Exception $e) {
        $error = $mail->ErrorInfo ?: $e->getMessage();
       $this->db->where('id', $email_id)->update('mail_data_customer', [
    'error_message' => $error,
    'sent_status'   => 3
]);
        echo "Message could not be sent. Mailer Error: {$error}\n";
        return false;
    }
}



public function test_brevo_mail()
{
    $this->load->library('phpmailer_lib');
    $mail = $this->phpmailer_lib->load();

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a4cdea001@smtp-brevo.com';
        $mail->Password   = 'LcrqkbAU3pnxE4wP'; // from Brevo
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('athirablindtex@gmail.com', 'Trade Blinds Direct');
        $mail->addAddress('athiravijayk13@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Brevo SMTP Test';
        $mail->Body    = '<h3>Brevo SMTP is working successfully</h3>';

        if($mail->send()){
            echo "Email sent successfully";
        } else {
            echo "Mail error: ".$mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo "Exception: ".$e->getMessage();
    }
}
}
?>
