
<?php


add_action('wp_ajax_buscarDocumentoRegistros', 'buscarDocumentoRegistros');
add_action('wp_ajax_nopriv_buscarDocumentoRegistros', 'buscarDocumentoRegistros');

function buscarDocumentoRegistros()
{
    
      if (isset($_POST['buscarDocumentoRegistros'])) {
            // recibe json y quita los slash
            $data = preg_replace('/\\\\\"/', "\"", $_POST['buscarDocumentoRegistros']);
            // transforma el string a un array asociativo
            $data = json_decode($data, true);
 

            
            $i = dbbuscarDocumentoRegistros($data);
            
            if(count($i) > 0){ ?>

                <div class="preUserData">
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Nombre completo: <?php echo $i['nombreCompleto'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Cédula: <?php echo $i['cedula'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Edad: <?php echo $i['edad'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Ciudad de residencia: <?php echo $i['ciudadResidencia'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Dirección: <?php echo $i['direccionResidencia'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Tlf movil: <?php echo $i['telefonoMovil'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Tlf fijo: <?php echo $i['telefonoFijo'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Estado civil: <?php echo $i['estadoCivil'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                N° de hijos: <?php echo $i['numeroHijos'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Bachillerato culminado: <?php echo $i['culminoBachillerato'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Estudios superiores: <?php echo $i['algunEstudioSuperior'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Dispone fines de semana: <?php echo $i['disponibilidadFinesDeSemana'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Referencias de empleo: <?php echo $i['tieneReferenciaDeEmpleos'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Referencias: <?php echo $i['referenciasAfirmativo'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Empleada Dom familiar: <?php echo $i['empleadaDomesticaFamilia'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Años de empleo dom familiar: <?php echo $i['anosEmpleadaCasaFamilia'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Empleada niñera: <?php echo $i['empleadaNinera'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Años de empleo de niñera: <?php echo $i['anosEmpleadaNinera'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Cuidado de adulto mayor: <?php echo $i['empleadaCuidadoraAdultoMayor'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Años cuidando adultos mayores: <?php echo $i['anosEmpleadaCuidadoraAdulto'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Cuidado de mascotas: <?php echo $i['empleadaCuidadoDeMascotas'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Años cuidando mascotas: <?php echo $i['anosCuidadoraMascota'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Cuidado de neo natales: <?php echo $i['empleadaCuidadoNeoNatales'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Años cuidando neo natales: <?php echo $i['anosCuidadoNeoNato'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Calificación de limpieza: <?php echo $i['calificaLimpieza'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Calificación cuidando niños: <?php echo $i['calificaCuidadoNinos'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Calificación cuidando ancianos: <?php echo $i['calificaCuidadoAncianos'] ?>
                            </h5>
                        </div>
                       <div class="col-3">
                        <h5 class="preData">
                            Calificación cocina: <?php echo $i['calificaCocina'] ?>
                        </h5>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-3">
                            <h5 class="preData">
                                Calificación de planchado: <?php echo $i['calificaPlanchado'] ?> 
                            </h5>
                        </div>
                       <div class="col-3">
                            <h5 class="preData">
                                Calificación cuidando enfermos: <?php echo $i['calificaCuidadoEnfermos'] ?>
                            </h5>
                       </div>
                       <div class="col-3">
                         
                        </div>
                       <div class="col-3">
                      
                       </div>
                   </div>
                </div>


            <?php }

            die();
        }
    

}


function dbbuscarDocumentoRegistros($data)
{
    global $wpdb;

    $tabla = $wpdb->prefix . 'base_preform';

    

            $data['documento'] = intval($data['documento']);
 
    $serial = $data['documento'];

    $info = $wpdb->get_results("SELECT * from $tabla where cedula = $serial", ARRAY_A);

    return $info;

}



?>