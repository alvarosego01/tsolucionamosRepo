<?php

function generarCodigo($length = 10)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-"), 0, $length);
}

function contractByUser($newContractTemplate)
{

    if (isset($_POST['dataContrato']) && is_user_logged_in()) {
        global $wpdb;

        $tablaOferta = $wpdb->prefix . 'ofertalaboral';

        $dataEntrada = preg_replace('/\\\\\"/', "\"", $_POST['dataContrato']);
        // transforma el string a un array asociativo
        $dataEntrada = json_decode($dataEntrada, true);

        // print_r($dataEntrada);
        // return;
        $idOferta = $dataEntrada['ofertaId'];

        $gg = $wpdb->get_results("SELECT serialOferta FROM $tablaOferta WHERE id = '$idOferta'", ARRAY_A);

        $serial = $gg[0]['serialOferta'];

        $current = $dataEntrada['current'];

        $can = $dataEntrada['can'];
        $fam = $dataEntrada['fam'];
        // $serial = $serial;
        $currentId = (get_current_user_id() === $current) ? $current : 0;

        // se verifica si esta petición actual ya tiene contrato existente.
        $S = array(
            'can' => $can,
            'fam' => $fam,
            'ofertaId' => $idOferta
        );
        $exist = dbContractExistValidate($S);


        if (validateUserProfileOwner($fam, $currentId, 'familia')) {
            // si existe contrato entocnes
            if (isset($currentId) && isset($dataEntrada['can']) && isset($dataEntrada['fam']) && isset($serial) && ( count($exist) > 0)) {

                $currentcurrent = $currentId;
                $cancan = $dataEntrada['can'];
                $famfam = $dataEntrada['fam'];
                $serialserial = $serial;

                viewInfoContractTemplate($currentcurrent, $cancan, $famfam, $serialserial);

            }
            if (isset($currentId) && isset($dataEntrada['can']) && isset($dataEntrada['fam']) && isset($serial) && ( count($exist) == 0)) {

                $currentcurrent = $currentId;
                $cancan = $dataEntrada['can'];
                $famfam = $dataEntrada['fam'];
                $serialserial = $serial;

                preContractTemplateFamily($can, $fam, $serial, $newContractTemplate);

            }

        } elseif (validateUserProfileOwner($can, $currentId, 'candidata')) {

            if ( count($exist) == 0) {

                preContractTemplate($can, $fam, $serial, $newContractTemplate);

            } elseif (isset($currentId) && isset($dataEntrada['can']) && isset($dataEntrada['fam']) && isset($serial) && ( count($exist) > 0)) {

                $currentcurrent = $currentId;
                $cancan = $dataEntrada['can'];
                $famfam = $dataEntrada['fam'];
                $serialserial = $serial;

                viewInfoContractTemplate($currentcurrent, $cancan, $famfam, $serialserial);
            }
        } elseif (validateUserProfileOwner($current, $currentId, 'adminTsoluciono')) {
            if ( count($exist) == 0) {



                // print_r($newContractTemplate);
                // return;
                // preContractTemplate($can, $fam, $serial, $newContractTemplate);
                preContractTemplateFamily($can, $fam, $serial, $newContractTemplate);

                // aqui poner un mensaje de error luego

            } elseif (isset($currentId) && isset($dataEntrada['can']) && isset($dataEntrada['fam']) && isset($serial) && ( count($exist) > 0)) {

                $currentcurrent = $currentId;
                $cancan = $dataEntrada['can'];
                $famfam = $dataEntrada['fam'];
                $serialserial = $serial;

                viewInfoContractTemplate($currentcurrent, $cancan, $famfam, $serialserial);
            }
        } else {
            $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
            die();
        }

    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }

}

function viewInfoContractTemplate($currentId, $can, $fam, $serial)
{

    $pagina = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    $data = array(
        'currentId' => $currentId,
        'can' => $can,
        'fam' => $fam,
        'serial' => $serial,
    );

    $info = getInfoContractExist($data);

//    esto para quitar slashes del texto contrato
    $contratoGenerado = $info[0]['textoContrato'];
    $x = stripslashes($contratoGenerado);
    $textoContrat = $x;
// print_r($info);

    $currentId = $data['currentId'];

    if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {
        $pagina = esc_url(get_permalink(get_page_by_title('Administración Tsoluciono')));
    }

    ?>


  <div id="textoContrato1" class="textoContrato row">
    <?php
// esto para quitar los slashes que mysql le añade al codigo
    echo $textoContrat;
// print_r($data);
     ?>

  </div>

   <div class="envio_formulario row justify-content-around">

    <a class="botonWam btn btn-danger" href="<?php echo $pagina ?>"> <i class="fa fa-long-arrow-left" aria-hidden="true"></i>Regresar</a>

  </div>

<?php }


