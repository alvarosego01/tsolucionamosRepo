

var formDataPresupuesto = jQuery('#formDataPresupuesto form').clone()[0];
jQuery('#formDataPresupuesto').remove();

jQuery(document).on('click', '[data-toggle="lightbox"]', function(event) {


                event.preventDefault();
                jQuery(this).ekkoLightbox();
});



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

