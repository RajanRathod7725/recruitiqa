<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>

</head>


<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

<?php require_once('includes/topbar.php'); ?>
<?php require_once('includes/sidebar.php'); ?>

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0"><?php echo $main_module;?></h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo site_url().'recruiter/dashboard';?>">Dashboard</a>
                                </li>
                                <?php if(@$sub_module==''){ ?>
                                    <li class="breadcrumb-item active"> <?php echo $main_module;?>
                                    </li>
                                <?php }else{ ?>
                                    <li class="breadcrumb-item"><a href="<?php echo $module_base_url;?>"><?php echo $main_module;?></a>
                                    </li>
                                    <li class="breadcrumb-item active"> <?php echo $sub_module;?>
                                    </li>
                                <?php }?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="page-account-settings">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="admin_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <!--<div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="account-old-password">Old Password</label>
                                                                <input type="password" class="form-control" id="account-old-password" required="" placeholder="Old Password" data-validation-required-message="This old password field is required">
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="account-new-password">New Password</label>
                                                                <input type="password" name="password" id="account-new-password" class="form-control" placeholder="New Password" required="" data-validation-required-message="The password field is required" minlength="6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="account-retype-new-password">Retype New
                                                                    Password</label>
                                                                <input type="password" name="cpassword" class="form-control" required="" id="account-retype-new-password" data-validation-match-match="password" placeholder="New Password" data-validation-required-message="The Confirm password field is required" minlength="6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Save
                                                            changes</button>
                                                        <button type="reset" class="btn btn-outline-warning waves-effect waves-light">Cancel</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>


<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>

</body>

</html>