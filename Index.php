<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 07/01/2019
 * Time: 15:47
 */
?>

<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="css/doc.css" >
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" >
    <script src="js/angular.min.js"></script>
    <script src="js/controller/participante.js"></script>
    <script src="js/notify.js"></script>
    <script src="common/apiserver.js"></script>

    <title>IMNC</title>
</head>
<body ng-app="myApp" ng-controller="participanteController">
<div class="container  w-50 shadow p-3 mb-5 bg-white rounded" style=" background: white;">
    <div id="si" ng-if="token_valido == true">
    <div class="container-fluid">
        <div class="text-center">
            <img src="image/logob.png" class="rounded w-25" style="padding-top: 20px;" alt="...">
            <h5 style="color: #846125;">Instituto Mexicano de Normalización y Certificación, A.C</h5>
            <div style="width:100%; height: 1px; background-color:#846125; margin-bottom: 10px;"></div>
            <h5>Centro Internacional de Formación y Aprendizaje (CIFA)</h5>
            <br>

        </div>
    </div>
    <div class="row"><div style="background-color: #846125;"><h5 class="text-uppercase" style="margin-left: 45px; color: white;margin-top: 7px;">INFORMACIóN</h5></div><img style="height: 40px;" src="image/parte.png"></div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item text-uppercase">CLIENTE: {{datosCliente.NOMBRE}} </li>
            <li class="list-group-item text-uppercase">CURSO: {{ datosCurso.NOMBRE }}</li>
            <li class="list-group-item text-uppercase" ng-if="modalidad == 'programado'">MODALIDAD: CURSO PROGRAMADO</li>
            <li class="list-group-item text-uppercase" ng-if="modalidad == 'insitu'">MODALIDAD: CURSO INSITUS</li>
        </ul>

        <br>
    <div class="container-fluid">
        <div><p>Estimado cliente, pedimos de su apoyo completando el siguiente registro para concluir su inscripción al curso adquirido.</p>
            <p>Favor de adicionar los participantes que desee incluir al curso.</p></div>
        <p class="text-danger">Obligatorio *</p>

    </div>
    <div class="row text-uppercase"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">NOS GUSTARíA CONOCER</h5></div><img style="height: 40px;" src="image/parte.png"></div>
        <form>
        <div class="container-fluid" style="padding-top: 20px;">

            <div >
                <label for="necesidades"><h5>Necesidades y expectativas del servicio contratado: <label class="text-danger">*</label></h5></label>
                <textarea  id="necesidades" name="necesidades" rows="3" ng-model="formData.necesidades"  placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="Cuéntenos que espera obtener con nuestro curso">
                </textarea>
                <small class="text-danger" id="error_razon_social"></small>
            </div>
            <div class="form-group">
                <label for="facturacion"><h5>¿Requiere factura? <label class="text-danger" style="margin-right: 20px;">*</label></h5></label>
                <select id="facturacion" name="facturacion" ng-model="formData.facturacion" ng-change="onFacturacion()">
                    <option value="NO" selected="selected">NO</option>
                    <option value="SI">SI</option>
                </select>
                <small class="text-danger" id="error_nombre"></small>
                    <div id="isFacturacion" ng-show="formData.facturacion == 'SI'" style="margin-left: 5px;margin-right: 5px; border-top: rgba(126,72,8,0.62) 1px solid;border-bottom: rgba(126,72,8,0.62) 1px solid; margin-top: 5px;" class="container-fluid">

                            <div class="form-group" style="margin-top: 20px;">
                                <label for="domicilio_fiscal" ><h5>Domicilio Fiscal <label class="text-danger">*</label></h5></label>
                                <a href="" class="float-right" ng-click="otroDomicilio(true)" ng-show="od != true"><small>Otro Domicilio</small></a>
                                <a href="" class="float-right" ng-click="otroDomicilio(false)" ng-show="od == true"><small>Cancelar</small></a>
                                <select id="domicilio_fiscal" name="domicilio_fiscal"ng-init="formData.domicilio_fiscal = datosDomicilios[0]" ng-model="formData.domicilio_fiscal" ng-options="d.NOMBRE for d in datosDomicilios" style="width:90%;" ng-show="od != true && cantidad_domicilios > 0" ng-change="onDomicilio()" required>
                                </select>

                                <input type="text" id="otro_domicilio" name="otro_domicilio" ng-model="formData.otro_domicilio" ng-show="od == true || cantidad_domicilios == 0" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="{{(od == true?'Escriba otro domicilio aquí':'')}}" ng-init="formData.otro_domicilio = formData.domicilio_fiscal.NOMBRE">
                                <small class="text-danger" id="error_otro_domicilio"></small>
                            </div>
                            <div class="form-group">
                                <label for="rfc_facturario" ><h5>RFC Facturario <label class="text-danger">*</label></h5></label>
                                <a href="" class="float-right" ng-click="otroRFC(true)" ng-show="orfc != true"><small>Otro RFC</small></a>
                                <a href="" class="float-right" ng-click="otroRFC(false)" ng-show="orfc == true"><small>Cancelar</small></a>
                                <input type="text" id="rfc_facturario" name="rfc_facturario" ng-model="formData.rfc_facturario" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="{{(orfc == true?'Escriba otro RFC':'')}}" ng-value="datosCliente.RFC_FACTURARIO? datosCliente.RFC_FACTURARIO:datosCliente.RFC" readonly>
                                <small class="text-danger" id="error_rfc_facturario"></small>
                            </div>
                            <div class="form-group">
                                <label for="domicilio_contacto" ><h5>Contacto <label class="text-danger">*</label></h5></label>
                                <a href="" class="float-right" ng-click="otroContacto(true)" ng-show="oc != true"><small>Otro Contacto</small></a>
                                <a href="" class="float-right" ng-click="otroContacto(false)" ng-show="oc == true"><small>Cancelar</small></a>
                               <select id="domicilio_contacto" name="domicilio_contacto" ng-init="formData.domicilio_contacto = datosContactos[0]"  ng-model="formData.domicilio_contacto" ng-options="c as c.TEXTO for c in datosContactos" style="width:90%;" ng-show="oc != true && cantidad_domicilios > 0" required>
                                   <small class="text-danger" id="error_domicilio_contacto"></small>
                                </select>
                                <input type="text" id="otro_contacto_nombre" name="otro_contacto_nombre" ng-model="formData.otro_contacto_nombre" ng-show="oc == true || cantidad_contacto == 0" placeholder="Nombre del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el nombre del contacto aquí':'')}}"  style="margin-bottom: 20px;">
                                <small class="text-danger" id="error_otro_contacto_nombre"></small>
                                <input type="text" id="otro_contacto_telefono" name="otro_contacto_telefono" ng-model="formData.otro_contacto_telefono" ng-show="oc == true || cantidad_contacto == 0" placeholder="Telefono del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el teléfono del contacto aquí':'')}}" style="margin-bottom: 20px;" >
                                <small class="text-danger" id="error_otro_contacto_nombre"></small>
                                <input type="text" id="otro_contacto_email" name="otro_contacto_email" ng-model="formData.otro_contacto_email" ng-show="oc == true || cantidad_contacto == 0" placeholder="Correo Elétronico del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el correo elétronico del contacto aquí':'')}}" >
                                <small class="text-danger" id="error_otro_contacto_nombre"></small>
                            </div>
                    </div>
            </div>
            <div class="form-group">
                <label for="viaRadio"><h5>¿Por qué medio se enteró nosotros? <label class="text-danger">*</label></h5></label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio1" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio1">He tomado cursos antes con IMNC</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio2" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio2">Cliente de "Certificación"</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio3" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio3">Cliente de "Venta de Normas"</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio4" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio4">Página web</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio5" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio5">Redes Sociales</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio6" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio6">Recomendación de una empresa/cliente</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="viaRadio7" name="viaRadio" class="custom-control-input">
                    <label class="custom-control-label" for="viaRadio7">Recomendación de consultor/instructor</label>
                </div>
                <small class="text-danger" id="error_razon_viaRadio"></small>
            </div>
    </div>
            <div class="row text-uppercase"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">INSCRIPCIÓN A CURSOS {{(modalidad == 'insitu'?'INSITU':'PROGRAMADOS')}} IMNC</h5></div><img style="height: 40px;" src="image/parte.png"></div>

            <div class="container-fluid" style="padding-top: 20px;">

            <div id="insitu" ng-show="modalidad == 'insitu'">
            <div class="form-group">
                <label for="sede_curso"><h5>Sede del curso: <label class="text-danger">*</label></h5></label>
                <a href="" class="float-right" ng-click="otraSede(true)" ng-show="sede != true"><small>Otra Sede</small></a>
                <a href="" class="float-right" ng-click="otraSede(false)" ng-show="sede == true"><small>Cancelar</small></a>
                <input type="text" id="sede_curso" name="sede_curso" ng-model="formData.sede_curso" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="{{(sede == true?'Escriba otra Sede':'')}}" required    ng-value="datosCurso.SEDE" readonly >
                    <small class="text-danger" id="error_sede"></small>
            </div>
             <div class="form-group">
                    <label for="hora_inicio"><h5>Horario del Curso: <label class="text-danger">*</label></h5></label>
                    <div class="form-inline">
                        <input type="text" class="timepicker timepicker-with-dropdown text-center" id="hora_inicio" style="width: 20%; margin-right: 10px;" name="hora_inicio" ng-model="formData.hora_inicio" placeholder="Hora Inicio" required  >
                        -
                        <input type="text" class="timepicker timepicker-with-dropdown text-center" id="hora_fin"  style="width: 20%; margin-left: 10px;" name="hora_fin" ng-model="formData.hora_fin" placeholder="Hora Fin" required  >

                    </div>
                     <small class="text-danger" id="error_horario"></small>
                </div>
                <div class="form-group">
                    <label for="recomendacion_hospedaje"><h5>Recomendación de hospedaje: </h5></label>
                    <input type="text" id="recomendacion_hospedaje" name="recomendacion_hospedaje" ng-model="formData.recomendacion_hospedaje" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required >
                    <small class="text-danger" id="error_recomendacion_hospedaje"></small>
                </div>
                <div class="form-group">
                    <label for="recomendacion_transporte"><h5>Recomendación de transporte: </h5></label>
                    <input type="text" id="recomendacion_transporte" name="recomendacion_transporte" ng-model="formData.recomendacion_transporte" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required >
                    <small class="text-danger" id="error_recomendacion_transporte"></small>
                </div>
                <div class="form-group">
                    <label for="disponibilidad_traslado"><h5>Disponibilidad para trasladar al instructor (hotel-curso-hotel):<label class="text-danger" style="margin-right: 20px;">*</label></h5></label>
                    <select id="disponibilidad_traslado" name="disponibilidad_traslado" ng-model="formData.disponibilidad_traslado" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required >
                        <option value="NO" selected="selected">NO</option>
                        <option value="SI">SI</option>
                    </select>
                    <small class="text-danger" id="error_disponibilidad_traslado"></small>
                </div>
                <div class="form-group">
                    <label for="medidas_proteccion"><h5>Medidas de protección requeridas por el instructor para brindar el servicio:<label class="text-danger" style="margin-right: 20px;">*</label></h5></label>
                    <select id="select_medidas_proteccion" name="select_medidas_proteccion" ng-model="formData.select_medidas_proteccion" data-toggle="tooltip" data-placement="right"  >
                        <option value="NO" selected="selected">NO</option>
                        <option value="SI">SI</option>
                    </select>
                    <input ng-show="formData.select_medidas_proteccion=='SI'" type="text" id="medidas_proteccion" name="recomendacion_transporte" ng-model="formData.recomendacion_transporte" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Indicar si el auditor necesita algún tipo de medidas de protección (cascos, zapatos o ropa especiales) para brindar el servicio" required >
                    <small class="text-danger" id="error_medidas_proteccion"></small>

                </div>
                <div class="form-group">
                    <label for="fecha_curso"><h5>Fecha del Curso: <label class="text-danger">*</label></h5></label>
                    <div class="form-inline">
                        <input type="text" class="text-center" id="fecha_curso" style="width: 20%;" name="fecha_curso" ng-model="formData.fecha_curso" placeholder="Dia/Mes/Año" data-toggle="tooltip" data-placement="right" title="Fecha en la que le gustaría que se realice el curso. Se usará como referencia para realizar la programación" required  >
                    </div>
                    <small class="text-danger" id="error_fecha_curso"></small>
                </div>
            </div>
            <div id="programado" ng-show="modalidad == 'programado'">
                <div class="form-group">
                    <label for="estado_visita"><h5>Estado del que nos visita:  <label class="text-danger">*</label></h5></label>
                    <select ng-model="formData.estado_visita" ng-options="estado.ENTIDAD_FEDERATIVA as estado.ENTIDAD_FEDERATIVA for estado in estados"
                             id="estado_visita" name="estado_visita"  required >
                        <option value="">Seleccione un Estado</option>
                    </select>
                    <small class="text-danger" id="error_estado_visita"></small>
                </div>
            </div>


    </form>
    </div>
    <div class="row"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">REGISTRO DE LOS PARTICIPANTES</h5></div><img style="height: 40px;" src="image/parte.png"></div>
    <div class="container-fluid" style="padding-top: 20px;">
        <ul class="list-group" ng-show="cantidad_insertados>0" style="margin-bottom: 20px;">
            <li class="list-group-item list-group-item-primary li">Participantes <div class="badge badge-secondary float-right">{{cantidad_insertados}} / {{total}}</div></li>
            <li ng-repeat="p in participantes" class="list-group-item" > {{ p.NOMBRE }} / {{p.EMAIL}} / {{p.CURP}} / {{p.PERFIL}}</li>
        </ul>
        <a href=""  ng-click="show = true" ng-show="show != true && cantidad_insertados>0"><small>+ Agregar Participante</small></a>
        <form ng-show="cantidad_insertados == 0 || show == true" ng-if="cantidad_insertados<total">
          <div id="formParticipantes">
              <div class="form-group">
                  <label for="nombre_participante"><h5>Nombre del participante:  <label class="text-danger">*</label></h5></label>
                  <input type="text" id="nombre_participante" name="nombre_participante" ng-model="formDataParticipante.nombre_participante" placeholder="Tu respuesta"  required >
                  <small class="text-danger" id="error_nombre_participante"></small>
              </div>
              <div class="form-group">
                  <label for="email_participante"><h5>Correo Electrónico:  <label class="text-danger">*</label></h5></label>
                  <input type="text" id="email_participante" name="email_participante" ng-model="formDataParticipante.email_participante" placeholder="Tu respuesta"  required >
                  <small class="text-danger" id="error_email_participante"></small>
              </div>
              <div class="form-group">
                  <label for="curp_participante"><h5>CURP del participante:  <label class="text-danger">*</label></h5></label>
                  <input type="text" id="curp_participante" name="curp_participante" ng-model="formDataParticipante.curp_participante" placeholder="Tu respuesta"  required >
                  <small class="text-danger" id="error_curp_participante"></small>
              </div>
              <div class="form-group">
                  <label for="perfil_participante"><h5>Perfil del participante:  <label class="text-danger">*</label></h5></label>
                  <textarea  id="perfil_participante" name="perfil_participante" rows="3" ng-model="formDataParticipante.perfil_participante"  placeholder="Tu respuesta" required >
                  </textarea>
                  <small class="text-danger" id="error_perfil_participante"></small><br>
                  <button class="btn btn-secondary" ng-click="submitFormParticipante()" data-toggle="tooltip" data-placement="right" title="Al hacer click aquí le agregaría un participante al curso.">+ Agregar participante</button>
              </div>



          </div>
        </form>
        <div class="form-group" style="margin-top: 20px;">
            <button class="btn btn-primary" ng-click="" ng-show="cantidad_insertados==total" data-toggle="tooltip" data-placement="right" title="Al hacer click aquí para enviar todos los datos introducidos al formulario">Enviar formulario</button>
        </div>
    </div>
    </div>
    <div id="no" class="text-center" ng-if="token_valido == false">
        <img src="image/logob.png" class="rounded w-25" style="padding-top: 20px;" alt="...">
        <h4 style="color: #846125;">Instituto Mexicano de Normalización y Certificación, A.C</h4>
        <div style="width:100%; height: 1px; background-color:#846125; margin-bottom: 10px;"></div>
        <h2>Lo sentimos, hay un problema de seguridad.</h2>
        <h3 style="color: red;">Token Inválido</h3>
        <p>Por favor pongase en contacto con el instituto, para que le envíen nuevamente el link del formulario.</p>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>



</body>
</html>
