

var formPayIt = jQuery('#formPayIt > form').clone()[0];
jQuery('#formPayIt').remove();



var validnewWork = {
    "titulopublicacion": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir el titulo de tu publicación'
            }
        }
    },
    "nombreEmpresa": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes especificar si prestas servicios como empresa ó persona particular'
            }
        }
    },
    "categoria": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar una categoría'
            }
        }
    },
    "horario": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar un horario'
            }
        }
    },
    "departamento": {
        field: 'select',
        required: true,
        valid: {
            nullSelect: {
                message: 'Debes seleccionar un departamento'
            }
        }
    },
    "ciudad": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir el nombre de la ciudad donde prestas tus servicios'
            }
        }
    },
    "direccion": {
        field: 'textfield',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir una dirección'
            }

        }
    },
    "telefono": {
        field: 'textfield',
        required: true,
        valid: {
            phone: {
                message: 'Debes escribir un teléfono válido'
            }

        }
    },
    "descripcion": {
        field: 'textarea',
        required: true,
        valid: {
            null: {
                message: 'Debes escribir una descripción de tu publicación profesional'
            }
        }
    },
    "logo": {
        field: 'imagen',
        required: false,
        valid: {
            formatImage: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "imagenes": {
        field: 'imagenes',
        required: true,
        valid: {
            formatImages: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            },
            nullImage1: {
                message: 'Debes subir al menos 1 imagen'
            },
            nullImageOver10: {
                message: 'Solo un máximo de 10 imagenes'
            }
        }
    },
    "video": {
        field: 'video',
        required: false,
        valid: {
            formatVideo: {
                message: 'Tipo de archivo inválido, solo se permite mp4/avi/MKV/WMVMOV'
            },
            nullTime3: {
                message: 'El video debe tener máximo 3 minutos de duración'
            }
        }
    },
    "instagram": {
        field: 'instagram',
        required: false,
        valid: {
            nullInstagram: {
                message: 'Debes escribir una dirección de Instagram valida'
            }

        }
    },
    "facebook": {
        field: 'facebook',
        required: false,
        valid: {
            nullFacebook: {
                message: 'Debes escribir una dirección de Facebook valida'
            }

        }
    },
    "twitter": {
        field: 'twitter',
        required: false,
        valid: {
            nullTwitter: {
                message: 'Debes escribir una dirección de Twitter valida'
            }

        }
    }
}




var dataPagoFields = {
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
    'step': 2
}
// datos.step = 1;

var dataPublicacionProfesional = new FormData();
var dataPagoFields = new FormData();


function continueCreateVacant() {


    console.log('step', datos.step);
    // return;
    if (datos.step == 1) {

        var x = {
            'step': datos.step
        }

        processStep(x);
        return;
    }
    if (datos.step == 2) {



        // return;
        var info = jQuery('#formSentProfesionalPub form.formData');
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


        if (error != true) {

            var formData1 = new FormData(jQuery('#formSentProfesionalPub form.formData')[0]);

            dataPublicacionProfesional = formData1;

            var f = jQuery('#imagenes')[0].files;
            var fls = [];
            var v = [];
            console.log(f.length);
            jQuery.each(f, function(i, file) {
                dataPublicacionProfesional.append('imagesProfeshional[]', file);
            });


            var x = {
                'step': datos.step,
                'dataService': jQuery.extend({}, values),
            }

            processStep(x);
        }
        return;
    }
    if (datos.step == 3) {
        var info = jQuery('#formSentContract');

        console.log(datos);

        // defineAdminFirma();



    }
}

function processStep(data) {

    var pgActual = data.step
    data.step = pgActual + 1;
    datos = data;

    if (data.step == 3) {


        var obj = data;
        // return;
        var valJson = JSON.stringify(obj);
        dataPublicacionProfesional.append('step', data.step);
        dataPublicacionProfesional.append('tipo', 'retorno3');
        dataPublicacionProfesional.append('action', 'stepNewProfesional');

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: dataPublicacionProfesional,
            processData: false,
            contentType: false,
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
                jQuery("#spinnerLoad").css('display', 'flex');
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
            success: function (data) {
                console.log("exito", data);
                data = data.slice(0, -1);
                tipo = '';
                console.log(data);
                //
                jQuery("#nuevoProfesional").html(data).fadeIn('slow');


            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();

                jQuery("#spinnerLoad").css('display', 'none');
            },
        });


    } else {

        var obj = data;
        // return;
        var valJson = JSON.stringify(obj);
        console.log('enviado', valJson);
        // return;
        jQuery.ajax({
            url: s.ajaxurl,
            type: "POST",
            data: {
                action: "stepNewProfesional",
                stepNewProfesional: valJson
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
                jQuery("#nuevoProfesional").html(data).fadeIn('slow');


            },
            complete: function () {

                // jQuery("#spinnerLoad").css('display', 'none');
                jQuery("#spinnerLoad").css('display', 'none');


                if (data.step == 2) {
                    console.log('valid seteado');
                    // configValidatorType(validnewWork);
                }
            }

        })

        return;
    }
}


// pagar ahora
function payServiceProfesional() {

    // formPayService
    swal({
        icon: 'info',
        title: "Paga ahora tu membresía",
        text: 'Llena los siguientes datos, para procesar tu pago',
        content: formPayIt,
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



    configValidatorType(dataPagoFields);

    jQuery('.swal-modal.formPayNow button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "processpayServiceProfesional()");


}


function processpayServiceProfesional() {


    var info = jQuery('.swal-modal.formPayNow form.formData');

    var error = false;
    var values = [];
    var imagen = '';

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

            imagen = val;

            // simular click para que salgan los validate message
            var parent = jQuery(valueOfElement).parent();
            parent.click();
            if (jQuery('.validateMessage', parent).is("[error='true']")) {
                error = true;
            }
        }
    });

    if (error != true){

        datos.step = 4;

        console.log('los values', values);

        var f = jQuery('#comprobante')[0].files;
        var fls = [];
        var v = [];

        jQuery.each(f, function(i, file) {
            dataPublicacionProfesional.append('comprobante', file);
        });

        var obj = _.extend({}, values);
        var m = JSON.stringify(obj);

        // dataPublicacionProfesional.append('datosFactura[]', valueOfElement);
        dataPublicacionProfesional.append('step', datos.step);
        dataPublicacionProfesional.append('datosFactura', m);
        dataPublicacionProfesional.append('tipo', 'recibePago');
        dataPublicacionProfesional.set('action', 'stepNewProfesional');

        jQuery.ajax({
            url: s.ajaxurl,
            type: 'post',
            data: dataPublicacionProfesional,
            processData: false,
            contentType: false,
            beforeSend: function () {
                console.log("before");
                // setting a timeout
                // jQuery("#spinnerPro").css('visibility', 'visible');
                jQuery("#spinnerLoad").css('display', 'flex');
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
                    text: "La correcta publicación de tu anuncio profesional, estará visible al publico cuando hayamos aprobado el pago",
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

                jQuery("#spinnerLoad").css('display', 'none');
            },
        });

    } else {
        swal.stopLoading();
    }

}

jQuery( ".categoria select" ).change(function() {
    // Check input( $( this ).val() ) for validity here
      var l = jQuery(this).children("option:selected").val();
      if(l == 'Otro'){
      jQuery('.otroServicio').removeClass('ocultar');
      jQuery('.otroServicio input').val('');
      }else{
      jQuery('.otroServicio').addClass('ocultar');
      jQuery('.otroServicio input').val(' ');
      }
  });
