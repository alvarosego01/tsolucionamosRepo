
<?php

// CARGAS A LA BASE DE DATOS
function createOfferLaboral($data)
{

    global $wpdb;

    $datos = array(
        'id' => sanitize_text_field($data['id']),
        'contratistaId' => sanitize_text_field($data['contratistaId']),
        'estado' => sanitize_text_field('En etapa de postulaciones'),
        'fechaCreacion' => sanitize_text_field($data['fechaCreacion']),
        'gestion' => sanitize_text_field('Gestionado por administración'),
        'fechaInicio' => sanitize_text_field($data['fechaInicio']),
        'fechaFin' => sanitize_text_field($data['fechaFin']),
        'nombreTrabajo' => sanitize_text_field($data['titulo']),
        'cargo' => sanitize_text_field($data['cargo']),
        'nombreFamilia' => sanitize_text_field($data['nombreFamilia']),
        'direccion' => sanitize_text_field($data['direccion']),
        'pais' => sanitize_text_field($data['pais']),
        'departamento' => sanitize_text_field($data['departamento']),
        'ciudad' => sanitize_text_field($data['ciudad']),
        'sueldo' => sanitize_text_field($data['sueldo']),
        'horario' => sanitize_text_field($data['horario']),
        'tipoServicio' => sanitize_text_field($data['servicio']),
        'descripcionExtra' => sanitize_text_field($data['direccion']),
        'firmaCandidata' => sanitize_text_field($data['jsonFirmaContratista']),
        'serialOferta' => sanitize_text_field(uniqid()),
        'publico' => 1,
    );

    $datos2 = array(
        'idOferta' => sanitize_text_field($data['id']),
        'fechaLlamada' => sanitize_text_field($data['fechaCreacion']),
        'publicar' => 1,
        'aprobado' => 0,
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
    );

    $formato2 = array(
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $tabla = $wpdb->prefix . 'ofertalaboral';
    $tablaAdminOffer = $wpdb->prefix . 'admin_vacantes_familia';

    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();

    $wpdb->insert($tablaAdminOffer, $datos2, $formato2);
    // print_r($datos2);
    die();
}

// carga en base de datos un nuevo postulante en ofeerta
function dbCreatePostulation($data)
{
    global $wpdb;

    $datos = array(
        'postulanteId' => sanitize_text_field($data['idCanidata']),
        'ofertaId' => sanitize_text_field($data['idOferta']),
        'mensaje' => sanitize_text_field($data['mensaje']),
        'candidatoEnterado' => sanitize_text_field($data['enterado']),
        'fechaCreacion' => date('d/m/Y')
    );
    $formato = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );
    $tabla = $wpdb->prefix . 'ofertapostulantes';

    // print_r($datos);

    $wpdb->insert($tabla, $datos, $formato);

    die();
}

