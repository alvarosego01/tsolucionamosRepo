


<?php


function buttonRegisterNewProfesional($args = array())
{
    global $wpdb;
  $defaults = array(
     'rows' => ''
  );
  $args = wp_parse_args($args, $defaults);

  if(!is_user_logged_in()){



 // Si sos profesional independiente y queres aumentar tus ingresos... regístrate

    $ooo = "<div id='registerProfesionalSlider'>
            <h6>¡Si sos <span>Profesional Independiente</span> <br> y querés aumentar tus ingresos! </h6> <a href='/registro-profesional/?drec=/profesionales/nueva-publicacion-profesional/?pg=1' class='resalte1'>¡Registrate ahora!</a>
        </div>";


  return $ooo;
  }
  $currentId = get_current_user_id();
  if(is_user_logged_in() && validateUserProfileOwner($currentId, $currentId, 'profesional')){

    $ooo = "<div id='registerProfesionalSlider'>
            <h6>¡Ya sos <span>Profesional Independiente</span> <br> aumenta tus ingresos ahora! </h6> <a href='/profesionales/nueva-publicacion-profesional/?pg=1' class='resalte1'>¡Publica tu servicio!</a>
        </div>";


    return $ooo;
  }





}add_shortcode('buttonRegisterNewProfesional', 'buttonRegisterNewProfesional');


  function getAllProfesionals($args = array())
{

  global $wpdb;
  $defaults = array(
      "rows" => 0,
      "allnot" => '',
      "family" => '',
      'all' => false,
      'type' => '',
      'paginado' => 0,
      'porpagina' => 0,
      'pg' => 0,
      'filterby' => '',
  );
  $args = wp_parse_args($args, $defaults);

  $separación = 'col-md-12 col-lg-4';

  // return $args;

  $tabla =$wpdb->prefix . 'public_profesional';

  if($args['all'] != false){
    $data = $wpdb->get_results("SELECT * FROM $tabla where publico = 1", ARRAY_A);
  }

  if($args['allnot'] != ''){

    $not = $args['allnot'];
    $data = $wpdb->get_results("SELECT * FROM $tabla where serialOferta != '$not' and publico=1", ARRAY_A);

  }
  if($args['family'] != ''){
    $id = $args['family'];
    // return $id;
    $data = $wpdb->get_results("SELECT * FROM $tabla where contratistaId = $id and publico=1", ARRAY_A);
  }
  if($args['family'] != '' && $args['type'] == 'gestion'){

    $separación = 'col-md-12 col-lg-4';

    if( isset($args['paginado']) && $args['paginado'] == 1){


      $perPage = $args['porpagina'];
      $pageno = $args['pg'];
      $filtroPor = $args['filterby'];

      $offset = ($pageno-1) * $perPage;

      $id = $args['family'];

      $data = $wpdb->get_results("SELECT * FROM $tabla where candidatoId = $id ", ARRAY_A);

      $total_rows = count($data);
      // $total_pages_sql = count($seriales);
      // $result = mysqli_query($conn,$total_pages_sql);
      // $total_rows = mysqli_fetch_array($result)[0];
      $total_pages = ceil($total_rows / $perPage);

      $data = $wpdb->get_results("SELECT * FROM $tabla where candidatoId = $id LIMIT $perPage OFFSET $offset ", ARRAY_A);
      $wpdb->flush();

      $vvv = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => $filtroPor
      );

    }else{

      $perPage = 9;
      $pageno = 1;
      $offset = ($pageno-1) * $perPage;


      $id = $args['family'];

      $data = $wpdb->get_results("SELECT * FROM $tabla where candidatoId = $id ", ARRAY_A);

      $total_rows = count($data);
      // $total_pages_sql = count($seriales);
      // $result = mysqli_query($conn,$total_pages_sql);
      // $total_rows = mysqli_fetch_array($result)[0];
      $total_pages = ceil($total_rows / $perPage);

      $data = $wpdb->get_results("SELECT * FROM $tabla where candidatoId = $id LIMIT $perPage OFFSET $offset ", ARRAY_A);
      $wpdb->flush();


      $vvv = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => 'myVacants'
      );

    }



  }

  // return "SELECT * FROM $tabla where contratistaId = $id and publico=1";

  $pagina = esc_url(get_permalink(get_page_by_title('Información de profesional')));
?>

