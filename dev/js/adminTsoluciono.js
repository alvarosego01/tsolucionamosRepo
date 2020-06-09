
/**
 * wickedpicker v0.4.1 - A simple jQuery timepicker.
 * Copyright (c) 2015-2016 Eric Gagnon - http://github.com/wickedRidge/wickedpicker
 * License: MIT
 */

(function ($, window, document) {

    "use strict";

    if (typeof String.prototype.endsWith != 'function') {
        /*
         * Checks if this string end ends with another string
         *
         * @param {string} the string to be checked
         *
         * @return {bool}
         */
        String.prototype.endsWith = function (string) {
            return string.length > 0 && this.substring(this.length - string.length, this.length) === string;
        }
    }

    /*
     * Returns if the user agent is mobile
     *
     * @return {bool}
     */
    var isMobile = function () {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    };

    var today = new Date();

    var pluginName = "wickedpicker",
        defaults = {
            now: today.getHours() + ':' + today.getMinutes(),
            twentyFour: false,
            upArrow: 'wickedpicker__controls__control-up',
            downArrow: 'wickedpicker__controls__control-down',
            close: 'wickedpicker__close',
            hoverState: 'hover-state',
            title: 'Timepicker',
            showSeconds: false,
            timeSeparator: ' : ',
            secondsInterval: 1,
            minutesInterval: 1,
            beforeShow: null,
            afterShow: null,
            show: null,
            clearable: false,
            closeOnClickOutside: true,
            onClickOutside: function() {},
        };

    /*
     * @param {object} The input object the timepicker is attached to.
     * @param {object} The object containing options
     */
    function Wickedpicker(element, options) {
        this.element = $(element);
        this.options = $.extend({}, defaults, options);

        this.element.addClass('hasWickedpicker');
        this.element.attr('onkeypress', 'return false;');
        this.element.attr('aria-showingpicker', 'false');
        this.createPicker();
        this.timepicker = $('.wickedpicker');
        this.up = $('.' + this.options.upArrow.split(/\s+/).join('.'));
        this.down = $('.' + this.options.downArrow.split(/\s+/).join('.'));
        this.separator = $('.wickedpicker__controls__control--separator');
        this.hoursElem = $('.wickedpicker__controls__control--hours');
        this.minutesElem = $('.wickedpicker__controls__control--minutes');
        this.secondsElem = $('.wickedpicker__controls__control--seconds');
        this.meridiemElem = $('.wickedpicker__controls__control--meridiem');
        this.close = $('.' + this.options.close.split(/\s+/).join('.'));

        //Create a new Date object based on the default or passing in now value
        var time = this.timeArrayFromString(this.options.now);
        this.options.now = new Date(today.getFullYear(), today.getMonth(), today.getDate(), time[0], time[1], time[2]);
        this.selectedHour = this.parseHours(this.options.now.getHours());
        this.selectedMin = this.parseSecMin(this.options.now.getMinutes());
        this.selectedSec = this.parseSecMin(this.options.now.getSeconds());
        this.selectedMeridiem = this.parseMeridiem(this.options.now.getHours());
        this.setHoverState();
        this.attach(element);
        this.setText(element);
    }

    $.extend(Wickedpicker.prototype, {

        /*
         * Show given input's timepicker
         *
         * @param {object} The input being clicked
         */
        showPicker: function (element) {
            //If there is a beforeShow function, then call it with the input calling the timepicker and the
            // timepicker itself
            if (typeof this.options.beforeShow === 'function') {
                this.options.beforeShow(element, this.timepicker);
            }
            var timepickerPos = $(element).offset();

            $(element).attr({'aria-showingpicker': 'true', 'tabindex': -1});
            this.setText(element);
            this.showHideMeridiemControl();
            if (this.getText(element) !== this.getTime()) {

                // Check meridiem
                var text = this.getText(element);
                var re = /\s[ap]m$/i;
                var meridiem = re.test(text) ? text.substr(-2, 2) : null;
                var inputTime = text.replace(re, '').split(this.options.timeSeparator);
                var newTime = {};
                newTime.hours = inputTime[0];
                newTime.minutes = inputTime[1];
                newTime.meridiem = meridiem;
                if (this.options.showSeconds) {
                    newTime.seconds = inputTime[2];
                }
                this.setTime(newTime);
            }
            this.timepicker.css({
                'z-index': this.element.css('z-index') + 1,
                position: 'absolute',
                left: timepickerPos.left,
                top: timepickerPos.top + $(element)[0].offsetHeight
            }).show();
            //If there is a show function, then call it with the input calling the timepicker and the
            // timepicker itself
            if (typeof this.options.show === 'function') {
                this.options.show(element, this.timepicker);
            }

            this.handleTimeAdjustments(element);
        },

        /*
         * Hides the timepicker that is currently shown if it is not part of the timepicker
         *
         * @param {Object} The DOM object being clicked on the page
         *
         * BeinnLora: added trigger function to call on closing/hiding timepicker.
         */
        hideTimepicker: function (element) {
            this.timepicker.hide();
            if (typeof this.options.afterShow === 'function') {
                this.options.afterShow(element, this.timepicker);
            }
            var pickerHidden = {
                start: function () {
                    var setShowPickerFalse = $.Deferred();
                    $('[aria-showingpicker="true"]').attr('aria-showingpicker', 'false');
                    return setShowPickerFalse.promise();
                }
            };

            function setTabIndex(index) {
                setTimeout(function () {
                    $('[aria-showingpicker="false"]').attr('tabindex', index);
                }, 400);
            }

            pickerHidden.start().then(setTabIndex(0));
        },

        /*
         * Create a new timepicker. A single timepicker per page
         */
        createPicker: function () {
            if ($('.wickedpicker').length === 0) {
                var picker = '<div class="wickedpicker"><p class="wickedpicker__title">' + this.options.title + '<span class="wickedpicker__close"></span></p><ul class="wickedpicker__controls"><li class="wickedpicker__controls__control"><span class="' + this.options.upArrow + '"></span><span class="wickedpicker__controls__control--hours" tabindex="-1">00</span><span class="' + this.options.downArrow + '"></span></li><li class="wickedpicker__controls__control--separator"><span class="wickedpicker__controls__control--separator-inner">:</span></li><li class="wickedpicker__controls__control"><span class="' + this.options.upArrow + '"></span><span class="wickedpicker__controls__control--minutes" tabindex="-1">00</span><span class="' + this.options.downArrow + '"></span></li>';
                if (this.options.showSeconds) {
                    picker += '<li class="wickedpicker__controls__control--separator"><span class="wickedpicker__controls__control--separator-inner">:</span></li><li class="wickedpicker__controls__control"><span class="' + this.options.upArrow + '"></span><span class="wickedpicker__controls__control--seconds" tabindex="-1">00</span><span class="' + this.options.downArrow + '"></span> </li>';
                }
                picker += '<li class="wickedpicker__controls__control"><span class="' + this.options.upArrow + '"></span><span class="wickedpicker__controls__control--meridiem" tabindex="-1">AM</span><span class="' + this.options.downArrow + '"></span></li></ul></div>';
                $('body').append(picker);
                this.attachKeyboardEvents();
            }
        },

        /*
         * Hides the meridiem control if this timepicker is a 24 hour clock
         */
        showHideMeridiemControl: function () {
            if (this.options.twentyFour === false) {
                $(this.meridiemElem).parent().show();
            }
            else {
                $(this.meridiemElem).parent().hide();
            }
        },

        /*
         * Hides the seconds control if this timepicker has showSeconds set to true
         */
        showHideSecondsControl: function () {
            if (this.options.showSeconds) {
                $(this.secondsElem).parent().show();
            }
            else {
                $(this.secondsElem).parent().hide();
            }
        },

        /*
         * Bind the click events to the input
         *
         * @param {object} The input element
         */
        attach: function (element) {
            var self = this;

            if (this.options.clearable) {
                self.makePickerInputClearable(element);
            }

            $(element).attr('tabindex', 0);
            $(element).on('click focus', function (event) {
                //Prevent multiple firings
                if ($(self.timepicker).is(':hidden')) {
                  self.showPicker($(this));
                  window.lastTimePickerControl = $(this); //Put the reference on this timepicker into global scope for unsing that in afterShow function
                  $(self.hoursElem).focus();
                }
            });


            //Handle click events for closing Wickedpicker
            var clickHandler = function (event) { //TODO: Fix double firing
                //Only fire the hide event when you have to
                if ($(self.timepicker).is(':visible')) {
                    //Clicking the X
                    if ($(event.target).is(self.close)) {
                      self.hideTimepicker(window.lastTimePickerControl);
                    } else if ($(event.target).closest(self.timepicker).length || $(event.target).closest($('.hasWickedpicker')).length) { //Clicking the Wickedpicker or one of it's inputs
                      event.stopPropagation();
                    } else {   //Everything else
                      if (typeof self.options.onClickOutside === 'function') {
                        self.options.onClickOutside();
                      }
                      else {
                        console.warn("Type of onClickOutside must be a function");
                      }

                      if (!self.options.closeOnClickOutside) {
                        return;
                      }
                      self.hideTimepicker(window.lastTimePickerControl);
                    }
                    window.lastTimePickerControl = null;
                }
            };
            $(document).off('click', clickHandler).on('click', clickHandler);
        },

        /**
         * Added keyboard functionality to improve usabil
         */
        attachKeyboardEvents: function () {
            $(document).on('keydown', $.proxy(function (event) {
                switch (event.keyCode) {
                    case 9:
                        if (event.target.className !== 'hasWickedpicker') {
                            $(this.close).trigger('click');
                        }
                        break;
                    case 27:
                        $(this.close).trigger('click');
                        break;
                    case 37: //Left arrow
                        if (event.target.className !== this.hoursElem[0].className) {
                            $(event.target).parent().prevAll('li').not(this.separator.selector).first().children()[1].focus();
                        } else {
                            $(event.target).parent().siblings(':last').children()[1].focus();
                        }
                        break;
                    case 39: //Right arrow
                        if (event.target.className !== this.meridiemElem[0].className) {
                            $(event.target).parent().nextAll('li').not(this.separator.selector).first().children()[1].focus();
                        } else {
                            $(event.target).parent().siblings(':first').children()[1].focus();
                        }
                        break;
                    case 38: //Up arrow
                        $(':focus').prev().trigger('click');
                        break;
                    case 40: //Down arrow
                        $(':focus').next().trigger('click');
                        break;
                    default:
                        break;
                }
            }, this));
        },

        /*
         * Set the time on the timepicker
         *
         * @param {object} The date being set
         */
        setTime: function (time) {
            this.setHours(time.hours);
            this.setMinutes(time.minutes);
            this.setMeridiem(time.meridiem);
            if (this.options.showSeconds) {
                this.setSeconds(time.seconds);
            }
        },

        /*
         * Get the time from the timepicker
         */
        getTime: function () {
            return [this.formatTime(this.getHours(), this.getMinutes(), this.getMeridiem(), this.getSeconds())];
        },

        /*
         * Set the timpicker's hour(s) value
         *
         * @param {string} hours
         */
        setHours: function (hours) {
            var hour = new Date();
            hour.setHours(hours);
            var hoursText = this.parseHours(hour.getHours());
            this.hoursElem.text(hoursText);
            this.selectedHour = hoursText;
        },

        /*
         * Get the hour(s) value from the timepicker
         *
         * @return {integer}
         */
        getHours: function () {
            var hours = new Date();
            hours.setHours(this.hoursElem.text());
            return hours.getHours();
        },

        /*
         * Returns the correct hour value based on the type of clock, 12 or 24 hour
         *
         * @param {integer} The hours value before parsing
         *
         * @return {string|integer}
         */
        parseHours: function (hours) {
            return (this.options.twentyFour === false) ? ((hours + 11) % 12) + 1 : (hours < 10) ? '0' + hours : hours;
        },

        /*
         * Sets the timpicker's minutes value
         *
         * @param {string} minutes
         */
        setMinutes: function (minutes) {
            var minute = new Date();
            minute.setMinutes(minutes);
            var minutesText = minute.getMinutes();
            var min = this.parseSecMin(minutesText);
            this.minutesElem.text(min);
            this.selectedMin = min;
        },

        /*
         * Get the minutes value from the timepicker
         *
         * @return {integer}
         */
        getMinutes: function () {
            var minutes = new Date();
            minutes.setMinutes(this.minutesElem.text());
            return minutes.getMinutes();
        },


        /*
         * Return a human-readable minutes/seconds value
         *
         * @param {string} value seconds or minutes
         *
         * @return {string|integer}
         */
        parseSecMin: function (value) {
            return ((value < 10) ? '0' : '') + value;
        },

        /*
         * Set the timepicker's meridiem value, AM or PM
         *
         * @param {string} The new meridiem
         */
        setMeridiem: function (inputMeridiem) {
            var newMeridiem = '';
            if (inputMeridiem === undefined) {
                var meridiem = this.getMeridiem();
                newMeridiem = (meridiem === 'PM') ? 'AM' : 'PM';
            } else {
                newMeridiem = inputMeridiem;
            }
            this.meridiemElem.text(newMeridiem);
            this.selectedMeridiem = newMeridiem;
        },

        /*
         * Get the timepicker's meridiem value, AM or PM
         *
         * @return {string}
         */
        getMeridiem: function () {
            return this.meridiemElem.text();
        },

        /*
         * Set the timepicker's seconds value
         *
         * @param {string} seconds
         */
        setSeconds: function (seconds) {
            var second = new Date();
            second.setSeconds(seconds);
            var secondsText = second.getSeconds();
            var sec = this.parseSecMin(secondsText);
            this.secondsElem.text(sec);
            this.selectedSec = sec;
        },

        /*
         * Get the timepicker's seconds value
         *
         * return {string}
         */
        getSeconds: function () {
            var seconds = new Date();
            seconds.setSeconds(this.secondsElem.text());
            return seconds.getSeconds();
        },

        /*
         * Get the correct meridiem based on the hours given
         *
         * @param {string|integer} hours
         *
         * @return {string}
         */
        parseMeridiem: function (hours) {
            return (hours > 11) ? 'PM' : 'AM';
        },

        /*
         * Handles time incrementing and decrementing and passes
         * the operator, '+' or '-', the input to be set after the change
         * and the current arrow clicked, to decipher if hours, ninutes, or meridiem.
         *
         * @param {object} The input element
         */
        handleTimeAdjustments: function (element) {
            var timeOut = 0;
            //Click and click and hold timepicker incrementer and decrementer
            $(this.up).add(this.down).off('mousedown click touchstart').on('mousedown click', {
                'Wickedpicker': this,
                'input': element
            }, function (event) {
                if(event.which!=1) return false;
                var operator = (this.className.indexOf('up') > -1) ? '+' : '-';
                var passedData = event.data;
                if (event.type == 'mousedown') {
                    timeOut = setInterval($.proxy(function (args) {
                        args.Wickedpicker.changeValue(operator, args.input, this);
                    }, this, {'Wickedpicker': passedData.Wickedpicker, 'input': passedData.input}), 200);
                } else {
                    passedData.Wickedpicker.changeValue(operator, passedData.input, this);
                }
            }).bind('mouseup touchend', function () {
                clearInterval(timeOut);
            });
        },

        /*
         * Change the timepicker's time base on what is clicked
         *
         * @param {string} The + or - operator
         * @param {object} The timepicker's associated input to be set post change
         * @param {object} The DOM arrow object clicked, determines if it is hours,
         * minutes, or meridiem base on the operator and its siblings
         */
        changeValue: function (operator, input, clicked) {
            var target = (operator === '+') ? clicked.nextSibling : clicked.previousSibling;
            var targetClass = $(target).attr('class');
            if (targetClass.endsWith('hours')) {
                this.setHours(eval(this.getHours() + operator + 1));
            } else if (targetClass.endsWith('minutes')) {
                this.setMinutes(eval(this.getMinutes() + operator + this.options.minutesInterval));
            } else if (targetClass.endsWith('seconds')) {
                this.setSeconds(eval(this.getSeconds() + operator + this.options.secondsInterval));
            } else {
                this.setMeridiem();
            }
            this.setText(input);
        },


        /*
         * Sets the give input's text to the current timepicker's time
         *
         * @param {object} The input element
         */
        setText: function (input) {
            $(input).val(this.formatTime(this.selectedHour, this.selectedMin, this.selectedMeridiem, this.selectedSec)).change();
        },

        /*
         * Get the given input's value
         *
         * @param {object} The input element
         *
         * @return {string}
         */
        getText: function (input) {
            return $(input).val();
        },

        /*
         * Returns the correct time format as a string
         *
         * @param {string} hour
         * @param {string} minutes
         * @param {string} meridiem
         *
         * @return {string}
         */
        formatTime: function (hour, min, meridiem, seconds) {
            var formattedTime = hour + this.options.timeSeparator + min;
            if (this.options.showSeconds) {
                formattedTime += this.options.timeSeparator  + seconds;
            }
            if (this.options.twentyFour === false) {
                formattedTime += ' ' + meridiem;
            }
            return formattedTime;
        },

        /**
         *  Apply the hover class to the arrows and close icon fonts
         */
        setHoverState: function () {
            var self = this;
            if (!isMobile()) {
                $(this.up).add(this.down).add(this.close).hover(function () {
                    $(this).toggleClass(self.options.hoverState);
                });
            }
        },

        /**
         * Wrapping the given input field with the clearable container
         * , add a span that will contain the x, and bind the clear
         * input event to the span
         *
         * @param input
         */
        makePickerInputClearable: function(input) {
            $(input).wrap('<div class="clearable-picker"></div>').after('<span data-clear-picker>&times;</span>');

            //When the x is clicked, clear its sibling input field
            $('[data-clear-picker]').on('click', function(event) {
               $(this).siblings('.hasWickedpicker').val('');
            });
        },

        /**
         * Convert the options time string format
         * to an array
         *
         * returns => [hours, minutes, seconds]
         *
         * @param stringTime
         * @returns {*}
         */
        timeArrayFromString: function (stringTime) {
            if (stringTime.length) {
                var time = stringTime.split(':');
                time[2] = (time.length < 3) ? '00' : time[2];
                return time;
            }
            return false;
        },

        //public functions
        /*
         * Returns the requested input element's value
         */
        _time: function () {
            var inputValue = $(this.element).val();
            return (inputValue === '') ? this.formatTime(this.selectedHour, this.selectedMin, this.selectedMeridiem, this.selectedSec) : inputValue;
        },
        _hide: function() {
            this.hideTimepicker(this.element);
        }
    });

    //optional index if multiple inputs share the same class
    $.fn[pluginName] = function (options, index) {
        if (!$.isFunction(Wickedpicker.prototype['_' + options])) {
            return this.each(function () {
                if (!$.data(this, "plugin_" + pluginName)) {
                    $.data(this, "plugin_" + pluginName, new Wickedpicker(this, options));
                }
            });
        }
        else if ($(this).hasClass('hasWickedpicker')) {
            if (index !== undefined) {
                return $.data($(this)[index], 'plugin_' + pluginName)['_' + options]();
            }
            else {
                return $.data($(this)[0], 'plugin_' + pluginName)['_' + options]();
            }
        }
    };

})(jQuery, window, document);