function dbDeletePostulation($data)
{
    global $wpdb;
    $idOferta = sanitize_text_field($data['idOferta']);
    $idCandidata = sanitize_text_field($data['idCanidata']);

    // print_r($data);

    $tabla = $wpdb->prefix . 'ofertapostulantes';
    $tablaHistorial = $wpdb->prefix . 'historialcontratos';

    try {
        $wpdb->query("DELETE FROM $tabla WHERE postulanteId = $idCandidata AND ofertaId = '$idOferta'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

function dbDeleteOfferLaboral($data)
{

    global $wpdb;

    $idOferta = sanitize_text_field($data['idOferta']);
    $idFamilia = sanitize_text_field($data['idFamilia']);
    $serialOferta = sanitize_text_field($data['serialOferta']);

    $tabla = $wpdb->prefix . 'ofertalaboral';

    try {
        $wpdb->query("DELETE FROM $tabla WHERE contratistaId = $idFamilia AND id = '$idOferta' AND serialOferta = '$serialOferta'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

function dbGetAllMyPostulantions($data, $pagController = '')
{
    global $wpdb;

    $tabla1 = $wpdb->prefix . 'ofertapostulantes';
    $tabla2 = $wpdb->prefix . 'ofertalaboral';

    $id = $data['id'];


    if(isset($pagController) && $pagController != ''){

        $perPage = $pagController['porPagina'];
        // $perPage = 1;
        $pageno = $pagController['pg'];
        $offset = ($pageno-1) * $perPage;
        $filtroPor = 'todos';

        if($filtroPor == 'todos'){

            $data = $wpdb->get_results("SELECT * from $tabla1 AS postulantes INNER JOIN $tabla2 AS oferta ON postulantes.ofertaId = oferta.id where postulantes.postulanteId = $id", ARRAY_A);

            $total_rows = count($data);
            $total_pages = ceil($total_rows / $perPage);

            $data = $wpdb->get_results("SELECT * from $tabla1 AS postulantes INNER JOIN $tabla2 AS oferta ON postulantes.ofertaId = oferta.id where postulantes.postulanteId = $id LIMIT $perPage OFFSET $offset", ARRAY_A);

            $v = array(
                'pageno' => $pageno,
                'total_pages' => $total_pages,
                'filterBy' => 'todos'
            );

            $data['pageData'] = $v;

            return $data;

        }


    }else{

        $pageno = 1;
        $perPage = 9;
        $offset = 0;

        $data = $wpdb->get_results("SELECT * from $tabla1 AS postulantes INNER JOIN $tabla2 AS oferta ON postulantes.ofertaId = oferta.id where postulantes.postulanteId = $id", ARRAY_A);

        $total_rows = count($data);
        $total_pages = ceil($total_rows / $perPage);

        $data = $wpdb->get_results("SELECT * from $tabla1 AS postulantes INNER JOIN $tabla2 AS oferta ON postulantes.ofertaId = oferta.id where postulantes.postulanteId = $id LIMIT $perPage OFFSET $offset", ARRAY_A);

        $v = array(
            'pageno' => $pageno,
            'total_pages' => $total_pages,
            'filterBy' => 'todos'
        );

        $data['pageData'] = $v;


        return $data;
    }
}

// se valida el dueño de la oferta laboral
function dbGetValidateOwnerOffer($data)
{
    global $wpdb;

    $tabla1 = $wpdb->prefix . 'ofertalaboral';

    $id = um_user('ID');
    $currentId = get_current_user_id();

    $serial = $data;
    $data = false;

    $data = $wpdb->get_results("SELECT count(*) FROM $tabla1 where contratistaId = $currentId AND serialOferta = '$serial'", ARRAY_A);

    return $data;
}

// se valida si la vacante laboral ha sido tomada, entonces para eliminar al postulante y cancelar la oferta de contrato
function dbGetOptionsPostulantOffer($serial, $postulant)
{

    global $wpdb;
    $tabla1 = $wpdb->prefix . 'proceso_contrato';
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';

    $id = um_user('ID');
    $currentId = get_current_user_id();

    $c = array(
        'serial' => $serial,
        'postulant' => $postulant,
    );
    // print_r($c);
    $data = false;

    // se obtiene el id de la oferta
    $data = $wpdb->get_results("SELECT id FROM $tablaOferta WHERE serialOferta = '$serial'", ARRAY_A);
    $ofertaId = $data[0]['id'];

    $e1 = $wpdb->get_results("SELECT count(*) FROM $tabla1 WHERE ofertaId = '$ofertaId' and candidataId=$postulant", ARRAY_A);
    $b = $e1[0]['count(*)'];
    // si no existe alguien seleccionado entonces
    if ($b == 0) {
        return $opc = '<div class="opc"><div class="buttonCustom"><button class="btn btn-primary" onclick="sendAdminSelectPostulant(' . $postulant . ', \'' . $serial . '\')"><i class="fa fa-file-text-o" aria-hidden="true"></i>Seleccionar candidato</button></div></div>';
    } elseif ($b > 0) {
        // osea si alguien ya fue seleccionado entonces la modalidad es esta.

        $e1 = $wpdb->get_results("SELECT count(*) FROM $tabla1 WHERE candidataId = $postulant AND ofertaId = '$ofertaId'", ARRAY_A);
        $b2 = $e1[0]['count(*)'];
        // si el que ingresa fue el seleccionado entonces.
        if ($b2 > 0) {
            $pagina = esc_url(get_permalink(get_page_by_title('Información de contrato')));

            // informacion-de-contrato
            return $opc = '<div class="opc">
                <div class="buttonCustom"><button class="btn btn-danger" onclick="sendDeleteAdminSelectPostulant(' . $postulant . ', \'' . $serial . '\')"><i class="fa fa-trash" aria-hidden="true"></i>Cancelar proceso</button>
                </div>

                </div>';
        } elseif ($b == 0) {
            // si el que entra NO fue seleccionado entonces
            return $opc = '';
        }

    }
}

function dbGetAllOfferInfo($data)
{
    global $wpdb;

    $tabla1 = $wpdb->prefix . 'ofertalaboral';

    $serial = $data;

    $data = $wpdb->get_results("SELECT * from $tabla1 WHERE serialOferta='$serial'", ARRAY_A);

    return $data;
}

function dbEditOfferInfo($data)
{

    global $wpdb;

    $tabla = $wpdb->prefix . 'ofertalaboral';

    $serial = $data['serialEdit'];

    $nombreTrabajo = sanitize_text_field($data['titulo']);
    $cargo = sanitize_text_field($data['cargo']);
    $direccion = sanitize_text_field($data['direccion']);
    $pais = sanitize_text_field($data['pais']);
    $departamento = sanitize_text_field($data['departamento']);
    $ciudad = sanitize_text_field($data['ciudad']);
    $sueldo = sanitize_text_field($data['sueldo']);
    $tipoServicio = sanitize_text_field($data['servicio']);
    $descripcionExtra = sanitize_text_field($data['descripcion']);

    try {
        $wpdb->query(" UPDATE $tabla SET nombreTrabajo='$nombreTrabajo', cargo='$cargo', direccion='$direccion', pais='$pais', departamento='$departamento',ciudad='$ciudad', sueldo=$sueldo, tipoServicio='$tipoServicio',descripcionExtra='$descripcionExtra' WHERE serialOferta='$serial'");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}


function dbSelectPostulantContrat($data)
{



    // $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    // $idOferta = $dataEntrada['ofertaId'];
    // $gg = $wpdb->get_results("SELECT serialOferta FROM $tablaOferta WHERE id = '$idOferta'", ARRAY_A);
    // $serial = $gg[0]['serialOferta'];

    global $wpdb;

    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaProcesoContrato = $wpdb->prefix . 'proceso_contrato';
    $idOferta = $data['idOferta'];
    $postuladoID = $data['idPostulan'];

    $publico = 0;

    $datos = array(
        'id' => sanitize_text_field($data['id']),
        'postuladoID' => sanitize_text_field($data['idPostulan']),
        'ofertaId' => sanitize_text_field($idOferta),
        'vistoXPostulado' => sanitize_text_field($data['visto']),
        'estado' => sanitize_text_field($data['estado']),
    );
    $formato = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );



    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    // para editar el estado de la oferta laboral y de la oferta

    try {

        $wpdb->query("UPDATE $tablaOferta SET publico=$publico WHERE id='$idOferta'");

        $wpdb->query("UPDATE $tablaProcesoContrato SET etapa=3 WHERE ofertaId='$idOferta'");
//
        $wpdb->insert($tablaEstado, $datos, $formato);




            // $procesoContrato = $wpdb->get_results("SELECT * from $tablaProcesoContrato where ofertaId = '$idOferta' and candidataId = $postuladoID", ARRAY_A);
            // $candidatoId = $procesoContrato[0]['candidataId'];
            // $contratistaId = $procesoContrato[0]['contratistaId'];
            // $can = $candidatoId;
            // $fam = $contratistaId;
            // $ofertaId = $idOferta;

            // print_r($procesoContrato);

        //     $infoOferta = $wpdb->get_results("SELECT * from $tablaOferta where id = '$ofertaId'", ARRAY_A);
        //     $tipoServicio = $infoOferta[0]['tipoServicio'];
        //     $nombreVacante = $infoOferta[0]['nombreTrabajo'];
        //     $serialVacante = $infoOferta[0]['serialOferta'];
        //     $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

        //     $candidatoInfo = getInfoNameEmailUsers($can);
        //     $familiaInfo = getInfoNameEmailUsers($fam);

        //                 // parte candidato
        //    $msj = 'Felicidades has recibido una propuesta de contrato por la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Lee atentamente el contrato <a href="#" class="resalte1">AQUI</a>.';
        //    $mensaje = array(
        //        'mensaje' => $msj,
        //        'subject' => 'Felicidades has recibido una propuesta de contrato por la vacante: '.$nombreVacante,
        //        'estado' => 0,
        //     // 'fecha' => ,
        //        'tipo' => 'sendContractProposal',
        //        'email' => $candidatoInfo['email'],
        //        'usuarioMuestra' => $candidatoInfo['id']
        //    );
        //    saveNotification($mensaje);
        //                 // parte candidato
        //    $msj = 'Administración ha enviado una propuesta de contrato al candidato <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> por tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Te mantendremos informado. ';
        //    $mensaje = array(
        //        'mensaje' => $msj,
        //        'subject' => 'Propuesta de contrato enviada para <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> la vacante: '.$nombreVacante,
        //        'estado' => 0,
        //     // 'fecha' => ,
        //        'tipo' => 'sendContractProposal',
        //        'email' => $familiaInfo['email'],
        //        'usuarioMuestra' => $familiaInfo['id']
        //    );
        //    saveNotification($mensaje);
        //    // parte Administracion
        //    $msj = 'Hemos enviado una propuesta de contrato al candidato <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> por la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>.';

        //    $mensaje = array(
        //        'mensaje' => $msj,
        //        'subject' => 'Enviamos una propuesta de contrato a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> por la vacante  publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>',
        //        'estado' => 0,
        //     // 'fecha' => ,
        //        'tipo' => 'sendContractProposal',
        //        'email' => '',
        //        'usuarioMuestra' => 'Tsoluciono'
        //    );
        //    saveNotification($mensaje);


    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

function dbDeleteSelectPostulantContrat($data)
{

    global $wpdb;
    $idOferta = $data['idOferta'];
    $idPostulan = $data['idPostulan'];

    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    // para editar el estado de la oferta laboral y de la oferta
    // [idPostulan] => 9
    // [serial] => 5c7c1dcc50eaa
    // [idOferta] => 5c7c1dcc50e2b5c7c1dcc50e2e

    try {
        $wpdb->query("DELETE FROM $tablaEstado WHERE ofertaId = '$idOferta' AND postuladoID = $idPostulan");
        $wpdb->query(" UPDATE $tablaOferta SET publico=1 WHERE id='$idOferta'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}
function dbGetNumberPostulants($data)
{

    $a = $data['serialOferta'];
    $b = $data['id'];

    $info = $wpdb->get_results("SELECT * from $tablaOfertaPostulantes where serialOferta='$a' AND ofertaId = '$b'", ARRAY_A);
    $wpdb->flush();

}

function dbGetInfoMyVacantTab2($data, $pagController = '')
{

    global $wpdb;
    $id = $data;
    $currentId = $id;
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaOfertaPostulantes = $wpdb->prefix . 'ofertapostulantes';
    $procesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
    $procesoEntrevistaEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaContratos = $wpdb->prefix . 'contratos';
    $tablaHistorialContratos = $wpdb->prefix . 'historialcontratos';
    $propuestaContratos = $wpdb->prefix . 'estadoofertalaboral ';

    if (validateUserProfileOwner($currentId, $currentId, 'familia')) {

        $datos = array();

        // Array ( [porPagina] => 5 [filterBy] => todos [data] => postInterviews [pg] => 1 )
        if(isset($pagController) && $pagController != ''){

            $perPage = $pagController['porPagina'];
            // $perPage = 1;
            $pageno = $pagController['pg'];
            $offset = ($pageno-1) * $perPage;
            $filtroPor = $pagController['filterBy'];

            if($filtroPor == 'dateAsc'){

                $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id ", ARRAY_A);
                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);
                $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id ORDER BY `id` ASC LIMIT $perPage OFFSET $offset", ARRAY_A);
                $wpdb->flush();
                // print_r($seriales);
            }
            if($filtroPor == 'dateDesc'){

                 $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id ", ARRAY_A);
                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);
                $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id ORDER BY `id` DESC LIMIT $perPage OFFSET $offset", ARRAY_A);
                $wpdb->flush();

            }
            if($filtroPor == 'withCt'){



                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id GROUP BY ofertaId", ARRAY_A);

                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);


                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id GROUP BY ofertaId LIMIT $perPage OFFSET $offset ", ARRAY_A);


            }
            if($filtroPor == 'noCt'){

                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id and contratos.etapa != 4 GROUP BY ofertaId", ARRAY_A);

                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);


                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id and contratos.etapa != 4 GROUP BY ofertaId LIMIT $perPage OFFSET $offset ", ARRAY_A);

            }
            if($filtroPor == 'leftIntw'){

                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id and contratos.etapa < 3 GROUP BY ofertaId", ARRAY_A);

                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);


                $seriales = $wpdb->get_results("SELECT oferta.* FROM $tablaOferta as oferta INNER JOIN $procesoEntrevistas as contratos ON (oferta.id = contratos.ofertaId) where oferta.contratistaId = $id and contratos.etapa < 3 GROUP BY ofertaId LIMIT $perPage OFFSET $offset ", ARRAY_A);

            }
            if($filtroPor == 'todos'){

                $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id", ARRAY_A);
                $total_rows = count($seriales);
                // $total_pages_sql = count($seriales);
                // $result = mysqli_query($conn,$total_pages_sql);
                // $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $perPage);

                $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id LIMIT $perPage OFFSET $offset ", ARRAY_A);
                $wpdb->flush();

            }

            $v = array(
                'pageno' => $pageno,
                'total_pages' => $total_pages,
                'filterBy' => $filtroPor
            );

            $datos['pageData'] = $v;

        }else{

            $perPage = 5;
            $offset = 0;
            $pageno = 1;

            $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id", ARRAY_A);
            $total_rows = count($seriales);
            // $total_pages_sql = count($seriales);
            // $result = mysqli_query($conn,$total_pages_sql);
            // $total_rows = mysqli_fetch_array($result)[0];
            $total_pages = ceil($total_rows / $perPage);

            $seriales = $wpdb->get_results("SELECT * from $tablaOferta as oferta where oferta.contratistaId=$id LIMIT $perPage OFFSET $offset ", ARRAY_A);
            $wpdb->flush();



        $v = array(
            'pageno' => $pageno,
            'total_pages' => $total_pages,
            'filterBy' => $filtroPor
        );

        $datos['pageData'] = $v;

        }



        foreach ($seriales as $r) {

            $a = $r['serialOferta'];
            $b = $r['id'];
            $c = $r['contratistaId'];

            // $info = $wpdb->get_results("SELECT postulantes.* from $tablaOfertaPostulantes as postulantes inner join $procesoEntrevistas as entrevista on (postulantes.ofertaId = entrevista.ofertaId) WHERE postulantes.ofertaId='$b'", ARRAY_A);
            // $wpdb->flush();
            $info = $wpdb->get_results("SELECT entrevista.* from $procesoEntrevistas as entrevista WHERE entrevista.ofertaId='$b'", ARRAY_A);
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
    if (validateUserProfileOwner($currentId, $currentId, 'candidata')) {
        // se obtienen las vacantes donde ya tiene entrevistas pautadas
        $candidataId = $id;

        $datos = array();
        // Array ( [porPagina] => 5 [filterBy] => todos [data] => postInterviews [pg] => 1 )
        if(isset($pagController) && $pagController != ''){

            $perPage = $pagController['porPagina'];
            // $perPage = 1;
            $pageno = $pagController['pg'];
            $offset = ($pageno-1) * $perPage;
            $filtroPor = $pagController['filterBy'];

            if($filtroPor == 'todos'){

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id", ARRAY_A);
            $wpdb->flush();

            $total_rows = count($info);
            $total_pages = ceil($total_rows / $perPage);

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id LIMIT $perPage OFFSET $offset", ARRAY_A);
            $wpdb->flush();

            $v = array(
                  'pageno' => $pageno,
                  'total_pages' => $total_pages,
                  'filterBy' => $pagController['filterBy']
            );

            $datos['pageData'] = $v;

            }

            if($filtroPor == 'dateAsc'){

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id", ARRAY_A);
                $wpdb->flush();

            $total_rows = count($info);
            $total_pages = ceil($total_rows / $perPage);

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id ORDER BY `id` ASC LIMIT $perPage OFFSET $offset", ARRAY_A);
            $wpdb->flush();

            $v = array(
                  'pageno' => $pageno,
                  'total_pages' => $total_pages,
                  'filterBy' => $pagController['filterBy']
            );

            $datos['pageData'] = $v;

            }
            if($filtroPor == 'dateDesc'){

                $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id", ARRAY_A);
                $wpdb->flush();

                $total_rows = count($info);
                $total_pages = ceil($total_rows / $perPage);

                $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id ORDER BY `id` DESC LIMIT $perPage OFFSET $offset", ARRAY_A);
                $wpdb->flush();

                $v = array(
                      'pageno' => $pageno,
                      'total_pages' => $total_pages,
                      'filterBy' => $pagController['filterBy']
                );

                $datos['pageData'] = $v;

            }
            if($filtroPor == 'withCt'){

                $info = $wpdb->get_results("SELECT procesoContrato.* FROM $procesoEntrevistas as procesoContrato INNER JOIN $tablaContratos as contratos ON (procesoContrato.ofertaId = contratos.ofertaId) INNER JOIN $tablaHistorialContratos as historial ON (historial.contratoId = contratos.id) where procesoContrato.candidataId = $id and historial.activos = 1", ARRAY_A);
                $wpdb->flush();


                $total_rows = count($info);
                $total_pages = ceil($total_rows / $perPage);

                $info = $wpdb->get_results("SELECT procesoContrato.* FROM $procesoEntrevistas as procesoContrato INNER JOIN $tablaContratos as contratos ON (procesoContrato.ofertaId = contratos.ofertaId) INNER JOIN $tablaHistorialContratos as historial ON (historial.contratoId = contratos.id) where procesoContrato.candidataId = $id and historial.activos = 1 ORDER BY `id` DESC LIMIT $perPage OFFSET $offset", ARRAY_A);
                $wpdb->flush();

                $v = array(
                      'pageno' => $pageno,
                      'total_pages' => $total_pages,
                      'filterBy' => $pagController['filterBy']
                );


                $datos['pageData'] = $v;


            }
            if($filtroPor == 'propCt'){


                $info = $wpdb->get_results("SELECT procesoContrato.* FROM $procesoEntrevistas as procesoContrato INNER JOIN $propuestaContratos as ofertaContrato ON (procesoContrato.ofertaId = ofertaContrato.ofertaId) where procesoContrato.candidataId = $id ", ARRAY_A);
                $wpdb->flush();


                $total_rows = count($info);
                $total_pages = ceil($total_rows / $perPage);

                $info = $wpdb->get_results("SELECT procesoContrato.* FROM $procesoEntrevistas as procesoContrato INNER JOIN $propuestaContratos as ofertaContrato ON (procesoContrato.ofertaId = ofertaContrato.ofertaId) where procesoContrato.candidataId = $id LIMIT $perPage OFFSET $offset", ARRAY_A);
                $wpdb->flush();

                $v = array(
                      'pageno' => $pageno,
                      'total_pages' => $total_pages,
                      'filterBy' => $pagController['filterBy']
                );


                $datos['pageData'] = $v;


            }
            if($filtroPor == 'leftIntw'){


            }
            if($filtroPor == 'withIntw'){

            }
            if($filtroPor == 'cancelado'){

            }

        }else{
            $pageno = 1;
            $perPage = 5;
            $offset = 0;

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id", ARRAY_A);
            $wpdb->flush();

            $total_rows = count($info);
            $total_pages = ceil($total_rows / $perPage);

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistas  where candidataId = $id LIMIT $perPage OFFSET $offset", ARRAY_A);
            $wpdb->flush();

            $v = array(
                  'pageno' => $pageno,
                  'total_pages' => $total_pages,
                  'filterBy' => 'todos'
              );

            $datos['pageData'] = $v;

            }
            foreach ($info as $r) {

            $ofertaId = $r['ofertaId'];
            $idEntrevista = $r['id'];
            $contratistaId = $r['contratistaId'];

            // por emedio de las publicaciones con entrevistas pautadas se extrae la información de las vacantes
            $seriales = $wpdb->get_results("SELECT * from $tablaOferta where id = '$ofertaId' and contratistaId = $contratistaId", ARRAY_A);
            $wpdb->flush();

            $info = $wpdb->get_results("SELECT * from $procesoEntrevistaEtapas WHERE idEntrevista='$idEntrevista'", ARRAY_A);
            $wpdb->flush();

                $p = array();
                foreach ($info as $rr) {
                    array_push($p, $rr);
                }

                $d = array(
                    'entrevista' => $r,
                    'etapas' => $p,
                    'oferta' => $seriales[0],
                    'contratista' => $contratistaId
                );
                array_push($datos, $d);

            }
            return $datos;


    }

}

// retorna las ofertas de contrato que un candidato tiene a partir de sus postulaciones
function dbGetInfoMyOfferContractsTab2($data)
{

    global $wpdb;
    $id = $data;
    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $preContratado = $wpdb->get_results("SELECT * FROM  $tablaEstado where postuladoID = $id", ARRAY_A);
    $datos = array();

    $i = 0;
    foreach ($preContratado as $r) {

        $s = $r['ofertaId'];
        // print_r($s);
        $info = $wpdb->get_results("SELECT * from $tablaOferta where id = '$s'", ARRAY_A);

        if (count($info) > 0) {
            $p = array();

            // array_push($p, $info[0]);

            $d = array(
                'estadoOferta' => $r,
                'oferta' => $info[0],
            );

            array_push($datos, $d);

        }

    }
    return $datos;

}

function dbGetContractDataOffer($data)
{

    global $wpdb;
    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    $tablaOferta = $wpdb->prefix . 'ofertalaboral';

    $can = $data['can'];
    $fam = $data['fam'];
    $serial = $data['serial'];

    $infoContract = $wpdb->get_results("SELECT * from $tablaOferta AS oferta INNER JOIN $tablaEstado AS estado ON oferta.id = estado.ofertaId where oferta.serialOferta = '$serial' AND estado.postuladoID = $can AND oferta.contratistaId = $fam", ARRAY_A);

    return $infoContract;

}

function dbContractExistValidate($data, $prop = false)
{
    global $wpdb;

    $tabla = $wpdb->prefix . 'contratos';

    $can = $data['can'];
    $fam = $data['fam'];
    $ofertaId = $data['ofertaId'];

    $info = $wpdb->get_results("SELECT * from $tabla where contratistaId=$fam AND candidataId=$can AND ofertaId = '$ofertaId'", ARRAY_A);

    if((count($info) == 0) && ($prop == true)){
        $tabla = $wpdb->prefix . 'estadoofertalaboral';
        $info = $wpdb->get_results("SELECT * from $tabla where postuladoID=$can AND ofertaId='$ofertaId'", ARRAY_A);


        if(count($info) == 0){

            $info = $wpdb->get_results("SELECT * from $tabla where  ofertaId='$ofertaId'", ARRAY_A);

            if(count($info) != 0){

                $info[0]['estado'] = 'Ya existe una propuesta de contrato para otro candidato';
                // print_r($info);
            }

        }
        // $info['espera'] = true;

        return $info;
    }

    return $info;

}

function dbDeleteOfferContract($data)
{
    global $wpdb;

    // ofertaId
    // postuladoId

    $ofertaId = $data['ofertaId'];
    $postuladoId = $data['postuladoId'];

    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    $tablaPostulantes = $wpdb->prefix . 'ofertapostulantes';

    try {
        $wpdb->query("DELETE FROM $tablaEstado WHERE postuladoID = $postuladoId AND ofertaId = '$ofertaId'");

        $wpdb->query("DELETE FROM $tablaPostulantes WHERE postulanteId = $postuladoId AND ofertaId = '$ofertaId'");
    } catch (Exception $e) {

    }

}

function dbAcceptContract($data, $textoContrato)
{
    global $wpdb;

    $tablaOferta = $wpdb->prefix . 'ofertalaboral';
    $tablaContratos = $wpdb->prefix . 'contratos';
    $tablaProcesoContratos = $wpdb->prefix . 'proceso_contrato';
    $tablaPostulantes = $wpdb->prefix . 'ofertapostulantes';
    $tablaEstado = $wpdb->prefix . 'estadoofertalaboral';
    $tablaContratoHistoriales = $wpdb->prefix . 'historialcontratos';

    $serial = $data['serial'];
    $can = $data['idPostulan'];

    $infoContract = $wpdb->get_results("SELECT * from $tablaOferta AS oferta INNER JOIN $tablaEstado AS estado ON oferta.id = estado.ofertaId where oferta.serialOferta = '$serial' AND estado.postuladoID = $can", ARRAY_A);

    $infoContract = $infoContract[0];
    $idContratosta = $infoContract['contratistaId'];
    $idCandidata = $data['idPostulan'];
    $firmaCandidata = $data['jsonfirmaCandidata'];
    $ofertaId = $infoContract['ofertaId'];

    $fechaCreacion = date('d/m/Y');
    $fechaInicio = $fechaCreacion;
    $fechaFin= date('d/m/Y', strtotime(' + 90 days'));


    $idContrato = uniqid() . uniqid();
    $datos = array(
        'id' => sanitize_text_field($idContrato),
        'contratistaId' => sanitize_text_field($infoContract['contratistaId']),
        'candidataId' => sanitize_text_field($data['idPostulan']),
        'ofertaId' => sanitize_text_field($infoContract['ofertaId']),
        'estado' => sanitize_text_field($infoContract['estado']),
        'fechaCreacion' => sanitize_text_field($fechaCreacion),
        'fechaInicio' => sanitize_text_field($fechaInicio),
        'fechaFin' => sanitize_text_field($fechaFin),
        'gestion' => sanitize_text_field($infoContract['gestion']),
        'urlPdf' => sanitize_text_field(''),
        'textoContrato' => $textoContrato,
        'firmaCandidata' => sanitize_text_field($idCandidata),
        'firmaContratista' => sanitize_text_field($idContratosta),
        'serialContrato' => sanitize_text_field($data['serial']),
        'nombreTrabajo' => sanitize_text_field($infoContract['nombreTrabajo']),
        'cargo' => sanitize_text_field($infoContract['cargo']),
        'nombreFamilia' => sanitize_text_field($infoContract['nombreFamilia']),
        'direccion' => sanitize_text_field($infoContract['direccion']),
        'turno' => sanitize_text_field(''),
        'pais' => sanitize_text_field($infoContract['pais']),
        'departamento' => sanitize_text_field($infoContract['departamento']),
        'ciudad' => sanitize_text_field($infoContract['ciudad']),
        // 'horario' => sanitize_text_field(''),
        'sueldo' => sanitize_text_field($infoContract['sueldo']),
        'tipoServicio' => sanitize_text_field($infoContract['tipoServicio']),
        'descripcionExtra' => sanitize_text_field($infoContract['descripcionExtra']),
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
        '%s',
    );


    $datoHistoriales = array(
        'id' => sanitize_text_field(uniqid() . uniqid()),
        'aceptado' => 1,
        'cancelado' => 0,
        'espera' => 0,
        'caducado' => 0,
        'activos' => 1,
        'eliminado' => 0,
        'engarantia' => 1,
        'definitivo' => 0,
        'contratoId' => sanitize_text_field($idContrato),
        'fecha' => sanitize_text_field($fechaCreacion)
    );
    $datosHistorialesFormato = array(
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

if(isset($firmaCandidata) && ($firmaCandidata != '') && ($firmaCandidata != null)){

    $idFamilia = $idCandidata;
$tabla = $wpdb->prefix . 'usuariofirmas';
$data = $wpdb->get_results("SELECT * FROM $tabla WHERE usuarioId = '$idFamilia'", ARRAY_A);

if(count($data) > 0){
    // si existe update

    try {
    $wpdb->query(" UPDATE $tabla SET firma = '$firmaCandidata' WHERE usuarioId='$idCandidata'");
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}

}else{
    // si no existe se crea
    $f = array(
        'firma' => $firmaCandidata,
        'usuarioId' => $idFamilia
    );

    $ft = array(
        '%s',
        '%s'
    );

    $wpdb->insert($tabla, $f, $ft);
    $wpdb->flush();

    }
}

    try {

        $wpdb->insert($tablaContratos, $datos, $formato);
        $wpdb->insert($tablaContratoHistoriales, $datoHistoriales, $datosHistorialesFormato);
        $ofertaId = $infoContract['ofertaId'];
        $wpdb->query("DELETE FROM $tablaEstado WHERE postuladoID = $can AND  ofertaId = '$ofertaId'");

        $cont = $infoContract['contratistaId'];

        // $wpdb->query("DELETE FROM $tablaProcesoContratos WHERE contratistaId = $cont AND  ofertaId = '$ofertaId'");

        $tb = $wpdb->prefix . 'proceso_contrato';

        $i = $wpdb->get_results("SELECT * from $tb where ofertaId='$ofertaId' and candidataId!=$cont and candidataId!=$can", ARRAY_A);
        print_r($i);
        // print_r($ofertaId);
        // print_r($cont);

        $wpdb->query("UPDATE $tablaProcesoContratos SET etapa=4 WHERE contratistaId = $cont AND ofertaId = '$ofertaId'");

        foreach ($i as $key => $value) {
            $tb = $wpdb->prefix . 'usuarios_recomendados';
            $datos = array(
                'idCandidato' => $value['candidataId'],
                'idOferta' => $value['ofertaId'],
                'idEntrevista' => $value['id'],
                'fechaRecomendado' => date('d/m/Y')
            );
            $formato = array(
                '%s',
                '%s',
                '%s',
                '%s'
            );
            $wpdb->insert($tb, $datos, $formato);

        }
          // datos mensajes
    // $candidatoId = $idCandidata;
    // $contratistaId = $idContratosta;

    // $tipoServicio = $infoContract['tipoServicio'];
    // $serialVacante = $infoContract['serialOferta'];
    // $nombreVacante = $infoContract['nombreTrabajo'];

    // $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

    // $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    // $familiaInfo = getInfoNameEmailUsers($contratistaId);

    // print_r($candidatoInfo);
    // print_r($familiaInfo);

    //     // mensaje notificacion
    //  // parte candidato
    //  $msj = 'Has aceptado la propuesta de contrato por la vacante laboral publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. A partir de este momento te encuentras en un periodo de prueba de 90 dias en el cargo laboral.';
    //  $mensaje = array(
    //      'mensaje' => $msj,
    //      'subject' => 'Has aceptado una propuesta de contrato por la vacante: '.$nombreVacante,
    //      'estado' => 0,
    //   // 'fecha' => ,
    //      'tipo' => 'acceptContract',
    //      'email' => $candidatoInfo['email'],
    //      'usuarioMuestra' => $candidatoInfo['id']
    //  );
    //  saveNotification($mensaje);

    //  // parte Familia
    //  $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha aceptado una propuesta de contrato por tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';

    //  $mensaje = array(
    //      'mensaje' => $msj,
    //      'subject' => 'Contrato aceptado de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por el cargo de la vacante: '.$nombreVacante,
    //      'estado' => 0,
    //   // 'fecha' => ,
    //      'tipo' => 'acceptContract',
    //      'email' => $familiaInfo['email'],
    //      'usuarioMuestra' => $familiaInfo['id']
    //  );
    //  saveNotification($mensaje);

    //  // parte Admin
    //  $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha aceptado una propuesta de contrato por la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>.';

    //  $mensaje = array(
    //      'mensaje' => $msj,
    //      'subject' => 'Contrato aceptado de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') por el cargo de la vacante: '.$nombreVacante,
    //      'estado' => 0,
    //   // 'fecha' => ,
    //      'tipo' => 'acceptContract',
    //      'email' => '',
    //      'usuarioMuestra' => 'Tsoluciono'
    //  );
    //  saveNotification($mensaje);




    } catch (Exception $e) {

    }

}

function getInfoContractExist($data)
{
    global $wpdb;

    $tablaContratos = $wpdb->prefix . 'contratos';

    $currentId = $data['currentId'];
    $can = $data['can'];
    $fam = $data['fam'];
    $serial = $data['serial'];

    $infoContract = $wpdb->get_results("SELECT * from $tablaContratos where contratistaId = $fam and candidataId = $can and serialContrato = '$serial'", ARRAY_A);

    return $infoContract;

}

function dbGetAllContractList()
{

    global $wpdb;

    $tablaContratos = $wpdb->prefix . 'contratos';
    $historiales = $wpdb->prefix . 'historialcontratos';

    // return $infoContract = $wpdb->get_results("SELECT * from $tablaContratos", ARRAY_A);
    return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId)", ARRAY_A);

}

function dbgetMyActiveContracts($data, $pagController = '')
{

    global $wpdb;

    $currentId = $data['current'];
    $tipo = $data['tipo'];

    $tablaContratos = $wpdb->prefix . 'contratos';
    $historiales = $wpdb->prefix . 'historialcontratos';

    if(isset($pagController) && $pagController != ''){

        $perPage = $pagController['porPagina'];
        // $perPage = 1;
        $pageno = $pagController['pg'];
        $offset = ($pageno-1) * $perPage;
        $filtroPor = $pagController['filterBy'];

        if($filtroPor == 'todos'){
            switch ($tipo) {
                case 'candidata':

                    return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where candidataId = $currentId", ARRAY_A);

                break;
                case 'familia':
                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId", ARRAY_A);

                    $total_rows = count($infoContract);
                    $total_pages = ceil($total_rows / $perPage);

                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId LIMIT $perPage OFFSET $offset ", ARRAY_A);

                    $v = array(
                        'pageno' => $pageno,
                        'total_pages' => $total_pages,
                        'filterBy' => $filtroPor
                    );

                    $infoContract['pageData'] = $v;
                    return $infoContract;
                    break;
                    default:
                    # code...
                break;
            }
        }
        if($filtroPor == 'activeCt'){
            switch ($tipo) {
                case 'candidata':

                    return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where candidataId = $currentId", ARRAY_A);

                break;
                case 'familia':
                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.activos = 1", ARRAY_A);

                    $total_rows = count($infoContract);
                    $total_pages = ceil($total_rows / $perPage);

                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.activos = 1 LIMIT $perPage OFFSET $offset ", ARRAY_A);

                    $v = array(
                        'pageno' => $pageno,
                        'total_pages' => $total_pages,
                        'filterBy' => $filtroPor
                    );

                    $infoContract['pageData'] = $v;
                    return $infoContract;
                    break;
                    default:
                    # code...
                break;
            }
        }
        if($filtroPor == 'garantCt'){
            switch ($tipo) {
                case 'candidata':

                    return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where candidataId = $currentId", ARRAY_A);

                break;
                case 'familia':
                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.activos = 1 and historial.engarantia = 1", ARRAY_A);

                    $total_rows = count($infoContract);
                    $total_pages = ceil($total_rows / $perPage);

                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.activos = 1 and historial.engarantia = 1 LIMIT $perPage OFFSET $offset ", ARRAY_A);

                    $v = array(
                        'pageno' => $pageno,
                        'total_pages' => $total_pages,
                        'filterBy' => $filtroPor
                    );

                    $infoContract['pageData'] = $v;
                    return $infoContract;
                    break;
                    default:
                    # code...
                break;
            }
        }
        if($filtroPor == 'CancelCt'){
            switch ($tipo) {
                case 'candidata':

                    return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where candidataId = $currentId", ARRAY_A);

                break;
                case 'familia':
                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.cancelado = 1", ARRAY_A);

                    $total_rows = count($infoContract);
                    $total_pages = ceil($total_rows / $perPage);

                    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId and historial.cancelado = 1 LIMIT $perPage OFFSET $offset ", ARRAY_A);

                    $v = array(
                        'pageno' => $pageno,
                        'total_pages' => $total_pages,
                        'filterBy' => $filtroPor
                    );

                    $infoContract['pageData'] = $v;
                    return $infoContract;
                    break;
                    default:
                    # code...
                break;
            }
        }


    }else{

        $pageno = 1;
        $perPage = 5;
        $offset = 0;

        switch ($tipo) {
        case 'candidata':

            return $infoContract = $wpdb->get_results("SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where candidataId = $currentId", ARRAY_A);

        break;
        case 'familia':
            $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId", ARRAY_A);

            $total_rows = count($infoContract);
            $total_pages = ceil($total_rows / $perPage);

            $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where contratistaId = $currentId LIMIT $perPage OFFSET $offset ", ARRAY_A);

            $v = array(
                'pageno' => $pageno,
                'total_pages' => $total_pages,
                'filterBy' => $filtroPor
            );

            $infoContract['pageData'] = $v;


            return $infoContract;

            break;
            default:
            # code...
        break;
    }
}

}

// obtener  postulantes seleccionados de una publicacion
function dbGetPostulantReadyContract($data)
{
    global $wpdb;
    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $id = $data;
    $info = $wpdb->get_results("SELECT * from $tablaSelectInterviews AS proceso, $tablaprocesoEntrevistasEtapas AS etapas  where proceso.ofertaId = '$id' AND etapas.etapa=3 AND etapas.aprobado=1", ARRAY_A);
    $wpdb->flush();
    return $info;

}


// 0
// procesar petición de cambio de candidato
function dbsendConfirmPetitionChange($data){

    global $wpdb;

    $tablaContratos = $wpdb->prefix . 'contratos';
    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaExperiencia = $wpdb->prefix . 'experiencia_contratos';
    $historialContratos = $wpdb->prefix . 'historialcontratos';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $ofertaId = $data['data']['datos']['ofertaId'];
    $canId = $data['data']['datos']['can'];
    $famId = $data['data']['datos']['fam'];
    $contratoId = $data['data']['cd'];
    $entrevistaIdCandidato = '';
    $tipoProceso = $data['form']['tipo'];

    $fechaMod = date('d/m/Y');

    $info = $wpdb->get_results("SELECT * from $tablaSelectInterviews AS proceso INNER JOIN $tablaprocesoEntrevistasEtapas AS etapas ON proceso.id = etapas.idEntrevista where proceso.ofertaId = '$ofertaId'", ARRAY_A);

    // extraer id entrevista del candidato que se quiere cambiar
    foreach ($info as $key => $value) {

        if($value['candidataId'] == $canId){
            $entrevistaIdCandidato = $value['idEntrevista'];
        }
        if(($value['candidataId'] == $famId) && ($value['contratistaId'] == $famId)){
            $entrevistaIdFamilia = $value['idEntrevista'];
        }

    }

    $infoExperiencia = array(
        'tipo' => $data['form']['tipo'],
        'detallesFamilia' => $data['form']['detalles'],
        'motivo' => $data['form']['motivo'],
        'calif' => $data['form']['calificacion']
    );

    $mensajeDetalles = array(
        'tipoProceso' => $data['form']['tipo'],
        'detallesPanel' => 'La familia ha solicitado un cambio de candidato, se requiere re plantear las entrevistas',
        'infoExperiencia' => $infoExperiencia,
        'dataContrato' => $data['data']
    );

    $infoExperiencia = json_encode($infoExperiencia, JSON_UNESCAPED_UNICODE);
    // cargar la experiencia de contrato
    $datosExperiencia = array(
        'idFamilia' => sanitize_text_field($famId),
        'idCandidato' => sanitize_text_field($canId),
        'ìdContrato' => sanitize_text_field($contratoId),
        'idEntrevista' => sanitize_text_field($entrevistaIdCandidato),
        'detallesExperiencia' => sanitize_text_field($infoExperiencia),
        'tipo' => sanitize_text_field($tipoProceso)
    );

    $formatoExperiencia = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    );

    $mensajeDetalles = json_encode($mensajeDetalles, JSON_UNESCAPED_UNICODE);

    try {

        // modificar el estado y la referencia del contrato y su registro en historiales
        $wpdb->query(" UPDATE $historialContratos SET cancelado=1, activos=0, eliminado=0, engarantia=0, solCambio=1, aceptado= 1, definitivo = 0, caducado = 0, detalles='$mensajeDetalles', fecha = '$fechaMod' WHERE contratoId='$contratoId'");

        // se carga el registro de experiencia sobre este contrato de parte de la familia como encuesta
        $wpdb->insert($tablaExperiencia, $datosExperiencia, $formatoExperiencia);

        // se modifica el estado de entrevistas de este proceso para que se vuelva a pedir otro usuario
        $wpdb->query("UPDATE $tablaSelectInterviews SET etapa=5, estado = 'En solicitud de otro candidato' WHERE contratistaId = $famId AND ofertaId = '$ofertaId'");

        // cambiar la nota de estado del candidato cambiado
        $wpdb->query("UPDATE $tablaprocesoEntrevistasEtapas SET estado='Cambiado por la familia' WHERE idEntrevista='$entrevistaIdCandidato'");

        //----------------------------------------------------------------
        // modificar quien selecciona candidato ahora para familia

        $xinfo = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista='$entrevistaIdFamilia'", ARRAY_A);

        $xinfo = $xinfo[0];
        $resultadosEntrevista = $xinfo['resultadosEntrevista'];
        $resultadosEntrevista = json_decode($resultadosEntrevista, true);
        unset($resultadosEntrevista['candidatoSeleccionado']);
        $resultadosEntrevista['seleccionPor'] = 'Familia';
        $resultadosEntrevista = json_encode($resultadosEntrevista, JSON_UNESCAPED_UNICODE);

        $wpdb->query("UPDATE $tablaprocesoEntrevistasEtapas SET resultadosEntrevista='$resultadosEntrevista', estado='Reeligiendo candidato' WHERE idEntrevista='$entrevistaIdFamilia'");

        // --------------------------------------------------------


    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    // aqui
        // mensaje notificacion
     // parte candidato

     $canId = $data['data']['datos']['can'];
     $famId = $data['data']['datos']['fam'];
     $ofertaId = $data['data']['datos']['ofertaId'];
     $contratoId = $data['data']['cd'];

    $candidatoId = $canId;
    $contratistaId = $famId;
    $ofertaId = $ofertaId;
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];

    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

    // familia
     $msj = 'Has solicitado cambiar a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> como candidata seleccionada bajo contrato en garantía para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Será revisado por la Administración.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Solicitaste cambiar a la candidata bajo contrato en garantía por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'changeCandSol',
         'email' => $familiaInfo['email'],
         'usuarioMuestra' => $familiaInfo['id']
     );
     saveNotification($mensaje);
    // familia
     $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> ha solicitado cambiarte como candidata seleccionada bajo contrato en garantía para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Será revisado por la Administración.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Han solicitado cambiarte como candidata bajo contrato en garantía por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'changeCandSol',
         'email' => $candidatoInfo['email'],
         'usuarioMuestra' => $candidatoInfo['id']
     );
     saveNotification($mensaje);
     // parte Admin
     $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> ha solicitado cambiar a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> como candidata seleccionada bajo contrato en garantía para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Solicitud de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para cambiar de candidata bajo contrato en garantía por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'reprogAsistencía',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);

}

// Completado
// solicitar reprogramación de asistencía
function dbSendCandSolChangeDate($data){


    global $wpdb;
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';

    $entrevistaId = $data['entrevistaId'];
    $info = $data['info'];


    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);

    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);

    $x = array(
        'estado' => 'Propuesta',
        'date' => $data['info']['date'],
        'hora' => $data['info']['hora']
    );

    $confirmadoFecha['candidato'] = $x;
    $confirmadoFecha['admin'] = 'Pendiente';
    $confirmadoFecha = json_encode($confirmadoFecha, JSON_UNESCAPED_UNICODE);


    // DATOS para la notificación

    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$entrevistaId'", ARRAY_A);
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

    // mensaje notificacion
     // parte candidato
     $msj = 'Has solicitado cambiar la hora y fecha de tu asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Será revisado por la Administración';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'reprogAsistencía',
         'email' => $candidatoInfo['email'],
         'usuarioMuestra' => $candidatoInfo['id']
     );
     saveNotification($mensaje);



     // parte Admin
     $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha solicitado cambiar su asistencía para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>. Publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Solicitud de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para reprogramar su asistencía a la entrevista por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'reprogAsistencía',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);



} catch (Exception $e) {

}


}

