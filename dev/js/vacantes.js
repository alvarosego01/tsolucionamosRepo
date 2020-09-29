


function filterAllVacants(data){

	var filter = data;

	var values = [];

	values['tipo'] = filter;


    obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log(valJson);

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'processfilterAllVacants',
            processfilterAllVacants: valJson
        },
        beforeSend: function () {
            console.log("before");
            // setting a timeout
            jQuery("#spinnerLoad").css('display', 'flex');
        },
        error: function () {
            console.log("error");

            jQuery('.swal-modal.formSelectForContract').remove();
            // swal({
            //     icon: 'error',
            //     title: "No pudimos procesar tu solicitud",
            //     text: 'Por favor intente mas tarde',
            //     className: 'errorSentOffer'
            // });

        },
        success: function (response) {
            console.log("exito", response);

               // alert(data);
               response = response.slice(0, -1);
               // tipo = '';
               jQuery("#listOfferts .list").html(response).fadeIn('slow');


        },
        complete: function () {
            console.log("complete");



            // window.location = pagina;

            jQuery("#spinnerLoad").css('display', 'none');
        },
    });

}