<?php
##developer By Lone Wolf## 
#final update in core 22-01-18##
//donot remove this,hrd coded site datas .may cause errors in site
$config['site_hard_date_format'] = 'd-m-Y';
$config['site_hard_date_time_format'] = 'd-m-Y h:i a';
$config['site_default_no_menu_icon'] = 'fa fa-dashboard';
///
//change initialy
$config['site_cookie_admin'] = 'silverhost_core_user';
$config['site_salt'] = '6845?s_cA#Uzg>VcpWN+[f>GNq/e&Zsa:(z4*}6*j%=#C,MX4t';
$config['user_salt'] = ',^7EfsQDtaFbt3*&f%/4rQHey)5:m}#C67/%)re#Dh@():UUm]';
$config['limit_request_attempts'] = 10000;
$config['super_admin_id'] = 1;
$config['admin_id'] = 5;
$config['company_id'] = 5;
//Constants
$config['access_tokken'] = 'accesstoken';
$config['token_valid'] = 'tokenvalid';
////
/*
const firebaseConfig = {
  apiKey: "AIzaSyD8ZWYVMR_s3jj-H1JpdEVssD8gKxq_9CE",
  authDomain: "blindtex-app.firebaseapp.com",
  projectId: "blindtex-app",
  storageBucket: "blindtex-app.appspot.com",
  messagingSenderId: "57280379701",
  appId: "1:57280379701:web:ebfa8bc72fbb0b09142a7c"
};
 
 */

//Admin mails (For sent Worksheets)
$config['admin_mails'] = array('athirablindtex@gmail.com','mohammedeublindtex@gmail.com','mujeeb@blindtex.com');
///Menu items
$config['menu'] = array(

	'company' => array(
		'name'		=>	'Company',
		'active'	=> 'company',
		'permission' =>	'company_list',
		'icon'		=>	'far fa-building',
		'link'		=> 	'admin/company/list',

	),
	'product' => array(
		'name'		=>	'Fabric',
		'active'	=> 'product',
		'permission' =>	'product_list',
		'icon'		=>	'fas fa-magic',
		'link'		=> 	'admin/product/list',

	),
    
'margin' => array(
    'name'        => 'Margin',
    'active'      => 'margin',
    'permission'  => 'margin_list_view',
    'icon'        => 'fas fa-percentage',
    'link'        => '#',
    'sub_child'   => array(

        'priceband_margin' => array(
            'name'        => 'Price Band Margin',
            'active'      => 'pricebandmargin',
            'permission' => 'pricebandmargin_list',
            'icon'        => 'fas fa-tag',
            'link'        => 'admin/product/pricebandmargin',
        ),

        'product_margin' => array(
            'name'        => 'Product Margin',
            'active'      => 'productmargin',
            'permission'  => 'productmargin_list',
            'icon'        => 'fas fa-box',
            'link'        => 'admin/product/productmargin',
        ),

        'extras_margin' => array(
            'name'        => 'Extras Margin',
            'active'      => 'extrasmargin',
            'permission'  => 'extras_margin_list',
            'icon'        => 'fas fa-plus-circle',
            'link'        => 'admin/extras/extrasmargin',
        ),

    )
),

	'salesperson' => array(
		'name'		=>	'Sales Person',
		'active'	=> 'salesperson',
		'permission' =>	'salesperson_list',
		'icon'		=>	'fa fa-user',
		'link'		=> 	'admin/salesperson/list',
	),
	'appointment' => array(
		'name'		=>	'Appointment',
		'active'	=> 'appointment',
		'permission' =>	'appointment_list',
		'icon'		=>	'fas fa-calendar-check',
		'link'		=> 	'admin/appointment/list',
	),
	'customer' => array(
		'name'		=>	'Customer',
		'active'	=> 'customer',
		'permission' =>	'customer_list',
		'icon'		=>	'fas fa-users',
		'link'		=> 	'admin/customer/list',

	),
	'quotation' => array(
		'name'		=>	'Quotations',
		'active'	=> 'quotation',
		'permission' =>	'quotation_list',
		'icon'		=>	'fas fa-calendar-check',
		'link'		=> 	'admin/quotation/list',
	),
	'quotationtransfer' => array(
		'name'		=>	'SP - SP Transfer',
		'active'	=> 'quotationtransfer',
		'permission' =>	'quotationtransfer_list',
		'icon'		=>	'fas fa-exchange-alt',
		'link'		=> 	'admin/quotationtransfer/list',
	),
	'price_band' => array(
		'name'		=>	'Price Band',
		'active'	=> 'priceband',
		'permission' =>	'priceband_list',
		'icon'		=>	'fas fa-tag',
		'link'		=> 	'admin/priceband/list',

	),
	'extras' => array(
		'name'		=>	'Extras',
		'active'	=> 'extras',
		'permission' =>	'extras_list',
		'icon'		=>	'fas fa-plus',
		'link'		=> 	'admin/extras/list',

	),
	'configuration' => array(
		'name'		=>	'Configuration',
		'active'	=> 'configuration',
		'permission' =>	'configuration_list',
		'icon'		=>	'fas fa-wrench',
		'link'		=> 	'admin/configuration/list',

	),
	'master' => array(
		'name'		=>	'Master',
		'active'	=> 'master',
		'permission' =>	'producttype_list',
		'icon'		=>	'fas fa-star',
		'link'		=> 	'#',
		'sub_child'	=>	array(
				'vendor'	=> array(
				'name'	=> 'Vendor',
				'active' =>	'vendor',
				'permission' =>	'vendor_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/vendor/list'
			),
			'product_type'	=> array(
				'name'	=> 'Product Type',
				'active' =>	'producttype',
				'permission' =>	'producttype_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/producttype/list'
			),
				'sub_product_type'	=> array(
				'name'	=> 'Sub Product Type',
				'active' =>	'subproducttype',
				'permission' =>	'producttype_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/subproducttype/list'
			),
			'measurement_type'	=> array(
				'name'	=> 'Measuring Type',
				'active' =>	'measurementtype',
				'permission' =>	'measurementtype_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/measurementtype/list'
			),
		
			'room_type'	=> array(
				'name'	=> 'Room Type',
				'active' =>	'roomtype',
				'permission' =>	'roomtype_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/roomtype/list'
			),
		
			'price_band_version'	=> array(
				'name'	=> 'Price Band Version',
				'active' =>	'price_band_version',
				'permission' =>	'priceband_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/pricebandversion/list'
			),
			'mail_template'	=> array(
				'name'	=> 'Mail Template',
				'active' =>	'mailtemplate',
				'permission' =>	'mailtemplate_list',
				'icon'	=>	'fa fa-list',
				'link'	=> 'admin/mailtemplate/list'
			)



		)
	)




);