// auxiliar
// PENDIENTE
function dbSendCandConfirmDate($data){


    global $wpdb;
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $entrevistaId = $data;



    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);

    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);

    // id usuarios
    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$entrevistaId'", ARRAY_A);

    $candidatoId = $procesoContrato[0]['candidataId'];
    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];
    $etapa = $procesoContrato[0]['etapa'];

    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];

    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    $d = array();
    if($confirmadoFecha['admin']['estado'] == 'Propuesta' && isset($confirmadoFecha['admin']['estado'])){

        $d['date'] = $confirmadoFecha['admin']['date'];
        $d['hora'] = $confirmadoFecha['admin']['hora'];

        $x = array(
            'admin' => 'Confirmada',
            'candidato' => 'Confirmada'
        );
        $x = json_encode($x, JSON_UNESCAPED_UNICODE);

        try {
            $date = $d['date'];
            $hora = $d['hora'];

            $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET fechaPautado = '$date', hora = '$hora', confirmaFecha = '$x' where idEntrevista='$entrevistaId'");

            if($etapa == 0){

                $msj = 'Has confirmado tu asistencía a las Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
                $mensaje = array(
                    'mensaje' => $msj,
                    'subject' => 'Has aprobado la solicitud propuesta para reprogramar tu asistencía a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
              'estado' => 0,
              'estado' => 0,
             // 'fecha' => ,
             'tipo' => 'confAsistencia',
                'email' => $candidatoInfo['email'],
                'usuarioMuestra' => $candidatoInfo['id']
            );
            saveNotification($mensaje);

             // parte Administracion
            $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha confirmado su asistencía a las Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> y hora: <strong>'.$info[0]['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong> y publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Aprobada la solicitud de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para reprogramar su asistencía a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'confAsistencia',
                'email' => '',
                'usuarioMuestra' => 'Tsoluciono'
            );
            saveNotification($mensaje);


            }else{

                $msj = 'Has confirmado tu asistencía a las Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
                $mensaje = array(
                    'mensaje' => $msj,
                    'subject' => 'Has aprobado la solicitud propuesta para reprogramar tu asistencía a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
              'estado' => 0,
              'estado' => 0,
             // 'fecha' => ,
             'tipo' => 'confAsistencia',
                'email' => $candidatoInfo['email'],
                'usuarioMuestra' => $candidatoInfo['id']
            );
            saveNotification($mensaje);

             // parte Administracion
            $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha confirmado su asistencía a las Pruebas Psico laborales para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> y hora: <strong>'.$info[0]['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong> y publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Aprobada la solicitud de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') para reprogramar su asistencía a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'confAsistencia',
                'email' => '',
                'usuarioMuestra' => 'Tsoluciono'
            );
            saveNotification($mensaje);

        }


        } catch (Exception $e) {

        }

    }else{

        $x = array(
            'admin' => 'Confirmada',
            'candidato' => 'Confirmada'
        );
        $x = json_encode($x, JSON_UNESCAPED_UNICODE);

        $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$x' where idEntrevista='$entrevistaId'");


        if($etapa == 0){

        // parte candidato
        $msj = 'Has confirmado tu asistencía a las Pruebas Psico laborales para la vacante  <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> y hora: <strong>'.$info[0]['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Has confirmado tu asistencía a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'confAsistencia',
            'email' => $candidatoInfo['email'],
            'usuarioMuestra' => $candidatoInfo['id']
        );
        saveNotification($mensaje);

        // parte Administracion
        $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>'.' Ha confirmado su asistencía a las Pruebas Psico laborales para la entrevista <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> hora: <strong>'.$info[0]['hora'].'</strong>. Por el cargo de: <strong>'.$tipoServicio.'</strong> publicado por: <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>';

        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Confirmada la asistencía de '.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].') a las Pruebas Psico laborales por la vacante: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'confAsistencia',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);

        }

    }

}


