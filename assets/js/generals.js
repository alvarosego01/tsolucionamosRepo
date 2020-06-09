

/*
 *  jQuery table2excel - v1.0.2
 *  jQuery plugin to export an .xls file in browser from an HTML table
 *  https://github.com/rainabba/jquery-table2excel
 *
 *  Made by rainabba
 *  Under MIT License
 */
!function(a,b,c,d){function e(b,c){this.element=b,this.settings=a.extend({},k,c),this._defaults=k,this._name=j,this.init()}function f(a){return a.filename?a.filename:"table2excel"}function g(a){var b=/(\s+alt\s*=\s*"([^"]*)"|\s+alt\s*=\s*'([^']*)')/i;return a.replace(/<img[^>]*>/gi,function(a){var c=b.exec(a);return null!==c&&c.length>=2?c[2]:""})}function h(a){return a.replace(/<a[^>]*>|<\/a>/gi,"")}function i(a){var b=/(\s+value\s*=\s*"([^"]*)"|\s+value\s*=\s*'([^']*)')/i;return a.replace(/<input[^>]*>|<\/input>/gi,function(a){var c=b.exec(a);return null!==c&&c.length>=2?c[2]:""})}var j="table2excel",k={exclude:".noExl",name:"Table2Excel"};e.prototype={init:function(){var b=this;b.template={head:'<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head>\x3c!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>',sheet:{head:"<x:ExcelWorksheet><x:Name>",tail:"</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>"},mid:"</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--\x3e</head><body>",table:{head:"<table>",tail:"</table>"},foot:"</body></html>"},b.tableRows=[],a(b.element).each(function(c,d){var e="";a(d).find("tr").not(b.settings.exclude).each(function(c,d){e+="<tr>",a(d).find("td,th").not(b.settings.exclude).each(function(c,d){a(d).find(b.settings.exclude).length>=1?e+="<td> </td>":e+="<td>"+a(d).html()+"</td>"}),e+="</tr>"}),b.settings.exclude_img&&(e=g(e)),b.settings.exclude_links&&(e=h(e)),b.settings.exclude_inputs&&(e=i(e)),b.tableRows.push(e)}),b.tableToExcel(b.tableRows,b.settings.name,b.settings.sheetName)},tableToExcel:function(d,e,g){var h,i,j,k=this,l="";if(k.format=function(a,b){return a.replace(/{(\w+)}/g,function(a,c){return b[c]})},g=void 0===g?"Sheet":g,k.ctx={worksheet:e||"Worksheet",table:d,sheetName:g},l=k.template.head,a.isArray(d))for(h in d)l+=k.template.sheet.head+g+h+k.template.sheet.tail;if(l+=k.template.mid,a.isArray(d))for(h in d)l+=k.template.table.head+"{table"+h+"}"+k.template.table.tail;l+=k.template.foot;for(h in d)k.ctx["table"+h]=d[h];if(delete k.ctx.table,!c.documentMode){var m=new Blob([k.format(l,k.ctx)],{type:"application/vnd.ms-excel"});b.URL=b.URL||b.webkitURL,i=b.URL.createObjectURL(m),j=c.createElement("a"),j.download=f(k.settings),j.href=i,c.body.appendChild(j),j.click(),c.body.removeChild(j)}else if("undefined"!=typeof Blob){l=k.format(l,k.ctx),l=[l];var n=new Blob(l,{type:"text/html"});b.navigator.msSaveBlob(n,f(k.settings))}else txtArea1.document.open("text/html","replace"),txtArea1.document.write(k.format(l,k.ctx)),txtArea1.document.close(),txtArea1.focus(),sa=txtArea1.document.execCommand("SaveAs",!0,f(k.settings));return!0}},a.fn[j]=function(b){var c=this;return c.each(function(){a.data(c,"plugin_"+j)||a.data(c,"plugin_"+j,new e(this,b))}),c}}(jQuery,window,document);

var icoMoney = '<svg height="487pt" viewBox="-29 0 487 487.71902" width="487pt" xmlns="http://www.w3.org/2000/svg"><path d="m220.867188 266.175781c-.902344-.195312-1.828126-.230469-2.742188-.09375-9.160156-1.066406-16.070312-8.816406-16.085938-18.035156 0-4.417969-3.582031-8-8-8-4.417968 0-8 3.582031-8 8 .023438 15.394531 10.320313 28.878906 25.164063 32.953125v8c0 4.417969 3.582031 8 8 8s8-3.582031 8-8v-7.515625c17.132813-3.585937 28.777344-19.542969 26.976563-36.953125-1.804688-17.410156-16.472657-30.640625-33.976563-30.644531-10.03125 0-18.164063-8.132813-18.164063-18.164063s8.132813-18.164062 18.164063-18.164062 18.164063 8.132812 18.164063 18.164062c0 4.417969 3.582031 8 8 8 4.417968 0 8-3.582031 8-8-.023438-16.164062-11.347657-30.105468-27.164063-33.441406v-7.28125c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v7.769531c-16.507813 4.507813-27.132813 20.535157-24.859375 37.496094s16.746094 29.621094 33.859375 29.617187c9.898437 0 17.972656 7.925782 18.152344 17.820313.183593 9.894531-7.597657 18.113281-17.488281 18.472656zm0 0"/><path d="m104.195312 222.5c0 64.070312 51.9375 116.007812 116.007813 116.007812s116.007813-51.9375 116.007813-116.007812-51.9375-116.007812-116.007813-116.007812c-64.039063.070312-115.933594 51.96875-116.007813 116.007812zm116.007813-100.007812c55.234375 0 100.007813 44.773437 100.007813 100.007812s-44.773438 100.007812-100.007813 100.007812-100.007813-44.773437-100.007813-100.007812c.0625-55.207031 44.800782-99.945312 100.007813-100.007812zm0 0"/><path d="m375.648438 358.230469-62.667969 29.609375c-8.652344-16.09375-25.25-26.335938-43.515625-26.851563l-57.851563-1.589843c-9.160156-.261719-18.148437-2.582032-26.292969-6.789063l-5.886718-3.050781c-30.140625-15.710938-66.066406-15.671875-96.175782.101562l.367188-13.335937c.121094-4.417969-3.359375-8.097657-7.777344-8.21875l-63.4375-1.746094c-4.417968-.121094-8.09375 3.359375-8.214844 7.777344l-3.832031 139.210937c-.121093 4.417969 3.359375 8.097656 7.777344 8.21875l63.4375 1.746094h.21875c4.335937 0 7.882813-3.449219 8-7.78125l.183594-6.660156 16.480469-8.824219c6.46875-3.480469 14.03125-4.308594 21.097656-2.308594l98.414062 27.621094c.171875.050781.34375.089844.519532.128906 7.113281 1.488281 14.363281 2.234375 21.628906 2.230469 15.390625.007812 30.601562-3.308594 44.589844-9.730469.34375-.15625.675781-.339843.992187-.546875l142.691406-92.296875c3.554688-2.300781 4.703125-6.96875 2.621094-10.65625-10.59375-18.796875-34.089844-25.957031-53.367187-16.257812zm-359.070313 107.5625 3.390625-123.21875 47.441406 1.304687-3.390625 123.222656zm258.925781-2.09375c-17.378906 7.84375-36.789062 10.007812-55.46875 6.191406l-98.148437-27.550781c-11.046875-3.121094-22.871094-1.828125-32.976563 3.605468l-8.421875 4.511719 2.253907-81.925781c26.6875-17.75 60.914062-19.574219 89.335937-4.765625l5.886719 3.050781c10.289062 5.3125 21.636718 8.242188 33.210937 8.578125l57.855469 1.589844c16.25.46875 30.050781 12.039063 33.347656 27.960937l-86.175781-2.378906c-4.417969-.121094-8.09375 3.363282-8.21875 7.777344-.121094 4.417969 3.363281 8.097656 7.777344 8.21875l95.101562 2.617188h.222657c4.332031-.003907 7.875-3.453126 7.992187-7.78125.097656-3.476563-.160156-6.957032-.773437-10.378907l64.277343-30.371093c.0625-.027344.125-.058594.1875-.089844 9.117188-4.613282 20.140625-3.070313 27.640625 3.871094zm0 0"/><path d="m228.203125 84v-76c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v76c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/><path d="m288.203125 84v-36c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v36c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/><path d="m168.203125 84v-36c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v36c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/></svg>';



function typeValid(type, value, dataValid) {
    // image/jpeg, image/png, application/pdf
    switch (type) {
        // campo vacio
        case 'null':
            console.log(value);
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'nullImage':
            console.log(value);
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'formatImage':
            console.log(value.length);
            if (value.length > 0) {
                if ((value == 'image/jpeg') || (value == 'image/png')) return false; else return true;
                break;
            } else {
                return false;
            }
        case 'nullFComprobante':
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'formatFComprobante':
            // console.log(value);
            if ((value == 'image/jpeg') || (value == 'image/png') || (value == 'application/pdf')) {
                return false;
            } else {
                return true;
            }
            break;
        case 'nullSignature':
            if ((value == null) || (value == '')) return true; else return false;
            break;
        case 'nullImage':
            if ((value == null) || (value == '') || (value == 'empty_file')) return true; else return false;
            break;
        // letras
        case 'letters':
            return !(/^[a-zA-Z]*jQuery/.test(value));
            break;
        // codigo postal
        case 'postalCode':
            if (value.match('^[0-9]{2}\-[0-9]{3}jQuery')) return true; else return false;
            break;
        case 'phone':
            var regex = /^\+?(\d.*){3,}jQuery/;
            return regex.test(value);
            break;
        case 'number':
            console.log(value);
            if (/^\d*jQuery/.test(value) == true) {
                return true;
            } else {
                return false;
            }

            break;
        case 'email':
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+jQuery/;
            return regex.test(value);
            break;
        case 'maxChar':
            var characters = value.length;
            if ((characters >= dataValid.max)) return true; else return false;
            break;
        // debo continuar este..
        case 'maxWords':
            var words = value.split(" ");
            if ((words.length >= dataValid.max)) return true; else return false;
            break;
        case 'nullSelect':
            if ((value == null) || (value == '') || (value == '-')) return true; else return false;
            break;
        case 'nullCheckbox':
            if (jQuery(value).is(":checked")) {
                return false;
            } else {
                return true;
            }
            break;
        default:
            break;
    }

}


function typeField(indexInArray, field) {


    if ((field.field == 'textfield')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            var val = jQuery('input', this).val();

            console.log('envio', val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            // si no existen errores entonces elimina el mensaje de error
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'textarea')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            var val = jQuery('textarea', this).val();

            console.log(val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            // si no existen errores entonces elimina el mensaje de error
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'select')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            // console.log('click aqui');
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('select', this).val();

            jQuery.each(field.valid, function (index, valueValid) {



                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });

            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'checkbox')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('input[type="checkbox"]', this);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });

            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');

                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'signature')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..
            var val = jQuery('input', this).val();

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'facturaComprobante')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..

            // console.log('d', indexInArray);

            var val = jQuery('input', this).val();
            if ((val != null) && (val != '')) {

                val = jQuery('input', this)[0].files[0].type;
            }


            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
    if ((field.field == 'imagen')) {

        jQuery('.' + indexInArray + '').on('keyup click change', function (e) {
            var error = [];
            // si es seleccion multiple entonces..

            // console.log('d', indexInArray);

            var val = jQuery('input', this).val();
            if ((val != null) && (val != '')) {

                val = jQuery('input', this)[0].files[0].type;
            }

            console.log(val);

            jQuery.each(field.valid, function (index, valueValid) {

                if (typeValid(index, val, valueValid)) {
                    jQuery('.' + indexInArray + ' .validateMessage').text(valueValid.message);
                    jQuery('.' + indexInArray + ' .validateMessage').attr('error', true);
                    error.push(true);
                } else {
                    error.push(false);
                }
            });
            if (!error.includes(true)) {
                jQuery('.validateMessage', this).text('');
                jQuery('.validateMessage', this).removeAttr('error');
                // jQuery('input#um-submit-btn').removeAttr('disabled');
                // jQuery('input#um-submit-btn').removeAttr('title');
            } else {
                // jQuery('input#um-submit-btn').attr('disabled', true);
                // jQuery('input#um-submit-btn').attr('title', 'Debes completar la información de forma correcta para continuar');
            }
        });
    }
}

