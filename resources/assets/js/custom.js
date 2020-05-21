
$(document).ready(function() {
    //themes scroll
    /*if($('.card-data').length > 0){
        var content = new PerfectScrollbar('.card-data ul',{
            theme: "dark"
        });
    }
    if($('#candidate_pop_detail').length > 0){
        var content = new PerfectScrollbar('#candidate_pop_detail',{
            theme: "dark"
        });
    }*/
    //data table
    if($(document).find('.zero-configuration').length>0){

        $('.zero-configuration').DataTable();
    }
    //DISABLE RECORD FROM LISTING
    $(document).on('click', '.status-switch', function () {
        var currRowId = $(this).closest('tr').attr('id').replace('data-', '');
        form_data = new FormData();
        form_data.append('id', currRowId);
        form_data.append('tbl', $("#hdn").val());
        form_data.append('column', $("#clm").val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_status',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success("Status has been modified successfully!", 'Success!');
                }
                else {
                    toastr.error(obj.error,'Error!');
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
            complete: function (obj) {
                obj = obj.responseJSON;
                $('#csrf_token').val(obj.csrf_token);
                $('#csrf_name').val(obj.csrf_name);
            },
        });
    });
    /*DELETE RECORD FROM LISTING*/
    var currDeleteRowId = '';
    var currDeleteRowThat = '';
    $(document).on('click', '.delete', function(){
        currDeleteRowId = $(this).closest('tr').attr('id').replace('data-','');
        currDeleteRowThat = $(this);
        $('#deleteModal').modal('show');
    });
    $('#deleteModal .delete-confirm-btn').on("click",  function(){
        ajax_delete();
    });
    function ajax_delete()
    {
        form_data = new FormData();
        form_data.append('id', currDeleteRowId);
        form_data.append('tbl', $("#hdn").val());
        form_data.append('column', $("#clm").val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl+'ajax_delete',
            success: function(obj){
                if(obj.code==1)
                {
                    //currDeleteRowThat.closest('tr').next('.desc').remove();
                    currDeleteRowThat.closest('.data').remove();
                    //currDeleteRowThat.closest('#data-'+currDeleteRowId).remove();
                    toastr.success("Record has been removed successfully!", 'Success!');
                }
                else
                {
                    //errormsg(obj.error,5000);
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
        $('#deleteModal').modal('hide');
    }

    /*show/hide password*/
    var open = 'icon-eye';
    var close = 'icon-eye-off';
    var ele = document.getElementById('employer_password');
    //var ele = $('#recruiter_password');
    if($('#employer_password').length>0) {
        document.getElementById('toggleBtn').onclick = function () {
            if (this.classList.contains(open)) {
                ele.type = "text";
                this.classList.remove(open);
                this.className += ' ' + close;
            } else {
                ele.type = "password";
                this.classList.remove(close);
                this.className += ' ' + open;
            }
        }
    }
    if($('#recruiter_password').length>0) {
        var ele2 = document.getElementById('recruiter_password');

        document.getElementById('toggleBtn').onclick = function () {
            if (this.classList.contains(open)) {
                ele2.type = "text";
                this.classList.remove(open);
                this.className += ' ' + close;
            } else {
                ele2.type = "password";
                this.classList.remove(close);
                this.className += ' ' + open;
            }
        }
    }
    /*show/hide password end*/


    //SELECT 2
    if($(document).find('.select2').length>0) {
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
    }

    /*LIST TODOS STATUS*/
    $(document).on('change', '#list_todo_status', function () {
        var currRowId = $(this).closest('tr').attr('id').replace('data-', '');
        form_data = new FormData();
        form_data.append('id', currRowId);
        form_data.append('tbl', $("#hdn").val());
        form_data.append('column', $("#clm").val());
        form_data.append('value', $(this).val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_todo_status',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success("Status has been modified successfully!", 'Success!');
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
    });

    /*if(old_status == 2){
     toastr.warning("You can not open this job because it has been paused!", 'Warning!');
     }else{
     toastr.warning("You can not open this job because it has been closed!", 'Warning!');
     }*/
    /* JOB LIST STATUS*/
    $(document).on('change', '.job_list_status', function () {
        var currRowId = $(this).closest('tr').attr('id').replace('data-', '');
        var old_status = $(this).attr('id').replace('job_list_status_', '');
        var status = $(this).val();
        if(status == 1){
            job_status(currRowId,status);
        }else if(status == 2){
            $('#pause_job_id').val(currRowId);
            $('#pauseJobModal').modal('show');
            $(document).on('click', '#pause_job_model_yes',function () {
                job_status($('#pause_job_id').val(),status);
            });
        }else{
            $('#close_job_id').val(currRowId);
            $('#closedJobModel').modal('show');
            $(document).on('click', '#close_job_model_yes',function () {
                job_status($('#close_job_id').val(),status);
            });
        }
    });
    $(document).on('click','.delete-job',function () {
        var currRowId = $(this).closest('tr').attr('id').replace('data-', '');
        $('#deleteJobModel').modal('show');
        $(document).on('click', '#delete_job_model_yes',function () {
            job_status(currRowId,4);
        });
    });

    //function for change job status
    function job_status(currRowId,status) {
        form_data = new FormData();
        form_data.append('id', currRowId);
        form_data.append('status', status);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_job_status',
            success: function (obj) {
                if (obj.code == 1) {
                    $('#pauseJobModal').modal('hide');
                    $('#closedJobModel').modal('hide');
                    $('#deleteJobModel').modal('hide');
                    if(status ==2){
                        toastr.success('Sourcing has been stopped for this role! Please hit the "Source More" button if you need more candidates.', 'Done!');
                    }
                    if(status==3){
                        toastr.success('The job has been closed now! You could reopen this job in the future by hitting "Source More" button from the closed status section.', 'Done!');
                    }
                    if(status==4){
                        toastr.success('The job has been removed from the platform.', 'Done!');
                    }
                    location.reload();
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

    //Year and month card
    $(document).find('.row.year-card').hide();
    $(document).on('click','#month_year_switch',function () {

        if($(this).prop("checked") == true){
            $(document).find('.row.month-card').hide('slow');
            $(document).find('.row.year-card').show('slow');
        }else{
            $(document).find('.row.year-card').hide('slow');
            $(document).find('.row.month-card').show('slow');
        }
    });

    //Employer - GET JOB LIST
    $(document).on('change','#selsect_job_id',function () {
        window.location.replace(siteUrl + 'candidate/'+$(this).val());
    });

    /*Ratting*/
    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function(){
        var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

        // Now highlight all the stars that's not after the current hovered star
        $(this).parent().children('li.star').each(function(e){
            if (e < onStar) {
                $(this).addClass('hover');
            }
            else {
                $(this).removeClass('hover');
            }
        });

    }).on('mouseout', function(){
        $(this).parent().children('li.star').each(function(e){
            $(this).removeClass('hover');
        });
    });


    /* 2. Action to perform on click */
    $('.stars li').on('click', function(){
        var onStar = parseInt($(this).data('value'), 10); // The star currently selected
        var stars = $(this).parent().children('li.star');

        for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('selected');
        }

        for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('selected');
        }
        // JUST RESPONSE (Not needed)
        var ratingValue = parseInt($(this).closest('.sortable > li').find('.stars li.selected').last().data('value'), 10);

        var candidate_id = $(this).closest('.sortable > li').attr('id').replace('candidate_','');
        form_data = new FormData();
        form_data.append('ratingValue', ratingValue);
        form_data.append('candidate_id', candidate_id);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + 'ajax_candidate_rating',
            success: function (obj) {
                if (obj.code == 1) {
                    toastr.success("Rating has been submitted successfully!", 'Success!');
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
    });
    /*Ratting end*/


    /*add more email*/

    $('.add_button').click(function(){
        var current_email_counter = $(document).find('.email-inputs').length;
        var new_email_counter = parseInt(current_email_counter+1);
        var fieldHTML = '<div class="remove-div w-100 d-inline-block "><div class="col-md-11 d-inline-block float-left"> <input type="email" name="email[]" id="candidate_email_'+new_email_counter+'" class="email-inputs form-control" placeholder="Email '+new_email_counter+'" data-validation-required-message="The email field is required"> </div> <div class="col-md-1 d-inline-block float-left"> <a href="javascript:void(0);" class="remove_button" title="Remove Email"><i class="feather icon-minus-square font-large-2 float-right"></i></a></div></div>';
        $('.field_wrapper').append(fieldHTML);

    });

    //Once remove button is clicked
    $('.field_wrapper').on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest('.remove-div').remove(); //Remove field html
    });
    /*add more email End*/

    /*add more location*/

    $('.add_location').click(function(){

        var current_location_counter = $(document).find('.location-inputs').length;
        var new_location_counter = parseInt(current_location_counter+1);
        var fieldHTML = '<div class="remove-div w-100 d-inline-block "><div class="col-md-11 d-inline-block float-left"> <input type="text" name="multiple_location[]" id="multiple_location_'+new_location_counter+'" class="location-inputs form-control" placeholder="Location '+new_location_counter+'" data-validation-required-message="The location field is required"> </div> <div class="col-md-1 d-inline-block float-left"> <a href="javascript:void(0);" class="remove_location" title="Remove Location"><i class="feather icon-minus-square font-large-2 float-right"></i></a></div></div>';
        $('.field_wrapper').append(fieldHTML);
        var placesAutocomplete = places({
            appId: 'plJES61WLXRM',
            apiKey: 'a17570e937aa49c6723f30c9c28645c0',
            container: document.querySelector('#multiple_location_'+new_location_counter)
        });
        placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
        });
    });
    if($(document).find('#multiple_location_1').length>0){
        var placesAutocomplete = places({
            appId: 'plJES61WLXRM',
            apiKey: 'a17570e937aa49c6723f30c9c28645c0',
            container: document.querySelector('#multiple_location_1')
        });
        placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
        });
    }

    //Once remove button is clicked
    $('.field_wrapper').on('click', '.remove_location', function(e){
        e.preventDefault();
        $(this).closest('.remove-div').remove(); //Remove field html
    });
    /*add more email End*/

    /*FOR SOURCE LOCATION CHANGE */
    $('.add_s_location').click(function(){

        var current_location_counter = $(document).find('.location-s-inputs').length;
        var new_location_counter = parseInt(current_location_counter+1);
        var fieldHTML = '<div class="remove-div w-100 d-inline-block "><div class="col-md-11 d-inline-block float-left mb-1"> <input type="text" name="multiple_s_location[]" id="multiple_s_location_'+new_location_counter+'" class="location-s-inputs form-control" placeholder="Location '+new_location_counter+'" data-validation-required-message="The location field is required" style="margin-bottom: 5px;"> <select class="select2 form-control" name="search_radius[]" id="search_radius_'+new_location_counter+'"></select> </div> <div class="col-md-1 d-inline-block float-left"> <a href="javascript:void(0);" class="remove_s_location" title="Remove Location"><i class="feather icon-minus-square font-large-2 float-right"></i></a></div></div>';
        $('.field_s_wrapper').append(fieldHTML);
        var $options = $("#search_radius_"+current_location_counter+" > option").clone();

        $('#search_radius_'+new_location_counter).append($options);
        /*$('#search_radius_'+new_location_counter).select2({ dropdownAutoWidth: true, width: '100%'});*/
        var placesAutocomplete = places({
            appId: 'plJES61WLXRM',
            apiKey: 'a17570e937aa49c6723f30c9c28645c0',
            container: document.querySelector('#multiple_s_location_'+new_location_counter)
        });
        placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
        });
    });
    if($(document).find('#multiple_s_location_1').length>0){
        var placesAutocomplete = places({
            appId: 'plJES61WLXRM',
            apiKey: 'a17570e937aa49c6723f30c9c28645c0',
            container: document.querySelector('#multiple_s_location_1')
        });
        placesAutocomplete.on('clear', function() {
            $address.textContent = 'none';
        });
    }

    //Once remove button is clicked
    $('.field_s_wrapper').on('click', '.remove_s_location', function(e){
        e.preventDefault();
        $(this).closest('.remove-div').remove(); //Remove field html
    });
    /*FOR SOURCE LOCATION CHANGE END*/


    /*show hide location div*/
    $(document).find('#location_radio_div input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        if(inputValue == '1'){
            $('.search-radius').hide();
            $('.multiple-location').show();
        }else{
            $('.multiple-location').hide();
            $('.search-radius').show();
        }
    });
    /*show hide location div end*/


    // Compose Modal - Reset Input Value on Click compose btn
    $('.compose-btn .btn').on('click', function (e) {
        // all input forms
        $(".modal .modal-body input").val("");
        // quill editor content
        var quill_editor = $(".modal .modal-body .ql-editor");
        quill_editor[0].innerHTML = "";
        // file input content
        var file_input = $(".modal .modal-body .custom-file .custom-file-label");
        file_input[0].innerHTML = "";
    });

    if($('#email-container .editor').length > 0 ) {
        var emailEditor = new Quill('#email-container .editor', {
            bounds: '#email-container .editor',
            modules: {
                'formula': true,
                'syntax': true,
                'toolbar': [
                    ['bold', 'italic', 'underline', 'strike', 'link', 'blockquote', 'code-block',
                        {
                            'header': '1'
                        }, {
                        'header': '2'
                    }, {
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'font': []
                    }]
                ],
            },
            placeholder: 'Message',
            theme: 'snow',
        });

        var editors = [emailEditor];
    }
    // Compose Modal - Reset Input Value on Click compose btn END



    var currDeleteRowId = '';
    var currDeleteRowThat = '';
    $(document).on('click','.remove_location,.remove_s_location',function () {

        var currDeleteRowId = $(this).attr('id');
        if(currDeleteRowId>0){
            currDeleteRowThat = $(this);
            $('#location_id').val(currDeleteRowId);
            $('#deletelocationModal').modal('show');
        }
    });
    $('#delete-loc-confirm-btn').on("click",  function(){
        form_data = new FormData();
        form_data.append('id', $('#location_id').val());
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl+'ajax_delete_location',
            success: function(obj){
                if(obj.code==1)
                {
                    currDeleteRowThat.closest('.col-md-1').prev('.col-md-11').remove();
                    currDeleteRowThat.closest('.col-md-1').remove();
                    toastr.success("Location has been removed successfully!", 'Success!');
                }
                else
                {
                    //errormsg(obj.error,5000);
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
        $('#deletelocationModal').modal('hide');
    });
    if($(document).find("#date_picker").length>0){
        $(document).find("#date_picker").pickadate({
            format: 'd/m/yyyy',
        });
    }
    if($(document).find("#start_date").length>0){
        $(document).find("#start_date").pickadate({
            format: 'd/m/yyyy',
        });
    }
    if($(document).find("#end_date").length>0){
        $(document).find("#end_date").pickadate({
            format: 'd/m/yyyy',
        });
    }
    if($(document).find("#time_picker").length>0) {
        $(document).find("#time_picker").pickatime();
    }

    /*show hide location div*/
    $(document).find('#email_radio_div input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        if(inputValue == '1'){
            $('.personal_email').hide();
            /*only email change*/
            form_data = new FormData();
            form_data.append('type', 1);
            form_data.append('csrf_token', $('#csrf_token').val());
            form_data.append('csrf_name', $('#csrf_name').val());
            $.ajax({
                dataType: 'json',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                url: siteUrl+'ajax_mail_type_change',
                success: function(obj){
                    if(obj.code==1)
                    {
                        toastr.success("Mail Account has been changed successfully!", 'Success!');
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
        }else{
            $('.personal_email').show();
            /*only email change*/
            form_data = new FormData();
            form_data.append('type', 2);
            form_data.append('csrf_token', $('#csrf_token').val());
            form_data.append('csrf_name', $('#csrf_name').val());
            $.ajax({
                dataType: 'json',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                url: siteUrl+'ajax_mail_type_change',
                success: function(obj){
                    if(obj.code==1)
                    {
                        if(obj.msg_show==1)
                        toastr.success("Mail Account has been changed successfully!", 'Success!');
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
        }
    });
    /*show hide location div end*/
    if($('#summernote').length > 0){
        $('#summernote').summernote({
            tabsize: 2,
            height: 150,
            maxHeight: 150,             // set maximum height of editor
            focus: true,
        });
    }
});
/**************************Document ready is over *****************************/

/*DELETE NOT RECORD*/
var currDeleteRowId = '';
var currDeleteRowThat = '';

$(document).on('click', '.delete-note', function(){
    currDeleteRowId = $(this).closest('li').attr('id').replace('data_','');
    currDeleteRowThat = $(this);
    $('#deleteNoteModal').modal('show');
});
$(document).on("click",'#delete-note-confirm-btn' , function(){
    ajax_delete_note();
});
function ajax_delete_note()
{
    form_data = new FormData();
    form_data.append('id', currDeleteRowId);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_note_delete',
        success: function(obj){
            if(obj.code==1)
            {
                currDeleteRowThat.closest('.not-li').remove();
                toastr.success("Record has been removed successfully!", 'Success!');
                $('#last_msg_id').val(obj.last_msg_id);
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
    $('#deleteNoteModal').modal('hide');
}

//admin change the email status to created.
$(document).on('change','#mail_status_select',function () {
    var status = $(this).val();
    var request_id = $(this).closest('tr').attr('id').replace('data-','');
    if(status==0){
        toastr.warning("Email Status is already in Created Stage!", 'Warning!');
    }
    if(status==1){
        $('#request_email_id').val(request_id);
        $('#setEmailPassModal').modal('show');
    }
    if(status==2){
        $('#email_employer_id').val(request_id);
        $('#deleteEmailModal').modal('show');
    }
});
//confirm mail delete if status = 1
$(document).on('click','#cnf_active_pass',function () {
    var request_id = $('#request_email_id').val();
    var password = $('#password').val();
    var status = 1;
    form_data = new FormData();
    form_data.append('request_id', request_id);
    form_data.append('password', password);
    form_data.append('status', status);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_email_status',
        success: function(obj){
            if(obj.code==1)
            {
                toastr.success("Email Status has been changed successfully!", 'Success!');
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

//confirm mail delete if status = 2
$(document).on('click','#cnf_delete_mail',function () {
    var request_id = $('#email_employer_id').val();
    var reject_reason = $('#email_reject_reason').val();
    var status = 3;
    form_data = new FormData();
    form_data.append('request_id', request_id);
    form_data.append('status', status);
    form_data.append('reject_reason', reject_reason);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_email_status',
        success: function(obj){
            if(obj.code==1)
            {
                $('#data-'+request_id).closest('.data').remove();
                toastr.success("Email Status has been changed successfully!", 'Success!');
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
            $('#deleteEmailModal').modal('hide');
        },
    });
});


// EMPLOYER - add_profile_size
var currAddRowId = '';
var currAddRowThat = '';

$(document).on('click', '.add_profile_size', function(){
    currAddRowId = $(this).closest('tr').attr('id').replace('data-','');
    currAddRowThat = $(this);
    $('#addProfileModal').modal('show');
});
//add batch size
$(document).on('click','#add_batch_size',function () {
    var size = $('#pop_batch_size').val();
    form_data = new FormData();
    form_data.append('id',currAddRowId );
    form_data.append('size',size);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_add_batch_size',
        success: function(obj){
            if(obj.code==1)
            {
                $('#data-'+currAddRowId).find('select').prepend('<option value="1" selected>Open</option>');
                $('#b_size_'+currAddRowId).text(obj.total);
                    toastr.success("You just made the sourcing request for another batch. We'll start working on it!", 'Done!');
                $('#addProfileModal').modal('hide');
                currAddRowThat.hide();
                location.reload();
            }
            else if(obj.code==2)
            {
                toastr.warning(obj.error_msg, 'Warning!');
            }
            else
            {
                toastr.error(obj.error, 'Error!');
                $('#addProfileModal').modal('hide');
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

//activate profile
$(document).on('click','.activate-profile',function () {
    var id = $(this).attr('id').replace('activate_','');
    form_data = new FormData();
    form_data.append('id',id);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_activate_account',
        success: function(obj){
            if(obj.code==1)
            {
                $('#activate_'+id).remove()
                $('#status_action_'+id).html(obj.html);
                toastr.success('Account has been successfully Activated.', 'Success!');
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

//approve the subscription request
$(document).on('change','#subscription_req_status',function () {
    var id = $(this).val();
    var that = $(this);
    var request_id = $(this).closest('tr').attr('id').replace('data-', '');
    form_data = new FormData();
    form_data.append('id',id);
    form_data.append('request_id',request_id);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_approve_subscription',
        success: function(obj){
            if(obj.code==1)
            {
                that.remove().html(obj.html);
                toastr.success('Subscription request has been successfully Approved.', 'Success!');
                setInterval(function() {
                    window.location.reload();
                }, 120000);
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

//approve the custom subscription request
var thisRowId='';
var thatRow='';
var thisId = '';
$(document).on('change','#sub_req_cust_status',function () {
    thisRowId =$(this).closest('tr').attr('id').replace('data-', '');
    thisId = $(this).val();
    thatRow = $(this);
    if(thisId ==1){
        $('#setCustomPackModal').modal('show');
    }

});
$(document).on('click','#cnf_active_cust_subs',function () {
    var sub_name = $('#sub_name').val();
    var month = $('#month').val();
    var profile = $('#profile').val();
    form_data = new FormData();
    form_data.append('c_request_id',thisRowId);
    form_data.append('sub_name',sub_name);
    form_data.append('month',month);
    form_data.append('profile',profile);
    form_data.append('csrf_token', $('#csrf_token').val());
    form_data.append('csrf_name', $('#csrf_name').val());
    $.ajax({
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        url: siteUrl+'ajax_approve_cust_subscription',
        success: function(obj){
            if(obj.code==1)
            {
                $(this).remove();
                toastr.success('Custom Subscription request has been successfully Approved.', 'Success!');
                $('#setCustomPackModal').modal('hide');
                $('#custom_request')[0].reset();
                setInterval(function() {
                    window.location.reload();
                }, 120000);
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

/***************************************CHAT JS***************************************/
var is_load = 1;
var loadtime = 60000;
var counter_load_time = 60000;
/*CHAT JQUESRY*/
// Chat user list
if($('.chat-application .chat-user-list').length > 0){
    var chat_user_list = new PerfectScrollbar(".chat-user-list");
}

// Chat user profile
if($('.chat-application .profile-sidebar-area .scroll-area').length > 0){
    var chat_user_list = new PerfectScrollbar(".profile-sidebar-area .scroll-area");
}
// Chat Profile sidebar toggle
$('.chat-application .sidebar-profile-toggle').on('click',function(){
    $('.chat-profile-sidebar').addClass('show');
    $('.chat-overlay').addClass('show');
});

// Update status by clickin on Radio
$('.chat-application .user-status input:radio[name=userStatus]').on('change', function(){
    var $className = "avatar-status-"+this.value;
    $(".header-profile-sidebar .avatar span").removeClass();
    $(".sidebar-profile-toggle .avatar span").removeClass();
    $(".header-profile-sidebar .avatar span").addClass($className+" avatar-status-lg");
    $(".sidebar-profile-toggle .avatar span").addClass($className);
});

// On Profile close click
$(".chat-application .close-icon").on('click',function(){
    $('.chat-profile-sidebar').removeClass('show');
    $('.user-profile-sidebar').removeClass('show');
    if(!$(".sidebar-content").hasClass("show")){
        $('.chat-overlay').removeClass('show');
    }
});

// On sidebar close click
$(".chat-application .sidebar-close-icon").on('click',function(){
    $('.sidebar-content').removeClass('show');
    $('.chat-overlay').removeClass('show');
});

// On overlay click
$(".chat-application .chat-overlay").on('click',function(){
    $('.app-content .sidebar-content').removeClass('show');
    $('.chat-application .chat-overlay').removeClass('show');
    $('.chat-profile-sidebar').removeClass('show');
    $('.user-profile-sidebar').removeClass('show');
});

// Favorite star click
$(".chat-application .favorite i").on("click", function(e) {
    $(this).parent('.favorite').toggleClass("warning");
    e.stopPropagation();
});

// Main menu toggle should hide app menu
$('.chat-application .menu-toggle').on('click',function(e){
    $('.app-content .sidebar-left').removeClass('show');
    $('.chat-application .chat-overlay').removeClass('show');
});

// Chat sidebar toggle
if ($(window).width() < 992) {
    if($('.chat-application .chat-profile-sidebar').hasClass('show')){
        $('.chat-profile-sidebar').removeClass('show');
    }
    $('.chat-application .sidebar-toggle').on('click',function(){
        $('.app-content .sidebar-content').addClass('show');
        $('.chat-application .chat-overlay').addClass('show');
    });
}

// For chat sidebar on small screen
if ($(window).width() > 992) {
    if($('.chat-application .chat-overlay').hasClass('show')){
        $('.chat-application .chat-overlay').removeClass('show');
    }
}

// Scroll Chat area
$(".user-chats").scrollTop($(".user-chats > .chats").height());

// Filter
$(".chat-application #chat-search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    if(value!=""){
        $(".chat-user-list .chat-users-list-wrapper li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    }
    else{
        // If filter box is empty
        $(".chat-user-list .chat-users-list-wrapper li").show();
    }
});

$(window).on("resize", function() {
    // remove show classes from sidebar and overlay if size is > 992
    if ($(window).width() > 992) {
        if($('.chat-application .chat-overlay').hasClass('show')){
            $('.app-content .sidebar-left').removeClass('show');
            $('.chat-application .chat-overlay').removeClass('show');
        }
    }
    // Chat sidebar toggle
    if ($(window).width() < 992) {
        if($('.chat-application .chat-profile-sidebar').hasClass('show')){
            $('.chat-profile-sidebar').removeClass('show');
        }
        $('.chat-application .sidebar-toggle').on('click',function(){
            $('.app-content .sidebar-content').addClass('show');
            $('.chat-application .chat-overlay').addClass('show');
        });
    }
});
/***************************************CHAT JS END***************************************/
/*************Employar chat************/
//TEAM CHAT
if($('#conversion_id').length >0 && $('#team_chat').length>0) {
    window.setInterval(function(){
        fetch_message($('#conversion_id').val())
    }, loadtime);
}
if($(".chat-application .chat-user-list ul li.active").length>0 && $('#team_chat').length>0){
    window.setInterval(function(){
        fetch_message($(".chat-application .chat-user-list ul li.active").attr('id').replace('conversion_',''));
    }, loadtime);
}
/*get message ajax function*/
function fetch_message(conversation_id) {
    var last_delivered_msg_id = $('#last_msg_id').val();
    if(last_delivered_msg_id==''){
        last_delivered_msg_id = 0;
    }
    form_data = new FormData();
    form_data.append('conversation_id',conversation_id);
    form_data.append('only_msg',1);
    form_data.append('last_delivered_msg_id',last_delivered_msg_id);
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
                $('.chats').append(obj.data_html);
                if (obj.last_msg_id != null){
                    $('#last_msg_id').val(obj.last_msg_id);
                    $('#conversion_'+conversation_id+' .truncate').html(obj.last_msg_txt);
                    $('#conversion_'+conversation_id+' .contact-meta').html('');
                    $('#conversion_'+conversation_id+' .contact-meta').html('<span class="float-right mb-25">'+obj.msgtime+'</span>');

                }
                if(obj.totalcounter>0){
                    $('#total_team_counter').text(obj.totalcounter);
                }else{
                    $('#total_team_counter').text('');
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

/*FATCH OLD TEAM MESSAGE*/
function fatch_old_msg(offset,conversation_id,last_counter) {
    if(last_counter>0){
        is_load = 0;
        //loder
        $('.chats').prepend('<div class="spinner-border" style="width: 5rem; height: 5rem;top: 30%; left: 70%;position:fixed;" role="status"><span class="sr-only">Loading...</span></div>');
        //ajax call
        form_data = new FormData();
        form_data.append('conversation_id',conversation_id);
        form_data.append('offset',offset);
        form_data.append('only_msg',1);
        form_data.append('old_msg',1);
        form_data.append('csrf_token', $('#csrf_token').val());
        form_data.append('csrf_name', $('#csrf_name').val());
        $.ajax({
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            async: false,
            type: 'post',
            url: siteUrl+'ajax_get_msg',
            success: function(obj){
                if(obj.code==1)
                {
                    is_load=1;
                    $('#last_counter').val(obj.last_serve_counter);
                    $('.spinner-border').hide();
                    $('.chats').prepend(obj.data_html);
                    var hh = $('.chats').height();
                    //$('.user-chats').scrollTop($('.user-chats')[0].scrollHeight-parseInt(hh-500));
                    $('.user-chats').scrollTop(500);
                    $('.user-chats').scroll(function(){
                        element = $(this);
                        scrollTop = element.scrollTop();
                        if (scrollTop < 50 && is_load == 1) {
                            var current_msg_count = $('.chat').length;
                            fatch_old_msg(current_msg_count,conversation_id,obj.last_serve_counter);
                        }
                    });
                    if(obj.totalcounter>0){
                        $('#total_team_counter').text(obj.totalcounter);
                    }else{
                        $('#total_team_counter').text('');
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
    }else{
        is_load = 0;
    }
}

/*************job chat************/

//JOB_CHAT
if($('#job_id').length >0 && $('#job_chat').length>0) {
    window.setInterval(function(){
        fetch_job_message($('#job_id').val())
    }, loadtime);
}
if($(".chat-application .chat-user-list ul li.active").length>0 && $('#job_chat').length>0){
    window.setInterval(function(){
        fetch_job_message($(".chat-application .chat-user-list ul li.active").attr('id').replace('job_',''));
    }, loadtime);
}
/*get job message ajax function*/
function fetch_job_message(job_id) {
    var last_delivered_msg_id = $('#last_msg_id').val();
    if(last_delivered_msg_id==''){
        last_delivered_msg_id = 0;
    }
    form_data = new FormData();
    form_data.append('job_id',job_id);
    form_data.append('only_msg',1);
    form_data.append('last_delivered_msg_id',last_delivered_msg_id);
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
                $('.chats').append(obj.data_html);
                if (obj.last_msg_id != null){
                    $('#last_msg_id').val(obj.last_msg_id);
                    $('#job_'+job_id+' .truncate').html(obj.last_msg_txt);
                    $('#job_'+job_id+' .contact-meta').html('');
                    $('#job_'+job_id+' .contact-meta').html('<span class="float-right mb-25">'+obj.msgtime+'</span>');
                }
                //todo perfect scroll off
                //var chat_user = new PerfectScrollbar('.user-chats');
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

/*FATCH OLD TEAM MESSAGE*/
function fatch_old_job_msg(offset,job_id,last_counter) {
    if(last_counter>0){
        is_load = 0;
        //loder
        $('.chats').prepend('<div class="spinner-border" style="width: 5rem; height: 5rem;top: 30%; left: 70%;position:fixed;" role="status"><span class="sr-only">Loading...</span></div>');
        //ajax call
        form_data = new FormData();
        form_data.append('job_id',job_id);
        form_data.append('offset',offset);
        form_data.append('only_msg',1);
        form_data.append('old_msg',1);
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
                    is_load=1;
                    $('#last_counter').val(obj.last_serve_counter);
                    $('.spinner-border').hide();
                    $('.chats').prepend(obj.data_html);
                    var hh = $('.chats').height();
                    $('.user-chats').scrollTop(500);
                    $('.user-chats').scroll(function(){
                        element = $(this);
                        scrollTop = element.scrollTop();
                        if (scrollTop < 50 && is_load == 1) {
                            var current_msg_count = $('.chat').length;
                            fatch_old_job_msg(current_msg_count,job_id,obj.last_serve_counter);
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
    }else{
        is_load = 0;
    }
}


window.setInterval(function(){
    if(side!='admin'){
        fetch_counter();
    }
}, counter_load_time);

function fetch_counter() {
    form_data = new FormData();
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
        url: siteUrl+'ajax_get_msg_counter',
        success: function(obj){
            if(obj.code==1)
            {
                //todo fatch team member vise counter
                if(!Array.isArray(obj.single_con_count)){
                    $.each(obj.single_con_count, function( k, v ) {
                        if(v>0){
                            $('#t_budge_'+k).text(v);
                        }

                    });
                }
                //fatch team total counter
                if(obj.totalcounter>0){
                    $('#total_team_counter').text(obj.totalcounter);
                }else{
                    $('#total_team_counter').text('');
                }

                //todo fatch job wise counter
                if(!Array.isArray(obj.single_job_count)){
                    $.each(obj.single_job_count, function( k, v ) {
                        if(v>0) {
                            $('#j_budge_' + k).text(v);
                        }
                    });
                }

                //todo fatch job total counter
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