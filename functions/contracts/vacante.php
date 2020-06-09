<?php

function formEditVacante($args = array()){

    $defaults = array(
        "serial" => ''
    );
    $args = wp_parse_args($args, $defaults);
    // $serial = $args['serial'];
    // $data = dbGetAllOfferInfo($serial);
    // print_r($data);
    ?>

     <div class="formSentOffer" id="formSentOffer" style="display:none;">

        <form action="" method="post" class="formData">
            <div class="field titulo">
                <label for="titulo">Titulo de la oferta</label>
                <input type="text" name="titulo">
                <small class="validateMessage"></small>
            </div>
            <div class="field servicio">
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
            <div class="field horario">
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

            <div class="field pais">
                <label for="pais">País</label>
                <select name="pais" id="">
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
            <div class="field ciudad">
                <label for="ciudad">Ciudad</label>
                <input type="text" name="ciudad">
                <small class="validateMessage"></small>
            </div>
            <div class="field direccion">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion">
                <small class="validateMessage"></small>
            </div>
            <div class="field sueldo">
                <label for="sueldo">Sueldo a ofrecer</label>
                <input type="number" name="sueldo">
                <small class="validateMessage"></small>
            </div>
            <div class="field descripcion">
                <label for="descripcion">Descripción general</label>
                <textarea name="descripcion" id="" cols="30" rows="10"></textarea>
                <small class="validateMessage"></small>
            </div>


        <div class="field form-group col terminos">

            <input type="checkbox" class="" name="terminos" /> Estoy de acuerdo con los <a class="hiper" target="_blank" href="/terminoscondiciones/">Términos y condiciones</a> y con las <a class="hiper" target="_blank" href="/politica-de-privacidad-de-los-datos/">Políticas de Privacidad de los Datos</a>
            <small class="validateMessage"></small>
        </div>
        </form>

    </div>
<?php

}add_shortcode('formEditVacante', 'formEditVacante');



// Se crea el formulario que se va a mostrar en el postulate sweetalert
function formPostulate($args = array()){

    $defaults = array(
        'nombre' => '',
        'rol' => ''
    );
    $args = wp_parse_args($args, $defaults);
    $name = $args['nombre'];
    $rol = $args['rol'];
    ?>
    <div class="formPostulate" id="formPostulate" style="display:none;">

        <form action="" method="post" class="formData">

                  <div class="field col form-group mensaje">
                <label for="titulo">Deja un mensaje al dueño de la oferta</label>
                <textarea class="form-control" name="mensaje" id="" cols="10" rows="2"></textarea>
                <div class="validateMessage">Este mensaje es opcional, puedes omitirlo</div>
            </div>

            <div class="field col form-group enterado">
                <label for="enterado">¿Cómo te has enterado de esta vacante?</label>
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


<?php }add_shortcode('formPostulate', 'formPostulate');


// funcion para cargar o procesar la petición de postulación ademas con una ultima confirmación de usuario CORRECTO
add_action('wp_ajax_createPostulation', 'createPostulation');
add_action('wp_ajax_nopriv_createPostulation', 'createPostulation');

