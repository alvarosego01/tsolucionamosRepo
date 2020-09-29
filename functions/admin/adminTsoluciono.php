<?php

  // $currentId = get_current_user_id();
if ( is_user_logged_in() && validateUserProfileOwner(get_current_user_id(), get_current_user_id(), 'adminTsoluciono')) {
  ?>



  <style>
    #menu-item-1387{
      display: none!important;
    }
  </style>

    <?php

}


function formDetailsIntegrateCand($data, $aei){
  global $wp_roles;
  global $wpdb;

  $ofertalaboralTabla = $wpdb->prefix . 'ofertalaboral';
    $usuarios_recomendadosTabla = $wpdb->prefix . 'usuarios_recomendados';
    $proceso_contrato = $wpdb->prefix . 'proceso_contrato';

  $departamentos = getDepartaments();


  $tipoServicios = $wpdb->get_results("SELECT oferta.* from $ofertalaboralTabla AS oferta INNER JOIN $usuarios_recomendadosTabla AS recomendados ON oferta.id = recomendados.idOferta", ARRAY_A);


    $seleccionados = $wpdb->get_results("SELECT proceso.* from $proceso_contrato as proceso where proceso.ofertaId = '$aei' ", ARRAY_A);
    $wpdb->flush();

    foreach ($seleccionados as $key => $value) {
        # code...
        $idd = $value['candidataId'];
        foreach ($data as $key2 => $value2) {
            # code...}
            $idd2 = $value2['idCandidato'];
            if($idd == $idd2){
                unset($data[$key2]);
            }
        }
    }

  ?>

  <div class="formDetailsIntegrateCand" id="formDetailsIntegrateCand" style="display:none;">


  <form action="" class="optionsFilter">
  <label>Filtrar candidatos por</label>

  <div class="row">
      <div class="field col form-group nombre">
        <label for="servicio">Nombre</label>
          <input  name="nombre" class="form-control form-control-sm" type="text">
      </div>

      <div class="field col form-group departamentos">
        <label for="servicio">Departamento</label>
        <select class="form-control form-control-sm" name="departamentos">
        <option value="">-</option>
          <?php
          foreach ($departamentos as $key => $value) { ?>
            <option><?php echo $value; ?></option>
          <?php } ?>
        </select>

      </div>
</div>

<div class="row">
    <div class="field col form-group servicio">
    <label for="servicio">Servicio</label>
        <select class="form-control form-control-sm" name="servicio">
            <option value="">-</option>
          <?php
          foreach ($tipoServicios as $key => $value) { ?>
            <option><?php echo $value['tipoServicio']; ?></option>
          <?php } ?>
        </select>

    </div>

    <div class="form-group field col recomendabilidad">
    <label for="recomendabilidad">Recomendabilidad</label>

    <div class="star-rating">
        <span class="fa fa-star-o" data-rating="1"></span>
        <span class="fa fa-star-o" data-rating="2"></span>
        <span class="fa fa-star-o" data-rating="3"></span>
        <span class="fa fa-star-o" data-rating="4"></span>
        <span class="fa fa-star-o" data-rating="5"></span>
        <input type="hidden" name="recomendabilidad" class="rating-value form-control form-control-sm" value="0">
    </div>
    <small class="validateMessage"></small>
  </div>

</div>

<div class="buttonCustom row">
  <?php
  $xxx = array(
    'ofertaId' => $aei,
    'tipo' => 'todos'
  );
  $xxx = json_encode($xxx);


  ?>
<button type="button" class="btn btn-primary btn-sm" onclick='refreshInfoAddCands(<?php echo $xxx ?>)' >
    Todos
<button type="button" class="btn btn-primary btn-sm" onclick="refreshInfoAddCands('<?php echo $aei ?>')" >
    Actualizar
  </button>

</div>

  </form>

  <form action="" method="post" class="formData">

    <div class="row">
      <div class="field col form-group candidatos">
        <label for="servicio">Candidatos entrevistados previamente</label>
        <select class="form-control form-control-sm" name="candidatos">

        <option value="">Selecciona un candidato</option>

        <?php foreach ($data as $key => $value) {

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

  </div>


<?php
}

function formDetailsChangeDateAdmin(){  ?>
  <div class="formDetailsChangeDateAdmin" id="formDetailsChangeDateAdmin" style="display:none;">

  <form action="" method="post" class="formData">

    <div class="row">
    <div class="form-group col field date">
      <label for="date">Nueva fecha</label>
      <input class="form-control" name="date" type="text" id="date">
      <small class="validateMessage"></small>
    </div>


    <div class="form-group col field hora">
      <label for="hora">Nueva hora</label>
      <input class="form-control" name="hora" type="text" id="hora">
      <small class="validateMessage"></small>
    </div>


    </div>
</form>
  </div>


<?php
}


function adminInfoVacanteForm()
{

// estoy aqui

global $wpdb;
  $tabla = $wpdb->prefix . 'configuracionesadmin';
    $infoSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
    $infoSettings = $infoSettings[0];
    if( isset($infoSettings['teamDatos']) ){
      $teamDatos = $infoSettings['teamDatos'];
      $teamDatos = json_decode($teamDatos, true);
    }

  ?>

  <div class="formTipoEntrevistainicio" id="formTipoEntrevistainicio" style="display:none">
  <form class="formData">
    <div class="row">
      <div class="form-group field col tipoEntrevista">
        <label for="tipoEntrevista">Tipo de entrevista</label>
        <select class="form-control" id="tipoEntrevista" name="tipoEntrevista">

        <option>
        	Pruebas Psico laborales
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



  <div class="formTipoEntrevista" id="formTipoEntrevista" style="display:none">
  <form class="formData">
    <div class="row">
      <div class="form-group field col tipoEntrevista">
        <label for="tipoEntrevista">Tipo de entrevista</label>
        <select class="form-control" id="tipoEntrevista" name="tipoEntrevista">

        <option>
            Entrevista con la Familia
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
<?php

}

function templateTabsAdminTsoluciono($args = array())
{
    global $wpdb;
    $defaults = array(
        "rows" => 0,
    );
    $args = wp_parse_args($args, $defaults); ?>

<div class="tabsAdminTsoluciono">
<div class="row globalPanel">

<div class="navOpc col-3">

  <h5>Administración</h5>
  <ul>
  <li><a href="#tab1">Gestión de vacantes</a></li>
  <li><a href="#tab11">Publicaciones Tsolucionamos</a></li>
  <li><a href="#tab2">En proceso de entrevistas</a></li>
  <li><a href="#tab3">Familias con contratos</a></li>
  <li><a href="#tab4">Balance y reportes</a></li>
  <li><a href="#tab5">Notificaciones</a></li>
  <li><a href="#tab7">Facturas</a></li>

  <li><a href="#tab10">Bolsa de trabajo</a></li>
  <li><a href="#tab6">Configuraciones</a></li>

  </ul>

</div>

  <div class="col-9 mainSections">
    <section id="content8">
      <?php adminTsoluciono8(); ?>
    </section>
    <section id="content9">
      <?php adminTsoluciono9(); ?>
    </section>

    <section id="content11">
      <?php adminTsoluciono11(); ?>
    </section>

     <section id="content10">
      <?php adminTsoluciono10(); ?>
    </section>

    <section id="content1">
      <?php adminTsoluciono1(); ?>
    </section>

    <section id="content2">
      <?php adminTsoluciono2(); ?>
    </section>

    <section id="content3">
      <?php adminTsoluciono3(); ?>
    </section>

    <section id="content4">
      <?php adminTsoluciono4(); ?>
    </section>

    <section id="content5">
      <?php adminTsoluciono5(); ?>
    </section>


    <section id="content7">
      <?php adminTsoluciono7(); ?>
    </section>


    <section id="content6">
      <?php adminTsoluciono6(); ?>
    </section>



  </div>

</div>
</div>

<?php
spinnerLoad();
}add_shortcode('templateTabsAdminTsoluciono', 'templateTabsAdminTsoluciono');

add_action('wp_ajax_containerVacantAdmin', 'containerVacantAdmin');
add_action('wp_ajax_nopriv_containerVacantAdmin', 'containerVacantAdmin');

function containerVacantAdmin()
{
    $dataPostulados = array(
        'panel' => 'postulantes',
        'page' => 1,
    );
    $dataSinVerificar = array(
        'panel' => 'porVerificar',
        'page' => 1,
    );

    $dataTodos = array(
        'panel' => 'todos',
        'page' => 1,
    );

    if (isset($_REQUEST['dataVacantAdmin']) && !empty($_REQUEST['dataVacantAdmin'])) {
        // recibe json y quita los slash
        $d = preg_replace('/\\\\\"/', "\"", $_REQUEST['dataVacantAdmin']);
        // transforma el string a un array asociativo
        $d = json_decode($d, true);

        $retorno = $d;
    } else {
        $retorno = $dataTodos;
    }

    $data = dbGetVacantsGestion1($retorno);

    $t = $retorno['panel'];


    // el cambio

    if (count($data) > 0) {
        $dataVacant = array(); ?>
<?php if ($t == 'postulantes') {?>
<h5>Publicaciones con postulaciones</h5>
<?php } elseif ($t == 'porVerificar') {?>
<h5>Publicaciones sin verificación</h5>
<?php } elseif ($t == 'todos') {?>
<h5>Todas las publicaciones</h5>

<?php } ?>
<div class="row list-group">
<?php

 ?>

  <?php foreach ($data as $r) {
            $serial = $r['oferta']['serialOferta'];
            $idOferta = $r['oferta']['idOferta'];
            $postulantes = $r['postulantes'];
            $contratistaId = $r['contratistaId'];
            $usuario = get_user_meta($contratistaId);
            $descripciónOferta = $r['oferta']['descripcionExtra'];
            $fechaCreado = $r['oferta']['fechaCreacion'];
            $nombreTrabajo = $r['oferta']['nombreTrabajo'];
            $tipoPublic = $r['oferta']['tipoPublic'];
            $sueldo = $r['oferta']['sueldo'];
            $imagenes = $r['oferta']['imagenes'];
            $imagenes = json_decode($imagenes, true);
            $imgPrincipal = $imagenes['principal']['src'];
            $tipoServicio = $r['oferta']['tipoServicio'];

            // nombre
            $nombreContratista = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            // urlPerfil
            $urlContratista = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

            // numero de postulantes en vacante
            if ($postulantes != null) {
                $n = count($postulantes);
            }
            // numero de postulantes seleccionados

            $p = dbGetPostulantSelectedByOffeId($idOferta);

            // print_r($p);

            if ($p['etapas'] != null) {
                $p = ((count($p['etapas']) > 0) && ($p['etapas'] != null)) ? count($p['etapas']) : 0;
            } else {
                $p = 0;
            }

            // url a la oferta
            $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));
            $urlOferta = $pagina . "?serial=$serial"; ?>

  <div class="list-group-item list-group-item-action flex-column align-items-start col-10 align-self-center">
    <a href="<?php echo $urlOferta; ?>">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1"><?php echo $nombreTrabajo; ?>
        </h5>
        <small>Publicado <?php echo $fechaCreado; ?></small>
      </div>
      <p class="mb-1">Descripción: <?php echo $descripciónOferta; ?>
      </p>
      <?php if ($t == 'postulantes') {?>
      <p>Usuarios postulados: <span class="badge badge-primary badge-pill"><?php echo $n; ?> <br> </span></p>
      <?php } ?>
    </a>
    <?php if ($t == 'porVerificar') {
                $user = getUserGeneralInfo($contratistaId);
                unset($user['userMeta']['um_account_secure_fields']);
                unset($r['oferta']['firmaCandidata']);
                unset($r['oferta']['idOferta']);
                unset($r['oferta']['id']);

                $u = array(
                    'nb' => $user['userMeta']['first_name'][0],
                    'tl' => $user['userMeta']['mobile_number'][0],
                );

                $g = array(
                    'fechaRevisado' => date('d/m/Y'),
                    'adminId' => get_current_user_id(),
                );

                $o = array(
                    'user' => $u,
                    'vacante' => $r,
                    'gestion' => $g,
                );

                $v = json_encode($o); ?>

    <div class="opc d-flex justify-content-end">
      <button onclick='verificaVacante(<?php echo $v; ?>)'
        type='button' class='btn btn-primary'>
        Verificar
      </button>
    </div>
    <?php
            } ?>
    <?php if ($t == 'todos') {
                $user = getUserGeneralInfo($contratistaId);
                unset($user['userMeta']['um_account_secure_fields']);
                unset($r['oferta']['firmaCandidata']);
                unset($r['oferta']['idOferta']);
                unset($r['oferta']['id']);

                $u = array(
                    'nb' => $user['userMeta']['first_name'][0],
                    'tl' => $user['userMeta']['mobile_number'][0],
                );

                $g = array(
                    'fechaRevisado' => date('d/m/Y'),
                    'adminId' => get_current_user_id(),
                );

                $o = array(
                    'user' => $u,
                    'vacante' => $r,
                    'gestion' => $g,
                );

                $v = json_encode($o); ?>

    <div class="opc d-flex justify-content-end">
      <button onclick='opcionesVacante(<?php echo $v; ?>)'
        type='button' class='btn btn-primary'>
        <i class="fa fa-cog" aria-hidden="true"></i>
      </button>
    </div>
    <?php
            } ?>
  </div>
  <?php
        } ?>

</div>
<?php
    } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
}

function adminTsoluciono1()
{
    global $wpdb;

    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {
        $dataPostulados = array(
            'panel' => 'postulantes',
            'page' => 1,
        );
        $dataSinVerificar = array(
            'panel' => 'porVerificar',
            'page' => 1,
        );

        $dataTodos = array(
            'panel' => 'todos',
            'page' => 1,
        );

        // contadores de vacantes
        $conPost = ((count(dbGetVacantsGestion1($dataPostulados)) > 0) && (dbGetVacantsGestion1($dataPostulados) != null)) ? count(dbGetVacantsGestion1($dataPostulados)) : 0;
        $sinVerf = ((count(dbGetVacantsGestion1($dataSinVerificar)) > 0) && (dbGetVacantsGestion1($dataSinVerificar) != null)) ? count(dbGetVacantsGestion1($dataSinVerificar)) : 0;
        $todos = ((count(dbGetVacantsGestion1($dataTodos)) > 0) && (dbGetVacantsGestion1($dataTodos) != null)) ? count(dbGetVacantsGestion1($dataTodos)) : 0;

        $nros = array(
            'conPost' => count(dbGetVacantsGestion1($conPost)),
            'sinVerf' => count(dbGetVacantsGestion1($sinVerf)),
            'todos' => count(dbGetVacantsGestion1($todos)),
        );

        $dataPostulados = json_encode($dataPostulados);

        $dataSinVerificar = json_encode($dataSinVerificar);

        $dataTodos = json_encode($dataTodos); ?>

<div class="opcVacants">
  <div class="list-group">

    <button onclick='load(event,<?php echo $dataTodos ?>)'
      type='button' class='list-group-item list-group-item-action active'>
      Todas las vacantes <span class="badge  badge-info"><?php echo $todos ?> </span>
    </button>
    <button onclick='load(event,<?php echo $dataSinVerificar ?>)'
      type='button' class='list-group-item list-group-item-action'>
      Sin verificación <span class="badge  badge-info"><?php echo $sinVerf ?> </span>
    </button>
    <button onclick='load(event,<?php echo $dataPostulados ?>)'
      type='button' class='list-group-item list-group-item-action'>
      Con postulados <span class="badge  badge-info"><?php echo $conPost ?> </span>
    </button>

  </div>
</div>

<div id="gestionVacantes">

  <?php containerVacantAdmin(); ?>

</div>

<div class="helpVacants">

<div class="buttonCustom">
  <?php
  // do_shortcode('[viewLaboralOfferButton profile_id="' . $id . '" currentUser_id="' . $currentId . '"]');

  $nOfertaUrl = esc_url(get_permalink(get_page_by_title('Nueva oferta laboral')));

  ?>
    <form action="<?php echo $nOfertaUrl ?>" method="post">
        <input type="hidden" name="pg" value="1">
       <button id="profileLaboralOfferButton" class="um-alt btn btn-primary">
       <i class="fa fa-heart" aria-hidden="true"></i> Asistir a crear vacante
      </button>
    </form>

 <form action="<?php echo $nOfertaUrl ?>" method="post">
        <input type="hidden" name="pg" value="1">
        <input type="hidden" name="type" value="anuncioPromocional">
       <button id="profileLaboralOfferButton" class="um-alt btn btn-primary">
       <i class="fa fa-bullhorn" aria-hidden="true"></i> Crear anuncio promocional
      </button>
    </form>

</div>

</div>


<?php
    }
}