<?php if(count($data) > 0){

$ooo = "<div id='listOfProfesionals'>
  <h3 class='anunTitle'>Publicaciones <span>Profesionales</span></h3>
<div class='container'>
  <div class='row rowList'>";
   foreach ($data as $r) {
  $serial='?serial='.$r['id'];
  $pg = $pagina.$serial;

  $imagenes = $r['media'];
  // $imagenes = stripslashes($imagenes);
  $imagenes = json_decode($imagenes, true);
  $imagenes = $imagenes['imagesProfeshional'];
  $imgPrincipal = $imagenes[0]['src'];
  $imgPrincipal = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$imgPrincipal : $imgPrincipal;

  $nombreTrabajo = $r['tituloPublicacion'];

  $ooo.= "<div   class='col-md-12 col-lg-4 gridOffer'>
    <div class='card elementProfesional' style='width: 18rem;'>

      <img class='card-img-top w-100' src='$imgPrincipal' alt='Card image cap'>

      <div class='card-body'>
      <h5 class='card-title'>$nombreTrabajo</h5>
      <a href='$pg' class='goBotton d-flex justify-content-center align-items-center btn btn-primary'>Ver</a>
      </div>

      </div>
    </div>";
     }
  $ooo.= "</div>
</div>
</div>";

$pag = $vvv['pageno'];
$total_pages = $vvv['total_pages'];
$filterBy = $vvv['filterBy'];

$ooo .= "<div class='pagineishon'>";
$ooo .= paginate('famTab1', $pag, $total_pages );
$ooo .= "</div>";



return $ooo;
  }
  else{

    $ooo = "<div id='listOfProfesionals'>
  <h3 class='anunTitle'>No hay publicaciones profesionales en este momento</h3>
  </div>";

  return $ooo;

  }


}add_shortcode('getAllProfesionals', 'getAllProfesionals');




function balancePresupuesto($data){

  global $wpdb;


  $tabla =$wpdb->prefix . 'presupuesto_profesional';
  $infoOferta = $wpdb->get_results("SELECT * FROM $tabla where servicio='$data' ", ARRAY_A);


    if(count($infoOferta) > 0){

       return '<h6 >'.count($infoOferta) .' Solicitudes de <span class="resalte1" >Presupuesto</span></h6>';

    }else{
       return '<h6>Sin solicitudes de <span class="resalte1">Presupuesto</span></h6>';
    }

}


add_action('wp_ajax_sendsolicitaPresupuesto', 'sendsolicitaPresupuesto');
add_action('wp_ajax_nopriv_sendsolicitaPresupuesto', 'sendsolicitaPresupuesto');
function sendsolicitaPresupuesto(){

    global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "ambos")) {
      if (isset($_POST['sendsolicitaPresupuesto'])) {
          // recibe json y quita los slash
          $data = preg_replace('/\\\\\"/', "\"", $_POST['sendsolicitaPresupuesto']);
          // transforma el string a un array asociativo
          $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos



          dbsendsolicitaPresupuesto($data);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }


}

