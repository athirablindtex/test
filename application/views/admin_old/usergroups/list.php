<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo @$page; ?>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo @$module; ?></a></li>
        <li class="active">List</li>
      </ol>
    </section>
     <?php if($this->session->flashdata('info')!=''){ ?>
                <div class="alert alert-info">
  <strong>Info!</strong> <?php echo $this->session->flashdata('info'); ?>
</div>
<?php } ?>
<?php if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success">
  <strong>Info!</strong> <?php echo $this->session->flashdata('success'); ?>
</div>
<?php } ?>
<?php  if($this->session->flashdata('error')!=''){ ?>
       
        <div class="alert alert-danger">
  <strong>Error!</strong>  <?php echo $this->session->flashdata('error'); ?>
</div>
        <?php } ?>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo @$page; ?></h3>
            </div>
            <!-- /.box-header -->
            <?php if(!empty($tabledata)) {?>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>#</th>
                  <th>Name</th>
                        
                          <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
				$i=1;
				if(!empty($tabledata)){ foreach($tabledata as $product){
				?>
                <tr>
                <td><?php echo $i++; ?></td>
              <td><?php echo @$product->name; ?></td>
              
              <td><a class="" href="<?php echo site_url('admin/'.$controller.'/delete/'.@$product->$module_id.''); ?>" onclick="return confirm('<?php echo $this->lang->line('common_confirm_delete'); ?>');"><span class="glyphicon glyphicon-trash"></span></a>
                <a class="" href="<?php echo site_url('admin/'.$controller.'/add/'.@$product->$module_id.''); ?>"><i class="glyphicon glyphicon-edit"></i></a>
                 <a class="" href="<?php echo site_url('admin/'.$controller.'/permissions/'.@$product->$module_id.''); ?>"><i class="glyphicon glyphicon-cog"></i></a>
                </td>
                </tr>
                <?php
				}
				}
				?>
                </tbody>
                <tfoot>
                <tr>
                <th>#</th>
                 <th>Name</th>
                          <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <?php }else{?>
		<div class="alert alert-danger">
  <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
</div>
		<?php } ?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>