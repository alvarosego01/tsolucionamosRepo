


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

  $separación = 'col-4';

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

    $separación = 'col-4';

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
  <div class='row'>";
   foreach ($data as $r) {
  $serial='?serial='.$r['id'];
  $pg = $pagina.$serial;

  $imagenes = $r['logo'];
  // $imagenes = stripslashes($imagenes);
  $imagenes = json_decode($imagenes, true);
  $imgPrincipal = $imagenes[0]['src'];
  $imgPrincipal = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$imgPrincipal : $imgPrincipal;

  $nombreTrabajo = $r['tituloPublicacion'];

  $ooo.= "<div class=' $separación  gridOffer'>
    <div class='card' style='width: 18rem;'>
      <img class='card-img-top w-100' src='$imgPrincipal' alt='Card image cap'>
      <div class='card-body'>
        <h5 class='card-title'>$nombreTrabajo</h5>
        <a href='$pg' class='d-flex justify-content-center align-items-center btn btn-primary'>Ver vacante</a>
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


?>