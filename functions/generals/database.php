<?php




// base de datos para entrevistas o estados de contratos
function base_preForm()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'base_preForm';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial not null,
        `nombreCompleto` varchar(300),
        `cedula` mediumtext,
        `edad` int,
        `ciudadResidencia` varchar(300),
        `direccionResidencia` varchar(300),
        `telefonoMovil` varchar(300),
        `telefonoFijo` varchar(300),
        `estadoCivil` varchar(50),
        `numeroHijos` varchar(50),
        `culminoBachillerato` varchar(50),
        `algunEstudioSuperior` varchar(50),
        `disponibilidadFinesDeSemana` varchar(50),
        `tieneReferenciaDeEmpleos` varchar(50),
        `referenciasAfirmativo` varchar(50),
        `empleadaDomesticaFamilia` varchar(50),
        `anosEmpleadaCasaFamilia` int,
        `empleadaNinera` varchar(50),
        `anosEmpleadaNinera` int,
        `empleadaCuidadoraAdultoMayor` varchar(50),
        `anosEmpleadaCuidadoraAdulto` int,
        `empleadaCuidadoDeMascotas` varchar(50),
        `anosCuidadoraMascota` int,
        `empleadaCuidadoNeoNatales` varchar(50),
        `anosCuidadoNeoNato` int,
        `calificaLimpieza` varchar(50),
        `calificaCuidadoNinos` varchar(50),
        `calificaCuidadoAncianos`varchar(50),
        `calificaCocina`varchar(50),
        `calificaPlanchado` varchar(50),
        `calificaCuidadoEnfermos` varchar(50),
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_preform', $dbversion);

}
add_action('after_setup_theme', 'base_preForm');

