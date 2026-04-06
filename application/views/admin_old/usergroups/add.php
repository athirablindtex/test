<style>
 
    .containers
    {
        //position: absolute;
        top: 10%; left: 10%; right: 0; bottom: 0;
    }
    .action
    {
        width: 400px;
        height: 30px;
        margin: 10px 0;
    }
    .cropped>img
    {
        margin-right: 10px;
    }
    .imageBox
    {
        position: relative;

        width: 507px;
        height: 373px;
        border:1px solid #aaa;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        cursor:move;
    }

    .imageBox .thumbBox
    {
        position: absolute;
        top: 7%;

        width: 427px;
        height: 292px;
        margin-top: 15px;
        margin-left: 40px;
        box-sizing: border-box;
        border: 1px solid rgb(102, 102, 102);
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
        background: none repeat scroll 0% 0% transparent;
    }

    .imageBox .spinner
    {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        text-align: center;
        line-height: 400px;
        background: rgba(0,0,0,0.7);
    }
</style>
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
            <form action="" method="post" enctype="multipart/form-data">
              <div class="box-body">
               <div class="col-lg-6">
        <div class="form-group">
			<label>Name</label>
          <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo @$res->name!=''?@$res->name:$this->input->post('name'); ?>" required>
       </div>
        </div>
                        <input type="hidden" name="id" value="<?php echo @$res->$module_id ? $res->$module_id : 0; ?>"> 
              
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
  
    <!-- elRTE -->

<!-- elRTE -->