function dbsendsolicitaPresupuesto($data){

   global $wpdb;

  $nombre = $data['nombre'];
  $telefono = $data['telefono'];
  $departamentos = $data['departamentos'];
  $ciudadDireccion = $data['ciudadDireccion'];
  $fechaServicio = $data['fechaServicio'];
  $hora = $data['hora'];
  $detallesServicio = $data['detallesServicio'];
  $solicitado = $data['solicitado'];

  $serial = $solicitado['serial'];
  $idBy = $solicitado['idBy'];


  $tabla =$wpdb->prefix . 'public_profesional';
  $publicProfesional = $wpdb->get_results("SELECT * FROM $tabla where id = '$serial'", ARRAY_A);

  $candidatoId = $publicProfesional[0]['candidatoId'];
  $tituloProfesional = $publicProfesional[0]['tituloPublicacion'];

  $datosSolicitante = getInfoNameEmailUsers($idBy);
  $datosCandidato = getInfoNameEmailUsers($candidatoId);


  $datosContacto = array(
    'nombre' => $nombre,
    'telefono' => $telefono,
    'email' => $datosSolicitante['email'],
    'departamento' => $departamentos,
    'ciudadDireccion' => $ciudadDireccion
  );

  $datosContacto = json_encode($datosContacto, JSON_UNESCAPED_UNICODE);


    // return;
    $datos = array(
      'candidatoId' => sanitize_text_field( $candidatoId ),
      'familiaId' => sanitize_text_field( $idBy ),
      'nombre' => sanitize_text_field( $nombre ),
      'datosContacto' => sanitize_text_field( $datosContacto ),
      'nombrePresupuesto' => $nombre.' solicita presupuesto a '. $datosCandidato['nombre']. '('.$datosCandidato['rol'].') por el servicio '.$publicProfesional[0]['tituloPublicacion'],
      'requerimientos' => sanitize_text_field( $detallesServicio ),
      'departamento' => sanitize_text_field( $departamentos ),
      'direccion' => sanitize_text_field( $ciudadDireccion ),
      'fecha' => sanitize_text_field( $fechaServicio ),
      'hora' => sanitize_text_field( $hora ),
      'redesSociales' => '-',
      'telefono' => sanitize_text_field( $telefono ),
      'email' => sanitize_text_field( $datosSolicitante['email'] ),
      'visto' => '-',
      'servicio' => sanitize_text_field( $serial ),
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
        '%s'
    );

    $tabla = $wpdb->prefix . 'presupuesto_profesional';

    $wpdb->insert($tabla, $datos, $formato);
    $wpdb->flush();



return;

    $canId = $candidatoId;
     $famId = $idBy;

    $candidatoInfo = $datosSolicitante;
    $familiaInfo = $datosCandidato;

    $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de profesional'))).'?serial='.$serialVacante;

    // familia
     $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha solicitado información de presupuesto por los servicios de tu publicación profesional <a href="'.$vacanteUrl.'" class="hiper">'.$tituloProfesional.'</a>. <br><br>
      Mensaje de solicitud: <br>
      Nombre de solicitante: '.$nombre.' <br>
      Teléfono: '.$telefono.' <br>
      Departamento: '.$departamento.' <br>
      Dirección: '.$direccion.' <br>
      Fecha de solicitud: '.$fecha.' <br>
      Hora: '.$hora.' <br>
      Email: '.$email.' <br>
      Detalles de requisitos: '.$requerimientos.' <br>
     ';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'ha solicitado presupuesto por los servicios de tu publicación profesional '.$tituloProfesional,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => $familiaInfo['email'],
         'usuarioMuestra' => $familiaInfo['id']
     );
     saveNotification($mensaje);
    // familia
      $msj = 'Solicitaste información de presupuesto por los servicios de la publicación profesional <a href="'.$vacanteUrl.'" class="hiper">'.$tituloProfesional.'</a>. <br><br>
      Mensaje de solicitud: <br>
      Nombre de solicitante: '.$nombre.' <br>
      Teléfono: '.$telefono.' <br>
      Departamento: '.$departamento.' <br>
      Dirección: '.$direccion.' <br>
      Fecha de solicitud: '.$fecha.' <br>
      Hora: '.$hora.' <br>
      Email: '.$email.' <br>
      Detalles de requisitos: '.$requerimientos.' <br>
     ';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => 'Has solicitado información de presupuesto por los servicios de la publicación profesional '.$tituloProfesional,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => $candidatoInfo['email'],
         'usuarioMuestra' => $candidatoInfo['id']
     );
     saveNotification($mensaje);
     // parte Admin


    $msj = '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha solicitado información de presupuesto por los servicios de la publicación profesional <a href="'.$vacanteUrl.'" class="hiper">'.$tituloProfesional.'</a>. <br><br>
      Mensaje de solicitud: <br>
      Nombre de solicitante: '.$nombre.' <br>
      Teléfono: '.$telefono.' <br>
      Departamento: '.$departamento.' <br>
      Dirección: '.$direccion.' <br>
      Fecha de solicitud: '.$fecha.' <br>
      Hora: '.$hora.' <br>
      Email: '.$email.' <br>
      Detalles de requisitos: '.$requerimientos.' <br>
     ';
     $mensaje = array(
         'mensaje' => $msj,
         'subject' => '<strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> ha solicitado presupuesto por los servicios de la publicación profesional '.$tituloProfesional,
         'estado' => 0,
      // 'fecha' => ,
         'tipo' => 'definCand',
         'email' => '',
         'usuarioMuestra' => 'Tsoluciono'
     );
     saveNotification($mensaje);




}



