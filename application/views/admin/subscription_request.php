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
                    <div class="col-md-12 mb-2">
                        <div class="tab-btn-group">
                            <a class="tab-btn pill <?php if(@$_GET['section']=='Pending' || @$_GET['section']=='') echo "active";?>" href="<?php echo site_url();?>admin/subscription_request?section=Pending">Pending</a>

                            <a class="tab-btn pill <?php if(@$_GET['section']=='Approve') echo "active";?>" href="<?php echo site_url();?>admin/subscription_request?section=Approve">
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
                                                                        <th width="10%">Employer Name</th>
                                                                        <th width="10%">Employer Company</th>
                                                                        <th width="10%">Employer Email</th>
                                                                        <th width="20%">Subscription Name</th>
                                                                        <th width="10%">No. of Profile</th>
                                                                        <th width="10%">Final Amount</th>
                                                                        <th width="10%">Requested On</th>
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
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->subscription_request_id; ?>">
                                                                                <td><?php echo $this->common_model->filterOutput($row->employer_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->company_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->outreach_email); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->subscription.' ('.$row->description.')'); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->profile.' ( * $'.$row->profile_rate.')'); ?> </td>
                                                                                <td><?php $amount = $row->profile * $row->profile_rate; echo '$'.round($amount); ?> </td>
                                                                                <td><?php echo $this->common_model->time_ago_in_php($row->created_at);?></td>
                                                                                <td class="action">
                                                                                    <?php if($row->request_status==1){?>
                                                                                        <span class="text-success">Approved</span>
                                                                                    <?php }else {?>
                                                                                        <select class="form-control select2" name="subscription_req_status" id="subscription_req_status">
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
                                                                        <th>Employer Name</th>
                                                                        <th>Employer Company</th>
                                                                        <th>Employer Email</th>
                                                                        <th>Subscription Name</th>
                                                                        <th>No. of Profile</th>
                                                                        <th>Final Amount</th>
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

</body>
<!--SET ACTIVATED MAIL PASSWORD-->
</html>