// COMPLETADA
function dbSendFamSolChangeDate($data){

    global $wpdb;
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';


    $entrevistaId = $data['entrevistaId'];
    $info = $data['info'];


    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);

    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);

    $x = array(
        'estado' => 'Propuesta',
        'date' => $data['info']['date'],
        'hora' => $data['info']['hora']
    );

    $confirmadoFecha['familia'] = $x;
    $confirmadoFecha['admin'] = 'Pendiente';
    $confirmadoFecha = json_encode($confirmadoFecha, JSON_UNESCAPED_UNICODE);


    // datos para mensaje
    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$entrevistaId'", ARRAY_A);

    $contratistaId = $procesoContrato[0]['contratistaId'];
    $ofertaId = $procesoContrato[0]['ofertaId'];
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;


try {
    $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$confirmadoFecha' where idEntrevista='$entrevistaId'");

      // parte familia
      $msj = 'Has solicitado cambiar la hora y fecha de tu asistencía para la entrevista por tu vacante publicada: <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>. Será revisado por la Administración.';
      $mensaje = array(
          'mensaje' => $msj,
          'subject' => 'Solicitud para reprogramar tu asistencía de entrevista por la vacante: '.$nombreVacante,
          'estado' => 0,
       // 'fecha' => ,
          'tipo' => 'reprogAsistencía',
          'email' => $familiaInfo['email'],
          'usuarioMuestra' => $familiaInfo['id']
      );
      saveNotification($mensaje);


      // parte Admin
      $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha solicitado cambiar su asistencía para la entrevista de su vacante publicada: <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, a la fecha: <strong>'.$data['info']['date'].'</strong> y hora: <strong>'.$data['info']['hora'].'</strong>.';

      $mensaje = array(
          'mensaje' => $msj,
          'subject' => 'Solicitud de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para reprogramar su asistencía a la entrevista por su vacante: '.$nombreVacante,
          'estado' => 0,
       // 'fecha' => ,
          'tipo' => 'reprogAsistencía',
          'email' => '',
          'usuarioMuestra' => 'Tsoluciono'
      );
      saveNotification($mensaje);


} catch (Exception $e) {

}

}


