<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Blindtex</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?php echo base_url(); ?>assets/admin/img/Blindtex-logo.png" type="image/x-icon"/>

	<!-- Fonts and icons. -->
	<script src="<?php echo base_url(); ?>assets/admin/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/admintheme.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css">


</head>
<body>
	<div class="wrapper">
		<!--
			Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
		<div class="main-header" data-background-color="purple">
			<!-- Logo Header -->
			<div class="logo-header">
				
				<a href="index.php" class="logo">
					<img src="<?php echo base_url(); ?>assets/admin/img/Blindtex-logo.png" alt="navbar brand" class="navbar-brand">
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
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-envelope"></i>
							</a>
							<ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
								<li>
									<div class="dropdown-title d-flex justify-content-between align-items-center">
										Messages 									
										<a href="#" class="small">Mark all as read</a>
									</div>
								</li>
								<li>
									<div class="message-notif-scroll scrollbar-outer">
										<div class="notif-center">
											<a href="#">
												<div class="notif-img"> 
													<img src="<?php echo base_url(); ?>assets/admin/img/jm_denis.jpg" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Jimmy Denis</span>
													<span class="block">
														How are you ?
													</span>
													<span class="time">5 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="<?php echo base_url(); ?>assets/admin/img/chadengle.jpg" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Chad</span>
													<span class="block">
														Ok, Thanks !
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="<?php echo base_url(); ?>assets/admin/img/mlane.jpg" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Jhon Doe</span>
													<span class="block">
														Ready for the meeting today...
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="<?php echo base_url(); ?>assets/admin/img/talha.jpg" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="subject">Talha</span>
													<span class="block">
														Hi, Apa Kabar ?
													</span>
													<span class="time">17 minutes ago</span> 
												</div>
											</a>
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all messages<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-bell"></i>
								<span class="notification">4</span>
							</a>
							<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
								<li>
									<div class="dropdown-title">You have 4 new notification</div>
								</li>
								<li>
									<div class="notif-scroll scrollbar-outer">
										<div class="notif-center">
											<a href="#">
												<div class="notif-icon notif-primary"> <i class="fa fa-user-plus"></i> </div>
												<div class="notif-content">
													<span class="block">
														New user registered
													</span>
													<span class="time">5 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-icon notif-success"> <i class="fa fa-comment"></i> </div>
												<div class="notif-content">
													<span class="block">
														Rahmad commented on Admin
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-img"> 
													<img src="<?php echo base_url(); ?>assets/admin/img/profile2.jpg" alt="Img Profile">
												</div>
												<div class="notif-content">
													<span class="block">
														Reza send messages to you
													</span>
													<span class="time">12 minutes ago</span> 
												</div>
											</a>
											<a href="#">
												<div class="notif-icon notif-danger"> <i class="fa fa-heart"></i> </div>
												<div class="notif-content">
													<span class="block">
														Farrah liked Admin
													</span>
													<span class="time">17 minutes ago</span> 
												</div>
											</a>
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="<?php echo base_url(); ?>assets/admin/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<li>
									<div class="user-box">
										<div class="avatar-lg"><img src="<?php echo base_url(); ?>assets/admin/img/profile.jpg" alt="image profile" class="avatar-img rounded"></div>
										<div class="u-text">
											<h4>Hizrian</h4>
											<p class="text-muted">hello@example.com</p>
										</div>
									</div>
								</li>
								<li>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="profile.php">My Profile</a>
				
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#">Logout</a>
								</li>
							</ul>
						</li>
						
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

        
		<!-- Sidebar -->
		<div class="sidebar">
			
			<div class="sidebar-background"></div>
			<div class="sidebar-wrapper scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="<?php echo base_url(); ?>assets/admin/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									Hizrian
									<span class="user-level">Administrator</span>
									<!-- <span class="caret"></span> -->
								</span>
							</a>
							<div class="clearfix"></div>
