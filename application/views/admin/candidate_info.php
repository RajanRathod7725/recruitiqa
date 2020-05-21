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
        </div>
        <!--breadscrum end-->

        <div class="content-body">
            <!-- page users view start -->
            <section class="page-users-view">
                <div class="row">
                    <!-- account start -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><?php echo $candidate->candidate_name.'\'s Information';?></div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="users-view-image">
                                        <img src="<?php echo check_image($candidate->candidate_photo,'uploads/candidate','size150'); ?>" class="users-avatar-shadow w-100 rounded mb-2 pr-2 ml-1" alt="avatar">
                                    </div>
                                    <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                        <table>
                                            <tr>
                                                <td class="font-weight-bold">Username</td>
                                                <td><?php echo $this->common_model->filterOutput($candidate->candidate_name); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Current Location</td>
                                                <td><?php echo $this->common_model->filterOutput($candidate->candidate_location); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Current Job Title</td>
                                                <td><?php echo $this->common_model->filterOutput($candidate->current_job_title); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Resume</td>
                                                <td>
                                                    <div class="font-medium-3">
                                                    <a href="<?=site_url().'uploads/candidate/resume/'.$candidate->candidate_resume; ?>"target="_blank"><i class="feather
icon-file-text ql-size-large"></i></a></div></td>
                                            </tr>
                                            <?php if($candidate->candidate_linkedin!='' || $candidate->candidate_git!='' || $candidate->candidate_fb!='' || $candidate->candidate_twitter!='' || $candidate->candidate_stack!='' || $candidate->candidate_google!='' || $candidate->candidate_xing!=''){ ?>
                                                <tr>
                                                    <td class="font-weight-bold">Social Links</td>
                                                    <td>
                                                        <?php if($candidate->candidate_linkedin!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_linkedin); ?>" target="_blank" title="LinkedIn"><i class="fa fa-linkedin-square font-medium-4 " style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_git!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_git); ?>" target="_blank" title="Github"><i class="fa fa-github font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_fb!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_fb); ?>" target="_blank" title="Facebook"><i class="fa fa-facebook font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_twitter!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_twitter); ?>" target="_blank" title="Twitter"><i class="fa fa-twitter font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_twitter!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_stack); ?>" target="_blank" title="Stack Overflow"><i class="fa fa-stack-overflow font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_google!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_google); ?>" target="_blank" title="Google Plus"><i class="fa fa-google-plus-official font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } if($candidate->candidate_xing!=''){ ?>
                                                            <a href="<?php echo $this->common_model->addHttp($candidate->candidate_xing); ?>" target="_blank" title="Xing"><i class="fa fa-xing-square font-medium-4" style="margin-right: 0.75rem;"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-5">
                                        <table class="ml-0 ml-sm-0 ml-lg-0">
                                            <tr>
                                                <td class="font-weight-bold">Email</td>
                                                <td><?php if($candidate->candidate_email!=''){
                                                        $emails=explode(',',$candidate->candidate_email);
                                                        foreach ($emails as $email){
                                                            echo $email.'<br>';
                                                        }
                                                    }else{echo '-';} ?></td>

                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Contact No.</td>
                                                <td><?php echo $candidate->candidate_phone!=''?$this->common_model->filterOutput($candidate->candidate_phone):'-'; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Applied For</td>
                                                <td><?php echo $this->common_model->filterOutput($candidate->job_title); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Added Recruiter</td>
                                                <td><?php echo $this->common_model->filterOutput($candidate->recruiter_name); ?></td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- account end -->
                </div>
            </section>
            <!-- page users view end -->
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