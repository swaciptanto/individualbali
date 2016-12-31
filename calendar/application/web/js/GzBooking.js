(function($) {
    $(function() {
        var url = $("#container-abc-url-id").text();

        $("#gz-abc-container-id").delegate("a.icon-delete", 'click', function(e) {
            e.preventDefault();
            $('#record_id').text($(this).attr('rev'));
            $('#dialogDelete').dialog('open');
        }).delegate("#mark-all-id", 'click', function(e) {
            if ($(this).prop('checked')) {
                $(".mark").prop('checked', true);
            } else {
                $(".mark").prop('checked', false);
            }
        }).delegate('#delete-selected-id', 'click', function(e) {
            $('#dialogDeleteSelected').dialog('open');
        }).delegate("#add-room-id", "click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                data: {
                    form: $("#new_booking").serialize()
                },
                url: url + "index.php?controller=GzBooking&action=addBookingRoomsType",
                success: function(res) {
                    $('#dialogAddBookingRoomFrmId').html(res);
                    $('#dialogAddBookingRoom').dialog('open');
                }
            });
        }).delegate("#room_type_id", "change", function(e) {
            $.ajax({
                type: "POST",
                data: {
                    form: $("#new_booking").serialize(),
                    id: $(this).val(),
                    room_id: $(".room_id_class").serialize()
                },
                url: url + "index.php?controller=GzBooking&action=addBookingRooms",
                success: function(res) {
                    $("#select-rooms-id").html(res);
                }
            });
        }).delegate("a.gz-summary-link-month", "click", function(e) {
            e.preventDefault();
            var $this = $(this), date = $this.attr("rel");
            $.ajax({
                type: "GET",
                data: {
                    day: date.split("-")[0],
                    month: date.split("-")[1],
                    year: date.split("-")[2],
                    view: date.split("-")[3]
                },
                beforeSend: function() {
                    $(".overlay").css('display', 'block');
                    $(".loading-img").css('display', 'block');
                },
                url: url + "index.php?controller=GzBooking&action=get_summary",
                success: function(result) {
                    $("#cal-container").html(result);
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                }
            });
        }).delegate("#search-drop-btn-id", "click", function(e) {
            e.preventDefault();

            if ($('#search-booking-frm-id').is(':visible')) {
                $('#search-booking-frm-id').slideUp();
            } else {
                $('#search-booking-frm-id').slideDown();
            }

        });
        if ($('#search-booking-frm-id').length > 0) {

            $('#from_start_time').datepicker({
                firstDay: $('#from_start_time').attr('first-day'),
                format: $('#start_time').attr('data-format'),
                onSelect: function(selectedDate) {
                    $('#project_to_start_time').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#to_start_time').datepicker({
                firstDay: $('#to_start_time').attr('first-day'),
                format: $('#to_start_time').attr('data-format'),
            });

            $('#from_end_time').datepicker({
                firstDay: $('#from_end_time').attr('first-day'),
                format: $('#from_end_time').attr('data-format'),
                onSelect: function(selectedDate) {
                    $('#to_end_time').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#to_end_time').datepicker({
                firstDay: $('#to_end_time').attr('first-day'),
                format: $('#to_end_time').attr('data-format'),
            });
        }
        $("#adults").spinner({
            spin: function(event, ui) {
                if (ui.value > 30) {
                    $(this).spinner("value", 0);
                    return false;
                } else if (ui.value < 0) {
                    $(this).spinner("value", 30);
                    return false;
                }
            }
        });
        $("#children").spinner({
            spin: function(event, ui) {
                if (ui.value > 30) {
                    $(this).spinner("value", 0);
                    return false;
                } else if (ui.value < 0) {
                    $(this).spinner("value", 30);
                    return false;
                }
            }
        });

        $('#reservationtime').daterangepicker({
            timePicker: false,
            format: $('#reservationtime').attr('date-format')
        }, function(start, end, label) {
            $.ajax({
                type: "POST",
                data: $("#new_booking, #edit_booking").serialize(),
                url: url + "index.php?controller=GzBooking&action=checkCalendarAvailability",
                success: function(res) {
                    $("#calendars-container-id").html(res);
                }
            });
        });

        if ($('#gzhotel-booking-booking-id').length > 0) {
            $('#gzhotel-booking-booking-id').dataTable({
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [0, 7, 8, 9]}
                ]
            });
        }

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
                                    controller: 'GzBooking',
                                    action: 'delete'
                                },
                                url: url + "index.php?controller=GzBooking&action=delete",
                                success: function(res) {
                                    $('#gzhotel-booking-booking-id').html(res);
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                }
                            });
                            $(this).dialog('close');
                        }
                    }, {
                        html: "<i class='fa fa-times'></i>&nbsp; Cancel",
                        "class": "btn btn-default",
                        click: function() {
                            $(this).dialog("close");
                        }
                    }]
            });
        }

        if ($("#dialogDeleteSelected").length > 0) {
            $("#dialogDeleteSelected").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                buttons: [{
                        html: "<i class='fa fa-trash-o'></i>&nbsp; Delete selected",
                        "class": "btn btn-danger",
                        click: function() {
                            $(".overlay").css('display', 'block');
                            $(".loading-img").css('display', 'block');

                            $("#table-frm-id").ajaxForm({
                                target: '#table-frm-id',
                                success: function() {
                                    if ($('#gzhotel-booking-booking-id').length > 0) {
                                        $('#gzhotel-booking-booking-id').dataTable({
                                            "aoColumnDefs": [
                                                {'bSortable': false, 'aTargets': [0, 7, 8, 9]}
                                            ]
                                        });
                                    }
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                }
                            }).submit();
                            $(this).dialog('close');
                        }
                    }, {
                        html: "<i class='fa fa-times'></i>&nbsp; Cancel",
                        "class": "btn btn-default",
                        click: function() {
                            $(this).dialog("close");
                        }
                    }]
            });
        }

        $("#dialogAddBookingRoom").dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
            height: 310,
            modal: true,
            close: function() {
                $('#record_id').text('');
            },
            buttons: {
                "Add": function() {
                    $.ajax({
                        type: "POST",
                        data: $("#dialogAddBookingRoomFrmId").serialize(),
                        url: url + "index.php?controller=GzBooking&action=getRoomTable",
                        success: function(res) {
                            $("#rooms-table-id").html(res);
                        }
                    });
                    $(this).dialog('close');
                },
                'Cancel': function() {
                    $(this).dialog('close');
                }
            }
        });
        $("body").delegate(".calculate-price-class", "click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: $(".booking-frm-class").serialize(),
                url: url + "index.php?controller=GzBooking&action=calculatePrice",
                success: function(json) {

                    $("#calendars_price").val(json.calendars_price);
                    $("#extra_price").val(json.extra_price);
                    $("#tax").val(json.tax);
                    $("#security").val(json.security);
                    $("#deposit").val(json.deposit);
                    $("#discount").val(json.discount);
                    $("#total").val(json.total);
                }
            });
        });
        if ($("#dialogRoomDelete").length > 0) {
            $("#dialogRoomDelete").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                close: function() {
                    $('#record_id').text('');
                },
                buttons: {
                    "Delete": function() {
                        $.ajax({
                            type: "POST",
                            data: $(".room_id_class").serialize(),
                            url: url + "index.php?controller=GzBooking&action=delete_room&deleted_room_id=" + $('#div_room_id').text(),
                            success: function(res) {
                                $("#rooms-table-id").html(res);
                            }
                        });
                        $(this).dialog('close');
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }

        if ($("#new_booking").length > 0) {
            $("#new_booking").validate();
        }

        $("#new_booking").delegate("#payment_method", "change", function(e) {

            if ($(this).val() == 'credit_card') {
                $("#credit_card_details").show();
            } else {
                $("#credit_card_details").hide();
            }
        });

        $("#cal-container").delegate(".calendar", "click", function(e) {
            e.preventDefault();
        });

        $("#cal-container").delegate(".reserved", "click", function(e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                type: "post",
                data: {
                    timestamp: $this.attr('rev')
                },
                url: url + "index.php?controller=GzBooking&action=getBooking",
                success: function(result) {
                    $("#booking_container").html(result);
                }
            });
        });

        if ($("#email_message_id").length > 0) {
            tinymce.init({
                file_browser_callback: function(field, url, type, win) {
                    tinyMCE.activeEditor.windowManager.open({
                        file: 'core/libs/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
                        title: 'KCFinder',
                        width: 700,
                        height: 500,
                        inline: true,
                        close_previous: false
                    }, {
                        window: win,
                        input: field
                    });
                    return false;
                },
                selector: "textarea",
                theme: "modern",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                templates: [
                    {title: 'Test template 1', content: 'Test 1'},
                    {title: 'Test template 2', content: 'Test 2'}
                ],
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
            });
        }
    });
}(jQuery));