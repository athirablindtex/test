<?php if (!defined('BASEPATH')) {
	exit('No direct script allowed');
}
class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		//$this->load->model(array('ordermodel','usermodel','productmodel'));
		$this->load->model(array('servicemodel', 'mailtemplatemodel', 'usersmodel', 'salespersonmodel', 'quotationmodel', 'producttypemodel'));
	}
	public function index()
	{
		$data['page'] = 'Dashboard';
		$data['active'] = 'dashboard';
		$data['content'] = 'admin/home';
		$this->db->where('user_group', $this->config->item('company_id'));
		$data['companies'] = $this->usersmodel->gets_all()->result();
		$this->load->vars($data);
		$this->load->view('admin/include/template');
	}
	public function wrong_turn()
	{
		$data['page'] = 'Not Found';
		$data['active'] = 'dashboard';
		$data['content'] = 'admin/permission-denied';
		$this->load->vars($data);
		$this->load->view('admin/include/template');
	}

	public function get_data_dashboard()
	{
		$filter = $this->input->post();
		$data = [];
		$data['companies'] = $this->usersmodel->get_total_companies_count();
		$data['quotation_sent'] = $this->quotationmodel->get_sent_quotation_count($filter);
		$data['quotation_confirmed'] =  $this->quotationmodel->get_confirmed_quotation_count($filter);
		$data['salesperson'] = $this->salespersonmodel->get_total_salespersons_count($filter);
		$data['sales'] = $this->quotationmodel->get_sales_amount($filter);
		$data['company_quataion_chart'] = $this->get_company_quataion_chart($filter);
		$data['product_type_chart'] = $this->get_product_type_chart($filter);
		$data['quotation_stats'] =  $this->get_quotation_stats_chart($filter);
		$data['order_per_month_graph'] = $this->get_order_per_month_graph($filter);
		$data['sales_per_month_graph'] = $this->get_sales_per_month_graph($filter);
		$data['top_companies'] = $this->get_top_companies_table($filter);
		echo json_encode($data);
	}

	function get_company_quataion_chart($filter = [])
	{
		$colors = array(
			'#4ce670',
			'#67abff',
			'#20cc9c'
		);
		$total_count = 0;
		$data = array();
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->limit(5);
		$this->db->select('u.name as company_name, COUNT(a.id) as count');
		$this->db->group_by('sp.company');
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$companies = $this->quotationmodel->gets_data()->result_array();
		$labels = array_column($companies, 'company_name');
		$data = array_column($companies, 'count');
		foreach ($companies as $c) {
			$total_count += (int)$c['count'];
		}
		$datasets[] = array(
			'label' => '# of Quotations',
			'data' => $data,
			'backgroundColor' => $colors,
			'borderWidth' => 1
		);
		$chart = [
			'type' => 'pie',
			'data' => [
				'datasets' => $datasets,
				'labels' => $labels
			],
		];
		return ['total_count' => $total_count, 'chart' => $chart];
	}

	function get_product_type_chart($filter = [])
	{
		$colors = array(
			'#1ee882',
			'#ffb23f',
			'#20cc9c',
		);
		$total_count = 0;
		$data = array();
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->limit(5);
		$this->db->select('pt.name as product_type_name, COUNT(DISTINCT(q.id)) as count');
		$this->db->group_by('p.product_type');
		$this->db->join('product p', 'p.id=a.product', 'left');
		$this->db->join('product_type pt', 'pt.id=p.product_type', 'left');
		$this->db->join('quotation_rooms qr', 'qr.id=a.room', 'left');
		$this->db->join('quotation q', 'q.id=qr.quotation', 'left');
		$this->db->join('sales_person sp', 'sp.id=q.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$product_types = $this->quotationmodel->gets_data_window()->result_array();
		$labels = array_column($product_types, 'product_type_name');
		$data  = array_column($product_types, 'count');
		foreach ($product_types as $pt) {
			$total_count += (int)$pt['count'];
		}
		$datasets[] = array(
			'label' => '# of Quotations',
			'data' => $data,
			'backgroundColor' => $colors,
			'borderWidth' => 1
		);
		$chart = [
			'type' => 'doughnut',
			'data' => [
				'datasets' => $datasets,
				'labels' => $labels
			],
		];
		return ['total_count' => $total_count, 'chart' => $chart];
	}

	function get_quotation_stats_chart($filter = [])
	{
		$colors = array(
			'#ff5d7e',
			'#5bf5ca',
			'#69c3c4',
		);
		$total_count = 0;
		$data = array();
		if (@$filter['company']) {
			$this->db->where('sp.company', @$filter['company']);
		}
		$this->db->limit(5);
		$this->db->select('a.status, COUNT(a.id) as count');
		$this->db->group_by('a.status');
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$statuses = $this->quotationmodel->gets_data()->result_array();
		$labels = array_column($statuses, 'status');
		$data = array_column($statuses, 'count');
		foreach ($statuses as $st) {
			$total_count += (int)$st['count'];
		}
		$datasets[] = array(
			'label' => '# of Quotations',
			'data' => $data,
			'backgroundColor' => $colors,
			'borderWidth' => 1
		);
		$chart = [
			'type' => 'pie',
			'data' => [
				'datasets' => $datasets,
				'labels' => $labels
			],
		];
		return ['total_count' => $total_count, 'chart' => $chart];
	}

	function get_order_per_month_graph($filter = [])
	{
		$month_data = $this->get_months_of_year();
		$labels = array();
		$datasets = array();
		$data = array();
		foreach ($month_data as $dt) {
			$temp_filter = [];
			$temp_date = $dt->format("Y-m-d");
			$temp_start_date = date('Y-m-1', strtotime($temp_date));
			$temp_end_date = date('Y-m-t', strtotime($temp_date));
			$temp_filter['a.created_date >='] = $temp_start_date;
			$temp_filter['a.created_date <='] = $temp_end_date;
			if (@$filter['company']) {
				$this->db->where('sp.company', @$filter['company']);
			}
			$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
			$this->db->join('admin_users u', 'u.id=sp.company', 'left');
			$this->db->where($temp_filter);
			$count = $this->quotationmodel->gets_data()->num_rows();
			$data[] = (int)$count;
			$labels[] = date('M', strtotime($temp_start_date));
		}
		$datasets[] = array(
			'label' => '# of Quotation',
			'data' => $data,
			'backgroundColor' => [
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4',
				'#67abff',
				'#69c3c4'

			],
			'borderWidth' => 1
		);
		$chart = [
			'type' => 'bar',
			'data' => [
				'datasets' => $datasets,
				'labels' => $labels
			],
			'options' => [
				'legend' => [
					'display' => false
				]
			]
		];
		return $chart;
	}

	function get_sales_per_month_graph($filter = [])
	{
		$month_data = $this->get_months_of_year();
		$labels = array();
		$datasets = array();
		$data = array();
		foreach ($month_data as $dt) {
			$temp_filter = [];
			$temp_date = $dt->format("Y-m-d");
			$temp_start_date = date('Y-m-1', strtotime($temp_date));
			$temp_end_date = date('Y-m-t', strtotime($temp_date));
			$temp_filter['a.created_date >='] = $temp_start_date;
			$temp_filter['a.created_date <='] = $temp_end_date;
			$this->db->where($temp_filter);
			$amount = $this->quotationmodel->get_sales_amount($filter);
			$data[] = (float)round($amount, 2);
			$labels[] = date('M', strtotime($temp_start_date));
		}
		$datasets[] = array(
			'label' => '# of Quotation',
			'data' => $data,
			'backgroundColor' => [
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
				'#20cc9c',
				'#69c3c4',
			],
			'borderWidth' => 1
		);
		$chart = [
			'type' => 'bar',
			'data' => [
				'datasets' => $datasets,
				'labels' => $labels
			],
			'options' => [
				'legend' => [
					'display' => false
				]
			]

		];
		return $chart;
	}

	function get_top_companies_table($filter = [])
	{
		$html = '';
		$this->db->limit(10);
		$this->db->select('u.id,u.name as company_name,u.image,COUNT(a.id) as count,SUM(sub_total) as amount');
		$this->db->order_by("amount", "desc");
		$this->db->group_by('sp.company');
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$quotations = $this->quotationmodel->gets_data()->result_array();
		if (count($quotations) > 0) {
			$html .= '<table class="table table-striped">';
			$html .= ' <thead>
                        <tr>
							<td>ID</td>
							<td>Company</td>
							<td>Image</td>
                            <td>Quotations</td>
                            <td>Amount</td>
                        </tr>
                      <thead> 
					  <tbody>';
			foreach ($quotations as $q) {
				$profile_image = is_file('uploads/users/' . $q['image']) ? base_url() . 'uploads/users/' . $q['image'] : base_url() . 'uploads/placeholder/image1.png';
				$html .= '<tr>';
				$html .= '<td>' . @$q['id'] . '</td>';
				$html .= '<td>' . @$q['company_name'] . '</td>';
				$html .= '<td><img src="' . $profile_image . '" alt="..." class="avatar-img rounded" style="width:40px;height:40px;"></td>';
				$html .= '<td>' . @$q['count'] . '</td>';
				$html .= '<td>₹' . round(@$q['amount'], 2) . '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody></table>';
		} else {
			$html .= '<div class="alert alert-danger" role="alert"> No Data found </div>';
		}
		return $html;
	}

	function get_months_of_year()
	{
		$end_year = date('Y-m-d');
		$start_year = date('Y-m-d', strtotime('-1 year'));
		$start_date = date('Y-m-1', strtotime($start_year));
		$end_date = date('Y-m-t', strtotime($end_year));
		$data = array();
		$start    = new \DateTime($start_date);
		$start->modify('first day of this month');
		$end      = new \DateTime($end_date);
		$end->modify('first day of next month');
		$interval = \DateInterval::createFromDateString('1 month');
		$period   = new \DatePeriod($start, $interval, $end);
		return $period;
	}




	function test()
	{
		// $this->load->helper(array('cookie'));
		// echo $ck = get_cookie($this->config->item('site_cookie_admin'));

		//$this->servicemodel->send_quotation_notification_service(14);


		$this->db->where('category', 'Invoice');
		$template_data = $this->mailtemplatemodel->get_latest_row();

		// print_r($template_data);
		 exit;

		$data = array(
			"customer_name" => "John Smith",
		);

		$placeholders = array(
			"%customer_name%"
		);
		$final_description = str_replace($placeholders, $data, $template_data->description);
		$inv_mail_params = array(
			'customer_email' => 'namshadnpt@gmail.com' ?: '',
			'invoice_pdf' => '',
			'description' => $final_description,
			'subject' => 'Test'
		);
		$this->servicemodel->send_mail_invoice($inv_mail_params);
	}
	function test_pdf()
	{
		$data = $this->servicemodel->send_quotation_notification_service(4);
	}
}
