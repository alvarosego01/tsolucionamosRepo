<?php
// Template name: Nueva oferta laboral

?>

<?php  get_header(); ?>

<?php

    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "familia") || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") ) {
        if (isset($_POST['pg'])) {

            $data = array(
                'pg' => $_POST['pg']
            );
            // $x = get_field('contratoServicios');
            $x = get_the_ID();
            $fx = getSignUser($currentId);
            $returnDashboard = esc_url(get_permalink(get_page_by_title('Mis vacantes')));
            $existeFirmaFamilia = ( (isset($fx['firma'])) && ($fx['firma'] != null) && ($fx['firma'] != '') )? $fx['firma'] : null;


            // $x = json_encode($x);
            wp_localize_script(
                'nuevaOfertaJs',
                'datosProceso',
                array(
                    'contrato' =>  $x,
                    'dashBoardUrl' => $returnDashboard,
                    'prevFamiliaFirma' => $existeFirmaFamilia
                )
            ); ?>


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

            <div id="containerNewOffer">

                <?php stepNewVacant($data); ?>

            </div>

        <?php
        spinnerLoad();
    }
    } ?>

<?php get_footer();?>