function getOpcPostulants($serial, $postulant)
{
    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {

        // validar propietario de publicación

        // se valida si este es el dueño de la publicación
        return $opc = dbGetOptionsPostulantOffer($serial, $postulant);
    } else {
        return $opc = '';
    }
}

function adminPanelButtonSendSelect()
{
    ?>

<div class="panelSendSelectPost row" id="panelSendSelectPost">

  <div class="panelButton">
    <button type="button" class="btn btn-primary" onclick="optionSendPostulates()">
      <i class="fa fa-users" aria-hidden="true"></i> Seleccionados
    </button>
  </div>

</div>

<?php
}

add_action('wp_ajax_adminSendSelectPostulants', 'adminSendSelectPostulants');
add_action('wp_ajax_nopriv_adminSendSelectPostulants', 'adminSendSelectPostulants');

function adminSendSelectPostulants()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataSelects'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataSelects']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $info = dbGetOfferLaboralInfoBySerial($data['serial']);

            // recibe info de oferta laboral

            $data['contratistaId'] = $info[0]['contratistaId'];
            $data['ofertaId'] = $info[0]['id'];

            $confirmaFecha = array(
              'admin' => 'Confirmada',
              'candidato' => 'Pendiente'
            );

              //  corrección en la fecha
              // $fp = $data['candidatos'][0]['info']['date'];
              // $fp = explode("/", $fp);
              // $fp = $fp[1].'/'.$fp[0].'/'.$fp[2];
              // $data['candidatos'][0]['info']['date'] = $fp;
              $data['candidatos'][0]['info']['confirmaFecha'] = $confirmaFecha;

            // print_r($data);

            dbSendBeginInterviewProcess($data);


            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_deleteAdminSelectPostulant', 'deleteAdminSelectPostulant');
add_action('wp_ajax_nopriv_deleteAdminSelectPostulant', 'deleteAdminSelectPostulant');

function deleteAdminSelectPostulant()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataSelectDelete'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataSelectDelete']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $info = dbGetOfferLaboralInfoBySerial($data['serial']);

            // recibe info de oferta laboral

            $data['contratistaId'] = $info[0]['contratistaId'];
            $data['ofertaId'] = $info[0]['id'];

            dbDeleteAdminSelectPostulant($data);

            // print_r($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}


// tab para las facturas
function adminTsoluciono7(){

  global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {
    $tabla = $wpdb->prefix . 'facturacion';
    $infoFacts = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);

    if(count($infoFacts) > 0){

  ?>
  <h4>Facturas de pago</h4>
  <div class="factUsers">
    <div class="container">
    <div class="col-12">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Pago</th>
          <th scope="col">Familia</th>
          <th scope="col">Servicio</th>
          <th scope="col">Tipo de pago</th>
          <th scope="col">Cuenta</th>

          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($infoFacts as $key => $value) {
           $fd = getInfoNameEmailUsers($value['contratistaId']);
           $serialFactura = $value['serialFactura'];
           $url = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;
           ?>
          <tr>
              <td> <?php echo $x = ($value['pagado'] == 1)? 'Si': 'No'; ?> </td>
              <td><?php echo $fd['nombre']; ?></td>
              <td><?php echo $value['nombreFactura']; ?></td>
              <td><?php echo $x = ($value['tipoPago'] != '')?$value['tipoPago']: 'Pendiente'; ?></td>
              <td><?php echo $x = ($value['cuenta'] != '')?$value['cuenta']: 'Pendiente'; ?></td>

              <td>
              <div class="buttonCustom">

                <a href='<?php echo $url; ?>' class="btn btn-success">
                    <small>
                    	Ver
                    </small>
                  </a>
              </div>

              </td>
          </tr>
       <?PHP } ?>
      </tbody>
  </table>
    </div>

</div>

<?php
  }else{ ?>
    <h4>No hay resultados en este momento</h4>
 <?php }
  }
}

// tab para configuraciones
function adminTsoluciono6(){

  global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {

    $tabla = $wpdb->prefix . 'configuracionesadmin';
    $infoSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
    $infoSettings = $infoSettings[0];
    if( isset($infoSettings['teamDatos']) ){
      $teamDatos = $infoSettings['teamDatos'];
      $teamDatos = json_decode($teamDatos, true);
    }
  ?>


  <h4>Configuraciones generales</h4>

  <div class="adminConfig">
  <form action="" method="post" class="formData">


    <h6>Cargos Administración</h6>
  <div class="row">
        <div class="field form-group col directiva">
        <label for="directiva">Directiva</label>
          <input class="form-control form-control-sm" name="directiva" type="text" id="directiva" value="<?Php echo $x = (isset($teamDatos['Directiva']))? $teamDatos['Directiva']['Nombre']: null ?>">
          <small class="validateMessage"></small>
            <button  type="button" onclick="defineAdminFirma()" class="btn btn-primary" >
              Definir firma
            </button>
        </div>
        <div class="field form-group col directivaFirma">
          <img src="<?Php echo $x = (isset($teamDatos['Directiva']))? $teamDatos['Directiva']['firma']: null ?>" alt="">
          <input value="<?Php echo $x = (isset($teamDatos['Directiva']))? $teamDatos['Directiva']['firma']: null ?>" type="hidden" name="directivaFirma" id="directivaFirma">
          <small class="validateMessage"></small>
        </div>
    </div>

  </form>

  <form action="" method="post" class="datosBancarios">
    <h6>Datos bancarios</h6>
  <div class="row">
    <div class="field form-group col banco1">

          <input placeholder='Nombre de banco' class="form-control form-control-sm" name="banco1" type="text" id="banco1" value="<?Php echo $x = (isset($teamDatos['Bancos']))? $teamDatos['Bancos']['banco1']: null ?>">

        </div>
        <div class="field form-group col numeroBanco1">

          <input placeholder='Cuenta bancaría' class="form-control form-control-sm" name="numeroBanco1" type="text" id="numeroBanco1" value="<?Php echo $x = (isset($teamDatos['Bancos']))? $teamDatos['Bancos']['numeroBanco1']: null ?>">

        </div>
      </div>
  <div class="row">
    <div class="field form-group col banco2">

          <input placeholder='Nombre de banco' class="form-control form-control-sm" name="banco2" type="text" id="banco2" value="<?Php echo $x = (isset($teamDatos['Bancos']))? $teamDatos['Bancos']['banco2']: null ?>">

        </div>
        <div class="field form-group col numeroBanco2">

          <input placeholder='Cuenta bancaría' class="form-control form-control-sm" name="numeroBanco2" type="text" id="numeroBanco2" value="<?Php echo $x = (isset($teamDatos['Bancos']))? $teamDatos['Bancos']['numeroBanco2']: null ?>">

        </div>
      </div>



    </div>
  </form>
  <form action="" method="post" class="otrosDatos">
    <h6>Otros datos</h6>
    <div class="row">

      <div class="field form-group col direccion">

          <input placeholder='Dirección de Tsolucionamos' class="form-control form-control-sm" name="direccion" type="text" id="direccion" value="<?Php echo $x = (isset($teamDatos['Otros']))? $teamDatos['Otros']['Direccion']: null ?>">

      </div>

    </div>

  </form>

  <div class="row">
    <div class="buttonCustom">

                <button type="button" class="btn btn-success" onclick="saveConfigAdminSettings()">
                    Guardar
                </button>

    </div>

    </div>



  </div>

<?php }

  }

// tab para notificaciones
function adminTsoluciono5(){

  global $wpdb;
  $defaults = array(
      "rows" => 0,
  );
  $args = wp_parse_args($args, $defaults);
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {

    $infoNotif = infoNotification($currentId);
  ?>
    <div class="row">
      <?php if(count($infoNotif) > 0){
          $pageData = $infoNotif['pageData'];
          unset($infoNotif['pageData']);
          // print_r($pageData);
        ?>




        <h4>Tus notificaciones</h4>

        <div class="col-12">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Leido</th>
          <th scope="col">Asunto</th>
          <th scope="col">Fecha</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($infoNotif as $key => $value) {

              $idNotif = $value['id'];
              $mensaje = $value['mensaje'];
              $subject = $value['subject'];
              $stdo = $value['estado'];
              $fecha = $value['fecha'];

              $pagina = esc_url(get_permalink(get_page_by_title('Mensaje')));

              $xxx = array(
                'm' => $idNotif
              );

              $xxx = json_encode($xxx);
        ?>

          <tr>
              <td><?php echo $x = ($stdo == 0)? 'No': 'Si' ?></td>
              <td><?php echo $subject ?></td>

              <td><?php echo $fecha ?></td>
              <td>
              <div class="buttonCustom">


                  <a class="btn btn-primary btn-block" href="<?php echo $pagina.'?mensaje='.$idNotif; ?>">
                    <small style="color: white;"> Leer </small>
                  </a>


              <button onclick='deleteNotif(<?php echo $xxx; ?>)' class="btn btn-block btn-danger"><small> Eliminar </small></button>
              </div>
              </td>
          </tr>

          <?php } ?>
      </tbody>
      </table>
      </div>

      <?php } else{ ?>
        <h4>No hay resultados en este momento</h4>
      <?php }?>
    </div>

  <?php }


}

