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
                        <a href="<?php echo $module_base_url; ?>" class="btn-icon btn btn-primary"><i class="feather icon-arrow-left"></i>Back</a>
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
                                    <div class="tab-pane fade active show">
                                        <div class="tab-pane" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                            <form class="form-horizontal" id="admin_frm" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
                                                <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_title">Mail Title</label>
                                                                <input type="text" class="form-control" id="mail_title" name="mail_title" required="" placeholder="Mail Title" data-validation-required-message="This Mail Title field is required" value="<?php echo set_value('mail_title',@$this->form_data->mail_title);?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_sender">Sender Mail</label>
                                                                <input type="text" name="mail_sender" id="mail_sender" class="form-control" placeholder="Sender Mail" required="" data-validation-required-message="The Sender Mail field is required" value="<?php echo set_value('mail_sender',@$this->form_data->mail_sender);?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_subject">Mail Subject</label>
                                                                <input type="text" name="mail_subject" id="mail_subject" class="form-control" placeholder="Mail Subject" required="" data-validation-required-message="The Mail Subject field is required" value="<?php echo set_value('mail_subject',@$this->form_data->mail_subject);?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_receiver">Receiver Mail</label>
                                                                <input type="text" name="mail_receiver" id="mail_receiver" class="form-control" placeholder="Receiver Mail" required="" data-validation-required-message="The Receiver Mail field is required" value="<?php echo set_value('mail_receiver',@$this->form_data->mail_receiver);?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_slug">Slug</label>
                                                                <input type="text" name="mail_slug" id="mail_slug" class="form-control" placeholder="Slug" required="" data-validation-required-message="The Slug field is required" value="<?php echo set_value('mail_slug',@$this->form_data->mail_slug);?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_from_text">From Text</label>
                                                                <input type="text" name="mail_from_text" id="mail_from_text" class="form-control" placeholder="From Text" required="" data-validation-required-message="The From Text field is required" value="<?php echo set_value('mail_from_text',@$this->form_data->mail_from_text);?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label for="mail_content">Content</label>
                                                                <textarea id="editor1" name="mail_content" required=""><?php echo set_value('mail_content',@$this->form_data->mail_content);?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                        <?php if($method=='Add'){ ?>
                                                        <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Submit</button>
                                                        <?php }else{ ?>
                                                        <button type="submit" class="btn btn-danger mr-sm-1 mb-1 mb-sm-0 waves-effect waves-light">Save changes</button>
                                                        <?php } ?>
                                                        <button type="reset" class="btn btn-outline-warning waves-effect waves-light">Cancel</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                                                <input type="hidden" name="method" value="<?php echo $method;?>"/>

                                            </form>
                                        </div>
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
<!-- BEGIN: Custom CSS-->

<script type="text/javascript" src='https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js'></script>
<script type="text/javascript">

jQuery(document).ready(function() {

    /*with html*/
    CKEDITOR.replace('editor1', {
        fullPage: true,
        allowedContent: true,
        autoGrow_onStartup: true,
        enterMode: CKEDITOR.ENTER_BR,
        height: '400px',
    });
});


</script>

<!-- END: Custom CSS-->
</body>

</html>