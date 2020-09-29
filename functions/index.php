<?php
// inyección de funcionalidades

// inyección spinner
function spinnerLoad()
{?>
<div class="spinnerLoad" id="spinnerLoad">
    <div class="sk-folding-cube">
        <div class="sk-cube1 sk-cube"></div>
        <div class="sk-cube2 sk-cube"></div>
        <div class="sk-cube4 sk-cube"></div>
        <div class="sk-cube3 sk-cube"></div>
    </div>
</div>
<?php
}

//paginacion endogena
function paginate($reload, $page, $tpages, $adjacents = 1)
{
    if($tpages > 1){
    $prevlabel = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
    $nextlabel = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
    $out = '<ul  class="pagination no-margin pagination-large">';

    // previous label

    if ($page == 1) {
        $out .= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
    } elseif ($page == 2) {
        $out .= "<li><span><a href='javascript:void(0);' onclick=load(1,'" . $reload . "')>$prevlabel</a></span></li>";
    } else {
        $out .= "<li><span><a href='javascript:void(0);' onclick=load(" . ($page - 1) . ",'" . $reload . "')>$prevlabel</a></span></li>";
    }

    // first label
    if ($page > ($adjacents + 1)) {
        $out .= "<li><a href='javascript:void(0);' onclick=load(1,'" . $reload . "')>1</a></li>";
    }
    // interval
    if ($page > ($adjacents + 2)) {
        $out .= "<li><a>...</a></li>";
    }

    // pages

    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= "<li class='active'><a>$i</a></li>";
        } elseif ($i == 1) {
            $out .= "<li><a href='javascript:void(0);' onclick=load(1,'" . $reload . "')>$i</a></li>";
        } else {
            $out .= "<li><a href='javascript:void(0);' onclick=load(" . $i . ",'" . $reload . "')>$i</a></li>";
        }
    }

    // interval

    if ($page < ($tpages - $adjacents - 1)) {
        $out .= "<li><a>...</a></li>";
    }

    // last

    if ($page < ($tpages - $adjacents)) {
        $out .= "<li><a href='javascript:void(0);' onclick=load($tpages,'" . $reload . "')>$tpages</a></li>";
    }

    // next

    if ($page < $tpages) {
        $out .= "<li><span><a href='javascript:void(0);' onclick=load(" . ($page + 1) . ",'" . $reload . "')>$nextlabel</a></span></li>";
    } else {
        $out .= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
    }

    $out .= "</ul>";
    return $out;
}
}
// retorna recursos de forma dinamica dependiendo de donde se requieran para reducir codigo
function resources($resource, $extra = '')
{
    switch ($resource) {


        // <!-- include jQuery library -->
        // <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

        // <!-- include FilePond library -->
        // <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

        // <!-- include FilePond plugins -->
        // <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

        // <!-- include FilePond jQuery adapter -->
        // <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>


        // case 'filePond':

        //     wp_register_style('filePondCss', get_template_directory_uri() . '/../Divi-child/assets/filepond/filepond.css', rand(), 'all');
        //     wp_enqueue_style('filePondCss');
        //     wp_register_script('filePondJs',  get_template_directory_uri() . '/../Divi-child/assets/filepond/filepond.js', rand(), 'all');
        //     wp_enqueue_script('filePondJs');
        //     wp_register_script('filePondJquery',  get_template_directory_uri() . '/../Divi-child/assets/filepond/filepond.jquery.js', rand(), 'all');
        //     wp_enqueue_script('filePondJquery');

        //     wp_register_script('FilePondPrevieJs',  get_template_directory_uri() . '/../Divi-child/assets/filepond/filePond-preview.js', rand(), 'all');
        //     wp_enqueue_script('FilePondPrevieJs');


        case 'csv':


        break;

        case 'dragable':


            // wp_register_script('EntireJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/draggable.bundle.js", rand(), 'all');
            // wp_enqueue_script( 'EntireJS' );

            // wp_register_script('legacyJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/draggable.bundle.legacy.js", rand(), 'all');
            // wp_enqueue_script( 'legacyJS' );

            // wp_register_script('DraggableJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/draggable.js", rand(), 'all');
            // wp_enqueue_script( 'DraggableJS' );

            // wp_register_script('SortableJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/sortable.js", rand(), 'all');
            // wp_enqueue_script( 'SortableJS' );

            // wp_register_script('DroppableJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/droppable.js", rand(), 'all');
            // wp_enqueue_script( 'DroppableJS' );

            // wp_register_script('SwappableJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/swappable.js", rand(), 'all');
            // wp_enqueue_script( 'SwappableJS' );

            // wp_register_script('PluginsJS',"https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.8/lib/plugins.js", rand(), 'all');
            // wp_enqueue_script( 'PluginsJS' );


            wp_register_script('sortableJS',  get_template_directory_uri() . '/../Divi-child/assets/js/sortable.js', rand(), 'all');
            wp_enqueue_script('sortableJS');


        break;



        // break;

        case 'pdfJS':

        // wp_register_style('lightboxCss', get_template_directory_uri() . '/../Divi-child/assets/lightbox/ekko-lightbox.css', rand(), 'all');
        // wp_enqueue_style('lightboxCss');

        // wp_register_script('pdfJS',  get_template_directory_uri() . '/../Divi-child/assets/pdfjs/html2pdf.bundle.min.js', rand(), 'all');
        // wp_enqueue_script('pdfJS');

        break;

        case 'lightbox':

        wp_register_style('lightboxCss', get_template_directory_uri() . '/../Divi-child/assets/lightbox/ekko-lightbox.css', rand(), 'all');
        wp_enqueue_style('lightboxCss');

        wp_register_script('lightboxJs',  get_template_directory_uri() . '/../Divi-child/assets/lightbox/ekko-lightbox.js', rand(), 'all');
        wp_enqueue_script('lightboxJs');

        break;

        case 'graficos':

        wp_register_style('CHartCss', get_template_directory_uri() . '/../Divi-child/assets/css/Chart.min.css', rand(), 'all');
        wp_enqueue_style('CHartCss');

        wp_register_script('chartJs',  get_template_directory_uri() . '/../Divi-child/assets/js/Chart.min.js', rand(), 'all');
        wp_enqueue_script('chartJs');

        wp_register_script('BundleChartJs',  get_template_directory_uri() . '/../Divi-child/assets/js/Chart.bundle.min.js', rand(), 'all');
        wp_enqueue_script('BundleChartJs');

//         <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
// <script language="javascript" type="text/javascript" src="jquery.min.js"></script>
// <script language="javascript" type="text/javascript" src="jquery.jqplot.min.js"></script>
// <link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />

        break;

        case 'horaPicker':

        wp_register_style('horaPickerCss', get_template_directory_uri() . '/../Divi-child/assets/css/horaPicker.css', rand(), 'all');
        wp_enqueue_style('horaPickerCss');

        wp_register_script('horaPicker', get_template_directory_uri() . '/../Divi-child/assets/js/horaPicker.js', rand(), 'all');
        wp_enqueue_script('horaPicker');

        break;
        case 'jQueryUI':

            wp_register_style('jQueryUiTheme', get_template_directory_uri() . '/../Divi-child/assets/jquery-UI/jquery-ui.theme.min.css', rand(), 'all');
            wp_enqueue_style('jQueryUiTheme');

            wp_register_style('jQueryUICSS', get_template_directory_uri() . '/../Divi-child/assets/jquery-UI/jquery-ui.min.css', array(), rand(), 'all');
            wp_enqueue_style('jQueryUICSS');

            wp_register_style('jQueryUISTRUCTURECSS', get_template_directory_uri() . '/../Divi-child/assets/jquery-UI/jquery-ui.structure.min.css', rand(), 'all');
            wp_enqueue_style('jQueryUISTRUCTURECSS');

            wp_register_script('jQueryUIJS', get_template_directory_uri() . '/../Divi-child/assets/jquery-UI/jquery-ui.min.js', rand(), 'all');
            wp_enqueue_script('jQueryUIJS');

            break;
        case 'jqueryLast':
            wp_register_script('jqueryLast', get_template_directory_uri() . '/../Divi-child/assets/js/jquery-3.3.1.min.js', rand(), 'all');
            wp_enqueue_script('jqueryLast');
            break;
        case 'fontawesome':
            wp_register_style('fontAwesomeCss', get_template_directory_uri() . '/../Divi-child/assets/css/font-awesome.min.css', array(), rand(), 'all');
            wp_enqueue_style('fontAwesomeCss');
            break;
        case 'sweetAlert':
            wp_register_script('sweetAlertJs', get_template_directory_uri() . '/../Divi-child/assets/js/sweetalert.min.js', array('jqueryLast'), rand(), 'all');
            wp_enqueue_script('sweetAlertJs');
            break;
        case 'signature':
            wp_register_script('jQuery112', get_template_directory_uri() . '/../Divi-child/assets/js/jquery1.12.min.js', rand(), 'all');
            wp_enqueue_script('jQuery112');

            wp_register_script('canvasJs', get_template_directory_uri() . '/../Divi-child/assets/js/jquery1.12.min.js', array('jQuery112'), rand(), 'all');
            wp_enqueue_script('canvasJs');

            wp_register_style('jQueryUiCss', get_template_directory_uri() . '/../Divi-child/assets/css/jquery-ui.css', array(), rand(), 'all');
            wp_enqueue_style('jQueryUiCss');

            wp_register_script('jQueryUiJs', get_template_directory_uri() . '/../Divi-child/assets/js/jquery-ui.min.js', array('jQuery112'), rand(), 'all');
            wp_enqueue_script('jQueryUiJs');

            wp_register_script('jQueryPunch', get_template_directory_uri() . '/../Divi-child/assets/js/jquery.ui.touch-punch.min.js', array('jQuery112'), rand(), 'all');
            wp_enqueue_script('jQueryPunch');

            wp_register_style('signatureCss', get_template_directory_uri() . '/../Divi-child/assets/css/jquery.signature.css', array(), rand(), 'all');
            wp_enqueue_style('signatureCss');

            wp_register_script('signatureJs', get_template_directory_uri() . '/../Divi-child/assets/js/jquery.signature.min.js', array('jQuery112'), rand(), 'all');
            wp_enqueue_script('signatureJs');
            break;
        case 'bootstrap':

            wp_register_style('bootstrapCss', get_template_directory_uri() . '/../Divi-child/assets/css/bootstrap.min.css', array(), rand(), 'all');
            wp_enqueue_style('bootstrapCss');

            wp_register_script('popperJs', get_template_directory_uri() . '/../Divi-child/assets/js/popper.min.js', array('jquery'), rand(), 'all');
            wp_enqueue_script('popperJs');
            wp_register_script('bootstrapJs', get_template_directory_uri() . '/../Divi-child/assets/js/bootstrap.min.js', array('jquery'), rand(), 'all');
            wp_enqueue_script('bootstrapJs');
            break;
        case 'dueñoUsuario':
            $id = um_user('ID');
            $currentId = get_current_user_id();
            if (validateUserProfileOwner($currentId, $currentId, $extra)) {
                return $currentId;
            } else {
                return false;
            }
            break;
        default:
            # code...
            break;
    }
}

