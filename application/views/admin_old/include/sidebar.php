<?php
$arr=$this->usergroupsmodel->get_privillages_user();
				$pr=array();
				$i=0;
				foreach($arr as $a){
						$pr[$i++]=$a['module'];
					}
					?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('admin/dashboard'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?php echo $this->config->item('site_title_short'); ?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $this->config->item('site_title'); ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        <?php $usr=$this->usersmodel->get_row($this->session->userdata('admin_id'));
			  		$profile_image=$usr->image!=''?$usr->image:'no_image.png';
			   ?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url().'uploads/users/'.$profile_image; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">App Settings</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
             <img src="<?php echo base_url().'uploads/users/'.$profile_image; ?>" class="img-circle" alt="User Image">
				 <p>
                  <?= $usr->name; ?>
                </p>
                <a href="<?php echo site_url().'admin/settings/profile'; ?>" class="btn btn-danger">Profile Settings</a>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url().'admin/settings'; ?>" class="btn btn-default btn-flat">Change password</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url().'admin/settings/logout'; ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="<?php if(@$active=='dashboard'){echo 'active';} ?> treeview">
          <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              
            </span>
          </a>
          
        </li>
        
        
          <?php 
				 
				
				 // $menu=$this->config->item('menu');
				
				  foreach($this->config->item('menu') as $m){
				  		$per=explode(',',$m['permission']);
						$flag=0;
						if($per[0]==''){
								$flag=1;
							}
						else{
								foreach($per as $pe){
										if(in_array($pe,$pr)){
												$flag=1;
											}
									}
							}
					?>
                     <li class="treeview<?= (@$active==$m['active'])?' active':''; ?><?= $flag==1?'':' hide'; ?>">
          <a href="<?= ($m['link']!='#' || $m['link']!='')?site_url($m['link']):'#'; ?>">
            <i class="<?= $m['icon']?:$this->config->item('site_default_no_menu_icon'); ?>"></i> <span><?= $m['name']; ?></span>
            <?php if(@$m['sub_child']){ ?>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
             <?php } ?>
             </a>
             	
               <?php if(@$m['sub_child']){ ?>
            	<ul class="treeview-menu">
				<?php foreach(@$m['sub_child'] as $c){ 
						$per=explode(',',$c['permission']);
						$flag=0;
						if($per[0]==''){
								$flag=1;
							}
						else{
								foreach($per as $pe){
										if(in_array($pe,$pr)){
												$flag=1;
											}
									}
							}
				
				?>
                		<li class="<?= (@$active_sub==$c['active'])?'active':''; ?><?= $flag==1?'':' hide'; ?>">
                          <a href="<?= ($c['link']!='#' || $c['link']!='')?site_url($c['link']):'#'; ?>"><i class="<?= $c['icon']; ?>" aria-hidden="true"></i> <?= $c['name']; ?>
                            <?php if(@$c['sub_child']){ ?>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            <?php } ?>
                        	 </a>
                             <?php if(@$c['sub_child']){ ?>
                            <ul class="treeview-menu">
                            <?php foreach(@$c['sub_child'] as $b){ 
									$per=explode(',',$b['permission']);
											$flag=0;
											if($per[0]==''){
													$flag=1;
												}
											else{
													foreach($per as $pe){
															if(in_array($pe,$pr)){
																	$flag=1;
																}
														}
												}
							?>
                            <li class="<?= (@$active_sub_sub==$b['active'])?'active':''; ?><?= $flag==1?'':' hide'; ?>"><a href="<?= ($b['link']!='#' || $b['link']!='')?site_url($b['link']):'#'; ?>"><i class="<?= $b['icon']; ?>"></i> <?= $b['name']; ?></a></li>
                            <?php } ?>
                          </ul>
                          <?php } ?>
                        </li>
				<?php } ?>
                </ul>
           <?php } ?>
         
          
        </li>
					<?php
					}?>
        
       
        
        <?php if(($this->session->userdata('admin_type')==1)){ ?>
        <li class="<?php if(@$active=='users'){echo 'active';} ?> treeview">
		
          <a href="#">
            <i class="fa fa-cogs" aria-hidden="true"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
            
            <li class="<?php if(@$active_sub=='usergroups'){echo 'active';} ?>">
              <a href="#"><i class="fa fa-users" aria-hidden="true"></i> User Groups
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(@$active_sub_sub=='usergroups_list'){echo 'active';} ?>"><a href="<?php echo site_url('admin/usergroups');?>"><i class="fa fa-list"></i> List</a></li>
                <li class="<?php if(@$active_sub_sub=='usergroups_add'){echo 'active';} ?>"><a href="<?php echo site_url('admin/usergroups/add');?>"><i class="fa fa-pencil-square-o"></i> Add</a></li>
              </ul>
            </li>
            
            <li class="<?php if(@$active_sub=='users'){echo 'active';} ?>">
              <a href="#"><i class="fa fa-user" aria-hidden="true"></i> Users
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(@$active_sub_sub=='users_list'){echo 'active';} ?>"><a href="<?php echo site_url('admin/users');?>"><i class="fa fa-list"></i> List</a></li>
                <li class="<?php if(@$active_sub_sub=='users_add'){echo 'active';} ?>"><a href="<?php echo site_url('admin/users/add');?>"><i class="fa fa-pencil-square-o"></i> Add</a></li>
              </ul>
            </li>
            
            <li class="<?php if(@$active_sub=='config'){echo 'active';} ?>">
              <a href="#"><i class="fa fa-wrench" aria-hidden="true"></i> Site Config
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(@$active_sub_sub=='config_list'){echo 'active';} ?>"><a href="<?php echo site_url('admin/site_config');?>"><i class="fa fa-list"></i> List</a></li>
                <li class="<?php if(@$active_sub_sub=='config_add'){echo 'active';} ?>"><a href="<?php echo site_url('admin/site_config/add');?>"><i class="fa fa-pencil-square-o"></i> Add</a></li>
              </ul>
            </li>
            
            
          </ul>
        </li>
        
		<?php } ?>
		
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>