function dbSendFamConfirmDate($data){

    global $wpdb;
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $procesoContrato = $wpdb->prefix . 'proceso_contrato';

    $entrevistaId = $data;

    $info = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas where idEntrevista = '$entrevistaId'", ARRAY_A);

    $confirmadoFecha = $info[0]['confirmaFecha'];
    $confirmadoFecha = json_decode($confirmadoFecha, true);


    // datos mensajes
    $procesoContrato = $wpdb->get_results("SELECT * from $procesoContrato where id = '$entrevistaId'", ARRAY_A);

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

    $d = array();
    if($confirmadoFecha['admin']['estado'] == 'Propuesta' && isset($confirmadoFecha['admin']['estado'])){

        $d['date'] = $confirmadoFecha['admin']['date'];
        $d['hora'] = $confirmadoFecha['admin']['hora'];

        $x = array(
            'admin' => 'Confirmada',
            'familia' => 'Confirmada'
        );
        $x = json_encode($x, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_UNICODE);

        try {
            $date = $d['date'];
            $hora = $d['hora'];
            // print_r($confirmadoFecha);
            // return;
            $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET fechaPautado = '$date', hora = '$hora', confirmaFecha = '$x' where idEntrevista='$entrevistaId'");

            // $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET fechaPautado = '$date', hora = '$hora', confirmaFecha = '$x' where idEntrevista='$entrevistaId'");

            $msj = 'Has confirmado tu asistencía para la entrevista sobre tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>.';
            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Has aprobado la solicitud propuesta para reprogramar tu asistencía de entrevista por tu vacante publicada: '.$nombreVacante,
              'estado' => 0,
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'confAsistencia',
                'email' => $familiaInfo['email'],
                'usuarioMuestra' => $familiaInfo['id']
            );
            saveNotification($mensaje);

             // parte Administracion
            $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha confirmado su asistencía para la entrevista de su vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$date.'</strong> y hora: <strong>'.$hora.'</strong>.';

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

        } catch (Exception $e) {

        }

    }else{

        $x = array(
            'admin' => 'Confirmada',
            'familia' => 'Confirmada'
        );
        $x = json_encode($x, JSON_UNESCAPED_UNICODE);

        $wpdb->query(" UPDATE $tablaprocesoEntrevistasEtapas SET confirmaFecha = '$x' where idEntrevista='$entrevistaId'");
        // parte candidato
        $msj = 'Has confirmado tu asistencía para la entrevista de tu vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> y hora: <strong>'.$info[0]['hora'].'</strong>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Has confirmado tu asistencía de entrevista por tu vacante publicada: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'confAsistencia',
            'email' => $familiaInfo['email'],
            'usuarioMuestra' => $familiaInfo['id']
        );
        saveNotification($mensaje);

        // parte Administracion
        $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha confirmado su asistencía para la entrevista de su vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>, con fecha: <strong>'.$info[0]['fechaCreacion'].'</strong> hora: <strong>'.$info[0]['hora'].'</strong>.';

        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Confirmada la asistencía de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].') para la entrevista de su vacante publicada: '.$nombreVacante,
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'confAsistencia',
            'email' => '',
            'usuarioMuestra' => 'Tsoluciono'
        );
        saveNotification($mensaje);


    }


}