// definición de click para el nav
// if(window.location.hash){

//     var r = jQuery('.tabsAdminTsoluciono .navOpc li');
//     jQuery.each(r, function (indexInArray, valueOfElement) {
//          var l = jQuery('a', valueOfElement).attr('href');
//          if(l == window.location.hash){

//             jQuery(valueOfElement).addClass('active');

//             jQuery('.tabsAdminTsoluciono section#content'+indexInArray).addClass('active');
//         }
//     });

// }
jQuery('.tabsAdminTsoluciono .navOpc li').click(function (e) {
    // e.preventDefault();
    console.log(e);
    jQuery('.tabsAdminTsoluciono .navOpc li').removeClass('active');
    jQuery(e.currentTarget).addClass('active');
    var r = jQuery('a', e.currentTarget).attr('href');
    r = r.split("#tab");
    jQuery('.tabsAdminTsoluciono section').removeClass('active');
    jQuery('.tabsAdminTsoluciono section#content' + r[1]).addClass('active');
});


// ---------------------------------------
var viewReasonsCandContent = jQuery('#viewReasonsCand').clone()[0];

jQuery('#viewReasonsCand').remove();

var formFirma = jQuery('#formFirma > form').clone()[0];

jQuery('#formFirma').remove();


var formSelectPostulate = jQuery('#formTipoEntrevista > form').clone()[0];

