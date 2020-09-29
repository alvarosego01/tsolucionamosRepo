<?php



function formDetailsChangeDateCand(){  ?>
  <div class="formDetailsChangeDateCand" id="formDetailsChangeDateCand" style="display:none;">

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

function formFirmaUsers(){ ?>

  <div class="formFirma" id="formFirma" style="display:none;">
        <form action="" method="post" class="formData">
            <div class="field form-group FirmaUsuario">
                <label for="FirmaUsuario">Firma aqui</label>
                <div id="FirmaUsuario" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
                <input type="hidden" class="form-control form-control-sm" name="jsonFirmaUsuario" id="jsonFirmaUsuario">
                <div class="botones">
                    <a class="botoWeb borrar">Borrar</a>
                </div>
                <small class="validateMessage"></small>
            </div>
        </form>
    </div>

<?php }


// modificacion para el cambio
// ESTOY AQUI
function formDetailsChangePetition(){ ?>

  <div class="formDetailsChangePetition" id="formDetailsChangePetition" style="display:none;">

  <form action="" method="post" class="formData">

    <div class="row">

    <div class="form-group col field calificacion">
    <label for="calificacion">Recomendabilidad del candidato</label>

    <div class="star-rating">
        <span class="fa fa-star-o" data-rating="1"></span>
        <span class="fa fa-star-o" data-rating="2"></span>
        <span class="fa fa-star-o" data-rating="3"></span>
        <span class="fa fa-star-o" data-rating="4"></span>
        <span class="fa fa-star-o" data-rating="5"></span>
        <input onchange="changeStar(this)" type="hidden" name="calificacion" class="rating-value" value="0">
    </div>

    <small class="validateMessage"></small>
  </div>


    </div>


    <div class="row">
      <div class="field col form-group motivo">
        <label for="servicio">Motivo de solicitud</label>
        <select class="form-control form-control-sm" name="motivo">

            <option>
              Incumplimiento con las tareas asignadas
            </option>
            <option>
              Faltas o llegadas tardes.
            </option>
            <option>
              Presencia inadecuada.
            </option>
            <option>
              Vocabulario inadecuado.
            </option>
            <option>
              Incumplimiento de las reglas de la casa
            </option>
            <option>
              Otras…
            </option>

        </select>
        <small class="validateMessage"></small>
      </div>
    </div>

    <div class="row">
      <div class="field col form-group detalles">
        <label for="detalles">Cuéntanos los detalles</label>
        <textarea disabled class="form-control form-control-sm" name="detalles" id="" cols="30" rows="2">Sin detalles</textarea>
        <small class="validateMessage"></small>
      </div>
    </div>


    </form>

    </div>


<?php }

function misVacantes1($pagController = '')
{
    global $wpdb;

    $id = um_user('ID');
    $currentId = get_current_user_id();


    if (validateUserProfileOwner($id, $currentId, 'familia')) {
        $tabla = $wpdb->prefix . 'ofertalaboral';
        $data = $wpdb->get_results("SELECT * FROM $tabla WHERE contratistaId = $currentId", ARRAY_A);
        $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante'))); ?>


<div class="formSentOffer" id="formSentOffer" style="display:none;">

  <form action="" method="post" class="formData">
    <div class="field col form-group titulo">
      <label for="titulo">Titulo de la oferta</label>
      <input type="text" name="titulo">
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group servicio">
      <label for="servicio">Servicio</label>
      <select name="servicio">
      <option>
    Cocinero
</option>
<option>
    Personal trainer
</option>
<option>
    Jardinero
</option>
<option>
    Babysitter
</option>
<option>
    Personal Doméstico con Retiro
</option>
<option>
    Personal Doméstico con Cama
</option>
<option>
    Doméstica Especial para Mudanzas
</option>
<option>
    Cuidado del Adulto Mayor
</option>
<option>
    Multi Función
</option>
      </select>
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group horario">
      <label for="horario">Horario</label>
      <select name="horario" id="">
        <option value="Matutino">
          Matutino
        </option>
        <option value="Vespertino">
          Vespertino
        </option>
        <option value="Nocturno">
          Nocturno
        </option>
        <option value="TiempoCompleto">
          Tiempo completo
        </option>
      </select>
      <small class="validateMessage"></small>
    </div>

    <div class="field col form-group pais">
      <label for="pais">País</label>
      <select name="pais" id="">
        <?php
$countries = getPaises();
        foreach ($countries as $c) {?>
        <option value="<?php echo $c; ?>">
          <?php echo $c; ?>
        </option>
        <?php } ?>

      </select>
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group ciudad">
      <label for="ciudad">Ciudad</label>
      <input type="text" name="ciudad">
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group direccion">
      <label for="direccion">Dirección</label>
      <input type="text" name="direccion">
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group sueldo">
      <label for="sueldo">Sueldo a ofrecer</label>
      <input type="number" name="sueldo">
      <small class="validateMessage"></small>
    </div>
    <div class="field col form-group descripcion">
      <label for="descripcion">Descripción general</label>
      <textarea name="descripcion" id="" cols="30" rows="10"></textarea>
      <small class="validateMessage"></small>
    </div>

    <div class="field col form-group terminos">
      <input type="checkbox" name="terminos" /> Estoy de acuerdo con los
      <strong>Terminos y condiciones</strong> y las <strong>Políticas de Privacidad de los Datos</strong>
      <small class="validateMessage"></small>
    </div>
  </form>

</div>

<div class="formSentOffer2" id="formSentOffer2" style="display:none;">
  <form action="" method="post" class="formData">
    <div class="field col form-group firmaContratista">
      <label for="firmaContratista">Firma aqui</label>
      <div id="firmaContratista" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
      <input type="hidden" name="firmaContratistaJson" id="firmaContratistaJson">
      <div class="botones">
        <a class="botoWeb borrar">Borrar</a>
      </div>
      <small class="validateMessage"></small>
    </div>
  </form>
</div>

<h4>Ofertas laborales creadas</h4>

<div class="buttonCustom">
  <?php
  // do_shortcode('[viewLaboralOfferButton profile_id="' . $id . '" currentUser_id="' . $currentId . '"]');

  $nOfertaUrl = esc_url(get_permalink(get_page_by_title('Nueva oferta laboral')));

  ?>
    <form action="<?php echo $nOfertaUrl ?>" method="post">
        <input type="hidden" name="pg" value="1">
       <button id="profileLaboralOfferButton" class="um-alt btn btn-primary">
       <i class="fa fa-plus" aria-hidden="true"></i> Contrata nuestros servicios
      </button>
    </form>

</div>

<?php

if($pagController != ''){


  $porPagina = $pagController['porPagina'];
  $data = $pagController['data'];
  $pg = $pagController['pg'];

  $exc = "filterby='$data' porpagina='$porPagina' pg='$pg' family='$currentId'";

  echo do_shortcode('[getAllVacantes paginado="1" type="gestion" '.$exc.' ]');

}else{

  echo do_shortcode('[getAllVacantes type="gestion" family="'.$currentId.'"]');

}


    }
    if (validateUserProfileOwner($id, $currentId, 'candidata')) {
        // si son candatos entonces
        $data = dbGetAllMyPostulantions(array(
            'id' => $currentId
        ),$pagController);
        $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante'))); ?>

  <?php if(count($data) > 0){

      $pageData = $data['pageData'];
      unset($data['pageData']); ?>





<h4>Postulaciones a vacantes realizadas</h4>
<div class="row misPostulaciones">



<?php


foreach ($data as $r) {
            $serial = "?serial=" . $r['serialOferta'];
            $pg = $pagina . $serial;
            $imagen = $r['imagenes'];
            $imagen = json_decode($imagen, true);
            $imagen = $imagen['principal']['src'];
            $tipoPublic = $r['tipoPublic'];


  $tipoServicio = $r['tipoServicio'];

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
  <div class="item" >

    <div class="card">
      <?php if($tipoPublic == 'Promoción'){?>
        <div class="imgContainer">

      <img class="card-img-top w-100" src="<?php echo $imagen ?>"
      alt="Card image cap">
      <span>Anuncio</span>
        </div>
    <?php }else{ ?>
        <div class="icono">
          <?php echo $icono ?>
        </div>
   <?php } ?>
    <div class="card-body text-center">

      <h6 class="card-title col-sm">
        <?php echo $r['nombreTrabajo'] ?>
      </h6>


      <p class="card-text"><strong>Mensaje</strong> <br>
        <?php echo $r['mensaje'] ?>
      </p>
      <!-- <a href="#" class="card-link"><i class="fa fa-envelope-o" aria-hidden="true"></i> Escribe al contratista</a> -->
      <a href="<?php echo $pg; ?>"
        class="d-flex justify-content-center align-items-center btn btn-primary">
        <i class="fa fa-check" aria-hidden="true"></i> Detalles
      </a>

    </div>
  </div>
  </div>
  <?php
        } ?>
</div>


<?php
  $pag = $pageData['pageno'];
  $total_pages = $pageData['total_pages'];
  $filterBy = $pageData['filterBy'];
  ?>

<div class="pagineishon">
  <?php echo paginate('canTab1', $pag, $total_pages ); ?>
</div>

<?php } else{ ?>
  <div class="row">
  <h4>No hay resultados en este momento</h4>
</div>
<?php } ?>
<?php
    }
}

function misVacantes8($pagController = ''){

    global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'candidata')) {
    $tabla = $wpdb->prefix . 'facturacion_profesional';


      $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where candidatoId = $currentId", ARRAY_A);

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
           $fd = getInfoNameEmailUsers($currentId);
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
</div>


  <?php }else{ ?>
    <h4>No hay resultados en este momento</h4>
   <?php
  }



  }


}





