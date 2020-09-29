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
            onClickOutside: function () { },
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

            $(element).attr({ 'aria-showingpicker': 'true', 'tabindex': -1 });
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
                if (event.which != 1) return false;
                var operator = (this.className.indexOf('up') > -1) ? '+' : '-';
                var passedData = event.data;
                if (event.type == 'mousedown') {
                    timeOut = setInterval($.proxy(function (args) {
                        args.Wickedpicker.changeValue(operator, args.input, this);
                    }, this, { 'Wickedpicker': passedData.Wickedpicker, 'input': passedData.input }), 200);
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
                formattedTime += this.options.timeSeparator + seconds;
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
        makePickerInputClearable: function (input) {
            $(input).wrap('<div class="clearable-picker"></div>').after('<span data-clear-picker>&times;</span>');

            //When the x is clicked, clear its sibling input field
            $('[data-clear-picker]').on('click', function (event) {
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
        _hide: function () {
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


// para el nav

jQuery('.tabMisVacantes .navOpc li').click(function (e) {
    // e.preventDefault();
    console.log(e);
    jQuery('.tabMisVacantes .navOpc li').removeClass('active');
    jQuery(e.currentTarget).addClass('active');
    var r = jQuery('a', e.currentTarget).attr('href');
    r = r.split("#tab");
    jQuery('.tabMisVacantes section').removeClass('active');
    jQuery('.tabMisVacantes section#content' + r[1]).addClass('active');

    jQuery('#pagControl form').removeClass('active');
    jQuery('#pagControl #formpagControl' + r[1]).addClass('active');
});

jQuery('.tabMisVacantes').ready(function () {

    if (window.location.hash) {

        var r = jQuery('.tabMisVacantes .navOpc li');
        jQuery.each(r, function (indexInArray, valueOfElement) {
            var l = jQuery('a', valueOfElement).attr('href');
            if (l == window.location.hash) {

                // jQuery(valueOfElement).addClass('active');
                jQuery(valueOfElement).click();

                // var n = indexInArray + 1;
                // jQuery('.tabMisVacantes section#content' + n).addClass('active');

                console.log(n);
            }
        });

    }
});



// ----------------



var formExperienciaUsuario = jQuery('#ExperienciaContractCand > form').clone()[0];
var formOfferLaboral2 = jQuery('#formSentOffer2 > form').clone()[0];
var formOfferLaboral = jQuery('#formSentOffer > form').clone()[0];
var formDetailsChangePetition = jQuery('#formDetailsChangePetition > form').clone()[0];
var formDetailsChangeDateCand = jQuery('#formDetailsChangeDateCand > form').clone()[0];

jQuery('#ExperienciaContractCand').remove();
jQuery('#formSentOffer').remove();
jQuery('#formSentOffer2').remove();
jQuery('#formDetailsChangePetition').remove();
jQuery('#formDetailsChangeDateCand').remove();

var formFirma = jQuery('#formFirma > form').clone()[0];
jQuery('#formFirma').remove();


var viewReasonsCandContent = jQuery('#viewReasonsCand').clone()[0];

jQuery('#viewReasonsCand').remove();


var valuesForm = [];


function deleteVacante(serial) {

    var values = [];
    values['serialOferta'] = serial;
    values['idFamilia'] = s.idUsuario;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'deleteOfferLaboral',
            deleteLaboralOffer: valJson
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
        success: function (response) {
            console.log("exito", response);
            swal({
                icon: 'success',
                title: '¡Vacante eliminada!',
                text: "Puedes volver a crear otra cuando quieras",
                className: 'successDeleteOfferLabora'
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


function sendDeleteVacante(serial) {

    var serialOferta = serial;

    swal({
        icon: 'warning',
        title: "¿Deseas eliminar esta vacante?",
        text: 'Elimina tu vacante',
        className: 'deleteVacante',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formDeleteVacanteSubmit",
                closeModal: false
            }
        }
    }).then(
        function (retorno) {
            console.log(retorno);
            switch (retorno) {
                case true:
                    deleteVacante(serialOferta);


                    break;
                case false:

                    break;

                default:
                    break;
            }
        });


}


// funcion para cancelar la invitación o oferta de contrato
function deleteContractOffer(datos) {

    var values = [];
    values = datos;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'deleteOfferContract',
            deleteOfferContract: valJson
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
        success: function (response) {
            console.log("exito", response);
            swal({
                icon: 'success',
                title: '¡Oferta de contrato eliminada!',
                text: "Se ha eliminado ademas tu postulación a esta vacante",
                className: 'successDelete'
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

function sendCancelContractOffer(ofertaId, postuladoId) {

    var datos = {
        'ofertaId': ofertaId,
        'postuladoId': postuladoId
    };

    swal({
        icon: 'warning',
        title: "¿Deseas eliminar esta oferta de contrato?",
        text: 'Se eliminara esta oferta de contrato junto con tu postulación a esta vacante',
        className: 'deleteContractOffer',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formdeleteContractOfferSubmit",
                closeModal: false
            }
        }
    }).then(
        function (retorno) {
            console.log(retorno);
            switch (retorno) {
                case true:
                    deleteContractOffer(datos);
                    console.log(datos);



                    break;
                case false:

                    break;

                default:
                    break;
            }
        });


}


// razones de cmabio e candidato
var formReasonsForChange = {
    "detalles": {
        field: 'textarea',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir tus razones para solicitar otro candidato'
            }
        }
    }
}


// parte de offer job


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


var validCreateOffer = {
    "servicio": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar un servicio'
            }
        }
    },
    "horario": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar una horario'
            }
        }
    },
    "sueldo": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes denotar el sueldo de la oferta laboral '
            },
            number: {
                message: 'Solo se permite escribir números'
            }
        }
    },
    "descripcion": {
        field: 'textarea',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir una descripción de la oferta laboral'
            }
        }
    },
    "terminos": {
        field: 'checkbox',
        required: true,
        valid: {
            nullCheckbox: {
                message: 'Debes aceptar los terminos y condiciones'
            }
        }
    },
    "cargo": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar el cargo a desempeñar'
            }
        }
    },
    "pais": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar un país'
            }
        }
    },
    "ciudad": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar la ciudad'
            }
        }
    },
    "direccion": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar una dirección'
            }
        }
    },
    "titulo": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir un titulo para el anuncio'
            }
        }
    },
    "fechaInicio": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes seleccionar una fecha de inicio'
            }
        }
    },
    "fechaFin": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes seleccionar una fecha de finalización'
            }
        }
    }
}