jQuery('#formTipoEntrevista').remove();

var templateVerifVacantAdmin = jQuery('#templateVerifVacantAdmin .info').clone()[0];

jQuery('#templateVerifVacantAdmin').remove();

var templateOptionsVacantAdmin = jQuery('#templateOptionsVacantAdmin .info').clone()[0];

jQuery('#templateOptionsVacantAdmin').remove();

var formDetailsChangeDateAdmin = jQuery('#formDetailsChangeDateAdmin > form').clone()[0];
jQuery('#formDetailsChangeDateAdmin').remove();





if (jQuery('div#formDetailsIntegrateCand').length > 0) {
    console.log('existe esto');

    var formDetailsIntegrateCand = jQuery('#formDetailsIntegrateCand > form').clone()[0];
    jQuery('#formDetailsIntegrateCand').remove();

    console.log(formDetailsIntegrateCand)

}


var dataSelectPostulant = {
    "tipoEntrevista": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar el tipo de entrevista'
            }
        }
    },
    "datoEntrevista": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar este dato'
            }
        }
    },
    "date": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes seleccionar una fecha'
            }
        }
    },
    "hora": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes seleccionar una hora'
            }
        }
    }

}

var reprogramaEntrevista = {
    "date": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar una nueva fecha'
            }
        }
    },
    "hora": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar una nueva hora'
            }
        }
    }
}

