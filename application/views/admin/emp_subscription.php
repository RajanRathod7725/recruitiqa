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
                            <a class="tab-btn pill <?php if(@$_GET['section']=='Active' || @$_GET['section']=='') echo "active";?>" href="<?php echo site_url();?>admin/emp_subscription?section=Active">Active</a>

                            <a class="tab-btn pill <?php if(@$_GET['section']=='Inactive') echo "active";?>" href="<?php echo site_url();?>admin/emp_subscription?section=Inactive">
                                <span class="">Inactive</span>
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
                                                                        <th width="10%">Start Date</th>
                                                                        <th width="10%">End Date</th>
                                                                        <th width="10%">Assigned Credit</th>
                                                                        <th width="10%">Remain Credit</th>
                                                                        <th width="10%">Status</th>
                                                                        <?php if(@$_GET['section']=='Active' || @$_GET['section']==''){ ?>
                                                                        <th class="action" width="10%">Action</th>
                                                                        <?php } ?>
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
                                                                            <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->employer_subscription_id; ?>">
                                                                                <td><?php echo $this->common_model->filterOutput($row->employer_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->company_name); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->outreach_email); ?></td>
                                                                                <td><?php echo $this->common_model->filterOutput($row->subscription_name); ?></td>
                                                                                <td><?php echo date('d/m/Y',strtotime($row->start_date)); ?> </td>
                                                                                <td><?php echo date('d/m/Y',strtotime($row->end_date)); ?> </td>
                                                                                <td><span id="assign_credit_<?php echo $row->employer_subscription_id; ?>"><?php echo $row->assigned_credit; ?></span> </td>
                                                                                <td><span id="remain_credit_<?php echo $row->employer_subscription_id; ?>"><?php echo $row->remain_credit; ?></span> </td>
                                                                                <td><?php echo $row->subscription_status==0?"<span class='text-success'>Active</span>":"<span class='text-danger'> Inactive</span>";?></td>
                                                                            <?php if(@$_GET['section']=='Active' || @$_GET['section']==''){ ?>
                                                                                <td class="action">
                                                                                    <a href="javascript:;" class="credit ml-1" title="Add Credit"><i class="feather icon-user-plus"></i></a>
                                                                                </td>
                                                                            <?php } ?>
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
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Assigned Credit</th>
                                                                        <th>Remain Credit</th>
                                                                        <th>Status</th>
                                                                        <?php if(@$_GET['section']=='Active' || @$_GET['section']==''){ ?>
                                                                        <th>Action</th>
                                                                        <?php } ?>
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

<div class="modal fade text-left" id="addCreditModal" tabindex="-1" role="dialog" aria-labelledby="addCreditModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Credit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Credit: </label>
                        <div class="form-group" >
                            <input type='number' class="form-control" id="credit"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger add-credit-confirm">Add</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>

<script>
    var curr_row_this_id ='';
    var curr_row_that = '';
    $(document).on('click','.credit',function () {
        curr_row_this_id =$(this).closest('tr').attr('id').replace('data-', '');
        curr_row_that = $(this);
        $('#addCreditModal').modal('show');
    });
    $(document).on('click','.add-credit-confirm',function () {
        var credit = $('#credit').val();
        var curret_credit = $('#assign_credit_'+curr_row_this_id).text();
        var remain_credit = $('#remain_credit_'+curr_row_this_id).text();
        form_data = new FormData();
        form_data.append('credit',credit);
        form_data.append('curr_row_this_id',curr_row_this_id);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl+'ajax_add_credit',
            success: function(obj){
                if(obj.code==1)
                {
                    $(this).remove();
                    toastr.success('Credit has been successfully added.', 'Success!');
                    $('#addCreditModal').modal('hide');
                    $('#credit').val('');
                    $('#assign_credit_'+curr_row_this_id).text(parseInt(curret_credit) + parseInt(credit));
                    $('#remain_credit_'+curr_row_this_id).text(parseInt(remain_credit) + parseInt(credit));
                }
                else if(obj.code==2)
                {
                    toastr.warning(obj.error_msg, 'Warning!');
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
<!--SET ACTIVATED MAIL PASSWORD-->
</html>