<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/css/pages/data-list-view.css">

    <!-- Full Calendar -->
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/resources/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <!-- END: Vendor CSS-->

    <link href='<?php echo base_url(); ?>/vendor/fullcalendar/core/main.css' rel='stylesheet' />
    <link href='<?php echo base_url(); ?>/vendor/fullcalendar/bootstrap/main.css' rel='stylesheet' />
    <link href='<?php echo base_url(); ?>/vendor/fullcalendar/timegrid/main.css' rel='stylesheet' />
    <link href='<?php echo base_url(); ?>/vendor/fullcalendar/daygrid/main.css' rel='stylesheet' />
    <link href='<?php echo base_url(); ?>/vendor/fullcalendar/list/main.css' rel='stylesheet' />
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/core/main.js'></script>
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/interaction/main.js'></script>
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/bootstrap/main.js'></script>
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/daygrid/main.js'></script>
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/timegrid/main.js'></script>
    <script src='<?php echo base_url(); ?>/vendor/fullcalendar/list/main.js'></script>
    <script src='<?php echo base_url(); ?>resources/src/js/scripts/fullcalendar-theme-chooser.js'></script>

    <script>

      document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');
        var calendar;

         $.ajax({

            url: siteUrl + 'ajax_get_events',
            type: 'POST',
            dataType: 'json', 
            cache: false,
            contentType: false,
            processData: false,
            success: function (obj) {
    
         initThemeChooser({

          init: function(themeSystem) {
            calendar = new FullCalendar.Calendar(calendarEl, {
              plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
              themeSystem: themeSystem,
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
              },
              defaultDate: obj.defaultDate,
              weekNumbers: true,
              navLinks: true, // can click day/week names to navigate views
              editable: true,
              eventLimit: true, // allow "more" link when too many events
              events: obj.events,
              eventClick: function(e) {

                $.ajax({

                    url: siteUrl + 'ajax_get_single_event',
                    type: 'POST',
                    dataType: 'json', 
                    data : { event_id : e.event.id },
                    success: function (obj) {

                        // console.log(obj.event);
                        // console.log(obj.event.event_id);

                        $('#update_event_id').val(obj.event.event_id);

                        $('#cal_event_name').val(obj.event.event_name);
                        $('#cal_event_description').val(obj.event.event_description);
                        if(obj.event.is_full_day_event == "1"){
                            $('#cal_event_full_day').prop('checked',true);
                        }else{
                            $('#cal_event_full_day').prop('checked',false);
                        }
                        $('#cal_event_start_date').val(obj.event.start_date);
                        $('#cal_event_start_time').val(obj.event.start_time);
                        if(obj.event.end_date != ""){
                            $('#cal_event_end_date').val(obj.event.end_date);
                        }
                        $('#cal_event_end_time').val(obj.event.end_time);
                        if(obj.event.reminder_date != ""){
                            $('#cal_event_reminder_date').val(obj.event.reminder_date);
                        }
                        $('#cal_event_reminder_time').val(obj.event.reminder_time);
                        $('#cal_event_url').val(obj.event.url);

                        $('#addEventModalHeading').text('Update Event');
                        $('#addEventModalFooter').html('<button type="button" class="btn btn-danger" data-dismiss="modal" id="update_event">Update</button>');
                        $("#addEventModal").modal("show");

                    }
                
                });
                

              },
              eventDrop: function(e){
                console.log(e);
              }
            });
            calendar.render();
          },

          change: function(themeSystem) {
            calendar.setOption('themeSystem', themeSystem);
          }

        });
            }

        });

        

      });

    </script>
    <style>

      body {
        margin: 0;
        padding: 0;
        font-size: 14px;
      }

      #top,
      #calendar.fc-unthemed {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      }

      #top {
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        font-size: 12px;
        color: #000;
      }

      #top .selector {
        display: inline-block;
        margin-right: 10px;
      }

      #top select {
        font: inherit; /* mock what Boostrap does, don't compete  */
      }

      .left { float: left }
      .right { float: right }
      .clear { clear: both }

      #calendar {
        /*max-width: 900px;*/
        margin: 40px auto;
        padding: 0 10px;
      }

    </style>

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
        <div class="content-body">

            <!-- Data list view starts -->
            <section id="data-list-view" class="data-list-view-header">

                <div class="col-md-12">
                    <button class="btn btn-sm pull-left btn-outline-primary block" data-toggle="modal" data-target="#addEventModal" >+ Add New Event</span></button>
                    <?php include_once(APPPATH."/views/admin/includes/display_msg.php"); ?>
                </div>
<!-- 
                <div class="col-md-12">
                </div>
                 -->

                <div class="col-md-12">
                    <div id='calendar'></div>
                </div>

             
            </section>
            <!-- Data list view end -->
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<?php require_once('includes/footer.php'); ?>
<?php require_once('includes/footerscripts.php'); ?>