// información que aparecera en el tab 2 de administración
function adminTsoluciono2()
{
    global $wp_roles;
    global $wpdb;

    // $postulants = dbGetPostulantSelectedByOffeId();
    $currentId = get_current_user_id();
    $lista = dbAdminGetVacantSelectedForInterview();




    if (count($lista) > 0) { ?>

  <div id="viewReasonsCand">

  </div>

<h4>Usuarios en proceso de entrevistas por vacante</h4>


 <?php
  formDetailsChangeDateAdmin();
  adminInfoVacanteForm();

 ?>


<?php foreach ($lista as $key => $l) {

        $oferta = $l['oferta'];
        $seleccionados = $l['seleccionados'];
        $entrevista = $l['entrevista'];

        // datos de la vacante
        $usuario = get_user_meta($oferta['contratistaId']);
        $nombreFamilia = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];
        $paginaVacante = esc_url(get_permalink(get_page_by_title('Información de vacante')));
        $nombreVacante = $oferta['nombreTrabajo'];
        $serial = $oferta['serialOferta'];
        $fechaPublicado = $oferta['fechaCreacion'];

        $urlFamilia = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

        $etapaEntrevista = $entrevista[0]['etapa'];
        $estadoEntrevista = $entrevista[0]['estado'];

        $ofertaId = $oferta['id'];
        $tipoPublic = $oferta['tipoPublic'];
        $tipoServicio  = $oferta['tipoServicio'];


        ?>

<div class="card entrevista">

  <div class="card-body">

    <div class="dataOffer row justify-content-around">

      <!-- <h6>Etapa: <?php echo $etapaEntrevista + 1; ?></h6> -->
      <?php if($tipoPublic != 'Promoción'){ ?>
      <h6>Estado: <?php echo $estadoEntrevista; ?>
      </h6>
      <?php }else{ ?>
        <h6 class="resalte1"><strong>Anuncio (<?php echo $tipoServicio ?>)</strong></h6>
     <?php } ?>
      <h6>Vacante: <a class="hiper"
          href="<?php echo $paginaVacante . '?serial=' . $serial ?>">
          <?php echo $nombreVacante ?></a></h6>
      <h6>Publicado: <?php echo $fechaPublicado; ?>
      </h6>
      <h6>Por: <a href="<?php echo $urlFamilia; ?>"
          class="hiper"> <?php echo $nombreFamilia; ?></a></h6>

    </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Nombre y rol</th>
          <th scope="col">Seleccionado</th>
          <th scope="col">Entrevista</th>
          <th scope="col">Estado</th>
          <th scope="col">Tipo entrevista</th>
          <th scope="col">Asistencia</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>


        <?php


     // colocar a familia en ultimo lugar
     $aux = null;
     foreach ($seleccionados as $key => $value) {
       if($value['tipoEntrevista'] == 'Entrevista con la familia' ){
         $aux = $value;
         unset($seleccionados[$key]);
       }
     }
     if($aux != null){
       array_push($seleccionados, $aux);
     }
     // ----------------------------------



        foreach ($seleccionados as $r) {
            // etapas obtengo el ultimo de las etapas, lo cual es el mas importantep ara esquematizar las etapas en la lista
            // $et = end($r['etapas']);

            // confirmación de asistencia
            $confAsistencia = $r['confirmaFecha'];
            $confAsistencia = json_decode($confAsistencia, true);
            $et = $r;

            // $Etapa = $et['etapa'];
            $Seleccionado = $et['fechaCreacion'];
            $proxima = (($et['fechaPautado'] == 'Realizada') || ($et['fechaPautado'] == 'Adicional'))?$et['fechaPautado']:$et['fechaPautado'].' - '.$et['hora'];

            $Nota = $et['nota'];
            $Tipo = $et['tipoEntrevista'];
            $Aprobado = $et['aprobado'];
            $idEntrevista = $et['idEntrevista'];
            $stdo = $et['estado'];

            // entrevista y otros datos
            $etvr = $wpdb->prefix . 'proceso_contrato';
            $ooo = $wpdb->get_results("SELECT candidataId, contratistaId from $etvr WHERE id='$idEntrevista'", ARRAY_A);

            $candidataId = $ooo[0]['candidataId'];
            $contratistaId = $ooo[0]['contratistaId'];

            $u = get_userdata($candidataId);
            $role = array_shift($u->roles);
            $rolCandidata = $wp_roles->roles[$role]['name'];

            $usuario = get_user_meta($candidataId);
            // nombre
            $nombre = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            $nombre .= ' ' . '(' . $rolCandidata . ')';
            $pagina = esc_url(get_permalink(get_page_by_title('Información de entrevista')));

            $postuladoId = $candidataId;


            // información de candidato y su contrato actual
            $ss = array(
              'idCandidato' => $candidataId,
              'idEntrevista' => $idEntrevista
            );
            $estadoCandidatoContrato = getStateCandidateOnContract($ss);
            // (


              if($etapaEntrevista >= 3){

                      // identificar si existe un contrato corriendo y si hay cambios
        $contratos = $wpdb->prefix . 'contratos';
        $historial = $wpdb->prefix . 'historialcontratos';
        $ooo =  $wpdb->get_results("SELECT * FROM $contratos as contratos INNER JOIN $historial as historial ON (contratos.id = historial.contratoId) where contratos.ofertaId = '$ofertaId' and contratos.contratistaId =  $contratistaId and contratos.candidataId = $candidataId", ARRAY_A);
        $existeContrato = $ooo[0];


        if( ($existeContrato['activos'] == 1)  ){
          // existe y fue aceptado, entonces se traen los estados
          $statusContrato = array(
            'estado' => 'activo'
          );
          $l = array();
          if($existeContrato['aceptado'] == 1){
              $l['aceptado'] = $existeContrato['aceptado'];
          }
          if($existeContrato['engarantia'] == 1){
            $l['engarantia'] = $existeContrato['engarantia'];
          }
          if($existeContrato['definitivo'] == 1){
            $l['definitivo'] = $existeContrato['definitivo'];
          }
          $statusContrato['status'] = $l;
        }elseif( ($existeContrato['activos'] == 0)  ){
          // existe y fue aceptado, entonces se traen los estados
          $statusContrato = array(
            'estado' => 'inactivo'
          );
          $l = array();
          if($existeContrato['cancelado'] == 1){
              $l['cancelado'] = $existeContrato['cancelado'];
          }
          if($existeContrato['caducado'] == 1){
            $l['caducado'] = $existeContrato['caducado'];
          }
          if($existeContrato['solCambio'] == 1){
            $l['solCambio'] = $existeContrato['solCambio'];
          }
          $statusContrato['status'] = $l;
        }else{

        }

          $x = '';
          $tool = '';
          if(($statusContrato['estado'] == 'activo') && ($statusContrato['status']['engarantia'] == 1)){
           $x = ' cGarantia';
           $tool = 'Candidato en garantía de prueba';
          }
          if(($statusContrato['estado'] == 'inactivo') && ($statusContrato['status']['solCambio'] == 1)){
            $x = ' cChanged';
            $tool = 'Candidato cambiado por la familia';
          }
          if(($statusContrato['estado'] == 'inactivo') && ($statusContrato['status']['caducado'] == 1)){
           $x = ' cExpirate';
          }
          if($r['fechaPautado'] == 'Adicional'){
           $x = ' cExtra';
           $tool = 'Candidato adicional, sugerido por Administración';
          }

        // si estamos en un usuario seleccionado para contrato
        if (($etapaEntrevista == 2) || ($etapaEntrevista == 5)) {

          $g = $lista[$key]['contratistaId'];
          $lg = $lista[$key]['entrevista'];
          $ddd = array_search($g, array_column($lg, 'candidataId'));
          $ownIntId = $lg[$ddd]['id'];

          $lg = $lista[$key]['seleccionados'];
          $ddd = array_search($ownIntId, array_column($lg, 'idEntrevista'));
          $res = $lg[$ddd]['resultadosEntrevista'];

          $res = json_decode($res, true);

          $ofertaId = $lista[$key]['oferta']['id'];

          // se confirma que no exista una propuesta de contrato enviada
          $tabla = $wpdb->prefix . 'estadoofertalaboral';
          $i = $wpdb->get_results("SELECT * from $tabla where ofertaId = '$ofertaId' and postuladoID = $candidataId", ARRAY_);
          $wpdb->flush();

        if ((($res['seleccionPor'] == 'Tsoluciono')) &&
          ($r['idEntrevista'] != $ownIntId) &&
          ($res['candidatoSeleccionado'] == $postuladoId ) &&
          (count($i) == 0 ) &&
          (!isset($estadoCandidatoContrato))) {

            $x = 'selectForContract';

          }
        }

                if (isset($statusContrato['estado'])) { ?>

                  <tr  <?php echo $t = ($tool != '')? "data-toggle='tooltip' data-placement='top' title='$tool'": '' ?> class="<?php echo $x; ?>">

                <?php }else{ ?>
                  <tr>
                <?php } ?>

              <?php } else{ ?>

                <tr>

              <?php } ?>

          <td><?php echo $nombre ?>
          </td>
          <td><?php echo $Seleccionado ?>
          </td>
          <td><?php echo $proxima ?>
          </td>
          <td><?php echo $stdo ?>
          </td>
          <td>
                <?php echo $Tipo ?>
          </td>
          <td>


          <?php

            if(isset($confAsistencia['candidato'])){
            if(isset($confAsistencia['candidato']['estado']) && $confAsistencia['candidato']['estado'] == 'Propuesta'){ ?>

              <small>
              Solicita reprogramar a: <br>
              <?php echo $confAsistencia['candidato']['date'].' - '.$confAsistencia['candidato']['hora']; ?>
              </small>
              <div class="buttonCustom">
                <button onclick="preSendAdminConfirmDate('<?php echo $idEntrevista ?>')" class="btn btn-success btn-block">Aceptar cambio
                </button>
                <button onclick="preSendAdminSolChangeDate('<?php echo $idEntrevista ?>')" class="btn btn-info btn-block">Reprogramar
                </button>
              </div>
              <?php

          }elseif (isset($confAsistencia['admin']['estado']) && $confAsistencia['admin']['estado'] == 'Propuesta') {
            $c = $confAsistencia['admin']; ?>


              <small>
                  Ofreciste cambiar a <br>
                  <?php echo $c['date'].' - '.$c['hora']; ?>
              </small>
          <?php } else{
            echo $confAsistencia['candidato'];
          }
        }

        if(isset($confAsistencia['familia'])){
          if(isset($confAsistencia['familia']['estado']) && $confAsistencia['familia']['estado'] == 'Propuesta'){ ?>

            <small>
            Solicita reprogramar a: <br>
            <?php echo $confAsistencia['familia']['date'].' - '.$confAsistencia['familia']['hora']; ?>
            </small>
            <div class="buttonCustom">
              <button onclick="preSendAdminConfirmDate('<?php echo $idEntrevista ?>')" class="btn btn-success btn-block">Aceptar cambio
              </button>
              <button onclick="preSendAdminSolChangeDate('<?php echo $idEntrevista ?>')" class="btn btn-info btn-block">Reprogramar
              </button>
            </div>
            <?php

        }elseif (isset($confAsistencia['admin']['estado']) && $confAsistencia['admin']['estado'] == 'Propuesta') {
          $c = $confAsistencia['admin']; ?>


            <small>
                Ofreciste cambiar a <br>
                <?php echo $c['date'].' - '.$c['hora']; ?>
            </small>
        <?php } else{
          echo $confAsistencia['familia'];
        }
        } ?>
          </td>
          <td>
            <?php if (
              ( $etapaEntrevista >= 0 ) &&
              ( isset($confAsistencia['candidato']) || isset($confAsistencia['familia']) ) &&
              ( $proxima == 'Realizada' || $proxima == 'Adicional' )
              ) { ?>

<?php if($asistencia != 'Confirmada' ){?>

<?php } ?>

              <?php } ?>


  <?php $x = array(
  'currentId' => $currentId,
  'can' => $postuladoId,
  'fam' => $contratistaId
);
$x = json_encode($x);

?>

<form target="_blank" id="formVerEstadoEntrevista" method="post"
action="<?php echo $pagina . '?ie=' . $idEntrevista ?>">
<input type="hidden" name="dataInterview" value='<?php echo $x; ?>'>
<div class="buttonCustom">
<button type="submit" class="btn btn-success btn-block"><?php echo $x = ($Tipo == 'Pruebas Psico laborales')? 'Pruebas': 'Entrevista'; ?></button>
</div>
</form>



            <?php if (
              ( $etapaEntrevista < 2 ) &&
              ( isset($confAsistencia['candidato']) ) &&
              ( $proxima != 'Realizada' )
              ) { ?>

            <?php if($asistencia != 'Confirmada' ){

              $x = array(
                'currentId' => $currentId,
                'can' => $postuladoId,
                'fam' => $contratistaId
              );
              $x = json_encode($x);

              ?>

            <?php } ?>

            <button
              onclick="sendDeleteProcessInterview('<?php echo $idEntrevista ?>')"
              type="button" name="" id="" class="btn btn-danger btn-block">
              Eliminar
            </button>

            <?php } ?>

            <?php if (
              ( $etapaEntrevista == 1 ) &&
              ( isset($confAsistencia['familia']) )
              ) { ?>

            <?php }

            if (($etapaEntrevista == 2) || ($etapaEntrevista == 5) || ($etapaEntrevista == 3) && !isset($estadoCandidatoContrato)) {


                $g = $lista[$key]['contratistaId'];
                $lg = $lista[$key]['entrevista'];
                $ddd = array_search($g, array_column($lg, 'candidataId'));
                $ownIntId = $lg[$ddd]['id'];

                $lg = $lista[$key]['seleccionados'];
                $ddd = array_search($ownIntId, array_column($lg, 'idEntrevista'));
                $res = $lg[$ddd]['resultadosEntrevista'];

                $res = json_decode($res, true);


                $ofertaId = $lista[$key]['oferta']['id'];

                // se confirma que no exista una propuesta de contrato enviada
                $tabla = $wpdb->prefix . 'estadoofertalaboral';
                $i = $wpdb->get_results("SELECT * from $tabla where ofertaId = '$ofertaId' and postuladoID = $candidataId", ARRAY_A);
                $wpdb->flush();

                $tabla = $wpdb->prefix . 'contratos';
                $verifContrato = $wpdb->get_results("SELECT * FROM $tabla as contratos where contratos.ofertaId = '$ofertaId' and contratos.candidataId = $candidataId", ARRAY_A);
                $wpdb->flush();
                ?>


              <?php
          /*
          if ((($res['seleccionPor'] == 'Tsoluciono')) && ($r['idEntrevista'] != $ownIntId) && ($res['candidatoSeleccionado'] == $postuladoId ) && (count($i) == 0 ) && (count($verifContrato) == 0)) {
                    $x = array(
                'ofertaId' => $ofertaId,
                'current' => $currentId,
                'can' => $candidataId,
                'fam' => $contratistaId,
            );
                    $x = json_encode($x);
                    $paginaContrato = esc_url(get_permalink(get_page_by_title('Información de contrato')));

            ?>
            <form target="_blank" id="formVerEstadoEntrevista" method="post"
              action="<?php echo $paginaContrato ?>">
              <input type='hidden' name='dataContrato'
                value='<?php echo $x ?>' />
              <div class="buttonCustom">
                <button type="submit" class="btn btn-primary btn-block"><small> Contratar </small></button>
              </div>
            </form>
            <?php
                }
              if(count($i) > 0 ){ ?>
                <small>
                  ¡Propuesta de contrato enviada!
                </small>
              <?php }

            */
          }
            if(isset($estadoCandidatoContrato) && $estadoCandidatoContrato != null){

                $data = array(
                  'candidataId' => $candidataId,
                  'entrevistaId' => $idEntrevista
                );
                $data = json_encode($data, JSON_UNESCAPED_UNICODE) ?>

              <div class="buttonCustom">
                <button alt="Comentarios y razones de la familia" onclick='viewReasonsCand(<?php echo $data ?>)' class='btn btn-block btn-warning'><small> Detalles </small></button>
              </div>

            <?php } ?>

          </td>


        </tr>
        <?php
        } ?>
      </tbody>
    </table>
    <?php
  $slcc = count($seleccionados);
        $nc = 0;
        $asist = 0;
        $confirmaPruebas = 0;
        foreach ($seleccionados as $key => $value) {
            # code...

            $vaux = $value['confirmaFecha'];
            $vaux = json_decode($vaux, true);

            if( $vaux['candidato'] == 'Confirmada' ){
              $asist++;
            }

            if (($value['aprobado'] == 1) && ($value['aprobado'] == '1')) {
                $nc++;
            }
            if ($value['tipoEntrevista'] == 'Pruebas Psico laborales'){
                $confirmaPruebas++;
            }
        }


        // if (($slcc == $nc) && ( $slcc == $asist) && ($etapaEntrevista < 1) && ($confirmaPruebas == 0) && ($tipoPublic != 'Promoción') ) {
        if ( ($etapaEntrevista < 1) && ($tipoPublic != 'Promoción') ) {

            $dataEvaluate = array(
               'id' => $oferta['contratistaId'],
                'idOferta' => $oferta['id'],
                'etapa' => $etapaEntrevista,
            );

            $dataEvaluate = json_encode($dataEvaluate); ?>

    <div class="row base justify-content-around" style="margin-top: 15px">

      <button
        onclick='CreateFamilyInterview(<?php echo $dataEvaluate ?>)'
        type='button' name=' id=' class='btn btn-success'>
        <i class="fa fa-check" aria-hidden="true"></i> Programar entrevista con la familia
      </button>

      <button
        onclick='omitirFamilyInterview(<?php echo $dataEvaluate ?>)'
        type='button' name=' id=' class='btn btn-success'>
        <i class="fa fa-check" aria-hidden="true"></i> Omitir entrevista con familia
      </button>

    </div>

    <?php
        }

        if ($etapaEntrevista == 3) {

            $g = $l['contratistaId'];
            $lg = $l['entrevista'];
            $ddd = array_search($g, array_column($lg, 'candidataId'));
            $ownIntId = $lg[$ddd]['id'];

            $lg = $l['seleccionados'];
            $ddd = array_search($ownIntId, array_column($lg, 'idEntrevista'));
            $res = $lg[$ddd]['resultadosEntrevista'];

            $res = json_decode($res, true);

            ?>
    <div class="row base justify-content-around" style="margin-top: 15px">

    <?php
    if($res['seleccionPor'] == 'Familia'){ ?>
      <p class="avisoRes">
        En espera de selección final del candidato, por parte de la Familia.
      </p>
    <?php }
    if($res['seleccionPor'] == 'Tsoluciono'){
      $tabla = $wpdb->prefix . 'estadoofertalaboral';
      $i = $wpdb->get_results("SELECT * from $tabla where ofertaId = '$ofertaId' and postuladoID = $candidataId", ARRAY_A);
      $wpdb->flush();?>

      <?php if(count($i) != 0 ){ ?>
          <p class="avisoRes">
            ¡La familia ha seleccionado a un candidato, es hora de contratar!
          </p>
        <?php } ?>

      <?php } ?>
    </div>
    <?php } ?>
  </div>

<?php  if($etapaEntrevista == 5){ ?>
  <div class="card-footer">

        <div class="opc">

        <?php if($etapaEntrevista == 5){
          // busqueda de candidatos previos entrevistados
          // ofertaId
          $tabla = $wpdb->prefix . 'usuarios_recomendados';
          $i = $wpdb->get_results("SELECT * from $tabla where idOferta != '$ofertaId'", ARRAY_A);
          $wpdb->flush();

          // $x = json_encode($i);

          formDetailsIntegrateCand($i, $ofertaId);
          ?>
          <h6>Opciones</h6>
        <button
          onclick='integrateNewPostulate()'
          type='button' name=' id='' class='btn btn-success'>Integrar nuevo candidato
        </button>

        <?php } ?>
        </div>

        <?php if($etapaEntrevista == 5){ ?>
          <p class="aviso">
            - ¡La familia haciendo uso de su garantía ha solicitado un nuevo candidato! <br>
            - La familia deberá elegir su nuevo candidato en la lista <br>
            - Podemos integrar un nuevo candidato a la lista
          </p>
        <?php } ?>

  </div>
<?php } ?>


</div>
<?php } } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
}

function confGarant(){

}


