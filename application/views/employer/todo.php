<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/extensions/jquery.contextMenu.min.css">

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
                                            <div class="col-md-12">
                                                <div class="task-board">
                                                    <!--Main logic-->
                                                    <?php if(!empty($list_records)){
                                                            foreach ($list_records as $todo_status){
                                                        ?>

                                                        <div class="status-card" id="status_<?php echo $todo_status->todo_status_id; ?>">
                                                            <div class="card-header p-0 todo-header">
                                                                <div class="row w-100">
                                                                    <div class="col-md-10">
                                                                        <span class="card-header-text font-medium-2" id="col_name_<?php echo $todo_status->todo_status_id; ?>"><?php echo $todo_status->title; ?></span>
                                                                    </div>
                                                                    <div class="col-md-2 pr-0" style="text-align:right;">
                                                                        <a href="javascript:;" class="status_options font-medium-2" id="status_menu_<?php echo $todo_status->todo_status_id; ?>"><i class="feather
icon-more-horizontal"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <ul class="sortable ui-sortable contact-ul" id="todo_status_<?php echo $todo_status->todo_status_id; ?>" data-todo-status-id="<?php echo $todo_status->todo_status_id; ?>">
                                                                <?php
                                                                $where_array=array('todo.status !='=>'3','todo.todo_status_id'=>$todo_status->todo_status_id);
                                                                $select_value= 'todo.*';
                                                                $tasks = $this->database_model->get_all_records('todo',$select_value,$where_array,'todo.todo_id','ASC','')->result();
                                                                foreach ($tasks as $task){ ?>
                                                                    <li class="text-row ui-sortable-handle" data-todo-id="<?php echo $task->todo_id; ?>" id="todo_<?php echo $task->todo_id; ?>">
                                                                        <div class="row">
                                                                            <div class="col-md-10 pr-0">
                                                                                <span class="font-medium-1" id="todo_task_<?php echo $task->todo_id; ?>"><?php if($task->todo_status=='1'){echo '<i class="fa fa-check-circle font-medium-3 text-success"></i>'; } ?> <?php echo $task->description; ?></span>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <a href="javascript:;" class="task_options font-medium-2" id="sub_menu_<?php echo $task->todo_id; ?>"><i class="feather
icon-more-horizontal"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                            <div class="card-footer p-0">
                                                                <form class="add-task-frm w-100 p-1">
                                                                    <a href="javascript:;" class="open-add-frm-task btn-icon btn waves-effect waves-light font-medium-3 w-100"><i class="feather icon-plus-square font-medium-4"></i> Add Task</a>
                                                                    <input class="form-control mb-1 list-input-task" type="text" name="name" placeholder="Enter task title..." autocomplete="off">
                                                                    <div class="list-add-control-task ">
                                                                        <button class="save_list_task btn-icon btn btn-success waves-effect waves-light">Add task</button>
                                                                        <a class="cancel_list_task" href="javascript:;"><i class="feather icon-x-square font-large-2"></i></a>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="status-card">
                                                            <div class="card-header p-0">
                                                                <form class="add-column-frm w-100 p-1">
                                                                    <a href="javascript:;" class="open-add-frm btn-icon btn waves-effect waves-light font-medium-3 w-100"><i class="feather icon-plus-square font-medium-4"></i> Add Column</a>
                                                                    <input class="form-control mb-1 list-input" type="text" name="name" placeholder="Enter list title..." autocomplete="off">
                                                                    <div class="list-add-control ">
                                                                        <button class="save_list btn-icon btn btn-success waves-effect waves-light">Add List</button>
                                                                        <a class="cancel_list" href="javascript:;"><i class="feather icon-x-square font-large-2"></i></a>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    <?php }else{?>
                                                        <div class="status-card">
                                                            <div class="card-header p-0">
                                                                <form class="add-column-frm w-100 p-1">
                                                                    <a href="javascript:;" class="open-add-frm btn-icon btn waves-effect waves-light font-medium-3 w-100"><i class="feather icon-plus-square font-medium-4"></i> Add Column</a>
                                                                    <input class="form-control mb-1 list-input" type="text" name="name" placeholder="Enter list title..." autocomplete="off">
                                                                    <div class="list-add-control ">
                                                                        <button class="save_list btn-icon btn btn-success waves-effect waves-light">Add List</button>
                                                                        <a class="cancel_list" href="javascript:;"><i class="feather icon-x-square font-large-2"></i></a>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    <?php }?>
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
            <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/extensions/jquery.contextMenu.min.js"></script>
<script src="<?php echo base_url(); ?>/resources/app-assets/vendors/js/extensions/jquery.ui.position.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

    $(document).on('click','.open-add-frm',function () {
        $(this).hide();
        $('.list-input').show();
        $('.list-add-control').show();
    });
    $(document).on('click','.cancel_list',function () {
        $('.list-input').hide();
        $('.list-input').focus();
        $('.list-add-control').hide();
        $('.open-add-frm').show();
        $(this).closest('form').find('.list-input').val('');
    });
    $(document).on('click','.save_list',function (e) {
        e.preventDefault();
        var title = $(this).closest('form').find('.list-input').val();

        if(title==''){
            $(this).closest('form').find('.list-input').focus();
        }else{
            form_data = new FormData();
            form_data.append('title', title);
            form_data.append('csrf_token', $('#csrf_token').val());
            form_data.append('csrf_name', $('#csrf_name').val());
            $.ajax({
                dataType: 'json',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                url: siteUrl + 'ajax_add_todo_status',
                success: function (obj) {
                    if (obj.code == 1) {
                        toastr.success('Todo List has been successfully added!', 'Success!');
                        $('.task-board .status-card:last-child').remove();
                        $('.task-board').append(obj.status_html);
                        $(document).find('ul[id^="todo_status"]').sortable({
                            connectWith: ".sortable",
                            receive: function (e, ui) {
                                var todo_status_id = $(ui.item).parent(".sortable").data("todo-status-id");
                                var todo_id = $(ui.item).data("todo-id");
                                move_task(todo_status_id,todo_id);
                            }

                        }).disableSelection();
                    }
                    else {
                        toastr.error(obj.error, 'Error!');
                    }
                },
                error: function (obj) {
                    errormsg(csrf_error);
                },
                complete: function (obj) {
                    bulkRowThat = ''
                    obj = obj.responseJSON;
                    $('#csrf_token').val(obj.csrf_token);
                    $('#csrf_name').val(obj.csrf_name);
                },
            });
        }
    });
    $(document).on('click','.open-add-frm-task',function () {
        $(this).hide();
        $(this).closest('.add-task-frm').find('.list-input-task').show();
        $(this).closest('.add-task-frm').find('.list-input-task').focus();
        $(this).closest('.add-task-frm').find('.list-add-control-task').show();
    });
    $(document).on('click','.cancel_list_task',function () {
        $(this).closest('.add-task-frm').find('.list-input-task').hide();
        $(this).closest('.add-task-frm').find('.list-add-control-task').hide();
        $(this).closest('.add-task-frm').find('.open-add-frm-task').show();
        $(this).closest('.add-task-frm').find(this).closest('form').find('.list-input-task').val('');
    });

    $(document).on('click','.save_list_task',function (e) {
        e.preventDefault();
        var title = $(this).closest('form').find('.list-input-task').val();
        var that = $(this);
        if(title==''){
            $(this).closest('form').find('.list-input-task').focus();
        }else{
            var status_id = $(this).closest('.status-card').attr('id').replace('status_','');
            form_data = new FormData();
            form_data.append('title', title);
            form_data.append('status_id', status_id);
            form_data.append('csrf_token', $('#csrf_token').val());
            form_data.append('csrf_name', $('#csrf_name').val());
            $.ajax({
                dataType: 'json',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                url: siteUrl + 'ajax_add_todo_task',
                success: function (obj) {
                    if (obj.code == 1) {
                        toastr.success('Task has been successfully added!', 'Success!');
                        $('#todo_status_'+status_id).append(obj.task_html);
                        that.closest('.add-task-frm').find('.list-input-task').hide();
                        that.closest('.add-task-frm').find('.list-add-control-task').hide();
                        that.closest('.add-task-frm').find('.open-add-frm-task').show();
                        that.closest('.add-task-frm').find('.list-input-task').val('');
                        $(document).find('ul[id^="todo_status"]').sortable({
                            connectWith: ".sortable",
                            receive: function (e, ui) {
                                var todo_status_id = $(ui.item).parent(".sortable").data("todo-status-id");
                                var todo_id = $(ui.item).data("todo-id");
                                move_task(todo_status_id,todo_id);
                            }

                        }).disableSelection();
                    }
                    else {
                        toastr.error(obj.error, 'Error!');
                    }
                },
                error: function (obj) {
                    errormsg(csrf_error);
                },
                complete: function (obj) {
                    bulkRowThat = ''
                    obj = obj.responseJSON;
                    $('#csrf_token').val(obj.csrf_token);
                    $('#csrf_name').val(obj.csrf_name);
                },
            });
        }
    });


    $('ul[id^="todo_status"]').sortable({
        connectWith: ".sortable",
        receive: function (e, ui) {
            var todo_status_id = $(ui.item).parent(".sortable").data("todo-status-id");
            var todo_id = $(ui.item).data("todo-id");
            move_task(todo_status_id,todo_id);
        }

    }).disableSelection();

    function move_task(column,task) {
        console.log(column+'-------'+task);
        form_data = new FormData();
        form_data.append('column', column);
        form_data.append('task', task);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_move_task',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success('Task has been successfully moved!', 'Success!');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    }

    $.contextMenu({
        selector: '.task_options',
        trigger: "left",
        callback: function (key, options) {
            var todo_id = $(this).attr('id').replace('sub_menu_','');
            var todo_txt = $('#todo_task_'+todo_id).text();
            task_option(todo_id,key,options,todo_txt);
        },
        items: {
            "Edit": { name: "Edit" },
            "Delete": { name: "Delete" },
        }
    });
    function task_option(todo_id,key,options,todo_txt) {
        if(key == 'Edit'){
            $('#todo_id').val(todo_id);
            $('#task_name').val(todo_txt);
            $('#updatetaskModal').modal('show');
        }
        if(key == 'Delete'){
            $('#delete_todo').val(todo_id);
            $('#deletetaskModal').modal('show');
        }

    }
    //update the task
    $(document).on('click','.edit-task-btn',function () {
        var task = $('#task_name').val();
        var todo_id = $('#todo_id').val();
        form_data = new FormData();
        form_data.append('todo_id', todo_id);
        form_data.append('todo_status', '');
        form_data.append('status', '');
        form_data.append('description',task );
        form_data.append('iscolumn',0 );
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_task_action',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#todo_'+todo_id).find('span').text(task);
                    toastr.success('Task has been modified successfully!', 'Success!');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
                $('#deletetaskModal').modal('hide');
            },
        });
    });

    $(document).on('click','.delete-task-confirm-btn',function () {
        var todo_id = $('#delete_todo').val();
        form_data = new FormData();
        form_data.append('todo_id', todo_id);
        form_data.append('todo_status', '');
        form_data.append('status', '3');
        form_data.append('description', '');
        form_data.append('iscolumn',0);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_task_action',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#todo_'+todo_id).remove();
                    toastr.success('Task has been deleted successfully!', 'Success!');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
                $('#deletetaskModal').modal('hide');
            },
        });
    });

    /*FOR COLUMN */
    $.contextMenu({
        selector: '.status_options',
        trigger: "left",
        callback: function (key, options) {
            var status_id = $(this).attr('id').replace('status_menu_','');
            var status_txt = $('#col_name_'+status_id).text();
            status_option(status_id,key,options,status_txt);
        },
        items: {
            "Edit": { name: "Edit" },
            "Delete": { name: "Delete" },
        }
    });
    function status_option(status_id,key,options,status_txt) {
        /*console.log(status_id);
        console.log(key);
        console.log(options);
        console.log(status_txt);
        return false;*/
        if(key == 'Edit'){
            $('#status_id').val(status_id);
            $('#status_name').val(status_txt);
            $('#updateStatusModal').modal('show');
        }
        if(key == 'Delete'){
            $('#delete_status').val(status_id);
            $('#deleteStatusModal').modal('show');
        }

    }
    //update the status
    $(document).on('click','.edit-status-btn',function () {
        var task = $('#status_name').val();
        var todo_id = $('#status_id').val();
        form_data = new FormData();
        form_data.append('todo_id', todo_id);
        form_data.append('todo_status', '');
        form_data.append('status', '');
        form_data.append('description',task );
        form_data.append('iscolumn',1 );
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_task_action',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#col_name_'+todo_id).text(task);
                    toastr.success('Column Name has been modified successfully!', 'Success!');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
                $('#deletetaskModal').modal('hide');
            },
        });
    });

    $(document).on('click','.delete-status-confirm-btn',function () {
        var status_id = $('#delete_status').val();
        form_data = new FormData();
        form_data.append('todo_id', status_id);
        form_data.append('todo_status', '');
        form_data.append('status', '3');
        form_data.append('description', '');
        form_data.append('iscolumn',1 );
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_task_action',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#status_'+status_id).remove();
                    toastr.success('Column has been deleted successfully!', 'Success!');
                }
                else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                bulkRowThat = ''
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
                $('#deletetaskModal').modal('hide');
            },
        });
    });
