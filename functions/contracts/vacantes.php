<?php

function getAllAnounces($args = array())
{
  global $wpdb;
  $defaults = array(
  );
  $args = wp_parse_args($args, $defaults);
  $tabla =$wpdb->prefix . 'ofertalaboral';

  $data = $wpdb->get_results("SELECT * FROM $tabla where tipoPublic = 'Promoción' and publico=1", ARRAY_A);

  $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));

  ?>

    <?php if(count($data) > 0){
      $retorno = '';
       if(count($data) > 1){
       $retorno .= "<h3 class='anunTitle'>Anuncios de <span>Tsolucionamos</span></h3>";
      }elseif (count($data) == 1) {
        $retorno .= "<h3 class='anunTitle'>Anuncio de <span>Tsolucionamos</span></h3>";
      }
      $retorno .= '<div id="anounceLists" class="row">';
      foreach ($data as $key => $r) {
        $imagenes = $r['imagenes'];
        $serialOferta = $r['serialOferta'];
        $imagenes = json_decode($imagenes, true);
        $imgPrincipal = $imagenes['principal']['src'];
        $nombrePublicación = $r['nombreTrabajo'];
        $cargo = $r['tipoServicio'];
        $estadoPublico = $r['publico'];
        $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialOferta;
        $fechaInicio = $r['fechaInicio'];
        $descripcionExtra = $r['descripcionExtra'];

        $serial='?serial='.$r['serialOferta'];
        $pg = $pagina.$serial;

        $titulo = explode(' ', $nombrePublicación);
        $t = "<span class='resalte1'>$titulo[0]</span>";
        foreach ($titulo as $key => $value) {
            if($key != 0){
                $t.=' '.$value;
            }
        }
        $nombrePublicación = $t;
      $retorno .= "<a data-toggle='tooltip' data-placement='right' title='¡Click para ver!' class='aLink' href='$pg'><div class='card'>
              <div class='row'>
      <div class='contratistaImg col-5'>
      <img src='$imgPrincipal' >
      <span class='etiqueta'>¡Anuncio para $cargo!</span>
      </div>
      <div class='info col-7'>
      <h6><strong>$nombrePublicación</strong></h6>
        <div class='subInfo'>
          <div class='row'>
            <div class='col desc'>
              <p>
                $descripcionExtra
              </p>
            </div>
          </div>
        </div>
          </div>
        </div>
      </div></a>";
      }
  $retorno .= '</div>';

  return $retorno; ?>
  <?php } ?>

<?php }add_shortcode('getAllAnounces', 'getAllAnounces');


function getVacantesDisponibles($args = array())
{

  global $wpdb;
  $defaults = array(
  );
  $args = wp_parse_args($args, $defaults);

  // return $args;

  $tabla =$wpdb->prefix . 'ofertalaboral';

  $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and publico=1", ARRAY_A);



  // $data = null;
?>

<?php if(count($data) > 0){
$selectores = array();
foreach ($data as $key => $value) {
  array_push($selectores, $value['tipoServicio']);
}
$selectores = array_unique($selectores);


$tabla = $wpdb->prefix . 'ofertapostulantes';

$ooo = "<div id='listOfferts'>
<div class='container'>
  <div class='selectors row'>
  <h6>Filtrar por: </h6>
  <ul>";
    $ooo .= "<li onclick='filterAllVacants(`todos`)'>Todos</li>";
    foreach ($selectores as $key => $value) {
      $ooo .= "<li onclick='filterAllVacants(`$value`)'>
        $value
      </li>";
    }
  $ooo .= "</ul></div>
  <div class='list row'>";
   $ooo .= getStringVacantAll($data);
  $ooo.= "</div>;
</div>
</div>";

$pag = $vvv['pageno'];
$total_pages = $vvv['total_pages'];
$filterBy = $vvv['filterBy'];

$ooo .= "<div class='pagineishon'>";
$ooo .= paginate('famTab1', $pag, $total_pages );
$ooo .= "</div>";

return $ooo;

}else{
 $ooo ="<div id='listOfferts'>
      <div class='container'>
        <div class='row'>
          <h4 class='noResults'>No hay vacantes publicadas en este momento</h4>
        </div>
      </div>
    </div>";
  return $ooo;
}

}add_shortcode('getVacantesDisponibles', 'getVacantesDisponibles');



