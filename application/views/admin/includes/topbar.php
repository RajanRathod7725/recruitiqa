    
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="javascript:;"><i class="ficon feather icon-menu"></i></a></li>
                        </ul>
                        <ul class="nav navbar-nav bookmark-icons">
                        <?php if(is_role_access('recruiter','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/recruiter'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Recruiter"><i class="ficon feather icon-user"></i></a></li>
                        <?php } if(is_role_access('employer','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/employer'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Employer"><i class="ficon feather icon-users"></i></a></li>
                        <?php } if(is_role_access('candidate','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/candidate'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Candidate"><i class="ficon feather icon-user-check"></i></a></li>
                        <?php } if(is_role_access('job','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/job'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Jobs"><i class="ficon fa fa-slideshare"></i></a></li>
                        <?php } if(is_role_access('team','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/team'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Team"><i class="ficon fa fa-users"></i></a></li>
                        <?php } if(is_role_access('email_request','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/email_request'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Email Request"><i class="ficon fa fa-envelope-open-o"></i></a></li>
                        <?php } if(is_role_access('subscription_request','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/subscription_request'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Subscription Request"><i class="ficon fa fa-hand-pointer-o"></i></a></li>
                        <?php } if(is_role_access('subscription_request_cust','index','display')){ ?>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="<?php echo site_url('/admin/subscription_request_cust'); ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Custom Subscription Request"><i class="ficon fa fa-cube"></i></a></li>
                        <?php } ?>

                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">

                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600"><?php echo ucfirst($this->session->userdata('admin_name')); ?></span><span class="user-status">Available</span></div><span><img class="round" src="<?php echo base_url(); ?>/resources/app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?php echo site_url('admin/change_pass'); ?>"><i class="feather icon-lock"></i> Change Password</a>
                                <!--<a class="dropdown-item" href="app-email.html"><i class="feather icon-mail"></i> My Inbox</a>
                                <a class="dropdown-item" href="app-todo.html"><i class="feather icon-check-square"></i> Task</a>
                                <a class="dropdown-item" href="app-chat.html"><i class="feather icon-message-square"></i> Chats</a>-->
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="<?php echo site_url('admin/login/logout');?>"><i class="feather icon-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>