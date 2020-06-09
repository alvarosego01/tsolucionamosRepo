<?php
// Template name: Panel Notificación
?>
<?php get_header(); 
     $current = get_current_user_id();
     $pagina = esc_url(get_permalink(get_page_by_title('Home')));


 

    // return;
 if (validateUserProfileOwner($current, $current, "ambos") || validateUserProfileOwner($current, $current, "adminTsoluciono")) {
    
    $pagina = esc_url(get_permalink(get_page_by_title('Mis vacantes')));

    if(validateUserProfileOwner($current, $current, "adminTsoluciono")){
        $pagina = esc_url(get_permalink(get_page_by_title('Administración Tsoluciono')));
        $current = 'Tsoluciono';
    }
 
    if(isset($_GET['mensaje']) && is_user_logged_in()){

    $mensajeId = $_GET['mensaje'];

    $tabla = $wpdb->prefix . 'notificacion_msg';
    $x = $wpdb->get_results("SELECT * from $tabla WHERE id=$mensajeId and usuarioMuestra = '$current'", ARRAY_A);
    $wpdb->flush();

    $infoMensaje = $x[0];

    $leido = $x[0]['estado'];


    $xxx = array(
        'm' => $infoMensaje['id']
      );

      $xxx = json_encode($xxx);

      
  
    if( (isset($infoMensaje)) && ($infoMensaje['usuarioMuestra'] == $current)){
   
?>

<div id="infoMensaje">

<div class="container">
    
<div class="row msj">


<div class="col-10 message">

    
    <div class="row title">
        <h3 style="text-align: center">
            Mensaje
        </h3>
    </div>
    <div class="row info">
        <h6>-Asunto: <?php echo $infoMensaje['subject']; ?></h6>
        <p>
            <?php echo $infoMensaje['mensaje']; ?>
        </p>
    </div>
    <div class="row opc">
    <div class="buttonCustom">
 
        <a name="" id="" class="btn btn-primary" href="<?php echo $pagina ?>" role="button">Ir al panel</a>
  
    <button onclick='deleteNotif(<?php echo $xxx; ?>)' class="btn btn-primary btn-danger">
        
            Eliminar mensaje
        
    </button>
    </div>
    </div>
    </div>
    <div class="col-2 stat">
        <?php if($leido == 0){ ?>
            <div class="estado unread">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <small>No leido</small>
            </div>
       <?php }else{ ?>
            <div class="estado read">
            <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
            <small>Leido</small>
            </div>
       <?php } ?>

    </div>
    </div>
</div>
</div>

<?php
         try {
            $tabla = $wpdb->prefix . 'notificacion_msg';
            $wpdb->query("UPDATE $tabla SET estado=1 WHERE id=$mensajeId");
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }else{
        // si no tiene registro va al panel
        header("Location: ".$pagina); 
    }
    }else{
    // a home
    header("Location: ".$pagina); 
    }
}else{
    // a home
    header("Location: ".$pagina); 
}
?>
<?php get_footer();

 

?>
