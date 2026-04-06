<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Change Password
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Change Password</li>
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
              <h3 class="box-title">Change Password</h3>
            </div>
            <?php if($this->session->flashdata('info')!=''){ ?>
                <div class="alert alert-danger">
 				 <strong>Error!</strong> <?php echo $this->session->flashdata('info'); ?><?php echo validation_errors();
				?>
					</div>
                <?php } ?>
                <?php if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success">
 				 <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
					</div>
                <?php } ?>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form"  action="" name="form2" onsubmit="return checkForm(this);" method="post">
              <div class="box-body">
               <div class="form-group">
			<label>Current Password</label>
			<input class="form-control" placeholder="Current Password" type="password" name="cpass" required>
		</div>
		<div class="form-group">
			<label>New password</label>
			<input class="form-control" placeholder="New Password" type="password" name="npass1" required>
		</div>
		<div class="form-group">
			<label>RE-Type New Password</label>
			<input class="form-control" placeholder="RE-Type New Password" type="password" name="npass2" required>
		</div>
              
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
  <script language="JavaScript" type="text/javascript">
function checkForm(form)
  {
    var p1=form.npass1.value;
	var p2=form.npass2.value;
	var p=form.cpass.value;
	if(p=='')
	    {
		   alert("Error: fill current Password !");
            form.cpass.focus();
            return false;
		}
	if(p1!=p2)
		{
			alert("Error: New Password doesn't match with confirmpassword!");
        form.npass1.focus();
        return false;
		}
    if(form.npass1.value != "" && form.npass1.value == form.npass2.value) {
      if(form.npass1.value.length < 6) {
        alert("Error: Password must contain at least six characters!");
        form.npass1.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(form.npass1.value)) {
        alert("Error: password must contain at least one number (0-9)!");
        form.npass1.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(form.npass1.value)) {
        alert("Error: password must contain at least one lowercase letter (a-z)!");
        form.npass1.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(form.npass1.value)) {
        alert("Error: password must contain at least one uppercase letter (A-Z)!");
        form.npass1.focus();
        return false;
      }
	  re= /[^\w\s]/gi;
	   if(!re.test(form.npass1.value)) {
        alert("Error: password must contain at least one Special Character!");
        form.npass1.focus();
        return false;
      }
    } 
}
</script>