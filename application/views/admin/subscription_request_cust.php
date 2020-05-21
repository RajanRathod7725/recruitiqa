<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/pickers/pickadate/pickadate.css">
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
            <section id="page-account-settings">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="tab-btn-group">
                            <a class="tab-btn pill <?php if(@$_GET['section']=='Pending' || @$_GET['section']=='') echo "active";?>" href="<?php echo site_url();?>admin/subscription_request_cust?section=Pending">Pending</a>

                            <a class="tab-btn pill <?php if(@$_GET['section']=='Approve') echo "active";?>" href="<?php echo site_url();?>admin/subscription_request_cust?section=Approve">
                                <span class="">Approve</span>
                            </a>
                        </div>
                    </div>
                </div>
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
                                                                        <th width="20%">Employer Name/ Email / Contact</th>
                                                                        <th width="50%">Description</th>
                                                                        <th width="15%">Requested On</th>
                                                                        <th class="action" width="15%">Subscription Status</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    if(empty($list_records))
                                                                    { ?>
                                                                        <tr class="data">
                                                                            <td colspan="8" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        $i=1;
                                                                        foreach($list_records as $row){  ?>
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->subscription_request_cust_id; ?>">
                                                                                <td><?php echo $this->common_model->filterOutput($row->employer_name).'<br>'.$this->common_model->filterOutput($row->outreach_email).'<br>'.$this->common_model->filterOutput($row->employer_phone); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->description); ?></td>
                                                                                <td><?php echo $this->common_model->time_ago_in_php($row->created_at);?></td>
                                                                                <td class="action">
                                                                                    <?php if($row->request_status==1){?>
                                                                                        <span class="text-success">Approved</span>
                                                                                    <?php }else {?>
                                                                                        <select class="form-control select2" name="sub_req_cust_status" id="sub_req_cust_status">
                                                                                            <option value="0" <?php echo $row->request_status==0?'selected':''; ?>>Pending </option>
                                                                                            <option value="1" <?php echo $row->request_status==1?'selected':''; ?>>Approve </option>
                                                                                        </select>
                                                                                    <?php }?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    } ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th>Employer Name/ Email / Contact</th>
                                                                        <th>Description</th>
                                                                        <th>Requested On</th>
                                                                        <th class="action">Subscription Status</th>
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
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>

</body>
<!--SET ACTIVATED MAIL PASSWORD-->
<div class="modal fade text-left" id="setCustomPackModal" tabindex="-1" role="dialog" aria-labelledby="setCustomPackModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Approve Custom Subscription</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="custom_request" id="custom_request">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="controls">
                                <label for="sub_name">Subscription Name</label>
                                <input type="text" class="form-control" id="sub_name" name="sub_name" required="" placeholder="Subscription Name" data-validation-required-message="This Subscription Name field is required" >

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="controls">
                                <label for="month">Month(s)</label>
                                <input type="number" class="form-control" id="month" name="month" required="" placeholder="Month(s)" data-validation-required-message="This Month field is required" >

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="controls">
                                <label for="profile">No. of Profile(s)</label>
                                <input type="number" class="form-control" id="profile" name="profile" required="" placeholder="Profile(s)" data-validation-required-message="This Profile field is required" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="controls">
                                <label for="month">Start Date</label>
                                <input type="text" class="form-control" id="start_date" name="start_date" required="" placeholder="Start Date" data-validation-required-message="This Start Date field is required" >

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="controls">
                                <label for="profile">End Date</label>
                                <input type="text" class="form-control" id="end_date" name="end_date" required="" placeholder="End Date" data-validation-required-message="This End Date field is required" >
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="cnf_active_cust_subs">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

</html>