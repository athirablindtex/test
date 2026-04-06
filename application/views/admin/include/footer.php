<!-- Custom template | don't include it in your project! -->
<div class="custom-template">
   <div class="title">Settings</div>
   <div class="custom-content">
      <div class="switcher">
         <div class="switch-block">
            <h4>Topbar</h4>
            <div class="btnSwitch">
               <button type="button" class="changeMainHeaderColor" data-color="blue"></button>
               <button type="button" class="selected changeMainHeaderColor" data-color="purple"></button>
               <button type="button" class="changeMainHeaderColor" data-color="light-blue"></button>
               <button type="button" class="changeMainHeaderColor" data-color="green"></button>
               <button type="button" class="changeMainHeaderColor" data-color="orange"></button>
               <button type="button" class="changeMainHeaderColor" data-color="red"></button>
            </div>
         </div>
         <div class="switch-block">
            <h4>Background</h4>
            <div class="btnSwitch">
               <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
               <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
               <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
            </div>
         </div>
      </div>
   </div>
   <div class="custom-toggle">
      <i class="flaticon-settings"></i>
   </div>
</div>
<!-- End Custom template -->
</div>
</div>

<script>
  
</script>
<script>
      <?php if($this->session->flashdata('info')!=''){ ?>
         tfna_alert_info('Info','<?php echo $this->session->flashdata("info");?>');
      <?php unset($_SESSION['info']);} ?>
      <?php if($this->session->flashdata('success')!=''){ ?>
         tfna_alert_success('Success','<?php echo $this->session->flashdata("success");?>');
      <?php unset($_SESSION['success']);} ?>
      <?php  if($this->session->flashdata('error')!=''){ ?>
         tfna_alert_danger('Error','<?php echo $this->session->flashdata("error");?>');
      <?php unset($_SESSION['error']);} ?>
      <?php  if(@validation_errors()){ ?>
         tfna_alert_danger('Error','<?php echo @validation_errors();?>');
      <?php } ?>
      <?php  if(@$error){ ?>
         tfna_alert_danger('Error','<?php echo @$error;?>');
      <?php } ?>
        </script>
</body>
</html>