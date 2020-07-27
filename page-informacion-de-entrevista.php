<?php
/*
Template Name: Información de entrevista
 */
?>

 <?php get_header();

    if( isset($_POST['dataInterview']) && $_GET['ie'] && is_user_logged_in() ){

        $data = $_POST['dataInterview'];
        $data = stripslashes($data);
        $data = json_decode($data, true);
        $data['ie'] = $_GET['ie'];

        $currentId = $data['currentId'];

        $info = dbGetAdminInfoInterviewById($data);

        // se setean los formularios que se usan
    if($info['entrevista']['etapa'] == '0'){

        calificarCandidato();
    }
    if($info['entrevista']['etapa'] == '1'){

        calificarFamilia();
    }

// si existe info entonces
if (count($info) > 0) {

    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono"))
    {
        $volverAtras = esc_url(get_permalink(get_page_by_title('Administración Tsoluciono')));
        $volverAtras .= '#tab2';
    }
    if (validateUserProfileOwner($currentId, $currentId, "familia")){
        $volverAtras = esc_url(get_permalink(get_page_by_title('Mis vacantes')));
        $volverAtras .= '#tab2';
    }
    $oferta = $info['oferta'];
    $entrevista = $info['entrevista'];
    $etapas = $info['etapas'][0];

    // $eta = $info['entrevista']['etapa'];

    $stepData = array(
        'usuario' => $data['can'],
        'contratista' => $data['fam'],
        'step' => $entrevista['etapa']
    );

    $e = $etapas;

    // datos iniciales
    $id = $e['id'];
    $idEntrevista = $e['idEntrevista'];

    $etvr = $wpdb->prefix . 'proceso_contrato';
    $ooo = $wpdb->get_results("SELECT * from $etvr WHERE id='$idEntrevista' and candidataId", ARRAY_A);

    $et = $ooo[0]['etapa'];
    $fechaAcordado = $e['fechaCreacion'];
    $aprobado = $e['aprobado'];

    $aprobado = ($e['resultadosEntrevista'] != '' && $e['resultadosPruebasPsico'] != '')? 1 : $aprobado;

    $maprobado = 'En espera';
    $nota = $e['nota'];
    $tipoEntrevista = $e['tipoEntrevista'];
    $datoEntrevista = $e['datoEntrevista'];
    $fechaPautado = $e['fechaPautado'].' - '.$e['hora'];
    $estado = $e['estado'];

    $fechaPautadoTrans = $e['fechaPautado'];
    $fechaPautadoTrans = tranformMeses($fechaPautadoTrans);
    $hora = $e['hora'];

    $confirmaFecha = stripslashes($e['confirmaFecha']);
    $confirmaFecha = json_decode($confirmaFecha, true);

    $expanded = 'true';
    $collapse = '';
    $show = 'show';

    if ($aprobado == 1) {
        $expanded = 'false';
        $collapse = 'collapsed';
        $maprobado = 'Aprobado';
        $show = '';
    }
    $ofertaId = $ooo[0]['ofertaId'];
    $toxo = $wpdb->prefix . 'ofertalaboral';
    $oxo = $wpdb->get_results("SELECT * from $toxo WHERE id='$ofertaId'", ARRAY_A);
    $serialOferta = $oxo[0]['serialOferta'];

    $dataEvaluate = array(
        'id' => $id,
        'idEntrevista' => $idEntrevista,
        'etapa' => $et,
        'ofertaId' => $ooo[0]['ofertaId'],
        'candId' => $data['can'],
        'serial' => $serialOferta
    );

    $dataEvaluate = json_encode($dataEvaluate, JSON_UNESCAPED_UNICODE);

    $candidataId = $entrevista['candidataId'];

    $u = get_userdata($candidataId);
    $role = array_shift($u->roles);
    $rolCandidata = $wp_roles->roles[$role]['name'];

    $usuario = get_user_meta($candidataId);

    $nombreCandidata = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];
    $nombreCandidata .= ' ' . '(' . $rolCandidata . ')';

    $urlCandidata = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

    $telef = $usuario['mobile_number'][0];
    $DocumentoIdentidad = $usuario['DocumentoIdentidad'][0];
    // / datos iniciales

    // datos de la oferta
    $fechaInicio = $oferta['fechaInicio'];
    $fechaFin = $oferta['fechaFin'];
    $nombreTrabajo = $oferta['nombreTrabajo'];
    // $cargo = $oferta['cargo'];
    $direccion = $oferta['direccion'];
    $pais = $oferta['pais'];
    $ciudad = $oferta['ciudad'];
    $horario = $oferta['horario'];
    $tipoServicio = $oferta['tipoServicio'];
    $descripcionExtra = $oferta['descripcionExtra'];
    $imagenes = $oferta['imagenes'];
    $imagenes = stripslashes($imagenes);
    $imagenes = json_decode($imagenes, true);

    // en caso de que sea por un candidato
    if( confirmStep2($stepData, 'can') ){

        //    info adicional del candidato


            $ft = $usuario['fotoFondoBlanco'][0];

            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$ft;
            $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;

            $turno = $usuario['Turno'][0];
            $turno = explode('"',$turno);

            $iii = 0;
            foreach ($turno as $t ) {
                if(($t != 'Mañana') && ($t != 'Tarde') && ($t != 'Noche')){
                    unset($turno[$iii]);
                }
                $iii++;
            }

            $animales = $usuario['Animales'][0];
            $animales = explode('"', $animales);
            $animales = $animales[1];

            $fuma = $usuario['Fuma'][0];
            $fuma = explode('"', $fuma);
            $fuma = $fuma[1];

            $curriculum = ($usuario['curriculum'][0] != '')? $usuario['curriculum'][0] : null;
            if($curriculum != null){
                $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$curriculum;
                $curriculum = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
            }

            $referenciaslaborales = ($usuario['referencialab'][0] != '')? $usuario['referencialab'][0]: null;
            if($referenciaslaborales != null){
                $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$referenciaslaborales;
                $referenciaslaborales = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
            }

            $referenciaspersonales = ($usuario['referenciasper'][0] != '')? $usuario['referenciasper'][0]: null;
            if($referenciaspersonales != null){
                $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$referenciaspersonales;
                $referenciaspersonales = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
            }

            $carnetSalud = ($usuario['carnetSalud'][0] != '')? $usuario['carnetSalud'][0]: null;
            if($carnetSalud != null){

                $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$carnetSalud;
                $carnetSalud = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
            }

            $tratamientoMedico = $usuario['tratamiento'][0];
            $habilidadesDestacadas = $usuario['habilidadDestacada'][0];
            $pasatiempo = $usuario['pasatiempo'][0];
            $salarioaspiraciones = $usuario['aspiraciones'][0];
            $experiencialaboral = $usuario['experiencialab'][0];
            $mensajeContraten = $usuario['mensajeContraten'][0];
            $horasdisponible = $usuario['cantidadHoras'][0];
            $estadoCivil = $usuario['estadoCivil'][0];

            $asistencia = $confirmaFecha['candidato'];

        if($aprobado == 1){
            $resultadosEntrevista = $e['resultadosEntrevista'];
            $resultadosEntrevista = json_decode($resultadosEntrevista,true);
            $cumple = $resultadosEntrevista['cumpleCandidato'];
            $recomendabilidad = $resultadosEntrevista['recomendabilidad'];
            $infoCandidatoEntrevista = $resultadosEntrevista['infoCandidatoEntrevista'];
        }

    }
    if( confirmStep2($stepData, 'fam') ){

        // foto dep erfil
        $fotoBlanco = esc_url( get_avatar_url( $r['postulanteId'] ) );

        // datos
        $asistencia = $confirmaFecha['familia'];



        $ofid = $oferta['id'];
        $xo = $stepData['contratista'];
        $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
        $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
        $elegidos = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas WHERE ofertaId='$ofid' and candidataId!='$xo'", ARRAY_A);
        $wpdb->flush();


    }
    // ----------
?>

<div id="infoInterview">

    <div class="container global">
        <div class="row intr">

        <div class="col-7 interviewInfo">
                <div class="container cont">
                    <div class="row titlePrincipal">
                        <?php if($tipoEntrevista == 'Pruebas Psico laborales'){ ?>

                            <h5><span class="resalte1">Pruebas </span> Psico laborales <small class="date">Actualizado en: <?php echo $fechaAcordado ?></small></h5>

                        <?php }else{ ?>

                            <h5><span class="resalte1">Entrevista </span> de usuario <small class="date">Actualizado en: <?php echo $fechaAcordado ?></small></h5>
                       <?php  } ?>
                    </div>
                    <div class="row interview">
                        <h6>
                            <?PHP if($tipoEntrevista == 'Pruebas Psico laborales'){
                                echo 'Fecha y hora de las pruebas';
                            }else{

                               echo  'Fecha y hora de entrevista';
                            } ?>
                        </h6>
                        <div class="pautado">
                            <?php if($aprobado == 0){ ?>

                                <div class="dat <?php echo $x = ($fechaPautadoTrans['dia'] == 'Realizada')?'realizada': '' ?>">
                                   <div class="mes">
                                        <?php echo $fechaPautadoTrans['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $fechaPautadoTrans['dia']; ?>
                                    </div>
                                    <?php if($fechaPautadoTrans['dia'] != 'Realizada'){ ?>

                                        <div class="anio">
                                                <?php echo $fechaPautadoTrans['anio']; ?>
                                                <h6>
                                                <?php echo $hora; ?>
                                                </h6>
                                                </div>
                                           <?php } ?>

                                </div>
                             <?php } ?>
                            <?php if($aprobado == 1){ ?>

                                <div class="dat realizada">
                                   <div class="mes">
                                        <?php echo $fechaPautadoTrans['mes']; ?>
                                    </div>
                                    <div class="dia">
                                        <?php echo $fechaPautadoTrans['dia']; ?>
                                    </div>

                                </div>
                             <?php } ?>
                        </div>
                    </div>


                    <div class="row interview2">

                    <?Php if($tipoEntrevista == 'Pruebas Psico laborales'){ ?>

<div class="info">
<h6>Nota</h6>
<p>
  <?php echo $nota; ?>
</p>
</div>

<div class="container info2">
<div class="row">

  <div class="col-6">
      <h6>Dirección de las pruebas</h6>
      <p>
          <?php echo $datoEntrevista; ?>
      </p>
  </div>
</div>
</div>

<div class="psicoTestsFields">
    <?php if($fechaPautadoTrans['dia'] != 'Realizada' && $asistencia == 'Confirmada'){ ?>

        <form action="" class="formData">
        <h6>
            Carga las Pruebas Psico Laborales
        </h6>

    <div class="row">
            <div class="form-group col field prueba1">
                    <label for="prueba1">Prueba 1</label>
                    <input type="file" class="form-control" id="prueba1" name="prueba1" accept="image/jpeg, image/png">
      <small class="validateMessage"></small>
    </div>

    <div class="form-group col field prueba2">
      <label for="prueba2">Prueba 2</label>
      <input type="file" class="form-control" id="prueba2" name="prueba2" accept="image/jpeg, image/png">
      <small class="validateMessage"></small>
    </div>


</div>


</form>
   <?php }elseif ($fechaPautadoTrans['dia'] == 'Realizada' && $asistencia == 'Confirmada') {



       $pruebasP = $e['pruebasPsico'];
        $pruebasP = json_decode($pruebasP, true);

        $resultadosPruebas = $e['resultadosPruebasPsico'];
        $resultadosPruebas = json_decode($resultadosPruebas, true);

        $fechaPruebasRealizadas = $pruebasP['fechaRealizado'];
        $horaPruebasRealizadas = $pruebasP['horaRealizado'];

       ?>
       <div class="pruebas">
        <h6>
            Pruebas Psico Laborales <small>(<?php echo $fechaPruebasRealizadas.' - '.$horaPruebasRealizadas  ?>)</small>
        </h6>
            <div class="row img">
                <?php  $i = 1; foreach ($pruebasP['imagenes'] as $key => $value) { ?>
                    <a target="_blank" href="<?php echo $value['src'] ?>"><i class="fa fa-picture-o" aria-hidden="true"></i><span>Prueba <?php echo $i ?></span></a>
                <?php $i++; } ?>
            </div>

            <?php if(isset($resultadosPruebas) && ( count($resultadosPruebas) > 0 )){ ?>


            <div class="row nota">

            <?php if($resultadosPruebas['califica'] == 'si'){ ?>

                <p class="cumple">
                  <?php echo $resultadosPruebas['motivo']; ?>
                </p>
                <?php if($resultadosPruebas['nota'] != '' && $resultadosPruebas['nota'] != null){ ?>
                    <p>
                    <?php echo 'Nota adicional: '. $resultadosPruebas['nota'] ?>
                    </p>
                <?php } ?>
            <?php } ?>
            <?php if($resultadosPruebas['califica'] == 'no'){ ?>

                <p class="noCumple">
                  <?php echo $resultadosPruebas['motivo']; ?>
                </p>
                <?php if($resultadosPruebas['nota'] != '' && $resultadosPruebas['nota'] != null){ ?>
                    <p>
                    <?php echo 'Nota adicional: '. $resultadosPruebas['nota'] ?>
                    </p>
                <?php } ?>
            <?php } ?>

            </div>

            <?php } ?>


       </div>
   <?php } ?>
</div>

                   <?php }else{
                    $pruebasP = $e['pruebasPsico'];
                    $pruebasP = json_decode($pruebasP, true);

                    $resultadosPruebas = $e['resultadosPruebasPsico'];
                    $resultadosPruebas = json_decode($resultadosPruebas, true);


        $fechaPruebasRealizadas = $pruebasP['fechaRealizado'];
        $horaPruebasRealizadas = $pruebasP['horaRealizado'];

                       ?>
                      <div class="info">
                          <h6>Nota de entrevista</h6>
                          <p>
                            <?php echo $nota; ?>
                          </p>
                      </div>

                      <div class="container info2">
                        <div class="row">
                            <div class="col-6">
                                <h6>Tipo de entrevista</h6>
                                <p>
                                    <?php echo $tipoEntrevista; ?>
                                </p>
                            </div>
                            <div class="col-6">
                                <h6>Dirección o forma de entrevista</h6>
                                <p>
                                    <?php echo $datoEntrevista; ?>
                                </p>
                            </div>
                        </div>
                      </div>

                      <?php if(validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono') && !confirmStep2($stepData, 'fam')){ ?>



                        <div class="psicoTestsFields">
       <div class="pruebas">
        <h6>
            Pruebas Psico Laborales <small>(<?php echo $fechaPruebasRealizadas.' - '.$horaPruebasRealizadas  ?>)</small>
        </h6>
            <div class="row img">
                <?php  $i = 1; foreach ($pruebasP['imagenes'] as $key => $value) { ?>
                    <a target="_blank" href="<?php echo $value['src'] ?>"><i class="fa fa-picture-o" aria-hidden="true"></i><span>Prueba <?php echo $i ?></span></a>
                <?php $i++; } ?>
            </div>

            <?php if(isset($resultadosPruebas) && ( count($resultadosPruebas) > 0 )){ ?>


<div class="row nota">

<?php if($resultadosPruebas['califica'] == 'si'){ ?>

    <p class="cumple">
      <?php echo $resultadosPruebas['motivo']; ?>
    </p>
    <?php if($resultadosPruebas['nota'] != '' && $resultadosPruebas['nota'] != null){ ?>
        <p>
        <?php echo 'Nota adicional: '. $resultadosPruebas['nota'] ?>
        </p>
    <?php } ?>
<?php } ?>
<?php if($resultadosPruebas['califica'] == 'no'){ ?>

    <p class="noCumple">
      <?php echo $resultadosPruebas['motivo']; ?>
    </p>
    <?php if($resultadosPruebas['nota'] != '' && $resultadosPruebas['nota'] != null){ ?>
        <p>
        <?php echo 'Nota adicional: '. $resultadosPruebas['nota'] ?>
        </p>
    <?php } ?>
<?php } ?>

</div>

<?php } ?>


       </div>
                        </div>

                   <?php  } ?>

                    <?php } ?>

                    </div>

                    <?php  if( confirmStep2($stepData, 'fam') ){ ?>

                    <div class="row interviewsData">

                    <div class="container candidatos">
                        <h6>
                            Candidatos entrevistados
                        </h6>



                        <div class="candidatoEntrevista">

                        <?php foreach ($elegidos as $key => $value) {
                            $ide = $value['id'];
                            $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
                            $xxx = $wpdb->get_results("SELECT * FROM $tablaprocesoEntrevistasEtapas where idEntrevista='$ide' ", ARRAY_A);
                            $wpdb->flush();
                            $cid = $value['candidataId'];
                            $usuario = get_user_meta($value['candidataId']);

                            $u = get_userdata($cid);
                            $role = array_shift($u->roles);
                            $rolCandidata = $wp_roles->roles[$role]['name'];

                            // $usuario = get_user_meta($candidataId);

                            // nombre
                            $nameC = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

                            $nameC .= ' ' . '(' . $rolCandidata . ')';
                            $urlCandidatox = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

                            $infoResultadoCandidato = $xxx[0]['resultadosEntrevista'];
                            $infoResultadoCandidato = json_decode($infoResultadoCandidato, true);

                            $cumple = $infoResultadoCandidato['cumpleCandidato'];
                            $recomendabilidad = $infoResultadoCandidato['recomendabilidad'];
                            $infoCandidatoEntrevista = $infoResultadoCandidato['infoCandidatoEntrevista'];

                            $ft = $usuario['fotoFondoBlanco'][0];
                            // $ft = explode('"', $ft);
                            // $fotoBlanco = $ft[7];
                            // $fotoBlanco = str_replace('/temp/', '/'.$candidataId.'/', $fotoBlanco );
                            $url = '/wp-content/uploads/ultimatemember/' . $value['candidataId'] . '/' . $ft;
                            $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;

                            $entrevistaCandidatoUrl = esc_url(get_permalink(get_page_by_title('Información de entrevista')));
                            $entrevistaCandidatoUrl .= '?ie='.$ide;

                            $x = array(
                            'currentId' => get_current_user_id(),
                            'can' => $value['candidataId'],
                            'fam' => $value['contratistaId']
                            );
                            $x = json_encode($x); ?>

                                    <form target="_blank" method="post" action="<?php echo $entrevistaCandidatoUrl; ?>">
                                    <input type="hidden" name="dataInterview" value='<?php echo $x; ?>'>
                                    <button type="submit">

                                        <div class="img">
                                            <img src="<?php echo $fotoBlanco ?>" alt="">
                                        </div>

                                        <div class="nombre">
                                            <h6><?php echo $nameC; ?></h6>
                                        </div>

                                            <div class="star-rating">
                                            <?php
                                            // echo $recomendabilidad;
                                            for ($i=1; $i <= 5 ; $i++) {
                                                // echo $i;
                                                if($i <= $recomendabilidad){ ?>
                                                    <span class="fa fa-star" data-rating="1"></span>
                                                <?php }else{ ?>
                                                    <span class="fa fa-star-o" data-rating="1"></span>
                                                <?php }

                                            } ?>
                                            </div>
                                    </button>
                                    </form>

                        <?php }
                        ?>
                        </div>

                    </div>

                    </div>


                    <?php } ?>


                    <?php if($aprobado == 1 && isset($resultadosEntrevista)){

                        $fechaPruebasRealizadasCandidato = $resultadosEntrevista['fechaRealizado'];
                        $horaPruebasRealizadasCandidato = $resultadosEntrevista['horaRealizado'];

                        ?>
                        <div class="row intervirew4">
                            <div class="container infoResultado">
                            <h6>
                                Resumen de entrevista <small>(<?php echo $fechaPruebasRealizadasCandidato.' - '.$horaPruebasRealizadasCandidato  ?>)</small>

                            <?php if(validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) { ?>
                                    <!-- $dataEvaluate -->
                                <button
                                onclick='changeResumenEntrevista(<?php echo $dataEvaluate ?>)'
                                style="padding: 1px 5px!important"
                                name="" id="" class="btn btn-success" type="button" role="button">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar
                                </button>

                            <?php } ?>

                            </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <h6>¿Cumple expectativas?</h6>
                                        <p>
                                            <?php echo $cumple; ?>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <h6>Recomendado</h6>
                                        <div class="star-rating">
                                        <?php
                                        // echo $recomendabilidad;
                                        for ($i=1; $i <= 5 ; $i++) {
                                            // echo $i;
                                            if($i <= $recomendabilidad){ ?>
                                                <span class="fa fa-star" data-rating="1"></span>
                                            <?php }else{ ?>
                                                <span class="fa fa-star-o" data-rating="1"></span>
                                            <?php }

                                        } ?>


                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="resultadosEntrevista">
                                        <h6>Información sobre la entrevista</h6>
                                        <?php
                                        $x = (isset($infoCandidatoEntrevista) && $infoCandidatoEntrevista != '')? $infoCandidatoEntrevista: 'Sin información adicional';
                                        // echo utf8_encode($x);
                                        echo $x = formatUTF8($x);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                        <div class="row interview3 buttonCustom justify-content-around">
                                        <button class="col-4 btn btn-light btn-block" type ="button">
                            <a href="<?php echo $volverAtras ?>" <i class="fa fa-undo" aria-hidden="true"></i> Atras
                            </a>
                                        </button>

                                        <?php if(validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") && ($asistencia != 'Confirmada')){ ?>

                                                <?php $x = array(
                                                    'idEntrevista' => $idEntrevista,
                                                );

                                                $x = json_encode($x);

                                        ?>
                                        <button onclick='forzarAsistencia(<?php echo $x ?>)' class='col-4 btn btn-success btn-block'>Aprobar asistencia</button>'
                                    <?php } ?>

                            <?php if (($aprobado == 1) && validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono') && $tipoEntrevista == 'Pruebas Psico laborales') {
                                global $wpdb;
                                $tabla = $wpdb->prefix . 'configuracionesadmin';
                                  $infoSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
                                  $infoSettings = $infoSettings[0];
                                  if( isset($infoSettings['teamDatos']) ){
                                    $teamDatos = $infoSettings['teamDatos'];
                                    $teamDatos = json_decode($teamDatos, true);
                                  }

                                ?>


                                <div class="formSetInterviewStep1" id="formSetInterviewStep1" style="display:none">
  <form class="formData">
    <div class="row">
      <div class="form-group field col tipoEntrevista">
        <label for="tipoEntrevista">Tipo de entrevista</label>
        <select class="form-control" id="tipoEntrevista" name="tipoEntrevista">

        <option>
            Entrevista con Recursos Humanos
        </option>
        </select>
        <small class="validateMessage"></small>
      </div>

      <div class="form-group field col datoEntrevista">
        <label for="datoEntrevista">Dirección o forma de entrevista</label>


        <select class="form-control" id="datoEntrevista" name="datoEntrevista">
        <option>
        <?Php echo $x = (isset($teamDatos['Otros']))? $teamDatos['Otros']['Direccion']: null ?>
        </option>

        </select>

        <small class="validateMessage"></small>
      </div>
    </div>

    <div class="row">
    	<div class="form-group field col date">
    	  <label for="date">Fecha de entrevista</label>
    	  <input class="form-control" name="date" type="text" id="date">
    	  <small class="validateMessage"></small>
    	</div>

    	<div class="form-group field col hora">
    	  <label for="hora">Hora</label>
    	  <input class="form-control" name="hora" type="text" id="hora">
    	  <small class="validateMessage"></small>
    	</div>
    </div>

    <div class="form-group field notaEntrevista">
      <label for="notaEntrevista">Nota</label>
      <textarea class="form-control" id="notaEntrevista" name="notaEntrevista" rows="3"></textarea>
    </div>

  </form>
</div>

                                <button style="margin-top: 0!important;" onclick="setInterviewCalificate('<?php echo $idEntrevista; ?>')" type="button" class=" col-4 btn btn-success btn-block">Programar entrevista</button>
                             <?php } ?>
                            <?php if (($aprobado == 0) && validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {?>

                                <?php if($tipoEntrevista == 'Pruebas Psico laborales' && $asistencia == 'Confirmada'){

                                    if($fechaPautadoTrans['dia'] != 'Realizada'){ ?>

                                        <button onclick='sendEvaluatePsicoTest(<?php echo $dataEvaluate ?>)' type='button' name=' id=' class='col-4 btn btn-success'>
                                        <i class="fa fa-check" aria-hidden="true"></i> Cargar pruebas
                                        </button>

                                   <?php }else{

                                        $tabla = $wpdb->prefix . 'configuracionesadmin';
                                        $infoSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
                                        $infoSettings = $infoSettings[0];
                                        if( isset($infoSettings['teamDatos']) ){
                                          $teamDatos = $infoSettings['teamDatos'];
                                          $teamDatos = json_decode($teamDatos, true);
                                        }


                                       ?>

                                    <div class="formDetailsCalifTests" id="formDetailsCalifTests" style="display:none;">

                                        <form action="" method="post" class="formData">


                                        <div class="row">
                                        <div class="form-group col field motivos">
                                          <label for="motivos">Calificación</label>
                                          <select class="form-control" name="motivos" id="motivos">
                                                <option value="si">Cumple los requisitos Psico laborales</option>
                                                <option value="no">No cumple con los requisitos Psico laborales</option>
                                          </select>
                                          <small class="validateMessage"></small>
                                        </div>

                                        </div>
                                        <div class="row">
                                        <div class="form-group col field date">
                                          <label for="date">Detalles</label>
                                          <textarea class="form-control" id="notaEntrevista" name="notaEntrevista" rows="3" placeholder="Puedes escribir una reseña sobre este resultado"></textarea>
                                          <small class="validateMessage"></small>
                                        </div>
                                        </div>
                                    </form>



                                    </div>

                                    <button onclick='sendCalifEvaluateTests(<?php echo $dataEvaluate ?>)' type='button' name=' id=' class='col-4 btn btn-success'>
                                        <i class="fa fa-check" aria-hidden="true"></i> Calificar pruebas
                                    </button>

                                   <?php  } ?>


                       <?php  }elseif ($asistencia == 'Confirmada') {
                            ?>


                        <button onclick='sendEvaluateInterview(<?php echo $dataEvaluate ?>)' type='button' name=' id=' class='col-4 btn btn-success'>
                                <i class="fa fa-check" aria-hidden="true"></i> Calificar entrevista
                            </button>

                            <?php } }?>
                    </div>

                </div>
            </div>

            <div class="col-5 userInfo">

            <?php  if( confirmStep2($stepData, 'can') ){ ?>

                <div class="container">
                    <div class="row archivos">
                        <div class="col photoFiles">
                            <div class="foto">
                                <?php echo $xx = ($asistencia == 'Confirmada')? '<span class="etiqueta confirmada">Asistencia: Confirmada</span>': '<span class="etiqueta pendiente">Asistencia: Pendiente</span>'; ?>
                                <img src="<?php echo $fotoBlanco ?>" alt="">




                            </div>
                            <div class="files">

                                <a class="name" href="<?php echo $urlCandidata ?>">
                                    <?php echo $nombreCandidata ?>
                                </a>
                            <?php if(isset($curriculum) && $curriculum != null){ ?>
                                <a target="_blank" class="hiper" href="<?php echo $curriculum; ?>"><i class="fa fa-briefcase" aria-hidden="true"></i>Curriculum</a>
                            <?php } ?>
                            <?php if(isset($referenciaslaborales) && $referenciaslaborales != null){

                                ?>
                                <a target="_blank" class="hiper" href="<?php echo $referenciaslaborales; ?>"> <i class="fa fa-handshake-o" aria-hidden="true"></i> Referencias laborales</a>
                            <?php } ?>
                            <?php if(isset($referenciaspersonales) && $referenciaspersonales != null){ ?>
                                <a target="_blank" class="hiper" href="<?php echo $referenciaspersonales; ?>"><i class="fa fa-address-card" aria-hidden="true"></i> Referencias personales</a>
                            <?php } ?>
                            <?php if(isset($carnetSalud) && $carnetSalud != null){ ?>
                                <a target="_blank" class="hiper" href="<?php echo $carnetSalud; ?>"><i class="fa fa-heartbeat" aria-hidden="true"></i> Carnet de salud</a>
                            <?php } ?>

                            </div>
                        </div>
                    </div>
                    <div class="container dataUser">
                        <div class="row">
                        <div class="col animales">
                                    <h6>Doc. Identidad</h6>
                                    <p>
                                        <?php echo $DocumentoIdentidad ?>
                                    </p>
                                </div>
                                <div class="col animales">
                                    <h6>Nro. Teléfono</h6>
                                    <p>
                                        <?php echo $telef ?>
                                    </p>
                                </div>
                                <div class="col animales">
                                    <h6>Cuida animales</h6>
                                    <p>

                                        <?php echo $animales ?>
                                    </p>
                                </div>
                            </div>

                        <div class="row">
                                <div class="col fuma">
                                    <h6>Fuma</h6>
                                    <p>

                                        <?php echo $fuma ?>
                                    </p>
                                </div>

                            <div class="col turno">
                                    <h6>Turno</h6>
                                    <p>
                                        <?php
                                            foreach ($turno as $t => $v ) {
                                                if($v === end($turno)){
                                                    echo $v;
                                                }else{
                                                    echo $v.', ';
                                                }
                                            }
                                            ?>
                                     </p>
                            </div>
                                        <div class="col horasDisp">
                                            <h6>Hrs disponibles</h6>
                                            <p>
                                                <?php echo $horasdisponible ?>
                                            </p>
                                        </div>
                            </div>
                            <div class="row">
                                <div class="col salarioaspiraciones">
                                    <h6>Aspiracion salarial</h6>
                                    <p>

                                        <?php echo $salarioaspiraciones ?>
                                    </p>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col habilidadesDestacadas">
                                        <h6>Habilidades destacadas</h6>
                                        <p>

                                            <?php echo $habilidadesDestacadas; ?>
                                        </p>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col pasatiempo">
                                    <h6>Pasatiempos</h6>
                                    <p>
                                        <?php echo $pasatiempo; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col tratamientoMedico">
                                    <h6>Sujeto a tratamiento médico</h6>
                                    <p>
                                        <?php echo $tratamientoMedico; ?>
                                    </p>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col experiencialaboral">
                                    <h6>Experiencía laboral</h6>
                                    <p>
                                        <?php echo $experiencialaboral ?>
                                    </p>
                                </div>

                                </div>
                            <div class="row">
                                <div class="col">
                                    <h6>Me gustaria ser contratado por</h6>
                                    <p>

                                        <?php echo $mensajeContraten; ?>
                                    </p>
                                </div>
                            </div>
                    </div>

                </div>

            <?php } ?>
            <?php  if( confirmStep2($stepData, 'fam') ){

                $foto = esc_url( get_avatar_url( $candidataId ) );

                ?>


                <div class="container">
                    <div class="row archivos">
                        <div class="col photoFiles">
                            <div class="foto">
                                <?php echo $xx = ($asistencia == 'Confirmada')? '<span class="etiqueta confirmada">Asistencia: Confirmada</span>': '<span class="etiqueta pendiente">Asistencia: Pendiente</span>'; ?>
                                <img src="<?php echo $foto ?>" alt="">
                            </div>
                            <div class="files">

                                <a class="name" href="<?php echo $urlCandidata ?>">
                                    <?php echo $nombreCandidata ?>
                                </a>

                                <div class="container dataFam">
                                    <div class="row">
                                        <div class="col-6">
                                        <h6>Teléfono</h6>
                                        <p>
                                            <?php echo $telef; ?>
                                        </p>
                                        </div>
                                        <div class="col-6">
                                        <h6>Documento de identidad</h6>
                                        <p>
                                            <?php echo $DocumentoIdentidad; ?>
                                        </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="container dataUser">

                    <h6 style="text-align: center;">Información de oferta laboral</h6>

                    <div class="row">
                            <div class='col nombreTrabajo' >
                                <h6>Vacante
                                </h6>
                                <p>
                                    <?php echo $oferta['nombreTrabajo'] ?>
                                </p>
                            </div>
                            <div class='col fechaCreacion' >
                                <h6>Creado en
                                </h6>
                                <p>
                                    <?php echo $oferta['fechaCreacion'] ?>
                                </p>
                            </div>

                    </div>
                    <div class="row">

                            <div class='col fechaInicio' >
                                <h6>Inicio del trabajo en
                                </h6>
                                <p>
                                    <?php echo $oferta['fechaInicio'] ?>
                                </p>
                            </div>
                            <div class='col fechaFin' >
                                <h6>Fin del trabajo
                                </h6>
                                <p>
                                    <?php echo $oferta['fechaFin'] ?>
                                </p>
                            </div>
                    </div>

                        <div class="row">

                            <div class='col tipoServicio' >
                                <h6>Tipo de servicio
                                </h6>
                                <p>
                                    <?php echo $oferta['tipoServicio'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">

                            <div class='col pais' >
                                <h6>País
                                </h6>
                                <p>
                                    <?php echo $oferta['pais'] ?>
                                </p>
                            </div>
                            <div class='col ciudad' >
                                <h6>Ciudad
                                </h6>
                                <p>
                                    <?php echo $oferta['ciudad'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                        <div class='col sueldo' >
                                <h6>Sueldo
                                </h6>
                                <p>
                                    <?php echo $oferta['sueldo'] ?>
                                </p>
                            </div>
                            <div class='col horario' >
                                <h6>Horario
                                </h6>
                                <p>
                                    <?php echo $oferta['horario'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col descripcionExtra' >
                                <h6>Descripción
                                </h6>
                                <p>
                                    <?php echo $oferta['descripcionExtra'] ?>
                                </p>
                            </div>
                        </div>


                   </div>

            </div>
            <?php } ?>


        </div>
    </div>


</div>
</div>

    <?php
    }
    // if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {

    //     infoInterview($data);

    // }
    // if (validateUserProfileOwner($currentId, $currentId, 'familia')) {

    //     infoInterview($data);

    // }
 ?>

 <?php

}
    get_footer();


    // function confirmStep2($data, $deaseado ){

    //     $current = $data['usuario'];
    //     $owner = $data['contratista'];
    //     $step = $data['step'];

    //     // paso 1
    //     if(  ($current != $owner) && ($deaseado == 'can')){
    //         return true;
    //     }
    //     // paso 2
    //     if(  ($current == $owner) && ($deaseado == 'fam') ){
    //         return true;
    //     }


    //     return false;
    // }


    ?>

