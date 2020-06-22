<?php

function calificarCandidato(){ ?>

<div class="formCalificarEntrevistaCandidato" id="formCalificarEntrevistaCandidato" style="display:none">
 <form class="formData">

  <div class="form-group field cumpleCandidato">
    <label for="cumpleCandidato">¿Cumple con los requerimientos?</label>
    <select class="form-control" id="cumpleCandidato" name="cumpleCandidato">
      <option>SI</option>
      <option>NO</option>
    </select>
    <small class="validateMessage"></small>
  </div>

  <div class="form-group field recomendabilidad">
    <label for="recomendabilidad">Recomendabilidad del candidato</label>

    <div class="star-rating">
        <span class="fa fa-star-o" data-rating="1"></span>
        <span class="fa fa-star-o" data-rating="2"></span>
        <span class="fa fa-star-o" data-rating="3"></span>
        <span class="fa fa-star-o" data-rating="4"></span>
        <span class="fa fa-star-o" data-rating="5"></span>
        <input type="hidden" name="recomendabilidad" class="rating-value" value="0">
    </div>

    <small class="validateMessage"></small>
  </div>

  <div class="form-group field infoCandidatoEntrevista">
    <label for="infoCandidatoEntrevista">Información para la familia</label>
    <textarea class="form-control" id="infoCandidatoEntrevista" name="infoCandidatoEntrevista" rows="3"></textarea>
  </div>

</form>
</div>

<?php }
function calificarFamilia(){ ?>

<div class="formCalifEntrevistaFamiliaResolv" id="formCalifEntrevistaFamiliaResolv" style="display:none">
 <form class="formData">

  <div class="form-group field solucionPropuesta">
    <label for="solucionPropuesta">¿Se resolvió una propuesta para la familia?</label>
    <select class="form-control" id="solucionPropuesta" name="solucionPropuesta">
      <option>SI</option>
      <option>NO</option>
    </select>
    <small class="validateMessage"></small>
  </div>
  <!-- <div class="form-group field seleccionPor">
    <label for="seleccionPor">¿Selección de candidato por?</label>
    <select class="form-control" id="seleccionPor" name="seleccionPor">
        <option >Tsoluciono</option>
        <option >Familia</option>
        <option >Ambos</option>
    </select>
    <small class="validateMessage"></small>
  </div> -->
  <div class="form-group field infoEntrevistaFamilia">
    <label for="infoEntrevistaFamilia">Información para la familia</label>
    <textarea class="form-control" id="infoEntrevistaFamilia" name="infoEntrevistaFamilia" rows="3"></textarea>
  </div>

</form>
</div>

<?php }


function templateInfoInterview()
{
    if (
        isset($_POST['current']) &&
        isset($_POST['can']) &&
        isset($_POST['fam']) &&
        isset($_GET['ie']) &&
        is_user_logged_in()) {

        $can = $_POST['can'];
        $fam = $_POST['fam'];
        $currentId = $_POST['current'];
        $ie = $_GET['ie'];
        $r = array(
            'can' => $can,
            'fam' => $fam,
            'currentId' => $currentId,
            'ie' => $ie,
        );

        if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {

            infoInterview($r);

        }
        if (validateUserProfileOwner($currentId, $currentId, 'familia')) {

            infoInterview($r);

        }
    }
}

