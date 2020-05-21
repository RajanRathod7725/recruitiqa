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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-12">
                                            <h3 class="font-weight-bold"><?php echo $this->common_model->filterOutput($row->job_title); ?><span class="font-small-3"> (<?php echo $this->common_model->filterOutput($row->code); ?>)</span></h3>
                                            <p class="p-0 mb-1"><?php echo $this->common_model->filterOutput($row->job_location); ?></p>
                                            <p class="p-0 mb-1">Client Name: <?php echo $this->common_model->filterOutput($row->employer_name); ?> | Job Industry: <?php echo $this->common_model->filterOutput($row->job_industry); ?></p>
                                            <p class="p-0 mb-2"><?php echo $this->common_model->filterOutput($row->job_type); ?></p>

                                            <h5 class="font-weight-bold">Sourcing Location(s): </h5>
                                            <p class="p-0 mb-1">
                                            <ol style="padding-left: 15px;">
                                                <?php foreach($locations as $loc){ ?>
                                                    <li>
                                                        <?php echo $loc->location;
                                                        if($loc->radius!=''){
                                                            echo " [ ".$loc->radius." ]";
                                                        }
                                                        ?>
                                                    </li>
                                                <?php } ?>
                                            </ol>
                                            </p>

                                            <h5 class="font-weight-bold">Overall Years of Experience:</h5>
                                            <p class="p-0 mb-1">Min - <?php echo $this->common_model->filterOutput($row->min_experience); ?> <span class="pr-4"></span>Max - <?php echo $this->common_model->filterOutput($row->max_experience); ?></p>

                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <h3 class="font-weight-bold mb-2 text-lg-center"><?php echo $this->common_model->filterOutput($row->remaining_candidate); ?> Remaining</h3>
                                            <?php if($row->remaining_candidate>0){?>
                                            <a class="btn btn-primary mr-1 waves-effect waves-light w-100 font-medium-4"  href="<?php echo $link_add_candidate.$row->job_id;?>">Submit Candidate</a>
                                            <?php }?>
                                        </div>

                                        <div class="col-md-12 col-sm-12">
                                            <hr>
                                            <h5 class="font-weight-bold">Job Description:</h5>
                                            <div class="remove-all-styles">
                                            <p class="p-0 mb-1">
                                                <?php echo $row->job_description; ?>
                                            </p></div>
                                            <hr>
                                            <h5 class="font-weight-bold">Must Have (Required Skills/Experiences):</h5>
                                            <p class="p-0 mb-1">
                                                <?php echo $this->common_model->filterOutput($row->job_requirement); ?>
                                            </p>

                                            <?php if($row->job_remark!=''){?>
                                                <h5 class="font-weight-bold">Advise to the Sources/additional information/any important comments:</h5>
                                                <p class="p-0 mb-1">
                                                    <?php echo $this->common_model->filterOutput($row->job_remark); ?>
                                                </p>
                                            <?php } ?>

                                            <?php if($row->job_profile_banchmark!=''){?>
                                                <h5 class="font-weight-bold">Banchmark Profile(s):</h5>
                                                <p class="p-0 mb-1">
                                                    <?php echo $this->common_model->filterOutput($row->job_profile_banchmark); ?>
                                                </p>
                                            <?php } ?>
                                            <?php if($row->black_listed_company!=''){?>
                                                <h5 class="font-weight-bold">Blacklisted Company for this job:</h5>
                                                <p class="p-0 mb-1">
                                                    <?php echo $this->common_model->filterOutput($row->black_listed_company); ?>
                                                </p>
                                            <?php } ?>

                                            <?php if($row->remaining_candidate>0){?>
                                            <div class="w-100 d-inline-block" style="text-align: center;">
                                                <a class="btn btn-primary mr-1 waves-effect waves-light w-10 font-medium-4" href="<?php echo $link_add_candidate.$row->job_id;?>">Submit Candidate</a>
                                            </div>
                                            <?php } ?>
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