function misVacantes2($pagController = '')
{
    global $wpdb;
    global $wp_roles;

    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, 'familia')) {


        $data = dbGetInfoMyVacantTab2($currentId, $pagController);


      if (count($data) > 0) {

          $pageData = $data['pageData'];
          unset($data['pageData']);

        }


  if (count($data) > 0) {

    $pageData = $data['pageData'];
    unset($data['pageData']);
    // print_r($pageData);

  ?>



<div id="viewReasonsCand">

</div>

<h4>Postulaciones y entrevistas </h4>


<div class="entrevista row">
  <?php foreach ($data as $r) { ?>

         <?Php
            $contratistaId = $r['contratistaId'];
            $oferta = $r['oferta'];
            // oferta
            $serial = $oferta['serialOferta'];
            $idOferta = $oferta['id'];
            $descripciónOferta = $oferta['descripcionExtra'];
            $nombreTrabajo = $oferta['nombreTrabajo'];
            $fechaCreado = $oferta['fechaCreacion'];
            $estado = $oferta['estado'];

            $usuario = get_user_meta($contratistaId);
            // nombre
            $nombreContratista = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            // urlPerfil
            $urlContratista = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

            // url a la oferta
            $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));

            $urlOferta = $pagina . "?serial=$serial";

            $tabla = $wpdb->prefix . 'proceso_contrato';
            $xxx = $wpdb->get_results("SELECT etapa FROM $tabla where ofertaId='$idOferta' ", ARRAY_A);
            $wpdb->flush();
            $etapaEntrevista = (isset($xxx[0]['etapa']))?$xxx[0]['etapa']: '';

            $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
            $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
            $elegidos = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas as proceso INNER JOIN $tablaprocesoEntrevistasEtapas as etapas ON (proceso.id = etapas.idEntrevista) WHERE proceso.ofertaId='$idOferta' and etapas.aprobado = 1 and ( etapas.tipoEntrevista = 'Entrevista con Recursos Humanos' OR etapas.tipoEntrevista = 'Añadido al proceso de entrevistas' )", ARRAY_A);
            $wpdb->flush();

            $tablaprocesoEntrevistas = $wpdb->prefix . 'proceso_contrato';
            $iii = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistas WHERE ofertaId='$idOferta' and contratistaId=$contratistaId and candidataId=$contratistaId", ARRAY_A);
            $wpdb->flush();

            if (count($iii) > 0) {
                $ideide= $iii[0]['id'];

                $x = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas WHERE idEntrevista='$ideide'", ARRAY_A);
                $res = '';

                if (($x != null) && ($etapaEntrevista >= 2)) {
                    $res = $x[0]['resultadosEntrevista'];

                    $res = json_decode($res, true);

                    $auxiliar = $res;

                    $res = $res['seleccionPor'];
                }
            } ?>


      <?php $existeContrato = ''; ?>
  <?php if(count($elegidos) > 0){ ?>

  <div class="card">
    <div class="card-body">
      <div class="dataOffer row justify-content-around">

        <?php if (count($elegidos) > 0) { ?>
        <!-- <h6>Etapa: <?php echo $etapaEntrevista + 1; ?></h6> -->
        <?php } ?>
        <h6>Vacante: <a class="hiper"
            href="<?php echo $urlOferta ?>"> <?php echo $nombreTrabajo ?></a></h6>
        <h6>Publicado: <?php echo $fechaCreado; ?>
        </h6>
      </div>
      <?php
      $entidCont = '';
            if (count($elegidos) > 0) {

              // colocar a familia en ultimo lugar
              $aux = null;
              foreach ($elegidos as $key => $value) {
                if($value['contratistaId'] == $value['candidataId'] ){
                  $aux = $value;
                  unset($elegidos[$key]);
                }
              }
              if($aux != null){
                array_push($elegidos, $aux);
              }
              // ----------------------------------


            foreach ($elegidos as $key => $value) {



                    $ide = $value['id'];
                    $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
                    $xxx = $wpdb->get_results("SELECT * FROM $tablaprocesoEntrevistasEtapas where id='$ide' ", ARRAY_A);
                    $wpdb->flush();
                    $cid = $value['candidataId'];
                    $candidataId = $cid;
                    $usuario = get_user_meta($value['candidataId']);

                    $u = get_userdata($candidataId);

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

                    if (($value['contratistaId'] == $currentId) && ($value['candidataId'] == $currentId)) {
                        $entidCont = $value['idEntrevista'];
                    } else {
                        $entidCont = 'xxx';
                    } ?>

             <?php
             $ss = array(
              'idCandidato' => $value['candidataId'],
              'idEntrevista' => $value['idEntrevista']
            );
            $estadoCandidatoContrato = getStateCandidateOnContract($ss);
      // etapa de selección de candidato por la familia
      if ($etapaEntrevista >= 2) {
        // identificar si existe un contrato corriendo y si hay cambios
        $contratos = $wpdb->prefix . 'contratos';
        $historial = $wpdb->prefix . 'historialcontratos';
        $ooo =  $wpdb->get_results("SELECT * FROM $contratos as contratos INNER JOIN $historial as historial ON (contratos.id = historial.contratoId) where contratos.ofertaId = '$idOferta' and contratos.contratistaId = $contratistaId and contratos.candidataId = $candidataId", ARRAY_A);
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
        }
        if( ($existeContrato['activos'] == 0)  ){
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
        }
        $x = '';
        $tool = '';


        if($value['candidataId'] == $auxiliar['candidatoSeleccionado']){
          $x = 'cSelected';

        }

        if($statusContrato['estado'] == 'activo'){
          if(($statusContrato['status']['engarantia'] == 1)){
            $x = ' cGarantia';
            $tool = 'Candidato en garantía de prueba';
           }

        }
        if($statusContrato['estado'] == 'inactivo'){
          if( ($statusContrato['status']['solCambio'] == 1)){
            $x =  ' cChanged' ;
            $tool = 'Candidato cambiado por la familia';
          }
          if( ($statusContrato['status']['caducado'] == 1)){
            $x =  ' cExpirate' ;
          }
        }

        if(!isset($statusContrato)){
          if( $value['fechaPautado'] == 'Adicional'){
            $x =  ' cExtra' ;
            $tool = 'Candidato adicional, sugerido por Administración';
          }
        }
        ?>

      <div <?php echo $t = ($tool != '')? "data-toggle='tooltip' data-placement='top' title='$tool'": '' ?> class="row candidato <?php echo $x;?>"
        style="border-bottom: 1px solid rgba(0,0,0,0.2); padding: 25px;">
        <?php
            if ($value['candidataId'] != $currentId) {
                $ft = $usuario['fotoFondoBlanco'][0];
                // $ft = explode('"', $ft);
                // $fotoBlanco = $ft[7];
                // $fotoBlanco = str_replace('/temp/', '/'.$candidataId.'/', $fotoBlanco );
                // $url = '/wp-content/uploads/ultimatemember/' . $value['candidataId'] . '/' . $ft;
                // $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsoluciono' . $url : $url;


                            if($ft != null){

                                $url = '/wp-content/uploads/ultimatemember/' . $value['candidataId'] . '/' . $ft;
                                $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;

                            }else{

                                $fotoBlanco = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos/wp-content/uploads/no-imagen.jpeg': '/wp-content/uploads/no-imagen.jpeg';
                            }


          } ?>


        <div class="col-2 imagenContainer">
          <div class="imagen">
            <?php  if ($value['candidataId'] != $currentId) { ?>

            <img src="<?php echo $fotoBlanco ?>" alt="">
            <?php } ?>
          </div>
        </div>
        <div class="col-10 info">
          <div class="container" style="padding-top: 0;">
            <?php if ($value['candidataId'] != $currentId) { ?>
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
                  <?php echo formatUTF8($infoResultadoCandidato['cumpleCandidato']); ?>
                </p>
              </div>
              <div class="col recomendado">
                <p style="padding: 0; margin-bottom: 0">
                  Recomendado:
                  <?php $recomendabilidad = $infoResultadoCandidato['recomendabilidad'] ?>

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


                </p>

              </div>

              <?php if (($etapaEntrevista == 2) || ($etapaEntrevista == 5) ) {
                $pagina = esc_url(get_permalink(get_page_by_title('Información de entrevista')));
                $postuladoId = $value['candidataId'];
                $cccid = $value['contratistaId'];
                $ideentr = $value['idEntrevista'];
                $ofid = $value['ofertaId'];


                $entrevistaCandidatoUrl = esc_url(get_permalink(get_page_by_title('Información de entrevista')));
                $entrevistaCandidatoUrl .= '?ie='.$ideentr;

                $x = array(
                'currentId' => get_current_user_id(),
                'can' => $postuladoId,
                'fam' => $cccid
                );
                $x = json_encode($x, JSON_UNESCAPED_UNICODE );


                ?>
              <div class="col opc">
              <form target="_blank" method="post" action="<?php echo $entrevistaCandidatoUrl; ?>">
                  <input type="hidden" name="dataInterview" value='<?php echo $x; ?>'>
                  <div class="buttonCustom">
                    <button type="submit" class="btn btn-success btn-block"> Ver entrevista</button>
                  </div>
              </form>

                <?php

                if ( (($res == 'Familia') || ($res == 'Ambos')) &&
                  ( (!isset($statusContrato['status']['solCambio'])  )  )
                ) {

                      $x = array(
                    'ofertaId' => $ofid,
                    'current' => $currentId,
                    'can' => $postuladoId,
                    'fam' => $cccid,
                );


                $x = json_encode($x, JSON_UNESCAPED_UNICODE);
                $paginaContrato = esc_url(get_permalink(get_page_by_title('Información de contrato'))); ?>

                <button type="button" class="btn btn-primary btn-block" onclick='selectForContract(<?php echo $x; ?>)'>
                  Seleccionar para el cargo
                </button>

                <?php if($etapaEntrevista == 5){

                } ?>

                <?php }
                // ver detalles de cambios de la familia sobre el candidato
                if(isset($estadoCandidatoContrato) && $estadoCandidatoContrato != null){

                  $data = array(
                    'candidataId' => $estadoCandidatoContrato['idCandidato'],
                    'entrevistaId' => $estadoCandidatoContrato['idEntrevista']
                  );
                  $data = json_encode($data, JSON_UNESCAPED_UNICODE) ?>


                <div class="buttonCustom">
                  <button alt="Comentarios y razones de la familia" onclick='viewReasonsCandFam(<?php echo $data ?>)' class='btn btn-block btn-warning'><small> Detalles </small></button>
                </div>

              <?php } ?>

              </div>



              <?php } ?>
            </div>


            <div class="row">
              <div class="col infoResultados">
                <p>
                  Información de la entrevista: <br>
                  <?php echo formatUTF8($infoResultadoCandidato['infoCandidatoEntrevista']); ?>
                </p>
              </div>
            </div>
            <?php } ?>
            <?php if ($value['candidataId'] == $currentId) {


              ?>


            <div class="row">
              <div class="col nombre">

                <p>
                  Nombre: <br>
                  <?php echo $nameC; ?>
                </p>
              </div>
              <div class="col cumple">
                <p>
                  ¿Se resolvió una propuesta para la familia?: <br>
                  <?php echo formatUTF8($infoResultadoCandidato['solucionPropuesta']); ?>
                </p>
              </div>
              <!-- <div class="col recomendado">
                            <p>
                                ¿Quíen enviará la propuesta de contrato?: <br>
                                <?php echo formatUTF8($infoResultadoCandidato['seleccionPor']); ?>
              </p>
            </div> -->
          </div>
          <div class="row">
            <div class="col infoResultados">
              <p>
                Información de la entrevista: <br>
                <?php echo formatUTF8($infoResultadoCandidato['infoEntrevistaFamilia']); ?>
              </p>
            </div>
          </div>
          <?php } ?>

        </div>
      </div>
    </div>
    <?php } ?>

    <?php }
            if (($etapaEntrevista < 2)) {
                    $datos = array();

                    $p = array();
                    foreach ($elegidos as $rr) {
                        $idEntrevista = $rr['id'];


                        $tablaprocesoEntrevistasEtapas = $wpdb->prefix . 'proceso_contrato_etapas';
                        $x = $wpdb->get_results("SELECT * from $tablaprocesoEntrevistasEtapas WHERE id='$idEntrevista'", ARRAY_A);
                        $wpdb->flush();

                        array_push($p, $x[0]);
                    }

                    $d = array(
                        'entrevista' => $elegidos,
                        'seleccionados' => $p,
                        'contratistaId' => $contratistaId,
                    );

                    array_push($datos, $d); ?>

    <div class="col-12">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Nombre y rol</th>
            <th scope="col">Seleccionado</th>
            <th scope="col">Entrevista</th>
            <th scope="col">Estado</th>
            <th scope="col">Asistencia</th>
            <th scope="col">Tipo entrevista</th>
          </tr>
        </thead>
        <tbody>

             <?php

      foreach ($datos[0]['seleccionados'] as $r) {
          // etapas obtengo el ultimo de las etapas, lo cual es el mas importantep ara esquematizar las etapas en la lista
          // $et = end($r['etapas']);


          $et = $r;

          $confAsistencia = $r['confirmaFecha'];
          $confAsistencia = json_decode($confAsistencia, true);

          // $Etapa = $et['etapa'];
          $Seleccionado = $et['fechaCreacion'];
          $proxima = ($et['fechaPautado'] == 'Realizada')?$et['fechaPautado']:$et['fechaPautado'].' - '.$et['hora'];

          // $proxima = 'aaa';
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
          // if ($r['idEntrevista'] != $entidCont) {
//
              // $nombre = '-Nombre Oculto-';
          // }

          $nombre .= ' ' . '(' . $rolCandidata . ')';
          $pagina = esc_url(get_permalink(get_page_by_title('Información de entrevista')));

          $postuladoId = $candidataId;


          if((isset($ooo)) && (count($ooo) > 0)){ ?>

          <?php } ?>
          <?php if ($r['idEntrevista'] != $entidCont) { ?>


          <tr>
            <td><?php echo $nombre ?>
            </td>
            <td><?php echo $Seleccionado ?>
            </td>
            <td><?php echo $proxima ?>
            </td>
            <td><?php echo $stdo ?>
            </td>
            <td>
              <?php
                  if( isset($confAsistencia['candidato']) ){
                    $xx = $confAsistencia['candidato'];
                    $c = ( isset($xx['estado']) )?'Pendiente':$xx;

                    ?>

                      <?php echo $c; ?>

                    <?php
                  }
              ?>
            </td>
            <td><?php echo $Tipo ?>
            </td>

          </tr>
          <?php } ?>

          <?php if ($r['idEntrevista'] == $entidCont) { ?>

          <td><?php echo $nombre ?>
          </td>
          <td><?php echo $Seleccionado ?>
          </td>
          <td><?php echo $proxima ?>
          </td>
          <td><?php echo $stdo ?>
          </td>
          <td>
          <?php
              // botones para confirmar o pedir otra fecha
              if(isset($confAsistencia['familia'])){
                //
              if($confAsistencia['familia'] == 'Confirmada'){
                echo 'Confirmada';
              }elseif($confAsistencia['familia'] == 'Pendiente'){
                if(isset($confAsistencia['admin']['estado']) && $confAsistencia['admin']['estado'] == 'Propuesta' ){
                  $c = $confAsistencia['admin'];
                  ?>
                  <small>
                  Tsoluciono te ofrece cambiar a <br>
                   <?php echo $c['date'].' - '.$c['hora']; ?>
                  </small>
               <?php } ?>

                <div class="buttonCustom">
                  <button onclick="preSendFamConfirmDate('<?php echo $idEntrevista; ?>')" class="btn btn-success btn-block">Confirmar asistencia</button>

                  <button onclick="preSendFamSolChangeDate('<?php echo $idEntrevista; ?>')" class="btn btn-info btn-block">Reprogramar hora y fecha</button>
                </div>

              <?php }elseif(isset($confAsistencia['familia']['estado']) && $confAsistencia['familia']['estado'] == 'Propuesta'){
                $c = $confAsistencia['familia']; ?>

                <small>
                  Solicitaste cambiar a <br>
                  <?php echo $c['date'].' - '.$c['hora']; ?>
                </small>

              <?php }
              }?>

            </td>
          <td><?php echo $Tipo ?>
          </td>

          </tr>
          <?php } ?>

          <?php
      } ?>
        </tbody>
      </table>
    </div>
    <?php
    } ?>


  </div>


  <?php

  if ($res == 'Tsoluciono' && !isset($existeContrato)) { ?>


  <p class="avisoRes">
    ¡Has seleccionado a un candidato, nosotros nos encargaremos del contrato!
  </p>
  <?php }
  if ($res == 'Familia') { ?>
  <p class="avisoRes">
  <?php echo $x = ($etapaEntrevista == 5)?'Has solicitado un cambio de candidato - ':''?>
  Selecciona a un candidato y nosotros hacemos el resto
  </p>
  <?php }
$res = '';
  ?>

</div>
  <?php } ?>


<?php
            } else { ?>
<!-- <p style="text-align: center">
  Los postulados aún no han sido elegidos
</p> -->
<?php }
        } ?>
    </div>

  <?php
  $pag = $pageData['pageno'];
  $total_pages = $pageData['total_pages'];
  $filterBy = $pageData['filterBy'];
  ?>

