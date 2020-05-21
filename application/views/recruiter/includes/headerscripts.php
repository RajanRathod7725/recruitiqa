<?php  $page_name= $this->router->fetch_class();?>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>/resources/app-assets/images/ico/apple-icon-120.png">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>/resources/app-assets/images/ico/favicon.ico">
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/charts/apexcharts.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/extensions/tether-theme-arrows.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/extensions/tether.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/tables/datatable/datatables.min.css">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/components.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/themes/dark-layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/themes/semi-dark-layout.css">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/core/menu/menu-types/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/core/colors/palette-gradient.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/pages/dashboard-analytics.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/pages/card-analytics.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/plugins/tour/tour.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/extensions/toastr.css">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/assets/css/style.css<?php echo JS_CSS_VERSION; ?>">
<!-- END: Custom CSS-->
<?php require_once('js_messages.php'); ?>