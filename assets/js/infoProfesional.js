
// jQuery('ul.boxImg li .opc button').click(function (e) {
//     // e.preventDefault();

//     console.log('dispara boton', e);

//     var padre = jQuery(e['currentTarget']).parent().parent();

// // jQuery('.swal-content ul.boxImg li').attr('dis','false');
// // jQuery('.swal-content ul.boxImg li').removeClass('remove');

// var att = jQuery(padre).attr('dis');

// att = (att == 'false')? 'true': 'false';

// jQuery(padre).attr('dis', att);

// jQuery(padre).toggleClass('remove');


// // jQuery(padre).toggleClass('remove');



// });




var formUpdateProfesional = jQuery('#formUpdateProfesional > form').clone()[0];
jQuery('#formUpdateProfesional').remove();




var formDataPresupuesto = jQuery('#formDataPresupuesto form').clone()[0];
jQuery('#formDataPresupuesto').remove();

jQuery(document).on('click', '[data-toggle="lightbox"]', function(event) {


                event.preventDefault();
                jQuery(this).ekkoLightbox();
});

var mediaFotos = null;
var mediaFotosAux = null;


var validPresupuesto = {

	"nombre": {
		field: 'textfield',
		required: true,
		valid: {
			null: {
                message: 'Debes escribir tu nombre'
            }
		}
	},
	"telefono": {
		field: 'textfield',
		required: true,
		valid: {
			null: {
                message: 'Debes especificar un teléfono de contacto'
            }
		}
	},
	"departamentos": {
		field: 'select',
		required: true,
		valid: {
			  nullSelect: {
                message: 'Debes seleccionar un departamento'
            }
		}
	},
	"ciudadDireccion": {
		field: 'textfield',
		required: true,
		valid: {
			null: {
                message: 'Debes especificar una ciudad o dirección'
            }
		}
	},
	"fechaServicio": {
		field: 'textfield',
		required: true,
		valid: {
			null: {
                message: 'Debes seleccionar una fecha'
            }
		}
	},
	"hora": {
		field: 'textfield',
		required: true,
		valid: {
			null: {
                message: 'Debes especificar una hora'
            }
		}
	},
	"detallesServicio": {
		field: 'textarea',
		required: true,
		valid: {
			null: {
                message: 'Debes describir a fondo tus necesidades'
            }
		}
	}

}



var validnewWork = {

    "logo": {
        field: 'imagen',
        required: false,
        valid: {
            formatImages: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "imagenes": {
        field: 'imagen',
        required: false,
        valid: {
            nullImage1: {
                message: 'Debes subir al menos 1 imagen'
            },
            nullImageOver10: {
                message: 'Solo un máximo de 10 imagenes'
            },
            formatImages: {
                message: 'Tipo de archivo inválido, solo se permite JPG/JPEG/PNG'
            }
        }
    },
    "video": {
        field: 'video',
        required: false,
        valid: {

            nullTime15: {
                message: 'El video debe tener máximo 1.5 minutos de duración y ser de formato mp4/avi/3gp'
            },
        }
    },
    "instagram": {
        field: 'url',
        required: false,
        valid: {
            nullUrl: {
                message: 'Debes escribir una dirección de Instagram valida'
            }

        }
    },
    "facebook": {
        field: 'url',
        required: false,
        valid: {
            nullUrl: {
                message: 'Debes escribir una dirección de Facebook valida'
            }

        }
    },
    "twitter": {
        field: 'url',
        required: false,
        valid: {
            nullUrl: {
                message: 'Debes escribir una dirección de Twitter valida'
            }

        }
    }
}



configValidatorType(validnewWork);




function solicitaPresupuesto(data){

    var serialOferta = data;

    swal({
        title: "¿Te interesa este servicio?",
        text: 'Solicita tu presupuesto, llena los siguientes datos',
        content: formDataPresupuesto,
        className: 'formSendPresupuesto',
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


     jQuery("#fechaServicio").datepicker({ minDate: 2, language: 'es', dateFormat: 'dd/mm/yy' });

    var options = {
        now: "12:35", //hh:mm 24 hour format only, defaults to current time
        twentyFour: false, //Display 24 hour format, defaults to false
        upArrow: 'wickedpicker__controls__control-up', //The up arrow class selector to use, for custom CSS
        downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        hoverState: 'hover-state', //The hover state class to use, for custom CSS
        title: 'Selector de hora', //The Wickedpicker's title,
        showSeconds: false, //Whether or not to show seconds,
        secondsInterval: 1, //Change interval for seconds, defaults to 1 ,
        minutesInterval: 1, //Change interval for minutes, defaults to 1
        beforeShow: null, //A function to be called before the Wickedpicker is shown
        show: null, //A function to be called when the Wickedpicker is shown
        clearable: false, //Make the picker's input clearable (has clickable "x")
    };

    jQuery('input#hora').wickedpicker(options);
    flechasHoraPicker();



    // colocado de la función de efecto click
        data = JSON.stringify(data);
    jQuery(".swal-modal.formSendPresupuesto button.swal-button.swal-button--confirm.formEditOfferButton").attr("onclick", "sendsolicitaPresupuesto('" + data + "')");

    // validaciones
    configValidatorType(validPresupuesto);


}


function sendsolicitaPresupuesto(data){

	 var info = jQuery('.swal-modal.formSendPresupuesto form');
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

    var x = JSON.parse(data);

    values['solicitado'] = x;

    var d = values;

    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    console.log(valJson);
    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'sendsolicitaPresupuesto',
            sendsolicitaPresupuesto: valJson
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
        success: function (data) {
            console.log("exito", data);

            swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Presupuesto solicitado, el profesional se pondrá en contacto contigo',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });
        },
        complete: function () {
            console.log("complete");
            swal.stopLoading();

        },
    });

}




    function updatePublicProfesional(id){

        var auxForm = formUpdateProfesional;




        var l = {
            'idPublicacion': id
        }
       var data = JSON.stringify(l);
        // estado = JSON.stringify(l);

    swal({
        icon: 'info',
        title: "Modificar publicación",
        text: 'Llena la información que desees modificar',
        content: auxForm,
        className: 'formUpdatePublicacion',
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
                className: "formSubmitConfirm",
                closeModal: false
            }
        }
    });


  jQuery('.swal-modal.formUpdatePublicacion button.swal-button.swal-button--confirm.formSubmitConfirm').attr('onclick', "continueCreateVacant(" + data + ")");





var prueba = jQuery('ul.boxImg');
console.log(prueba);
new Sortable(prueba[0], {
    animation: 150,
    ghostClass: 'blue-background-class',
    handle: '.handle', // handle's class
    filter: '.remove', // 'filtered' class is not draggable
});



jQuery('ul.boxImg li .opc button').on('click', function (e) {



    console.log('dispara boton', e);

    var padre = jQuery(e['currentTarget']).parent().parent();

// jQuery('.swal-content ul.boxImg li').attr('dis','false');
// jQuery('.swal-content ul.boxImg li').removeClass('remove');

var att = jQuery(padre).attr('dis');

att = (att == 'false')? 'true': 'false';

jQuery(padre).attr('dis', att);

jQuery(padre).toggleClass('remove');


// jQuery(padre).toggleClass('remove');

return;

});



    }