// template interno de la vista de información entrevista
function infoInterview($data)
{
    global $wp_roles;
    global $wpdb;

    ?>
        <pre>
        <?php print_r($data); ?>
        </pre>
    <?Php


    $info = dbGetAdminInfoInterviewById($data);

    // adminInfoVacanteForm();
    if($info['entrevista']['etapa'] == '0'){

        calificarCandidato();
    }
    if($info['entrevista']['etapa'] == '1'){

        calificarFamilia();
    }

    $currentId = $data['currentId'];

    if (count($info) > 0) {
        $oferta = $info['oferta'];
        $entrevista = $info['entrevista'];
        $etapas = $info['etapas'];

        // $eta = $info['entrevista']['etapa'];

        $stepData = array(
            'usuario' => $data['can'],
            'contratista' => $data['fam'],
            'step' => $entrevista['etapa']
        )



        ?>

<?php echo $info['entrevista']['etapa']; ?>
<div class="container">

    <div id="accordion">
    <?php foreach ($etapas as $e) {
            $id = $e['id'];
            $idEntrevista = $e['idEntrevista'];

            $etvr = $wpdb->prefix . 'proceso_contrato';
            $ooo = $wpdb->get_results("SELECT * from $etvr WHERE id='$idEntrevista' and candidataId", ARRAY_A);
            $et = $ooo[0]['etapa'];
            $fechaAcordado = $e['fechaCreacion'];
            $aprobado = $e['aprobado'];
            $maprobado = 'En espera';
            $nota = $e['nota'];
            $tipoEntrevista = $e['tipoEntrevista'];
            $datoEntrevista = $e['datoEntrevista'];
            $fechaPautado = $e['fechaPautado'].' - '.$e['hora'];
            $estado = $e['estado'];

            $expanded = 'true';
            $collapse = '';
            $show = 'show';

            if ($aprobado == 1) {
                $expanded = 'false';
                $collapse = 'collapsed';
                $maprobado = 'Aprobado';
                $show = '';
            }

            $dataEvaluate = array(
                'id' => $id,
                'idEntrevista' => $idEntrevista,
                'etapa' => $et,
                'ofertaId' => $ooo[0]['ofertaId']
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
      if( confirmStep2($stepData, 'can') ){


        //    info adicional del candidato
            $pd = $usuario['curriculum'][0];
            // $pd = explode('"',$pd);
            // $curriculum = $pd[3];
            // $curriculum = str_replace('/temp/', '/'.$candidataId.'/', $curriculum );
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$pd;

            $curriculum = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsoluciono'.$url : $url;



            $ft = $usuario['fotoFondoBlanco'][0];
            // $ft = explode('"', $ft);
            // $fotoBlanco = $ft[7];
            // $fotoBlanco = str_replace('/temp/', '/'.$candidataId.'/', $fotoBlanco );
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$ft;
            $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsoluciono'.$url : $url;



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

            $referenciaslaborales = $usuario['referencialab'][0];
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$referenciaslaborales;
            $referenciaslaborales = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsoluciono'.$url : $url;

            $referenciaspersonales = $usuario['referenciasper'][0];
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$referenciaspersonales;
            $referenciaspersonales = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsoluciono'.$url : $url;



            $tratamientoMedico = $usuario['tratamiento'][0];
            $habilidadesDestacadas = $usuario['habilidadDestacada'][0];
            $pasatiempo = $usuario['pasatiempo'][0];
            $salarioaspiraciones = $usuario['aspiraciones'][0];
            $experiencialaboral = $usuario['experiencialab'][0];
            $mensajeContraten = $usuario['mensajeContraten'][0];
            $horasdisponible = $usuario['cantidadHoras'][0];
            $estadoCivil = $usuario['estadoCivil'][0];
        }
            ?>

    <div class="card">

        <div id="etapa-<?php echo $et; ?>" class="collapse <?php echo $show; ?>" aria-labelledby="heading<?php echo $et ?>" data-parent="#accordion">
            <div class="card-body">
                <div class="row info d-flex justify-content-center flex-row">
                    <p class="nota col">
                        Nota: <?php echo $nota ?>
                    </p>
                    <p  class="estado col">
                        Usuario: <a class="hiper" href="<?php echo $urlCandidata ?>"><?php echo $nombreCandidata ?></a> <br>
                        Telf: <?php echo $telef; ?> <br>
                        Documento de identidad: <?php echo $DocumentoIdentidad ?> <br>
                        <?php if(confirmStep2($stepData, 'can')){ ?>
                        <!-- Estado civil: --> <?php // echo $estadoCivil;
                        }?>
                    </p>
                </div>
                <div class="row info justify-content-around">
                    <small>Tipo de entrevista: <?php echo $tipoEntrevista ?></small>
                    <small>Fecha de entrevista: <?php echo $fechaPautado ?></small>
                    <small>Dirección o forma de entrevista: <?php echo $datoEntrevista ?></small>
                </div>
 <?php
    // parte de información del candidato
            if( confirmStep2($stepData, 'can') ){
 ?>
                <div class="row info justify-content-around">
                    <h5>Información del candidato</h5>
                    <div class="container">
                        <div class="row">
                        <div class="col photoFiles">
                            <div class="foto">
                                <img src="<?php echo $fotoBlanco ?>" alt="">
                            </div>
                            <div class="files">
                                Curriculum: <br>
                                <a target="_blank" class="hiper" href="<?php echo $curriculum; ?>">Descargar</a> <br>
                                Referencias laborales: <br>
                                <a target="_blank" class="hiper" href="<?php echo $referenciaslaborales; ?>">Descargar</a> <br>
                                Referencias personales: <br>
                                <a target="_blank" class="hiper" href="<?php echo $referenciaspersonales; ?>">Descargar</a> <br>

                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col turno">
                                <p>
                                    Turno: <br>
                                    <?php
                                        foreach ($turno as $t => $v ) {
                                            if($v === end($turno)){
                                                echo $v;
                                            }else{
                                                echo $v.', ';
                                            }
                                        }
                                    ?> <br>
                                    <small>
                                        Horas disponibles: <br>
                                        <?php echo $horasdisponible ?>
                                    </small>
                                </p>
                            </div>
                            <div class="col animales">
                                <p>
                                    Animales: <br>
                                    <?php echo $animales ?>
                                </p>
                            </div>
                            <div class="col fuma">
                                <p>
                                    Fuma: <br>
                                    <?php echo $fuma ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col salarioaspiraciones">
                                <p>
                                     Aspiracion salarial: <br>
                                    <?php echo $salarioaspiraciones ?>
                                </p>
                            </div>
                            <div class="col habilidadesDestacadas">
                                <p>
                                    Habilidades destacadas: <br>
                                    <?php echo $habilidadesDestacadas; ?>
                                </p>
                            </div>
                            <div class="col pasatiempo">
                                <p>
                                    Pasatiempos: <br>
                                    <?php echo $pasatiempo; ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col tratamientoMedico">
                                <p>
                                    Sujeto a tratamiento médico: <br>
                                    <?php echo $tratamientoMedico; ?>
                                </p>
                            </div>
                            <div class="col experiencialaboral">
                                <p>
                                    Exp laboral: <br>
                                    <?php echo $experiencialaboral ?>
                                </p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <p>
                                    Me gustaria ser contratado por: <br>
                                    <?php echo $mensajeContraten; ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                 <?php
                }
               if(confirmStep2($stepData, 'fam')) {
                    // obtener los prospectos para mostrar a la famila
                    $ofid = $oferta['id'];
                    $xo = $stepData['contratista'];
                    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
                    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
                    $elegidos = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas WHERE ofertaId='$ofid' and candidataId!='$xo'", ARRAY_A);
                    $wpdb->flush();

                    ?>
                <div class="row info justify-content-around">
                    <h5>Requerimientos de la vacante</h5>
                    <div class="container requerimientos">

                        <div class="row">
                            <div class='col fechaCreacion' >
                                <p>
                                    Creado en: <br>
                                    <?php echo $oferta['fechaCreacion'] ?>
                                </p>
                            </div>
                            <div class='col fechaInicio' >
                                <p>
                                    Inicio del trabajo en: <br>
                                    <?php echo $oferta['fechaInicio'] ?>
                                </p>
                            </div>
                            <div class='col fechaFin' >
                                <p>
                                    Fin del trabajo: <br>
                                    <?php echo $oferta['fechaFin'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col nombreTrabajo' >
                                <p>
                                    Vacante: <br>
                                    <?php echo $oferta['nombreTrabajo'] ?>
                                </p>
                            </div>
                            <div class='col cargo' >
                                <p>
                                    Cargo: <br>
                                    <?php echo $oferta['cargo'] ?>
                                </p>
                            </div>
                            <div class='col direccion' >
                                <p>
                                    Dirección: <br>
                                    <?php echo $oferta['direccion'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col turno' >
                                <p>
                                    Turno: <br>
                                    <?php echo $oferta['turno'] ?>
                                </p>
                            </div>
                            <div class='col pais' >
                                <p>
                                    País: <br>
                                    <?php echo $oferta['pais'] ?>
                                </p>
                            </div>
                            <div class='col ciudad' >
                                <p>
                                    Ciudad: <br>
                                    <?php echo $oferta['ciudad'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col sueldo' >
                                <p>
                                    Sueldo: <br>
                                    <?php echo $oferta['sueldo'] ?>
                                </p>
                            </div>
                            <div class='col horario' >
                                <p>
                                    Horario: <br>
                                    <?php echo $oferta['horario'] ?>
                                </p>
                            </div>
                            <div class='col tipoServicio' >
                                <p>
                                    Tipo de servicio: <br>
                                    <?php echo $oferta['tipoServicio'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col descripcionExtra' >
                                <p>
                                    Descripción: <br>
                                    <?php echo $oferta['descripcionExtra'] ?>
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="container candidatos">
                        <h6>
                            Candidatos entrevistados
                        </h6>
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

                            // $infoResultadoCandidato = preg_replace('/\\\\\"/', "\"", $infoResultadoCandidato);


                            $infoResultadoCandidato = json_decode($infoResultadoCandidato, true);
                            $ft = $usuario['fotoFondoBlanco'][0];
                            // $ft = explode('"', $ft);
                            // $fotoBlanco = $ft[7];
                            // $fotoBlanco = str_replace('/temp/', '/'.$candidataId.'/', $fotoBlanco );
                            $url = '/wp-content/uploads/ultimatemember/' . $value['candidataId'] . '/' . $ft;
                            $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsoluciono' . $url : $url;
                            ?>

                            <div class="row candidato">
                                <div class="col-1">
                                    <div class="imagen">
                                        <img src="<?php echo $fotoBlanco ?>" alt="">
                                    </div>
                                </div>
                                <div class="col-11">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col nombre">
                                                <p>
                                                    Nombre: <br>
                                                    <a href="<?php echo $urlCandidatox; ?>"><?php echo $nameC; ?></a>
                                                </p>
                                            </div>
                                            <div class="col cumple">
                                                <p>
                                                    ¿Cumple con las espectativas?: <br>
                                                    <?php echo $infoResultadoCandidato['cumpleCandidato'] ?>
                                                </p>
                                            </div>
                                            <div class="col recomendado">
                                                <p>
                                                    Recomendado: <br>
                                                    <?php echo $infoResultadoCandidato['recomendabilidad'] ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col infoResultados">
                                                <p>
                                                    Información de la entrevista: <br>
                                                    <?php echo $infoResultadoCandidato['infoCandidatoEntrevista'] ?>
                                                </p>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                        <?php }
                        ?>

                    </div>
                </div>
               <?php }
//  botones para etapas segun administración
            ?>
                <?php if (($aprobado == 0) && validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {?>
                <div class="row base justify-content-around" style="margin-top: 15px">


                        <button onclick='sendEvaluateInterview(<?php echo $dataEvaluate ?>)' type='button' name=' id=' class='btn btn-success'>
                            <i class="fa fa-check" aria-hidden="true"></i> Calificar entrevista
                        </button>

                </div>
                <?php }?>

                <?php
//  botones para etapas segun familia
            ?>
                 <?php if (($et == 3) && ($aprobado == 0) && validateUserProfileOwner($currentId, $currentId, 'familia')) {?>
                <div class="row base justify-content-around" style="margin-top: 15px">


                        <button onclick='sendEvaluateInterview(<?php echo $dataEvaluate ?>)' type='button' name=' id=' class='btn btn-success'>
                            <i class="fa fa-check" aria-hidden="true"></i> Evaluar
                        </button>

                </div>
                <?php }?>

            </div>
        </div>
    </div>
    <?php }?>
    </div>

</div>

<?php
}
}



// si el proceso no es de candidatos entonces
function confirmStep2($data, $deaseado ){

    $current = $data['usuario'];
    $owner = $data['contratista'];
    $step = $data['step'];

    // paso 1
    if(  ($current != $owner) && ($deaseado == 'can')){
        return true;
    }
    // paso 2
    if(  ($current == $owner) && ($deaseado == 'fam') ){
        return true;
    }


    return false;
}
?>