function aditionalConfig()
{
    wp_enqueue_script('jquery');


    // estilos generales
    resources('fontawesome');

    wp_register_style('generalCss', get_template_directory_uri() . '/../Divi-child/assets/css/generals.css', array(), rand(), 'all');
    wp_enqueue_style('generalCss');
    // scripts generales
    wp_register_script('generalJs', get_template_directory_uri() . '/../Divi-child/assets/js/generals.js', array(), rand(), 'all');
    wp_enqueue_script('generalJs');

    // si son paginas entonces

    if (is_page('terminoscondiciones') || is_page('politica-de-privacidad-de-los-datos') || is_page( 'terminos-y-condiciones-profesionales-independientes-pdf' ) || is_page( 'politica-de-privacidad-profesionales-independientes' )){

    wp_register_style('terminosCondicionesCss', get_template_directory_uri() . '/../Divi-child/assets/css/terminosCondiciones.css', array(), rand(), 'all');
    wp_enqueue_style('terminosCondicionesCss');

    }

    if (is_page('terminoscondicionesprofesional')){

    wp_register_style('terminosCondicionesCss', get_template_directory_uri() . '/../Divi-child/assets/css/terminosCondiciones.css', array(), rand(), 'all');
    wp_enqueue_style('terminosCondicionesCss');

    }

    if (is_page('formulario-pre-registro')) {
        resources('jqueryLast');

        resources('sweetAlert');
        resources('bootstrap');

        resources('jQueryUI');

        // --------------------
        // estilos y script de pagina
        wp_register_style('preFormCss', get_template_directory_uri() . '/../Divi-child/assets/css/preForm.css', array(), rand(), 'all');
        wp_enqueue_style('preFormCss');
        wp_register_script('preFormJs', get_template_directory_uri() . '/../Divi-child/assets/js/preForm.js', array(), rand(), 'all');
        wp_enqueue_script('preFormJs');
        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'preFormJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );
    }
    if (is_page('infomensaje')) {

        resources('jqueryLast');

        resources('sweetAlert');

        resources('bootstrap');

        wp_register_style('infoMensajeCss', get_template_directory_uri() . '/../Divi-child/assets/css/infoMensaje.css', array(), rand(), 'all');
        wp_enqueue_style('infoMensajeCss');
        wp_register_script('infoMensajeJs', get_template_directory_uri() . '/../Divi-child/assets/js/infoMensaje.js', array(), rand(), 'all');
        wp_enqueue_script('infoMensajeJs');


        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'generalJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }
 if (is_page('profesionales')) {

        // resources('jqueryLast');

        // resources('sweetAlert');

        resources('bootstrap');

        wp_register_style('profesionalesCss', get_template_directory_uri() . '/../Divi-child/assets/css/profesionales.css', array(), rand(), 'all');
        wp_enqueue_style('profesionalesCss');
        wp_register_script('profesionalesJs', get_template_directory_uri() . '/../Divi-child/assets/js/profesionales.js', array(), rand(), 'all');
        wp_enqueue_script('profesionalesJs');


        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'profesionalesJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }

     if (is_page('adminprofesionales')) {


        resources('jqueryLast');
        resources('sweetAlert');
        // resources('signature');
        resources('jQueryUI');

        resources('horaPicker');
        resources('signature');

        resources('bootstrap');


        resources('graficos');


        // estilos y script de pagina
        wp_register_style('adminTsolucionoCss', get_template_directory_uri() . '/../Divi-child/assets/css/adminTsoluciono.css', array(), rand(), 'all');
        wp_enqueue_style('adminTsolucionoCss');

        wp_register_script('adminTsolucionoJs', get_template_directory_uri() . '/../Divi-child/assets/js/adminTsoluciono.js', array(), rand(), 'all');
        wp_enqueue_script('adminTsolucionoJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'adminTsolucionoJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }


    if (is_page('perfil-de-usuario')) {
        // para perfil de usuario
        // se llaman recursos
        resources('jqueryLast');
        resources('sweetAlert');

        resources('jQueryUI');
        resources('signature');
        resources('bootstrap');


        // --------------------
        // estilos y script de pagina
        wp_register_style('userProfileCss', get_template_directory_uri() . '/../Divi-child/assets/css/userProfile.css', array(), rand(), 'all');
        wp_enqueue_style('userProfileCss');
        wp_register_script('userProfileJs', get_template_directory_uri() . '/../Divi-child/assets/js/userProfile.js', array(), rand(), 'all');
        wp_enqueue_script('userProfileJs');
        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'userProfileJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }

       // vista para facturas
    if (is_page('factura')) {

        resources('jqueryLast');
        resources('sweetAlert');

        resources('bootstrap');

       // estilos y script de pagina
        wp_register_style('vistaFacturaCss', get_template_directory_uri() . '/../Divi-child/assets/css/factura.css', array(), rand(), 'all');
        wp_enqueue_style('vistaFacturaCss');

        wp_register_script('vistaFacturaJs', get_template_directory_uri() . '/../Divi-child/assets/js/factura.js', array(), rand(), 'all');
        wp_enqueue_script('vistaFacturaJs');
        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'vistaFacturaJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }

    if (is_page('nueva-oferta-laboral')) {

        resources('jqueryLast');
        resources('sweetAlert');
        // resources('signature');
        resources('jQueryUI');

        // resources('horaPicker');
        resources('signature');

        resources('bootstrap');


        wp_register_style('nuevaOfertaCss', get_template_directory_uri() . '/../Divi-child/assets/css/nuevaOferta.css', array(), rand(), 'all');
        wp_enqueue_style('nuevaOfertaCss');

        wp_register_script('nuevaOfertaJs', get_template_directory_uri() . '/../Divi-child/assets/js/nuevaOferta.js', array(), rand(), 'all');
        wp_enqueue_script('nuevaOfertaJs');

        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'nuevaOfertaJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

    }
    if (is_page('mis-vacantes')) {

        $data = resources('dueñoUsuario', 'ambos');
        resources('jqueryLast');
        resources('signature');

        if ($data != false) {
        } else {
            $pagina = esc_url(get_permalink(get_page_by_title('Vacantes disponibles')));
            // echo "<script>window.location = '$pagina'</script>";
        }
        resources('jQueryUI');

        resources('horaPicker');

        resources('bootstrap');
        resources('sweetAlert');
        // auxiliares
        wp_register_script('vacanteJs', get_template_directory_uri() . '/../Divi-child/assets/js/vacante.js', array(), rand(), 'all');
        wp_enqueue_script('vacanteJs');
        wp_register_style('userProfileCss', get_template_directory_uri() . '/../Divi-child/assets/css/userProfile.css', array(), rand(), 'all');
        wp_enqueue_style('userProfileCss');
        // wp_register_script('userProfileJs', get_template_directory_uri() . '/../Divi-child/assets/js/userProfile.js', array(), rand(), 'all');
        // wp_enqueue_script('userProfileJs');

        // estilos y script de pagina

        wp_register_style('misVacantesCss', get_template_directory_uri() . '/../Divi-child/assets/css/misVacantes.css', array(), rand(), 'all');
        wp_enqueue_style('misVacantesCss');

        wp_register_script('misVacantesJs', get_template_directory_uri() . '/../Divi-child/assets/js/misVacantes.js', array(), rand(), 'all');
        wp_enqueue_script('misVacantesJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'misVacantesJs',
            's',
            array(
                'ajaxurl' => $admin,
                'idCandidata' => $data,
                'idUsuario' => $data,
            )
        );

        spinnerLoad();

    }

    if (is_page('vacantes-disponibles')) {
        resources('bootstrap');

        // resources('horaPicker');

        // estilos y script de pagina
        resources('horaPicker');
        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'userProfileJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

         spinnerLoad();

    }
    if (is_page('administracion-tsoluciono')) {
        $data = resources('dueñoUsuario', 'adminTsoluciono');

        if ($data != false) {
            // template sweet alert para la verificación
            templateVerifVacantAdmin();
            // template para sweet alert opciones de vacantes
            templateOptionsVacantAdmin();
        } else {
            $pagina = esc_url(get_permalink(get_page_by_title('Error 404')));
            echo "<script>window.location = '$pagina'</script>";
        }

        resources('jqueryLast');
        resources('sweetAlert');
        // resources('signature');
        resources('jQueryUI');

        resources('horaPicker');
        resources('signature');

        resources('bootstrap');


        resources('graficos');
        // resources('csv');

          // generales
    wp_register_script('ExportCsv', get_template_directory_uri() . '/../Divi-child/assets/js/table2csv.js', array(), rand(), 'all');
    wp_enqueue_script('ExportCsv');

        // estilos y script de pagina
        wp_register_style('adminTsolucionoCss', get_template_directory_uri() . '/../Divi-child/assets/css/adminTsoluciono.css', array(), rand(), 'all');
        wp_enqueue_style('adminTsolucionoCss');

        wp_register_script('adminTsolucionoJs', get_template_directory_uri() . '/../Divi-child/assets/js/adminTsoluciono.js', array(), rand(), 'all');
        wp_enqueue_script('adminTsolucionoJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'adminTsolucionoJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

        // spinnerLoad();
    }
    if (is_page('informacion-de-contrato')) {
        $data = resources('dueñoUsuario', 'ambos');

        if ($data != false) {
        } else {
            $pagina = esc_url(get_permalink(get_page_by_title('Vacantes disponibles')));
            // echo "<script>window.location = '$pagina'</script>";
        }
        resources('jqueryLast');
        resources('sweetAlert');
        resources('signature');
        resources('bootstrap');
        resources('pdfJS');

        // estilos y script de pagina
        wp_register_style('infoContratoCss', get_template_directory_uri() . '/../Divi-child/assets/css/informacionContrato.css', array(), rand(), 'all');
        wp_enqueue_style('infoContratoCss');

        wp_register_script('infoContratoJs', get_template_directory_uri() . '/../Divi-child/assets/js/informacionContrato.js', array(), rand(), 'all');
        wp_enqueue_script('infoContratoJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'infoContratoJs',
            's',
            array(
                'ajaxurl' => $admin,
                )
            );
        }

        if ( is_page('informacion-de-entrevista') ) {
            resources('jqueryLast');
            resources('sweetAlert');
            resources('bootstrap');
            resources('fontawesome');
            resources('horaPicker');




        if (resources('dueñoUsuario', 'adminTsoluciono')) {
            resources('jQueryUI');

            wp_register_style('adminTsolucionoCss', get_template_directory_uri() . '/../Divi-child/assets/css/adminTsoluciono.css', array(), rand(), 'all');
            wp_enqueue_style('adminTsolucionoCss');

            wp_register_script('adminTsolucionoJs', get_template_directory_uri() . '/../Divi-child/assets/js/adminTsoluciono.js', array(), rand(), 'all');
            wp_enqueue_script('adminTsolucionoJs');

            // para el ajax
            $admin = admin_url('admin-ajax.php', null);

            wp_localize_script(
                'adminTsolucionoJs',
                's',
                array(
                    'ajaxurl' => $admin,
                )
            );
        } elseif (resources('dueñoUsuario', 'ambos')) {
            resources('jQueryUI');

            wp_register_style('adminTsolucionoCss', get_template_directory_uri() . '/../Divi-child/assets/css/adminTsoluciono.css', array(), rand(), 'all');
            wp_enqueue_style('adminTsolucionoCss');

            wp_register_script('adminTsolucionoJs', get_template_directory_uri() . '/../Divi-child/assets/js/adminTsoluciono.js', array(), rand(), 'all');
            wp_enqueue_script('adminTsolucionoJs');

            // para el ajax
            $admin = admin_url('admin-ajax.php', null);

            wp_localize_script(
                'adminTsolucionoJs',
                's',
                array(
                'ajaxurl' => $admin,
                )
            );
        } else {
            $pagina = esc_url(get_permalink(get_page_by_title('Vacantes disponibles')));
            // echo "<script>window.location = '$pagina'</script>";
        }

        // estilos y script de pagina

        wp_register_style('estiloEntrevistaCss', get_template_directory_uri() . '/../Divi-child/assets/css/entrevistas.css', array(), rand(), 'all');
        wp_enqueue_style('estiloEntrevistaCss');


        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'infoContratoJs',
            's',
            array(
                'ajaxurl' => $admin,
            )
        );

        spinnerLoad();
    }

    if(is_page( 'informacion-de-profesional' )){


           wp_register_script('infoProfesional', get_template_directory_uri() . '/../Divi-child/assets/js/infoProfesional.js', array(), rand(), 'all');
            wp_enqueue_script('infoProfesional');

            resources('dragable');

            $admin = admin_url('admin-ajax.php', null);

            wp_localize_script(
                'infoProfesional',
                's',
                array(
                    'ajaxurl' => $admin,
                )
            );


    }

    if (is_page('info-vacante') || is_page('informacion-de-profesional')) {
        resources('jqueryLast');
        // --------------------------------------
        // se toma el id del dueño del perfil una vez validado
        $data = resources('dueñoUsuario', 'candidata');
        $dataUser = array();
        // si el id existe entonces se hacen las cosas necesiarias para este modulo.
        if (resources('dueñoUsuario', 'candidata')) {
            $u = get_userdata($data);
            global $wp_roles;
            if (is_user_logged_in()) {
                $role = array_shift($u->roles);
            }
            $rol = $wp_roles->roles[$role]['name'];
            $userMeta = get_user_meta($data);
            $dataUser['rol'] = $rol;
            $dataUser['data'] = $userMeta;
            do_shortcode('[formPostulate rol="' . $rol . '" nombre="' . $dataUser['data']['first_name'][0] . '"]');
        }
        if (resources('dueñoUsuario', 'adminTsoluciono')) {
            // si usuario es admin entonces.

            adminInfoVacanteForm();
            // adminPanelButtonSendSelect();

            wp_register_style('adminTsolucionoCss', get_template_directory_uri() . '/../Divi-child/assets/css/adminTsoluciono.css', array(), rand(), 'all');
            wp_enqueue_style('adminTsolucionoCss');

            wp_register_script('adminTsolucionoJs', get_template_directory_uri() . '/../Divi-child/assets/js/adminTsoluciono.js', array(), rand(), 'all');
            wp_enqueue_script('adminTsolucionoJs');

            $admin = admin_url('admin-ajax.php', null);

            wp_localize_script(
                'adminTsolucionoJs',
                's',
                array(
                    'ajaxurl' => $admin,
                )
          );
          }
        // recursos
        resources('bootstrap');
        resources('sweetAlert');

        resources('jQueryUI');

        resources('horaPicker');

        resources('lightbox');

        // estilos y script de pagina
        wp_register_style('vacanteCss', get_template_directory_uri() . '/../Divi-child/assets/css/vacante.css', array(), rand(), 'all');
        wp_enqueue_style('vacanteCss');

        wp_register_script('vacanteJs', get_template_directory_uri() . '/../Divi-child/assets/js/vacante.js', array(), rand(), 'all');
        wp_enqueue_script('vacanteJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'vacanteJs',
            's',
            array(
                'ajaxurl' => $admin,
                'idCandidata' => $data,
            )
        );
    }

    if (is_page('nueva-publicacion-profesional')) {
        // registro de candidatas
        resources('jqueryLast');
        resources('bootstrap');
        resources('sweetAlert');

        resources('filePond');

        // estilos y script de pagina
        wp_register_style('NuevaprofesionalesCss', get_template_directory_uri() . '/../Divi-child/assets/css/Nuevaprofesionales.css', array(), rand(), 'all');
        wp_enqueue_style('NuevaprofesionalesCss');

        wp_register_script('NuevaprofesionalesJs', get_template_directory_uri() . '/../Divi-child/assets/js/Nuevaprofesionales.js', array(), rand(), 'all');
        wp_enqueue_script('NuevaprofesionalesJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'NuevaprofesionalesJs',
            's',
            array(
                'ajaxurl' => $admin
            )
        );
        spinnerLoad();
    }
    if (is_page('profesionales')) {
        // registro de candidatas
        resources('jqueryLast');
        resources('bootstrap');
        resources('sweetAlert');
        // estilos y script de pagina
        wp_register_style('profesionalesCss', get_template_directory_uri() . '/../Divi-child/assets/css/profesionales.css', array(), rand(), 'all');
        wp_enqueue_style('profesionalesCss');

        wp_register_script('profesionalesJs', get_template_directory_uri() . '/../Divi-child/assets/js/profesionales.js', array(), rand(), 'all');
        wp_enqueue_script('profesionalesJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'profesionalesJs',
            's',
            array(
                'ajaxurl' => $admin
            )
        );
    }
    if (is_page('vacantes-disponibles')) {

         resources('bootstrap');

        wp_register_style('vacantesCss', get_template_directory_uri() . '/../Divi-child/assets/css/vacantes.css', array(), rand(), 'all');
        wp_enqueue_style('vacantesCss');

        wp_register_script('vacantesJs', get_template_directory_uri() . '/../Divi-child/assets/js/vacantes.js', array(), rand(), 'all');
        wp_enqueue_script('vacantesJs');

         $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'vacantesJs',
            's',
            array(
                'ajaxurl' => $admin
            )
        );


    }
    if (is_page('registro-de-candidata') || is_page('perfil-de-usuario')) {
        // registro de candidatas
        // resources('jqueryLast');
        resources('bootstrap');
        resources('sweetAlert');
        // estilos y script de pagina
        wp_register_style('registroCandidatoCss', get_template_directory_uri() . '/../Divi-child/assets/css/registroCandidato.css', array(), rand(), 'all');
        wp_enqueue_style('registroCandidatoCss');

        wp_register_script('registroCandidatoJs', get_template_directory_uri() . '/../Divi-child/assets/js/registroCandidato.js', array(), rand(), 'all');
        wp_enqueue_script('registroCandidatoJs');

        // para el ajax
        $admin = admin_url('admin-ajax.php', null);

        wp_localize_script(
            'registroCandidatoJs',
            's',
            array(
                'ajaxurl' => $admin
            )
        );
    }
}add_action('wp_enqueue_scripts', 'aditionalConfig'); // Add Theme Stylesheet

// modulos de funciones
require get_template_directory() . '/../Divi-child/functions/generals/index.php';
require get_template_directory() . '/../Divi-child/functions/contracts/index.php';
require get_template_directory() . '/../Divi-child/functions/entrevistas/index.php';
require get_template_directory() . '/../Divi-child/functions/admin/index.php';



function dias_pasados($fecha_inicial,$fecha_final)
{

    $a = $fecha_inicial;
    $b = $fecha_final;

    $fi = $fecha_inicial;
    $fi = explode('/', $fi);
    $fi = $fi[2].'-'.$fi[1].'-'.$fi[0];
    $fecha_inicial = $fi;

    $ff = $fecha_final;
    $ff = explode('/', $ff);
    $ff = $ff[2].'-'.$ff[1].'-'.$ff[0];
    $fecha_final = $ff;

    $fecha1= new DateTime($fecha_inicial);
    $fecha2= new DateTime($fecha_final);
    $diff = $fecha1->diff($fecha2);

return $diff->days;

}




// #f9f9f9
?>



