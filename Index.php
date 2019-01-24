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
    <link rel="shortcut icon" href="image/logo.ico">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="css/doc.css" >
    <link rel="stylesheet" type="text/css" href="css/bootstrap-material-datetimepicker.css" >
   <!-- <link rel="stylesheet" type="text/css" href="css/foundation-datepicker.css" > -->
     <script src="js/angular.min.js"></script>
     <script src="js/controller/participante.js"></script>
     <script src="js/notify.js"></script>
     <script src="common/apiserver.js"></script>

     <title>IMNC</title>
 </head>
 <body ng-app="myApp" ng-controller="participanteController">
 <div class="container  shadow p-3 mb-5 bg-white rounded" style="width: 60%; background: white;">
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
     <div class="row" ><div style="background-color: #846125;"><h5 class="text-uppercase" style="margin-left: 45px; color: white;margin-top: 7px;">INFORMACIóN</h5></div><img style="height: 40px;" src="image/parte.png"></div>
         <div class="alert alert-success"style="margin-top: 10px;" ng-show="mensaje_success">{{mensaje_success}}</div>
         <ul class="list-group list-group-flush" style="margin-top: 10px;">
             <li class="list-group-item text-uppercase">CLIENTE: {{datosCliente.NOMBRE}} </li>
             <li class="list-group-item text-uppercase">CURSO: {{ datosCurso.NOMBRE }}</li>
             <li class="list-group-item text-uppercase" ng-if="modalidad == 'programado'">MODALIDAD: CURSO PROGRAMADO</li>
             <li class="list-group-item text-uppercase" ng-if="modalidad == 'insitu'">MODALIDAD: CURSO INSITUS</li>
             <li class="list-group-item text-uppercase">ETAPA: {{datosCurso.NOMBRE_ETAPA}}</li>
         </ul>

         <br>
     <div class="container-fluid" ng-show="!isdisabled">
         <div><p>Estimado cliente, pedimos de su apoyo completando el siguiente registro para concluir su inscripción al curso adquirido.</p>
             <p>Favor de adicionar los participantes que desee incluir al curso.</p></div>
         <p class="text-danger">Obligatorio *</p>

     </div>
     <div class="alert alert-danger" ng-show="isdisabled">Este formulario ya no se puede editar, este curso ya no permite inscripción, por favor consulte con el IMNC</div>


     <div class="row text-uppercase"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">NOS GUSTARíA CONOCER</h5></div><img style="height: 40px;" src="image/parte.png"></div>
         <form id="inscricion_form">
         <div class="container-fluid" style="padding-top: 20px;">

             <div style=" {{(error_necesidades?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                 <label for="necesidades"><h5>Necesidades y expectativas del servicio contratado: <label class="text-danger">*</label></h5></label>
                 <textarea  id="necesidades" name="necesidades" rows="3" ng-change="error_necesidades = (formData.necesidades?'':'Complete este campo')" ng-model="formData.necesidades"  placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="Cuéntenos que espera obtener con nuestro curso" ng-disabled="isdisabled">
                 </textarea>
                 <small class="text-danger">{{error_necesidades}}</small>
             </div>
             <div class="form-group">
                 <label for="facturacion"><h5>¿Requiere factura? <label class="text-danger" style="margin-right: 20px;">*</label></h5></label>
                 <select id="facturacion" name="facturacion" ng-model="formData.facturacion" ng-change="onFacturacion()" ng-disabled="isdisabled">
                     <option value="NO" selected="selected">NO</option>
                     <option value="SI">SI</option>
                 </select>
                     <div id="isFacturacion" ng-show="formData.facturacion == 'SI'" style="margin-left: 5px;margin-right: 5px; border-top: rgba(126,72,8,0.62) 1px solid;border-bottom: rgba(126,72,8,0.62) 1px solid; margin-top: 5px;" class="container-fluid">

                             <div class="form-group" style="margin-top: 20px; {{(error_otro_domicilio?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf != true">
                                 <label for="domicilio_fiscal" ><h5>Domicilio Fiscal <label class="text-danger">*</label></h5></label>
                                 <a href="" class="float-right" ng-click="otroDomicilio(true)" ng-show="od != true && cantidad_domicilios > 0" ng-if="!isdisabled"><small>Otro Domicilio</small></a>
                                 <a href="" class="float-right" ng-click="otroDomicilio(false);error_otro_domicilio='';" ng-show="od == true" ng-if="!isdisabled"><small>Cancelar</small></a>
                                 <select id="domicilio_fiscal" name="domicilio_fiscal"ng-init="formData.domicilio_fiscal = datosDomicilios[0]" ng-model="formData.domicilio_fiscal" ng-options="d.NOMBRE for d in datosDomicilios track by d.NOMBRE" style="width:90%;" ng-show="od != true && cantidad_domicilios > 0" ng-change="onDomicilio()" required ng-disabled="isdisabled">
                                 </select>
                                 <input type="text" id="otro_domicilio" name="otro_domicilio" ng-change="error_otro_domicilio = (formData.otro_domicilio?'':'Complete este campo')" ng-model="formData.otro_domicilio" ng-show="od == true || cantidad_domicilios == 0" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="{{(od == true?'Escriba otro domicilio aquí':'')}}" ng-init="formData.otro_domicilio = formData.domicilio_fiscal.NOMBRE">
                                 <small class="text-danger">{{error_otro_domicilio}}</small>
                             </div>
                             <div class="form-group" style="margin-top: 20px; {{(error_otro_domicilio?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf == true">
                                 <label for="domicilio_fiscal" ><h5>Domicilio Fiscal <label class="text-danger">*</label></h5></label>
                               <!--  <a href="" class="float-right" ng-click="otroDomicilio(true)" ng-show="od != true && cantidad_domicilios > 0" ng-if="!isdisabled"><small>Otro Domicilio</small></a>
                                 <a href="" class="float-right" ng-click="otroDomicilio(false);error_otro_domicilio='';" ng-show="od == true" ng-if="!isdisabled"><small>Cancelar</small></a> -->
                                 <input type="text" id="otro_domicilio" name="otro_domicilio" ng-change="error_otro_domicilio = (formData.otro_domicilio?'':'Complete este campo')" ng-model="formData.otro_domicilio" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="{{(od == true?'Escriba otro domicilio aquí':'')}}" ng-init="formData.otro_domicilio = detalles.DOMICILIO" ng-disabled="isdisabled">
                                 <small class="text-danger">{{error_otro_domicilio}}</small>
                             </div>
                             <div class="form-group" style="{{(error_rfc_facturario?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf != true">
                                 <label for="rfc_facturario" ><h5>RFC Facturario <label class="text-danger">*</label></h5></label>
                                 <a href="" class="float-right" ng-click="otroRFC(true)" ng-show="orfc != true" ng-if="(!isdisabled)&& editf!=true"><small>Otro RFC</small></a>
                                 <a href="" class="float-right" ng-click="otroRFC(false);error_rfc_facturario=''" ng-show="orfc == true" ng-if="!isdisabled"><small>Cancelar</small></a>
                                 <label class="label_show"  ng-show="orfc != true">{{(datosCliente.RFC_FACTURARIO? datosCliente.RFC_FACTURARIO:datosCliente.RFC)}}</label>
                                 <input type="text"  ng-show="orfc == true" id="rfc_facturario" name="rfc_facturario" ng-change="error_rfc_facturario = (validar_rfc(formData.rfc_facturario)?'':'Complete este campo')" ng-model="formData.rfc_facturario" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="Escriba otro RFC" ng-init="formData.rfc_facturario = (datosCliente.RFC_FACTURARIO? datosCliente.RFC_FACTURARIO:datosCliente.RFC)">
                                 <small class="text-danger">{{(error_rfc_facturario)}}</small>
                             </div>
                             <div class="form-group" style="{{(error_rfc_facturario?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf == true">
                                 <label for="rfc_facturario" ><h5>RFC Facturario <label class="text-danger">*</label></h5></label>
                                 <a href="" class="float-right" ng-click="otroRFC(true)" ng-show="orfc != true" ng-if="(!isdisabled)&& editf!=true"><small>Otro RFC</small></a>
                                 <a href="" class="float-right" ng-click="otroRFC(false);error_rfc_facturario=''" ng-show="orfc == true" ng-if="!isdisabled"><small>Cancelar</small></a>
                                 <input type="text"  id="rfc_facturario" name="rfc_facturario" ng-change="error_rfc_facturario = (validar_rfc(formData.rfc_facturario)?'':'Complete este campo')" ng-model="formData.rfc_facturario" placeholder="Tu respuesta" required data-toggle="tooltip" data-placement="right" title="Escriba otro RFC" ng-disabled="isdisabled" >
                                 <small class="text-danger">{{(error_rfc_facturario)}}</small>
                             </div>

                             <div class="form-group" style="{{((error_domicilio_contacto||error_otro_contacto_nombre||error_otro_contacto_telefono||error_otro_contacto_email)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf != true">
                                 <label for="domicilio_contacto" ><h5>Contacto <label class="text-danger">*</label></h5></label>
                                 <a href="" class="float-right" ng-click="otroContacto(true)" ng-show="oc != true && cantidad_contacto  > 0" ng-if="!isdisabled "><small>Otro Contacto</small></a>
                                 <a href="" class="float-right" ng-click="otroContacto(false);error_otro_contacto_nombre='';error_otro_contacto_telefono='';error_otro_contacto_email=''" ng-show="oc == true && cantidad_contacto  > 0" ng-if="!isdisabled"><small>Cancelar</small></a>
                                <select id="domicilio_contacto" name="domicilio_contacto" ng-init="formData.domicilio_contacto = datosContactos[0]"  ng-model="formData.domicilio_contacto" ng-options="c as c.TEXTO for c in datosContactos" style="width:90%;" ng-show="oc != true && cantidad_contacto  > 0" required ng-disabled="isdisabled">
                                 </select>
                                 <br>
                                 <small class="text-danger" style="margin-bottom: 20px;" >{{error_domicilio_contacto}}</small>
                                 <input type="text" id="otro_contacto_nombre" name="otro_contacto_nombre" ng-change="error_otro_contacto_nombre = (formData.otro_contacto_nombre?'':'Complete este campo')" ng-model="formData.otro_contacto_nombre" ng-show="oc == true || cantidad_contacto == 0" placeholder="Nombre del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el nombre del contacto aquí':'')}}" ng-init="formData.otro_contacto_nombre = formData.domicilio_contacto.NOMBRE_CONTACTO" >
                                 <small class="text-danger" style="margin-bottom: 20px;">{{error_otro_contacto_nombre}}</small>
                                 <input type="text" id="otro_contacto_telefono" name="otro_contacto_telefono" ng-change="error_otro_contacto_telefono = (validar_telefono(formData.otro_contacto_telefono)?'':'Teléfono Inválido')" ng-model="formData.otro_contacto_telefono" ng-show="oc == true || cantidad_contacto == 0" placeholder="Telefono del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el teléfono del contacto aquí':'')}}" ng-init="formData.otro_contacto_telefono = (formData.domicilio_contacto.TELEFONO_FIJO?formData.domicilio_contacto.TELEFONO_FIJO:formData.domicilio_contacto.TELEFONO_MOVIL)" >
                                 <small class="text-danger" style="margin-bottom: 20px;">{{error_otro_contacto_telefono}}</small>
                                 <input type="text" id="otro_contacto_email" name="otro_contacto_email" ng-change="error_otro_contacto_email = (validar_email(formData.otro_contacto_email)?'':'Correo electrónico Inválido')" ng-model="formData.otro_contacto_email" ng-show="oc == true || cantidad_contacto == 0" placeholder="Correo Elétronico del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el correo elétronico del contacto aquí':'')}}" ng-init="formData.otro_contacto_email = formData.domicilio_contacto.EMAIL" >
                                 <small class="text-danger" style="margin-bottom: 20px;" >{{error_otro_contacto_email}}</small>
                             </div>
                             <div class="form-group" style="{{((error_otro_contacto_nombre||error_otro_contacto_telefono||error_otro_contacto_email)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="editf == true">
                                 <label for="domicilio_contacto" ><h5>Contacto <label class="text-danger">*</label></h5></label>
                               <!--  <a href="" class="float-right" ng-click="otroContacto(true)" ng-show="oc != true && cantidad_contacto  > 0" ng-if="!isdisabled"><small>Otro Contacto</small></a>
                                 <a href="" class="float-right" ng-click="otroContacto(false);error_otro_contacto_nombre='';error_otro_contacto_telefono='';error_otro_contacto_email=''" ng-show="oc == true && cantidad_contacto  > 0" ng-if="!isdisabled"><small>Cancelar</small></a> -->
                                 <small class="text-danger" style="margin-bottom: 20px;" >{{error_domicilio_contacto}}</small>
                                 <input type="text" id="otro_contacto_nombre" name="otro_contacto_nombre" ng-change="error_otro_contacto_nombre = (formData.otro_contacto_nombre?'':'Complete este campo')" ng-model="formData.otro_contacto_nombre"  placeholder="Nombre del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el nombre del contacto aquí':'')}}" ng-disabled="isdisabled">
                                 <small class="text-danger" style="margin-bottom: 20px;">{{error_otro_contacto_nombre}}</small>
                                 <input type="text" id="otro_contacto_telefono" name="otro_contacto_telefono" ng-change="error_otro_contacto_telefono = (validar_telefono(formData.otro_contacto_telefono)?'':'Teléfono Inválido')" ng-model="formData.otro_contacto_telefono"  placeholder="Telefono del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el teléfono del contacto aquí':'')}}" ng-disabled="isdisabled">
                                 <small class="text-danger" style="margin-bottom: 20px;">{{error_otro_contacto_telefono}}</small>
                                 <input type="text" id="otro_contacto_email" name="otro_contacto_email" ng-change="error_otro_contacto_email = (validar_email(formData.otro_contacto_email)?'':'Correo electrónico Inválido')" ng-model="formData.otro_contacto_email"  placeholder="Correo Elétronico del contacto" required data-toggle="tooltip" data-placement="right" title="{{(oc == true?'Escriba el correo elétronico del contacto aquí':'')}}" ng-disabled="isdisabled">
                                 <small class="text-danger" style="margin-bottom: 20px;" >{{error_otro_contacto_email}}</small>
                             </div>

                     </div>
             </div>
             <div class="form-group" style="{{(error_razon_viaRadio?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" >
                 <label for="viaRadio"><h5>¿Por qué medio se enteró nosotros? <label class="text-danger">*</label></h5></label>
                 <div class="custom-control custom-radio" >
                     <input type="radio" id="viaRadio1" name="viaRadio" ng-model="formData.medio" value="He tomado cursos antes con IMNC"  class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio1">He tomado cursos antes con IMNC</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio2" name="viaRadio" ng-model="formData.medio" value='Cliente de "Certificación"' class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio2">Cliente de "Certificación"</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio3" name="viaRadio" ng-model="formData.medio" value='Cliente de "Venta de Normas' class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio3">Cliente de "Venta de Normas"</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio4" name="viaRadio" ng-model="formData.medio" value="Página web" class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio4">Página web</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio5" name="viaRadio" ng-model="formData.medio" value="Redes Sociales" class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio5">Redes Sociales</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio6" name="viaRadio" ng-model="formData.medio" value="Recomendación de una empresa/cliente" class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio6">Recomendación de una empresa/cliente</label>
                 </div>
                 <div class="custom-control custom-radio">
                     <input type="radio" id="viaRadio7" name="viaRadio" ng-model="formData.medio" value="Recomendación de consultor/instructor" class="custom-control-input" ng-disabled="isdisabled">
                     <label class="custom-control-label" for="viaRadio7">Recomendación de consultor/instructor</label>
                 </div>
                 <small class="text-danger">{{error_razon_viaRadio}}</small>

             </div>
     </div>
             <div class="row text-uppercase"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">INSCRIPCIÓN A CURSOS {{(modalidad == 'insitu'?'INSITU':'PROGRAMADOS')}} IMNC</h5></div><img style="height: 40px;" src="image/parte.png"></div>

             <div class="container-fluid" style="padding-top: 20px;">

             <div id="insitu" ng-show="modalidad == 'insitu'">
             <div class="form-group" style="{{((error_sede)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="edit != true">
                 <label for="sede_curso"><h5>Sede del curso: <label class="text-danger">*</label></h5></label>
                 <a href="" class="float-right" ng-click="otraSede(true)" ng-show="sede != true && datosCurso.SEDE!=''" ng-if="!isdisabled"><small>Otra Sede</small></a>
                 <a href="" class="float-right" ng-click="otraSede(false);error_sede;" ng-show="sede == true" ng-if="!isdisabled"><small>Cancelar</small></a>
                 <label class="label_show"  ng-show="sede != true && datosCurso.SEDE">{{(datosCurso.SEDE)}}</label>
                 <input type="text"  ng-show="sede == true || datosCurso.SEDE==''" id="sede_curso" name="sede_curso"  ng-change="error_sede = (formData.sede_curso?'':'Complete este campo')" ng-model="formData.sede_curso" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="{{(sede == true?'Escriba otra Sede':'')}}" required    ng-init="formData.sede_curso = datosCurso.SEDE" >
                    <small class="text-danger">{{error_sede}}</small>
             </div>
             <div class="form-group" style="{{((error_sede)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}" ng-if="edit == true">
                 <label for="sede_curso"><h5>Sede del curso: <label class="text-danger">*</label></h5></label>
                 <textarea  id="sede_curso" name="sede_curso"  ng-change="error_sede = (formData.sede_curso?'':'Complete este campo')" ng-model="formData.sede_curso" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="{{(sede == true?'Escriba otra Sede':'')}}" required   ng-disabled="isdisabled" ></textarea>
                 <small class="text-danger">{{error_sede}}</small>
             </div>
              <div class="form-group" style="{{((error_horario)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                     <label for="hora_inicio"><h5>Horario del Curso: <label class="text-danger">*</label></h5></label>
                     <div class="form-inline text-center" style="border-bottom: rgba(126,72,8,0.62) 1px solid; width: 43%;">
                         <select class="text-center" id="hora_inicio" name="hora_inicio" ng-model="formData.hora_inicio" ng-change="error_horario = (formData.hora_inicio?'':'Complete este campo')" ng-disabled="isdisabled">
                             <option value="" selected="selected">Hora Inicio</option>
                             <option value="7:00 AM">7:00 AM</option>
                             <option value="8:00 AM">8:00 AM</option>
                             <option value="9:00 AM">9:00 AM</option>
                             <option value="10:00 AM">10:00 AM</option>
                             <option value="11:00 AM">11:00 AM</option>
                             <option value="12:00 PM">12:00 PM</option>
                             <option value="1:00 PM">1:00 PM</option>
                         </select>
                          <label style="margin-left: 20px;margin-right: 10px;"> -A- </label>
                          <select class="text-center" id="hora_fin" name="hora_fin" ng-model="formData.hora_fin" disabled >
                             <option ng-value="" ng-selected="true">Hora Fin</option>
                             <option value="4:00 PM" ng-selected="formData.hora_inicio=='7:00 AM'">4:00 PM</option>
                             <option value="5:00 PM" ng-selected="formData.hora_inicio=='8:00 AM'">5:00 PM</option>
                             <option value="6:00 PM" ng-selected="formData.hora_inicio=='9:00 AM'">6:00 PM</option>
                             <option value="7:00 PM" ng-selected="formData.hora_inicio=='10:00 AM'">7:00 PM</option>
                             <option value="8:00 PM" ng-selected="formData.hora_inicio=='11:00 AM'">8:00 PM</option>
                             <option value="9:00 PM" ng-selected="formData.hora_inicio=='12:00 PM'">9:00 PM</option>
                             <option value="10:00 PM" ng-selected="formData.hora_inicio=='1:00 PM'">10:00 PM</option>
                         </select>
                     </div>
                      <small class="text-danger">{{error_horario}}</small>
                 </div>
                 <div class="form-group">
                     <label for="recomendacion_hospedaje"><h5>Recomendación de hospedaje: </h5></label>
                     <input type="text" id="recomendacion_hospedaje" name="recomendacion_hospedaje" ng-model="formData.recomendacion_hospedaje" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required ng-disabled="isdisabled">
                     <small class="text-danger" id="error_recomendacion_hospedaje"></small>
                 </div>
                 <div class="form-group">
                     <label for="recomendacion_transporte"><h5>Recomendación de transporte: </h5></label>
                     <input type="text" id="recomendacion_transporte" name="recomendacion_transporte" ng-model="formData.recomendacion_transporte" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required ng-disabled="isdisabled">
                     <small class="text-danger" id="error_recomendacion_transporte"></small>
                 </div>
                 <div class="form-group">
                     <label for="disponibilidad_traslado"><h5>Disponibilidad para trasladar al instructor (hotel-curso-hotel):</h5></label>
                     <select id="disponibilidad_traslado" name="disponibilidad_traslado" ng-model="formData.disponibilidad_traslado" data-toggle="tooltip" data-placement="right" title="Para servicios fuera de la ciudad de México solamente" required ng-disabled="isdisabled">
                         <option value="NO" selected="selected">NO</option>
                         <option value="SI">SI</option>
                     </select>
                     <small class="text-danger" id="error_disponibilidad_traslado"></small>
                 </div>
                 <div class="form-group"  style="{{((error_medidas_proteccion)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                     <label for="medidas_proteccion"><h5>Medidas de protección requeridas por el instructor para brindar el servicio:</h5></label>
                     <select id="select_medidas_proteccion" name="select_medidas_proteccion" ng-model="formData.select_medidas_proteccion" data-toggle="tooltip" data-placement="right" ng-disabled="isdisabled" >
                         <option value="NO" selected="selected">NO</option>
                         <option value="SI">SI</option>
                     </select>
                     <input ng-show="formData.select_medidas_proteccion=='SI'" type="text" id="medidas_proteccion" name="medidas_proteccion" ng-change="error_medidas_proteccion = (formData.medidas_proteccion?'':'Complete este campo')" ng-model="formData.medidas_proteccion" placeholder="Tu respuesta" data-toggle="tooltip" data-placement="right" title="Indicar si el auditor necesita algún tipo de medidas de protección (cascos, zapatos o ropa especiales) para brindar el servicio" required ng-disabled="isdisabled">
                     <small class="text-danger">{{error_medidas_proteccion}}</small>

                 </div>
                 <div class="form-group"  style="{{((error_fecha_curso)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                     <label for="fecha_curso"><h5>Fecha del Curso: <label class="text-danger">*</label></h5></label>
                       <input type="text" data-select="datepicker"  class="text-center"  id="fecha_curso" style="width: 20%;" name="fecha_curso" ng-change="error_fecha_curso = (validar_fecha(formData.fecha_curso)?'':'Fecha Inválida')" ng-model="formData.fecha_curso" placeholder="Dia/Mes/Año" data-toggle="tooltip" data-placement="right" title="Fecha en la que le gustaría que se realice el curso. Se usará como referencia para realizar la programación" required  ng-disabled="isdisabled" readonly>
                     <small class="text-danger">{{error_fecha_curso}}</small>
                 </div>
             </div>
             <div id="programado" ng-show="modalidad == 'programado'">
                 <div class="form-group"  style="{{((error_estado_visita)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                     <label for="estado_visita"><h5>Estado del que nos visita:  <label class="text-danger">*</label></h5></label>
                     <select ng-model="formData.estado_visita" ng-options="estado.ENTIDAD_FEDERATIVA as estado.ENTIDAD_FEDERATIVA for estado in estados"
                              id="estado_visita" name="estado_visita" ng-change="error_estado_visita = (formData.estado_visita?'':'Complete este campo')" required ng-disabled="isdisabled">
                         <option value="">Seleccione un Estado</option>
                     </select>
                     <small class="text-danger">{{error_estado_visita}}</small>
                 </div>
             </div>


     </form>
     </div>
     <div class="row"><div style="background-color: #846125;"><h5 style="margin-left: 45px; color: white;margin-top: 7px;">REGISTRO DE LOS PARTICIPANTES</h5></div><img style="height: 40px;" src="image/parte.png"></div>
     <div class="container-fluid" style="padding-top: 20px; ">
         <table class="table " ng-show="cantidad_insertados>0">
             <thead>
                 <th colspan="6">Participantes  <a href="" style="margin-left: 20px;"  ng-click="showFormP()" ng-show="show_p == false && cantidad_insertados>0 && cantidad_insertados < total && (!isdisabled)"><small>+ Agregar Participante</small></a><div class="badge badge-secondary float-right">{{cantidad_insertados}} / {{total}}</div></th>
             </thead>

         </table>

         <div class="card" id="formParticipantes"   ng-show="(show_p == true  || cantidad_insertados==0) && (!isdisabled) "  style="margin-bottom: 20px;margin-top: 20px; {{((mensaje)?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
             <h5 class="card-header">{{title_form_participante}}</h5>
             <div class="card-body">
                 <form novalidate>
                     <div class="form-group" style=" {{(error_nombre_participante?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                         <label for="nombre_participante"><h5>Nombre:  <label class="text-danger">*</label></h5></label>
                         <input type="text" id="nombre_participante" name="nombre_participante" ng-change="error_nombre_participante = (formDataParticipante.nombre_participante?'':'Complete este campo')" ng-model="formDataParticipante.nombre_participante" placeholder="Tu respuesta"  required
                                ng-class="{ error: inscricion_form.nombre_participante.$error.required && !nombre_participante.$pristine}">
                         <small class="text-danger"  >{{error_nombre_participante}}</small>
                     </div>
                     <div class="form-group"  style="{{(error_email_participante?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                         <label for="email_participante"><h5>Correo Electrónico:  <label class="text-danger">*</label></h5></label>
                         <input type="text" id="email_participante" name="email_participante" ng-change="error_email_participante = (validar_email(formDataParticipante.email_participante)?'':'Correo electrónico Inválido')"  ng-model="formDataParticipante.email_participante" placeholder="Tu respuesta"  required >
                         <small class="text-danger" >{{error_email_participante}}</small>
                     </div>
                     <div class="form-group"  style="{{(error_telefono_participante?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                         <label for="telefono_participante"><h5>Teléfono:  <label class="text-danger">*</label></h5></label>
                         <input type="text" id="telefono_participante" name="telefono_participante" ng-change="error_telefono_participante = (validar_telefono(formDataParticipante.telefono_participante)?'':'Telefono Inválido')"  ng-model="formDataParticipante.telefono_participante" placeholder="Tu respuesta"  required >
                         <small class="text-danger" >{{error_telefono_participante}}</small>
                     </div>
                     <div class="form-group" style="{{(error_curp_participante?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                         <label for="curp_participante"><h5>CURP del participante:  <label class="text-danger">*</label></h5></label>
                         <input type="text" id="curp_participante" name="curp_participante" ng-change="onChangeCURP()" ng-model="formDataParticipante.curp_participante" placeholder="Tu respuesta"  required >
                         <small class="text-danger" >{{error_curp_participante}}</small>
                     </div>
                     <div class="form-group" style="{{(error_perfil_participante?'background-color: rgba(255,9,19,0.16);border-radius: 5px;padding: 5px; ':'')}}">
                         <label for="perfil_participante"><h5>Perfil del participante:  <label class="text-danger">*</label></h5></label>
                         <textarea  id="perfil_participante" name="perfil_participante" rows="3" ng-change="error_perfil_participante = (formDataParticipante.perfil_participante?'':'Complete este campo')" ng-model="formDataParticipante.perfil_participante"  placeholder="Tu respuesta" required >
                   </textarea>
                         <small class="text-danger" >{{error_perfil_participante}}</small>

                     </div>
                     <div class="form-group ">
                         <div ng-show="mensaje" class="rounded" style="color: #ff0707;margin-bottom:20px;padding:5px;border: #ccc 1px solid;border-left: rgb(255,60,21) 4px solid;">{{mensaje}}</div>
                         <button id="guardarP" ng-show="accion == 'editar'" class="btn btn-secondary btn-sm" ng-click="submitFormParticipante('editar')" >Editar participante</button>
                         <button id="guardarP" ng-show="accion == 'editar'" class="btn btn-sm" ng-click="cancelEditParticipantes()" style="margin-left: 10px;">Cancelar</button>
                         <button  id="guardarP" ng-show="accion == 'editar'" class="btn  btn-sm btn-danger float-right" ng-click="eliminaParticipante()" >Eliminar</button>
                         <button id="guardarP" ng-show="accion != 'editar'" class="btn btn-secondary btn-sm" ng-click="submitFormParticipante('insertar')" >+ Agregar participante</button>
                         <button id="guardarP" ng-show="accion != 'editar' && cantidad_insertados>0" class="btn btn-sm" ng-click="cancelEditParticipantes()" style="margin-left: 10px;">Cancelar</button>
                     </div>

                 </form>
             </div>
         </div>


         <div class="card-columns">
         <div class="card " ng-repeat="(key, item) in participantes">
             <div class="card-body">
                 <h5 class="card-title"><strong>{{ item.NOMBRE }}</strong></h5>
                 <p class="card-text">{{ item.PERFIL }}</p>
             </div>
             <ul class="list-group list-group-flush">
                 <li class="list-group-item">Correo Electrónico: <strong>{{ item.EMAIL }}</strong></li>
                 <li class="list-group-item">Telefono: <strong>{{ item.TELEFONO }}</strong></li>
                 <li class="list-group-item">CURP: <strong>{{ item.CURP }}</strong></li>
             </ul>
             <div class="card-body">
                 <button ng-if="!isdisabled" class="btn btn-secondary btn-sm" ng-click="showEditParticipantes(key);" ><i class="fa fa-pencil"></i>Editar</button>
             </div>
         </div>
         </div>
         <br>
         <small class="text-danger" >{{error_limite}}</small>





     </div>
     <div class="form-group" style="margin-top: 40px; border-top: rgba(126,72,8,0.62) 1px solid"  >
         <button class="btn btn-primary" ng-click="submitForm()" data-toggle="tooltip" data-placement="right" style="margin-top: 20px;" title="Al hacer click aquí para enviar todos los datos introducidos al formulario" ng-if="!isdisabled">Enviar al IMNC </button>
         <div class="d-flex align-items-center hide" ng-show="enviar == true">
             <strong>Enviando los datos, espere por favor...</strong>
             <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
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
 <!-- Flexbox container for aligning the toasts -->
<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">

<div class="toast" id="mensaje" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <img src="..." class="rounded mr-2" alt="...">
        <strong class="mr-auto">{{toast_title}}</strong>
        <small class="text-muted"></small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        {{toast_body}}
    </div>
</div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="js/jquery.min.js"></script>
 <script src="js/popper.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
 <script src="js/moment.min.js"></script>
 <script src="js/bootstrap-material-datetimepicker.js"></script>
<!--<script src="js/foundation-datepicker.js"></script>
<script src="js/foundation-datepicker.es.js"></script> -->









</body>
</html>