add_action('wp_ajax_updateProfesional', 'updateProfesional');
add_action('wp_ajax_nopriv_updateProfesional', 'updateProfesional');
function updateProfesional(){


  global $wpdb;
  $id = um_user('ID');
  $currentId = get_current_user_id();

  if (validateUserProfileOwner($currentId, $currentId, "profesional") || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") ) {


    if( (isset($_POST['updateProfesional'])) || (isset($_POST['action']) && ($_POST['action'] == 'updateProfesional') ) ){
          // recibe json y quita los slash
          // $data = preg_replace('/\\\\\"/', "\"", $_POST);
          // transforma el string a un array asociativo
          // $data = json_decode($data, true);
          // se envia la información tipo json para que se cargue en la base de datos
          $_POST['dataProfesional'] = stripslashes($_POST['dataProfesional']);
          $_POST['dataProfesional'] = json_decode($_POST['dataProfesional'], true);


          $dataReturned = array(
            // 'tipo' => 'retorno3',
            'informacion' => $_POST,
            'archivos' => $_FILES
          );
          echo ' la data ';
          print_r($dataReturned);



          dbupdateProfesional($dataReturned);
      }
  } else {
      $pagina = esc_url(get_permalink(get_page_by_title('Inicio')));
      die();
  }




}


// Array
// (
//     [informacion] => Array
//         (
//             [titulopublicacion] =>
//             [nombreEmpresa] =>
//             [categoria] => null
//             [otroServicio] =>
//             [departamento] => null
//             [ciudad] =>
//             [direccion] =>
//             [telefono] =>
//             [descripcion] =>
//             [instagram] => sdfsdf
//             [facebook] => fsdfs
//             [twitter] => dfsdf
//             [tipo] => update
//        [dataProfesional] => Array
// (
//   [serialOferta] => Array
//       (
//           [idPublicacion] => 5ef65255b3a955ef65255b3a98
//       )

// [auxOrden] => Array
// (
//   [0] => Array
//       (
//           [nro] => 2
//           [dis] => false
//       )

//   [1] => Array
//       (
//           [nro] => 1
//           [dis] => true
//       )

//   [2] => Array
//       (
//           [nro] => 0
//           [dis] => false
//       )

// )


// )

//             [action] => updateProfesional
//         )

// [archivos] => Array
// (
//     [logo] => Array
//         (
//             [name] => left-brain-right-brain.jpg
//             [type] => image/jpeg
//             [tmp_name] => C:\xampp\tmp\php7156.tmp
//             [error] => 0
//             [size] => 70636
//         )

//     [imagenes] => Array
//         (
//             [name] => esto.PNG
//             [type] => image/png
//             [tmp_name] => C:\xampp\tmp\php7187.tmp
//             [error] => 0
//             [size] => 78962
//         )

//     [video] => Array
//         (
//             [name] =>
//             [type] =>
//             [tmp_name] =>
//             [error] => 4
//             [size] => 0
//         )

//     [imagesProfeshional] => Array
//         (
//             [name] => Array
//                 (
//                     [0] => coordCarlos - copia.jpg
//                     [1] => descarga.jpg
//                     [2] => esto.PNG
//                 )

//             [type] => Array
//                 (
//                     [0] => image/jpeg
//                     [1] => image/jpeg
//                     [2] => image/png
//                 )

//             [tmp_name] => Array
//                 (
//                     [0] => C:\xampp\tmp\php7188.tmp
//                     [1] => C:\xampp\tmp\php7189.tmp
//                     [2] => C:\xampp\tmp\php718A.tmp
//                 )

//             [error] => Array
//                 (
//                     [0] => 0
//                     [1] => 0
//                     [2] => 0
//                 )

//             [size] => Array
//                 (
//                     [0] => 57378
//                     [1] => 5399
//                     [2] => 78962
//                 )

//         )

// )