function adminTsoluciono3()
{
    global $wp_roles;
    global $wpdb;

    $data = dbGetAllContractList();

    if (count($data) > 0) {
        $dataVacant = array(); ?>
<h4>Lista de contratos de familias a candidatos</h4>



<div class="list-group allContracts row">

  <?php foreach ($data as $r) {
            // $serial = $r['oferta']['serialOferta'];
  // datos e imagenes
$tablaOferta = $wpdb->prefix . 'ofertalaboral';
$ofid = $r['ofertaId'];
$oferta = $wpdb->get_results("SELECT * FROM $tablaOferta where id = '$ofid'", ARRAY_A);
$oferta = $oferta[0];
$imagenes = $oferta['imagenes'];
$serialOferta = $oferta['serialOferta'];
$imagenes = json_decode($imagenes, true);

$imgPrincipal = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos/wp-content/uploads/icono-de-sitio.png' : '/wp-content/uploads/icono-de-sitio.png';

$estadoContrato = '';
$estadoContrato2 = '';
if( $r['activos'] == 1 ){
  $estadoContrato = '<span class="top activo">Contrato activo</span>';

  $estadoContrato2 = ($r['engarantia'] == 1)? '<span class="bottom garantia">En garantía</span>': $estadoContrato2;
  $estadoContrato2 = ($r['definitivo'] == 1)? '<span class="bottom definitivo" >Definitivo</span>': $estadoContrato2;

}
if($r['cancelado'] == 1){
  $estadoContrato = '<span class="top inactivo" >Contrato inactivo</span>';
  $estadoContrato2 = ($r['solCambio'] == 1)? '<span class="bottom cambio">Solicitud de cambio</span>': $estadoContrato2;
  $estadoContrato2 = ($r['caducado'] == 1)? '<span class="bottom caducado">Caducado</span>': $estadoContrato2;
  $estadoContrato2 = ($r['eliminado'] == 1)? '<span class="bottom eliminado">Eliminado</span>': $estadoContrato2;
  $estadoContrato2 = ($r['cancelado'] == 1)? '<span class="bottom cancelado">Cancelado</span>': $estadoContrato2;
}
// ------------
  $nombre = $r['nombreFamilia'];
  $vacante = $r['nombreTrabajo'];
  $cargo = $r['cargo'];
  $sueldo = $r['sueldo'];
  $descripcion = $r['descripcionExtra'];
  $contratistaId = $r['contratistaId'];
  $serial = $r['serialContrato'];
  $horario = $r['horario'];
  $ofertaId = $r['ofertaId'];
  // para los botones
  $postuladoId = $r['candidataId'];

  $fechaInicio = $r['fechaInicio'];
  $fechaFin = $r['fechaFin'];
  $fechaCreacionContrato = $r['fechaCreacion'];

  $pagina = esc_url(get_permalink(get_page_by_title('Información de contrato')));
  $c = get_current_user_id();

  // 5dc0f6dbbdf5e5dc0f6dbbdf61

  $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialOferta;

  $x = array(
      'ofertaId' => $ofertaId,
      'current' => $c,
      'can' => $postuladoId,
      'fam' => $contratistaId,
    );

    $fechaActual= date('d/m/Y');

    $diasPasados = dias_pasados($fechaCreacionContrato, $fechaActual);
    $estado = array();

    $gdias = 90 - $diasPasados;

    if ($r['activos'] == 1) {

        if($r['engarantia'] == 1){
          $estado['estado'] = 'En garantía';
          $estado['garantia'] = 'Días de garantía: '. $gdias;
          $estado['dias'] = $diasPasados;
        }

    }

    $estado['cd'] = $r['contratoId'];
    $estado['datos'] = $x;
    $xx = json_encode($estado, JSON_UNESCAPED_UNICODE);
    $x = json_encode($x, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_UNICODE);

    //-----
    $nnn =  getInfoNameEmailUsers($postuladoId);
    $nombreCand = $nnn['nombre'];


            ?>
<div class="card col-12">


<div class="row">
<div class="contratistaImg col-4">
      <div class="sueldo" >
        <div class="n">$<?php echo $r['sueldo'] ?></div>
        <!-- <div class="m">$/mes</div> -->
      </div>
      <img src="<?php echo $imgPrincipal; ?>" alt="">
    <?php echo $estadoContrato2; ?>
</div>
<div class="info col-8">



<h6  style="text-align: left;">Contrato de <strong><?php echo $nombre; ?></strong> con <strong><?php echo $nombreCand; ?></strong> <br> <?php echo $r['nombreTrabajo']; ?></h6>
  <div class="subInfo">
    <div class="row">
      <div class="col">
        <h6>
          <strong>Inicio</strong>
        </h6>
        <p>
          <?php echo $r['fechaInicio']; ?>
        </p>
      </div>
      <div class="col">
        <h6>
          <strong>Vencimiento</strong>
        </h6>
        <p>
          <?php echo $r['fechaFin']; ?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col desc">
        <h6><strong>Descripción</strong></h6>
        <p>
          <?php echo $r['descripcionExtra']; ?>
        </p>
      </div>
    </div>

  </div>

<div class="buttonCustom">

  <a target="_blank" href="<?php echo $vacanteUrl ?>" class="btn btn-info"> <i class="fa fa-briefcase" aria-hidden="true"></i> Ver oferta laboral</a>

  <form target="_blank" id="formVerContratos" method="post"
  action="<?php echo $pagina ?>">
  <input type='hidden' name='dataContrato'
  value='<?php echo $x ?>' />

    <button type="submit" class="btn btn-success"><i class="fa fa-file-text" aria-hidden="true"></i> Ver contrato</button>
  </form>



  </div>
</div>


</div>



</div>

  <?php
        } ?>

</div>
<?php
    } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
}

add_action('wp_ajax_DeleteProcessInterview', 'DeleteProcessInterview');
add_action('wp_ajax_nopriv_DeleteProcessInterview', 'DeleteProcessInterview');

function DeleteProcessInterview()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataSelectDelete'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataSelectDelete']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $info = $data['idEntrevista'];

            dbAdminDeleteProcessInterview($info);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_adminAddNewInterview', 'adminAddNewInterview');
add_action('wp_ajax_nopriv_adminAddNewInterview', 'adminAddNewInterview');

