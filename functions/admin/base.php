<?php


function dbGetVacantsGestion1($data)
{

    global $wpdb;
    // $id = $data;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaOfertaPostulantes = $wpdb->prefix . 'ofertapostulantes';

    $tablaAdminVacantes = $wpdb->prefix . 'admin_vacantes_familia';

    $tipo = $data['panel'];
    $page = $data['page'];

    if ($tipo == 'postulantes') {
        $seriales = $wpdb->get_results("SELECT * from $tablaOferta where tipoPublic IS NULL OR tipoPublic != 'Promoción' ", ARRAY_A);
        $wpdb->flush();
        $datos = array();

        foreach ($seriales as $r) {

            $a = $r['serialOferta'];
            $b = $r['id'];
            $c = $r['contratistaId'];

            $info = $wpdb->get_results("SELECT postulanteId, ofertaId, mensaje from $tablaOfertaPostulantes WHERE ofertaId='$b'", ARRAY_A);
            $wpdb->flush();

            if (count($info) > 0) {
                $p = array();

                foreach ($info as $rr) {
                    array_push($p, $rr);
                }

                $d = array(
                    'oferta' => $r,
                    'postulantes' => $p,
                    'contratistaId' => $c,
                );
                array_push($datos, $d);
            }
        }

        return $datos;
    }
    if ($tipo == 'porVerificar') {
        $seriales = $wpdb->get_results("SELECT * from $tablaOferta AS oferta, $tablaAdminVacantes AS adminVacantes where adminVacantes.aprobado = 0 and  oferta.id = adminVacantes.idOferta", ARRAY_A);
        $wpdb->flush();
        $datos = array();

        foreach ($seriales as $r) {
            $a = $r['serialOferta'];
            $b = $r['id'];
            $c = $r['contratistaId'];

            $info = $wpdb->get_results("SELECT postulanteId, ofertaId, mensaje from $tablaOfertaPostulantes WHERE ofertaId='$b'", ARRAY_A);
            $wpdb->flush();

            if (count($info) > 0) {
                $p = array();

                foreach ($info as $rr) {
                    array_push($p, $rr);
                }

                $d = array(
                    'oferta' => $r,
                    'postulantes' => $p,
                    'contratistaId' => $c,
                );
                array_push($datos, $d);
            } else {
                $d = array(
                    'oferta' => $r,
                    'postulantes' => 0,
                    'contratistaId' => $c,
                );
                array_push($datos, $d);

            }

        }
        return $datos;

    }
    if ($tipo = 'todos') {

        $seriales = $wpdb->get_results("SELECT * from $tablaOferta where tipoPublic IS NULL OR tipoPublic != 'Promoción' ", ARRAY_A);
        $wpdb->flush();
        $datos = array();

        foreach ($seriales as $r) {
            $a = $r['serialOferta'];
            $b = $r['id'];
            $c = $r['contratistaId'];

            $info = $wpdb->get_results("SELECT postulanteId, ofertaId, mensaje from $tablaOfertaPostulantes WHERE ofertaId='$b'", ARRAY_A);
            $wpdb->flush();

            if (count($info) > 0) {
                $p = array();

                foreach ($info as $rr) {
                    array_push($p, $rr);
                }

                $d = array(
                    'oferta' => $r,
                    'postulantes' => $p,
                    'contratistaId' => $c,
                );
                array_push($datos, $d);
            } else {
                $d = array(
                    'oferta' => $r,
                    'postulantes' => 0,
                    'contratistaId' => $c,
                );
                array_push($datos, $d);

            }

        }

        return $datos;

    }
}

function dbGetOfferLaboralInfoBySerial($data)
{

    global $wpdb;

    $tabla = $wpdb->prefix . 'ofertalaboral';

    $serial = $data;

    $info = $wpdb->get_results("SELECT * FROM  $tabla where serialOferta = '$serial'", ARRAY_A);

    return $info;

}



function dbSendBeginInterviewProcess($data)
{
    global $wpdb;

    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';

    $candidatos = $data['candidatos'];
    $serial = $data['serial'];
    $contratistaId = $data['contratistaId'];
    $ofertaId = $data['ofertaId'];

    $estado = 'En espera de Pruebas Psico laborales';
    $creadoEn = date('d/m/Y');

    $i = 0;

    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where serialOferta = '$serial'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serial;
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];

    $candidatoInfo = getInfoNameEmailUsers($candidatoId);

    // $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $urlReprogEntrevista = esc_url(get_permalink(get_page_by_title('Mis vacantes'))).'?pg=tab2&oid='.$ofertaId;



    foreach ($candidatos as $r) {
       $idC = $r['idpostulant'];

        $x = $wpdb->get_results("SELECT count(*) from $procesoContrato where contratistaId = $contratistaId and candidataId=$idC and ofertaId='$ofertaId'", ARRAY_A);

        // print_r($x);
        if($x[0]['count(*)'] == 0){

        $confirmaFecha = json_encode($r['info']['confirmaFecha']);

        $id = uniqid() . uniqid();

        $datos1 = array(
            'id' => sanitize_text_field($id),
            'contratistaId' => sanitize_text_field($contratistaId),
            'candidataId' => sanitize_text_field($r['idpostulant']),
            'ofertaId' => sanitize_text_field($ofertaId),
            'etapa' => sanitize_text_field('0'),
            'estado' => 'En etapa de selección',
        );
        $datos2 = array(
            'idEntrevista' => sanitize_text_field($id),
            'fechaCreacion' => sanitize_text_field($creadoEn),
            'fechaPautado' => sanitize_text_field($r['info']['date']),
            'estado' => $estado,
            'hora' => sanitize_text_field($r['info']['hora']),
            'tipoEntrevista' => sanitize_text_field($r['info']['tipoEntrevista']),
            'aprobado' => 0,
            'datoEntrevista' => sanitize_text_field($r['info']['datoEntrevista']),
            'nota' => sanitize_text_field($r['info']['notaEntrevista']),
            'resultadosEntrevista' => '',
            'confirmaFecha' => sanitize_text_field($confirmaFecha)
        );
        $formato1 = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        );
        $formato2 = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        );
        $tabla = $wpdb->prefix . 'proceso_contrato';
        $wpdb->insert($tabla, $datos1, $formato1);
        $wpdb->flush();
        $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
        $wpdb->insert($tabla, $datos2, $formato2);
        $wpdb->flush();

        // para mensaje

        $candidatoInfo = getInfoNameEmailUsers($r['idpostulant']);

        $msj = 'Te hemos programado la realización de Pruebas Psico laborales con nosotros por la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$r['info']['date'].'</strong> y hora: <strong>'.$r['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.  <br><br> Confirma o solicita una reprogramación de fecha y hora <a href="'.$urlReprogEntrevista.'">AQUÍ</a>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Tienes Pruebas Psico laborales pendientes con administración por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newEntrevista',
            'email' => $candidatoInfo['email'],
            'usuarioMuestra' => $candidatoInfo['id']
        );
        saveNotification($mensaje);

        $msj = 'Se han programado Pruebas Psico laborales con <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>.'.' Para la vacante  <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Se realizará en la fecha: <strong>'.$r['info']['date'].'</strong> y hora: <strong>'.$r['info']['hora'].'</strong>.';

        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Hemos programado Pruebas Psico Laborales con '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newEntrevista',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);


        // print_r($r);

        echo postulateList();
    }


    }
    die();

}

function dbDeleteAdminSelectPostulant($data)
{

    global $wpdb;

    $candidataId = $data['idpostulant'];
    $ofertaId = $data['ofertaId'];

    // print_r($data);

    // return;
    $tabla = $wpdb->prefix . 'proceso_contrato';

    try {
        $wpdb->query("DELETE FROM $tabla WHERE candidataId = '$candidataId' AND ofertaId = '$ofertaId'");
    } catch (Exception $e) {

    }

}

// obtener  postulantes seleccionados de una publicacion
function dbGetPostulantSelectedByOffeId($data = '')
{
    global $wpdb;
    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $id = $data;
    $info = $wpdb->get_results("SELECT * from $tablaSelectInterviews as entrevista where entrevista.ofertaId = '$id'", ARRAY_A);


    // return;

    if (($data != '') && ($data != null) && ($info != null)) {

        $vv = array();
        foreach ($info as $key => $value) {

            $idEntrevista = $value['id'];
            $etapas = $wpdb->get_results("SELECT * from $tablaEtapas as etapas  where etapas.idEntrevista = '$idEntrevista'", ARRAY_A);
            $wpdb->flush();
            array_push($vv, $etapas[0]);

        }

        $d = array(
                'entrevista' => $info[0],
                'etapas' => $vv,
        );



        $v = $d;

        return $v;

    } else {

        return null;

    }

}

// retorna las ofertas laborales con postulantes seleccionados para listarlos
function dbAdminGetVacantSelectedForInterview()
{
    global $wpdb;
    // $id = $data;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $seriales = $wpdb->get_results("SELECT * from $tablaOferta", ARRAY_A);
    $wpdb->flush();
    $datos = array();

    foreach ($seriales as $r) {

        $a = $r['serialOferta'];
        $b = $r['id'];
        $c = $r['contratistaId'];

        $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas WHERE ofertaId='$b'", ARRAY_A);
        $wpdb->flush();

        if (count($info) > 0) {
            $p = array();
            foreach ($info as $rr) {

                $idEntrevista = $rr['id'];

                $x = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas WHERE idEntrevista='$idEntrevista'", ARRAY_A);
                $wpdb->flush();

                // $t = array(
                    // $x,
                // );

                array_push($p, $x[0]);
            }

            $d = array(
                'oferta' => $r,
                'entrevista' => $info,
                'seleccionados' => $p,
                'contratistaId' => $c,
            );

            array_push($datos, $d);

        }

    }?>

<?php return $datos;

}

function dbGetAdminInfoInterviewById($data)
{

    global $wpdb;
// $id = $data;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $entrevistaId = $data['ie'];
    $fam = $data['fam'];
    $can = $data['can'];

    $entrevista = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id='$entrevistaId' and contratistaId = $fam and candidataId = $can", ARRAY_A);

    // $etapa = $entrevista[0]['etapa'];
    $ofertaId = $entrevista[0]['ofertaId'];

    $etapas = $wpdb->get_results("SELECT * from $tablaEtapas where idEntrevista='$entrevistaId'", ARRAY_A);

    $oferta = $wpdb->get_results("SELECT * from $tablaOferta where id='$ofertaId' and contratistaId = $fam", ARRAY_A);

    $datos = array(
        'oferta' => $oferta[0],
        'entrevista' => $entrevista[0],
        'etapas' => $etapas,
    );

    return $datos;
}

// COMPLETADO
// eliminar proceso de entrevistas desde el panel de administración

