<?php
/*
Template Name: Formulario pre-registro */

 
?>

 <?php get_header();

$custom_logo_id = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src($custom_logo_id, 'full');

?>
<div class="container">


<div class="preForm" id="preForm">

<div class="logoForm">
    <img src="" alt="">
</div>

<h4>Formulario de Postulación</h4>

<form action="" class="preData" id="preData">

    <div class="personal">
            <h6>Datos personales</h6>

        <div class="row">
            <div class="field col form-group nombreCompleto">
                <label for="nombreCompleto">Nombre completo *</label>
                <input type="text" class="form-control" name="nombreCompleto">
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group cedula">
                <label for="cedula">Número de cédula *</label>
                <input type="text" class="form-control" name="cedula">
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group edad">
                <label for="edad">Edad *</label>
                <input type="number" class="form-control" name="edad">
                <small class="validateMessage"></small>
            </div>
        </div>

        <div class="row">
            <div class="field col form-group ciudadResidencia">
                <label for="ciudadResidencia">Ciudad de residencia *</label>
                <input type="text" class="form-control" name="ciudadResidencia">
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group direccionResidencia">
                <label for="direccionResidencia">Dirección de residencia *</label>
                <input type="text" class="form-control" name="direccionResidencia">
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group telefonoMovil">
                <label for="telefonoMovil">Teléfono celular *</label>
                <input type="tel" class="form-control" name="telefonoMovil">
                <small class="validateMessage"></small>
            </div>
        </div>

        <div class="row">
            <div class="field col form-group telefonoFijo">
                <label for="telefonoFijo">Teléfono fijo</label>
                <input type="tel" class="form-control" name="telefonoFijo">
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group estadoCivil">
                <label for="estadoCivil">Estado civil *</label>
                    <select class="form-control" name="estadoCivil">
                        <option value="Soltera(o)">Soltera(o)</option>
                        <option value="Casada(o)">Casada(o)</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group numeroHijos">
                <label for="numeroHijos">Número de hijos</label>
                <input type="number" class="form-control" name="numeroHijos">
                <small class="validateMessage"></small>
            </div>
        </div>

        <div class="row">
            <div class="field col form-group culminoBachillerato">
                <label for="culminoBachillerato">¿Culminó el bachillerato? (No es requisito) *</label>
                    <select class="form-control" name="culminoBachillerato">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group algunEstudioSuperior">
                <label for="algunEstudioSuperior">¿Realizó algún estudio superior? (No es requisito)</label>
                <input type="text" class="form-control" name="algunEstudioSuperior">
                <small class="validateMessage"></small>
            </div>
        </div>

    </div>
    <div class="laboralInfo">
        <h6>Información laboral</h6>
        <div class="row">
            <div class="field col form-group disponibilidadFinesDeSemana">
                <label for="disponibilidadFinesDeSemana">¿Tiene disponibilidad de trabajar fines de semana?</label>
                <select class="form-control" name="disponibilidadFinesDeSemana">
                    <option value="No">No</option>
                    <option value="Si">Si</option>
                </select>
                <small class="validateMessage"></small>
            </div>
            <div class="field col form-group tieneReferenciaDeEmpleos">
                <label for="tieneReferenciaDeEmpleos">¿Tiene referencias de sus anteriores empleos? *</label>
                <select class="form-control" name="tieneReferenciaDeEmpleos">
                    <option value="No">No</option>
                    <option value="Si">Si</option>
                </select>
                <small>En caso afirmativo indique nombre completo y telefonos de contacto</small>
                <small class="validateMessage"></small>
            </div>
        </div>
        <div class="row">
            <div class="field form-group col referenciasAfirmativo">
                    <label for="referenciasAfirmativo">Referencias</label>
                    <textarea class="form-control" name="referenciasAfirmativo" id="" cols="30" rows="5"></textarea>
                    <small class="validateMessage"></small>
            </div>
        </div>
    </div>
    <div class="hasWork">
        <h6>Ha trabajado como</h6>
        <div class="row">
            <div class="field col form-group empleadaDomesticaFamilia">
                    <label for="empleadaDomesticaFamilia">¿Empleada doméstica en casa de familia? *</label>
                    <select class="form-control" name="empleadaDomesticaFamilia">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
             <div class="field col form-group anosEmpleadaCasaFamilia">
                    <label for="anosEmpleadaCasaFamilia">¿Cuántos años?</label>
                    <input type="number" class="form-control" name="anosEmpleadaCasaFamilia">
                    <small class="validateMessage"></small>
            </div>
        </div>
        <div class="row">
            <div class="field col form-group empleadaNinera">
                    <label for="empleadaNinera">¿Has sido niñera? *</label>
                    <select class="form-control" name="empleadaNinera">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
             <div class="field col form-group anosEmpleadaNinera">
                    <label for="anosEmpleadaNinera">¿Cuántos años?</label>
                    <input type="number" class="form-control" name="anosEmpleadaNinera">
                    <small class="validateMessage"></small>
            </div>
        </div>
        <div class="row">
            <div class="field col form-group empleadaCuidadoraAdultoMayor">
                    <label for="empleadaCuidadoraAdultoMayor">¿Has sido cuidadora de adulto mayor? *</label>
                    <select class="form-control" name="empleadaCuidadoraAdultoMayor">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
             <div class="field col form-group anosEmpleadaCuidadoraAdulto">
                    <label for="anosEmpleadaCuidadoraAdulto">¿Cuántos años?</label>
                    <input type="number" class="form-control" name="anosEmpleadaCuidadoraAdulto">
                    <small class="validateMessage"></small>
            </div>
        </div>
        <div class="row">
            <div class="field col form-group empleadaCuidadoDeMascotas">
                    <label for="empleadaCuidadoDeMascotas">¿Has sido cuidadora de mascotas? *</label>
                    <select class="form-control" name="empleadaCuidadoDeMascotas">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
             <div class="field col form-group anosCuidadoraMascota">
                    <label for="anosCuidadoraMascota">¿Cuántos años?</label>
                    <input type="number" class="form-control" name="anosCuidadoraMascota">
                    <small class="validateMessage"></small>
            </div>
        </div>
        <div class="row">
            <div class="field col form-group empleadaCuidadoNeoNatales">
                    <label for="empleadaCuidadoNeoNatales">¿Experiencia cuidando recien nacidos? *</label>
                    <select class="form-control" name="empleadaCuidadoNeoNatales">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
             <div class="field col form-group anosCuidadoNeoNato">
                    <label for="anosCuidadoNeoNato">¿Cuántos años?</label>
                    <input type="number" class="form-control" name="anosCuidadoNeoNato">
                    <small class="validateMessage"></small>
            </div>
        </div>
    </div>
    <div class="hability">
        <h6>Como califica sus habilidades en</h6>
         <div class="row">
            <div class="field col form-group calificaLimpieza">
                    <label for="calificaLimpieza">Limpieza</label>
                    <select class="form-control" name="calificaLimpieza">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group calificaCuidadoNinos">
                    <label for="calificaCuidadoNinos">Cuidado de niños</label>
                    <select class="form-control" name="calificaCuidadoNinos">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group calificaCuidadoAncianos">
                    <label for="calificaCuidadoAncianos">Cuidado de ancianos</label>
                    <select class="form-control" name="calificaCuidadoAncianos">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
        </div>
         <div class="row">
            <div class="field col form-group calificaCocina">
                    <label for="calificaCocina">Cocina</label>
                    <select class="form-control" name="calificaCocina">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group calificaPlanchado">
                    <label for="calificaPlanchado">Planchado</label>
                    <select class="form-control" name="calificaPlanchado">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
            <div class="field col form-group calificaCuidadoEnfermos">
                    <label for="calificaCuidadoEnfermos">Cuidado de enfermos</label>
                    <select class="form-control" name="calificaCuidadoEnfermos">
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                    <small class="validateMessage"></small>
            </div>
        </div>
    </div>

</form>
    <h5>"GRACIAS POR SU INTERÉS EN TRABAJAR CON NOSOTROS. PRONTO ESTAREMOS EN CONTACTO"</h5>
    <div class="opc row justify-content-center">
        <button onclick="guardarPreForm()" type="button" class="btn btn-primary">Guardar</button>
    </div>
</div>






</div>


 <?php get_footer();?>

