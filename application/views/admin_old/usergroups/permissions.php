<link href="<?php echo base_url(); ?>assets/admin/plugins/iCheck/flat/green.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <?php echo @$page; ?>
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/'.@$controller); ?>"><?php echo @$module; ?></a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo @$page; ?></h3>
            </div>
              <?php if($this->session->flashdata('info')!=''){ ?>
                <div class="alert alert-info">
  <strong>Info!</strong> <?php echo $this->session->flashdata('info'); ?>
</div>
<?php } ?>
<?php if(validation_errors()){ ?>
       
        <div class="alert alert-danger">
  <strong>Error!</strong>  <?php echo validation_errors(); ?>
</div>
        <?php } ?>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="" method="post">
              <div class="box-body">
			  <div class="form-group">
			  <div class="col-md-3">
						<label>
							<input type="checkbox" class="flat-red" id="al" <?= count($all)==count($pr)?'checked':''; ?>>
							Select/Unselect All
						</label>
					</div>
			  <?php foreach($modules as $m){ 
						$this->db->where('parent',$m->module);
					$mod=$this->usergroupsmodel->get_modules()->result();
					$r=array('_list','_add','_delete');
					$i=0;
					foreach($pr as $k){
							foreach($r as $rm){
									if(strpos($k,$rm) == true){
											$t=explode($rm,$k);
											($t[0] == $m->module) ? $i++ : $i;
										}
								}
						}
			  ?>
					<div class="col-md-12">
						<h3><?= $m->module_language_key; ?></h3>
					</div>
					<div class="col-md-3">
						<label>
							<input type="checkbox" class="flat-red alls fs" id="<?= $m->id; ?>" <?= $i==count($mod)?'checked':''; ?>>
							Select/Unselect All
						</label>
					</div>
					<?php
				 
				foreach($mod as $o){
				  ?>
					<div class="col-md-3">
						<label>
							<input type="checkbox" class="flat-red subs sub_<?= $m->id; ?> fs" name="permission[]" value="<?= $o->module; ?>" <?= in_array($o->module,$pr)==1?'checked':''; ?>>
							<?= $o->module_language_key; ?>
						</label>
					</div>	
				<?php } ?>
			  <?php } ?>
			  </div>
						<input type="hidden" name="user" value="<?= $this->uri->segment('4'); ?>" />
              </div>
              <!-- /.box-body -->
			  
			  
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

       
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
   <script>
  $(function () {
			$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });
		});
   </script>
   <script>
   $(function () {
    $("#example1").DataTable();
  	});
    </script>
    <script>
	$('#al').on('ifChecked', function(event){
			$(".fs").iCheck('check');
		});
	$('#al').on('ifUnchecked', function(event){
			$('.fs').iCheck('uncheck');
		});
	$('.alls').on('ifChecked', function(event){
  			var id = $(this).attr("id");
			var class1='sub_'+id;
			//$("."+class1).prop( "checked", true );
			$("."+class1).iCheck('check');
		});
	$('.alls').on('ifUnchecked', function(event){
  			var id = $(this).attr("id");
			var class1='sub_'+id;
			//$("."+class1).iCheck('check');
			$('.'+class1).iCheck('uncheck');
		});
	</script>