var validCreateOffer2 = {
    "firmaContratista": {
        field: 'signature',
        required: true,
        valid: {
            nullSignature: {
                message: 'Debes firmar para proceder'
            }
        }
    }
}

var controlPag = {
    "porPagina": {
        field: 'number',
        required: true,
        valid: {
            number: {
                message: 'Solo se permite escribir números'
            }
        }
    }
}




function sendOfferJob() {

    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.confirmOfferLaboral form.formData');
    // -----
    var values = valuesForm;
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
    values['jsonFirmaContratista'] = values['firmaContratistaJson'];
    console.log('values', values);

    // return;
    if ((error != true)) {

        var obj = _.extend({}, values);
        var valJson = JSON.stringify(obj);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'createOfferJob',
                dataOffer: valJson
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
                jQuery('input#firmaContratistaJson').val('');

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (response) {
                console.log("exito", response);
                info[0].reset();
                jQuery('input#firmaContratistaJson').val('');
                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: "Propuesta de oferta laboral creada",
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
        console.log('error', error);
        swal.stopLoading();
    }
}

function preSendOfferLaboral() {
    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.formOfferLaboral form.formData');
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

    if (error != true) {
        info[0].reset();
        jQuery('.swal-modal.formOfferLaboral').remove();
        swal({
            icon: 'success',
            title: "Todo parece estar en orden",
            text: 'Debes firmar tu oferta laboral para uso de un posible contrato',
            content: formOfferLaboral2,
            className: 'confirmOfferLaboral',
            buttons: {
                cancel: {
                    text: "Cancelar",
                    value: false,
                    visible: true,
                    className: "confirmFormOfferCloseButton",
                    closeModal: true
                },
                confirm: {
                    text: "Enviar",
                    value: true,
                    visible: true,
                    className: "confirmOfferButton",
                    closeModal: false
                }
            }
        });

        valuesForm = values;

        jQuery('.swal-modal.confirmOfferLaboral button.swal-button.swal-button--confirm.confirmOfferButton').attr('onclick', 'sendOfferJob()');

        jQuery('div#firmaContratista').signature({
            syncField: '#firmaContratistaJson',
            guideline: true,
            syncFormat: 'PNG'
        });
        jQuery('.field.firmaContratista .borrar').click(function () {
            jQuery('div#firmaContratista').signature('clear');
            jQuery('input#firmaContratistaJson').val('');
            jQuery('.areaFirmas .contratista div.firma img').remove();
        });
        configValidatorType(validCreateOffer2);
    } else {
        console.log('error', error);
        swal.stopLoading();
    }
}


function preSendClickOffer() {

    swal({
        title: "Nueva vacante laboral",
        text: 'Carga los siguientes datos',
        content: formOfferLaboral,
        className: 'formOfferLaboral',
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
                className: "formOfferButton",
                closeModal: false
            }
        }
    });

    var dateFormat = "mm/dd/yy",
        from = jQuery("#fechaInicio")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,

            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
                jQuery('#fechaFin').val('');
            }),
        to = jQuery("#fechaFin").datepicker({
            defaultDate: "+1w",
            changeMonth: true,

        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            });
    jQuery("#anim").on("change", function () {
        jQuery("#fechaInicio").datepicker("option", "showAnim", jQuery(this).val());
    });
    jQuery("#anim").on("change", function () {
        jQuery("#fechaFin").datepicker("option", "showAnim", jQuery(this).val());
    });

    function getDate(element) {
        var date;
        try {
            date = jQuery.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }
    // colocado de la función de efecto click
    jQuery('.swal-modal.formOfferLaboral button.swal-button.swal-button--confirm.formOfferButton').attr('onclick', 'preSendOfferLaboral()');
    // validaciones
    configValidatorType(validCreateOffer);

}