function dbpayLater($data){

    global $wpdb;

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $datos = $data['datosOferta']['dataService'];
    $firmaFamilia = $data['datosOferta']['firmaFamilia'];
    $guardarFirma = $data['datosOferta']['guardarFirma'];
    $imagenes = $data['imagenes'];

    $idOferta = uniqid().uniqid();
    $fechaCreado = date('d/m/Y');

    $datosFamilia = getInfoNameEmailUsers($data['contratistaId']);
    $nombreFamilia = $datosFamilia['nombre'];

    $serialFactura = uniqid('F-',true);
    $serialOferta = uniqid('O-', true);


    // $nombreVacante = $data['nombreVacante'];

    $df = array(
        'serialFactura' => $serialFactura,
        'serialOferta' => $serialOferta,
        'nombreFactura' => $datos['titulo'],
        'id' => $data['contratistaId']
    );

    // print_r($data);

    createFacturation($df);


    // $upload_dir = $upload_dir."/publicaciones";

    $iii = array(
        'imagenes' => $imagenes,
        'carpeta' => '/publicaciones',
        'serial' => $idOferta,
    );

    $imagenesJson = cargarImagenes($iii);

    // return;

    $datos = array(
        'id' => sanitize_text_field($idOferta),
        'contratistaId' => sanitize_text_field($data['contratistaId']),
        'estado' => sanitize_text_field('En espera de pago'),
        'fechaCreacion' => sanitize_text_field($fechaCreado),
        'gestion' => sanitize_text_field('Gestionado por administración'),
        'fechaInicio' => sanitize_text_field($datos['fechaInicio']),
        'fechaFin' => sanitize_text_field($datos['fechaFin']),
        'nombreTrabajo' => sanitize_text_field($datos['titulo']),
        'cargo' => sanitize_text_field($datos['cargo']),
        'nombreFamilia' => sanitize_text_field($nombreFamilia),
        'direccion' => sanitize_text_field($datos['direccion']),
        'pais' => sanitize_text_field($datos['pais']),
        'departamento' => sanitize_text_field($datos['departamento']),
        'ciudad' => sanitize_text_field($datos['ciudad']),
        'sueldo' => sanitize_text_field($datos['sueldo']),
        'horario' => sanitize_text_field($datos['horario']),
        'tipoServicio' => sanitize_text_field($datos['servicio']),
        'descripcionExtra' => sanitize_text_field($datos['descripcion']),
        'firmaCandidata' => $data['contratistaId'],
        'serialOferta' => sanitize_text_field($serialOferta),
        'contratoTerminosPublicacion' => $data['terminosCompleto'],
        'aceptaTerminosContrato' => 1,
        'aceptaTerminosPublicacion' => 1,
        'serialFactura' => $serialFactura,
        'publico' => 0,
        'imagenes' => sanitize_text_field($imagenesJson),
    );

    $datos2 = array(
        'idOferta' => sanitize_text_field($idOferta),
        'fechaLlamada' => '',
        'publicar' => 0,
        'aprobado' => 0,
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
    );

    $formato2 = array(
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $tabla = $wpdb->prefix . 'ofertalaboral';
    $tablaAdminOffer = $wpdb->prefix . 'admin_vacantes_familia';

    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();

    $wpdb->insert($tablaAdminOffer, $datos2, $formato2);
    // print_r($datos2);

    if($guardarFirma == 1){

        $idFamilia = $data['contratistaId'];
        $tabla = $wpdb->prefix . 'usuariofirmas';
        $data = $wpdb->get_results("SELECT * FROM $tabla WHERE usuarioId = '$idFamilia'", ARRAY_A);

        if(count($data) > 0){
            // si existe update

        try {
            $wpdb->query(" UPDATE $tabla SET firma = '$firmaFamilia' WHERE usuarioId='$idFamilia'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        }else{
            // si no existe se crea

            $f = array(
                'firma' => $firmaFamilia,
                'usuarioId' => $idFamilia
            );

            $ft = array(
                '%s',
                '%s'
            );

            $wpdb->insert($tabla, $f, $ft);
            $wpdb->flush();

        }
    }
    $idFamilia = $data['contratistaId'];

        // parte de mensaje
        $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

        $contratistaId = $idFamilia;
        $ofertaId = $idOferta;

        $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

        $tipoServicio = $infoOferta[0]['tipoServicio'];
        $serialVacante = $infoOferta[0]['serialOferta'];
        // $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
        $nombreVacante = $infoOferta[0]['nombreTrabajo'];
        $familiaInfo = getInfoNameEmailUsers($contratistaId);


        // mensaje familia
    $msj = 'Se ha creado una nueva oferta laboral llamada: '.$nombreVacante.', con fecha: <strong>'.$fechaCreado.'</strong>. Paga tu factura para que tu oferta pueda estar visible al público <a class="hiper" href="'.$urlFactura.'">AQUI</a>.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Has creado una nueva oferta laboral: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'newOfferLaboral',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);


    die();


}

// pagar luego
function createFacturation($data){

    global $wpdb;
    $serialFactura = $data['serialFactura'];
    $nombreFactura = $data['nombreFactura'];
    $serialOferta = $data['serialOferta'];
    $id = $data['id'];
    $nombreVacante = $nombreFactura;

        $datosFc = array(
            'nombreFactura' => sanitize_text_field($nombreFactura),
            // 'mensaje' => sanitize_text_field(),
            'fechaCreada' => date('d/m/Y'),
            // 'fechaPagada' => sanitize_text_field(),
            // 'plan' => sanitize_text_field(),
            // 'comprobante' => sanitize_text_field(),
            // 'referencia' => sanitize_text_field(),
            'estado' => sanitize_text_field('Esperando comprobante de pago'),
            'pagado' => 0,
            'contratistaId' => $id,
            'serialFactura' => $serialFactura
        );
        $formatoFc = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        );

        //    crear factura
        $tabla = $wpdb->prefix . 'facturacion';
        $wpdb->insert($tabla, $datosFc, $formatoFc);
        $wpdb->flush();



        $idFamilia = $id;
        // parte de mensaje
        $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
        $contratistaId = $idFamilia;

        $familiaInfo = getInfoNameEmailUsers($contratistaId);

        $facturaUrl = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;


                // mensaje familia
        $msj = 'Tienes una factura pendiente para cancelación por la creación de la oferta laboral: <strong>'.$nombreFactura.'</strong>. Puedes hacer el proceso de pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Factura pendiente por cancelar ',
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'nuevaFacturaEspera',
            'email' => $familiaInfo['email'],
            'usuarioMuestra' => $familiaInfo['id']
        );
        saveNotification($mensaje);




              // parte Administracion
              $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Tiene una factura pendiente para cancelación por los servicios de su vacante publicada: '.$nombreFactura.'. Puedes ver su factura <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
              $mensaje = array(
                  'mensaje' => $msj,
                  'subject' => 'Factura pendiente de '.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')',
                  'estado' => 0,
               // 'fecha' => ,
                  'tipo' => 'nuevaFacturaEspera',
                  'email' => '',
                  'usuarioMuestra' => 'Tsoluciono'
              );
              saveNotification($mensaje);



}

// pago de inmediato AQUI MIENTRAS
 function dbprocesspayService($data){


    global $wpdb;

    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';
    $datosFactura = $data['datosFactura'];
    $datos = $data['datos'];
    $firmaFamilia = $datos['firmaFamilia'];
    $guardarFirma = $datos['guardarFirma'];
    $contratistaId = (isset($datos['asignadoA']) && $datos['asignadoA'] != null)? $datos['asignadoA']: $datos['contratistaId'];
    $fotosOferta = $datos['fotos'];
    $imagenes = imagesToArray($fotosOferta);

    $idOferta = uniqid().uniqid();
    $fechaCreado = date('d/m/Y');

    $datosFamilia = getInfoNameEmailUsers($contratistaId);
    $nombreFamilia = $datosFamilia['nombre'];
    $serialFactura = uniqid('F-',true);
    $contratoTerminos = $data['contratoTerminos'];

    $dfct = array(
        'tipo' => $datosFactura['tipo'],
        'referencia' => $datosFactura['referencia'],
        'mensajeOpcional' => $datosFactura['mensajeOpcional']
    );

    $df = array(
        'comprobante' => $datosFactura['comprobante'],
        'datosFactura' => $dfct,
        'serialFactura' => $serialFactura,
        'nombreFactura' => $datos['titulo'],
        'id' => $contratistaId,
        'nombreVacante' => $datos['titulo']
    );

    $iii = array(
        'imagenes' => $imagenes,
        'carpeta' => '/publicaciones',
        'serial' => $idOferta,
    );
    $imagenesJson = cargarImagenes($iii);

    createAndPayBill($df);

    // return;
    $datos = array(
        'id' => sanitize_text_field($idOferta),
        'contratistaId' => sanitize_text_field($contratistaId),
        'estado' => sanitize_text_field('En revisión de pago efectuado'),
        'fechaCreacion' => sanitize_text_field($fechaCreado),
        'gestion' => sanitize_text_field('Gestionado por administración'),
        'fechaInicio' => sanitize_text_field($datos['fechaInicio']),
        'fechaFin' => sanitize_text_field($datos['fechaFin']),
        'nombreTrabajo' => sanitize_text_field($datos['titulo']),
        'cargo' => sanitize_text_field($datos['cargo']),
        'nombreFamilia' => sanitize_text_field($nombreFamilia),
        'direccion' => sanitize_text_field($datos['direccion']),
        'pais' => sanitize_text_field($datos['pais']),
        'departamento' => sanitize_text_field($datos['departamento']),
        'ciudad' => sanitize_text_field($datos['ciudad']),
        'sueldo' => sanitize_text_field($datos['sueldo']),
        'horario' => sanitize_text_field($datos['horario']),
        'tipoServicio' => sanitize_text_field($datos['servicio']),
        'descripcionExtra' => sanitize_text_field($datos['descripcion']),
        'firmaCandidata' => $contratistaId,
        'serialOferta' => sanitize_text_field(uniqid('O-', true)),
        'contratoTerminosPublicacion' => $contratoTerminos,
        'aceptaTerminosContrato' => 1,
        'aceptaTerminosPublicacion' => 1,
        'serialFactura' => $serialFactura,
        'publico' => 0,
        'imagenes' => sanitize_text_field($imagenesJson)
    );


    $datos2 = array(
        'idOferta' => sanitize_text_field($idOferta),
        'fechaLlamada' => sanitize_text_field($data['fechaCreacion']),
        'publicar' => 0,
        'aprobado' => 0,
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

    $formato2 = array(
        '%s',
        '%s',
        '%s',
        '%s',
    );

    $tabla = $wpdb->prefix . 'ofertalaboral';
    $tablaAdminOffer = $wpdb->prefix . 'admin_vacantes_familia';

    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();

    $wpdb->insert($tablaAdminOffer, $datos2, $formato2);
    // print_r($datos2);

    if($guardarFirma == 1){

        $idFamilia = $contratistaId;
        $tabla = $wpdb->prefix . 'usuariofirmas';
        $data = $wpdb->get_results("SELECT * FROM $tabla WHERE usuarioId = '$idFamilia'", ARRAY_A);

        if(count($data) > 0){
            // si existe update

        try {
            $wpdb->query(" UPDATE $tabla SET firma = '$firmaFamilia' WHERE usuarioId='$idFamilia'");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        }else{
            // si no existe se crea

            $f = array(
                'firma' => $firmaFamilia,
                'usuarioId' => $idFamilia
            );

            $ft = array(
                '%s',
                '%s'
            );

            $wpdb->insert($tabla, $f, $ft);
            $wpdb->flush();

        }
    }
    $idFamilia = $contratistaId;

        // parte de mensaje
        $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

        $contratistaId = $idFamilia;
        $ofertaId = $idOferta;

        $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

        $tipoServicio = $infoOferta[0]['tipoServicio'];
        $serialVacante = $infoOferta[0]['serialOferta'];
        // $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;
        $nombreVacante = $infoOferta[0]['nombreTrabajo'];
        $familiaInfo = getInfoNameEmailUsers($contratistaId);


        $facturaUrl = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;


        // mensaje familia
    $msj = 'Se ha creado una nueva oferta laboral llamada: '.$nombreVacante.', con fecha: <strong>'.$fechaCreado.'</strong>. El costo asociado con la prestación de este servicio ha sido pagado y se encuentra en revisión. Puedes ver el estado actual de tu pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>. <br><br>
    El proceso de publicación de tu oferta laboral, postulaciones y selección de candidato iniciará cuando comprobemos la validez del pago realizado. Sé paciente por favor.
    ';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Has creado una nueva oferta laboral: '.$nombreVacante,
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'newOfferLaboral',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);

 }

function createAndPayBill($data){

    global $wpdb;
    $comprobante = $data['comprobante'];
    $datosFactura = $data['datosFactura'];
    $serialFactura = $data['serialFactura'];
    $nombreFactura = $data['nombreFactura'];
    $id = $data['id'];
    $nombreVacante = $data['nombreVacante'];
    $mensaje = $datosFactura['mensajeOpcional'];
    $mensaje = sanitize_text_field($mensaje);
    $mensaje = ($mensaje != '')?"<strong>Nota de familia:</strong> <br> $mensaje": $mensaje;

    // configurar imagen
    $image = $comprobante['tmp_name'];
    $tamano = $comprobante['size'];
    $formato = $comprobante['type'];

    $formato = explode('/', $formato);
    $formato = $formato[1];

    // $video[0] = $archivos['video'];
    // $video = imagesToArray($video);
    // $iii = array(
    //     'imagenes' => $video,
    //     'carpeta' => '/pubprofesional',
    //     'serial' => $idPublicacion,
    // );
    // $videoJson = cargarImagenes($iii);
    // $videoJson = json_decode($videoJson, true);
    // $newMedia['video'] = $videoJson;


    // print_r($mensaje);
    // return;

    // $imgContenido = addslashes(file_get_contents($image));

    // $fp = fopen($image, "rb");
    // $contenido = fread($fp, $tamano);
    // $contenido = addslashes($contenido);
    // fclose($fp);

        $datosFc = array(
            'nombreFactura' => sanitize_text_field($nombreFactura),
            'mensaje' => $mensaje,
            'fechaCreada' => date('d/m/Y'),
            'fechaPagada' => date('d/m/Y'),
            'plan' => 'Tarifa base',
            'referencia' => sanitize_text_field($datosFactura['referencia']),
            'estado' => sanitize_text_field('En revisión por la administración'),
            'pagado' => 0,
            'contratistaId' => $id,
            'serialFactura' => $serialFactura,
            'cuenta' => $datosFactura['tipo'],
            'tipoPago' => sanitize_text_field('Transferencia bancaria'),
            'comprobante' => 1,
            'formato' => $formato

        );
        $formatoFc = array(
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
            // '%s',
        );

        //    crear factura
        $tabla = $wpdb->prefix . 'facturacion';
        $wpdb->insert($tabla, $datosFc, $formatoFc);
        $wpdb->flush();


        try {


            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/facturas';
            if (! is_dir($upload_dir)) {
               mkdir( $upload_dir, 0777 );
            }

            move_uploaded_file($image, $upload_dir.'/'.$serialFactura.'.'.$formato);

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }




        $idFamilia = $id;
        // parte de mensaje
        $contratistaId = $idFamilia;

        $familiaInfo = getInfoNameEmailUsers($contratistaId);

        $facturaUrl = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;


                // mensaje familia
        $msj = 'Has tramitado un comprobante de pago, para los servicios de la oferta laboral creada: <strong>'.$nombreVacante.'</strong>. Tú oferta estará visible al público, una vez hayamos confirmado tu pago. Puedes ver el estado actual de tu pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
        $mensaje = array(
            'mensaje' => $msj,
            'subject' => 'Has enviado un comprobante de pago de servicios por tu oferta laboral: <strong>'.$nombreVacante.'</strong> ',
            'estado' => 0,
         // 'fecha' => ,
            'tipo' => 'nuevaFacturaPagadEspera',
            'email' => $familiaInfo['email'],
            'usuarioMuestra' => $familiaInfo['id']
        );
        saveNotification($mensaje);

                // parte Administracion
                $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha tramitado un comprobante de pago para los servicios de su vacante publicada: '.$nombreVacante.'.  Puedes verificar la veracidad del pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
                $mensaje = array(
                    'mensaje' => $msj,
                    'subject' => $familiaInfo['nombre'].'('.$familiaInfo['rol'].') Ha enviado un comprobante de pago',
                    'estado' => 0,
                 // 'fecha' => ,
                    'tipo' => 'nuevaFacturaPagadEspera',
                    'email' => '',
                    'usuarioMuestra' => 'Tsoluciono'
                );
                saveNotification($mensaje);


}


function dbafterPayBill($data){

    global $wpdb;
    $file = $data['archivo']['comprobante'];
    $datosFactura = $data['datosFactura'];
    $serialFactura = $data['serial'];



    // configurar imagen
    $image = $file['tmp_name'];
    $tamano = $file['size'];
    $formato = $file['type'];

    $formato = explode('/', $formato);
    $formato = $formato[1];

    $tabla = $wpdb->prefix . 'facturacion';
    $infoFactura = $wpdb->get_results("SELECT * from $tabla WHERE serialFactura='$serialFactura'", ARRAY_A);

    $familiaId = $infoFactura[0]['contratistaId'];
    $nombreVacante = $infoFactura[0]['nombreFactura'];
    $formatoViejo = $infoFactura[0]['formato'];
    $mensaje = $datosFactura['mensajeOpcional'];
    $mensaje = sanitize_text_field($mensaje);
    $mensaje = ($mensaje != '')?"<strong>Nota de Familia:</strong> <br> $mensaje": $mensaje;


    $cuenta = $datosFactura['tipo'];
    $referencia = $datosFactura['referencia'];
    $plan = 'Tarifa base';
    $comprobante = 1;
    $fechaPagada = date('d/m/Y');
    $estado = 'En revisión por la administración';
    $tipoPago = 'Transferencia bancaria';


    try {

        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/facturas';
        if (! is_dir($upload_dir)) {
           mkdir( $upload_dir, 0777 );
        }

        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/facturas';
        $archivo = "$upload_dir/$serialFactura.$formatoViejo";
        unlink($archivo);

        move_uploaded_file($image, $upload_dir.'/'.$serialFactura.'.'.$formato);

        $wpdb->query(" UPDATE $tabla SET mensaje = '$mensaje',fechaPagada = '$fechaPagada',plan = '$plan',referencia = '$referencia',estado = '$estado',pagado = 0,cuenta = '$cuenta',tipoPago = '$tipoPago',comprobante = $comprobante,formato = '$formato' WHERE serialFactura = '$serialFactura'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    // notificacion
    $contratistaId = $familiaId;

    $familiaInfo = getInfoNameEmailUsers($contratistaId);

    $facturaUrl = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;

            // mensaje familia
    $msj = 'Has tramitado un comprobante de pago, para los servicios de la oferta laboral creada: <strong>'.$nombreVacante.'</strong>. Tú oferta estará visible al público, una vez hayamos confirmado tu pago. Puedes ver el estado actual de tu pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
    $mensaje = array(
        'mensaje' => $msj,
        'subject' => 'Has enviado un comprobante de pago de servicios por tu oferta laboral: <strong>'.$nombreVacante.'</strong> ',
        'estado' => 0,
     // 'fecha' => ,
        'tipo' => 'nuevaFacturaPagadEspera',
        'email' => $familiaInfo['email'],
        'usuarioMuestra' => $familiaInfo['id']
    );
    saveNotification($mensaje);

            // parte Administracion
            $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong>'.' Ha tramitado un comprobante de pago para los servicios de su vacante publicada: '.$nombreVacante.'.  Puedes verificar la veracidad del pago <a class="hiper" href="'.$facturaUrl.'">AQUI</a>.';
            $mensaje = array(
                'mensaje' => $msj,
                'subject' => $familiaInfo['nombre'].'('.$familiaInfo['rol'].') Ha enviado un comprobante de pago',
                'estado' => 0,
             // 'fecha' => ,
                'tipo' => 'nuevaFacturaPagadEspera',
                'email' => '',
                'usuarioMuestra' => 'Tsoluciono'
            );
            saveNotification($mensaje);



}


function dbsavedUserSettings($data){
    global $wpdb;

    $tabla = $wpdb->prefix . 'usuariofirmas';
    $usuarioFirma = $data['usuarioFirma'];
    $idUsuario = $data['idUsuario'];


        $infoAdmin = array(
            'usuarioId' => $idUsuario,
            'firma' => $usuarioFirma
        );
        $formato = array(
            '%s'
        );

        $configVerf = $wpdb->get_results("SELECT * FROM $tabla where usuarioId = '$idUsuario'", ARRAY_A);

        if(count($configVerf) > 0){

            try {
                $wpdb->query("UPDATE $tabla SET firma = '$usuarioFirma'");
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }

        }else{
            // no existe
            $wpdb->insert($tabla, $infoAdmin, $formato);
            $wpdb->flush();
        }

}


function dbInfoConfirmService($data){

    global $wpdb;
    // return;
    $tablaContratos = $wpdb->prefix . 'contratos';
    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaExperiencia = $wpdb->prefix . 'experiencia_contratos';
    $historialContratos = $wpdb->prefix . 'historialcontratos';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $tablaOfertaLaboral = $wpdb->prefix . 'ofertalaboral';

    $ofertaId = $data['data']['datos']['ofertaId'];
    $canId = $data['data']['datos']['can'];
    $famId = $data['data']['datos']['fam'];
    $contratoId = $data['data']['cd'];
    $entrevistaIdCandidato = '';
    $tipoProceso = $data['form']['tipo'];

    $fechaMod = date('d/m/Y');

    $info = $wpdb->get_results("SELECT * from $tablaSelectInterviews AS proceso INNER JOIN $tablaprocesoEntrevistasEtapas AS etapas ON proceso.id = etapas.idEntrevista where proceso.ofertaId = '$ofertaId'", ARRAY_A);

    // extraer id entrevista del candidato que se quiere cambiar
    foreach ($info as $key => $value) {

        if($value['candidataId'] == $canId){
            $entrevistaIdCandidato = $value['idEntrevista'];
        }
        if(($value['candidataId'] == $famId) && ($value['contratistaId'] == $famId)){
            $entrevistaIdFamilia = $value['idEntrevista'];
        }

    }

    $infoExperiencia = array(
        'tipo' => $data['form']['tipo'],
        'detallesFamilia' => $data['form']['detalles'],
        'desempeno' => $data['form']['desempeno'],
        'calif' => $data['form']['calificacion']
    );

    $mensajeDetalles = array(
        'tipoProceso' => $data['form']['tipo'],
        'detallesPanel' => 'La familia ha solicitado un cambio de candidato, se requiere re plantear las entrevistas',
        'infoExperiencia' => $infoExperiencia,
        'dataContrato' => $data['data']
    );

    // print_r($mensajeDetalles);
    $infoExperiencia = json_encode($infoExperiencia, JSON_UNESCAPED_UNICODE);
    // cargar la experiencia de contrato
    $datosExperiencia = array(
        'idFamilia' => sanitize_text_field($famId),
        'idCandidato' => sanitize_text_field($canId),
        'ìdContrato' => sanitize_text_field($contratoId),
        'idEntrevista' => sanitize_text_field($entrevistaIdCandidato),
        'detallesExperiencia' => sanitize_text_field($infoExperiencia),
        'tipo' => sanitize_text_field($tipoProceso)
    );

    $formatoExperiencia = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    );
    $mensajeDetalles = json_encode($mensajeDetalles, JSON_UNESCAPED_UNICODE);

    try {

        $wpdb->query("UPDATE $historialContratos SET cancelado=0, activos=1, eliminado=0, engarantia=0, solCambio=0, aceptado= 1, definitivo = 1, caducado = 0, detalles='$mensajeDetalles', fecha = '$fechaMod' WHERE contratoId='$contratoId'");

        // se carga el registro de experiencia sobre este contrato de parte de la familia como encuesta
        $wpdb->insert($tablaExperiencia, $datosExperiencia, $formatoExperiencia);

        // se modifica el estado de entrevistas de este proceso para que se vuelva a pedir otro usuario
        $wpdb->query("UPDATE $tablaSelectInterviews SET etapa=6, estado = 'Candidato definitivo seleccionado' WHERE contratistaId = $famId AND ofertaId = '$ofertaId'");

        // cambiar la nota de estado del candidato cambiado
        $wpdb->query("UPDATE $tablaprocesoEntrevistasEtapas SET estado='Candidato definitivo en el cargo' WHERE idEntrevista='$entrevistaIdCandidato'");



    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

     $canId = $data['data']['datos']['can'];
     $famId = $data['data']['datos']['fam'];
     $ofertaId = $data['data']['datos']['ofertaId'];
     $contratoId = $data['data']['cd'];

    $candidatoId = $canId;
    $contratistaId = $famId;
    $ofertaId = $ofertaId;
    $infoOferta = $wpdb->get_results("SELECT * from $tablaOfertaLaboral where id = '$ofertaId'", ARRAY_A);

    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $candidatoInfo = getInfoNameEmailUsers($candidatoId);
    $familiaInfo = getInfoNameEmailUsers($contratistaId);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];

    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

    // familia
     $msj = 'Has confirmado a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> como candidato seleccionado definitivo para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Confirmaste al candidato bajo contrato definitivo por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => $familiaInfo['email'],
         'usuarioMuestra' => $familiaInfo['id']
     );
     saveNotification($mensaje);
    // familia
     $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> te ha seleccionado bajo contrato definitivo para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Te han seleccionado como candidato bajo contrato definitivo: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => $candidatoInfo['email'],
         'usuarioMuestra' => $candidatoInfo['id']
     );
     saveNotification($mensaje);
     // parte Admin
     $msj = '<strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> ha seleccionado a <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> como candidato seleccionado bajo contrato definitivo para la vacante <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Por el cargo de: <strong>'.$tipoServicio.'</strong>.';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => $familiaInfo['nombre'].'('.$familiaInfo['rol'].') ha seleccionado candidato bajo contrato definitivo por la vacante: '.$nombreVacante,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);


}