<!-- 
							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#profile">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									<li>
										<a href="#edit">
											<span class="link-collapse">Edit Profile</span>
										</a>
									</li>
									<li>
										<a href="#settings">
											<span class="link-collapse">Settings</span>
										</a>
									</li>
								</ul>
							</div> -->
						</div>
					</div>
					<ul class="nav">
						<li class="nav-item">
							<a href="index.php">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="company.php">
								<i class="far fa-building"></i>
								<p>Company</p>
							</a>
						</li>
								<li class="nav-item">
							<a href="product.php">
					<i class="fas fa-magic"></i>
								<p>Product</p>
							</a>
						</li>
							<li class="nav-item">
							<a href="sales_person.php">
					<i class="fas fa-user"></i>
								<p>Sales Person</p>
							</a>
						</li>
								<li class="nav-item">
							<a href="appoinment.php">
				<i class="fas fa-calendar-check"></i>
								<p>Appoinment</p>
							</a>
						</li>
							<li class="nav-item">
							<a href="customer.php">
			<i class="fas fa-users"></i>
								<p>Customer</p>
							</a>
						</li>
								<li class="nav-item">
							<a href="price_band.php">
			<i class="fas fa-tag"></i>
								<p>Price Band</p>
							</a>
						</li>

							<li class="nav-item">
							<a href="extras.php">
			<i class="fas fa-plus"></i>
								<p>Extras</p>
							</a>
						</li>
							<li class="nav-item">
							<a href="config.php">
			<i class="fas fa-wrench"></i>
								<p>Configuration</p>
							</a>
						</li>
								<li class="nav-item">
							<a href="permissions.php">
			<i class="fas fa-eye"></i>
								<p>Permissions</p>
							</a>
						</li>



								
						<li class="nav-item">
							<a data-toggle="collapse" href="#submenu">
							<i class="fas fa-cog"></i>
								<p>Settings</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="submenu">
								<ul class="nav nav-collapse">
									
									<li>
										<a  href="users.php">
											<span class="sub-item">Users</span>
										</a>
									</li>
									<li>
										<a  href="users_group.php" >
											<span class="sub-item">Users Groups</span>
										</a>
									</li>
									
								</ul>
								</div>
							</li>


								<li class="nav-item">
							<a href="quotation.php">
