<?php $question=array('','How was your experience in the washroom?','How was the cleaning experience?'); 
      $rating=array('','Unhappy','Content','Happy');
?>
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

             <div class="box-body">
            <div class="form-group">
            <form method="get">
            	<div class="col-md-3 filter">
               <div class="form-group">
                <label>From Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker" name="from" value="<?php echo $this->input->get('from'); ?>">
                </div>
                <!-- /.input group -->
              </div>
                </div>
                <div class="col-md-3 filter">
               <div class="form-group">
                <label>To Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker1" name="to" value="<?php echo $this->input->get('to'); ?>">
                </div>
                <!-- /.input group -->
              </div>
                </div>


                 <div class="col-md-3 filter">
               <div class="form-group">
                <label>Location:</label>

                <select class="form-control" name="location">
          <option value="">--select any --</option>
         <?php foreach($locations as $p){ ?>
          <option value="<?php echo $p->id; ?>" <?php if($p->id==@$this->input->get('location')){ echo "selected"; } ?>><?php echo $p->name; ?></option>
          <?php } ?>
          </select>
                <!-- /.input group -->
              </div>
                </div>
                
                      
                      
                      
                      
                <div class="col-md-2" style="margin-top: 25px;">
                <div class="input-group">
                <button type="submit" class="btn btn-info pull-right">Filter</button>
                </div>
                </div>
                <!-- /.input group -->
                </form>
                
                <!-- /.input group -->
               
              </div>
            </div>
            <!-- /.box-header -->
            <?php if(!empty($tabledata)) {?>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>#</th>
                  <th>Location</th>
                 
                        <th>Rating</th>
                        <th>Rating reason</th>
                        <th>Rating Reason Details</th>
                        <th>Date</th>
                          
                </tr>
                </thead>
                <tbody>
                <?php
				$i=1;
				if(!empty($tabledata)){ foreach($tabledata as $product){
				?>
                <tr>
                <td><?php echo $i++; ?></td>
              <td><?php echo @$product->location_name; ?></td>
             
              <td><?php echo @$product->rating; ?></td>
              <td><?php echo @$product->rating_reason; ?></td>
              <td><?php echo @$product->rating_details=="Other"?$product->rating_other_details:$product->rating_details; ?></td>
              <td><?php echo date('d-m-Y h:i a',strtotime(@$product->created_date)); ?></td>
                </tr>
                <?php
				}
				}
				?>
                </tbody>
                <tfoot>
                <tr>
                <th>#</th>
                 < <th>Location</th>
                  
                        <th>Rating</th>
                        <th>Rating reason</th>
                        <th>Rating Reason Details</th>
                        <th>Date</th>
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
<script>
	 $(function () {
   		 //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
	$('#datepicker1').datepicker({
      autoclose: true
    });
    });
 </script>