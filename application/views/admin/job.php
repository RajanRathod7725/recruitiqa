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
            <!--<div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrum-right">
                    <div class="dropdown">
                        <a href="<?php /*echo $link_add; */?>" class="btn-icon btn btn-primary"><i class="feather
icon-plus-square"></i>Add</a>
                    </div>
                </div>
            </div>-->
        </div>
        <!--breadscrum end-->

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
            <section id="page-search">
                <div class="row">
                    <div class="col-md-12">
                        <form action="<?php echo $action; ?>" method="get">
                            <div class="mb-1 w-75 d-inline-block">
                                <label for="job_status w-25"><b> Status : </b></label>
                                <select class="form-control d-inline-block w-25 mr-1" name="status" id="search">
                                    <option value="">All</option>
                                    <option value="1" <?php echo $this->input->get('status')==1?'selected':''; ?>>Open</option>
                                    <option value="2" <?php echo $this->input->get('status')==2?'selected':''; ?>>Paused</option>
                                    <option value="3" <?php echo $this->input->get('status')==3?'selected':''; ?>>Closed</option>
                                </select>
                                <label for="job_status w-25"><b> Company : </b></label>
                                <div class="d-inline-block" style="width: 55% !important;">
                                    <select class="form-control d-inline-block mr-1 select2" name="company" id="company">
                                        <option value="">All</option>
                                        <?php foreach ($companies as $company){?>
                                            <option value="<?php echo $company->employer_id?>" <?php echo $this->input->get('company')==$company->employer_id?'selected':''; ?>><?php echo $company->company_name?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-1 d-inline-block" style="width: 24%">
                                <button class="btn-icon btn btn-primary waves-effect waves-light d-inline-block w-25" value="Apply" type="submit">Apply</button>
                                <?php if($this->input->get('status') != '' ||$this->input->get('company') != ''){ ?>
                                    <div class="d-inline-block">
                                        <a href="<?php echo $module_base_url;?>" style="color:#fff;" class="btn-icon btn btn-success waves-effect waves-light">ALL</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <section id="page-account-settings">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <section id="basic-datatable">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-body card-dashboard">
                                                            <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                            <div class="table-responsive">
                                                                <table class="table zero-configuration table-hover-animation">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Jobs</th>
                                                                        <th>Company</th>
                                                                        <th>Location</th>
                                                                        <th>Posted On</th>
                                                                        <th>Submitted</th>
                                                                        <th>Contacted</th>
                                                                        <th>Remaining</th>
                                                                        <th>Batch Size</th>
                                                                        <th width="10%">Job Status</th>
                                                                        <th class="action" width="10%">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    if(empty($list_records))
                                                                    { ?>
                                                                        <tr class="data">
                                                                            <td colspan="11" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        $i=1;
                                                                        foreach($list_records as $row){  ?>
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->job_id; ?>">
                                                                                <td><?php echo $this->common_model->filterOutput($row->job_title); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->company_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->job_location); ?></td>
                                                                                <td><?php echo $this->common_model->time_ago_in_php($row->created_at);?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->submitted_candidate); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->contacted_candidate); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->remaining_candidate); ?></td>
                                                                                <td><span id="b_size_<?php echo $row->job_id; ?>"><?php echo $this->common_model->filterOutput($row->job_profile_size); ?></span></td>

                                                                                <td class="action">
                                                                                    <select class="job_list_status form-control" name="job_status" id="job_list_status_<?php echo $row->job_status; ?>" style="width: 100px;">
                                                                                    <?php if($row->job_status==1 ){ ?>
                                                                                        <option value="1" <?php echo $row->job_status==1?'selected':''; ?>>Open</option>
                                                                                    <?php } ?>
                                                                                        <option value="2" <?php echo $row->job_status==2?'selected':''; ?>>Paused</option>
                                                                                        <option value="3" <?php echo $row->job_status==3?'selected':''; ?>>Closed</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="action">
                                                                                    <div style="text-align: center;">
                                                                                    <?php if($row->remaining_candidate==0){ ?>
                                                                                        <a href="javascript:;" title="Source Again" class="add_profile_size activate-profile btn-icon btn btn-primary mb-1" style="width:120px;">Source Again</a><br>
                                                                                    <?php }  if(is_role_access('job','edit','display')){ ?>
                                                                                            <a href="<?php echo $edit_link.$row->job_id; ?>" class="" title="Edit"><i class="feather icon-edit-2"></i></a>
                                                                                        <?php } if(is_role_access('job','delete','display')){ ?>
                                                                                            <a href="javascript:;" class="delete ml-1" title="Delete" ><i class="feather icon-trash"></i></a>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    } ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th>Jobs</th>
                                                                        <th>Company</th>
                                                                        <th>Location</th>
                                                                        <th>Posted On</th>
                                                                        <th>Submitted</th>
                                                                        <th>Contacted</th>
                                                                        <th>Remaining</th>
                                                                        <th>Batch Size</th>
                                                                        <th>Job Status</th>
                                                                        <th class="action">Action</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <input type="hidden" id="hdn" value="<?php echo $this->common_model->Encryption($tbl); ?>" />
                                                                <input type="hidden" id="clm" value="<?php echo $this->common_model->Encryption($column); ?>" />
                                                                <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
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
<!--Add profile size-->
<div class="modal fade text-left" id="addProfileModal" tabindex="-1" role="dialog" aria-labelledby="addProfileModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Enter Batch Size</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="controls">
                                <label for="title">Size</label>
                                <input type="number" class="form-control" id="pop_batch_size" name="pop_batch_size" required="" placeholder="Size" data-validation-required-message="This Batch Size field is required">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="add_batch_size">Add</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--pause job model-->
<div class="modal fade text-left" id="pauseJobModal" tabindex="-1" role="dialog" aria-labelledby="pauseJobModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Confirmation Alert</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Do you want to stop sourcing on this job?</h5>
                <input type="hidden" name="pause_job_id" id="pause_job_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="pause_job_model_yes">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--close job model-->
<div class="modal fade text-left" id="closedJobModel" tabindex="-1" role="dialog" aria-labelledby="closedJobModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Confirmation Alert</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Do you want to close this job?</h5>
                <input type="hidden" name="close_job_id" id="close_job_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="close_job_model_yes">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
</html>