// RE USO DE FUNCION
function dbAdminDeleteProcessInterview($data)
{
    global $wpdb;

    $idEntrevista = $data;

    $tabla = $wpdb->prefix . 'proceso_contrato';

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';


    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$idEntrevista'", ARRAY_A);

    $candidatoId = $procesoContrato[0]['candidataId'];
    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];

    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];

    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    try {
        $wpdb->query("DELETE FROM $tabla WHERE id = '$idEntrevista'");

        if($candidatoId == $contratistaId){
            // es familia
               // es candidato
        $msj = 'Se ha eliminado tu proceso de entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Se ha eliminado tu proceso de entrevista por la vacante: '.$nombreVacante,
          'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'deleteEntrevista',
            'email' => $familiaInfo['email'],
            'usuarioMuestra' => $familiaInfo['id']
        );
        saveNotification($mensaje);
        // parte admin
        $msj = 'Se ha eliminado el proceso de la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong> publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Se ha eliminado el proceso de: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>. Para entrevista por la vacante: '.$nombreVacante,
            'estado' => 0,
            // 'fecha' => ,
            'tipo' => 'deleteEntrevista',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);
        }else{
        // es candidato
        $msj = 'Se ha eliminado tu proceso de entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Se ha eliminado tu proceso de entrevista por la vacante: '.$nombreVacante,
          'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'deleteEntrevista',
            'email' => $candidatoInfo['email'],
            'usuarioMuestra' => $candidatoInfo['id']
        );
        saveNotification($mensaje);
        // parte admin
        $msj = 'Se ha eliminado el proceso de la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong> publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Se ha eliminado el proceso de: <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>. Para entrevista por la vacante: '.$nombreVacante,
            'estado' => 0,
            // 'fecha' => ,
            'tipo' => 'deleteEntrevista',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);
    }

    } catch (Exception $e) {

    }

}



