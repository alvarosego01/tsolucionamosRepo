

function typeValid(type, value, dataValid) {
    // image/jpeg, image/png, application/pdf
    switch (type) {
        // campo vacio
        case 'null':
            console.log(value);
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'nullImage':
            console.log(value);
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'formatImage':
            console.log(value.length);
            if (value.length > 0) {
                if ((value == 'image/jpeg') || (value == 'image/png')) return false; else return true;
                break;
            } else {
                return false;
            }
        case 'nullFComprobante':
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'formatFComprobante':
            // console.log(value);
            if ((value == 'image/jpeg') || (value == 'image/png') || (value == 'application/pdf')) {
                return false;
            } else {
                return true;
            }
            break;
        case 'nullSignature':
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'nullImage':
            if ((value == null) || (value == '') || (value == 'empty_file')) return true; else return false;
            break;
        // letras
        case 'letters':
            return !(/^[a-zA-Z]*jQuery/.test(value));
            break;
        // codigo postal
        case 'postalCode':
            if (value.match('^[0-9]{2}\-[0-9]{3}jQuery')) return true; else return false;
            break;
        case 'phone':
            var regex = /^\+?(\d.*){3,}jQuery/;
            return regex.test(value);
            break;
        case 'number':
            console.log(value);
            if (/^\d*jQuery/.test(value) == true) {
                return true;
            } else {
                return false;
            }

            break;
        case 'email':
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+jQuery/;
            return regex.test(value);
            break;
        case 'maxChar':
            var characters = value.length;
            if ((characters >= dataValid.max)) return true; else return false;
            break;
        // debo continuar este..
        case 'maxWords':
            var words = value.split(" ");
            if ((words.length >= dataValid.max)) return true; else return false;
            break;
        case 'nullSelect':
            if ((value == null) || (value == '') || (value == '-')) return true; else return false;
            break;
        case 'nullCheckbox':
            if (jQuery(value).is(":checked")) {
                return false;
            } else {
                return true;
            }
            break;
        default:
            break;
    }

}


function typeField(indexInArray, field) {


    if ((field.field == 'textfield')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            var val = jQuery('input', this).val();

            console.log('envio', val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            // si no existen errores entonces elimina el mensaje de error
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'textarea')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            var val = jQuery('textarea', this).val();

            console.log(val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            // si no existen errores entonces elimina el mensaje de error
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'select')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            // console.log('click aqui');
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('select', this).val();

            jQuery.each(field.valid, function (index, valueValid) {



                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });

            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'checkbox')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('input[type="checkbox"]', this);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });

            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'signature')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('input', this).val();

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'facturaComprobante')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..

            // console.log('d', indexInArray);

            var val = jQuery('input', this).val();
            if ((val != null) && (val != '')) {

                val = jQuery('input', this)[0].files[0].type;
            }


            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'imagen')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..

            // console.log('d', indexInArray);

            var val = jQuery('input', this).val();
            if ((val != null) && (val != '')) {

                val = jQuery('input', this)[0].files[0].type;
            }

            console.log(val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
}

function configValidatorType(data) {



    jQuery.each(data, function (indexInArray, valueOfElement) {
        typeField(indexInArray, valueOfElement);
    });

}




jQuery(document).ready(function () {
    jQuery('<img class="logoFooter" src="/wp-content/uploads/logoForge.png" alt="ForgeSystem">').appendTo("#footer-info > div > div.devFooter > a");
});






function deleteNotif(data) {



    swal({
        icon: 'warning',
        title: "¿Deseas eliminar esta Notificación?",
        // text: 'Elimina tu vacante',
        className: 'deleteNotification',
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
                className: "formdeleteNotificationSubmit",
                closeModal: false
            }
        }
    });

    data = JSON.stringify(data);

    jQuery('.swal-modal.deleteNotification button.swal-button.swal-button--confirm.formdeleteNotificationSubmit').attr('onclick', "deleteNotification(" + data + ")");


}

function deleteNotification(data) {


    var values = [];
    values = data;


    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log('llamada', valJson);

    console.log(s.ajaxurl);
    // swal.stopLoading();

    // return;

    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'processDeleteNot',
            processDeleteNot: valJson
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
                title: '¡Notificación eliminada!',
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


function urlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}


// span.wickedpicker__controls__control-up

// span.wickedpicker__controls__control-down


// span.wickedpicker__controls__control--separator-inner


function flechasHoraPicker() {
    console.log('flechas');
    if (jQuery('span.wickedpicker__controls__control-up i.fa.fa-angle-up').length > 0) {
    } else {
        jQuery('<i class="fa fa-angle-up" aria-hidden="true"></i>').appendTo('span.wickedpicker__controls__control-up');
    }
    if (jQuery('span.wickedpicker__controls__control-down i.fa.fa-angle-down').length > 0) {
    } else {
        jQuery('<i class="fa fa-angle-down" aria-hidden="true"></i>').appendTo('span.wickedpicker__controls__control-down');

    }

}

function setStars(element) {

    var starRating = jQuery('.fa', element);

    console.log(starRating);


    starRating.on('click', function () {
        starRating.siblings('input.rating-value').val(jQuery(this).data('rating'));

        starRating.each(function () {
            if (parseInt(starRating.siblings('input.rating-value').val()) >= parseInt(jQuery(this).data('rating'))) {
                return jQuery(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
                return jQuery(this).removeClass('fa-star').addClass('fa-star-o');
            }
        });

    });

}



jQuery(document).ready(function () {
    // jQuery('[data-toggle="tooltip"]').tooltip();}
});