function createPostulation()
{
    $id = um_user( 'ID' );
    $currentId = get_current_user_id();
    global $wpdb;
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata
    if( validateUserProfileOwner( $id, $currentId, "candidata") ){
        if (isset($_POST[ 'dataPostulation' ])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'dataPostulation' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $serial = $data['serialOferta'];
            $tabla =$wpdb->prefix . 'ofertalaboral';
            $idOferta = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);
            // se confirma si el usuario ya existe en la lista de postulaciones
            // si no existe entonces se carga en la base

                $data['idOferta'] = $idOferta[0]['id'];
                // se envia la información tipo json para que se cargue en la base de datos
                dbCreatePostulation($data);


        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_deletePostulation', 'deletePostulation');
add_action('wp_ajax_nopriv_deletePostulation', 'deletePostulation');

function deletePostulation()
{
    $id = um_user( 'ID' );
    $currentId = get_current_user_id();
    global $wpdb;
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata
    if( validateUserProfileOwner( $id, $currentId, "candidata") ){
        if (isset($_POST[ 'deletePostulation' ])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'deletePostulation' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $serial = $data['serialOferta'];
            $tabla =$wpdb->prefix . 'ofertalaboral';
            $idOferta = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);
            // se confirma si el usuario ya existe en la lista de postulaciones
            // si no existe entonces se carga en la base
            $data['idOferta'] = $idOferta[0]['id'];
            // se envia la información tipo json para que se cargue en la base de datos
            dbDeletePostulation($data);

        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

// funcion para retornar la lista de postulantes que hay por oferta.
function postulateList(){

    global $wpdb;

    $currentId = get_current_user_id();
    $serial = $_GET['serial'];

    if(($serial == '') || ($serial == null)){
        if(isset($_POST['getSerial'])){
            $serial = $_POST['getSerial'];
        }
    }

    $tabla = $wpdb->prefix . 'ofertapostulantes';
    $tabla2 = $wpdb->prefix . 'ofertalaboral';
    $entrevistasTabla = $wpdb->prefix . 'proceso_contrato';
    $data = $wpdb->get_results("SELECT id from $tabla2 WHERE serialOferta = '$serial'", ARRAY_A);
    $ofertaId = $data[0]['id'];
    $data = $wpdb->get_results("SELECT postulanteId, mensaje from $tabla WHERE ofertaId = '$ofertaId'", ARRAY_A);
    $list = '';
//     $data = $wpdb->get_results("SELECT postulanteId, mensaje from $tabla as ofertapostulantes INNER JOIN $entrevistasTabla as proceso ON ofertapostulantes.postulanteId != proceso.candidataId  WHERE ofertaId = '$ofertaId'", ARRAY_A);


    ?>

    <?php
    if( (count($data) > 0) && ( validateUserProfileOwner( $currentId, $currentId, "adminTsoluciono" )) ){
        $opc = '';  ?>
        <h5 class="resalte1">Información de postulantes</h5>
        <div id='postuList'>
            <ul class='uList'>
        <?php foreach ($data as $r) {
            $pid = $r['postulanteId'];
            $mensaje = $r['mensaje'];
            $userMeta = get_user_meta($r['postulanteId']);
            // print_r($userMeta);
            $name = $userMeta['first_name'][0];
            $opc = getOpcPostulants($serial, $r['postulanteId']);
            $url = '/perfil-de-usuario/'.$userMeta['um_user_profile_url_slug_name_dash'][0];
            $img = esc_url( get_avatar_url( $r['postulanteId'] ) );

            // confirmar si fue tomado

        $tomado = $wpdb->get_results("SELECT * from $entrevistasTabla WHERE ofertaId = '$ofertaId' and candidataId = $pid", ARRAY_A);

            if(count($tomado) > 0){ ?>
            <li class='postulante selected'>
                <div class='pst'>
                  <div class='perfil '>
                    <a href='<?php echo $url ?>'>
                      <img style='width: 100px;' src='<?php echo $img ?>'>
                      <h4><?php echo $name ?></h4>
                    </a>
                  </div>
                    <div class='msj'>
                    <?php if($mensaje != ''){ ?>
                        <p><strong>Mensaje</strong><br>
                        <?php echo $mensaje ?>
                        </p>
                    <?php } ?>
                    <div class="opc"><div class="buttonCustom">
                    <button onclick="sendAdminDeleteSelectPostulant(<?php echo $pid ?>, '<?php echo $serial ?>')" class='delete btn btn-danger'><i class="fa fa-times" aria-hidden="true"></i> Eliminar selección</button>
                    </div></div>
                    </div>
                </div>
            </li>

            <?php } else {  ?>

                <li class='postulante '>
                    <div class='pst'>
                      <div class='perfil '>
                        <a href='<?php echo $url ?>'>
                          <img style='width: 100px;' src='<?php echo $img ?>'>
                          <h4><?php echo $name ?></h4>
                        </a>
                      </div>
                        <div class='msj'>
                        <?php if($mensaje != ''){ ?>
                            <p><strong>Mensaje</strong><br>
                            <?php echo $mensaje ?>
                            </p>
                        <?php } ?>
                        <div class="opc"><div class="buttonCustom"><button class="add btn btn-primary" onclick="sendAdminSelectPostulant(<?php echo $pid ?>, '<?php echo $serial ?>')"><i class="fa fa-file-text-o" aria-hidden="true"></i>Seleccionar candidato</button></div></div>
                        </div>
                    </div>
                </li>

           <?php }

         } ?>
         </ul>
        </div>


    <?php }elseif ( (count($data) > 0) && ( !validateUserProfileOwner( $currentId, $currentId, "adminTsoluciono" )) ){
        return '<h6 >'.count($data) .' <span class="resalte1" >Postulante(s)</span> en esta oferta laboral</h6>';
    }else{

        return '<h6>Sin <span class="resalte1">postulaciones</span></h6>';
    }
}



// esto es para retornar data de la candidata comportandose como un api llamado desde el front con shorrtcodes
function getDataVacante($parametro){
    global $wpdb;

     $serial = $_GET['serial'];
     $tabla = $wpdb->prefix . 'ofertalaboral';

        $p = array("contratistaId", "estado", "fechaCreacion", "gestion", "fechaInicio", "fechaFin", "nombreTrabajo", "cargo", "nombreFamilia", "direccion", "pais", "ciudad", "sueldo", "tipoServicio", "descripcionExtra", "firmaCandidata", "serialOferta");
        $request = '';
        $data = $wpdb->get_results("SELECT * FROM $tabla WHERE serialOferta = '$serial' and publico=1", ARRAY_A);
        switch ($parametro) {
            case 'estado':
                $request = $p[1];
                break;
            case 'creado':
                $request = $p[2];
                break;
            case 'gestion':
                $request = $p[3];
                break;
            case 'inicio':
                $request = $p[4];
                break;
            case 'fin':
                $request = $p[5];
                break;
            case 'titulo':
                $request = $p[6];
                break;
            case 'cargo':
                $request = $p[7];
                break;
            case 'contratista':
                $request = $p[8];
                break;
            case 'direccion':
                $request = $p[9];
                break;
            case 'pais':
                $request = $p[10];
                break;
            case 'ciudad':
                $request = $p[11];
                break;
            case 'sueldo':
                $request = $p[12];
                break;
            case 'tipoServicio':
                $request = $p[13];
                break;
            case 'descripcionExtra':
                $request = $p[14];
                break;
            case 'botonPostularse':

                $id = um_user( 'ID' );
                $currentId = get_current_user_id();
                if( validateUserProfileOwner( $id, $currentId, "candidata" ) && !validateUserProfileOwner($id, $currentId, 'profesional')){
                    $tabla = $wpdb->prefix . 'ofertapostulantes';
                    $tabla2 = $wpdb->prefix . 'ofertalaboral';
                    $data = $wpdb->get_results("SELECT postulanteId FROM $tabla WHERE postulanteId=$currentId AND ofertaId = (select id from $tabla2 where serialOferta ='$serial')", ARRAY_A);
                    if( count($data) == 0 ){
                        $b = '<button onclick="sendPostulacion(\''.$serial.'\')" class="btn btn-success btn-block">¡Quiero postularme!</button>';
                        return $b;
                    }else{
                        return '<button class="btn btn-danger btn-block" onclick="sendDeletePostulation(\''.$serial.'\')"><i class="fa fa-trash" aria-hidden="true"></i>Elimina tu postulación</button>';
                    }
                }elseif( validateUserProfileOwner( $id, $currentId, "familia" ) ){
                    $b = dbGetValidateOwnerOffer($serial);
                    if( $b[0]['count(*)'] == 1 ){
                        $b = do_shortcode("[formEditVacante serial='$serial']").'<div class="buttonCustom"><button class="btn btn-primary" onclick="sendEditVacant(\''.$serial.'\')"><i class="fa fa-edit" aria-hidden="true"></i>Editar vacante</button></div>';
                        $b = '';
                        return $b;
                    }else{
                        return $b = '';
                    }

                }elseif(validateUserProfileOwner( $id, $currentId, "adminTsoluciono" )) {

                    return null;
                }
                elseif (validateUserProfileOwner($id, $currentId, 'profesional')) {
                    return null;
                }
                else{
                  $ure = $_SERVER["REQUEST_URI"];
                    $regst = "/registro-de-candidata?drec=$ure";
                    // $regst = "/prueba-register?drec=$ure";
                    $logsi = "/iniciar-sesion?drec=$ure";

                    $urlr = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$regst : $regst;
                    $urll = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$logsi : $logsi;

                    return '<h6>¡<a class="hiper" href="'.$urlr.'" >Registrate</a> ó <a class="hiper" href="'.$urll.'">Inicia sesión</a> para postularte!</h6>';
                }
                break;
        case 'botonPostularseAnuncio':

                $id = um_user( 'ID' );
                $currentId = get_current_user_id();
                if( validateUserProfileOwner( $id, $currentId, "candidata" ) && !validateUserProfileOwner( $id, $currentId, "profesional" ) ){
                    $tabla = $wpdb->prefix . 'ofertapostulantes';
                    $tabla2 = $wpdb->prefix . 'ofertalaboral';
                    $data = $wpdb->get_results("SELECT postulanteId FROM $tabla WHERE postulanteId=$currentId AND ofertaId = (select id from $tabla2 where serialOferta ='$serial')", ARRAY_A);
                    if( count($data) == 0 ){
                       $b = '<button onclick="sendPostulacion(\''.$serial.'\')" class="btn btn-success btn-block">¡Quiero postularme!</button>';
                        return $b;
                    }else{
                        return '<button class="btn btn-danger btn-block" onclick="sendDeletePostulation(\''.$serial.'\')"><i class="fa fa-trash" aria-hidden="true"></i>Elimina tu postulación</button>';
                    }
                }elseif( validateUserProfileOwner( $id, $currentId, "familia" ) ){
                    $b = dbGetValidateOwnerOffer($serial);
                    if( $b[0]['count(*)'] == 1 ){
                        $b = do_shortcode("[formEditVacante serial='$serial']").'<div class="buttonCustom"><button class="btn btn-primary" onclick="sendEditVacant(\''.$serial.'\')"><i class="fa fa-edit" aria-hidden="true"></i>Editar vacante</button></div>';
                        $b = '';
                        return $b;
                    }else{
                        return $b = '';
                    }

                }elseif(validateUserProfileOwner( $id, $currentId, "adminTsoluciono" )) {

                    return null;
                }
                elseif (validateUserProfileOwner( $id, $currentId, "profesional" )) {

                    return null;
                }
                else{
                    $ure = $_SERVER["REQUEST_URI"];
                    $regst = "/registro-de-candidata?drec=$ure";
                    // $regst = "/prueba-register?drec=$ure";
                    $logsi = "/iniciar-sesion?drec=$ure";

                    $urlr = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$regst : $regst;
                    $urll = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$logsi : $logsi;

                    return '<h6>¡<a class="resalte1" href="'.$urlr.'" >Registrate</a> ó <a class="resalte1" href="'.$urll.'">Inicia sesión</a> para postularte!</h6>';
                }
                break;
            case 'listaPostulantes':
                return postulateList();
            break;
            default:
                # code...
                break;
        }

        return $data[0][$request];

}

// este es el shortcode que va a actuar como si fuera un api..
function getInfoVacante($args = array()){

    $defaults = array(
        "parametro" => ''
    );
    $args = wp_parse_args($args, $defaults);
    $parametro = $args['parametro'];
    $retorno = getDataVacante($parametro);
    return $retorno;
}add_shortcode('getInfoVacante', 'getInfoVacante');


// edit info vacante
add_action('wp_ajax_editOfferJob', 'editOfferJob');
add_action('wp_ajax_nopriv_editOfferJob', 'editOfferJob');

function editOfferJob(){

    $id = um_user( 'ID' );
    $currentId = get_current_user_id();

    if( validateUserProfileOwner( $id, $currentId, "familia") ){
        if (isset($_POST[ 'dataEditOffer' ])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'dataEditOffer' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);

            // print_r($data);

            dbEditOfferInfo($data);

            die();
        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }

}

add_action('wp_ajax_selectPostulantContrat', 'selectPostulantContrat');
add_action('wp_ajax_nopriv_selectPostulantContrat', 'selectPostulantContrat');

function selectPostulantContrat(){
    $id = um_user( 'ID' );
    $currentId = get_current_user_id();
    global $wpdb;
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata
    if( validateUserProfileOwner( $id, $currentId, "familia") ){
        if (isset($_POST[ 'selectPostulant' ])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'selectPostulant' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $serial = $data['serial'];
            $tabla =$wpdb->prefix . 'ofertalaboral';
            $idOferta = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);
            // se confirma si el usuario ya existe en la lista de postulaciones
            // si no existe entonces se carga en la base
            $data['idOferta'] = $idOferta[0]['id'];
            $data['id'] = uniqid().uniqid();
            $data['visto'] = 0;
            $data['estado'] = 'En espera de aprobación del candidato';
            // se envia la información tipo json para que se cargue en la base de datos

            // print_r($data);
            dbSelectPostulantContrat($data);

        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}

add_action('wp_ajax_deleteSelectPostulantContrat', 'deleteSelectPostulantContrat');
add_action('wp_ajax_nopriv_deleteSelectPostulantContrat', 'deleteSelectPostulantContrat');

function deleteSelectPostulantContrat(){
    $id = um_user( 'ID' );
    $currentId = get_current_user_id();
    global $wpdb;
    // se confirma si el usuario existe, esta logeado y de paso es dueño del perfil de candidata
    if( validateUserProfileOwner( $id, $currentId, "familia") ){
        if (isset($_POST[ 'deleteSelectPostulant' ])) {

            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST[ 'deleteSelectPostulant' ]);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
            $serial = $data['serial'];
            $tabla =$wpdb->prefix . 'ofertalaboral';
            $idOferta = $wpdb->get_results("SELECT id FROM $tabla WHERE serialOferta ='$serial'", ARRAY_A);
            // se confirma si el usuario ya existe en la lista de postulaciones
            // si no existe entonces se carga en la base
            $data['idOferta'] = $idOferta[0]['id'];
            // se envia la información tipo json para que se cargue en la base de datos

            // print_r($data);

            dbDeleteSelectPostulantContrat($data);

        }
    }else{
        $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
        die();
    }
}








?>