function dbprocessacceptAditionalPurpose2($data){

    global $wpdb;

    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $ofertalaboral = $wpdb->prefix . 'ofertalaboral';
    $usuarios_recomendados = $wpdb->prefix . 'usuarios_recomendados';

    $confirmaFecha = array(
        'admin' => 'Confirmada',
        'candidato' => 'Confirmada'
    );
    $aprobado = 1;
    $confirmaFecha = json_encode($confirmaFecha);

    $entrevistaAnexar = $wpdb->get_results("SELECT proceso.* from $tablaSelectInterviews as proceso inner JOIN $tablaprocesoEntrevistasEtapas as etapas on proceso.id = etapas.idEntrevista where etapas.idEntrevista = '$data'", ARRAY_A);
    $ofertaId = $entrevistaAnexar[0]['ofertaId'];

    $famIdAnexar = $entrevistaAnexar[0]['contratistaId'];
    $ci = $entrevistaAnexar[0]['candidataId'];

    try {

        $wpdb->query("UPDATE $tablaprocesoEntrevistasEtapas SET tipoEntrevista = 'Adición de candidato aceptada' , aprobado = $aprobado, confirmaFecha = '$confirmaFecha' WHERE idEntrevista='$data'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    $infoOferta = $wpdb->get_results("SELECT * from $ofertalaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $familiaInfo = getInfoNameEmailUsers($famIdAnexar);
    $candidatoInfo = getInfoNameEmailUsers($ci);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

        //   parte Administracion
           $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha aceptado una propuesta de trabajo tomando en cuenta una de sus entrevistas realizadas en anterioridad, ahora puedes seleccionarlo bajo contrato para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha sido añadido como candidato adicional para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCandAccept',
               'email' => $familiaInfo['email'],
               'usuarioMuestra' => $familiaInfo['id']
           );

           saveNotification($mensaje);
            // parte candidato
           $msj = 'Has aceptado una propuesta de trabajo tomando en cuenta una de tus entrevistas realizadas en anterioridad, ahora <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> puede seleccionarte bajo contrato para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Aceptaste la propuesta de trabajo para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCandAccept',
               'email' => $candidatoInfo['email'],
               'usuarioMuestra' => $candidatoInfo['id']
           );

           saveNotification($mensaje);

           $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha aceptado una propuesta de trabajo tomando en cuenta una de sus entrevistas realizadas en anterioridad, ahora <strong>'.$familiaInfo['nombre'].'('.$familiaInfo['rol'].')</strong> puede seleccionarlo bajo contrato para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha aceptado una propuesta de trabajo para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCandAccept',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);
           // parte Administracion


}