function configValidatorType(data) {

    // console.log('abre validator');


    jQuery.each(data, function (indexInArray, valueOfElement) {

        // console.log(valueOfElement);
        typeField(indexInArray, valueOfElement);

    });

}




jQuery(document).ready(function () {
    jQuery('<img class="logoFooter" src="/wp-content/uploads/logoForge.png" alt="ForgeSystem">').appendTo("#footer-info > div > div.devFooter > a");
});






function deleteNotif(data) {



    swal({
        icon: 'warning',
        title: "¿Deseas eliminar esta Notificación?",
        // text: 'Elimina tu vacante',
        className: 'deleteNotification',
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
                className: "formdeleteNotificationSubmit",
                closeModal: false
            }
        }
    });

    data = JSON.stringify(data);

    jQuery('.swal-modal.deleteNotification button.swal-button.swal-button--confirm.formdeleteNotificationSubmit').attr('onclick', "deleteNotification(" + data + ")");


}

function deleteNotification(data) {


    var values = [];
    values = data;


    var obj = _.extend({}, values);
    var valJson = JSON.stringify(obj);

    console.log('llamada', valJson);

    console.log(s.ajaxurl);
    // swal.stopLoading();

    // return;

    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'processDeleteNot',
            processDeleteNot: valJson
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
                title: '¡Notificación eliminada!',
                className: 'successDelete'
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


function urlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}