// etapa de la creación
function preContractTemplateFamily($can, $fam, $serial, $newContractTemplate)
{
    $data = array(
        'can' => $can,
        'fam' => $fam,
        'serial' => $serial,
    );

    $infoContract = dbGetAllOfferInfo($serial);



    $datosUsuarios = array(
        'familia' => datosUsuarios('familia', $fam, $can),
        'candidata' => datosUsuarios('candidata', $fam, $can),
    );


    $codigoContrato = uniqid('c-', true);

    $pagina = esc_url(get_permalink(get_page_by_title('Home')));

    $textoContrato = $newContractTemplate;

    // datos familia
    $i = 'familia';
    $textoContrato = str_replace("{{nombreFam}}",'<strong>' .$datosUsuarios[$i]['nombreFam'].'</strong>', $textoContrato);

    $textoContrato = str_replace("{{rolFam}}", '<a href="' . $datosUsuarios[$i]['urlFam'] . '">' . $datosUsuarios[$i]['rolFam'] . '</a>', $textoContrato);

    // $textoContrato = str_replace("{{contratista}}", $datosUsuarios[$i]['nombreFam'], $textoContrato);

    $codFirma = $infoContract[0]['firmaCandidata'];
    $codFirma = getSignUser($codFirma);
    // print_r($codFirma);
    $firma = '<div id="firmaContratista"><img src="' . $codFirma['firma'] . '"></div>';
    $textoContrato = str_replace("{{firmaContratista}}", $firma, $textoContrato);

    $textoContrato = str_replace("{{direccionRegistroFam}}", '<strong>'.$datosUsuarios[$i]['direccionRegistroFam'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{documentoFam}}", '<strong>'.$datosUsuarios[$i]['documentoFam'].'</strong>', $textoContrato);

    // datos candidata
    $i = 'candidata';
    $textoContrato = str_replace("{{nombreCan}}", '<strong>'.$datosUsuarios[$i]['nombreCan'].'</strong>', $textoContrato);

    $textoContrato = str_replace("{{direccionRegistroCan}}", '<strong>'.$datosUsuarios[$i]['direccionRegistroCan'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{documentoCan}}", '<strong>'.$datosUsuarios[$i]['documentoCan'].'</strong>', $textoContrato);



    $c = '<strong id="codContrato"> En espera </strong>';
    $textoContrato = str_replace("{{serialContrato}}", $c, $textoContrato);



    // para el nuevo contrato
    // logoEmpresa
    // fechaInicio
    // fechaFinal
    // nombreFam
    // documentoFam
    // direccionRegistroFam
    // nombreCan
    // documentoCan
    // direccionRegistroCan
    // sueldoVacante
    // diaActual
    // mesActual
    // añoActual
    // nombreFam
    // firmaContratista
    // nombreCan
    // firmaCandidata
    // serialContrato



    $fechaActual = date('d/m/Y');

    $fechaActual = tranformMeses($fechaActual);


    $infoContract = $infoContract[0];

    $fechaCreacion = date('d/m/Y');
    $fechaInicio = $fechaCreacion;
    $fechaFin= date('d/m/Y', strtotime(' + 90 days'));



    $imag = '<img src="">';
    $textoContrato = str_replace("{{firmaCandidata}}", $imag, $textoContrato);

    $textoContrato = str_replace("{{fechaInicio}}", setCalendarContract($fechaInicio), $textoContrato);
    $textoContrato = str_replace("{{fechaFinal}}", setCalendarContract($fechaFin), $textoContrato);

    $textoContrato = str_replace("{{sueldoVacante}}", '<strong>$'.$infoContract['sueldo'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{diaActual}}", $fechaActual['dia'].' ', $textoContrato);
    $textoContrato = str_replace("{{mesActual}}", $fechaActual['mes'], $textoContrato);
    $textoContrato = str_replace("{{añoActual}}", $fechaActual['anio'], $textoContrato);


    return preInfoContractFamilyTemplate($data, $textoContrato);

}

function setCalendarContract($date){

    $dateTransform = tranformMeses($date);
    $texto = "";

    $mes = $dateTransform['mes'];
    $dia = $dateTransform['dia'];
    $anio = $dateTransform['anio'];

    $texto = "<div class='dat'>
    <div class='mes'>
        $mes
    </div>
    <div class='dia'>
        $dia
    </div>
    <div class='anio'>
        $anio
    </div>
    </div>";

    return $texto;

}

// estoy acá
function preContractTemplate($can, $fam, $serial, $newContractTemplate)
{
    $data = array(
        'can' => $can,
        'fam' => $fam,
        'serial' => $serial,
    );

    $infoContract = dbGetAllOfferInfo($serial);



    $datosUsuarios = array(
        'familia' => datosUsuarios('familia', $fam, $can),
        'candidata' => datosUsuarios('candidata', $fam, $can),
    );


    $codigoContrato = uniqid('C-', true);

    $pagina = esc_url(get_permalink(get_page_by_title('Home')));

    $textoContrato = $newContractTemplate;

    // datos familia
    $i = 'familia';
    $textoContrato = str_replace("{{nombreFam}}",'<strong>' .$datosUsuarios[$i]['nombreFam'].'</strong>', $textoContrato);

    $textoContrato = str_replace("{{rolFam}}", '<a href="' . $datosUsuarios[$i]['urlFam'] . '">' . $datosUsuarios[$i]['rolFam'] . '</a>', $textoContrato);

    // $textoContrato = str_replace("{{contratista}}", $datosUsuarios[$i]['nombreFam'], $textoContrato);

    $codFirma = $infoContract[0]['firmaCandidata'];
    $codFirma = getSignUser($codFirma);
    // print_r($codFirma);
    $firma = '<div id="firmaContratista"><img src="' . $codFirma['firma'] . '"></div>';
    $textoContrato = str_replace("{{firmaContratista}}", $firma, $textoContrato);

    $textoContrato = str_replace("{{direccionRegistroFam}}", '<strong>'.$datosUsuarios[$i]['direccionRegistroFam'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{documentoFam}}", '<strong>'.$datosUsuarios[$i]['documentoFam'].'</strong>', $textoContrato);

    // datos candidata
    $i = 'candidata';
    $textoContrato = str_replace("{{nombreCan}}", '<strong>'.$datosUsuarios[$i]['nombreCan'].'</strong>', $textoContrato);

    $textoContrato = str_replace("{{direccionRegistroCan}}", '<strong>'.$datosUsuarios[$i]['direccionRegistroCan'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{documentoCan}}", '<strong>'.$datosUsuarios[$i]['documentoCan'].'</strong>', $textoContrato);



    $c = '<br><strong id="codContrato">'.$codigoContrato.'</strong>';
    $textoContrato = str_replace("{{serialContrato}}", $c, $textoContrato);



    // para el nuevo contrato
    // logoEmpresa
    // fechaInicio
    // fechaFinal
    // nombreFam
    // documentoFam
    // direccionRegistroFam
    // nombreCan
    // documentoCan
    // direccionRegistroCan
    // sueldoVacante
    // diaActual
    // mesActual
    // añoActual
    // nombreFam
    // firmaContratista
    // nombreCan
    // firmaCandidata
    // serialContrato



    $fechaActual = date('d/m/Y');



    $fechaActual = tranformMeses($fechaActual);


    $fechaCreacion = date('d/m/Y');
    $fechaInicio = $fechaCreacion;
    $fechaFin= date('d/m/Y', strtotime(' + 90 days'));


    $infoContract = $infoContract[0];

    $fx = getSignUser($can);

    $existeFirma = ( (isset($fx['firma'])) && ($fx['firma'] != null) && ($fx['firma'] != '') )? $fx['firma'] : null;

    $imag = '<img src="'.$existeFirma.'">';
    $textoContrato = str_replace("{{firmaCandidata}}", $imag, $textoContrato);

    $textoContrato = str_replace("{{fechaInicio}}", setCalendarContract($fechaInicio), $textoContrato);
    $textoContrato = str_replace("{{fechaFinal}}", setCalendarContract($fechaFin), $textoContrato);

    $textoContrato = str_replace("{{sueldoVacante}}", '<strong>'.$infoContract['sueldo'].'</strong>', $textoContrato);
    $textoContrato = str_replace("{{diaActual}}", $fechaActual['dia'].' ', $textoContrato);
    $textoContrato = str_replace("{{mesActual}}", $fechaActual['mes'], $textoContrato);
    $textoContrato = str_replace("{{añoActual}}", $fechaActual['anio'], $textoContrato);


    return aceptarContrato($data, $textoContrato);

}

function datosUsuarios($tipoTusuario, $fam = 0, $can = 0)
{
    global $wpdb;

    $id1 = $fam;
    $id2 = $can;

    if ($tipoTusuario == 'familia') {

        $usuario = get_user_meta($id1);
        // nombre
        $nombre = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];


        // rol
        global $wp_roles;

        $u = get_userdata($id1);
        //
        $role = array_shift($u->roles);
        $rolFamilia = $wp_roles->roles[$role]['name'];
        // urlPerfil
        $urlFamilia = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

        $DocumentoIdentidad = $usuario['DocumentoIdentidad'][0];
        $direccionRegistroFam = (isset($usuario['direccion'][0]))? $usuario['direccion'][0]: 'Direccion';



    // para el nuevo contrato
    // logoEmpresa
    // fechaInicio
    // fechaFinal
    // nombreFam
    // documentoFam
    // direccionRegistroFam
    // nombreCan
    // documentoCan
    // direccionRegistroCan
    // sueldoVacante
    // diaActual
    // mesActual
    // añoActual
    // nombreFam
    // firmaContratista
    // nombreCan
    // firmaCandidata
    // serialContrato


        $datosUsuarios = array(
            'nombreFam' => $nombre,
            'rolFam' => $rolFamilia,
            'urlFam' => $urlFamilia,
            'documentoFam' => $DocumentoIdentidad,
            'direccionRegistroFam' => $direccionRegistroFam
        );

        return $datosUsuarios;

    } elseif ($tipoTusuario == 'candidata') {

        $usuario = get_user_meta($id2);
        // nombre
        $nombre = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];
        // rol
        global $wp_roles;

        $u = get_userdata($id2);
        $role = array_shift($u->roles);
        $rolFamilia = $wp_roles->roles[$role]['name'];
        // urlPerfil
        $urlFamilia = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];
        $DocumentoIdentidad = $usuario['DocumentoIdentidad'][0];

        $direccionRegistroCan = (isset($usuario['direccion'][0]))? $usuario['direccion'][0]: 'Direccion';

        $datosUsuarios = array(
            'nombreCan' => $nombre,
            'rolCan' => $rolFamilia,
            'urlCan' => $urlFamilia,
            'documentoCan' => $DocumentoIdentidad,
            'direccionRegistroCan' => $direccionRegistroCan
        );

        return $datosUsuarios;

    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Home')));
        echo '<script>window.location = "' . $pagina . '"</script>';
    }

}

function preInfoContractFamilyTemplate($data, $textoContrat)
{
    $can = $data['can'];
    $fam = $data['fam'];
    $serial = $data['serial'];
    $idCurrent = get_current_user_id();

    $pagina = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    if(validateUserProfileOwner($idCurrent, $idCurrent, 'adminTsoluciono')){

        $pagina = esc_url(get_permalink(get_page_by_title('Administración Tsoluciono')));
    }

    $dd = array(
        'can' => $can,
        'fam' => $fam,
        'serial' => $serial,
        'current' => get_current_user_id(),
    );

    $dataEntrada = preg_replace('/\\\\\"/', "\"", $_POST['dataContrato']);
// transforma el string a un array asociativo
$dataEntrada = json_decode($dataEntrada, true);

$idOferta = $dataEntrada['ofertaId'];


    $idCurrent = get_current_user_id();

    $S = array(
    'can' => $can,
    'fam' => $fam,
    'ofertaId' => $idOferta
    );
    $infoExist = dbContractExistValidate($S, true);

    // $infoExist = dbGetContractDataOffer($dd);

    ?>


  <div id="textoContrato1" class="textoContrato row">
    <?php
// esto para quitar los slashes que mysql le añade al codigo
    echo $textoContrat;
    ?>

  </div>


  <?php
// se valida si ya existe un envio de propuesta de contrato o si no.
    if ((isset($infoExist)) && (count($infoExist) > 0)) {
        // si eres admin entonces
        ?>

        <?php if( isset( $infoExist[0]['estado'])  ){?>
            <p style="text-align: center">
                <?php echo $infoExist[0]['estado']; ?>
            </p>
        <?php } ?>

        <div class="envio_formulario row justify-content-around">
          <a class="botonWam btn btn-danger" href="<?php echo $pagina ?>">Atras</a>
        </div>

    <?php } else {
        // si no existe una propuesta enviada todavia entonces.
        $x = json_encode($dd);
        ?>
        <div class="envio_formulario row justify-content-around">
          <a class="botonWam btn btn-danger" href="<?php echo $pagina ?>">Atras</a>
          <button class='botonWam btn btn-success' onclick='sendContractRequest(<?php echo $x; ?>)'>Enviar propuesta de contrato</button>
        </div>
   <?Php }?>


<?php }

function aceptarContrato($data, $textoContrat)
{
    $can = $data['can'];
    $fam = $data['fam'];
    $serial = $data['serial'];
    $pagina = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    ?>



 <div class="formSentOffer2" id="aceptarContrato" style="display:none;">
  <form action="" method="post" class="formData">
            <div class="field campoFirmaCandidata">
                <label for="firmaCandidata">Firma aqui</label>
                <div id="firmaCandidata" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
                <input type="hidden" name="jsonFirmaCandidata" id="jsonFirmaCandidata">
                <div class="botones">
                <a class="btn btn-danger borrar">Borrar</a>
                </div>
                <small class="validateMessage"></small>
            </div>
        </form>
</div>


  <div id="textoContrato1" class="textoContrato row">
    <?php
// esto para quitar los slashes que mysql le añade al codigo
    echo $textoContrat;
    ?>

  </div>


  <div class="envio_formulario row justify-content-around">
    <a class="botonWam btn btn-danger" href="<?php echo $pagina ?>">Cancelar</a>

    <!-- <button class="botonWam btn btn-primary" onclick="sendAceptarContrato('<?php echo $can ?>','<?php echo $serial ?>');">Aceptar propuesta de contrato</button> -->


    <?php
    // verificar si existe firma
    $fx = getSignUser(get_current_user_id());
    ?>

    <?php if(isset($fx['firma']) && ($fx['firma'] != '')){
            $x = array(
                'idPostulan' => $can,
                'serial' => $serial
            );
            $x = json_encode($x);
        ?>
        <button class="btn btn-success" onclick='acceptContractSg(<?php echo $x ?>)' >
                Firmar y continuar
            </button>
        <?php }else{
            // formulario de aceptar contrato sin firma
            $x = array(
                'idPostulan' => $can,
                'serial' => $serial
            );
            $x = json_encode($x);
            ?>

    <div class="formFirma" id="formFirma" style="display:none;">
        <form action="" method="post" class="formData">
            <div class="field form-group firmaCandidata">
                <label for="firmaCandidata">Firma aqui</label>
                <div id="firmaCandidata" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
                <input type="hidden" class="form-control form-control-sm" name="jsonfirmaCandidata" id="jsonfirmaCandidata">
                <div class="botones">
                    <a class="botoWeb borrar">Borrar</a>
                </div>
                <small class="validateMessage"></small>
            </div>

        </form>
    </div>

        <button class="btn btn-success" onclick='acceptContractNoSg(<?php echo $x ?>)'>
                Firmar y continuar
        </button>
      <?php } ?>

  </div>




<?php }

add_action('wp_ajax_acceptContract', 'acceptContract');
add_action('wp_ajax_nopriv_acceptContract', 'acceptContract');

function acceptContract()
{

    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, 'candidata')) {

        if ((isset($_POST['acceptContractData'])) && (isset($_POST['contractText']))) {

            $data = preg_replace('/\\\\\"/', "\"", $_POST['acceptContractData']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $textoContrato = $_POST['contractText'];

            // print_r($textoContrato);
            dbAcceptContract($data, $textoContrato);
            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_ContractRequest', 'ContractRequest');
add_action('wp_ajax_nopriv_ContractRequest', 'ContractRequest');

function ContractRequest()
{

    $currentId = get_current_user_id();
    if ((validateUserProfileOwner($currentId, $currentId, 'familia')) || (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) ) {

        if ((isset($_POST['contractRequest']))) {

            global $wpdb;
            $data = preg_replace('/\\\\\"/', "\"", $_POST['contractRequest']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $tabla = $wpdb->prefix . 'contratos';

            $tabla = $wpdb->prefix . 'ofertalaboral';

            $serial = $data['serial'];
            $can = $data['can'];

            $x = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);

            $y = array(
                'idOferta' => $x[0]['id'],
                'id' => uniqid() . uniqid(),
                'idPostulan' => $can,
                'visto' => 0,
                'estado' => 'En espera de revisión de contrato por parte del candidato',
            );


            dbSelectPostulantContrat($y);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}