var newIntegrateCand = {

    "candidatos": {
        field: 'select',
        required: true,
        valid: {
            null: {
                message: 'Debes seleccionar a un candidato'
            }
        }
    }
}

// var dataAdminVacant = {
//     "necesidades": {
//         field: 'textarea',
//         required: true,
//         valid: {
//             null: {
//                 message: 'Debes escribir las necesidades basicas que se entienden de la vacante'
//             }
//         }
//     },
//     "notaAdmin": {
//         field: 'textarea',
//         required: true,
//         valid: {
//             null: {
//                 message: 'Debes escribir una descripción de la oferta laboral'
//             }
//         }
//     },
//     "estadoPublico": {

//     },
//     "estadoAprobado": {

//     }
// }

var configAdmin = {
    'directiva': {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Ingresa el nombre del respnsable directivo'
            }
        }
    },
    'directivaFirma': {
        field: 'textfield',
        required: true,
        valid: {
            nullSignature: {
                message: 'Hace falta especificar una firma'
            }
        }

    }
}

var postuladosElegidos = [];
var serialpEntrevistas = '';

var boton = '';


// acomodando
// Ejecución de selección al proceso de entrevistas de un postulante
function AdminAddPostulant(idpostulant, serial) {

    var info = jQuery('.swal-modal.formSelectPostulate form.formData');
    var values = [];
    var error = false;

    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });

    if (error != true) {
        info[0].reset();

        var obj = _.extend({}, values);

        console.log(obj);

        var i = {
            0: {
                'info': obj,
                'idpostulant': idpostulant
            }
        }

        var p = {
            'candidatos': i,
            'serial': serial
        }

        console.log(obj);

        //window.location.href
var url = new URL(window.location.href);
var c = url.searchParams.get("serial");
// console.log(c);


        var valJson = JSON.stringify(p);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'adminSendSelectPostulants',
                dataSelects: valJson,
                getSerial: c
            },
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
            },
            error: function () {
                console.log("error");
                swal.stopLoading();

                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (data) {
                // console.log("exito", data);
                data = data.slice(0, -1);
                swal({
                    icon: 'success',
                    title: '¡Listo!',
                    text: "El proceso de entrevista creado para este usuario",
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        // location.reload();
                    });

                    jQuery("#state").html(data).fadeIn('slow');
            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();

            },
        });



    } else {
        swal.stopLoading();
    }

}

// revisando esto
function AdminSelectPostulant() {
    // se jala la información del formulario enviado

    console.log('send seleccionados', postuladosElegidos);
    // console.log(info);
    var p = [];

    p['candidatos'] = postuladosElegidos;
    p['serial'] = serialpEntrevistas;

    var values = p;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    // console.log(values);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'adminSendSelectPostulants',
            dataSelects: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Felicidades!',
                text: "El proceso de entrevistas ha sido iniciado para los candidatos seleccionados",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });

}

// para seleccionar al postulante al proceso de entrevistas de una vacante laboral
function sendAdminSelectPostulant(idpostulant, serial) {


    swal({
        title: "Datos de selección del postulante",
        text: 'Carga los siguientes datos',
        content: formSelectPostulate,
        className: 'formSelectPostulate',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            add: {
                text: "Seleccionar",
                value: true,
                visible: true,
                className: "formAddButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker
    jQuery("#date").datepicker({ minDate: 2 });
    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });

    var options = {
        now: "12:35", //hh:mm 24 hour format only, defaults to current time
        twentyFour: false, //Display 24 hour format, defaults to false
        upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS
        downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        hoverState: 'hover-state', //The hover state class to use, for custom CSS
        title: 'Selector de hora', //The Wickedpicker's title,
        showSeconds: false, //Whether or not to show seconds,
        secondsInterval: 1, //Change interval for seconds, defaults to 1 ,
        minutesInterval: 1, //Change interval for minutes, defaults to 1
        beforeShow: null, //A function to be called before the Wickedpicker is shown
        show: null, //A function to be called when the Wickedpicker is shown
        clearable: false, //Make the picker's input clearable (has clickable "x")
    };

    jQuery('input#hora').wickedpicker(options);
    flechasHoraPicker();
    // .wickedpicker(options);
    // flechasHoraPicker();
    // flechasHoraPicker();

    // jQuery('input#hora').wickedpicker();


    // colocado de la función de efecto click
    jQuery(".swal-modal.formSelectPostulate button.swal-button.swal-button--add.formAddButton").attr("onclick", "AdminAddPostulant(" + idpostulant + ",'" + serial + "')");

    // validaciones

    // configValidatorType(dataSelectPostulant);


}


// ajustando aqui

function AdminDeleteSelectPostulant(idpostulant, serial) {


    // console.log(info);
    var p = [];
    p['candidato'] = idpostulant;
    p['serial'] = serial;

    var values = p;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    var url = new URL(window.location.href);
var c = url.searchParams.get("serial");

    // console.log(values);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'AdminDeleteSelectPostulant',
            AdminDeleteSelectPostulant: valJson,
            getSerial: c
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {

            data = data.slice(0, -1);
            swal({
                icon: 'success',
                title: '¡Listo!',
                text: "El candidato ya no esta seleccionado para ser entrevistado en esta oferta laboral",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    // location.reload();
                });

            jQuery("#state").html(data).fadeIn('slow');
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });



}


// eliminar el candidato actual de la selección de proximas entrevistas
function sendAdminDeleteSelectPostulant(idpostulant, serial) {
    swal({
        icon: 'warning',
        title: "¿Desea eliminar esta selección?",
        text: 'El postulante puede ser seleccionado de nuevo',
        className: 'formDeleteSelectPostulate',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });

    jQuery(".swal-modal.formDeleteSelectPostulate button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "AdminDeleteSelectPostulant(" + idpostulant + ",'" + serial + "')");
}

// para abrir el panel de opciones para enviar o eliminar por completo
function optionSendPostulates() {

    var c = postuladosElegidos.length;


    console.log(c);
    // return;
    //
    if (c > 0) {
        swal({
            icon: 'success',
            title: "Iniciar proceso de entrevistas a postulantes",
            text: 'Has seleccionado a ' + c + ' usuario(s)',
            className: 'formSendSelectPostulate',
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: false,
                    visible: true,
                    className: "formCancelButton",
                    closeModal: true
                },
                confirm: {
                    text: "Enviar candidatos",
                    value: true,
                    visible: true,
                    className: "formSubmitButton",
                    closeModal: false
                }
            }
        });
        // colocado de la función de efecto click

        jQuery('.swal-modal.formSendSelectPostulate button.swal-button.swal-button--confirm.formSubmitButton').attr('onclick', 'AdminSelectPostulant()');
    } else if (c == 0) {
        swal({
            icon: 'warning',
            title: 'No has seleccionado a ningun candidato',
            // text: "",
            className: 'successSendOffer'
        });
    }

}




function deleteAdminSelectPostulant(idpostulant, serial) {
    // se jala la información del formulario enviado

    console.log('send Delete seleccionado', idpostulant);
    // console.log(info);
    var p = [];

    p['idpostulant'] = idpostulant;
    p['serial'] = serial;

    var values = p;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'deleteAdminSelectPostulant',
            dataSelectDelete: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: 'Proceso de entrevista eliminado',
                text: "El proceso de entrevistas ha sido cancelado para este candidato",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });

}

