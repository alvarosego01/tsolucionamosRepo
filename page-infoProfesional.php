<?php
/*
Template Name: Información profesional */

 get_header();


function formUpdatePublicProfesional($imagenes){ ?>


  <div style="display: none" class="formUpdateProfesional" id="formUpdateProfesional">

<form action="" method="post" class="formData" id="formDataUpdate">
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
            <option selected value=""> </option>
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
  <option selected value=""> </option>
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

    <?php if(isset($imagenes) && count($imagenes) > 0){ ?>
    <div class="row imgList">

    <label>Orden de imagenes</label>
<ul class="boxImg">


<?php foreach ($imagenes as $key => $value) {
        $img = $value['src'];
        $img = ($_SERVER['SERVER_NAME'] == 'localhost')?'/tsolucionamos'.$img : $img;
    ?>
    <li dis="false" nro="<?Php echo $key ?>">

        <div class="drag">
        <i class="fa fa-bars handle" aria-hidden="true"></i>
        </div>
        <img src="<?php echo $img ?>" alt="">

        <?php if ( $value === reset( $imagenes ) ) {  ?>
            <small>
            Imagen principal
            </small>
       <?Php } ?>

       <div class="opc">
            <button type="button" class="btn btn-danger " >
            <i class="fa fa-trash" aria-hidden="true"></i>
            </button>
       </div>


    </li>
<?php } ?>

</ul>


    </div>
<?php  } ?>

    <?php
    if(count($imagenes) >= 1 && count($imagenes) <= 9){ ?>


    <div class="row imagenes">

      <div class="field col form-group imagenes">
        <label for="imagenes">Añadir imágenes</label>
        <input type="file" class="form-control" id="imagenes" name="imagenes"  accept="image/jpeg, image/png"  multiple min="1" max="3"/>
        <!-- <input onchange="" type="file" class="form-control" id="imagenes" name="imagenes"  accept="image/jpeg, image/png"  multiple/> -->
        <small>Tienes <?php echo count($imagenes) ?> imágenes, puedes tener máximo 10</small>
        <small class="validateMessage"></small>



      </div>


    </div>

    <?php }else{ ?>
        <small>
        Tienes el máximo de imágenes permitidas
        </small>
   <?php  } ?>
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
            <input type="text" class="form-control form-control-sm" name="instagram" placeholder="Escribe la dirección URL">
            <small class="validateMessage"></small>
        </div>
        <div class="field col form-group facebook">
            <label for="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</label>
            <input type="text" class="form-control form-control-sm" name="facebook" placeholder="Escribe la dirección URL">
            <small class="validateMessage"></small>
        </div>
         <div class="field col form-group twitter">
            <label for="twitter"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</label>
            <input type="text" class="form-control form-control-sm" name="twitter" placeholder="Escribe la dirección URL">
            <small class="validateMessage"></small>
        </div>

    </div>


    </div>







    <?php
}



$paginaHome = esc_url(get_permalink(get_page_by_title('Home')));

