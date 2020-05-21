<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">

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

        <!--breadscrum end-->

        <div class="content-body">
            <section id="page_employer_candidate">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 font-large-1">
                                            <i class="feather icon-users"></i>
                                            <label class="font-medium-5 ml-2">Job:</label>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="select2 form-control" name="selsect_job_id" id="selsect_job_id">
                                                <option value="">Select Job </option>
                                                <?php foreach ($jobs as $job) { ?>
                                                    <option value="<?php echo $job->job_id; ?>" <?php echo @$job_id==$job->job_id?'selected':''; ?>><?php echo $job->job_title.' ('.$job->code.')'; ?> - <?php echo $job->job_location; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="w-100 d-inline-block" style="text-align: right;">
                                                <?php if(is_role_access_employer('job','information','display')){ ?>
                                                    <a class="btn btn-primary mr-1 waves-effect waves-light w-10 font-medium-2" href="<?php echo site_url().'employer/job/information/'.$job_id; ?>">Job Details</a>
                                                <?php } ?>
                                                <?php if(is_role_access_employer('candidate','export_candidate','display')){  if(!empty($candidates)){?>
                                                    <a class="btn btn-primary mr-1 waves-effect waves-light w-10 font-medium-2" href="<?php echo site_url().'employer/candidate/export_candidate/'.$job_id; ?>">Export Candidates</a>
                                                <?php } } ?>
                                            </div>
                                        </div>
                                        <!---------------BELLOEW COLUMNS------------------------>
                                        <div class="col-md-12 mt-2">
                                            <div class="task-board">
                                                <!--1 REVIEW-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">
                                                            <span class="pl-0">To Review</span>
                                                                <span class="badge badge-pill badge-primary badge-mb" id="counter_1"><?php if(isset($candidates[1])){ $total=count(@$candidates[1]); if($total>0){ echo $total; } }?></span>
                                                                <a class="c-btn btn-primary waves-effect waves-light w-20 font-medium-1 d-inline" id='contact_btn' href="javascript:;">Contact</a>
                                                        </span>

                                                        <div class="custom-control custom-checkbox float-left">
                                                            <input type="checkbox" class="custom-control-input" id="select_all_contact">
                                                            <label class="custom-control-label" for="select_all_contact" style="padding-left: 0rem"></label>
                                                        </div>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable contact-ul" id="sort1" data-status-id="1">
                                                            <?php if( isset($candidates[1]) && @$total>0){ foreach ($candidates[1] as $review_candidate){?>
                                                                <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                        <div class="col-md-7 pl-0">
                                                                            <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                            <div class='rating-stars'>
                                                                                <ul class='stars'>
                                                                                    <?php
                                                                                    for($i=1;$i<=5;$i++) {
                                                                                        $selected = "";
                                                                                        if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                            <i class='fa fa-star fa-fw'></i>
                                                                                        </li>
                                                                                    <?php }  ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 pr-0 div-ck-box">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input contact" name="contact[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                                <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                            <h6><?php echo $review_candidate['candidate_location']; ?></h6>
                                                                            <div class="social-media">
                                                                                <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                                <?php }
                                                                                if($review_candidate['candidate_git']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_twitter']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_stack']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_google']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_xing']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                        <div class="col-md-4 pl-0">

                                                                            <?php if($review_candidate['candidate_email']!=''){?>
                                                                                <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;">Contact</a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php } }?>
                                                        </ul>
                                                    </div>

                                                </div>
                                                <!--2 CONTACTED-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">
                                                            <span>Contacted</span>
                                                            <span class="badge badge-pill badge-info badge-mb" id="counter_2"><?php if(isset($candidates[2])){ $total=count(@$candidates[2]); if($total>0){ echo $total; } }?></span>
                                                            <a class="c-btn btn-primary waves-effect waves-light w-15 font-medium-1 d-inline" id='followup_btn' href="javascript:;">Followup</a>
                                                        </span>
                                                        <div class="custom-control custom-checkbox float-left">
                                                            <input type="checkbox" class="custom-control-input" id="select_all_follow">
                                                            <label class="custom-control-label" for="select_all_follow" style="padding-left: 0rem"></label>
                                                        </div>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable follow-ul" id="sort2" data-status-id="2">
                                                        <?php if(isset($candidates[2]) &&  $total>0){ foreach ($candidates[2] as $review_candidate){?>
                                                            <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                    <div class="col-md-7 pl-0">
                                                                        <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                        <div class='rating-stars'>
                                                                            <ul class='stars'>
                                                                                <?php
                                                                                for($i=1;$i<=5;$i++) {
                                                                                    $selected = "";
                                                                                    if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                        <i class='fa fa-star fa-fw'></i>
                                                                                    </li>
                                                                                <?php }  ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 pr-0 div-ck-box">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input follow" name="follow[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                            <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                        <h6><?php echo $review_candidate['candidate_location']; ?></h6>
                                                                        <div class="social-media">
                                                                            <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                            <?php }
                                                                            if($review_candidate['candidate_git']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_twitter']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_stack']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_google']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_xing']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                    <div class="col-md-4 pl-0">
                                                                        <?php if($review_candidate['candidate_email']!=''){?>
                                                                            <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;">Follow-up</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php } }?>
                                                    </ul>
                                                    </div>
                                                </div>
                                                <!--3 REGECTED-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">Rejected
                                                                <span class="badge badge-pill badge-danger badge-mb" id="counter_3"><?php if(isset($candidates[3])){ $total=count(@$candidates[3]); if($total>0){ echo $total; } }?></span>

                                                        </span>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable" id="sort3" data-status-id="3">
                                                        <?php if(isset($candidates[3]) && $total>0){ foreach ($candidates[3] as $review_candidate){?>
                                                            <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                    <div class="col-md-7 pl-0">
                                                                        <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                        <div class='rating-stars'>
                                                                            <ul class='stars'>
                                                                                <?php
                                                                                for($i=1;$i<=5;$i++) {
                                                                                    $selected = "";
                                                                                    if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                        <i class='fa fa-star fa-fw'></i>
                                                                                    </li>
                                                                                <?php }  ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 pr-0 div-ck-box">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="other[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                            <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                        <h6><?php echo $review_candidate['candidate_location']; ?></h6>
                                                                        <div class="social-media">
                                                                            <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                            <?php }
                                                                            if($review_candidate['candidate_git']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_twitter']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_stack']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_google']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_xing']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                    <div class="col-md-4 pl-0">
                                                                        <?php if($review_candidate['candidate_email']!=''){?>
                                                                            <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;" style="display: none;"></a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php } }?>
                                                    </ul>
                                                    </div>
                                                </div>
                                                <!--4 Intrested-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">Interested
                                                                <span class="badge badge-pill badge-interested badge-mb" id="counter_4"><?php if(isset($candidates[4])){ $total=count(@$candidates[4]); if($total>0){ echo $total; } }?></span>

                                                        </span>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable" id="sort4" data-status-id="4">
                                                            <?php if(isset($candidates[4]) && $total>0){ foreach (@$candidates[4] as $review_candidate){?>
                                                                <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                        <div class="col-md-7 pl-0">
                                                                            <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                            <div class='rating-stars'>
                                                                                <ul class='stars'>
                                                                                    <?php
                                                                                    for($i=1;$i<=5;$i++) {
                                                                                        $selected = "";
                                                                                        if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                            <i class='fa fa-star fa-fw'></i>
                                                                                        </li>
                                                                                    <?php }  ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 pr-0 div-ck-box">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" name="other[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                                <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                            <h6><?php echo $review_candidate['candidate_location']; ?></h6>


                                                                            <div class="social-media">
                                                                                <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                                <?php }
                                                                                if($review_candidate['candidate_git']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_twitter']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_stack']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_google']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_xing']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                        <div class="col-md-4 pl-0">
                                                                            <?php if($review_candidate['candidate_email']!=''){?>
                                                                                <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;">Contact</a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php } }?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--5 NOT INTRESTED-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">Not Interested
                                                                <span class="badge badge-pill badge-notinterested badge-mb" id="counter_5"><?php if(isset($candidates[5])){ $total=count(@$candidates[5]); if($total>0){ echo $total; } }?></span>

                                                        </span>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable" id="sort5" data-status-id="5">
                                                            <?php if(isset($candidates[5]) && $total>0){ foreach (@$candidates[5] as $review_candidate){?>
                                                                <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                    <div class="row">
                                                                        <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                        <div class="col-md-7 pl-0">
                                                                            <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                            <div class='rating-stars'>
                                                                                <ul class='stars'>
                                                                                    <?php
                                                                                    for($i=1;$i<=5;$i++) {
                                                                                        $selected = "";
                                                                                        if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                            <i class='fa fa-star fa-fw'></i>
                                                                                        </li>
                                                                                    <?php }  ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 pr-0 div-ck-box">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input" name="other[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                                <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                            <h6><?php echo $review_candidate['candidate_location']; ?></h6>


                                                                            <div class="social-media">
                                                                                <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                                <?php }
                                                                                if($review_candidate['candidate_git']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_twitter']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_stack']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_google']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                                <?php } if($review_candidate['candidate_xing']){?>
                                                                                    <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                        <div class="col-md-4 pl-0">
                                                                            <?php if($review_candidate['candidate_email']!=''){?>
                                                                                <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;" style="display: none;"></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php } }?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--6 INTERVIEW-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">Interview
                                                                <span class="badge badge-pill badge-warning badge-mb" id="counter_6"><?php if(isset($candidates[6])){ $total=count(@$candidates[6]); if($total>0){ echo $total; } }?></span>

                                                        </span>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable" id="sort6" data-status-id="6">
                                                        <?php if(isset($candidates[6]) && $total>0){ foreach (@$candidates[6] as $review_candidate){?>
                                                            <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                    <div class="col-md-7 pl-0">
                                                                        <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                        <div class='rating-stars'>
                                                                            <ul class='stars'>
                                                                                <?php
                                                                                for($i=1;$i<=5;$i++) {
                                                                                    $selected = "";
                                                                                    if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                        <i class='fa fa-star fa-fw'></i>
                                                                                    </li>
                                                                                <?php }  ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 pr-0 div-ck-box">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="other[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                            <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                        <h6><?php echo $review_candidate['candidate_location']; ?></h6>


                                                                        <div class="social-media">
                                                                            <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                            <?php }
                                                                            if($review_candidate['candidate_git']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_twitter']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_stack']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_google']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_xing']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                    <div class="col-md-4 pl-0">
                                                                        <?php if($review_candidate['candidate_email']!=''){?>
                                                                            <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;">Invite</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php } }?>
                                                    </ul>
                                                    </div>
                                                </div>
                                                <!--7 Hired-->
                                                <div class="status-card">
                                                    <div class="card-header">
                                                        <span class="card-header-text font-medium-3 pb-1">Hired
                                                            <span class="badge badge-pill badge-success badge-mb" id="counter_7"><?php if(isset($candidates[7])){ $total=count(@$candidates[7]); if($total>0){ echo $total; } }?></span>
                                                        </span>
                                                    </div>
                                                    <div class="card-data">
                                                        <ul class="sortable ui-sortable" id="sort7" data-status-id="7">
                                                        <?php if(isset($candidates[7]) && $total>0){ foreach (@$candidates[7] as $review_candidate){?>
                                                            <li class="text-row ui-sortable-handle pb-0" data-candidate-id="<?php echo $review_candidate['candidate_id']; ?>" id="candidate_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <input type="hidden" name="current_dtatus" class="current_status" value="<?php echo $review_candidate['candidate_status']; ?>" id="c_status_<?php echo $review_candidate['candidate_id']; ?>">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-1"><img class="round" src="<?php echo check_image($review_candidate['candidate_photo'],'uploads/candidate','thumb'); ?>" alt="avatar" height="40" width="40"></div>
                                                                    <div class="col-md-7 pl-0">
                                                                        <h6 class="font-weight-bold "><a class="candidate-info" href="javascript:;"><?php echo $review_candidate['candidate_name']; ?></a></h6>
                                                                        <div class='rating-stars'>
                                                                            <ul class='stars'>
                                                                                <?php
                                                                                for($i=1;$i<=5;$i++) {
                                                                                    $selected = "";
                                                                                    if(!empty($review_candidate["rating"]) && $i<=$review_candidate["rating"]) {
                                                                                        $selected = "selected";
                                                                                    }
                                                                                    ?>
                                                                                    <li class='star <?php echo $selected; ?>' title='<?php echo $i.' Star'; ?>' data-value='<?php echo $i; ?>'>
                                                                                        <i class='fa fa-star fa-fw'></i>
                                                                                    </li>
                                                                                <?php }  ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 pr-0 div-ck-box">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="other[]" id="<?php echo $review_candidate['candidate_id']; ?>">
                                                                            <label class="custom-control-label" for="<?php echo $review_candidate['candidate_id']; ?>"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <h6><?php echo $review_candidate['current_job_title']; ?></h6>
                                                                        <h6><?php echo $review_candidate['candidate_location']; ?></h6>
                                                                        <div class="social-media">
                                                                            <?php if($review_candidate['candidate_linkedin']!=''){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_linkedin']); ?>" target="_blank"><i class="fa fa-linkedin-square font-medium-4"></i></a>
                                                                            <?php }
                                                                            if($review_candidate['candidate_git']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_git']); ?>" target="_blank"><i class="fa fa-github font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_fb']){ ?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_fb']); ?>" target="_blank"><i class="fa
fa-facebook font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_twitter']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_twitter']); ?>" target="_blank"><i class="fa fa-twitter font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_stack']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_stack']); ?>" target="_blank"><i class="fa fa-stack-overflow font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_google']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_google']); ?>" target="_blank"><i class="fa fa-google-plus-official font-medium-4"></i></a>
                                                                            <?php } if($review_candidate['candidate_xing']){?>
                                                                                <a href="<?php echo $this->common_model->addHttp($review_candidate['candidate_xing']); ?>" target="_blank"><i class="fa fa-xing-square font-medium-4"></i></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8 pr-0 pb-0"><h6><?php echo date('d M, Y',strtotime($review_candidate['created_at'])); ?></h6></div>
                                                                    <div class="col-md-4 pl-0">
                                                                        <?php if($review_candidate['candidate_email']!=''){?>
                                                                            <a class="c-btn btn-primary waves-effect waves-light w-15 font-small-1 pull-right single-contact" href="javascript:;">Contact</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php } }?>
                                                    </ul>
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
            </section>
            <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>
<script>

    //CONTACT
    /*radio all-check /uncheck*/
    $("#select_all_contact").change(function(){  //"select all" change
        var status = this.checked; // "select all" checked status
        $('.custom-control-input.contact').each(function(){ //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
        });
        show_contact_btn();
    });

    $('.custom-control-input.contact').change(function(){ //".checkbox" change
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(this.checked == false){ //if this item is unchecked
            $("#select_all_contact")[0].checked = false; //change "select all" checked status to fals
        }

        //check "select all" if all checkbox items are checked
        if ($('.custom-control-input.contact:checked').length == $('.custom-control-input.contact').length ){
            $("#select_all_contact")[0].checked = true; //change "select all" checked status to true
        }
        show_contact_btn();
    });

    //REVIEW
    /*radio all-check /uncheck*/
    $("#select_all_follow").change(function(){  //"select all" change
        var status = this.checked; // "select all" checked status

        $('.custom-control-input.follow').each(function(){ //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
        });
        show_follow_btn();
    });

    $('.custom-control-input.follow').change(function(){ //".checkbox" change
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(this.checked == false){ //if this item is unchecked
            $("#select_all_follow")[0].checked = false; //change "select all" checked status to false
        }

        //check "select all" if all checkbox items are checked
        if ($('.custom-control-input.follow:checked').length == $('.custom-control-input.follow').length ){
            $("#select_all_follow")[0].checked = true; //change "select all" checked status to true
        }

        show_follow_btn();
    });

    //contact/follow button hide
    $(document).find('#contact_btn').attr("style", "display: none !important");
    $(document).find('#followup_btn').attr("style", "display: none !important");

    function show_contact_btn() {
        var sList = 0;
        $('.custom-control-input.contact').each(function () {
            var sThisVal = parseInt(this.checked ? 1 : 0);
            sList = sList + sThisVal;
        });
        if(sList>0){
            $(document).find('#contact_btn').attr("style", "display: inline !important");
        }else{
            $(document).find('#contact_btn').attr("style", "display: none !important");
        }

    }

    function show_follow_btn() {
        var tList = 0;
        $('.custom-control-input.follow').each(function () {
            var tThisVal = parseInt(this.checked ? 1 : 0);
            tList = tList + tThisVal;
        });
        if(tList>0){
            $(document).find('#followup_btn').attr("style", "display: inline !important");
        }else{
            $(document).find('#followup_btn').attr("style", "display: none !important");
        }
    }

    //SORTABLE
    $('ul[id^="sort"]').sortable({
        connectWith: ".sortable",
        receive: function (e, ui) {
            var status_id = $(ui.item).parent(".sortable").data("status-id");
            var candidate_id = $(ui.item).data("candidate-id");
            var candidate_old_status = $('#c_status_'+candidate_id).val();
            //ERROR IF CANDIDATE NOT IN GIVEN STAGE
            if(candidate_old_status==3 && status_id!=1){
                toastr.error("You can not move profile of this candidate because he/she has been <b>Rejected</b> Stage!", 'Error!');
                $('ul[id^="sort"]').sortable('cancel');
                return false;
            }
            if(status_id==3 && candidate_old_status!=1){
                toastr.error("You can not move profile of this candidate because he/she not in <b>To Review</b> Stage!", 'Error!');
                $('ul[id^="sort"]').sortable('cancel');
                return false;
            }
            //REJECTED POPUP
            if(status_id==3){
                //reject note
                $('#pop_status_id').val(status_id);
                $('#pop_candidate_id').val(candidate_id);
                $('#selsect_reason_id').change(function () {
                    var reason = $(this).val();
                    if(reason ==1){
                        $('#other_reason').css('display','block');
                    }else{
                        $('#other_reason').css('display','none');
                    }
                });
                $('#rejectReasonModal').modal('show');
            //SCHEDULE INTERVIEW
            }else if(status_id==6){
                //interview
                $('#pop_status_id').val(status_id);
                $('#pop_candidate_id').val(candidate_id);
                $('#setinterviewModal').modal('show');
            //NORMAL CASE AJAX CALL
            }else{
                form_data = new FormData();
                form_data.append('status_id', status_id);
                form_data.append('candidate_id', candidate_id);
                form_data.append('job_id', $('#selsect_job_id').val());
                form_data.append('csrf_token', $('#csrf_token').val());
                form_data.append('csrf_name', $('#csrf_name').val());
                $.ajax({
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    url: siteUrl + 'ajax_candidate_status',
                    success: function (obj) {
                        if (obj.code == 1) {
                            $('#c_status_'+obj.candidate_id).val(obj.new_status_id);
                            $('#counter_' + obj.new_status_id).text(obj.new_count);
                            if (obj.old_count == 0) {
                                $('#counter_' + obj.old_status_id).text('');
                            } else {
                                $('#counter_' + obj.old_status_id).text(obj.old_count);
                            }
                            if(obj.new_status_id==1){
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input contact');
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','contact[]');
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                                show_contact_btn();
                                show_follow_btn();
                            }else if(obj.new_status_id==2){
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input follow');
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','follow[]');
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                                show_contact_btn();
                                show_follow_btn();

                            }else if(obj.new_status_id==3){
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                                show_contact_btn();
                                show_follow_btn();
                            }
                            else{
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                                $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                                $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                                show_contact_btn();
                                show_follow_btn();
                            }

                            /*Rebind the check all and check code*/
                            $("#select_all_contact").change(function(){  //"select all" change
                                var status = this.checked; // "select all" checked status
                                $('.custom-control-input.contact').each(function(){ //iterate all listed checkbox items
                                    this.checked = status; //change ".checkbox" checked status
                                });
                                show_contact_btn();
                            });

                            $('.custom-control-input.contact').change(function(){ //".checkbox" change
                                //uncheck "select all", if one of the listed checkbox item is unchecked
                                if(this.checked == false){ //if this item is unchecked
                                    $("#select_all_contact")[0].checked = false; //change "select all" checked status to fals
                                }

                                //check "select all" if all checkbox items are checked
                                if ($('.custom-control-input.contact:checked').length == $('.custom-control-input.contact').length ){
                                    $("#select_all_contact")[0].checked = true; //change "select all" checked status to true
                                }

                                show_contact_btn();
                            });

                            //REVIEW
                            /*radio all-check /uncheck*/
                            $("#select_all_follow").change(function(){  //"select all" change
                                var status = this.checked; // "select all" checked status

                                $('.custom-control-input.follow').each(function(){ //iterate all listed checkbox items
                                    this.checked = status; //change ".checkbox" checked status
                                });
                                show_follow_btn();
                            });

                            $('.custom-control-input.follow').change(function(){ //".checkbox" change
                                //uncheck "select all", if one of the listed checkbox item is unchecked
                                if(this.checked == false){ //if this item is unchecked
                                    $("#select_all_follow")[0].checked = false; //change "select all" checked status to false
                                }

                                //check "select all" if all checkbox items are checked
                                if ($('.custom-control-input.follow:checked').length == $('.custom-control-input.follow').length ){
                                    $("#select_all_follow")[0].checked = true; //change "select all" checked status to true
                                }

                                show_follow_btn();
                            });
                            /*END OF REBIND THE CONTROL*/
                            if(obj.is_dispaly=='block'){
                                $('#candidate_'+candidate_id+' .single-contact').show();
                                $('#candidate_'+candidate_id+' .single-contact').text(obj.contact_btn);
                            }else{
                                $('#candidate_'+candidate_id+' .single-contact').hide();
                                $('#candidate_'+candidate_id+' .single-contact').text('');
                            }
                            if(obj.new_status_id==1){
                                toastr.success("Candidate has been moved to contacted!", 'Super!');
                            }
                            if(obj.new_status_id==2){
                                toastr.success("Candidate has been moved successfully.", 'Success!');
                            }
                            if(obj.new_status_id==4){
                                toastr.success("You've got the interested candidate!", 'HOORAY!');
                            }
                            if(obj.new_status_id==5){
                                toastr.success("Let’s keep connecting with sourced candidates!.", 'No worries!');
                            }
                            if(obj.new_status_id==7){
                                toastr.success("Great News.. Congratulations on your hire!", 'Congrats!');
                            }

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
            }
        }

    }).disableSelection();

    /*****************************If Status 3 Reject**********************************/
    $(document).on('click','#reson_submit',function () {
        var status_id = $('#pop_status_id').val();
        var candidate_id = $('#pop_candidate_id').val();
        var reason = $('#reject_reson').val();
        var reason_id = $('#selsect_reason_id option:selected').val();

        form_data = new FormData();
        form_data.append('status_id', status_id);
        form_data.append('candidate_id', candidate_id);
        form_data.append('reason_id', reason_id);
        form_data.append('reason', reason);
        form_data.append('job_id', $('#selsect_job_id').val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_candidate_status',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#c_status_'+obj.candidate_id).val(obj.new_status_id);
                    $('#counter_' + obj.new_status_id).text(obj.new_count);
                    if (obj.old_count == 0) {
                        $('#counter_' + obj.old_status_id).text('');
                    } else {
                        $('#counter_' + obj.old_status_id).text(obj.old_count);
                    }
                    if(obj.new_status_id==1){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input contact');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','contact[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }else if(obj.new_status_id==2){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input follow');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','follow[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();

                    }else if(obj.new_status_id==3){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }
                    else{
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }

                    /*Rebind the check all and check code*/
                    $("#select_all_contact").change(function(){  //"select all" change
                        var status = this.checked; // "select all" checked status
                        $('.custom-control-input.contact').each(function(){ //iterate all listed checkbox items
                            this.checked = status; //change ".checkbox" checked status
                        });
                        show_contact_btn();
                    });

                    $('.custom-control-input.contact').change(function(){ //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){ //if this item is unchecked
                            $("#select_all_contact")[0].checked = false; //change "select all" checked status to fals
                        }

                        //check "select all" if all checkbox items are checked
                        if ($('.custom-control-input.contact:checked').length == $('.custom-control-input.contact').length ){
                            $("#select_all_contact")[0].checked = true; //change "select all" checked status to true
                        }

                        show_contact_btn();
                    });

                    //REVIEW
                    /*radio all-check /uncheck*/
                    $("#select_all_follow").change(function(){  //"select all" change
                        var status = this.checked; // "select all" checked status

                        $('.custom-control-input.follow').each(function(){ //iterate all listed checkbox items
                            this.checked = status; //change ".checkbox" checked status
                        });
                        show_follow_btn();
                    });

                    $('.custom-control-input.follow').change(function(){ //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){ //if this item is unchecked
                            $("#select_all_follow")[0].checked = false; //change "select all" checked status to false
                        }

                        //check "select all" if all checkbox items are checked
                        if ($('.custom-control-input.follow:checked').length == $('.custom-control-input.follow').length ){
                            $("#select_all_follow")[0].checked = true; //change "select all" checked status to true
                        }

                        show_follow_btn();
                    });
                    /*END OF REBIND THE CONTROL*/
                    if(obj.is_dispaly=='block'){
                        $('#candidate_'+candidate_id+' .single-contact').show();
                        $('#candidate_'+candidate_id+' .single-contact').text(obj.contact_btn);
                    }else{
                        $('#candidate_'+candidate_id+' .single-contact').hide();
                        $('#candidate_'+candidate_id+' .single-contact').text('');
                    }
                    toastr.success("Candidate has been rejected", 'Disqualified!');
                    $('#reject_reson').text('');
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
    /*****************************If Status 3 Reject END**********************************/

    /*****************************If Status 6 Interview**********************************/
    $(document).on('click','#interview_set_submit',function () {
        var status_id = $('#pop_status_id').val();
        var candidate_id = $('#pop_candidate_id').val();
        var idate = $('#date_picker').val();
        var itime = $('#time_picker').val();
        var inote = $('#interview_note').val();
        form_data = new FormData();
        form_data.append('status_id', status_id);
        form_data.append('candidate_id', candidate_id);
        form_data.append('idate', idate);
        form_data.append('itime', itime);
        form_data.append('inote', inote);
        form_data.append('job_id', $('#selsect_job_id').val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_candidate_status',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#c_status_'+obj.candidate_id).val(obj.new_status_id);
                    $('#counter_' + obj.new_status_id).text(obj.new_count);
                    if (obj.old_count == 0) {
                        $('#counter_' + obj.old_status_id).text('');
                    } else {
                        $('#counter_' + obj.old_status_id).text(obj.old_count);
                    }
                    if(obj.new_status_id==1){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input contact');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','contact[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }else if(obj.new_status_id==2){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input follow');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','follow[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();

                    }else if(obj.new_status_id==3){
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }
                    else{
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').removeClass().addClass('custom-control-input ');
                        $(document).find('#candidate_'+candidate_id+' .custom-control.custom-checkbox input:checkbox').prop('name','other[]');
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", true);
                        $('#page_employer_candidate').find('input[type="checkbox"]').prop("checked", false);
                        show_contact_btn();
                        show_follow_btn();
                    }

                    /*Rebind the check all and check code*/
                    $("#select_all_contact").change(function(){  //"select all" change
                        var status = this.checked; // "select all" checked status
                        $('.custom-control-input.contact').each(function(){ //iterate all listed checkbox items
                            this.checked = status; //change ".checkbox" checked status
                        });
                        show_contact_btn();
                    });

                    $('.custom-control-input.contact').change(function(){ //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){ //if this item is unchecked
                            $("#select_all_contact")[0].checked = false; //change "select all" checked status to fals
                        }

                        //check "select all" if all checkbox items are checked
                        if ($('.custom-control-input.contact:checked').length == $('.custom-control-input.contact').length ){
                            $("#select_all_contact")[0].checked = true; //change "select all" checked status to true
                        }

                        show_contact_btn();
                    });

                    //REVIEW
                    /*radio all-check /uncheck*/
                    $("#select_all_follow").change(function(){  //"select all" change
                        var status = this.checked; // "select all" checked status

                        $('.custom-control-input.follow').each(function(){ //iterate all listed checkbox items
                            this.checked = status; //change ".checkbox" checked status
                        });
                        show_follow_btn();
                    });

                    $('.custom-control-input.follow').change(function(){ //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){ //if this item is unchecked
                            $("#select_all_follow")[0].checked = false; //change "select all" checked status to false
                        }

                        //check "select all" if all checkbox items are checked
                        if ($('.custom-control-input.follow:checked').length == $('.custom-control-input.follow').length ){
                            $("#select_all_follow")[0].checked = true; //change "select all" checked status to true
                        }

                        show_follow_btn();
                    });
                    /*END OF REBIND THE CONTROL*/
                    if(obj.is_dispaly=='block'){
                        $('#candidate_'+candidate_id+' .single-contact').show();
                        $('#candidate_'+candidate_id+' .single-contact').text(obj.contact_btn);
                    }else{
                        $('#candidate_'+candidate_id+' .single-contact').hide();
                        $('#candidate_'+candidate_id+' .single-contact').text('');
                    }
                    toastr.success("Positive Vibes.. Offer is on the way!", 'Woohoo!');
                    $('#interview_schedule')[0].reset();
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
    /*****************************If Status 6 Interview END**********************************/

    /*contact button*/
    $(document).on('click','#contact_btn',function () {
        var arr = [];
        $('.contact-ul').children('li').each(function () {
            if($(this).find('input[type=checkbox]', this).is(':checked')){
                arr.push($(this).attr('id').replace('candidate_',''));
            }
        });
        compose_candidate_mail(arr,'contact');
    });
    /*followup button*/
    $(document).on('click','#followup_btn',function () {
        var arr = [];
        $('.follow-ul').children('li').each(function () {
            if($(this).find('input[type=checkbox]', this).is(':checked')){
                arr.push($(this).attr('id').replace('candidate_',''));
            }
        });
        compose_candidate_mail(arr,'followup');
    });

    //single contact from employer single card
    $(document).on('click','.single-contact',function () {
        var arr=[];
        arr.push($(this).closest('li').attr('id').replace('candidate_',''));
        var title = $(this).text();
        var click_type = '';
        if(title == 'Contact'){
            click_type='contact';
        }else if(title == 'Follow-up'){
            click_type = 'followup';
        }else if(title=='Invite'){
            click_type = 'invite';
        }
        compose_candidate_mail(arr,click_type);
    });
    //candidate detail popup contact button
    $(document).on('click','.pop-single-contact',function () {
        var arr=[];
        arr.push($(this).attr('id').replace('pop_contact_',''));
        compose_candidate_mail(arr,'contact');
    });

    //candidate detail popup rejct button
    $(document).on('click','.pop-single-reject',function () {
        var can_id = $(this).attr('id').replace('pop_reject_','');
        $('#pop_status_id').val(3);
        $('#pop_candidate_id').val(can_id);
        $('#selsect_reason_id').change(function () {
            var reason = $(this).val();
            if(reason ==1){
                $('#other_reason').css('display','block');
            }else{
                $('#other_reason').css('display','none');
            }
        });
        $('#rejectReasonModal').modal('show');
    });

    function compose_candidate_mail(ids,type){

        form_data = new FormData();
        form_data.append('ids', ids);
        form_data.append('type', type);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_get_candidate_emails',
            success: function (obj) {
                if (obj.code == 1) {
                    if(obj.is_email_found==1){
                        $('#emailTo').val(obj.emails);
                        $('#type').val(obj.type);
                        $('#composeForm').modal('show');
                    }else{
                        toastr.error('Selected Candidate has no any email address for contact/followup.', 'Error!');
                    }
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
    }
    //mail sending ajax call
    $(document).on('click','#candidate_mail_send',function () {
        $(this).val('Sending...');
        var myEditor = document.querySelector('.editor')
        var html = $('#summernote').val();
        var form = $('#mail_send_form')[0];
        // Create an FormData object
        form_data = new FormData(form);
        form_data.append('html', html);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_send_candidate_emails',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#candidate_mail_send').val('Send');
                    toastr.success('Email has been sent successfully', 'Success!');
                    $('#composeForm').modal('hide');
                    $('#mail_send_form').get(0).reset();

                    if(obj.isreaload == 1){
                        location.reload();
                    }
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
        /*setTimeout(
            function()
            {
                $('#composeForm').modal('hide');
            }, 5000);*/
    });

    /*candidate detail ajax call */
    $(document).on('click','.candidate-info',function () {
        var candidate_id = $(this).closest('li').attr('id').replace('candidate_','');
        form_data = new FormData();
        form_data.append('candidate_id', candidate_id);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_get_candidate_details',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#c_name').text(obj.candidate.candidate_name);
                    $('#pop_candidaite_id').val(obj.candidate.candidate_id);
                    $('#c_job').text(obj.candidate.current_job_title);
                    $('#c_posted_on').text(obj.posted_on);
                    $('#c_location').text(obj.candidate.candidate_location);
                    $('#c_profile').attr('src',obj.candidate_pic);
                    $('#c_resume').attr('href',obj.candidate_resume);
                    $('#c_ratting').html(obj.ratting_html);
                    $('#c_email').html(obj.emails);
                    $('#c_contact').html(obj.contact);
                    $('#c_social').html(obj.social);
                    $('#c_status').html(obj.status);
                    $('#note').html(obj.note_html);
                    $('#activity').html(obj.activity_html);
                    $('#pdf-view').html(obj.pdf_html);
                    $('#contact_reject_btn').html(obj.contact_reject_btn);
                    $('#candidateDetailModal').modal('show');
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

    /*add note button click*/
    $(document).on('click','#add_note',function () {
        $('#add_note_popup').modal('show');
    });

    /*candidate detail ajax call */
    $(document).on('click','#add_note_submit_btn',function () {
        form_data = new FormData();
        form_data.append('candidate_id', $('#pop_candidaite_id').val());
        form_data.append('note', $('#note_input').val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_add_note',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success('Note has been successfully added!', 'Success!');
                    $('#add_note_form').get(0).reset();
                    $('#note').html(obj.note_html);
                    $('#add_note_popup').modal('hide');
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

    //ajax call for mail template
    $(document).on('change','#mail_template',function () {
        form_data = new FormData();
        form_data.append('mail_id', $(this).val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_get_mail',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#emailSubject').val(obj.mail_temp.mail_subject);
                    $('#summernote').val(obj.mail_temp.mail_content);
                    $('#summernote').summernote("code", obj.mail_temp.mail_content);
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

    // modal close event
    $(document).on('hide.bs.modal',"#rejectReasonModal", function(){
        $('ul[id^="sort"]').sortable('cancel');
    });
</script>
<!--Reject candidate reason model-->
<div class="modal fade text-left" id="rejectReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectReasonModal" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Reason For The Reject!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control" name="selsect_reason_id" id="selsect_reason_id">
                                <option value="">Select Reason </option>
                                <?php foreach ($reasons as $reason) { ?>
                                    <option value="<?php echo $reason->reject_reason_id; ?>" <?php echo @$reason_id==$reason->reject_reason_id?'selected':''; ?>><?php echo $reason->reason; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="other_reason" style="display: none;">
                        <label>Reason: </label>
                        <div class="form-group" >
                            <textarea rows="5" placeholder="Reason for the rejection this candidate" class="form-control" name="reject_reson" id="reject_reson"></textarea>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal" id="reson_submit">submit</button>
                    </div>
                    <input type="hidden" name="pop_status_id" id="pop_status_id">
                    <input type="hidden" name="pop_candidate_id" id="pop_candidate_id">
                </form>
            </div>
        </div>
    </div>
</div>

<!--Interview set model-->
<div class="modal fade text-left" id="setinterviewModal" tabindex="-1" role="dialog" aria-labelledby="setinterviewModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="">Interview Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="interview_schedule">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Date: </label>
                                <div class="form-group" >
                                    <input type='text' class="form-control" id="date_picker"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Time: </label>
                                <div class="form-group" >
                                    <input type='text' class="form-control" id="time_picker"/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Note: </label>
                                <div class="form-group mb-5" >
                                    <textarea rows="7" placeholder="Note" class="form-control" name="interview_note" id="interview_note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal" id="interview_set_submit">Set</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mail Modal -->
<div class="modal fade text-left" id="composeForm" tabindex="-1" role="dialog" aria-labelledby="emailCompose" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-text-bold-600" id="emailCompose">New Email</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" name="mail_send_form" id="mail_send_form">
            <div class="modal-body pt-1">
                <div class="form-label-group mt-1">
                    <input type="text" id="emailTo" class="form-control" placeholder="To" name="emailto" readonly>
                    <label for="emailTo">To</label>
                </div>
                <div class="form-label-group mt-1">
                    Email Template
                    <select name="mail_template" class="form-control" id="mail_template">
                        <option value="">Pick Template</option>
                        <?php foreach ($mail_temps as $temp){?>
                            <option value="<?php echo $temp->mail_template_id; ?>"><?php echo $temp->mail_title;?></option>
                        <?php } ?>
                    </select>
                    <label for="mail_template">Email Template</label>
                </div>
                <div class="form-label-group">
                    <input type="text" id="emailSubject" class="form-control" placeholder="Subject Line" name="subject">
                    <label for="emailSubject">Subject Line</label>
                </div>
                <div class="form-group">
                    <textarea id="summernote" name="html"></textarea>
                </div>
                <div class="form-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="emailAttach" name="attachment">
                        <label class="custom-file-label" for="emailAttach">Attach file</label>
                    </div>
                </div>
            </div>
                <input type="hidden" id="type" name="type" value="">
            </form>
            <div class="modal-footer">
                <input type="button" value="Send" class="btn btn-primary" id="candidate_mail_send">
                <input type="Reset" value="Cancel" class="btn btn-white" data-dismiss="modal">
            </div>
        </div>
    </div>
</div>


<!--Candidate Detail model-->
<div class="modal fade" id="candidateDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <!--<div class="modal-header bg-primary white pt-1">
                <h5 class="modal-title text-text-bold-600" style="padding-top: 0.5rem;font-size: 1.5rem;">Candidate Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2 pr-0">
                                <img height="90" width="90" src="" alt="avatar" id="c_profile">
                            </div>
                            <div class="col-md-8 pl-0">
                                <h3 class="mb-1" id="c_name"></h3>
                                <span>
                                    <span class="mr-1"><i class="feather icon-briefcase" style="margin-right: 0.5rem;color: #000;"></i><span id="c_job"> </span></span>
                                    <span class="mr-1"><i class="feather icon-calendar" style="margin-right: 0.5rem;color: #000;"></i><span id="c_posted_on"> </span></span>
                                    <span class="mr-1"><i class="feather icon-map-pin" style="margin-right: 0.5rem;color: #000;"></i><span id="c_location"> </span></span>
                                </span>
                                <div class='rating-stars mt-1' id="c_ratting">

                                </div>
                            </div>
                            <div class="col-md-2" id="contact_reject_btn">

                            </div>

                            <div class="col-md-4 mt-2">
                                <div class="d-inline-block w-100 mt-1 ml-1">
                                    <span class="mr-1"><div class="d-inline-block" style="vertical-align: top"><i class="feather icon-mail" style="margin-right: 0.75rem; color: #000;"></i></div><div id="c_email" class="d-inline-block"></div></span>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="d-inline-block w-100 mt-1 ml-1">
                                    <span class="mr-1">Resume : <a href="" target="_blank" id="c_resume"><i class="feather icon-file-text font-medium-4" style="margin-right: 0.75rem;"></i></a></span>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="d-inline-block w-100 mt-1 ml-1">
                                    <span class="mr-1">Status : <span id="c_status"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-inline-block w-100 mt-1 ml-1">
                                    <span class="mr-1"><i class="feather icon-phone" style="margin-right: 0.75rem; color: #000;"></i><span id="c_contact"></span></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-inline-block w-100 mt-1 ml-1">
                                    <span class="mr-1">Connect On : <span id="c_social"></span>
                                    </span>
                                </div>
                            </div>
                            <!--TABS -->
                            <div class="col-12 mt-2">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="note-tab" data-toggle="tab" href="#pdf-view" aria-controls="note" role="tab" aria-selected="true">Resume</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="note-tab" data-toggle="tab" href="#note" aria-controls="note" role="tab" aria-selected="true">Notes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" aria-controls="activity" role="tab" aria-selected="false">Activities</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="pdf-view" aria-labelledby="pdf-view" role="tabpanel">
                                        <!--pdf html-->
                                    </div>
                                    <div class="tab-pane" id="note" aria-labelledby="note-tab" role="tabpanel">
                                        <!--note html-->
                                    </div>
                                    <div class="tab-pane p-1" id="activity" aria-labelledby="activity-tab" role="tabpanel">
                                        <!--activity html-->
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pop_candidaite_id" id="pop_candidaite_id" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--note add model-->
<div class="modal fade text-left" id="add_note_popup" tabindex="-1" role="dialog" aria-labelledby="emailCompose" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-text-bold-600" >Add New Note</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" name="not_add_form" id="add_note_form">
                <div class="modal-body pt-1">
                    <div class="form-label-group mt-1">
                        <textarea id="note_input" class="form-control" placeholder="Note" name="note" rows="3"></textarea>
                        <label for="note_input">Note</label>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <input type="button" value="Add" class="btn btn-primary" id="add_note_submit_btn">
                <input type="Reset" value="Cancel" class="btn btn-white" data-dismiss="modal">
            </div>
        </div>
    </div>
</div>

<!--delete confirm note -->
<div class="modal fade text-left" id="deleteNoteModal" tabindex="-1" role="dialog" aria-labelledby="deleteNoteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Confirmation Alert</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want to delete this note? You cannot recover it back.</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-note-confirm-btn">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>