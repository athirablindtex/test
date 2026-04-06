<?php
class Servicemodel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model(array('quotationmodel', 'customermodel', 'mailmodel', 'salespersonmodel', 'email_configurationmodel', 'mailtemplatemodel', 'producttypemodel', 'productmodel', 'extrasmodel'));
    }

    /*
				After send quotation
			*/
    public function send_quotation_notification_service($quotation_id, $online_quoation)
    {


        try {
            if ($quotation = $this->quotationmodel->get_row($quotation_id)) {



                //Rooms 
                $rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation_id);

                //Customer
                $this->db->where(array('sales_person' => @$quotation->sales_person, 'phone' => @$quotation->customer_phone));
                $customer = $this->customermodel->gets_data()->row_array();


                //Company
                $this->db->from('sales_person a')
                    ->where('a.id', @$quotation->sales_person)
                    ->join('admin_users u', 'u.id = a.company', 'left')

                    ->select('
     a.email as sales_person_email,
            u.id as company_id,
        u.reply_mail as reply_mail,
        u.name as company_name,
        u.image as company_image,
        u.address as company_address,
        u.phone as company_phone,
        u.company_email as company_email,
        u.email as email,
        u.bank_name,
        u.account_no,
       u.account_name,
        u.swift_code,
        u.iban,
        u.branch as bank_branch
    ');

                $query = $this->db->get(); // Execute the query


                $company = $query->row_array();
                $config_mails = $this->config->item('admin_mails');

                $admin_mails = is_array($config_mails) ? implode(',', $config_mails) : '';
                // print_r($admin_mails);
                // exit;

                $products_data = array();
                $invoice_data = array();
                //hANDLE Sendings
                $invoice_data = [
                    'quotation' => $quotation,
                    'rooms' => $rooms,
                    'customer' => $customer,
                    'company' => $company,
                    "bank_name" => @$company['bank_name'] ?: '',
                    "account_no" => @$company['account_no'] ?: '',
                    "swift_code" => @$company['swift_code'] ?: '',
                    "iban" => @$company['iban'] ?: '',

                    "account_name" => @$company['account_name'] ?: '',
                    "bank_branch" => @$company['bank_branch'] ?: '',
                    "swift_code" => @$company['swift_code'] ?: '',
                ];

                $mail_body = array(
                    "customer_name" => @$quotation->customer_name ?: '',
                    "quotation_id" => @$quotation->id ?: '',
                    "total_amount" => @$quotation->sub_total ?: '',
                    "advance_amount" => @$quotation->advance ?: '',
                    "company_name" => @$company['company_name'] ?: '',

                );

                $mail_placeholders = array(
                    "%customer_name%",
                    "%quotation_id%",
                    "%total_amount%",
                    "%advance_amount%",
                    "%company_name%"
                );

                $this->db->where('category', 'Worksheet');
                $template_data = $this->mailtemplatemodel->get_latest_row();
                $final_description = str_replace($mail_placeholders, $mail_body, @$template_data->description);

                $invoice_pdf = $this->generate_pdf_order_invoice($invoice_data);

                if ($invoice_pdf != "") {
                    if ($quotation->confirm == 1) {
                        $this->db->where('category', 'Invoice Confirmed');
                    } else {
                        $this->db->where('category', 'Quotation');
                    }
                    $template_data = $this->mailtemplatemodel->get_latest_row();
                    $final_description = str_replace($mail_placeholders, $mail_body, @$template_data->description);
                    $inv_mail_params = array(
                        'customer_email' => $customer['email'] ?: '',
                        'reply_mail' => $company['reply_mail'] ?: '',
                        'invoice_pdf' => $invoice_pdf,
                        'description' => $final_description,
                        'subject' => @$template_data->name ?: '',

                    );

                    $this->send_mail_invoice($inv_mail_params, $quotation,  $customer,  $company);
                }
                $worksheet_pdf = $this->generate_pdf_worksheet($invoice_data); ///MUJEEB  test



                if ($worksheet_pdf != "") {


                    // Get email template once outside loop
                    $this->db->where('category', 'Worksheet');
                    $template_data = $this->mailtemplatemodel->get_latest_row();
                    $final_description = str_replace($mail_placeholders, $mail_body, @$template_data->description);


                    $email = $company['email'] ?: '';
                    $worksheet_mail_params = array();

                    if (!empty($email)) {
                        $attachments = [];

                        if (!empty($worksheet_pdf) && $quotation->confirm == 1) { /// Confirm order only send worksheet

                            $attachments[] = $worksheet_pdf;
                        } else {
                            $template_data->name = 'Quotation';
                        }

                        if (!empty($invoice_pdf)) {
                            $attachments[] = $invoice_pdf;
                        }

                        $pdf_names = implode(', ', $attachments);


                        $worksheet_mail_params = array(
                            'email' => $email,
                            'cc_email' => $admin_mails,
                            'worksheet_pdf' => $pdf_names,
                            'description' => $final_description,
                            'subject' => @$template_data->name ?: '',
                            'invoice_pdf' => $invoice_pdf ?? null
                        );



                        $this->send_mail_worksheet($worksheet_mail_params, $quotation,  $customer,  $company);
                    }


                    // Delete worksheet file after mailing

                }
            }
        } catch (Exception $e) {
        }
    }



    /*
				After send quotation
			*/
    function send_quotation_notification_service_send_only($quotation_id = 0)
    {

        try {
            if ($quotation = $this->quotationmodel->get_row($quotation_id)) {

                //Rooms 
                $rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation_id);
                //Customer
                $this->db->where(array('sales_person' => @$quotation->sales_person, 'phone' => @$quotation->customer_phone));
                $customer = $this->customermodel->gets_data()->row_array();
                //Company
                $this->db->where('a.id', @$quotation->sales_person)
                    ->join('admin_users u', 'u.id=a.company', 'left')
                    ->select('u.name as company_name,u.image as company_image,u.address as company_address,u.phone as company_phone,u.email as company_email,c.bank_name as bank_name,c.account_no as account_no,c.account_name as account_name,c.swift_code as swift_code,c.iban as iban,c.branch as bank_branch');
                $company = $this->salespersonmodel->gets_data()->row_array();

                $products_data = array();
                $invoice_data = array();
                //hANDLE Sendings
                $invoice_data = ['quotation' => $quotation, 'rooms' => $rooms, 'customer' => $customer, 'company' => $company];

                $mail_body = array(
                    "customer_name" => @$quotation->customer_name ?: '',
                    "quotation_id" => @$quotation->id ?: '',
                    "total_amount" => @$quotation->sub_total ?: '',
                    "advance_amount" => @$quotation->advance ?: '',
                    "company_name" => @$company['company_name'] ?: '',
                    "company_image" => @$company['company_image'] ?: ''
                );
                $mail_placeholders = array(
                    "%customer_name%",
                    "%quotation_id%",
                    "%total_amount%",
                    "%advance_amount%",
                    "%company_name%",

                );

                $invoice_pdf = $this->generate_pdf_order_invoice($invoice_data);
                $inv_mail_params = array();
                if ($invoice_pdf != "") {
                    if ($quotation->confirm == 1) {
                        $this->db->where('category', 'Invoice Confirmed');
                    } else {
                        $this->db->where('category', 'Quotation');
                    }
                    $template_data = $this->mailtemplatemodel->get_latest_row();

                    $final_description = str_replace($mail_placeholders, $mail_body, $template_data->description);
                    $inv_mail_params = array(
                        'customer_email' => $customer['email'] ?: '',
                        'reply_mail' => $company['reply_mail'] ?: '',
                        'invoice_pdf' => $invoice_pdf,
                        'description' => $final_description,
                        'subject' => @$template_data->name ?: '',

                    );
                    $this->send_mail_invoice($inv_mail_params, $quotation,  $customer,  $company);
                    // unlink(APPPATH . '../uploads/invoice/' . $invoice_pdf);
                }
            }
        } catch (Exception $e) {
        }
    }


    /*
			 send quotation balance
			*/
    function send_quotation_balance_notification($quotation_id = 0)
    {
       
        try {
            if ($quotation = $this->quotationmodel->get_row($quotation_id)) {
       
                //Rooms 
                $rooms = $this->quotationmodel->get_sub_rooms_quotation($quotation_id);
                //Customer
                $this->db->where(array('sales_person' => @$quotation->sales_person, 'phone' => @$quotation->customer_phone));
                $customer = $this->customermodel->gets_data()->row_array();
                   $this->db->from('sales_person a')
    ->where('a.id', @$quotation->sales_person)
    ->join('admin_users u', 'u.id=a.company', 'left')
    ->select('
        a.email as sales_person_email,
        u.id as company_id,
        u.reply_mail as reply_mail,
        u.name as company_name,
        u.image as company_image,
        u.address as company_address,
        u.phone as company_phone,
        u.company_email as company_email,
        u.email as email,
        u.bank_name,
        u.account_no,
        u.account_name,
        u.swift_code,
        u.iban,
        u.branch as bank_branch
    ');

$query   = $this->db->get(); // Execute the query
$company = $query->row_array();



                $products_data = array();
                $invoice_data = array();
       
                //hANDLE Sendings
                $invoice_data = [
                    'quotation' => $quotation,
                    'rooms' => $rooms,
                    'customer' => $customer,
                    'company' => $company,
                    "bank_name" => @$company['bank_name'] ?: '',
                    "account_no" => @$company['account_no'] ?: '',
                    "swift_code" => @$company['swift_code'] ?: '',
                    "iban" => @$company['iban'] ?: '',

                    "account_name" => @$company['account_name'] ?: '',
                    "bank_branch" => @$company['bank_branch'] ?: '',
                    "swift_code" => @$company['swift_code'] ?: '',
                ];

                $invoice_pdf = $this->generate_balance_pdf_order_invoice($invoice_data);
                if ($invoice_pdf != "") {
                    $this->db->where('category', 'Invoice with Balance');
                    $template_data = $this->mailtemplatemodel->get_latest_row();
                    $mail_body = array(
                        "customer_name" => @$quotation->customer_name ?: '',
                        "quotation_id" => @$quotation->id ?: '',
                        "total_amount" => @$quotation->sub_total ?: '',
                        "advance_amount" => @$quotation->advance ?: '',
                        "company_name" => @$company['company_name'] ?: ''
                    );
                    $mail_placeholders = array(
                        "%customer_name%",
                        "%quotation_id%",
                        "%total_amount%",
                        "%advance_amount%",
                        "%company_name%",
                    );
                    $final_description = str_replace($mail_placeholders, $mail_body, $template_data->description);
                    $inv_mail_params = array();
                    $inv_mail_params = array(
                        'customer_email' => $customer['email'] ?: '',
                        'invoice_pdf' => $invoice_pdf,
                        'description' => $final_description,
                        'subject' => @$template_data->name ?: '',
                    );
                    $this->send_mail_invoice($inv_mail_params, $quotation,  $customer,  $company);
                    // unlink(APPPATH . '../uploads/invoice/' . $invoice_pdf);
                }
            }
        } catch (Exception $e) {

        }
    }

    function send_mail_worksheet($params = array(), $quotation,  $customer,  $company)
    {
        try {
            // $worksheet = array();
            // // $worksheet[] = $params['worksheet_pdf'];
            // // //$worksheet[] = APPPATH . '../uploads/invoice/' . $params['invoice_pdf'];
            $template = $this->new_template($params['description'], $quotation,  $customer,  $company);
            $subject = $params['subject'] ?: 'Order Worksheet';
            // $pdf_link = implode(',',  $worksheet);
            $created_at = date('Y-m-d H:i:s');



            $this->db->insert('mail_data_customer', [
                'to_mail' => $params['email'],
                'from_mail' => $company['company_name'],
                'cc_mail' => $params['cc_email'] ?? '',
                'subject' =>  $subject . '-' . $quotation->invoiceno,
                'content' => $template,
                'company_id' => $company['company_id'] ?? null,
                'pdf_link' =>  $params['worksheet_pdf'],
                'created_at' => $created_at,
            ]);



            //echo "<br/>subject=".$subject;
            //echo "<br/>template:".$template; 

            // $this->mailmodel->send_email_html($subject, $template, $params['email'], 'Blindtex', $worksheet);// BY MUJEEB 20/03/2024
            // $this->mailmodel->send_email_html($subject, $template, $params['email'], 'Blindtex');
        } catch (Exception $e) {
            echo $e;
        }
    }
    function send_mail_invoice($params = array(), $quotation,  $customer,  $company)
    {

        try {
            $invoice = array();
            $invoice[] =  $params['invoice_pdf'];
            $template = $this->new_template($params['description'], $quotation,  $customer,  $company);
            $subject = $params['subject'] ?: 'Order Invoice';
            // $this->mailmodel->send_email_html($subject, $template, $params['customer_email'], 'Blindtex', $invoice); // BY MUJEEB 20/03/2024

            $to_mail = $params['customer_email'];
            $from_mail = $company['company_name'];
            $subject = $subject;
            $content = $template; // or render it if it's a template name
            $pdf_link = implode(',', $invoice);
            $created_at = date('Y-m-d H:i:s');

            $this->db->insert('mail_data_customer', [
                'to_mail' => $to_mail,
                'from_mail' => $from_mail,
                'cc_mail' => $company['sales_person_email'] ?? '',
                'reply_mail' => $company['reply_mail'] ?? '',

                'subject' => $subject . '-' . $quotation->invoiceno,
                'content' => $content,
                'company_id' => $company['company_id'] ?? null,
                'pdf_link' => $pdf_link,
                'created_at' => $created_at,
            ]);
        } catch (Exception $e) {
            echo $e;
        }
    }


    function generate_pdf_order_invoice($data = array())
    {
        try {
            $this->load->library('pdf');

            // Load footer HTML as a string
            $data['footer'] = $this->load->view('pdf_templates/footer-terms-conditions', [], true);

            // Main content should include a placeholder for the footer
            $html = $this->load->view('pdf_templates/confirmed_order', $data, true);
            $real_path = base_url() . '/uploads/invoice/';
            // Save file
            $path = APPPATH . '../uploads/invoice/';
            $invoice_no = str_replace(['/', '\\'], '-', $data['quotation']->invoiceno);
            $file_name = 'INV-QTN-' .  $invoice_no;
            $pdf = $this->pdf->createPDF($html, $file_name, $path);

            return  'uploads/invoice/' . $pdf . '.pdf';
        } catch (Exception $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            return false;
        }
    }

    function generate_balance_pdf_order_invoice($data = array())
    {
        try {
            $this->load->library('pdf');
             $data['footer'] = $this->load->view('pdf_templates/footer-terms-conditions', [], true);
            $html = $this->load->view('pdf_templates/confirmed_order_balance', $data, true);
            $path = APPPATH . '../uploads/invoice/';
            $invoice_no = str_replace(['/', '\\'], '-', $data['quotation']->invoiceno);
            $file_name = 'INV-QTN-' .  $invoice_no;
            $pdf = $this->pdf->createPDF($html, $file_name, $path);
            return  'uploads/invoice/' . $pdf . '.pdf';
        } catch (Exception $e) {
        }
    }

    function generate_pdf_worksheet($data = array())
    {
        try {
            $this->load->library('pdf');
            $html = $this->load->view('pdf_templates/worksheet_confirmed_order', $data, true);
            $file_path = base_url() . 'uploads/worksheet/';
            $path = APPPATH . '../uploads/worksheet/';
            $invoice_no = str_replace(['/', '\\'], '-', $data['quotation']->invoiceno);
            $file_name = 'WS-QTN-' . $invoice_no;
            $pdf = $this->pdf->createPDF($html, $file_name, $path, '', '', 'landscape');
            return  'uploads/worksheet/' . $pdf . '.pdf';
        } catch (Exception $e) {
        }
    }


    /*
			 send Custom Message to User
			*/

    function send_mail_customer($params = array())
    {
        try {

            $this->db->where('a.id', @$params['sp_id'])
                ->join('admin_users u', 'u.id=a.company', 'left')
                ->select('a.name as salesperson_name,a.image as salesperson_image,u.name as company_name,u.image as company_image,u.address as company_address,u.phone as company_phone,u.email as company_email');
            $data = $this->salespersonmodel->gets_data()->row_array();
            $c_image = @$data['company_image'] ? base_url() . 'uploads/users/' . $data['company_image'] : base_url() . 'uploads/placeholder/image1.png';
            $msg = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
            <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
            <title>Mailto</title>
            <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
            <style type="text/css">
            html { -webkit-text-size-adjust: none; -ms-text-size-adjust: none;}
            
                @media only screen and (min-device-width: 750px) {
                    .table750 {width: 750px !important;}
                }
                @media only screen and (max-device-width: 750px), only screen and (max-width: 750px){
                  table[class="table750"] {width: 100% !important;}
                  .mob_b {width: 93% !important; max-width: 93% !important; min-width: 93% !important;}
                  .mob_b1 {width: 100% !important; max-width: 100% !important; min-width: 100% !important;}
                  .mob_left {text-align: left !important;}
                  .mob_soc {width: 50% !important; max-width: 50% !important; min-width: 50% !important;}
                  .mob_menu {width: 50% !important; max-width: 50% !important; min-width: 50% !important; box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2); }
                  .mob_center {text-align: center !important;}
                  .top_pad {height: 15px !important; max-height: 15px !important; min-height: 15px !important;}
                  .mob_pad {width: 15px !important; max-width: 15px !important; min-width: 15px !important;}
                  .mob_div {display: block !important;}
                 }
               @media only screen and (max-device-width: 550px), only screen and (max-width: 550px){
                  .mod_div {display: block !important;}
               }
                .table750 {width: 750px;}
            </style>
            </head>
            <body style="margin: 0; padding: 0;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
                 <tr>
                   <td align="center" valign="top">   			
                       <!--[if (gte mso 9)|(IE)]>
                     <table border="0" cellspacing="0" cellpadding="0">
                     <tr><td align="center" valign="top" width="750"><![endif]-->
                       <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750" style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
                           <tr>
                           <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                               <td align="center" valign="top" style="background: #ffffff;">
            
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                                 <tr>
                                    <td align="right" valign="top">
                                       <div class="top_pad" style="height: 25px; line-height: 25px; font-size: 23px;">&nbsp;</div>
                                    </td>
                                 </tr>
                              </table>
            
                              <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                 <tr>
                                    <td align="left" valign="top">
                                       <div style="height: 39px; line-height: 39px; font-size: 37px;">&nbsp;</div>
                                       <a href="#" target="_blank" style="display: block; max-width: 128px;">
                                          <img src="' . $c_image . '" alt="img" width="128" border="0" style="object-fit:contain;display: block; width: 128px;max-height:50px" />
                                       </a>
                                       <div style="height: 33px; line-height: 33px; font-size: 71px;">&nbsp;</div>
                                    </td>
                                 </tr>
                              </table>
            
                              <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                 <tr>
                                    <td align="left" valign="top">
                                       <font face="Source Sans Pro, sans-serif" color="#1a1a1a" style="font-size: 25px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">
                                          <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 32px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">Greetings,</span>
                                       </font>
                                       <div style="height: 8px; line-height: 8px; font-size: 11px;">&nbsp;</div>
                                       <font face="Source Sans Pro, sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                          <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">' . $params['message'] . '</span>
                                       </font>
                                       <div style="height: 20px; line-height: 20px; font-size: 18px;">&nbsp;</div>

                                       <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                                        <!-- Button -->
                                        <font face="Source Sans Pro, sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                          <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">Regards,</span>
                                       </font>
                                       <div style="height: 55px; line-height: 75px; font-size: 73px;">&nbsp;</div>
                                    </td>
                                 </tr>
                              </table>
            
            
                              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                              <tr>
                              <td align="center" valign="top">
                                 <div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
                                 <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                    <tr>
                                       <td align="center" valign="top">
                                          <table cellpadding="0" cellspacing="0" border="0" width="78%" style="min-width: 300px;">
                                             <tr>
                                                
                                             </tr>
                                          </table>                                          
                                          
                                          <!---->
                                          <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                              </table>  
            
                           </td>
                           <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                        </tr>
                     </table>
                     <!--[if (gte mso 9)|(IE)]>
                     </td></tr>
                     </table><![endif]-->
                  </td>
               </tr>
            </table>
            </body>
            </html>';
            $this->mailmodel->send_email_html(@$params['subject'], $msg, @$params['customer_email'], 'Blindtex', []);
        } catch (Exception $e) {
        }
    }

    function get_mail_template($desc = '')
    {
        $template = '<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<style type="text/css">
				/* CLIENT-SPECIFIC STYLES */
				body,
				table,
				td,
				a {
					-webkit-text-size-adjust: 100%;
					-ms-text-size-adjust: 100%;
				}
		
				table,
				td {
					mso-table-lspace: 0pt;
					mso-table-rspace: 0pt;
				}
		
				img {
					-ms-interpolation-mode: bicubic;
				}
		
				/* RESET STYLES */
				img {
					border: 0;
					height: auto;
					line-height: 100%;
					outline: none;
					text-decoration: none;
				}
		
				table {
					border-collapse: collapse !important;
				}
		
				body {
					height: 100% !important;
					margin: 0 !important;
					padding: 0 !important;
					width: 100% !important;
				}
		
				/* iOS BLUE LINKS */
				a[x-apple-data-detectors] {
					color: inherit !important;
					text-decoration: none !important;
					font-size: inherit !important;
					font-family: inherit !important;
					font-weight: inherit !important;
					line-height: inherit !important;
				}
		
				/* MOBILE STYLES */
				@media screen and (max-width:600px) {
					h1 {
						font-size: 32px !important;
						line-height: 32px !important;
					}
				}
		
				/* ANDROID CENTER FIX */
				div[style*="margin: 16px 0;"] {
					margin: 0 !important;
				}
			</style>
		</head>
		
		<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
			<!-- HIDDEN PREHEADER TEXT -->
			<!-- <div
											style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Arial, Helvetica, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
											We\'re thrilled to have you here! Get ready to dive into your new account. </div> -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<!-- LOGO -->
				<tr>
					<td bgcolor="#f4f4f4" align="center">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
							<tr>
								<td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
							<tr>
								<td bgcolor="#ffffff" align="center" valign="top"
									style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Arial, Helvetica, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 1px; line-height: 48px;">
		
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
		
							<tr>
								<td bgcolor="#ffffff" align="left"
									style="padding: 0px 30px 0px 30px; color: #666666; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
									<p>
										' . @$desc . '</p>
								</td>
							</tr>
		
						</table>
					</td>
				</tr>
									<tr>
										<td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
												<tr>
													<td bgcolor="#f4f4f4" align="center"
														style="padding: 0px 30px 30px 30px; color: #666666; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;">
														<br>
							
													</td>
												</tr>
											</table>
										</td>
									</tr>
			</table>
		</body>
		
		</html>';
        return $template;
    }

    function new_template($params = array(), $quotation, $customer, $company)
    {
        $template = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Invoice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
@media only screen and (max-width: 600px) {
  .body {
    padding: 20px 15px;
  }

  .info-box {
    width: 100% !important;
    margin-bottom: 20px;
  }

  .summary strong {
    width: auto;
    display: block;
    margin-bottom: 4px;
  }
}
</style>
</head>
<body style="margin: 0; padding: 0; background: #f4f4f4; font-family: "Inter", sans-serif;">
  <div class="container" style="max-width: 680px; width: 100%; margin: 30px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 8px rgba(0,0,0,0.06);">
    <div class="header" style="padding: 20px; text-align: center; background: #fafafa; border-bottom: 1px solid #eaeaea;">
      <img src="' . SITE_URL() . '/uploads/users/' . $company['company_image'] . '" alt="Company Logo" style="max-height: 60px;">
    </div>

    <div class="body" style="padding: 20px 30px;">
      <div class="greeting" style="font-size: 16px; margin-bottom: 20px;">
        Hi ' . htmlspecialchars($quotation->customer_name) . ',<br><br>
        Thank you for your order. Please find  attached pdf with your ' . ($quotation->confirm == 1 ? "invoice" : "quotation") . ' details below.
      </div>

      <div class="summary" style="background: #e8f0fe; border-left: 4px solid #007bff; padding: 18px; border-radius: 8px; margin-bottom: 30px; font-size: 14px;">
        <div style="margin: 5px 0;"><strong style="display: inline-block; width: 120px;">Invoice No:</strong> ' . htmlspecialchars($quotation->invoiceno) . '</div>
        <div style="margin: 5px 0;"><strong style="display: inline-block; width: 120px;">Status:</strong> ' . ($quotation->confirm == 1 ? htmlspecialchars($quotation->status) : "Pending") . '</div>
        <div style="margin: 5px 0;"><strong style="display: inline-block; width: 120px;">Date:</strong> ' . htmlspecialchars($quotation->updated_date ?: $quotation->created_date) . '</div>
        <div style="margin: 5px 0;"><strong style="display: inline-block; width: 120px;">Salesperson:</strong> ' . htmlspecialchars($quotation->sales_person_name) . '</div>
      </div>

      <div class="info-boxes" style="display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; margin-bottom: 30px;">
        <div class="info-box" style="background: #f9f9f9; border-radius: 8px; padding: 15px; width: 48%; box-sizing: border-box; font-size: 14px;">
          <h4 style="margin: 0 0 10px; font-size: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">From</h4>
          ' . htmlspecialchars($company['company_name']) . '<br>
          ' . $company['company_address'] . '<br>
          Phone: ' . htmlspecialchars($company['company_phone']) . '<br>
          Email: <a href="mailto:' . htmlspecialchars($company['company_email']) . '" style="color: #007bff; text-decoration: none;">' . htmlspecialchars($company['company_email']) . '</a>
        </div>
        <div class="info-box" style="background: #f9f9f9; border-radius: 8px; padding: 15px; width: 48%; box-sizing: border-box; font-size: 14px;">
          <h4 style="margin: 0 0 10px; font-size: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">To</h4>
          ' . htmlspecialchars($quotation->customer_name) . '<br>
          ' . $customer->address ?: '-' . '<br>
          Phone: ' . htmlspecialchars($quotation->customer_phone) . '<br>
          Email: <a href="mailto:' . htmlspecialchars($quotation->customerEmail) . '" style="color: #007bff; text-decoration: none;">' . htmlspecialchars($quotation->customerEmail) . '</a>
        </div>
      </div>
    </div>

    <div class="footer" style="text-align: center; font-size: 13px; padding: 20px; background: #fafafa; border-top: 1px solid #eee; color: #555;">
      Need help? Contact us at <a href="mailto:' . htmlspecialchars($company['company_email']) . '">' . htmlspecialchars($company['company_email']) . '</a><br>
      &copy; ' . date('Y') . ' ' . htmlspecialchars($company['company_name']) . '
    </div>
  </div>
</body>
</html>';

        return $template;
    }




    function send_order_to_blindata($post_obj, $user_id)
    {

        $this->db->where('a.id', $post_obj["user_id"])
            ->join('admin_users u', 'u.id=a.company', 'left')
            ->select('u.name as company_name, u.image as company_image, u.address as company_address, u.phone as company_phone, u.email as company_email, u.company_code as company_code, a.name as salesperson_name');
        $company = $this->salespersonmodel->gets_data()->row_array();
        $invoice_number = isset($post_obj["invoiceno"])
            ? preg_replace('/\D/', '', $post_obj["invoiceno"])
            : '';
        // $filename = "BA" . date("YmdHis") . "-mr";

        $filename = str_replace("/", "", $post_obj["invoiceno"]);
        $CompAccCode_ = !empty($company["company_code"]) ? $company["company_code"] : "INN002";
        $CompanyName_ = !empty($company["company_name"]) ? $company["company_name"] : "Innov8 Sampling";

        $FileTransfName_ = $filename . ".xml";
        $CreateDate_ = date("d/m/Y");
        $CreateTime_ = date("H:i:s");
        $PurchaseOrderNumber_ = preg_replace('/\D/', '', $filename); // ADDED BY MUJEEB FOR PURCHASE ORDER
        //$PurchaseOrderNumber_ =  '12233';
        $CustomerReference_ = $post_obj["invoiceno"] ?  $post_obj["invoiceno"] : "Demo Customer";
        $OrderDate_ = date("d/m/Y");
        $OrderNote_ = $post_obj["remarks"] ? $post_obj["remarks"] : "from Blindex app By Athira Blindtex";

        $generated_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $generated_xml .= "<Transfer>\n";
        $generated_xml .= "  <TEDISenderInformation_Details CompAccCode_=\"$CompAccCode_\" CompanyName_=\"$CompanyName_\" FileTransfName_=\"$FileTransfName_\" CreateDate_=\"$CreateDate_\" CreateTime_=\"$CreateTime_\">\n";
        $generated_xml .= "    <TEDIOrderHeader_Details PurchaseOrderNumber_=\"$PurchaseOrderNumber_\" CustomerReference_=\"$CustomerReference_\" OrderDate_=\"$OrderDate_\" OrderNote_=\"$OrderNote_\">\n";

        $i = 0;
        foreach ($post_obj["rooms"] as $room) {
            foreach ($room["windows"] as $window) {
                ++$i;

                $blind_code = $this->get_blind_code($window["product"]);
                $fabric_code = $this->get_fabric_code($window["product"]);

                $OrderDetailID_ = "$i";
                $BlindTypeCode_ = $blind_code ?: "";
                $BlindTypeDescription_ = $window["priceBandVersion"];
                $MetricMeasurement_ = "0";
                $FabricCode_ = $fabric_code ?: "";
                $FabricDescription_ = htmlspecialchars(str_replace(["\r", "\n"], ' ', $window["product_name"]), ENT_QUOTES | ENT_XML1);
                $Width_ = ($window["unit"] == "mm") ? $window["width"] : $window["width"] * 10;
                $Drop_ = ($window["unit"] == "mm") ? $window["height"] : $window["height"] * 10;
                $InstallHeight_ = $window["chain_drop"] ?: "0";
                $MeasureName_ = $window["measurmenttype"];
                $Quantity_ = $window["activeItem"];
                $Location_ = $window["window_name"];
                $WidthDisplay_ = $Width_;
                $DropDisplay_ = $Drop_;
                $CustomerOrderReference_ = $window["note"];
                $OrderID_ = $filename;
                $IsDirectDeliveryBySupplier_ = "True";
                $DelEmail_ = $post_obj["customerEmail"];
                $DelPhone_ = $post_obj["customer_phone"];
                $DelFirstName_ = $post_obj["customer_name"];
                $DelLine1_ = $post_obj["customer_name"];
                $CustomerAddressLine1_ = $post_obj["customer_name"];
                $DelLine2_ = $post_obj["customer_name"];
                $CustomerAddressLine2_ = $post_obj["customer_name"];
                $Salesperson_ = @$company["salesperson_name"];

                $generated_xml .= "      <TEDIOrderDetails_Details OrderDetailID_=\"$OrderDetailID_\" BlindTypeCode_=\"$BlindTypeCode_\" BlindTypeDescription_=\"$BlindTypeDescription_\" MetricMeasurement_=\"$MetricMeasurement_\" FabricCode_=\"$FabricCode_\" FabricDescription_=\"$FabricDescription_\" Width_=\"$Width_\" Drop_=\"$Drop_\" InstallHeight_=\"$InstallHeight_\" MeasureName_=\"$MeasureName_\" Quantity_=\"$Quantity_\" Location_=\"$Location_\" WidthDisplay_=\"$WidthDisplay_\" DropDisplay_=\"$DropDisplay_\" CustomerOrderReference_=\"$CustomerOrderReference_\" OrderID_=\"$OrderID_\" IsDirectDeliveryBySupplier_=\"$IsDirectDeliveryBySupplier_\" DelEmail_=\"$DelEmail_\" DelPhone_=\"$DelPhone_\" DelFirstName_=\"$DelFirstName_\" DelLine1_=\"$DelLine1_\" CustomerAddressLine1_=\"$CustomerAddressLine1_\" DelLine2_=\"$DelLine2_\" CustomerAddressLine2_=\"$CustomerAddressLine2_\" Salesperson_=\"$Salesperson_\">\n";

                foreach ($window["extras"] as $extra) {
                    $OptionName_ = $extra["extra_name"];
                    $Choice_ = $extra["sub_extra_name"];
                    $Choice_id = $extra["sub_extra"];
                    $ChoiceCode_ = $this->get_extra_code($Choice_id);
                    $generated_xml .= "        <TEDIOptionList_Details OptionName_=\"$OptionName_\" Choice_=\"$Choice_\" ChoiceCode_=\"$ChoiceCode_\"/>\n";
                }

                $generated_xml .= "      </TEDIOrderDetails_Details>\n";
            }
        }

        $generated_xml .= "    </TEDIOrderHeader_Details>\n";
        $generated_xml .= "  </TEDISenderInformation_Details>\n";
        $generated_xml .= "</Transfer>\n";

        $upload_folder = APPPATH . 'uploads/EDI/';

        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0755, true);
        }

        if (!is_writable($upload_folder)) {
            die("Folder not writable: " . realpath($upload_folder));
        }

        $source_file_path = $upload_folder . 'EDI-' . $filename . ".xml";

        $myfile = fopen($source_file_path, "w") or die("Unable to open file: " . $source_file_path);
        if (fwrite($myfile, $generated_xml) === false) {
            fclose($myfile);
            die("Unable to write content to file: " . $source_file_path);
        }

        fclose($myfile);
        chmod($source_file_path, 0644);

        $this->load->library('ediftp');
        // return true;
        return $this->ediftp->upload_file($source_file_path, 'EDI-' . $filename . ".xml");
    }

    function ftptest()
    {
        $this->load->library('ediftp');
        $upload_folder = APPPATH . '../uploads/EDI/testfile.xml';
        return $this->ediftp->upload_file($upload_folder, "testfile.xml");
    }





    function get_blind_code1($blind_name)
    {
        $this->db->where("a.name", $blind_name)
            ->select("a.blind_code as blind_code");
        $result = $this->producttypemodel->gets_data()->row_array();
        return $result["blind_code"];
    }

    function get_blind_code($blind_id)
    {
        // Step 1: Get product record
        $this->db->where('id', $blind_id);
        $product = $this->db->get('product')->row_array();

        if (!$product) {
            return null; // Product not found
        }

        // Step 2: Check if product itself has blind_code
        if (!empty($product['blind_code'])) {
            return $product['blind_code'];
        }

        $sub_product_type_id = $product['sub_product_type'];
        $parent_id = $product['product_type'];

        // Step 3: Check sub_product_type's blind_code
        $this->db->where('id', $sub_product_type_id);
        $sub_product = $this->db->get('product_type')->row_array();

        if (!empty($sub_product['blind_code'])) {
            return $sub_product['blind_code'];
        }

        // Step 4: Check parent product_type's blind_code
        $this->db->where('id', $parent_id);
        $parent_product = $this->db->get('product_type')->row_array();

        return !empty($parent_product['blind_code']) ? $parent_product['blind_code'] : null;
    }



    function get_fabric_code($product_id)
    {
        $this->db->where("a.id", $product_id)
            ->select("a.fabric_code as fabric_code");
        $result = $this->productmodel->gets_data()->row_array();
        return $result["fabric_code"];
    }

    function get_extra_code($extra_name)
    {
        $this->db->where("a.id", $extra_name)
            ->select("a.extra_code as extra_code");
        $result = $this->extrasmodel->gets_data()->row_array();
        return $result["extra_code"] ?? "DEFAULT_CODE";
    }



    function test_service($quote_id = 89)
    {
        $this->load->library('ediftp');
        $this->ediftp->test();
        echo "<br/>TEST 123";

        $quotation = $this->quotationmodel->get_row($quote_id);

        print_r($quotation);
        echo "<br/><br/> Rooms = ";
        $rooms = $this->quotationmodel->get_sub_rooms_quotation($quote_id);
        print_r($rooms);
    }
}
