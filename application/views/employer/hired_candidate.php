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
                                                            <?php include_once(APPPATH."/views/employer/includes/display_msg.php"); ?>
                                                            <div class="table-responsive">
                                                                <table class="table zero-configuration table-hover-animation">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Photo</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Phone</th>
                                                                        <th>Location</th>
                                                                        <th>Current Job</th>
                                                                        <th>Applied For</th>
                                                                        <th class="action">Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    if(empty($list_records))
                                                                    { ?>
                                                                        <tr class="data">
                                                                            <td colspan="10" align="center">Let's get connected with sourced candidates and make the first hire!</td>
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
                                                                                <td class="action">
                                                                            <?php if(is_role_access_employer('hired_candidate','information','display')){ ?>
                                                                                    <a href="<?php echo $info_link.$row->candidate_id; ?>" class="" title="Detail"><i class="feather icon-eye"></i></a>
                                                                                <?php } ?>
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