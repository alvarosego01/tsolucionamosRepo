<?php

/* Template Name: Profesionales */





?>



<?php

get_header();



$publicaciones = getAllProfesionals();





?>


<div class="container" id="areaProfesionales">





<?php

	echo do_shortcode( '[getAllProfesionals all="true"]' );

 ?>







</div>





<?php

	echo do_shortcode( '[rev_slider alias="HeaderProfesional"]' );


	if(!is_user_logged_in()){

		// Si sos profesional independiente y queres aumentar tus ingresos... regístrate
?>

		   <div id='registerProfesionalSlider'>
				   <h6>¡Si sos <span>Profesional Independiente</span> <br> y querés aumentar tus ingresos! </h6> <a href='/registro-profesional/?drec=/profesionales/nueva-publicacion-profesional/?pg=1' class='resalte1'>¡Registrate ahora!</a>
			   </div>


	<?php
		 }
		 $currentId = get_current_user_id();
		 if(is_user_logged_in() && validateUserProfileOwner($currentId, $currentId, 'profesional')){ ?>

		   <div id='registerProfesionalSlider'>
				   <h6>¡Ya sos <span>Profesional Independiente</span> <br> aumenta tus ingresos ahora! </h6> <a href='/profesionales/nueva-publicacion-profesional/?pg=1' class='resalte1'>¡Publica tu servicio!</a>
			   </div>


	<?php
		 }




 ?>






<?php get_footer(); ?>