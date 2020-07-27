<?php
// Template name: Información de vacante

 get_header();
 $paginaHome = esc_url(get_permalink(get_page_by_title('Inicio')));

//  return;
if( (isset($_GET['serial'])) && ($_GET['serial'] != '')){

    global $wpdb;
    global $wp_role;
    $serialOferta = $_GET['serial'];
    $tabla = $wpdb->prefix . 'ofertalaboral';
    $infoOferta = $wpdb->get_results("SELECT * FROM $tabla where serialOferta='$serialOferta' ", ARRAY_A);

    if(isset($infoOferta) && (count($infoOferta) > 0) ){

    if ($infoOferta[0]['publico'] == 0) {
        # code...
        header('Location: '.$paginaHome);
        die();
    }
    $infoOferta = $infoOferta[0];
    $imagenes = $infoOferta['imagenes'];
    $imagenes = json_decode($imagenes, true);

    // datos
    $contratistaId = $infoOferta['contratistaId'];
    $estado = $infoOferta['estado'];
    $fechaCreacion = $infoOferta['fechaCreacion'];
    $gestion = $infoOferta['gestion'];
    $fechaInicio = $infoOferta['fechaInicio'];
    $fechaFin = $infoOferta['fechaFin'];
    $nombreTrabajo = $infoOferta['nombreTrabajo'];
    $cargo = $infoOferta['cargo'];
    $nombreFamilia = $infoOferta['nombreFamilia'];
    $direccion = $infoOferta['direccion'];
    $turno = $infoOferta['turno'];
    $pais = $infoOferta['pais'];
    $departamento = $infoOferta['departamento'];
    $ciudad = $infoOferta['ciudad'];
    $sueldo = $infoOferta['sueldo'];
    $horario = $infoOferta['horario'];
    $tipoServicio = $infoOferta['tipoServicio'];
    $descripcionExtra = $infoOferta['descripcionExtra'];
    $firmaCandidata = $infoOferta['firmaCandidata'];
    $serialOferta = $infoOferta['serialOferta'];
    $contratoTerminosPublicacion = $infoOferta['contratoTerminosPublicacion'];
    $aceptaTerminosContrato = $infoOferta['aceptaTerminosContrato'];
    $aceptaTerminosPublicacion = $infoOferta['aceptaTerminosPublicacion'];
    $serialFactura = $infoOferta['serialFactura'];
    $publico = $infoOferta['publico'];
    $solCambio = $infoOferta['solCambio'];
    $tipoPublic = $infoOferta['tipoPublic'];

    $sueldo = moneyConversion('uy', $sueldo);

    // resaltar primera palabra
    $titulo = $infoOferta['nombreTrabajo'];
    $titulo = explode(' ', $titulo);

    $t = "<span class='resalte1'>$titulo[0]</span>";
    foreach ($titulo as $key => $value) {
        if($key != 0){
            $t.=' '.$value;
        }
    }
    $titulo = $t;

    $inicio = tranformMeses($fechaInicio);
    $fin = tranformMeses($fechaFin);

    // otras publicaciones
    $otrasPublicaciones = $wpdb->get_results("SELECT * FROM $tabla where serialOferta!='$serialOferta' ", ARRAY_A);
    ?>

    <?php if ($tipoPublic == 'Promoción') { ?>


     <div class="formPostulate2" id="formPostulate2" style="display:none;">

        <form action="" method="post" class="formData">

                  <div class="field col form-group mensaje">
                <label for="titulo">Deja un mensaje al equipo de Tsolucionamos</label>
                <textarea class="form-control" name="mensaje" id="" cols="10" rows="2"></textarea>
                <div class="validateMessage">Este mensaje es opcional, puedes omitirlo</div>
            </div>

            <div class="field col form-group enterado">
                <label for="enterado">¿Cómo te has enterado de este anuncio?</label>
                <select class="form-control" name="enterado">
                    <option>
                        Un amigo
                    </option>
                    <option>
                        Redes sociales
                    </option>
                    <option>
                        Pagina de la empresa
                    </option>
                    <option>
                        Otra forma
                    </option>
                </select>
                <small class="validateMessage"></small>
            </div>

        </form>

    </div>


    <?php } ?>



<div id="offerInfo" class="offerInfo">

                <?php if($tipoPublic == 'Promoción'){ ?>
        <div class="info1">
            <div class="container">

                <div class="row">
                    <div class="col-6 imgPrincipal">
                    <div class="img">
                       <img src="<?php echo $imagenes['principal']['src']; ?>" alt="">
                    </div>
                    </div>
                    <div class="col-6 details">
                        <div class="titulo">
                            <h6>
                                Publicado <?php echo $fechaCreacion ?>
                            </h6>
                            <h1>
                                <?php echo $titulo; ?>
                            </h1>
                        </div>
                        <div class="desc">
                            <!-- <h5>Detalles</h5> -->
                            <?php if($tipoPublic != 'Promoción'){ ?>

                            <div class="row">

                                <div class="col-5 descp">
                                <p>
                                   <strong>País:</strong> <br>
                                   <?php echo $pais ?> <br>
                                   <strong>Departamento:</strong> <br>
                                   <?php echo $departamento ?> <br>
                                   <strong>Ciudad:</strong> <br>
                                   <?php echo $ciudad ?> <br>
                                </p>
                                </div>

                                   <div class="col-5 descp">
                                    <p>

                                       <strong>Tipo de servicio:</strong> <br>
                                       <?php echo $tipoServicio ?> <br>
                                       <strong>Horario:</strong> <br>
                                       <?php echo $horario ?>
                                    </p>
                                </div>

                            </div>
                            <div class="row salario">
                                <div class="col-8">

                                    <h6>Salario propuesto: <?php echo $sueldo ?></h6>
                                </div>
                            </div>

                            <?php } ?>

                            <?php if($tipoPublic == 'Promoción'){ ?>
                            <div class="row">

                                <div class="col-5 descp">
                                    <p>

                                        <strong>Servicio de interés:</strong> <br>
                                        <?php echo $tipoServicio ?> <br>

                                        <strong>País:</strong> <br>
                                        <?php echo $pais ?> <br>

                                    </p>
                                </div>

                            </div>

                            <?php } ?>

                        </div>
                        <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom">
                                    <!-- <button onclick="preSendFamConfirmDate('<?php echo $idEntrevista; ?>')" class="btn btn-success btn-block">¡Quiero postularme!</button> -->
                                    <?php
                                    if($tipoPublic != 'Promoción'){ ?>
                                    <?php echo getDataVacante('botonPostularse'); ?>
                                    <?php } ?>
                                    <?php if($tipoPublic == 'Promoción'){ ?>
                                     <?php echo getDataVacante('botonPostularseAnuncio'); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>




                        <?php if( is_user_logged_in() &&
                            (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ) )
                            ){
                             $listadoAdmin = esc_url(get_permalink(get_page_by_title('Administración Tsoluciono')));
                            ?>
                            <div class="opc">
                            <div class="row">

                                  <div class="col-8 buttonCustom">

                                  <a href="/anuncios-tsolucionamos" class='btn btn-success btn-block'>Volver al listado de publicaciones</a>

                                </div>

                                <div class="col-8 buttonCustom" style="margin: 5px 0">

                                  <a href="#" onclick="deletePublicOfert('<?php echo $serialOferta ?>')" class='btn btn-danger btn-block'>Eliminar publicación</a>

                                </div>

                                </div>
                            </div>
                            <?php } ?>

                    </div>
                </div>

                <?php }else{

                     $icono = '';

  if( $tipoServicio == 'Cocinero'){
    $icono = iconosPublic('cocinero');
  }
  if( $tipoServicio == 'Personal trainer'){
    $icono = iconosPublic('trainer');
  }
  if( $tipoServicio == 'Jardinero'){
    $icono = iconosPublic('jardinero');
  }
  if( $tipoServicio == 'Baby sister'){
    $icono = iconosPublic('babysitter');
  }
  if( $tipoServicio == 'Personal Doméstico con Retiro'){
    $icono = iconosPublic('domestico');
  }
  if( $tipoServicio == 'Personal Doméstico con Cama'){
    $icono = iconosPublic('domestico');
  }
  if( $tipoServicio == 'Doméstica Especial para Mudanzas'){
    $icono = iconosPublic('mudanzas');
  }
  if( $tipoServicio == 'Cuidado del Adulto Mayor'){
    $icono = iconosPublic('enfermera');
  }
  if( $tipoServicio == 'Multi Función'){
    $icono = iconosPublic('multi');
  }

                    ?>
             <div class="info1 infoPublic">
                    <div class="container">
                    <div class="row">
                        <div class="iconoPublic">
                            <?php echo $icono; ?>
                        </div>
                    </div>
                    <div class="row details">
                             <div class="titulo">
                            <h6>
                                Publicado <?php echo $fechaCreacion ?>
                            </h6>
                            <h1>
                                <?php echo $titulo; ?>
                            </h1>
                        </div>
                        <div class="desc">
                            <!-- <h5>Detalles</h5> -->
                            <?php if($tipoPublic != 'Promoción'){ ?>

                            <div class="row infoCenter">

                                   <div class="descp">
                                <p>
                                   <strong>País:</strong> <br>
                                   <?php echo $pais ?> <br>
                                   <strong>Departamento:</strong> <br>
                                   <?php echo $departamento ?> <br>
                                   <strong>Ciudad:</strong> <br>
                                   <?php echo $ciudad ?> <br>
                                </p>
                                </div>


                                <div class="descp">
                                    <p>

                                       <strong>Tipo de servicio:</strong> <br>
                                       <?php echo $tipoServicio ?> <br>
                                       <strong>Horario:</strong> <br>
                                       <?php echo $horario ?>
                                    </p>
                                </div>


                            </div>
                            <div class="row salario">
                                <div class="money">

                                    <h6>Salario propuesto: <?php echo $sueldo ?></h6>
                                </div>
                            </div>

                            <?php } ?>

                            <?php if($tipoPublic == 'Promoción'){ ?>
                            <div class="row">

                                <div class="col-5 descp">
                                    <p>

                                        <strong>Servicio de interés:</strong> <br>
                                        <?php echo $tipoServicio ?> <br>

                                        <strong>País:</strong> <br>
                                        <?php echo $pais ?> <br>

                                    </p>
                                </div>

                            </div>

                            <?php } ?>

                        </div>
                        <div class="opc">
                            <div class="row">

                                <div class="buttonCustom">
                                    <!-- <button onclick="preSendFamConfirmDate('<?php echo $idEntrevista; ?>')" class="btn btn-success btn-block">¡Quiero postularme!</button> -->
                                    <?php
                                    if($tipoPublic != 'Promoción'){ ?>
                                    <?php echo getDataVacante('botonPostularse'); ?>
                                    <?php } ?>
                                    <?php if($tipoPublic == 'Promoción'){ ?>
                                     <?php echo getDataVacante('botonPostularseAnuncio'); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>



                    </div>

                <?php } ?>
            </div>
        </div>

        <div class="info2">
            <div class="container">
                <div class="row rowDesc">

                    <div class="descriptions">
                        <?php if($tipoPublic != 'Promoción'){ ?>
                        <p>
                            <strong>Descripción</strong> <br>
                            <?php echo $descripcionExtra; ?>
                        </p>
                        <?php } ?>
                        <?php if($tipoPublic == 'Promoción'){ ?>
                        <p>
                            <strong>Descripción del anuncio</strong> <br>
                            <?php echo $descripcionExtra; ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
                <div class="row rowDates">
                    <div class="container">
                        <?php if($tipoPublic != 'Promoción'){ ?>
                        <h5>Días del trabajo</h5>
                        <div class="row justify-content-center">
                            <div class="col-4 desde">
                               <div class="dat">
                                   <div class="mes">
                                        <?php echo $inicio['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $inicio['dia']; ?>
                                    </div>
                                    <div class="anio">
                                        <?php echo $inicio['anio']; ?>
                                   </div>
                                </div>

                            </div>
                            <div class="col-4 hasta">
                            <div class="dat">
                                   <div class="mes">
                                        <?php echo $fin['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $fin['dia']; ?>
                                    </div>
                                    <div class="anio">
                                        <?php echo $fin['anio']; ?>
                                   </div>
                                </div>

                            </div>
                        </div>


                        <?php } ?>
                        <?php if($tipoPublic == 'Promoción'){





                            if(isset($fin) && $fin['mes'] != '' && $fin['dia'] != '' && $fin['anio'] != '' ){ ?>

                    <h5>Dias del anuncio</h5>
                        <div class="row justify-content-center">
                            <div class="col-4 desde">
                               <div class="dat">
                                   <div class="mes">
                                        <?php echo $inicio['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $inicio['dia']; ?>
                                    </div>
                                    <div class="anio">
                                        <?php echo $inicio['anio']; ?>
                                   </div>
                                </div>

                            </div>
                            <div class="col-4 hasta">
                            <div class="dat">
                                   <div class="mes">
                                        <?php echo $fin['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $fin['dia']; ?>
                                    </div>
                                    <div class="anio">
                                        <?php echo $fin['anio']; ?>
                                   </div>
                                </div>

                            </div>
                        </div>

                            <?php }else{ ?>
                            <h5 style="margin-bottom: 0;">¡Este anuncio estará en vigencia desde el <span class="resalte1"><?php echo $inicio['dia']. ' de '. $inicio['mes']. ' ' . $inicio['anio']; ?></span>hasta nuevo aviso!</h5>
                        <?php } } ?>
                    </div>
                </div>
        </div>
        </div>

        <div class="info3">
            <div class="row extraButton">

                <div class="container">
                    <div class="row title">

                        <h5>¿Tienes preguntas? <span class="resalte1">Tsolucionamos</span> tus dudas</h5>
                        <a href="/contacto" class="btn btn-success">CONTACTO</a>
                    </div>

                </div>
            </div>
            <div class="row others">

            </div>
        </div>

        <?php if(count($otrasPublicaciones) > 0 && ($tipoPublic != 'Promoción')){
            $otras = do_shortcode('[getAllVacantes allnot="'.$serialOferta.'"]');


            ?>

            <div style="display: none" class="info4">
                <div class="container">
                    <h5>Otras publicaciones</h5>
                        </div>
                     <div class="publicacion">
                        <?php echo $otras; ?>
                     </div>
            </div>
        <?php } ?>

        <div class="info5">

            <div class="container">
                <div id="state" class="state">
                <?php echo postulateList(); ?>
                </div>

            </div>
        </div>
        <div class="info6">

            <div class="container">

                <a href="<?php echo get_home_url(); ?>" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true"></i> Menú principal</a>

            </div>
        </div>



</div>


<?php
    }else{
        header('Location: '.$paginaHome);
    }
}else{
    header('Location: '.$paginaHome);
}
get_footer();?>
