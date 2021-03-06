

// -------------------------------------------------------

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

// --------------------------------------------------------

// formFirma

var formFirmaFamilia = jQuery('#formFirma > form').clone()[0];
jQuery('#formFirma').remove();

var formPayService = jQuery('#formPayIt > form').clone()[0];
jQuery('#formPayIt').remove();



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
                message: 'Debes denotar el sueldo, solo se admiten números '
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
    },
    "imagenPrincipal": {
        field: 'imagen',
        required: true,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            },
            nullImage: {
                message: 'Debes subir una imagen principal'
            }
        }
    },
    "extraImagen1": {
        field: 'imagen',
        required: false,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "extraImagen2": {
        field: 'imagen',
        required: false,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "extraImagen3": {
        field: 'imagen',
        required: false,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "extraImagen4": {
        field: 'imagen',
        required: false,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    }

}

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

var datos = {
    'step': 1
}
// datos.step = 1;

var dataOferta = new FormData();

var terminosCompleto = '';
configValidatorType(validCreateOffer);



function continueCreateVacant() {

    if (datos.step == 1) {
        // console.log(datosProceso.contrato);
        // return;
        // configValidatorType(validCreateOffer);
        // validador

        var info = jQuery('#formSentOffer form.formData');
        var values = [];
        var error = false;

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

        // console.log(values);

        if (error != true) {

            var formData1 = new FormData(jQuery('#formSentOffer form.formData')[0]);


            // return;
            dataOferta = formData1;

            // console.log(dataOferta);

            var x = {
                'step': datos.step,
                'dataService': jQuery.extend({}, values),
                // 'dataService': values,
                'contratoServicio': datosProceso.contrato
            }

            // console.log(datosProceso.contrato);
            // console.log(x);
            // return;
            processStep(x);
        }
        return;
    }
    if (datos.step == 2) {
        var info = jQuery('#formSentContract');

        console.log(datos);

        defineAdminFirma();


        return;

    }
}

function processStep(data) {

    // console.log(data);
    // return;
    var p = data.step
    var pgActual = ((p) && (p != 1)) ? p : 1;
    data.step = pgActual + 1;

    // console.log(data);
    datos = data;
    console.log(datos);
    // if (data.dataService) {

    //     // var aux =

    //     // datos['datos'] = JSON.stringify(aux);
    //     // console.log(datos);
    //     // return;
    // }
    // if(data.contratoServicio){

    //     // //  x = JSON.stringify(data.contratoServicio);
    //     // var x = data.contratoServicio;

    //     // datos['contratoServicio'] = x;

    // }

    // var obj = _.extend({}, data);
    var obj = data;
    // console.log(obj);
    // return;
    var valJson = JSON.stringify(obj);
    // console.log(valJson);
    // return;

    // console.log(valJson);
    // return;
    jQuery.ajax({
        url: s.ajaxurl,
        type: "POST",
        data: {
            action: "stepNewVacant",
            stepNewVacant: valJson
        },
        beforeSend: function (result) {
            console.log(result);
            // jQuery("#spinnerLoad").css('display', 'flex');
            jQuery("#spinnerLoad").css('display', 'flex');
        },
        success: function (data) {

            // alert(data);
            data = data.slice(0, -1);
            tipo = '';
            console.log(data);
            //
            jQuery("#containerNewOffer").html(data).fadeIn('slow');


        },
        complete: function () {

            // jQuery("#spinnerLoad").css('display', 'none');
            jQuery("#spinnerLoad").css('display', 'none');
        }

    })

}

function continueUseSign() {

    if (datos.step == 2) {
        var info = jQuery('#formSentContract').html();
        terminosCompleto = info;

        var fe = datosProceso.prevFamiliaFirma;

        datos['firmaFamilia'] = fe
        datos['guardarFirma'] = 0
        // datos['contratoTerminos'] = info

        datos.step = datos.step + 1;

        // console.log('sin transformar',datos);
        // return;
        var obj = datos;
        var valJson = JSON.stringify(obj);
        // info = JSON.stringify(info);
        // info =  escape(info);
        // var valJson = obj;
        // valJson = valJson.replace('\"', '"');

        // valJson = valJson.replace( "\\\"", '"');

        // console.log('transformado',valJson);

        // return;
        jQuery.ajax({
            url: s.ajaxurl,
            type: "POST",
            // dataType: 'json',
            data: {
                action: "stepNewVacant",
                stepNewVacant: valJson
            },
            beforeSend: function (result) {
                console.log(result);
                // jQuery("#spinnerLoad").css('display', 'flex');
                jQuery("#spinnerLoad").css('display', 'flex');
            },
            success: function (data) {

                // alert(data);
                data = data.slice(0, -1);
                tipo = '';

                //
                jQuery("#containerNewOffer").html(data).fadeIn('slow');


            },
            complete: function () {

                // jQuery("#spinnerLoad").css('display', 'none');
                jQuery("#spinnerLoad").css('display', 'none');
            }

        })






        return;

    }
}



function defineAdminFirma() {

    var m = formFirmaFamilia;

    swal({
        icon: 'success',
        title: "Firma del contrato",
        text: 'Si estas de acuerdo con los términos del servicio, firma el documento',
        content: m,
        className: 'formFirmaFamilia',
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "formFirmaFamiliaClose",
                closeModal: true
            },
            confirm: {
                text: 'Confirmar',
                value: true,
                visible: true,
                className: "formFirmaFamiliaConfirm",
                closeModal: true
            }
        }
    });
    // firmaFamilia
    // jsonfirmaFamilia
    jQuery('div#firmaFamilia').signature({
        syncField: '#jsonfirmaFamilia',
        guideline: true,
        syncFormat: 'PNG'
    });

    // se setea la firma en caso de que exista
    jQuery('.field.firmaFamilia .borrar').click(function () {
        jQuery('div#firmaFamilia').signature('clear');
        jQuery('input#jsonfirmaFamilia').val('');
        // jQuery('.directivaFirma img').attr('src', '');
    });

    jQuery('.swal-modal.formFirmaFamilia button.swal-button.swal-button--confirm.formFirmaFamiliaConfirm').attr('onclick', 'saveSign()');

}