function sendDeleteAdminSelectPostulant(idpostulant, serial) {


    swal({
        icon: 'warning',
        title: "Elimina el proceso de entrevistas de este candidato",
        text: 'Esta acción, cancelara por completo el proceso de entrevistas de este usuario',
        className: 'formDeleteSelect',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker

    // colocado de la función de efecto click
    jQuery(".swal-modal.formDeleteSelect button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "deleteAdminSelectPostulant(" + idpostulant + ",'" + serial + "')");

    // validaciones

    configValidatorType(dataSelectPostulant);

}

function DeleteProcessInterview(idEntrevista) {

    var p = [];
    p['idEntrevista'] = idEntrevista;
    var values = p;
    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'DeleteProcessInterview',
            dataSelectDelete: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: 'Proceso de entrevista eliminado',
                text: "El proceso de entrevistas ha sido cancelado para este candidato",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });
}

function sendDeleteProcessInterview(idEntrevista) {


    swal({
        icon: 'warning',
        title: "Elimina el proceso de entrevistas de este candidato",
        text: 'Esta acción, cancelara por completo el proceso de entrevistas de este usuario',
        className: 'deleteProcessInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker

    // colocado de la función de efecto click
    jQuery(".swal-modal.deleteProcessInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "DeleteProcessInterview('" + idEntrevista + "')");


}

function adminAddNewInterview(datos) {

    var info = jQuery('.swal-modal.formDesignNewInterview form.formData');
    var values = [];
    var siguienteEnt = [];
    var error = false;

    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    // console.log(values);
    // return;
    if (error != true) {
        info[0].reset();
        // pasar de array a objeto para que los datos sean mas faciles de manejar
        values = jQuery.extend({}, values);
        datos = JSON.parse(datos);
        var d = {
            'entrevista': datos,
            'info': values
        };

        var obj = _.extend({}, d);
        var valJson = JSON.stringify(obj);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'adminAddNewInterview',
                dataNew: valJson
            },
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
            },
            error: function () {
                console.log("error");
                swal.stopLoading();

                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (data) {
                console.log("exito", data);

                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: 'Entrevista aprobada, siguiente entrevista programada',
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        location.reload();
                    });
            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();

            },
        });



    } else {
        swal.stopLoading();
    }
}

function sendEvaluateInterview3(datos) {


    swal({
        title: 'Califica la entrevista actual',
        text: 'Carga los siguientes datos',
        content: formSelectPostulate,
        className: 'sendCreateFamilyPostulantSelectionStep',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker
    jQuery("#date").datepicker();
    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });
    // colocado de la función de efecto click

    jQuery(".swal-modal.sendCreateFamilyPostulantSelectionStep button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendCreateFamilyPostulantSelectionStep('" + datos + "')");

    // validaciones

    configValidatorType(dataSelectPostulant);

}
function sendEvaluateInterview2(datos) {

    console.log('este es');

    swal({
        title: 'Califica la  entrevista actual',
        text: 'Carga los siguientes datos',
        content: formSelectPostulate,
        className: 'formDesignNewInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });

    var stars = jQuery('.formDesignNewInterview .recomendabilidad .star-rating');
    setStars(stars);



    // activacion datepicker
    jQuery("#date").datepicker();
    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });
    // colocado de la función de efecto click

    jQuery(".swal-modal.formDesignNewInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "adminAddNewInterview('" + datos + "')");

    // validaciones

    configValidatorType(dataSelectPostulant);

}

function sendEvaluateInterview3Final(datos) {

    datos = JSON.parse(datos);
    var d = {
        'entrevista': datos
    };

    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'adminAddNewInterview',
            dataNew: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Entrevista final aprobada, candidato habilitado para contrato',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });



}


function sendEvaluateInterview(datos) {

    swal({
        icon: 'success',
        title: "Aprueba la entrevista ",
        text: 'Se procederá a calificar la entrevista actual',
        className: 'deleteProcessInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker
    // colocado de la función de efecto click
    // console.log(datos['etapa']);
    // return;
    if (datos['etapa'] == 2) {
        datos = JSON.stringify(datos);
        jQuery(".swal-modal.deleteProcessInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendEvaluateInterview3('" + datos + "')");
    }

    if (datos['etapa'] == 3) {
        datos = JSON.stringify(datos);
        jQuery(".swal-modal.deleteProcessInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendEvaluateInterview3Final('" + datos + "')");
    }
    if (datos['etapa'] == 1) {
        datos = JSON.stringify(datos);
        jQuery(".swal-modal.deleteProcessInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendEvaluateInterview2('" + datos + "')");
    }

}

function adminModifyInterview(datos) {

    var info = jQuery('.swal-modal.formModifyInterview form.formData');
    var values = [];
    var siguienteEnt = [];
    var error = false;

    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    info[0].reset();

    // pasar de array a objeto para que los datos sean mas faciles de manejar
    values = jQuery.extend({}, values);
    datos = JSON.parse(datos);

    var d = {
        'entrevista': datos,
        'info': values
    };


    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'adminModifyInterview',
            dataModify: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Entrevista modificada',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });

}

function sendModifyInterview(datos) {

    // console.log(datos);



    swal({
        title: 'Modifica la siguiente entrevista',
        text: 'Carga los siguientes datos',
        content: formSelectPostulate,
        className: 'formModifyInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });
    datos = JSON.stringify(datos);
    // activacion datepicker
    jQuery("#date").datepicker();
    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });
    // colocado de la función de efecto click
    jQuery(".swal-modal.formModifyInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "adminModifyInterview('" + datos + "')");

    // validaciones

    // configValidatorType(dataSelectPostulant);

}

function load(e, data) {

    jQuery('.tabsAdminTsoluciono #content1 .opcVacants .list-group button').removeClass('active');

    jQuery(e.target).addClass('active');

    //  alert(panel);
    var p = data['panel'];
    var pg = data['page'];


    var obj = _.extend({}, data);
    var valJson = JSON.stringify(obj);
    // console.log(data);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: "POST",
        data: {
            action: "containerVacantAdmin",
            dataVacantAdmin: valJson
        },
        beforeSend: function (result) {
            console.log(result);
            jQuery("#spinnerLoad").css('display', 'flex');
        },
        success: function (data) {

            // alert(data);
            data = data.slice(0, -1);
            // tipo = '';


            jQuery("#gestionVacantes").html(data).fadeIn('slow');
        },
        complete: function (data) {

            jQuery("#spinnerLoad").css('display', 'none');
        }

    })


}


var dataGestionVacante = [];
// función para enviar la información del sweet para la gestión administrativa de una vacante
function processVerificaVacante() {

    var info = jQuery('.swal-modal.formVerifyVacant form.formData');
    // -----
    var values = [];
    var error = false;
    console.log('sendfinal');
    // console.log(info);

    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    info[0].reset();
    var serial = dataGestionVacante['vacante']['oferta']['serialOferta'];
    var fechaRevisado = dataGestionVacante['gestion']['fechaRevisado'];
    var adminId = dataGestionVacante['gestion']['adminId'];
    var o = jQuery.extend({}, values);

    var send = {
        'serial': serial,
        'adminData': o,
        'fechaRevisado': fechaRevisado,
        'adminId': adminId
    };

    var obj = _.extend({}, send);
    // var obj = send;
    var valJson = JSON.stringify(obj);

    // console.log(send);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'processAdminVacantVerify',
            dataAdmin: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();
            info[0].reset();
            jQuery('input#jsonFirmaContratista').val('');

            jQuery('.swal-modal.confirmOfferLaboral').remove();
            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: "Los datos de verificación de la vacante estan puestos al dia",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();
            // window.location = pagina;
            // jQuery("#spinnerPro").css('visibility', 'hidden');
        },
    });

    // swal.stopLoading();

}
// función para construir el sweet para la gestión administrativa de una vacante
function verificaVacante(data) {

    var t = templateVerifVacantAdmin;

    var info = jQuery('.swal-modal.confirmOfferLaboral form.formData');

    jQuery('.vacantName span', t).text('Vacante: ' + data['vacante']['oferta']['nombreTrabajo']);
    jQuery('.familia span', t).text('Familia: ' + data['user']['nb']);
    jQuery('.telefono span', t).text('Tel: ' + data['user']['tl']);
    jQuery('.cargo span', t).text('Cargo: ' + data['vacante']['oferta']['cargo']);
    jQuery('.horario span', t).text('Horario: ' + data['vacante']['oferta']['horario']);
    jQuery('.sueldo span', t).text('Sueldo: ' + data['vacante']['oferta']['sueldo']);
    jQuery('.tipoServicio span', t).text('Servicio: ' + data['vacante']['oferta']['tipoServicio']);
    jQuery('.direccion span', t).text('Dirección: ' + data['vacante']['oferta']['direccion']);
    jQuery('.pais span', t).text('Pais/ciudad: ' + data['vacante']['oferta']['pais'] + ' ' + data['vacante']['oferta']['ciudad']);
    jQuery('.publicadoFecha span', t).text('Publicado: ' + data['vacante']['oferta']['fechaCreacion']);
    jQuery('.desdeFecha span', t).text('Desde: ' + data['vacante']['oferta']['fechaInicio']);
    jQuery('.hastaFecha span', t).text('Hasta: ' + data['vacante']['oferta']['fechaFin']);
    jQuery('.fechaRevision span', t).text('Revisado en: ' + data['gestion']['fechaRevisado']);
    jQuery('.descripcion span', t).text('Descripción: ' + data['vacante']['oferta']['descripcionExtra']);

    swal({
        title: "Revisión de publicación",
        text: 'Gestiona la siguiente publicación de vacante laboral',
        content: templateVerifVacantAdmin,
        className: 'formVerifyVacant',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            verificar: {
                text: "Guardar",
                value: true,
                visible: true,
                className: "verificarButton",
                closeModal: false
            }
        }
    });

    // colocado de la función de efecto click
    jQuery(".swal-modal.formVerifyVacant button.swal-button.swal-button--verificar.verificarButton").attr("onclick", "processVerificaVacante()");

    dataGestionVacante = data;

}

