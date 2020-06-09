


var formPostulate = jQuery('#formPostulate > form').clone()[0];
jQuery('#formPostulate').remove();

var formEditOffer = jQuery('#formSentOffer > form').clone()[0];
jQuery('#formSentOffer').remove();

var valuesForm = [];

// validaciones para el formulario de edición de vacantes.
var validEditOffer = {
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
    }
}
// confirmación de edición laboral
function confirmEditVacant(serial) {

    console.log('confirm edit vacante', serial);


    var info = jQuery('.swal-modal.formEditOfferLaboral form.formData');
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

        values['serialEdit'] = serial;
        var obj = _.extend({}, values);
        var valJson = JSON.stringify(obj);
        console.log(valJson);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'editOfferJob',
                dataEditOffer: valJson
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

                jQuery('.swal-modal.formEditOfferLaboral').remove();
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

                jQuery('.swal-modal.formEditOfferLaboral').remove();
                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: "Propuesta de vacante laboral editada",
                    className: 'successSendEditOffer'
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

// editar vacante laboral
function sendEditVacant(serial) {
    console.log("Inicia edit vacante");
    var serialOferta = serial;



    swal({
        title: "Editar vacante laboral",
        text: 'Edita los siguientes datos',
        content: formEditOffer,
        className: 'formEditOfferLaboral',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formEditOfferCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Enviar",
                value: true,
                visible: true,
                className: "formEditOfferButton",
                closeModal: false
            }
        }
    });
    // colocado de la función de efecto click
    jQuery('.swal-modal.formEditOfferLaboral button.swal-button.swal-button--confirm.formEditOfferButton').attr('onclick', 'confirmEditVacant(\'' + serial + '\')');
    // validaciones
    configValidatorType(validEditOffer);
}


function postulate(info, serial) {

    var values = [];
    // extraer cada campo
    jQuery.each(info[0], function (indexInArray, valueOfElement) {
        var name = jQuery(valueOfElement).attr('name');
        var val = jQuery(valueOfElement).val();
        if (val == '') {
            val = null;
        }
        values[name] = val;
    });
    values['serialOferta'] = serial;
    values['idCanidata'] = s.idCandidata;
    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);
    console.log(valJson);
    // llamada ajax
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'createPostulation',
            dataPostulation: valJson
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
                title: '¡Felicidades!',
                text: "Te has postulado en esta vacante",
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

function deletePostulate(serial) {

    var values = [];
    values['serialOferta'] = serial;
    values['idCanidata'] = s.idCandidata;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'deletePostulation',
            deletePostulation: valJson
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
                title: '¡Postulación eliminada!',
                text: "Puedes volver a postularte cuando quieras",
                className: 'successDeletePostulate'
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

function sendPostulacion(serial) {

    console.log("Inicia postulación");
    var serialOferta = serial;
    swal({
        title: "Postulate en esta vacante laboral como:",
        text: 'Nosotros nos encargamos de tus datos',
        content: formPostulate,
        className: 'formPostulate',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formCloseButton",
                closeModal: true
            },
            confirm: {
                text: "Enviar",
                value: true,
                visible: true,
                className: "formPostulateSubmit",
                closeModal: false
            }
        }
    }).then(function (retorno) {
        console.log(retorno);
        switch (retorno) {
            case true:
                var data = jQuery('.swal-modal.formPostulate form.formData');
                postulate(data, serialOferta);
                break;
            case false:
                break;
            default:
                break;
        }
    });
}

function sendDeletePostulation(serial) {

    var serialOferta = serial;

    swal({
        icon: 'warning',
        title: "¿Deseas eliminar tu postulación?",
        text: 'Elimina tu postulación',
        className: 'deletePostulate',
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
                className: "formDeletePostulateSubmit",
                closeModal: false
            }
        }
    }).then(
        function (retorno) {
            console.log(retorno);
            switch (retorno) {
                case true:
                    deletePostulate(serialOferta);
                    break;
                case false:

                    break;

                default:
                    break;
            }
        });

}

function selectPostulantContrat(postulant) {

    var values = postulant;
    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log(valJson);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'selectPostulantContrat',
            selectPostulant: valJson
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
                className: 'errorSelectPostulant'
            });
        },
        success: function (response) {
            console.log("exito", response);
            swal({
                icon: 'success',
                title: '¡Postulante seleccionado!',
                text: "Estaremos en etapa de mediación con el postulante ;)",
                className: 'successSelectPostulate'
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


function sendSelectPostulantContrat(idPostulant, serial) {

    var postulant = {
        idPostulan: idPostulant,
        serial: serial
    };

    swal({
        icon: 'warning',
        title: "¿Deseas seleccionar al postulante?",
        text: 'La oferta de vacante entrara en estado de espera mientras se acuerda el contrato con el postulante. Puedes cancelar esta selección para re abrir al publico la vacante laboral',
        className: 'selectPostulant',
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
                className: "formselectPostulantSubmit",
                closeModal: false
            }
        }
    }).then(
        function (retorno) {
            console.log(retorno);
            switch (retorno) {
                case true:
                    selectPostulantContrat(postulant);
                    break;
                case false:

                    break;

                default:
                    break;
            }
        });
}


function deleteSelectPostulantContrat(postulant) {

    var values = postulant;
    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log(valJson);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'deleteSelectPostulantContrat',
            deleteSelectPostulant: valJson
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
                className: 'errorSelectPostulant'
            });
        },
        success: function (response) {
            console.log("exito", response);
            swal({
                icon: 'success',
                title: '¡Postulante seleccionado!',
                text: "Estaremos en etapa de mediación con el postulante ;)",
                className: 'successSelectPostulate'
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

function sendDeleteSelectPostulantContrat(idPostulant, serial) {

    var postulant = {
        idPostulan: idPostulant,
        serial: serial
    };
    swal({
        icon: 'warning',
        title: "¿Deseas eliminar la oferta al postulante?",
        text: 'La oferta de contrato al postulante sera eliminada. La oferta vacante volverá a su estado publico',
        className: 'deleteSelectPostulant',
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
                className: "formsDeleteElectPostulantSubmit",
                closeModal: false
            }
        }
    }).then(
        function (retorno) {
            console.log(retorno);
            switch (retorno) {
                case true:
                    deleteSelectPostulantContrat(postulant);
                    break;
                case false:

                    break;

                default:
                    break;
            }
        });
}





if(jQuery('div#containerBlog .posts').length > 0){
    var art = jQuery('div#containerBlog .posts article');
    
    jQuery.each(art, function (indexInArray, valueOfElement) { 
        var t = jQuery('h2.entry-title a', valueOfElement).text();
        console.log(t);
        var p = jQuery('.post-content p', valueOfElement).text();
        p = p.replace(t, '');
        p = p.replace('ENVIOS', '');
        
        jQuery('.post-content p', valueOfElement).text(p);

    });
    
    
}