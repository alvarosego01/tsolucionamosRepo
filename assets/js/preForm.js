
// var formOfferLaboral = $('#preForm > form').clone()[0];
// $('#formSentOffer').remove();

var dataPreForm = {
    "nombreCompleto": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir tu nombre completo'
            }
        }
    },
    "cedula": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir tu cédula'
            }
        }
    },
    "edad": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir tu edad'
            }
        }
    },
    "ciudadResidencia": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir el nombre de tu ciudad'
            }
        }
    },
    "direccionResidencia": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir una dirección de residencia'
            }
        }
    },
    "telefonoMovil": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir un número de teléfono movil'
            },
            number: {
                message: 'Solo se permite escribir números'
            }
        }
    }
}



// set logo
var url = jQuery('.logo_container img').attr('src');
jQuery('.logoForm img').attr('src', url);

configValidatorType(dataPreForm);


function guardarPreForm() {


    var info = jQuery('#preForm form.preData');
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

    if ((error != true)) {
        
        var obj = _.extend({}, values);
        var valJson = JSON.stringify(obj);
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'sendDataPreForm',
                dataPreForm: valJson
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
                    title: '¡Listo!',
                    text: "Datos cargados",
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
        swal({
            icon: 'error',
            title: "Error en los campos",
            text: 'Asegurate de escribir bien los datos',
            className: 'errorSentOffer'
        });
    }

}