function adminAddNewInterview()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if ((validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) || (validateUserProfileOwner($currentId, $currentId, "familia"))) {
        if (isset($_POST['dataNew'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataNew']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $etapa = $data['entrevista']['etapa'];

            $data['entrevista']['actualizado'] = date('d/m/Y');

            if ($etapa < 3) {
                $data['entrevista']['estado'] = 'Entrevista realizada';
                $e = $etapa + 1;
                $data['info']['estado'] = 'En espera de selección';
            } elseif (($etapa == 3) && (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono"))) {
                $data['entrevista']['estado'] = 'Entrevista 3/3 aprobada por administración, candidato habilitado para contrato';
            } elseif (($etapa == 3) && (validateUserProfileOwner($currentId, $currentId, "familia"))) {
                $data['entrevista']['estado'] = 'Entrevista 3/3 aprobada por la familia, candidato habilitado para contrato';
            }


            dbAdminAddNewInterview($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_changeDataInterview', 'changeDataInterview');
add_action('wp_ajax_nopriv_changeDataInterview', 'changeDataInterview');

function changeDataInterview()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if ( (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) ) {
        if (isset($_POST['dataNew'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataNew']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $data['entrevista']['actualizado'] = date('d/m/Y');


            dbchangeDataInterview($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_adminModifyInterview', 'adminModifyInterview');
add_action('wp_ajax_nopriv_adminModifyInterview', 'adminModifyInterview');

function adminModifyInterview()
{
    $id = um_user('ID');
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataModify'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataModify']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            $data['entrevista']['actualizado'] = date('d/m/Y');

            // print_r($data);

            dbAdminModifyInterview($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_SendDataVacantAdminJs', 'SendDataVacantAdminJs');
add_action('wp_ajax_nopriv_SendDataVacantAdminJs', 'SendDataVacantAdminJs');

function SendDataVacantAdminJs()
{
    if (isset($_POST['dataInfo'])) {
        $data = preg_replace('/\\\\\"/', "\"", $_POST['dataInfo']);
        // transforma el string a un array asociativo
        $data = json_decode($data, true);

        $user = getUserGeneralInfo($data['idCont']);

        $oferta = dbGetOfferLaboralInfoBySerial($data['serialOferta']);

        $v = array(
            'user' => $user,
            'oferta' => $oferta,
        );

        // proyect($v);
    }
}

function templateVerifVacantAdmin()
{?>

<div class="templateVerifVacantAdmin" id="templateVerifVacantAdmin" style="display:none">
  <div class="info">
    <form action="" method="post" class="formData">
      <div class="row header d-flex justify-content-center">
        <h5 class="vacantName">
          <span>Aaaa</span>
        </h5>
      </div>
      <div class="datos">
        <div class="row verifData">
          <h6 class="familia col">
            <span>Aaaa</span>
          </h6>
          <h6 class="telefono col">
            <span>Aaaa</span>
          </h6>
        </div>
        <div class="infoVacant">
          <h6 class="centerTitle">Información</h6>
          <div class="row">
            <h6 class="cargo col">
              <span>cargo</span>
            </h6>
            <h6 class="horario col">
              <span>horario</span>
            </h6>
            <h6 class="sueldo col">
              <span>sueldo</span>
            </h6>
          </div>
          <div class="row">
            <h6 class="tipoServicio col">
              <span>tipoServicio</span>
            </h6>
            <h6 class="direccion col">
              <span>direccion</span>
            </h6>
            <h6 class="pais col">
              <span>pais</span>
            </h6>
          </div>
          <div class="row">
            <h6 class="publicadoFecha col">
              <span>publicadoFecha</span>
            </h6>
            <h6 class="desdeFecha col">
              <span>desdeFecha</span>
            </h6>
            <h6 class="hastaFecha col">
              <span>hastaFecha</span>
            </h6>
          </div>
          <div class="row">
            <p class="descripcion col">
              <span>escripcion</span>
            </p>

          </div>
        </div>
      </div>
      <div class="adminData">
        <h6 class="centerTitle">Gestión de administración</h6>
        <div class="row">
          <div class="field col form-group necesidades">
            <label for="necesidades">Necesidades de la vacante</label>
            <textarea class="form-control form-control-sm" name="necesidades" id="" cols="30" rows="3"
              placeholder="Puedes describir las necesidades que se entienden del cliente en esta vacante"></textarea>
            <small class="validateMessage"></small>
          </div>
        </div>
        <div class="row">
          <div class="field col form-group notaAdmin">
            <label for="notaAdmin">Notas de la vacante</label>
            <textarea class="form-control form-control-sm" name="notaAdmin" id="" cols="30" rows="3"
              placeholder="Puedes especificar y describir una nota adicional sobre la gestión"></textarea>
            <small class="validateMessage"></small>
          </div>
        </div>
        <div class="row">
          <div class="field col form-group estadoPublico">
            <label for="estadoPublico">¿Vacante pública?</label>
            <select class="form-control" name="estadoPublico">
              <option value="1">Si</option>
              <option value="0">No</option>
            </select>
          </div>
          <div class="field col form-group estadoAprobado">
            <label for="estadoAprobado">¿Vacante verificada?</label>
            <select class="form-control" name="estadoAprobado">
              <option value="0">No</option>
              <option value="1">Si</option>
            </select>
          </div>
        </div>
        <div class="row">
          <h6 class="fechaRevision col">
            <span>fechaRevision</span>
          </h6>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
}
function templateOptionsVacantAdmin()
{?>

<div class="templateOptionsVacantAdmin" id="templateOptionsVacantAdmin" style="display:none">
  <div class="info">
    <form action="" method="post" class="formData">
      <div class="row header d-flex justify-content-center">
        <h5 class="vacantName">
          <span>Aaaa</span>
        </h5>
      </div>
      <div class="datos">
        <div class="row verifData">
          <h6 class="familia col">
            <span>Aaaa</span>
          </h6>
          <h6 class="telefono col">
            <span>Aaaa</span>
          </h6>
        </div>
        <div class="infoVacant">
          <h6 class="centerTitle">Información</h6>
          <div class="row">
            <h6 class="cargo col">
              <span>cargo</span>
            </h6>
            <h6 class="horario col">
              <span>horario</span>
            </h6>
            <h6 class="sueldo col">
              <span>sueldo</span>
            </h6>
          </div>
          <div class="row">
            <h6 class="tipoServicio col">
              <span>tipoServicio</span>
            </h6>
            <h6 class="direccion col">
              <span>direccion</span>
            </h6>
            <h6 class="pais col">
              <span>pais</span>
            </h6>
          </div>
          <div class="row">
            <h6 class="publicadoFecha col">
              <span>publicadoFecha</span>
            </h6>
            <h6 class="desdeFecha col">
              <span>desdeFecha</span>
            </h6>
            <h6 class="hastaFecha col">
              <span>hastaFecha</span>
            </h6>
          </div>
          <div class="row">
            <p class="descripcion col">
              <span>escripcion</span>
            </p>

          </div>
        </div>
      </div>
      <div class="adminData">
        <h6 class="centerTitle">Gestión de administración</h6>
        <div class="row">
          <div class="field col form-group necesidades">
            <label for="necesidades">Necesidades de la vacante</label>
            <p><span>Necesidades</span></p>
          </div>
        </div>
        <div class="row">
          <div class="field col form-group notaAdmin">
            <label for="notaAdmin">Notas de la vacante</label>
            <p><span>Notas admin</span></p>
          </div>
        </div>
        <div class="row">
          <div class="field col form-group estadoPublico">
            <label for="estadoPublico">¿Vacante pública?</label>
            <select class="form-control" name="estadoPublico">
              <option value="1">Si</option>
              <option value="0">No</option>
            </select>
          </div>
          <div class="field col form-group estadoAprobado">
            <label for="estadoAprobado">¿Vacante verificada?</label>
            <p><span>Verificación</span></p>
          </div>
        </div>
        <div class="row">
          <h6 class="fechaRevision col">
            <span>fechaRevision</span>
          </h6>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
}

add_action('wp_ajax_processAdminVacantVerify', 'processAdminVacantVerify');
add_action('wp_ajax_nopriv_processAdminVacantVerify', 'processAdminVacantVerify');

function processAdminVacantVerify()
{
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataAdmin'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataAdmin']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // print_r($data);
            dbProcessAdminVacantVerify($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_processOpcionesVacante', 'processOpcionesVacante');
add_action('wp_ajax_nopriv_processOpcionesVacante', 'processOpcionesVacante');

function processOpcionesVacante()
{
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataAdmin'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataAdmin']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // print_r($data);
            dbProcessOpcionesVacante($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_CreateFamilyInterview', 'CreateFamilyInterview');
add_action('wp_ajax_nopriv_CreateFamilyInterview', 'CreateFamilyInterview');

function CreateFamilyInterview()
{
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataInterviewFamily'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataInterviewFamily']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);


            $confirmaFecha = array(
              'admin' => 'Confirmada',
              'familia' => 'Pendiente'
            );


              $data['info']['confirmaFecha'] = $confirmaFecha;
            // }
            dbCreateFamilyInterview($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_goomitirFamilyInterview', 'goomitirFamilyInterview');
add_action('wp_ajax_nopriv_goomitirFamilyInterview', 'goomitirFamilyInterview');

function goomitirFamilyInterview()
{
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['goomitirFamilyInterview'])) {

            $data = stripslashes($_POST['goomitirFamilyInterview']);

            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $info = $data['info'];
            $info = json_decode($info,true);
            $familia = $data['familia'];
            $familia = json_decode($familia,true);

            $confirmaFecha = array(
              'admin' => 'Confirmada',
              'familia' => 'Confirmada'
            );


            $info['confirmaFecha'] = $confirmaFecha;
            $data['info'] = $info;
            $data['familia'] = $familia;


            dbgoomitirFamilyInterview($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}


add_action('wp_ajax_sendCreateFamilyPostulantSelectionStep', 'sendCreateFamilyPostulantSelectionStep');
add_action('wp_ajax_nopriv_sendCreateFamilyPostulantSelectionStep', 'sendCreateFamilyPostulantSelectionStep');

function sendCreateFamilyPostulantSelectionStep()
{
    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['dataFamilySelectPostulantStep'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['dataFamilySelectPostulantStep']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);


            // print_r($data);

            dbSendCreateFamilyPostulantSelectionStep($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}



function adminTsoluciono4() { ?>

<?php

global $wpdb;
global $wp_roles;
// info usuarios
$uTodos = get_users([ 'role__not_in' => [ 'administrator', 'um_administracion-tsoluciono' ] ]);
$uFamilias = get_users(['role__in' => ['um_familia'] ]);
$uCandidatos = get_users(['role__not_in' => ['um_familia', 'administrator', 'um_administracion-tsoluciono'] ]);

$uTodos = (count($uTodos) > 0)?$uTodos:'Sin registros';
$uFamilias = (count($uFamilias) > 0)?$uFamilias:'Sin registros';
$uCandidatos = (count($uCandidatos) > 0)?$uCandidatos:'Sin registros';


// info contratos
$tabla = $wpdb->prefix . 'contratos';

$rr = $wpdb->get_results("SELECT * from $tabla", ARRAY_A);
$wpdb->flush();
$nContratos = (count($rr)>0)?count($rr):'Sin registros';

$tabla = $wpdb->prefix . 'historialcontratos';

$rrGarantia = $wpdb->get_results("SELECT * from $tabla where engarantia=1", ARRAY_A);
$rrDefinitivos = $wpdb->get_results("SELECT * from $tabla where definitivo=1", ARRAY_A);

$nContratosGarantia = (count($rrGarantia) > 0)?count($rrGarantia): 'Sin registros';
$nContratosDefinitivos = (count($rrDefinitivos) > 0)?count($rrDefinitivos): 'Sin registros';

$coloresUusarios = colorUsers(); ?>

<div class="container">
  <h4>
    Reportes y balances de Tsolucionamos

  </h4>
  <div class="data">
    <div class="row usersInfo">
    <?php
    // información de balances
      $todosUsuarios = getAllUsersByRole();
      $numeroUsuauarios = $todosUsuarios['usuariosTotales'];
      unset($todosUsuarios['usuariosTotales']);

    ?>
      <h5>
        Usuarios <?php echo ' - Total: ' . $numeroUsuauarios; ?>
      </h5>
      <div class="container">
        <div class="row todosUsuariosGraficoPadre">

       <canvas id="todosUsuariosGrafico" class="graficoDona"></canvas>

<script>
var ctx = document.getElementById('todosUsuariosGrafico');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
          <?php foreach ($todosUsuarios as $key => $value) {

              if($key == 'Candidatos'){
                foreach ($value as $kkey => $vvalue) {
                  # code...
                  echo "'".$kkey."',";
                }
              }else{
                echo "'".$key."',";
              }
          } ?>
        ],
        datasets: [{
            label: 'Usuarios',
            data: [
              <?php foreach ($todosUsuarios as $key => $value1) {
              if($key == 'Candidatos'){
                foreach ($value1 as $kkey => $vv) {
                  echo count($vv).',';
                }
              }else{
                echo count($value1).',';
              }
          } ?>
            ],
            backgroundColor: [
              <?php foreach ($todosUsuarios as $key => $value) {
              if($key == 'Candidatos'){
                foreach ($value as $kkey => $vv) {
                  echo "'".$coloresUusarios[$kkey]."',";
                }
              }else{
                echo "'".$coloresUusarios[$key]."',";
              }
          } ?>
            ],
            borderColor: [
              <?php foreach ($todosUsuarios as $key => $value) {
              if($key == 'Candidatos'){
                foreach ($value as $kkey => $vv) {
                  echo "'".$coloresUusarios[$kkey]."',";
                }
              }else{
                echo "'".$coloresUusarios[$key]."',";
              }
          } ?>
            ],
            borderWidth: 5
        }]
    },

});
</script>

        </div>
      </div>
    </div>

    <div class="row infoContratos">

    <?php
           $tabla1 = $wpdb->prefix . 'ofertalaboral';
        $tabla2 = $wpdb->prefix . 'proceso_contrato_etapas';
        $tabla3 = $wpdb->prefix . 'contratos';
        $tabla4 = $wpdb->prefix . 'historialcontratos';

         $todoYear = array();
        $registrosGet = $wpdb->get_results("SELECT * from $tabla1 AS oferta where oferta.tipoPublic IS NULL", ARRAY_A);
            foreach ($registrosGet as $key => $value) {
              $dtt = $value['fechaCreacion'];
              $dtt = explode("/", $dtt);
              array_push($todoYear, $dtt[2]);
            }

            // sacar entrevistas
             $registrosGet = $wpdb->get_results("SELECT * from $tabla2 AS entrevistas where", ARRAY_A);
            foreach ($registrosGet as $key => $value) {
              $dtt = $value['fechaCreacion'];
              $dtt = explode("/", $dtt);
              array_push($todoYear, $dtt[2]);
            }

            //  en garantias
             $registrosGet = $wpdb->get_results("SELECT * FROM $tabla3 as contratos inner join $tabla4 as historial on contratos.id = historial.contratoId where historial.engarantia = 1 ", ARRAY_A);
            foreach ($registrosGet as $key => $value) {
              $dtt = $value['fechaCreacion'];
              $dtt = explode("/", $dtt);
              array_push($todoYear, $dtt[2]);
            }

             //  en definito
             $registrosGet = $wpdb->get_results("SELECT * FROM $tabla3 as contratos inner join $tabla4 as historial on contratos.id = historial.contratoId where historial.definitivo = 1 ", ARRAY_A);
            foreach ($registrosGet as $key => $value) {
              $dtt = $value['fechaCreacion'];
              $dtt = explode("/", $dtt);
              array_push($todoYear, $dtt[2]);
            }
          $todoYear = array_unique($todoYear);

     ?>

      <h5>
      Número de transacciones que se efectuaron

        <span class="selector"> Filtrar por año
        <select onchange="filterStatics('transacciones')" name="selectorYearTransacs" id="selectorYearTransacs">
          <?php foreach ($todoYear as $key => $value) { ?>
              <option value="-" disabled></option>
              <option value="<?php echo $value ?>"><?php echo $value ?></option>
         <?php  } ?>
      </select>
    </span>

      </h5>

      <div class="container">
        <div class="row">
        <?php
        $dt = date("j/n/Y");
        $dt = explode("/", $dt);
        $anio = $dt[2];
        $mesesIniciales = array();
        for ($i = 1; $i <= $dt[1]; $i++) {
            $o = '0/'.$i.'/0';
            $t = tranformMeses($o);
            array_push($mesesIniciales, $t['mes']);
        }
        // buscar ofertas
          $dataBymes = array();


        foreach ($mesesIniciales as $key => $value) {
            $ii = $key + 1;
            $dtb = "$ii/$anio";

            $registrosGet = $wpdb->get_results("SELECT * from $tabla1 AS oferta where oferta.fechaCreacion LIKE '%$dtb%' and oferta.tipoPublic IS NULL", ARRAY_A);
            $dataBymes[$value]['oferta'] = count($registrosGet);

            // sacar entrevistas
             $registrosGet = $wpdb->get_results("SELECT * from $tabla2 AS entrevistas where oferta.fechaCreacion LIKE '%$dtb%'", ARRAY_A);
            $dataBymes[$value]['entrevistas'] = count($registrosGet);

            //  en garantias
             $registrosGet = $wpdb->get_results("SELECT * FROM $tabla3 as contratos inner join $tabla4 as historial on contratos.id = historial.contratoId where historial.engarantia = 1 and contratos.fechaCreacion LIKE '%$dtb%'", ARRAY_A);
            $dataBymes[$value]['garantia'] = count($registrosGet);

             //  en definito
             $registrosGet = $wpdb->get_results("SELECT * FROM $tabla3 as contratos inner join $tabla4 as historial on contratos.id = historial.contratoId where historial.definitivo = 1 and contratos.fechaCreacion LIKE '%$dtb%'", ARRAY_A);
            $dataBymes[$value]['definitivo'] = count($registrosGet);

        }

          // obtener todosl os años existentes

         ?>
       <canvas id="todosContratosInformacion" class="graficoLineas"></canvas>

       <script>
window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};


		var config = {
			type: 'bar',
			data: {
				labels: [
         <?php foreach ($mesesIniciales as $key => $value) { ?>
              <?php
                if( !next( $mesesIniciales ) ) {
                    echo "'$value'";
                }else{
                    echo "'$value',";
                }
               ?>
            <?Php } ?>
        ],
				datasets: [{
					label: 'Ofertas',
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					data: [
            <?php foreach ($dataBymes as $key => $value) { ?>
              <?php
                if(!next($dataBymes)){
                  $xxx = $value['oferta'];
                  echo "$xxx";
                }else{
                  $xxx = $value['oferta'];
                  echo "$xxx,";
                }
               ?>

            <?php } ?>
					],
					fill: false,
        },
        {
					label: 'Candidatos procesados',
					fill: false,
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: [
            <?php foreach ($dataBymes as $key => $value) { ?>
              <?php
                if(!next($dataBymes)){
                  $xxx = $value['entrevistas'];
                  echo "$xxx";
                }else{
                  $xxx = $value['entrevistas'];
                  echo "$xxx,";
                }
               ?>

            <?php } ?>
					],
        },
        {
					label: 'Retención de 90 días',
					fill: false,
					backgroundColor: window.chartColors.orange,
					borderColor: window.chartColors.orange,
					data: [
            <?php foreach ($dataBymes as $key => $value) { ?>
              <?php
                if(!next($dataBymes)){
                  $xxx = $value['garantia'];
                  echo "$xxx";
                }else{
                  $xxx = $value['garantia'];
                  echo "$xxx,";
                }
               ?>

            <?php } ?>
					],
        },
        {
					label: 'Retención de 6 meses',
					fill: false,
					backgroundColor: window.chartColors.purple,
					borderColor: window.chartColors.purple,
					data: [
            <?php foreach ($dataBymes as $key => $value) { ?>
              <?php
                if(!next($dataBymes)){
                  $xxx = $value['definitivo'];
                  echo "$xxx";
                }else{
                  $xxx = $value['definitivo'];
                  echo "$xxx,";
                }
               ?>

            <?php } ?>
					],
        }
      ]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Mes'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Cantidad'
						},
						ticks: {
							min: 0,
							// max: 50,

							// forces step size to be 5 units
							stepSize: 5
						}
					}]
				}
			}
		};
			var ctx = document.getElementById('todosContratosInformacion').getContext('2d');
			window.myLine1 = new Chart(ctx, config);
	</script>

        </div>
      </div>
    </div>


    <div class="row infoDifusion" style="margin-bottom: 15px">

    <?php

     $year = (isset($_POST['year']) && $_POST['year'] != '')? $_POST['year'] : 0;

// SELECT * FROM `wp_ofertapostulantes` where fechaCreacion LIKE '%2020%'
         $tabla1 = $wpdb->prefix . 'ofertapostulantes';
        $registros = $wpdb->get_results("SELECT * from $tabla1 AS postulantes", ARRAY_A);

        $yearAll = array();
        foreach ($registros as $key => $value) {

          $dt = $value['fechaCreacion'];
          $dt = explode("/",$dt);

          array_push($yearAll, $dt['2']);

        }

        $yearAll = array_unique($yearAll);

        $counts = array(
          'Un amigo' => 0,
          'Redes sociales' => 0,
          'Pagina de la empresa' => 0,
          'Otra forma' => 0,

        );

        if($year == 0){
          $dt = date("j/n/Y");
          $dt = explode("/", $dt);
          $dt = $dt['2'];
           $registros = $wpdb->get_results("SELECT * from $tabla1 AS postulantes where fechaCreacion LIKE '%$dt%'", ARRAY_A);

        }else{
          $registros = $wpdb->get_results("SELECT * from $tabla1 AS postulantes where fechaCreacion LIKE '%$year%'", ARRAY_A);
        }
           foreach ($registros as $key => $value) {

             switch ($value['candidatoEnterado']) {

             case 'Un amigo':
              $index = $value['candidatoEnterado'];
              $counts[$index] += 1;
              break;
             case 'Redes sociales':
              $index = $value['candidatoEnterado'];
              $counts[$index] += 1;
              break;
             case 'Pagina de la empresa':
              $index = $value['candidatoEnterado'];
              $counts[$index] += 1;
              break;
             case 'Otra forma':
              $index = $value['candidatoEnterado'];
              $counts[$index] += 1;
              break;

               default:
                 // code...
                 break;
             }

           }

     ?>
      <h5>
      Difusión de vacantes
      <span class="selector"> Filtrar por año
        <select onchange="filterStatics('difusion')" name="selectorYearStatics" id="selectorYearStatics">
          <?php foreach ($yearAll as $key => $value) { ?>
              <option value="-" disabled></option>
              <option value="<?php echo $value ?>"><?php echo $value ?></option>
         <?php  } ?>
      </select>
    </span>
      </h5>

      <div class="container">
        <div class="row">

        <?Php
        $tiposDifusion = colorsEnterado();

        ?>


       <canvas id="infoGraficoDifusion" class="graficoLineas"></canvas>

       <script>
window.colores = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

		var config2 = {
			type: 'bar',
			data: {
				labels: [
          <?php  foreach ($tiposDifusion as $key => $value) {
                echo "'".$key."',";
             } ?>
        ],
				datasets: [{
					label: 'Un amigo',
					backgroundColor: window.colores.red,
					borderColor: window.colores.red,
					data: [
            <?php echo $counts['Un amigo']; ?>, 0 , 0, 0
					],
					fill: false,
        },
        {
					label: 'Redes sociales',
					fill: false,
					backgroundColor: window.colores.blue,
					borderColor: window.colores.blue,
					data: [
            0, <?php echo $counts['Redes sociales']; ?>, 0, 0
					],
        },
        {
					label: 'Pagina de la empresa',
					fill: false,
					backgroundColor: window.colores.orange,
					borderColor: window.colores.orange,
					data: [
            0, 0, <?php echo $counts['Pagina de la empresa']; ?>, 0
					],
        },
        {
					label: 'Otra forma',
					fill: false,
					backgroundColor: window.colores.purple,
					borderColor: window.colores.purple,
					data: [
            0, 0, 0, <?php echo $counts['Otra forma']; ?>
					],
        }
      ]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: ''
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
			}
		};


			var ctx2 = document.getElementById('infoGraficoDifusion').getContext('2d');
			window.myLine2 = new Chart(ctx2, config2);

	</script>

        </div>
      </div>
    </div>

    <div class="row infoReports">

    <div class="formDetailsBalancOfert" id="formDetailsBalancOfert" style="display:none;">

    <div class="internalformDetailsBalancOfert" id="internalformDetailsBalancOfert">

    </div>

    </div>
      <?php

      $infoReports = getInfoOfertsReport();
      ?>
      <h5>
      Ciclo de vida de la vacante
      </h5>
      <div class="container">
      <?php if(count($infoReports) > 0){ ?>
      <table class="table table-hover">
          <thead>
            <tr>

              <th scope="col">Oferta</th>
              <th scope="col">T. Ocupar puesto</th>
              <th scope="col">T. Para contratar</th>
              <th scope="col">Fuente de contratación</th>
              <th scope="col">Nro. Solicitantes</th>
              <th scope="col">Detalles</th>
            </tr>
          </thead>
          <tbody>

          <?php foreach ($infoReports as $key => $value) {
            $nombreTrabajo = $value['nombreTrabajo'];
            $tOcuparPuesto = $value['contratoActivo']['tiempoOcuparPuesto'];
            $tContratar = $value['contratoActivo']['tiempoParaContratar'];
            $fuenteContratacion = $value['contratoActivo']['candidatoEnterado'];
            $NroSolicitantes = $value['numeroPostulantes'];

            $ofertaId = $value['id'];?>
            <tr>
              <td> <?php echo $nombreTrabajo; ?> </td>
              <td> <?php echo $tOcuparPuesto. ' Días'; ?> </td>
              <td> <?php echo $tContratar. ' Días'; ?> </td>
              <td> <?php echo $fuenteContratacion; ?> </td>
              <td> <?php echo count($NroSolicitantes); ?> </td>
              <td>

              <div class="buttonCustom">
                <button type="button" class="btn btn-primary btn-sm" onclick="detailsBalancOfferInfo('<?php echo $ofertaId ?>')" > Ver </button>
              </div>

              </td>
            </tr>

          <?php } ?>

          </tbody>
      </table>
    <?php }else{ ?>
      <p>
        Sin registros
      </p>
    <?php } ?>

      </div>
    </div>
    <div class="row usersRecomended">
      <h5>
        Candidatos de recomendación
      </h5>
      <div class="container">
        <?php
           $tabla = $wpdb->prefix . 'usuarios_recomendados';

           $in = $wpdb->get_results("SELECT * from $tabla ", ARRAY_A);
          if(count($in) > 0){ ?>

        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Nombre y rol</th>
              <th scope="col">Oferta</th>
              <th scope="col">Tipo servicio</th>
              <th scope="col">Entrevista</th>
              <th scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>

          <?php
          $tablaVacante = $wpdb->prefix . 'ofertalaboral';

          foreach ($in as $key => $value) {

            $usuario = get_user_meta($value['idCandidato']);

            $u = get_userdata($value['idCandidato']);

            $role = array_shift($u->roles);
            $rolCandidata = $wp_roles->roles[$role]['name'];
         // nombre
            $nameC = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            $nameC .= ' ' . '(' . $rolCandidata . ')';

            $urlCandidatox = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

            $idOferta = $value['idOferta'];

            $vacante = $wpdb->get_results("SELECT * from $tablaVacante where id = '$idOferta' ", ARRAY_A);

            $urlOferta = '/vacantes-disponibles/info-vacante/?serial='.$vacante[0]['serialOferta'];

            $nombreOferta = $vacante[0]['nombreTrabajo'];

            $tipoServicioOferta = $vacante[0]['tipoServicio'];


            $pagina = esc_url(get_permalink(get_page_by_title('Información de entrevista')));

            $currentId = get_current_user_id();
            $postuladoId = $value['idCandidato'];
            $contratistaId = $vacante[0]['contratistaId'];

            $data = array(
              'candidatoId' => $postuladoId,
              'ofertaId' => $idOferta
            );

            $data = json_encode($data); ?>

            <tr>

              <td><a href="<?php echo $urlCandidatox ?>"><?php echo $nameC; ?></a></td>
              <td><a href="<?php echo $urlOferta ?>"><?php echo $nombreOferta ?></a></td>
              <td><?php echo $tipoServicioOferta ?></td>
              <td>
                <form target="_blank" id="formVerEstadoEntrevista" method="post"
              action="<?php echo $pagina . '?ie=' . $value['idEntrevista'] ?>">

                <?php
                    $xxxl = array(
                      'currentId' => $currentId,
                      'can' => $postuladoId,
                      'fam' => $contratistaId,
                    );

                    $xxxl = json_encode($xxxl); ?>

              <input type="hidden" name="dataInterview" value='<?php echo $xxxl; ?>'>

              <div class="buttonCustom">
                <button type="submit"> Ver entrevista</button>
              </div>
            </form>
          </td>
              <td>
                <button type="button" class="btn btn-danger" onclick='deleteRecomended(<?php echo $data; ?>)'>
                  <i class="fa fa-trash-o" aria-hidden="true"></i>
                </button>
              </td>

            </tr>

         <?php } ?>

          </tbody>
        </table>

          <?php }else{ ?>
            <p>
              Sin registros
            </p>
         <?php } ?>

      </div>
    </div>

    <div class="row usersRecomended" id="interviewByAnounce">

        <div id="formSchemabyAnounce" class="formSchemabyAnounce" style="display: none">
          <form action=""></form>
        </div>


      <h5>
        Entrevistas por anuncios
      </h5>
       <div class="container">
          <?php getListCandByAnounce() ?>
       </div>

    </div>

  </div>
</div>

<?php }

function getListCandByAnounce($data = ''){?>

        <?php
        global $wpdb;
        global $wp_roles;
           $oferta = $wpdb->prefix . 'ofertalaboral';
           $entrevista = $wpdb->prefix . 'proceso_contrato';
           $etapas = $wpdb->prefix . 'proceso_contrato_etapas';

           $in = $wpdb->get_results("SELECT oferta.*, entrevista.*, etapas.* FROM $oferta as oferta inner join $entrevista as entrevista on oferta.id = entrevista.ofertaId inner join $etapas as etapas on entrevista.id = etapas.idEntrevista where oferta.tipoPublic = 'Promoción'", ARRAY_A);

           $in2 = $in;

           if($data == '' || $data == 'todos'){
            $tipo = 'todos';
           }else{

           $in = $wpdb->get_results("SELECT oferta.*, entrevista.*, etapas.* FROM $oferta as oferta inner join $entrevista as entrevista on oferta.id = entrevista.ofertaId inner join $etapas as etapas on entrevista.id = etapas.idEntrevista where oferta.tipoPublic = 'Promoción' and oferta.tipoServicio = '$data'", ARRAY_A);

           }

          if(count($in) > 0){

          ?>
              <h6>Filtrar por</h6>
      <ul class="listaFiltroAnounce">

      <li>
        <a href="#" onclick="sendFilterInterviewByAnounce('todos')" class="hiper">
         Todos
        </a>
      </li>

    <?php
    $auxCount = array();
    foreach ($in2 as $key => $value) {

      $eRol = $value['tipoServicio'];

      array_push($auxCount, $eRol);
    }
    $auxCount = array_unique($auxCount); ?>

    <?php foreach ($auxCount as $key => $value) { ?>
      <li>
        <a href="#" onclick="sendFilterInterviewByAnounce('<?php echo $value ?>')" class="hiper">
        <?php echo $value; ?>
        </a>
      </li>
    <?php } ?>
      </ul>
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Nombre y rol</th>
              <th scope="col">Anuncio</th>
              <th scope="col">Tipo servicio</th>
              <th scope="col">Entrevista</th>
              <th scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>

          <?php
          $tablaVacante = $wpdb->prefix . 'ofertalaboral';
          foreach ($in as $key => $value) {

            $usuario = get_user_meta($value['candidataId']);

            $u = get_userdata($value['candidataId']);

            $role = array_shift($u->roles);
            $rolCandidata = $wp_roles->roles[$role]['name'];
         // nombre
            $nameC = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            $nameC .= ' ' . '(' . $rolCandidata . ')';

            $urlCandidatox = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

            $idOferta = $value['ofertaId'];

            $vacante = $wpdb->get_results("SELECT * from $tablaVacante where id = '$idOferta' ", ARRAY_A);

            $urlOferta = '/vacantes-disponibles/info-vacante/?serial='.$vacante[0]['serialOferta'];

            $nombreOferta = $vacante[0]['nombreTrabajo'];

            $tipoServicioOferta = $vacante[0]['tipoServicio'];

            $idEntrevista = $value['idEntrevista'];


            $pagina = esc_url(get_permalink(get_page_by_title('Información de entrevista')));

            $currentId = get_current_user_id();
            $postuladoId = $value['candidataId'];
            $contratistaId = $vacante[0]['contratistaId'];

            $data = array(
              'candidatoId' => $postuladoId,
              'ofertaId' => $idOferta
            );

            $data = json_encode($data);

            $dataAdd = array(
              'candidatoId' => $postuladoId,
              'idEntrevista' => $idEntrevista
            );
            $dataAdd = json_encode($dataAdd);
            ?>
            <tr>

              <td><a href="<?php echo $urlCandidatox ?>"><?php echo $nameC; ?></a></td>
              <td><a href="<?php echo $urlOferta ?>"><?php echo $nombreOferta ?></a></td>
              <td><?php echo $tipoServicioOferta ?></td>
              <td>
                <form target="_blank" id="formVerEstadoEntrevista" method="post"
              action="<?php echo $pagina . '?ie=' . $value['idEntrevista'] ?>">


              <?php
                  $x = array(
                  'currentId' => get_current_user_id(),
                  'can' => $value['candidataId'],
                  'fam' => $value['contratistaId']
                  );
                  $x = json_encode($x);
               ?>

              <input type="hidden" name="dataInterview" value='<?php echo $x; ?>'>


              <div class="buttonCustom">
                <button type="submit"> Ver entrevista</button>
              </div>
            </form>
          </td>
              <td>
                <button type="button" class="btn btn-success" onclick='integrateNewPostulateByAnounce(<?php echo $dataAdd; ?>)'>
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
              </td>

            </tr>

         <?php } ?>

          </tbody>
        </table>

        <?php }else{ ?>
            <p>
              Sin registros
            </p>
        <?php }

}


function adminTsoluciono8(){

  global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {
    $tabla = $wpdb->prefix . 'facturacion_profesional';
    $infoFacts = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);

    if(count($infoFacts) > 0){
  ?>
  <h4>Pagos de membresías</h4>
  <div class="factUsers">
    <div class="container">
    <div class="col-12">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Pago</th>
          <th scope="col">Usuario</th>
          <th scope="col">Servicio</th>
          <th scope="col">Tipo de pago</th>
          <th scope="col">Cuenta</th>
          <th scope="col">Membresía</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($infoFacts as $key => $value) {
           $fd = getInfoNameEmailUsers($value['candidatoId']);
           $serialFactura = $value['serialFactura'];
           $url = esc_url(get_permalink(get_page_by_title('Factura'))).'?fserial='.$serialFactura;
           ?>
          <tr>
              <td> <?php echo $x = ($value['pagado'] == 1)? 'Si': 'No'; ?> </td>
              <td><?php echo $fd['nombre']; ?></td>
              <td><?php echo $value['nombreFactura']; ?></td>
              <td><?php echo $x = ($value['tipoPago'] != '')?$value['tipoPago']: 'Pendiente'; ?></td>
              <td><?php echo $x = ($value['cuenta'] != '')?$value['cuenta']: 'Pendiente'; ?></td>
              <td><?php echo $value['plan']; ?></td>
              <td>
              <div class="buttonCustom">

                <a href='<?php echo $url; ?>' class="btn btn-success">
                    <small>
                      Ver
                    </small>
                  </a>
              </div>

              </td>
          </tr>
       <?PHP } ?>
      </tbody>
  </table>
    </div>

</div>

<?php
  }else{ ?>
    <h4>No hay resultados en este momento</h4>
 <?php }
  }
}
function adminTsoluciono9(){

        global $wpdb;
        $tabla = $wpdb->prefix . 'public_profesional';
        $tabla2 = $wpdb->prefix . 'facturacion_profesional';
        $currentId = get_current_user_id();

        $data = $wpdb->get_results("SELECT * FROM $tabla as public INNER JOIN $tabla2 as factura ON (public.factura = factura.serialFactura)", ARRAY_A);



     if (count($data) > 0) {
        $dataVacant = array(); ?>

<h4>Publicaciones actualmente en vigencia</h4>

<div class="list-group allContracts row">

  <?php foreach ($data as $r) {
            // $serial = $r['oferta']['serialOferta'];
  // datos e imagenes
    $idPublicacion = $r['id'];
    $serialOferta = $idPublicacion;
    $candidatoId = $r['candidatoId'];
    $plan = $r['plan'];
    $estado = $r['estado'];
    $nombreEmpresa = $r['nombreEmpresa'];
    $tituloPublicacion = $r['tituloPublicacion'];
    $categoria = $r['categoria'];
    $fechaCreada = $r['fechaCreada'];
    $detalles = $r['detalles'];
    $logo = $r['logo'];
    $direccion = $r['direccion'];
    $departamento = $r['departamento'];
    $horario = $r['horario'];
    $ciudad = $r['ciudad'];
    $redesSociales = $r['redesSociales'];
    $telefono = $r['telefono'];
    $email = $r['email'];
    $media = $r['media'];
    $factura = $r['factura'];
    $publico = $r['publico'];
    $meses = $r['meses'];
    $fechaPagada = $r['fechaPagada'];
    $pagado = $r['pagado'];

    $c = get_current_user_id();

    $imgPrincipal = $r['logo'];
    $imgPrincipal = stripslashes($imgPrincipal);

    $imgPrincipal = json_decode($imgPrincipal, true);
    $imgPrincipal = $imgPrincipal[0]['src'];



     $fd = getInfoNameEmailUsers($candidatoId);
     $nombre = $fd['nombre'];

      $dias = $meses * 30;
      $fechaPagada = $fechaPagada;
      $formatFecha = explode('/', $fechaPagada);
      $fecant = $formatFecha[2].'-'.$formatFecha[1].'-'.$formatFecha[0];
      // Seteo fecha de comienzo
      $fecha_inicial = $fecant;
      // Pongo los dias que quiero sumar
      $dias_a_sumar = $dias;
      // Paso la fecha de comienzo a timestamp
      $tiempo=strtotime($fecha_inicial);
      // Paso los dias a segundos
      $sumar=$dias_a_sumar*86400;
      // Formatear date a gusto, aca viene dd/mm/aaaa
      $nuevafecha = date("j/n/Y", $tiempo+$sumar);
      // $nuevafecha = date($fechaPagada, strtotime(' +'.$dias.' days'));

  // 5dc0f6dbbdf5e5dc0f6dbbdf61

  $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de profesional'))).'?serial='.$serialOferta;

  ?>
  <div class="card col-12">

  <div class="row">
  <div class="contratistaImg col-4">
      <div class="sueldo" >

        <div class="m">
          <?php
            echo $xxx = ($publico == 1 && $pagado == 1)? 'Publicado': 'No publicado';
           ?>
        </div>
      </div>
      <img src="<?php echo $imgPrincipal; ?>" alt="">
      <span class="bottom garantia">
    <?php echo $plan; ?>
      </span>
  </div>
  <div class="info col-8">
  <h6  style="text-align: left;">Publicación de <strong><?php echo $nombre; ?></strong> con <?php echo $meses; ?> de membresía</h6>

  <div class="subInfo">
    <div class="row">
      <div class="col">
        <h6>
          <strong>Inicio</strong>
        </h6>
        <p>
          <?php echo $fechaCreada; ?>
        </p>
      </div>
      <div class="col">
        <h6>
          <strong>Vencimiento</strong>
        </h6>
        <p>
          <?php echo $nuevafecha; ?>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col desc">
        <h6><strong>Descripción</strong></h6>
        <p>
          <?php echo $detalles; ?>
        </p>
      </div>
    </div>

  </div>

<div class="buttonCustom">

  <a target="_blank" href="<?php echo $vacanteUrl ?>" class="btn btn-info"> Ver publicación
  </a>



</div>

</div>


</div>



</div>

  <?php
        } ?>

</div>
<?php
    } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }


}

function adminTsoluciono11(){


  global $wpdb;
  global $wp_roles;

  $tablaOferta = $wpdb->prefix . 'ofertalaboral';
  $info = $wpdb->get_results("SELECT * FROM $tablaOferta as oferta where oferta.tipoPublic = 'Promoción'", ARRAY_A);
  $data = $info;  ?>


<div style="display: none" class="modifyAnounce" id="modifyAnounce">

<form action="" method="post" class="formData">


    <div class="row">

        <div class="field col form-group titulo">
            <label for="titulo">Titulo del anuncio</label>
            <input type="text" class="form-control form-control-sm" name="titulo">
            <small class="validateMessage"></small>
        </div>

    </div>

    <div class="row">
         <div class="form-group field col fechaInicio">
          <label for="fechaInicio">Fecha de inicio</label>
          <input class="form-control form-control-sm" name="fechaInicio" type="text" id="fechaInicio">
          <small class="validateMessage"></small>
        </div>


    </div>
<div class="row">
	        <div class="form-group field col fechaFin">
	          <label for="fechaFin">Fecha de finalización</label>
	          <input class="form-control form-control-sm" name="fechaFin" type="text" id="fechaFin">
	          <small class="validateMessage"></small>
	        </div>
</div>


    <div class="row">

     <div class="field form-group col descripcion">
            <label for="descripcion">Descripción general</label>
            <textarea class="form-control form-control-sm" name="descripcion" id="" cols="30" rows="2"></textarea>
            <small class="validateMessage"></small>
        </div>

    </div>

    <div class="row imagenes">
                    <div class="field form-group col imagenPrincipal">
                        <label for="imagenPrincipal">Imagen principal</label>
                        <input type="file" class="form-control" id="imagenPrincipal" name="imagenPrincipal" accept="image/jpeg, image/png">
                        <small>Esta será la imagen principal de tu publicación.</small>
                        <small class="validateMessage"></small>
                    </div>
    </div>

</form>
</div>



  <div class="row tsolucionamosPublic">

    <?php if(isset($data) && count($data) > 0) { ?>
  <h4>Publicaciones Tsolucionamos</h4>

    <?php foreach ($data as $key => $r){

  $imagenes = $r['imagenes'];
  $serialOferta = $r['serialOferta'];
  $imagenes = json_decode($imagenes, true);
  $imgPrincipal = $imagenes['principal']['src'];
  $nombrePublicación = $r['nombreTrabajo'];
  $cargo = $r['tipoServicio'];
  $estadoPublico = $r['publico'];


  $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialOferta; ?>

    <div class="card col-12">
      <div class="row">
      <div class="contratistaImg col-4">
            <img src="<?php echo $imgPrincipal; ?>" alt="">
      </div>
      <div class="info col-8">
      <h6  style="text-align: left;"><strong><?php echo $nombrePublicación; ?></strong> - <?php echo $cargo; ?></h6>
        <div class="subInfo">
          <div class="row">
            <div class="col">
              <h6>
                <strong>Inicio</strong>
              </h6>
              <p>
                <?php echo $r['fechaInicio']; ?>
              </p>
            </div>
            <div class="col">
              <h6>
                <strong>Finalización</strong>
              </h6>
              <p>
                <?php echo $r['fechaFin']; ?>
              </p>
            </div>

          </div>

          <div class="row">
            <div class="col desc">
              <h6><strong>Descripción</strong></h6>
              <p>
                <?php echo $r['descripcionExtra']; ?>
              </p>
            </div>
          </div>

        </div>

      <div class="buttonCustom">
         <a style="color: white;" onclick="stateAnounce('<?php echo $serialOferta ?>', <?php echo $estadoPublico ?>);" type="button" class="btn   btn-success">
          Modificar
         </a>
        <a target="_blank" href="<?php echo $vacanteUrl ?>" class="btn btn-info">Ver publicación</a>
    </div>
      </div>
    </div>
  </div>

   <?php } ?>
  <?php }else{ ?>
    <h4>No hay resultados en este momento</h4>
   <?php } ?>
  </div>


  <?php
}

function adminTsoluciono10($tipo = 'todos', $lugar = ''){

  global $wpdb;
  global $wp_roles;

  ?>
<h4>Bolsa de trabajo</h4>
  <?Php

  $usuarios = getAllUsersByRole();





  if( count($usuarios['Profesionales']) > 0 ){
    $usuarios['Candidatos']['Profesionales'] = $usuarios['Profesionales'];
  }
  if( count($usuarios['Familias']) > 0 ){
    $usuarios['Candidatos']['Familias'] = $usuarios['Familias'];
  }

  if($tipo == 'todos'){

    if(count($usuarios['Candidatos']) > 0 ){ ?>

      <div class="TitleOptions">
      <h6>Filtrar por</h6>
      <button class="btn btn-success" id="generadorExcel">Exportar a CSV</button>
      </div>

      <ul class="listaFiltro">


      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('todos')" class="hiper">
         Todos
        </a>
      </li>
    <?php foreach ($usuarios['Candidatos'] as $key => $value) { ?>
      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('<?php echo $key ?>')" class="hiper">
        <?php echo $key; ?>
        </a>
      </li>
    <?php } ?>



    <li>
      <select onchange="sendFilterBolsaTrabajo('departamento')" class="form-control form-control-sm" name="departamento" id="">
        <option value="">Departamentos</option>
        <?php
      $dep = array();
        foreach ($usuarios['Candidatos'] as $key => $value) {
    foreach ($value as $kkey => $vvalue) {
      $candidataId = $vvalue;
      $usuario = get_user_meta($candidataId);
$departamentoCandidato = $usuario['DepartamentoCandidata'][0];
    if($departamentoCandidato != ''){
      array_push($dep,$departamentoCandidato);
    }

}}

$dep = array_unique($dep);

  ?>
  <pre>
    <?php print_r($dep); ?>
  </pre>
  <?php

foreach( $dep as $kkk => $value ){ ?>

      <option value="<?php echo $value; ?>">
      <?php echo $value; ?>
    </option>
    <?php
}

        ?>
    </select>
      </li>

      </ul>

<table class="table table-hover" id="tablaBolsa" >
          <thead>
            <tr>

              <th scope="col">Nombre</th>
              <th scope="col">Nro. Teléfono</th>
              <th scope="col">Email</th>
              <th scope="col">Rol</th>
              <th scope="col">Fecha Registro</th>
              <th scope="col">Curriculum</th>
            </tr>
          </thead>
          <tbody>
    <?php
      foreach ($usuarios['Candidatos'] as $key => $value) {

        foreach ($value as $kkey => $vvalue) {

          $candidataId = $vvalue;
          $u = get_userdata($candidataId);

          $fechaCreada = $u->data->user_registered;
          $fechaCreada = explode(' ',$fechaCreada);
          $fechaCreada = explode('-', $fechaCreada[0]);
          $fechaCreada = $fechaCreada[2].'/'.$fechaCreada[1].'/'.$fechaCreada[0];

          $role = array_shift($u->roles);
          $rolCandidata = $wp_roles->roles[$role]['name'];
          $usuario = get_user_meta($candidataId);

          $nombreCandidata = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

          $urlCandidata = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

          $urlCandidata = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$urlCandidata : $urlCandidata;

          $telef = $usuario['mobile_number'][0];
          $email = $usuario['nickname'][0];


          $pd = $usuario['curriculum'][0];
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$pd;
          $curriculum = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
          $curriculum = ($rolCandidata == 'Profesional Independiente')? '/#': $curriculum;



          ?>
        <tr>
          <td>
          <a target="_blank" class="name hiper" href="<?php echo $urlCandidata ?>">
              <?php echo $nombreCandidata ?>
          </a>
          </td>
          <td><?php echo $telef ?></td>
          <td><?php echo $email ?></td>
          <td><?php echo $rolCandidata ?></td>
          <td><?php echo $fechaCreada ?></td>
          <td>
          <a style="margin: 0 auto;
    text-align: center;
    width: 100%;
    display: block;" target="_blank" class="hiper" href="<?php echo $curriculum; ?>">
    <?php if($rolCandidata != 'Profesional Independiente' && $rolCandidata != 'Familia'){ ?>
    <i class="fa fa-briefcase" aria-hidden="true"></i>
    <?Php } ?>
    </a>
          </td>
        </tr>

        <?php }

      } ?>

      </tbody>
  </table>

   <?php }

  }elseif ($tipo == 'departamento') {

    if(count($usuarios['Candidatos']) > 0){ ?>




      <div class="TitleOptions">
      <h6>Filtrar por</h6>
      <button class="btn btn-success" id="generadorExcel">Exportar a CSV</button>

      <ul class="listaFiltro">


      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('todos')" class="hiper">
         Todos
        </a>
      </li>
    <?php foreach ($usuarios['Candidatos'] as $key => $value) { ?>
      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('<?php echo $key ?>')" class="hiper">
        <?php echo $key; ?>
        </a>
      </li>
    <?php } ?>



    <li>
      <select onchange="sendFilterBolsaTrabajo('departamento')" class="form-control form-control-sm" name="departamento" id="">
        <option value="">Departamentos</option>
        <?php
      $dep = array();
        foreach ($usuarios['Candidatos'] as $key => $value) {
    foreach ($value as $kkey => $vvalue) {
      $candidataId = $vvalue;
      $usuario = get_user_meta($candidataId);
$departamentoCandidato = $usuario['DepartamentoCandidata'][0];
      array_push($dep,$departamentoCandidato);

}}

$dep = array_unique($dep);


foreach( $dep as $kkk => $value ){ ?>

      <option value="<?php echo $value; ?>">
      <?php echo $value; ?>
    </option>
    <?php
}
      ?>
    </select>
      </li>

      </ul>

<table class="table table-hover" id="tablaBolsa" >
          <thead>
            <tr>

              <th scope="col">Nombre</th>
              <th scope="col">Nro. Teléfono</th>
              <th scope="col">Email</th>
              <th scope="col">Rol</th>
              <th scope="col">Fecha registro</th>
              <th scope="col">Curriculum</th>
            </tr>
          </thead>
          <tbody>
    <?php
      foreach ($usuarios['Candidatos'] as $key => $value) {

        foreach ($value as $kkey => $vvalue) {

          $candidataId = $vvalue;
          $u = get_userdata($candidataId);
          $role = array_shift($u->roles);
          $rolCandidata = $wp_roles->roles[$role]['name'];
          $usuario = get_user_meta($candidataId);

          $fechaCreada = $u->data->user_registered;
          $fechaCreada = explode(' ',$fechaCreada);
          $fechaCreada = explode('-', $fechaCreada[0]);
          $fechaCreada = $fechaCreada[2].'/'.$fechaCreada[1].'/'.$fechaCreada[0];


          $nombreCandidata = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

          $urlCandidata = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

          $urlCandidata = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$urlCandidata : $urlCandidata;

          $telef = $usuario['mobile_number'][0];
          $email = $usuario['nickname'][0];

          $departamentoCandidato = $usuario['DepartamentoCandidata'][0];

          $pd = $usuario['curriculum'][0];
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$pd;
          $curriculum = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
          $curriculum = ($rolCandidata == 'Profesional Independiente')? '/#': $curriculum;
          $departamentoCandidato = $usuario['DepartamentoCandidata'][0];

        if($departamentoCandidato == $lugar ){
          ?>
        <tr>
          <td>
          <a target="_blank" class="name hiper" href="<?php echo $urlCandidata ?>">
              <?php echo $nombreCandidata ?>
          </a>
          </td>
          <td><?php echo $telef ?></td>
          <td><?php echo $email ?></td>
          <td><?php echo $rolCandidata ?></td>
          <td><?php echo $fechaCreada ?></td>
          <td>
          <a style="margin: 0 auto;
    text-align: center;
    width: 100%;
    display: block;" target="_blank" class="hiper" href="<?php echo $curriculum; ?>">
    <?php if($rolCandidata != 'Profesional Independiente' && $rolCandidata != 'Familia'){ ?>
    <i class="fa fa-briefcase" aria-hidden="true"></i>
    <?Php } ?>
    </a>
          </td>
        </tr>

    <?php }
        }

      } ?>

      </tbody>
  </table>

  <?php  }
 }else{

    if(count($usuarios['Candidatos']) > 0){ ?>



    <div class="TitleOptions">
      <h6>Filtrar por</h6>
      <button class="btn btn-success" id="generadorExcel">Exportar a CSV</button>
    </div>
      <ul class="listaFiltro">
      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('todos')" class="hiper">
        Todos
        </a>
      </li>
    <?php foreach ($usuarios['Candidatos'] as $key => $value) { ?>
      <li>
        <a href="#" onclick="sendFilterBolsaTrabajo('<?php echo $key ?>')" class="hiper <?php echo $x = ($key == $tipo)?' active': ''; ?>">
        <?php echo $key; ?>
        </a>
      </li>
    <?php } ?>

         <li>
      <select onchange="sendFilterBolsaTrabajo('departamento')" class="form-control form-control-sm" name="departamento" id="">
        <option value="">Departamentos</option>
        <?php
      $dep = array();
        foreach ($usuarios['Candidatos'] as $key => $value) {
    foreach ($value as $kkey => $vvalue) {
      $candidataId = $vvalue;
      $usuario = get_user_meta($candidataId);
$departamentoCandidato = $usuario['DepartamentoCandidata'][0];
      array_push($dep,$departamentoCandidato);

}}

$dep = array_unique($dep);

foreach( $dep as $kkk => $value ){ ?>

      <option value="<?php echo $value; ?>">
      <?php echo $value; ?>
    </option>
    <?php
}


        ?>
    </select>
      </li>

      </ul>

<table class="table table-hover" id="tablaBolsa" >
          <thead>
            <tr>

              <th scope="col">Nombre</th>
              <th scope="col">Nro. Teléfono</th>
              <th scope="col">Email</th>
              <th scope="col">Rol</th>
              <th scope="col">Fecha registro</th>
              <th scope="col">Curriculum</th>
            </tr>
          </thead>
          <tbody>
    <?php
      foreach ($usuarios['Candidatos'] as $key => $value) {

        if($key == $tipo){

        foreach ($value as $kkey => $vvalue) {

          $candidataId = $vvalue;
          $u = get_userdata($candidataId);
          $role = array_shift($u->roles);
          $rolCandidata = $wp_roles->roles[$role]['name'];
          $usuario = get_user_meta($candidataId);

          $fechaCreada = $u->data->user_registered;
          $fechaCreada = explode(' ',$fechaCreada);
          $fechaCreada = explode('-', $fechaCreada[0]);
          $fechaCreada = $fechaCreada[2].'/'.$fechaCreada[1].'/'.$fechaCreada[0];



          $nombreCandidata = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

          $urlCandidata = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

          $urlCandidata = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$urlCandidata : $urlCandidata;

          $telef = $usuario['mobile_number'][0];
          $email = $usuario['nickname'][0];


          $pd = $usuario['curriculum'][0];
            $url = '/wp-content/uploads/ultimatemember/'.$candidataId.'/'.$pd;
          $curriculum = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$url : $url;
          ?>
        <tr>
          <td>
          <a target="_blank" class="name hiper" href="<?php echo $urlCandidata ?>">
              <?php echo $nombreCandidata ?>
          </a>
          </td>
          <td><?php echo $telef ?></td>
          <td><?php echo $email ?></td>
          <td><?php echo $rolCandidata ?></td>
          <td><?php echo $fechaCreada ?></td>
          <td>
          <a style="margin: 0 auto;
    text-align: center;
    width: 100%;
    display: block;" target="_blank" class="hiper" href="<?php echo $curriculum; ?>">
    <?php if($rolCandidata != 'Profesional Independiente' ){ ?>
    <i class="fa fa-briefcase" aria-hidden="true"></i>
    <?Php } ?>
    </a>
          </td>
        </tr>

        <?php }
      }

      } ?>

      </tbody>
  </table>

   <?php }

  }

}


add_action('wp_ajax_sendDeleteRecomended', 'sendDeleteRecomended');
add_action('wp_ajax_nopriv_sendDeleteRecomended', 'sendDeleteRecomended');

function sendDeleteRecomended(){

$currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['sendDeleteRecomended'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['sendDeleteRecomended']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);


            // print_r($data);

            dbSendDeleteRecomended($data);

            die();
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

// procesar solicitud de reprogramación de entrevista
add_action('wp_ajax_SendAdminSolChangeDate', 'SendAdminSolChangeDate');
add_action('wp_ajax_nopriv_SendAdminSolChangeDate', 'SendAdminSolChangeDate');
function SendAdminSolChangeDate(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['SendAdminSolChangeDate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendAdminSolChangeDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos

          // $fi = $data['info']['date'];
          // $fi = explode("/", $fi);
          // $fi = $fi[1].'/'.$fi[0].'/'.$fi[2];
          // $data['info']['date'] = $fi;

          // print_r($data);


          dbSendAdminSolChangeDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }

}

// aceptar hora y fecha propuesta por candidatos
add_action('wp_ajax_SendAdminConfirmDate', 'SendAdminConfirmDate');
add_action('wp_ajax_nopriv_SendAdminConfirmDate', 'SendAdminConfirmDate');
function SendAdminConfirmDate(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['SendAdminConfirmDate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendAdminConfirmDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos


          dbSendAdminConfirmDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}

// integrar nuevo candidato a la lista
add_action('wp_ajax_sendIntegrateNewPostulate', 'sendIntegrateNewPostulate');
add_action('wp_ajax_nopriv_sendIntegrateNewPostulate', 'sendIntegrateNewPostulate');

function sendIntegrateNewPostulate(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['sendIntegrateNewPostulate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['sendIntegrateNewPostulate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos

          // print_r($data);


          dbSendIntegrateNewPostulate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}

add_action('wp_ajax_saveConfigAdminSettings', 'saveConfigAdminSettings');
add_action('wp_ajax_nopriv_saveConfigAdminSettings', 'saveConfigAdminSettings');
// almacenar datos config admin
function saveConfigAdminSettings()
{

//   cargosConfig
// bancosConfig
  global $wpdb;
  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['cargosConfig']) && isset($_POST['bancosConfig']) ) {
          // recibe json y quita los slash

          $data = preg_replace('/\\\\\"/', "\"", $_POST['cargosConfig']);
          $data = json_decode($data, true);
          $cargosConfig = $data;

          $data = preg_replace('/\\\\\"/', "\"", $_POST['bancosConfig']);
          $data = json_decode($data, true);
          $bancosConfig = $data;

          $data = preg_replace('/\\\\\"/', "\"", $_POST['otrosConfig']);
          $data = json_decode($data, true);
          $otrosConfig = $data;

          $l = array(
            'cargos' => $cargosConfig,
            'bancos' => $bancosConfig,
            'otros' => $otrosConfig
          );

          dbsaveConfigAdminSettings($l);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}


// aceptar pago de familia
add_action('wp_ajax_sendacceptPay', 'sendacceptPay');
add_action('wp_ajax_nopriv_sendacceptPay', 'sendacceptPay');
function sendacceptPay(){

    $currentId = get_current_user_id();
    if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['sendacceptPay'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['sendacceptPay']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);




            // print_r($data);
            dbsendacceptPay($data);

            // return;

        }

    }

}

// rechazar pago de familia
add_action('wp_ajax_sendrefusePay', 'sendrefusePay');
add_action('wp_ajax_nopriv_sendrefusePay', 'sendrefusePay');
function sendrefusePay(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['sendrefusePay'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['sendrefusePay']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);



          dbsendrefusePay($data);

      }

  }

}
// Eliminar pago de familia
add_action('wp_ajax_senddeletePay', 'senddeletePay');
add_action('wp_ajax_nopriv_senddeletePay', 'senddeletePay');

function senddeletePay(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['senddeletePay'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['senddeletePay']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);

          $data['usuario'] = 'admin';

          dbsenddeletePay($data);
      }
  }
  if (validateUserProfileOwner($currentId, $currentId, "familia")) {
      if (isset($_POST['senddeletePay'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['senddeletePay']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);

          $data['usuario'] = 'familia';

          dbsenddeletePay($data);
      }
  }

}


add_action('wp_ajax_AdminDeleteSelectPostulant', 'AdminDeleteSelectPostulant');
add_action('wp_ajax_nopriv_AdminDeleteSelectPostulant', 'AdminDeleteSelectPostulant');

function AdminDeleteSelectPostulant()
{

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['AdminDeleteSelectPostulant'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['AdminDeleteSelectPostulant']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);



          dbAdminDeleteSelectPostulant($data);

      }

    }
}
add_action('wp_ajax_AdminAddPostulant', 'AdminAddPostulant');
add_action('wp_ajax_nopriv_AdminAddPostulant', 'AdminAddPostulant');

