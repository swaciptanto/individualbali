(function($) {
    $(function() {
        var url = $("#container-abc-url-id").text();
        
        if ($('#gzhotel-booking-price-plan-id').length > 0) {
            //modified: add properties
            $('#gzhotel-booking-price-plan-id').dataTable({
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [11, 12]}
                ]
            });
        }

        if ($('#add_price_plan_id').length > 0) {
            var startDate, endDate;
            if ($("#dialogAddPricePlan").length > 0) {
                $("#dialogAddPricePlan").dialog({
                    autoOpen: false,
                    resizable: true,
                    draggable: false,
                    //modified: change value
                    //height: 790,
                    height: 390,
                    width: 690,
                    modal: true,
                    open: function() {
                        $('#daterange-btn').daterangepicker({
                            timePicker: false,
                            format: $('#daterange-btn').attr('date-format')
                        });
                    },
                    buttons: {
                        "Add": function() {
                            if ($(this).dialog().find('form').valid()) {
                                $.ajax({
                                    type: "POST",
                                    data: $(this).dialog().find('form').serialize(),
                                    url: url + "GzPrice/add_price_plan",
                                    beforeSend: function() {
                                        $(".overlay").css('display', 'block');
                                        $(".loading-img").css('display', 'block');
                                    },
                                    success: function(res) {
                                        $("#prce-plan-id").html(res);
                                        $('#gzhotel-booking-price-plan-id').dataTable({
                                            "aoColumnDefs": [
                                                {'bSortable': false, 'aTargets': [11, 12]}
                                            ]
                                        });
                                        $(".overlay").css('display', 'none');
                                        $(".loading-img").css('display', 'none');
                                    }
                                });
                                $(this).dialog('close');

                            }
                        },
                        'Cancel': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            }
            $("#gz-abc-container-id").delegate("#add_price_plan_id", "click", function(e) {
                e.preventDefault();
                //modified: change method
                //$('#dialogAddPricePlan').dialog('open');
                $.ajax({
                    type: "POST",
                    data: {
                        calendar_id: $("#hidden_calendar_id").val(),
                        controller: 'GzPrice',
                        action: 'get_frm_add_price_plan'
                    },
                    url: url + "GzPrice/get_frm_add_price_plan",
                    success: function(res) {
                        $("#dialogAddPricePlan").html(res);
                        $('#dialogAddPricePlan').dialog('open');
                    }
                });
            });
        }


        if ($("#dialogEditPricePlan").length > 0) {
            $("#dialogEditPricePlan").dialog({
                autoOpen: false,
                resizable: true,
                draggable: false,
                //modified: change value
                //height: 790,
                height: 390,
                width: 690,
                id: $('#record_id').text(),
                calendar_id: $("#calendar_id").text(),
                modal: true,
                open: function() {
                    $('#edit-daterange-btn').daterangepicker({
                        timePicker: false,
                        startDate: $("#edit_from_date").val(),
                        endDate: $("#edit_to_date").val(),
                        format: $('#edit-daterange-btn').attr('date-format')
                    });
                },
                buttons: {
                    //modified: changed
                    //"Edit": function() {
                    "Save": function() {
                        if ($(this).dialog().find('form').valid()) {
                            $.ajax({
                                type: "POST",
                                data: $(this).dialog().find('form').serialize(),
                                url: url + "GzPrice/edit",
                                beforeSend: function() {
                                    $(".overlay").css('display', 'block');
                                    $(".loading-img").css('display', 'block');
                                },
                                success: function(res) {

                                    $("#prce-plan-id").html(res);
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                    $('#gzhotel-booking-price-plan-id').dataTable({
                                        "aoColumnDefs": [
                                            {'bSortable': false, 'aTargets': [11, 12]}
                                        ]
                                    });
                                }
                            });
                            $(this).dialog('close');
                        }
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }
        $("#prce-plan-id").delegate(".icon-edit", "click", function(e) {
            e.preventDefault();
            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');
            $.ajax({
                type: "POST",
                data: {
                    id: $(this).attr('rev'),
                    calendar_id: $(this).attr('rel'),
                    controller: 'GzPrice',
                    action: 'get_frm_edit_price_plan'
                },
                url: url + "GzPrice/get_frm_edit_price_plan",
                success: function(res) {

                    $("#dialogEditPricePlan").html(res);
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                    $('#dialogEditPricePlan').dialog('open');

                    $("#dialogEditPricePlan").delegate("#is_edit_default", 'change', function(e) {

                        if ($("#is_edit_default").val() == 'T') {
                            $('#edit_adults').prop('disabled', 'disabled');
                            $('#edit_children').prop('disabled', 'disabled');
                        } else {
                            $('#edit_adults').prop('disabled', false);
                            $('#edit_children').prop('disabled', false);
                        }
                    });
                }
            });
        });

        $("#prce-plan-id").delegate('a.icon-delete', 'click', function(e) {
            e.preventDefault();
            $('#record_id').text($(this).attr('rev'));
            $('#calendar_id').text($(this).attr('rel'));
            $('#dialogDelete').dialog('open');
        });
        if ($("#dialogDelete").length > 0) {
            $("#dialogDelete").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                close: function() {
                    $('#record_id').text('');
                },
                buttons: [{
                        html: "<i class='fa fa-trash-o'></i>&nbsp; Delete item",
                        "class": "btn btn-danger",
                        click: function() {
                            $(".overlay").css('display', 'block');
                            $(".loading-img").css('display', 'block');
                            $.ajax({
                                type: "POST",
                                data: {
                                    id: $('#record_id').text(),
                                    calendar_id: $("#calendar_id").text(),
                                    controller: 'GzPrice',
                                    action: 'delete'
                                },
                                url: url + "GzPrice/delete",
                                success: function(res) {
                                    $("#prce-plan-id").html(res);
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                    $('#gzhotel-booking-price-plan-id').dataTable({
                                        "aoColumnDefs": [
                                            {'bSortable': false, 'aTargets': [11, 12]}
                                        ]
                                    });
                                }
                            });
                            $(this).dialog('close');
                        }}, {
                        html: "<i class='fa fa-times'></i>&nbsp; Cancel",
                        "class": "btn btn-default",
                        click: function() {
                            $(this).dialog('close');
                        }}]
            });
        }

        $("#gz-abc-container-id").delegate('a.room_type_drop_down', 'click', function(e) {

            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');
            e.preventDefault();
            var calendar_id = $(this).attr('rel');
            $.ajax({
                type: "POST",
                data: {
                    calendar_id: calendar_id
                },
                url: url + "GzPrice/get_price_plan_table",
                success: function(res) {
                    $("#prce-plan-id").html(res);
                    $("#hidden_calendar_id").val(calendar_id);
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                    $('#gzhotel-booking-price-plan-id').dataTable({
                        "aoColumnDefs": [
                            {'bSortable': false, 'aTargets': [11, 12]}
                        ]
                    });
                }
            });
        });

        $("#dialogAddPricePlan").delegate("#is_default", 'change', function(e) {

            if ($(this).val() == 'T') {
                $('#adults').prop('disabled', 'disabled');
                $('#children').prop('disabled', 'disabled');
            } else {
                $('#adults').prop('disabled', false);
                $('#children').prop('disabled', false);
            }
        });
    });
}(jQuery));