// función para enviar la información del sweet para la gestión administrativa de una vacante
function processOpcionesVacante() {

    var info = jQuery('.swal-modal.formOptionsVacant form.formData');
    // -----
    var values = [];
    var error = false;
    console.log('sendfinal');
    // console.log(info);

    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    info[0].reset();
    var serial = dataGestionVacante['vacante']['oferta']['serialOferta'];
    var fechaRevisado = dataGestionVacante['gestion']['fechaRevisado'];
    var adminId = dataGestionVacante['gestion']['adminId'];
    var o = jQuery.extend({}, values);

    var send = {
        'serial': serial,
        'adminData': o,
        'fechaRevisado': fechaRevisado,
        'adminId': adminId
    };

    // console.log(send);
    // return;

    var obj = _.extend({}, send);
    // var obj = send;
    var valJson = JSON.stringify(obj);

    // console.log(send);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'processOpcionesVacante',
            dataAdmin: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();
            info[0].reset();
            jQuery('input#jsonFirmaContratista').val('');

            jQuery('.swal-modal.confirmOfferLaboral').remove();
            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: "Los datos de verificación de la vacante estan puestos al dia",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();
            // window.location = pagina;
            // jQuery("#spinnerPro").css('visibility', 'hidden');
        },
    });

    // swal.stopLoading();

}

// funci+ón para crear el sweet alert para las opciones de la vacante laboral de parte de la administración
function opcionesVacante(data) {

    var t = templateOptionsVacantAdmin;

    var necesidades = data['vacante']['oferta']['necesidades'];
    var notaAdmin = data['vacante']['oferta']['notaAdmin'];
    var estadoPublico = data['vacante']['oferta']['publicar'];
    var estadoAprobado = data['vacante']['oferta']['aprobado'];
    var fLLamada = data['vacante']['oferta']['fechaLlamada'];

    if (estadoPublico == '0') {
        jQuery('.estadoPublico select option[value="1"]', t).attr("selected", false);
        jQuery('.estadoPublico select option[value="0"]', t).attr("selected", true);
    }
    if (estadoPublico == '1') {
        jQuery('.estadoPublico select option[value="0"]', t).attr("selected", false);
        jQuery('.estadoPublico select option[value="1"]', t).attr("selected", true);

    }
    // console.log(estadoPublico);
    // return;

    necesidades = ((necesidades != null) && (necesidades != '')) ? necesidades : 'No hay información sobre sus necesidades en este momento';
    notaAdmin = ((notaAdmin != null) && (notaAdmin != '')) ? notaAdmin : 'No hay información sobre notas adicionales en este momento';
    estadoPublico = ((estadoPublico != null) && (estadoPublico != 0)) ? 'Si' : 'No';
    estadoAprobado = ((estadoAprobado != null) && (estadoAprobado != 0)) ? 'Si' : 'No';

    var info = jQuery('.swal-modal.confirmOfferLaboral form.formData');

    jQuery('.vacantName span', t).text('Vacante: ' + data['vacante']['oferta']['nombreTrabajo']);
    jQuery('.familia span', t).text('Familia: ' + data['user']['nb']);
    jQuery('.telefono span', t).text('Tel: ' + data['user']['tl']);
    jQuery('.cargo span', t).text('Cargo: ' + data['vacante']['oferta']['cargo']);
    jQuery('.horario span', t).text('Horario: ' + data['vacante']['oferta']['horario']);
    jQuery('.sueldo span', t).text('Sueldo: ' + data['vacante']['oferta']['sueldo']);
    jQuery('.tipoServicio span', t).text('Servicio: ' + data['vacante']['oferta']['tipoServicio']);
    jQuery('.direccion span', t).text('Dirección: ' + data['vacante']['oferta']['direccion']);
    jQuery('.pais span', t).text('Pais/ciudad: ' + data['vacante']['oferta']['pais'] + ' ' + data['vacante']['oferta']['ciudad']);
    jQuery('.publicadoFecha span', t).text('Publicado: ' + data['vacante']['oferta']['fechaCreacion']);
    jQuery('.desdeFecha span', t).text('Desde: ' + data['vacante']['oferta']['fechaInicio']);
    jQuery('.hastaFecha span', t).text('Hasta: ' + data['vacante']['oferta']['fechaFin']);
    jQuery('.fechaRevision span', t).text('Revisado en: ' + fLLamada);
    jQuery('.descripcion span', t).text('Descripción: ' + data['vacante']['oferta']['descripcionExtra']);

    jQuery('.necesidades span', t).text(necesidades);
    jQuery('.notaAdmin span', t).text(notaAdmin);

    jQuery('.estadoAprobado span', t).text(estadoAprobado);



    swal({
        title: "Opciones de publicación",
        text: 'Gestiona las opciones correspondientes',
        content: templateOptionsVacantAdmin,
        className: 'formOptionsVacant',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            opciones: {
                text: "Guardar",
                value: true,
                visible: true,
                className: "opcionesButton",
                closeModal: false
            }
        }
    });

    // colocado de la función de efecto click
    jQuery(".swal-modal.formOptionsVacant button.swal-button.swal-button--opciones.opcionesButton").attr("onclick", "processOpcionesVacante()");

    dataGestionVacante = data;

}