function AdminAddPostulant()
{

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['AdminAddPostulant'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['AdminAddPostulant']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);



          dbAdminAddPostulant($data);

      }
    }
}


add_action('wp_ajax_sendviewReasonsCand', 'sendviewReasonsCand');
add_action('wp_ajax_nopriv_sendviewReasonsCand', 'sendviewReasonsCand');
function sendviewReasonsCand()
{

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") || validateUserProfileOwner($currentId, $currentId, "familia")) {
      if (isset($_POST['sendviewReasonsCand'])) {

        global $wpdb;
        $contratoExperiencia = $wpdb->prefix . 'experiencia_contratos';
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['sendviewReasonsCand']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);

          $candidataId = $data['candidataId'];
          $entrevistaId = $data['entrevistaId'];


          $x = $wpdb->get_results("SELECT * from $contratoExperiencia WHERE idCandidato=$candidataId and idEntrevista='$entrevistaId' ", ARRAY_A);
          $wpdb->flush();
          $x = $x[0];

          $detallesExperiencia = $x['detallesExperiencia'];
          $detallesExperiencia = json_decode($detallesExperiencia, true);
          $recomendabilidad = $detallesExperiencia['calif'];
          ?>

            <div class="reasons">
              <h6 style="margin-bottom: 0;"><?php echo $x['tipo']; ?> - <?php echo $detallesExperiencia['motivo']; ?></h6>
              <div class="star-rating">
            <?php
                // echo $recomendabilidad;
                for ($i=1; $i <= 5 ; $i++) {
                  if($i <= $recomendabilidad){ ?>
                    <span class="fa fa-star" data-rating="1"></span>
                  <?php }else{ ?>
                    <span class="fa fa-star-o" data-rating="1"></span>
                  <?php }

                } ?>
              </div>

              <?php if($detallesExperiencia['detallesFamilia']){ ?>
                  <p style="color: black; text-align: center;">Detalles: <?php echo $detallesExperiencia['detallesFamilia']; ?></p>
              <?php } ?>

            </div>


          <?php

      }

    }
}



