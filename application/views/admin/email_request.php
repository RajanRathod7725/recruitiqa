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
                                                                        <th width="20%">Employer Name</th>
                                                                        <th width="20%">Requested Email</th>
                                                                        <th width="10%">Posted On</th>
                                                                        <th width="25%">Note</th>
                                                                        <th class="action" width="15%">Email Status</th>
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
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->personal_email_id; ?>">
                                                                                <td><?php echo $this->common_model->filterOutput($row->employer_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->email_username); ?> <span class="text-muted" style="cursor: not-allowed"><?php echo $this->site_setting->employer_mail_suffix; ?></span></td>
                                                                                <td><?php echo $this->common_model->time_ago_in_php($row->created_at);?></td>
                                                                                <td><?php echo $row->note!=''?$this->common_model->filterOutput($row->note):'--'; ?></td>
                                                                                <td class="action">
                                                                                    <select class="form-control select2" name="mail_status" id="mail_status_select">
                                                                                        <option value="0" <?php echo $row->email_status==0?'selected':''; ?>>Pending </option>
                                                                                        <option value="1" <?php echo $row->email_status==1?'selected':''; ?>>Created </option>
                                                                                        <option value="2">Delete</option>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    } ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th>Employer Name</th>
                                                                        <th>Requested Email</th>
                                                                        <th>Posted On</th>
                                                                        <th>Note</th>
                                                                        <th class="action">Email Status</th>
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
<!--SET ACTIVATED MAIL PASSWORD-->
<div class="modal fade text-left" id="setEmailPassModal" tabindex="-1" role="dialog" aria-labelledby="setEmailPassModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Created Mail Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100">
                    <div class="form-group">
                        <div class="controls">
                            <label for="email_note">Password</label>
                            <input type="text" class="form-control" id="password" name="password" required="" placeholder="Password" data-validation-required-message="This Password field is required" >
                            <input type="hidden" name="request_email_id" id="request_email_id">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cnf_active_pass">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--DELETE REQUESTED MAIL AND REASON FOR DELETION-->
<div class="modal fade text-left" id="deleteEmailModal" tabindex="-1" role="dialog" aria-labelledby="deleteEmailModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Confirmation Alert</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="mb-1">Are you sure you want to delete this record? You cannot recover it back.</h5>
                <div class="w-100">
                    <div class="form-group">
                        <div class="controls">
                            <label for="email_note">Reason for the Deletion</label>
                            <textarea class="form-control" id="email_reject_reason" name="email_reject_reason" placeholder="Reason" data-validation-required-message="This Reason field is required" rows="3"></textarea>
                            <input type="hidden" name="email_employer_id" id="email_employer_id">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="cnf_delete_mail">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
</html>