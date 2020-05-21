<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!--26 last id-->
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
            <section id="page-permission">
                <div class="row">
                    <!-- right content section -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="role_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <?php
                                                $per= explode(',',$permission);
                                                 ?>

                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <div class="d-inline-block"><h3> Role : </h3></div>
                                                        <div class="d-inline-block font-medium-4"><?php echo $this->form_data->title; ?></div>
                                                    </div>
                                                    <!--<div class="col-6">
                                                        <div class="d-inline-block"><h3> Role For : </h3></div>
                                                        <div class="d-inline-block font-medium-4"><?php /*echo $this->form_data->role_for=='1'?'Admins':'Employers'; */?></div>
                                                    </div>-->
                                                </div>
                                                <?php if($this->form_data->role_for=='1'){?>
                                                    <!--For ADMIN-->
                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <h5 class="mb-1">Recruiter</h5>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="recruiter__index__1" value="View" class="custom-control-input" <?php if(in_array(1,$per)){ echo 'checked';}?>>
                                                                <label class="custom-control-label" for="recruiter__index__1">View</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="recruiter__add__2" value="Add" class="custom-control-input" <?php if(in_array(2,$per)){ echo 'checked';}?>>
                                                                <label class="custom-control-label" for="recruiter__add__2">Add</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="recruiter__edit__3" value="Edit" class="custom-control-input" <?php if(in_array(3,$per)){ echo 'checked';}?>>
                                                                <label class="custom-control-label" for="recruiter__edit__3">Edit</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="recruiter__status__4" value="Status" class="custom-control-input" <?php if(in_array(4,$per)){ echo 'checked';}?>>
                                                                <label class="custom-control-label" for="recruiter__status__4">Status</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="recruiter__delete__5" value="Delete" class="custom-control-input" <?php if(in_array(5,$per)){ echo 'checked';}?>>
                                                                <label class="custom-control-label" for="recruiter__delete__5">Delete</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <h5 class="mb-1">Employer</h5>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="employer__index__6" value="View" class="custom-control-input" <?php if(in_array(6,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="employer__index__6">View</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="employer__add__7" value="Add" class="custom-control-input" <?php if(in_array(7,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="employer__add__7">Add</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="employer__edit__8" value="Edit" class="custom-control-input" <?php if(in_array(8,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="employer__edit__8">Edit</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="employer__status__9" value="Status" class="custom-control-input" <?php if(in_array(9,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="employer__status__9">Status</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="employer__delete__10" value="Delete" class="custom-control-input" <?php if(in_array(10,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="employer__delete__10">Delete</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <h5 class="mb-1">Candidate</h5>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="candidate__index__11" value="View" class="custom-control-input" <?php if(in_array(11,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="candidate__index__11">View</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="candidate__edit__12" value="Edit" class="custom-control-input" <?php if(in_array(12,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="candidate__edit__12">Edit</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="candidate__information__13" value="Detail" class="custom-control-input" <?php if(in_array(13,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="candidate__information__13">Detail</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <h5 class="mb-1">Jobs</h5>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__index__14" value="View" class="custom-control-input" <?php if(in_array(14,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="job__index__14">View</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__information__15" value="Detail" class="custom-control-input" <?php if(in_array(15,$per)){ echo 'checked';}?>>>
                                                                <label class="custom-control-label" for="job__information__15">Detail</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }else { ?>
                                                    <!--For EMPLOYER-->
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <h5 class="mb-1">Jobs</h5>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__index__16" value="View" class="custom-control-input" <?php if(in_array(16,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__index__16">View Posted Job(s)</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__add__17" value="Add" class="custom-control-input" <?php if(in_array(17,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__add__17">Can Post</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__edit__18" value="Edit" class="custom-control-input" <?php if(in_array(18,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__edit__18">Edit</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__status__19" value="Status" class="custom-control-input" <?php if(in_array(19,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__status__19">Make Sourcing Request</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__delete__20" value="Delete" class="custom-control-input" <?php if(in_array(20,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__delete__20">Delete</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="job__information__21" value="Detail" class="custom-control-input" <?php if(in_array(21,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="job__information__21">Check Details</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <h5 class="mb-1">Candidate</h5>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="candidate__index__22" value="View" class="custom-control-input" <?php if(in_array(22,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="candidate__index__22">View & Contact</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="candidate__export_candidate__26" value="Export Candidate" class="custom-control-input" <?php if(in_array(26,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="candidate__export_candidate__26">Export Candidate</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <h5 class="mb-1">Hired Candidate</h5>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="hired_candidate__index__23" value="View" class="custom-control-input" <?php if(in_array(23,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="hired_candidate__index__23">View Hired Candidate(s)</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="hired_candidate__information__24" value="Detail" class="custom-control-input" <?php if(in_array(24,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="hired_candidate__information__24">Check Details</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <h5 class="mb-1">Tasks</h5>
                                                                <div class="custom-control custom-checkbox mb-1"><input type="checkbox" id="todo__index__25" value="View" class="custom-control-input" <?php if(in_array(25,$per)){ echo 'checked';}?>>
                                                                    <label class="custom-control-label" for="todo__index__25">View Tasks</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                                <input type="hidden" name="role_id" id="role_id" value="<?php echo @$this->form_data->id; ?>"/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--employer detail-->
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
<script>
    $(document).on('click', '#page-permission .custom-checkbox input[type="checkbox"]', function () {
        var id = $(this).attr('id');
        var id_array = id.split("__");
        var module = id_array[0];
        var method = id_array[1];
        var ck_id = id_array[2];
        var ck_val = $(this).val();
        var role_id = $('#role_id').val();
        var operation = '';
        if($(this).is(":checked")){
            operation ='Add';
        }else{
            operation = 'remove';
        }
        form_data = new FormData();
        form_data.append('role_id', role_id);
        form_data.append('module', module);
        form_data.append('method', method);
        form_data.append('ck_id', ck_id);
        form_data.append('ck_val', ck_val);
        form_data.append('operation', operation);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl+'ajax_add_remove_permission',
            success: function(obj){
                if(obj.code==1)
                {
                    toastr.success(obj.message, 'Success!');
                    //"Permission has been removed successfully!"
                }
                else
                {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function(obj){
                errormsg(csrf_error);
            },
            complete: function(obj){
                bulkRowThat = ''
                obj=obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    });
</script>
</body>
</html>