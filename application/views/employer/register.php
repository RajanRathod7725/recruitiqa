<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title><?php echo $title; ?></title>
    <link rel="apple-touch-icon" href="<?php echo base_url('/resources/'); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('/resources/'); ?>app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('/resources/'); ?>app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">

    <!-- BEGIN: Content-->
    <div class="app-content content">

        <div class="content-overlay"></div>
        
        <div class="header-navbar-shadow"></div>
        
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-xl-10 col-11 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0">
                            <div class="row m-0">
                                <div class="col-lg-5 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="<?php echo base_url('/resources/'); ?>app-assets/images/pages/register.jpg" alt="<?php echo $this->site_setting->site_name; ?>">
                                </div>
                                <div class="col-lg-7 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1" style="padding-top: 0Px!important;">
                                            <div class="card-title">
                                                <h4 class="mb-0">Sign up for free</h4>
                                            </div>
                                            <div class="float-right" style="width: 30%">
                                                <img src="<?php echo base_url()."resources/app-assets/images/logo/site_logo.svg" ?>" alt="<?php echo $this->site_setting->site_name; ?>" style="width: 100%;">
                                            </div>
                                        </div>
                                        <p class="px-2 mb-0">Get started for free. <?php echo $this->site_setting->free_subscription_days; ?> days trial with 50 free qualified profiles. No credit card required.</p>

                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <?php include_once(APPPATH."/views/employer/includes/display_msg.php"); ?>
                                                <form method="POST" action="<?php echo site_url('employer/register/create_account');?>" id="emp_reg_frm" enctype="multipart/form-data">
                                                    <h5 class="mb-1">Account Details</h5>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                                <input type="text" name="employer_name" class="form-control" id="employer_name" placeholder="Your Full Name" required value="<?php echo $this->input->post('employer_name'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="feather icon-user"></i>
                                                                </div>
                                                                <label for="employer_name">Your Full Name</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                                <input type="email" name="employer_email" class="form-control" id="employer_email" placeholder="Work Email" required value="<?php echo $this->input->post('employer_email'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="feather icon-mail"></i>
                                                                </div>
                                                                <label for="employer_email">Work Email</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <input type="password" name="employer_password" class="form-control" id="" placeholder="Password" required>
                                                                <div class="form-control-position">
                                                                    <i class="feather icon-lock"></i>
                                                                </div>
                                                                <label for="employer_password">Password</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <input type="number" name="employer_phone" class="form-control" id="employer_phone" placeholder="Contact No." required value="<?php echo $this->input->post('employer_phone'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="feather icon-smartphone"></i>
                                                                </div>
                                                                <label for="employer_phone">Contact No.</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <select name="employer_country" class="select2 form-control" id="employer_country">
                                                                    <option value="">Select Country</option>
                                                                    <?php foreach ($countries as $country){?>
                                                                        <option value="<?php echo $country->country_id; ?>" <?php echo $this->form_data->employer_country==$country->country_id?'selected':'';?>><?php echo $country->country_name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <input type="text" name="employer_position" class="form-control" id="employer_position" placeholder="Your Position" required value="<?php echo $this->input->post('employer_position'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="fa fa-graduation-cap"></i>
                                                                </div>
                                                                <label for="employer_position">Your Position</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <input type="url" name="employer_linkedin" class="form-control" id="employer_linkedin" placeholder="LinkedIn Profile Link" value="<?php echo $this->input->post('employer_linkedin'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="fa fa-linkedin"></i>
                                                                </div>
                                                                <label for="employer_linkedin">LinkedIn Profile Link</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group position-relative has-icon-left">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="employer_photo" name="employer_photo">
                                                                    <label class="custom-file-label" for="employer_photo">Profile Photo</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <h5 class="mb-1">Company Details</h5>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                                <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Company Name" required value="<?php echo $this->input->post('company_name'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="fa fa-building-o"></i>
                                                                </div>
                                                                <label for="company_name">Company Name</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                                <input type="text" name="company_website" class="form-control" id="company_website" placeholder="Company Website" required value="<?php echo $this->input->post('company_website'); ?>">
                                                                <div class="form-control-position">
                                                                    <i class="fa fa-television"></i>
                                                                </div>
                                                                <label for="company_website">Company Website</label>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-12">
                                                            <fieldset class="checkbox d-inline-block">
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox"  id="term_condition" name="term_condition" <?php echo @$this->form_data->term_condition=='on'?'checked':''; ?>>
                                                                    <span class="vs-checkbox">
                                                                        <span class="vs-checkbox--check">
                                                                            <i class="vs-icon feather icon-check"></i>
                                                                        </span>
                                                                    </span>
                                                                    <span> I agree to the </span>
                                                                </div>
                                                            </fieldset>
                                                            <div class="d-inline-block" style="vertical-align: super;"><a href="<?php echo site_url('terms-and-condition'); ?>" target="_blank"> Terms of Services</a> & <a href="<?php echo site_url('privacy_policy'); ?>" target="_blank">Privacy Policy</a></div>
                                                        </div>
                                                    </div>
                                                    <div class="d-inline-block" style="margin-top: 10px;">Already Registered? <a href="<?php echo site_url('employer/login'); ?>">Login</a></div>
                                                    <button type="submit" class="btn btn-primary float-right btn-inline mb-2">Create Account</button>
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


    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo base_url('/resources/'); ?>app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?php echo base_url('/resources/'); ?>app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo base_url('/resources/'); ?>app-assets/js/core/app.js"></script>
    <script src="<?php echo base_url('/resources/'); ?>app-assets/js/scripts/components.js"></script>
    <script src="<?php echo base_url('/resources/'); ?>app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="<?php echo base_url('/resources/'); ?>assets/js/custom.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->
</body>
<!-- END: Body-->

</html>