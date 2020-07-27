<?php


// shortcode para colocar boton "Ver/crear ofertas laborales "





function viewLaboralOfferButton($args = array())
{
    $defaults = array(
        'profile_id' => 0,
        'currentuser_id' => 0
    );
    $args = wp_parse_args($args, $defaults);

    if (is_user_logged_in()) {
        global $wp_roles;

        // se comprueba que el usuario que esta viendo este perfil, es el mismo usuario que esta logeado.. osea si el usuario logeado que ve el perfil es TRUE y ademas de rol familia, entonces permite mostrar el boton de crear nueva oferta laboral
        // id usuario objetivo
        $user_id1 = $args['profile_id'];
        //  id usuario logeado
        $user_id2 = $args['currentuser_id'];

        $rol1 = UM()->roles()->um_get_user_role($user_id1);
        $rol2 = UM()->roles()->um_get_user_role($user_id2);

        if( validateUserProfileOwner($user_id1, $user_id2, 'familia') || validateUserProfileOwner($user_id1, $user_id2, 'adminTsoluciono')  ){ ?>


    <div class="formSentOffer" id="formSentOffer" style="display:none;">

        <form action="" method="post" class="formData">
            <div class="row">

                <div class="field col form-group titulo">
                    <label for="titulo">Titulo de la oferta</label>
                    <input type="text" class="form-control form-control-sm" name="titulo">
                    <small class="validateMessage"></small>
                </div>
                <div class="field col form-group servicio">
                    <label for="servicio">Servicio</label>
                    <select class="form-control form-control-sm" name="servicio">
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
                    <select class="form-control form-control-sm" name="horario" id="">
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
            </div>
            <div class="row">

                <div class="field col form-group pais">
                    <label for="pais">País</label>
                    <select class="form-control form-control-sm" name="pais" id="">
                        <?php
                            $countries = getPaises();
                            foreach ($countries as $c) { ?>
                        <option value="<?php echo $c; ?>">
                            <?php echo $c; ?>
                        </option>
                        <?php } ?>

                    </select>
                    <small class="validateMessage"></small>
                </div>
                <div class="field col form-group ciudad">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control form-control-sm" name="ciudad">
                    <small class="validateMessage"></small>
                </div>
            </div>
            <div class="row">

                <div class="field col form-group direccion">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control form-control-sm" name="direccion">
                    <small class="validateMessage"></small>
                </div>
                <div class="field col form-group sueldo">
                    <label for="sueldo">Sueldo a ofrecer</label>
                    <input type="number" class="form-control form-control-sm" name="sueldo">
                    <small class="validateMessage"></small>
                </div>

            </div>
            <div class="row">

                <div class="form-group field col fechaInicio">
                  <label for="fechaInicio">Fecha de inicio</label>
                  <input class="form-control form-control-sm" name="fechaInicio" type="text" id="fechaInicio">
                  <small class="validateMessage"></small>
                </div>
                <div class="form-group field col fechaFin">
                  <label for="fechaFin">Fecha de finalización</label>
                  <input class="form-control form-control-sm" name="fechaFin" type="text" id="fechaFin">
                  <small class="validateMessage"></small>
                </div>

            </div>
                <div class="row">
                    <div class="field form-group descripcion">
                        <label for="descripcion">Descripción general</label>
                        <textarea class="form-control form-control-sm" name="descripcion" id="" cols="30" rows="5"></textarea>
                        <small class="validateMessage"></small>
                    </div>
                </div>


        <div class="field form-group col terminos">

            <input type="checkbox" class="" name="terminos" /> Estoy de acuerdo con los <a class="hiper" target="_blank" href="/terminoscondiciones/">Términos y condiciones</a> y con las <a class="hiper" target="_blank" href="/politica-de-privacidad-de-los-datos/">Políticas de Privacidad de los Datos</a>
            <small class="validateMessage"></small>
        </div>

        </form>

    </div>

    <div class="formSentOffer2" id="formSentOffer2" style="display:none;">
        <form action="" method="post" class="formData">
            <div class="field form-group firmaContratista">
                <label for="firmaContratista">Firma aqui</label>
                <div id="firmaContratista" class="{signature: {guideline: true, guidelineColor: 'black'}}"></div>
                <input type="hidden" class="form-control form-control-sm" name="jsonFirmaContratista" id="jsonFirmaContratista">
                <div class="botones">
                    <a class="botoWeb borrar">Borrar</a>
                </div>
                <small class="validateMessage"></small>
            </div>
        </form>
    </div>

    <div class="containerProfileOfferButton row" id="containerProfileOfferButton">
        <?php
        $userMeta = get_user_meta($user_id2);
        $name = $userMeta['first_name'][0];
        $url = '/mis-vacantes/';

        if(is_page('mis-vacantes')){

            ?>

            <button id="profileLaboralOfferButton" onclick="preSendClickOffer()" class="um-alt btn btn-primary"> <i class="fa fa-plus" aria-hidden="true"></i> Crear oferta laboral</button>

 <?php }
         if(is_page('perfil-de-usuario')){ ?>

                <a href="<?php echo $url; ?>" id="profileLaboralOfferButton" class="um-alt btn btn-success">
                    <?php echo iconosPublic('moneyHand');?> Ver mis ofertas laborales
                </a>

        <?php } ?>
    </div>
    <?php }
        if( validateUserProfileOwner($user_id1, $user_id2, 'candidata') ){
                $userMeta = get_user_meta($user_id2);
                $name = $userMeta['first_name'][0];
                $url = '/mis-vacantes/';
            ?>

        <div class="containerProfileOfferButton" id="containerProfileOfferButton">
              <a href="<?php echo $url; ?>" id="profileLaboralOfferButton" class="um-alt btn btn-success">
                    <?php echo iconosPublic('moneyHand');?> Ver mis ofertas laborales
                </a>
        </div>

        <?php

        }
    }
}add_shortcode('viewLaboralOfferButton', 'viewLaboralOfferButton');


