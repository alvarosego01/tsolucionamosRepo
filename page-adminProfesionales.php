<?php
	// Template name: Admin profesionales

?>


 <?php get_header(); ?>


<?php
  $currentId = get_current_user_id();
  if (validateUserProfileOwner($currentId, $currentId, 'adminTsoluciono')) {
 ?>

<div id="adminPanel">
	<div class="container global">


<div class="tabsAdminTsoluciono">


	<div class="row">

<div class="navOpc col-3">

  <h5>Administración</h5>
  <ul>

  <li><a href="#tab8">Pagos de membresía</a></li>
  <li><a href="#tab9">Publicaciones profesionales</a></li>


  </ul>

</div>

  <div class="col-9 mainSections">
    <section id="content8">
      <?php adminTsoluciono8(); ?>
    </section>
    <section id="content9">
      <?php adminTsoluciono9(); ?>
    </section>




  </div>

</div>

	</div>

	</div>
</div>


<?php
	}
 ?>
 <?php get_footer();?>
