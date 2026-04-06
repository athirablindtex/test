<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo @$page; ?>
        <small><?php echo @$product->name; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('admin/'.@$controller); ?>"><?php echo @$module; ?></a></li>
        <li class="active"><?php echo @$product->name; ?></li>
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

    
<?php if(!empty($product)) {?>
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
             <?php echo @$product->name; ?>
            <small class="pull-right"></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
         <b> Name</b>
          <address>
            <?php echo @$product->name; ?>
          </address>
        </div>

        <div class="col-sm-4 invoice-col">
         <b> Email</b>
          <address>
            <?php echo @$product->email; ?>
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
         <b> User Group</b>
          <address>
            <?php echo @$product->group; ?>
          </address>
        </div>
        <!-- /.col -->
       
       
        
        <!-- /.col -->
        
        <!-- /.col -->
        
        <!-- /.col -->
       
        
        <div class="col-sm-4 invoice-col">
          Status
         <address>
           <?php 
        $st=array('<span class="label label-success">Active</span>','<span class="label label-danger">Blocked</span>');
        echo $st[@$product->blocked]; ?>
          </address>
        </div>
      </div>
      <!-- /.row -->
  <div class="col-sm-8 invoice-col<?php @$product->image!=''?'':' hidden'; ?>">
         <b> Image</b>
          <address>
           <img src="<?php echo base_url().'uploads/users/'.@$product->image; ?>" width="400px" />
          </address>
        </div>
      <!-- Table row -->
      
      <!-- /.row -->

      
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          
          <a type="button" href="<?php echo site_url('admin/'.$controller.'/add/'.@$product->$module_id); ?>" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> Edit
          </a>
          <a type="button" class="btn btn-primary pull-right" href="<?php echo site_url('admin/'.$controller); ?>" style="margin-right: 5px;">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
    </section>
    <!-- /.content -->
    <?php } else{?>
     <section class="invoice">
      <!-- title row -->
      <div class="row">
    <div class="alert alert-danger">
  <strong></strong> <?php echo $this->lang->line('common_no_data'); ?>
</div>
</div>
</section>
    <?php } ?>
    <div class="clearfix"></div>
  </div>