add_action('wp_ajax_sendEvaluatePsicoTest', 'sendEvaluatePsicoTest');
add_action('wp_ajax_nopriv_sendEvaluatePsicoTest', 'sendEvaluatePsicoTest');

function sendEvaluatePsicoTest()
{

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['action']) && $_POST['action'] == 'sendEvaluatePsicoTest') {
          // recibe json y quita los slash

          $data = stripslashes($_POST['data']);

          $files = imagesToArray($_FILES);

          $data = array(
            'imagenes' => $files,
            'data' => $data
          );


          dbsendEvaluatePsicoTest($data);

      }

    }
}

add_action('wp_ajax_CalifEvaluateTests2', 'CalifEvaluateTests2');
add_action('wp_ajax_nopriv_CalifEvaluateTests2', 'CalifEvaluateTests2');

function CalifEvaluateTests2()
{

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['CalifEvaluateTests2'])) {

        $data = stripslashes($_POST['CalifEvaluateTests2']);

        dbCalifEvaluateTests2($data);

      }
  }
}

add_action('wp_ajax_FinalResultTest', 'FinalResultTest');
add_action('wp_ajax_nopriv_FinalResultTest', 'FinalResultTest');

function FinalResultTest(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['FinalResultTest'])) {

        $data = stripslashes($_POST['FinalResultTest']);

        $data = json_decode($data, true);


        dbFinalResultTest($data);

      }

    }

}

