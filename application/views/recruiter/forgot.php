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
                    <div class="col-xl-8 col-11 d-flex justify-content-center">
                        <div class="card bg-authentication rounded-0 mb-0">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                    <img src="<?php echo base_url('/resources/'); ?>app-assets/images/pages/forgot-password.png" alt="branding logo">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">Recover your password</h4>
                                            </div>
                                        </div>
                                        <p class="px-2 mb-0">Please enter your email address and we'll send you instructions on how to reset your password.</p>
                                        <div class="card-content px-2 mt-1"><?php include_once(APPPATH."/views/recruiter/includes/display_msg.php"); ?></div>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <form method="POST" action="<?php echo $action;?>">
                                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <input type="email" name="forgot_email" class="form-control" id="forgot_email" placeholder="Email" required >
                                                        <div class="form-control-position">
                                                            <i class="feather icon-mail"></i>
                                                        </div>
                                                        <label for="user-name">Email</label>
                                                    </fieldset>
                                                    <a href="<?php echo site_url('recruiter/login'); ?>" class="btn btn-outline-primary btn-inline-block px-75 waves-effect waves-light mb-3">Back to Login</a>
                                                    <button type="submit" class="btn btn-primary float-right btn-inline">Recover Password</button>
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
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>