function dbprocesssendrefuseAditionalPurpose2($data){

    global $wpdb;

    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
    $ofertalaboral = $wpdb->prefix . 'ofertalaboral';
    $usuarios_recomendados = $wpdb->prefix . 'usuarios_recomendados';

    $entrevistaAnexar = $wpdb->get_results("SELECT proceso.* from $tablaSelectInterviews as proceso inner JOIN $tablaprocesoEntrevistasEtapas as etapas on proceso.id = etapas.idEntrevista where etapas.idEntrevista = '$data'", ARRAY_A);
    $ofertaId = $entrevistaAnexar[0]['ofertaId'];

    $famIdAnexar = $entrevistaAnexar[0]['contratistaId'];
    $ci = $entrevistaAnexar[0]['candidataId'];

    try {

        $wpdb->query("DELETE FROM $tablaSelectInterviews WHERE id = '$data' AND ofertaId = '$ofertaId'");

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    $infoOferta = $wpdb->get_results("SELECT * from $ofertalaboral where id = '$ofertaId'", ARRAY_A);
    $tipoServicio = $infoOferta[0]['tipoServicio'];
    $familiaInfo = getInfoNameEmailUsers($famIdAnexar);
    $candidatoInfo = getInfoNameEmailUsers($ci);
    $nombreVacante = $infoOferta[0]['nombreTrabajo'];
    $serialVacante = $infoOferta[0]['serialOferta'];
    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;


           saveNotification($mensaje);
            // parte candidato
           $msj = 'Has rechazado una propuesta de trabajo tomando en cuenta una de tus entrevistas realizadas en anterioridad, para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';
           $mensaje = array(
               'mensaje' => $msj,
               'subject' => 'Rechazaste una propuesta de trabajo para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCandAccept',
               'email' => $candidatoInfo['email'],
               'usuarioMuestra' => $candidatoInfo['id']
           );

           saveNotification($mensaje);

           $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha rechazado una propuesta de trabajo tomando en cuenta una de sus entrevistas realizadas en anterioridad, para la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>';

           $mensaje = array(
               'mensaje' => $msj,
               'subject' => '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha rechazado una propuesta de trabajo para la vacante: '.$nombreVacante,
               'estado' => 0,
            // 'fecha' => ,
               'tipo' => 'addExtraCandAccept',
               'email' => '',
               'usuarioMuestra' => 'Tsoluciono'
           );
           saveNotification($mensaje);
           // parte Administracion

}

function pagoProfesional($dataReturned) {

    global $wpdb;

    $tablafacturacion_profesional = $wpdb->prefix . 'facturacion_profesional';
    $tablapublic_profesional = $wpdb->prefix . 'public_profesional';

    $candidatoId = $dataReturned['candidatoId'];
    $informacion = $dataReturned['infoGeneral'];
    $archivos = $dataReturned['archivos'];
    $canidatoId = $dataReturned['currentId'];
    $infoFactura = $informacion['datosFactura'];
    $infoFactura = stripslashes($infoFactura);
    $infoFactura = json_decode($infoFactura, true);

    // informacion
    $idPublicProfesional =  uniqid(). uniqid();
    $titulopublicacion = $informacion['titulopublicacion'];
    $nombreEmpresa = $informacion['nombreEmpresa'];
    $categoria = ($informacion['categoria'] == 'Otro')? $informacion['otroServicio']: $informacion['categoria'] ;
    $horario = $informacion['horario'];
    $departamento = $informacion['departamento'];
    $ciudad = $informacion['ciudad'];
    $direccion = $informacion['direccion'];
    $telefono = $informacion['telefono'];
    $descripcion = $informacion['descripcion'];
    $step = $informacion['step'];
    $tipo = $informacion['tipo'];

    $instagram = $informacion['instagram'];
    $facebook = $informacion['facebook'];
    $twitter = $informacion['twitter'];

    $redesSociales = array(
        'instagram' => $instagram,
        'facebook' => $facebook,
        'twitter' => $twitter,
    );

    $redesSociales = json_encode($redesSociales, JSON_UNESCAPED_UNICODE);
    $infoNameEmail = getInfoNameEmailUsers($candidatoId);
    $email = $infoNameEmail['email'];

    // archivos
    $logo[0] = $archivos['logo'];
    $video[0] = $archivos['video'];
    $comprobanteImagen[0] = $archivos['comprobante'];
    $imagesProfeshional = $archivos['imagesProfeshional'];

    if(isset($imagesProfeshional) && ($imagesProfeshional != null)){
        $imgAjustadas = array();
        $i = 0;
        $name = array();
        $type = array();
        $tmp_name = array();
        $error = array();
        $size = array();

        foreach ($imagesProfeshional as $key => $value) {

            if( $key == 'name'){
                $name[$key] = $value;
            }
            if( $key == 'type'){
                $type[$key] = $value;
            }
            if( $key == 'tmp_name'){
                $tmp_name[$key] = $value;
            }
            if( $key == 'error'){
                $error[$key] = $value;
            }
            if( $key == 'size'){
                $size[$key] = $value;
            }

        }
        $cantidad = count($name['name']);
        for ($i = 0; $i <= $cantidad; $i++) {

            $imgAjustadas[$i] = array(
                'name' => $name['name'][$i],
                'type' => $type['type'][$i],
                'tmp_name' => $tmp_name['tmp_name'][$i],
                'error' => $error['error'][$i],
                'size' => $size['size'][$i],
            );

        }
        $imagesProfeshional = $imgAjustadas;

    }
    // subida logo
    $media = array();

    if( isset($logo) && $logo != null){
          $logo = imagesToArray($logo);

    $iii = array(
        'imagenes' => $logo,
        'carpeta' => '/pubprofesional',
        'serial' => $idPublicProfesional,
    );

    $logoJson = cargarImagenes($iii);
    $logoJson = json_decode($logoJson, true);
    $media['logo'] = $logoJson;
    $logo = $logoJson;
    $logo = json_encode($logo, JSON_UNESCAPED_UNICODE);
    }else{
        $logoJson = 'No';
        $media['logo'] = $logoJson;
        $logo = $logoJson;
        $logo = json_encode($logo, JSON_UNESCAPED_UNICODE);
    }
   // subida logo

    $imagesProfeshional = imagesToArray($imagesProfeshional);
    $iii = array(
        'imagenes' => $imagesProfeshional,
        'carpeta' => '/pubprofesional',
        'serial' => $idPublicProfesional,
    );
    $imagesProfeshionalJson = cargarImagenes($iii);
    $imagesProfeshionalJson = json_decode($imagesProfeshionalJson, true);
    $media['imagesProfeshional'] = $imagesProfeshionalJson;


    if( isset($video) && $video != null){
    $video = imagesToArray($video);
    $iii = array(
        'imagenes' => $video,
        'carpeta' => '/pubprofesional',
        'serial' => $idPublicProfesional,
    );
    $videoJson = cargarImagenes($iii);
    $videoJson = json_decode($videoJson, true);
    $media['video'] = $videoJson;
    }else{
        $videoJson = 'No';
        $media['video'] = $videoJson;
    }

    $media = json_encode($media, JSON_UNESCAPED_UNICODE);
   //  // factura
    $serialFactura = uniqid('FP-',true);
    $nombreFactura = $titulopublicacion;
    $mensaje = $infoFactura['mensajeOpcional'];
    $fechaCreada = date('d/m/Y');
    $fechaPagada = date('d/m/Y');
    $plan = $infoFactura['membresia'];
    $meses = 0;

    if (isset($plan) && $plan != '') {
        $meses = $plan;
        switch ($meses) {
            case 1:
            $plan = 'Un mes ($150)';
                break;
            case 2:
            $plan = 'Dos ($300)';
                break;
            case 3:
            $plan = 'Tres meses ($450)';
                break;
            case 4:
            $plan = 'Cuatro meses ($600)';
                break;
            case 5:
            $plan = 'Cinco meses ($750)';
                break;
            case 6:
            $plan = 'Seis meses ($900)';
                break;
            case 7:
            $plan = 'Siete meses ($1050)';
                break;
            case 8:
            $plan = 'Ocho meses ($1200)';
                break;
            case 9:
            $plan = 'Nueve meses ($1350)';
                break;
            case 10:
            $plan = '¡Un año por Diez meses!($1500)';
                break;
            default:
                // code...
                break;
        }
    }

    $comprobante = 1;
    $comprobanteImagen = imagesToArray($comprobanteImagen);
    $formato = $comprobanteImagen[0]['formato'];
    // subir comprobante
     $iii = array(
        'imagenes' => $comprobanteImagen,
        'carpeta' => '/pubprofesional',
        'serial' => $idPublicProfesional,
    );
    $comprobanteJson = cargarImagenes($iii);
    $referencia = $infoFactura['referencia'];
    $estado = 'En revisión por la administración';
    $tipoPago = 'Transferencia bancaria';
    $cuenta = $infoFactura['tipo'];
    $pagado = 0;

    $datosFacturaTabla = array(
        'serialFactura' => sanitize_text_field($serialFactura),
        'nombreFactura' => sanitize_text_field($nombreFactura),
        'mensaje' => sanitize_text_field($mensaje),
        'fechaCreada' => sanitize_text_field($fechaCreada),
        'fechaPagada' => sanitize_text_field($fechaPagada),
        'plan' => sanitize_text_field($plan),
        'meses' => sanitize_text_field($meses),
        'comprobante' => sanitize_text_field($comprobante),
        'referencia' => sanitize_text_field($referencia),
        'estado' => sanitize_text_field($estado),
        'tipoPago' => sanitize_text_field($tipoPago),
        'cuenta' => sanitize_text_field($cuenta),
        'formato' => sanitize_text_field($formato),
        'pagado' => 0,
        'imagenReferencia' => sanitize_text_field($comprobanteJson),
        'candidatoId' => sanitize_text_field($candidatoId)
    );

    $datosPublicacionProfesional = array(
        'id' => sanitize_text_field( $idPublicProfesional ),
        'candidatoId' => sanitize_text_field($candidatoId),
        'plan' => sanitize_text_field($plan),
        'estado' => 'En espera de aprobación del pago por:'. sanitize_text_field($plan),
        'nombreEmpresa' => sanitize_text_field( $nombreEmpresa ),
        'tituloPublicacion' => sanitize_text_field( $nombreFactura ),
        'categoria' => sanitize_text_field( $categoria ),
        'fechaCreada' => sanitize_text_field( $fechaCreada ),
        'detalles' => sanitize_text_field( $descripcion ),
        'logo' => sanitize_text_field( $logo ),
        'direccion' => sanitize_text_field( $direccion ),
        'departamento' => sanitize_text_field( $departamento ),
        'horario' => sanitize_text_field( $horario ),
        'ciudad' => sanitize_text_field( $ciudad ),
        'redesSociales' => sanitize_text_field( $redesSociales ),
        'telefono' => sanitize_text_field( $telefono ),
        'email' => sanitize_text_field( $email ),
        'media' => sanitize_text_field( $media ),
        'factura' => sanitize_text_field( $serialFactura ),
    );

    $formatoFacturaTabla = array(
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
        '%s'
    );

    $formatoInformacion = array(
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
        '%s'
    );

        $wpdb->insert($tablafacturacion_profesional, $datosFacturaTabla, $formatoFacturaTabla);
        $wpdb->flush();

        $wpdb->insert($tablapublic_profesional, $datosPublicacionProfesional, $formatoInformacion);
        $wpdb->flush();

}