function CreateFamilyInterview(datos) {

    swal({
        icon: 'success',
        title: "Programar entrevista con la familia ",
        text: 'Se procederá a programar una entrevista con la familia',
        className: 'createFamilyInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });

    datos = JSON.stringify(datos);

    jQuery(".swal-modal.createFamilyInterview button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendCreateFamilyInterview('" + datos + "')");

}





// para seleccionar al postulante al proceso de entrevistas de una vacante laboral
function sendCreateFamilyInterview(datos) {
    datos = JSON.stringify(datos);


    swal({
        title: "Datos de selección del postulante",
        text: 'Carga los siguientes datos',
        content: formSelectPostulate,
        className: 'formSelectFamilyInterview',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            add: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formAddButton",
                closeModal: false
            }
        }
    });
    // activacion datepicker

    // jQuery('.swal-modal.formSelectFamilyInterview .date input').datepicker({ minDate: 2 });

    jQuery("#date").datepicker({ minDate: 2, language: 'es' });

    var options = {
        now: "12:35", //hh:mm 24 hour format only, defaults to current time
        twentyFour: false, //Display 24 hour format, defaults to false
        upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS
        downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        hoverState: 'hover-state', //The hover state class to use, for custom CSS
        title: 'Selector de hora', //The Wickedpicker's title,
        showSeconds: false, //Whether or not to show seconds,
        secondsInterval: 1, //Change interval for seconds, defaults to 1 ,
        minutesInterval: 1, //Change interval for minutes, defaults to 1
        beforeShow: null, //A function to be called before the Wickedpicker is shown
        show: null, //A function to be called when the Wickedpicker is shown
        clearable: false, //Make the picker's input clearable (has clickable "x")
    };

    jQuery('input#hora').wickedpicker(options);
    flechasHoraPicker();


    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });
    // colocado de la función de efecto click
    jQuery(".swal-modal.formSelectFamilyInterview button.swal-button.swal-button--add.formAddButton").attr("onclick", "sendCreateFamilyInterviewConfirm(" + datos + ")");

    // validaciones

    configValidatorType(dataSelectPostulant);


}


function sendCreateFamilyInterviewConfirm(datos) {

    var info = jQuery('.swal-modal.formSelectFamilyInterview form.formData');


    var values = [];
    var error = false;
    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    info[0].reset();

    // pasar de array a objeto para que los datos sean mas faciles de manejar
    values = jQuery.extend({}, values);
    datos = JSON.parse(datos);

    var d = {
        'familia': datos,
        'info': values
    };


    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    // console.log(d);
    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'CreateFamilyInterview',
            dataInterviewFamily: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Entrevista programada con la familia',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });
}
function sendCreateFamilyPostulantSelectionStep(datos) {

    var info = jQuery('.swal-modal.sendCreateFamilyPostulantSelectionStep form.formData');
    var values = [];
    var error = false;
    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }
    });
    info[0].reset();

    // pasar de array a objeto para que los datos sean mas faciles de manejar
    values = jQuery.extend({}, values);
    datos = JSON.parse(datos);

    var d = {
        'familia': datos,
        'info': values
    };

    // console.log(d);
    // return;
    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendCreateFamilyPostulantSelectionStep',
            dataFamilySelectPostulantStep: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Proceso de entrevistas terminado, candidatos habilitados para contrato',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });
}


function deleteRecomended(data) {
    data = JSON.stringify(data);

    swal({
        icon: 'warning',
        title: "¿Desea eliminar este registro?",
        text: 'Se eliminará la recomendación del usuario y su entrevista para esta oferta',
        className: 'formDeleteRecomendedUser',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitButton",
                closeModal: false
            }
        }
    });

    // console.log(data);
    // return;
    jQuery(".swal-modal.formDeleteRecomendedUser button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendDeleteRecomended(" + data + ")");


}

function sendDeleteRecomended(data) {

    var d = data;

    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendDeleteRecomended',
            sendDeleteRecomended: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Proceso de entrevistas terminado, candidatos habilitados para contrato',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });
}


// solicitud reprogramar entrevista
function preSendAdminSolChangeDate(data) {

    // console.log(data);
    // return;

    swal({
        icon: 'info',
        title: "Solicita reprogramar la entrevista",
        text: 'Ingresa la hora y fecha que propones',
        content: formDetailsChangeDateAdmin,
        className: 'formSendChangeDate',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formOfferCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Enviar",
                value: true,
                visible: true,
                className: "formSubmitConfirm",
                closeModal: false
            }
        }
    });
    // jQuery(".formSendChangeDate #date").datepicker({ minDate: 2, });
    jQuery("#date").datepicker({ minDate: 2, language: 'he' });

    var options = {
        now: "12:35", //hh:mm 24 hour format only, defaults to current time
        twentyFour: false, //Display 24 hour format, defaults to false
        upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS
        downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        hoverState: 'hover-state', //The hover state class to use, for custom CSS
        title: 'Selector de hora', //The Wickedpicker's title,
        showSeconds: false, //Whether or not to show seconds,
        secondsInterval: 1, //Change interval for seconds, defaults to 1 ,
        minutesInterval: 1, //Change interval for minutes, defaults to 1
        beforeShow: null, //A function to be called before the Wickedpicker is shown
        show: null, //A function to be called when the Wickedpicker is shown
        clearable: false, //Make the picker's input clearable (has clickable "x")
    };

    jQuery('input#hora').wickedpicker(options);
    flechasHoraPicker();

    // jQuery('input#hora').wickedpicker();

    jQuery("#anim").on("change", function () {
        jQuery("#date").datepicker("option", "showAnim", jQuery(this).val());
    });

    data = JSON.stringify(data);

    jQuery('.swal-modal.formSendChangeDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendAdminSolChangeDate(" + data + ")");

    // validaciones
    configValidatorType(reprogramaEntrevista);

}
// enviar los valores
function SendAdminSolChangeDate(data) {
    var info = jQuery('.swal-modal.formSendChangeDate form.formData');
    // -----
    var error = false;
    var values = [];
    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }

    });

    // console.log(values);
    // return;
    if ((error != true)) {
        var obj = _.extend({}, values);

        dt = {
            'entrevistaId': data,
            'info': obj
        }
        // console.log('enviado', dt);
        // return;
        var valJson = JSON.stringify(dt);
        // console.log(valJson);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'SendAdminSolChangeDate',
                SendAdminSolChangeDate: valJson
            },
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
            },
            error: function () {
                console.log("error");
                swal.stopLoading();


                jQuery('.swal-modal.formSelectForContract').remove();
                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (data) {
                console.log("exito", data);

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "La propuesta de reprogramación de entrevista ha sido enviada al candidato",
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        location.reload();
                    });
            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();


                // window.location = pagina;
                // jQuery("#spinnerPro").css('visibility', 'hidden');
            },
        });
    } else {
        swal.stopLoading();
    }
}



