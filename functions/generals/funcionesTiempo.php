<?php




// Acción personalizada
// funcionesTiempo
add_action( 'PruebaPlugin', 'funcionesDeTiempo' );
function funcionesDeTiempo() {

    // tiempoGarantia();


}


//Registro de intervalos
add_filter( 'cron_schedules', 'dcms_my_custom_schedule');
function dcms_my_custom_schedule( $schedules ) {
     $schedules['5seconds'] = array(
        'interval' => 5,
        'display' =>__('5 seconds','dcms_lang_domain')
     );
     return $schedules;
}



function tiempoGarantia(){

    global $wpdb;

    $tablaContratos = $wpdb->prefix . 'contratos';
    $historiales = $wpdb->prefix . 'historialcontratos';
    $infoContract = $wpdb->get_results("  SELECT * FROM $tablaContratos as contratos INNER JOIN $historiales as historial ON (contratos.id = historial.contratoId) where historial.activos = 1 and historial.engarantia = 1", ARRAY_A);

    $fechaActual = date('d/m/y');

    foreach ($infoContract as $key => $value) {

        $fechaCreacionContrato = $value['fechaCreacion'];
        $diasPasados = dias_pasados($fechaCreacionContrato, $fechaActual);
        $estado = array();
        $gdias = 90 - $diasPasados;
// notificacion de 3 dias
        if($gdias > 3){

            $contratistaId = $value['contratistaId'];
            $candidataId = $value['candidataId'];

            $ofertaId = $value['ofertaId'];
            $infoOferta = $wpdb->get_results("SELECT * from $ofertalaboral where id = '$ofertaId'", ARRAY_A);

            $tipoServicio = $infoOferta[0]['tipoServicio'];
            $familiaInfo = getInfoNameEmailUsers($contratistaId);
            $candidatoInfo = getInfoNameEmailUsers($candidataId);
            $nombreVacante = $infoOferta[0]['nombreTrabajo'];
            $serialVacante = $infoOferta[0]['serialOferta'];

            $vacanteUrl = esc_url(get_permalink(get_page_by_title('Información de vacante'))).'?serial='.$serialVacante;

            $msj = 'Te restan '.$gdias.' días de garantía bajo contrato activo con <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong> por la vacante publicada <a href="'.$vacanteUrl.'" class="hiper">'.$nombreVacante.'</a>. Puedes hacer uso de dicha garantía <a href="#" class="resalte1">AQUI</a>.';

            $mensaje = array(
                'mensaje' => $msj,
                'subject' => 'Te restan '.$gdias.' días de garantía por el contrato de prueba con <strong>'.$candidatoInfo['nombre'].'('.$candidatoInfo['rol'].')</strong>',
                'estado' => 0,
                // 'fecha' => ,
                'tipo' => 'addExtraCandAccept',
                'email' => $familiaInfo['email'],
               'usuarioMuestra' => $familiaInfo['id']
            );
            saveNotification($mensaje);
            // parte Administracion
        }

    }



}



?>