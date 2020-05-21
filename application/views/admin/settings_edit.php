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
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="admin_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="recruiter_name">Field Name</label>
                                                                <input type="text" class="form-control" id="setting_fieldname" name="setting_fieldname" required="" placeholder="Field Name" data-validation-required-message="This Field Name field is required" value="<?php echo set_value('setting_fieldname',@$this->form_data->setting_fieldname);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="recruiter_email">Key Text</label>
                                                                <input type="text" name="setting_keytext" id="setting_keytext" class="form-control" placeholder="Key Text" required="" data-validation-required-message="The Key Text field is required" value="<?php echo set_value('setting_keytext',@$this->form_data->setting_keytext);?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if($this->form_data->setting_type=="Image"){ ?>

                                                        <?php
                                                        if(@$this->form_data->setting_value != ""){
                                                            $col1 = 'col-md-8';
                                                        }else{
                                                            $col1 = 'col-md-12';
                                                        }
                                                        ?>
                                                        <div class="<?php echo $col1; ?>">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label for="basicInputFile"> Photo</label>
                                                                    <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" id="setting_image" name="setting_image">
                                                                            <label class="custom-file-label" for="setting_image">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if($this->form_data->setting_type=="Image" && $this->form_data->setting_value != ""){ ?>

                                                                <img src="<?php echo check_image($this->form_data->setting_value,'uploads/others','thumb'); ?>" height="45" width="45" class="avatar m-1 avatar-lg"/>
                                                        <?php } ?>
                                                    <?php }else{ ?>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label for="recruiter_email">Key Value</label>
                                                                    <input type="text" name="setting_value" id="setting_value" class="form-control" placeholder="Key Text" required="" data-validation-required-message="The Value field is required" value="<?php echo set_value('setting_value',@$this->form_data->setting_value);?>" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

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
                                                <input type="hidden" name="setting_type" id="setting_type" value="<?php echo $this->form_data->setting_type; ?>"/>
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