function preSendAdminConfirmDate(data) {
    swal({
        icon: 'info',
        title: "¿Aceptas esta hora y fecha?",
        text: 'Si no estas de acuerdo, puedes ofrecer otras',
        // content: formDetailsChangeDateCand,
        className: 'formConfirmAdminDate',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formOfferCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitConfirm",
                closeModal: false
            }
        }
    });
    data = JSON.stringify(data);
    jQuery('.swal-modal.formConfirmAdminDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendAdminConfirmDate(" + data + ")");
}

function SendAdminConfirmDate(data) {

    var valJson = JSON.stringify(data);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'SendAdminConfirmDate',
            SendAdminConfirmDate: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();


            jQuery('.swal-modal.formSelectForContract').remove();
            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {
            console.log("exito", data);

            jQuery('.swal-modal.confirmOfferLaboral').remove();
            swal({
                icon: 'success',
                title: '¡Exito!',
                text: "Has aceptado la hora y fecha pautada",
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();


            // window.location = pagina;
            // jQuery("#spinnerPro").css('visibility', 'hidden');
        },
    });
}


function integrateNewPostulate() {

    swal({
        icon: 'info',
        title: "Integración de candidato",
        text: 'Selecciona un candidato previamente entrevistado para incluirlo como propuesta',
        content: formDetailsIntegrateCand,
        className: 'formSendIntegrateCand',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formOfferCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Enviar",
                value: true,
                visible: true,
                className: "formSubmitConfirm",
                closeModal: false
            }
        }
    });

    jQuery('.swal-modal.formSendIntegrateCand button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "sendIntegrateNewPostulate()");

    configValidatorType(newIntegrateCand);

}

function sendIntegrateNewPostulate() {
    var info = jQuery('.swal-modal.formSendIntegrateCand form.formData');
    // -----
    var error = false;
    var values = [];
    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }

    });

    if ((error != true)) {
        // console.log(values);
        // return;
        console.log(values['candidatos']);

        // var obj = _.extend({}, values);
        // console.log('enviado', dt);
        // return;
        // var valJson = JSON.stringify(obj);
        var valJson = values['candidatos'];
        // console.log(valJson);
        // return;
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'sendIntegrateNewPostulate',
                sendIntegrateNewPostulate: valJson
            },
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
            },
            error: function () {
                console.log("error");
                swal.stopLoading();


                jQuery('.swal-modal.formSelectForContract').remove();
                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (data) {
                console.log("exito", data);

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "El candidato ha sido integrado como sugerencia a la familia",
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        location.reload();
                    });
            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();


                // window.location = pagina;
                // jQuery("#spinnerPro").css('visibility', 'hidden');
            },
        });
    } else {
        swal.stopLoading();
    }
}



function defineAdminFirma() {

    swal({
        icon: 'success',
        title: "Firma de la directiva",
        text: 'Esta será la firma que aparecerá en nombre de la directiva de Tsolucionamos. Para contratos y documentos',
        content: formFirma,
        className: 'formFirmaAdmin',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formFirmaAdminClose",
                closeModal: true
            },
            confirm: {
                text: 'Confirmar',
                value: true,
                visible: true,
                className: "formFirmaAdminConfirm",
                closeModal: true
            }
        }
    });
    // firmaDirectiva
    // jsonFirmaDirectiva
    jQuery('div#firmaDirectiva').signature({
        syncField: '#jsonFirmaDirectiva',
        guideline: true,
        syncFormat: 'PNG'
    });
    jQuery('.field.firmaDirectiva .borrar').click(function () {
        jQuery('div#firmaDirectiva').signature('clear');
        jQuery('input#jsonFirmaDirectiva').val('');
        // jQuery('.directivaFirma img').attr('src', '');
    });

    jQuery('.swal-modal.formFirmaAdmin button.swal-button.swal-button--confirm.formFirmaAdminConfirm').attr('onclick', 'saveSign()');
}

function saveSign() {
    var info = jQuery('.swal-modal.formFirmaAdmin form.formData');


    // -----
    var error = false;
    var values = [];
    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }

    });
    console.log(values);
    jQuery('.directivaFirma img').attr('src', values['jsonFirmaDirectiva']);
    jQuery('.directivaFirma input').attr('value', values['jsonFirmaDirectiva']);
    swal.stopLoading();
}



jQuery('.tabsAdminTsoluciono').ready(function () {

    if (window.location.hash) {

        var r = jQuery('.tabsAdminTsoluciono .navOpc li');
        jQuery.each(r, function (indexInArray, valueOfElement) {
            var l = jQuery('a', valueOfElement).attr('href');
            if (l == window.location.hash) {

                jQuery(valueOfElement).click();

            }
        });

    }
});


function saveConfigAdminSettings() {
    var info = jQuery('.adminConfig form.formData');
    var infoBancarios = jQuery('.adminConfig form.datosBancarios');
    configValidatorType(configAdmin);
    // -----
    var error = false;
    var values = [];

    var l = [];
    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }

    });
    values = _.extend({}, values);
    values = JSON.stringify(values);
    var c = values;
    values = [];
    jQuery.each(infoBancarios[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
        // simular click para que salgan los validate message
        var parent = jQuery(valueOfElement).parent();
        parent.click();
        if (jQuery('.validateMessage', parent).is("[error='true']")) {
            error = true;
        }

    });
    values = _.extend({}, values);
    values = JSON.stringify(values);
    var b = values;

    if ((error != true)) {
        // var obj = _.extend({}, l);
        // var valJson = JSON.stringify(obj);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'saveConfigAdminSettings',
                cargosConfig: c,
                bancosConfig: b
            },
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                jQuery("#spinnerLoad").css('display', 'flex');
            },
            error: function () {
                console.log("error");
                swal.stopLoading();


                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (data) {
                console.log("exito", data);

                swal({
                    icon: 'success',
                    title: '¡Datos de configuración guardados!',
                    // text: "Propuesta de oferta laboral creada",
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        location.reload();
                    });
            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();


                // window.location = pagina;
                jQuery("#spinnerLoad").css('display', 'none');
            },
        });
    } else {
        console.log('error', error);
        swal.stopLoading();
    }
}



function viewReasonsCand(data){

    console.log(data);
    console.log(viewReasonsCandContent);
    var valJson = JSON.stringify(data);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendviewReasonsCand',
            sendviewReasonsCand: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            jQuery("#spinnerLoad").css('display', 'flex');
        },
        error: function () {
            console.log("error");
            swal.stopLoading();

            swal({
                icon: 'error',
                title: "No pudimos procesar tu solicitud",
                text: 'Por favor intente mas tarde',
                className: 'errorSentOffer'
            });

        },
        success: function (data) {

            console.log("exito", data);
            data = data.slice(0, -1);
            swal({
                icon: 'info',
                title: 'Razones y motivos de la familia',
                // text: "El proceso de entrevista creado para este usuario",
                content: viewReasonsCandContent,
                // content: '<div id="viewReasonsCand"></div>',
                className: 'viewReasonsCand',
                buttons: {
                    cancel: {
                        text: "Cancelar",
                        value: false,
                        visible: true,
                        className: "formOfferCloseButton",
                        closeModal: true
                    }
                }
            });

                jQuery("#viewReasonsCand").html(data).fadeIn('slow');
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

            jQuery("#spinnerLoad").css('display', 'none');

        },
    });

}