// span.wickedpicker__controls__control-up

// span.wickedpicker__controls__control-down


// span.wickedpicker__controls__control--separator-inner


function flechasHoraPicker() {
    console.log('flechas');
    if (jQuery('span.wickedpicker__controls__control-up i.fa.fa-angle-up').length > 0) {
    } else {
        jQuery('<i class="fa fa-angle-up" aria-hidden="true"></i>').appendTo('span.wickedpicker__controls__control-up');
    }
    if (jQuery('span.wickedpicker__controls__control-down i.fa.fa-angle-down').length > 0) {
    } else {
        jQuery('<i class="fa fa-angle-down" aria-hidden="true"></i>').appendTo('span.wickedpicker__controls__control-down');

    }

}

function setStars(element) {

    var starRating = jQuery('.fa', element);

    console.log(starRating);


    starRating.on('click', function () {
        starRating.siblings('input.rating-value').val(jQuery(this).data('rating'));

        starRating.each(function () {
            if (parseInt(starRating.siblings('input.rating-value').val()) >= parseInt(jQuery(this).data('rating'))) {
                return jQuery(this).removeClass('fa-star-o').addClass('fa-star');
            } else {
                return jQuery(this).removeClass('fa-star').addClass('fa-star-o');
            }
        });

    });

}



