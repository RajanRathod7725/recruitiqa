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
            <section id="page-edit-job">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="job_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_title">Job Title</label>
                                                                <input type="text" class="form-control" id="job_title" name="job_title" required="" placeholder="Job Title" data-validation-required-message="This Job Title field is required" value="<?php echo set_value('job_title',@$this->form_data->job_title);?>" <?php echo $method=='Edit'?'readonly':'';?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="code">Job ID</label>
                                                                <input type="text" class="form-control" id="code" name="code" required="" placeholder="Add Job ID" data-validation-required-message="This Job Code field is required" value="<?php echo set_value('code',@$this->form_data->code);?>" <?php echo $method=='Edit'?'readonly':'';?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_type_id">Job Type</label>
                                                                <select class="select2 form-control" name="job_type_id" id="job_type_id">
                                                                    <option value="">Select Job Type</option>
                                                                    <?php foreach ($job_types as $job_type) { ?>
                                                                        <option value="<?php echo $job_type->job_type_id; ?>" <?php echo @$this->form_data->job_type_id==$job_type->job_type_id?'selected':''; ?>><?php echo $job_type->title; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_location">Job Location(s)</label>
                                                                <input type="text" class="form-control" id="job_location" name="job_location" required="" placeholder="Job Location" data-validation-required-message="This Job Location field is required" value="<?php echo set_value('job_location',@$this->form_data->job_location);?>">
                                                                <p>Selected: <strong id="address-value">none</strong></p>
                                                                <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>
                                                                <script>
                                                                    (function() {
                                                                        var placesAutocomplete = places({appId: 'plJES61WLXRM',apiKey: 'a17570e937aa49c6723f30c9c28645c0',container: document.querySelector('#job_location')});var $address = document.querySelector('#address-value');placesAutocomplete.on('change', function(e) {$address.textContent = e.suggestion.valueconsole.log(e);});placesAutocomplete.on('clear', function() {$address.textContent = 'none';});})();
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" id="location_radio_div">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_source_location">Source Location(s)</label>
                                                                <div class="d-inline-block w-100 mt-1">
                                                                    <div class="custom-control custom-radio d-inline-block w-25">
                                                                        <input type="radio" class="custom-control-input" name="source_location" id="multiple_location" value="1" <?php echo (@$this->form_data->source_location=='' || @$this->form_data->source_location=='1')?'checked':''; ?>>
                                                                        <label class="custom-control-label" for="multiple_location">Source from Location(s)</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio d-inline-block w-25">
                                                                        <input type="radio" class="custom-control-input" name="source_location" id="search_radius" value="2" <?php echo @$this->form_data->source_location=='2'?'checked':''; ?>>
                                                                        <label class="custom-control-label" for="search_radius">Source within Search Radius</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 multiple-location" style="display: <?php echo (@$this->form_data->source_location=='' || @$this->form_data->source_location=='1')?'block':'none'; ?>">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_email">Location(s)</label>
                                                                <div class="row field_wrapper">
                                                                    <!--edit or not empty location-->
                                                                    <?php
                                                                    $lcaount = count(@$multiple_locations);
                                                                    if($lcaount >0){
                                                                        $i=1;
                                                                        for($j=0;$j<$lcaount;$j++){  ?>

                                                                            <div class="col-md-11"><input type="text" name="multiple_location[]" id="multiple_location_<?php echo $i; ?>" class="location-inputs form-control" placeholder="Location <?php echo $i; ?>" data-validation-required-message="The multiple location field is required" value="<?php echo @$multiple_locations[$j]; ?>">
                                                                                <input type="hidden" name="location_ids[]" value="<?php echo @$location_ids[$j]; ?>">
                                                                                <script>
                                                                                    var placesAutocomplete = places({appId: 'plJES61WLXRM',apiKey: 'a17570e937aa49c6723f30c9c28645c0',container: document.querySelector('#multiple_location_<?php echo $i; ?>')});placesAutocomplete.on('clear', function() {$address.textContent = 'none';});
                                                                                </script>
                                                                            </div>

                                                                            <?php if($i==1){ ?>
                                                                                <div class="col-md-1"><a href="javascript:void(0);" class="add_location" title="Add Location"><i class="feather icon-plus-square font-large-2 float-right"></i></a></div>
                                                                            <?php }else{ ?>
                                                                                <div class="col-md-1 d-inline-block float-left"> <a href="javascript:void(0);" class="remove_location" id="<?php echo @$location_ids[$j]; ?>"" title="Remove Location"><i class="feather icon-minus-square font-large-2 float-right"></i></a></div>
                                                                            <?php }
                                                                            $i++; }
                                                                    }else{ ?>
                                                                        <!--add /empty location-->
                                                                        <div class="col-md-11"><input type="text" name="multiple_location[]" id="multiple_location_1" class="location-inputs form-control" placeholder="Location 1" data-validation-required-message="The location field is required"></div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0);" class="add_location" title="Add Location"><i class="feather icon-plus-square font-large-2 float-right"></i></a>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--SEARCH RADIUS-->
                                                    <div class="col-12 search-radius" style="display: <?php echo @$this->form_data->source_location=='2'?'block':'none'; ?>">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="candidate_email">Location(s)</label>
                                                                <div class="row field_s_wrapper">
                                                                    <?php $count = count(@$s_location); if($count>0){
                                                                        $i=1;
                                                                        for($j=0;$j<$count;$j++){

                                                                            ?>
                                                                            <div class="col-md-11 mb-1">
                                                                                <input type="text" name="multiple_s_location[]" id="multiple_s_location_<?php echo $i; ?>" class="location-s-inputs form-control" placeholder="Location <?php echo $i; ?>" data-validation-required-message="The multiple location field is required" value="<?php echo $s_location[$j]; ?>" style="margin-bottom: 5px;">
                                                                                <input type="hidden" name="location_ids[]" value="<?php echo @$location_ids[$j]; ?>">
                                                                                <select class="form-control" name="search_radius[]" id="search_radius_<?php echo $i; ?>">
                                                                                    <?php foreach ($search_radiuses as $search_radius) { ?>
                                                                                        <option value="<?php echo $search_radius->search_radius_id; ?>" <?php echo @$s_radius[$j]==$search_radius->search_radius_id?'selected':''; ?>><?php echo $search_radius->radius; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <script>
                                                                                    var placesAutocomplete = places({appId: 'plJES61WLXRM',apiKey: 'a17570e937aa49c6723f30c9c28645c0',container: document.querySelector('#multiple_s_location_<?php echo $i; ?>')});placesAutocomplete.on('clear', function() {$address.textContent = 'none';});
                                                                                </script>
                                                                            </div>
                                                                            <?php if($i==1){ ?>
                                                                                <div class="col-md-1"><a href="javascript:void(0);" class="add_s_location" title="Add Location"><i class="feather icon-plus-square font-large-2 float-right"></i></a></div>
                                                                            <?php }else{ ?>
                                                                                <div class="col-md-1 d-inline-block float-left"> <a href="javascript:void(0);" class="remove_s_location" id="<?php echo @$location_ids[$j]; ?>" title="Remove Location"><i class="feather icon-minus-square font-large-2 float-right"></i></a></div>
                                                                            <?php }
                                                                            $i++;
                                                                        }
                                                                    }else{ ?>
                                                                        <div class="col-md-11 mb-1">
                                                                            <input type="text" name="multiple_s_location[]" id="multiple_s_location_1" class="location-s-inputs form-control" placeholder="Location 1" data-validation-required-message="The location field is required" style="margin-bottom: 5px;">
                                                                            <select class="form-control" name="search_radius[]" id="search_radius_1">
                                                                                <?php foreach ($search_radiuses as $search_radius) { ?>
                                                                                    <option value="<?php echo $search_radius->search_radius_id; ?>"><?php echo $search_radius->radius; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0);" class="add_s_location" title="Add Location"><i class="feather icon-plus-square font-large-2 float-right"></i></a>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_type_id">Job Industry</label>
                                                                <select class="select2 form-control" name="job_industry_id" id="job_industry_id">
                                                                    <option value="">Select Job Industry</option>
                                                                    <?php foreach ($job_industries as $job_industry) { ?>
                                                                        <option value="<?php echo $job_industry->job_industry_id; ?>" <?php echo @$this->form_data->job_industry_id==$job_industry->job_industry_id?'selected':''; ?>><?php echo $job_industry->title; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_industry">Years of Experience</label>
                                                                <div class="form-group row">
                                                                    <div class="col-md-1">
                                                                        <label for="job_industry" style="margin-top: 10px;">Minimum</label>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <input type="number" class="form-control" id="min_experience" name="min_experience" required="" placeholder="Year(s)" data-validation-required-message="This Minimum Experience field is required" value="<?php echo set_value('min_experience',@$this->form_data->min_experience);?>">
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <label for="job_industry" style="margin-top: 10px;">Maximum</label>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <input type="number" class="form-control" id="max_experience" name="max_experience" required="" placeholder="Year(s)" data-validation-required-message="This Maximum Experience field is required" value="<?php echo set_value('max_experience',@$this->form_data->max_experience);?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_profile_size">How many profiles do you want us to source for your first batch of candidates?</label>
                                                                <input type="number" class="form-control" id="job_profile_size" name="job_profile_size" required="" placeholder="Number of profiles" data-validation-required-message="This No. Of Profile field is required" value="<?php echo set_value('job_profile_size',@$this->form_data->job_profile_size);?>" <?php echo $method=='Edit'?'readonly':'';?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_description">Job Description</label>
                                                                <textarea class="form-control" id="job_description" name="job_description" required="" placeholder="Job Description" data-validation-required-message="This Job Description field is required" rows="7"><?php echo set_value('job_description',@$this->form_data->job_description);?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_requirement">Must Haves (Required Skills/Experiences)</label>
                                                                <textarea class="form-control" id="job_requirement" name="job_requirement" required="" placeholder="Add Must-haves/Required Skills for this Job" data-validation-required-message="This Job Requirement field is required" rows="7"><?php echo set_value('job_requirement',@$this->form_data->job_requirement);?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_remark">Your advise to the Sourcers/additional information/any important comments:</label>
                                                                <textarea class="form-control" id="job_remark" name="job_remark" placeholder="Your Comments"><?php echo set_value('job_remark',@$this->form_data->job_remark);?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="job_profile_banchmark">Benchmark Profile(s)</label>
                                                                <input type="text" class="form-control" id="job_profile_banchmark" name="job_profile_banchmark"  placeholder="Add Benchmark Profile Links" data-validation-required-message="This Profile Banchmark field is required" value="<?php echo set_value('job_profile_banchmark',@$this->form_data->job_profile_banchmark);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="black_listed_company">Blacklisted Company</label>
                                                                <input type="text" class="form-control" id="black_listed_company" name="black_listed_company"  placeholder="Name of Company(s)" data-validation-required-message="This Black Listed Company field is required" value="<?php echo set_value('black_listed_company',@$this->form_data->black_listed_company);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <?php if($method=='Add'){ ?>
                                                            <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Post</button>
                                                        <?php }else{ ?>
                                                            <button type="submit" class="btn btn-danger mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Save changes</button>
                                                        <?php } ?>
                                                        <button type="reset" class="btn btn-outline-warning waves-effect waves-light">Cancel</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                                <input type="hidden" name="method" value="<?php echo $method;?>"/>
                                                <input type="hidden" id="hdn" value="<?php echo $this->common_model->Encryption($tbl); ?>" />
                                                <input type="hidden" id="clm" value="<?php echo $this->common_model->Encryption($column); ?>" />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--admin detail-->
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
<script type="text/javascript" src='https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js'></script>
<script type="text/javascript">
    jQuery(document).ready(function() {

        /*with html*/
        CKEDITOR.replace('job_description', {
            fullPage: false,
            allowedContent: true,
            autoGrow_onStartup: true,
            enterMode: CKEDITOR.ENTER_BR,
            height: '200px',
        });
    });


</script>
</body>
<div class="modal fade text-left" id="deletelocationModal" tabindex="-1" role="dialog" aria-labelledby="deletelocationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Confirmation Alert</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want to delete this record? You cannot recover it back.</h5>
                <!--<p>Oat </p>-->
            </div>
            <input type="hidden" name="location_id" id="location_id">
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-loc-confirm-btn">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

</html>