<?php
/*
Template Name: Pre contrato
 */
?>

 <?php get_header();


$textoContrato1 = apply_filters('the_content', $post->post_content);


 ?>


<div id="globalContainerNewContract">
<div id="containerNewContract" class="container">


            <div class="row titleSection">
                <h2><span class="resalte1">Contrato</span> de trabajo</h2>
                <h5>Lee con atención los términos</h5>
            </div>

        <?php

        contractByUser($textoContrato1);

        // echo $textoContrato1;
        ?>
     
</div>
</div>


 <?php get_footer(); ?>

