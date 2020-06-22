<?php
// Template name: Ver factura

?>

<?php  get_header();  ?>


<div class="formRefusePay" id="formRefusePay" style="display:none">
  <form class="formData">

    <div class="col form-group field refuseNote">
      <label for="refuseNote">Mensaje para la familia</label>
      <textarea class="form-control" id="refuseNote" name="refuseNote" rows="2"></textarea>
      <small class="validateMessage"></small>
    </div>

  </form>
</div>




<?php

$home = esc_url(get_permalink(get_page_by_title('Inicio')));
if (isset($_GET['fserial']) && is_user_logged_in()) {


       $direc = '';

    $serial = $_GET['fserial'];



    $tabla = $wpdb->prefix . 'facturacion';
    $x = $wpdb->get_results("SELECT * FROM $tabla where serialFactura = '$serial'", ARRAY_A);
    $opcion = 1;
    // echo $serial;
    // SELECT * FROM `wp_facturacion_profesional` where serialFactura = 'FP-5eaabfcecae700.53437665'

    if( isset($x) && count($x) == 0 ){
    $tabla = $wpdb->prefix . 'facturacion_profesional';
    $tabla2 = $wpdb->prefix . 'public_profesional';
    $x = $wpdb->get_results("SELECT * FROM $tabla AS factura where factura.serialFactura = '$serial'", ARRAY_A);
    $opcion = 2; ?>
    <?php

    }

  


    $currentId = get_current_user_id();

if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") && (count($x) > 0)) {
    global $wpdb;

    $formato = $x[0]['formato'];
    $serial = $x[0]['serialFactura'];
    $pagado = $x[0]['pagado'];

    $factura = $x[0];

    $upload = wp_upload_dir();
    $upload_dir = $upload['baseurl'];
    $archivo  = '';
    if($opcion == 2){
        $direc = $x[0]['id'];
        $comprobanteJson = $x[0]['imagenReferencia'];
        $comprobanteJson = json_decode($comprobanteJson, true);
        $direccin = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$comprobanteJson[0]['src']: '';
        $archivo = $direccin;

    }else{
    $upload_dir = $upload_dir . '/facturas';
    $archivo = "$upload_dir/$serial.$formato";

    }


    // datos de la persona
    $familia = getInfoNameEmailUsers($x[0]['contratistaId']);
    $familia = ($opcion == 2)? getInfoNameEmailUsers($x[0]['candidatoId']) : $familia;

?>


<div id="vFactura">
<div class="container globalF">

      <?php if($opcion == 2){ ?>

            <h3 class="tituloBase" ><span class="resalte1">Información</span> de membresía  - <?php echo $x[0]['meses']; ?> <?php echo $xxx = ($x[0]['meses'] > 1)?'meses': 'mes'; ?>  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>


        <?php }else{ ?>


        <h3 class="tituloBase" ><span class="resalte1">Información</span> de factura  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>

        <?php } ?>


        <div class="container">
            <div class="row info1">
                <div class="col-2 opc">
                        <?php if( $factura['comprobante'] == 1 ){ ?>

                    <a class="viewCmp" href="<?php echo $archivo; ?>" target="_blank" >
                        <span><i class="fa fa-file-text" aria-hidden="true"></i></span>
                        <small>Ver comprobante</small>
                    </a>


                    <?php }else{ ?>
                        <a class="viewCmp" href="#" >
                        <span><i class="fa fa-times" aria-hidden="true"></i></span>
                        <small>Sin comprobante</small>
                         </a>

                    <?php } ?>

                    <?php if(($pagado != 1 && ($factura['comprobante'] == 1) && ($factura['estado'] != 'Pago rechazado')) ){ ?>

                        <?php if($opcion == 2){

                            $xll = array(
                                'serial' => $serial,
                                'type' => 'pubprof'
                            );
                            $xll = json_encode($xll);

                            ?>

                        <div class="buttonCustom">
                        <button type="button" onclick='acceptPay(<?php echo $xll ?>)' name="" id="" class="btn-sm btn btn-primary btn-block">Aprobar</button>
                        <button type="button" onclick='refusePay(<?php echo $xll ?>)' name="" id="" class="btn-sm btn btn-warning btn-block">Rechazar</button>
                        <button type="button" onclick='deletePay(<?php echo $xll ?>)' name="" id="" class="btn-sm btn btn-danger btn-block">Eliminar</button>
                        </div>


                       <?Php }else{ ?>

                        <div class="buttonCustom">
                        <button type="button" onclick='acceptPay('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-primary btn-block">Aprobar</button>
                        <button type="button" onclick="refusePay('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-warning btn-block">Rechazar</button>
                        <button type="button" onclick="deletePay('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-danger btn-block">Eliminar</button>
                        </div>

                      <?php  } ?>
                    <?php } ?>
                </div>
                <div class="col-7 details">
                    <div class="row">
                        <strong><h5>Detalles</h5></strong>
                    </div>
                    <div class="row">
                        <div class="col-4">
                        <p>
                            <strong>Nombre de usuario:</strong> <br> <?php echo $familia['nombre'] ?> <br>
                            <strong>Servicio:</strong> <br> <?php echo $factura['nombreFactura'] ?> <br>
                        </p>
                        </div>
                        <div class="col-4">
                        <p>
                            <strong>Fecha del servicio:</strong> <br> <?php echo $factura['fechaCreada']; ?> <br>
                            <?php echo $ooo = ($factura['fechaPagada'] != '')?"<strong>Fecha del comprobante:</strong> <br> ".$factura['fechaPagada']: '' ?>
                            </p>
                        </div>
                        <div class="col-4">
                        <p>
                                <?php echo $ooo = ($factura['tipoPago'])?"<strong>Tipo de pago:</strong> <br>".$factura['tipoPago']."<br>": "" ?>
                                <?php echo $ooo = ($factura['cuenta'])?"<strong>Cuenta:</strong> <br>".$factura['cuenta']."<br>": "" ?>
                                <?php echo $ooo = ($factura['estado'])?"<strong>Estado actual:</strong> <br>".$factura['estado']."<br>": "" ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>
                            <?php echo $ooo = ($factura['mensaje'] != '')? $factura['mensaje']: '' ?>
                            </p>
                        </div>
                        </div>
                </div>
                <div class="col-3 bill">
                    <div class='card container facturaCuerpo'>

                            <div class='contenido row justify-content-center'>
                                <p class='tituloContrato'><strong>Comprobante de pago</strong></p>
                            </div>
                            <div class='detalles'>

                                <?php if($opcion == 2){ ?>

                                 <div class="monto">
                                    <p style="text-align: center">Costo total:<Strong> <?php echo $x[0]['plan']; ?>  </Strong></p>
                                </div>
                               <?php }else{ ?>

                                <div class="monto">
                                    <p style="text-align: center">Costo total:<Strong> $7.000  </Strong></p>
                                </div>
                                <?php } ?>

                            </div>


                    </div>
                      <?php if(($pagado == 1 && ($factura['comprobante'] == 1) && ($factura['estado'] != 'Pago rechazado'))  && $opcion == 2 ){ ?>
                    <div class="container avisoVencimiento">

                        <?php
                            $dias = $x[0]['meses'] * 30;
                            $fechaPagada = $factura['fechaPagada'];
                            $formatFecha = explode('/', $fechaPagada);
                            $fecant = $formatFecha[2].'-'.$formatFecha[1].'-'.$formatFecha[0];
                            // Seteo fecha de comienzo
                            $fecha_inicial= $fecant;
                            // Pongo los dias que quiero sumar
                            $dias_a_sumar= $dias;
                            // Paso la fecha de comienzo a timestamp
                            $tiempo=strtotime($fecha_inicial);
                            // Paso los dias a segundos
                            $sumar=$dias_a_sumar*86400;
                            // Formatear date a gusto, aca viene dd/mm/aaaa
                            $nuevafecha = date("j/n/Y", $tiempo+$sumar);
                            // $nuevafecha = date($fechaPagada, strtotime(' +'.$dias.' days'));

                         ?>
                        <p style="margin: 25px 0; width: 100%; display: block">
                            <strong style="width: 100%; display: block; text-align: center;">
                            Vencimiento (<?php echo $nuevafecha; ?>)
                            </strong>
                        </p>
                    </div>
                        <?php } ?>
                </div>
            </div>
            <?php
            // <div class="row disclaimer">
            //     <small>
            //         Tsolucionamos, es una empresa de colocación de personal y no se hace responsable por el desempeño actual y/o futuro del candidato seleccionado. Nuestros procesos inician en la selección del personal requerido y finalizan cuando el interesado contrata al seleccionado.
            //     </small>
            // </div>


            ?>

        </div>

</div>
</div>

<?php } elseif (validateUserProfileOwner($currentId, $currentId, "familia") && (count($x) > 0)) {
    global $wpdb;

    $formato = $x[0]['formato'];
    $serial = $x[0]['serialFactura'];
    $pagado = $x[0]['pagado'];

    $factura = $x[0];

    $upload = wp_upload_dir();
    $upload_dir = $upload['baseurl'];
    $archivo  = '';
    if($opcion == 2){
        $direc = $x[0]['id'];
        $comprobanteJson = $x[0]['imagenReferencia'];
        $comprobanteJson = json_decode($comprobanteJson, true);
        $direccin = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$comprobanteJson[0]['src']: '';
        $archivo = $direccin;

    }else{
    $upload_dir = $upload_dir . '/facturas';
    $archivo = "$upload_dir/$serial.$formato";

    }
    // datos de la persona
    $familia = getInfoNameEmailUsers($x[0]['contratistaId']);
    $familia = ($opcion == 2)? getInfoNameEmailUsers($x[0]['candidatoId']) : $familia;


    // $formato = $x[0]['formato'];
    // $serial = $x[0]['serialFactura'];

    // $factura = $x[0];

    // $upload = wp_upload_dir();
    // $upload_dir = $upload['baseurl'];
    // $upload_dir = $upload_dir . '/facturas';
    // $archivo = "$upload_dir/$serial.$formato";

    // // datos de la persona
    // $familia = getInfoNameEmailUsers($x[0]['contratistaId']);

?>


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
                                    <option value="<?php echo $mmm = $aux.' - '.$aux2; ?>">
                                        <?php echo $mmm = $aux.' - '.$aux2;?>
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

<div id="vFactura">
<div class="container globalF">

        <?php if($opcion == 2){ ?>

            <h3 class="tituloBase" ><span class="resalte1">Información</span> de membresía  - <?php echo $x[0]['meses']; ?> <?php echo $xxx = ($x[0]['meses'] > 1)?'meses': 'mes'; ?>  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>


        <?php }else{ ?>


        <h3 class="tituloBase" ><span class="resalte1">Información</span> de factura  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>

        <?php } ?>


        <div class="container">
            <div class="row info1">
                <div class="col-2 opc">
                    <?php if( $factura['comprobante'] == 1 ){ ?>

                    <a class="viewCmp" href="<?php echo $archivo; ?>" target="_blank" >
                        <span><i class="fa fa-file-text" aria-hidden="true"></i></span>
                        <small>Ver comprobante</small>
                    </a>

                    <?php }else{ ?>

                        <a class="viewCmp" href="#" >
                        <span><i class="fa fa-times" aria-hidden="true"></i></span>
                        <small>Sin comprobante</small>
                         </a>

                        <div class="buttonCustom">
                        <button type="button" onclick="payService('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-primary btn-block">Pagar factura</button>
                        <button type="button" onclick="payService('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-danger btn-block">Eliminar</button>
                        </div>

                    <?php } ?>

                </div>
                <div class="col-7 details">
                    <div class="row">
                        <strong><h5>Detalles</h5></strong>
                    </div>
                    <div class="row">
                        <div class="col-4">
                        <p>
                            <strong>Nombre de usuario:</strong> <br> <?php echo $familia['nombre'] ?> <br>
                            <strong>Servicio:</strong> <br> <?php echo $factura['nombreFactura'] ?> <br>
                        </p>
                        </div>
                        <div class="col-4">
                        <p>
                            <strong>Fecha del servicio:</strong> <br> <?php echo $factura['fechaCreada']; ?> <br>
                            <?php echo $ooo = ($factura['fechaPagada'] != '')?"<strong>Fecha del comprobante:</strong> <br> ".$factura['fechaPagada']: '' ?>
                            </p>
                        </div>
                        <div class="col-4">
                        <p>
                                <?php echo $ooo = ($factura['tipoPago'])?"<strong>Tipo de pago:</strong> <br>".$factura['tipoPago']."<br>": "" ?>
                                <?php echo $ooo = ($factura['cuenta'])?"<strong>Cuenta:</strong> <br>".$factura['cuenta']."<br>": "" ?>
                                <?php echo $ooo = ($factura['estado'])?"<strong>Estado actual:</strong> <br>".$factura['estado']."<br>": "" ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>
                            <?php echo $ooo = ($factura['mensaje'] != '')? $factura['mensaje']: '' ?>
                            </p>
                        </div>
                        </div>
                </div>
                <div class="col-3 bill">
                <div class='card container facturaCuerpo'>
    <div>
        <div class='contenido row justify-content-center'>
            <p class='tituloContrato'><strong>Comprobante de pago</strong></p>
        </div>
        <div class='detalles'>

        <?php if($opcion == 2){ ?>

             <div class="monto">
                <p style="text-align: center">Costo total:<Strong> <?php echo $x[0]['plan']; ?>  </Strong></p>
            </div>
           <?php }else{ ?>
            <div class="monto">
                <p style="text-align: center">Costo total:<Strong> $7.000  </Strong></p>
            </div>
         <?php } ?>


        </div>

    </div>

</div>

     <?php if(($pagado == 1 && ($factura['comprobante'] == 1) && ($factura['estado'] != 'Pago rechazado'))  && $opcion == 2 ){ ?>
                    <div class="container avisoVencimiento">

                        <?php
                            $dias = $x[0]['meses'] * 30;
                            $fechaPagada = $factura['fechaPagada'];
                            $formatFecha = explode('/', $fechaPagada);
                            $fecant = $formatFecha[2].'-'.$formatFecha[1].'-'.$formatFecha[0];
                            // Seteo fecha de comienzo
                            $fecha_inicial= $fecant;
                            // Pongo los dias que quiero sumar
                            $dias_a_sumar= $dias;
                            // Paso la fecha de comienzo a timestamp
                            $tiempo=strtotime($fecha_inicial);
                            // Paso los dias a segundos
                            $sumar=$dias_a_sumar*86400;
                            // Formatear date a gusto, aca viene dd/mm/aaaa
                            $nuevafecha = date("j/n/Y", $tiempo+$sumar);
                            // $nuevafecha = date($fechaPagada, strtotime(' +'.$dias.' days'));

                         ?>
                        <p style="margin: 25px 0; width: 100%; display: block">
                            <strong style="width: 100%; display: block; text-align: center;">
                            Vencimiento (<?php echo $nuevafecha; ?>)
                            </strong>
                        </p>
                    </div>
                        <?php } ?>


                </div>
            </div>
                <?php
            // <div class="row disclaimer">
            //     <small>
            //         Tsolucionamos, es una empresa de colocación de personal y no se hace responsable por el desempeño actual y/o futuro del candidato seleccionado. Nuestros procesos inician en la selección del personal requerido y finalizan cuando el interesado contrata al seleccionado.
            //     </small>
            // </div>
            ?>

        </div>

</div>
</div>
<?php } elseif (validateUserProfileOwner($currentId, $currentId, "candidata") && (count($x) > 0)) {
    global $wpdb;

    $formato = $x[0]['formato'];
    $serial = $x[0]['serialFactura'];
    $pagado = $x[0]['pagado'];

    $factura = $x[0];

    $upload = wp_upload_dir();
    $upload_dir = $upload['baseurl'];
    $archivo  = '';
    if($opcion == 2){
        $direc = $x[0]['id'];
        $comprobanteJson = $x[0]['imagenReferencia'];
        $comprobanteJson = json_decode($comprobanteJson, true);
        $direccin = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$comprobanteJson[0]['src']: '';
        $archivo = $direccin;

    }else{
    $upload_dir = $upload_dir . '/facturas';
    $archivo = "$upload_dir/$serial.$formato";

    }
    // datos de la persona
    $familia = getInfoNameEmailUsers($x[0]['contratistaId']);
    $familia = ($opcion == 2)? getInfoNameEmailUsers($x[0]['candidatoId']) : $familia;


    // $formato = $x[0]['formato'];
    // $serial = $x[0]['serialFactura'];

    // $factura = $x[0];

    // $upload = wp_upload_dir();
    // $upload_dir = $upload['baseurl'];
    // $upload_dir = $upload_dir . '/facturas';
    // $archivo = "$upload_dir/$serial.$formato";

    // // datos de la persona
    // $familia = getInfoNameEmailUsers($x[0]['contratistaId']);

?>


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
                                    <option value="<?php echo $mmm = $aux.' - '.$aux2; ?>">
                                        <?php echo $mmm = $aux.' - '.$aux2;?>
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

<div id="vFactura">
<div class="container globalF">


        <?php if($opcion == 2){ ?>

            <h3 class="tituloBase" ><span class="resalte1">Información</span> de membresía  - <?php echo $x[0]['meses']; ?> <?php echo $xxx = ($x[0]['meses'] > 1)?'meses': 'mes'; ?>  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>


        <?php }else{ ?>


        <h3 class="tituloBase" ><span class="resalte1">Información</span> de factura  <?php  if( $factura['comprobante'] == 1 ){ ?> <small class="ref">Referencia: <?php echo $factura['referencia']; ?></small><?php } ?></h3>

        <?php } ?>


        <div class="container">
            <div class="row info1">
                <div class="col-2 opc">
                    <?php if( $factura['comprobante'] == 1 ){ ?>

                    <a class="viewCmp" href="<?php echo $archivo; ?>" target="_blank" >
                        <span><i class="fa fa-file-text" aria-hidden="true"></i></span>
                        <small>Ver comprobante</small>
                    </a>

                    <?php }else{ ?>

                        <a class="viewCmp" href="#" >
                        <span><i class="fa fa-times" aria-hidden="true"></i></span>
                        <small>Sin comprobante</small>
                         </a>

                        <div class="buttonCustom">
                        <button type="button" onclick="payService('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-primary btn-block">Pagar factura</button>
                        <button type="button" onclick="payService('<?php echo $serial ?>')" name="" id="" class="btn-sm btn btn-danger btn-block">Eliminar</button>
                        </div>

                    <?php } ?>

                </div>
                <div class="col-7 details">
                    <div class="row">
                        <strong><h5>Detalles</h5></strong>
                    </div>
                    <div class="row">
                        <div class="col-4">
                        <p>
                            <strong>Nombre de usuario:</strong> <br> <?php echo $familia['nombre'] ?> <br>
                            <strong>Servicio:</strong> <br> <?php echo $factura['nombreFactura'] ?> <br>
                        </p>
                        </div>
                        <div class="col-4">
                        <p>
                            <strong>Fecha del servicio:</strong> <br> <?php echo $factura['fechaCreada']; ?> <br>
                            <?php echo $ooo = ($factura['fechaPagada'] != '')?"<strong>Fecha del comprobante:</strong> <br> ".$factura['fechaPagada']: '' ?>
                            </p>
                        </div>
                        <div class="col-4">
                        <p>
                                <?php echo $ooo = ($factura['tipoPago'])?"<strong>Tipo de pago:</strong> <br>".$factura['tipoPago']."<br>": "" ?>
                                <?php echo $ooo = ($factura['cuenta'])?"<strong>Cuenta:</strong> <br>".$factura['cuenta']."<br>": "" ?>
                                <?php echo $ooo = ($factura['estado'])?"<strong>Estado actual:</strong> <br>".$factura['estado']."<br>": "" ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>
                            <?php echo $ooo = ($factura['mensaje'] != '')? $factura['mensaje']: '' ?>
                            </p>
                        </div>
                        </div>
                </div>
                <div class="col-3 bill">
                <div class='card container facturaCuerpo'>
    <div>
        <div class='contenido row justify-content-center'>
            <p class='tituloContrato'><strong>Comprobante de pago</strong></p>
        </div>
        <div class='detalles'>

        <?php if($opcion == 2){ ?>

             <div class="monto">
                <p style="text-align: center">Costo total:<Strong> <?php echo $x[0]['plan']; ?>  </Strong></p>
            </div>
           <?php }else{ ?>
            <div class="monto">
                <p style="text-align: center">Costo total:<Strong> $7.000  </Strong></p>
            </div>
         <?php } ?>


        </div>

    </div>

</div>

     <?php if(($pagado == 1 && ($factura['comprobante'] == 1) && ($factura['estado'] != 'Pago rechazado'))  && $opcion == 2 ){ ?>
                    <div class="container avisoVencimiento">

                        <?php
                            $dias = $x[0]['meses'] * 30;
                            $fechaPagada = $factura['fechaPagada'];
                            $formatFecha = explode('/', $fechaPagada);
                            $fecant = $formatFecha[2].'-'.$formatFecha[1].'-'.$formatFecha[0];
                            // Seteo fecha de comienzo
                            $fecha_inicial= $fecant;
                            // Pongo los dias que quiero sumar
                            $dias_a_sumar= $dias;
                            // Paso la fecha de comienzo a timestamp
                            $tiempo=strtotime($fecha_inicial);
                            // Paso los dias a segundos
                            $sumar=$dias_a_sumar*86400;
                            // Formatear date a gusto, aca viene dd/mm/aaaa
                            $nuevafecha = date("j/n/Y", $tiempo+$sumar);
                            // $nuevafecha = date($fechaPagada, strtotime(' +'.$dias.' days'));

                         ?>
                        <p style="margin: 25px 0; width: 100%; display: block">
                            <strong style="width: 100%; display: block; text-align: center;">
                            Vencimiento (<?php echo $nuevafecha; ?>)
                            </strong>
                        </p>
                    </div>
                        <?php } ?>


                </div>
            </div>

            <?php
            // <div class="row disclaimer">
            //     <small>
            //         Tsolucionamos, es una empresa de colocación de personal y no se hace responsable por el desempeño actual y/o futuro del candidato seleccionado. Nuestros procesos inician en la selección del personal requerido y finalizan cuando el interesado contrata al seleccionado.
            //     </small>
            // </div>
            ?>

        </div>

</div>
</div>

<?php } else {
    header('Location: '.$home);
}

}else{
    header('Location: '.$home);
}
get_footer();




?>
