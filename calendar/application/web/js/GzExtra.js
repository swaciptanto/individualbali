(function($) {
    $(function() {
        var url = $("#container-abc-url-id").text();

        if ($('#gzhotel-booking-extra-id').length > 0) {
            $('#gzhotel-booking-extra-id').dataTable();
        }

        $("#gz-abc-container-id").delegate('a.room_type_drop_down', 'click', function(e) {
            e.preventDefault();
            var room_type_id = $(this).attr('rel');
            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');
            $.ajax({
                type: "POST",
                data: {
                    room_type_id: room_type_id
                },
                url: url + "index.php?controller=GzExtra&action=get_extra_table",
                success: function(res) {
                    $("#prce-plan-id").html(res);
                    $("#hidden_room_type_id").val(room_type_id);
                    $('#gzhotel-booking-extra-id').dataTable();
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');
                }
            });
        });
        $("#frm_add_extra_id").ajaxForm({
            beforeSubmit: function() {
                if ($("#frm_add_extra_id").valid()) {
                    return true;
                } else {
                    return false;
                }
            },
            complete: function(res) {
                $("#prce-plan-id").html(res.responseText);
                $('#gzhotel-booking-extra-id').dataTable();
                $("#dialogAddExtra").dialog('close');
            }
        });

        if ($('#add_extra_id').length > 0) {
            if ($("#dialogAddExtra").length > 0) {
                $("#dialogAddExtra").dialog({
                    autoOpen: false,
                    resizable: true,
                    draggable: false,
                    height: 570,
                    width: 690,
                    modal: true,
                    buttons: {}
                });
            }
            $("#gz-abc-container-id").delegate("#add_extra_id", "click", function(e) {
                e.preventDefault();
                $('#dialogAddExtra').dialog('open');
            });
        }

        if ($("#dialogEditExtra").length > 0) {
            $("#dialogEditExtra").dialog({
                autoOpen: false,
                resizable: true,
                draggable: false,
                height: 570,
                width: 690,
                modal: true,
                buttons: {
                }
            });
        }
        $("#gz-abc-container-id").delegate(".icon-edit", "click", function(e) {
            e.preventDefault();

            $(".overlay").css('display', 'block');
            $(".loading-img").css('display', 'block');
            $.ajax({
                type: "POST",
                data: {
                    id: $(this).attr('rev'),
                    controller: 'GzExtra',
                    action: 'get_frm_edit_extra'
                },
                url: url + "index.php?controller=GzExtra&action=get_frm_edit_extra",
                success: function(res) {
                    $(".overlay").css('display', 'none');
                    $(".loading-img").css('display', 'none');

                    $("#dialogEditExtra").html(res);
                    $('#dialogEditExtra').dialog('open');

                    $("#frm_edit_extra_id").ajaxForm({
                        beforeSubmit: function() {
                            if ($("#frm_edit_extra_id").valid()) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        complete: function(res) {
                            $("#prce-plan-id").html(res.responseText);
                            $('#gzhotel-booking-extra-id').dataTable();
                            $("#dialogEditExtra").dialog('close');
                        }
                    });
                }
            });
        });

        $("#gz-abc-container-id").delegate('a.icon-delete', 'click', function(e) {
            e.preventDefault();
            $('#record_id').text($(this).attr('rev'));
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
                                    room_type_id: $("#room_type_id").text(),
                                    controller: 'GzExtra',
                                    action: 'delete'
                                },
                                url: url + "index.php?controller=GzExtra&action=delete",
                                success: function(res) {
                                    $("#prce-plan-id").html(res);
                                    $('#gzhotel-booking-extra-id').dataTable();
                                    $(".overlay").css('display', 'none');
                                    $(".loading-img").css('display', 'none');
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
        if ($("#dialogDeleteGallery").length > 0) {
            $("#dialogDeleteGallery").dialog({
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
                        $(".overlay").css('display', 'block');
                        $(".loading-img").css('display', 'block');
                        $.ajax({
                            type: "POST",
                            data: {
                                id: $('#record_id').text(),
                                controller: 'GzExtra',
                                action: 'deleteImage'
                            },
                            url: url + "index.php?controller=GzExtra&action=deleteImage",
                            success: function(res) {
                                $("#prce-plan-id").html(res);
                                $('#gzhotel-booking-extra-id').dataTable();
                                $(".overlay").css('display', 'none');
                                $(".loading-img").css('display', 'none');
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
        if ($("a.gallery-delete").length > 0) {
            $("#prce-plan-id").delegate("a.gallery-delete", 'click', function(e) {
                e.preventDefault();
                $('#record_id').text($(this).attr('rev'));
                $('#dialogDeleteGallery').dialog('open');
            });
        }
    });
}(jQuery));