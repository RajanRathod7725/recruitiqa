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
            <section id="page-recruiter-job">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="tab-btn-group">
                            <a class="tab-btn pill <?php if(@$_GET['section']=='Remaining' || @$_GET['section']=='') echo "active";?>" href="<?php echo site_url();?>recruiter/job?section=Remaining">Remaining</a>

                            <a class="tab-btn pill <?php if(@$_GET['section']=='All') echo "active";?>" href="<?php echo site_url();?>recruiter/job?section=All">
                                <span class="pr-3 pl-3">All</span>
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
                                    <div class="table-responsive">
                                        <?php include_once(APPPATH."/views/recruiter/includes/display_msg.php"); ?>
                                        <table class="table table-hover-animation">
                                            <tbody>
                                                <?php
                                                if(empty($list_records))
                                                { ?>
                                                    <tr>
                                                        <td colspan="8" align="center">
                                                            No Jobs Found!
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                else
                                                {
                                                    foreach($list_records as $row){  ?>
                                                        <tr class="mb-1">
                                                            <td class="width-30-per"><?php echo '<h5 class="m-0 p-0">'.$this->common_model->filterOutput($row->job_title).'</h5><br>'.$this->common_model->filterOutput($row->job_location);?></td>
                                                            <td class="width-20-per"><?php echo date('d M,Y',strtotime($this->common_model->filterOutput($row->created_at))); ?></td>
                                                            <td class="width-20-per"><?php echo $this->common_model->time_ago_in_php($row->created_at);?></td>
                                                            <td class="width-20-per"><?php echo $this->common_model->filterOutput($row->remaining_candidate).' Missing'; ?></td>
                                                            <td class="width-10-per">
                                                                <a href="<?php echo $info_link.$row->job_id; ?>" class="mr-1"><i class="feather icon-eye font-medium-4" title="Detail" data-placement="bottom"></i></a>
                                                                <?php if($row->is_candidate>0){?>
                                                                    <a href="<?php echo $candidate_list_link.$row->job_id; ?>"><i class="feather icon-user font-medium-4" title="Submitted Candidate List" data-placement="bottom"></i></a>
                                                                <?php }?>
                                                            </td>
                                                        </tr>
                                                <?php } }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-right mb-2">
                                        <?php echo $pagination; ?>
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