function proceso_contrato()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'proceso_contrato';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` varchar(100) not null,
        `contratistaId` int not null,
        `candidataId` int not null,
        `ofertaId` varchar(100) not null,
         `etapa` varchar(300),
         `estado` varchar(300),
      primary key(id, ofertaId)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_entrevistas', $dbversion);

}

function proceso_contrato_etapas()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'proceso_contrato_etapas';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial not null,
        `idEntrevista` varchar(100) not null,
        `fechaCreacion` varchar(100) not null,
        `fechaPautado` varchar(100) not null,
        `hora` varchar(100) not null,
        `estado` varchar(300),
        `tipoEntrevista` varchar(300),
        `aprobado` boolean,
        `datoEntrevista` long,
        `nota` longtext,
        `resultadosEntrevista` longtext,
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_etapas', $dbversion);

    try {
        // $ad = "ALTER TABLE $tabla ADD confirmaFecha longtext;";
        // $wpdb->query($ad);
        // $ad = "ALTER TABLE $tabla ADD detalles longtext;";
        // $wpdb->query($ad);
        // $ad = "ALTER TABLE $tabla ADD pruebasPsico longtext;";
        // $wpdb->query($ad);
        $ad = "ALTER TABLE $tabla ADD resultadosPruebasPsico longtext;";
        $wpdb->query($ad);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}



function historiales_candidatos()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'historiales_candidatos';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `idHistorialCandidato` serial not null,
        `candidataId` int not null,
        `idEntrevista` varchar(100) not null,
        `fechaHistorial` varchar(100) not null,
        `pruebasPsico` longtext,
        `referenciasLaborales` varchar(300),
        `resultado` varchar(300),
        `aprobado` boolean,
        `datoEntrevista` longtext,
        `notaAdmin` longtext,
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_etapas', $dbversion);

}

function admin_vacante_familia()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'admin_vacantes_familia';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial not null,
        `idOferta` varchar(100) not null,
        `fechaLlamada` varchar(100) not null,
        `necesidades` mediumtext,
        `notaAdmin` longtext,
        `publicar` boolean,
        `aprobado` boolean,
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_admin_vacante_familia', $dbversion);

}

function admin_usuarios_recomendados(){
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'usuarios_recomendados';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial not null,
        `idCandidato` varchar(100) not null,
        `idOferta` varchar(100) not null,
        `idEntrevista` varchar(100) not null,
        `fechaRecomendado` varchar(100) not null,
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_admin_usuarios_recomendados', $dbversion);
}

function admin_experiencia_contratos(){
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'experiencia_contratos';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial not null,
        `idFamilia` varchar(100) not null,
        `idCandidato` varchar(100) not null,
        `ìdContrato` varchar(100) not null,
        `idEntrevista` varchar(100) not null,
        `detallesExperiencia` longtext,
        primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_admin_experiencia_contratos', $dbversion);

    try {
        $ad = "ALTER TABLE $tabla ADD tipo varchar(200);";
        $wpdb->query($ad);

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

function foreig_wp_proceso_contrato()
{
    global $wpdb;

    $tabla = $wpdb->prefix . 'proceso_contrato';

    $tablaForanea = $wpdb->prefix . 'ofertalaboral';

    $tablaProceso = $wpdb->prefix . 'proceso_contrato_etapas';

    $admin_necesidades_familia = $wpdb->prefix . 'admin_vacantes_familia';
    $contratos = $wpdb->prefix . 'contratos';
    $charset = $wpdb->get_charset_collate();




    try {


        $s = "ALTER TABLE $tabla drop foreign key FK_procesocontrato_ofertaId;";
        $s2 = "ALTER TABLE $tablaProceso drop foreign key FK_procesocontrato_etapas_entrevistaid;";
        $admin_ofertas = "ALTER TABLE $admin_necesidades_familia drop foreign key FK_admin_vacante_familia;";
        $t = $wpdb->prefix . 'usuarios_recomendados';
        $usersRecomendados1 = "ALTER TABLE $t drop foreign key FK_admin_recomendados_entrevista1;";
        $usersRecomendados2 = "ALTER TABLE $t drop foreign key FK_admin_recomendados_entrevista2;";
        $t = $wpdb->prefix . 'experiencia_contratos';
        $experienciaContrato1 = "ALTER TABLE $t drop foreign key FK_admin_experiencia_contratos;";
        $experienciaContrato2 = "ALTER TABLE $t drop foreign key FK_admin_experiencia_contratos2;";

        $wpdb->query($s);
        $wpdb->query($s2);
        $wpdb->query($admin_ofertas);
        $wpdb->query($usersRecomendados1);
        $wpdb->query($usersRecomendados2);
        $wpdb->query($experienciaContrato1);
        $wpdb->query($experienciaContrato2);

        $s = "ALTER TABLE $tabla CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $s2 = "ALTER TABLE $tablaProceso CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $admin_ofertas = "ALTER TABLE $admin_necesidades_familia CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $t = $wpdb->prefix . 'usuarios_recomendados';
        $usersRecomendados1 = "ALTER TABLE $t CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $usersRecomendados2 = "ALTER TABLE $t CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $t = $wpdb->prefix . 'experiencia_contratos';
        $experienciaContrato1 = "ALTER TABLE $t CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $experienciaContrato2 = "ALTER TABLE $t CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        $wpdb->query($s);
        $wpdb->query($s2);
        $wpdb->query($admin_ofertas);
        $wpdb->query($usersRecomendados1);
        $wpdb->query($usersRecomendados2);
        $wpdb->query($experienciaContrato1);
        $wpdb->query($experienciaContrato2);

        $s = "ALTER TABLE $tabla ADD CONSTRAINT FK_procesocontrato_ofertaId FOREIGN KEY (ofertaId) REFERENCES $tablaForanea(id) ON DELETE CASCADE;";
        $s2 = "ALTER TABLE $tablaProceso ADD CONSTRAINT FK_procesocontrato_etapas_entrevistaid FOREIGN KEY (idEntrevista) REFERENCES $tabla(id) ON DELETE CASCADE;";
        $admin_ofertas = "ALTER TABLE $admin_necesidades_familia ADD CONSTRAINT FK_admin_vacante_familia FOREIGN KEY (idOferta) REFERENCES $tablaForanea(id) ON DELETE CASCADE;";
        $t = $wpdb->prefix . 'usuarios_recomendados';
        $usersRecomendados1 = "ALTER TABLE $t ADD CONSTRAINT FK_admin_recomendados_entrevista1 FOREIGN KEY (idEntrevista) REFERENCES $tabla(id) ON DELETE CASCADE;";
        $usersRecomendados2 = "ALTER TABLE $t ADD CONSTRAINT FK_admin_recomendados_entrevista2 FOREIGN KEY (idOferta) REFERENCES $tablaForanea(id) ON DELETE CASCADE;";
        $t = $wpdb->prefix . 'experiencia_contratos';
        $experienciaContrato1 = "ALTER TABLE $t ADD CONSTRAINT FK_admin_experiencia_contratos FOREIGN KEY (ìdContrato) REFERENCES $contratos(id) ON DELETE CASCADE;";
        $experienciaContrato2 = "ALTER TABLE $t ADD CONSTRAINT FK_admin_experiencia_contratos2 FOREIGN KEY (idEntrevista) REFERENCES $tabla(id) ON DELETE CASCADE;";

        $wpdb->query($s);
        $wpdb->query($s2);
        $wpdb->query($admin_ofertas);
        $wpdb->query($usersRecomendados1);
        $wpdb->query($usersRecomendados2);
        $wpdb->query($experienciaContrato1);
        $wpdb->query($experienciaContrato2);

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}

// add_action('after_setup_theme', 'proceso_contrato');
// add_action('after_setup_theme', 'proceso_contrato_etapas');
// add_action('after_setup_theme', 'admin_vacante_familia');

// add_action('after_setup_theme', 'admin_usuarios_recomendados');
// add_action('after_setup_theme', 'admin_experiencia_contratos');






// CREACION DE LA BASE DE DATOS.

function historialcontratosDB()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '1.0';
    $tabla = $wpdb->prefix . 'historialcontratos';
    $referencia = $wpdb->prefix . 'contratos';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
    `id` varchar(100) not null,
    `aceptado` boolean not null,
    `cancelado` boolean not null,
    `espera` boolean not null,
    `caducado` boolean not null,
    `activos` boolean not null,
    `eliminado` boolean not null,
    `engarantia` boolean default 1,
    `definitivo` boolean default 0 ,
    `contratoId` varchar(100) not null,
    primary key(id),
    FOREIGN KEY (contratoId) REFERENCES $referencia(id) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_historiales', $dbversion);

    // columnas adicionales

    try {
        $ad = "ALTER TABLE $tabla ADD solCambio boolean default 0;";
        $wpdb->query($ad);
        $ad = "ALTER TABLE $tabla ADD detalles longtext;";
        $wpdb->query($ad);
        $ad = "ALTER TABLE $tabla ADD fecha varchar(100);";
        $wpdb->query($ad);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
    // dbDelta($ad);
}



function usuariofirmas()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'usuariofirmas';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` SERIAL,
        `firma` longtext,
        `usuarioId` longtext,
    primary key(id)) ENGINE=InnoDB $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_usuariofirmas', $dbversion);

}