<i class="fas fa-file-alt"></i>
								<p>Quotaion</p>
							</a>
						</li>


									<li class="nav-item">
							<a data-toggle="collapse" href="#submenu2">
							<i class="fas fa-star"></i>
								<p>Master</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="submenu2">
								<ul class="nav nav-collapse">
									
									<li>
										<a  href="master_product_type.php">
											<span class="sub-item">Product Type</span>
										</a>
									</li>
									<li>
										<a  href="master_room_type.php" >
											<span class="sub-item">Room Type</span>
										</a>
									</li>
									<li>
										<a href="master_vendor.php">
											<span class="sub-item">Vendor</span>
										</a>
									</li>
									<li>
										<a href="master_sub_product_type.php" >
											<span class="sub-item">Sub Product Type</span>
										</a>
									</li>
								</ul>
								</div>
							</li>
								
					<!-- 	<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Components</h4>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#base">
								<i class="fas fa-layer-group"></i>
								<p>Base</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="base">
								<ul class="nav nav-collapse">
									<li>
										<a href="components/avatars.html">
											<span class="sub-item">Avatars</span>
										</a>
									</li>
									<li>
										<a href="components/buttons.html">
											<span class="sub-item">Buttons</span>
										</a>
									</li>
									<li>
										<a href="components/gridsystem.html">
											<span class="sub-item">Grid System</span>
										</a>
									</li>
									<li>
										<a href="components/panels.html">
											<span class="sub-item">Panels</span>
										</a>
									</li>
									<li>
										<a href="components/notifications.html">
											<span class="sub-item">Notifications</span>
										</a>
									</li>
									<li>
										<a href="components/sweetalert.html">
											<span class="sub-item">Sweet Alert</span>
										</a>
									</li>
									<li>
										<a href="components/font-awesome-icons.html">
											<span class="sub-item">Font Awesome Icons</span>
										</a>
									</li>
									<li>
										<a href="components/flaticons.html">
											<span class="sub-item">Flaticons</span>
										</a>
									</li>
									<li>
										<a href="components/typography.html">
											<span class="sub-item">Typography</span>
										</a>
									</li>
								</ul>
							</div>
						</li> 
						<li class="nav-item">
							<a data-toggle="collapse" href="#forms">
								<i class="fas fa-pen-square"></i>
								<p>Forms</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="forms">
								<ul class="nav nav-collapse">
									<li>
										<a href="forms/forms.html">
											<span class="sub-item">Basic Form</span>
										</a>
									</li>
									
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#tables">
								<i class="fas fa-table"></i>
								<p>Tables</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="tables">
								<ul class="nav nav-collapse">
									<li>
										<a href="tables/tables.html">
											<span class="sub-item">Basic Table</span>
										</a>
									</li>
									<li>
										<a href="tables/datatables.html">
											<span class="sub-item">Datatables</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#maps">
								<i class="fas fa-map-marker-alt"></i>
								<p>Maps</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="maps">
								<ul class="nav nav-collapse">
									<li>
										<a href="maps/googlemaps.html">
											<span class="sub-item">Google Maps</span>
										</a>
									</li>
									<li>
										<a href="maps/fullscreenmaps.html">
											<span class="sub-item">Full Screen Maps</span>
										</a>
									</li>
									<li>
										<a href="maps/jqvmap.html">
											<span class="sub-item">JQVMap</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						
						
						<li class="nav-item">
							<a href="widgets.html">
								<i class="fas fa-desktop"></i>
								<p>Widgets</p>
								<span class="badge badge-count badge-success">4</span>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#custompages">
								<i class="fas fa-paint-roller"></i>
								<p>Custom Pages</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="custompages">
								<ul class="nav nav-collapse">
									<li>
										<a href="login.html">
											<span class="sub-item">Login & Register 1</span>
										</a>
									</li>
									<li>
										<a href="login2.html">
											<span class="sub-item">Login & Register 2</span>
										</a>
									</li>
									<li>
										<a href="userprofile.html">
											<span class="sub-item">User Profile</span>
										</a>
									</li>
									<li>
										<a href="404.html">
											<span class="sub-item">404</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#submenu">
								<i class="fas fa-bars"></i>
								<p>Menu Levels</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="submenu">
								<ul class="nav nav-collapse">
									<li>
										<a data-toggle="collapse" href="#subnav1">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav1">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a data-toggle="collapse" href="#subnav2">
											<span class="sub-item">Level 1</span>
											<span class="caret"></span>
										</a>
										<div class="collapse" id="subnav2">
											<ul class="nav nav-collapse subnav">
												<li>
													<a href="#">
														<span class="sub-item">Level 2</span>
													</a>
												</li>
											</ul>
										</div>
									</li>
									<li>
										<a href="#">
											<span class="sub-item">Level 1</span>
										</a>
									</li>
								</ul>
							</div>
						</li>-->
						<li class="nav-item">
							<a href="login.php">

								<p>Login</p>
							</a>
						</li>
					</ul>

				</div>
			</div>
		</div>
		<!-- End Sidebar -->





<!-- 	<a  href="#" data-toggle="modal" data-target="#productTypeModal">
											<span class="sub-item">Product Type</span>
										</a>
									</li>
									<li>
										<a data-toggle="collapse" href="#roomTypeModal">
											<span class="sub-item">Room Type</span>
										</a>
									</li>
									<li>
										<a data-toggle="collapse" href="vendorModal">
											<span class="sub-item">Vendor</span>
										</a>
									</li>
									<li>
										<a data-toggle="collapse" href="#subProductTypeModal"> -->

		  <!-- Modal -->
                     <div class="modal fade" id="productTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                              <div class="modal-header no-bd">
                                 <h5 class="modal-title">
                                    <span class="fw-mediumbold">
                                    Product </span> 
                                    <span class="fw-light">
                                   Type
                                    </span>
                                 </h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">×</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              <form>
                                    <div class="row">
                                       
  <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Name</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer no-bd">
                                 <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>





                      <div class="modal fade" id="roomTypeModal"   role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                              <div class="modal-header no-bd">
                                 <h5 class="modal-title">
                                    <span class="fw-mediumbold">
                                    Room  </span> 
                                    <span class="fw-light">
                                   Type
                                    </span>
                                 </h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">×</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              <form>
                                    <div class="row">
                                       
  <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Name</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer no-bd">
                                 <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>


 <div class="modal fade" id="vendorModal"   role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                              <div class="modal-header no-bd">
                                 <h5 class="modal-title">
                                    <span class="fw-mediumbold">
                                    Vendor  </span> 
                                 </h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">×</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              <form>
                                    <div class="row">
                                       
  <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Name</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer no-bd">
                                 <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>


