<?php if(@$error_msg!=''){?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="text-danger"><i class="feather
icon-alert-triangle mr-1 align-middle"></i>Error !</h5>
        <p class="mb-0">
            <?php echo $error_msg;?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php } ?>
<?php if(@$information_msg!=''){?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h5 class="text-info"><i class="feather icon-alert-circle mr-1 align-middle"></i>Note:</h5>
        <p class="mb-0">
        <?php echo $information_msg;?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php } ?>
<?php if(@$success_msg!=''){?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h5 class="text-success"><i class="feather icon-check mr-1 align-middle"></i>Success !</h5>
        <p class="mb-0">
        <?php echo $success_msg;?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php }?>
<?php if(@$warning_msg!=''){?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="text-warning"><i class="feather icon-alert-octagon mr-1 align-middle"></i>Warning !</h5>
        <p class="mb-0">
        <?php echo $warning_msg;?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php } ?>

<!--session messages-->
<?php if($this->session->flashdata('notification')){?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h5 class="text-success"><i class="feather icon-check mr-1 align-middle"></i>Success !</h5>
        <p class="mb-0">
            <?php echo $this->session->flashdata('notification');?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php }
if($this->session->flashdata('warning')){?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="text-warning"><i class="feather icon-alert-octagon mr-1 align-middle"></i>Warning !</h5>
        <p class="mb-0">
            <?php echo $this->session->flashdata('warning');?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php }?>
<?php if($this->session->flashdata('error')){?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="text-danger"><i class="feather
icon-alert-triangle mr-1 align-middle"></i>Error !</h5>
        <p class="mb-0">
            <?php echo $this->session->flashdata('error');?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php } ?>

<?php
if(!validation_errors()){
	if(@$csrf_error!=''){?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="text-danger"><i class="feather
icon-alert-triangle mr-1 align-middle"></i>Error !</h5>
            <p class="mb-0">
                <?php echo $csrf_error; ?>
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
            </button>
        </div>
	<?php }
}?>
<?php
if(validation_errors()){ ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="text-danger"><i class="feather
icon-alert-triangle mr-1 align-middle"></i>Following error(s) need your attention:</h5>
        <p class="mb-0">
            <?php echo validation_errors(); ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
	<?php
}
if(@$upload_error['error']!='') { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="text-danger"><i class="feather
icon-alert-triangle mr-1 align-middle"></i>Following error(s) need your attention:</h5>
        <p class="mb-0">
            <?php echo @$upload_error['error']; ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php } ?>
