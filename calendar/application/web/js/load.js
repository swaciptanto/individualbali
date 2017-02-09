/*!
 * Availability Booking Calendar PHP v1.0
 * http://gzscripts.com/home.html
 *
 * Copyright 2015, GzScript Ltd.
 *
 * Date: Mon Feb 11 23:42:58 2014 +0300
 */
var gz$ = jQuery.noConflict();
(function (window, undefined) {
    "use strict";
    window.GzAvailabilityCalendar = GzAvailabilityCalendar;
    var server = gz$("#server-id").text();
    gz$('.gzABCalCell').tooltipster({
        multiple: true,
        animation: 'grow',
        delay: 200
    });
    gz$(".gzABCalCellDivInner").tooltipster({multiple: true, trigger: "custom"});
    gz$("#gz-abc-main-container").delegate(".gz-current", "click", function (e) {
        e.preventDefault();
        if (gz$("#first-languages").is(':visible')) {
            gz$("#first-languages").slideUp();
        } else {
            gz$("#first-languages").slideDown();
        }
    }).delegate("#first-languages a", "click", function (e) {
        var lang = gz$(this).attr('rel');
        var request = gz$.ajax({
            type: "GET",
            data: gz$("#lang-frm-id").serialize(),
            url: server + "load.php?controller=GzFront&action=calendars&lang=" + lang,
            beforeSend: function () {
            },
            success: function (res) {
                gz$("#gz-abc-main-container").html(res);
                gz$.each(GzAvailabilityCalendarObj, function (key, value) {
                    GzAvailabilityCalendarObj[key] = new GzAvailabilityCalendar(value.options);
                });
            }
        });
    });
    function GzAvailabilityCalendar(options) {
        if (!(this instanceof GzAvailabilityCalendar)) {
            return new GzAvailabilityCalendar(options);
        }
        this.reset.call(this);
        this.init.call(this, options);
        return this;
    }
    GzAvailabilityCalendar.inObject = function (val, obj) {
        var key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) {
                if (obj[key] == val) {
                    return true;
                }
            }
        }
        return false;
    };
    GzAvailabilityCalendar.size = function (obj) {
        var key,
                size = 0;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) {
                size += 1;
            }
        }
        return size;
    };
    GzAvailabilityCalendar.compare = function (obj1, obj2) {
        var p;
        for (p in obj1) {
            if (obj2[p] === undefined) {
                return false;
            }
        }
        for (p in obj1) {
            if (obj1[p]) {
                switch (typeof (obj1[p])) {
                    case 'object':
                        if (!obj1[p].equals(obj2[p])) {
                            return false;
                        }
                        break;
                    case 'function':
                        if (obj2[p] === undefined || (p != 'equals' && obj1[p].toString() != obj2[p].toString())) {
                            return false;
                        }
                        break;
                    default:
                        if (obj1[p] != obj2[p]) {
                            return false;
                        }
                }
            } else {
                if (obj2[p])
                {
                    return false;
                }
            }
        }
        for (p in obj2) {
            if (obj1[p] === undefined) {
                return false;
            }
        }
        return true;
    };
    GzAvailabilityCalendar.prototype = {
        reset: function () {
            this.lang = null;
            this.$container = null;
            this.container = null;
            this.dateFrom = null;
            this.dateTo = null;
            this.adults = null;
            this.shildren = null;
            this.promo_code = null;
            this.start_date = null;
            this.end_date = null;
            this.selector = ".gzABCalCellNightsPendingPending, .gzABCalCellNightsPendingReserved, .gzABCalCellNightsReservedPending, .gzABCalCellNightsReservedReserved, .gzABCalCellPending, .gzABCalCellReserved, .gzABCalCellReservedNightsStart, .gzABCalCellReservedNightsEnd, .gzABCalCellPendingNightsStart, .gzABCalCellPendingNightsEnd, .gzABCalCellAvil";
            this.selectorClick = ".gzABCalCellReservedNightsStart, .gzABCalCellReservedNightsEnd, .gzABCalCellPendingNightsStart, .gzABCalCellPendingNightsEnd, .gzABCalCellAvil";
            this.selectedClassArr = [];
            this.selectedTimeArr = [];
            this.options = {};
            return this;
        },
        init: function (opts) {
            var self = this;
            this.options = opts;
            this.$gzCalContainer = gz$("#gz-abc-main-container-" + this.options.cal_id);
            this.$gzABCalendar = gz$("#gz-abc-calendar-container-" + this.options.cal_id);
            if (self.options.enable_booking === '1') {
                self.$gzABCalendar.on("click", self.selectorClick, function (e) {
                    if (self.start_date == null) {
                        self.selectedTimeArr = [];
                        self.selectedClassArr = [];
                        gz$(this).addClass('gzABCalFirstSelect');
                        gz$(this).addClass('gzABCalCellSelected');
                    }
                    self.select.call(self, this);
                }).on("mouseenter", this.selector, function (e) {
                    self.decorate.call(self, this);
                }).on("click", '.close', function (e) {
                    e.stopPropagation();
                    self.clearDate.call(self, this);
                    gz$(".gzABCalCellDivInner").tooltipster('hide');
                });
            }

            if (gz$("#startdate").length > 0) {

                self.bindDatePicker.call(self, this);
            }
            var $screen = gz$(document).width();
            if ($screen <= 991) {
                
                self.bindModalDatePicker.call(self, this);
            }
            //modified: add to only some action could activated this
            if (this.options.action == 'load_inquiry_form' || this.options.action == 'load_modal_form') {
                gz$(document).delegate(".btn-inquiry", "click", function (e) {
                    e.preventDefault();
                    self.sendInquiryForm.call(self, this);
                }).delegate("#currencies-value-id", "change", function (e) {

                    var currancy = gz$(this).val();

                    gz$.ajax({
                        type: "POST",
                        data: {
                            currencies: currancy
                        },
                        url: self.options.server + "load.php?controller=GzFront&action=changeCurrancy&cid=" + self.options.cal_id,
                        success: function (res) {
                            self.calculateInquiryFormPrice.call(self, this);
                            self.ABCCalendar.call(self, this);
                            self.convertCurrency.call(self, this);
                        }
                    });
                });
            }
            this.$gzCalContainer.delegate("#back_to_calendar_id", "click", function (e) {
                e.preventDefault();
                self.ABCCalendar.call(self, this);
            }).delegate("#booking_frm_btn_id", "click", function (e) {
                e.preventDefault();
                if (gz$("#gz-abc-form-id").valid()) {
                    self.ABCDetailForm.call(self, this);
                } else {
                    Ladda.stopAll();
                }
            }).delegate("#back_to_extra_frm_id", "click", function (e) {
                e.preventDefault();
                self.ABCExtraForm.call(self);
            }).delegate("#back_booking_frm_btn_id", "click", function (e) {
                e.preventDefault();
                self.ABCBackToExtraForm.call(self, this);
            }).delegate("#checkout_frm_btn_id", "click", function (e) {
                e.preventDefault();
                self.ABCCheckoutForm.call(self, this);
            }).delegate("#change-date-id", "click", function (e) {
                e.preventDefault();
                self.ABCCalendar.call(self, this);
            }).delegate("#terms_link", 'click', function (e) {
                e.preventDefault();
                gz$("#dialogTerms").dialog({
                    width: 'auto', // overcomes width:'auto' and maxWidth bug
                    maxWidth: 600,
                    height: 'auto',
                    modal: true,
                    fluid: true, //new option
                    resizable: false
                });
            }).delegate("[name^=extra]", "click", function (e) {
                self.calculatePrice.call(self, this);
            }).delegate("[name^=adults]", "change", function (e) {
                self.calculatePrice.call(self, this);
            }).delegate("[name^=children]", "change", function (e) {
                self.calculatePrice.call(self, this);
            }).delegate(".gzABCalCellArrow", "click", function (e) {
                self.options.month = gz$(this).attr('data-month');
                self.options.year = gz$(this).attr('data-year');
                self.decorate.call(self, this);
                self.ABCCalendar.call(self, this);
            }).delegate("#payment_method", "change", function (e) {

                if (gz$(this).val() == 'credit_card') {
                    gz$("#bank_acount_details").hide();
                    gz$("#credit_card_details").show();
                } else if (gz$(this).val() == 'bank_acount') {
                    gz$("#bank_acount_details").show();
                    gz$("#credit_card_details").hide();
                } else {
                    gz$("#bank_acount_details").hide();
                    gz$("#credit_card_details").hide();
                }
            }).delegate("#promo_code", "blur", function (e) {
                self.calculatePrice.call(self, this);
            });
        },
        bindModalDatePicker: function (e) {
            var self = this;

            gz$('#modal-startdate').datepicker({
                firstDay: gz$('#modal-startdate').attr('first-day'),
                dateFormat: gz$('#modal-startdate').attr('data-format'),
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate(gz$('#modal-startdate').attr('data-format'), date);
                    return [self.options.bookedDate.indexOf(string) == -1]
                },
                onSelect: function (selectedDate) {
                    self.calculateInquiryFormPrice.call(self, this);
                    gz$('#modal-finishdate').datepicker('option', 'minDate', selectedDate);
                }
            });
            gz$('#modal-finishdate').datepicker({
                firstDay: gz$('#modal-finishdate').attr('first-day'),
                dateFormat: gz$('#modal-startdate').attr('data-format'),
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate(gz$('#modal-startdate').attr('data-format'), date);
                    return [self.options.bookedDate.indexOf(string) == -1]
                },
                onSelect: function (selectedDate) {
                    self.calculateInquiryFormPrice.call(self, this);
                    gz$('#modal-startdate').datepicker('option', 'maxDate', selectedDate);
                }
            });
        },
        bindDatePicker: function (e) {
            var self = this;

            gz$('#startdate').datepicker({
                firstDay: gz$('#startdate').attr('first-day'),
                dateFormat: gz$('#startdate').attr('data-format'),
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate(gz$('#startdate').attr('data-format'), date);
                    return [self.options.bookedDate.indexOf(string) == -1]
                },
                onSelect: function (selectedDate) {
                    self.calculateInquiryFormPrice.call(self, this);
                    gz$('#finishdate').datepicker('option', 'minDate', selectedDate);
                }
            });
            gz$('#finishdate').datepicker({
                firstDay: gz$('#finishdate').attr('first-day'),
                dateFormat: gz$('#startdate').attr('data-format'),
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate(gz$('#startdate').attr('data-format'), date);
                    return [self.options.bookedDate.indexOf(string) == -1]
                },
                onSelect: function (selectedDate) {
                    self.calculateInquiryFormPrice.call(self, this);
                    gz$('#startdate').datepicker('option', 'maxDate', selectedDate);
                }
            });
        },
        sendInquiryForm: function (e) {
            var self = this;
            var $screen = gz$(document).width();
            if ($screen <= 991) {
                var frm = gz$("#modal-webform-client-form");
            } else {
                var frm = gz$("#webform-client-form");
            }
            frm.validate({
                rules: {
                    thefield: {digits: true, required: true}
                },
                tooltip_options: {
                    thefield: {placement: 'left'}
                }
            });
            if (frm.valid()) {
                gz$(".btn-inquiry").addClass("disabled");
                gz$.ajax({
                    type: "POST",
                    data: frm.serialize(),
                    url: self.options.server + "load.php?controller=GzFront&action=send_inquiry_form&cid=" + self.options.cal_id,
                    success: function (res) {
                        //modified: changed to redirect
                        /*
                        frm[0].reset();
                        gz$("#result-message-id").html(res);
                        gz$(".btn-inquiry").removeClass("disabled");

                        if ($screen <= 991) {
                            gz$('#myBooking').modal('hide');
                        }
                        */
                        /* Testing purpose
                        gz$(".btn-inquiry").removeClass("disabled");
                        alert(res);
                        */
                        gz$(location).attr('href', 'http://individualbali.com/inquiry-thank-you');
                    }
                });
            }
        },
        decorate: function (el) {
            var index;
            var hover_date;
            var element;
            var timestamp;
            var self = this;
            var $el = gz$(el);
            if (self.start_date !== null && self.end_date === null) {

                hover_date = parseInt($el.data("timestamp"), 10);
                //loop all cell on motnh
                self.$gzABCalendar.find(self.selector).each(function (i, item) {

                    element = gz$(item);
                    timestamp = parseInt(element.data("timestamp"), 10);
                    index = gz$.inArray(timestamp, self.selectedTimeArr);
                    if ((self.start_date > hover_date && timestamp <= self.start_date && timestamp >= hover_date) ||
                            (self.start_date < hover_date && timestamp >= self.start_date && timestamp <= hover_date)) {

                        if (element.attr("class").match("gzABCalCellReserved") !== null && element.attr("class").match("gzABCalCellReservedNights") === null) {
                            //log('Reserved');
                        } else if (element.attr("class").match("gzABCalCellPending") !== null && element.attr("class").match("gzABCalCellPendingNights") === null) {
                            //log('Pending');
                        } else if (element.attr("class").match("gzABCalCellNightsReservedPending") !== null) {
                            //log('Pending');
                        } else if (element.attr("class").match("gzABCalCellNightsPendingReserved") !== null) {
                            //log('Pending');
                        } else if (element.attr("class").match("gzABCalCellNightsPendingPending") !== null) {
                            //log('Pending');
                        } else if (element.attr("class").match("gzABCalCellNightsReservedReserved") !== null) {
                            //log('Pending');
                        } else {
                            element.addClass("gzABCalCellSelected");
                        }
                        if (index === -1) {
                            self.selectedTimeArr.push(timestamp);
                            self.selectedClassArr.push(element.attr("class"));
                        }
                    } else if (self.start_date != timestamp) {
                        element.removeClass("gzABCalCellSelected");
                        if (index !== -1) {
                            self.selectedTimeArr.splice(index, 1);
                            self.selectedClassArr.splice(index, 1);
                        }
                    }
                });
            }
        },
        select: function (el) {
            var daysCount, end_date, i, iCnt,
                    $el = gz$(el),
                    nightsStart = 0,
                    nightsEnd = 0,
                    pendingStart = false,
                    pendingEnd = false,
                    pendingReserved = false,
                    reservedPending = false,
                    partial = false,
                    timestamp = parseInt($el.data("timestamp"), 10);
            if (this.start_date === null && this.end_date === null) {
                // first click 
                this.firstClick.call(this, $el, timestamp);
                return;
            } else {
                // second click 
                end_date = timestamp;
                this.secondClick.call(this, $el, end_date);
                return;
            }
        },
        firstClick: function ($el, timestamp) {
            this.start_date = timestamp;
            this.$firstCell = $el;
        },
        secondClick: function ($el, timestamp) {
            var self = this;
            if (!this.end_date) {
                $el.addClass('gzABCalLastSelect');
                this.end_date = timestamp;
                this.$secondCell = $el;
                var check = true;
                if (this.end_date == this.start_date) {
                    check = false;
                } else {
                    check = this.checkDates.call(self, $el.get(0));
                }

                if (check) {
                    self.ABCExtraForm.call(self);
                } else {
                    $el.find(".gzABCalCellDivInner").tooltipster('show');
                }
            } else {
                self.clearDate.call(self, this);
            }
        },
        checkDates: function (el) {
            var self = this;
            var classArr = [],
                    tdays, end_dt, i, cnt,
                    $el = gz$(el),
                    nightsStart = 0,
                    nightsEnd = 0,
                    pStart = false,
                    pEnd = false,
                    pendingReserved = false,
                    reservedPending = false,
                    partial = false,
                    time = parseInt($el.data("time"), 10);
            //console.log(self.selectedTimeArr);
            classArr = self.selectedClassArr;
            for (i = 0, cnt = classArr.length; i < cnt; i += 1) {

                if (classArr[i].match("gzABCalCellReserved ") !== null) {
                    return false;
                }
                if (classArr[i].match("gzABCalCellPending ") !== null) {
                    return false;
                }
                if (classArr[i].match("gzABCalCellReservedNightsStart") !== null || classArr[i].match("gzABCalCellPendingNightsStart")) {
                    nightsStart += 1;
                }
                if (classArr[i].match("gzABCalCellReservedNightsEnd") !== null || classArr[i].match("gzABCalCellPendingNightsEnd")) {
                    nightsEnd += 1;
                }

                if (nightsStart > 1 || nightsEnd > 1) {
                    return false;
                }
            }

            var cnt = parseInt(classArr.length) - parseInt(1);
            //console.log(self.$firstCell.attr("class"));
            //console.log(self.$secondCell.attr("class"));

            if (parseInt(self.start_date) > parseInt(self.end_date)) {
                if (nightsEnd == 1 && self.$secondCell.attr("class").match("gzABCalCellReservedNightsEnd") === null && self.$secondCell.attr("class").match("gzABCalCellPendingNightsEnd") === null) {

                    //console.log(1);
                    return false;
                }
                if ((nightsStart == 1 && (self.$firstCell.attr("class").match("gzABCalCellReservedNightsStart") === null && self.$firstCell.attr("class").match("gzABCalCellPendingNightsStart") === null))) {

                    //console.log(2);
                    return false;
                }

                if (self.$firstCell.attr("class").match("gzABCalCellReservedNightsEnd") !== null) {
                    return false;
                }
                if (self.$firstCell.attr("class").match("gzABCalCellPendingNightsEnd") !== null) {
                    return false;
                }
            } else {
                if (nightsEnd == 1 && self.$firstCell.attr("class").match("gzABCalCellReservedNightsEnd") === null && self.$firstCell.attr("class").match("gzABCalCellPendingNightsEnd") === null) {
                    //console.log(3);
                    return false;
                }
                if ((nightsStart == 1 && (self.$secondCell.attr("class").match("gzABCalCellReservedNightsStart") === null && self.$secondCell.attr("class").match("gzABCalCellPendingNightsStart") === null))) {
                    //console.log(4);
                    return false;
                }

                if (self.$secondCell.attr("class").match("gzABCalCellReservedNightsEnd") !== null) {
                    return false;
                }
                if (self.$secondCell.attr("class").match("gzABCalCellPendingNightsEnd") !== null) {
                    return false;
                }
            }

            return true;
        },
        clearDate: function () {
            var self = this;
            self.start_date = null;
            self.end_date = null;
            self.selectedTime = [];
            self.selectedElement = [];
            self.$gzABCalendar.find("td").removeClass("gzABCalCellSelected gzABCalFirstSelect");
            self.$gzABCalendar.find("td").removeClass("gzABCalCellSelected gzABCalLastSelect");
        },
        ABCExtraForm: function () {
            var self = this;
            var $screen = gz$(document).width();

            if ($screen <= 991) {
                gz$('#myBooking').modal('show');
                self.bindModalDatePicker.call(self, this);

                gz$.ajax({
                    type: "POST",
                    data: {
                        start_date: self.start_date,
                        end_date: self.end_date,
                        cal_id: self.options.cal_id
                    },
                    dataType: 'json',
                    url: self.options.server + "load.php?controller=GzFront&action=get_json_date&cid=" + self.options.cal_id,
                    success: function (res) {

                        gz$("#modal-startdate").val(res.start_date);
                        gz$("#modal-finishdate").val(res.end_date);

                        self.calculateInquiryFormPrice.call(self, this);
                    }
                });
            } else {
                gz$.ajax({
                    type: "POST",
                    data: {
                        start_date: self.start_date,
                        end_date: self.end_date,
                        cal_id: self.options.cal_id
                    },
                    dataType: 'json',
                    url: self.options.server + "load.php?controller=GzFront&action=get_json_date&cid=" + self.options.cal_id,
                    success: function (res) {

                        gz$("#startdate").val(res.start_date);
                        gz$("#finishdate").val(res.end_date);

                        self.calculateInquiryFormPrice.call(self, this);
                    }
                });
            }

            /*gz$.ajax({
             type: "POST",
             data: {
             start_date: self.start_date,
             end_date: self.end_date,
             cal_id: self.options.cal_id
             },
             url: self.options.server + "load.php?controller=GzFront&action=extra_form&cid=" + self.options.cal_id,
             success: function (res) {
             gz$(self.$gzABCalendar).html(res);
             self.galleryBind.call(self);
             Ladda.bind('#booking_frm_btn_id');
             Ladda.bind('#back_to_calendar_id');
             self.clearDate.call(self, this);
             gz$("#gz-abc-form-id").validate({
             rules: {
             adults: {
             required: "#children:blank"
             },
             children: {
             required: "#adults:blank"
             }
             }
             });
             }
             });*/
        },
        ABCBackToExtraForm: function () {
            var self = this;
            var frm = gz$("#gz-abc-form-id");
            gz$.ajax({
                type: "POST",
                data: frm.serialize(),
                url: self.options.server + "load.php?controller=GzFront&action=extra_form&cid=" + self.options.cal_id,
                success: function (res) {
                    gz$(self.$gzABCalendar).html(res);
                    self.galleryBind.call(self);
                    Ladda.bind('#booking_frm_btn_id');
                    Ladda.bind('#back_to_calendar_id');
                    gz$("#gz-abc-form-id").validate({
                        rules: {
                            adults: {
                                required: "#children:blank"
                            },
                            children: {
                                required: "#adults:blank"
                            }
                        }
                    });
                }
            });
        },
        ABCCalendar: function () {
            var self = this;
            //self.clearDate.call(self, this);

            gz$.ajax({
                type: "POST",
                data: {
                    cal_id: self.options.cal_id,
                    month: self.options.month,
                    year: self.options.year
                },
                url: self.options.server + "load.php?controller=GzFront&action=calendar&cid=" + self.options.cal_id + "&view_month=" + self.options.view_month,
                success: function (res) {
                    gz$(self.$gzABCalendar).html(res);
                    if (self.start_date != null) {

                        var $this = gz$("[data-timestamp='" + self.start_date + "']");
                        $this.addClass('gzABCalFirstSelect');
                        $this.addClass('gzABCalCellSelected');
                    }
                    //modified: add new block if 
                    //used to refresh calendar when currency changed or calendar month changed
                    if (self.end_date != null) {
                        var timestamp_one_day = 24 * 60 * 60;
                        var i;
                        for (i = self.start_date; i <= self.end_date; i = i + timestamp_one_day) {
                            //var $this = $('#' + self.options.cal_id.toString() + '_' + i.toString());
                            var $this = gz$("[data-timestamp='" + i + "']");
                            if (i == self.end_date) {
                                $this.addClass('gzABCalLastSelect');
                            }
                            $this.addClass('gzABCalCellSelected');
                        }
                    }

                    gz$('.gzABCalCell').tooltipster({
                        multiple: true,
                        animation: 'grow',
                        delay: 200
                    });
                    gz$(".gzABCalCellDivInner").tooltipster({multiple: true, trigger: "custom"});
                }
            })
        },
        ABCDetailForm: function () {
            var self = this;
            var frm = gz$("#gz-abc-form-id");
            gz$.ajax({
                type: "POST",
                data: frm.serialize(),
                url: self.options.server + "load.php?controller=GzFront&action=booking_details&cid=" + self.options.cal_id,
                success: function (res) {
                    gz$(self.$gzABCalendar).html(res);
                    Ladda.bind('#back_booking_frm_btn_id');
                    Ladda.bind('#checkout_frm_btn_id');
                }
            });
        },
        ABCCheckoutForm: function () {
            var self = this;
            var frm = gz$("#gz-abc-form-id");
            gz$.ajax({
                type: "POST",
                data: frm.serialize(),
                url: self.options.server + "load.php?controller=GzFront&action=checkout&cid=" + self.options.cal_id,
                success: function (res) {
                    gz$(self.$gzABCalendar).html(res);
                    if (gz$("#gz-hotel-booking-pay-frm-id").length > 0) {
                        gz$("#gz-hotel-booking-pay-frm-id").submit();
                    }
                }
            });
        },
        galleryBind: function () {
            gz$(".gz-gallery-first").each(function (i, obj) {

                var gclass = gz$(this).attr('rel');
                gz$("." + gclass + "").colorbox({rel: gclass, transition: "none", width: "85%", height: "85%"});
            });
        },
        calculateInquiryFormPrice: function () {
            var self = this;

            var $screen = gz$(document).width();

            if ($screen <= 991) {
                var frm = gz$('#modal-webform-client-form');
            } else {
                var frm = gz$('#webform-client-form');
            }
            
            //modified: add new
            if ($screen <= 991) {
                if (gz$("#modal-startdate").val() != '' && gz$("#modal-finishdate").val() != '') {
                    gz$("#modal-total").html('Calculating Total...');
                    gz$("#modal-total-with-tax").html('');
                } else {
                    gz$("#modal-total").html('');
                    gz$("#modal-total-with-tax").html('');
                    return false;
                }
            } else {
                if (gz$("#startdate").val() != '' && gz$("#finishdate").val() != '') {
                    gz$("#total").html('Calculating Total...');
                    gz$("#total-with-tax").html('');
                } else {
                    gz$("#total").html('');
                    gz$("#total-with-tax").html('');
                    return false;
                }
            }

            gz$.ajax({
                type: "POST",
                dataType: 'json',
                data: frm.serialize(),
                url: self.options.server + "index.php?controller=GzFront&action=calculateInquiryFormPrice&cid=" + self.options.cal_id,
                success: function (res) {
                    if ($screen <= 991) {
                        gz$("#modal-total").html(res.formated_total);
                        //modified: add new
                        gz$("#modal-total-with-tax").html(res.formated_total_with_tax);
                    } else {
                        gz$("#total").html(res.formated_total);
                        //gz$("#total").text(res.formated_total);
                        //modified: add new
                        gz$("#total-with-tax").html(res.formated_total_with_tax);
                    }
                }
            });
        },
        calculatePrice: function () {
            var self = this;
            var frm = gz$('#gz-abc-form-id');
            gz$.ajax({
                type: "POST",
                dataType: 'json',
                data: frm.serialize(),
                url: self.options.server + "index.php?controller=GzFront&action=calculatePrice&cid=" + self.options.cal_id,
                success: function (json) {

                    if (gz$("#calendars_price").length > 0) {
                        gz$("#calendars_price").html(json.formated_calendars_price);
                    }
                    if (gz$("#extra_price").length > 0) {
                        gz$("#extra_price").html(json.formated_extra_price);
                    }
                    if (gz$("#tax").length > 0) {
                        gz$("#tax").html(json.formated_tax);
                    }
                    if (gz$("#security").length > 0) {
                        gz$("#security").html(json.formated_security);
                    }
                    if (gz$("#deposit").length > 0) {
                        gz$("#deposit").html(json.formated_deposit);
                    }
                    if (gz$("#discount").length > 0) {
                        gz$("#discount").html(json.formated_discount);
                    }
                    if (gz$("#total").length > 0) {
                        gz$("#total").html(json.formated_total);
                    }
                }
            });
        },
        convertCurrency: function () {
            var self = this;
            var $screen = gz$(document).width();
            if ($screen <= 991) {
                var frm = gz$('#modal-webform-client-form');
            } else {
                var frm = gz$('#webform-client-form');
            }
            $(".title-low-rate").each(function() {
                var element_low_rate = $(this);
                var villa_node_id = element_low_rate.find(":hidden").val();
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    data: frm.serialize(),
                    url: self.options.server
                            + "index.php?controller=GzFront&action=convertCurrency"
                            + "&vnid=" + villa_node_id,
                    success: function (res) {
                        var title_low_rate = '';
                        var country_code = res.country_code;
                        var currency_symbol = res.currency_symbol;
                        var rate_low = res.rate_low;
                        if (country_code !== '') {
                            title_low_rate = country_code + ' ';
                        }
                        title_low_rate += currency_symbol + ' ' + rate_low;
                        element_low_rate.find("span").html(title_low_rate);
                    }
                });
            });
        }
    }

    window.GzAvailabilityCalendar = GzAvailabilityCalendar;
})(window);
