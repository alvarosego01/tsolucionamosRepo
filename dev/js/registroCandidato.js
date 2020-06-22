


console.log('registros');






function buscarDocumentoRegistros(documento) {

    var values = [];
    values['documento'] = documento;


    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'buscarDocumentoRegistros',
            buscarDocumentoRegistros: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            // jQuery("#spinnerPro").css('visibility', 'visible');
        },
        error: function () {
            console.log("error");
            // swal.stopLoading();
            // swal({
            //     icon: 'error',
            //     title: "No pudimos procesar tu solicitud",
            //     text: 'Por favor intente mas tarde',
            //     className: 'errorSentOffer'
            // });
        },
        success: function (data) {
            console.log("exito", data);
            // swal({
            //     icon: 'success',
            //     className: 'successDeleteOfferLabora'
            // }).then(
            //     function (retorno) {
            //         location.reload();
            //     });

            // jQuery("body").html(data).fadeIn('slow');
            if (data.includes('preUserData')) {
                console.log('existe data', data.length);
                jQuery('.preTag').remove();
                jQuery('#DocumentoIdentidad-89').after('<small class="preTag">Usuario con información pre cargada</small>');
            } else {
                jQuery('.preTag').remove();
            }

        },
        complete: function () {
            console.log("complete");
            // swal.stopLoading();
            // window.location = pagina;
            // jQuery("#spinnerPro").css('visibility', 'hidden');
        },
    });
}













var clockResetIndex = 0;
// this is the input we are tracking
jQuery('#DocumentoIdentidad-89').on('keyup keypress paste', function () {
    // reset any privious clock:
    if (clockResetIndex !== 0) clearTimeout(clockResetIndex);

    // set a new clock ( timeout )
    clockResetIndex = setTimeout(function () {
        // your code goes here :
        console.log('Documento escrito');

        var d = jQuery('#DocumentoIdentidad-89').val();
        // console.log(d);
        buscarDocumentoRegistros(d);
    }, 2000);
});