function contratosDB()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'contratos';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
      `id` varchar(100) not null,
      `contratistaId` int not null,
      `candidataId` int not null,
      `ofertaId` varchar(100) not null,
      `estado` varchar(300),
      `fechaCreacion` varchar(100) not null,
      `fechaInicio` varchar(100) not null,
      `fechaFin` varchar(100) not null,
      `gestion` varchar(300),
      `firmaCandidata` int,
      `firmaContratista` int,
      `urlPdf` mediumtext,
      `textoContrato` longtext,
      `serialContrato` mediumtext,
      `nombreTrabajo` mediumtext,
      `cargo` mediumtext,
      `nombreFamilia` mediumtext,
      `direccion` mediumtext,
      `turno` mediumtext,
      `pais` mediumtext,
      `ciudad` mediumtext,
      `horario` mediumtext,
      `sueldo` mediumtext,
      `tipoServicio` mediumtext,
      `descripcionExtra` longtext,
      primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_contratos', $dbversion);

    $ad = "ALTER TABLE $tabla ADD departamento mediumtext;";
    $wpdb->query($ad);


}

function offerLaboral()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '1.0';
    $tabla = $wpdb->prefix . 'ofertalaboral';
    $tablaForanea = $wpdb->prefix . 'users';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS `$tabla`(
      `id` varchar(100) not null,
      `contratistaId` bigint(20) not null,
      `estado` varchar(300),
      `fechaCreacion` varchar(100) not null,
      `gestion` varchar(300),
      `fechaInicio` varchar(100) not null,
      `fechaFin` varchar(100) not null,
      `nombreTrabajo` mediumtext,
      `cargo` mediumtext,
      `nombreFamilia` mediumtext,
      `direccion` mediumtext,
      `turno` mediumtext,
      `pais` mediumtext,
      `ciudad` mediumtext,
      `sueldo` mediumtext,
      `horario` mediumtext,
      `tipoServicio` mediumtext,
      `descripcionExtra` longtext,
      `firmaCandidata` bigint(20),
      `serialOferta` mediumtext,
      `contratoTerminosPublicacion` longtext,
      `aceptaTerminosContrato` boolean,
      `aceptaTerminosPublicacion` boolean,
      `serialFactura` varchar(100),
      `publico` boolean,
      PRIMARY KEY(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_oferta', $dbversion);

    $ad = "ALTER TABLE $tabla ADD departamento mediumtext;";
    $wpdb->query($ad);

    $ad = "ALTER TABLE $tabla ADD tipoPublic mediumtext;";
    $wpdb->query($ad);

}

function offerPostulantes()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '1.0';
    $tabla = $wpdb->prefix . 'ofertapostulantes';
    $tablaForanea = $wpdb->prefix . 'ofertaLaboral';
    $tablaForanea2 = $wpdb->prefix . 'users';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS `$tabla`(
      `id` serial,
      `postulanteId` bigint(20) not null,
      `mensaje` longtext,
      `ofertaId` varchar(100) not null,
     primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_postulantes', $dbversion);

    $ad = "ALTER TABLE $tabla ADD candidatoEnterado longtext;";
    $wpdb->query($ad);
    $ad = "ALTER TABLE $tabla ADD fechaCreacion varchar(100);";
    $wpdb->query($ad);
}

function offerState()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '1.0';
    $tabla = $wpdb->prefix . 'estadoofertalaboral';
    $tablaForanea = $wpdb->prefix . 'ofertaLaboral';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
      `id` varchar(100) not null,
      `postuladoID` bigint(20) not null,
      `vistoXPostulado` boolean,
      `estado` mediumtext,
      `ofertaId` varchar(100) not null,
        PRIMARY KEY(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_ofertaEstados', $dbversion);
}

function notificacion_msg()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'notificacion_msg';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` SERIAL,
        `mensaje` longtext,
        `mensajeEmail` longtext,
        `subject` mediumtext,
        `estado` varchar(100),
        `fecha` varchar(100),
        `tipo` varchar(150),
        `email` varchar(500),
        `usuarioMuestra` mediumtext,
    primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_notificacion_msg', $dbversion);

}

function facturacion()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'facturacion';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `serialFactura` varchar(100),
        `nombreFactura` longtext,
        `mensaje` longtext,
        `fechaCreada` varchar(100),
        `fechaPagada` varchar(100),
        `plan` mediumtext,
        `comprobante` boolean,
        `referencia` mediumtext,
        `estado` mediumtext,
        `tipoPago` longtext,
        `cuenta` longtext,
        `formato` varchar(50),
        `pagado` boolean default 0,
        `contratistaId` bigint(20) not null,
    primary key(serialFactura)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_facturacion', $dbversion);

}

function facturacion_profesional()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'facturacion_profesional';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `serialFactura` varchar(100),
        `nombreFactura` longtext,
        `mensaje` longtext,
        `fechaCreada` varchar(100),
        `fechaPagada` varchar(100),
        `plan` mediumtext,
        `meses` longtext,
        `comprobante` boolean,
        `referencia` mediumtext,
        `estado` mediumtext,
        `tipoPago` longtext,
        `cuenta` longtext,
        `formato` varchar(50),
        `pagado` boolean default 0,
        `imagenReferencia` longtext,
        `candidatoId` bigint(20) not null,
    primary key(serialFactura)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_facturacion_profesional', $dbversion);

}



function presupuesto_profesional()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'presupuesto_profesional';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial,
        `candidatoId` bigint(20) not null,
        `familiaId` bigint(20) not null,
        `nombre` mediumtext,
        `datosContacto` longtext,
        `nombrePresupuesto` longtext,
        `requerimientos` longtext,
        `departamento` mediumtext,
        `direccion` mediumtext,
        `fecha` mediumtext,
        `hora` mediumtext,
        `redesSociales` longtext,
        `telefono` mediumtext,
        `email` mediumtext,
        `visto` boolean default 0,
        `servicio` varchar(100),
    primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_presupuesto_profesional', $dbversion);

}

function publicacion_profesional()
{

    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'public_profesional';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` varchar(100),
        `candidatoId` bigint(20) not null,
        `plan` mediumtext,
        `estado` mediumtext,
        `nombreEmpresa` mediumtext,
        `tituloPublicacion` longtext,
        `categoria` mediumtext,
        `fechaCreada` varchar(100),
        `detalles` longtext,
        `logo` longtext,
        `direccion` mediumtext,
        `departamento` mediumtext,
        `horario` mediumtext,
        `ciudad` mediumtext,
        `redesSociales` longtext,
        `telefono` mediumtext,
        `email` mediumtext,
        `media` longtext,
        `publico` boolean,
        `factura` varchar(100),
    primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_public_profesional', $dbversion);

    // $ad = "ALTER TABLE $tabla ADD publico boolean default 0;";
    // $wpdb->query($ad);

}

