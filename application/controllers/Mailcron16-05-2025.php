<?php
class Mailcron extends CI_Controller {

    public function send_pending_mails()
    {
        $this->load->database();

        $emails = $this->db->where('sent_status', 0)
            ->order_by('id', 'DESC')
            ->limit(10)
            ->get('mail_data_customer')
            ->result();

        foreach ($emails as $email) {
            $to_email = trim(strtolower($email->to_mail));

            // Validate email
            if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email: " . $to_email . "<br>";

                $this->db->where('id', $email->id)->update('mail_data_customer', [
                    'sent_status' => 2,
                    'sent_date' => date('Y-m-d H:i:s'),
                    'error_message' => 'Invalid email format'
                ]);
                continue;
            }

            // Determine file type
            $file_type = ($email->subject === 'Confirmed Invoice') ? 'invoice' : 'worksheet';
            $filename = basename($email->pdf_link);

            // Send the email
            $sent = $this->send_email_html_server(
                $email->subject,
                $email->content,
                $to_email,
                'Blindtex',
                $filename,
                $file_type,
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

    public function send_email_html_server($subject, $message, $to_address, $from_name, $filename, $file_type, $email_id)
{
    $this->load->library('email');

    // Clear previous email data and attachments
    $this->email->clear(TRUE); // 🔥 this is the fix!

    $config['mailtype'] = 'html';
    $this->email->initialize($config);

    $this->email->from($this->config->item('email_reply'), $from_name);
    $this->email->to($to_address);
    $this->email->subject($subject);
    $this->email->message($message);

    // Determine full file path
    $full_path = APPPATH . '../uploads/' . $file_type . '/' . $filename;

    if (!empty($filename) && file_exists($full_path)) {
        $this->email->attach($full_path);
    } else {
        $this->db->where('id', $email_id)->update('mail_data_customer', [
            'error_message' => "Attachment not found: $full_path"
        ]);
        echo "<pre>Attachment not found: $full_path</pre>";
    }

    // Attempt to send
    if (!$this->email->send()) {
        $error = $this->email->print_debugger();
        $this->db->where('id', $email_id)->update('mail_data_customer', [
            'error_message' => $error
        ]);
        echo "<pre>Email to {$to_address} failed:\n{$error}</pre>";
        return false;
    }

    return true;
}

}
?>