<div class="modal fade" id="subProductTypeModal"   role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                              <div class="modal-header no-bd">
                                 <h5 class="modal-title">
                                    <span class="fw-mediumbold">
                                    Sub product type  </span> 
                                 </h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">×</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                              <form>
                                    <div class="row">
                                       
  <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Product Type</label>
                                             <select class="form-control" id="formGroupDefaultSelect">
                                 <option>1</option>
                                 <option>2</option>
                                 <option>3</option>
                                 <option>4</option>
                                 <option>5</option>
                              </select>
                                          </div>
                                       </div>
                                        <div class="col-md-12">
                                          <div class="form-group form-group-default">
                                             <label>Sub Product Type</label>
                                             <input id="addPosition" type="text" class="form-control">
                                          </div>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                              <div class="modal-footer no-bd">
                                 <button type="button" id="addRowButton" class="btn btn-primary">Add</button>
                                 <button type="button" class="btn" data-dismiss="modal">Close</button>
                              </div>
                           </div>
                        </div>
                     </div>



                     <div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Dashboard</h4>
						<div class="btn-group btn-group-page-header ml-auto">
							<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								All Company 
							</button>
							<div class="dropdown-menu">
								<div class="arrow"></div>
								<a class="dropdown-item" href="#">Company 1</a>
								<a class="dropdown-item" href="#">Company 2</a>
							<a class="dropdown-item" href="#">Company 3</a>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-primary card-round mb-3" style="
    background: #67abff;
">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-box-3
"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Companies</p>
												<h4 class="card-title">1,294</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-info card-round mb-3" style="
    background: #ffb23f;
">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-arrows-2
"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quotation Sent

</p>
												<h4 class="card-title">1303</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-success card-round mb-3" style="
    background: #20cc9c;
">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-hands
"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quot. Confirmed

</p>
												<h4 class="card-title">45</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-secondary card-round mb-3" style="
    background: #2bd652;
">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-user
"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales Person

</p>
												<h4 class="card-title">576</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-success card-round" style="
    background: #ff5d7e;
">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-analytics"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales
</p>
												<h4 class="card-title">3432</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				<!-- 	<div class="row row-card-no-pd row-count">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="far fa-building"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Companies</p>
												<h4 class="card-title">1.2985</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="far fa-paper-plane"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quotation Sent</p>
												<h4 class="card-title">1202</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-clipboard-check"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quotation Confirmed

</p>
												<h4 class="card-title">23</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-user-tie"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales Person

