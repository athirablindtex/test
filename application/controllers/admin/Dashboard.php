<?php if (!defined('BASEPATH')) {
	exit('No direct script allowed');
}
// ini_set("display_errors", 1);
// error_reporting(E_ALL);
class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('servicemodel', 'mailtemplatemodel', 'usersmodel', 'salespersonmodel', 'quotationmodel', 'producttypemodel'));
	}
	public function index()
	{

		$data['page'] = 'Dashboard';
		$data['active'] = 'dashboard';
		$data['content'] = 'admin/home';
		$session = $this->session->userdata();

		if (isset($session['admin_type']) && $session['admin_type'] == 1) {


			$this->db->where('user_group', $this->config->item('company_id'));
			$data['companies'] = $this->usersmodel->gets_all()->result();
		} else {


			$adminCompanies = $session['admin_companies'] ?? [];

			if (!empty($adminCompanies)) {
				$this->db->where_in('id', $adminCompanies);
			} else {
				$this->db->where('id', 0);
			}

			$data['companies'] = $this->usersmodel->gets_all()->result();
		}


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
	


		$session = $this->session->userdata();


		if (empty($filter['company'])) {
        $filter['company'] = $session['admin_companies'];
		
		}
	
	

if (!empty($filter['range'])) {
   switch ($filter['range']) {

    /* ================= THIS ================= */

    case 'this_week':
        $filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $filter['to_date']   = date('Y-m-d 23:59:59');
        break;

    case 'this_month':
        $filter['from_date'] = date('Y-m-01 00:00:00');
        $filter['to_date']   = date('Y-m-d 23:59:59');
        break;

    case 'this_year':
        $filter['from_date'] = date('Y-01-01 00:00:00');
        $filter['to_date']   = date('Y-m-d 23:59:59');
        break;


    /* ================= PAST ================= */

    case 'week':
        $filter['from_date'] = date('Y-m-d 00:00:00', strtotime('monday last week'));
        $filter['to_date']   = date('Y-m-d 23:59:59', strtotime('sunday last week'));
        break;

    case 'month':
        $filter['from_date'] = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $filter['to_date']   = date('Y-m-t 23:59:59', strtotime('last day of last month'));
        break;

    case 'year':
        $lastYear = date('Y') - 1;
        $filter['from_date'] = $lastYear . '-01-01 00:00:00';
        $filter['to_date']   = $lastYear . '-12-31 23:59:59';
        break;
}
}



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
	{$colors = [
    '#1ee882', // green
    '#67abff', // blue
    '#20cc9c', // teal
    '#ffb23f', // orange
    '#9b59b6', // purple
    '#e74c3c', // red
];
		$total_count = 0;
		$data = array();
			    if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
    }
	 	  if (!empty($filter['from_date'])) {
        $this->db->where('a.created_date >=', $filter['from_date']);
    }
			if (!empty($filter['to_date'])) {
			$this->db->where('a.created_date <=', $filter['to_date']);
			}

		$this->db->limit(10);
		$this->db->select('u.name as company_name, COUNT(a.id) as count');
		$this->db->group_by('sp.company');
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$companies = [];

		$companies = $this->quotationmodel->gets_data()->result_array();
		// Print SQL query
// echo $this->db->last_query();
// exit;
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

	function get_product_type_chart1($filter = [])
	{
		$colors = array(
			'#1ee882',
			'#ffb23f',
			'#20cc9c',
		);
		$total_count = 0;
		$data = array();
			    if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
    }
	
		$this->db->limit(10);
		$this->db->select('pt.name as product_type_name, COUNT(DISTINCT(q.id)) as count');
		$this->db->group_by('p.product_type');
		$this->db->join('product p', 'p.id=a.product', 'left');
		$this->db->join('product_type pt', 'pt.id=p.product_type', 'left');
		$this->db->join('quotation_rooms qr', 'qr.id=a.room', 'left');
		$this->db->join('quotation q', 'q.id=qr.quotation', 'left');
		$this->db->join('sales_person sp', 'sp.id=q.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$product_types =[];
		$product_types = $this->quotationmodel->gets_data_window()->result_array();
		// echo "<pre>";
		// print_r($product_types);
		//  exit;
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

	function get_product_type_chart($filter = [])
	{
		$colors = array(
			'#1ee882',
			'#ffb23f',
			'#20cc9c',
			'#ff6384',
			'#36a2eb'
		);

		$total_count = 0;
		$data = array();

	 if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
    }
		  if (!empty($filter['from_date'])) {
        $this->db->where('q.created_date >=', $filter['from_date']);
    }
			if (!empty($filter['to_date'])) {
			$this->db->where('q.created_date <=', $filter['to_date']);
			}

		// Select product type and count of quotations
		$this->db->select('IFNULL(pt.name, "Others") as product_type_name, COUNT(DISTINCT(q.id)) as count');
		$this->db->join('product p', 'p.id=a.product', 'left');
		$this->db->join('product_type pt', 'pt.id=p.product_type', 'left');
		$this->db->join('quotation_rooms qr', 'qr.id=a.room', 'left');
		$this->db->join('quotation q', 'q.id=qr.quotation', 'left');
		$this->db->join('sales_person sp', 'sp.id=q.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');

		$this->db->group_by('IFNULL(pt.name, "Others")');

		$this->db->order_by('count', 'DESC');


		$this->db->limit(5);


		$product_types = $this->quotationmodel->gets_data_window()->result_array();

		// Prepare chart data
		$labels = array_column($product_types, 'product_type_name');
		$data  = array_column($product_types, 'count');

		foreach ($product_types as $pt) {
			$total_count += (int)$pt['count'];
		}

		$datasets[] = array(
			'label' => '# of Quotations',
			'data' => $data,
			'backgroundColor' => array_slice($colors, 0, count($data)),
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
	
	 if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
    }
	 	  if (!empty($filter['from_date'])) {
        $this->db->where('a.created_date >=', $filter['from_date']);
    }
				if (!empty($filter['to_date'])) {
    $this->db->where('a.created_date <=', $filter['to_date']);
}
		$this->db->limit(10);
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
			
	 if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
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
	function get_order_per_month_graph2($filter = [])
{
    $month_data = $this->get_months_of_year();
    $labels = [];
    $data = [];
    $datasets = [];

    foreach ($month_data as $dt) {

        // 🔄 Reset query each loop
        $this->db->reset_query();

        $temp_date = $dt->format("Y-m-d");
        $temp_start_date = date('Y-m-01', strtotime($temp_date));
        $temp_end_date   = date('Y-m-t', strtotime($temp_date));

        // Monthly range
        $this->db->where('a.created_date >=', $temp_start_date);
        $this->db->where('a.created_date <=', $temp_end_date);

        // Global range (past year logic)
        if (!empty($filter['from_date'])) {
            $this->db->where('a.created_date >=', $filter['from_date']);
        }
					if (!empty($filter['to_date'])) {
    $this->db->where('a.created_date <=', $filter['to_date']);
}

        // Company filter
        if (!empty($filter['company']) && $filter['company'] != 0) {
            if (is_array($filter['company'])) {
                $this->db->where_in('sp.company', $filter['company']);
            } else {
                $this->db->where('sp.company', $filter['company']);
            }
        }

        $this->db->where('a.deleted_at IS NULL', null, false);

        $this->db->from($this->table_name . ' a');
        $this->db->join('sales_person sp', 'sp.id = a.sales_person', 'left');
        $this->db->join('admin_users u', 'u.id = sp.company', 'left');

        $count = $this->db->count_all_results();

        $data[]   = (int) $count;
        $labels[] = date('M', strtotime($temp_start_date));
    }

    $datasets[] = [
        'label' => '# of Quotation',
        'data' => $data,
        'backgroundColor' => [
            '#67abff', '#69c3c4', '#67abff', '#69c3c4',
            '#67abff', '#69c3c4', '#67abff', '#69c3c4',
            '#67abff', '#69c3c4', '#67abff', '#69c3c4'
        ],
        'borderWidth' => 1
    ];

    $chart = [
        'type' => 'bar',
        'data' => [
            'datasets' => $datasets,
            'labels' => $labels
        ],
        'options' => [
            'legend' => ['display' => false]
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
		 if (isset($filter['company']) && !empty($filter['company'])) {
        if (is_array($filter['company'])) {
            $this->db->where_in('sp.company', $filter['company']);
        } else {
            $this->db->where('sp.company', $filter['company']);
        }
    }
		$this->db->select('u.id,u.name as company_name,u.image,COUNT(a.id) as count,SUM(sub_total) as amount');
		$this->db->order_by("amount", "desc");
		$this->db->group_by('sp.company');
		$this->db->join('sales_person sp', 'sp.id=a.sales_person', 'left');
		$this->db->join('admin_users u', 'u.id=sp.company', 'left');
		$this->db->where('a.confirm',1);
		  if (!empty($filter['from_date'])) {
        $this->db->where('a.created_date >=', $filter['from_date']);
    }
				if (!empty($filter['to_date'])) {
    $this->db->where('a.created_date <=', $filter['to_date']);
}

		$quotations = $this->quotationmodel->gets_data()->result_array();
// 		echo $this->db->last_query();
// exit;

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
				$html .= '<td>AED  ' . round(@$q['amount'], 2) . '</td>';
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