<div class="pagineishon">
  <?php echo paginate('famTab2', $pag, $total_pages ); ?>
</div>



     <?php   } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php } ?>

<?php
    }
    if (validateUserProfileOwner($currentId, $currentId, 'candidata')) {
        $data = dbGetInfoMyVacantTab2($currentId, $pagController);




      if (count($data) > 0) {

          $pageData = $data['pageData'];
          unset($data['pageData']);

        }


        if (count($data) > 0) {

          ?>

<h4>Vacantes con entrevistas para ti</h4>

<div class="list-group row entrevistasCandidata">

  <?php foreach ($data as $r) {
            $contratistaId = $r['contratista'];
            $entrevista = $r['entrevista'];
            $etapas = $r['etapas'];
            $oferta = $r['oferta'];
            $contratista = $r['contratista'];

            // oferta
            $serial = $oferta['serialOferta'];
            $idOferta = $oferta['id'];
            $descripciónOferta = $oferta['descripcionExtra'];
            $nombreTrabajo = $oferta['nombreTrabajo'];
            $fechaCreado = $oferta['fechaCreacion'];
            $estado = $oferta['estado'];

            // etapas de entrevistas
            $idEntrevista = $entrevista['id'];
            $candidataId = $entrevista['candidataId'];

            // $postulantes = $r['postulantes'];
            $usuario = get_user_meta($contratistaId);
            // nombre
            $nombreContratista = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

            // urlPerfil
            $urlContratista = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

            // url a la oferta
            $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));

            $urlOferta = $pagina . "?serial=$serial";


            $etapaEntrevista = $r['entrevista']['etapa'];


            // verificar si existe contrato
            $contratos = $wpdb->prefix . 'contratos';
            $historial = $wpdb->prefix . 'historialcontratos';

            $ooo =  $wpdb->get_results("SELECT * FROM $contratos as contratos INNER JOIN $historial as historial ON (contratos.id = historial.contratoId) where contratos.ofertaId = '$idOferta' and contratos.contratistaId =  $contratista and contratos.candidataId = $candidataId", ARRAY_A);
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

        }
        if( ($existeContrato['activos'] == 0)  ){
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

        }

        ?>


  <div class="card <?php echo $x = ($statusContrato['estado'] == 'activo')? 'cActive':''; echo $x = ($statusContrato['estado'] == 'inactivo')? 'cInactive':'' ?>">

    <div class="card-body">

      <div class="dataOffer row justify-content-around">
        <!-- <h6>Etapa: <?php echo $etapaEntrevista + 1; ?></h6> -->
        <h6>Vacante: <a class="hiper"
            href="<?php echo $urlOferta ?>"> <?php echo $nombreTrabajo ?></a></h6>
        <h6>Publicado: <?php echo $fechaCreado; ?>
        </h6>
        <h6>Familia: <a href="<?php echo $urlContratista; ?>"
            class="hiper"> <?php echo $nombreContratista; ?></a></h6>




      </div>


      <table class="table table-hover">
        <thead>
          <tr>

            <th scope="col">Seleccionado en</th>

            <th scope="col">Entrevista</th>
            <th scope="col">Tipo entrevista</th>
            <th scope="col">Estado</th>
            <th scope="col">Asistencia</th>
          </tr>
        </thead>
        <tbody>

          <?php
        foreach ($etapas as $e) {
            $idEntrevista = $e['idEntrevista'];
            $fechaCreacion = $e['fechaCreacion'];
            $fechaPautado = $e['fechaPautado'];
            $hora = $e['hora'];
            $estado = $e['estado'];
            $etapa = $etapaEntrevista;
            $tipoEntrevista = $e['tipoEntrevista'];
            $aprobado = ($e['aprobado'] == 1)?'Aprobado':'En espera';
            $datoEntrevista = $e['datoEntrevista'];

            $confirmaFecha = $e['confirmaFecha'];
            $confirmaFecha = json_decode($confirmaFecha, true);


            switch ($etapa) {
            case '1':
              $entrevistaCon = 'Administración';
              break;
              case '2':
              $entrevistaCon = 'Administración';
              break;
              case '3':
              $entrevistaCon = 'Familia';

              break;

            default:

              break;
          } ?>
          <tr>

            <td><?php echo $fechaCreacion ?>
            </td>

            <td><?php echo $fechaPautado ?>
            </td>
            <td><?php echo $tipoEntrevista ?>
            </td>
            <td><?php echo $aprobado ?>
            </td>
            <td>
              <?php
              // botones para confirmar o pedir otra fecha
              if($confirmaFecha['candidato'] == 'Confirmada'){
                echo 'Confirmada';
              }elseif($confirmaFecha['candidato'] == 'Pendiente' && $etapa != 5){
                if(isset($confirmaFecha['admin']['estado']) && $confirmaFecha['admin']['estado'] == 'Propuesta' ){
                  $c = $confirmaFecha['admin'];
                  ?>
                  <small>
                  Tsoluciono te ofrece cambiar a <br>
                   <?php echo $c['date'].' - '.$c['hora']; ?>
                  </small>
               <?php } ?>

                <div class="buttonCustom">
                  <button onclick="preSendCandConfirmDate('<?php echo $idEntrevista; ?>')" class="btn btn-success btn-block">Confirmar asistencia</button>

                  <button onclick="preSendCandSolChangeDate('<?php echo $idEntrevista; ?>')" class="btn btn-info btn-block">Reprogramar hora y fecha</button>
                </div>

              <?php }elseif(isset($confirmaFecha['candidato']['estado']) && $confirmaFecha['candidato']['estado'] == 'Propuesta'){
                $c = $confirmaFecha['candidato']; ?>

                <small>
                  Solicitaste cambiar a <br>
                  <?php echo $c['date'].' - '.$c['hora']; ?>
                </small>

              <?php } ?>


              <?php if($etapa == 5){ ?>
              <?php if($confirmaFecha['candidato'] == 'Pendiente'){ ?>
                <div class="buttonCustom">
                  <button onclick="acceptAditionalPurpose('<?php echo $idEntrevista; ?>')" class="btn btn-success btn-block">Aceptar propuesta</button>

                  <button onclick="refuseAditionalPurpose('<?php echo $idEntrevista; ?>')" class="btn btn-info btn-block">Rechazar propuesta</button>
                </div>
              <?php } ?>

             <?php  } ?>
            </td>
          </tr>

          <?php
        } ?>
        </tbody>
      </table>
      <?php

