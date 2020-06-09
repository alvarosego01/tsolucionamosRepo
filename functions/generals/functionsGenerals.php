<?php

function moneyConversion($country, $money){

    $retorno = '';

    switch ($country) {
        case 'uy':

            $retorno = '$'.number_format($money, 2, ",", ".");
            break;

        default:
            // code...
            break;
    }

    return $retorno;
}


function getRoles($tipo)
{

    $arrayopciones1 = '';

    switch ($tipo) {

        case 'adminTsoluciono':
            $arrayopciones1 = array('Administración Tsoluciono');
            break;
        case 'candidata':
            $arrayopciones1 = array(
                'Cocinero',
                'Jardinero',
                'Personal trainer',
                'Personal Doméstico con Cama',
                'Personal Doméstico con Retiro',
                'Doméstica Especial para Mudanzas',
                'Cuidado del Adulto Mayor',
                'Babysitter',
                'Profesional Independiente'
            );
            break;
        case 'familia':
            $arrayopciones1 = array('Familia');
            break;
        case 'profesional':
            $arrayopciones1 = array('Profesional Independiente');
            break;
        case 'ambos':
            $arrayopciones1 = array(
                'Familia',
                'Cocinero',
                'Jardinero',
                'Personal trainer',
                'Personal Doméstico con Cama',
                'Personal Doméstico con Retiro',
                'Doméstica Especial para Mudanzas',
                'Cuidado del Adulto Mayor',
                'Babysitter',
                'Profesional Independiente'
            );
            break;
        default:
            # code...
            break;

    }

    return $arrayopciones1;

}

function getPaises()
{
    return $countries = array(
        'Uruguay'
    );
    return $countries = array("Afganistán", "Albania", "Alemania", "Andorra ", "Angola", "Antigua y Barbuda ", "Arabia Saudita", "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés", "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", "Chipre", "Ciudad del Vaticano", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba Habana.", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos", "Estonia", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón", "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea", "Guinea-Bisáu", "Guinea Ecuatorial", "Haití", "Honduras", "Hungría", "India", "Indonesia", "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega", "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Panamá", "Papúa Nueva Guinea", "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido.", "República Centroafricana", "República Checa", "República de Macedonia", "República del Congo", "República Democrática del Congo", "República Dominicana", "República Sudafricana", "Ruanda", "Rumanía", "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino Marino.", "San Vicente y las Granadinas", "Santa Lucía", "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia", "Sri Lanka", "Suazilandia", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam", "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Yibuti", "Zambia", "Zimbabue");
}

// funcion para validar estar LOGEADO y ser dueño del PERFIL o Pagina actual que se esta visualizando
function validateUserProfileOwner($profile_id, $currentuser_id, $tipo)
{
    if (is_user_logged_in()) {
        global $wp_roles;

        // se comprueba que el usuario que esta viendo este perfil, es el mismo usuario que esta logeado.. osea si el usuario logeado que ve el perfil es TRUE y ademas de rol familia, entonces permite mostrar el boton de crear nueva oferta laboral
        // id usuario objetivo
        $user_id1 = $profile_id;
        //  id usuario logeado
        $user_id2 = $currentuser_id;

        $tipo = $tipo;

        // $rol1 = UM()->roles()->um_get_user_role($user_id1);
        // $rol2 = UM()->roles()->um_get_user_role($user_id2);

        $u1 = get_userdata($user_id1);
        global $wp_roles;
        if (is_user_logged_in()) {
            $role = array_shift($u1->roles);
        }
        $rol1 = $wp_roles->roles[$role]['name'];
        $u2 = get_userdata($user_id2);
        global $wp_roles;
        if (is_user_logged_in()) {
            $role = array_shift($u2->roles);
        }
        $rol2 = $wp_roles->roles[$role]['name'];

        switch ($tipo) {
            case 'adminTsoluciono':
                $opciones = getRoles($tipo);
                $u1 = get_userdata($user_id1);
                $u2 = get_userdata($user_id2);

                // solución loca para arreglar la identificación de usuarios de distinto tipo
                if((isset($u1->roles[1])) && ($u1->roles[1] != null) ){
                    $rol1 = $u1->roles[1];
                    $rol1 = $wp_roles->roles[$rol1]['name'];
                }else{
                    $rol1 = $u1->roles;
                    $rol1 = array_shift($rol1);
                    $rol1 = $wp_roles->roles[$rol1]['name'];
                }
                if((isset($u2->roles[1])) && ($u2->roles[1] != null) ){
                    $rol2 = $u2->roles[1];
                    $rol2 = $wp_roles->roles[$rol2]['name'];
                }else{
                    $rol2 = $u2->roles;
                    $rol2 = array_shift($rol2);
                    $rol2 = $wp_roles->roles[$rol2]['name'];
                }


                if ((in_array($rol1, $opciones)) && (in_array($rol2, $opciones)) && ($user_id1 == $user_id2)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'familia':
                $opciones = getRoles($tipo);
                if ((in_array($rol1, $opciones)) && (in_array($rol2, $opciones)) && ($user_id1 == $user_id2)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'candidata':
                $opciones = getRoles($tipo);
                if ((in_array($rol1, $opciones)) && (in_array($rol2, $opciones)) && ($user_id1 == $user_id2)) {
                    return true;
                } else {
                    return false;
                }
            case 'profesional':
                $opciones = getRoles($tipo);
                if ((in_array($rol1, $opciones)) && (in_array($rol2, $opciones)) && ($user_id1 == $user_id2)) {
                    return true;
                } else {
                    return false;
                }
            case 'ambos':
                $opciones = getRoles($tipo);
                if ((in_array($rol1, $opciones)) && (in_array($rol2, $opciones)) && ($user_id1 == $user_id2)) {
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return false;
                break;
        }
    } else {
        return false;
    }

}

// para el formulario
add_action('wp_ajax_sendDataPreForm', 'sendDataPreForm');
add_action('wp_ajax_nopriv_sendDataPreForm', 'sendDataPreForm');

function sendDataPreForm()
{

    if (isset($_POST['dataPreForm'])) {
        // recibe json y quita los slash
        $data = preg_replace('/\\\\\"/', "\"", $_POST['dataPreForm']);
        // transforma el string a un array asociativo
        $data = json_decode($data, true);

        // print_r($data);

        dbGuardarPreForm($data);

        // return;
        die();
    }

}

function dbGuardarPreForm($data)
{
    global $wpdb;

    $datos = array(
        'nombreCompleto' => sanitize_text_field($data['nombreCompleto']),
        'cedula' => sanitize_text_field($data['cedula']),
        'edad' => sanitize_text_field($data['edad']),
        'ciudadResidencia' => sanitize_text_field($data['ciudadResidencia']),
        'direccionResidencia' => sanitize_text_field($data['direccionResidencia']),
        'telefonoMovil' => sanitize_text_field($data['telefonoMovil']),
        'telefonoFijo' => sanitize_text_field($data['telefonoFijo']),
        'estadoCivil' => sanitize_text_field($data['estadoCivil']),
        'numeroHijos' => sanitize_text_field($data['numeroHijos']),
        'culminoBachillerato' => sanitize_text_field($data['culminoBachillerato']),
        'algunEstudioSuperior' => sanitize_text_field($data['algunEstudioSuperior']),
        'disponibilidadFinesDeSemana' => sanitize_text_field($data['disponibilidadFinesDeSemana']),
        'tieneReferenciaDeEmpleos' => sanitize_text_field($data['tieneReferenciaDeEmpleos']),
        'referenciasAfirmativo' => sanitize_text_field($data['referenciasAfirmativo']),
        'empleadaDomesticaFamilia' => sanitize_text_field($data['empleadaDomesticaFamilia']),
        'anosEmpleadaCasaFamilia' => sanitize_text_field($data['anosEmpleadaCasaFamilia']),
        'empleadaNinera' => sanitize_text_field($data['empleadaNinera']),
        'anosEmpleadaNinera' => sanitize_text_field($data['anosEmpleadaNinera']),
        'empleadaCuidadoraAdultoMayor' => sanitize_text_field($data['empleadaCuidadoraAdultoMayor']),
        'anosEmpleadaCuidadoraAdulto' => sanitize_text_field($data['anosEmpleadaCuidadoraAdulto']),
        'empleadaCuidadoDeMascotas' => sanitize_text_field($data['empleadaCuidadoDeMascotas']),
        'anosCuidadoraMascota' => sanitize_text_field($data['anosCuidadoraMascota']),
        'empleadaCuidadoNeoNatales' => sanitize_text_field($data['empleadaCuidadoNeoNatales']),
        'anosCuidadoNeoNato' => sanitize_text_field($data['anosCuidadoNeoNato']),
        'calificaLimpieza' => sanitize_text_field($data['calificaLimpieza']),
        'calificaCuidadoNinos' => sanitize_text_field($data['calificaCuidadoNinos']),
        'calificaCuidadoAncianos' => sanitize_text_field($data['calificaCuidadoAncianos']),
        'calificaCocina' => sanitize_text_field($data['calificaCocina']),
        'calificaPlanchado' => sanitize_text_field($data['calificaPlanchado']),
        'calificaCuidadoEnfermos' => sanitize_text_field($data['calificaCuidadoEnfermos']),
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
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );
    $tabla = $wpdb->prefix . 'base_preForm';
    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();

    die();
}

// retornar información de usuarios en general en formato json
function getUserGeneralInfo($data)
{
    global $wp_roles;

    $id = $data;
    $userMeta = get_user_meta($id);
    $userData = get_userdata($id);

    $d = array(
        'userMeta' => $userMeta,
        // 'userData' => $userData,
    );

    return $d;

}

// obtener nombre y email de personas
function getInfoNameEmailUsers($data){

    global $wpdb;
    global $wp_roles;

    $usuario = get_user_meta($data);
    $u = get_userdata($data);

    $role = array_shift($u->roles);
    $rolCandidata = $wp_roles->roles[$role]['name'];

    $nameC = $usuario['first_name'][0] . ' ' . $usuario['last_name'][0];

    $url = '/perfil-de-usuario/' . $usuario['um_user_profile_url_slug_name_dash'][0];

    $userEmail = $u->data->user_email;

    $x = array(
        'id' => $data,
        'nombre' => $nameC,
        'rol' => $rolCandidata,
        'email' => $userEmail,
        'url' => $url
    );

    return $x;

}

function saveNotification($data){

    global $wpdb;
    $tabla = $wpdb->prefix . 'notificacion_msg';

    // PARTE DE LA NOTIFICACIÓN
    $mensaje = $data['mensaje'];
    $subject = $data['subject'];
    $estado = $data['estado'];
    $fecha = date('d/m/Y');
    $tipo = $data['tipo'];
    $email = $data['email'];
    $usuarioMuestra = $data['usuarioMuestra'];

    $datos = array(
        'mensaje' => $mensaje,
        'subject' => sanitize_text_field($subject),
        'estado' => sanitize_text_field($estado),
        'fecha' => sanitize_text_field($fecha),
        'tipo' => sanitize_text_field($tipo),
        'email' => sanitize_text_field($email),
        'usuarioMuestra' => sanitize_text_field($usuarioMuestra),
    );

    $formato = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
    );

    // print_r($datos);
    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();

    $sujetoDeCorreo = strip_tags($subject);

    wp_mail(
        'alvarosego01@gmail.com',
        $sujetoDeCorreo.' ('.$fecha.')',
        '<div style="width: 50%;
        margin: 25px auto;
        border: 2px solid #336EB2;
        padding: 50px;
        ">

            <div style="display: flex;
            flex-direction: column;
            border-bottom: 1px solid rgba(0,0,0,0.3);
            padding-bottom: 15px;">
            <img style="width: 200px; margin: auto; display: block" src="http://www.tsolucionamos.com/wp-content/uploads/Logo-Azul.png" alt="">

            </div>

            <div>
                <p style="color: black;
                text-align: justify;
                margin: 25px 0;">
                '.$subject.' ('.$fecha.') <br><br>

                '.$mensaje.'
                </p>
            </div>

            <div style="border-top: 1px solid rgba(0,0,0,0.3); width: 100%; margin-top: 25px;">
                <small style="text-align:  center; margin-top: 25px; padding-top: 15px; color: black">
                    ©Tsolucionamos 2020.
                </small>
            </div>

        </div>'
    );


}

function infoNotification($data, $pagController = ''){

    global $wpdb;
    $id = $data;
    $tabla = $wpdb->prefix . 'notificacion_msg';

    if (validateUserProfileOwner($id, $id, 'adminTsoluciono')) {
        $id = 'Tsoluciono';
    }


if(isset($pagController) && $pagController != ''){


    $perPage = $pagController['porPagina'];
        // $perPage = 1;
        $pageno = $pagController['pg'];
        $offset = ($pageno-1) * $perPage;
        $filtroPor = $pagController['filterBy'];

        if($filtroPor == 'todos'){


    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id'", ARRAY_A);
    $wpdb->flush();

    $total_rows = count($x);
    $total_pages = ceil($total_rows / $perPage);

    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' LIMIT $perPage OFFSET $offset", ARRAY_A);
    $wpdb->flush();

    $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
    );

    $x['pageData'] = $v;


    return $x;

        }

        if($filtroPor == 'leidos'){
               $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' and estado = 1", ARRAY_A);
    $wpdb->flush();

    $total_rows = count($x);
    $total_pages = ceil($total_rows / $perPage);

    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' and estado = 1 LIMIT $perPage OFFSET $offset", ARRAY_A);
    $wpdb->flush();

    $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
    );

    $x['pageData'] = $v;

    return $x;

        }

        if($filtroPor == 'noLeidos'){
               $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' and estado = 0", ARRAY_A);
    $wpdb->flush();

    $total_rows = count($x);
    $total_pages = ceil($total_rows / $perPage);

    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' and estado = 0 LIMIT $perPage OFFSET $offset", ARRAY_A);
    $wpdb->flush();

    $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
    );

    $x['pageData'] = $v;

    return $x;

        }

}else{

        $pageno = 1;
        $perPage = 10;
        $offset = 0;

    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id'", ARRAY_A);
    $wpdb->flush();

    $total_rows = count($x);
    $total_pages = ceil($total_rows / $perPage);

    $x = $wpdb->get_results("SELECT * from $tabla WHERE usuarioMuestra='$id' ORDER BY id DESC LIMIT $perPage OFFSET $offset ", ARRAY_A);
    $wpdb->flush();

    $v = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
    );

    $x['pageData'] = $v;


    return $x;
}

}


function notificationNavBar($args = array())
{
    if (is_user_logged_in()) {
    global $wpdb;
    $defaults = array(
        "rows" => 0,
    );
    $args = wp_parse_args($args, $defaults);

    $id =  get_current_user_id();

    $tabla = $wpdb->prefix . 'notificacion_msg';
    $infoNotif = infoNotification($id);
    $m = '';

    if(count($infoNotif)>0) {

        $pageData = $infoNotif['pageData'];
          unset($infoNotif['pageData']);

        $pagina = esc_url(get_permalink(get_page_by_title('Mensaje')));

        $n = 0;

        // info preview
        foreach ($infoNotif as $key => $value) {
            $n += ($value['estado'] == 0)? 1: 0;
            $i = ($value['estado'] == 0)? '<span class="new"></span>': '';
            $i .= $value['subject'].' - <span>'.$value['fecha'].'</span>';
            // $l = <div class="notification-comment">fdfdsf</div>
            $m .= '<li onclick="openNotif('.$value['id'].')" class="notification-item menu-item menu-item-type-post_type menu-item-object-page">';
            $m .= '<a href="'.$pagina.'?mensaje='.$value['id'].'">'. $i.'</a></li>';

        }
    }
    // <ul class="sub-menu">
    $x = '<li id="menuNotif" class="menu-item menu-item-type-post_type menu-item-object-page">

    <div id="notification-header">
             <button id="notification-icon" name="button" onclick="myFunction()" class="dropbtn">
             <span id="notification-count">';
    $x .= ($n>0)? $n: '';
    $x .='</span>
               <i class="fa fa-bell" aria-hidden="true"></i>
            </button>
             <ul id="notification-latest" class="class="sub-menu">';

    $x .= (count($infoNotif) > 0)? '<h6>Notificaciones</h6>'.$m: '<h6>Sin resultados</h6>';
    $x .= '</div></ul></li>';

    return $x;

    }
}add_shortcode('notificationNavBar', 'notificationNavBar');