function dbupdateProfesional($data){



  global $wpdb;

    $id = um_user('ID');
    $currentId = get_current_user_id();
    $idPublicacion = $data['informacion']['dataProfesional']['serialOferta']['idPublicacion'];
    $tablaPublicProfesional = $wpdb->prefix . 'public_profesional';


    $x = $wpdb->get_results("SELECT * from $tablaPublicProfesional WHERE id='$idPublicacion'", ARRAY_A);
    $wpdb->flush();


    if ( isset($x) && count($x) > 0 ){
      $x = $x[0];
    }

    if( (isset($x) ) && ( $currentId == $x['candidatoId'] || validateUserProfileOwner($currentId, $currentId, "adminTsoluciono") ))
    {


      echo 'toda la info nueva';
      print_r($data);

      $informacion = $data['informacion'];
      $archivos = $data['archivos'];

      $informacion['dataProfesional']['auxOrden'] = json_decode($informacion['dataProfesional']['auxOrden'], true);


      $nuevoOrdenFotos = $informacion['dataProfesional']['auxOrden'];

// ------------ nuevos datos
    $titulopublicacion =  sanitize_text_field($informacion['titulopublicacion']);
    $nombreEmpresa =  sanitize_text_field($informacion['nombreEmpresa']);
    $categoria =  sanitize_text_field($informacion['categoria']);
    $otroServicio =  sanitize_text_field($informacion['otroServicio']);
    $departamento =  sanitize_text_field($informacion['departamento']);
    $ciudad =  sanitize_text_field($informacion['ciudad']);
    $direccion =  sanitize_text_field($informacion['direccion']);
    $telefono =  sanitize_text_field($informacion['telefono']);
    $descripcion =  sanitize_text_field($informacion['descripcion']);
    $instagram =  sanitize_text_field($informacion['instagram']);
    $facebook =  sanitize_text_field($informacion['facebook']);
    $twitter =  sanitize_text_field($informacion['twitter']);

// datos originales
    $redesSociales = $x['redesSociales'];
    $redesSociales = json_decode($redesSociales, true);
    if($instagram != ''){
      $redesSociales['instagram'] = $instagram;
    }
    if($facebook != ''){
      $redesSociales['facebook'] = $facebook;
    }
    if($twitter != ''){
      $redesSociales['twitter'] = $twitter;
    }
// -------------------------
    $newMedia = [];
    // actualizando orden de fotos // funciona
    $mediaOriginal = json_decode($x['media'], true);
    $imgs = $mediaOriginal['imagesProfeshional'];
    $imagenesCopia = [];

    foreach ($nuevoOrdenFotos as $key => $value) {

      if($value['dis'] == 'true'){


        $url = $imgs[$value['nro']]['src'];
//
        //
        // $path = parse_url($url, PHP_URL_PATH); // Remove "http://localhost"
        // $fullPath = get_home_path() . $path;
        // unlink($fullPath);

        eliminarArchivo($url);

      }else{

        array_push($imagenesCopia, $imgs[$value['nro']]);

      }

    }


    if( isset($archivos['video']) && count($archivos['video']) > 0 ){

      eliminarArchivo($mediaOriginal['video']['src']);

      $video[0] = $archivos['video'];
      $video = imagesToArray($video);
      $iii = array(
          'imagenes' => $video,
          'carpeta' => '/pubprofesional',
          'serial' => $idPublicacion,
      );
      $videoJson = cargarImagenes($iii);
      $videoJson = json_decode($videoJson, true);
      $newMedia['video'] = $videoJson;

    }

    if( isset($archivos['logo']) && count($archivos['logo']) > 0 ){

      eliminarArchivo($mediaOriginal['logo']['src']);

      $logo[0] = $archivos['logo'];
      $logo = imagesToArray($logo);
      // $logo[0] = $logo;


      $iii = array(
          'imagenes' => $logo,
          'carpeta' => '/pubprofesional',
          'serial' => $idPublicacion,
      );

      $logoJson = cargarImagenes($iii);
      $logoJson = json_decode($logoJson, true);
      $logo['logo'] = $logoJson;
      $logo = $logoJson;
      // $logo = json_encode($logo, JSON_UNESCAPED_UNICODE);

      $newMedia['logo'] = $logo;


      // $logo = imagesToArray($logo);

      // $iii = array(
      //     'imagenes' => $logo,
      //     'carpeta' => '/pubprofesional',
      //     'serial' => $idPublicProfesional,
      // );

      // $logoJson = cargarImagenes($iii);
      // $logoJson = json_decode($logoJson, true);
      // $media['logo'] = $logoJson;
      // $logo = $logoJson;
      // $logo = json_encode($logo, JSON_UNESCAPED_UNICODE);

    }

    if( isset($archivos['imagesProfeshional']['name']) && count($archivos['imagesProfeshional']['name']) > 0 ){


      $newFiles = transformMultipleIMG($archivos['imagesProfeshional']);


      $imagesProfeshional = imagesToArray($newFiles);


      $iii = array(
        'imagenes' => $imagesProfeshional,
        'carpeta' => '/pubprofesional',
        'serial' => $idPublicacion,
      );
    $imagesProfeshionalJson = cargarImagenes($iii);
    $imagesProfeshionalJson = json_decode($imagesProfeshionalJson, true);
    $newFiles = $imagesProfeshionalJson;

    foreach ($newFiles as $key => $value) {

      array_push($imagenesCopia, $value);

    }


    $newMedia['imagesProfeshional'] = $imagenesCopia;

  }else{
    $newMedia['imagesProfeshional'] = $imagenesCopia;
  }


  $textoGuardar = null;

if($titulopublicacion != ''){
  $textoGuardar .= "tituloPublicacion = '$titulopublicacion', ";
}
if($nombreEmpresa != ''){
  $textoGuardar .= "nombreEmpresa = '$nombreEmpresa', ";
}
if($categoria != ''){
  $textoGuardar .= "categoria = '$categoria', ";
}
if($otroServicio != ''){

}
if($departamento != ''){
  $textoGuardar .= "departamento = '$departamento', ";
}
if($ciudad != ''){
  $textoGuardar .= "ciudad = '$ciudad', ";
}
if($direccion != ''){
  $textoGuardar .= "direccion = '$direccion', ";
}
if($telefono != ''){
  $textoGuardar .= "telefono = '$telefono', ";
}
if($descripcion != ''){
  $textoGuardar .= "detalles = '$descripcion', ";
}

// $mediaOriginal

if(isset($newMedia['imagesProfeshional']) && count($newMedia['imagesProfeshional']) > 0){
  $mediaOriginal['imagesProfeshional'] = $newMedia['imagesProfeshional'];
}

if(isset($newMedia['video']) && count($newMedia['video']) > 0){
  $mediaOriginal['video'] = $newMedia['video'];
}

if(isset($newMedia['logo']) && count($newMedia['logo']) > 0){
  $mediaOriginal['logo'] = $newMedia['logo'];
  $lgg = json_encode($newMedia['logo'], JSON_UNESCAPED_UNICODE);
  $textoGuardar .= "logo = '$lgg', ";
}

$mediaOriginal = json_encode($mediaOriginal, JSON_UNESCAPED_UNICODE);
$textoGuardar .= "media = '$mediaOriginal', ";


$redesSociales = json_encode($redesSociales);
$textoGuardar .= "redesSociales = '$redesSociales'";

// echo 'texto a guardar    '. $textoGuardar;



// [titulopublicacion] => aaa
// [nombreEmpresa] => bbb
// [categoria] => Alimentos y Bebidas
// [otroServicio] =>
// [departamento] => Cerro Largo
// [ciudad] => sdfsdf
// [direccion] => dsfsdfdsf
// [telefono] => sdfsdfs
// [descripcion] => afasfdfsafadf



// tituloPublicacion
// nombreEmpresa
// categoria
// detalles
// logo
// departamento
// ciudad
// direccion
// telefono
// redesSociales
// email
// media




    try {
      $wpdb->query(" UPDATE $tablaPublicProfesional SET $textoGuardar WHERE id='$idPublicacion'");
  } catch (Exception $e) {
      echo 'Caught exception: ', $e->getMessage(), "\n";
  }


    }




}




