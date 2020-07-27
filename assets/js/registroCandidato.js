


var addRefer = jQuery('#addRefer').clone()[0];
jQuery('#addRefer').remove();



console.log(addRefer);

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



function sendaddRef(data){



     var info = jQuery('#addRefer');
    var values = [];
    var siguienteEnt = [];
    var error = false;

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
    info[0].reset();

          var nt = '';

        // Nombre de la persona que dará la referencia. Teléfono, cargo que desempeño y ano y mes de trabajo
        nt += (values['nombrePersona'] != null && values['nombrePersona'] != '')? ' Nombre: ' + values['nombrePersona']  : '';
        nt += (values['telefono'] != null && values['telefono'] != '')? ' teléfono: ' + values['telefono'] : '';
        nt += (values['cargo'] != null && values['cargo'] != '')? ' cargo: ' + values['cargo'] : '';
        nt += (values['anio'] != null && values['anio'] != '')? ' año: ' + values['anio'] : '';
        nt += (values['mes'] != null && values['mes'] != '')? ' mes: ' + values['mes'] : '';


    if(data == 'personal' && nt != ''){

        var l = jQuery("textarea#refPersonalesText").text();

        l += (l != '')? '\n' + nt : nt;

        jQuery("textarea#refPersonalesText").text(l);

    }

    if(data == 'laboral' && nt != ''){

        var l = jQuery("textarea#refLaboralesText").text();

        l += (l != '')? '\n' + nt : nt;

        jQuery("textarea#refLaboralesText").text(l);

    }



    swal.stopLoading();
}


function addRef(data){
    console.log(data);

       swal({
        icon: 'success',
        title: "Agrega una referencia " + data,
        // text: 'Carga los datos de la siguiente manera',
        content: addRefer,
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
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formRefuseAccept",
                closeModal: false
            }
        }
    });

       jQuery('#addRefer').css({
           display: 'block',
       });

    var dt = JSON.stringify(data);
    // colocado de la función de efecto click
    jQuery('.swal-modal.formRefusePay button.swal-button.swal-button--confirm.formRefuseAccept').attr('onclick',"sendaddRef(" + dt + ")");




}
function deleteRef(data){
    console.log(data);

    if(data == 'personal'){
jQuery("textarea#refPersonalesText").text('');
    }

    if(data == 'laboral'){
jQuery("textarea#refLaboralesText").text('');
    }

}





    jQuery('textarea#refPersonalesText').attr('readonly', 'true');
    jQuery('textarea#refLaboralesText').attr('readonly', 'true');


var bt = "<button data-toggle='tooltip' data-placement='bottom' title='Agregar referencia' type='button' onclick='addRef("+'"personal"'+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button><button data-toggle='tooltip' data-placement='bottom' title='Eliminar referencias' type='button' onclick='deleteRef("+'"personal"'+")'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";

jQuery(bt).insertAfter("textarea#refPersonalesText");

bt = "<button data-toggle='tooltip' data-placement='bottom' title='Agregar referencia' type='button' onclick='addRef("+'"laboral"'+")'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button><button data-toggle='tooltip' data-placement='bottom' title='Eliminar referencias' type='button' onclick='deleteRef("+'"laboral"'+")'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";

jQuery(bt).insertAfter("textarea#refLaboralesText");
