<?php

/*
Template Name: Nuevo profesional */

?>

<?php  get_header();

$currentId = get_current_user_id();
if (validateUserProfileOwner($currentId, $currentId, "profesional")) {

  if( !isset($_POST['pg']) && isset($_GET['pg']) ){
      $_POST['pg'] = $_GET['pg'];
  }

    if (isset($_POST['pg'])) {?>



<div class="formFirma" id="formFirma" style="display:none;">
    <form action="" method="post" class="formData">
        <div class="field form-group firmaFamilia">
            <label for="firmaFamilia">Firma aqui</label>
            <div id="firmaFamilia" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
            <input type="hidden" class="form-control form-control-sm" name="jsonfirmaFamilia" id="jsonfirmaFamilia">
            <div class="botones">
                <a class="botoWeb borrar">Borrar</a>
            </div>
            <small class="validateMessage"></small>
        </div>
        <div class="field form-group guardarFirma">
                <input type="checkbox" name="guardarFirma" /> ¿Deseas utilizar esta firma en documentos futuros?
                <small class="validateMessage"></small>
            </div>
    </form>
</div>




<div class="formPayIt" id="formPayIt" style="display:none;">
    <form action="" method="post" class="formData" enctype="multipart/form-data">

    <?php
       $tabla = $wpdb->prefix . 'configuracionesadmin';
       $infoSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
       $infoSettings = $infoSettings[0];
       if( isset($infoSettings['teamDatos']) ){
         $teamDatos = $infoSettings['teamDatos'];
         $teamDatos = json_decode($teamDatos, true);
         $bancos = $teamDatos['Bancos'];
       }


     ?>
            <div class="row">

              <div class="form-group field col membresia">
                  <label for="membresia">Membresía por:</label>

                    <select class="form-control form-control-sm" name="membresia">

                      <option value="1">Un mes <strong>($150)</strong></option>
                      <option value="2">Dos meses <strong>($300)</strong> </option>
                      <option value="3">Tres meses <strong>($450)</strong> </option>
                      <option value="4">Cuatro meses <strong>($600)</strong> </option>
                      <option value="5">Cinco meses <strong>($750)</strong> </option>
                      <option value="6">Seis meses <strong>($900)</strong> </option>
                      <option value="7">Siete meses <strong>($1050)</strong> </option>
                      <option value="8">Ocho meses <strong>($1200)</strong> </option>
                      <option value="9">Nueve meses <strong>($1350)</strong> </option>
                      <option value="10">¡Un año por Diez meses! <strong>($1500)</strong> </option>

                    </select>

              </div>

            </div>

            <div class="row">
            <div class="form-group field col tipo">
                  <label for="tipo">Cuenta bancaría</label>
                    <select class="form-control form-control-sm" name="tipo">
                        <?php
                          $xl = 0;
                          $aux = '';
                        foreach ($bancos as $key => $value) {
                            $xl = ($xl > 1)? 0: $xl;
                             if($xl == 1){
                                $aux2 = $value; ?>
                                <option value="<?php echo $x = $aux.' - '.$aux2; ?>">
                                    <?php echo $x = $aux.' - '.$aux2;?>
                                </option>
                            <?php }else{
                                $aux = $value;
                            }
                            $xl++;
                         } ?>

                    </select>

                  <small class="validateMessage"></small>
                </div>
            </div>


            <div class="row">
                <div class="form-group field col referencia">
                  <label for="referencia">Referencia</label>
                  <input class="form-control form-control-sm" name="referencia" type="text" id="referencia">
                  <small>Código de referencia o número de transacción relacionada</small>
                  <small class="validateMessage"></small>
                </div>
            </div>

            <div class="row">

                <div class="field form-group col mensajeOpcional">
                    <label for="mensajeOpcional">Mensaje opcional</label>
                    <textarea class="form-control form-control-sm" name="mensajeOpcional" id="" cols="20" rows="2"></textarea>
                    <small>Puedes dejarnos un mensaje adicional</small>
                    <small class="validateMessage"></small>
                </div>

            </div>

            <div class="row">
                <div class="field form-group col comprobante">
                    <label for="comprobante">Comprobante</label>
                    <input type="file" class="form-control" id="comprobante" name="comprobante"  accept="image/jpeg, image/png, application/pdf" />
                    <small >Puede ser una imagen de captura, documento o factura de transacción.</small>
                    <small class="validateMessage"></small>
                </div>

            </div>
    </form>
</div>



<div id="nuevoProfesional">

    <?php newProfesional(); ?>

</div>

    <?php }
         } ?>

<?php get_footer(); ?>