function foreignProfesional(){

    global $wpdb;

    $tabla1 = $wpdb->prefix . 'facturacion_profesional';
    $tabla2 = $wpdb->prefix . 'presupuesto_profesional';
    $tabla3 = $wpdb->prefix . 'public_profesional';


    $charset = $wpdb->get_charset_collate();

    try {

        // $s1 = "ALTER TABLE $tabla1 drop foreign key FK_estadoOferta_ofertaId;";
        // $s2 = "ALTER TABLE $tabla2 drop foreign key FK_ofertapostulantes_ofertaId;";
        // $s3 = "ALTER TABLE $tablaOfertaLaboral drop foreign key FK_oferta_factura;";


        // $wpdb->query($s1);
        // $wpdb->query($s2);
        // $wpdb->query($s3);



        $s1 = "ALTER TABLE $tabla1 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $s2 = "ALTER TABLE $tabla2 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $s3 = "ALTER TABLE $tabla3 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        $wpdb->query($s1);
        $wpdb->query($s2);
        $wpdb->query($s3);


        $s1 = "ALTER TABLE $tabla3 ADD CONSTRAINT FK_profesional_factura FOREIGN KEY (factura) REFERENCES $tabla1(serialFactura) ON DELETE CASCADE;";
        $s2 = "ALTER TABLE $tabla2 ADD CONSTRAINT FK_profesional_presupuesto FOREIGN KEY (servicio) REFERENCES $tabla3(id) ON DELETE CASCADE;";

        $wpdb->query($s1);
        $wpdb->query($s2);



    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }


}