// // formas
// )
// antes de transformar imagenesArray
// (
//     [0] => Array
//         (
//             [name] => adasdd.jpg
//             [type] => image/jpeg
//             [tmp_name] => C:\xampp\tmp\php3EA4.tmp
//             [error] => 0
//             [size] => 47858
//         )

//     [1] => Array
//         (
//             [name] => coordCarlos - copia.jpg
//             [type] => image/jpeg
//             [tmp_name] => C:\xampp\tmp\php3EA5.tmp
//             [error] => 0
//             [size] => 57378
//         )

//     [2] => Array
//         (
//             [name] => coordCarlos.jpg
//             [type] => image/jpeg
//             [tmp_name] => C:\xampp\tmp\php3EA6.tmp
//             [error] => 0
//             [size] => 57378
//         )

//     [3] => Array
//         (
//             [name] =>
//             [type] =>
//             [tmp_name] =>
//             [error] =>
//             [size] =>
//         )

// )
// como queda imagenesArray
// (
//     [0] => Array
//         (
//             [formato] => jpeg
//             [file] => C:\xampp\tmp\php3EA4.tmp
//         )

//     [1] => Array
//         (
//             [formato] => jpeg
//             [file] => C:\xampp\tmp\php3EA5.tmp
//         )

//     [2] => Array
//         (
//             [formato] => jpeg
//             [file] => C:\xampp\tmp\php3EA6.tmp
//         )

// )

?>




