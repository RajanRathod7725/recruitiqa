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
                        <a href="<?php echo $link_add; ?>" class="btn-icon btn btn-primary"><i class="feather
icon-plus-square"></i>Add</a>
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
                                                                        <th>Photo</th>
                                                                        <th>Member Name</th>
                                                                        <th>Member Email</th>
                                                                        <th>Member Phone</th>
                                                                        <th>Member Role</th>
                                                                        <th class="action">Status</th>
                                                                        <th class="action">Action</th>
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
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->admin_id; ?>">
                                                                                <td>
                                                                                    <div class="avatar mr-1 avatar-lg">
                                                                                    <img src="<?php echo check_image($row->admin_photo,'uploads/admin','thumb'); ?>" class="img-responsive thumb-sm" height="45" width="45"/>
                                                                                    </div>
                                                                                </td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->admin_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->admin_email); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->admin_phone); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->title); ?></td>
                                                                                <td class="action">
                                                                                    <?php if($row->status==0){ echo "Not Verified!";

                                                                                    }else{?>
                                                                                    <div class="custom-control custom-switch switch-lg custom-switch-success mr-1" id="">
                                                                                    <input type="checkbox" class="list-switch custom-control-input status-switch" id="switch<?php echo $row->admin_id; ?>" <?php echo $row->status=='1'?'checked':'';?> >
                                                                                    <label class="custom-control-label" for="switch<?php echo $row->admin_id; ?>">
                                                                                        <span class="switch-text-left">Enable</span>
                                                                                        <span class="switch-text-right">Disable</span>
                                                                                    </label>
                                                                                    </div>
                                                                                <?php }?>
                                                                                </td>
                                                                                <td class="action">
                                                                                    <a href="<?php echo $edit_link.$row->admin_id; ?>" class="" title="Edit"><i class="feather icon-edit-2"></i></a>
                                                                                    <a href="javascript:;" class="delete ml-1" title="Delete" ><i class="feather icon-trash"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    } ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th>Photo</th>
                                                                        <th>Member Name</th>
                                                                        <th>Member Email</th>
                                                                        <th>Member Phone</th>
                                                                        <th>Member Role</th>
                                                                        <th class="action">Status</th>
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

</html>