add_action('wp_ajax_processDeleteNot', 'processDeleteNot');
add_action('wp_ajax_nopriv_processDeleteNot', 'processDeleteNot');
function processDeleteNot(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
      if (isset($_POST['processDeleteNot'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['processDeleteNot']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos


          dbprocessDeleteNot($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}

function dbprocessDeleteNot($data){
    global $wpdb;

    $id = $data['m'];

    try {
        $tabla = $wpdb->prefix . 'notificacion_msg';
        $wpdb->query("DELETE FROM $tabla WHERE id = $id");
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

// retorna las firmas almacenadas
function getSignUser($userId){

    global $wpdb;
    $tablaFirmas = $wpdb->prefix . 'usuariofirmas';
    $x = $wpdb->get_results("SELECT * FROM $tablaFirmas WHERE usuarioId =$userId", ARRAY_A);

    return $x[0];

}

// extrae imagenes de un form y las ordena en un array para trabajarlas
function imagesToArray($data){



    $r = array();
    foreach ($data as $key => $value) {
        # code...
        if(($value['tmp_name'] != '') && ($value['tmp_name'] != null)){

            $f = $value['type'];
            $f =  explode('/', $f);
            $f = $f[1];
            $r[$key] = array(
                'formato' => $f,
                'file' => $value['tmp_name']
            );
        }

    }


    return $r;
    // $datos = stripslashes($datos);


}

function cargarImagenes($data){




    $imagenes = $data['imagenes'];
    $carpeta = $data['carpeta'];
    $serial = $data['serial'];

    $arrayImagenes = array();

    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . "/$carpeta";
    $folder = $upload_dir;

    if (! is_dir($folder)) {
       mkdir( $folder, 0777 );
    }
    $folder .= "/$serial";
    if (! is_dir($folder)) {
       mkdir( $folder, 0777 );
    }

    $urlImg = $upload['url']."$carpeta/$serial";
    $urlImg = explode("/wp-content", $urlImg);
    $urlImg = "/wp-content".$urlImg[1];


    foreach ($imagenes as $key => $value) {


        if($key === 'imagenPrincipal'){

            $i = $value['file'];
            $f = $value['formato'];
            $s = uniqid();
            $archivo = "$folder/$s.$f";
            // unlink($archivo);
            move_uploaded_file($i, $archivo);
            $imgUrl = "$urlImg/$s.$f";
            $x = array(
                // 'img' => $i,
                'formato' => $f,
                'serial' => $s,
                'src' => $imgUrl
            );
            $arrayImagenes['principal'] = $x;
        }else{
            $i = $value['file'];
            $f = $value['formato'];
            $s = uniqid();
            $archivo = "$folder/$s.$f";
            // unlink($archivo);
            move_uploaded_file($i, $archivo);
            $imgUrl = "$urlImg/$s.$f";
            $x = array(
                // 'img' => $i,
                'formato' => $f,
                'serial' => $s,
                'src' => $imgUrl
            );
            $arrayImagenes[$key] = $x;
        }

    }

    if(count($arrayImagenes) > 0){

        // $m = $arrayImagenes;
        $m = json_encode($arrayImagenes);

        return $m;
    }


}

function tranformMeses($data){

    $date = explode("/", $data);
    $dia = $date[0];
    $mesNumero = $date[1];
    $anio = $date[2];
    $mes = '';

    switch ($mesNumero) {
        case '1':
            $mes = 'Enero';
        break;
        case '2':
            $mes = 'Febrero';
        break;
        case '3':
            $mes = 'Marzo';
        break;
        case '4':
            $mes = 'Abril';
        break;
        case '5':
            $mes = 'Mayo';
        break;
        case '6':
            $mes = 'Junio';
        break;
        case '7':
            $mes = 'Julio';
        break;
        case '8':
            $mes = 'Agosto';
        break;
        case '9':
            $mes = 'Septiembre';
        break;
        case '10':
            $mes = 'Octubre';
        break;
        case '11':
            $mes = 'Noviembre';
        break;
        case '12':
            $mes = 'Diciembre';
        break;
        default:
            # code...
            break;
    }

    $x = array(
        'dia' => $dia,
        'mes' => $mes,
        'anio' => $anio
    );

    return $x;
}

function getStateCandidateOnContract($data){

    global $wpdb;

    $tablaSelectInterviews = $wpdb->prefix . 'proceso_contrato';
    $tablaExperiencia = $wpdb->prefix . 'experiencia_contratos';

    // $idFamilia = $data['idFamilia'];
    // $ìdContrato = $data['ìdContrato'];
    // $detallesExperiencia = $data['detallesExperiencia'];
    $idCandidato = $data['idCandidato'];
    $idEntrevista = $data['idEntrevista'];

    $info = $wpdb->get_results("SELECT * from $tablaExperiencia where idCandidato=$idCandidato and idEntrevista='$idEntrevista'", ARRAY_A);

    return $info[0];

}

add_action('wp_ajax_datarefreshInfo', 'datarefreshInfo');
add_action('wp_ajax_nopriv_datarefreshInfo', 'datarefreshInfo');

function datarefreshInfo(){

  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {


    if (isset($_POST['datarefreshInfo'])) {

          // recibe json y quita los slash
        $data = preg_replace('/\\\\\"/', "\"", $_POST['datarefreshInfo']);
          // transforma el string a un array asociativo
        $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos

        //   Array
// (
    // [porPagina] => 5
    // [filterBy] => todos
    // [data] => postInterviews
        $d = $data['data'];
        $data['pg'] = ( isset($data['pg']))? $data['pg']: 1;

        // print_r($data);

        // print_r(array(
        //     'mensaje' => 'recibe php',
        //     'controller' => $data
        // ));

        if($d == 'myVacants' ){
            misVacantes1($data);
        }
        if($d == 'postInterviews' ){
            misVacantes2($data);
        }
        if($d == 'contractList' ){
            misVacantes3($data);
        }
        if($d == 'notifList' ){
            misVacantes4($data);
        }
        if($d == 'factList' ){
            misVacantes5($data);
        }

      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}


function colorUsers(){
    return array(
        'Familias' => 'rgba(255, 159, 64, 1)',
        'Cocinero' => 'rgba(54, 162, 235, 1)',
        'Personal trainer' => 'rgba(255, 206, 86, 1)',
        'Jardinero' => 'rgba(68, 211, 98, 1)',
        'Babysitter' => 'rgba(224, 254, 254, 1)',
        'Personal Doméstico con Retiro' => 'rgba(230, 185, 161, 1)',
        'Personal Doméstico con Cama' => 'rgba(255, 154, 162, 1)',
        'Doméstica Especial para Mudanzas' => 'rgba(88, 148, 156, 1)',
        'Cuidado del Adulto Mayor' => 'rgba(75, 192, 192, 1)',
        'Profesional Independiente' => 'rgba(153, 102, 255, 1)',
        'usuarios' => 'rgba(255, 99, 132, 1)'
    );
}





function colorsEnterado(){
    return array(
        'Un amigo' => 'rgba(255, 159, 64, 1)',
        'Redes sociales'  => 'rgba(54, 162, 235, 1)',
        'Pagina de la empresa' => 'rgba(255, 99, 132, 1)',
        'Otra forma' => 'rgba(153, 102, 255, 1)',

    );
}

// solución para las tareas en tiempo
add_filter( 'cron_schedules', 'tiempoPersonalizadoTareas');
function tiempoPersonalizadoTareas( $schedules ) {

    $schedules['unDia'] = array(
       'interval' => 86400,
       'display' =>'1 dia'
    );
    $schedules['5Segundos'] = array(
       'interval' => 5,
       'display' =>'5 segundos'
    );
    return $schedules;

}



function getDepartaments(){


    return array(
        'Artigas',
        'Canelones',
        'Cerro Largo',
        'Colonia',
        'Durazno',
        'Flores',
        'Florida',
        'Lavalleja',
        'Maldonado',
        'Montevideo',
        'Paysandú',
        'Río Negro',
        'Rivera',
        'Rocha',
        'Salto',
        'San José',
        'Soriano',
        'Tacuarembó',
        'Treinta y Tres'
    );

}




function getAllUsersByRole(){

        global $wpdb;

        $blogusers = get_users( [ 'role__in' => [
            'um_domestica-especial-para-mudanzas',
            'um_cuidado-del-adulto-mayor',
            'um_babysitter',
            'um_personal-domestico-con-cama',
            'um_jardinero',
            'um_personal-trainer',
            'um_personal-domestico-con-retiro',
            'um_cocinero',
            'um_familia',
            'um_profesional-independiente'
            ] ] );



        $um_domestica_especial_para_mudanzas = array();
        $um_cuidado_del_adulto_mayor = array();
        $um_babysitter = array();
        $um_personal_domestico_con_cama = array();
        $um_jardinero = array();
        $um_personal_trainer = array();
        $um_personal_domestico_con_retiro = array();
        $um_cocinero = array();
        $um_profesional_independiente = array();
        $familias = array();

        foreach ($blogusers as $key => $value) {

            if( $value->roles[0] == 'um_profesional-independiente'){

                 array_push($um_profesional_independiente, $value->ID);
            }

            if( $value->roles[0] == 'um_domestica-especial-para-mudanzas'){

                 array_push($um_domestica_especial_para_mudanzas, $value->ID);
            }

            if( $value->roles[0] == 'um_cuidado-del-adulto-mayor'){

                 array_push($um_cuidado_del_adulto_mayor, $value->ID);
            }

            if( $value->roles[0] == 'um_babysitter'){

                 array_push($um_babysitter, $value->ID);
            }

            if( $value->roles[0] == 'um_personal-domestico-con-cama'){

                 array_push($um_personal_domestico_con_cama, $value->ID);
            }

            if( $value->roles[0] == 'um_jardinero'){

                 array_push($um_jardinero, $value->ID);
            }

            if( $value->roles[0] == 'um_personal-trainer'){

                 array_push($um_personal_trainer, $value->ID);
            }

            if( $value->roles[0] == 'um_personal-domestico-con-retiro'){

                 array_push($um_personal_domestico_con_retiro, $value->ID);
            }

            if( $value->roles[0] == 'um_cocinero'){

                 array_push($um_cocinero, $value->ID);
            }

            if( $value->roles[0] == 'um_familia'){

                    array_push($familias, $value->ID);
            }


            //  array_push($ids, $value->ID);
        }


        $usuarios = array(
            'Familias' => $familias,
            'Profesionales' => $um_profesional_independiente,
            'Candidatos' => array(),
            'usuariosTotales' => count($blogusers)

        );
        if(count($um_cocinero) > 0){
            $usuarios['Candidatos']['Cocinero'] = $um_cocinero;
        }
        if(count($um_personal_trainer) > 0){
            $usuarios['Candidatos']['Personal trainer'] = $um_personal_trainer;
        }
        if(count($um_jardinero) > 0){
            $usuarios['Candidatos']['Jardinero'] = $um_jardinero;
        }
        if(count($um_babysitter) > 0){
            $usuarios['Candidatos']['Babysitter'] = $um_babysitter;
        }
        if(count($um_personal_domestico_con_retiro) > 0){
            $usuarios['Candidatos']['Personal Doméstico con Retiro'] = $um_personal_domestico_con_retiro;
        }
        if(count($um_personal_domestico_con_cama) > 0){
            $usuarios['Candidatos']['Personal Doméstico con Cama'] = $um_personal_domestico_con_cama;
        }
        if(count($um_domestica_especial_para_mudanzas) > 0){
            $usuarios['Candidatos']['Doméstica Especial para Mudanzas'] = $um_domestica_especial_para_mudanzas;
        }
        if(count($um_cuidado_del_adulto_mayor) > 0){
            $usuarios['Candidatos']['Cuidado del Adulto Mayor'] = $um_cuidado_del_adulto_mayor;
        }



        return $usuarios;
}


function iconosPublic($tipo){

    $icono = '';
    if($tipo == 'trato'){
        $icono = '<svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m255.995 101.946c5.522 0 10-4.478 10-10v-50.94c0-5.522-4.477-10-10-10s-10 4.477-10 10v50.94c.001 5.522 4.478 10 10 10z"/><path d="m180.812 118.373c1.901 3.017 5.148 4.669 8.469 4.669 1.822 0 3.667-.498 5.322-1.541 4.672-2.944 6.072-9.119 3.128-13.792l-26.644-42.275c-2.945-4.672-9.12-6.073-13.791-3.128-4.672 2.944-6.072 9.119-3.128 13.792z"/><path d="m317.42 121.504c1.655 1.041 3.498 1.538 5.318 1.538 3.322 0 6.571-1.654 8.472-4.673l26.616-42.275c2.942-4.674 1.539-10.847-3.135-13.791-4.676-2.941-10.847-1.537-13.79 3.135l-26.616 42.275c-2.942 4.675-1.538 10.848 3.135 13.791z"/><path d="m508.776 266.432-17.375-30.695c-2.722-4.808-8.824-6.498-13.627-3.776-4.807 2.721-6.497 8.822-3.776 13.627l17.367 30.682c.87 1.542.644 3.029.432 3.778-.204.721-.766 2.052-2.236 2.864l-26.778 14.833c-2.379 1.314-5.484.484-6.781-1.804l-6.803-12.034c-.028-.054-.06-.104-.089-.157l-70.33-124.394c-.869-1.531-.649-2.999-.441-3.738.201-.715.759-2.036 2.229-2.849l26.81-14.836c2.409-1.334 5.438-.524 6.755 1.81l15.674 27.693c2.721 4.807 8.824 6.496 13.628 3.777 4.807-2.72 6.497-8.822 3.777-13.628l-15.667-27.681c-6.682-11.846-21.864-16.095-33.846-9.472l-26.809 14.836c-5.801 3.206-9.992 8.505-11.8 14.924-1.706 6.052-1.096 12.37 1.697 17.915l-14.768 4.28c-6.291 1.82-10.109 2.807-12.228 2.807-.009 0-.017 0-.025 0-1.459-.005-3.05-.562-7.591-2.282-2.06-.78-4.625-1.752-7.778-2.852l-26.165-9.147c-14.471-5.054-20.998-4.782-30.454-3.919l-44.616 3.993c-20.339 1.802-33.306 6.39-40.464 14.347l-25.426-7.367c2.736-5.521 3.323-11.778 1.633-17.775-1.808-6.415-5.996-11.713-11.791-14.919l-26.794-14.843c-11.992-6.629-27.183-2.378-33.866 9.465l-77.204 136.523c-3.305 5.819-4.123 12.587-2.301 19.058 1.81 6.426 6 11.73 11.79 14.932l26.794 14.841c3.831 2.118 7.993 3.124 12.1 3.124 8.725 0 17.207-4.539 21.766-12.588l2.831-5.008c7.726 3.401 14.213 8.342 18.634 14.192-4.318 5.046-7.038 11.273-7.801 18.046-1.008 8.957 1.522 17.745 7.124 24.75 5.554 6.945 13.496 11.327 22.377 12.359-.94 8.881 1.6 17.583 7.159 24.51 5.746 7.184 13.867 11.375 22.362 12.364-.936 8.868 1.595 17.562 7.137 24.493 5.551 6.96 13.495 11.351 22.385 12.383-.941 8.888 1.599 17.595 7.158 24.523 6.626 8.284 16.408 12.597 26.283 12.596 7.372 0 14.796-2.404 20.994-7.354l20.938-16.754c1.663-1.327 3.174-2.794 4.533-4.374l11.96 9.554c6.185 4.936 13.592 7.334 20.954 7.333 9.88-.001 19.678-4.319 26.313-12.611 3.497-4.372 5.711-9.362 6.711-14.513 3.497 1.21 7.189 1.835 10.953 1.834 1.262 0 2.532-.07 3.806-.211 8.96-.995 16.972-5.401 22.539-12.383 5.569-6.938 8.109-15.639 7.17-24.519 8.489-.991 16.614-5.181 22.377-12.362 5.74-7.177 8.044-16.013 7.142-24.511 8.876-1.034 16.814-5.415 22.355-12.344 5.606-6.986 8.147-15.761 7.154-24.711-.754-6.794-3.476-13.042-7.802-18.102 4.408-5.848 10.882-10.795 18.613-14.194l2.837 5.018c4.556 8.046 13.034 12.583 21.761 12.583 4.109-.001 8.275-1.007 12.11-3.127l26.779-14.834c5.788-3.2 9.976-8.496 11.793-14.911 1.832-6.465 1.031-13.233-2.262-19.068zm-452.783 29.504c-1.3 2.296-4.407 3.124-6.776 1.813l-26.793-14.84c-1.463-.81-2.021-2.136-2.225-2.855-.209-.745-.432-2.224.45-3.774l77.216-136.55c.884-1.567 2.55-2.445 4.28-2.445.842 0 1.698.207 2.488.644l26.786 14.838c.003.002.006.004.009.005 1.466.811 2.023 2.132 2.225 2.847.208.739.428 2.207-.451 3.755zm72.974 51.46c-2.82 2.259-6.381 3.28-10.02 2.875-3.641-.404-6.886-2.178-9.14-4.996-2.259-2.825-3.277-6.385-2.868-10.023.407-3.623 2.182-6.852 5.016-9.107l37.438-29.926c5.876-4.691 14.477-3.726 19.173 2.139 2.258 2.823 3.277 6.377 2.869 10.004-.406 3.615-2.182 6.841-5 9.083-.062.049-.115.104-.175.155l-37.284 29.789c-.003.002-.006.005-.009.007zm10.364 34.734c-2.257-2.813-3.277-6.358-2.874-9.982.403-3.627 2.18-6.871 5.002-9.134l.003-.002c.002-.001.004-.003.006-.004.001 0 .001-.001.002-.002l53.926-43.084c5.875-4.692 14.478-3.734 19.173 2.139 2.255 2.819 3.274 6.369 2.871 9.995-.404 3.63-2.186 6.874-5.015 9.134l-53.912 43.068c-5.882 4.694-14.482 3.747-19.182-2.128zm38.658 41.89c-3.64-.404-6.882-2.18-9.139-5.01-2.255-2.819-3.274-6.369-2.871-9.995.404-3.627 2.183-6.868 5.008-9.128.002-.002.005-.004.007-.006l37.318-29.812c.044-.034.092-.063.136-.098 5.86-4.691 14.46-3.736 19.16 2.122 2.259 2.824 3.278 6.384 2.869 10.022-.408 3.623-2.183 6.852-5.016 9.107l-37.437 29.925c-2.829 2.259-6.394 3.275-10.035 2.873zm65.518 8.118c-.405 3.627-2.182 6.864-5.012 9.124l-20.939 16.757c-5.883 4.695-14.483 3.75-19.181-2.126-2.257-2.811-3.278-6.359-2.875-9.99s2.178-6.87 4.995-9.12c.003-.002.007-.005.01-.007l20.934-16.734c.011-.009.023-.016.034-.024 5.877-4.69 14.478-3.729 19.174 2.139 2.249 2.814 3.266 6.357 2.86 9.981zm158.678-86.864c-2.254 2.818-5.5 4.593-9.14 4.997-3.637.409-7.2-.615-10.02-2.875-.001-.001-.003-.002-.005-.003-.001-.001-.003-.002-.004-.003l-26.758-21.379c-4.316-3.448-10.608-2.744-14.054 1.57-3.447 4.314-2.744 10.607 1.57 14.054l26.749 21.372c5.868 4.699 6.835 13.283 2.165 19.122-4.712 5.872-13.313 6.827-19.185 2.124l-29.025-23.162c-4.316-3.444-10.608-2.737-14.053 1.579-3.444 4.316-2.738 10.608 1.579 14.053l29.011 23.151c2.827 2.264 4.606 5.51 5.01 9.141.403 3.624-.617 7.17-2.893 10.005-2.249 2.82-5.491 4.595-9.13 5-3.643.401-7.208-.616-10.026-2.865l-14.442-11.55c-.025-.02-.047-.041-.072-.061l-12.202-9.755-2.281-1.824c-4.315-3.45-10.607-2.749-14.055 1.563-.431.54-.798 1.11-1.1 1.702-1.448 2.83-1.424 6.149-.065 8.932.629 1.289 1.535 2.466 2.729 3.421l14.521 11.613c5.829 4.707 6.782 13.262 2.115 19.097-4.697 5.871-13.299 6.83-19.172 2.145l-16.475-13.162c.686-8.559-1.85-16.906-7.214-23.613-3.334-4.167-7.469-7.323-12.01-9.44 3.328-4.617 5.441-10.041 6.097-15.873 1.008-8.956-1.522-17.744-7.134-24.761-3.335-4.156-7.465-7.305-11.997-9.417 3.334-4.631 5.447-10.066 6.097-15.906.995-8.936-1.537-17.707-7.129-24.7-9.622-12.03-25.894-15.687-39.437-9.939-1.084-4.978-3.299-9.674-6.569-13.763-11.578-14.468-32.78-16.835-47.271-5.273l-26.776 21.403c-6.055-7.727-14.286-14.135-24.018-18.705l55.079-97.421 29.326 8.497c.134 1.569.388 3.219.813 4.967 7.099 29.188 33.973 42.788 70.136 35.501 30.47-6.131 43.014 3.002 67.997 21.195 2.592 1.887 5.285 3.848 8.104 5.867 17.967 12.89 37.167 28.041 50.04 38.31l32.46 25.942c2.82 2.25 4.595 5.49 4.998 9.124.403 3.631-.617 7.179-2.884 10.003zm-.311-43.286-21.783-17.409c-13.051-10.411-32.522-25.774-50.867-38.936-2.78-1.991-5.431-3.922-7.983-5.78-25.906-18.865-44.629-32.495-83.717-24.634-11.964 2.411-40.468 5.23-46.755-20.618-.886-3.649.014-4.903.397-5.438 1.337-1.864 6.717-6.401 27.766-8.265l44.663-3.997c7.278-.664 10.929-.998 22.037 2.882l26.168 9.148c2.909 1.016 5.235 1.896 7.288 2.674 12.6 4.773 15.939 4.772 32.492-.019l19.292-5.59 55.005 97.289c-9.735 4.567-17.958 10.97-24.003 18.693z"/><path d="m460.57 211.599c5.522 0 10-4.492 10-10.015s-4.478-10-10-10-10 4.478-10 10v.028c0 5.523 4.478 9.987 10 9.987z"/></g></svg>';
    }
    if($tipo == 'moneyHand'){
        $icono = '<svg height="487pt" viewBox="-29 0 487 487.71902" width="487pt" xmlns="http://www.w3.org/2000/svg"><path d="m220.867188 266.175781c-.902344-.195312-1.828126-.230469-2.742188-.09375-9.160156-1.066406-16.070312-8.816406-16.085938-18.035156 0-4.417969-3.582031-8-8-8-4.417968 0-8 3.582031-8 8 .023438 15.394531 10.320313 28.878906 25.164063 32.953125v8c0 4.417969 3.582031 8 8 8s8-3.582031 8-8v-7.515625c17.132813-3.585937 28.777344-19.542969 26.976563-36.953125-1.804688-17.410156-16.472657-30.640625-33.976563-30.644531-10.03125 0-18.164063-8.132813-18.164063-18.164063s8.132813-18.164062 18.164063-18.164062 18.164063 8.132812 18.164063 18.164062c0 4.417969 3.582031 8 8 8 4.417968 0 8-3.582031 8-8-.023438-16.164062-11.347657-30.105468-27.164063-33.441406v-7.28125c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v7.769531c-16.507813 4.507813-27.132813 20.535157-24.859375 37.496094s16.746094 29.621094 33.859375 29.617187c9.898437 0 17.972656 7.925782 18.152344 17.820313.183593 9.894531-7.597657 18.113281-17.488281 18.472656zm0 0"/><path d="m104.195312 222.5c0 64.070312 51.9375 116.007812 116.007813 116.007812s116.007813-51.9375 116.007813-116.007812-51.9375-116.007812-116.007813-116.007812c-64.039063.070312-115.933594 51.96875-116.007813 116.007812zm116.007813-100.007812c55.234375 0 100.007813 44.773437 100.007813 100.007812s-44.773438 100.007812-100.007813 100.007812-100.007813-44.773437-100.007813-100.007812c.0625-55.207031 44.800782-99.945312 100.007813-100.007812zm0 0"/><path d="m375.648438 358.230469-62.667969 29.609375c-8.652344-16.09375-25.25-26.335938-43.515625-26.851563l-57.851563-1.589843c-9.160156-.261719-18.148437-2.582032-26.292969-6.789063l-5.886718-3.050781c-30.140625-15.710938-66.066406-15.671875-96.175782.101562l.367188-13.335937c.121094-4.417969-3.359375-8.097657-7.777344-8.21875l-63.4375-1.746094c-4.417968-.121094-8.09375 3.359375-8.214844 7.777344l-3.832031 139.210937c-.121093 4.417969 3.359375 8.097656 7.777344 8.21875l63.4375 1.746094h.21875c4.335937 0 7.882813-3.449219 8-7.78125l.183594-6.660156 16.480469-8.824219c6.46875-3.480469 14.03125-4.308594 21.097656-2.308594l98.414062 27.621094c.171875.050781.34375.089844.519532.128906 7.113281 1.488281 14.363281 2.234375 21.628906 2.230469 15.390625.007812 30.601562-3.308594 44.589844-9.730469.34375-.15625.675781-.339843.992187-.546875l142.691406-92.296875c3.554688-2.300781 4.703125-6.96875 2.621094-10.65625-10.59375-18.796875-34.089844-25.957031-53.367187-16.257812zm-359.070313 107.5625 3.390625-123.21875 47.441406 1.304687-3.390625 123.222656zm258.925781-2.09375c-17.378906 7.84375-36.789062 10.007812-55.46875 6.191406l-98.148437-27.550781c-11.046875-3.121094-22.871094-1.828125-32.976563 3.605468l-8.421875 4.511719 2.253907-81.925781c26.6875-17.75 60.914062-19.574219 89.335937-4.765625l5.886719 3.050781c10.289062 5.3125 21.636718 8.242188 33.210937 8.578125l57.855469 1.589844c16.25.46875 30.050781 12.039063 33.347656 27.960937l-86.175781-2.378906c-4.417969-.121094-8.09375 3.363282-8.21875 7.777344-.121094 4.417969 3.363281 8.097656 7.777344 8.21875l95.101562 2.617188h.222657c4.332031-.003907 7.875-3.453126 7.992187-7.78125.097656-3.476563-.160156-6.957032-.773437-10.378907l64.277343-30.371093c.0625-.027344.125-.058594.1875-.089844 9.117188-4.613282 20.140625-3.070313 27.640625 3.871094zm0 0"/><path d="m228.203125 84v-76c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v76c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/><path d="m288.203125 84v-36c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v36c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/><path d="m168.203125 84v-36c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v36c0 4.417969 3.582031 8 8 8s8-3.582031 8-8zm0 0"/></svg>';
    }

    if( $tipo == 'trainer' ){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 496 496" style="enable-background:new 0 0 496 496;" xml:space="preserve">
<g>
    <g>
        <path d="M392.704,317.832L304,290.12v-22.288c13.688-11.32,23.824-26.728,28.712-44.304C352.528,221.16,368,204.44,368,184v-80    C368,46.656,321.344,0,264,0h-32c-57.344,0-104,46.656-104,104v80c0,20.44,15.472,37.16,35.288,39.52    c4.888,17.576,15.024,32.984,28.712,44.304v22.288l-88.704,27.72C79.792,325.176,64,346.664,64,371.288V496h368V371.288    C432,346.664,416.208,325.176,392.704,317.832z M335.664,206.632c0.168-2.2,0.336-4.392,0.336-6.632v-38.528    c9.288,3.312,16,12.112,16,22.528C352,194.536,345.128,203.416,335.664,206.632z M352,152.208c-4.672-3.536-10.056-6.184-16-7.392    v-2.712c6.52-4.696,11.944-9.904,16-15.528V152.208z M232,16h32c45.824,0,83.536,35.216,87.592,80h-16.064    c-4-35.944-34.536-64-71.528-64h-32c-36.992,0-67.528,28.056-71.528,64h-16.064C148.464,51.216,186.176,16,232,16z M319.36,96    H176.64c3.904-27.096,27.208-48,55.36-48h32C292.152,48,315.456,68.904,319.36,96z M144,126.568c4.056,5.632,9.48,10.84,16,15.536    v2.704c-5.944,1.216-11.328,3.856-16,7.392V126.568z M144,184c0-10.416,6.712-19.216,16-22.528V200    c0,2.24,0.168,4.432,0.336,6.632C150.872,203.416,144,194.536,144,184z M153.928,112H342.08c-9.376,20.64-52.744,40-94.072,40    S163.304,132.64,153.928,112z M176,200v-48.392C196.856,161.864,223.088,168,248,168s51.144-6.136,72-16.392V200    c0,39.696-32.304,72-72,72S176,239.696,176,200z M288,278.288v15.024c-4.048,4.92-17.376,18.688-40,18.688    c-22.672,0-35.928-13.712-40-18.688v-15.024c12.016,6.16,25.592,9.712,40,9.712S275.984,284.448,288,278.288z M197.192,305.256    C204.288,313.176,221.144,328,248,328s43.712-14.824,50.808-22.744l8.304,2.592L248,386.664l-59.112-78.816L197.192,305.256z     M128,480H80v-48h48V480z M240,480h-96V368h-16v48H80v-44.712c0-17.592,11.28-32.936,28.072-38.176l64.616-20.192l61.68,82.248    C223.552,400.304,216,411.248,216,424c0,14.88,10.216,27.424,24,30.992V480z M264,480h-8v-40h-8c-8.824,0-16-7.176-16-16    c0-8.824,7.176-16,16-16c8.824,0,16,7.176,16,16V480z M416,480h-48v-48h48V480z M416,416h-48v-48h-16v112h-72v-56    c0-12.752-7.552-23.696-18.376-28.84l61.68-82.248l64.616,20.192c16.8,5.248,28.08,20.592,28.08,38.184V416z"/>
    </g>
</g>
<g>
    <g>
        <circle cx="288" cy="176" r="8"/>
    </g>
</g>
<g>
    <g>
        <circle cx="208" cy="176" r="8"/>
    </g>
</g>
<g>
    <g>
        <path d="M264,216c0,8.824-7.176,16-16,16c-8.824,0-16-7.176-16-16h-16c0,17.648,14.352,32,32,32s32-14.352,32-32H264z"/>
    </g>
</g>
<g>
    <g>
        <rect x="240" y="64" width="16" height="16"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
    }
    if( $tipo == 'jardinero' ){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M10.138,480H10c-5.522,0-10,4.477-10,10s4.478,10,10,10h0.138c5.522,0,10-4.477,10-10S15.66,480,10.138,480z"/>
    </g>
</g>
<g>
    <g>
        <path d="M480.581,350.419c-0.146-0.05-0.292-0.098-0.439-0.142l-82.738-24.547c-2.23-0.826-3.796-2.364-3.983-3.861v-24.536    c0-0.33-0.018-0.656-0.049-0.978l31.542-18.086c0.182-0.104,0.36-0.215,0.536-0.331c14.477-9.561,23.985-26.586,25.435-45.543    c0.02-0.254,0.029-0.508,0.029-0.763v-10.37c14.64-4.325,25.354-17.892,25.354-33.915c0-8.341-2.908-16.013-7.757-22.066    c4.485,1.421,8.91,2.902,13.229,4.479c1.122,0.41,2.281,0.607,3.429,0.607c3.06,0,6.028-1.407,7.959-3.943    c2.654-3.487,2.729-8.297,0.184-11.865l-46.285-64.876V41.231c0-4.146-2.559-7.863-6.433-9.342    C438.466,31.077,387.94,12,339.038,12c-48.902,0-99.429,19.077-101.556,19.889c-3.873,1.479-6.433,5.196-6.433,9.342v48.467    l-46.075,64.882c-2.535,3.57-2.453,8.375,0.203,11.855c2.656,3.482,7.269,4.83,11.381,3.328c4.223-1.542,8.549-2.991,12.932-4.384    c-4.804,6.038-7.683,13.673-7.683,21.97c0,16.023,10.715,29.591,25.355,33.915v10.37c0,0.254,0.01,0.508,0.029,0.762    c1.454,19.037,10.895,36.063,25.252,45.544c0.179,0.118,0.361,0.23,0.548,0.337l31.906,18.24c-0.021,0.27-0.041,0.541-0.041,0.817    v20.07c-0.102,0.572-0.164,1.158-0.164,1.759v2.412c0,1.585-1.645,3.276-4.021,4.156l-82.811,24.546    c-0.148,0.044-0.297,0.092-0.443,0.143C178.92,356.852,166,374.252,166,392.732V480h-64v-98h19c19.299,0,35-15.701,35-35V209.958    c0-5.523-4.478-10-10-10c-5.522,0-10,4.477-10,10V347c0,8.271-6.729,15-15,15h-10.287V209.031c0-5.523-4.478-10-10-10    c-5.522,0-10,4.477-10,10V362H65.425V209.031c0-5.523-4.478-10-10-10c-5.522,0-10,4.477-10,10V362H35c-8.271,0-15-6.729-15-15    V209.958c0-5.523-4.478-10-10-10s-10,4.477-10,10V347c0,19.299,15.701,35,35,35h19v98H39.985c-5.522,0-10,4.477-10,10    s4.478,10,10,10H502c5.522,0,10-4.477,10-10c0-1.187,0-97.268,0-97.268C512,374.252,499.08,356.852,480.581,350.419z M82,480h-8    v-98h8V480z M251.05,48.31C265.792,43.357,303.475,32,339.038,32c35.486,0,73.23,11.364,87.987,16.315v32.011    c-16.019-3.191-46.719-7.808-90.324-7.808c-42.198,0-70.506,4.345-85.651,7.548V48.31z M247.175,101.534    c9.055-2.393,38.72-9.016,89.526-9.016c51.354,0,84.291,6.768,94.308,9.154l28.646,40.153    c-36.776-9.957-77.852-15.156-120.507-15.156c-42.694,0-83.803,5.208-120.605,15.183L247.175,101.534z M263.217,261.084    c-9.014-6.081-14.99-17.195-16.054-29.849v-18.531c0-5.523-4.478-10-10-10c-8.467,0-15.355-6.888-15.355-15.355    s6.889-15.355,15.355-15.355c5.332,0,9.727-4.184,9.988-9.509l0.34-6.928c28.736-5.85,59.697-8.887,91.657-8.887    c32,0,62.998,3.044,91.765,8.908v6.416c0,5.523,4.478,10,10,10c8.467,0,15.354,6.888,15.354,15.355s-6.888,15.355-15.354,15.355    c-5.522,0-10,4.477-10,10v18.529c-1.06,12.582-7.106,23.697-16.241,29.854l-60.818,34.872c-0.182,0.105-0.361,0.215-0.536,0.331    c-8.942,5.905-19.617,5.904-28.558,0c-0.179-0.118-0.361-0.231-0.548-0.337L263.217,261.084z M373.419,322.461v6.463    l-34.236,34.082l-34.326-34.089v-20.99l9.165,5.238c7.731,5.03,16.373,7.543,25.017,7.543c8.645,0,17.289-2.515,25.021-7.547    l9.359-5.366v10.633c-0.018,0.244-0.037,0.487-0.037,0.735v2.412C373.382,321.873,373.407,322.166,373.419,322.461z M212.958,480    H186v-87.268c0-9.723,7.632-19.725,17.787-23.352l9.171-2.718V480z M445,480h-11.694v-70.355c0-5.523-4.478-10-10-10    c-5.522,0-10,4.477-10,10V480H264.652v-70.355c0-5.523-4.478-10-10-10c-5.522,0-10,4.477-10,10V480h-11.694V360.734l53.661-15.906    c0.149-0.044,0.297-0.092,0.443-0.143c1.292-0.449,2.525-0.98,3.701-1.578l41.382,41.095c1.95,1.937,4.498,2.904,7.047,2.904    c2.552,0,5.104-0.971,7.055-2.913l41.206-41.02c1.135,0.569,2.319,1.08,3.561,1.512c0.146,0.051,0.292,0.098,0.439,0.142    L445,360.713V480z M492,480h-27V366.646l9.213,2.733c10.154,3.628,17.787,13.63,17.787,23.353V480z"/>
    </g>
</g>
<g>
    <g>
        <path d="M380.319,177.349c-6.249,0-11.333,5.084-11.333,11.333s5.084,11.333,11.333,11.333c6.249,0,11.333-5.084,11.333-11.333    C391.652,182.433,386.568,177.349,380.319,177.349z"/>
    </g>
</g>
<g>
    <g>
        <path d="M297.757,177.349c-6.249,0-11.333,5.084-11.333,11.333s5.084,11.333,11.333,11.333c6.249,0,11.333-5.084,11.333-11.333    S304.006,177.349,297.757,177.349z"/>
    </g>
</g>
<g>
    <g>
        <path d="M254.652,369.568c-5.522,0-10,4.477-10,10v0.141c0,5.523,4.478,10,10,10c5.522,0,10-4.477,10-10v-0.141    C264.652,374.045,260.174,369.568,254.652,369.568z"/>
    </g>
</g>
<g>
    <g>
        <path d="M423.306,369.568c-5.522,0-10,4.477-10,10v0.141c0,5.523,4.477,10,10,10c5.522,0,10-4.477,10-10v-0.141    C433.306,374.045,428.828,369.568,423.306,369.568z"/>
    </g>
</g>
<g>
    <g>
        <path d="M356.648,235.28c-3.409-4.012-9.307-4.663-13.496-1.613c-0.333,0.153-1.634,0.667-4.113,0.667    c-2.373,0-3.617-0.469-3.956-0.62c-4.14-3.063-10.002-2.49-13.457,1.448c-3.644,4.151-3.231,10.469,0.919,14.112    c1.353,1.187,6.539,5.06,16.494,5.06c9.87-0.001,15.101-3.795,16.468-4.958C359.715,245.798,360.226,239.487,356.648,235.28z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
    }
    if( $tipo == 'babysitter' ){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M298.102,479.277c-5.124-2.055-10.948,0.435-13.003,5.562c-1.717,4.283-6.071,7.161-10.833,7.161h-36.532    c-4.762,0-9.116-2.878-10.833-7.161c-2.055-5.127-7.876-7.612-13.003-5.562c-5.126,2.056-7.616,7.877-5.561,13.003    c4.803,11.98,16.341,19.72,29.397,19.72h36.532c13.056,0,24.594-7.74,29.396-19.72    C305.718,487.154,303.228,481.333,298.102,479.277z"/>
    </g>
</g>
<g>
    <g>
        <path d="M415.541,427.762c-2.716-14.004-11.344-26.128-23.677-33.288c2.243-1.832,4.347-3.858,6.267-6.089    c9.471-11.003,13.665-25.548,11.504-39.903c-3.509-23.324-13.436-45.117-28.707-63.024    c-11.434-13.407-25.488-24.211-41.217-31.803c19.249-20.534,31.057-48.124,31.057-78.423    c-0.001-52.434-35.348-96.769-83.465-110.428c6.554-7.825,10.13-17.601,10.13-27.931C297.434,16.541,280.892,0,260.561,0    c-0.001,0-0.002,0-0.003,0c-8.207,0-15.894,2.669-21.643,7.514c-6.354,5.354-9.853,12.879-9.854,21.188c0,5.522,4.477,10,10,10    c5.522,0,10-4.477,10-10c0-2.379,0.922-4.362,2.742-5.896c2.148-1.81,5.257-2.807,8.756-2.807h0.001    c9.304,0,16.874,7.568,16.874,16.872c0,6.302-2.454,12.226-6.91,16.682c-4.456,4.455-10.379,6.909-16.679,6.909    c-0.001,0-0.001,0-0.002,0c-0.245,0-0.485,0.019-0.725,0.037c-61.956,1.536-111.885,52.413-111.886,114.732    c0,30.299,11.808,57.889,31.057,78.423c-15.728,7.592-29.782,18.395-41.216,31.803c-15.271,17.907-25.198,39.7-28.708,63.023    c-2.16,14.356,2.033,28.901,11.504,39.904c1.92,2.231,4.024,4.258,6.267,6.089c-12.333,7.16-20.962,19.284-23.677,33.288    c-2.723,14.04,0.771,28.553,9.584,39.816l21.415,27.365c8.652,11.056,21.795,17.001,35.126,17.001    c7.568,0,15.198-1.916,22.1-5.901c19.061-11.005,27.229-34.697,19-55.109l-12.991-32.228c-0.868-2.153-1.894-4.218-3.039-6.202    H261c5.523,0,10-4.478,10-10c0-5.522-4.477-10-10-10h-92.356v-21.081c0-2.715,2.208-4.923,4.923-4.923h164.867    c2.714,0,4.923,2.208,4.923,4.923v22.464c-9.922,5.439-17.755,14.163-22.051,24.819l-12.991,32.227    c-8.229,20.413-0.061,44.105,19,55.11c6.903,3.985,14.53,5.901,22.1,5.901c13.33,0,26.475-5.946,35.127-17.001l21.414-27.365    C414.771,456.314,418.264,441.802,415.541,427.762z M161.231,175.232c0.001-52.255,42.515-94.768,94.769-94.769    c52.254,0.001,94.768,42.514,94.769,94.769c0,52.255-42.513,94.768-94.769,94.769C203.745,270,161.231,227.487,161.231,175.232z     M172.146,426.183l12.991,32.229c4.525,11.227,0.031,24.258-10.452,30.311c-10.482,6.053-24.015,3.43-31.476-6.105l-21.415-27.365    c-5.243-6.699-7.321-15.331-5.701-23.683c1.62-8.352,6.775-15.582,14.143-19.836c7.367-4.254,16.206-5.101,24.249-2.33    C162.528,412.177,168.965,418.294,172.146,426.183z M338.5,326.5c-5.523,0-10,4.478-10,10v10H183.566v-10c0-5.522-4.477-10-10-10    c-5.523,0-10,4.478-10,10v12.101c-8.777,3.861-14.923,12.635-14.923,22.822v14.121c-7.573-0.757-14.6-4.379-19.617-10.207    c-5.667-6.585-8.176-15.289-6.884-23.88c5.725-38.053,31.097-69.494,66.716-83.206C207.75,281.925,230.948,290,256,290    s48.25-8.075,67.142-21.749c35.619,13.712,60.991,45.153,66.716,83.206c1.292,8.591-1.217,17.295-6.885,23.88    c-5.016,5.828-12.044,9.45-19.617,10.207v-14.121c0-10.161-6.115-18.916-14.856-22.792V336.5    C348.5,330.978,344.023,326.5,338.5,326.5z M390.206,455.252l-21.414,27.365c-7.461,9.533-20.992,12.157-31.476,6.105    c-10.484-6.053-14.977-19.084-10.451-30.313l12.991-32.228c3.181-7.89,9.618-14.006,17.661-16.779    c3.115-1.073,6.349-1.604,9.571-1.604c5.097,0,10.164,1.329,14.678,3.935c7.368,4.254,12.522,11.484,14.143,19.836    C397.527,439.921,395.449,448.553,390.206,455.252z"/>
    </g>
</g>
<g>
    <g>
        <path d="M307.79,395.43c-1.86-1.86-4.44-2.93-7.07-2.93s-5.21,1.069-7.07,2.93c-1.86,1.861-2.93,4.44-2.93,7.07    c0,2.64,1.07,5.21,2.93,7.069c1.86,1.87,4.44,2.931,7.07,2.931s5.21-1.061,7.07-2.931c1.86-1.859,2.93-4.439,2.93-7.069    S309.65,397.29,307.79,395.43z"/>
    </g>
</g>
<g>
    <g>
        <path d="M283.57,209.382c-4.29-3.478-10.587-2.817-14.065,1.472c-3.323,4.1-8.246,6.451-13.505,6.451    c-5.259,0-10.183-2.352-13.505-6.451c-3.477-4.291-9.774-4.948-14.065-1.472c-4.291,3.477-4.95,9.774-1.472,14.065    c7.138,8.807,17.724,13.857,29.042,13.857s21.905-5.051,29.042-13.857C288.52,219.156,287.861,212.859,283.57,209.382z"/>
    </g>
</g>
<g>
    <g>
        <path d="M230.731,168.16c-9.071-9.071-23.831-9.071-32.904,0c-3.905,3.905-3.906,10.236,0,14.142    c3.905,3.906,10.237,3.906,14.142,0.001c1.273-1.273,3.346-1.273,4.62,0c1.953,1.952,4.512,2.929,7.071,2.929    c2.559,0,5.119-0.977,7.071-2.929C234.636,178.398,234.636,172.066,230.731,168.16z"/>
    </g>
</g>
<g>
    <g>
        <path d="M314.173,168.159c-9.072-9.07-23.833-9.07-32.904,0c-3.905,3.905-3.905,10.237,0,14.143    c1.952,1.953,4.512,2.929,7.071,2.929c2.559,0,5.119-0.977,7.071-2.929c1.273-1.272,3.346-1.274,4.62,0    c3.905,3.906,10.237,3.905,14.142-0.001C318.078,178.396,318.078,172.065,314.173,168.159z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
    }
    if( $tipo == 'domestico' ){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M405.242,222.02v-39.033c0-30.266-9.53-58.345-25.739-81.407c0.179-2.133,0.276-4.298,0.276-6.469    c0-40.116-31.018-72.772-69.205-72.939C297.554,8.227,277.453,0,255.999,0c-21.454,0-41.555,8.227-54.575,22.171    c-38.188,0.167-69.205,32.823-69.205,72.939c0,2.145,0.095,4.284,0.27,6.394c-9.737,13.891-17.002,29.53-21.224,45.863    c-1.037,4.01,1.373,8.102,5.384,9.139c0.63,0.162,1.261,0.24,1.883,0.24c3.337,0,6.382-2.244,7.256-5.625    c9.521-36.827,35.962-68.182,70.73-83.873c3.776-1.704,5.455-6.146,3.751-9.921c-1.704-3.775-6.145-5.458-9.921-3.751    c-15.451,6.973-29.711,16.839-41.796,28.849c5.457-25.865,27.216-45.255,53.167-45.255c0.822,0,1.692,0.024,2.66,0.071    c2.373,0.106,4.657-0.896,6.164-2.729C220.584,22.294,237.576,15,255.999,15c18.423,0,35.416,7.294,45.455,19.512    c1.508,1.834,3.813,2.852,6.164,2.729c0.969-0.048,1.839-0.071,2.661-0.071c25.988,0,47.771,19.443,53.191,45.361    c-25.684-25.604-61.096-41.456-100.141-41.456h-14.66c-9.516,0-19.03,0.948-28.277,2.818c-4.06,0.821-6.686,4.778-5.864,8.838    c0.821,4.061,4.78,6.679,8.838,5.865c8.271-1.673,16.784-2.521,25.304-2.521h14.661c69.98,0,126.913,56.933,126.913,126.912v20.62    c-0.271-0.353-0.57-0.69-0.911-0.999l-16.181-14.689c-0.004-0.003-0.007-0.007-0.011-0.011l-0.519-0.47    c-22.204-20.163-50.954-31.268-80.952-31.268h-38.49c-2.453,0-4.879-0.154-7.269-0.455c-23.099-2.906-42.741-19.476-49.122-42.288    c-1.439-5.11-2.169-10.432-2.169-15.816v-7.28c0-4.143-3.358-7.5-7.5-7.5c-4.142,0-7.5,3.357-7.5,7.5v7.28    c0,4.922,0.493,9.806,1.456,14.577c-28.074,20.137-44.686,52.286-44.686,87.053v17.25h-10.55c-1.375,0-2.735,0.082-4.08,0.234    V182.99c0-1.116,0.009-2.213,0.038-3.319c0.105-4.141-3.166-7.583-7.307-7.688c-0.065-0.001-0.13-0.002-0.194-0.002    c-4.054,0-7.39,3.233-7.494,7.309c-0.031,1.233-0.042,2.455-0.042,3.7v39.018c-10.298,6.49-16.73,17.896-16.73,30.282    c0,12.399,6.44,23.814,16.73,30.294v45.066c0,24.166,12.048,46.095,31.94,58.989c-11.922,14.702-18.489,33.193-18.489,52.171    v65.69c0,4.143,3.358,7.5,7.5,7.5h19.07h218.44h19.07c4.143,0,7.5-3.357,7.5-7.5v-65.69c0-33.58-20.532-64.007-51.407-76.688    c-0.09-0.187-0.176-0.372-0.267-0.56c-1.953-3.987-5.515-6.891-9.771-7.965c-0.025-0.006-0.051-0.013-0.076-0.019    c-0.087-0.022-0.175-0.043-0.265-0.063c-4.626-1.105-9.301,0.031-12.804,2.844c-1.277-0.127-2.56-0.232-3.848-0.299    c-2.735-2.527-5.751-4.755-9.002-6.665v-9.29c24.081-10.638,43.918-28.865,56.593-52.005h25.217c1.381,0,2.742-0.087,4.083-0.24    v40.287c0,11.895-3.695,23.237-10.686,32.801c-2.444,3.344-1.715,8.036,1.629,10.48c1.335,0.976,2.884,1.446,4.42,1.446    c2.312,0,4.592-1.065,6.061-3.075c8.882-12.15,13.576-26.554,13.576-41.652V282.57c10.038-6.351,16.724-17.546,16.724-30.28    C421.969,239.56,415.283,228.368,405.242,222.02z M105.029,252.289c0-8.178,4.827-15.627,12.325-18.988    c2.652-1.201,5.507-1.811,8.485-1.811h10.553c0.013,1.961,0.076,3.913,0.181,5.855c0.017,0.308,0.038,0.615,0.057,0.923    c0.047,0.764,0.101,1.527,0.162,2.288c0.027,0.331,0.053,0.662,0.083,0.993c0.075,0.842,0.159,1.681,0.252,2.519    c0.023,0.206,0.042,0.412,0.065,0.617c0.12,1.037,0.254,2.071,0.401,3.102c0.036,0.251,0.077,0.502,0.114,0.753    c0.117,0.788,0.241,1.574,0.374,2.359c0.056,0.328,0.113,0.656,0.171,0.983c0.136,0.761,0.279,1.519,0.43,2.276    c0.055,0.276,0.107,0.552,0.163,0.827c0.209,1.017,0.43,2.031,0.666,3.041c0.032,0.138,0.068,0.276,0.101,0.414    c0.207,0.873,0.425,1.743,0.652,2.611c0.082,0.312,0.167,0.622,0.251,0.933c0.195,0.722,0.398,1.441,0.607,2.159    c0.09,0.308,0.178,0.615,0.27,0.922c0.268,0.89,0.545,1.778,0.833,2.663c0.034,0.103,0.065,0.207,0.099,0.311    c0.324,0.982,0.664,1.96,1.014,2.935c0.103,0.286,0.21,0.571,0.315,0.856c0.156,0.423,0.306,0.847,0.467,1.269h-18.281    c-2.977,0-5.831-0.609-8.512-1.824C109.856,267.926,105.029,260.473,105.029,252.289z M121.759,327.65v-39.784    c1.345,0.152,2.705,0.234,4.08,0.234h25.225c12.202,22.258,31.151,40.081,54.085,50.864v12.09    c-2.245,1.505-4.358,3.179-6.339,4.997c-1.343,0.069-2.68,0.178-4.013,0.31c-3.544-2.849-8.257-3.976-12.894-2.833    c-0.117,0.029-0.232,0.061-0.348,0.095c-4.226,1.097-7.747,3.986-9.683,7.959c-0.092,0.19-0.182,0.381-0.274,0.571    c-3.652,1.495-7.216,3.254-10.621,5.267c-4.089,2.412-7.94,5.159-11.537,8.214C132.285,365.782,121.759,347.696,121.759,327.65z     M220.342,386.678c0.102,0.096,0.211,0.183,0.314,0.278c0.026,0.024,0.053,0.047,0.079,0.07c0.526,0.482,1.055,0.96,1.599,1.418    c0.038,0.032,0.078,0.061,0.116,0.093c0.137,0.115,0.28,0.223,0.418,0.337c0.141,0.115,0.283,0.229,0.426,0.343    c0.795,0.634,1.607,1.243,2.436,1.827c0.1,0.071,0.2,0.142,0.301,0.212c0.903,0.625,1.824,1.219,2.764,1.782    c0.038,0.022,0.074,0.046,0.112,0.069c0.841,0.5,1.697,0.973,2.566,1.423c0.082,0.042,0.162,0.086,0.244,0.128    c0.083,0.042,0.163,0.087,0.246,0.129c-11.919,5.422-27.892,7.617-33.996,3.253c-5.473-3.911,0.572-16.263,3.427-21.395    c1.613-2.903,3.587-5.564,5.865-7.929c0.005-0.006,0.01-0.009,0.014-0.013l4.099,6.856c0.536,0.897,1.102,1.769,1.686,2.625    c0.076,0.111,0.151,0.223,0.228,0.334c0.153,0.22,0.318,0.428,0.474,0.645c0.359,0.501,0.72,1,1.096,1.485    c0.179,0.233,0.359,0.464,0.542,0.693c0.151,0.188,0.308,0.368,0.461,0.553c0.296,0.358,0.594,0.714,0.899,1.064    c0.311,0.357,0.625,0.711,0.945,1.058c0.11,0.119,0.219,0.237,0.33,0.355c0.232,0.246,0.469,0.485,0.705,0.725    c0.433,0.443,0.87,0.881,1.318,1.307C220.152,386.494,220.246,386.588,220.342,386.678z M220.149,361.002v-5.771v-10.402    c0.083,0.026,0.167,0.047,0.249,0.073c1.273,0.396,2.552,0.772,3.836,1.125c0.255,0.07,0.511,0.136,0.766,0.204    c1.136,0.304,2.276,0.592,3.42,0.863c0.226,0.053,0.45,0.11,0.676,0.162c1.289,0.296,2.583,0.569,3.881,0.822    c0.293,0.057,0.587,0.11,0.881,0.165c1.167,0.218,2.337,0.421,3.511,0.604c0.19,0.03,0.379,0.063,0.57,0.092    c1.311,0.198,2.627,0.37,3.946,0.525c0.314,0.037,0.629,0.07,0.944,0.105c1.24,0.135,2.483,0.255,3.73,0.352    c0.117,0.009,0.233,0.021,0.349,0.03c1.338,0.1,2.682,0.171,4.028,0.227c0.324,0.013,0.649,0.025,0.974,0.036    c1.36,0.045,2.722,0.076,4.089,0.076c1.266,0,2.528-0.027,3.789-0.066c0.309-0.009,0.617-0.02,0.925-0.032    c1.255-0.048,2.507-0.11,3.756-0.197c0.053-0.004,0.106-0.009,0.159-0.013c1.206-0.086,2.407-0.195,3.607-0.316    c0.301-0.031,0.602-0.061,0.902-0.094c1.238-0.135,2.474-0.284,3.706-0.457c0.095-0.013,0.189-0.03,0.283-0.043    c1.156-0.166,2.309-0.352,3.459-0.551c0.289-0.05,0.578-0.1,0.866-0.153c1.223-0.221,2.443-0.458,3.658-0.717    c0.119-0.025,0.237-0.054,0.356-0.08c1.122-0.244,2.241-0.507,3.356-0.783c0.272-0.068,0.545-0.134,0.817-0.204    c1.207-0.308,2.411-0.632,3.609-0.978c0.034-0.01,0.068-0.018,0.102-0.028v8.322v7.103l-0.476,0.797l-3.62,6.062    c-0.014,0.023-0.029,0.045-0.043,0.069c-0.364,0.605-0.744,1.195-1.138,1.773c-0.103,0.152-0.212,0.298-0.317,0.448    c-0.334,0.474-0.675,0.941-1.028,1.396c-0.087,0.112-0.175,0.223-0.263,0.333c-0.436,0.546-0.882,1.082-1.345,1.599    c-0.001,0.001-0.003,0.003-0.004,0.004c-3.554,3.957-7.906,7.015-12.75,9.009c-0.16,0.065-0.323,0.126-0.484,0.189    c-0.439,0.173-0.882,0.336-1.329,0.491c-0.336,0.116-0.674,0.228-1.015,0.334c-0.246,0.078-0.494,0.149-0.742,0.221    c-0.465,0.134-0.931,0.266-1.404,0.381c-0.027,0.007-0.054,0.012-0.081,0.019c-0.594,0.143-1.194,0.274-1.8,0.389    c-0.386,0.074-0.772,0.138-1.16,0.199c-0.125,0.02-0.25,0.043-0.376,0.061c-0.369,0.055-0.738,0.1-1.108,0.143    c-0.407,0.046-0.816,0.086-1.226,0.118c-0.332,0.027-0.664,0.049-0.997,0.066c-0.487,0.025-0.975,0.041-1.466,0.047    c-0.25,0.003-0.5,0.006-0.75,0.003c-0.579-0.005-1.156-0.023-1.73-0.055c-0.348-0.02-0.696-0.051-1.044-0.081    c-0.243-0.021-0.486-0.045-0.728-0.07c-0.383-0.042-0.766-0.083-1.148-0.137c-1.936-0.265-3.826-0.69-5.658-1.257    c-0.076-0.024-0.153-0.044-0.229-0.069c-0.519-0.164-1.031-0.346-1.54-0.533c-0.145-0.053-0.29-0.105-0.435-0.161    c-0.482-0.185-0.959-0.382-1.431-0.588c-0.159-0.069-0.317-0.14-0.474-0.211c-0.466-0.211-0.929-0.431-1.385-0.662    c-0.143-0.073-0.284-0.149-0.426-0.224c-0.469-0.246-0.935-0.498-1.393-0.765c-0.079-0.046-0.156-0.096-0.235-0.143    c-1.517-0.902-2.964-1.921-4.331-3.044c-0.107-0.088-0.217-0.17-0.322-0.259c-0.344-0.29-0.678-0.594-1.011-0.897    c-0.196-0.178-0.393-0.354-0.585-0.537c-0.294-0.279-0.579-0.567-0.864-0.857c-0.223-0.228-0.445-0.457-0.663-0.692    c-0.254-0.273-0.504-0.55-0.75-0.832c-0.242-0.278-0.479-0.561-0.713-0.847c-0.214-0.26-0.427-0.52-0.634-0.788    c-0.27-0.35-0.53-0.71-0.789-1.071c-0.16-0.223-0.324-0.441-0.479-0.668c-0.409-0.6-0.806-1.211-1.182-1.841L220.149,361.002z     M153.098,412.441c-0.444,1.629-0.878,3.269-1.303,4.921c-0.134,0.522-0.271,1.04-0.403,1.565    c-0.524,2.078-1.035,4.172-1.529,6.286c-0.1,0.429-0.196,0.864-0.295,1.294c-0.405,1.76-0.799,3.533-1.183,5.316    c-0.129,0.602-0.259,1.203-0.386,1.807c-0.459,2.185-0.908,4.381-1.336,6.601c-0.467,2.427-0.911,4.871-1.337,7.327    c-0.139,0.801-0.266,1.61-0.4,2.413c-0.279,1.664-0.555,3.329-0.814,5.005c-0.149,0.961-0.287,1.928-0.43,2.893    c-0.227,1.536-0.451,3.074-0.663,4.619c-0.14,1.022-0.272,2.048-0.405,3.074c-0.195,1.507-0.385,3.016-0.565,4.53    c-0.125,1.046-0.245,2.094-0.362,3.144c-0.17,1.519-0.33,3.041-0.485,4.567c-0.105,1.042-0.211,2.083-0.309,3.127    c-0.149,1.576-0.285,3.158-0.417,4.741c-0.083,0.994-0.17,1.985-0.247,2.981c-0.134,1.735-0.251,3.475-0.366,5.218    c-0.056,0.849-0.119,1.695-0.17,2.545c-0.012,0.194-0.027,0.387-0.039,0.581h-4.445V438.81h0c0-15.757,5.516-31.106,15.529-43.219    c3.339-4.036,7.093-7.645,11.218-10.786c-0.079,0.21-0.152,0.427-0.23,0.637c-0.693,1.868-1.376,3.75-2.04,5.657    c-0.082,0.235-0.16,0.475-0.241,0.71c-0.591,1.71-1.168,3.438-1.735,5.178c-0.185,0.567-0.368,1.136-0.55,1.706    c-0.538,1.686-1.067,3.384-1.583,5.097c-0.096,0.318-0.196,0.632-0.291,0.952c-0.595,1.995-1.172,4.012-1.736,6.044    C153.397,411.335,153.249,411.889,153.098,412.441z M376.789,438.81V497h-4.444c-0.011-0.195-0.027-0.389-0.039-0.585    c-0.052-0.868-0.117-1.731-0.174-2.597c-0.114-1.732-0.231-3.464-0.364-5.188c-0.077-1.002-0.165-1.998-0.248-2.997    c-0.133-1.585-0.269-3.168-0.418-4.746c-0.099-1.049-0.205-2.094-0.311-3.14c-0.155-1.526-0.316-3.049-0.486-4.568    c-0.118-1.055-0.239-2.107-0.365-3.158c-0.181-1.515-0.371-3.024-0.566-4.531c-0.134-1.031-0.267-2.061-0.408-3.088    c-0.212-1.545-0.437-3.083-0.665-4.619c-0.144-0.969-0.283-1.941-0.433-2.907c-0.26-1.671-0.536-3.332-0.815-4.991    c-0.137-0.813-0.265-1.63-0.406-2.44c-0.428-2.459-0.874-4.905-1.343-7.334c-0.427-2.217-0.876-4.41-1.335-6.593    c-0.125-0.594-0.252-1.185-0.38-1.776c-0.386-1.795-0.783-3.579-1.191-5.35c-0.095-0.414-0.187-0.832-0.284-1.245    c-0.496-2.122-1.009-4.224-1.536-6.311c-0.125-0.495-0.254-0.985-0.381-1.478c-0.431-1.678-0.873-3.344-1.324-4.999    c-0.145-0.532-0.289-1.066-0.436-1.596c-0.568-2.043-1.147-4.072-1.746-6.078c-0.081-0.271-0.166-0.538-0.247-0.808    c-0.529-1.758-1.071-3.5-1.624-5.229c-0.176-0.55-0.352-1.099-0.53-1.646c-0.568-1.746-1.147-3.478-1.738-5.193    c-0.081-0.233-0.158-0.47-0.239-0.703c-0.666-1.914-1.35-3.804-2.045-5.679c-0.073-0.197-0.142-0.401-0.215-0.598    C366.557,397.469,376.789,417.353,376.789,438.81z M326.633,368.13l-0.001,0.003c0.494,1.016,0.978,2.032,1.466,3.076    c3.046,6.5,5.879,13.326,8.492,20.458c5.748,15.692,10.432,32.859,13.984,51.286c0.05,0.257,0.094,0.517,0.143,0.774    c0.394,2.059,0.774,4.128,1.137,6.21c0.149,0.855,0.286,1.719,0.43,2.578c0.254,1.515,0.506,3.031,0.744,4.557    c0.151,0.968,0.292,1.942,0.436,2.914c0.213,1.434,0.422,2.871,0.62,4.314c0.138,1.007,0.271,2.017,0.403,3.028    c0.187,1.435,0.366,2.874,0.539,4.317c0.12,1.006,0.239,2.013,0.352,3.022c0.167,1.489,0.324,2.984,0.477,4.48    c0.098,0.964,0.199,1.926,0.29,2.892c0.155,1.632,0.295,3.271,0.432,4.911c0.07,0.836,0.147,1.67,0.212,2.508    c0.175,2.251,0.333,4.509,0.475,6.773c0.014,0.229,0.033,0.456,0.047,0.685c0.002,0.028,0.003,0.055,0.004,0.083H154.682    c0.001-0.022,0.002-0.045,0.004-0.068c0.013-0.215,0.031-0.429,0.044-0.644c0.142-2.275,0.301-4.543,0.477-6.805    c0.064-0.825,0.14-1.646,0.208-2.469c0.138-1.649,0.279-3.297,0.435-4.939c0.09-0.95,0.189-1.896,0.285-2.844    c0.154-1.515,0.312-3.027,0.481-4.534c0.11-0.982,0.225-1.962,0.342-2.942c0.176-1.475,0.359-2.946,0.55-4.413    c0.126-0.972,0.253-1.944,0.386-2.912c0.205-1.494,0.421-2.982,0.641-4.467c0.136-0.917,0.269-1.835,0.41-2.749    c0.251-1.615,0.516-3.219,0.784-4.822c0.128-0.766,0.25-1.536,0.382-2.298c0.409-2.355,0.834-4.7,1.282-7.027    c5.17-26.85,12.742-51.018,22.533-71.892c0.466-1.019,0.952-2.037,1.395-3.033c0.024-0.006,0.049-0.012,0.074-0.019    c-0.196,0.544-0.386,1.099-0.579,1.647c-0.302,0.853-0.606,1.702-0.902,2.565c-0.219,0.639-0.432,1.291-0.649,1.935    c-0.267,0.795-0.537,1.584-0.799,2.387c-0.22,0.673-0.433,1.358-0.649,2.037c-0.252,0.788-0.507,1.571-0.754,2.367    c-0.211,0.679-0.415,1.37-0.623,2.054c-0.245,0.808-0.494,1.611-0.735,2.426c-0.2,0.677-0.393,1.367-0.59,2.049    c-0.241,0.836-0.486,1.666-0.722,2.509c-0.179,0.639-0.352,1.289-0.529,1.933c-0.247,0.899-0.497,1.794-0.739,2.702    c-0.174,0.653-0.341,1.317-0.512,1.975c-0.237,0.908-0.476,1.812-0.707,2.729c-0.173,0.684-0.338,1.378-0.508,2.066    c-0.223,0.905-0.449,1.805-0.667,2.717c-0.158,0.659-0.308,1.329-0.463,1.992c-0.222,0.951-0.447,1.898-0.664,2.857    c-0.135,0.599-0.264,1.207-0.397,1.809c-0.23,1.039-0.462,2.074-0.685,3.123c-0.076,0.356-0.148,0.718-0.223,1.075    c-0.274,1.3-0.546,2.601-0.81,3.915c-0.077,0.383-0.149,0.772-0.225,1.155c-0.59,2.98-1.16,5.989-1.7,9.04    c-0.022,0.127-0.043,0.256-0.066,0.383c-0.888,5.04-1.711,10.174-2.462,15.401c-0.57,3.969,2.082,7.685,6.021,8.434    c1.697,0.323,3.396,0.636,5.096,0.939c0.401,0.072,0.803,0.137,1.204,0.207c1.296,0.227,2.592,0.454,3.889,0.669    c0.654,0.108,1.308,0.209,1.963,0.314c1.048,0.169,2.095,0.341,3.144,0.503c0.689,0.106,1.38,0.203,2.07,0.306    c1.015,0.151,2.029,0.306,3.045,0.45c0.698,0.099,1.397,0.189,2.095,0.285c1.009,0.138,2.017,0.279,3.026,0.41    c0.723,0.094,1.447,0.179,2.171,0.269c0.984,0.123,1.969,0.249,2.954,0.365c0.759,0.09,1.519,0.17,2.279,0.255    c0.951,0.107,1.9,0.218,2.852,0.318c0.787,0.083,1.576,0.158,2.364,0.237c0.924,0.093,1.848,0.189,2.773,0.276    c0.822,0.077,1.645,0.145,2.468,0.218c0.89,0.079,1.78,0.161,2.67,0.234c0.849,0.07,1.698,0.13,2.547,0.195    c0.867,0.066,1.734,0.136,2.602,0.197c0.854,0.06,1.71,0.111,2.564,0.166c0.863,0.055,1.726,0.115,2.59,0.165    c0.856,0.05,1.713,0.091,2.569,0.136c0.864,0.045,1.727,0.094,2.591,0.134c0.857,0.04,1.714,0.071,2.57,0.105    c0.865,0.035,1.729,0.074,2.595,0.104c0.866,0.03,1.732,0.051,2.598,0.076c0.855,0.025,1.711,0.053,2.567,0.073    c0.874,0.02,1.749,0.031,2.624,0.046c0.85,0.014,1.7,0.033,2.55,0.042c0.879,0.01,1.759,0.011,2.638,0.015    c0.767,0.004,1.533,0.013,2.3,0.013c0.078,0,0.155-0.001,0.233-0.001c0.946,0,1.893-0.01,2.84-0.017    c0.791-0.006,1.582-0.007,2.374-0.017c0.956-0.012,1.912-0.033,2.869-0.051c0.781-0.015,1.562-0.025,2.343-0.044    c0.966-0.023,1.932-0.056,2.899-0.086c0.774-0.024,1.547-0.043,2.322-0.071c0.965-0.035,1.931-0.079,2.896-0.12    c0.776-0.033,1.551-0.061,2.327-0.098c0.963-0.046,1.926-0.101,2.889-0.153c0.779-0.042,1.557-0.08,2.336-0.126    c0.976-0.058,1.953-0.126,2.93-0.19c0.764-0.05,1.528-0.096,2.293-0.15c0.975-0.069,1.949-0.148,2.924-0.224    c0.77-0.06,1.54-0.115,2.311-0.179c0.975-0.081,1.951-0.171,2.927-0.259c0.766-0.068,1.532-0.132,2.299-0.204    c0.995-0.094,1.99-0.198,2.985-0.299c0.749-0.076,1.498-0.147,2.247-0.226c0.999-0.106,1.999-0.222,2.998-0.335    c0.745-0.084,1.49-0.163,2.235-0.251c1.006-0.119,2.012-0.248,3.018-0.373c0.739-0.092,1.477-0.179,2.216-0.275    c1.021-0.133,2.042-0.275,3.063-0.415c0.724-0.099,1.447-0.192,2.17-0.294c1.039-0.147,2.078-0.305,3.117-0.459    c0.706-0.104,1.412-0.204,2.117-0.312c1.081-0.166,2.161-0.342,3.242-0.515c0.664-0.107,1.328-0.207,1.992-0.317    c1.192-0.197,2.383-0.404,3.574-0.61c0.553-0.095,1.106-0.186,1.658-0.283c1.744-0.308,3.488-0.627,5.232-0.955    c3.947-0.743,6.608-4.464,6.035-8.439c-2.016-13.998-4.534-27.343-7.529-39.962c-0.05-0.212-0.098-0.428-0.149-0.64    c-0.288-1.205-0.585-2.397-0.882-3.589c-0.098-0.392-0.192-0.791-0.29-1.182c-0.242-0.958-0.492-1.903-0.74-2.852    c-0.161-0.616-0.317-1.238-0.48-1.85c-0.2-0.75-0.407-1.489-0.611-2.233c-0.218-0.798-0.433-1.603-0.655-2.394    c-0.177-0.632-0.362-1.254-0.542-1.882c-0.255-0.891-0.508-1.786-0.769-2.668c-0.156-0.528-0.318-1.046-0.476-1.571    c-0.292-0.972-0.583-1.948-0.882-2.909c-0.12-0.386-0.244-0.763-0.365-1.147c-0.343-1.089-0.686-2.179-1.037-3.255    c-0.096-0.294-0.196-0.582-0.293-0.875c-0.381-1.157-0.764-2.312-1.156-3.453c-0.059-0.173-0.121-0.342-0.181-0.515    c-0.426-1.234-0.854-2.463-1.291-3.678C326.617,368.126,326.625,368.128,326.633,368.13z M322.615,410.334    c0.201-0.142,0.382-0.291,0.575-0.436c2.216,9.528,4.16,19.491,5.811,29.865c-48.995,8.422-98.051,8.487-146.038,0.199    c1.659-10.446,3.612-20.475,5.843-30.059c0.149,0.112,0.286,0.229,0.44,0.339c4.965,3.548,11.626,5.048,18.864,5.048    c15.48,0,33.587-6.865,43.33-15.232c0.175,0.011,0.354,0.008,0.53,0.017c0.826,0.046,1.652,0.074,2.478,0.079    c0.101,0.001,0.2,0.01,0.301,0.01c0.002,0,0,0,0.001,0c0.131,0,0.259-0.012,0.389-0.012c0.824-0.006,1.647-0.033,2.469-0.079    c0.417-0.023,0.831-0.056,1.245-0.089c0.49-0.04,0.978-0.087,1.466-0.141c6.467,5.706,16.575,10.677,27.537,13.394    c4.119,1.021,9.97,2.117,16.107,2.117C310.428,415.354,317.211,414.137,322.615,410.334z M310.255,376.029    c3.005,5.301,9.383,18.06,3.728,22.039c-6.315,4.442-23.224,1.879-35.196-3.898c0.075-0.04,0.146-0.087,0.22-0.128    c0.891-0.484,1.77-0.989,2.628-1.527c0.115-0.072,0.225-0.152,0.34-0.225c0.743-0.474,1.473-0.968,2.189-1.481    c0.267-0.191,0.528-0.39,0.791-0.587c0.57-0.426,1.132-0.864,1.684-1.315c0.263-0.215,0.525-0.429,0.784-0.649    c0.61-0.52,1.206-1.057,1.791-1.607c0.163-0.153,0.332-0.299,0.493-0.455c0.732-0.707,1.444-1.44,2.136-2.195    c0.181-0.198,0.354-0.405,0.532-0.606c0.512-0.577,1.013-1.164,1.501-1.767c0.154-0.191,0.312-0.378,0.464-0.572    c0.07-0.089,0.134-0.185,0.204-0.275c0.447-0.576,0.881-1.165,1.305-1.763c0.147-0.207,0.302-0.404,0.446-0.614    c0.051-0.074,0.098-0.152,0.148-0.227c0.581-0.855,1.147-1.725,1.684-2.622l4.942-8.27c0.001-0.001,0.002-0.003,0.002-0.004    l0.114-0.186C305.973,369.674,308.355,372.676,310.255,376.029z M360.612,223.994v6.689c0,4.094-0.229,8.143-0.687,12.142    c-1.372,11.996-4.795,23.535-10.226,34.433c-11.628,23.324-31.368,41.39-55.586,50.869c-12.131,4.753-24.954,7.163-38.113,7.163    c-3.509,0-6.991-0.17-10.441-0.51c-1.097-0.108-2.19-0.24-3.281-0.382c-8.966-1.17-17.696-3.494-26.085-6.953    c-0.213-0.088-0.427-0.17-0.639-0.259c0,0-0.001-0.001-0.002-0.001c-23.131-9.707-42.042-27.44-53.249-49.929    c-7.241-14.531-10.913-30.202-10.913-46.576v-6.689v-24.75c0-28.238,12.74-54.479,34.497-71.899    c0.129,0.292,0.27,0.576,0.402,0.865c0.164,0.358,0.328,0.716,0.498,1.071c0.252,0.526,0.512,1.046,0.776,1.565    c0.167,0.329,0.331,0.659,0.502,0.985c0.298,0.565,0.607,1.121,0.919,1.677c0.151,0.27,0.297,0.542,0.452,0.81    c0.444,0.768,0.902,1.528,1.373,2.278c0.03,0.047,0.057,0.096,0.087,0.144c0.506,0.803,1.029,1.592,1.564,2.373    c0.15,0.219,0.308,0.431,0.46,0.648c0.389,0.553,0.78,1.104,1.182,1.645c0.203,0.272,0.412,0.539,0.619,0.808    c0.366,0.477,0.734,0.953,1.111,1.42c0.228,0.283,0.461,0.562,0.694,0.842c0.369,0.445,0.742,0.886,1.121,1.321    c0.244,0.28,0.491,0.558,0.739,0.834c0.383,0.427,0.771,0.848,1.164,1.265c0.252,0.268,0.505,0.535,0.761,0.799    c0.408,0.421,0.823,0.835,1.241,1.245c0.25,0.245,0.497,0.493,0.75,0.735c0.455,0.435,0.919,0.861,1.385,1.284    c0.225,0.204,0.446,0.412,0.673,0.614c0.588,0.521,1.186,1.031,1.791,1.533c0.112,0.094,0.222,0.191,0.335,0.284    c0.729,0.598,1.469,1.183,2.22,1.753c0.155,0.117,0.314,0.228,0.469,0.344c0.591,0.441,1.185,0.877,1.789,1.301    c0.259,0.182,0.524,0.356,0.785,0.535c0.509,0.348,1.02,0.693,1.538,1.028c0.298,0.193,0.599,0.379,0.9,0.568    c0.493,0.309,0.988,0.614,1.488,0.912c0.317,0.189,0.637,0.373,0.957,0.557c0.495,0.285,0.994,0.564,1.496,0.836    c0.327,0.178,0.654,0.354,0.984,0.527c0.511,0.268,1.026,0.528,1.543,0.784c0.325,0.161,0.649,0.323,0.976,0.479    c0.547,0.261,1.099,0.511,1.652,0.758c0.303,0.136,0.605,0.276,0.91,0.407c0.638,0.275,1.283,0.536,1.929,0.793    c0.226,0.09,0.45,0.186,0.677,0.274c0.881,0.34,1.769,0.665,2.664,0.972c0.126,0.043,0.254,0.08,0.38,0.123    c0.764,0.257,1.532,0.505,2.306,0.738c0.308,0.092,0.619,0.175,0.928,0.264c0.599,0.171,1.198,0.34,1.802,0.497    c0.357,0.093,0.717,0.177,1.076,0.264c0.564,0.137,1.129,0.27,1.697,0.394c0.379,0.083,0.759,0.159,1.14,0.236    c0.556,0.112,1.114,0.218,1.674,0.317c0.388,0.069,0.777,0.135,1.167,0.197c0.562,0.09,1.127,0.172,1.692,0.249    c0.389,0.053,0.777,0.106,1.167,0.153c0.584,0.07,1.171,0.128,1.759,0.184c0.373,0.036,0.744,0.075,1.118,0.105    c0.646,0.051,1.294,0.088,1.943,0.122c0.318,0.017,0.634,0.041,0.953,0.053c0.969,0.039,1.94,0.061,2.916,0.061h38.49    c1.54,0,3.076,0.036,4.608,0.103c0.065,0.003,0.13,0.004,0.194,0.007c1.481,0.067,2.957,0.169,4.429,0.297    c0.114,0.01,0.228,0.017,0.342,0.028c1.434,0.13,2.862,0.293,4.285,0.481c0.148,0.02,0.296,0.036,0.444,0.057    c1.395,0.19,2.783,0.411,4.165,0.656c0.172,0.031,0.345,0.059,0.518,0.091c1.361,0.248,2.714,0.526,4.062,0.827    c0.189,0.042,0.379,0.083,0.568,0.126c1.332,0.306,2.657,0.639,3.975,0.996c0.197,0.053,0.395,0.106,0.592,0.161    c1.308,0.362,2.608,0.751,3.9,1.163c0.2,0.064,0.4,0.128,0.6,0.193c1.286,0.419,2.564,0.863,3.833,1.332    c0.198,0.073,0.395,0.147,0.592,0.221c1.268,0.477,2.527,0.977,3.775,1.503c0.19,0.08,0.379,0.161,0.568,0.242    c1.253,0.536,2.496,1.095,3.728,1.679c0.174,0.083,0.348,0.168,0.522,0.251c1.242,0.598,2.473,1.218,3.691,1.865    c0.154,0.082,0.308,0.166,0.462,0.249c1.232,0.662,2.454,1.346,3.661,2.058c0.13,0.077,0.258,0.156,0.387,0.233    c1.228,0.731,2.444,1.485,3.644,2.268c0.094,0.061,0.187,0.125,0.281,0.187c1.23,0.808,2.447,1.64,3.646,2.501    c0.051,0.037,0.102,0.075,0.153,0.112c2.492,1.798,4.915,3.709,7.26,5.734c0.018,0.016,0.037,0.031,0.056,0.047V223.994z     M375.609,210.409l3.638,3.303c1.437,1.306,3.242,1.948,5.04,1.948c2.041,0,4.074-0.828,5.555-2.458    c0.143-0.158,0.273-0.323,0.4-0.489v4.016c-1.341-0.153-2.702-0.24-4.083-0.24h-10.55V210.409z M386.159,273.101h-18.281    c0.156-0.41,0.303-0.822,0.454-1.233c0.111-0.303,0.225-0.605,0.335-0.909c0.346-0.964,0.682-1.931,1.002-2.902    c0.046-0.141,0.089-0.282,0.134-0.423c0.275-0.845,0.54-1.692,0.796-2.543c0.099-0.328,0.194-0.658,0.289-0.987    c0.201-0.691,0.396-1.384,0.585-2.079c0.091-0.334,0.182-0.667,0.269-1.001c0.215-0.823,0.421-1.649,0.619-2.478    c0.043-0.18,0.09-0.36,0.132-0.54c0.232-0.995,0.45-1.993,0.656-2.994c0.063-0.304,0.12-0.61,0.181-0.915    c0.143-0.723,0.281-1.448,0.41-2.174c0.063-0.353,0.125-0.705,0.184-1.058c0.127-0.748,0.245-1.498,0.357-2.25    c0.042-0.283,0.088-0.566,0.129-0.85c0.144-1.016,0.276-2.034,0.395-3.055c0.028-0.242,0.05-0.485,0.077-0.727    c0.087-0.799,0.168-1.601,0.24-2.404c0.031-0.354,0.06-0.707,0.089-1.061c0.059-0.73,0.11-1.463,0.156-2.196    c0.021-0.333,0.043-0.667,0.061-1.001c0.055-1.019,0.1-2.04,0.13-3.063c0.002-0.07,0.003-0.141,0.005-0.211    c0.023-0.85,0.039-1.701,0.044-2.555h10.553c11.475,0,20.81,9.331,20.81,20.8C406.969,263.765,397.634,273.101,386.159,273.101z"/>
    </g>
</g>
<g>
    <g>
        <path d="M287.936,260.31c-3.406-2.358-8.077-1.508-10.435,1.896c-4.891,7.063-12.928,11.278-21.501,11.278    s-16.611-4.216-21.501-11.278c-2.357-3.405-7.03-4.255-10.435-1.896c-3.405,2.357-4.254,7.03-1.897,10.435    c7.69,11.107,20.338,17.739,33.833,17.739c13.494,0,26.142-6.632,33.833-17.739C292.191,267.34,291.341,262.668,287.936,260.31z"/>
    </g>
</g>
<g>
    <g>
        <path d="M310.322,215.962c-4.142,0-7.5,3.357-7.5,7.5v2c0,4.143,3.358,7.5,7.5,7.5c4.143,0,7.5-3.357,7.5-7.5v-2    C317.822,219.319,314.465,215.962,310.322,215.962z"/>
    </g>
</g>
<g>
    <g>
        <path d="M201.676,215.962c-4.142,0-7.5,3.357-7.5,7.5v2c0,4.143,3.358,7.5,7.5,7.5c4.142,0,7.5-3.357,7.5-7.5v-2    C209.176,219.319,205.818,215.962,201.676,215.962z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
    }

    if( $tipo == 'mudanzas' ){
        $icono = '<svg id="Layer_1_1_" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m55.8 19.4c-.189-.252-.485-.4-.8-.4h-9v-7c0-.552-.448-1-1-1h-35v2h34v26h-9v-12h1c.433 0 .817-.279.951-.691s-.013-.863-.363-1.118l-2.588-1.882v-5.309c0-.552-.448-1-1-1h-4c-.552 0-1 .448-1 1v.945l-2.412-1.754c-.352-.255-.826-.255-1.177 0l-11 8c-.35.254-.497.706-.363 1.118.135.412.519.691.952.691h1v8h-4v2h4v2h-8v2h37v2h-40v-2c.552 0 1-.448 1-1v-27h3v-2h-4c-.552 0-1 .448-1 1v27c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h2.073c-.042.332-.073.666-.073 1 0 4.411 3.589 8 8 8s8-3.589 8-8c0-.334-.031-.668-.073-1h24.073.073c-.042.332-.073.666-.073 1 0 4.411 3.589 8 8 8s8-3.589 8-8c0-.334-.031-.668-.073-1h.073c.552 0 1-.448 1-1v-16c0-.216-.07-.427-.2-.6zm-30.8-.164 3.412 2.481c.305.222.708.253 1.042.083.335-.171.546-.515.546-.891v-1.909h2v4.818c0 .32.153.621.412.809l.513.373h-15.85zm-8 7.764h16v12h-4v-7c0-.552-.448-1-1-1h-6c-.552 0-1 .448-1 1v7h-4zm10 12h-4v-6h4zm-12 7c0 1.103-.897 2-2 2s-2-.897-2-2c0-.415.129-.743.284-1h3.432c.155.257.284.585.284 1zm4 0c0 3.309-2.691 6-6 6s-6-2.691-6-6c0-.334.037-.668.094-1h2.052c-.086.326-.146.659-.146 1 0 2.206 1.794 4 4 4s4-1.794 4-4c0-.341-.06-.674-.146-1h2.052c.057.332.094.666.094 1zm29-25v7c0 .552.448 1 1 1h6v-2h-5v-6h4.5l4.5 6h-2v2h3v14h-14v-22zm7 25c0 1.103-.897 2-2 2s-2-.897-2-2c0-.415.129-.743.284-1h3.432c.155.257.284.585.284 1zm4 0c0 3.309-2.691 6-6 6s-6-2.691-6-6c0-.334.037-.668.094-1h2.052c-.086.326-.146.659-.146 1 0 2.206 1.794 4 4 4s4-1.794 4-4c0-.341-.06-.674-.146-1h2.052c.057.332.094.666.094 1z"/><path d="m49 32h3v2h-3z"/><path d="m7 35h2v2h-2z"/></svg>';
    }
    if( $tipo == 'multi' ){
        $icono = '<svg id="Layer_3" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m36 40.673v-23.673c0-7.168-5.832-13-13-13h-6c-7.168 0-13 5.832-13 13v23.673c-.245.193-.483.396-.707.62-1.479 1.478-2.293 3.443-2.293 5.535v15.172c0 .553.448 1 1 1h36c.552 0 1-.447 1-1v-15.172c0-2.092-.814-4.058-2.293-5.535-.224-.224-.462-.427-.707-.62zm-30-23.673c0-6.065 4.935-11 11-11h6c6.065 0 11 4.935 11 11v22.532c-.891-.345-1.845-.532-2.829-.532h-6.171v-2.764c4.091-1.605 7-5.583 7-10.236v-8c0-.552-.448-1-1-1h-8.586l-2.707-2.707c-.391-.391-1.023-.391-1.414 0l-2.707 2.707h-6.586c-.552 0-1 .448-1 1v8c0 4.653 2.909 8.631 7 10.236v2.764h-6.171c-.984 0-1.938.187-2.829.532zm15 18h-2c-4.962 0-9-4.038-9-9v-7h6c.265 0 .52-.105.707-.293l2.293-2.293 2.293 2.293c.187.188.442.293.707.293h8v7c0 4.962-4.038 9-9 9zm-2 2h2c.684 0 1.35-.071 2-.191v2.777l-3 3-3-3v-2.777c.65.12 1.316.191 2 .191zm-3.414 4 3.127 3.127-2.491 3.736-2.745-6.863zm8.828 0h2.109l-2.745 6.863-2.491-3.736zm12.586 20h-4v-11h-2v11h-22v-11h-2v11h-4v-14.172c0-1.557.606-3.021 1.707-4.121 1.101-1.101 2.565-1.707 4.122-1.707h2.494l3.748 9.371c.138.345.456.585.825.623.035.004.07.006.104.006.332 0 .646-.165.832-.445l3.168-4.752 3.168 4.752c.186.28.5.445.832.445.034 0 .069-.002.104-.006.37-.038.687-.278.825-.623l3.748-9.371h2.494c1.557 0 3.021.606 4.122 1.707s1.707 2.564 1.707 4.121z"/><path d="m14 21h2v4h-2z"/><path d="m24 21h2v4h-2z"/><path d="m22 30c0 .551-.449 1-1 1h-2c-.551 0-1-.449-1-1v-1h-2v1c0 1.654 1.346 3 3 3h2c1.654 0 3-1.346 3-3v-1h-2z"/><path d="m46 28.829v5.171c0 .553.448 1 1 1h8c.552 0 1-.447 1-1v-5.171c0-1.557.606-3.021 1.707-4.122 1.479-1.478 2.293-3.444 2.293-5.536v-1.171c0-4.962-4.038-9-9-9s-9 4.038-9 9v1.171c0 2.091.814 4.057 2.293 5.536 1.101 1.101 1.707 2.564 1.707 4.122zm2 4.171v-2h6v2zm-4-15c0-3.86 3.14-7 7-7s7 3.14 7 7v1.171c0 1.557-.606 3.021-1.707 4.122-1.479 1.478-2.293 3.444-2.293 5.536v.171h-2v-10h2v-2h-6v2h2v10h-2v-.171c0-2.091-.814-4.057-2.293-5.536-1.101-1.101-1.707-2.564-1.707-4.122z"/><path d="m50 2h2v5h-2z"/><path d="m58.379 7.5h4.243v2h-4.243z" transform="matrix(.707 -.707 .707 .707 11.71 45.27)"/><path d="m40.5 6.379h2v4.243h-2z" transform="matrix(.707 -.707 .707 .707 6.145 31.835)"/></svg>';
    }

    if($tipo == 'cocinero'){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 436.807 436.807" style="enable-background:new 0 0 436.807 436.807;" xml:space="preserve">
<path id="XMLID_130_" d="M109.246,207.047c4.971,0,9,4.029,9,9s-4.029,9-9,9c-28.8,0-55.557-15.563-69.828-40.613
    c-2.46-4.319-0.953-9.814,3.365-12.275c4.32-2.461,9.814-0.954,12.275,3.365C66.136,194.968,86.898,207.047,109.246,207.047z
     M108.747,293.452v65.035c0,4.971,4.029,9,9,9s9-4.029,9-9v-65.035c0-4.971-4.029-9-9-9S108.747,288.481,108.747,293.452z
     M242.334,216.047c0,4.971,4.029,9,9,9c16.438,0,32.245-4.935,45.711-14.271c4.085-2.832,5.101-8.439,2.269-12.524
    c-2.832-4.086-8.44-5.102-12.524-2.269c-10.439,7.238-22.699,11.063-35.455,11.063C246.363,207.047,242.334,211.076,242.334,216.047
    z M289.092,283.602c-4.971,0-9,4.029-9,9v86.591c-1.76,1.876-9.404,6.48-29.197,10.428c-18.741,3.738-42.864,5.797-67.927,5.797
    c-25.062,0-49.185-2.059-67.926-5.797c-19.792-3.947-27.437-8.552-29.196-10.428V263.873c3.387,0.363,6.823,0.549,10.306,0.549
    c21.905,0,43.341-7.566,60.356-21.306c3.867-3.122,4.471-8.788,1.348-12.656c-3.122-3.866-8.788-4.474-12.656-1.348
    c-14.024,11.324-30.985,17.31-49.048,17.31C53.059,246.422,18,211.363,18,168.271c0-43.092,35.059-78.149,78.151-78.149
    c2.466,0,4.996,0.122,7.522,0.362c3.745,0.354,7.309-1.647,8.949-5.027c13.199-27.183,40.153-44.068,70.345-44.068
    c30.193,0,57.148,16.886,70.347,44.066c1.641,3.38,5.209,5.386,8.949,5.028c2.52-0.239,5.05-0.361,7.521-0.361c4.971,0,9-4.029,9-9
    s-4.029-9-9-9c-1.036,0-2.079,0.018-3.127,0.052c-7.73-13.642-18.691-25.215-31.979-33.708
    c-15.435-9.863-33.315-15.077-51.711-15.077c-18.394,0-36.274,5.214-51.709,15.079c-13.288,8.492-24.249,20.065-31.979,33.706
    c-1.049-0.034-2.093-0.052-3.128-0.052C43.134,72.121,0,115.254,0,168.271c0,43.171,28.599,79.788,67.846,91.9v119.956
    c0,11.597,12.454,20.085,38.074,25.949c20.684,4.733,48.046,7.341,77.048,7.341c29.003,0,56.366-2.607,77.05-7.341
    c25.62-5.864,38.074-14.353,38.074-25.949v-87.525C298.092,287.631,294.063,283.602,289.092,283.602z M387.606,62.163
    c-4.971,0-9,4.029-9,9v65.035c0,4.971,4.029,9,9,9s9-4.029,9-9V71.163C396.606,66.192,392.577,62.163,387.606,62.163z
     M319.396,228.659c-13.924,11.454-31.543,17.763-49.611,17.763c-18.062,0-35.022-5.985-49.049-17.311
    c-3.866-3.122-9.534-2.517-12.656,1.35c-3.122,3.867-2.519,9.533,1.35,12.656c17.017,13.738,38.451,21.305,60.355,21.305
    c22.229,0,43.909-7.764,61.047-21.862c3.839-3.157,4.391-8.829,1.232-12.668C328.906,226.052,323.234,225.5,319.396,228.659z
     M436.807,40.252v125.003c0,17.42-28.017,26.08-58.317,27.494v134.428c10.538,3.72,18.11,13.779,18.11,25.575v42.542
    c0,14.948-12.162,27.109-27.11,27.109c-14.95,0-27.111-12.161-27.111-27.109v-42.542c0-11.797,7.572-21.857,18.111-25.575V192.749
    c-30.301-1.414-58.318-10.074-58.318-27.494V40.252c0-2.063,0.694-3.966,1.863-5.483c7.948-14.966,39.576-20.365,65.455-20.365
    c25.871,0,57.487,5.398,65.445,20.354C436.108,36.277,436.807,38.183,436.807,40.252z M369.489,343.642h-0.003
    c-5.022,0-9.108,4.087-9.108,9.11v42.542c0,5.022,4.086,9.109,9.108,9.109c5.026,0,9.113-4.087,9.113-9.109v-42.542
    C378.6,347.729,374.513,343.642,369.489,343.642z M418.807,42.867c-2.771-3.33-19.627-10.464-49.317-10.464
    c-29.683,0-46.538,7.129-49.318,10.461v121.633c2.785,3.334,19.642,10.46,49.318,10.46c29.676,0,46.531-7.126,49.317-10.46V42.867z
     M351.371,62.163c-4.971,0-9,4.029-9,9v65.035c0,4.971,4.029,9,9,9s9-4.029,9-9V71.163
    C360.371,66.192,356.342,62.163,351.371,62.163z"/>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
    }

    if($tipo == 'enfermera'){
        $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M405.242,222.02v-39.033c0-30.266-9.53-58.345-25.739-81.407c0.179-2.133,0.276-4.298,0.276-6.469
            c0-40.116-31.018-72.772-69.205-72.939C297.554,8.227,277.453,0,255.999,0c-21.454,0-41.555,8.227-54.575,22.171
            c-38.188,0.167-69.205,32.823-69.205,72.939c0,2.145,0.095,4.284,0.27,6.394c-9.737,13.891-17.002,29.53-21.224,45.863
            c-1.037,4.01,1.373,8.102,5.384,9.139c0.63,0.162,1.261,0.24,1.883,0.24c3.337,0,6.382-2.244,7.256-5.625
            c9.521-36.827,35.962-68.182,70.73-83.873c3.776-1.704,5.455-6.146,3.751-9.921c-1.704-3.775-6.145-5.458-9.921-3.751
            c-15.451,6.973-29.711,16.839-41.796,28.849c5.457-25.865,27.216-45.255,53.167-45.255c0.822,0,1.692,0.024,2.66,0.071
            c2.373,0.106,4.657-0.896,6.164-2.729C220.584,22.294,237.576,15,255.999,15c18.423,0,35.416,7.294,45.455,19.512
            c1.508,1.834,3.813,2.852,6.164,2.729c0.969-0.048,1.839-0.071,2.661-0.071c25.988,0,47.771,19.443,53.191,45.361
            c-25.684-25.604-61.096-41.456-100.141-41.456h-14.66c-9.516,0-19.03,0.948-28.277,2.818c-4.06,0.821-6.686,4.778-5.864,8.838
            c0.821,4.061,4.78,6.679,8.838,5.865c8.271-1.673,16.784-2.521,25.304-2.521h14.661c69.98,0,126.913,56.933,126.913,126.912v20.62
            c-0.271-0.353-0.57-0.69-0.911-0.999l-16.181-14.689c-0.004-0.003-0.007-0.007-0.011-0.011l-0.519-0.47
            c-22.204-20.163-50.954-31.268-80.952-31.268h-38.49c-2.453,0-4.879-0.154-7.269-0.455c-23.099-2.906-42.741-19.476-49.122-42.288
            c-1.439-5.11-2.169-10.432-2.169-15.816v-7.28c0-4.143-3.358-7.5-7.5-7.5c-4.142,0-7.5,3.357-7.5,7.5v7.28
            c0,4.922,0.493,9.806,1.456,14.577c-28.074,20.137-44.686,52.286-44.686,87.053v17.25h-10.55c-1.375,0-2.735,0.082-4.08,0.234
            V182.99c0-1.116,0.009-2.213,0.038-3.319c0.105-4.141-3.166-7.583-7.307-7.688c-0.065-0.001-0.13-0.002-0.194-0.002
            c-4.054,0-7.39,3.233-7.494,7.309c-0.031,1.233-0.042,2.455-0.042,3.7v39.018c-10.298,6.49-16.73,17.896-16.73,30.282
            c0,12.399,6.44,23.814,16.73,30.294v45.066c0,24.166,12.048,46.095,31.94,58.989c-11.922,14.702-18.489,33.193-18.489,52.171
            v65.69c0,4.143,3.358,7.5,7.5,7.5h19.07h218.44h19.07c4.143,0,7.5-3.357,7.5-7.5v-65.69c0-33.58-20.532-64.007-51.407-76.688
            c-0.09-0.187-0.176-0.372-0.267-0.56c-1.953-3.987-5.515-6.891-9.771-7.965c-0.025-0.006-0.051-0.013-0.076-0.019
            c-0.087-0.022-0.175-0.043-0.265-0.063c-4.626-1.105-9.301,0.031-12.804,2.844c-1.277-0.127-2.56-0.232-3.848-0.299
            c-2.735-2.527-5.751-4.755-9.002-6.665v-9.29c24.081-10.638,43.918-28.865,56.593-52.005h25.217c1.381,0,2.742-0.087,4.083-0.24
            v40.287c0,11.895-3.695,23.237-10.686,32.801c-2.444,3.344-1.715,8.036,1.629,10.48c1.335,0.976,2.884,1.446,4.42,1.446
            c2.312,0,4.592-1.065,6.061-3.075c8.882-12.15,13.576-26.554,13.576-41.652V282.57c10.038-6.351,16.724-17.546,16.724-30.28
            C421.969,239.56,415.283,228.368,405.242,222.02z M105.029,252.289c0-8.178,4.827-15.627,12.325-18.988
            c2.652-1.201,5.507-1.811,8.485-1.811h10.553c0.013,1.961,0.076,3.913,0.181,5.855c0.017,0.308,0.038,0.615,0.057,0.923
            c0.047,0.764,0.101,1.527,0.162,2.288c0.027,0.331,0.053,0.662,0.083,0.993c0.075,0.842,0.159,1.681,0.252,2.519
            c0.023,0.206,0.042,0.412,0.065,0.617c0.12,1.037,0.254,2.071,0.401,3.102c0.036,0.251,0.077,0.502,0.114,0.753
            c0.117,0.788,0.241,1.574,0.374,2.359c0.056,0.328,0.113,0.656,0.171,0.983c0.136,0.761,0.279,1.519,0.43,2.276
            c0.055,0.276,0.107,0.552,0.163,0.827c0.209,1.017,0.43,2.031,0.666,3.041c0.032,0.138,0.068,0.276,0.101,0.414
            c0.207,0.873,0.425,1.743,0.652,2.611c0.082,0.312,0.167,0.622,0.251,0.933c0.195,0.722,0.398,1.441,0.607,2.159
            c0.09,0.308,0.178,0.615,0.27,0.922c0.268,0.89,0.545,1.778,0.833,2.663c0.034,0.103,0.065,0.207,0.099,0.311
            c0.324,0.982,0.664,1.96,1.014,2.935c0.103,0.286,0.21,0.571,0.315,0.856c0.156,0.423,0.306,0.847,0.467,1.269h-18.281
            c-2.977,0-5.831-0.609-8.512-1.824C109.856,267.926,105.029,260.473,105.029,252.289z M121.759,327.65v-39.784
            c1.345,0.152,2.705,0.234,4.08,0.234h25.225c12.202,22.258,31.151,40.081,54.085,50.864v12.09
            c-2.245,1.505-4.358,3.179-6.339,4.997c-1.343,0.069-2.68,0.178-4.013,0.31c-3.544-2.849-8.257-3.976-12.894-2.833
            c-0.117,0.029-0.232,0.061-0.348,0.095c-4.226,1.097-7.747,3.986-9.683,7.959c-0.092,0.19-0.182,0.381-0.274,0.571
            c-3.652,1.495-7.216,3.254-10.621,5.267c-4.089,2.412-7.94,5.159-11.537,8.214C132.285,365.782,121.759,347.696,121.759,327.65z
             M220.342,386.678c0.102,0.096,0.211,0.183,0.314,0.278c0.026,0.024,0.053,0.047,0.079,0.07c0.526,0.482,1.055,0.96,1.599,1.418
            c0.038,0.032,0.078,0.061,0.116,0.093c0.137,0.115,0.28,0.223,0.418,0.337c0.141,0.115,0.283,0.229,0.426,0.343
            c0.795,0.634,1.607,1.243,2.436,1.827c0.1,0.071,0.2,0.142,0.301,0.212c0.903,0.625,1.824,1.219,2.764,1.782
            c0.038,0.022,0.074,0.046,0.112,0.069c0.841,0.5,1.697,0.973,2.566,1.423c0.082,0.042,0.162,0.086,0.244,0.128
            c0.083,0.042,0.163,0.087,0.246,0.129c-11.919,5.422-27.892,7.617-33.996,3.253c-5.473-3.911,0.572-16.263,3.427-21.395
            c1.613-2.903,3.587-5.564,5.865-7.929c0.005-0.006,0.01-0.009,0.014-0.013l4.099,6.856c0.536,0.897,1.102,1.769,1.686,2.625
            c0.076,0.111,0.151,0.223,0.228,0.334c0.153,0.22,0.318,0.428,0.474,0.645c0.359,0.501,0.72,1,1.096,1.485
            c0.179,0.233,0.359,0.464,0.542,0.693c0.151,0.188,0.308,0.368,0.461,0.553c0.296,0.358,0.594,0.714,0.899,1.064
            c0.311,0.357,0.625,0.711,0.945,1.058c0.11,0.119,0.219,0.237,0.33,0.355c0.232,0.246,0.469,0.485,0.705,0.725
            c0.433,0.443,0.87,0.881,1.318,1.307C220.152,386.494,220.246,386.588,220.342,386.678z M220.149,361.002v-5.771v-10.402
            c0.083,0.026,0.167,0.047,0.249,0.073c1.273,0.396,2.552,0.772,3.836,1.125c0.255,0.07,0.511,0.136,0.766,0.204
            c1.136,0.304,2.276,0.592,3.42,0.863c0.226,0.053,0.45,0.11,0.676,0.162c1.289,0.296,2.583,0.569,3.881,0.822
            c0.293,0.057,0.587,0.11,0.881,0.165c1.167,0.218,2.337,0.421,3.511,0.604c0.19,0.03,0.379,0.063,0.57,0.092
            c1.311,0.198,2.627,0.37,3.946,0.525c0.314,0.037,0.629,0.07,0.944,0.105c1.24,0.135,2.483,0.255,3.73,0.352
            c0.117,0.009,0.233,0.021,0.349,0.03c1.338,0.1,2.682,0.171,4.028,0.227c0.324,0.013,0.649,0.025,0.974,0.036
            c1.36,0.045,2.722,0.076,4.089,0.076c1.266,0,2.528-0.027,3.789-0.066c0.309-0.009,0.617-0.02,0.925-0.032
            c1.255-0.048,2.507-0.11,3.756-0.197c0.053-0.004,0.106-0.009,0.159-0.013c1.206-0.086,2.407-0.195,3.607-0.316
            c0.301-0.031,0.602-0.061,0.902-0.094c1.238-0.135,2.474-0.284,3.706-0.457c0.095-0.013,0.189-0.03,0.283-0.043
            c1.156-0.166,2.309-0.352,3.459-0.551c0.289-0.05,0.578-0.1,0.866-0.153c1.223-0.221,2.443-0.458,3.658-0.717
            c0.119-0.025,0.237-0.054,0.356-0.08c1.122-0.244,2.241-0.507,3.356-0.783c0.272-0.068,0.545-0.134,0.817-0.204
            c1.207-0.308,2.411-0.632,3.609-0.978c0.034-0.01,0.068-0.018,0.102-0.028v8.322v7.103l-0.476,0.797l-3.62,6.062
            c-0.014,0.023-0.029,0.045-0.043,0.069c-0.364,0.605-0.744,1.195-1.138,1.773c-0.103,0.152-0.212,0.298-0.317,0.448
            c-0.334,0.474-0.675,0.941-1.028,1.396c-0.087,0.112-0.175,0.223-0.263,0.333c-0.436,0.546-0.882,1.082-1.345,1.599
            c-0.001,0.001-0.003,0.003-0.004,0.004c-3.554,3.957-7.906,7.015-12.75,9.009c-0.16,0.065-0.323,0.126-0.484,0.189
            c-0.439,0.173-0.882,0.336-1.329,0.491c-0.336,0.116-0.674,0.228-1.015,0.334c-0.246,0.078-0.494,0.149-0.742,0.221
            c-0.465,0.134-0.931,0.266-1.404,0.381c-0.027,0.007-0.054,0.012-0.081,0.019c-0.594,0.143-1.194,0.274-1.8,0.389
            c-0.386,0.074-0.772,0.138-1.16,0.199c-0.125,0.02-0.25,0.043-0.376,0.061c-0.369,0.055-0.738,0.1-1.108,0.143
            c-0.407,0.046-0.816,0.086-1.226,0.118c-0.332,0.027-0.664,0.049-0.997,0.066c-0.487,0.025-0.975,0.041-1.466,0.047
            c-0.25,0.003-0.5,0.006-0.75,0.003c-0.579-0.005-1.156-0.023-1.73-0.055c-0.348-0.02-0.696-0.051-1.044-0.081
            c-0.243-0.021-0.486-0.045-0.728-0.07c-0.383-0.042-0.766-0.083-1.148-0.137c-1.936-0.265-3.826-0.69-5.658-1.257
            c-0.076-0.024-0.153-0.044-0.229-0.069c-0.519-0.164-1.031-0.346-1.54-0.533c-0.145-0.053-0.29-0.105-0.435-0.161
            c-0.482-0.185-0.959-0.382-1.431-0.588c-0.159-0.069-0.317-0.14-0.474-0.211c-0.466-0.211-0.929-0.431-1.385-0.662
            c-0.143-0.073-0.284-0.149-0.426-0.224c-0.469-0.246-0.935-0.498-1.393-0.765c-0.079-0.046-0.156-0.096-0.235-0.143
            c-1.517-0.902-2.964-1.921-4.331-3.044c-0.107-0.088-0.217-0.17-0.322-0.259c-0.344-0.29-0.678-0.594-1.011-0.897
            c-0.196-0.178-0.393-0.354-0.585-0.537c-0.294-0.279-0.579-0.567-0.864-0.857c-0.223-0.228-0.445-0.457-0.663-0.692
            c-0.254-0.273-0.504-0.55-0.75-0.832c-0.242-0.278-0.479-0.561-0.713-0.847c-0.214-0.26-0.427-0.52-0.634-0.788
            c-0.27-0.35-0.53-0.71-0.789-1.071c-0.16-0.223-0.324-0.441-0.479-0.668c-0.409-0.6-0.806-1.211-1.182-1.841L220.149,361.002z
             M153.098,412.441c-0.444,1.629-0.878,3.269-1.303,4.921c-0.134,0.522-0.271,1.04-0.403,1.565
            c-0.524,2.078-1.035,4.172-1.529,6.286c-0.1,0.429-0.196,0.864-0.295,1.294c-0.405,1.76-0.799,3.533-1.183,5.316
            c-0.129,0.602-0.259,1.203-0.386,1.807c-0.459,2.185-0.908,4.381-1.336,6.601c-0.467,2.427-0.911,4.871-1.337,7.327
            c-0.139,0.801-0.266,1.61-0.4,2.413c-0.279,1.664-0.555,3.329-0.814,5.005c-0.149,0.961-0.287,1.928-0.43,2.893
            c-0.227,1.536-0.451,3.074-0.663,4.619c-0.14,1.022-0.272,2.048-0.405,3.074c-0.195,1.507-0.385,3.016-0.565,4.53
            c-0.125,1.046-0.245,2.094-0.362,3.144c-0.17,1.519-0.33,3.041-0.485,4.567c-0.105,1.042-0.211,2.083-0.309,3.127
            c-0.149,1.576-0.285,3.158-0.417,4.741c-0.083,0.994-0.17,1.985-0.247,2.981c-0.134,1.735-0.251,3.475-0.366,5.218
            c-0.056,0.849-0.119,1.695-0.17,2.545c-0.012,0.194-0.027,0.387-0.039,0.581h-4.445V438.81h0c0-15.757,5.516-31.106,15.529-43.219
            c3.339-4.036,7.093-7.645,11.218-10.786c-0.079,0.21-0.152,0.427-0.23,0.637c-0.693,1.868-1.376,3.75-2.04,5.657
            c-0.082,0.235-0.16,0.475-0.241,0.71c-0.591,1.71-1.168,3.438-1.735,5.178c-0.185,0.567-0.368,1.136-0.55,1.706
            c-0.538,1.686-1.067,3.384-1.583,5.097c-0.096,0.318-0.196,0.632-0.291,0.952c-0.595,1.995-1.172,4.012-1.736,6.044
            C153.397,411.335,153.249,411.889,153.098,412.441z M376.789,438.81V497h-4.444c-0.011-0.195-0.027-0.389-0.039-0.585
            c-0.052-0.868-0.117-1.731-0.174-2.597c-0.114-1.732-0.231-3.464-0.364-5.188c-0.077-1.002-0.165-1.998-0.248-2.997
            c-0.133-1.585-0.269-3.168-0.418-4.746c-0.099-1.049-0.205-2.094-0.311-3.14c-0.155-1.526-0.316-3.049-0.486-4.568
            c-0.118-1.055-0.239-2.107-0.365-3.158c-0.181-1.515-0.371-3.024-0.566-4.531c-0.134-1.031-0.267-2.061-0.408-3.088
            c-0.212-1.545-0.437-3.083-0.665-4.619c-0.144-0.969-0.283-1.941-0.433-2.907c-0.26-1.671-0.536-3.332-0.815-4.991
            c-0.137-0.813-0.265-1.63-0.406-2.44c-0.428-2.459-0.874-4.905-1.343-7.334c-0.427-2.217-0.876-4.41-1.335-6.593
            c-0.125-0.594-0.252-1.185-0.38-1.776c-0.386-1.795-0.783-3.579-1.191-5.35c-0.095-0.414-0.187-0.832-0.284-1.245
            c-0.496-2.122-1.009-4.224-1.536-6.311c-0.125-0.495-0.254-0.985-0.381-1.478c-0.431-1.678-0.873-3.344-1.324-4.999
            c-0.145-0.532-0.289-1.066-0.436-1.596c-0.568-2.043-1.147-4.072-1.746-6.078c-0.081-0.271-0.166-0.538-0.247-0.808
            c-0.529-1.758-1.071-3.5-1.624-5.229c-0.176-0.55-0.352-1.099-0.53-1.646c-0.568-1.746-1.147-3.478-1.738-5.193
            c-0.081-0.233-0.158-0.47-0.239-0.703c-0.666-1.914-1.35-3.804-2.045-5.679c-0.073-0.197-0.142-0.401-0.215-0.598
            C366.557,397.469,376.789,417.353,376.789,438.81z M326.633,368.13l-0.001,0.003c0.494,1.016,0.978,2.032,1.466,3.076
            c3.046,6.5,5.879,13.326,8.492,20.458c5.748,15.692,10.432,32.859,13.984,51.286c0.05,0.257,0.094,0.517,0.143,0.774
            c0.394,2.059,0.774,4.128,1.137,6.21c0.149,0.855,0.286,1.719,0.43,2.578c0.254,1.515,0.506,3.031,0.744,4.557
            c0.151,0.968,0.292,1.942,0.436,2.914c0.213,1.434,0.422,2.871,0.62,4.314c0.138,1.007,0.271,2.017,0.403,3.028
            c0.187,1.435,0.366,2.874,0.539,4.317c0.12,1.006,0.239,2.013,0.352,3.022c0.167,1.489,0.324,2.984,0.477,4.48
            c0.098,0.964,0.199,1.926,0.29,2.892c0.155,1.632,0.295,3.271,0.432,4.911c0.07,0.836,0.147,1.67,0.212,2.508
            c0.175,2.251,0.333,4.509,0.475,6.773c0.014,0.229,0.033,0.456,0.047,0.685c0.002,0.028,0.003,0.055,0.004,0.083H154.682
            c0.001-0.022,0.002-0.045,0.004-0.068c0.013-0.215,0.031-0.429,0.044-0.644c0.142-2.275,0.301-4.543,0.477-6.805
            c0.064-0.825,0.14-1.646,0.208-2.469c0.138-1.649,0.279-3.297,0.435-4.939c0.09-0.95,0.189-1.896,0.285-2.844
            c0.154-1.515,0.312-3.027,0.481-4.534c0.11-0.982,0.225-1.962,0.342-2.942c0.176-1.475,0.359-2.946,0.55-4.413
            c0.126-0.972,0.253-1.944,0.386-2.912c0.205-1.494,0.421-2.982,0.641-4.467c0.136-0.917,0.269-1.835,0.41-2.749
            c0.251-1.615,0.516-3.219,0.784-4.822c0.128-0.766,0.25-1.536,0.382-2.298c0.409-2.355,0.834-4.7,1.282-7.027
            c5.17-26.85,12.742-51.018,22.533-71.892c0.466-1.019,0.952-2.037,1.395-3.033c0.024-0.006,0.049-0.012,0.074-0.019
            c-0.196,0.544-0.386,1.099-0.579,1.647c-0.302,0.853-0.606,1.702-0.902,2.565c-0.219,0.639-0.432,1.291-0.649,1.935
            c-0.267,0.795-0.537,1.584-0.799,2.387c-0.22,0.673-0.433,1.358-0.649,2.037c-0.252,0.788-0.507,1.571-0.754,2.367
            c-0.211,0.679-0.415,1.37-0.623,2.054c-0.245,0.808-0.494,1.611-0.735,2.426c-0.2,0.677-0.393,1.367-0.59,2.049
            c-0.241,0.836-0.486,1.666-0.722,2.509c-0.179,0.639-0.352,1.289-0.529,1.933c-0.247,0.899-0.497,1.794-0.739,2.702
            c-0.174,0.653-0.341,1.317-0.512,1.975c-0.237,0.908-0.476,1.812-0.707,2.729c-0.173,0.684-0.338,1.378-0.508,2.066
            c-0.223,0.905-0.449,1.805-0.667,2.717c-0.158,0.659-0.308,1.329-0.463,1.992c-0.222,0.951-0.447,1.898-0.664,2.857
            c-0.135,0.599-0.264,1.207-0.397,1.809c-0.23,1.039-0.462,2.074-0.685,3.123c-0.076,0.356-0.148,0.718-0.223,1.075
            c-0.274,1.3-0.546,2.601-0.81,3.915c-0.077,0.383-0.149,0.772-0.225,1.155c-0.59,2.98-1.16,5.989-1.7,9.04
            c-0.022,0.127-0.043,0.256-0.066,0.383c-0.888,5.04-1.711,10.174-2.462,15.401c-0.57,3.969,2.082,7.685,6.021,8.434
            c1.697,0.323,3.396,0.636,5.096,0.939c0.401,0.072,0.803,0.137,1.204,0.207c1.296,0.227,2.592,0.454,3.889,0.669
            c0.654,0.108,1.308,0.209,1.963,0.314c1.048,0.169,2.095,0.341,3.144,0.503c0.689,0.106,1.38,0.203,2.07,0.306
            c1.015,0.151,2.029,0.306,3.045,0.45c0.698,0.099,1.397,0.189,2.095,0.285c1.009,0.138,2.017,0.279,3.026,0.41
            c0.723,0.094,1.447,0.179,2.171,0.269c0.984,0.123,1.969,0.249,2.954,0.365c0.759,0.09,1.519,0.17,2.279,0.255
            c0.951,0.107,1.9,0.218,2.852,0.318c0.787,0.083,1.576,0.158,2.364,0.237c0.924,0.093,1.848,0.189,2.773,0.276
            c0.822,0.077,1.645,0.145,2.468,0.218c0.89,0.079,1.78,0.161,2.67,0.234c0.849,0.07,1.698,0.13,2.547,0.195
            c0.867,0.066,1.734,0.136,2.602,0.197c0.854,0.06,1.71,0.111,2.564,0.166c0.863,0.055,1.726,0.115,2.59,0.165
            c0.856,0.05,1.713,0.091,2.569,0.136c0.864,0.045,1.727,0.094,2.591,0.134c0.857,0.04,1.714,0.071,2.57,0.105
            c0.865,0.035,1.729,0.074,2.595,0.104c0.866,0.03,1.732,0.051,2.598,0.076c0.855,0.025,1.711,0.053,2.567,0.073
            c0.874,0.02,1.749,0.031,2.624,0.046c0.85,0.014,1.7,0.033,2.55,0.042c0.879,0.01,1.759,0.011,2.638,0.015
            c0.767,0.004,1.533,0.013,2.3,0.013c0.078,0,0.155-0.001,0.233-0.001c0.946,0,1.893-0.01,2.84-0.017
            c0.791-0.006,1.582-0.007,2.374-0.017c0.956-0.012,1.912-0.033,2.869-0.051c0.781-0.015,1.562-0.025,2.343-0.044
            c0.966-0.023,1.932-0.056,2.899-0.086c0.774-0.024,1.547-0.043,2.322-0.071c0.965-0.035,1.931-0.079,2.896-0.12
            c0.776-0.033,1.551-0.061,2.327-0.098c0.963-0.046,1.926-0.101,2.889-0.153c0.779-0.042,1.557-0.08,2.336-0.126
            c0.976-0.058,1.953-0.126,2.93-0.19c0.764-0.05,1.528-0.096,2.293-0.15c0.975-0.069,1.949-0.148,2.924-0.224
            c0.77-0.06,1.54-0.115,2.311-0.179c0.975-0.081,1.951-0.171,2.927-0.259c0.766-0.068,1.532-0.132,2.299-0.204
            c0.995-0.094,1.99-0.198,2.985-0.299c0.749-0.076,1.498-0.147,2.247-0.226c0.999-0.106,1.999-0.222,2.998-0.335
            c0.745-0.084,1.49-0.163,2.235-0.251c1.006-0.119,2.012-0.248,3.018-0.373c0.739-0.092,1.477-0.179,2.216-0.275
            c1.021-0.133,2.042-0.275,3.063-0.415c0.724-0.099,1.447-0.192,2.17-0.294c1.039-0.147,2.078-0.305,3.117-0.459
            c0.706-0.104,1.412-0.204,2.117-0.312c1.081-0.166,2.161-0.342,3.242-0.515c0.664-0.107,1.328-0.207,1.992-0.317
            c1.192-0.197,2.383-0.404,3.574-0.61c0.553-0.095,1.106-0.186,1.658-0.283c1.744-0.308,3.488-0.627,5.232-0.955
            c3.947-0.743,6.608-4.464,6.035-8.439c-2.016-13.998-4.534-27.343-7.529-39.962c-0.05-0.212-0.098-0.428-0.149-0.64
            c-0.288-1.205-0.585-2.397-0.882-3.589c-0.098-0.392-0.192-0.791-0.29-1.182c-0.242-0.958-0.492-1.903-0.74-2.852
            c-0.161-0.616-0.317-1.238-0.48-1.85c-0.2-0.75-0.407-1.489-0.611-2.233c-0.218-0.798-0.433-1.603-0.655-2.394
            c-0.177-0.632-0.362-1.254-0.542-1.882c-0.255-0.891-0.508-1.786-0.769-2.668c-0.156-0.528-0.318-1.046-0.476-1.571
            c-0.292-0.972-0.583-1.948-0.882-2.909c-0.12-0.386-0.244-0.763-0.365-1.147c-0.343-1.089-0.686-2.179-1.037-3.255
            c-0.096-0.294-0.196-0.582-0.293-0.875c-0.381-1.157-0.764-2.312-1.156-3.453c-0.059-0.173-0.121-0.342-0.181-0.515
            c-0.426-1.234-0.854-2.463-1.291-3.678C326.617,368.126,326.625,368.128,326.633,368.13z M322.615,410.334
            c0.201-0.142,0.382-0.291,0.575-0.436c2.216,9.528,4.16,19.491,5.811,29.865c-48.995,8.422-98.051,8.487-146.038,0.199
            c1.659-10.446,3.612-20.475,5.843-30.059c0.149,0.112,0.286,0.229,0.44,0.339c4.965,3.548,11.626,5.048,18.864,5.048
            c15.48,0,33.587-6.865,43.33-15.232c0.175,0.011,0.354,0.008,0.53,0.017c0.826,0.046,1.652,0.074,2.478,0.079
            c0.101,0.001,0.2,0.01,0.301,0.01c0.002,0,0,0,0.001,0c0.131,0,0.259-0.012,0.389-0.012c0.824-0.006,1.647-0.033,2.469-0.079
            c0.417-0.023,0.831-0.056,1.245-0.089c0.49-0.04,0.978-0.087,1.466-0.141c6.467,5.706,16.575,10.677,27.537,13.394
            c4.119,1.021,9.97,2.117,16.107,2.117C310.428,415.354,317.211,414.137,322.615,410.334z M310.255,376.029
            c3.005,5.301,9.383,18.06,3.728,22.039c-6.315,4.442-23.224,1.879-35.196-3.898c0.075-0.04,0.146-0.087,0.22-0.128
            c0.891-0.484,1.77-0.989,2.628-1.527c0.115-0.072,0.225-0.152,0.34-0.225c0.743-0.474,1.473-0.968,2.189-1.481
            c0.267-0.191,0.528-0.39,0.791-0.587c0.57-0.426,1.132-0.864,1.684-1.315c0.263-0.215,0.525-0.429,0.784-0.649
            c0.61-0.52,1.206-1.057,1.791-1.607c0.163-0.153,0.332-0.299,0.493-0.455c0.732-0.707,1.444-1.44,2.136-2.195
            c0.181-0.198,0.354-0.405,0.532-0.606c0.512-0.577,1.013-1.164,1.501-1.767c0.154-0.191,0.312-0.378,0.464-0.572
            c0.07-0.089,0.134-0.185,0.204-0.275c0.447-0.576,0.881-1.165,1.305-1.763c0.147-0.207,0.302-0.404,0.446-0.614
            c0.051-0.074,0.098-0.152,0.148-0.227c0.581-0.855,1.147-1.725,1.684-2.622l4.942-8.27c0.001-0.001,0.002-0.003,0.002-0.004
            l0.114-0.186C305.973,369.674,308.355,372.676,310.255,376.029z M360.612,223.994v6.689c0,4.094-0.229,8.143-0.687,12.142
            c-1.372,11.996-4.795,23.535-10.226,34.433c-11.628,23.324-31.368,41.39-55.586,50.869c-12.131,4.753-24.954,7.163-38.113,7.163
            c-3.509,0-6.991-0.17-10.441-0.51c-1.097-0.108-2.19-0.24-3.281-0.382c-8.966-1.17-17.696-3.494-26.085-6.953
            c-0.213-0.088-0.427-0.17-0.639-0.259c0,0-0.001-0.001-0.002-0.001c-23.131-9.707-42.042-27.44-53.249-49.929
            c-7.241-14.531-10.913-30.202-10.913-46.576v-6.689v-24.75c0-28.238,12.74-54.479,34.497-71.899
            c0.129,0.292,0.27,0.576,0.402,0.865c0.164,0.358,0.328,0.716,0.498,1.071c0.252,0.526,0.512,1.046,0.776,1.565
            c0.167,0.329,0.331,0.659,0.502,0.985c0.298,0.565,0.607,1.121,0.919,1.677c0.151,0.27,0.297,0.542,0.452,0.81
            c0.444,0.768,0.902,1.528,1.373,2.278c0.03,0.047,0.057,0.096,0.087,0.144c0.506,0.803,1.029,1.592,1.564,2.373
            c0.15,0.219,0.308,0.431,0.46,0.648c0.389,0.553,0.78,1.104,1.182,1.645c0.203,0.272,0.412,0.539,0.619,0.808
            c0.366,0.477,0.734,0.953,1.111,1.42c0.228,0.283,0.461,0.562,0.694,0.842c0.369,0.445,0.742,0.886,1.121,1.321
            c0.244,0.28,0.491,0.558,0.739,0.834c0.383,0.427,0.771,0.848,1.164,1.265c0.252,0.268,0.505,0.535,0.761,0.799
            c0.408,0.421,0.823,0.835,1.241,1.245c0.25,0.245,0.497,0.493,0.75,0.735c0.455,0.435,0.919,0.861,1.385,1.284
            c0.225,0.204,0.446,0.412,0.673,0.614c0.588,0.521,1.186,1.031,1.791,1.533c0.112,0.094,0.222,0.191,0.335,0.284
            c0.729,0.598,1.469,1.183,2.22,1.753c0.155,0.117,0.314,0.228,0.469,0.344c0.591,0.441,1.185,0.877,1.789,1.301
            c0.259,0.182,0.524,0.356,0.785,0.535c0.509,0.348,1.02,0.693,1.538,1.028c0.298,0.193,0.599,0.379,0.9,0.568
            c0.493,0.309,0.988,0.614,1.488,0.912c0.317,0.189,0.637,0.373,0.957,0.557c0.495,0.285,0.994,0.564,1.496,0.836
            c0.327,0.178,0.654,0.354,0.984,0.527c0.511,0.268,1.026,0.528,1.543,0.784c0.325,0.161,0.649,0.323,0.976,0.479
            c0.547,0.261,1.099,0.511,1.652,0.758c0.303,0.136,0.605,0.276,0.91,0.407c0.638,0.275,1.283,0.536,1.929,0.793
            c0.226,0.09,0.45,0.186,0.677,0.274c0.881,0.34,1.769,0.665,2.664,0.972c0.126,0.043,0.254,0.08,0.38,0.123
            c0.764,0.257,1.532,0.505,2.306,0.738c0.308,0.092,0.619,0.175,0.928,0.264c0.599,0.171,1.198,0.34,1.802,0.497
            c0.357,0.093,0.717,0.177,1.076,0.264c0.564,0.137,1.129,0.27,1.697,0.394c0.379,0.083,0.759,0.159,1.14,0.236
            c0.556,0.112,1.114,0.218,1.674,0.317c0.388,0.069,0.777,0.135,1.167,0.197c0.562,0.09,1.127,0.172,1.692,0.249
            c0.389,0.053,0.777,0.106,1.167,0.153c0.584,0.07,1.171,0.128,1.759,0.184c0.373,0.036,0.744,0.075,1.118,0.105
            c0.646,0.051,1.294,0.088,1.943,0.122c0.318,0.017,0.634,0.041,0.953,0.053c0.969,0.039,1.94,0.061,2.916,0.061h38.49
            c1.54,0,3.076,0.036,4.608,0.103c0.065,0.003,0.13,0.004,0.194,0.007c1.481,0.067,2.957,0.169,4.429,0.297
            c0.114,0.01,0.228,0.017,0.342,0.028c1.434,0.13,2.862,0.293,4.285,0.481c0.148,0.02,0.296,0.036,0.444,0.057
            c1.395,0.19,2.783,0.411,4.165,0.656c0.172,0.031,0.345,0.059,0.518,0.091c1.361,0.248,2.714,0.526,4.062,0.827
            c0.189,0.042,0.379,0.083,0.568,0.126c1.332,0.306,2.657,0.639,3.975,0.996c0.197,0.053,0.395,0.106,0.592,0.161
            c1.308,0.362,2.608,0.751,3.9,1.163c0.2,0.064,0.4,0.128,0.6,0.193c1.286,0.419,2.564,0.863,3.833,1.332
            c0.198,0.073,0.395,0.147,0.592,0.221c1.268,0.477,2.527,0.977,3.775,1.503c0.19,0.08,0.379,0.161,0.568,0.242
            c1.253,0.536,2.496,1.095,3.728,1.679c0.174,0.083,0.348,0.168,0.522,0.251c1.242,0.598,2.473,1.218,3.691,1.865
            c0.154,0.082,0.308,0.166,0.462,0.249c1.232,0.662,2.454,1.346,3.661,2.058c0.13,0.077,0.258,0.156,0.387,0.233
            c1.228,0.731,2.444,1.485,3.644,2.268c0.094,0.061,0.187,0.125,0.281,0.187c1.23,0.808,2.447,1.64,3.646,2.501
            c0.051,0.037,0.102,0.075,0.153,0.112c2.492,1.798,4.915,3.709,7.26,5.734c0.018,0.016,0.037,0.031,0.056,0.047V223.994z
             M375.609,210.409l3.638,3.303c1.437,1.306,3.242,1.948,5.04,1.948c2.041,0,4.074-0.828,5.555-2.458
            c0.143-0.158,0.273-0.323,0.4-0.489v4.016c-1.341-0.153-2.702-0.24-4.083-0.24h-10.55V210.409z M386.159,273.101h-18.281
            c0.156-0.41,0.303-0.822,0.454-1.233c0.111-0.303,0.225-0.605,0.335-0.909c0.346-0.964,0.682-1.931,1.002-2.902
            c0.046-0.141,0.089-0.282,0.134-0.423c0.275-0.845,0.54-1.692,0.796-2.543c0.099-0.328,0.194-0.658,0.289-0.987
            c0.201-0.691,0.396-1.384,0.585-2.079c0.091-0.334,0.182-0.667,0.269-1.001c0.215-0.823,0.421-1.649,0.619-2.478
            c0.043-0.18,0.09-0.36,0.132-0.54c0.232-0.995,0.45-1.993,0.656-2.994c0.063-0.304,0.12-0.61,0.181-0.915
            c0.143-0.723,0.281-1.448,0.41-2.174c0.063-0.353,0.125-0.705,0.184-1.058c0.127-0.748,0.245-1.498,0.357-2.25
            c0.042-0.283,0.088-0.566,0.129-0.85c0.144-1.016,0.276-2.034,0.395-3.055c0.028-0.242,0.05-0.485,0.077-0.727
            c0.087-0.799,0.168-1.601,0.24-2.404c0.031-0.354,0.06-0.707,0.089-1.061c0.059-0.73,0.11-1.463,0.156-2.196
            c0.021-0.333,0.043-0.667,0.061-1.001c0.055-1.019,0.1-2.04,0.13-3.063c0.002-0.07,0.003-0.141,0.005-0.211
            c0.023-0.85,0.039-1.701,0.044-2.555h10.553c11.475,0,20.81,9.331,20.81,20.8C406.969,263.765,397.634,273.101,386.159,273.101z"
            />
    </g>
</g>
<g>
    <g>
        <path d="M287.936,260.31c-3.406-2.358-8.077-1.508-10.435,1.896c-4.891,7.063-12.928,11.278-21.501,11.278
            s-16.611-4.216-21.501-11.278c-2.357-3.405-7.03-4.255-10.435-1.896c-3.405,2.357-4.254,7.03-1.897,10.435
            c7.69,11.107,20.338,17.739,33.833,17.739c13.494,0,26.142-6.632,33.833-17.739C292.191,267.34,291.341,262.668,287.936,260.31z"
            />
    </g>
</g>
<g>
    <g>
        <path d="M310.322,215.962c-4.142,0-7.5,3.357-7.5,7.5v2c0,4.143,3.358,7.5,7.5,7.5c4.143,0,7.5-3.357,7.5-7.5v-2
            C317.822,219.319,314.465,215.962,310.322,215.962z"/>
    </g>
</g>
<g>
    <g>
        <path d="M201.676,215.962c-4.142,0-7.5,3.357-7.5,7.5v2c0,4.143,3.358,7.5,7.5,7.5c4.142,0,7.5-3.357,7.5-7.5v-2
            C209.176,219.319,205.818,215.962,201.676,215.962z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
';
    }

    return $icono;
}


function getIconsGlobal($args = array())
{
    global $wpdb;
    $defaults = array(
        "icono" => '',
    );
    $args = wp_parse_args($args, $defaults);

    $tipo = $args['icono'];

    $icono = iconosPublic($tipo);

    if($icono != ''){
        return $icono;
    }else{

        if($tipo == 'creaProfesional'){
            $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M38.83,401.586c-5.523,0-10,4.478-10,10v0.131c0,5.522,4.477,10,10,10s10-4.478,10-10v-0.131    C48.83,406.063,44.353,401.586,38.83,401.586z"/>
    </g>
</g>
<g>
    <g>
        <path d="M132.89,180.14c-1.86-1.859-4.44-2.93-7.07-2.93s-5.21,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.07,5.21,2.93,7.07c1.86,1.859,4.44,2.93,7.07,2.93c2.64,0,5.21-1.07,7.07-2.93c1.86-1.86,2.93-4.44,2.93-7.07    S134.75,182,132.89,180.14z"/>
    </g>
</g>
<g>
    <g>
        <path d="M511.976,113.999c-0.001-2.601-0.993-5.158-2.905-7.071l-104-104c-1.916-1.916-4.48-2.907-7.087-2.904    C397.99,0.016,397.994,0.009,398,0H122c-16.542,0-30,13.458-30,30v111.499c0,5.522,4.477,10,10,10s10-4.478,10-10V30    c0-5.514,4.486-10,10-10h266v74c0,16.542,13.458,30,30,30h74v358c0,5.514-4.486,10-10,10H122c-5.514,0-10-4.486-10-10v-24.499    c3.622,0.331,7.268,0.499,10.933,0.499h134.909c19.769,0,35.853-16.083,35.853-35.853v-0.499c0-5.695-1.341-11.08-3.713-15.867    c19.532-0.277,35.339-16.242,35.339-35.839v-0.499c0-10.151-4.248-19.323-11.051-25.853c6.803-6.529,11.051-15.701,11.051-25.853    v-0.499c0-16.413-11.089-30.275-26.165-34.513c2.803-5.108,4.401-10.967,4.401-17.193v-0.499c0-19.77-16.083-35.853-35.853-35.853    h-75.656l22.974-49.652c9.231-19.95,5.962-43.635-8.33-60.34l-11.372-13.292c-1.901-2.222-4.677-3.499-7.599-3.499    c-0.027,0-0.055,0-0.083,0c-2.951,0.024-5.741,1.352-7.622,3.625l-34.762,42.016c-3.521,4.256-2.925,10.56,1.33,14.08    s10.559,2.925,14.079-1.33l27.186-32.858l3.645,4.261c9.223,10.78,11.333,26.064,5.375,38.939l-29.544,63.852    c-1.433,3.097-1.188,6.71,0.65,9.585s5.014,4.614,8.426,4.614h91.301c8.741,0,15.853,7.111,15.853,15.853v0.499    c0,8.741-7.111,15.853-15.853,15.853h-47.617c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h47.617h21.764    c8.741,0,15.853,7.112,15.853,15.854v0.499c0,8.741-7.111,15.853-15.853,15.853h-69.381c-5.523,0-10,4.478-10,10    c0,5.522,4.477,10,10,10h69.381c8.741,0,15.853,7.111,15.853,15.853v0.499c0,8.741-7.111,15.853-15.853,15.853h-31.625h-37.755    c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h37.755c8.741,0,15.853,7.112,15.853,15.854v0.499    c0,8.741-7.111,15.853-15.853,15.853H122.933c-12.482,0-24.704-2.312-36.326-6.869L74,426.187V265.534l30.564-36.942    c3.521-4.256,2.925-10.56-1.33-14.08c-4.255-3.521-10.559-2.925-14.079,1.33l-17.293,20.902c-1.831-2.331-4.668-3.834-7.862-3.834    H10c-5.523,0-10,4.478-10,10V448c0,5.522,4.477,10,10,10h54c5.523,0,10-4.478,10-10v-0.33l5.305,2.08    c4.171,1.636,8.407,3.025,12.695,4.177V482c0,16.542,13.458,30,30,30h360c16.542,0,30-13.458,30-30V114    C511.992,114,511.984,113.999,511.976,113.999z M418,104c-5.514,0-10-4.486-10-10V34.143L477.858,104H418z M54,438H20V252.91h34    V438z"/>
    </g>
</g>
<g>
    <g>
        <path d="M428.57,158.93c-1.86-1.86-4.44-2.93-7.07-2.93s-5.21,1.069-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.07,5.21,2.93,7.069c1.86,1.86,4.44,2.931,7.07,2.931s5.21-1.07,7.07-2.931c1.86-1.859,2.93-4.439,2.93-7.069    S430.43,160.79,428.57,158.93z"/>
    </g>
</g>
<g>
    <g>
        <path d="M381,156H250c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h131c5.523,0,10-4.478,10-10    C391,160.478,386.523,156,381,156z"/>
    </g>
</g>
<g>
    <g>
        <path d="M421.924,208H311.5c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h110.424c5.523,0,10-4.478,10-10    C431.924,212.478,427.447,208,421.924,208z"/>
    </g>
</g>
<g>
    <g>
        <path d="M421.5,260.758h-66.75c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h66.75c5.523,0,10-4.478,10-10    C431.5,265.236,427.023,260.758,421.5,260.758z"/>
    </g>
</g>
<g>
    <g>
        <path d="M421.924,312.758H354.75c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h67.174c5.523,0,10-4.478,10-10    C431.924,317.236,427.447,312.758,421.924,312.758z"/>
    </g>
</g>
<g>
    <g>
        <path d="M333,104h-83c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h83c5.523,0,10-4.478,10-10C343,108.478,338.523,104,333,104    z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
        }

        if($tipo == 'celebracion'){
            $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M309.126,158.729c-4.505-3.195-10.747-2.137-13.943,2.368l-0.194,0.273c-3.196,4.504-2.136,10.746,2.368,13.942    c1.757,1.246,3.777,1.846,5.779,1.846c3.131,0,6.214-1.466,8.164-4.214l0.194-0.273    C314.69,168.167,313.63,161.925,309.126,158.729z"/>
    </g>
</g>
<g>
    <g>
        <path d="M286.344,187.069c-3.502-4.271-9.803-4.892-14.073-1.389c-13.893,11.396-29.68,19.508-46.923,24.112    c-5.336,1.425-8.507,6.905-7.082,12.241c1.194,4.472,5.236,7.423,9.655,7.423c0.854,0,1.722-0.11,2.586-0.342    c20.017-5.345,38.335-14.756,54.447-27.972C289.225,197.64,289.847,191.338,286.344,187.069z"/>
    </g>
</g>
<g>
    <g>
        <path d="M482,226.47c-16.542,0-30,13.458-30,29.999v5.001c0,41.937-30.535,76.854-70.535,83.743    c5.224-7.705,8.28-16.994,8.28-26.984v-6.946c0-26.58-21.625-48.204-48.205-48.204c-26.58,0-48.204,21.624-48.204,48.204v6.946    c0,9.736,2.91,18.8,7.893,26.387c-38.429-8.146-67.357-42.326-67.357-83.146v-5.001c0-16.541-13.458-29.999-30-29.999    s-30,13.458-30,29.999v5.001c0,20.297,4.13,39.926,12.098,58.061h-66.217h-1.826V218.823c0-4.464-2.958-8.388-7.25-9.614    C57.288,193.94,20,144.509,20,89v-5c0-5.514,4.486-10,10-10s10,4.486,10,10v5c0,57.897,47.103,105,105,105h48.127    c57.897,0,105-47.103,105-105v-5c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10v5c0,15.371-2.769,30.386-8.231,44.625    c-1.978,5.156,0.599,10.94,5.756,12.918c5.154,1.979,10.94-0.6,12.918-5.756c6.342-16.534,9.557-33.958,9.557-51.787v-5    c0-16.542-13.458-30-30-30s-30,13.458-30,30v5c0,41.936-30.535,76.853-70.534,83.743c5.224-7.705,8.279-16.993,8.279-26.982    v-6.946c0-26.58-21.625-48.205-48.205-48.205s-48.204,21.625-48.204,48.205v6.946c0,9.735,2.91,18.799,7.893,26.385    C88.927,164,60,129.82,60,89v-5c0-16.542-13.458-30-30-30S0,67.458,0,84v5c0,32.106,10.322,62.548,29.849,88.033    c17.344,22.635,41.309,39.875,68.079,49.118V329.53c0,5.522,4.477,10,10,10h1.826V502c0,5.522,4.477,10,10,10    c5.523,0,10-4.478,10-10V339.53h66.96c2.186,3.399,4.521,6.726,7.008,9.972c0.662,0.864,1.351,1.704,2.032,2.552V502    c0,5.522,4.477,10,10,10c5.523,0,10-4.478,10-10V372.49c13.625,11.419,29.286,20.342,46.047,26.13V502c0,5.522,4.477,10,10,10    s10-4.478,10-10V391.292c0-4.464-2.958-8.388-7.25-9.614c-53.39-15.269-90.678-64.699-90.678-120.208v-5.001    c0-5.514,4.486-9.999,10-9.999c5.514,0,10,4.485,10,9.999v5.001c0,57.897,47.103,105,105,105H367c57.897,0,105-47.103,105-105    v-5.001c0-5.514,4.486-9.999,10-9.999c5.514,0,10,4.485,10,9.999v5.001c0,56.534-38.152,106.205-92.778,120.791    c-4.376,1.168-7.42,5.132-7.42,9.661v72.411c0,5.522,4.477,10,10,10s10-4.478,10-10V399.39    C471.176,380.101,512,324.459,512,261.47v-5.001C512,239.928,498.542,226.47,482,226.47z M139.463,138.814    c0-15.553,12.652-28.205,28.204-28.205s28.205,12.652,28.205,28.205v6.946c0,15.552-12.652,28.204-28.205,28.204    c-15.552,0-28.204-12.652-28.204-28.204V138.814z M369.745,318.229c0,15.552-12.652,28.204-28.205,28.204    c-15.552,0-28.204-12.652-28.204-28.204v-6.946c0-15.552,12.652-28.204,28.204-28.204s28.205,12.652,28.205,28.204V318.229z"/>
    </g>
</g>
<g>
    <g>
        <path d="M408.87,494.93c-1.86-1.861-4.44-2.93-7.07-2.93s-5.21,1.069-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    s1.07,5.21,2.93,7.069c1.86,1.86,4.44,2.931,7.07,2.931s5.21-1.07,7.07-2.931c1.86-1.859,2.93-4.439,2.93-7.069    S410.73,496.79,408.87,494.93z"/>
    </g>
</g>
<g>
    <g>
        <path d="M167.667,374c-5.523,0-10,4.478-10,10v118c0,5.522,4.477,10,10,10c5.523,0,10-4.478,10-10V384    C177.667,378.478,173.19,374,167.667,374z"/>
    </g>
</g>
<g>
    <g>
        <path d="M466,82c-5.523,0-10,4.478-10,10v12.43c0,5.522,4.477,10,10,10s10-4.478,10-10V92C476,86.478,471.523,82,466,82z"/>
    </g>
</g>
<g>
    <g>
        <path d="M466,141.57c-5.523,0-10,4.478-10,10V164c0,5.522,4.477,10,10,10s10-4.478,10-10v-12.43    C476,146.048,471.523,141.57,466,141.57z"/>
    </g>
</g>
<g>
    <g>
        <path d="M502,118h-12.43c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10H502c5.523,0,10-4.478,10-10    C512,122.478,507.523,118,502,118z"/>
    </g>
</g>
<g>
    <g>
        <path d="M442.43,118H430c-5.523,0-10,4.478-10,10c0,5.522,4.477,10,10,10h12.43c5.523,0,10-4.478,10-10    C452.43,122.478,447.953,118,442.43,118z"/>
    </g>
</g>
<g>
    <g>
        <path d="M219.873,0c-5.523,0-10,4.478-10,10v12.43c0,5.522,4.477,10,10,10c5.523,0,10-4.478,10-10V10    C229.873,4.478,225.396,0,219.873,0z"/>
    </g>
</g>
<g>
    <g>
        <path d="M219.873,59.57c-5.523,0-10,4.478-10,10V82c0,5.522,4.477,10,10,10c5.523,0,10-4.478,10-10V69.57    C229.873,64.048,225.396,59.57,219.873,59.57z"/>
    </g>
</g>
<g>
    <g>
        <path d="M255.873,36h-12.43c-5.523,0-10,4.478-10,10s4.477,10,10,10h12.43c5.523,0,10-4.478,10-10S261.396,36,255.873,36z"/>
    </g>
</g>
<g>
    <g>
        <path d="M196.303,36h-12.43c-5.523,0-10,4.478-10,10s4.477,10,10,10h12.43c5.523,0,10-4.478,10-10S201.826,36,196.303,36z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
        }

        if($tipo == 'comunicacion'){
            $icono = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M181.72,366.814c-0.723-0.206-1.463-0.322-2.208-0.362c10.954-11.513,17.7-27.066,17.7-44.174v-52.611    c0-0.481-0.046-0.95-0.111-1.412c0.066-0.95,0.111-1.906,0.111-2.872v-33.286c0-5.523-4.477-10-10-10h-67.073    c-28.245,0-51.223,22.979-51.223,51.223v48.958c0,17.108,6.745,32.661,17.7,44.174c-0.744,0.04-1.484,0.156-2.206,0.362    C37.153,371.093,0,410.924,0,459.276V502c0,5.523,4.477,10,10,10h246.127c5.523,0,10-4.477,10-10v-42.724    C266.127,410.925,228.976,371.094,181.72,366.814z M88.916,286.001v-12.682c0-17.216,14.007-31.223,31.223-31.223h57.073v23.286    c0,11.723-9.537,21.26-21.26,21.26H88.916V286.001z M88.916,306.642h67.036c7.772,0,15.047-2.162,21.26-5.914v21.549    c0,24.343-19.805,44.148-44.148,44.148c-24.343,0-44.148-19.805-44.148-44.148V306.642z M154.829,386.426l-21.765,21.766    l-21.765-21.766H154.829z M246.128,492L246.128,492H20v-20h41.5c5.523,0,10-4.477,10-10s-4.477-10-10-10H20.365    c3.359-33.716,29.806-60.717,63.255-64.969l42.373,42.373c1.875,1.875,4.419,2.929,7.071,2.929c2.652,0,5.196-1.054,7.071-2.929    l42.373-42.373c35.824,4.553,63.62,35.204,63.62,72.245V492z"/>
    </g>
</g>
<g>
    <g>
        <path d="M427.595,144.718c-0.723-0.206-1.464-0.322-2.21-0.362c10.954-11.513,17.699-27.066,17.699-44.174V47.57    c0-0.481-0.046-0.95-0.111-1.412c0.066-0.95,0.111-1.906,0.111-2.872V10c0-5.523-4.477-10-10-10h-67.073    c-28.245,0-51.223,22.979-51.223,51.223v48.958c0,17.108,6.745,32.661,17.699,44.174c-0.745,0.04-1.487,0.156-2.21,0.362    c-47.255,4.281-84.405,44.112-84.405,92.462v42.723c0,5.523,4.477,10,10,10h80.46c5.523,0,10-4.477,10-10s-4.477-10-10-10h-70.46    V237.18c0-37.041,27.795-67.692,63.619-72.245l42.373,42.373c1.953,1.953,4.512,2.929,7.071,2.929s5.119-0.976,7.071-2.929    l42.373-42.373C464.205,169.489,492,200.139,492,237.18v32.723h-73.667c-5.523,0-10,4.477-10,10s4.477,10,10,10H502    c5.523,0,10-4.477,10-10V237.18C512,188.83,474.849,148.999,427.595,144.718z M378.937,186.095l-21.765-21.765h43.53    L378.937,186.095z M423.085,100.181c0,24.343-19.805,44.148-44.148,44.148s-44.148-19.804-44.148-44.147V84.546h67.036    c7.772,0,15.047-2.163,21.26-5.914V100.181z M423.085,43.286c0,11.723-9.537,21.26-21.26,21.26h-67.036v-0.641V51.223    c0-17.216,14.007-31.223,31.223-31.223h57.073V43.286z"/>
    </g>
</g>
<g>
    <g>
        <path d="M386.01,272.83c-1.86-1.86-4.44-2.93-7.07-2.93c-2.64,0-5.21,1.07-7.07,2.93c-1.87,1.86-2.93,4.44-2.93,7.07    c0,2.64,1.06,5.21,2.93,7.07c1.86,1.87,4.43,2.93,7.07,2.93c2.63,0,5.21-1.06,7.07-2.93c1.86-1.86,2.93-4.43,2.93-7.07    C388.94,277.27,387.87,274.69,386.01,272.83z"/>
    </g>
</g>
<g>
    <g>
        <path d="M267.405,139.929l-41.821-41.821V50c0-27.57-22.43-50-50-50H50C22.43,0,0,22.43,0,50v88c0,27.57,22.43,50,50,50h125.583    c20.674,0,38.702-12.407,46.266-31h38.484c4.044,0,7.691-2.437,9.239-6.173C271.12,147.091,270.264,142.789,267.405,139.929z     M214.554,137c-4.659,0-8.701,3.217-9.746,7.758C201.66,158.442,189.643,168,175.583,168H50c-16.542,0-30-13.458-30-30V50    c0-16.542,13.458-30,30-30h125.583c16.542,0,30,13.458,30,30v52.25c0,2.652,1.054,5.196,2.929,7.071L236.191,137H214.554z"/>
    </g>
</g>
<g>
    <g>
        <path d="M462,324H336.417c-20.674,0-38.702,12.407-46.266,31h-38.484c-4.044,0-7.691,2.437-9.239,6.173    c-1.548,3.736-0.692,8.038,2.167,10.898l41.821,41.821V462c0,27.57,22.43,50,50,50H462c27.57,0,50-22.43,50-50v-88    C512,346.43,489.57,324,462,324z M492,462c0,16.542-13.458,30-30,30H336.417c-16.542,0-30-13.458-30-30v-52.25    c0-2.652-1.054-5.196-2.929-7.071L275.809,375h21.637c4.659,0,8.701-3.217,9.746-7.758C310.34,353.558,322.357,344,336.417,344    H462c16.542,0,30,13.458,30,30V462z"/>
    </g>
</g>
<g>
    <g>
        <path d="M444.156,391.392c-3.904-3.905-10.236-3.905-14.141,0l-26.583,26.583l-12.361-12.361c-3.905-3.905-10.237-3.905-14.143,0    c-3.905,3.905-3.905,10.237,0,14.143l19.432,19.432c1.953,1.953,4.512,2.929,7.071,2.929s5.119-0.976,7.071-2.929l33.654-33.654    C448.061,401.63,448.061,395.298,444.156,391.392z"/>
    </g>
</g>
<g>
    <g>
        <path d="M158.253,66.268c-1.182-17.147-15.004-30.969-32.151-32.151c-9.723-0.669-18.991,2.611-26.091,9.239    c-7.001,6.535-11.017,15.775-11.017,25.349c0.001,5.524,4.478,10.001,10.001,10.001s10-4.477,10-10    c0-4.111,1.656-7.921,4.664-10.729c3.003-2.804,6.938-4.196,11.069-3.906c7.239,0.499,13.074,6.334,13.573,13.573    c0.505,7.319-4.293,13.787-11.408,15.379c-7.788,1.742-13.227,8.513-13.227,16.465v5.335c0,5.523,4.477,10,10,10s10-4.477,10-10    v-2.91C149.16,97.291,159.385,82.684,158.253,66.268z"/>
    </g>
</g>
<g>
    <g>
        <path d="M130.74,136.02c-1.86-1.86-4.44-2.93-7.07-2.93c-2.64,0-5.21,1.07-7.07,2.93c-1.87,1.86-2.93,4.44-2.93,7.07    s1.06,5.21,2.93,7.07c1.86,1.86,4.43,2.93,7.07,2.93c2.63,0,5.21-1.07,7.07-2.93c1.86-1.86,2.93-4.44,2.93-7.07    S132.6,137.88,130.74,136.02z"/>
    </g>
</g>
<g>
    <g>
        <path d="M108.57,454.93c-1.86-1.86-4.44-2.93-7.07-2.93s-5.21,1.07-7.07,2.93s-2.93,4.44-2.93,7.07s1.07,5.21,2.93,7.07    c1.86,1.86,4.44,2.93,7.07,2.93s5.21-1.07,7.07-2.93s2.93-4.44,2.93-7.07S110.43,456.79,108.57,454.93z"/>
    </g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
        }



        return $icono;

    }

}add_shortcode('templateMisVacantes', 'templateMisVacantes');


// para el formulario
add_action('wp_ajax_senddeletePublicOfert', 'senddeletePublicOfert');
add_action('wp_ajax_nopriv_senddeletePublicOfert', 'senddeletePublicOfert');
function senddeletePublicOfert(){

    global $wpdb;

    if (isset($_POST['senddeletePublicOfert']) && $_POST['action'] == 'senddeletePublicOfert') {

    $tabla = $wpdb->prefix . 'public_profesional';
    $tabla2 = $wpdb->prefix . 'ofertalaboral';

    $serialOferta = $_POST['senddeletePublicOfert'];
    $serialOferta = stripslashes($serialOferta);

    $infoOferta = $wpdb->get_results("SELECT * FROM $tabla where id='$serialOferta'", ARRAY_A);
    $dtb = $tabla;
    $candidatoId = array(
        'index' => 'candidatoId',
        'id' => $infoOferta[0]['candidatoId']
    );

    if(count($infoOferta) == 0){
        $infoOferta = $wpdb->get_results("SELECT * FROM $tabla2 where serialOferta='$serialOferta' ", ARRAY_A);
        $dtb = $tabla2;
        $candidatoId = array(
            'index' => 'contratistaId',
            'id' => $infoOferta[0]['contratistaId']
        );


        ?>
        <pre>
     <?php print_r($candidatoId) ?>
        </pre>
        <?Php
    }




    if( count($infoOferta) > 0 &&
        is_user_logged_in() &&
    (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ) ||
    validateUserProfileOwner( get_current_user_id(), $candidatoId['id'], "candidata" ) )
    ){





        if($candidatoId['index'] == 'candidatoId'){
            $c = $candidatoId['index'];
            $i = $candidatoId['id'];
            $st = "$c = $i AND id = '$serialOferta'";
            ?>
            <pre>
            <?php print_r($st); ?>

            </pre>
            <?php

        try {
            $wpdb->query("DELETE FROM $dtb WHERE $st");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        }
        if($candidatoId['index'] == 'contratistaId'){
            $c = $candidatoId['index'];
            $i = $candidatoId['id'];
            $st = "$c = $i AND serialOferta = '$serialOferta'";
        try {
            $wpdb->query("DELETE FROM $dtb WHERE $st");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        }


    }

    }

}