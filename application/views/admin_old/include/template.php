<!DOCTYPE html>
<html><head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($page_title) ? $page_title : $this->config->item('site_title'); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/css/skins/_all-skins.min.css">
 
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/daterangepicker/daterangepicker.css">
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css">
 
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/iCheck/all.css">
 
 <!-- jQuery 2.2.3 -->

<script src="<?php echo base_url(); ?>assets/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
 <script src="<?php echo base_url(); ?>assets/admin/plugins/jQuery/jquery-migrate-1.0.0.js"></script>
 
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery-ui.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- daterangepicker -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/admin/dist/js/app.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/iCheck/icheck.min.js"></script>
<!--<script src="<?php //echo base_url(); ?>assets/admin/dist/js/demo.js"></script>-->
<!-- elRTE -->
		
		
        
         <!-- elRTE -->
    	<script src="<?php echo base_url(); ?>assets/admin/plugins/elrte/js/jquery-ui-1.8.7.custom.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/elrte/css/smoothness/jquery-ui-1.8.7.custom.css" type="text/css" media="screen" charset="utf-8">

		<!-- elRTE -->
		<script src="<?php echo base_url(); ?>assets/admin/plugins/elrte/js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/elrte/css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
         <!--image cropping-->
        <link href="<?php echo base_url(); ?>assets/admin/plugins/croppic/croppic.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/admin/plugins/croppic/croppic.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/search/bootstrap-chosen.css" />
    
    <script src="<?php echo base_url(); ?>assets/admin/plugins/search/chosen.jquery.js"></script>
     
	
		

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  
  <!-- Left side column. contains the logo and sidebar -->
  <?php $this->load->view('admin/include/sidebar'); ?>

  <!-- Content Wrapper. Contains page content -->
  <?php $this->load->view(@$content); ?>
  <!-- /.content-wrapper -->
  <?php $this->load->view('admin/include/footer'); ?>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

</body>
</html>