if (($etapa == 3) && ($e['aprobado'] == 1)) {
    $S = array(
    'can' => $candidataId,
    'fam' => $contratistaId,
    'ofertaId' => $idOferta
    );
    $infoExist = dbContractExistValidate($S, true);

    if ((isset($infoExist)) && (count($infoExist) > 0)) {
        if (($infoExist[0]['postuladoID'] == $currentId)) {
            $x = array(
        'ofertaId' => $idOferta,
        'current' => $currentId,
    'can' => $candidataId,
    'fam' => $contratistaId,
  );
            $x = json_encode($x ,JSON_UNESCAPED_UNICODE);

            $paginaContrato = esc_url(get_permalink(get_page_by_title('Información de contrato'))); ?>

      <div class="opc d-flex justify-content-center  flex-column">
        <small style="text-align: center">Felicidades, cumples con todos los requisitos para ser contratado</small>
        <form target="_blank" id="formVerEstadoEntrevista" method="post"
          action="<?php echo $paginaContrato ?>">
          <input type='hidden' name='dataContrato'
            value='<?php echo $x ?>' />
          <div class="buttonCustom d-flex justify-content-center">
            <button type="submit" class="btn btn-success"><small><i class="fa fa-check" aria-hidden="true"></i> Ver propuesta de contrato </small></button>
          </div>
        </form>
      </div>

      <?php
        }
    }
} ?>



    </div>
    <?php if(isset($statusContrato['estado'])){ ?>



        <?php if(($statusContrato['estado'] == 'activo') && ($statusContrato['status']['aceptado'] == 1)){ ?>
      <div class="card-footer">
          <small class="aviso">
          Posees un contrato <strong>ACTIVO</strong> en esta vacante<?php echo $x = ($statusContrato['status']['engarantia'] == 1)? ' - En periodo de garantía': ''. $x = ($statusContrato['status']['definitivo'])? ' - ¡Contrato definitivo!': ''?>

          </small>
      </div>
        <?php } ?>
        <?php if(($statusContrato['estado'] == 'inactivo') && ($statusContrato['status']['cancelado'] == 1)){ ?>
      <div class="card-footer">
          <small class="aviso">
          Posees un contrato <strong>INACTIVO</strong> en esta vacante <?php echo $x = ($statusContrato['status']['solCambio'] == 1)? ' - La familia ha solicitado un cambio de candidato ': ''. $x = ($statusContrato['status']['caducado'])? ' - ¡El contrato ha llegado a su fin!': ''?>

          </small>
      </div>
        <?php } ?>

        <?php } ?>
    </div>
    <?php
        } ?>
        </div>

        <?php
  $pag = $pageData['pageno'];
  $total_pages = $pageData['total_pages'];
  $filterBy = $pageData['filterBy'];
  ?>

<div class="pagineishon">
  <?php echo paginate('canTab2', $pag, $total_pages ); ?>
</div>


       <?php } else { ?>
<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
    }
}

function misVacantes3($pagController = '')
{
  formDetailsChangePetition();
  formDetailsChangeDateCand();
    global $wpdb;

    $currentId = get_current_user_id();
    $fechaActual = date('d/m/y');
    if (validateUserProfileOwner($currentId, $currentId, 'familia')) {
        $p = array(
            'current' => $currentId,
            'tipo' => 'familia',
        );
        $data = dbgetMyActiveContracts($p, $pagController);

        if (count($data) > 0) {

          $pageData = $data['pageData'];
          unset($data['pageData']);
        }
          ?>



<div id="ExperienciaContractCand" style="display:none;">



    <form action="" method="post" class="formData">

    <div class="row">

  <div class="form-group col field calificacion">
    <label for="calificacion">Recomendabilidad del candidato</label>
    <div class="star-rating">
        <span class="fa fa-star-o" data-rating="1"></span>
        <span class="fa fa-star-o" data-rating="2"></span>
        <span class="fa fa-star-o" data-rating="3"></span>
        <span class="fa fa-star-o" data-rating="4"></span>
        <span class="fa fa-star-o" data-rating="5"></span>
        <input type="hidden" name="calificacion" class="rating-value" value="0">
    </div>
    <small class="validateMessage"></small>
  </div>
  </div>


  <div class="row">
        <div class="field col form-group desempeno">
          <label for="servicio">Desempeño</label>
          <select class="form-control form-control-sm" name="desempeno">
            <option>Excelente</option>
            <option>Buen desempeño con algunos inconvenientes</option>
            <option>Regular</option>
          </select>
          <small class="validateMessage"></small>
        </div>
      </div>

      <div class="row">
        <div class="field col form-group detalles">
          <label for="detalles">Cuéntanos los detalles</label>
          <textarea disabled class="form-control form-control-sm" name="detalles" id="" cols="30" rows="2">Sin detalles</textarea>
          <small class="validateMessage"></small>
        </div>
      </div>


  </form>
    </div>



<?php if (count($data) > 0) {?>

<h4>Tus contratos</h4>

<div class="row contractListCand">
<?php
foreach ($data as $r) {

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

    $diasPasados = dias_pasados($fechaInicio, $fechaActual);
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
    $x = json_encode($x, JSON_UNESCAPED_UNICODE);

    //-----
    $nnn =  getInfoNameEmailUsers($postuladoId);
    $nombre = $nnn['nombre'];

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
    <h6  style="text-align: left;">Contrato con <strong><?php echo $nombre; ?></strong> - <?php echo $r['nombreTrabajo']; ?></h6>
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

      <?php if(($r['engarantia'] == 1) && ($diasPasados <= 90)){ ?>

        <div class="opcAdd">

        <button onclick='showGarantContractOpc(<?php echo $xx; ?>)' type="submit" class="btn btn-info"><i class="fa fa-cog" aria-hidden="true"></i> Opciones de garantía</button>
        </div>


        <?php } ?>

      </div>
    </div>


  </div>



</div>

<?php
      }

          ?>
</div>
<?php

  $pag = $pageData['pageno'];
  $total_pages = $pageData['total_pages'];
  $filterBy = $pageData['filterBy'];
  ?>
<div class="pagineishon">
  <?php echo paginate('famTab3', $pag, $total_pages  ); ?>
</div>


<?php } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
    }
    if (validateUserProfileOwner($currentId, $currentId, 'candidata')) {
        $p = array(
            'current' => $currentId,
            'tipo' => 'candidata',
        );
        $data = dbgetMyActiveContracts($p);

        ?>

        <pre>
          <?php print_r($data); ?>
        </pre>

<?php if (count($data) > 0) {?>

  <h4>Tus contratos</h4>

<div class="row contractListCand">
  <?php
foreach ($data as $r) {

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

    $pagina = esc_url(get_permalink(get_page_by_title('Información de contrato')));
    $c = get_current_user_id();

    // 5dc0f6dbbdf5e5dc0f6dbbdf61

    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialOferta;

    $x = array(
        'ofertaId' => $ofid,
        'current' => $c,
        'can' => $postuladoId,
        'fam' => $contratistaId,
      );
      $x = json_encode($x, JSON_UNESCAPED_UNICODE);

      print_r($x);
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
      <h6  style="text-align: left;">Contrato con <strong><?php echo $nombre; ?></strong> - <?php echo $r['nombreTrabajo']; ?></h6>
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

        <a target="_blank" href="<?php echo $vacanteUrl ?>" class="btn btn-info">Ver oferta laboral</a>

        <form target="_blank" id="formVerContratos" method="post"
        action="<?php echo $pagina ?>">
        <input type='hidden' name='dataContrato'
        value='<?php echo $x ?>' />

          <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Ver
          contrato</button>

        </form>

    </div>
      </div>


    </div>



  </div>

  <?php
        }

            ?>
</div>
<?php } else {?>

<div class="row">
  <h4>No hay resultados en este momento</h4>
</div>

<?php }
    }
}