<!-- Modal : Add Event Form -->
<div class="modal fade text-left" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalHeading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document" style="max-width: 1000px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addEventModalHeading">Add New Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" style="font-size: 15px;">Event Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cal_event_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" style="font-size: 15px;">Description</label>
                            <textarea type="text" class="form-control" id="cal_event_description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <input type="checkbox" id="cal_event_full_day">
                            <label for="cal_event_full_day" style="font-size: 15px;">Full Day Event</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="cal_event_start_date" style="font-size: 15px;">Start Date <span class="text-danger">*</span></label>
                            <input type='text' id="cal_event_start_date" class="form-control pickadate"/>
                        </div>
                        <div class="col-md-3 full-day-event-block">
                            <label for="cal_event_start_time" style="font-size: 15px;">Start Time</label>
                            <input type="text" class="form-control pickatime-custom" id="cal_event_start_time">
                        </div>
                        <div class="col-md-3 full-day-event-block">
                            <label for="cal_event_end_date" style="font-size: 15px;">End Date</label>
                            <input type='text' id="cal_event_end_date" class="form-control pickadate"/>
                        </div>
                        <div class="col-md-3 full-day-event-block">
                            <label for="cal_event_end_time" style="font-size: 15px;">End Time</label>
                            <input type="text" class="form-control pickatime-custom" id="cal_event_end_time">
                        </div>
                        <div class="col-md-3">
                            <label for="cal_event_reminder_date" style="font-size: 15px;">Reminder Date</label>
                            <input type='text' id="cal_event_reminder_date" class="form-control pickadate"/>
                        </div>
                        <div class="col-md-3">
                            <label for="cal_event_reminder_time" style="font-size: 15px;">Reminder Time</label>
                            <input type="text" class="form-control pickatime-custom" id="cal_event_reminder_time">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="cal_event_url" style="font-size: 15px;">URL</label>
                            <input type="text" class="form-control" id="cal_event_url">
                        </div>
                    </div>
                    <input type="text" id="update_event_id">

                </div>
            </div>
            <div class="modal-footer" id="addEventModalFooter">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="save_event">Save</button>
            </div>
        </div>
    </div>
</div>

    <!-- BEGIN: Date Picker -->
    <script src="<?php echo base_url(); ?>resources/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo base_url(); ?>resources/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo base_url(); ?>resources/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="<?php echo base_url(); ?>resources/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    
    <script src="<?php echo base_url(); ?>resources/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>
    <!-- END: Date Picker-->

    <script>
        
        $('#cal_event_full_day').on('change', function(){

            if($(this).prop('checked')){
                $('.full-day-event-block').hide();
            }else{
                $('.full-day-event-block').show();
            }

        });

        $('#save_event').on('click', function(){

            if($('#cal_event_full_day').prop('checked')){
                var cal_event_full_day = 1;
            }else{
                var cal_event_full_day = 0;
            }
            var action = "insert";

            var form_data = {
                action : action,
                cal_event_name : $('#cal_event_name').val(),
                cal_event_description : $('#cal_event_description').val(),
                cal_event_start_date : $('#cal_event_start_date').val(),
                cal_event_start_time : $('#cal_event_start_time').val(),
                cal_event_end_date : $('#cal_event_end_date').val(),
                cal_event_end_time : $('#cal_event_end_time').val(),
                cal_event_full_day : cal_event_full_day,
                cal_event_url : $('#cal_event_url').val(),
                cal_event_reminder_date : $('#cal_event_reminder_date').val(),
                cal_event_reminder_time : $('#cal_event_reminder_time').val()
            };

            $.ajax({

                url: siteUrl + 'ajax_save_event',
                type: 'POST',
                dataType: 'json', 
                data : { form_data },
                success: function (data) {

                    $('#cal_event_name').val('');
                    $('#cal_event_description').val('');
                    $('#cal_event_start_date').val('');
                    $('#cal_event_start_time').val('');
                    $('#cal_event_end_date').val('');
                    $('#cal_event_end_time').val('');
                    $('#cal_event_url').val('');
                    $('#cal_event_reminder_date').val('');
                    $('#cal_event_reminder_time').val('');

                    location.reload();

                }
    
            });

        });

        $('#update_event').on('click', function(){

            var event_id = $('#update_event_id').val();
            var action = "update";
            // console.log(action);
            // return false;

            if($('#cal_event_full_day').prop('checked')){
                var cal_event_full_day = 1;
            }else{
                var cal_event_full_day = 0;
            }

            var form_data = {
                action : action,
                cal_event_name : $('#cal_event_name').val(),
                cal_event_description : $('#cal_event_description').val(),
                cal_event_start_date : $('#cal_event_start_date').val(),
                cal_event_start_time : $('#cal_event_start_time').val(),
                cal_event_end_date : $('#cal_event_end_date').val(),
                cal_event_end_time : $('#cal_event_end_time').val(),
                cal_event_full_day : cal_event_full_day,
                cal_event_url : $('#cal_event_url').val(),
                cal_event_reminder_date : $('#cal_event_reminder_date').val(),
                cal_event_reminder_time : $('#cal_event_reminder_time').val()
            };

            console.log(form_data);
            return false;

            $.ajax({

                url: siteUrl + 'ajax_save_event',
                type: 'POST',
                dataType: 'json', 
                data : { form_data },
                success: function (data) {

                    $('#cal_event_name').val('');
                    $('#cal_event_description').val('');
                    $('#cal_event_start_date').val('');
                    $('#cal_event_start_time').val('');
                    $('#cal_event_end_date').val('');
                    $('#cal_event_end_time').val('');
                    $('#cal_event_url').val('');
                    $('#cal_event_reminder_date').val('');
                    $('#cal_event_reminder_time').val('');

                    location.reload();

                }
    
            });

        });

    </script>


</body>