function getStringVacantAll($data){
  global $wpdb;


  if(isset($data['tipo']) && $data['tipo'] == 'selector'){
    $tipo = $data['tipo'];
    $data = $data['data'];
  }else{
    $tipo = false;
  }



  $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));
    foreach ($data as $r) {



  $ofertaId = $r['id'];
  $serial='?serial='.$r['serialOferta'];
  $pg = $pagina.$serial;

  $imagenes = $r['imagenes'];
  $imagenes = json_decode($imagenes, true);
  $imgPrincipal = $imagenes['principal']['src'];
  $nombreTrabajo = $r['nombreTrabajo'];
  $tipoServicio = $r['tipoServicio'];
  $salario = $r['sueldo'];
  $salario = moneyConversion('uy', $salario);
  $icono = '';

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


  if($tipo != 'selector'){


  $dataPostulantes = $wpdb->get_results("SELECT * from $tabla WHERE ofertaId = '$ofertaId'", ARRAY_A);
  $postulantes = (count($dataPostulantes) == 0)? 'Sin postulaciones': '<i class="fa fa-users" aria-hidden="true"></i> <span>'.count($dataPostulantes).'</span>';

  $ooo.= '
  <a class="vacantLink col-4" href="'.$pg.'">
  <div data-toggle="tooltip" data-placement="top" title="Vacante para '.$tipoServicio.'" class="public card">
    <div class="icon">
      '.$icono.'
    </div>
    <div class="info">
      <h6>'.$nombreTrabajo.'</h6>
      <div class="subInfo">
        <small data-toggle="tooltip" data-placement="bottom" title="Nro Postulantes">
          '.$postulantes.'
        </small>
        <small data-toggle="tooltip" data-placement="bottom" title="Salario propuesto">
          <strong>'.$salario.'</strong>
        </small>
      </div>
    </div>

  </div></a>';
  }elseif ($tipo == 'selector') {
  $dataPostulantes = $wpdb->get_results("SELECT * from $tabla WHERE ofertaId = '$ofertaId'", ARRAY_A);
  $postulantes = (count($dataPostulantes) == 0)? 'Sin postulaciones': '<i class="fa fa-users" aria-hidden="true"></i> <span>'.count($dataPostulantes).'</span>';

    ?>

       <a class="vacantLink col-4" href="<?php echo $pg ?>">
  <div data-toggle="tooltip" data-placement="top" title="Vacante para <?php echo $tipoServicio ?>" class="public card">
    <div class="icon">
      <?php echo $icono ?>
    </div>
    <div class="info">
      <h6><?php echo $nombreTrabajo ?></h6>
      <div class="subInfo">
        <small data-toggle="tooltip" data-placement="bottom" title="Nro Postulantes">
          <?php echo $postulantes ?>
        </small>
        <small data-toggle="tooltip" data-placement="bottom" title="Salario propuesto">
          <strong><?php echo $salario ?></strong>
        </small>
      </div>
    </div>

  </div></a>

  <?php }

  }

  return $ooo;

}

function getAllVacantes($args = array())
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

  // return $args;

  $tabla =$wpdb->prefix . 'ofertalaboral';

  if($args['all'] != false){
    $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and publico=1", ARRAY_A);
  }

  if($args['allnot'] != ''){

    $not = $args['allnot'];
    $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and serialOferta != '$not' and publico=1", ARRAY_A);

  }
  if($args['family'] != ''){
    $id = $args['family'];
    // return $id;
    $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id and publico=1", ARRAY_A);
  }
  if($args['family'] != '' && $args['type'] == 'gestion'){

    $tipoEspacio = 'gestioNFamilia';

    if( isset($args['paginado']) && $args['paginado'] == 1){


      $perPage = $args['porpagina'];
      $pageno = $args['pg'];
      $filtroPor = $args['filterby'];

      $offset = ($pageno-1) * $perPage;



      $id = $args['family'];

      $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id ", ARRAY_A);

      $total_rows = count($data);
      // $total_pages_sql = count($seriales);
      // $result = mysqli_query($conn,$total_pages_sql);
      // $total_rows = mysqli_fetch_array($result)[0];
      $total_pages = ceil($total_rows / $perPage);

      $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id LIMIT $perPage OFFSET $offset ", ARRAY_A);
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

      $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id ", ARRAY_A);

      $total_rows = count($data);
      // $total_pages_sql = count($seriales);
      // $result = mysqli_query($conn,$total_pages_sql);
      // $total_rows = mysqli_fetch_array($result)[0];
      $total_pages = ceil($total_rows / $perPage);

      $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id LIMIT $perPage OFFSET $offset ", ARRAY_A);
      $wpdb->flush();


      $vvv = array(
        'pageno' => $pageno,
        'total_pages' => $total_pages,
        'filterBy' => 'myVacants'
      );





    }

    // return;
  }

  // return "SELECT * FROM $tabla where estado != 'Anuncio en circulación' and contratistaId = $id and publico=1";

  $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));
  // $data = null;
?>