// ajustar enlaces
function enlaces() {
    var url = window.location.href;
    if (url.includes('localhost')) {
        var l = jQuery('[src]');
        jQuery.each(l, function (indexInArray, valueOfElement) {

            var s = jQuery(valueOfElement).attr('src');

            if(s.includes('/wp-content/')){

                if (s.includes("/tsoluciono/")) {

                    s = s.replace("/tsoluciono/", "/tsolucionamos/");
                    jQuery(valueOfElement).attr('src', s);

                }

                if(!s.includes('/tsolucionamos/')){

                    s = s.replace('/wp-content/','/tsolucionamos/wp-content/');
                    jQuery(valueOfElement).attr('src', s);

                }
            }

        });

    }
}


jQuery(document).ready(function () {
    // jQuery('[data-toggle="tooltip"]').tooltip();}
    enlaces();
});




jQuery(document).on("keydown", "form", function(event) {
    return event.key != "Enter";
});



function deletePublicOfert(serial){


    var serialOferta = serial;

    swal({
        icon: 'warning',
        title: "Eliminar publicación",
        // text: '',
        // content: formDataPresupuesto,
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
                text: "Aceptar",
                value: true,
                visible: true,
                className: "formEditOfferButton",
                closeModal: false
            }
        }
    });


    // colocado de la función de efecto click
    serialOferta = JSON.stringify(serialOferta);
    jQuery(".swal-modal.formSendPresupuesto button.swal-button.swal-button--confirm.formEditOfferButton").attr("onclick", "senddeletePublicOfert('" + serialOferta + "')");


}


function senddeletePublicOfert(serial){

    var d = serial;

    var obj = _.extend({}, d);
    var valJson = JSON.stringify(obj);

    valJson = JSON.parse(serial);

    console.log(valJson);
    // return;

    jQuery.ajax({
        url: s.ajaxurl,
        type: 'post',
        data: {
            action: 'senddeletePublicOfert',
            senddeletePublicOfert: valJson
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
                text: 'La publicación ha sido eliminada',
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


