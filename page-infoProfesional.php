<?php
/*
Template Name: Información profesional */

 get_header();
$paginaHome = esc_url(get_permalink(get_page_by_title('Home')));

//  return;
if( (isset($_GET['serial'])) && ($_GET['serial'] != '')){

    global $wpdb;
    global $wp_role;
    $serialOferta = $_GET['serial'];
    $tabla = $wpdb->prefix . 'public_profesional';
    $infoOferta = $wpdb->get_results("SELECT * FROM $tabla where id='$serialOferta' ", ARRAY_A);


    if(isset($infoOferta) && count($infoOferta) == 0){
    $pgg = esc_url(get_permalink(get_page_by_title('Profesionales')));
    header('Location: '.$pgg);
    die();
    }
    if(isset($infoOferta) && (count($infoOferta) > 0)){

    $infoOferta = $infoOferta[0];
    $candidatoId = $infoOferta['candidatoId'];
	$plan = $infoOferta['plan'];
	$estado = $infoOferta['estado'];
	$nombreEmpresa = $infoOferta['nombreEmpresa'];
	$titulo = $infoOferta['tituloPublicacion'];
	$categoria = $infoOferta['categoria'];
	$fechaCreacion = $infoOferta['fechaCreada'];
	$detalles = $infoOferta['detalles'];
	$logo = $infoOferta['logo'];
	$direccion = $infoOferta['direccion'];
	$departamento = $infoOferta['departamento'];
	$horario = $infoOferta['horario'];
	$ciudad = $infoOferta['ciudad'];
	$redesSociales = $infoOferta['redesSociales'];
	$telefono = $infoOferta['telefono'];
	$email = $infoOferta['email'];
	$media = $infoOferta['media'];
	$factura = $infoOferta['factura'];
	$publico = $infoOferta['publico'];

	$logo = json_decode($logo, true);
	$logo = $logo[0]['src'];

	$media = json_decode($media, true);
	$media = $media['imagesProfeshional'];

	$logo = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$logo : $logo;

	  $titulo = explode(' ', $titulo);

    $t = "<span class='resalte1'>$titulo[0]</span>";
    foreach ($titulo as $key => $value) {
        if($key != 0){
            $t.=' '.$value;
        }
    }
    $titulo = $t;

    // $inicio = tranformMeses($fechaInicio);
    // $fin = tranformMeses($fechaFin);


    // otras publicaciones
    $otrasPublicaciones = $wpdb->get_results("SELECT * FROM $tabla where id!='$serialOferta' ", ARRAY_A);


    ?>

<div id="offerInfo" class="offerInfo">


        <div class="info1">
            <div class="container">

                <div class="row">
                    <div class="col-6 imgPrincipal">
                    <div class="img">
                       <img src="<?php echo $logo; ?>" alt="">
                    </div>
                    </div>
                    <div class="col-6 details">
                        <div class="titulo">
                            <h6>
                                Publicado <?php echo $fechaCreacion ?>
                            </h6>
                            <h1>
                                <?php echo $titulo; ?>
                            </h1>
                        </div>

                        <div class="desc">
                            <!-- <h5>Detalles</h5> -->
                            <div class="row">
                                <div class="col-5 descp">
                                    <p>
                                       <strong>Titular o empresa:</strong> <br>
                                       <?php echo $nombreEmpresa ?> <br>
                                       <strong>Tipo de servicio:</strong> <br>
                                       <?php echo $categoria ?> <br>
                                       <strong>Horario de atención:</strong> <br>
                                       <?php echo $horario ?> <br>

                                    </p>
                                </div>
                                <div class="col-5 descp">
                                <p>
                                   <strong>Departamento:</strong> <br>
                                   <?php echo $departamento ?> <br>
                                   <strong>Ciudad:</strong> <br>
                                   <?php echo $ciudad ?> <br>

                                </p>
                                </div>
                            </div>

                        </div>

						<?php if( is_user_logged_in() && (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ))){

                            $listadoAdmin = esc_url(get_permalink(get_page_by_title('Admin profesionales')));
                            ?>

                            <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom">

                                  <a href="<?php echo $listadoAdmin.'#tab9' ?>" class='btn btn-success btn-block'>Volver al listado de publicaciones</a>

                                </div>
                            </div>
                        </div>

                        <?php } ?>

                        <?php if( is_user_logged_in() &&
                            (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ) ||
                            validateUserProfileOwner( get_current_user_id(), $candidatoId, "candidata" ) )
                            ){
                            ?>
                            <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom" style="margin: 5px 0">

                                  <a href="#" onclick="deletePublicOfert('<?php echo $serialOferta ?>')" class='btn btn-danger btn-block'>Eliminar publicación</a>

                                </div>
                                </div>
                            </div>
                            <?php } ?>

						<?php if( (is_user_logged_in()) && (get_current_user_id() != $candidatoId) && (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "ambos" ))){ ?>


                        <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom">

                                	<?php $x = array(
                                		'serial' => $serialOferta,
                                		'idBy' => get_current_user_id()
                                	);

                                	$x = json_encode($x);
                                	?>

                                  <button onclick='solicitaPresupuesto(<?php echo $x ?>)' class='btn btn-success btn-block'>¡Solicitar presupuesto!</button>'

                                </div>
                            </div>
                        </div>

                        	<?php } ?>

                    </div>
                </div>

            </div>
        </div>

        <div class="info2">
            <div class="container">
                <div class="row rowDesc">

                    <div class="descriptions">
                        <p>
                            <strong>Descripción del servicio</strong> <br>
                            <?php echo $detalles; ?>
                        </p>
                    </div>
                </div>

                <div class="row imagenesProfesional">

					 <p >
                            <strong>Imagenes del servicio</strong> <br>
                      </p>
                	<div class="imgs">


                		<?php foreach ($media as $key => $value) {

                			$img = $value['src'];

                			$img = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$img : $img;

                			?>

						<a href="<?php echo $img; ?>" data-toggle="lightbox" data-gallery="example-gallery">
            			    <img src="<?php echo $img; ?>" class="img-fluid">
            			</a>

                		<?php } ?>

                	</div>

                </div>

        </div>
        </div>

        <div class="info3">
            <div class="row extraButton">

                <div class="container">
                    <div class="row title">

                        <h5>¿Tienes preguntas? <span class="resalte1">Tsolucionamos</span> tus dudas</h5>
                        <a href="/contacto" class="btn btn-success">CONTACTO</a>
                    </div>

                </div>
            </div>
            <div class="row others">

            </div>
        </div>



        <div class="info5">

            <div class="container">
                <div id="state" class="state">


					<?php echo balancePresupuesto($serialOferta); ?>
                </div>

            </div>
        </div>



        <div class="info6">

            <div class="container">

                <a href="<?php echo get_home_url(); ?>" class="btn btn-primary"><i class="fa fa-home" aria-hidden="true"></i> Menú principal</a>

            </div>
        </div>