<?php if(count($data) > 0){
$selectores = array();
foreach ($data as $key => $value) {
  array_push($selectores, $value['tipoServicio']);
}
$selectores = array_unique($selectores);


$tabla = $wpdb->prefix . 'ofertapostulantes';

$ooo = "<div id='listOfferts'>
<div class='container'>
  <div class='list row'>";
   foreach ($data as $r) {

  $ofertaId = $r['id'];
  $serial='?serial='.$r['serialOferta'];
  $pg = $pagina.$serial;

  $imagenes = $r['imagenes'];
  $imagenes = json_decode($imagenes, true);
  $imgPrincipal = $imagenes['principal']['src'];
  $nombreTrabajo = $r['nombreTrabajo'];
  $tipoServicio = $r['tipoServicio'];
  $salario = $r['sueldo'];
  $salario = moneyConversion('uy', $salario);
  $icono = '';

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

  $dataPostulantes = $wpdb->get_results("SELECT * from $tabla WHERE ofertaId = '$ofertaId'", ARRAY_A);
  $postulantes = (count($dataPostulantes) == 0)? 'Sin postulaciones': '<i class="fa fa-users" aria-hidden="true"></i> <span>'.count($dataPostulantes).'</span>';
  $cola = ($tipoEspacio != 'gestioNFamilia')? 'col-4': 'col-6';
  $ooo.= '
  <a class="vacantLink '.$cola.' " href="'.$pg.'">
  <div data-toggle="tooltip" data-placement="top" title="Vacante para '.$tipoServicio.'" class="public card">
    <div class="icon">
      '.$icono.'
    </div>
    <div class="info">
      <h6>'.$nombreTrabajo.'</h6>
      <div class="subInfo">
        <small data-toggle="tooltip" data-placement="bottom" title="Nro Postulantes">
          '.$postulantes.'
        </small>
        <small data-toggle="tooltip" data-placement="bottom" title="Salario propuesto">
          <strong>'.$salario.'</strong>
        </small>
      </div>
    </div>

  </div></a>';
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

}else{
 $ooo ="<div id='listOfferts'>
      <div class='container'>
        <div class='row'>
          <h4 class='noResults'>No hay vacantes publicadas en este momento</h4>
        </div>
      </div>
    </div>";
  return $ooo;
}

}add_shortcode('getAllVacantes', 'getAllVacantes');


function getAllVacantWithPostulant($args = array())
{
  global $wpdb;
  $defaults = array(
      "rows" => 0
  );
  $args = wp_parse_args($args, $defaults);
  $tabla =$wpdb->prefix . 'ofertalaboral';
  $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and publico=1", ARRAY_A);
  $pagina = esc_url(get_permalink(get_page_by_title('Información de vacante')));
?>

<div class="container">
  <div class="row">

    <?php foreach ($data as $r) {
        $serial="?serial=".$r['serialOferta'];
        $pg = $pagina.$serial;
        ?>

    <div class="card col-md-3" style="width: 18rem;">
      <img class="card-img-top w-100" src="/wp-content/uploads/blank-profile-picture-973460_960_720.png"
        alt="Card image cap">
      <div class="card-body">
        <h5 class="card-title">
          <?php echo $r['nombreTrabajo'] ?>
        </h5>
        <h6 class="card-subtitle mb-2 text-muted">Se busca:
          <?php echo $r['tipoServicio'] ?>
        </h6>
        <p class="card-text">Descripción:
          <?php echo $r['descripcionExtra'] ?>
        </p>
        <!-- <a href="#" class="card-link"><i class="fa fa-envelope-o" aria-hidden="true"></i> Escribe al contratista</a> -->
        <a href="<?php echo $pg; ?>" class="d-flex justify-content-center align-items-center btn btn-success">
          <i class="fa fa-check" aria-hidden="true"></i> Ver vacante</a>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php }add_shortcode('getAllVacantWithPostulant', 'getAllVacantWithPostulant');




add_action('wp_ajax_processfilterAllVacants', 'processfilterAllVacants');
add_action('wp_ajax_nopriv_processfilterAllVacants', 'processfilterAllVacants');


function processfilterAllVacants(){


   global $wpdb;


      if (isset($_POST['processfilterAllVacants'])) {

        $data = stripslashes($_POST['processfilterAllVacants']);

        $data = json_decode($data, true);


        $tipo = $data['tipo'];

          $tabla =$wpdb->prefix . 'ofertalaboral';

        if($tipo == 'todos' || $tipo == ''){
          $data = $wpdb->get_results("SELECT * FROM $tabla where estado != 'Anuncio en circulación' and publico=1", ARRAY_A);

        }else{
          $data = $wpdb->get_results("SELECT * FROM $tabla as oferta where estado != 'Anuncio en circulación' and publico=1 and oferta.tipoServicio = '$tipo'", ARRAY_A);
        }


        $data = array(
          'data' => $data,
          'tipo' => 'selector'
      );


        getStringVacantAll($data);

      }





}




?>