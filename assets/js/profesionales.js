
var formUpdateProfesional = jQuery('#formUpdateProfesional > form').clone()[0];
jQuery('#formUpdateProfesional').remove();




jQuery(document).ready(function () {



    jQuery('.card.elementProfesional').click(function (e) {
        e.preventDefault();



        console.log(e);

        var u = jQuery('a.goBotton', e.currentTarget).attr('href');


        window.location.href = u;

    });





});