

var campoFirmaAceptarContrato = $('#aceptarContrato > form.formData').clone()[0];
$('#aceptarContrato').remove();




// modelo para aceptar contrato sin firma
var formFirmaCandidata = jQuery('#formFirma > form').clone()[0];
jQuery('#formFirma').remove();





var validFirmaContract = {
    "campoFirmaCandidata": {
        field: 'signature',
        required: true,
        valid: {
            nullSignature: {
                message: 'Debes firmar para proceder'
            }
        }
    }
}

var validAcceptContractSg = {
    "firmaCandidata": {
        field: 'signature',
        required: true,
        valid: {
            nullSignature: {
                message: 'Debes firmar para proceder'
            }
        }
    }
}


// var infoAccept = {};

var valuesForm = [];

function sendAcceptContract() {


    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.aceptContractswal form.formData');
    // -----
    var values = valuesForm;
    var error = false;
    // console.log(values);

    // return;
    // console.log('sendfinal');
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
        // console.log('error', error);

        // se instancia la firma del candidato
        jQuery('.firmaCandidata').attr('id', 'firmaCandidata');
        var firmaCan = values['jsonFirmaCandidata'];
        jQuery('<img src="' + firmaCan + '">').appendTo('#firmaCandidata');

        var obj = _.extend({}, values);
        var valJson = JSON.stringify(obj);
        var textoContrato = jQuery('#textoContrato1').html();
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'acceptContract',
                acceptContractData: valJson,
                contractText: textoContrato
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
                jQuery('input#jsonFirmaCandidata').val('');
                jQuery('.areaFirmas .contratista div.firma img').remove();
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
                jQuery('input#jsonFirmaCandidata').val('');
                jQuery('.areaFirmas .contratista div.firma img').remove();
                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: "Contrato aceptado",
                    className: 'successContractAccept'
                }).then(
                    function (retorno) {

                        window.location.replace("/vacantes-disponibles/mis-vacantes/");
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
    configValidatorType(validFirmaContract);
}

function sendAceptarContrato(idPostulant, serial) {


    var codContrato = jQuery('#codContrato').text();

    valuesForm['idPostulan'] = idPostulant;
    valuesForm['serial'] = serial;

    valuesForm['codigoContrato'] = codContrato;

    // console.log(valuesForm);
    swal({
        icon: 'warning',
        title: "¿Deseas aceptar este contrato?",
        text: 'Firma en el siguiente espacio, asegurate de leer atentamente los detalles del contrato',
        content: campoFirmaAceptarContrato,
        className: 'aceptContractswal',
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
                className: "aceptConfirmContract",
                closeModal: false
            }
        }
    });
    jQuery('.swal-modal.aceptContractswal button.swal-button.swal-button--confirm.aceptConfirmContract').attr('onclick', 'sendAcceptContract()');

    jQuery('div#firmaCandidata').signature({
        syncField: '#jsonFirmaCandidata',
        guideline: true,
        syncFormat: 'PNG'
    });
    jQuery('.field.campoFirmaCandidata .borrar').click(function () {
        jQuery('div#firmaCandidata').signature('clear');
        jQuery('input#jsonFirmaCandidata').val('');
        jQuery('.areaFirmas .contratista div.firma img').remove();
    });

    configValidatorType(validFirmaContract);
}



function ContractRequest(data) {

    data = JSON.parse(data);

    var obj = _.extend({}, data);
    var valJson = JSON.stringify(obj);

    // console.log(valJson);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'ContractRequest',
            contractRequest: valJson,
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
                text: "Contrato enviado",
                className: 'successContractAccept'
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


function sendContractRequest(data) {

    console.log(data);

    swal({
        icon: 'warning',
        title: "¿Deseas enviar este modelo de contrato?",
        text: 'El usuario candidato lo verá en cuanto pueda',
        className: 'sendContractRequest',
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
                className: "sendContractButton",
                closeModal: false
            }
        }
    });
    data = JSON.stringify(data);
    jQuery(".swal-modal.sendContractRequest button.swal-button.swal-button--confirm.sendContractButton").attr("onclick", "ContractRequest('" + data + "')");

}


