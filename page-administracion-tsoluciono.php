<?php
/*
Template Name: Administración Tsoluciono
 */
?>


 <?php get_header();


?>

<div class="formFirma" id="formFirma" style="display:none;">
        <form action="" method="post" class="formData">
            <div class="field form-group firmaDirectiva">
                <label for="firmaDirectiva">Firma aqui</label>
                <div id="firmaDirectiva" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
                <input type="hidden" class="form-control form-control-sm" name="jsonFirmaDirectiva" id="jsonFirmaDirectiva">
                <div class="botones">
                    <a class="botoWeb borrar">Borrar</a>
                </div>
                <small class="validateMessage"></small>
            </div>
        </form>
    </div>

<div class="" id="adminPanel">

    <div class="container global">

    <?php do_shortcode("[templateTabsAdminTsoluciono]"); ?>





    </div>
</div>


 <?php get_footer();?>