function dbAdminAddNewInterview($data)
{
    global $wpdb;
    $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $entrevista = $data['entrevista'];
    $info = $data['info'];

    // print_r($data);
    // return;

    // entrevista
    $idEntrevista = sanitize_text_field($entrevista['idEntrevista']);
    $estadoActualizado = sanitize_text_field($info['estado']);
    $fechaActualizado = sanitize_text_field($entrevista['actualizado']);

    $cumpleCandidato = sanitize_text_field($info['cumpleCandidato']);
    $recomendabilidad = sanitize_text_field($info['recomendabilidad']);
    $infoCandidatoEntrevista = sanitize_text_field($info['infoCandidatoEntrevista']);
    // $estado = sanitize_text_field($info['estado']);

    $procesoContratoEtapas = $wpdb->get_results("SELECT * from $tabla where idEntrevista = '$idEntrevista'", ARRAY_A);

    $fechaRealizado = $procesoContratoEtapas[0]['fechaPautado'];
    $horaRealizado = $procesoContratoEtapas[0]['hora'];

    $informacion = array(
        'cumpleCandidato' => $cumpleCandidato,
        'recomendabilidad' => $recomendabilidad,
        'infoCandidatoEntrevista' => $infoCandidatoEntrevista,
        'fechaRealizado' => $fechaRealizado,
        'horaRealizado' => $horaRealizado
    );

    // datos para los mensajes
    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$idEntrevista'", ARRAY_A);

    $candidatoId = $procesoContrato[0]['candidataId'];
    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];

    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    $informacion = json_encode($informacion);



    if ($etapa < 3) {
        // caso candidato
        try {

            $wpdb->query("UPDATE $tabla SET fechaCreacion='$fechaActualizado', fechaPautado = 'Realizada',estado='$estadoActualizado', aprobado=1, resultadosEntrevista = '$informacion' WHERE idEntrevista='$idEntrevista'");

            // parte mensajes
            $msj = 'Se ha realizado tu entrevista con éxito para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Te han entrevistado para la vacante la vacante: '.$nombreVacante,
              'estado' => 0,
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'completaEntrevista',
                'email' => $candidatoInfo['email'],
                'usuarioMuestra' => $candidatoInfo['id']
            );
            saveNotification($mensaje);

             // parte Administracion
            $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha sido entrevistado para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong> y publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Hemos realizado la entrevista de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'completaEntrevista',
                'email' => '',
                'usuarioMuestra' => 'Tsoluciono'
            );
            saveNotification($mensaje);


        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

    } elseif ($etapa == 3) {
        // caso familia

        $x = 'igual que';
        // print_r($x);

        try {
            $wpdb->query("UPDATE $tabla SET fechaCreacion='$fechaActualizado', estado='$estadoActualizado', aprobado=1 WHERE idEntrevista='$idEntrevista' and etapa=$etapa");

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

}

function dbAdminModifyInterview($data)
{
    global $wpdb;
    $idEntrevista = $data['entrevista']['idEntrevista'];
    $entrevista = $data['entrevista'];
    $info = $data['info'];

// entrevista
    $idEntrevista = sanitize_text_field($entrevista['idEntrevista']);
    $estadoActualizado = sanitize_text_field($entrevista['estado']);
    $fechaActualizado = sanitize_text_field($entrevista['actualizado']);

    $etapa = $entrevista['etapa'];
// información nueva
    $tipoEntrevista = sanitize_text_field($info['tipoEntrevista']);
    $datoEntrevista = sanitize_text_field($info['datoEntrevista']);
    $date = sanitize_text_field($info['date']);
    $nuevoEstado = sanitize_text_field($info['estado']);
    $notaEntrevista = sanitize_text_field($info['notaEntrevista']);

    // obtengo los datos actuales de sta entrevista para compararlos con los nuevos y asi sustituir el cambio
    $tablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $infoEtapas = $wpdb->get_results("SELECT * from $tablaEtapas where idEntrevista='$idEntrevista' and etapa = $etapa", ARRAY_A);

    // si la variable no esta seteada o no existe entonces se sustituye asi
    $dateF = ($date != '') ? $date : $infoEtapas[0]['fechaPautado'];
    $tipoEntrevistaF = ($tipoEntrevista != $infoEtapas[0]['tipoEntrevista']) ? $tipoEntrevista : $infoEtapas[0]['tipoEntrevista'];
    $datoEntrevistaF = ($datoEntrevista != '') ? $datoEntrevista : $infoEtapas[0]['datoEntrevista'];
    $notaEntrevistaF = ($notaEntrevista != '') ? $notaEntrevista : $infoEtapas[0]['nota'];

    try {
        $wpdb->query("UPDATE $tablaEtapas SET fechaCreacion='$fechaActualizado', fechaPautado='$dateF', tipoEntrevista='$tipoEntrevistaF', datoEntrevista='$datoEntrevistaF', nota='$notaEntrevistaF' WHERE idEntrevista='$idEntrevista' and etapa=$etapa");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

function dbGetInfoVerifyVacant($data)
{

    $user = getUserGeneralInfo($data['idCont']);

    $oferta = dbGetOfferLaboralInfoBySerial($data['serialOferta']);

    $v = array(
        'user' => $user,
        'oferta' => $oferta,
    );

    return $v;

}

// funcion para almacenar el ajuste de estatus de verificacion de vacante laboral

function dbProcessAdminVacantVerify($data)
{

    global $wpdb;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaOfertaAdmin = $wpdb->prefix . 'admin_vacantes_familia';

    $serial = sanitize_text_field($data['serial']);
    $recibidos = $data['adminData'];

    $necesidades = sanitize_text_field($recibidos['necesidades']);
    $notaAdmin = sanitize_text_field($recibidos['notaAdmin']);
    $estadoPublico = sanitize_text_field($recibidos['estadoPublico']);
    $estadoAprobado = sanitize_text_field($recibidos['estadoAprobado']);

    $fechaRevisado = sanitize_text_field($data['fechaRevisado']);

    $d = $wpdb->get_results("SELECT * from $tablaOferta where serialOferta='$serial'", ARRAY_A);
    $wpdb->flush();
    $contratistaId = $d[0]['contratistaId'];
    $idOferta = $d[0]['id'];


    $datosVerif = $wpdb->get_results("SELECT * from $tablaOfertaAdmin where idOferta='$idOferta'", ARRAY_A);
    $wpdb->flush();
    $publicarViejo = $datosVerif[0]['publicar'];
    $aprobadoViejo = $datosVerif[0]['aprobado'];
    $publicarViejo = ($publicarViejo == null || $publicarViejo == '')? 0: 1;
    $aprobadoViejo = ($aprobadoViejo == null || $aprobadoViejo == '')? 0: 1;



    try {

        $idOferta = $idOferta;
        $wpdb->query("UPDATE $tablaOfertaAdmin SET fechaLlamada='$fechaRevisado', necesidades='$necesidades', notaAdmin='$notaAdmin', publicar=$estadoPublico, aprobado=$estadoAprobado where idOferta='$idOferta'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    // paerte de mensaje
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $contratistaId = $contratistaId;

    $tipoServicio = $d[0]['tipoServicio'];
    $serialVacante = $d[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
    $nombreVacante = $d[0]['nombreTrabajo'];
    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    if( ($publicarViejo == 0) && ($aprobadoViejo == 0) && ($estadoPublico == 1) && ($estadoAprobado == 1) ){

    $msj = 'Tu oferta laboral <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a> ha sido verificada exitosamente según tus necesidades.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Se ha verificado tu oferta laboral: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'verifyOffer',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);

    // parte Administracion
    $msj = 'Hemos verificado la oferta laboral <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a> exitosamente según las necesidades de '.$familiaInfo['nombre'].'.';

    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Hemos verificado la oferta laboral: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'verifyOffer',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);

    return;
    }



}
// funcion para almacenar el ajuste de estatus de opciones de vacante laboral
function dbProcessOpcionesVacante($data)
{

    global $wpdb;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaOfertaAdmin = $wpdb->prefix . 'admin_vacantes_familia';

    $serial = sanitize_text_field($data['serial']);
    $recibidos = $data['adminData'];
    $estadoPublico = sanitize_text_field($recibidos['estadoPublico']);

    try {
        $d = $wpdb->get_results("SELECT id from $tablaOferta where serialOferta='$serial'", ARRAY_A);
        $wpdb->flush();

        $idOferta = $d[0]['id'];

        $wpdb->query("UPDATE $tablaOfertaAdmin SET publicar=$estadoPublico where idOferta='$idOferta'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}



function dbCreateFamilyInterview($data){

    global $wpdb;

    $familia = $data['familia'];
    // $serial = $data['serial'];
    $contratistaId = $familia['id'];
    $ofertaId = $familia['idOferta'];
    $estado = 'En espera de entrevista';
    $creadoEn = date('d/m/Y');
    $confirmaFecha = json_encode($data['info']['confirmaFecha']);

    $i = 0;
        $id = uniqid() . uniqid();
        $datos1 = array(
            'id' => sanitize_text_field($id),
            'contratistaId' => sanitize_text_field($contratistaId),
            'candidataId' => sanitize_text_field($contratistaId),
            'ofertaId' => sanitize_text_field($ofertaId),
            'etapa' => '1',
            'estado' => 'Mediación con la familia',
        );


        $datos2 = array(
            'idEntrevista' => sanitize_text_field($id),
            'fechaCreacion' => sanitize_text_field($creadoEn),
            'fechaPautado' => sanitize_text_field($data['info']['date']),
            'hora' => sanitize_text_field($data['info']['hora']),
            'estado' => $estado,
            'tipoEntrevista' => sanitize_text_field($data['info']['tipoEntrevista']),
            'aprobado' => 0,
            'datoEntrevista' => sanitize_text_field($data['info']['datoEntrevista']),
            'nota' => sanitize_text_field($data['info']['notaEntrevista']),
            'resultadosEntrevista' => '',
            'confirmaFecha' => sanitize_text_field($confirmaFecha)
        );
        $formato1 = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        );
        $formato2 = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        );
        $tabla1 = $wpdb->prefix . 'proceso_contrato';
        $wpdb->insert($tabla1, $datos1, $formato1);
        $wpdb->flush();
        $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
        $wpdb->insert($tabla, $datos2, $formato2);
        $wpdb->flush();


        // parte de mensaje
        $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
        $procesoContrato = $wpdb->prefix . 'proceso_contrato';
        // $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

        $contratistaId = $contratistaId;
        $ofertaId = $ofertaId;

        $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

        $tipoServicio = $infoOferta[0]['tipoServicio'];
        $serialVacante = $infoOferta[0]['serialOferta'];
        $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
        $nombreVacante = $infoOferta[0]['nombreTrabajo'];
        $familiaInfo = getInfoNameEmailUsers($contratistaId);

        $msj = 'Te hemos programado entrevista con nosotros por tu vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Para tratar el tema de candidatos disponibles por el cargo de: <strong>'.$tipoServicio.'</strong>.
         <br><br> Confirma o solicita una reprogramación de fecha y hora <a href="'.$urlReprogEntrevista.'">AQUÍ</a>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Tienes una entrevista pendiente con administración por tu vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newEntrevista',
            'email' => $familiaInfo['email'],
            'usuarioMuestra' => $familiaInfo['id']
        );
        saveNotification($mensaje);

        $urlReprogEntrevista = esc_url(get_permalink(get_page_by_title('Mis vacantes'))).'?pg=tab2&oid='.$ofertaId;

        $msj = 'Se ha programado una entrevista con <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>.'.' Para tratar temas sobre su vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Se realizará en la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>.';

        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Hemos programado una entrevista con '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') por su vacante publicada: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newEntrevista',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);


try {
    $tabla = $wpdb->prefix . 'proceso_contrato';

    $wpdb->query("UPDATE $tabla SET etapa='1', estado='Mediación con la familia' where ofertaId='$ofertaId'");

} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

    die();


}



function dbSendCreateFamilyPostulantSelectionStep($data){

    global $wpdb;

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';

    $ofertaId = $data['familia']['ofertaId'];
    $id = $data['familia']['id'];
    $idEntrevista = $data['familia']['idEntrevista'];
    $etapa = $data['familia']['etapa'];
    $solucionPropuesta = $data['info']['solucionPropuesta'];
    $seleccionPor = 'Familia';
    $infoEntrevistaFamilia = $data['info']['infoEntrevistaFamilia'];

    // return;
    $estado = 'En proceso de selección definitiva';
    $creadoEn = date('d/m/Y');

        $informacion = array(
        'solucionPropuesta' => $solucionPropuesta,
        'seleccionPor' => $seleccionPor,
        'infoEntrevistaFamilia' => $infoEntrevistaFamilia
    );

    $informacion = json_encode($informacion);



try {
    $tabla = $wpdb->prefix . 'proceso_contrato';

    $wpdb->query("UPDATE $tabla SET etapa='2', estado='Selección final de candidato' where ofertaId='$ofertaId'");

    $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
   $wpdb->query("UPDATE $tabla SET fechaCreacion='$creadoEn', fechaPautado = 'Realizada',estado='En espera de selección final', aprobado=1, resultadosEntrevista = '$informacion' WHERE idEntrevista='$idEntrevista'");


    // parte de mensaje
  // datos mensajes
    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$idEntrevista'", ARRAY_A);

  $candidatoId = $procesoContrato[0]['candidataId'];
  $contratistaId = $procesoContrato[0]['contratistaId'];
  $ofertaId = $procesoContrato[0]['ofertaId'];

  $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

  $tipoServicio = $infoOferta[0]['tipoServicio'];
  $serialVacante = $infoOferta[0]['serialOferta'];
  $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
  $nombreVacante = $infoOferta[0]['nombreTrabajo'];

  // $candidatoInfo = getInfoNameEmailUsers($candidatoId);
  $familiaInfo = getInfoNameEmailUsers($contratistaId);



       // parte mensajes
              $msj = 'Se ha realizado tu entrevista con éxito, como dueño de la publicación para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. ';
              $mensaje = array(
                  'mensaje' => $msj,
                  'subject' => 'Te han entrevistado por tu vacante: '.$nombreVacante.'.',
                'estado' => 0,
                  'estado' => 0,
               // 'fecha' => ,
                  'tipo' => 'completaEntrevista',
                  'email' => $familiaInfo['email'],
                  'usuarioMuestra' => $familiaInfo['id']
              );
              saveNotification($mensaje);



               // parte Administracion
              $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha sido entrevistado por su vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
              $mensaje = array(
                  'mensaje' => $msj,
                  'subject' => 'Hemos realizado la entrevista de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') por su vacante: '.$nombreVacante.'.',
                  'estado' => 0,
               // 'fecha' => ,
                  'tipo' => 'completaEntrevista',
                  'email' => '',
                  'usuarioMuestra' => 'Tsoluciono'
              );
              saveNotification($mensaje);


} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
    die();

}


function dbSendSelectForContract($data){

    global $wpdb;

    $ofertaId = $data['ofertaId'];
    $current = $data['current'];
    $can = $data['can'];
    $fam = $data['fam'];



    if($fam == $current){
        $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

        $tabla = $wpdb->prefix . 'proceso_contrato';

        $i = $wpdb->get_results("SELECT * from $tabla where ofertaId = '$ofertaId' and contratistaId = $fam and candidataId = $fam", ARRAY_A);
        $wpdb->flush();

        $idEntrevista = $i[0]['id'];


        $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
        $tablaprocesoEntrevistas = $tabla;
        $i = $wpdb->get_results("SELECT id, resultadosEntrevista from $tabla where idEntrevista = '$idEntrevista'", ARRAY_A);

        $resultadosEntrevista = $i[0]['resultadosEntrevista'];

        $resultadosEntrevista = json_decode($resultadosEntrevista, true);

        $resultadosEntrevista['seleccionPor'] = 'Tsoluciono';
        $resultadosEntrevista['candidatoSeleccionado'] = $can;

        $resultadosEntrevista = json_encode($resultadosEntrevista);

        $id = $i['0']['id'];

        try {


            $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
            $wpdb->query("UPDATE $tabla SET resultadosEntrevista='$resultadosEntrevista' where id=$id and idEntrevista='$idEntrevista'");

            // $procesoContrato = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$entrevistaId'", ARRAY_A);
            // $candidatoId = $procesoContrato[0]['candidataId'];
            // $contratistaId = $procesoContrato[0]['contratistaId'];
            $ofertaId = $ofertaId;

            $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
            $tipoServicio = $infoOferta[0]['tipoServicio'];

            $nombreVacante = $infoOferta[0]['nombreTrabajo'];
            $serialVacante = $infoOferta[0]['serialOferta'];
            $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

            $candidatoInfo = getInfoNameEmailUsers($can);
            $familiaInfo = getInfoNameEmailUsers($fam);

                        // parte candidato
           $msj = 'Te han seleccionado para ser contratado por la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Administración te enviará una propuesta de contrato. Te mantendremos informado.';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Te han seleccionado para contrato por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'selectForContract',
               'email' => $candidatoInfo['email'],
               'usuarioMuestra' => $candidatoInfo['id']
           );
           saveNotification($mensaje);
                        // parte candidato
           $msj = 'Felicitaciones has culminado el proceso de selección del candidato <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>  para ser contratado por tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Administración le enviará una propuesta de contrato. Te mantendremos informado. ';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Seleccionaste a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> para contrato por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'selectForContract',
               'email' => $familiaInfo['email'],
               'usuarioMuestra' => $familiaInfo['id']
           );
           saveNotification($mensaje);
           // parte Administracion
           $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha seleccionado al candidato <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> a ser contratado por la vacante publicada  <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Debemos enviarle al candidato la propuesta de contrato';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha seleccionado al candidato <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> a ser contratado',
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'selectForContract',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);


        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

}

function dbSendAdminSolChangeDate($data){

    global $wpdb;
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $entrevistaId = $data['entrevistaId'];
    $info = $data['info'];

    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);

    $v = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$entrevistaId' and contratistaId = candidataId", ARRAY_A);

    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);

    $x = array(
        'estado' => 'Propuesta',
        'date' => $data['info']['date'],
        'hora' => $data['info']['hora']
    );

    $confirmadoFecha['admin'] = $x;

    $tipoProceso = '';
    // confirmar si es candidato o familia
    if( (isset($v)) && (count($v) > 0) && ($v[0]['contratistaId'] == $v[0]['candidataId']) ){
        $confirmadoFecha['familia'] = 'Pendiente';
        $tipoProceso = 'Familia';
    }else{
        $confirmadoFecha['candidato'] = 'Pendiente';
        $tipoProceso = 'Candidato';
    }

    $confirmadoFecha = json_encode($confirmadoFecha);

    // datos para notificacion

    $procesoContrato = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$entrevistaId'", ARRAY_A);
    $candidatoId = $procesoContrato[0]['candidataId'];
    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

    try {

        $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$confirmadoFecha' where idEntrevista='$entrevistaId'");

        if($tipoProceso == 'Candidato'){

           // parte candidato
           $msj = 'Has recibido una propuesta de reprogramación para tu asistencía en la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, para la fecha: <strong>'.$x['date'].'</strong> y hora: <strong>'.$x['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Recibiste una solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'reprogAsistencia',
               'email' => $candidatoInfo['email'],
               'usuarioMuestra' => $candidatoInfo['id']
           );
           saveNotification($mensaje);

           // parte Administracion
           $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha recibido una propuesta de reprogramación de su asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Enviamos una solicitud a '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para reprogramar su asistencía a la entrevista por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'reprogAsistencia',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);

        }
        if($tipoProceso == 'Familia'){
            // parte candidato
           $msj = 'Has recibido una propuesta de reprogramación para tu asistencía en la entrevista de tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, para la fecha: <strong>'.$x['date'].'</strong> y hora: <strong>'.$x['hora'].'</strong>.';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Recibiste una solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'reprogAsistencia',
               'email' => $familiaInfo['email'],
               'usuarioMuestra' => $familiaInfo['id']
           );
           saveNotification($mensaje);
           // parte Administracion
           $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha recibido una propuesta de reprogramación de su asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>.';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Enviamos una solicitud a '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para reprogramar su asistencía a la entrevista por la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'reprogAsistencia',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);
        }

    } catch (Exception $e) {

    }


}

