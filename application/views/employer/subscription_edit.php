<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/vendors/css/extensions/nouislider.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/css/plugins/extensions/noui-slider.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/css/core/colors/palette-noui.css">
    <script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/extensions/nouislider.min.js"></script>
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
                                <li class="breadcrumb-item"><a href="<?php echo site_url().'employer/dashboard';?>">Dashboard</a>
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
            <section id="accordion-with-margin">
                <div class="row">

                    <div class="col-sm-12">
                        <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                        <div class="card collapse-icon accordion-icon-rotate">
                            <div class="card-body">
                                <form name="subscription_form" action="<?php echo $action; ?>" method="post">
                                <div class="row"><!--justify-content-md-center-->
                                    <?php foreach ($plans as $plan){ ?>
                                    <div class="col-lg-4 col-md-4 col-12" style="text-align: center">
                                        <div class="card border-primary text-center price">
                                            <div class="card-body d-flex justify-content-between align-items-center flex-column">
                                                <h3 class="mb-0 text-primary"><?php echo $plan->name; ?></h3>
                                            </div>
                                            <div class="card-body d-flex justify-content-around flex-column align-items-center pt-0">
                                                <h5 class="mb-1"><span> <?php echo $plan->description; ?></span></h5>
                                                <?php
                                                $slab_divider = 4;
                                                $cal_val = $plan->maximum_credit-$plan->minimum_credit;
                                                $single_slab = $cal_val/$slab_divider;
                                                $per = 100/$slab_divider;
                                                $temp = 0;
                                                $temp_per = $per;
                                                $result_array = '';
                                                for($i=1;$i<$slab_divider;$i++){
                                                    if($i==1){
                                                        $temp = $plan->minimum_credit+$single_slab;
                                                    }else{
                                                        $temp = $temp + $single_slab;
                                                    }
                                                    $result_array .="'".$temp_per."%': [".$temp.", 0],";
                                                    $temp_per +=$per;
                                                }
                                                ?>

                                                <div id="pips_range_<?php echo $plan->subscription_id; ?>" class="mt-1 mb-5 w-100"></div>
                                                <div class="row w-100">
                                                    <div class="col-md-6 p-0" style="text-align: left">
                                                        <h6 class="d-inline-block" style="font-size:1rem;"><strong>Profiles: </strong> <span id="profile_<?php echo $plan->subscription_id; ?>"></span></h6>
                                                        <input type="hidden" name="profile_count_<?php echo $plan->subscription_id; ?>" id="profile_count_<?php echo $plan->subscription_id; ?>">
                                                    </div>
                                                    <div class="col-md-6 p-0" style="text-align: right">
                                                        <h6 class="d-inline-block" style="font-size:1rem;"><strong>1 Profile Rate: </strong> <span>$<?php echo $plan->profile_rate; ?></span></h6>
                                                    </div>
                                                </div>
                                                <h2 class="">$<span class="font-large-2" id="price_<?php echo $plan->subscription_id; ?>">40</span><span class="font-small-1"> Billed <?php echo $plan->description; ?></span></h2>
                                                <script type="text/javascript">
                                                    // RTL Support
                                                    var direction = 'ltr';
                                                    // Range
                                                    var range_all_sliders = { 'min': [<?php echo $plan->minimum_credit; ?>], <?php echo $result_array; ?> 'max': [<?php echo $plan->maximum_credit; ?>]};
                                                    var pipsRange_<?php echo $plan->subscription_id; ?> = document.getElementById('pips_range_<?php echo $plan->subscription_id; ?>');
                                                    noUiSlider.create(pipsRange_<?php echo $plan->subscription_id; ?>, { range: range_all_sliders, start: 0, behaviour: 'tap', connect: 'lower', direction: direction, pips: { mode: 'range', density: 3}
                                                    });

                                                    // get range infos at html
                                                    var dateValues_<?php echo $plan->subscription_id; ?> = [document.getElementById('profile_<?php echo $plan->subscription_id; ?>')];
                                                    var profile_count_<?php echo $plan->subscription_id; ?> = [document.getElementById('profile_count_<?php echo $plan->subscription_id; ?>')];
                                                    var price_id_<?php echo $plan->subscription_id; ?> = document.getElementById('price_<?php echo $plan->subscription_id; ?>');
                                                    pipsRange_<?php echo $plan->subscription_id; ?>.noUiSlider.on('update', function (values, handle) {
                                                        //profile counter
                                                        var profile = Math.round(+values[handle]);
                                                        dateValues_<?php echo $plan->subscription_id; ?>[handle].innerHTML = profile ;
                                                        profile_count_<?php echo $plan->subscription_id; ?>[handle].value = profile ;
                                                        var cal = profile *<?php echo $plan->profile_rate; ?>;
                                                        //price calculation
                                                        price_id_<?php echo $plan->subscription_id; ?>.innerHTML = Math.round(cal);
                                                    });

                                                </script>
                                                <button type="submit" class="btn btn-primary w-100 box-shadow-1 mt-2" value="<?php echo $plan->subscription_id; ?>" name="subscription">Send Request & Buy</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-lg-4 col-md-4 col-12" style="text-align: center">
                                        <div class="card border-primary text-center price">
                                            <div class="card-body d-flex justify-content-between align-items-center flex-column mb-2">
                                                <h3 class="mb-0 text-primary">Customized Plan</h3>
                                            </div>
                                            <div class="card-body d-flex justify-content-around flex-column align-items-center pt-0">
                                                <h5 class="mb-4"><span>Monthly / Quarterly</span></h5>
                                                <h5 class="mb-4"><span>Need Something <br>Customized?</span></h5>
                                                <a class="sub-contact btn btn-primary w-100 box-shadow-1 mt-1" href="javascript:;">Schedule a Call</a>
                                                <a class="sub-contact btn btn-primary w-100 box-shadow-1 mt-1" href="javascript:;">Send Request Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                </form>
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
<script>
    $(document).on('click','.sub-contact',function () {
        $('#custom_package_model').modal('show');
    });

    $(document).on('click','#custome_sub_send',function () {
        var msg = $('#sub_msg').val();
        form_data = new FormData();
        form_data.append('msg', msg);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_custom_sub_req',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success('Custom subscription request has been sent successfully', 'Success!');
                    $('#custom_package_model').modal('hide');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    });

</script>
<!--custom_package model-->
<div class="modal fade text-left" id="custom_package_model" tabindex="-1" role="dialog" aria-labelledby="custom_package_model" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Let us know how we can help you!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Please write us your customized requirements : </label>
                        <div class="form-group" >
                            <textarea class="form-control" name="sub_msg" id="sub_msg" rows="5" placeholder="Your message"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="custome_sub_send">Send Now</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