var dataPublicacionProfesional = new FormData();
var dataPagoFields = new FormData();


function continueCreateVacant(data) {


    console.log('la data', data);

        mediaFotosAux = mediaFotos;
        // return;
        var info = jQuery('.formUpdatePublicacion form.formData');
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

            var formData1 = new FormData(jQuery('.formUpdatePublicacion form.formData')[0]);

            dataPublicacionProfesional = formData1;

            if(jQuery('#imagenes').length > 0){
                var f = jQuery('#imagenes')[0].files;
                var fls = [];
                var v = [];
                console.log(f.length);
                jQuery.each(f, function(i, file) {
                    dataPublicacionProfesional.append('imagesProfeshional[]', file);
                });
            }



            var l = jQuery('.swal-content ul.boxImg li');
            var auxOrden = [];


            for (let index = 0; index < l.length; index++) {
                const element = l[index];

                var nro = jQuery(element).attr('nro');
                var dis = jQuery(element).attr('dis');
//
                var lll = {
                    nro: nro,
                    dis: dis
                }

                auxOrden.push(lll);


            }

            var x = {
                // 'step': datos.step,
                serialOferta: data,
                auxOrden: JSON.stringify(auxOrden),
                // 'dataService': jQuery.extend({}, values),
            }



            console.log('la informacion', x);
            // return;

            processStep(x);
        }
        // return;


}

function processStep(data) {


        var obj = data;
        // return;
        var valJson = JSON.stringify(obj);
        // dataPublicacionProfesional.append('step', data.step);
        dataPublicacionProfesional.append('tipo', 'update');
        dataPublicacionProfesional.append('dataProfesional', valJson);
        dataPublicacionProfesional.append('action', 'updateProfesional');

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
                console.log(data);
              swal({
                icon: 'success',
                title: '¡Listo!',
                text: 'Se ha modificado la publicación',
                className: 'successSendOffer'
            }).then(
                function (retorno) {
                    location.reload();
                });

            },
            complete: function () {
                console.log("complete");
                swal.stopLoading();

                jQuery("#spinnerLoad").css('display', 'none');
            },
        });




}




jQuery(document).ready(function () {



    if(dataPublicacion.data){

        // console.log(dataPublicacion.data);

        var aux = dataPublicacion.data.media;
        aux = JSON.parse(aux);
        mediaFotos = aux.imagesProfeshional;
        // console.log
        // console.log('infoProfesional', mediaFotos);

    }


    // jQuery('ul.boxImg li .opc button').click(function (e) {
    //     // e.preventDefault();

    // console.log('dispara boton', e);
    // var padre = jQuery(e['currentTarget']).parent().parent();
    // // jQuery('.swal-content ul.boxImg li').attr('dis','false');
    // // jQuery('.swal-content ul.boxImg li').removeClass('remove');
    // var att = jQuery(padre).attr('dis');
    // att = (att == 'false')? 'true': 'false';
    // jQuery(padre).attr('dis', att);
    // jQuery(padre).toggleClass('remove');

    // // jQuery(padre).toggleClass('remove');
    // });

});

