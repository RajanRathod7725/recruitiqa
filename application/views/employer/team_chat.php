<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/css/pages/app-chat.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/css/emoji.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern content-left-sidebar chat-application navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="content-left-sidebar">

<?php require_once('includes/topbar.php'); ?>
<?php require_once('includes/sidebar.php'); ?>

<!-- BEGIN: Content-->
<div class="app-content content" id="team_chat">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-area-wrapper">
        <?php if($this->session->userdata('employer_type')== 2){ ?>
        <div class="sidebar-left">
            <div class="sidebar">
                <!-- Chat Sidebar area -->
                <div class="chat-profile-sidebar">
                    <header class="chat-profile-header">
                            <span class="close-icon">
                                <i class="feather icon-x"></i>
                            </span>
                        <div class="header-profile-sidebar">
                            <div class="avatar">
                                <img src="<?php echo check_image($this->session->userdata('employer_photo'),'uploads/employer','size150'); ?>" alt="<?php echo $this->session->userdata('employer_name');?>" height="70" width="70">
                                <!--<span class="avatar-status-online avatar-status-lg"></span>-->
                            </div>
                            <h4 class="chat-user-name"><?php echo $this->session->userdata('employer_name');?></h4>
                        </div>
                    </header>
                    <div class="profile-sidebar-area">
                        <div class="scroll-area">
                            <h6>About</h6>
                            <div class="about-user">
                                <fieldset class="mb-0">
                                    <textarea data-length="120" class="form-control char-textarea" id="employer_about" rows="5" placeholder="About User"><?php echo $this->session->userdata('employer_about');?></textarea>
                                </fieldset>
                                <small class="counter-value float-right"><span class="char-count"><?php echo strlen($this->session->userdata('employer_about'))?></span> / 500 </small>
                            </div>
                            <h6 class="mt-3">Status<?php echo $this->session->userdata('chat_status'); ?></h6>
                            <ul class="list-unstyled user-status mb-0">
                                <li class="pb-50">
                                    <fieldset>
                                        <div class="vs-radio-con vs-radio-success">
                                            <input type="radio" name="userStatus" value="1" class="chat_status" <?php echo $this->session->userdata('chat_status')==1?"checked='checked'":""; ?>>
                                            <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                            <span class="">Active</span>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="pb-50">
                                    <fieldset>
                                        <div class="vs-radio-con vs-radio-danger">
                                            <input type="radio" name="userStatus" value="2" class="chat_status" <?php echo $this->session->userdata('chat_status')==2?"checked='checked'":""; ?>>
                                            <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                            <span class="">Do Not Disturb</span>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="pb-50">
                                    <fieldset>
                                        <div class="vs-radio-con vs-radio-warning">
                                            <input type="radio" name="userStatus" value="3" class="chat_status" <?php echo $this->session->userdata('chat_status')==3?"checked='checked'":""; ?>>
                                            <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                            <span class="">Away</span>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="pb-50">
                                    <fieldset>
                                        <div class="vs-radio-con vs-radio-secondary">
                                            <input type="radio" name="userStatus" value="4" class="chat_status" <?php echo $this->session->userdata('chat_status')==4?"checked='checked'":""; ?>>
                                            <span class="vs-radio">
                                                    <span class="vs-radio--border"></span>
                                                    <span class="vs-radio--circle"></span>
                                                </span>
                                            <span class="">Offline</span>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ User Chat profile area -->
                <div class="sidebar-content card">
                        <span class="sidebar-close-icon">
                            <i class="feather icon-x"></i>
                        </span>
                    <div class="chat-fixed-search">
                        <div class="d-flex align-items-center">
                            <div class="position-relative d-inline-flex sidebar-profile-toggle">
                                <div class="avatar">
                                    <img src="<?php echo check_image($this->session->userdata('employer_photo'),'uploads/employer','size150'); ?>" alt="user_avatar" height="40" width="40">
                                    <!--<span class="avatar-status-online"></span>-->
                                </div>
                                <div class="bullet-success bullet-sm position-absolute"></div>
                            </div>
                            <fieldset class="form-group position-relative has-icon-left mx-1 my-0 w-100">
                                <input type="text" class="form-control round" id="chat-search" placeholder="Search Team Member">
                                <div class="form-control-position">
                                    <i class="feather icon-search"></i>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="users-list" class="chat-user-list list-group position-relative">
                        <!--<h3 class="primary p-1 mb-0">Team Member(s)</h3>-->
                        <ul class="chat-users-list-wrapper media-list">
                            <?php foreach ($teams_chat as $single_user){
                            if($single_user->last_msg!='') {
                                $message = explode('||', $single_user->last_msg);
                            }else{
                                $message=array();
                                //'lalalalal','2020-05-10 11:48:02'
                            }?>
                            <li id="conversion_<?php echo $single_user->conversation_id; ?>" class="<?php echo $teams_chat[0]->team_employer_id==$single_user->team_employer_id?'active':''; ?>">
                                <div class="pr-1">
                                    <span class="avatar m-0 avatar-md"><img class="media-object rounded-circle" src="<?php echo check_image($single_user->employer_photo,'uploads/employer','size150'); ?>" height="42" width="42" alt="<?php echo $single_user->employer_name; ?>">
                                        <i></i>
                                    </span>
                                </div>
                                <div class="user-chat-info">
                                    <div class="contact-info">
                                        <h5 class="font-weight-bold mb-0"><?php echo $single_user->employer_name; ?><?php if($single_user->unread>0){?><span class="badge badge-primary badge-pill ml-1" id="t_budge_<?php echo $single_user->conversation_id; ?>"><?php echo $single_user->unread; ?></span><?php } ?></h5>
                                        <?php if(!empty($message)){?>
                                            <p class="truncate"><?php echo $message[0];?></p>
                                        <?php }else{ ?>
                                            <p class="truncate">Let's begin the conversation!</p>
                                        <?php } ?>
                                    </div>
                                    <?php if(!empty($message)){ ?>
                                    <div class="contact-meta">
                                        <?php if($message[1]!=''){?>
                                        <span class="float-right mb-25"><?php echo $this->common_model->time_ago_in_php($message[1]); ?></span>
                                        <?php } ?>
                                    </div>
                                    <?php }else{?>
                                        <div class="contact-meta">
                                            <span class="float-right mb-25"></span>
                                        </div>
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
                        <!--html append div-->
                        <div class="active-chat">
                            <!--header-->
                            <div class="chat_navbar">
                                <header class="chat_header d-flex justify-content-between align-items-center p-1">
                                    <div class="vs-con-items d-flex align-items-center">
                                        <div class="sidebar-toggle d-block d-lg-none mr-1"><i class="feather icon-menu font-large-1"></i></div>

                                    </div>
                                    <span class="favorite"><i class="feather icon-star font-medium-5"></i></span>
                                </header>
                            </div>
                            <!--data-->
                            <div class="user-chats ps ps--active-y">
                            </div>
                            <!--footer-->
                            <div class="chat-app-form">
                                <form class="chat-app-input d-flex" onsubmit="enter_chat();" action="javascript:void(0);">
                                    <input type="text" class="form-control message mr-1 ml-50" placeholder="Type your message" id="msg_txt_box">
                                    <button type="button" class="btn btn-primary send waves-effect waves-light"><i class="fa fa-paper-plane-o d-lg-none"></i> <span class="d-none d-lg-block">Send</span></button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <?php }else{ ?>
            <input type="hidden" name="conversion_id" value="<?php echo $member_chat->conversation_id; ?>" id="conversion_id">
            <div class="content-right" style="width: 100% !important;">
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
        <?php } ?>
        <!-- User Chat profile right area -->
        <div class="user-profile-sidebar">
            <header class="user-profile-header">
                <span class="close-icon">
                    <i class="feather icon-x"></i>
                </span>
                <div class="header-profile-sidebar">
                    <div class="avatar">
                        <img src="" alt="" height="70" width="70" id="receiver_pic">
                        <!--<span class="avatar-status-busy avatar-status-lg"></span>-->
                    </div>
                    <h4 class="chat-user-name" id="receiver_name"></h4>
                </div>
            </header>
            <div class="user-profile-sidebar-area p-2">
                <h6>About</h6>
                <p id="receiver_about"></p>
                <div id="receiver_status"></div>
            </div>
        </div>
        <!--/ User Chat profile right area -->
        <input type="hidden" name="last_counter" id="last_counter" value=""/>
        <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>

<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/config.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/util.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/jquery.emojiarea.js"></script>
<script src="<?php echo base_url(); ?>resources/app-assets/emoji_master/lib/js/emoji-picker.js"></script>
<!-- END: Page CSS -->

</body>
<script>

    // For chat sidebar on small screen
    if ($(window).width() > 992) {
        if($('.chat-application .chat-overlay').hasClass('show')){
            $('.chat-application .chat-overlay').removeClass('show');
        }
    }
    //1. AUTO CALL
    //if any active li then auto call ajax
    if($(".chat-application .chat-user-list ul li.active").length>0){
        var active_li_id = $(".chat-application .chat-user-list ul li.active").attr('id').replace('conversion_','');
        get_msg(active_li_id,0);
    }


    //2. WHEN CLICK ON ANY CONVERSATION
    //When left side li clicked
    $(".chat-application .chat-user-list ul li").on('click', function(){
        if($('.chat-user-list ul li').hasClass('active')){
            $('.chat-user-list ul li').removeClass('active');
        }
        $(this).addClass("active");
        $(this).find(".badge").text('');

        var conversion_id= $(this).attr('id').replace('conversion_','');
        get_msg(conversion_id,0);
    });
    //3. if teammate is opening then
    if($('#conversion_id').length >0){
        get_msg($('#conversion_id').val(),0);
    }
    function get_msg(conversation_id,offset){
        if(offset==0){
            $('.active-chat').html('');
            $('.active-chat').html('<div class="spinner-border" style="width: 5rem; height: 5rem;top: 50%; left: 70%;position:fixed;" role="status"><span class="sr-only">Loading...</span></div>');
        }
        //ajax call
        form_data = new FormData();
        form_data.append('conversation_id',conversation_id);
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
            url: siteUrl+'ajax_get_msg',
            success: function(obj){
                if(obj.code==1)
                {

                    $('.active-chat').html('');
                    $('.active-chat').html(obj.data_html);
                    $('#last_counter').val(obj.last_serve_counter);
                    $('#csrf_token').val(obj.csrf_token);
                    $('#csrf_name').val(obj.csrf_name);
                    //todo perfect scroll off
                    /*var chat_user = new PerfectScrollbar('.user-chats');*/
                    //$('.user-chats').animate({ scrollTop: $('.user-chats').height() }, 1000);
                    $('.user-chats').scrollTop($('.user-chats')[0].scrollHeight);
                    $('#msg_txt_box').emojiPicker();
                    $('.user-chats').scroll(function(){
                        element = $(this);
                        scrollTop = element.scrollTop();
                        if (scrollTop < 50 && is_load == 1) {
                            var current_msg_count = $('.chat').length;
                            var last_counter = $('#last_counter').val();
                            fatch_old_msg(current_msg_count,conversation_id,last_counter);
                        }
                    });

                    if(obj.totalcounter>0){
                        $('#total_team_counter').text(obj.totalcounter);
                    }else{
                        $('#total_team_counter').text('');
                    }

                    // User Profile sidebar toggle
                    $('.chat-application .user-profile-toggle').on('click',function(){
                        $('.user-profile-sidebar').addClass('show');
                        $('.chat-overlay').addClass('show');
                    });

                    $('#receiver_pic').attr('src',obj.receiver_photo);
                    $('#receiver_pic').attr('alt',obj.receiver_name);
                    $('#receiver_name').text(obj.receiver_name);
                    $('#receiver_about').text(obj.receiver_employer_about);
                    $('#receiver_status').html(obj.receiver_html);

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
        if($('#conversion_id').length>0){
            var conversation_id= $('#conversion_id').val();
        }else{
            var conversation_id = $(".chat-application .chat-user-list ul li.active").attr('id').replace('conversion_','');
        }

        //ajax call
        form_data = new FormData();
        form_data.append('conversation_id',conversation_id);
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
            url: siteUrl+'ajax_send_msg',
            success: function(obj){
                if(obj.code==1)
                {
                    $('#msg_txt_box').val('');
                    $('.emoji-wysiwyg-editor').empty();
                    $('.chats').append(obj.msg_html);
                    $('#conversion_'+conversation_id+' .truncate').text(message);
                    $('#conversion_'+conversation_id+' .contact-meta').html('');
                    $('#conversion_'+conversation_id+' .contact-meta').html('<span class="float-right mb-25">'+obj.msgtime+'</span>');
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
    $(document).on('focusout','#employer_about',function () {
        set_chat_data($('#employer_about').val(),$('.chat_status:checked').val());
    });
    $(document).on('click','.chat_status',function () {
        set_chat_data($('#employer_about').val(),$('.chat_status:checked').val());
    });

    function set_chat_data(about_txt,chat_status){

        form_data = new FormData();
        form_data.append('employer_about',about_txt);
        form_data.append('chat_status',chat_status);
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
            url: siteUrl+'ajax_up_about_status',
            success: function(obj){
                if(obj.code==1)
                {

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
<!-- END: Body-->
</html>