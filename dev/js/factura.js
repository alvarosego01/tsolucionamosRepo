var formPayService = jQuery('#formPayIt > form').clone()[0];
jQuery('#formPayIt').remove();





var formRefusePay = jQuery('#formRefusePay > form').clone()[0];
jQuery('#formRefusePay').remove();


var dataPago = {
    'tipo': {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar la cuenta bancaría a la que transferiste'
            }
        }
    },
    'referencia': {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir el número de transacción o comprobante'
            }
        }
    },
    'comprobante': {
        field: 'facturaComprobante',
        required: true,
        valid: {
            nullFComprobante: {
                message: 'Debes subir un comprobante de transacción'
            },
            formatFComprobante: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG/PDF'
            }
        }
    },
}

var refusePayData = {
    'refuseNote': {
        field: 'textarea',
        required: true,
        valid: {
            null: {
                message: 'Es necesario detallar las razones'
            }
        }
    }
}


function refusePay(data){


    swal({
        icon: 'info',
        title: "Rechazar comprobante de pago",
        text: 'Se solicitará a la familia rehacer el proceso de pago',
        content: formRefusePay,
        className: 'formRefusePay',
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
                className: "formRefuseAccept",
                closeModal: false
            }
        }
    });

    data = JSON.stringify(data);
    // colocado de la función de efecto click
    jQuery('.swal-modal.formRefusePay button.swal-button.swal-button--confirm.formRefuseAccept').attr('onclick',"sendrefusePay(" + data + ")");

    configValidatorType(refusePayData);


}

function sendrefusePay($data){

    var info = jQuery('.swal-modal.formRefusePay form.formData');
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

    values['serial'] = $data;

    // console.log(values);
    // swal.stopLoading();
    // return;

    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log(valJson);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendrefusePay',
            sendrefusePay: valJson
        },
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
                text: "Paga tu factura, para que tu publicación sea visible",
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


}

function acceptPay(data){

    swal({
        icon: 'info',
        title: "Confirmar transacción",
        // text: 'Carga los siguientes datos',
        // content: formRefusePay,
        className: 'formAcceptPay',
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
                className: "formAcceptPay",
                closeModal: false
            }
        }
    });

        data = JSON.stringify(data);
        console.log(data);
        // colocado de la función de efecto click
        jQuery('.swal-modal.formAcceptPay button.swal-button.swal-button--confirm.formAcceptPay').attr('onclick',"sendacceptPay(" + data + ")");

}

function sendacceptPay(data){

    var valJson = JSON.stringify(data);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendacceptPay',
            sendacceptPay: valJson
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
                title: '¡Exito!',
                text: "El pago ha sido confirmado",
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


function deletePay(data){


    swal({
        icon: 'info',
        title: "Eliminar transacción",
        text: 'Esta acción eliminará tambien el servicio ligado a esta factura',
        // content: formRefusePay,
        className: 'formDeletePay',
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
                className: "formDeletePay",
                closeModal: false
            }
        }
    });

        data = JSON.stringify(data);
        // colocado de la función de efecto click
        jQuery('.swal-modal.formDeletePay button.swal-button.swal-button--confirm.formDeletePay').attr('onclick',"senddeletePay(" + data + ")");

}


function senddeletePay(data){

    var valJson = JSON.stringify(data);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'senddeletePay',
            senddeletePay: valJson
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
                title: '¡Exito!',
                text: "La factura y el servicio han sido eliminados",
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
function payService(data) {

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



    data = JSON.stringify(data);

    configValidatorType(dataPago);

    jQuery('.swal-modal.formPayNow button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "processpayService(" + data + ")");


}

function processpayService(data) {


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


    // data = JSON.stringify(data);

    console.log(data);

    if (error != true) {

    var formData1 = new FormData(jQuery('.swal-modal.formPayNow form.formData')[0]);

    formData1.append( 'serial', data);
    formData1.append( 'action', 'afterPayBill');

    // console.log(terminosCompleto);

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