add_action('wp_ajax_createOfferJob', 'createOfferJob');
add_action('wp_ajax_nopriv_createOfferJob', 'createOfferJob');

function createOfferJob()
{
    $id = um_user( 'ID' );
    $currentId = get_current_user_id();
    if( validateUserProfileOwner( $id, $currentId, "familia") ){
        if (isset($_POST[ 'dataOffer' ])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'dataOffer' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $data['contratistaId'] = $id;
            $data['id'] = uniqid().uniqid();
            $data['fechaCreacion'] = date('d/m/Y');
            $u = get_user_meta($data['contratistaId']);
            $nombre = $u['first_name'][0];
            $data['nombreFamilia'] = $nombre;

            // convertir fecha al formato correcto
            // $fi = $data['fechaInicio'];
            // $ff = $data['fechaFin'];

            // $fi = explode("/", $fi);
            // $ff = explode("/", $ff);

            // $fi = $fi[1].'/'.$fi[0].'/'.$fi[2];
            // $ff = $ff[1].'/'.$ff[0].'/'.$ff[2];

            // $data['fechaInicio'] = $fi;
            // $data['fechaFin'] = $ff;


            // return;
            createOfferLaboral($data);

            die();
        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}



add_action('wp_ajax_stepNewVacant', 'stepNewVacant');
add_action('wp_ajax_nopriv_stepNewVacant', 'stepNewVacant');
function stepNewVacant(){

    global $wpdb;
    $currentId = get_current_user_id();

    $returnDashboard = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    if (validateUserProfileOwner($currentId, $currentId, "familia") || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        // con proceso
        $pg = 1;
        if(isset($_POST['stepNewVacant'])){
            // recibe json y quita los slash
            $dataReturned = preg_replace('/\\\\\"/', "\"", $_POST['stepNewVacant']);

            $dataReturned = json_decode($dataReturned, true);
            $pg = $dataReturned['step'];
            ?>
        <?php

            if($pg == 2){

                $c = $dataReturned['contratoServicio'];
                $c = get_field('contratoServicios', $c);
                $contratoServicioBlank = $c;
                // $data['datos'] == $dataReturned['datos'];
            }
            if($pg == 3){


                $c = $dataReturned['contratoServicio'];
                $c = get_field('formatoFactura', $c);
                $formatoFacturaBlank = $c;


            }
        } ?>

         <div class="container" id="containerProcess">

            <?php if (validateUserProfileOwner($currentId, $currentId, "familia")){ ?>

            <div class="iconoHelp">
                <span>¿Necesitas ayuda? <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                <div class="msj">
                    <h4>
                        ¡Nosotros te ayudamos a crear tu oferta laboral!
                    </h4>
                    <a href="<?php echo $urle = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.'/contacto' : '/contacto'; ?>">
                        Contactanos
                         <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <?php } ?>

        <?php

    if($pg == 1){


        $currentId = get_current_user_id();

        if( validateUserProfileOwner($currentId, $currentId, 'familia') || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") ){
         ?>

                <?php
                if( validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") ) { ?>

                    <?php if(!isset($_POST['type']) && $_POST['type'] != 'anuncioPromocional'){ ?>

                    <div class="row titleSection">
            <h2><span class="resalte1">Asistencia</span> de oferta laboral</h2>
            <h5>Completa la información según las necesidades del cliente</h5>
            </div>

                <?php }else{ ?>
                     <div class="row titleSection">
            <h2><span class="resalte1">Publicación</span> de anuncio promocional</h2>
            <h5>Completa la información según el tipo de candidato que se busca atraer</h5>
            </div>
                <?php } ?>

                <?php }else{ ?>


        <div class="row titleSection">
            <h2><span class="resalte1">Crea</span> tu oferta laboral</h2>
            <h5>Completa la información según tus necesidades</h5>
        </div>
               <?php  } ?>
        <?php if( !isset($_POST['type']) && $_POST['type'] != 'anuncioPromocional'){ ?>
        <div class="row steps1">
            <div class="col-6">
                <div class="stp active">
                    <div class="icono">

                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                    </div>
                    <h6>Oferta laboral</h6>
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

        <?php } ?>

        <div class="formSentOffer" id="formSentOffer">



<?php if( isset($_POST['type']) && $_POST['type'] == 'anuncioPromocional' && validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")){ ?>

<form action="" method="post" class="formData">


    <div class="row">
        <div class="field col form-group titulo">
            <label for="titulo">Titulo del anuncio</label>
            <input type="text" class="form-control form-control-sm" name="titulo">
            <small class="validateMessage"></small>
        </div>
        <div class="field col form-group servicio">
            <label for="servicio">Foco de servicio</label>
            <select class="form-control form-control-sm" name="servicio">
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
<option>
    Otro
</option>
            </select>
            <small class="validateMessage"></small>
        </div>

        <div class="field col form-group otroServicio ocultar">
            <label for="otroServicio">Tipo de servicio</label>
            <input value=" " type="text" class="form-control form-control-sm" name="otroServicio">
            <small class="validateMessage"></small>
        </div>

        <div class="field col form-group pais">
            <label for="pais">País</label>
            <select class="form-control form-control-sm" name="pais" id="">
                <?php
                    $countries = getPaises();
                    foreach ($countries as $c) { ?>
                <option value="<?php echo $c; ?>">
                    <?php echo $c; ?>
                </option>
                <?php } ?>

            </select>
            <small class="validateMessage"></small>
        </div>

    </div>

    <div class="row">



         <div class="form-group field col fechaInicio">
          <label for="fechaInicio">Fecha de inicio</label>
          <input class="form-control form-control-sm" name="fechaInicio" type="text" id="fechaInicio">
          <small class="validateMessage"></small>
        </div>

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


<?php } ?>
<?php if( !isset($_POST['type']) && $_POST['type'] != 'anuncioPromocional' ){ ?>

<form action="" method="post" class="formData">
    <div class="row">

               <?php if( validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono') ){

                    $famlas = getAllUsersByRole();
                    $famlas = $famlas['Familias'];

                 ?>
                    <div class="field col form-group asignadoFamilia">
                    <label for="asignadoFamilia">Asignado a la familia</label>
                    <select class="form-control form-control-sm" name="asignadoFamilia">

                        <?php foreach ($famlas as $key => $value) {
                            $infoUsuario = getInfoNameEmailUsers($value);
                            ?>
                            <option value="<?php echo $value ?>">
                                <?php echo $infoUsuario['nombre']; ?>
                            </option>

                        <?php } ?>

                    </select>
                    </div>

                <?php } ?>

        <div class="field col form-group titulo">
            <label for="titulo">Titulo de la oferta</label>
            <input type="text" class="form-control form-control-sm" name="titulo">
            <small class="validateMessage"></small>
        </div>
        <div class="field col form-group servicio">
            <label for="servicio">Servicio</label>
            <select class="form-control form-control-sm" name="servicio">
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
            <select class="form-control form-control-sm" name="horario" id="">
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
    </div>
    <div class="row">

        <div class="field col form-group pais">
            <label for="pais">País</label>
            <select class="form-control form-control-sm" name="pais" id="">
                <?php
                    $countries = getPaises();
                    foreach ($countries as $c) { ?>
                <option value="<?php echo $c; ?>">
                    <?php echo $c; ?>
                </option>
                <?php } ?>

            </select>
            <small class="validateMessage"></small>
        </div>
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

    </div>
    <div class="row">

  <div class="field col form-group direccion">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control form-control-sm" name="direccion">
            <small class="validateMessage"></small>
        </div>


        <div class="field col form-group sueldo">
            <label for="sueldo">Sueldo a ofrecer</label>
            <input type="number" class="form-control form-control-sm" id="sueldo" name="sueldo">
            <small class="validateMessage"></small>
        </div>

    </div>
    <div class="row">

        <div class="form-group field col fechaInicio">
          <label for="fechaInicio">Fecha de inicio</label>
          <input class="form-control form-control-sm" name="fechaInicio" type="text" id="fechaInicio">
          <small class="validateMessage"></small>
        </div>
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






        <div class="field form-group col terminos">

            <input type="checkbox" class="" name="terminos" /> Estoy de acuerdo con los <a class="hiper" target="_blank" href="/terminoscondiciones/">Términos y condiciones</a> y con las <a class="hiper" target="_blank" href="/politica-de-privacidad-de-los-datos/">Políticas de Privacidad de los Datos</a>
            <small class="validateMessage"></small>
        </div>
    </div>
</form>

<?php } ?>

<div class="row botones">
    <div class="buttonCustom">




                <?php if(!isset($_POST['type']) && $_POST['type'] != 'anuncioPromocional' ){?>

                <button class="btn btn-success" onclick="continueCreateVacant()">
                        Continuar
                </button>

                <?php }else{ ?>
                <button class="btn btn-success" onclick="createPromoAnounce()">
                        Aceptar
                </button>

                <?php } ?>

    </div>

</div>

</div>

        <?php }
    }
    // PAUSADO
    // parte del contrato de servcisios
    if( ($pg == 2) ){


    $datosUsuarios = array(
        'familia' => datosUsuarios('familia', $currentId)
    );
        $i = 'familia';
        $d = date('d/m/Y');
        $d = str_replace('/', '', $d);
        $c = uniqid('ol-',true).$d;

        $fx = getSignUser($currentId);

        $existeFirma = ( (isset($fx['firma'])) && ($fx['firma'] != null) && ($fx['firma'] != '') )? $fx['firma'] : null;

        // obtener firma de directiva
        $tabla = $wpdb->prefix . 'configuracionesadmin';
        $adminSettings = $wpdb->get_results("SELECT * FROM $tabla", ARRAY_A);
        $adminSettings = $adminSettings[0];
        if( isset($adminSettings['teamDatos']) ){
          $teamDatos = $adminSettings['teamDatos'];
          $teamDatos = json_decode($teamDatos, true);
        }

        $directivaNombre = $teamDatos['Directiva']['Nombre'];
        $firmaDirectiva = $teamDatos['Directiva']['firma'];

        $codFirma = $firmaDirectiva;
        $firma = '<div id="firmaDirectivaContrato"><img src="' . $codFirma . '"></div>';
        $contratoServicioBlank = str_replace("{{firmaDirector}}", $firma, $contratoServicioBlank);
        $firma = '<div id="firmaFamiliaContrato"><img src="'.$existeFirma.'"></div>';
        $contratoServicioBlank = str_replace("{{firmaFamilia}}", $firma, $contratoServicioBlank);
        // -----------------------------
        $contratoServicioBlank = str_replace("-Nombre de presidente de Tsoluciono-", $directivaNombre, $contratoServicioBlank);
        $contratoServicioBlank = str_replace("{{nombreFam}}", $datosUsuarios[$i]['nombreFam'], $contratoServicioBlank);
        $contratoServicioBlank = str_replace("{{familia}}", $datosUsuarios[$i]['nombreFam'], $contratoServicioBlank);
        $contratoServicioBlank = str_replace("{{rolFam}}", '<a href="' . $datosUsuarios[$i]['urlFam'] . '">' . $datosUsuarios[$i]['rolFam'] . '</a>', $contratoServicioBlank);
        $contratoServicioBlank = str_replace("{{serialContratoServicios}}", $c, $contratoServicioBlank);
        $contratoServicioBlank = str_replace("logo", '', $contratoServicioBlank);

        ?>

        <div class="row titleSection">
            <h2><span class="resalte1">Contrato</span> de acuerdos y condiciones</h2>
            <h5>Lee con atención nuestros términos</h5>
        </div>
        <div class="row steps1">
            <div class="col-6">
                <div class="stp">
                    <div class="icono">

                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                    </div>
                    <h6>Oferta laboral</h6>
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

        <div class="row contract">
            <div class="formSentContract" id="formSentContract">
                <?php echo $contratoServicioBlank; ?>
            </div>
        </div>

        <div class="row botones">
            <div class="buttonCustom">

                <?php if(isset($fx['firma']) && ($fx['firma'] != '')){ ?>
                    <button class="btn btn-success" onclick="continueUseSign()">
                        Firmar y continuar
                    </button>
               <?php }else{ ?>
                    <button class="btn btn-success" onclick="continueCreateVacant()">
                            Firmar y continuar
                    </button>
               <?php } ?>
            </div>
        </div>

    <?php }

        if( $pg == 4){
            header('Location: '.$returnDashboard);
        }

      if( ($pg == 3) ){

          ?>


     <div class="row titleSection">
            <h2><span class="resalte1">Resumen</span> del proceso</h2>
            <h5>Emplea el método de pago más cómodo para ti</h5>
        </div>
        <div class="row steps1">
            <div class="col-6">
                <div class="stp">
                    <div class="icono">

                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                    </div>
                    <h6>Oferta laboral</h6>
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

                <h6>Tu publicación</h6>

                    <div class="info container">

                    <div class="row">

                    <?php
                        if(validateUserProfileOwner( get_current_user_id(),  get_current_user_id(), 'adminTsoluciono')  ){
                            $infoUsuario = getInfoNameEmailUsers($dataReturned['dataService']['asignadoFamilia']);
                            ?>

                        <div class="field col form-group asignadoFamilia">
                        <label for="asignadoFamilia">Asignado a la familia: <br> <?php echo $infoUsuario['nombre']; ?> </label>

                    </div>

                 <?php } ?>

                    <div class="field col form-group titulo">
                        <label for="titulo">Titulo de la oferta <br> <?php echo $dataReturned['dataService']['titulo']; ?> </label>

                    </div>
        <div class="field col form-group servicio">
            <label for="servicio">Servicio <br> <?php echo $dataReturned['dataService']['servicio']; ?> </label>


            <small class="validateMessage"></small>
        </div>
        <div class="field col form-group horario">
            <label for="horario">Horario <br> <?php echo $dataReturned['dataService']['horario']; ?> </label>

        </div>
    </div>
    <div class="row">


        <div class="field col form-group pais">
            <label for="pais">País <br> <?php echo $dataReturned['dataService']['pais']; ?> </label>

        </div>
        <div class="field col form-group departamento">
            <label for="departamento">Departamento <br> <?php echo $dataReturned['dataService']['departamento']; ?> </label>

        </div>
        <div class="field col form-group ciudad">
            <label for="ciudad">Ciudad <br> <?php echo $dataReturned['dataService']['ciudad']; ?> </label>

        </div>



    </div>
    <div class="row">

    <div class="field col form-group direccion">
            <label for="direccion">Dirección <br> <?php echo $dataReturned['dataService']['direccion']; ?> </label>

        </div>



        <div class="field col form-group sueldo">
            <label for="sueldo">Sueldo a ofrecer <br> <?php echo '$'.$dataReturned['dataService']['sueldo']; ?> </label>

        </div>

    </div>
    <div class="row">

        <div class="form-group field col fechaInicio">
          <label for="fechaInicio">Fecha de inicio <br> <?php echo $dataReturned['dataService']['fechaInicio']; ?> </label>

        </div>
        <div class="form-group field col fechaFin">
          <label for="fechaFin">Fecha de finalización <br> <?php echo $dataReturned['dataService']['fechaFin']; ?> </label>

        </div>

    </div>
    <div class="row">
        <div class="field form-group col descripcion">
            <label for="descripcion">Descripción general <br> <p>
                <?php echo $dataReturned['dataService']['descripcion']; ?>
            </p> </label>

        </div>
    </div>


                    </div>
                </div>

                <div class="col-4 pago">

                    <?php echo $formatoFacturaBlank; ?>

                    <div class="buttonCustom container">
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" onclick="payService()">
                                        Pagar ahora
                                </button>
                                <?php
                                // // <button class="btn btn-warning" onclick="payLater()">
                                        // Pagar después
                                // </button>
                                ?>

                            </div>
                        </div>
                    </div>


                </div>

            </div>

            <div class="row disclaimer">
                <small>
                    Tsolucionamos, es una empresa de colocación de personal y no se hace responsable por el desempeño actual y/o futuro del candidato seleccionado. Nuestros procesos inician en la selección del personal requerido y finalizan cuando el interesado contrata al seleccionado.
                </small>
            </div>

            </div>

      <?php }
    ?>
</div>
<?php }
}

function getContractProcess($id){

    // $id = get_the_ID();

    $d = array(
        'contrato' => get_field('contratoServicios', $id)
    );

    return $d;
}


// putas
add_action('wp_ajax_payLater', 'payLater');
add_action('wp_ajax_nopriv_payLater', 'payLater');

function payLater(){

    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, "familia")) {
        if (isset($_POST['action']) && ($_POST['action'] == 'payLater')) {

            $files = imagesToArray($_FILES);

            $datos = stripslashes($_POST['datos']);
            $datos = json_decode($datos, true);

            // $fi = $datos['dataService']['fechaInicio'];
            // $ff = $datos['dataService']['fechaFin'];
            // $fi = explode("/", $fi);
            // $ff = explode("/", $ff);
            // $fi = $fi[1].'/'.$fi[0].'/'.$fi[2];
            // $ff = $ff[1].'/'.$ff[0].'/'.$ff[2];
            // $datos['dataService']['fechaInicio'] = $fi;
            // $datos['dataService']['fechaFin'] = $ff;

            // se envia la información tipo json para que se cargue en la base de datos
            if(isset($_POST['terminosCompleto'])){
                $terminosCompleto = stripslashes( $_POST[ "terminosCompleto" ] );
            }

            $d = array(
                'contratistaId' => $currentId,
                'imagenes' => $files,
                'datosOferta' => $datos,
                'terminosCompleto' => $terminosCompleto
            );

            //   // convertir fecha al formato correcto
            dbpayLater($d);
        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }

}


add_action('wp_ajax_processpayService', 'processpayService');
add_action('wp_ajax_nopriv_processpayService', 'processpayService');
function processpayService(){

    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, "familia") || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['action']) && ($_POST['action'] == 'processpayService')  ) {

            print_r($_POST);
            // datos de la factura
            $archivos = $_FILES;
            $f = $_POST['datosFactura'];
            $f = stripslashes($f);
            $f = json_decode($f, true);
            $fac = array(
                'tipo' => $f['tipo'],
                'referencia' => $f['referencia'],
                'mensajeOpcional' => $f['mensajeOpcional'],
                'comprobante' => $archivos['comprobante']
            );
            unset($archivos['comprobante']);

            $firmaFamilia = $_POST['firmaFamilia'];
            $firmaFamilia = stripslashes($firmaFamilia);
            $guardarFirma = $_POST['guardarFirma'];
            $guardarFirma = stripslashes($guardarFirma);

            $asignadoFamilia = (isset($_POST['asignadoFamilia']) && $_POST['asignadoFamilia'] != '')? $_POST['asignadoFamilia']: null;

            // datos del proceso oferta
            $datos = array(
                'asignadoA' => $asignadoFamilia,
                'titulo' => $_POST['titulo'],
                'servicio' => $_POST['servicio'],
                'horario' => $_POST['horario'],
                'cargo' => $_POST['cargo'],
                'pais' => $_POST['pais'],
                'departamento' => $_POST['departamento'],
                'ciudad' => $_POST['ciudad'],
                'direccion' => $_POST['direccion'],
                'sueldo' => $_POST['sueldo'],
                'fechaInicio' => $_POST['fechaInicio'],
                'fechaFin' => $_POST['fechaFin'],
                'descripcion' => $_POST['descripcion'],
                'contratistaId' => $currentId,
                'fotos' => $archivos,
                'firmaFamilia' => $firmaFamilia,
                'guardarFirma' => $guardarFirma
            );
            // convertir fechaas
             // convertir fecha al formato correcto
            //  $fi = $datos['fechaInicio'];
            //  $ff = $datos['fechaFin'];

            //  $fi = explode("/", $fi);
            //  $ff = explode("/", $ff);

            //  $fi = $fi[1].'/'.$fi[0].'/'.$fi[2];
            //  $ff = $ff[1].'/'.$ff[0].'/'.$ff[2];

            //  $datos['fechaInicio'] = $fi;
            //  $datos['fechaFin'] = $ff;

            // contrato de terminos
            $terminos = $_POST['terminosCompleto'];
            $terminos = stripslashes($terminos);



            $x = array(
                'datosFactura' => $fac,
                'datos' => $datos,
                'contratoTerminos' => $terminos
            );
            // return;
            // print_r($x);
            dbprocesspayService($x);

        }
    } else {
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}



add_action('wp_ajax_afterPayBill', 'afterPayBill');
add_action('wp_ajax_nopriv_afterPayBill', 'afterPayBill');
function afterPayBill(){

    global $wpdb;
    $id = um_user('ID');
    $currentId = get_current_user_id();

    if (validateUserProfileOwner($currentId, $currentId, "familia") || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono")) {
        if (isset($_POST['action']) && ($_POST['action'] == 'afterPayBill')  ) {

            $fac = array(
                'tipo' => $_POST['tipo'],
                'referencia' => $_POST['referencia'],
                'mensajeOpcional' => $_POST['mensajeOpcional']
            );
            //imagen
            $archivos = $_FILES;

            $x = array(
                'archivo' => $archivos,
                'datosFactura' => $fac,
                'serial' => $_POST['serial']
            );

            // print_r($archivos);
            // return;

            dbafterPayBill($x);


        }

    }

}

 ?>