add_action('wp_ajax_processbeginInterviewCycle', 'processbeginInterviewCycle');
add_action('wp_ajax_nopriv_processbeginInterviewCycle', 'processbeginInterviewCycle');

function processbeginInterviewCycle(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processbeginInterviewCycle'])) {

        $data = stripslashes($_POST['processbeginInterviewCycle']);

        $data = json_decode($data, true);


        dbprocessbeginInterviewCycle($data);

      }

  }

}

add_action('wp_ajax_processsendsetInterviewCalificate', 'processsendsetInterviewCalificate');
add_action('wp_ajax_nopriv_processsendsetInterviewCalificate', 'processsendsetInterviewCalificate');
function processsendsetInterviewCalificate(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processsendsetInterviewCalificate'])) {

        $data = stripslashes($_POST['processsendsetInterviewCalificate']);

        $data = json_decode($data, true);


        dbprocesssendsetInterviewCalificate($data);

      }

  }

}

add_action('wp_ajax_processrefreshInfoAddCands', 'processrefreshInfoAddCands');
add_action('wp_ajax_nopriv_processrefreshInfoAddCands', 'processrefreshInfoAddCands');

function processrefreshInfoAddCands(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processrefreshInfoAddCands'])) {

        $data = stripslashes($_POST['processrefreshInfoAddCands']);

        $data = json_decode($data, true);
        dbprocessrefreshInfoAddCands($data);
      }
  }
}


add_action('wp_ajax_processdetailsBalancOfferInfo', 'processdetailsBalancOfferInfo');
add_action('wp_ajax_nopriv_processdetailsBalancOfferInfo', 'processdetailsBalancOfferInfo');

function processdetailsBalancOfferInfo(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processdetailsBalancOfferInfo'])) {

        $data = stripslashes($_POST['processdetailsBalancOfferInfo']);

        $data = json_decode($data, true);
        dbprocessdetailsBalancOfferInfo($data);
      }
  }
}

add_action('wp_ajax_processsendFilterBolsaTrabajo', 'processsendFilterBolsaTrabajo');
add_action('wp_ajax_nopriv_processsendFilterBolsaTrabajo', 'processsendFilterBolsaTrabajo');

function processsendFilterBolsaTrabajo(){


  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processsendFilterBolsaTrabajo'])) {

        $data = stripslashes($_POST['processsendFilterBolsaTrabajo']);

        $data = json_decode($data, true);


        $tipo = $data['tipo'];

        $lugar = (isset($data['lugar']) && $data['lugar'] != '')? $data['lugar']: '';


        adminTsoluciono10($tipo, $lugar);

      }

  }


}


add_action('wp_ajax_createPromoAnounceProcess', 'createPromoAnounceProcess');
add_action('wp_ajax_nopriv_createPromoAnounceProcess', 'createPromoAnounceProcess');
function createPromoAnounceProcess(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['action']) && $_POST['action'] == 'createPromoAnounceProcess') {


        $archivos = $_FILES;
        $datos = $_POST;

        $datos = array(
          'imagen' => $archivos,
          'data' => $datos
        );

        if($datos['data']['servicio'] == 'Otro'){
          $datos['data']['servicio'] = $datos['data']['otroServicio'];
        }
        dbcreatePromoAnounceProcess($datos);

      }

  }

}

add_action('wp_ajax_integrateNewPostulateByAnounce', 'integrateNewPostulateByAnounce');
add_action('wp_ajax_nopriv_integrateNewPostulateByAnounce', 'integrateNewPostulateByAnounce');
function integrateNewPostulateByAnounce(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['integrateNewPostulateByAnounce']) && $_POST['action'] == 'integrateNewPostulateByAnounce') {


        $data = $_POST['integrateNewPostulateByAnounce'];
        $data = stripslashes($data);
        $data = json_decode($data, true);


        dbintegrateNewPostulateByAnounce($data);

      }
  }
}

add_action('wp_ajax_processsendintegrateNewPostulateByAnounce', 'processsendintegrateNewPostulateByAnounce');
add_action('wp_ajax_nopriv_processsendintegrateNewPostulateByAnounce', 'processsendintegrateNewPostulateByAnounce');
function processsendintegrateNewPostulateByAnounce(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['processsendintegrateNewPostulateByAnounce']) && $_POST['action'] == 'processsendintegrateNewPostulateByAnounce') {
        $data = $_POST['processsendintegrateNewPostulateByAnounce'];
        $data = stripslashes($data);
        $data = json_decode($data, true);

        dbprocesssendintegrateNewPostulateByAnounce($data);

      }

  }

}

add_action('wp_ajax_sendFilterInterviewByAnounce', 'sendFilterInterviewByAnounce');
add_action('wp_ajax_nopriv_sendFilterInterviewByAnounce', 'sendFilterInterviewByAnounce');
function sendFilterInterviewByAnounce(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if (isset($_POST['sendFilterInterviewByAnounce']) && $_POST['action'] == 'sendFilterInterviewByAnounce') {

        $data = $_POST['sendFilterInterviewByAnounce'];
        $data = stripslashes($data);
        $data = json_decode($data, true);

        $tipo = $data['tipo'];


        getListCandByAnounce($tipo);

      }
  }
}

add_action('wp_ajax_sendstateAnounce', 'sendstateAnounce');
add_action('wp_ajax_nopriv_sendstateAnounce', 'sendstateAnounce');
function sendstateAnounce(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if ($_POST['action'] == 'sendstateAnounce') {

        $archivos = $_FILES;
        $datos = $_POST;

        $datos = array(
          'imagen' => $archivos,
          'data' => $datos,
          'cambio' => true
        );

        dbcreatePromoAnounceProcess($datos);
      }
  }
}

add_action('wp_ajax_processsendchangeState', 'processsendchangeState');
add_action('wp_ajax_nopriv_processsendchangeState', 'processsendchangeState');
function processsendchangeState(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if ($_POST['action'] == 'processsendchangeState') {


        $data = $_POST['processsendchangeState'];
        $data = stripslashes($data);
        $data = json_decode($data, true);

        // print_r($data);

        dbprocesssendchangeState($data);
      }
  }
}

add_action('wp_ajax_continueforzarAsistencia', 'continueforzarAsistencia');
add_action('wp_ajax_nopriv_continueforzarAsistencia', 'continueforzarAsistencia');
function continueforzarAsistencia(){

  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
      if ($_POST['action'] == 'continueforzarAsistencia') {


        $data = $_POST['continueforzarAsistencia'];
        $data = stripslashes($data);
        $data = json_decode($data, true);

        // print_r($data);

        dbcontinueforzarAsistencia($data);
      }
  }
}



  ?>
