<?php
defined('BASEPATH') or exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class Welcome extends CI_Controller
{


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		//$this->load->view('welcome_message');
		redirect('admin/login');
	}
	function test()
	{
		$date = 1598326387315;
		//$date/=1000;
		//echo date('Y/m/d',strtotime(@$date));
		$seconds = ceil($date / 1000);
		echo date("d-m-Y", $seconds);
	}
	public function test2()
	{
		//$post=file_get_contents('php://input');
		$quote_id = $_GET["id"];
		$this->load->model(array('servicemodel'));
		$this->servicemodel->test_service($quote_id);
	}


	public  function sendFirebaseNotification()
	{

		$this->load->library('Firebase');
		$response = $this->firebase->sendNotification(
			'fKaT8ddjQvqMj6RJVD7-9L:APA91bE9DBfD3yBOchr22r3B7sj6_DLAjvFK1XZhhoaI6DSbJ7bojDJRM59o8Z8itT-Gn24ExoXupGp2aB6HuB3i2i6sh0uXvmYmCroeOlQMgqPfxYJQGec',
			'Test Title',
			'Hello from CodeIgniter using Firebase HTTP v1!'
		);

		echo $response;
	}
	function mycore_hashing_user($pass = '')
	{
		if ($pass != '') {
			return crypt($this->config->item('user_salt'), hash('sha256', $pass));
		}
	}

	public function get_extras_data()
	{

		$this->load->model('Extrasmodel', 'extrasmodel');

		$this->db->select('id as row_id');
		$this->db->where('deleted_at IS NOT NULL');
		// if ($last_synch_date) {
		// $this->db->where('deleted_at >', $last_synch_date);
		// }
		$cur_all = $this->gets_data('extras')->result_array();

		$data['extras_delete'] = array_column($cur_all, 'row_id');
	}

public function pdf()
{
    $this->load->library('pdf');

    $imgPath = FCPATH . 'assets/images/';

    $mpdf = new \Mpdf\Mpdf([
        'mode'              => 'utf-8',
        'format'            => 'A4',
        'margin_top'        => 35,   // ⬅️ space for header
        'margin_bottom'     => 30,   // ⬅️ space for footer
        'margin_left'       => 10,
        'margin_right'      => 10,
        'autoPageBreak'     => true,
    ]);

    /* ===== IMPORTANT mPDF SETTINGS ===== */
    $mpdf->simpleTables = true;
    $mpdf->packTableData = true;
    $mpdf->shrink_tables_to_fit = 0;
    $mpdf->img_dpi = 300;
    $mpdf->setAutoTopMargin = 'stretch';
    $mpdf->setAutoBottomMargin = 'stretch';

    /* ===== HEADER ===== */
	$mpdf->SetHTMLHeader('
	<div style="
		height:60px;
		background:url(' . $imgPath . 'invoice_header.png) no-repeat;
		background-size:100% 100%;
		position:relative;
	">

		<!-- LOGO -->
		<div style="
			position:absolute;
			top:18px;
			left:30px;
			width:120px;
			padding-left:20px;
			padding-top:20px;
		">
			<img src="' . $imgPath . 'logo.png" style="width:100%; height:auto;">
		</div>

		<!-- INVOICE TITLE -->
		<div style="
			position:absolute;
			top:px;
			right:30px;
			font-size:40px;
			font-weight:bold;
			color:#007279;
			white-space:nowrap;
			text-align:right;
		">
			INVOICE
		</div>

	</div>
	');

    /* ===== FOOTER ===== */
    $mpdf->SetHTMLFooter('
    <div style="
        height:120px;
        background:url(' . $imgPath . 'invoice_footer.png) no-repeat bottom center;
        background-size:100% 120px;
        position:relative;
    ">
        <div style="
            position:absolute;
            bottom:15px;
            right:40px;
            color:#ffffff;
            font-size:10px;
            line-height:1.4;
            text-align:right;
			padding-top:10px;
			padding-right:10px;
        ">
            <strong >Blindtex DMCC</strong><br>
            Unit G-02, Preatoni Tower<br>
            JLT Cluster L – Dubai<br>
            UAE<br>
            04 564 8448<br>
            salesjlt@blindtex.com
        </div>
    </div>
    ');

    /* ===== LOAD VIEW ===== */
    $html = $this->load->view('invoice_pdf', [], true);

    $mpdf->WriteHTML($html);



	$headerWithoutTitle = '
<div style="
    height:60px;
    background:url(' . $imgPath . 'invoice_header.png) no-repeat;
    background-size:100% 100%;
    position:relative;
">
    <div style="
        position:absolute;
        top:18px;
        left:30px;
        width:120px;
        padding-left:20px;
        padding-top:20px;
    ">
        <img src="' . $imgPath . 'logo.png" style="width:100%; height:auto;">
    </div>
</div>';

$footerHtml = '
 <div style="
        height:120px;
        background:url(' . $imgPath . 'invoice_footer.png) no-repeat bottom center;
        background-size:100% 120px;
        position:relative;
    ">
        <div style="
            position:absolute;
            bottom:15px;
            right:40px;
            color:#ffffff;
            font-size:10px;
            line-height:1.4;
            text-align:right;
			padding-top:10px;
			padding-right:10px;
        ">
            <strong>Blindtex DMCC</strong><br>
            Unit G-02, Preatoni Tower<br>
            JLT Cluster L – Dubai<br>
            UAE<br>
            04 564 8448<br>
            salesjlt@blindtex.com
        </div>
    </div>';


  
/* ===== LAST PAGE ===== */
$mpdf->SetHTMLHeader($headerWithoutTitle); 
$mpdf->AddPage();                           
$mpdf->SetHTMLFooter($footerHtml);         



    $termsHtml = $this->load->view('pdf_templates/footer-terms-conditions-new', [], true);
    $mpdf->WriteHTML($termsHtml);



    $mpdf->Output('invoice.pdf', 'I');
}
public function open($invoice = null, $email = null)
{
    $email = urldecode($email);

    if(!empty($invoice) && !empty($email)){

        $this->db->where('invoiceno', $invoice);
        $this->db->where('customerEmail', $email);
        $this->db->where('email_opened',0);

        $this->db->update('quotation',[
            'email_opened'=>1,
            'email_opened_time'=>date('Y-m-d H:i:s')
        ]);
    }

    header('Content-Type:image/gif');
    echo base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
}


}
