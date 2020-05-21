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
                                                        <h4>Company Details:</h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="company_name">Name</label>
                                                                <input type="text" class="form-control" id="company_name" name="company_name" required="" placeholder="Name" data-validation-required-message="This Name field is required" value="<?php echo set_value('company_name',@$this->form_data->company_name);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="company_website">Website</label>
                                                                <input type="text" name="company_website" id="company_website" class="form-control" placeholder="Website" required="" data-validation-required-message="The Website field is required" value="<?php echo set_value('company_website',@$this->form_data->company_website);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--employer detail-->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <div class="row">
                                                    <div class="col-12">
                                                        <h4>Employer Account Details:</h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_name">Name</label>
                                                                <input type="text" class="form-control" id="employer_name" name="employer_name" required="" placeholder="Name" data-validation-required-message="This Name field is required" value="<?php echo set_value('employer_name',@$this->form_data->employer_name);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_email">Email</label>
                                                                <input type="email" name="employer_email" id="employer_email" class="form-control" placeholder="Email" required="" data-validation-required-message="The email field is required" value="<?php echo set_value('employer_email',@$this->form_data->employer_email);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_password">Password</label>
                                                                <div class="wrapper">
                                                                    <input type="password" name="employer_password" class="form-control" <?php /*if($method=='Add'){ echo "required"; }*/?> id="employer_password" placeholder="Password" data-validation-required-message="The password field is required" minlength="6">
                                                                    <button id="toggleBtn" class="  feather icon-eye toggler-ico" type="button" >&nbsp;</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_phone">Contact Number</label>
                                                                <input type="number" name="employer_phone" class="form-control" required id="employer_phone" placeholder="Contact Number" data-validation-required-message="The Contact Number is required" minlength="10" maxlength="13" value="<?php echo set_value('employer_phone',@$this->form_data->employer_phone);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_country">Country</label>
                                                                <select name="employer_country" class="select2 form-control" id="employer_country">
                                                                    <option value="">Select Country</option>
                                                                    <?php foreach ($countries as $country){?>
                                                                        <option value="<?php echo $country->country_id; ?>" <?php echo $this->form_data->employer_country==$country->country_id?'selected':'';?>><?php echo $country->country_name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_position">Position in company</label>
                                                                <input type="text" class="form-control" id="employer_position" name="employer_position" placeholder="Position in company" data-validation-required-message="This Position in company field is required" value="<?php echo set_value('employer_position',@$this->form_data->employer_position);?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="employer_linkedin">LinkedIn Profile Link</label>
                                                                <input type="link" class="form-control" id="employer_linkedin" name="employer_linkedin" placeholder="LinkedIn Profile Link" data-validation-required-message="This LinkedIn Profile Link field is required" value="<?php echo set_value('employer_linkedin',@$this->form_data->employer_linkedin);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if(@$this->form_data->employer_photo != ""){
                                                        $col1 = 'col-md-8';
                                                    }else{
                                                        $col1 = 'col-md-12';
                                                    }
                                                    ?>
                                                    <div class="<?php echo $col1; ?>">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="basicInputFile">Profile Photo</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="employer_photo" name="employer_photo">
                                                                    <label class="custom-file-label" for="employer_photo">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if(@$this->form_data->employer_photo != ""){ ?>

                                                        <img src="<?php echo check_image($this->form_data->employer_photo,'uploads/employer','thumb'); ?>" height="45" width="45" class="avatar m-1 avatar-lg"/>
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