// aceptar contrato con firma
function acceptContractSg(data) {

    swal({
        icon: 'info',
        title: "Aceptar contrato",
        text: 'Tomaremos la firma guardada en tu panel de usuario',
        className: 'formFirmaCandidata',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formFirmaCandidataClose",
                closeModal: true
            },
            confirm: {
                text: 'Confirmar',
                value: true,
                visible: true,
                className: "formFirmaCandidataConfirm",
                closeModal: false
            }
        }
    });


    data = JSON.stringify(data);


    jQuery('.swal-modal.formFirmaCandidata button.swal-button.swal-button--confirm.formFirmaCandidataConfirm').attr('onclick', "processacceptContractSg(" + data + ")");


}

function processacceptContractSg(data) {


    var codContrato = jQuery('#codContrato').text();
    valuesForm['idPostulan'] = data['idPostulan'];
    valuesForm['serial'] = data['serial'];
    valuesForm['codigoContrato'] = codContrato;

    var values = valuesForm;


    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);
    var textoContrato = jQuery('#textoContrato1').html();
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'acceptContract',
            acceptContractData: valJson,
            contractText: textoContrato
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
                text: "Contrato aceptado",
                className: 'successContractAccept'
            }).then(
                function (retorno) {

                    // window.location.replace("/vacantes-disponibles/mis-vacantes/");

                    window.location.reload();
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

// aceptar contrato sin firma
function acceptContractNoSg(data) {


    var codContrato = jQuery('#codContrato').text();

    valuesForm['idPostulan'] = data['idPostulan'];
    valuesForm['serial'] = data['serial'];
    valuesForm['codigoContrato'] = codContrato;

    var m = formFirmaCandidata;

    swal({
        icon: 'success',
        title: "Firma del contrato",
        text: 'Si estas de acuerdo con los términos del servicio, firma el documento',
        content: m,
        className: 'formFirmaCandidata',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formFirmaCandidataClose",
                closeModal: true
            },
            confirm: {
                text: 'Confirmar',
                value: true,
                visible: true,
                className: "formFirmaCandidataConfirm",
                closeModal: false
            }
        }
    });
    // firmaFamilia
    // jsonfirmaFamilia
    jQuery('div#firmaCandidata').signature({
        syncField: '#jsonfirmaCandidata',
        guideline: true,
        syncFormat: 'PNG'
    });

    jQuery('#jsonfirmaCandidata').val('');

    // se setea la firma en caso de que exista
    jQuery('.field.firmaCandidata .borrar').click(function () {
        jQuery('div#firmaCandidata').signature('clear');
        jQuery('input#jsonfirmaCandidata').val('');
        // jQuery('.directivaFirma img').attr('src', '');
    });

    jQuery('.swal-modal.formFirmaCandidata button.swal-button.swal-button--confirm.formFirmaCandidataConfirm').attr('onclick', 'saveSign()');

    configValidatorType(validAcceptContractSg);

}

// codigoContrato: "c-5dcc7146c9a7c1.04336442"
// guardarFirma: "on"
// idPostulan: "15"
// jsonfirmaCandidata: ""
// serial: "O-5dc0f6dc1f2274.10497176"
// proceso sin firma
function saveSign() {

    // se jala la información del formulario enviado
    var info = jQuery('.swal-modal.formFirmaCandidata form.formData');
    // -----
    var values = valuesForm;
    var error = false;
    // console.log(values);

    // jQuery('.areaFirmas  .candidata img').attr('src', )
    // return;
    // console.log('sendfinal');
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
        // console.log('error', error);

        // se instancia la firma del candidato
        jQuery('.firmaCandidata').attr('id', 'firmaCandidata');
        var firmaCan = values['jsonfirmaCandidata'];
        jQuery('.areaFirmas .candidata img').attr('src', firmaCan);
        // var l = jQuery('.field.form-group.guardarFirma input:checked').length;
        // console.log(values);
        // return;
        jQuery(info).remove();

        var obj = _.extend({}, values);
        var valJson = JSON.stringify(obj);
        var textoContrato = jQuery('#textoContrato1').html();
        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: {
                action: 'acceptContract',
                acceptContractData: valJson,
                contractText: textoContrato
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
                jQuery('input#jsonFirmaCandidata').val('');
                jQuery('.areaFirmas .contratista div.firma img').remove();
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
                jQuery('input#jsonFirmaCandidata').val('');
                jQuery('.areaFirmas .contratista div.firma img').remove();
                swal({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: "Contrato aceptado",
                    className: 'successContractAccept'
                }).then(
                    function (retorno) {

                        // window.location.replace("/vacantes-disponibles/mis-vacantes/");

                        window.location.reload();
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