//  return;
if( (isset($_GET['serial'])) && ($_GET['serial'] != '')){

    global $wpdb;
    global $wp_role;
    $serialOferta = $_GET['serial'];
    $tabla = $wpdb->prefix . 'public_profesional';
    $infoOferta = $wpdb->get_results("SELECT * FROM $tabla where id='$serialOferta' ", ARRAY_A);

    $currenTuserId = get_current_user_id() || -1;



    if(
        (isset($infoOferta) && count($infoOferta) == 0) ||
    ( !validateUserProfileOwner($currenTuserId, $currenTuserId, "adminTsoluciono") &&  $infoOferta[0]['publico'] == 0 )
    ){
    $pgg = esc_url(get_permalink(get_page_by_title('Servicios profesionales')));
    header('Location: '.$pgg);
    die();
    }
    if(isset($infoOferta) && (count($infoOferta) > 0)){

        $admin = admin_url('admin-ajax.php', null);
        wp_localize_script(
            'infoProfesional',
            'dataPublicacion',
            array(
                // 'ajaxurl' => $admin,
                'data' => $infoOferta[0]
            )
        );




        // template para modificar


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
    $video = $media['video'];
    $video = $video[0];
    $media = $media['imagesProfeshional'];



    formUpdatePublicProfesional($media);

	$redesSociales = json_decode($redesSociales, true);
	// $media = $media['imagesProfeshional'];

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
                    <div class="col-md-12 col-lg-6 imgPrincipal">
                    <div class="img">
                       <img src="<?php echo $media[0]['src']; ?>" alt="">
                    </div>
                    </div>
                    <div class="col-md-12 col-lg-6 details">
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
                                <div class="col-md-12 col-lg-5 descp">
                                    <p>
                                       <strong>Titular o empresa:</strong> <br>
                                       <?php echo $nombreEmpresa ?> <br>
                                       <strong>Tipo de servicio:</strong> <br>
                                       <?php echo $categoria ?> <br>
                                       <!-- <strong>Horario de atención:</strong> <br>
                                       <?php echo $horario ?> <br> -->

                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-5 descp">
                                <p>
                                   <strong>Departamento:</strong> <br>
                                   <?php echo $departamento ?> <br>
                                   <strong>Ciudad:</strong> <br>
                                   <?php echo $ciudad ?> <br>

                                </p>
                                </div>
                            </div>

                        </div>

						<?php //if( is_user_logged_in() && (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ))){

                            $listadoAdmin = esc_url(get_permalink(get_page_by_title('Admin profesionales')));
                            ?>

                            <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom">

                                  <a href="/profesionales/" class='btn btn-primary btn-block'>Volver al listado de publicaciones</a>

                                </div>
                            </div>
                        </div>

                        <?php //} ?>

                        <?php if( is_user_logged_in() &&
                            (validateUserProfileOwner( get_current_user_id(), get_current_user_id(), "adminTsoluciono" ) ||
                            validateUserProfileOwner( get_current_user_id(), $candidatoId, "candidata" ) )
                            ){
                            ?>
                            <div class="opc">
                            <div class="row">

                                <div class="col-8 buttonCustom" style="margin: 5px 0">

                                  <a href="#" onclick="updatePublicProfesional('<?php echo $serialOferta ?>')" class='btn btn-success btn-block'>Modificar publicación</a>

                                </div>
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


                    <?php if($logo != null && $logo != ''){ ?>


                    <div class="logotipo">

                        <img src="<?php echo $logo ?>" alt="">

                    </div>

                   <?php  } ?>

                   <?php if($detalles != null && $detalles != ''){ ?>

                       <div class="descriptions">
                        <p>
                            <strong>Descripción del servicio</strong> <br>
                            <?php echo $detalles; ?>
                            </p>
                        </div>

                    <?php } ?>

                    <?php
                    if(
                        $redesSociales != null &&
                        ( $redesSociales['instagram'] != "" || $redesSociales['facebook'] != "" || $redesSociales['twitter'] != "" )
                    ){ ?>
                    <div class="redesSociales">



                            <ul>

                            <?Php if($redesSociales['instagram'] != "" ){ ?>
                            <li>
                                <a target="_blank" href="<?php echo $redesSociales['instagram'] ?>">
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </a>
                            </li>
                            <?php }?>
                            <?Php if($redesSociales['facebook'] != "" ){ ?>
                            <li>
                                <a target="_blank" href="<?php echo $redesSociales['facebook'] ?>">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                            </li>
                            <?php }?>
                            <?Php if($redesSociales['twitter'] != "" ){ ?>
                            <li>
                                <a target="_blank" href="<?php echo $redesSociales['twitter'] ?>">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                            </li>
                            <?php }?>

                            </ul>

                    </div>
                    <?php } ?>

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


                <?php if(isset($video) && $video['src'] != ''){ ?>

                    <div class="row videoSpace">

                    <video controls crossorigin >
		 <source src="<?php echo $video['src']; ?>" type="video/mp4" size="576">
			<!-- <source src="<?php echo $video['src']; ?>" type="video/mp4" size="720"> -->
			<!-- <source src="<?php echo $video['src']; ?>" type="video/mp4" size="1080"> -->


	</video>
                    </div>


                <script>

                    // Change the second argument to your options:
// https://github.com/sampotts/plyr/#options
const player = new Plyr('video', {captions: {active: true}});

// Expose player so it can be used from the console
window.player = player;


                </script>

               <?php  } ?>

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