// aceptar cambio de hora y fecha propuesto por candidatos y familia
function dbSendAdminConfirmDate($data){

    global $wpdb;
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $entrevistaId = $data;

    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);


    $v = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$entrevistaId' and contratistaId = candidataId", ARRAY_A);


    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);

    $d = array();

    // datos para el mensaje ------

    // datos del proceso de contrato
    $procesoContrato = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$entrevistaId'", ARRAY_A);
    $candidatoId = $procesoContrato[0]['candidataId'];
    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];

    // datos de la vacante
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

    // -----

    if( (isset($v)) && (count($v) > 0) && ($v[0]['contratistaId'] == $v[0]['candidataId']) ){

        if(isset($confirmadoFecha['familia']['estado']) && $confirmadoFecha['familia']['estado'] == 'Propuesta'){
            // con propuesta

            $d['date'] = $confirmadoFecha['familia']['date'];
            $d['hora'] = $confirmadoFecha['familia']['hora'];

            $x = array(
                'admin' => 'Confirmada',
                'familia' => 'Confirmada'
            );
            $x = json_encode($x);

            try {
                $date = $d['date'];
                $hora = $d['hora'];

                $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET fechaPautado = '$date', hora = '$hora', confirmaFecha = '$x' where idEntrevista='$entrevistaId'");
                     // parte Admin
     $msj = 'Administración ha aprobado la solicitud de <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' para cambiar su asistencía para la entrevista de su vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>.';

     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Aprobada la solicitud de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para reprogramar su asistencía a la entrevista por su vacante publicada: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);

          // parte familia
          $msj = 'Administración ha aprobado tu solicitud para cambiar la hora y fecha de tu asistencía para la entrevista de tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>.';
          $mensaje = array(
              'mensaje' => $msj,
              'subject' => 'Aprobada tu solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
              'estado' => 0,
           // 'fecha' => ,
              'tipo' => 'confAsistencia',
              'email' => $familiaInfo['email'],
              'usuarioMuestra' => $familiaInfo['id']
          );
          saveNotification($mensaje);

            } catch (Exception $e) {

            }

        }else{

            // sin propuesta
            $x = array(
                'admin' => 'Confirmada',
                'familia' => 'Confirmada'
            );
            $x = json_encode($x);

            $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$x' where idEntrevista='$entrevistaId'");

                 // parte Admin
     $msj = 'Ha sido confirmada la asistencia de <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' para la entrevista de su vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>.';

     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Confirmación de asistencia de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para la entrevista de su vacante publicada: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);

    //  parte familia

     $msj = 'Ha sido confirmada la asistencia de <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' para la entrevista de tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Confirmación de tu asistencía a la entrevista de tu vacante publicada: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => $familiaInfo['email'],
         'usuarioMuestra' => $familiaInfo['id']
     );
     saveNotification($mensaje);

        }

    }else{
        if(isset($confirmadoFecha['candidato']['estado']) && $confirmadoFecha['candidato']['estado'] == 'Propuesta'){

            // con propuesta
            $d['date'] = $confirmadoFecha['candidato']['date'];
            $d['hora'] = $confirmadoFecha['candidato']['hora'];

            $x = array(
                'admin' => 'Confirmada',
                'candidato' => 'Confirmada'
            );
            $x = json_encode($x);

            try {
                $date = $d['date'];
                $hora = $d['hora'];
                // print_r($confirmadoFecha);
                // return;
                $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET fechaPautado = '$date', hora = '$hora', confirmaFecha = '$x' where idEntrevista='$entrevistaId'");
                                     // parte Admin
     $msj = 'Administración ha aprobado la solicitud de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' para cambiar su asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Aprobada la solicitud de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para reprogramar su asistencía a la entrevista por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);

               // parte candidato
               $msj = 'Administración ha aprobado tu solicitud para cambiar la hora y fecha de tu asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
               $mensaje = array(
                   'mensaje' => $msj,
                   'subject' => 'Aprobada tu solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
                   'estado' => 0,
                // 'fecha' => ,
                   'tipo' => 'confAsistencia',
                   'email' => $candidatoInfo['email'],
                   'usuarioMuestra' => $candidatoInfo['id']
               );
               saveNotification($mensaje);
            } catch (Exception $e) {

            }

        }else{
            // sin propuesta
            $x = array(
                'admin' => 'Confirmada',
                'candidato' => 'Confirmada'
            );
            $x = json_encode($x);

            $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$x' where idEntrevista='$entrevistaId'");

                             // parte Admin
     $msj = 'Ha sido confirmada la asistencia de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>.';

     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Confirmación de asistencia de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para su entrevista por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);

    //  parte familia

     $msj = 'Ha sido confirmada la asistencia de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Confirmación de tu asistencía a la entrevista por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'confAsistencia',
         'email' => $candidatoInfo['email'],
         'usuarioMuestra' => $candidatoInfo['id']
     );
     saveNotification($mensaje);


        }
    }



}




function dbSendIntegrateNewPostulate($data){

    // return;
    global $wpdb;
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';


    $ci = $data['ci'];
    $ei = $data['ei'];
    $aei = $data['aei'];

    // obtener entrevista original y etapas de entrevista para traerlas al proceso deseado a la integración
    $entrevista = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$ei'", ARRAY_A);
    $entrevista = $entrevista[0];

    $tipoEntrevista = $entrevista['tipoEntrevista'];

    $etapas = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$ei'", ARRAY_A);
    $etapas = $etapas[0];

    $tipoEntrevista = $etapas['tipoEntrevista'];
    $aprobado = $etapas['aprobado'];
    $aprobado = 0;
    $datoEntrevista = $etapas['datoEntrevista'];
    $nota = $etapas['nota'];
    $resultadosEntrevista = $etapas['resultadosEntrevista'];
    // $confirmaFecha = $etapas['confirmaFecha'];
    $confirmaFecha = array(
        'admin' => 'Confirmada',
        'candidato' => 'Pendiente'
    );

    $confirmaFecha = json_encode($confirmaFecha);
    $pruebasPsico = $etapas['pruebasPsico'];
    $resultadosPruebasPsico = $etapas['resultadosPruebasPsico'];


    // obtener información del proceso de entrevistas al que queremos anexar al candidato

    $entrevistaAnexar = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where ofertaId = '$aei'", ARRAY_A);

    $idEntrevistaAnexar = $entrevistaAnexar[0]['id'];

    $etapasAnexar = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$idEntrevistaAnexar'", ARRAY_A);
    // $etapasAnexar = $etapasAnexar[0];

    $famIdAnexar = $entrevistaAnexar[0]['contratistaId'];
    $ofertaIdAnexar = $entrevistaAnexar[0]['ofertaId'];
    $etapaAnexar = $entrevistaAnexar[0]['etapa'];
    $estadoAnexar = $etapasAnexar[0]['estado'];
    $horaAnexar = $entrevistaAnexar[0]['hora'];
    $estadoAnexar = $entrevistaAnexar[0]['estado'];

    // ahora se integran en el proceso deseado
    // cargar datos
    $id = uniqid() . uniqid();
    $datos1 = array(
        'id' => sanitize_text_field($id),
        'contratistaId' => sanitize_text_field($famIdAnexar),
        'candidataId' => sanitize_text_field($ci),
        'ofertaId' => sanitize_text_field($aei),
        'etapa' => sanitize_text_field($etapaAnexar),
        'estado' => sanitize_text_field($estadoAnexar)
    );
    $formato1 = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $datos2 = array(
        // 'id' => ,
        'idEntrevista' => sanitize_text_field($id),
        'fechaCreacion' => date('d/m/Y'),
        'fechaPautado' => 'Adicional',
        'hora' => 'S.H',
        'estado' => sanitize_text_field($estadoAnexar),
        'tipoEntrevista' => 'Añadido al proceso de entrevistas',
        'aprobado' => sanitize_text_field($aprobado),
        'datoEntrevista' => sanitize_text_field($datoEntrevista),
        'nota' => sanitize_text_field($nota),
        'resultadosEntrevista' => sanitize_text_field($resultadosEntrevista),
        'confirmaFecha' => sanitize_text_field($confirmaFecha),
        'pruebasPsico' => sanitize_text_field($pruebasPsico),
        'resultadosPruebasPsico' => sanitize_text_field($resultadosPruebasPsico)
    );

    $formato2 = array(

        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    // print_r($datos1);
    // print_r($datos2);

    $wpdb->insert($tablaprocesoEntrevistas, $datos1, $formato1);
    $wpdb->flush();
    $wpdb->insert($tablaprocesoEntrevistasEtapas, $datos2, $formato2);
    $wpdb->flush();

    // parte de notifiacion

    $ofertaId = $aei;
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];

    $familiaInfo = getInfoNameEmailUsers($famIdAnexar);
    $candidatoInfo = getInfoNameEmailUsers($ci);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;


            // parte candidato
           $msj = 'Tienes una propuesta de trabajo tomando en cuenta una de tus entrevistas realizadas en anterioridad, ahora puedes rechazar o aceptar esta oportunidad laboral para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'propuesta de trabajo para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCand',
               'email' => $candidatoInfo['email'],
               'usuarioMuestra' => $candidatoInfo['id']
           );

           saveNotification($mensaje);

           $msj = 'Enviamos una propuesta a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> como candidato adicional tomando en cuenta una de sus entrevistas realizadas en anterioridad para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>.';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Enviamos una propuesta de trabajo a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCand',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);


}


