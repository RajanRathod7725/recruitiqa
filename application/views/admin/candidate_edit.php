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
                                            <form class="form-horizontal" id="candidate_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4>Candidate Detail</h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_name">Name</label>
                                                                <input type="text" class="form-control" id="candidate_name" name="candidate_name" required="" placeholder="Name" data-validation-required-message="This Name field is required" value="<?php echo set_value('candidate_name',@$this->form_data->candidate_name);?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--candidate multiple email-->
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_email">Email</label>
                                                                <div class="row field_wrapper">
                                                                    <div class="col-md-12">
                                                                        <?php if($this->form_data->candidate_email!=''){
                                                                            $emails=explode(',',$this->form_data->candidate_email);
                                                                            $i=1;
                                                                            foreach ($emails as $email){ ?>
                                                                                <input type="email" name="email[]" id="candidate_email_<?php echo $i; ?>" class="email-inputs form-control mb-1" placeholder="Email <?php echo $i; ?>" data-validation-required-message="The email field is required" value="<?php echo $email; ?>">

                                                                        <?php $i++; } }else{ ?>
                                                                            <input type="email" name="email[]" id="candidate_email_1" class="email-inputs form-control" placeholder="Email 1" data-validation-required-message="The email field is required">
                                                                        <?php } ?>
                                                                    </div>
                                                                    <!--<div class="col-md-1">
                                                                        <a href="javascript:void(0);" class="add_button" title="Add Email"><i class="feather icon-plus-square font-large-2 float-right"></i></a>
                                                                    </div>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_phone">Contact Number</label>
                                                                <input type="number" name="candidate_phone" id="candidate_phone" class="form-control" placeholder="Contact Number"  data-validation-required-message="The Contact Number field is required" value="<?php echo set_value('candidate_phone',@$this->form_data->candidate_phone);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_location">Candidate Location</label>
                                                                <input type="text" name="candidate_location" id="candidate_location" class="form-control" placeholder="Candidate Location" required="" data-validation-required-message="The Candidate Location field is required" value="<?php echo set_value('candidate_location',@$this->form_data->candidate_location);?>">
                                                                <p>Selected: <strong id="address-value">none</strong></p>
                                                                <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>
                                                                <script>
                                                                    (function() {
                                                                        var placesAutocomplete = places({
                                                                            appId: 'plJES61WLXRM',
                                                                            apiKey: 'a17570e937aa49c6723f30c9c28645c0',
                                                                            container: document.querySelector('#candidate_location')
                                                                        });

                                                                        var $address = document.querySelector('#address-value')
                                                                        placesAutocomplete.on('change', function(e) {
                                                                            $address.textContent = e.suggestion.value
                                                                            console.log(e);
                                                                        });

                                                                        placesAutocomplete.on('clear', function() {
                                                                            $address.textContent = 'none';
                                                                        });

                                                                    })();
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="current_job_title">Current Jobtitle</label>
                                                                <input type="text" name="current_job_title" id="current_job_title" class="form-control" placeholder="Current Jobtitle" required="" data-validation-required-message="The Current Jobtitle field is required" value="<?php echo set_value('current_job_title',@$this->form_data->current_job_title);?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4>Social-Networking Detail</h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_linkedin">LinkedIn Profile Link</label>
                                                                <input type="text" class="form-control" id="candidate_linkedin" name="candidate_linkedin" placeholder="LinkedIn Profile Link" data-validation-required-message="This LinkedIn Profile Link field is required" value="<?php echo set_value('candidate_linkedin',@$this->form_data->candidate_linkedin);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_git">GitHub Profile Link</label>
                                                                <input type="text" class="form-control" id="candidate_git" name="candidate_git" placeholder="GitHub Profile Link" data-validation-required-message="This GitHub Profile Link field is required" value="<?php echo set_value('candidate_git',@$this->form_data->candidate_git);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_fb">Facebook Profile Link</label>
                                                                <input type="text" class="form-control" id="candidate_fb" name="candidate_fb" placeholder="Facebook Profile Link" data-validation-required-message="This Facebook Profile Link field is required" value="<?php echo set_value('candidate_fb',@$this->form_data->candidate_fb);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_twitter">Twitter Profile Link</label>
                                                                <input type="text" class="form-control" id="candidate_twitter" name="candidate_twitter"  placeholder="Twitter Profile Link" data-validation-required-message="This Twitter Profile Link field is required" value="<?php echo set_value('candidate_twitter',@$this->form_data->candidate_twitter);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if(@$this->form_data->candidate_photo != ""){
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
                                                                    <input type="file" class="custom-file-input" id="candidate_photo" name="candidate_photo">
                                                                    <label class="custom-file-label" for="candidate_photo">Choose file</label>
                                                                </div>
                                                                <label for="basicInputFile">Accept PNG,JPG and JPEG files only</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if(@$this->form_data->candidate_photo != ""){ ?>

                                                        <img src="<?php echo check_image($this->form_data->candidate_photo,'uploads/candidate','thumb'); ?>" height="45" width="45" class="avatar m-1 avatar-lg"/>
                                                    <?php } ?>

                                                </div>
                                                <?php
                                                if(@$this->form_data->candidate_resume != ""){
                                                    $col1 = 'col-md-8';
                                                }else{
                                                    $col1 = 'col-md-12';
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4>Resume</h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="<?php echo $col1; ?>">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" id="candidate_resume" name="candidate_resume">
                                                                    <label class="custom-file-label" for="candidate_resume">Choose file</label>
                                                                </div>
                                                                <label for="basicInputFile"> Accept PDF files only</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(@$this->form_data->candidate_resume != ""){ ?>
                                                        <div class="font-large-1"><a href="<?=site_url().'uploads/candidate/resume/'.$this->form_data->candidate_resume; ?>"target="_blank"><i class="feather
icon-file-text ql-size-large"></i></a></div>
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
                                                <input type="hidden" name="j_id" value="<?php echo $this->form_data->job_id;?>"/>
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