function saveSign() {
    var info = jQuery('.swal-modal.formFirmaFamilia form.formData');

    var contractService = jQuery('#formSentContract').html();

    // console.log(contractService);
    // -----
    var x = jQuery('#formSentContract').html();
    terminosCompleto = x;
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

    if ((values['jsonfirmaFamilia']) && (values['jsonfirmaFamilia'] != null) && (values['jsonfirmaFamilia'] != '')) {

        console.log('hay firma');

        jQuery('#firmaFamiliaContrato img').attr('src', values['jsonfirmaFamilia']);
        jQuery('input#jsonfirmaFamilia').attr('value', values['jsonfirmaFamilia']);
        // datos.push({'firmaFamilia': values['jsonfirmaFamilia']});

        var l = jQuery('.field.form-group.guardarFirma input:checked').length;

        datos['firmaFamilia'] = values['jsonfirmaFamilia'];
        datos['guardarFirma'] = l;

        datos.step = datos.step + 1;

        jQuery(info).remove();

        var obj = datos;
        var valJson = JSON.stringify(obj);
        jQuery.ajax({
            url: s.ajaxurl,
            type: "POST",
            data: {
                action: "stepNewVacant",
                stepNewVacant: valJson,
                terminosCompleto: terminosCompleto
            },
            beforeSend: function (result) {
                console.log(result);
                // jQuery("#spinnerLoad").css('display', 'flex');
                jQuery("#spinnerLoad").css('display', 'flex');
            },
            success: function (data) {

                // alert(data);
                data = data.slice(0, -1);
                tipo = '';
                console.log(data);
                //
                jQuery("#containerNewOffer").html(data).fadeIn('slow');


            },
            complete: function () {

                // jQuery("#spinnerLoad").css('display', 'none');
                jQuery("#spinnerLoad").css('display', 'none');
            }

        })
    } else {
        swal({
            icon: 'error',
            title: "Se requiere tu firma",
            // text: 'Por favor intente mas tarde',
            className: 'errorSentOffer'
        });
    }


    swal.stopLoading();
}


// pagar ahora
function payService() {

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

    console.log('objeto', datos);
    // AHORA ESTOY AQUI

    if (error != true) {
        datos.step = datos.step + 1;
        // se toman los datos de la factura
        // var formData1 = new FormData(jQuery('.swal-modal.formPayNow form.formData')[0]);
        var form = jQuery('.swal-modal.formPayNow form.formData');
        // var form = jQuery('#formSentOffer form.formData');

        // var files = jQuery("#comprobante")[0].files;
        var images =  jQuery('input:file', form);

        jQuery.each(images, function (indexInArray, valueOfElement) {
            var n = jQuery(valueOfElement).attr('name');
            var f = jQuery(valueOfElement)[0].files[0];

                if(f){
                    dataOferta.append(n, f);
                    // console.log('res', formData.get(n))
                }

            });

        obj = _.extend({}, values);
        var m = JSON.stringify(obj);

        dataOferta.append('paginaId', JSON.stringify(datos.contratoServicio));
        dataOferta.append('firmaFamilia', JSON.stringify(datos.firmaFamilia));
        dataOferta.append('guardarFirma', JSON.stringify(datos.guardarFirma));
        dataOferta.append('datosFactura', m);
        dataOferta.append('terminosCompleto', terminosCompleto);
        dataOferta.append('action', 'processpayService');

        console.log(terminosCompleto);

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: dataOferta,
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

// pagar despuess
function payLater() {


    swal({
        icon: 'info',
        title: "Puedes pagar después",
        text: 'Mientras tanto tu publicación estará almacenada',
        // content: formDetailsChangePetition,
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
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formSubmitConfirm",
                closeModal: false
            }
        }
    });



    jQuery('.swal-modal.formSendReasonsChange button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "processpayLater()");


}


// REFERENCia

function processpayLater() {

    var url = datosProceso.dashBoardUrl;
    datos.step = datos.step + 1;

    // console.log(url);
        // var m = (Object.values(datos));
        var m = JSON.stringify(datos);
        // var m = Object.values(datos);
        dataOferta.append('datos', m);
        dataOferta.append('terminosCompleto', terminosCompleto);
        dataOferta.append('action', 'payLater');


    // var obj = datos;
    // var valJson = JSON.stringify(obj);
    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: dataOferta,
        processData: false,
        contentType: false,
        // data: {
        //     action: 'payLater',
        //     payLater: valJson,
        //     terminosCompleto: terminosCompleto
        // },
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


//