function misVacantes4($pagController = '')
{

    global $wpdb;
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, 'ambos')) {
      $infoNotif = infoNotification($currentId, $pagController);

      ?>


      <div class="row notifTab">
        <?php if(count($infoNotif) > 0){

        $pageData = $infoNotif['pageData'];
        unset($infoNotif['pageData']); ?>



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

                $xxx = json_encode($xxx, JSON_UNESCAPED_UNICODE);
          ?>



            <tr>
                <td style="text-align: center; font-size: 20px"><?php echo $x = ($stdo == 0)? '<i class="fa fa-envelope-o" aria-hidden="true"></i>': '<i class="fa fa-envelope-open-o" aria-hidden="true"></i>' ?></td>
                <td><?php echo $subject ?></td>

                <td><?php echo $fecha ?></td>
                <td>
                <div class="buttonCustom">


                    <a class="btn btn-block btn-info" href="<?php echo $pagina.'?mensaje='.$idNotif; ?>">
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


        <?php

$pag = $pageData['pageno'];
$total_pages = $pageData['total_pages'];
$filterBy = $pageData['filterBy'];
?>
<div class="pagineishon">
<?php echo paginate('famTab4', $pag, $total_pages  ); ?>
</div>




        <?php } else{ ?>
          <h4>No hay resultados en este momento</h4>
        <?php }?>
      </div>


    <?php }

}

function templateMisVacantes($args = array())
{
    global $wpdb;
    $defaults = array(
        "rows" => 0,
    );
    $args = wp_parse_args($args, $defaults);
    $id = um_user('ID');
    $currentId = get_current_user_id();
    // form para cambiar de firma
    formFirmaUsers();

    if(!is_user_logged_in()){

      if( isset($_GET['drec']) && $_GET['drec'] != null){

        $login = esc_url(get_permalink(get_page_by_title('Iniciar sesión')));
        $login .= '?drec='.$_GET['drec'];

        echo $login;
        header('Location:'.$login);
        die();


        // die(
        // );
      }else{

        header('Location:'. get_home_url( ) );
      }

    }

    if( isset($_GET['tab']) ){
      $page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $tab = $_GET['tab'];
      $page_url = remove_query($page_url);

      $page_url .= '#tab'.$tab;
      die(

        header('Location:'.$page_url)
      );
    }

    ?>

  <div class="tabMisVacantes container">
    <div class="row contener">

      <div class="navOpc col-3">
        <h5>Panel de usuario</h5>



    <?php if (validateUserProfileOwner($id, $currentId, 'familia')) {?>

      <ul>
      <li><a href="#tab1">Mis vacantes</a></li>
      <li><a href="#tab2">Vacantes con postulados</a></li>
      <li><a href="#tab3">Contratos</a></li>
      <li><a href="#tab4">Notificaciones</a></li>
      <li><a href="#tab5">Facturas</a></li>
      <li><a href="#tab6">Configuraciones</a></li>
      </ul>

  <?php } elseif ( validateUserProfileOwner($id, $currentId, 'candidata') && !validateUserProfileOwner($id, $currentId, 'profesional') ) {?>

    <ul>
      <li><a href="#tab1">Mis postulaciones</a></li>

      <li><a href="#tab2">Proceso de entrevistas</a></li>
      <li><a href="#tab3">Contratos</a></li>
      <li><a href="#tab4">Notificaciones</a></li>

      <li><a href="#tab6">Configuraciones</a></li>
    </ul>

    <?php ?>

  <?php } elseif (validateUserProfileOwner($id, $currentId, 'profesional')) {?>

    <ul>

      <li><a href="#tab7">Mis publicaciones profesionales</a></li>


      <li><a href="#tab4">Notificaciones</a></li>
      <li><a href="#tab8">Pagos de membresía</a></li>

      <?php
      // <li><a href="#tab6">Configuraciones</a></li>
       ?>
    </ul>

    <?php }else{


 header('Location:'. get_home_url( ) );

    } ?>

    <div id="pagControl">



      <?php if (validateUserProfileOwner($id, $currentId, 'familia')) {?>
      <form id="formpagControl1">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="9" value="9">
          <small class="validateMessage"></small>
        </div>

      </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('myVacants')" >
          Actualizar
        </button>

      </div>
      </form>

      <form id="formpagControl2">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="5" value="5">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

      <div class="field col form-group filterBy">
        <h6 for="servicio">Filtrar por</h6>
        <select class="form-control form-control-sm" name="filterBy">
          <option value="todos">Todos</option>
          <option value="dateAsc">Fecha - ascendente</option>
          <option value="dateDesc">Fecha - descendente</option>
          <option value="withCt">Con contrato</option>
          <option value="noCt">Sin contrato</option>
          <option value="leftIntw">Por entrevistar</option>
        </select>
        <small class="validateMessage"></small>
      </div>

    </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('postInterviews')" >
          Actualizar
        </button>

      </div>
      </form>

      <form id="formpagControl3">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="5" value="5">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

      <div class="field col form-group filterBy">
        <h6 for="servicio">Filtrar por</h6>
        <select class="form-control form-control-sm" name="filterBy">
          <option value="todos">Todos</option>
          <option value="activeCt">Activo</option>
          <option value="garantCt">En Garantía</option>
          <option value="CancelCt">Cancelado</option>
        </select>
        <small class="validateMessage"></small>
      </div>

    </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('contractList')" >
          Actualizar
        </button>

      </div>
      </form>


      <form id="formpagControl5">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="5" value="5">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

      <div class="field col form-group filterBy">
        <h6 for="servicio">Filtrar por</h6>
        <select class="form-control form-control-sm" name="filterBy">
          <option value="todos">Todos</option>
          <option value="xConfirmar">Por confirmar</option>
          <option value="Pagado">Pagado</option>

        </select>
        <small class="validateMessage"></small>
      </div>

    </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('factList')" >
          Actualizar
        </button>
      </div>

      </form>
      <form id="formpagControl4">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="10" value="10">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

<div class="field col form-group filterBy">
  <h6 for="servicio">Filtrar por</h6>
  <select class="form-control form-control-sm" name="filterBy">
    <option value="todos">Todos</option>
    <option value="leidos">Leidos</option>
    <option value="noLeidos">No Leidos</option>
      </select>
  <small class="validateMessage"></small>
</div>

</div>


    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('notifList')" >
          Actualizar
        </button>
      </div>

      </form>

      <?php } ?>
      <?php if (validateUserProfileOwner($id, $currentId, 'candidata')) {?>
      <form id="formpagControl1">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="9" value="9">
          <small class="validateMessage"></small>
        </div>

      </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('myVacants')" >
          Actualizar
        </button>

      </div>
      </form>

      <form id="formpagControl2">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="5" value="5">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

      <div class="field col form-group filterBy">
        <h6 for="servicio">Filtrar por</h6>
        <select class="form-control form-control-sm" name="filterBy">
          <option value="todos">Todos</option>
          <option value="dateAsc">Fecha - ascendente</option>
          <option value="dateDesc">Fecha - descendente</option>
          <option value="withCt">Con contrato</option>
          <option value="propCt">Con propuesta de contrato</option>
          <option value="leftIntw">Por entrevistar</option>
          <option value="withIntw">Entrevistado</option>
          <option value="cancelado">Cancelado</option>
        </select>
        <small class="validateMessage"></small>
      </div>

    </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('postInterviews')" >
          Actualizar
        </button>

      </div>
      </form>

      <form id="formpagControl3">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="5" value="5">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

      <div class="field col form-group filterBy">
        <h6 for="servicio">Filtrar por</h6>
        <select class="form-control form-control-sm" name="filterBy">
          <option value="todos">Todos</option>
          <option value="activeCt">Activo</option>
          <option value="garantCt">En Garantía</option>
          <option value="CancelCt">Cancelado</option>
        </select>
        <small class="validateMessage"></small>
      </div>

    </div>

    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('contractList')" >
          Actualizar
        </button>

      </div>
      </form>

      <form id="formpagControl4">
        <h5>Control de resultados</h5>
      <div class="row">

        <div class="field col form-group porPagina">
          <h6 for="servicio">N° Resultados</h6>
          <input name="porPagina" class="form-control form-control-sm" type="number" placeholder="10" value="10">
          <small class="validateMessage"></small>
        </div>

      </div>


      <div class="row">

<div class="field col form-group filterBy">
  <h6 for="servicio">Filtrar por</h6>
  <select class="form-control form-control-sm" name="filterBy">
    <option value="todos">Todos</option>
    <option value="leidos">Leidos</option>
    <option value="noLeidos">No Leidos</option>
      </select>
  <small class="validateMessage"></small>
</div>

</div>


    <div class="buttonCustom row">

      <button type="button" class="btn btn-primary btn-sm" onclick="refreshInfo('notifList')" >
          Actualizar
        </button>
      </div>

      </form>

      <?php } ?>


    </div>

  </div>

  <div class="col-9 mainSections">



   <?php if (validateUserProfileOwner($id, $currentId, 'familia')) {?>

  <section id="content1">
      <?php misVacantes1($pagController = ''); ?>
    </section>
    <section id="content3">
      <?php misVacantes3($pagController = ''); ?>
    </section>

    <section id="content4">
      <?php misVacantes4($pagController = ''); ?>
    </section>

    <section id="content6">
      <?php misVacantes6(); ?>
    </section>
    <section id="content5">
      <?php misVacantes5($pagController = ''); ?>
    </section>

    <section id="content2">
      <?php misVacantes2($pagController = ''); ?>
    </section>

  <?php } elseif ( validateUserProfileOwner($id, $currentId, 'candidata') && !validateUserProfileOwner($id, $currentId, 'profesional') ) { ?>

     <section id="content1">
      <?php misVacantes1($pagController = ''); ?>
    </section>
    <section id="content3">
      <?php misVacantes3($pagController = ''); ?>
    </section>

    <section id="content4">
      <?php misVacantes4($pagController = ''); ?>
    </section>


    <section id="content2">
      <?php misVacantes2($pagController = ''); ?>
    </section>


    <section id="content6">
      <?php misVacantes6(); ?>
    </section>



  <?php } elseif (validateUserProfileOwner($id, $currentId, 'profesional')) {?>


   <section id="content8">
      <?php misVacantes8($pagController = ''); ?>
    </section>

  <section id="content4">
      <?php misVacantes4($pagController = ''); ?>
    </section>



    <section id="content7">
      <?php misVacantes7($pagController = ''); ?>
    </section>


  <?php } ?>

  </div>

</div>

</div>


<?php
}add_shortcode('templateMisVacantes', 'templateMisVacantes');