function configuracionesadmin()
{
    global $wpdb;
    global $dbversion;
    $dbversion = '2.0';
    $tabla = $wpdb->prefix . 'configuracionesadmin';
    $charset = $wpdb->get_charset_collate();
    $s = "CREATE TABLE IF NOT EXISTS $tabla(
        `id` serial,
        `teamDatos` longtext,
    primary key(id)) ENGINE=InnoDB $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);
    add_option('version_configuracionesadmin', $dbversion);

}

function foreig_wp_estadoofertalaboral()
{
    global $wpdb;
    $tabla1 = $wpdb->prefix . 'estadoofertalaboral';
    $tablaForanea1 = $wpdb->prefix . 'ofertalaboral';
    $charset = $wpdb->get_charset_collate();
    $s = "ALTER TABLE $tabla ADD CONSTRAINT FK_estadoOferta_ofertaId FOREIGN KEY (ofertaId) REFERENCES $tablaForanea(id) ON DELETE CASCADE;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($s);

}



function foreig_base()
{
    global $wpdb;

    $tabla1 = $wpdb->prefix . 'estadoofertalaboral';
    $tablaForanea1 = $wpdb->prefix . 'ofertalaboral';

    $tabla2 = $wpdb->prefix . 'ofertapostulantes';
    $tablaForanea2 = $wpdb->prefix . 'ofertalaboral';

    $tablaOfertaLaboral = $wpdb->prefix. 'ofertalaboral';
    $tablaFacturacion = $wpdb->prefix . 'facturacion';
    $tablaFirmas = $wpdb->prefix . 'usuariofirmas';
    $tablaProcesoContrato = $wpdb->prefix . 'proceso_contrato';

    $tablaContrtos = $wpdb->prefix . 'contratos';
    $tablaHistorialContratos = $wpdb->prefix . 'historialcontratos';



    $charset = $wpdb->get_charset_collate();

    try {


        $s1 = "ALTER TABLE $tabla1 drop foreign key FK_estadoOferta_ofertaId;";
        $s2 = "ALTER TABLE $tabla2 drop foreign key FK_ofertapostulantes_ofertaId;";
        $s3 = "ALTER TABLE $tablaOfertaLaboral drop foreign key FK_oferta_factura;";

        $s4 = "ALTER TABLE $tablaHistorialContratos drop foreign key FK_historial_contratos;";

        $wpdb->query($s1);
        $wpdb->query($s2);
        $wpdb->query($s3);
        $wpdb->query($s4);

        $s1 = "ALTER TABLE $tabla1 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $s2 = "ALTER TABLE $tabla2 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $s3 = "ALTER TABLE $tablaOfertaLaboral CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        $s4 = "ALTER TABLE $tablaHistorialContratos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        $wpdb->query($s1);
        $wpdb->query($s2);
        $wpdb->query($s3);
        $wpdb->query($s4);

        $s1 = "ALTER TABLE $tabla1 ADD CONSTRAINT FK_estadoOferta_ofertaId FOREIGN KEY (ofertaId) REFERENCES $tablaForanea1(id) ON DELETE CASCADE;";
        $s2 = "ALTER TABLE $tabla2 ADD CONSTRAINT FK_ofertapostulantes_ofertaId FOREIGN KEY (ofertaId) REFERENCES $tablaForanea2(id) ON DELETE CASCADE;";
        $s3 = "ALTER TABLE $tablaOfertaLaboral ADD CONSTRAINT FK_oferta_factura FOREIGN KEY (serialFactura) REFERENCES $tablaFacturacion(serialFactura) ON DELETE CASCADE;";

        $s4 = "ALTER TABLE $tablaHistorialContratos ADD CONSTRAINT FK_historial_contratos2 FOREIGN KEY (contratoId) REFERENCES $tablaContrtos(id) ON DELETE CASCADE;";

        $wpdb->query($s1);
        $wpdb->query($s2);
        $wpdb->query($s3);
        $wpdb->query($s4);


    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

}




add_action('after_setup_theme', 'facturacion_profesional');
add_action('after_setup_theme', 'presupuesto_profesional');
add_action('after_setup_theme', 'publicacion_profesional');
// add_action('after_setup_theme', 'contratosDB');
add_action('after_setup_theme', 'offerLaboral');
// add_action('after_setup_theme', 'historialcontratosDB');
// add_action('after_setup_theme', 'offerState');
// add_action('after_setup_theme', 'offerPostulantes');
// add_action('after_setup_theme', 'notificacion_msg');
// add_action('after_setup_theme', 'facturacion');
// add_action('after_setup_theme', 'configuracionesadmin');
// add_action('after_setup_theme', 'usuariofirmas');


// // // establecer relaciones
add_action('after_setup_theme', 'foreignProfesional');
// add_action('after_setup_theme', 'foreig_base');
// add_action('after_setup_theme', 'foreig_wp_proceso_contrato');


// prueba correos


function tipo_de_contenido_html() {
     return 'text/html';
}
add_filter( 'wp_mail_content_type', 'tipo_de_contenido_html' );

?>