</script>
</body>
<div class="modal fade text-left" id="deletetaskModal" tabindex="-1" role="dialog" aria-labelledby="deletetaskModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Just want to confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want this column to be deleted? It'll be gone completely.</h5>
                <input type="hidden" name="delete_todo" id="delete_todo" value="">
                <!--<p>Oat </p>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-task-confirm-btn" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="updatetaskModal" tabindex="-1" role="dialog" aria-labelledby="updatetaskModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Edit Task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#">
                    <div class="modal-body">
                        <label>Task: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Task" class="form-control" id="task_name">
                            <input type="hidden" name="todo_id" id="todo_id" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger edit-task-btn" data-dismiss="modal">Update</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--FOR COLUMN-->

<div class="modal fade text-left" id="deleteStatusModal" tabindex="-1" role="dialog" aria-labelledby="deleteStatusModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Just want to confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want this column to be deleted? It'll be gone completely.</h5>
                <input type="hidden" name="delete_status" id="delete_status" value="">
                <!--<p>Oat </p>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-status-confirm-btn" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="">Edit Column</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#">
                    <div class="modal-body">
                        <label>Column Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Column Name" class="form-control" id="status_name">
                            <input type="hidden" name="status_id" id="status_id" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger edit-status-btn" data-dismiss="modal">Update</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

</html>