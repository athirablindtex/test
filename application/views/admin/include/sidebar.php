<?php
$arr = $this->usergroupsmodel->get_privillages_user();
$pr = array();
$i = 0;
foreach ($arr as $a) {
   $pr[$i++] = $a['module'];
}

$usr = $this->usersmodel->get_row_details($this->session->userdata('admin_id'));
$profile_image = is_file('uploads/users/' . @$usr->image) ? base_url() . 'uploads/users/' . @$usr->image : base_url() . 'uploads/placeholder/image1.png';
?>
<!-- Sidebar -->
<div class="sidebar">
<style>
/* ===== Sidebar Container ===== */
.sidebar {
    width: 260px;
    background: #ffffff;
    border-right: 1px solid #e5e7eb;
    font-family: "Inter", sans-serif;
}

.sidebar-wrapper {
    padding: 10px;
}

.sidebar-background {
    display: none;
}

/* ===== User Section ===== */
.sidebar .user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 10px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 8px;
}

.avatar-img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
}

.user .info span {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    line-height: 1.2;
}

.user-level {
    font-size: 12px;
    font-weight: 400;
    color: #6b7280;
}

/* ===== Menu ===== */
.sidebar .nav {
    margin: 0;
    padding: 0;
    list-style: none;
}

.sidebar .nav-item {
    margin-bottom: 4px;
}

.sidebar .nav-item > a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar .nav-item > a i {
    width: 18px;
    text-align: center;
    font-size: 15px;
}

/* Hover */
.sidebar .nav-item > a:hover {
    background: #f3f4f6;
    color: #111827;
}

/* Active */
.sidebar .nav-item.active > a {
    background: #ede9fe;
    color: #7b2cbf;
    font-weight: 600;
}

.sidebar .nav-item.active i {
    color: #7b2cbf;
}

/* ===== Sub Menu ===== */
.nav-collapse {
    padding-left: 28px;
    margin-top: 4px;
}

.nav-collapse li {
    margin-bottom: 2px;
}

.nav-collapse li a {
    display: block;
    padding: 6px 10px;
    font-size: 12.5px;
    color: #6b7280;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.nav-collapse li a:hover,
.nav-collapse li.active a {
    background: #f5f3ff;
    color: #7b2cbf;
}

/* ===== Caret ===== */
.caret {
    margin-left: auto;
    font-size: 11px;
}

/* ===== Scrollbar ===== */
.scrollbar-inner {
    max-height: 100vh;
    overflow-y: auto;
}

.scrollbar-inner::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-inner::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
}



