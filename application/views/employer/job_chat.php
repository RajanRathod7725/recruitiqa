<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/css/pages/app-chat.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/css/emoji.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- END: Page CSS-->
</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern content-left-sidebar chat-application navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="content-left-sidebar">

<?php require_once('includes/topbar.php'); ?>
<?php require_once('includes/sidebar.php'); ?>

<!-- BEGIN: Content-->
<div class="app-content content" id="job_chat">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-area-wrapper">
        <div class="sidebar-left">
            <div class="sidebar">
                <!-- Chat Sidebar area -->
                <div class="sidebar-content card">
                        <span class="sidebar-close-icon">
                            <i class="feather icon-x"></i>
                        </span>
                    <div class="chat-fixed-search">
                        <div class="d-flex align-items-center">
                            <fieldset class="form-group position-relative has-icon-left mx-1 my-0 w-100">
                                <input type="text" class="form-control round" id="chat-search" placeholder="Search Job">
                                <div class="form-control-position">
                                    <i class="feather icon-search"></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="job-list" class="chat-user-list list-group position-relative">
                        <!--<h3 class="primary p-1 mb-0">Team Member(s)</h3>-->
                        <ul class="chat-users-list-wrapper media-list">
                            <?php foreach ($jobs_chat as $single_job){
                                if($single_job->last_msg!='') {
                                    $message = explode('||', $single_job->last_msg);
                                }else{
                                    $message=array();
                                }?>
                            <li id="job_<?php echo $single_job->job_id; ?>" class="<?php echo ($single_job->job_status==2 || $single_job->job_status==3)?'disable ':''; ?><?php echo $jobs_chat[0]->job_id==$single_job->job_id?'active':''; ?> ">
                                <div class="user-chat-info">
                                    <div class="contact-info">
                                        <h5 class="font-weight-bold mb-0"><?php echo $single_job->job_title; ?><span class="badge badge-primary badge-pill ml-1 badge-counter" id="j_budge_<?php echo $single_job->job_id; ?>"><?php echo $single_job->unread_counter>0?$single_job->unread_counter:'';?></span><?php if($single_job->job_status==2){?><span class="badge badge-warning badge-pill ml-1 font-small-1">Paused</span><?php } if($single_job->job_status==3){?><span class="badge badge-danger badge-pill ml-1 font-small-1">Closed</span><?php } ?></h5>
                                        <?php if($single_job->last_msg!='') {
                                            if($message[2]==1){
                                                $last_user="You";
                                            }else{
                                                $last_user = $this->database_model->get_all_records('recruiter','recruiter_name',array('recruiter_id'=>$message[3]),'recruiter_id ASC',1)->row()->recruiter_name;
                                            }
                                            ?>

                                            <p class="truncate"><?php echo '<b>'.$last_user.': </b>'.$message[0] ?></p>
                                        <?php } else{?>
                                            <p class="truncate">Let's begin the conversation!</p>
                                        <?php } ?>
                                    </div>
                                    <?php if($message[1]!=''){?>
                                        <span class="float-right mb-25"><?php echo $this->common_model->time_ago_in_php($message[1]); ?></span>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!--/ Chat Sidebar area -->
            </div>
        </div>
        <div class="content-right">
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="chat-overlay"></div>
                    <section class="chat-app-window">

                        <div class="active-chat">

                        </div>
                    </section>
                </div>
            </div>
        </div>
        <input type="hidden" name="last_counter" id="last_counter" value=""/>
        <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>.
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/config.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/util.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/jquery.emojiarea.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/emoji-picker.js"></script>
</body>
<script>

    //1. AUTO CALL
    //if any active li then auto call ajax
    if($(".chat-application .chat-user-list ul li.active").length>0){
        var active_li_id = $(".chat-application .chat-user-list ul li.active").attr('id').replace('job_','');
        get_job_msg(active_li_id,0);
    }


    //2. WHEN CLICK ON ANY CONVERSATION
    //When left side li clicked
    $(".chat-application .chat-user-list ul li").on('click', function(){
        if($('.chat-user-list ul li').hasClass('active')){
            $('.chat-user-list ul li').removeClass('active');
        }
        $(this).addClass("active");
        $(this).find(".badge-counter").text('');

        var job_id= $(this).attr('id').replace('job_','');
        get_job_msg(job_id,0);
    });

    function get_job_msg(job_id,offset){
        if(offset==0){
            $('.active-chat').html('');
            $('.active-chat').html('<div class="spinner-border" style="width: 5rem; height: 5rem;top: 50%; left: 70%;position:fixed;" role="status"><span class="sr-only">Loading...</span></div>');
        }
        //ajax call
        form_data = new FormData();
        form_data.append('job_id',job_id);
        form_data.append('offset',offset);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            async: false,
            url: siteUrl+'ajax_get_job_msg',
            success: function(obj){
                if(obj.code==1)
                {
                    $('.active-chat').html('');
                    $('.active-chat').html(obj.data_html);
                    if(obj.msgtime !=null){
                        $('#job_'+job_id+' .contact-meta').html('<span class="float-right mb-25">'+obj.msgtime+'</span>');
                    }else{
                        $('#job_'+job_id+' .contact-meta').html('<span class="float-right mb-25"></span>');
                    }
                    $('#last_counter').val(obj.last_serve_counter);
                    $('.user-chats').scrollTop($('.user-chats')[0].scrollHeight);
                    $('.user-chats').scroll(function(){
                        element = $(this);
                        scrollTop = element.scrollTop();
                        if (scrollTop < 50 && is_load == 1) {
                            var current_msg_count = $('.chat').length;
                            var last_counter = $('#last_counter').val();
                            fatch_old_job_msg(current_msg_count,job_id,last_counter);
                        }
                    });
                    if(obj.current_job_counter>0){
                        $('#j_budge_'+job_id).text(obj.current_job_counter);
                    }else{
                        $('#j_budge_'+job_id).text('');
                    }
                    if(obj.total_job_counter>0){
                        $('#total_job_counter').text(obj.total_job_counter);
                    }else{
                        $('#total_job_counter').text('');
                    }
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
                obj=obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    }

    //save message
    $(document).on('click','.msg-send',function () {
        var message = $('#msg_txt_box').val();
        if(message==''){
            $('#msg_txt_box').focus();
            return false;
        }
        var job_id = $(".chat-application .chat-user-list ul li.active").attr('id').replace('job_','');
        //ajax call
        form_data = new FormData();
        form_data.append('job_id',job_id);
        form_data.append('message',message);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            async: false,
            url: siteUrl+'ajax_send_job_msg',
            success: function(obj){
                if(obj.code==1)
                {
                    $('#msg_txt_box').val('');
                    $('.emoji-wysiwyg-editor').empty();
                    $('.chats').append(obj.msg_html);
                    $('#job_'+job_id+' .truncate').html("<b>You: </b>"+message);
                    $('#job_'+job_id+' .contact-meta').html('');
                    $('#job_'+job_id+' .contact-meta').html('<span class="float-right mb-25">'+obj.msgtime+'</span>');
                    $('.user-chats').animate({ scrollTop: $('.user-chats').prop("scrollHeight")}, 1000);
                    if (obj.last_msg_id != null){
                        $('#last_msg_id').val(obj.last_msg_id);
                    }
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
                obj=obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    });
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/img/',
        popupButtonClasses: 'fa fa-smile-o'
    });
    // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
    // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
    // It can be called as many times as necessary; previously converted input fields will not be converted again
    window.emojiPicker.discover();
</script>
</html>