function misVacantes7(){

  $currentId = get_current_user_id();

  ?>

<div style="padding: 0;" class="container publicacionesProfesionales">

<h4>Mis publicaciones</h4>

<div class="buttonCustom">
  <?php
  // do_shortcode('[viewLaboralOfferButton profile_id="' . $id . '" currentUser_id="' . $currentId . '"]');

  $nOfertaUrl = esc_url(get_permalink(get_page_by_title('Nueva publicación profesional')));

  ?>
    <form action="<?php echo $nOfertaUrl ?>" method="post">
        <input type="hidden" name="pg" value="1">
       <button id="profileLaboralOfferButton" class="um-alt btn btn-primary">
       <i class="fa fa-plus" aria-hidden="true"></i> Nueva publicación profesional
      </button>
    </form>

</div>


<?php echo do_shortcode( '[getAllProfesionals type="gestion" family="'.$currentId.'"]' ); ?>


</div>



  <?php
}

// tab para configuraciones
function misVacantes6(){

  global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'ambos')) {

    $tabla = $wpdb->prefix . 'usuariofirmas';
    $infoSettings = $wpdb->get_results("SELECT * FROM $tabla where usuarioId = '$currentId'", ARRAY_A);
    $infoSettings = $infoSettings[0];

    $firma = $infoSettings['firma'];

    // print_r($infoSettings);

  ?>

  <h4>Configuraciones generales</h4>

  <div class="adminConfig">
  <form action="" method="post" class="formData">



  <div class="row">

        <div class="field form-group col usuarioFirma">
          <h6>Establece la firma que aparecerá en automatico en caso necesario sobre documentos.</h6>
          <div class="firma">

            <img src="<?Php echo $x = (isset($firma))? $firma: null ?>" alt="">
            <input value="<?Php echo $x = (isset($firma))? $firma: null ?>" type="hidden" name="usuarioFirma" id="usuarioFirma">
            <small class="validateMessage"></small>
            <button  type="button"  onclick="defineAdminFirma()" class="btn btn-primary" >
                Definir firma
              </button>
            </div>
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

// tab para las facturas
function misVacantes5($pagController = ''){

  global $wpdb;
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, 'familia')) {
    $tabla = $wpdb->prefix . 'facturacion';

    if(isset($pagController) && $pagController != ''){

      $perPage = $pagController['porPagina'];
      // $perPage = 1;
      $pageno = $pagController['pg'];
      $offset = ($pageno-1) * $perPage;
      $filtroPor = $pagController['filterBy'];


      if($filtroPor == 'todos'){

        $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId", ARRAY_A);

      $total_rows = count($infoFacts);
      $total_pages = ceil($total_rows / $perPage);

      $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId LIMIT $perPage OFFSET $offset ", ARRAY_A);

      $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
    );

    $pageData = $v;

      }
      if($filtroPor == 'xConfirmar'){

        $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId and comprobante = 1 and pagado = 0", ARRAY_A);

        $total_rows = count($infoFacts);
        $total_pages = ceil($total_rows / $perPage);

        $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId and comprobante = 1 and pagado = 0 LIMIT $perPage OFFSET $offset ", ARRAY_A);

        $v = array(
          'pageno' => $pageno,
          'total_pages' => $total_pages,
          'filterBy' => $filtroPor
      );

      $pageData = $v;

      }
      if($filtroPor == 'Pagado'){

        $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId and pagado = 1", ARRAY_A);

        $total_rows = count($infoFacts);
        $total_pages = ceil($total_rows / $perPage);

        $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId and pagado = 1 LIMIT $perPage OFFSET $offset ", ARRAY_A);

        $v = array(
          'pageno' => $pageno,
          'total_pages' => $total_pages,
          'filterBy' => $filtroPor
      );

      $pageData = $v;
      }

    }else{

      $pageno = 1;
      $perPage = 10;
      $offset = 0;

      $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId", ARRAY_A);

      $total_rows = count($infoFacts);
      $total_pages = ceil($total_rows / $perPage);

      $infoFacts = $wpdb->get_results("SELECT * FROM $tabla  where contratistaId = $currentId LIMIT $perPage OFFSET $offset ", ARRAY_A);

      $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => 'todos'
    );

    $pageData = $v;

    }


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
$pag = $pageData['pageno'];
  $total_pages = $pageData['total_pages'];
  $filterBy = $pageData['filterBy'];
  ?>
<div class="pagineishon">
  <?php echo paginate('famTab5', $pag, $total_pages  ); ?>
</div>

<?php
  }else{ ?>
    <h4>No hay resultados en este momento</h4>
 <?php }
  }
}

