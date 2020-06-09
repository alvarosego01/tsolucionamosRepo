<?php

/* Template Name: Profesionales */





?>



<?php

get_header();



$publicaciones = getAllProfesionals();





?>





<?php

	echo do_shortcode( '[rev_slider alias="HeaderProfesional"]' );

 ?>







<div class="container" id="areaProfesionales">





<?php

	echo do_shortcode( '[getAllProfesionals all="true"]' );

 ?>







</div>















<?php get_footer(); ?>