</style>
   <div class="sidebar-background"></div>
   <div class="sidebar-wrapper scrollbar-inner">
      <div class="sidebar-content">
         <div class="user">
            <div class="avatar-sm float-left mr-2">
               <img src="<?= @$profile_image; ?>" alt="..." class="avatar-img rounded-circle">
            </div>
            <div class="info">
               <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                  <span>
                     <?= @$usr->name; ?>
                     <span class="user-level"><?= $this->config->item('company_id') == $usr->user_group ? @$usr->company_name : @$usr->group; ?></span>
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
            <li class="nav-item <?php if (@$active == 'dashboard') {
                                    echo 'active';
                                 } ?>">
               <a href="<?php echo site_url('admin/dashboard'); ?>">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
               </a>
            </li>

            <?php


            // $menu=$this->config->item('menu');
            $i = 0;
            $menu_items = "";
            foreach ($this->config->item('menu') as $m) {
               $i++;
               $item_id = 'main_menu_' . $i;
               $per = explode(',', $m['permission']);
               $flag = 0;
               if ($per[0] == '') {
                  $flag = 1;
               } else {
                  foreach ($per as $pe) {
                     if (in_array($pe, $pr)) {
                        $flag = 1;
                        break;
                     }
                  }
               }
               if ($flag == 1) {
                  $sub_child_exist = 0;
                  $subs = "";
                  if (@$m['sub_child']) {
                     if (count(@$m['sub_child']) > 0) {

                        foreach ($m['sub_child'] as $sub) {
                           $cur_per = 0;
                           $sub_per = explode(',', $sub['permission']);
                           if ($sub_per[0] == '') {
                              $sub_child_exist = 1;
                           } else {
                              foreach ($sub_per as $spr) {
                                 if (in_array($spr, $pr)) {
                                    $sub_child_exist = 1;
                                    $cur_per = 1;
                                    break;
                                 }
                              }
                           }
                           if ($sub_child_exist == 1 && $cur_per == 1) {
                              $link = ($sub['link'] != '#' || $sub['link'] != '') ? site_url($sub['link']) : '#';
                              $mn_sub = @$active_sub == $sub['active'] ? 'class="active"' : '';
                              $subs .= '<li ' . $mn_sub . '><a href="' . $link . '"><span class="sub-item">' . $sub["name"] . '</span></a></li>';
                           }
                        }
                     }
                  }
                  $menu_item = "";
                  $mn_active = (@$active == $m['active']) ? ' active' : '';
                  if ($subs != "") {
                     $mn_show_sub = (@$active == $m['active']) ? 'show' : '';
                     $sub_item = '<div class="collapse ' . $mn_show_sub . '" id="' . $item_id . '"><ul class="nav nav-collapse">' . $subs . '</ul></div>';
                     $menu_item = '<li class="nav-item ' . $mn_active . '">
                                             <a data-toggle="collapse" href="#' . $item_id . '">
                                                   <i class="' . $m['icon'] . '"></i>
                                                   <p>' . $m["name"] . '</p>
                                                   <span class="caret"></span>
                                             </a>' . $sub_item . '</li>';
                  } else {
                     $menu_item = '<li class="nav-item ' . $mn_active . '">
                                          <a href="' . site_url($m["link"]) . '">
                                          <i class="' . $m['icon'] . '"></i>
                                             <p>' . $m["name"] . '</p>
                                             
                                          </a>
                                       </li>';
                  }

                  $menu_items .= $menu_item;
            ?>


            <?php }
            }
            echo $menu_items;
            ?>




            <?php if ($this->session->userdata('admin_type') == $this->config->item('super_admin_id')) {
               $mn_active_settings = (@$active == 'users') ? ' active' : '';
               $mn_sub_users = @$active_sub == 'users' ? 'class="active"' : '';
               $mn_sub_group = @$active_sub == 'usergroups' ? 'class="active"' : '';
               $mn_sub_config = @$active_sub == 'config' ? 'class="active"' : '';
            ?>

               <li class="nav-item <?= $mn_active_settings; ?>">
                  <a data-toggle="collapse" href="#submenu">
                     <i class="fas fa-cog"></i>
                     <p>Settings</p>
                     <span class="caret"></span>
                  </a>
                  <div class="collapse <?= @$active == 'users' ? 'show' : ''; ?>" id="submenu">
                     <ul class="nav nav-collapse">

                        <li <?= $mn_sub_users; ?>>
                           <a href="<?php echo site_url('admin/users/list'); ?>">
                              <span class="sub-item">Users</span>
                           </a>
                        </li>
                        <li <?= $mn_sub_group; ?>>
                           <a href="<?php echo site_url('admin/usergroups/list'); ?>">
                              <span class="sub-item">Users Groups</span>
                           </a>
                        </li>
                        <li <?= $mn_sub_config; ?>>
                           <a href="<?php echo site_url('admin/site_config/list'); ?>">
                              <span class="sub-item">Site Config</span>
                           </a>
                        </li>
                         <li <?= $mn_sub_config; ?>>
                           <a href="<?php echo site_url('admin/userhistory/list'); ?>">
                              <span class="sub-item">User History</span>
                           </a>
                        </li>

                     </ul>
                  </div>
               </li>
            <?php } ?>






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






<div class="modal fade" id="roomTypeModal" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  Room </span>
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


<div class="modal fade" id="vendorModal" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  Vendor </span>
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


<div class="modal fade" id="subProductTypeModal" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header no-bd">
            <h5 class="modal-title">
               <span class="fw-mediumbold">
                  Sub product type </span>
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