</p>
												<h4 class="card-title">23</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-tag"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales</p>
												<h4 class="card-title">45</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> -->
			
					<div class="row">
							<div class="col-md-4">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Company Quotaion</div>
								</div>
								<div class="card-body pb-0">
								<canvas id="chart1" width="400" height="300"></canvas>
							
							<h4 class="card-title text-center mb-3 mt-3">1,294</h4>
							
								</div>
							</div>
						</div>
								<div class="col-md-4">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Product Type</div>
								</div>
								<div class="card-body pb-0">
								<canvas id="chart2" width="400" height="300"></canvas>
							
							<h4 class="card-title text-center mb-3 mt-3">1,294</h4>
							
								</div>
							</div>
						</div>
								<div class="col-md-4">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Quotaion Stats</div>
								</div>
								<div class="card-body pb-0">
								<canvas id="chart3" width="400" height="300"></canvas>
							
							<h4 class="card-title text-center mb-3 mt-3">1,294</h4>
							
								</div>
							</div>
						</div>
							<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Order Quotaion/Month</div>
								</div>
								<div class="card-body pb-0">
								<canvas id="chart4" width="400" height="300"></canvas>
							
							
								</div>
							</div>
						</div>
								<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Sales/Month</div>
								</div>
								<div class="card-body pb-0">
								<canvas id="chart5" width="400" height="300"></canvas>
							
							
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Top New Companies</div>
								</div>
								<div class="card-body pb-0">
											<div class="d-flex">
										<div class="avatar">
											<img src="<?php echo base_url(); ?>assets/admin/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
										</div>
										<div class="clearfix d-flex align-items-center justify-content-center justify-content-end pl-2">
											<div>
											<h5 class="fw-bold mb-1">Company Name</h5>
										</div>
										</div>
										<div class="d-flex ml-auto align-items-center">
											<h3 class="text-dark fw-bold">$100 - $600 <span class="text-success">(10%)</span></h3>
										</div>
									</div>
									<div class="separator-dashed"></div>
														<div class="d-flex">
										<div class="avatar">
											<img src="<?php echo base_url(); ?>assets/admin/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
										</div>
										<div class="clearfix d-flex align-items-center justify-content-center justify-content-end pl-2">
											<div>
											<h5 class="fw-bold mb-1">Company Name</h5>
										</div>
										</div>
										<div class="d-flex ml-auto align-items-center">
											<h3 class="text-dark fw-bold">$100 - $600 <span class="text-success">(10%)</span></h3>
										</div>
									</div>
									<div class="separator-dashed"></div>
													<div class="d-flex">
										<div class="avatar">
											<img src="<?php echo base_url(); ?>assets/admin/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
										</div>
										<div class="clearfix d-flex align-items-center justify-content-center justify-content-end pl-2">
											<div>
											<h5 class="fw-bold mb-1">Company Name</h5>
										</div>
										</div>
										<div class="d-flex ml-auto align-items-center">
											<h3 class="text-dark fw-bold">$100 - $600 <span class="text-success">(10%)</span></h3>
										</div>
									</div>
						
								
								
							
								
								</div>
							</div>
						</div>
					
			
					</div>
					<div class="row">
					
				
					</div>
				</div>
			</div>
			
		</div>
		
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
<!--   Core JS Files   -->
<script src="<?php echo base_url(); ?>assets/admin/js/core/jquery.3.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/core/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/core/bootstrap.min.js"></script>

<!-- jQuery UI -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Moment JS -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/moment/moment.min.js"></script>

<!-- Chart JS -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Bootstrap Toggle -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

<!-- Google Maps Plugin -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/gmaps/gmaps.js"></script>

<script src="<?php echo base_url(); ?>assets/admin/js/plugin/gmaps/gmaps.js"></script>

<!-- Sweet Alert -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>


<!-- DateTimePicker -->
    <script src="<?php echo base_url(); ?>assets/admin/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>


<!-- admintheme JS -->
<script src="<?php echo base_url(); ?>assets/admin/js/ready.min.js"></script>


<script>
var ctx = document.getElementById('chart1').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3],
            backgroundColor: [
                '#4ce670',
                '#67abff',
                '#20cc9c',
            ],
            borderWidth: 1
        }]
    },

});


var ctx = document.getElementById('chart2').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3],
            backgroundColor: [
                '#1ee882',
                '#ffb23f',
                '#20cc9c',
            ],
            borderWidth: 1
        }]
    },

});


var ctx = document.getElementById('chart3').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3],
            backgroundColor: [
                '#ff5d7e',
                '#5bf5ca',
                '#69c3c4',
            ],
            borderWidth: 1
        }]
    },

});




var ctx = document.getElementById('chart4').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '# of Votes',
            data: [8, 10, 8, 9,9,7,7,7,10,8,6,10],
            backgroundColor: [
                '#67abff',
                 '#69c3c4',
                 '#67abff',
                    '#69c3c4',
                 '#67abff',
                 '#69c3c4',
                     '#67abff',
                  '#69c3c4',
                 '#67abff',
                  '#69c3c4',
                   '#67abff',
                     '#69c3c4',
                     '#67abff',
                       '#69c3c4'
               
            ],
            borderWidth: 1
        }]
    },

});



var ctx = document.getElementById('chart5').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '# of Votes',
            data: [8, 10, 8, 9,9,7,7,7,10,8,6,10],
            backgroundColor: [
                '#20cc9c',
               
            ],
            borderWidth: 1
        }]
    },

});


	// $('input').tagsinput({
	// 		tagClass: 'badge-info'
	// 	});

</script>
</body>
</html>