</div>





  <div class="formDataPresupuesto" id="formDataPresupuesto" style="display:none;">

  	<?php
  $departamentos = getDepartaments();
  	 ?>

        <form action="" method="post" class="formData">

	<div class="row">
    <div class="form-group col field nombre">
      <label for="nombre">Tu nombre</label>
      <input class="form-control" name="nombre" type="text" id="nombre">
      <small class="validateMessage"></small>
    </div>


    <div class="form-group col field telefono">
      <label for="telefono">Teléfono</label>
      <input class="form-control" name="telefono" type="text" id="telefono">
      <small class="validateMessage"></small>
    </div>


    </div>


    <div class="row">

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

    <div class="form-group field col ciudadDireccion">
    	  <label for="ciudadDireccion">Ciudad / Dirección</label>
    	  <input class="form-control" name="ciudadDireccion" type="text" id="ciudadDireccion">
    	  <small class="validateMessage"></small>
    </div>

    </div>

    <div class="row">
    	<div class="form-group field col fechaServicio">
    	  <label for="fechaServicio">Fecha de servicio</label>
    	  <input class="form-control" name="fechaServicio" type="text" id="fechaServicio">
    	  <small class="validateMessage"></small>
    	</div>

    	<div class="form-group field col hora">
    	  <label for="hora">Hora</label>
    	  <input class="form-control" name="hora" type="text" id="hora">
    	  <small class="validateMessage"></small>
    	</div>
    </div>


 <div class="form-group field detallesServicio">
      <label for="detallesServicio">Cuéntanos tus necesidades</label>
      <textarea class="form-control" id="detallesServicio" name="detallesServicio" rows="3"></textarea>
    </div>


        </form>

    </div>





<?php
    }else{
        header('Location: '.$paginaHome);
    }
}else{
    header('Location: '.$paginaHome);
}
get_footer();?>

