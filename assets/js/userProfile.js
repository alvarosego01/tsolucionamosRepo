
var formOfferLaboral2 = $('#formSentOffer2 > form').clone()[0];
var formOfferLaboral = $('#formSentOffer > form').clone()[0];
$('#formSentOffer').remove();
$('#formSentOffer2').remove();

var valuesForm = [];


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

function dateFormats() {

}


function sendOfferJob() {

    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.confirmOfferLaboral form.formData');
    // -----
    var values = valuesForm;
    var error = false;
    console.log('sendfinal');
    // console.log(info);

    $.each(info[0], function (indexInArray, valueOfElement) {
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
                jQuery('input#jsonFirmaContratista').val('');

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
                jQuery('input#jsonFirmaContratista').val('');
                jQuery('.swal-modal.confirmOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: "Hemos recibido con éxito tu solicitud, la estaremos procesando, y dentro de las proximas 24hs habiles nos estaremos comunicando con Ud.",
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
    $.each(info[0], function (indexInArray, valueOfElement) {

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
            syncField: '#jsonFirmaContratista',
            guideline: true,
            syncFormat: 'PNG'
        });
        jQuery('.field.firmaContratista .borrar').click(function () {
            jQuery('div#firmaContratista').signature('clear');
            jQuery('input#jsonFirmaContratista').val('');
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
    // colocado de la función de efecto click
    // dateFormat

    var dateFormat = "mm/dd/yy",
        from = $("#fechaInicio")
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,

            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
                $('#fechaFin').val('');
            }),
        to = $("#fechaFin").datepicker({
            defaultDate: "+1w",
            changeMonth: true,

        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            });
    $("#anim").on("change", function () {
        $("#fechaInicio").datepicker("option", "showAnim", $(this).val());
    });
    $("#anim").on("change", function () {
        $("#fechaFin").datepicker("option", "showAnim", $(this).val());
    });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }

    jQuery('.swal-modal.formOfferLaboral button.swal-button.swal-button--confirm.formOfferButton').attr('onclick', 'preSendOfferLaboral()');
    // validaciones
    configValidatorType(validCreateOffer);

}





