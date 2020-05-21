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
        <!--breadscrum-->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0"><?php echo $main_module;?></h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo site_url().'admin/dashboard';?>">Dashboard</a>
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
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrum-right">
                    <div class="dropdown">
                        <a href="<?php echo $module_base_url; ?>" class="btn-icon btn btn-primary"><i class="feather icon-arrow-left"></i>Back</a>
                    </div>
                </div>
            </div>
        </div>
        <!--breadscrum end-->
        <div class="content-body">
            <section id="page-account-settings">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="job_type_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="name">Name</label>
                                                                <input type="text" class="form-control" id="name" name="name" required="" placeholder="Name" data-validation-required-message="This Name field is required" value="<?php echo set_value('name',@$this->form_data->name);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="description">Description</label>
                                                                <input type="text" class="form-control" id="description" name="description" required="" placeholder="Description" data-validation-required-message="This Description field is required" value="<?php echo set_value('description',@$this->form_data->description);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="minimum_credit">Minimum Credit</label>
                                                                <input type="number" class="form-control" id="minimum_credit" name="minimum_credit" required="" placeholder="Minimum Credit" data-validation-required-message="This Minimum Credit field is required" value="<?php echo set_value('minimum_credit',@$this->form_data->minimum_credit);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="maximum_credit">Maximum Credit</label>
                                                                <input type="number" class="form-control" id="maximum_credit" name="maximum_credit" required="" placeholder="Maximum Credit" data-validation-required-message="This Maximum Credit field is required" value="<?php echo set_value('maximum_credit',@$this->form_data->maximum_credit);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="profile_rate">Profile Rate</label>
                                                                <input type="text" class="form-control" id="profile_rate" name="profile_rate" required="" placeholder="Profile Rate" data-validation-required-message="This Profile Rate field is required" value="<?php echo set_value('profile_rate',@$this->form_data->profile_rate);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="no_of_month">No. Of Month</label>
                                                                <input type="number" class="form-control" id="no_of_month" name="no_of_month" required="" placeholder="No. Of Month" data-validation-required-message="This No. Of Month field is required" value="<?php echo set_value('no_of_month',@$this->form_data->no_of_month);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <?php if($method=='Add'){ ?>
                                                            <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Submit</button>
                                                        <?php }else{ ?>
                                                            <button type="submit" class="btn btn-danger mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Save changes</button>
                                                        <?php } ?>
                                                        <button type="reset" class="btn btn-outline-warning waves-effect waves-light">Cancel</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                                <input type="hidden" name="method" value="<?php echo $method;?>"/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--employer detail-->
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