function selectForContract(data) {
    swal({
        icon: 'info',
        title: "Selección para ejercer el cargo de la vacante ",
        // text: 'Nuestro equipo se hará cargo de formalizar el contrato con el candidato que selecciones',
        className: 'formSelectForContract',
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
    data = JSON.stringify(data);
    // colocado de la función de efecto click
    jQuery(".swal-modal.formSelectForContract button.swal-button.swal-button--confirm.formSubmitButton").attr("onclick", "sendSelectForContract(" + data + ")");

}

function sendSelectForContract(data) {

    // data = JSON.stringify(data);

    var obj = _.extend({}, data);
    var valJson = JSON.stringify(obj);

    // console.log(valJson);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendSelectForContract',
            sendSelectForContract: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
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
        success: function (response) {
            console.log("exito", response);

            jQuery('.swal-modal.confirmOfferLaboral').remove();
            swal({
                icon: 'success',
                title: '¡Exito!',
                text: "Candidato seleccionado, tendrás a tu disposición un contrato en PDF suscrito con la persona seleccionada",
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


function showGarantContractOpc(data) {

    // console.log(data);
    // return;
    //
    // dias: 14
    // ​
    // estado: "En garantía"
    // ​
    // garantia: "Días de garantía: 14"
    //
    // return;
    var gdias = 90 - data['dias'];
    swal({
        icon: 'info',
        title: "Estado de garantía",
        text: 'Actualmente tienes una garantía vigente en este contrato. \n\ Dias restantes: ' + gdias,
        className: 'formSelectForContract',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCancelButton",
                closeModal: true
            },
            petition: {
                text: "Solicitar un nuevo candidato",
                value: true,
                visible: true,
                className: "formSubmitPetition",
                closeModal: false
            }
            // confirm: {
            //     text: "Confirmar candidato",
            //     value: true,
            //     visible: true,
            //     className: "formSubmitConfirm",
            //     closeModal: false
            // }
        }
    });
    // activacion datepicker
    data = JSON.stringify(data);
    // colocado de la función de efecto click
    jQuery(".swal-modal.formSelectForContract button.swal-button.swal-button--petition.formSubmitPetition").attr("onclick", "sendPetitionChange(" + data + ")");


    // jQuery(".swal-modal.formSelectForContract button.swal-button.swal-button--confirm.formSubmitConfirm").attr("onclick", "sendConfirmService(" + data + ")");
}



// candidato definitivo
function sendConfirmService(data) {

    swal({
        icon: 'info',
        title: "Cuéntanos tu experiencía",
        text: 'Puedes omitir llenar esta información',
        content: formExperienciaUsuario,
        className: 'formExperiencia',
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

    var stars = jQuery('.formExperiencia .calificacion .star-rating');
    setStars(stars);

    data = JSON.stringify(data);

    jQuery(".swal-modal.formExperiencia .swal-button.swal-button--confirm.formSubmitConfirm").attr("onclick", "sendInfoConfirmService(" + data + ")");


}




function sendInfoConfirmService(data) {

    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.formExperiencia form.formData');
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
    values['tipo'] = 'Calificación de usuario';


    var xx = _.extend({}, data);
    var obj = _.extend({}, values);
    // console.log('xx',xx);
    // return;
    data = {
        'data': xx,
        'form': obj
    }
    // return;
    // console.log(data);
    // return;
    var valJson = JSON.stringify(data);


    console.log('valjs', valJson);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'InfoConfirmService',
            InfoConfirmService: valJson
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
        success: function (response) {
            console.log("exito", response);

            jQuery('.swal-modal.confirmOfferLaboral').remove();
            swal({
                icon: 'success',
                title: '¡Exito!',
                text: "La petición de cambio de candidato ha sido enviada, se desactivará el contrato con el candidato actual",
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

// si la familia pide cambio
function sendPetitionChange(data) {
    swal({
        icon: 'info',
        title: "Solicitar un nuevo candidato",
        text: 'Se solicitará a administración la propuesta de un nuevo candidato',
        className: 'formSelectForContract',
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
                className: "formConfirmPetitionNew",
                closeModal: false
            }
        }
    });
    // activacion datepicker
    data = JSON.stringify(data);
    // colocado de la función de efecto click
    jQuery(".swal-modal.formSelectForContract button.swal-button.swal-button--confirm.formConfirmPetitionNew").attr("onclick", "sendReasonsChange(" + data + ")");
}

// encuesta de detalles de experiencia
function sendReasonsChange(data) {

    swal({
        icon: 'info',
        title: "Cuéntanos que sucedió",
        text: 'Puedes omitir llenar esta información',
        content: formDetailsChangePetition,
        className: 'formSendReasonsChange',
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


    var stars = jQuery('.formSendReasonsChange .calificacion .star-rating');
    setStars(stars);

    data = JSON.stringify(data);

    jQuery('.swal-modal.formSendReasonsChange button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "sendReasonsChangeForm(" + data + ")");

    configValidatorType(formReasonsForChange);

}


function sendReasonsChangeForm(d) {
    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.formSendReasonsChange form.formData');
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
    values['tipo'] = 'Solicitud de cambio';

    var xx = _.extend({}, d);
    var obj = _.extend({}, values);
    // console.log('xx',xx);
    // return;
    data = {
        'data': xx,
        'form': obj
    }
    // return;
    // console.log(data);
    // return;
    if (error != true) {


        var valJson = JSON.stringify(data);

        // console.log('valjs',valJson);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'sendConfirmPetitionChange',
                sendConfirmPetitionChange: valJson
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
            success: function (response) {
                console.log("exito", response);

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "La petición de cambio de candidato ha sido enviada, se desactivará el contrato con el candidato actual",
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

}


// solicitud reprogramar entrevista
function preSendFamSolChangeDate(data) {

    swal({
        icon: 'info',
        title: "Solicita reprogramar tu entrevista",
        text: 'Ingresa la hora y fecha que dispones',
        content: formDetailsChangeDateCand,
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
    jQuery("#date").datepicker({ minDate: 0, dateFormat: 'dd/mm/yy' });

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

    jQuery('.swal-modal.formSendChangeDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendFamSolChangeDate(" + data + ")");

    // validaciones
    configValidatorType(reprogramaEntrevista);

}

function SendFamSolChangeDate(data) {
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
        var valJson = JSON.stringify(dt);
        // console.log(valJson);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'SendFamSolChangeDate',
                SendFamSolChangeDate: valJson
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
            success: function (response) {
                console.log("exito", response);

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "La petición de reprogramación de entrevista ha sido enviada a administración",
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



// solicitud reprogramar entrevista
function preSendCandSolChangeDate(data) {

    swal({
        icon: 'info',
        title: "Solicita reprogramar tu entrevista",
        text: 'Ingresa la hora y fecha que dispones',
        content: formDetailsChangeDateCand,
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
    jQuery("#date").datepicker({ minDate: 0, dateFormat: 'dd/mm/yy' });

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

    jQuery('.swal-modal.formSendChangeDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendCandSolChangeDate(" + data + ")");

    // validaciones
    configValidatorType(reprogramaEntrevista);

}
// enviar los valores
function SendCandSolChangeDate(data) {
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
        var valJson = JSON.stringify(dt);
        // console.log(valJson);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'SendCandSolChangeDate',
                SendCandSolChangeDate: valJson
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
            success: function (response) {
                console.log("exito", response);

                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "La petición de reprogramación de entrevista ha sido enviada a administración",
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



function preSendCandConfirmDate(data) {

    swal({
        icon: 'info',
        title: "¿Aceptas esta hora y fecha?",
        text: 'Si no estas de acuerdo, puedes solicitar otras',
        // content: formDetailsChangeDateCand,
        className: 'formConfirmCandDate',
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
    jQuery('.swal-modal.formConfirmCandDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendCandConfirmDate(" + data + ")");


}


function SendCandConfirmDate(data) {

    var valJson = JSON.stringify(data);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'SendCandConfirmDate',
            SendCandConfirmDate: valJson
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
        success: function (response) {
            console.log("exito", response);

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



function preSendFamConfirmDate(data) {

    swal({
        icon: 'info',
        title: "¿Aceptas esta hora y fecha?",
        text: 'Si no estas de acuerdo, puedes solicitar otras',
        // content: formDetailsChangeDateCand,
        className: 'formConfirmCandDate',
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
    jQuery('.swal-modal.formConfirmCandDate button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "SendFamConfirmDate(" + data + ")");

}


function SendFamConfirmDate(data) {

    // console.log(data);
    // return;
    var valJson = JSON.stringify(data);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'SendFamConfirmDate',
            SendFamConfirmDate: valJson
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
        success: function (response) {
            console.log("exito", response);

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






// pagar ahora
function sendPay(data) {

    // formPayService
    swal({
        icon: 'info',
        title: "Paga ahora tu servicio",
        text: 'Llena los siguientes datos, para procesar tu pago',
        content: formPayService,
        className: 'formPayNow',
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

 jQuery('.swal-icon.swal-icon--info').html(icoMoney);
    jQuery('.swal-icon.swal-icon--info').addClass('noAfterBefore');



    configValidatorType(dataPago);

    jQuery('.swal-modal.formPayNow button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "processpayService()");


}

function processpayService() {


    var info = jQuery('.swal-modal.formPayNow form.formData');

    var error = false;
    var values = [];

    var imagen = '';

    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {

        var name = jQuery(valueOfElement).attr('name');
        if (jQuery(valueOfElement).attr('type') != 'file') {

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

        }
        if (jQuery(valueOfElement).attr('type') == 'file') {

            var name = jQuery(valueOfElement).attr('name');
            var val = jQuery(valueOfElement)[0].files[0];

            if (val == '') {
                val = null;
            }

            values[name] = val;

            imagen = val;

            // simular click para que salgan los validate message
            var parent = jQuery(valueOfElement).parent();
            parent.click();
            if (jQuery('.validateMessage', parent).is("[error='true']")) {
                error = true;
            }
        }
    });


    if (error != true) {
        datos.step = datos.step + 1;
        var formData1 = new FormData(jQuery('.swal-modal.formPayNow form.formData')[0]);

        // var m = (Object.values(datos));
        var m = JSON.stringify(datos);
        // var m = Object.values(datos);
        formData1.append('datos', m);
        formData1.append('terminosCompleto', terminosCompleto);
        formData1.append('action', 'processpayService');

        console.log(terminosCompleto);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: formData1,
            processData: false,
            contentType: false,
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
            },
            error: function () {
                console.log("error");

                jQuery('.swal-modal.formSelectForContract').remove();
                swal({
                    icon: 'error',
                    title: "No pudimos procesar tu solicitud",
                    text: 'Por favor intente mas tarde',
                    className: 'errorSentOffer'
                });

            },
            success: function (response) {
                console.log("exito", response);

                swal({
                    icon: 'success',
                    title: '¡Exito!',
                    text: "Comprobaremos la transacción",
                    className: 'successSendOffer'
                }).then(
                    function (retorno) {
                        // window.location.href = url;
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
        className: 'formFirmaUser',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formFirmaUserClose",
                closeModal: true
            },
            confirm: {
                text: 'Confirmar',
                value: true,
                visible: true,
                className: "formFirmaUserConfirm",
                closeModal: true
            }
        }
    });

    // FirmaUsuario
    // jsonFirmaUsuario
    // firmaDirectiva
    // jsonFirmaDirectiva
    jQuery('div#FirmaUsuario').signature({
        syncField: '#jsonFirmaUsuario',
        guideline: true,
        syncFormat: 'PNG'
    });
    jQuery('.field.FirmaUsuario .borrar').click(function () {
        jQuery('div#FirmaUsuario').signature('clear');
        jQuery('input#jsonFirmaUsuario').val('');
        // jQuery('.directivaFirma img').attr('src', '');
    });

    jQuery('.swal-modal.formFirmaUser button.swal-button.swal-button--confirm.formFirmaUserConfirm').attr('onclick', 'saveSign()');
}

function saveSign() {
    var info = jQuery('.swal-modal.formFirmaUser form.formData');
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
    jQuery('.usuarioFirma img').attr('src', values['jsonFirmaUsuario']);
    jQuery('.usuarioFirma input').attr('value', values['jsonFirmaUsuario']);
    swal.stopLoading();
}


function saveConfigAdminSettings() {
    var info = jQuery('.adminConfig form.formData');


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

    values['idUsuario'] = s.idUsuario;

    values = _.extend({}, values);
    values = JSON.stringify(values);
    var b = values;

    // console.log('aaa', b);
    // return;
    if ((error != true)) {
        // var obj = _.extend({}, l);
        // var valJson = JSON.stringify(obj);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'savedUserSettings',
                savedUserSettings: b,
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
            success: function (response) {
                console.log("exito", response);

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


function viewReasonsCandFam(data) {

    console.log(data);
    console.log(viewReasonsCandContent);

    console.log(data);
    // return;
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


var controlPaginator = [];

function refreshInfo(data) {


    configValidatorType(controlPag);
    var destiny = '';
    if (data == 'myVacants') {
        var info = jQuery('#formpagControl1');

        destiny = '#content1';
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

        if (values['porPagina'] == null || values['porPagina'] == '') {
            values['porPagina'] = 9;
        }

    }

    if (data == 'postInterviews') {
        var info = jQuery('#formpagControl2');

        destiny = '#content2';
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

        if (values['porPagina'] == null || values['porPagina'] == '') {
            values['porPagina'] = 5;
        }

    }
    if (data == 'contractList') {
        var info = jQuery('#formpagControl3');

        destiny = '#content3';
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

        if (values['porPagina'] == null || values['porPagina'] == '') {
            values['porPagina'] = 5;
        }

    }
    if (data == 'notifList') {
        var info = jQuery('#formpagControl4');

        destiny = '#content4';
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

        if (values['porPagina'] == null || values['porPagina'] == '') {
            values['porPagina'] = 10;
        }

    }
    if (data == 'factList') {

        var info = jQuery('#formpagControl5');

        destiny = '#content5';
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

        if (values['porPagina'] == null || values['porPagina'] == '') {
            values['porPagina'] = 10;
        }

    }
    values['data'] = data;

    controlPaginator = values;
    values = _.extend({}, values);

    var valJson = JSON.stringify(values);

    // console.log('destiny',destiny);
    // console.log('valjson',valJson);
    console.log('control refresh', controlPaginator);

    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'datarefreshInfo',
            datarefreshInfo: valJson
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

            // console.log("exito", data);
            data = data.slice(0, -1);


            jQuery(destiny).html(data).fadeIn('slow');

            enlaces();

            destiny = '';

            // console.log('control global ', controlPaginator);
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

            jQuery("#spinnerLoad").css('display', 'none');

        },
    });

}


// data: "myVacants"
// ​
// length: 0
// ​
// pg: 2
// ​
// porPagina: "1"
function load(page, panel) {

    var destiny = '';
    var p = panel;
    var pg = page;

    console.log('load control', controlPaginator);
    // console.log('load control', controlPaginator.length);
    // return;

    if (destiny == '' && panel != '') {
        if (panel == 'famTab1') {
            destiny = '#content1';
            // valJson {"porPagina":1,"undefined":null,"data":controlPaginator['data']!= 'myVacants'
            if (!controlPaginator.data) {

                controlPaginator['data'] = 'myVacants';
                controlPaginator['porPagina'] = 9;
                controlPaginator['pg'] = 1;
            }
        }


        if (panel == 'famTab2') {
            destiny = '#content2';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'postInterviews') {

                console.log('sin control js');
                controlPaginator['data'] = 'postInterviews';
                controlPaginator['porPagina'] = 5;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }


        if (panel == 'famTab3') {
            destiny = '#content3';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'postInterviews') {

                console.log('sin control js');
                controlPaginator['data'] = 'postInterviews';
                controlPaginator['porPagina'] = 5;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }


        if (panel == 'famTab4') {
            destiny = '#content4';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'notifList') {

                console.log('sin control js');
                controlPaginator['data'] = 'notifList';
                controlPaginator['porPagina'] = 10;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }
        if (panel == 'famTab5') {
            destiny = '#content5';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'factList') {

                console.log('sin control js');
                controlPaginator['data'] = 'factList';
                controlPaginator['porPagina'] = 10;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }
        if (panel == 'canTab1') {
            destiny = '#content1';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'myVacants') {

                console.log('sin control js');
                controlPaginator['data'] = 'myVacants';
                controlPaginator['porPagina'] = 1;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }
        if (panel == 'canTab2') {
            destiny = '#content2';
            // valJson {"porPagina":1,"undefined":null,"data":"myVacants","pg":2}
            if (controlPaginator['data']!= 'postInterviews') {

                console.log('sin control js');
                controlPaginator['data'] = 'postInterviews';
                controlPaginator['porPagina'] = 1;
                controlPaginator['pg'] = 1;
                controlPaginator['filterBy'] = 'todos';

            }
        }

    }

    // console.log("llamada ajax", tipo);
    var v = controlPaginator;
    v['pg'] = pg;

    values = _.extend({}, v);
    var valJson = JSON.stringify(values);

    console.log('load valjson', valJson);
    //

    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'datarefreshInfo',
            datarefreshInfo: valJson
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


            jQuery(destiny).html(data).fadeIn('slow');

            enlaces();

            destiny = '';
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

            jQuery("#spinnerLoad").css('display', 'none');

        },
    });

}


jQuery(document).ready(function () {

    configValidatorType(controlPag);

});