// guardar datos de config admin
function dbsaveConfigAdminSettings($data){

    global $wpdb;
    $tablaconfiguracionesadmin = $wpdb->prefix . 'configuracionesadmin';


    $directiva = $data['cargos']['directiva'];
    $directivaFirma = $data['cargos']['directivaFirma'];
    $otros = $data['otros'];

    $teamDatos = array(
        'Directiva' => array(
            'Nombre' => $directiva,
            'firma' => $directivaFirma
        ),
        'Bancos' => array(
            'banco1' => $data['bancos']['banco1'],
            'numeroBanco1' => $data['bancos']['numeroBanco1'],
            'banco2' => $data['bancos']['banco2'],
            'numeroBanco2' => $data['bancos']['numeroBanco2']
        ),
        'Otros' => array(
            'Direccion' => $otros['direccion']
        )
    );

    $teamDatos = json_encode($teamDatos, JSON_UNESCAPED_UNICODE);
    // guardar tabla config admin
    $infoAdmin = array(
        'teamDatos' => $teamDatos
    );
    $formato = array(
        '%s'
    );

    $configVerf = $wpdb->get_results("SELECT * FROM $tablaconfiguracionesadmin", ARRAY_A);

    if(count($configVerf) > 0){

        try {
            $wpdb->query("UPDATE $tablaconfiguracionesadmin SET teamDatos = '$teamDatos'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

    }else{
        // no existe
        $wpdb->insert($tablaconfiguracionesadmin, $infoAdmin, $formato);
        $wpdb->flush();
        // print_r($infoAdmin);
    }


}


// estoy ahora aqui
function dbsendacceptPay($data){

    global $wpdb;

    if( !isset($data['type']) && $data['type'] != 'pubprof'){

    $facturacion = $wpdb->prefix . 'facturacion';
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $serial = $data;

    $factura = $wpdb->get_results("SELECT * from $facturacion where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();
    $oferta = $wpdb->get_results("SELECT * from $tablaOferta where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();

    $serialOferta = $oferta[0]['serialOferta'];

    $contratistaId = $factura[0]['contratistaId'];
    $nombreFactura = $factura[0]['nombreFactura'];



    $estado = 'Pago tramitado y confirmado';
    $pagado = 1;
    $estadoPublico = 1;

    try {

        $wpdb->query("UPDATE $facturacion SET estado = '$estado', pagado = $pagado  where serialFactura = '$serial'");
        $wpdb->query("UPDATE $tablaOferta SET publico = '$estadoPublico'  where serialFactura = '$serial'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

           // datos para el mensaje ------

    // datos del proceso de contrato
    $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $ofertaURL = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serial;

                                 // parte Admin
    $msj = 'Hemos aprobado el pago de <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' por la prestación de servicios para la creación de su oferta laboral: <a href="'.$ofertaURL.'" class="hiper">'.$nombreFactura.'</a>.';

    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pago aprobado de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].').',
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'acceptPay',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);

                                //  parte familia
    $msj = 'Administración ha aprobado tu pago por prestación de servicios para la creación de tu oferta laboral: <a href="'.$ofertaURL.'" class="hiper">'.$nombreFactura.'</a>. Dicha publicación ahora se encuentra visible al público.';

    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Te aprobarón el pago por el servicio '.$nombreFactura.'.',
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'acceptPay',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);


    }else{

    $facturacion_profesional = $wpdb->prefix . 'facturacion_profesional';
    $public_profesional = $wpdb->prefix . 'public_profesional';
    $serial = $data['serial'];

    $factura = $wpdb->get_results("SELECT * from $facturacion_profesional where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();
    $oferta = $wpdb->get_results("SELECT * from $public_profesional where factura = '$serial'", ARRAY_A);
    $wpdb->flush();

    $serialOferta = $oferta[0]['serialOferta'];

    $contratistaId = $factura[0]['contratistaId'];
    $nombreFactura = $factura[0]['nombreFactura'];

    $estado = 'Pago tramitado y confirmado';
    $pagado = 1;
    $estadoPublico = 1;


    try {

        $wpdb->query("UPDATE $facturacion_profesional SET estado = '$estado', pagado = $pagado  where serialFactura = '$serial'");
        $wpdb->query("UPDATE $public_profesional SET publico = '$estadoPublico'  where factura = '$serial'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


    }

}




// aqui
function dbsendrefusePay($data){

    global $wpdb;


     if( !isset($data['data']['type']) && $data['data']['type'] != 'pubprof'){

    $facturacion = $wpdb->prefix . 'facturacion';

    $serial = $data['serial'];
    $nota = $data['refuseNote'];
    $nota = sanitize_text_field($nota);
    $nota = ($nota != '')?"<strong>Nota de Administración:</strong> <br> $nota": '';
    $pagado = 0;
    $estado = 'Pago rechazado';

    $formato = '';
    $comprobante = 0;
    $pagado = 0;

    $factura = $wpdb->get_results("SELECT * from $facturacion where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();

    $idUsuario = $factura[0]['contratistaId'];
    $nombreFactura = $factura['']['nombreFactura'];


    $formatoViejo = $factura[0]['formato'];
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/facturas';
    $archivo = "$upload_dir/$serial.$formatoViejo";

    try {

        unlink($archivo);
        $wpdb->query("UPDATE $facturacion SET estado = '$estado', pagado = $pagado, mensaje='$nota', formato = '$formato', comprobante = '$comprobante' where serialFactura = '$serial'");


    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    $familiaInfo = getInfoNameEmailUsers($idUsuario);
    $facturaUrl = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serial;

                                 // parte Admin
    $msj = 'Hemos rechazado el pago de <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' por la prestación de servicios para la creación de su oferta laboral: '.$nombreFactura.'. Además se le ha solicitado rehacer el proceso de pago en la siguiente <a href="'.$facturaUrl.'" class="hiper">FACTURA</a>';

    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pago rechazado de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].').',
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'refusePay',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);

                                //  parte familia
    $msj = 'Administración han rechazado tu pago por prestación de servicios para la creación de tu oferta laboral: '.$nombreFactura.'. Puedes intentar rehacer el proceso de pago en la siguiente <a href="'.$facturaUrl.'" class="hiper">FACTURA</a>';

    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pago rechazado por el servicio '.$nombreFactura.'.',
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'refusePay',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);

}else{


    $facturacion = $wpdb->prefix . 'facturacion_profesional';

    $serial = $data['data']['data'];
    $nota = $data['refuseNote'];
    $nota = sanitize_text_field($nota);
    $nota = ($nota != '')?"<strong>Nota de Administración:</strong> <br> $nota": '';
    $pagado = 0;
    $estado = 'Pago rechazado';

    $formato = '';
    $comprobante = 0;
    $pagado = 0;

    $factura = $wpdb->get_results("SELECT * from $facturacion where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();


    $idUsuario = $factura[0]['candidatoId'];

    $comprobanteJson = $factura[0]['imagenReferencia'];
    $comprobanteJson = json_decode($comprobanteJson, true);

    $direccin = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$comprobanteJson[0]['src']: '';
    $archivo = $direccin;


    print_r($archivo);

    try {

        unlink($archivo);
        // return;
        $wpdb->query("UPDATE $facturacion SET imagenReferencia = '', estado = '$estado', pagado = $pagado, mensaje='$nota', formato = '$formato', comprobante = '$comprobante' where serialFactura = '$serial'");


    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


}



}



function dbsenddeletePay($data){
    global $wpdb;
    $facturacion = $wpdb->prefix . 'facturacion';
    $serial = $data;

    $usuario = $data['usuario'];

    $factura = $wpdb->get_results("SELECT * from $facturacion where serialFactura = '$serial'", ARRAY_A);
    $wpdb->flush();

    $familiaId = $factura[0]['contratistaId'];
    $formato = $factura[0]['formato'];
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/facturas';
    $archivo = "$upload_dir/$serial.$formato";


    try {

        unlink($archivo);
        $wpdb->query("DELETE FROM $facturacion WHERE serialFactura = '$serial'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }



}



function dbAdminDeleteSelectPostulant($data){

    global $wpdb;
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $etapasTabla = $wpdb->prefix. 'proceso_contrato_etapas';
    $ofertaTabla = $wpdb->prefix. 'ofertalaboral';

    $candidataId = $data['candidato'];
    $serial = $data['serial'];

    $oferta = $wpdb->get_results("SELECT * FROM $ofertaTabla where serialOferta='$serial'", ARRAY_A);
    $wpdb->flush();
    $ofertaId = $oferta[0]['id'];


    $entrevistas = $wpdb->get_results("SELECT * FROM $entrevistasTabla as entrevistas INNER JOIN $etapasTabla as etapas ON entrevistas.id = etapas.idEntrevista where entrevistas.ofertaId = '$ofertaId' and entrevistas.candidataId = $candidataId", ARRAY_A);
    $wpdb->flush();



    if(count($entrevistas) > 0){

        try {

            $wpdb->query("DELETE FROM $entrevistasTabla WHERE candidataId = $candidataId and ofertaId = '$ofertaId'");

            echo postulateList();

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

}


function dbAdminAddPostulant($data){

}

function dbsendEvaluatePsicoTest($data){

    global $wpdb;

    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $etapasTabla = $wpdb->prefix. 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $imagenes = $data['imagenes'];
    $data = $data['data'];
    $data = json_decode($data, true);
    $idEntrevista = $data['idEntrevista'];
    $ofertaId = $data['ofertaId'];
    $estado = 'En calficación de las pruebas';


    $info = $wpdb->get_results("SELECT * from $entrevistasTabla where id = '$idEntrevista'", ARRAY_A);
    $infoEtapa = $wpdb->get_results("SELECT * from $etapasTabla where idEntrevista = '$idEntrevista'", ARRAY_A);

    $canId = $info[0]['candidataId'];
    $FechaPautado = $infoEtapa[0]['fechaPautado'];
    $horaPautado = $infoEtapa[0]['hora'];

    $iii = array(
        'imagenes' => $imagenes,
        'carpeta' => '/pruebas',
        'serial' => $idEntrevista,
    );

    $imagenesJson = cargarImagenes($iii);
    $imagenesJson = json_decode($imagenesJson, true);
    $nuevoJson = array(
        'imagenes' => $imagenesJson,
        'fechaRealizado' => $FechaPautado,
        'horaRealizado' => $horaPautado
    );
    $imagenesJson = json_encode($nuevoJson);

    // print_r($imagenesJson);
    // return;

    try {
        $wpdb->query(" UPDATE $etapasTabla SET pruebasPsico = '$imagenesJson',estado = '$estado',fechaPautado = 'Realizada'  WHERE idEntrevista='$idEntrevista'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

   $candidatoId = $canId;

   $ofertaId = $ofertaId;
   $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

   $tipoServicio = $infoOferta[0]['tipoServicio'];
   $candidatoInfo = getInfoNameEmailUsers($candidatoId);

   $nombreVacante = $infoOferta[0]['nombreTrabajo'];
   $serialVacante = $infoOferta[0]['serialOferta'];

       print_r(array(
        'data' => $data,
        'info' => $infoOferta,
        'cand' => $candidatoInfo
    ));
    // return;

   $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

   // candidato
    $msj = 'Hemos realizado tus Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. <br><br>

    Te estaremos informando sobre tu calificación';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pruebas Psico laborales por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => $candidatoInfo['email'],
        'usuarioMuestra' => $candidatoInfo['id']
    );
    saveNotification($mensaje);
   // familia
    // parte Admin
    $msj = 'Hemos ralizado las Pruebas Psico laborales de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Realizamos las Pruebas Psico laborales de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);


}


function dbCalifEvaluateTests2($data){

    global $wpdb;
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $etapasTabla = $wpdb->prefix. 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';



}



function dbFinalResultTest($data){

    global $wpdb;
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $info = $data['info'];


    $entrevista = $data['entrevista'];


    $data = $data['data'];
    $idEntrevista = $data['idEntrevista'];
    $etapa = $data['etapa'];
    $ofertaId = $data['ofertaId'];
    $candId = $data['candId'];
    $serial = $data['serial'];


    // return;

    if($info['motivos'] == 'si'){


    // datos de las pruebas psico
        $pruebasResultados = array(
            'califica' => $info['motivos'],
            'motivo' => 'Cumple los requisitos Psico laborales',
            'nota' => $info['notaEntrevista']
        );
        $pruebasResultados = json_encode($pruebasResultados, JSON_UNESCAPED_UNICODE);
        $estado = 'El candidato califica para el cargo';



    try {
        $wpdb->query(" UPDATE $entrevistasTablaEtapas SET resultadosPruebasPsico = '$pruebasResultados', aprobado = 1, estado = '$estado' WHERE idEntrevista='$idEntrevista'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


   $candidatoId = $candId;

   $ofertaId = $ofertaId;
   $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

   $tipoServicio = $infoOferta[0]['tipoServicio'];
   $candidatoInfo = getInfoNameEmailUsers($candidatoId);

   $nombreVacante = $infoOferta[0]['nombreTrabajo'];
   $serialVacante = $infoOferta[0]['serialOferta'];

       print_r(array(
        'data' => $data,
        'info' => $infoOferta,
        'cand' => $candidatoInfo
    ));
    // return;

   $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

   // candidato
    $msj = 'Hemos calificado tus Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. <br><br>

    Te calificación es positiva, tu próxima entrevista será programada en breve';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pruebas Psico laborales <strong>APROBADAS</strong> por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => $candidatoInfo['email'],
        'usuarioMuestra' => $candidatoInfo['id']
    );
    saveNotification($mensaje);

    // parte Admin
    $msj = 'Hemos calificado de forma positiva las Pruebas Psico laborales de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pruebas Psico laborales <strong>APROBADAS</strong> de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);


    }elseif ($info['motivos'] == 'no') {

        $pruebasResultados = array(
            'califica' => $info['motivos'],
            'motivo' => 'No cumple con los requisitos Psico laborales',
            'nota' => $info['notaEntrevista']
        );
        $pruebasResultados = json_encode($pruebasResultados, JSON_UNESCAPED_UNICODE);
        $estado = 'El candidato no califica para el cargo';

        // $datoEntrevista = ''
        // $nota
        // $confirmaFecha

        try {
            $wpdb->query(" UPDATE $entrevistasTablaEtapas SET resultadosPruebasPsico = '$pruebasResultados', aprobado = 1, estado = '$estado' WHERE idEntrevista='$idEntrevista'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


   $candidatoId = $candId;

   $ofertaId = $ofertaId;
   $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

   $tipoServicio = $infoOferta[0]['tipoServicio'];
   $candidatoInfo = getInfoNameEmailUsers($candidatoId);

   $nombreVacante = $infoOferta[0]['nombreTrabajo'];
   $serialVacante = $infoOferta[0]['serialOferta'];

       print_r(array(
        'data' => $data,
        'info' => $infoOferta,
        'cand' => $candidatoInfo
    ));
    // return;

   $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

   // candidato
    $msj = 'Hemos calificado tus Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. <br><br>

    Lamentamos informarte que tu calificación es negativa, por lo tanto no cumples para la entrevista';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pruebas Psico laborales <strong>RECHAZADAS</strong> por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => $candidatoInfo['email'],
        'usuarioMuestra' => $candidatoInfo['id']
    );
    saveNotification($mensaje);

    // parte Admin
    $msj = 'Hemos calificado de forma negativa las Pruebas Psico laborales de <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Pruebas Psico laborales <strong>RECHAZADAS</strong> de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'testInfo',
        'email' => '',
        'usuarioMuestra' => 'Tsoluciono'
    );
    saveNotification($mensaje);

    }


    // print_r($data);

}


function dbprocessbeginInterviewCycle($data){

    global $wpdb;
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $contratistaId = $data['id'];
    $idOferta = $data['idOferta'];
    $etapa = $data['etapa'];

    $fechaCreacion = '-';
    $fechaPautado = '-';
    $estado = 'Sin entrevista programada';
    $hora = '-';
    $tipoEntrevista = '-';
    $aprobado = 0;
    $datoEntrevista = '-';
    $nota = '-';
    $resultadosEntrevista = '-';
    $confirmaFecha = '-';


    $seleccionados = $wpdb->get_results("SELECT * from $entrevistasTabla where ofertaId = '$idOferta'", ARRAY_A);




    // return;
    foreach ($seleccionados as $key => $value) {

        $entrevistaId = $value['id'];


        try {
            $wpdb->query(" UPDATE $entrevistasTablaEtapas SET fechaCreacion = '$fechaCreacion', fechaPautado = '$fechaPautado', estado = '$estado', hora = '$hora', tipoEntrevista = '$tipoEntrevista', aprobado = 0, datoEntrevista = '$datoEntrevista', nota = '$nota', resultadosEntrevista = '$resultadosEntrevista', confirmaFecha = '$confirmaFecha' WHERE idEntrevista = '$entrevistaId'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";

        }


    }

    try {
        $wpdb->query(" UPDATE $entrevistasTabla SET etapa = 1, estado = 'En etapa de entrevistas' WHERE ofertaId = '$idOferta'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


}





function dbprocesssendsetInterviewCalificate($data){

    // print_r($data);
    global $wpdb;
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $tipoEntrevista = $data['tipoEntrevista'];
    $datoEntrevista = $data['datoEntrevista'];
    $date = $data['date'];
    $hora = $data['hora'];
    $notaEntrevista = $data['notaEntrevista'];
    $entrevistaId = $data['entrevistaId'];


    $confirmaFecha = array(
        'admin' => 'Confirmada',
        'candidato' => 'Pendiente'
    );

    $confirmaFecha = json_encode($confirmaFecha);

    $estado = 'En espera de la entrevista';
    $creadoEn = date('d/m/Y');




    try {
        $wpdb->query(" UPDATE $entrevistasTablaEtapas SET fechaCreacion = '$creadoEn', fechaPautado = '$date', estado = '$estado', hora = '$hora', tipoEntrevista = '$tipoEntrevista', aprobado = 0, datoEntrevista = '$datoEntrevista', nota = '$notaEntrevista', confirmaFecha = '$confirmaFecha' WHERE idEntrevista = '$entrevistaId'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


    $infoOferta = $wpdb->get_results("SELECT * from $entrevistasTabla where id = '$entrevistaId'", ARRAY_A);
    $ofertaId = $infoOferta[0]['ofertaId'];
    $candidatoId = $infoOferta[0]['candidataId'];



    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];

    $candidatoInfo = getInfoNameEmailUsers($candidatoId);

    // $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $urlReprogEntrevista = esc_url(get_permalink(get_page_by_title('Mis vacantes'))).'?pg=tab2&oid='.$ofertaId;



        $msj = 'Te hemos programado una entrevista con nosotros por la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$r['info']['date'].'</strong> y hora: <strong>'.$r['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.  <br><br>

        Confirma o solicita una reprogramación de fecha y hora <a href="'.$urlReprogEntrevista.'">AQUÍ</a>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Tienes una entrevista pendiente con administración por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newInterview',
            'email' => $candidatoInfo['email'],
            'usuarioMuestra' => $candidatoInfo['id']
        );
        saveNotification($mensaje);



        $msj = 'Se ha programado una entrevista con <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>.'.' Para la vacante  <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Se realizará en la fecha: <strong>'.$r['info']['date'].'</strong> y hora: <strong>'.$r['info']['hora'].'</strong>.';

        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Hemos programado una entrevista con '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'newEntrevista',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);



}




function getAllVacantsInfo(){

}

function getInfoOfertsReport(){

    global $wpdb;

    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $contratosTabla = $wpdb->prefix . 'contratos';
    $historialContratosTabla = $wpdb->prefix . 'historialcontratos';
    $postulacionesTabla = $wpdb->prefix . 'ofertapostulantes';
    $experiencia_contratosTabla = $wpdb->prefix . 'experiencia_contratos';
    $ofertapostulantesTabla = $wpdb->prefix . 'ofertapostulantes';

    $inform = array();

    $ofertas = $wpdb->get_results("SELECT oferta.* FROM $tablaOfertaLaboral as oferta inner join $contratosTabla as contratos on oferta.id = contratos.ofertaId inner join $historialContratosTabla as historial on contratos.id = historial.contratoId where historial.activos=1 and (historial.definitivo = 1 or historial.engarantia = 1) ", ARRAY_A);




    $ofertasEnviadas = array();

    foreach ($ofertas as $key => $value) {


        $ofertaId = $value['id'];
        $contrato = $wpdb->get_results("SELECT * FROM $contratosTabla as contrato where contrato.ofertaId='$ofertaId' ", ARRAY_A);

        $numeroPostulantes = $wpdb->get_results("SELECT * FROM $ofertapostulantesTabla as postulantes where postulantes.ofertaId = '$ofertaId' ", ARRAY_A);

        if(count($contrato) > 0){



            $contratistaId = $contrato[0]['contratistaId'];
            $candidataId = $contrato[0]['candidataId'];

            // punto a diferencia de tiempo entre publicación y contrato
            $ofertaFecha = $value['fechaCreacion'];
            $contratoFecha = $contrato[0]['fechaCreacion'];
            $diasPasados = dias_pasados($ofertaFecha, $contratoFecha);

            $candidatoId = $contrato[0]['candidataId'];
            $postulacion = $wpdb->get_results("SELECT postulacion.* FROM $postulacionesTabla as postulacion inner join $tablaOfertaLaboral as oferta on oferta.id = postulacion.ofertaId where postulacion.ofertaId = '$ofertaId' and postulacion.postulanteId = $candidatoId", ARRAY_A);
            $diasPasadosPostulacion = $postulacion[0]['fechaCreacion'];
            $contratoFecha = $contrato[0]['fechaCreacion'];
            if(isset($diasPasadosPostulacion) && isset($contratoFecha)){

                $diasPasadosPostulacion = dias_pasados($diasPasadosPostulacion, $contratoFecha);
            }else{
                $diasPasadosPostulacion = 'Hay un error';
            }

            $fuenteEnterado = $wpdb->get_results("SELECT postulacion.* FROM $postulacionesTabla as postulacion inner join $tablaOfertaLaboral as oferta on oferta.id = postulacion.ofertaId where postulacion.ofertaId = '$ofertaId' and postulacion.postulanteId = $candidatoId", ARRAY_A);



            if( $contrato[0]['definitivo'] == 1) {

                // obtener id de contrato
                $serialContrato = $contrato[0]['serialContrato'];
                $idContrato = $wpdb->get_results("SELECT * FROM $contratosTabla as contrato where contrato.serialContrato='$serialContrato' ", ARRAY_A);

                $idEntrevista = $wpdb->get_results("SELECT * FROM $entrevistasTabla as procesoContrato where procesoContrato.contratistaId = $contratistaId and procesoContrato.candidataId = $candidataId and procesoContrato.ofertaId = '$ofertaId' ", ARRAY_A);

                $idContrato = $idContrato[0]['id'];
                $idEntrevista = $idEntrevista[0]['id'];

                $contratoExperiencia = $wpdb->get_results("SELECT * FROM $experiencia_contratosTabla as experiencia  where experiencia.idFamilia = $contratistaId and experiencia.idCandidato = $candidataId and experiencia.ìdContrato = '$idContrato' and experiencia.idEntrevista = '$idEntrevista'", ARRAY_A);

                $finalExperiencia = $contratoExperiencia[0]['detallesExperiencia'];

            }elseif ( $contrato[0]['engarantia'] == 1 ) {
                # code...
                $finalExperiencia = 'Sin información';
            }else{
                $finalExperiencia = 'Sin información';
            }



            $ofertas[$key]['contratoActivo'] = $contrato[0];
            $ofertas[$key]['contratoActivo']['tiempoOcuparPuesto'] = $diasPasados;
            $ofertas[$key]['contratoActivo']['tiempoParaContratar'] = $diasPasadosPostulacion;
            $ofertas[$key]['contratoActivo']['candidatoEnterado'] = $postulacion[0]['candidatoEnterado'];
            $ofertas[$key]['contratoActivo']['finalExperiencia'] = $finalExperiencia;


        }
        $ofertas[$key]['numeroPostulantes'] = $numeroPostulantes;

    }

    return $ofertas;

}


function getAllBalancTime($year = ''){
    global $wpdb;

    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $contratosTabla = $wpdb->prefix . 'contratos';
    $historialContratosTabla = $wpdb->prefix . 'historialcontratos';


    if($year == ''){
        $d = date('d/m/Y');
        $d = tranformMeses($d);
        $year = $d['anio'];
        $year = "%/$year%";
    }else{
        $year = "%/$year%";
    }


    $ofertas = $wpdb->get_results("SELECT * FROM $tablaOfertaLaboral as oferta where oferta.fechaCreacion LIKE '$year'", ARRAY_A);

    $info = array(
        'ofertas' => $ofertas
    );

    $meses = array();

    foreach ($ofertas as $key => $value) {
        $dato = $value['fechaCreacion'];
        $transform = tranformMeses($dato);
        // si no existe el mes entonces lo añade
        $mes = $transform['mes'];

        if (!array_key_exists($mes, $meses)) {

            $meses[$mes] = array();
            if (!array_key_exists('ofertas', $meses[$mes])) {
                $meses[$mes]['ofertas'] = 0;
                $meses[$mes]['ofertas'] = $meses[$mes]['fertas'] + 1;
            }else{
                $meses[$mes]['ofertas'] = $meses[$mes]['ofertas'] + 1;
            }


        }else{

            if (!array_key_exists('ofertas', $meses[$mes])) {
                $meses[$mes]['ofertas'] = 0;
                $meses[$mes]['ofertas'] = $meses[$mes]['fertas'] + 1;
            }else{
                $meses[$mes]['ofertas'] = $meses[$mes]['ofertas'] + 1;
            }

        }

    }


    return $meses;
}

function getAllContractsInfo(){

    global $wpdb;

    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $entrevistasTablaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $contratosTabla = $wpdb->prefix . 'contratos';
    $historialContratosTabla = $wpdb->prefix . 'historialcontratos';

    $ofertas = $wpdb->get_results("SELECT * FROM $tablaOfertaLaboral ORDER BY $tablaOfertaLaboral.`fechaCreacion` DESC", ARRAY_A);

    $contratos = $wpdb->get_results("SELECT contratos.* FROM $contratosTabla as contratos INNER JOIN $historialContratosTabla as historial ON contratos.id = historial.contratoId where historial.definitivo = 1 ORDER BY contratos.`fechaCreacion` DESC", ARRAY_A);

    $a = array(
        'ofertas' => $ofertas,
        'contratos' => $contratos
    );

        return $a;


}

function getAllReviewsInfo(){

}

function dbprocessrefreshInfoAddCands($data){

    global $wpdb;
    global $wp_roles;


    $usermeta = $wpdb->prefix . 'usermeta';
    $ofertalaboral = $wpdb->prefix . 'ofertalaboral';
    $usuarios_recomendados = $wpdb->prefix . 'usuarios_recomendados';
    $proceso_contrato_etapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $proceso_contrato = $wpdb->prefix . 'proceso_contrato';


    $nombre = $data['nombre'];
    $departamentos = $data['departamentos'];
    $servicio = $data['servicio'];
    $recomendabilidad = $data['recomendabilidad'];
    $tipo = (isset($data['tipo']) && $data['tipo'] != null) ? $data['tipo'] : null;


    $inners = "";
    $wheres = " where ";
    $consulta = "SELECT recomendados.* FROM $usuarios_recomendados as recomendados";
    $conector = 0;
    $ofertaId = $data['ofertaId'];
    unset($data['ofertaId']);
    unset($data['undefined']);

    $conector = count($data) - 1;

    if($tipo == null && $tipo != 'todos'){

    if($data['departamentos'] != null || $data['servicio'] ){
        $inners = " inner JOIN $ofertalaboral as oferta on recomendados.idOferta = oferta.id";
    }

    if($data['recomendabilidad'] != null){
        $inners .= " inner JOIN $proceso_contrato_etapas as etapas on recomendados.idEntrevista = etapas.idEntrevista";
        // $inners .= " inner JOIN $proceso_contrato_etapas as etapas on recomendados.idEntrevista = etapas.idEntrevista";
    }

    if($data['servicio'] != null){
        $wheres .= " oferta.tipoServicio = '$servicio'";
        if($conector > 0){
        $wheres .= ' and';
        $conector = $conector - 1;

        }
    }

    if($data['departamentos'] != null){
        $wheres .= " oferta.departamento = '$departamentos'";
        if($conector > 0){

        $wheres .= ' and';
        $conector = $conector - 1;

        }
    }
    if($data['recomendabilidad'] != null){
        $reco = '%"recomendabilidad":"'.$recomendabilidad.'"%';
        $wheres .= " etapas.resultadosEntrevista LIKE '$reco'";
        if($conector > 0){
        $wheres .= ' and';
        $conector = $conector - 1;

        }
    }
    if($data['nombre'] != null){
        $wheres .= " recomendados.idCandidato = (SELECT meta.user_id FROM $usermeta AS meta WHERE meta.meta_value LIKE '$nombre')";
    }


    $consultx = $consulta.$inners.$wheres;
   }
   if($tipo == 'todos'){
       $consultx = $consulta;
   }

    $aei = $ofertaId;

    $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
    $seleccionados = $wpdb->get_results("SELECT proceso.* from $proceso_contrato as proceso where proceso.ofertaId = '$aei' ", ARRAY_A);
    $wpdb->flush();
    $nuevaConsulta = $wpdb->get_results("$consultx", ARRAY_A);
    $wpdb->flush();

    // return;
    foreach ($seleccionados as $key => $value) {
        # code...
        $idd = $value['candidataId'];
        foreach ($nuevaConsulta as $key2 => $value2) {
            # code...}
            $idd2 = $value2['idCandidato'];
            if($idd == $idd2){
                unset($nuevaConsulta[$key2]);
            }
        }
    }


    ?>


  <form action="" method="post" class="formData">

<div class="row">
  <div class="field col form-group candidatos">
    <label for="servicio">Candidatos entrevistados previamente</label>
    <select class="form-control form-control-sm" name="candidatos">

    <option value="">Selecciona un candidato</option>

    <?php foreach ($nuevaConsulta as $key => $value) {

      $candidatoId = $value['idCandidato'];
      $entrevistaId = $value['idEntrevista'];
      $fechaRecomendado = $value['fechaRecomendado'];

      // wp_proceso_contrato_etapas
      $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
      $d = $wpdb->get_results("SELECT * from $tabla WHERE idEntrevista='$entrevistaId'", ARRAY_A);
      $wpdb->flush();

      $reco = $d[0]['resultadosEntrevista'];
      $reco = json_decode($reco, true);

      $expectativas = $reco['cumpleCandidato'];
      $recoNivel = $reco['recomendabilidad'];

      // datos de la vacante
      $usuario = get_user_meta($candidatoId);

      $nombreFamilia = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

      $u = get_userdata($candidatoId);

      $role = array_shift($u->roles);
      $rolCandidata = $wp_roles->roles[$role]['name'];


      $nombreFamilia .= ' ' . '(' . $rolCandidata . ')';

      $v = array(
        'ci' => $candidatoId,
        'ei' => $entrevistaId,
        'aei' => $aei
      );

      $v = json_encode($v);

      $x = 'Fecha: '.$fechaRecomendado.' - '.$nombreFamilia.' - Expectativas: '.$expectativas.' - Recomendado: '.$recoNivel;
      ?>

        <option value='<?php echo $v ?>'>
          <small>
          <?php echo $x; ?>
          </small>
        </option>

    <?php  } ?>
    </select>
    <small class="validateMessage"></small>
  </div>
</div>

</form>



<?php
}

function dbprocessdetailsBalancOfferInfo($data){
    global $wpdb;
    global $wp_roles;

    $ofertas = getInfoOfertsReport();
    $oferta = '';
    $ofertaId = $data['idOferta'];
    foreach ($ofertas as $key => $value) {

        if($value['id'] == $ofertaId){
            $oferta = $ofertas[$key];
        }

    }

    if(isset($oferta) && (count($oferta) > 0)){
        $numeroPostulantes = $oferta['numeroPostulantes'];

        $colorsEnterado = colorsEnterado();

        $cuentasPostulantes = array();

        foreach ($numeroPostulantes as $key => $value) {

            $candidatoEnterado = $value['candidatoEnterado'];

            if( $candidatoEnterado == 'Un amigo'){
                $cuentasPostulantes[$candidatoEnterado]++;
            }
            if( $candidatoEnterado == 'Redes sociales'){
                $cuentasPostulantes[$candidatoEnterado]++;
            }
            if( $candidatoEnterado == 'Pagina de la empresa'){
                $cuentasPostulantes[$candidatoEnterado]++;
            }
            if( $candidatoEnterado == 'Otra forma'){
                $cuentasPostulantes[$candidatoEnterado]++;
            }
        }


        $experiencia = $oferta['contratoActivo']['finalExperiencia'];
        ?>
    <div class="container">
        <div class="row experienca">
            <h6 style="text-align: center;width: 100%">
                Satisfacción de la contratación
            </h6>
            <p style="text-align: center;width: 100%">
                <?php echo $experiencia; ?>
            </p>
        </div>
        <div class="row solicitantesTotal">
            <h6 style="text-align: center;width: 100%">
                Fuente de Contratación de usuarios
            </h6>

            <canvas id="graficosolicitantesTotal" class="graficoDona"></canvas>

            <script>
var ctx = document.getElementById('graficosolicitantesTotal');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            <?php  foreach ($cuentasPostulantes as $key => $value) {
                echo "'".$key."',";
             } ?>
        ],
        datasets: [{
            label: 'Usuarios',
            data: [
                <?php foreach ($cuentasPostulantes as $key => $value1) {
                echo $value1.',';
              } ?>
            ],
            backgroundColor: [
                <?php  foreach ($cuentasPostulantes as $key => $value) {
                echo "'". $colorsEnterado[$key]."',";
             } ?>
            ],
            borderColor: [
                <?php  foreach ($cuentasPostulantes as $key => $value) {
                echo "'". $colorsEnterado[$key]."',";
             } ?>
            ],
            borderWidth: 5
        }]
    },

});
</script>

        </div>
    </div>
    <?php
    }else{ ?>
        <div class="container">
        <h6>
                Sin información
            </h6>
        </div>
    <?php }

}


function dbcreatePromoAnounceProcess($data){

    global $wpdb;
    global $wp_roles;

    if (!isset($data['cambio']) && $data['cambio'] != true) {
        # code...
        $fotosOferta = $data['imagen'];
        $datos = $data['data'];

        $imagenes = imagesToArray($fotosOferta);
        $idOferta = uniqid().uniqid();
    $fechaCreado = date('d/m/Y');

    $nombreFamilia = 'Tsolucionamos';

    $iii = array(
        'imagenes' => $imagenes,
        'carpeta' => '/publicaciones',
        'serial' => $idOferta,
    );
    $imagenesJson = cargarImagenes($iii);

    // return;
    $datos = array(
        'id' => sanitize_text_field($idOferta),
        'contratistaId' => get_current_user_id(),
        'estado' => sanitize_text_field('Anuncio en circulación'),
        'fechaCreacion' => sanitize_text_field($fechaCreado),
        'gestion' => sanitize_text_field('Gestionado por administración'),
        'fechaInicio' => sanitize_text_field($datos['fechaInicio']),
        'fechaFin' => sanitize_text_field($datos['fechaFin']),
        'nombreTrabajo' => sanitize_text_field($datos['titulo']),
        'cargo' => sanitize_text_field($datos['cargo']),
        'nombreFamilia' => sanitize_text_field($nombreFamilia),
        'direccion' => sanitize_text_field($datos['direccion']),
        'pais' => sanitize_text_field($datos['pais']),
        'departamento' => '-',
        'ciudad' => '-',
        'sueldo' => '-',
        'horario' => '-',
        'tipoServicio' => sanitize_text_field($datos['servicio']),
        'descripcionExtra' => sanitize_text_field($datos['descripcion']),
        'firmaCandidata' => '-',
        'serialOferta' => sanitize_text_field(uniqid('O-', true)),
        'contratoTerminosPublicacion' => '-',
        'aceptaTerminosContrato' => 1,
        'aceptaTerminosPublicacion' => 1,
        'publico' => 1,
        'imagenes' => sanitize_text_field($imagenesJson),
        'tipoPublic' => 'Promoción'
    );

    $formato = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $tabla = $wpdb->prefix . 'ofertalaboral';

    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();
    }else{
        $fotosOferta = $data['imagen'];
        $datos = $data['data'];

        // print_r($data);

        $serialOferta = $datos['idAnuncio'];
        $serialOferta = stripslashes($serialOferta);

        unset($datos['idAnuncio']);
        unset($datos['action']);


        if(isset($fotosOferta) && $fotosOferta['imagenPrincipal']['name'] != ''){
            $imagenes = imagesToArray($fotosOferta);

            $iii = array(
                'imagenes' => $imagenes,
                'carpeta' => '/publicaciones',
                'serial' => $idOferta,
            );

            $imagenesJson = cargarImagenes($iii);

        }else{
            $imagenesJson = '';
        }

        $rrr = array(
            'fechaInicio' => $datos['fechaInicio'],
            'fechaFin' => $datos['fechaFin'],
            'nombreTrabajo' => $datos['titulo'],
            'descripcionExtra' => $datos['descripcion'],
            'imagenes' => $imagenesJson
        );


        foreach ($rrr as $key => $value) {
               if(!next($rrr)){
                    $UpdateStr .= ($value != '')? "$key = '$value'" : ' ';
                }else{
                    $UpdateStr .= ($value != '')? "$key = '$value', ": ' ';
                }
        }

        if(isset($UpdateStr) && $UpdateStr != ''){

            $tabla = $wpdb->prefix . 'ofertalaboral';
            print_r("UPDATE $tabla SET $UpdateStr WHERE serialOferta='$serialOferta'");
            $wpdb->query("UPDATE $tabla SET $UpdateStr WHERE serialOferta='$serialOferta'");

        }

    }

}

//
    // [candidatoId] => 20
    // [idEntrevista] => 5e853e23ea4205e853e23ea423

function dbintegrateNewPostulateByAnounce($data){

    global $wpdb;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaEntrevista = $wpdb->prefix . 'proceso_contrato';

    $candidatoId = $data['candidatoId'];
    $idEntrevista = $data['idEntrevista'];
    // vacantes sin entrevistas

$sinEntrevistas = $wpdb->get_results("SELECT oferta.* from $tablaOferta as oferta WHERE (oferta.tipoPublic IS NULL or oferta.tipoPublic != 'Promoción') and oferta.id NOT IN (SELECT ofertaId FROM $tablaEntrevista)", ARRAY_A);
    $wpdb->flush();
    // Con entrevistas pero donde no esta este candidato
$conEntrevistas = $wpdb->get_results("SELECT oferta.*, entrevista.* from $tablaOferta as oferta inner join $tablaEntrevista as entrevista on (oferta.id = entrevista.ofertaId) WHERE (oferta.tipoPublic IS NULL or oferta.tipoPublic != 'Promoción')", ARRAY_A);
    $wpdb->flush();

    $exclu = array();
    foreach ($conEntrevistas as $key => $value) {
        if($value['candidataId'] == $candidatoId){
        $l = $value['ofertaId'];
        $l = "'$l'";
        array_push($exclu, $l);
        }
    }
    if(count($exclu) > 0){
    $exclu = implode(",",$exclu);

    $conEntrevistas = $wpdb->get_results("SELECT oferta.*, entrevista.* from $tablaOferta as oferta inner join $tablaEntrevista as entrevista on (oferta.id = entrevista.ofertaId) WHERE (oferta.tipoPublic IS NULL or oferta.tipoPublic != 'Promoción') AND entrevista.ofertaId NOT IN ($exclu)", ARRAY_A);
    $wpdb->flush();

    }

    ?>

     <div class="field col form-group addByAnounce">
        <label for="addByAnounce"></label>
        <select class="form-control form-control-sm" name="addByAnounce">

            <?php if(count($sinEntrevistas) > 0){ ?>
                <option disabled><strong>Vacantes sin entrevistas</strong></option>
                <?php foreach ($sinEntrevistas as $key => $value) {

                    $ofertaId = $value['id'];
                    $nombreVacante = $value['nombreTrabajo'];
                    $tipoServicio = $value['tipoServicio'];

                 ?>
                    <option value="<?php echo $ofertaId ?>">
                        <?php echo 'Vacante: ' .$nombreVacante. ', Servicio: ' .$tipoServicio ?>
                    </option>
                <?php } ?>
            <?php } ?>

            <?php if(count($conEntrevistas) > 0){ ?>
                <option disabled>Vacantes con entrevistas</option>

                 <?php foreach ($conEntrevistas as $key => $value) {

                    $ofertaId = ($value['ofertaId'] != null && $value['ofertaId'] != '')? $value['ofertaId']: $value['id'];
                    $nombreVacante = $value['nombreTrabajo'];
                    $tipoServicio = $value['tipoServicio'];

                 ?>
                    <option value="<?php echo $ofertaId ?>">
                        <?php echo 'Vacante: ' .$nombreVacante. ', Servicio: ' .$tipoServicio ?>
                    </option>
                <?php } ?>
            <?php } ?>

            <?php if(count($sinEntrevistas) == 0 && count($conEntrevistas) == 0){ ?>
                <option value="null">Sin vacantes disponibles</option>
            <?php } ?>

        </select>

      </div>

    <?php
}



function dbprocesssendintegrateNewPostulateByAnounce($data){

    global $wpdb;
    $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';


    $ci = $data['candidatoId'];
    $ei = $data['idEntrevistaInyect'];
    $aei = $data['addByAnounce'];

    // obtener entrevista original y etapas de entrevista para traerlas al proceso deseado a la integración
    $entrevista = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where id = '$ei'", ARRAY_A);
    $entrevista = $entrevista[0];

    $tipoEntrevista = $entrevista['tipoEntrevista'];

    $etapas = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$ei'", ARRAY_A);
    $etapas = $etapas[0];

    $tipoEntrevista = $etapas['tipoEntrevista'];
    $aprobado = $etapas['aprobado'];
    $aprobado = 0;
    $datoEntrevista = $etapas['datoEntrevista'];
    $nota = $etapas['nota'];
    $resultadosEntrevista = $etapas['resultadosEntrevista'];
    // $confirmaFecha = $etapas['confirmaFecha'];
    $confirmaFecha = array(
        'admin' => 'Confirmada',
        'candidato' => 'Pendiente'
    );

    $confirmaFecha = json_encode($confirmaFecha);
    $pruebasPsico = $etapas['pruebasPsico'];
    $resultadosPruebasPsico = $etapas['resultadosPruebasPsico'];


    // obtener información del proceso de entrevistas al que queremos anexar al candidato
    $entrevistaAnexar = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas where ofertaId = '$aei'", ARRAY_A);
    if(isset($entrevistaAnexar) && count($entrevistaAnexar) > 0){

    $idEntrevistaAnexar = $entrevistaAnexar[0]['id'];

    $etapasAnexar = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$idEntrevistaAnexar'", ARRAY_A);
    // $etapasAnexar = $etapasAnexar[0];

    $famIdAnexar = $entrevistaAnexar[0]['contratistaId'];
    $ofertaIdAnexar = $entrevistaAnexar[0]['ofertaId'];
    $etapaAnexar = $entrevistaAnexar[0]['etapa'];

    $horaAnexar = $entrevistaAnexar[0]['hora'];
    $estadoAnexar = $entrevistaAnexar[0]['estado'];

    // print_r($entrevistaAnexar);
    // return;
    }else{
        $entrevistaAnexar = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$aei'", ARRAY_A);

        $famIdAnexar = $entrevistaAnexar[0]['contratistaId'];
        // $ofertaIdAnexar = $entrevistaAnexar[0]['ofertaId'];
        $etapaAnexar = 0;
        $estadoAnexar = 'En etapa de selección';
        $horaAnexar = 'S.H';

    }
    // ahora se integran en el proceso deseado
    // cargar datos
    $id = uniqid() . uniqid();
    $datos1 = array(
        'id' => sanitize_text_field($id),
        'contratistaId' => sanitize_text_field($famIdAnexar),
        'candidataId' => sanitize_text_field($ci),
        'ofertaId' => sanitize_text_field($aei),
        'etapa' => sanitize_text_field($etapaAnexar),
        'estado' => sanitize_text_field($estadoAnexar)
    );
    $formato1 = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $datos2 = array(
        // 'id' => ,
        'idEntrevista' => sanitize_text_field($id),
        'fechaCreacion' => date('d/m/Y'),
        'fechaPautado' => 'Adicional',
        'hora' => 'S.H',
        'estado' => sanitize_text_field($estadoAnexar),
        'tipoEntrevista' => 'Añadido al proceso de entrevistas',
        'aprobado' => sanitize_text_field($aprobado),
        'datoEntrevista' => sanitize_text_field($datoEntrevista),
        'nota' => sanitize_text_field($nota),
        'resultadosEntrevista' => sanitize_text_field($resultadosEntrevista),
        'confirmaFecha' => sanitize_text_field($confirmaFecha),
        'pruebasPsico' => sanitize_text_field($pruebasPsico),
        'resultadosPruebasPsico' => sanitize_text_field($resultadosPruebasPsico)
    );

    $formato2 = array(

        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    // print_r($datos1);
    // print_r($datos2);

    $wpdb->insert($tablaprocesoEntrevistas, $datos1, $formato1);
    $wpdb->flush();
    $wpdb->insert($tablaprocesoEntrevistasEtapas, $datos2, $formato2);
    $wpdb->flush();

}

function dbprocesssendchangeState($data){

    global $wpdb;
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $serialOferta = $data['id'];
    $estado = $data['estado'];

    $cambio = ($estado == 1)? 0: 1;
    try {
        $wpdb->query("UPDATE $tablaOfertaLaboral SET publico=$cambio WHERE serialOferta='$serialOferta'");
} catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

function dbsendstateAnounce($data){

    global $wpdb;
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';


}