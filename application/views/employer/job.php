<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/pages/data-list-view.css">

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
        <div class="content-body">
            <section id="status-wise-job">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h3 class="text-bold-700 mb-0">Total Jobs</h3>
                                    <h3 class="mb-1"><?php echo $total_records; ?></h3>
                                </div>
                                <div class="avatar bg-rgba-primary p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-briefcase text-primary font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h3 class="text-bold-700 mb-0">Open</h3>
                                    <h3 class="mb-1"><?php echo $open_counter; ?></h3>
                                </div>
                                <div class="avatar bg-rgba-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-briefcase text-success font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h3 class="text-bold-700 mb-0">Paused</h3>
                                    <h3 class="mb-1"><?php echo $paused_counter; ?></h3>
                                </div>
                                <div class="avatar bg-rgba-danger p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-briefcase text-warning font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-start pb-0">
                                <div>
                                    <h3 class="text-bold-700 mb-0">Closed</h3>
                                    <h3 class="mb-1"><?php echo $closed_counter; ?></h3>
                                </div>
                                <div class="avatar bg-rgba-warning p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-briefcase text-danger font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Data list view starts -->
            <section id="data-list-view" class="data-list-view-header">
                <div>
                    <?php if($total_records<1){?>
                        <div class="dt-buttons btn-group"><a class="btn btn-outline-primary" tabindex="0" aria-controls="DataTables_Table_0" href="<?php echo site_url('employer/job/add')?>"><span><i class="feather icon-plus"></i>Post a Job</span></a> </div>
                    <?php }?>
                </div>

                <!-- DataTable starts -->
                <div class="table-responsive">
                    <table class="table data-list-view">
                        <thead>
                        <tr>
                            <th>JOB</th>
                            <th>LOCATION</th>
                            <th>POSTED ON</th>
                            <th>SUBMITTED</th>
                            <th>CONTACTED</th>
                            <th>REMAINING</th>
                            <th>BATCH</th>
                            <th>JOB STATUS</th>
                            <th>ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(empty($list_records))
                        { ?>
                            <tr>
                                <td colspan="10" align="center">
                                    <?php if($total_records<1 ){ ?>
                                    No job has been posted yet. Let's post the first job!
                                    <?php } if($this->input->get('status')==1 ){ ?>
                                        No open job. Let's post a new one! <a href="<?php echo site_url('employer/job/add')?>" class="btn-icon btn btn-primary waves-effect waves-light">Post a Job</a>
                                         <a href="<?php echo $module_base_url; ?>" class="btn-icon btn btn-primary waves-effect waves-light">All Jobs</a>
                                    <?php } if($this->input->get('status')==2 ){ ?>
                                        No paused job yet! <a href="<?php echo $module_base_url; ?>" class="btn-icon btn btn-primary waves-effect waves-light">All Jobs</a>
                                    <?php }if($this->input->get('status')==3){?>
                                        No closed job yet! <a href="<?php echo $module_base_url; ?>" class="btn-icon btn btn-primary waves-effect waves-light">All Jobs</a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                        else
                        {
                            $i=1;
                            foreach($list_records as $row){
                        ?>
                                <tr class="data" id="data-<?php echo $row->job_id; ?>">
                                    <td class="product-price">
                                        <?php if(is_role_access_employer('candidate','index','display')){ ?>
                                            <a href="<?php echo site_url().'employer/candidate/'.$row->job_id; ?>"><?php echo $this->common_model->filterOutput($row->job_title).'<p class="font-small-1 ">ID: '.$this->common_model->filterOutput($row->code).'</p>'; ?></a>
                                        <?php }else{ ?>
                                            <?php echo $this->common_model->filterOutput($row->job_title).'<p class="font-small-1 ">ID: '.$this->common_model->filterOutput($row->code).'</p>'; ?>
                                        <?php }?>
                                    </td>
                                    <td class="product-price"><?php echo $this->common_model->filterOutput($row->job_location); ?></td>
                                    <td class="product-category">
                                        <?php echo date('d M, Y h:i A',strtotime($row->created_at));?>
                                    </td>
                                    <td class="product-price">
                                        <?php echo $this->common_model->filterOutput($row->submitted_candidate); ?>
                                    </td>
                                    <td class="product-price">
                                        <?php echo $this->common_model->filterOutput($row->contacted_candidate); ?>
                                    </td>
                                    <td class="product-price">
                                        <?php echo $this->common_model->filterOutput($row->remaining_candidate); ?>
                                    </td>
                                    <td class="product-price">
                                        <span id="b_size_<?php echo $row->job_id; ?>"><?php echo $this->common_model->filterOutput($row->job_profile_size); ?></span>
                                    </td>
                                    <td>
                                        <select class="job_list_status form-control" name="job_status" id="job_list_status_<?php echo $row->job_status; ?>" style="width: 100px;">
                                            <?php if($row->job_status==1 ){ ?>
                                            <option value="1" <?php echo $row->job_status==1?'selected':''; ?>>Open</option>
                                            <?php } ?>
                                            <option value="2" <?php echo $row->job_status==2?'selected':''; ?>>Paused</option>
                                            <option value="3" <?php echo $row->job_status==3?'selected':''; ?>>Closed</option>
                                        </select>
                                    </td>
                                    <td class="product-action">
                                        <div style="text-align: center;">
                                        <!--<span class="action-edit"><i class="feather icon-edit"></i></span>
                                        <span class="action-delete"><i class="feather icon-trash"></i></span>-->
                                            <?php if($row->remaining_candidate==0){ ?>
                                                <a href="javascript:;" class="add_profile_size w-100 d-block">
                                                <div class="chip chip-primary" style="width:100px;">
                                                    <div class="chip-body">
                                                        <div class="chip-text">Source More</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php } if(is_role_access_employer('job','edit','display')){ ?>
                                                <a href="<?php echo $edit_link.$row->job_id; ?>" class="" title="Edit"><i class="feather icon-edit-2"></i></a>
                                            <?php } if(is_role_access_employer('job','delete','display')){ ?>
                                                <a href="javascript:;" class="delete-job" title="Delete" ><i class="feather icon-trash"></i></a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>

                        <?php } } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="hdn" value="<?php echo $this->common_model->Encryption($tbl); ?>" />
                    <input type="hidden" id="clm" value="<?php echo $this->common_model->Encryption($column); ?>" />
                    <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                </div>
                <!-- DataTable ends -->
            </section>
            <!-- Data list view end -->
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>


<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>
<script>
    "use strict"
    // init list view datatable
    var dataListView = $(".data-list-view").DataTable({
        responsive: false,
        columnDefs: [
            {
                orderable: true,
                targets: 0,
                checkboxes: { selectRow: true }
            }
        ],
        dom:
            '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
            /*'<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',*/
        oLanguage: {
            sLengthMenu: "_MENU_",
            sSearch: ""
        },
        aLengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        select: {
            style: "multi"
        },
        order: [[1, "asc"]],
        bInfo: false,
        pageLength: 10,
        buttons: [
            {
                text: "<i class='feather icon-plus'></i> Post a Job",
                action: function() {
                    $(this).removeClass("btn-secondary")
                    window.location.replace(siteUrl+'job/add')
                },
                className: "btn-outline-primary"
            }
        ],
        initComplete: function(settings, json) {
            $(".dt-buttons .btn").removeClass("btn-secondary")
        }
    });

    // To append actions dropdown before add new button
    /*var actionDropdown = $(".actions-dropodown")
    actionDropdown.insertBefore($(".top .actions .dt-buttons"))*/

    // Scrollbar
    if ($(".data-items").length > 0) {
        new PerfectScrollbar(".data-items", { wheelPropagation: false })
    }

    $('<div class="emp_job_st_div">' +
        '<div class="d-inline-block"><label class="font-medium-3">Job Status: </label></div><div class="d-inline-block v-50"><select class="form-control job_status_select"></div>'+
        '<option value="">All</option>'+
        '<option value="1" <?php echo $this->input->get('status')==1?'selected':''; ?>>Open</option>'+
        '<option value="2" <?php echo $this->input->get('status')==2?'selected':''; ?>>Paused</option>'+
        '<option value="3" <?php echo $this->input->get('status')==3?'selected':''; ?>>Closed</option>'+
        '</select>' +
        '</div>').appendTo(".top .actions");

    $(document).on('change','.job_status_select',function () {
        if($(this).val()==1){
            window.location.replace(siteUrl+'job/?status=1');
        }else if($(this).val()==2){
            window.location.replace(siteUrl+'job/?status=2');
        }else if($(this).val()==3){
            window.location.replace(siteUrl+'job/?status=3');
        }else{
            window.location.replace(siteUrl+'job');
        }
    });
</script>

</body>

<!--Add profile size-->
<div class="modal fade text-left" id="addProfileModal" tabindex="-1" role="dialog" aria-labelledby="addProfileModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Need more profiles?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="controls">
                                <label for="title">How many candidates do you need in the next batch?</label>
                                <input type="number" class="form-control" id="pop_batch_size" name="pop_batch_size" required="" placeholder="Number of Candidates (Batch Size)" data-validation-required-message="This Batch Size field is required">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="add_batch_size">Request Sourcing Now!</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Discard</button>
            </div>
        </div>
    </div>
</div>

<!--pause job model-->
<div class="modal fade text-left" id="pauseJobModal" tabindex="-1" role="dialog" aria-labelledby="pauseJobModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Just want to confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Do you want to stop sourcing on this job right now?</h5>
                <div class="alert alert-danger">
                    <i class="feather icon-info mr-1"></i>Don't worry! Your remaining profile credits of this job will be reverted back to your account instantly.
                </div>
                <input type="hidden" name="pause_job_id" id="pause_job_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="pause_job_model_yes">Yes, go for it!</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Nope</button>
            </div>
        </div>
    </div>
</div>

<!--close job model-->
<div class="modal fade text-left" id="closedJobModel" tabindex="-1" role="dialog" aria-labelledby="closedJobModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Just want to confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Do you want to close this job?</h5>
                <div class="alert alert-danger">
                    <i class="feather icon-info mr-1"></i>Don't worry! Your remaining profile credits of this job will be reverted back to your account instantly if you have remaining profiles.
                </div>
                <input type="hidden" name="close_job_id" id="close_job_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="close_job_model_yes">Yes, let's close it!</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Nope</button>
            </div>
        </div>
    </div>
</div>

<!--delete job model-->
<div class="modal fade text-left" id="deleteJobModel" tabindex="-1" role="dialog" aria-labelledby="deleteJobModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Just want to confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want this job to be deleted? It'll be gone completely!</h5>
                <div class="alert alert-danger">
                    <i class="feather icon-alert-triangle mr-1"></i>Note: The candidates submitted for this job will be removed from the platform.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_job_model_yes">Yes, go for it!</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Nope</button>
            </div>
        </div>
    </div>
</div>
</html>