<?php
$usr = $this->usersmodel->get_row_details($this->session->userdata('admin_id'));
$profile_image = is_file('uploads/users/' . @$usr->image) ? base_url() . 'uploads/users/' . @$usr->image : base_url() . 'uploads/placeholder/image1.png';
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <title><?php echo isset($page_title) ? $page_title : $this->config->item('site_title'); ?></title>
   <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
   <link rel="icon" href="<?= base_url(); ?>assets/admin/img/Blindtex-logo.png" type="image/x-icon" />
   <!-- Fonts and icons. -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/webfont/webfont.min.js"></script>
   <script>
      WebFont.load({
         google: {
            "families": ["Open+Sans:300,400,600,700"]
         },
         custom: {
            "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"],
            urls: ['<?= base_url(); ?>assets//admin/css/fonts.css']
         },
         active: function() {
            sessionStorage.fonts = true;
         }
      });
   </script>
   <!-- CSS Files -->
   <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/bootstrap.min.css">
   <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/admintheme.css?version=678">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css">
   <!--   Core JS Files   -->
   <script src="<?= base_url(); ?>assets/admin/js/core/jquery.3.2.1.min.js"></script>
   <script src="<?= base_url(); ?>assets/admin/js/core/popper.min.js"></script>
   <script src="<?= base_url(); ?>assets/admin/js/core/bootstrap.min.js"></script>
   <!-- jQuery UI -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
   <script src="<?= base_url(); ?>assets/admin/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
   <!-- jQuery Scrollbar -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
   <!-- Moment JS -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/moment/moment.min.js"></script>
   <!-- Chart JS -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/chart.js/chart.min.js"></script>
   <!-- jQuery Sparkline -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
   <!-- Chart Circle -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/chart-circle/circles.min.js"></script>
   <!-- Datatables -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/datatables/datatables.min.js"></script>
   <!-- Bootstrap Notify -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
   <!-- Bootstrap Toggle -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
   <!-- Sweet Alert -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
   <!-- DateTimePicker -->
   <script src="<?= base_url(); ?>assets/admin/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>
   <!-- admintheme JS -->
   <script src="<?= base_url(); ?>assets/admin/js/ready.min.js"></script>
   <!-- AdminTheme Alerts -->
   <script src="<?= base_url(); ?>assets/admin/js/theme/alerts.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">



</head>

<body>
   <style>
html, body, .wrapper, .main-panel, .content {
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}
table, th, td,
input, select, textarea,
button, .btn {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
}




   </style>
   <div class="wrapper">
      <!--
         Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
         -->
      <div class="main-header" data-background-color="purple">
         <!-- Logo Header -->
         <div class="logo-header">
            <a href="<?php echo site_url('admin/dashboard'); ?>" class="logo">
               <img src="<?= base_url(); ?>assets/admin/img/Blindtex-logo.png" alt="navbar brand" class="navbar-brand">
            </a>
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon">
                  <i class="fa fa-bars"></i>
               </span>
            </button>
            <button class="topbar-toggler more"><i class="fa fa-ellipsis-v"></i></button>
            <div class="navbar-minimize">
               <button class="btn btn-minimize btn-rounded">
                  <i class="fa fa-bars"></i>
               </button>
            </div>
         </div>
         <!-- End Logo Header -->
         <!-- Navbar Header -->
         <nav class="navbar navbar-header navbar-expand-lg">
            <div class="container-fluid">
               <div class=col-sm-6>
                 
                     <div class="display-date">
                        <span id="day">day</span>,
                        <span id="daynum">00</span>
                        <span id="month">month</span>
                        <span id="year">0000</span>
                     </div>
                  </div>
                  <div class=col-sm-6>
                     <div class="display-time"></div>
                  </div>
          


               <!-- 	<div class="collapse" id="search-nav">
                  <form class="navbar-left navbar-form nav-search mr-md-3">
                  	<div class="input-group">
                  		<div class="input-group-prepend bg-transparent">
                  			<button type="submit" class="btn btn-search pr-1">
                  				<i class="fa fa-search search-icon"></i>
                  			</button>
                  		</div>
                  		<input type="text" placeholder="Search ..." class="form-control bg-transparent">
                  	</div>
                  </form>
                  </div> -->
               <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">


                  <li class="nav-item dropdown hidden-caret">
                     <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                           <img src="<?= @$profile_image; ?>" alt="..." class="avatar-img rounded-circle">
                        </div>
                     </a>
                     <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <li>
                           <div class="user-box">
                              <div class="avatar-lg"><img src="<?= @$profile_image; ?>" alt="image profile" class="avatar-img rounded"></div>
                              <div class="u-text">
                                 <h4><?= @$usr->name; ?></h4>
                                 <p class="text-muted"><?= @$usr->email; ?></p>
                              </div>
                           </div>
                        </li>
                        <li>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item" href="<?php echo base_url() . 'admin/settings/profile'; ?>">My Profile</a>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item" href="<?php echo base_url() . 'admin/settings'; ?>">Change Pasword</a>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item" href="<?php echo base_url() . 'admin/settings/logout'; ?>">Logout</a>
                        </li>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
         <script>
           var sessionStartTime = <?= $this->session->userdata('session_start_time'); ?> * 1000; // convert to milliseconds

    function updateSessionTime() {
        var now = new Date().getTime();
        var elapsed = Math.floor((now - sessionStartTime) / 1000); // seconds

        var hours = Math.floor(elapsed / 3600);
        var minutes = Math.floor((elapsed % 3600) / 60);
        var seconds = elapsed % 60;

        // Format time as HH:MM:SS
        var formatted =
            (hours < 10 ? "0" + hours : hours) + ":" +
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);

        $('.display-time').text("Session Time: " + formatted);
    }

    // Update every second
    setInterval(updateSessionTime, 1000);
    updateSessionTime();


            // Date
            function updateDate() {
               let today = new Date();

               // return number
               let dayName = today.getDay(),
                  dayNum = today.getDate(),
                  month = today.getMonth(),
                  year = today.getFullYear();

               const months = [
                  "January",
                  "February",
                  "March",
                  "April",
                  "May",
                  "June",
                  "July",
                  "August",
                  "September",
                  "October",
                  "November",
                  "December",
               ];
               const dayWeek = [
                  "Sunday",
                  "Monday",
                  "Tuesday",
                  "Wednesday",
                  "Thursday",
                  "Friday",
                  "Saturday",
               ];
               // value -> ID of the html element
               const IDCollection = ["day", "daynum", "month", "year"];
               // return value array with number as a index
               const val = [dayWeek[dayName], dayNum, months[month], year];
               for (let i = 0; i < IDCollection.length; i++) {
                  document.getElementById(IDCollection[i]).firstChild.nodeValue = val[i];
               }
            }

            updateDate();
         </script>
         <!-- End Navbar -->
      </div>