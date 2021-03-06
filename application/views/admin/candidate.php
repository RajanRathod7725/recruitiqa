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
                                                                        <th width="10%">Photo</th>
                                                                        <th width="10%">Name</th>
                                                                        <th width="10%">Email</th>
                                                                        <th width="10%">Phone</th>
                                                                        <th width="10%">Location</th>
                                                                        <th width="10%">Current Job</th>
                                                                        <th width="10%">Applied For</th>
                                                                        <th width="10%">Recruiter</th>
                                                                        <th class="action" width="20%">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    if(empty($list_records))
                                                                    { ?>
                                                                        <tr class="data">
                                                                            <td colspan="10" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        $i=1;
                                                                        foreach($list_records as $row){  ?>
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->candidate_id; ?>">
                                                                                <td>
                                                                                    <div class="avatar mr-1 avatar-lg">
                                                                                        <img src="<?php echo check_image($row->candidate_photo,'uploads/candidate','thumb'); ?>" class="img-responsive thumb-sm" height="45" width="45"/>
                                                                                    </div>
                                                                                </td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->candidate_name); ?></td>
                                                                                <td><?php if($row->candidate_email!=''){
                                                                                        $emails=explode(',',$row->candidate_email);
                                                                                        foreach ($emails as $email){
                                                                                            echo $email.'<br>';
                                                                                        }
                                                                                    }else{echo '-';} ?></td>
                                                                                <td><?php echo $row->candidate_phone!=''?$this->common_model->filterOutput($row->candidate_phone):'-'; ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->candidate_location); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->current_job_title); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->job_title); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->recruiter_name); ?></td>
                                                                                <td class="action">
                                                                                    <div class="pt-1">
                                                                                        <?php  if(is_role_access('candidate','edit','display')){ ?>
                                                                                            <a href="<?php echo $edit_link.$row->candidate_id; ?>" class="mr-1" title="Edit"><i class="feather icon-edit-2"></i></a>
                                                                                        <?php } if(is_role_access('candidate','information','display')){ ?>
                                                                                            <a href="<?php echo $info_link.$row->candidate_id; ?>" class="" title="Detail"><i class="feather icon-eye"></i></a>
                                                                                        <?php } if(is_role_access('candidate','delete','display')){ ?>
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
                                                                        <th>Photo</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>Location</th>
                                                                        <th>Current Job</th>
                                                                        <th>Applied For</th>
                                                                        <th>Recruiter</th>
                                                                        <th class="action">Action</th>
                                                                    </tr>
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

</html>