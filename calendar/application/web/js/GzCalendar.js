(function ($) {
    $(function () {

        var url = $("#container-abc-url-id").text();

        if ($('#gz-booking-calendar-id').length > 0) {
            var oTable = $('#gz-booking-calendar-id').dataTable({
                "aoColumnDefs": [
                    //modified: changed
                    //{'bSortable': false, 'aTargets': [0, 4, 5, 6]}
                    {'bSortable': false, 'aTargets': [0, 1, 5, 6, 7]}
                ]
            });
            //sort by column id desc
            oTable.fnSort([[2, "desc"]]);
        }

        if ($('#general-options-id').length > 0) {
            $('.colorbox').colorpicker();
        }

        if ($('#frm_calendar').length > 0 || $('#general-options-id').length > 0) {
            tinymce.init({
                file_browser_callback: function (field, url, type, win) {
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

        if ($("#dialogDelete").length > 0) {
            $("#dialogDelete").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                close: function () {
                    $('#record_id').text('');
                },
                buttons: [{
                        html: "<i class='fa fa-trash-o'></i>&nbsp; Delete item",
                        "class": "btn btn-danger",
                        click: function () {
                            $(".overlay").css('display', 'block');
                            $(".loading-img").css('display', 'block');
                            $.ajax({
                                type: "POST",
                                data: {
                                    id: $('#record_id').text(),
                                    controller: 'GzCalendar',
                                    action: 'delete'
                                },
                                url: url + "index.php?controller=GzCalendar&action=delete",
                                success: function (res) {
                                    $("#table-frm-id").html(res);
                                    if ($('#gz-booking-calendar-id').length > 0) {
                                        $('#gz-booking-calendar-id').dataTable({
                                            "aoColumnDefs": [
                                                //modified: changed
                                                //{'bSortable': false, 'aTargets': [0, 4, 5, 6]}
                                                {'bSortable': false, 'aTargets': [0, 1, 5, 6, 7]}
                                            ]
                                        });
                                    }
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                }
                            });
                            $(this).dialog('close');
                        }}, {
                        html: "<i class='fa fa-times'></i>&nbsp; Cancel",
                        "class": "btn btn-default",
                        click: function () {
                            $(this).dialog('close');
                        }}]
            });
        }

        if ($("a.icon-delete").length > 0) {
            $("#gz-abc-container-id").delegate("a.icon-delete", 'click', function (e) {
                e.preventDefault();
                $('#record_id').text($(this).attr('rev'));
                $('#dialogDelete').dialog('open');
            });
        }

        $("#gz-abc-container-id").delegate('#photoimg', 'change', function () {

            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');

            $("#galelry-frm-id").ajaxForm({
                target: '#preview',
                success: function () {
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                }
            }).submit();
        });
        if ($("#dialogDeleteGallery").length > 0) {
            $("#dialogDeleteGallery").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                close: function () {
                    $('#image_id').text('');
                },
                buttons: {
                    "Delete": function () {
                        $(".overlay").css('display', 'block');
                        $(".loading-img").css('display', 'block');
                        $.ajax({
                            type: "POST",
                            data: {
                                id: $('#image_id').text(),
                                calendar_id: $('#type_id').text(),
                                controller: 'GzCalendar',
                                action: 'deleteImage'
                            },
                            url: url + "index.php?controller=GzCalendar&action=deleteImage",
                            success: function (res) {
                                $("#preview").html(res);
                                $(".overlay").css('display', 'none');
                                $(".loading-img").css('display', 'none');
                            }
                        });
                        $(this).dialog('close');
                    },
                    'Cancel': function () {
                        $(this).dialog('close');
                    }
                }
            });
        }

        $("#gz-abc-container-id").delegate("a.gallery-delete", 'click', function (e) {
            e.preventDefault();

            $('#image_id').text($(this).attr('rev'));
            $('#type_id').text($(this).attr('rel'));
            $('#dialogDeleteGallery').dialog('open');
        }).delegate(".icon-edit", "click", function (e) {
            e.preventDefault();

            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');
            $.ajax({
                type: "POST",
                data: {
                    id: $(this).attr('rel'),
                    controller: 'GzCalendar',
                    action: 'get_frm_edit_block'
                },
                url: url + "index.php?controller=GzCalendar&action=get_frm_edit_block",
                success: function (res) {
                    $("#dialogEditBlocking").html(res);
                    $('#dialogEditBlocking').dialog('open');
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                }
            });
        }).delegate("#block-mark-all-id", 'click', function (e) {
            if ($(this).prop('checked')) {
                $(".mark").prop('checked', true);
            } else {
                $(".mark").prop('checked', false);
            }
        }).delegate('#delete-block-selected-id', 'click', function (e) {
            $('#dialogDeleteBlockSelected').dialog('open');
        }).delegate("#mark-all-id", 'click', function (e) {
            if ($(this).prop('checked')) {
                $(".mark").prop('checked', true);
            } else {
                $(".mark").prop('checked', false);
            }
        }).delegate('#delete-selected-id', 'click', function (e) {
            $('#dialogDeleteSelected').dialog('open');
        }).delegate("#paypal_allow", "change", function (e) {
            if ($(this).val() != '1|2::1') {
                $('.paypal_class').hide();
            } else {
                $('.paypal_class').show();
            }
        }).delegate("#authorize_allow", "change", function (e) {
            if ($(this).val() != '1|2::1') {
                $('.authorize_class').hide();
            } else {
                $('.authorize_class').show();
            }
        }).delegate("#2checkout_allow", "change", function (e) {
            if ($(this).val() != '1|2::1') {
                $('.checkout_class').hide();
            } else {
                $('.checkout_class').show();
            }
        }).delegate("#bank_acount_allow", "change", function (e) {
            if ($(this).val() != '1|2::1') {
                $('.bank_account_class').hide();
            } else {
                $('.bank_account_class').show();
            }
        });
        if ($("#dialogDeleteBlockSelected").length > 0) {
            $("#dialogDeleteBlockSelected").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                buttons: [{
                        html: "<i class='fa fa-trash-o'></i>&nbsp; Delete selected",
                        "class": "btn btn-danger",
                        click: function () {
                            $(".overlay").css('display', 'block');
                            $(".loading-img").css('display', 'block');
                            $("#gz-abc-blocking-id").ajaxForm({
                                target: '#gz-abc-table-blocking-id_wrapper',
                                success: function () {
                                    if ($('#gz-abc-table-blocking-id').length > 0) {
                                        $('#gz-abc-table-blocking-id').dataTable({
                                            "aoColumnDefs": [
                                                {'bSortable': false, 'aTargets': [0, 4, 5]}
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
                        click: function () {
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
                        click: function () {
                            $(".overlay").css('display', 'block');
                            $(".loading-img").css('display', 'block');

                            $("#table-frm-id").ajaxForm({
                                target: '#gz-booking-calendar-id_wrapper',
                                success: function () {
                                    if ($('#gz-booking-calendar-id').length > 0) {
                                        $('#gz-booking-calendar-id').dataTable({
                                            "aoColumnDefs": [
                                                //modified: change
                                                //{'bSortable': false, 'aTargets': [0, 5, 6]}
                                                {'bSortable': false, 'aTargets': [0, 1, 5, 6, 7]}
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
                        click: function () {
                            $(this).dialog("close");
                        }
                    }]
            });
        }
        if ($("#frm_calendar").length > 0) {
            $("#frm_calendar").validate();
        }

        if ($("#dialogAddBlocking").length > 0) {
            $("#dialogAddBlocking").dialog({
                autoOpen: false,
                resizable: true,
                draggable: false,
                height: 290,
                width: 490,
                modal: true,
                open: function () {
                    $('#daterange-btn').daterangepicker({
                        timePicker: false,
                        format: $('#daterange-btn').attr('date-format')
                    });
                },
                buttons: {
                    "Add": function () {
                        if ($(this).dialog().find('form').valid()) {
                            $.ajax({
                                type: "POST",
                                data: $(this).dialog().find('form').serialize(),
                                url: url + "index.php?controller=GzCalendar&action=add_blocking",
                                beforeSend: function () {
                                    $(".overlay").css('display', 'block');
                                    $(".loading-img").css('display', 'block');
                                },
                                success: function (res) {
                                    $("#gz-abc-table-blocking-id_wrapper").html(res);
                                    $('#gz-abc-table-blocking-id').dataTable({
                                        "aoColumnDefs": [
                                            {'bSortable': false, 'aTargets': [0, 3, 4]}
                                        ]
                                    });
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
                                }
                            });
                            $(this).dialog('close');
                        }

                    },
                    'Cancel': function () {
                        $(this).dialog('close');
                    }
                }
            });
        }

        $("#gz-abc-blocking-id").delegate("#add_blocking", 'click', function (e) {
            $("#dialogAddBlocking").dialog('open');
        });

        if ($('#gz-abc-table-blocking-id').length > 0) {
            $('#gz-abc-table-blocking-id').dataTable({
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [0, 4, 5]}
                ]
            });
        }

        if ($("#dialogBlockDelete").length > 0) {
            $("#dialogBlockDelete").dialog({
                autoOpen: false,
                resizable: false,
                draggable: false,
                height: 220,
                modal: true,
                close: function () {
                    $('#div_block_id').text('');
                },
                buttons: {
                    "Delete": function () {
                        $.ajax({
                            type: "POST",
                            data: {
                                id: $('#div_block_id').text(),
                                controller: 'GzCalendar',
                                action: 'delete_block'
                            },
                            url: url + "index.php?controller=GzCalendar&action=delete_block",
                            beforeSend: function () {
                                $(".overlay").css('display', 'block');
                                $(".loading-img").css('display', 'block');
                            },
                            success: function (res) {
                                $(".overlay").css('display', 'none');
                                $(".loading-img").css('display', 'none');
                                $("#gz-abc-table-blocking-id_wrapper").html(res);
                                $('#gz-abc-table-blocking-id').dataTable({
                                    "aoColumnDefs": [
                                        {'bSortable': false, 'aTargets': [0, 4, 5]}
                                    ]
                                });
                            }
                        });
                        $(this).dialog('close');
                    },
                    'Cancel': function () {
                        $(this).dialog('close');
                    }
                }
            });
        }

        $("#gz-abc-blocking-id").delegate("a.icon-block-delete", 'click', function (e) {
            e.preventDefault();

            $('#div_block_id').text($(this).attr('rev'));
            $('#dialogBlockDelete').dialog('open');
        });

        if ($('.icon-edit').length > 0) {

            if ($("#dialogEditBlocking").length > 0) {
                $("#dialogEditBlocking").dialog({
                    autoOpen: false,
                    resizable: true,
                    draggable: false,
                    height: 290,
                    width: 490,
                    id: $('#div_block_id').text(),
                    modal: true,
                    open: function () {
                        $('#edit-daterange-btn').daterangepicker({
                            timePicker: false,
                            startDate: $("#edit_from_date").val(),
                            endDate: $("#edit_to_date").val(),
                            format: $('#edit-daterange-btn').attr('date-format')
                        });
                    },
                    buttons: {
                        "Edit": function () {
                            if ($(this).dialog().find('form').valid()) {
                                $.ajax({
                                    type: "POST",
                                    data: $(this).dialog().find('form').serialize(),
                                    url: url + "index.php?controller=GzCalendar&action=edit_block",
                                    beforeSend: function () {
                                        $(".overlay").css('display', 'block');
                                        $(".loading-img").css('display', 'block');
                                    },
                                    success: function (res) {
                                        $(".overlay").css('display', 'none');
                                        $(".loading-img").css('display', 'none');
                                        $("#gz-abc-table-blocking-id_wrapper").html(res);
                                        $('#gz-abc-table-blocking-id').dataTable({
                                            "aoColumnDefs": [
                                                {'bSortable': false, 'aTargets': [0, 4, 5]}
                                            ]
                                        });
                                    }
                                });
                                $(this).dialog('close');
                            }
                        },
                        'Cancel': function () {
                            $(this).dialog('close');
                        }

                    }
                });
            }
        }
    });
}(jQuery));