add_action('wp_ajax_deleteOfferLaboral', 'deleteOfferLaboral');
add_action('wp_ajax_nopriv_deleteOfferLaboral', 'deleteOfferLaboral');
function deleteOfferLaboral()
{
    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata

    if (validateUserProfileOwner($id, $currentId, "familia")) {
        if (isset($_POST['deleteLaboralOffer'])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['deleteLaboralOffer']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $serial = $data['serialOferta'];
            $tabla = $wpdb->prefix . 'ofertalaboral';
            $idOferta = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);
            // se confirma si el usuario ya existe en la lista de postulaciones
            // si no existe entonces se carga en la base
            $data['idOferta'] = $idOferta[0]['id'];
            // se envia la información tipo json para que se cargue en la base de datos



            dbDeleteOfferLaboral($data);
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

function postulateListMisVacantes($serial, $id)
{
    $data = $id;
    $list = '';
    // print_r($mensaje);
    if (count($data) > 0) {
        $opc = '';
        $list = "<div class='postList' style='width: 100%;'><ul class='uList'>";
        $i = 0;
        foreach ($data as $r) {
            $idr = $r['postulanteId'];
            $userMeta = get_user_meta($idr);
            $mensaje = $r['mensaje'];
            // print_r($userMeta);
            $name = $userMeta['first_name'][0];
            $opc = getOpcPostulants($serial, $idr);
            $url = '/perfil-de-usuario/' . $userMeta['um_user_profile_url_slug_name_dash'][0];
            $img = esc_url(get_avatar_url($idr));
            $list .= "<li class='postulante'>
                            <div class='row'>
                              <div class='col-md-12 d-flex justify-content-center'>
                                <a href='$url'>
                                  <img style='width: 100px;' src='$img'>
                                  <h5>$name</h5>
                                </a>
                              </div>
                              <div class='col-md-12'>
                              <p>Mensaje: ";

            $list .= $mensaje;

            $list .= "</p></div>
                          </div>
                            <div class='row d-flex justify-content-center'>
                              $opc
                            </div></li>";
        }
        $list .= "</ul></div>";
        return $list;
    } else {
        return 'Sin postulaciones';
    }
}

function myContractOfferList($serial, $id)
{
    $data = $id;
    $list = '';
    // print_r($mensaje);
    if (count($data) > 0) {
        $opc = '';
        $list = "<div class='postList' style='width: 100%;'><ul class='uList'>";
        $i = 0;
        foreach ($data as $r) {
            $idr = $r['postulanteId'];
            $userMeta = get_user_meta($idr);
            $mensaje = $r['mensaje'];
            // print_r($userMeta);
            $name = $userMeta['first_name'][0];
            $opc = getOpcPostulants($serial, $idr);
            $url = '/perfil-de-usuario/' . $userMeta['um_user_profile_url_slug_name_dash'][0];
            $img = esc_url(get_avatar_url($idr));
            $list .= "<li class='postulante'>
                            <div class='row'>
                              <div class='col-md-12 d-flex justify-content-center'>
                                <a href='$url'>
                                  <img style='width: 100px;' src='$img'>
                                  <h4>$name</h4>
                                </a>
                              </div>
                              <div class='col-md-12'>
                              <p>Mensaje: ";

            $list .= $mensaje;

            $list .= "</p></div>
                          </div>
                            <div class='row d-flex justify-content-center'>
                              $opc
                            </div></li>";
        }
        $list .= "</ul></div>";
        return $list;
    } else {
        return 'Sin postulaciones';
    }
}

add_action('wp_ajax_deleteOfferContract', 'deleteOfferContract');
add_action('wp_ajax_nopriv_deleteOfferContract', 'deleteOfferContract');

function deleteOfferContract()
{
    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata

    // ofertaId
    // postuladoId

    if (validateUserProfileOwner($currentId, $currentId, "candidata")) {
        if (isset($_POST['deleteOfferContract'])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['deleteOfferContract']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // se envia la información tipo json para que se cargue en la base de datos



            dbDeleteOfferContract($data);
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}


add_action('wp_ajax_sendSelectForContract', 'sendSelectForContract');
add_action('wp_ajax_nopriv_sendSelectForContract', 'sendSelectForContract');

// funcion para elegir candidato
function sendSelectForContract()
{
    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();


    if (validateUserProfileOwner($currentId, $currentId, "familia")) {
        if (isset($_POST['sendSelectForContract'])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['sendSelectForContract']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // se envia la información tipo json para que se cargue en la base de datos



            dbSendSelectForContract($data);
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_sendConfirmPetitionChange', 'sendConfirmPetitionChange');
add_action('wp_ajax_nopriv_sendConfirmPetitionChange', 'sendConfirmPetitionChange');

function sendConfirmPetitionChange()
{
    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, "familia")) {
        if (isset($_POST['sendConfirmPetitionChange'])) {

          // print_r($_POST['sendConfirmPetitionChange']);
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['sendConfirmPetitionChange']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // se envia la información tipo json para que se cargue en la base de datos

            // print_r($l);
            // print_r($data);
            dbsendConfirmPetitionChange($data);
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}


// procesar solicitud de reprogramación de entrevista
add_action('wp_ajax_SendCandSolChangeDate', 'SendCandSolChangeDate');
add_action('wp_ajax_nopriv_SendCandSolChangeDate', 'SendCandSolChangeDate');
function SendCandSolChangeDate(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
      if (isset($_POST['SendCandSolChangeDate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendCandSolChangeDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos

          // $fi = $data['info']['date'];
          // $fi = explode("/", $fi);
          // $fi = $fi[1].'/'.$fi[0].'/'.$fi[2];
          // $data['info']['date'] = $fi;


          dbSendCandSolChangeDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }

}

add_action('wp_ajax_SendCandConfirmDate', 'SendCandConfirmDate');
add_action('wp_ajax_nopriv_SendCandConfirmDate', 'SendCandConfirmDate');
function SendCandConfirmDate(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
      if (isset($_POST['SendCandConfirmDate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendCandConfirmDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos


          dbSendCandConfirmDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}


add_action('wp_ajax_SendFamSolChangeDate', 'SendFamSolChangeDate');
add_action('wp_ajax_nopriv_SendFamSolChangeDate', 'SendFamSolChangeDate');
function SendFamSolChangeDate(){
  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
      if (isset($_POST['SendFamSolChangeDate'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendFamSolChangeDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);

          dbSendFamSolChangeDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }

}


add_action('wp_ajax_SendFamConfirmDate', 'SendFamConfirmDate');
add_action('wp_ajax_nopriv_SendFamConfirmDate', 'SendFamConfirmDate');
function SendFamConfirmDate(){
  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  // $data = 'AAAA';


  if (validateUserProfileOwner($currentId, $currentId, "familia")) {
    if (isset($_POST['SendFamConfirmDate'])) {

      $data = $_POST['SendFamConfirmDate'];

          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['SendFamConfirmDate']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos



          dbSendFamConfirmDate($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }
}

add_action('wp_ajax_savedUserSettings', 'savedUserSettings');
add_action('wp_ajax_nopriv_savedUserSettings', 'savedUserSettings');
function savedUserSettings(){
  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
    if (isset($_POST['savedUserSettings'])) {

      $data = $_POST['savedUserSettings'];

          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['savedUserSettings']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos



          dbsavedUserSettings($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}



add_action('wp_ajax_InfoConfirmService', 'InfoConfirmService');
add_action('wp_ajax_nopriv_InfoConfirmService', 'InfoConfirmService');

function InfoConfirmService($data){


  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
    if (isset($_POST['InfoConfirmService'])) {

      $data = $_POST['InfoConfirmService'];



          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['InfoConfirmService']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos

      // print_r($data);


          dbInfoConfirmService($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}


add_action('wp_ajax_processacceptAditionalPurpose', 'processacceptAditionalPurpose');
add_action('wp_ajax_nopriv_processacceptAditionalPurpose', 'processacceptAditionalPurpose');

function processacceptAditionalPurpose(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "candidata")) {
    if (isset($_POST['processacceptAditionalPurpose'])) {

          // $data = stripslashes($_POST['processacceptAditionalPurpose']);

          $data = $_POST['processacceptAditionalPurpose'];

          print_r(array(
            'mensaje' => 'prueba',
            'data' => $data
          ));


          dbprocessacceptAditionalPurpose2($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }

}


add_action('wp_ajax_processsendrefuseAditionalPurpose', 'processsendrefuseAditionalPurpose');
add_action('wp_ajax_nopriv_processsendrefuseAditionalPurpose', 'processsendrefuseAditionalPurpose');

function processsendrefuseAditionalPurpose(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "candidata")) {
    if (isset($_POST['processsendrefuseAditionalPurpose'])) {

          // $data = stripslashes($_POST['processsendrefuseAditionalPurpose']);

          $data = $_POST['processsendrefuseAditionalPurpose'];


          dbprocesssendrefuseAditionalPurpose2($data);


      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }

}




add_action('wp_ajax_stepNewProfesional', 'stepNewProfesional');
add_action('wp_ajax_nopriv_stepNewProfesional', 'stepNewProfesional');

function stepNewProfesional(){

    global $wpdb;
    $currentId = get_current_user_id();

    $returnDashboard = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    if (validateUserProfileOwner($currentId, $currentId, "profesional")) {


        if( (isset($_POST['stepNewProfesional'])) || (isset($_POST['action']) && ($_POST['action'] == 'stepNewProfesional') ) ){
            // recibe json y quita los slash
            $dataReturned = preg_replace('/\\\\\"/', "\"", $_POST['stepNewProfesional']);

            $dataReturned = json_decode($dataReturned, true);
            $pg = $dataReturned['step'];

            // echo 'coño pero maldita sea pana';

            // print_r($dataReturned);


            // return;

            if(isset($_POST['action']) && $_POST['action'] == 'stepNewProfesional' && $_POST['tipo'] == 'retorno3'){

              $dataReturned = array(
                'tipo' => 'retorno3',
                'informacion' => $_POST,
                'archivos' => $_FILES
              );

              $pg = $dataReturned['informacion']['step'];

              $auxiliarProfesional = $dataReturned;

              // print_r($stepNewProfesional);
              // return;


            }

            if(isset($_POST['action']) && $_POST['action'] == 'stepNewProfesional' && $_POST['tipo'] == 'recibePago'){

              $dataReturned = array(
                'candidatoId' => $currentId,
                'tipo' => 'recibePago',
                'infoGeneral' => $_POST,
                'archivos' => $_FILES,
              );

              $pg = $dataReturned['infoGeneral']['step'];


              // print_r($pg);
              // return;

            }

            if($pg == 2){

              newProfesional($pg);
              return;

            }

            if($pg == 3){

              newProfesional($dataReturned);
              return;

            }
            if($pg == 4){
              pagoProfesional($dataReturned);
            }



        }


    }else{

        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();

    }


}


function newProfesional($retorno = ''){


  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "profesional")  ){


      $pg = ($retorno != '')? $retorno : 2;

      $pg = (isset($retorno['tipo']) && $retorno['tipo'] == 'retorno3')? $retorno['informacion']['step'] : $pg;

      $returnDashboard = esc_url(get_permalink(get_page_by_title('Mis vacantes')));




  if($pg == 1){


     ?>

  <div id="containerProcess" class="container">


  <div class="row titleSection">
              <h2><span class="resalte1">Publicación profesional</span></h2>
              <h5>Da a conocer tu trabajo en linea</h5>
          </div>
          <div class="row steps1">
              <div class="col-4">
                  <div class="stp active">
                      <div class="icono">

                          <i class="fa fa-briefcase" aria-hidden="true"></i>
                      </div>
                      <h6>Detalles del servicio</h6>
                  </div>
              </div>

              <div class="col-4">
                  <div class="stp">
                      <div class="icono">
                          <?php echo iconosPublic('trato');?>
                      </div>
                          <h6>Registro del Profesional </h6>
                  </div>
              </div>

              <div class="col-4">
                  <div class="stp">
                      <div class="icono">

                          <?php echo iconosPublic('moneyHand');?>
                      </div>
                      <h6>Facturación</h6>
                  </div>
              </div>
          </div>

          <div class="row contract">
              <div class="formSentContract" id="formSentContract">


  <div class="detallesServicio">

  <p>
      <strong>Objetivo.</strong> Atraer profesionales de trabajo a Domicilio para acercar a nuestros clientes otras opciones de servicios, para el profesional disponemos la plataforma para que forme parte de una comunidad, de a conocer sus servicios y pueda dar presupuesto, todo on line.
  </p>

  <p>
      Nuestros profesionales contaran con <strong>segmento exclusivo</strong> dentro de la pagina para colocar 10 fotos, 1 video del funcionamiento de sus servicios (1.5 minutos) y datos de contacto. Será una membresía a nuestra comunidad por un costo mensual de 150 pesos uruguayos.
  </p>

  <div class="pasos">
      <h6>
          Pasos:
      </h6>
      <ul class="listaMayor">
          <li class="itemMayor">
              1.  <strong>Registro del Profesional a Domicilio</strong>. Datos de nombre de la empresa, logo, dirección, redes sociales, teléfono, email. Departamento que atiende con sus servicios.
          </li>
          <li class="itemMayor">
              2.  Vamos a recibir profesionales en <strong>3 categorías</strong>, al momento del registro el debe escoger una de estas:
              <ul class="listaHijo">

              <?Php
              $url = '/wp-content/uploads/publicprofesional-3.jpg';
              $foto1 = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;
              $url = '/wp-content/uploads/publicprofesional-2.jpg';
              $foto2 = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;
              $url = '/wp-content/uploads/culutraedu.jpg';
              $foto3 = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;
              ?>

                  <li class="itemHijo">
                      <img src="<?php echo $foto1 ?>" alt="">
                      <small>Salud y Belleza</small>
                  </li>
                  <li class="itemHijo">
                      <img src="<?php echo $foto2 ?>" alt="">
                      <small>Alimentos y Bebidas</small>
                  </li>
                  <li class="itemHijo">
                      <img src="<?php echo $foto3 ?>" alt="">
                      <small>Educación y cultura</small>
                  </li>

              </ul>
          </li>

          <li class="itemMayor">
              3.  <strong>Botón de presupuesto</strong>, donde el cliente va a llenar un formato con el detalle del servicio que quiere recibir y sus datos de contacto, le llegara un email a el profesional con este detalle para que este evalué: capacidad de realizarlo, disponibilidad de fecha y hora, requerimientos especiales y en función a eso prepare presupuesto.
          </li>
          <li class="itemMayor">
              4.  Cuando el profesional se registra debe realizar el pago mensual o anual de la membresía. Contaremos con la opción de pago anual, pagando 10 meses por adelantado te damos el servicio por 12 meses.
          </li>
          <li class="itemMayor">
              5.  Las comunicaciones y coordinaciones entre cliente y profesional a domicilio ocurrieran entre ellos y serán fuera de la plataforma.
          </li>
      </ul>
  </div>
  </div>


              </div>
          </div>


          <div class="row botones">
              <div class="buttonCustom">

                  <?php


                  $url = '/vacantes-disponibles/mis-vacantes/#tab7';
                  $urlCompleta = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/tsolucionamos' . $url : $url;
                  ?>

                  <button class="btn btn-success" onclick="continueCreateVacant()">
                          Continuar
                  </button>

              </div>

          </div>


          </div>
  <?php } ?>

  <?php if($pg == 2){

    ?>


      <div id="containerProcess" class="container">



      <div class="row titleSection">

              <h2><span class="resalte1">Publicación profesional</span></h2>
              <h5>Da a conocer tu trabajo en linea</h5>
          </div>
          <div class="row steps1">

              <div class="col-6">
                  <div class="stp active">
                      <div class="icono">
                          <?php echo iconosPublic('trato');?>
                      </div>
                          <h6>Registro del Profesional </h6>
                  </div>
              </div>

              <div class="col-6">
                  <div class="stp">
                      <div class="icono">

                          <?php echo iconosPublic('moneyHand');?>
                      </div>
                      <h6>Facturación</h6>
                  </div>
              </div>
          </div>

  <div class="formSentProfesionalPub" id="formSentProfesionalPub">

  <form action="" method="post" class="formData">
      <div class="row">
          <div class="field col form-group titulopublicacion">
              <label for="titulopublicacion">Titulo de la publicación</label>
              <input type="text" class="form-control form-control-sm" name="titulopublicacion">
              <small class="validateMessage"></small>
          </div>
          <div class="field col form-group nombreEmpresa">
              <label for="nombreEmpresa">Nombre del titular</label>
              <input type="text" class="form-control form-control-sm" name="nombreEmpresa">
              <small class="validateMessage"></small>
          </div>

             <div class="field col form-group categoria">
              <label for="categoria">Categoría</label>
              <select class="form-control form-control-sm" name="categoria">

              <option>Salud y Belleza</option>
              <option>Alimentos y Bebidas</option>
              <option>Educación y cultura</option>
              <option>Otro</option>

              </select>
              <small class="validateMessage"></small>
          </div>

          <div class="field col form-group otroServicio ocultar">
            <label for="otroServicio">Otra categoría</label>
            <input value=" " type="text" class="form-control form-control-sm" name="otroServicio">
            <small class="validateMessage"></small>
        </div>



      </div>


      <div class="row">

      <div class="field col form-group departamento">

<label for="departamento">Departamento</label>
    <select class="form-control form-control-sm" name="departamento" id="">
        <option value="Artigas">
            Artigas
        </option>n>
        <option value="Canelones">
            Canelones
        </option>n>
        <option value="Cerro Largo">
            Cerro Largo
        </option>n>
        <option value="Colonia">
            Colonia
        </option>n>
        <option value="Durazno">
            Durazno
        </option>n>
        <option value="Flores">
            Flores
        </option>n>
        <option value="Florida">
            Florida
        </option>n>
        <option value="Lavalleja">
            Lavalleja
        </option>n>
        <option value="Maldonado">
            Maldonado
        </option>n>
        <option value="Montevideo">
            Montevideo
        </option>n>
        <option value="Paysandú">
            Paysandú
        </option>n>
        <option value="Río Negro">
            Río Negro
        </option>n>
        <option value="Rivera">
            Rivera
        </option>n>
        <option value="Rocha">
            Rocha
        </option>n>
        <option value="Salto">
            Salto
        </option>n>
        <option value="San José">
            San José
        </option>n>
        <option value="Soriano">
            Soriano
        </option>n>
        <option value="Tacuarembó">
            Tacuarembó
        </option>n>
        <option value="Treinta y Tres">
            Treinta y Tres
        </option>n>
    </select>
    <small class="validateMessage"></small>
</div>

        <div class="field col form-group ciudad">
              <label for="ciudad">Ciudad</label>
              <input type="text" class="form-control form-control-sm" name="ciudad">
              <small class="validateMessage"></small>
          </div>
          <div class="field col form-group direccion">
              <label for="direccion">Dirección</label>
              <input type="text" class="form-control form-control-sm" name="direccion">
              <small class="validateMessage"></small>
          </div>

          <div class="field col form-group telefono">
              <label for="telefono">Teléfono</label>
              <input type="text" class="form-control form-control-sm" name="telefono">
              <small class="validateMessage"></small>
          </div>

      </div>


      <div class="row">
          <div class="field col form-group descripcion">
              <label for="descripcion">Descripción general</label>
              <textarea class="form-control form-control-sm" name="descripcion" id="" cols="30" rows="2"></textarea>
              <small class="validateMessage"></small>
          </div>
      </div>

        <div class="row logodiv">
                      <div class="field col form-group logo">
                          <label for="logo">Logo personal ó de empresa</label>
                          <input type="file" class="form-control" id="logo" name="logo"  accept="image/jpeg, image/png" />

                          <small class="validateMessage"></small>
                      </div>
      </div>
      <div class="row imagenes">

        <div class="field col form-group imagenes">
          <label for="imagenes">Imagenes de tu trabajo profesional</label>
          <input type="file" class="form-control" id="imagenes" name="imagenes"  accept="image/jpeg, image/png"  multiple/>
          <!-- <input onchange="" type="file" class="form-control" id="imagenes" name="imagenes"  accept="image/jpeg, image/png"  multiple/> -->
          <small>Máximo 10 imagenes</small>
          <small class="validateMessage"></small>


<!--

          <input id="filePond" type="file" class="my-pond" name="filepond"/>
 -->
        </div>




      </div>
      <div class="row video">

        <div class="field col form-group video">
          <label for="video">Video opcional</label>
          <input type="file" class="form-control" id="video" name="video"  accept="video/*" />
          <small>No mayor a 1.5 minutos</small>
          <small class="validateMessage"></small>
        </div>

      </div>

       <h6>Redes sociales</h6>
      <div class="row">

           <div class="field col form-group instagram">
              <label for="instagram"><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</label>
              <input type="text" class="form-control form-control-sm" name="instagram" id="instagram" placeholder="Escribe la dirección URL">
              <small class="validateMessage"></small>
          </div>
          <div class="field col form-group facebook">
              <label for="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</label>
              <input type="text" class="form-control form-control-sm" name="facebook" id="facebook" placeholder="Escribe la dirección URL">
              <small class="validateMessage"></small>
          </div>
           <div class="field col form-group twitter">
              <label for="twitter"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</label>
              <input type="text" class="form-control form-control-sm" name="twitter" id="twitter" placeholder="Escribe la dirección URL">
              <small class="validateMessage"></small>
          </div>

      </div>


      <div class="row">

        <div class="field form-group col terminos">

          <input type="checkbox" class="" name="terminos" /> Estoy de acuerdo con los <a class="hiper" target="_blank" href="/terminos-y-condiciones-profesionales-independientes-pdf/">Términos y condiciones</a> y con las <a class="hiper" target="_blank" href="/politica-de-privacidad-profesionales-independientes/">Políticas de Privacidad de los Datos</a>
          <small class="validateMessage"></small>
        </div>
      </div>


      <div class="row terminos">
        <h6 style="width: 100%; text-align: center;">

        ¿Tienes dudas? Visita este <a href="/terminoscondicionesprofesional/" style="font-weight: bold;" class="resalte1"> Enlace</a>
        </h6>
      </div>

      </div>



  </form>

  <div class="row botones">
      <div class="buttonCustom">

                  <button class="btn btn-success" onclick="continueCreateVacant()">
                          Continuar
                  </button>

      </div>

      </div>








  </div>

  <?php }

  // if( $pg == 4){
  //           header('Location: '.$returnDashboard);
  //       }

      if( ($pg == 3) ){ ?>



<div id="containerProcess" class="container">

      <div class="row titleSection">
              <h2><span class="resalte1">Publicación profesional</span></h2>
              <h5>Da a conocer tu trabajo en linea</h5>
          </div>
          <div class="row steps1">

              <div class="col-6">
                  <div class="stp">
                      <div class="icono">
                          <?php echo iconosPublic('trato');?>
                      </div>
                          <h6>Registro del Profesional </h6>
                  </div>
              </div>

              <div class="col-6">
                  <div class="stp active">
                      <div class="icono">

                          <?php echo iconosPublic('moneyHand');?>
                      </div>
                      <h6>Facturación</h6>
                  </div>
              </div>
          </div>


            <div class="row info">
                <div class="col-8 publicacion">

                    <div class="info container">


                      <h5 class="subTitulo lineaAzul">
                        Información de servicios
                      </h5>
                    <div class="row">
        <div class="field col form-group titulo">
            <label for="titulo">Titulo de la publicación <br> <?php echo $retorno['informacion']['titulopublicacion'] ?> </label>

        </div>
        <div class="field col form-group servicio">
            <label for="servicio">Nombre de titular y/o empresa <br> <?php echo $retorno['informacion']['nombreEmpresa'] ?> </label>

        </div>
        <div class="field col form-group horario">
            <label for="horario">Categoría de servicios <br> <?php echo $x = ($retorno['informacion']['categoria'] == 'Otro')? $retorno['informacion']['otroServicio']: $retorno['informacion']['categoria'] ?> </label>

        </div>
    </div>
        <h5 class="subTitulo lineaAzul">
          Localidad de servicios
        </h5>
    <div class="row">

        <div class="field col form-group pais">
            <label for="pais">Departamento <br> <?php echo $retorno['informacion']['departamento'] ?> </label>

        </div>
        <div class="field col form-group departamento">
            <label for="departamento">Ciudad <br> <?php echo $retorno['informacion']['ciudad'] ?> </label>

        </div>
        <div class="field col form-group ciudad">
            <label for="ciudad">Dirección <br> <?php echo $retorno['informacion']['direccion'] ?> </label>

        </div>



    </div>

    <h5 class="subTitulo lineaAzul">
      Más detalles
      </h5>
    <div class="row">
        <div class="field form-group col-12 horario">
            <label for="descripcion">Horario <br> <p>
                <?php echo $retorno['informacion']['horario'] ?>
            </p> </label>

        </div>
        <div class="field form-group col-12 descripcion">
            <label for="descripcion">Descripción general <br> <p>
                <?php echo $retorno['informacion']['descripcion'] ?>
            </p> </label>

        </div>
    </div>


                    </div>
                </div>

                <div class="col-4 pago">

                <div class="card container facturaCuerpo">
<div class="contenido row justify-content-center">
<p class="tituloContrato"><strong>Tipo de membresía</strong></p>

</div>
<div class="detalles">
<div class="monto">
<p style="text-align: center;">Mensual:<strong> $150 </strong></p>
<p style="text-align: center;">Anual:<strong> $1.800 </strong></p>
<p style="text-align: center;">Si pagas la anualidad de contado, pagarás <strong> $1.500 </strong></p>

</div>
</div>
</div>

                    <div class="buttonCustom container">
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" onclick="payServiceProfesional()">
                                        Pagar ahora
                                </button>
                                <?php

                                ?>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row disclaimer">
                <small>
                     Tsolucionamos es una empresa de servicios que conecta personal/profesionales con las necesidades de nuestros clientes y por ende, no se hace responsable por el desempeño actual y futuro del personal seleccionado. Podremos actualizar las condiciones del servicio en el futuro. Te informaremos sobre esos cambios a través de nuestra página web y/o por correo electrónico.
                </small>
            </div>

  </div>

      <?php } ?>

<?Php }

}
