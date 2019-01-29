/**
 * @ngdoc controller
 * @name controller:appParticipantesCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('myApp', []).controller('participanteController', function($scope) {
  $scope.formData = {};
  $scope.formDataParticipante = {};
  $scope.datosCliente = {};
  $scope.modalidad = "";
  $scope.datosCurso = {};
  $scope.datosDomicilios = {};
  $scope.datosContactos = {};
  $scope.participantes = {};
  $scope.detalles = {};
  $scope.ID = "";
  $scope.formData.facturacion = "NO";
  $scope.formData.select_medidas_proteccion = "NO";
  $scope.formData.disponibilidad_traslado = "NO";
  $scope.cantidad_domicilios = 0;
  $scope.cantidad_contacto = 0;
  $scope.cantidad_insertados = 0; // cantidad de participantes insertados
  $scope.total = 0; //cantidad total de participantes que tiene registrado ese curso
  $scope.show_p = false;
  $scope.formData.medio = "";
  $scope.isdisabled = false;
  $scope.isedit = false;
  $scope.title_form_participante = "Agregar Participante";


  $scope.token = getQueryVariable("token");

  $scope.valida_form = function () {

  }

// ================================================================================
// *****         Verifica el token, si correcto devuelve datos                *****
// ================================================================================/
$scope.verificaToken = function () {
    var token = {
        TOKEN:  $scope.token
    };
  
    $.post(global_apiserver + "/getDecodeToken/", JSON.stringify(token), function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta.validez == "valido") {
          $scope.token_valido = true;
            $scope.datosCliente = respuesta.CLIENTE;
            $scope.modalidad = respuesta.MODALIDAD;
            $scope.datosCurso = respuesta.CURSO;
            $scope.datosDomicilios = respuesta.DOMICILIOS;
            $scope.datosContactos = (respuesta.DOMICILIOS.length>0?respuesta.DOMICILIOS[0].CONTACTOS:{});
            $scope.ID = respuesta.ID;
            $scope.detalles = respuesta.DETALLES;
            $scope.isedit = respuesta.EDIT;
            $scope.cantidad_domicilios = respuesta.CD;
            $scope.cantidad_contacto = (respuesta.DOMICILIOS.length>0?respuesta.DOMICILIOS[0].CC:0);
            $scope.isdisabled = respuesta.DISABLED;
            $scope.total = respuesta.CANTIDAD_PARTICIPANTES;
            $scope.cargarDetalles();
            $scope.cargaParticipantes();
            $scope.$apply();

        }
        else
        {

                $scope.token_valido = false;


        }
        $scope.$apply();
    });


}
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS ESTADOSS		 *****
// ===================================================================
    $scope.cargarEstados = function(){
        $.post(global_apiserver + "/getAllEstados/", function (respuesta) {
            respuesta = JSON.parse(respuesta);
                $scope.estados= respuesta;
                $scope.$apply();

            });
    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR DETALLES GUARDADOS		 *****
// ===================================================================
    $scope.cargarDetalles = function(){

        if($scope.isedit)
        {
            $scope.formData.necesidades = $scope.detalles.NECESIDADES;

            if($scope.detalles.ISFACTURACION)
            {
                $scope.formData.facturacion = $scope.detalles.ISFACTURACION;
                $scope.edit = true;
            }

            if($scope.formData.facturacion=="SI")
            {
                $scope.editf = true;

                $scope.formData.otro_domicilio = $scope.detalles.DOMICILIO;

                $scope.formData.rfc_facturario = $scope.detalles.RFC_FACTURARIO;

                var contacto = $scope.detalles.CONTACTO.split(",");
                $scope.formData.otro_contacto_nombre = contacto[0];
                $scope.formData.otro_contacto_telefono = contacto[1];
                $scope.formData.otro_contacto_email = contacto[2];

            }
            else
            {
                $scope.editf = false;
            }

            $scope.formData.medio = $scope.detalles.MEDIO;
            $scope.formData.sede_curso = $scope.detalles.SEDE;
            $scope.formData.hora_inicio = $scope.detalles.HORA_INICIO;
            $scope.formData.hora_fin = $scope.detalles.HORA_FIN;
            $scope.formData.recomendacion_hospedaje = $scope.detalles.HOSPEDAJE;
            $scope.formData.recomendacion_transporte = $scope.detalles.TRASPORTE;
            if($scope.detalles.TRASLADO)
                $scope.formData.disponibilidad_traslado = $scope.detalles.TRASLADO;
            if($scope.detalles.ISMEDIDAS)
                $scope.formData.select_medidas_proteccion = $scope.detalles.ISMEDIDAS;
            var fecha = $scope.detalles.FECHA_CURSO;

            $scope.formData.fecha_curso = (fecha?fecha.substring(6,8)+"/"+fecha.substring(4,6)+"/"+fecha.substring(0,4):'');

            if($scope.formData.select_medidas_proteccion=="SI")
            {
                $scope.formData.medidas_proteccion = $scope.detalles.MEDIDAS;
            }

            $scope.formData.estado_visita = $scope.detalles.ESTADO;
        }



    }
// ================================================================================
// *****      Accion select si facturacion                              *****
// ================================================================================/
    $scope.onFacturacion = function()
    {
       $scope.datosContactos = ($scope.datosDomicilios.length>0?$scope.datosDomicilios[0].CONTACTOS:{});

       $scope.cantidad_contacto = ($scope.datosDomicilios.length>0?$scope.datosDomicilios[0].CC:0);

      /* $scope.domicilio_contacto = $scope.datosContactos[0];
       alert(JSON.stringify($scope.datosContactos[0]));*/

    }

// ================================================================================
// *****      Accion select si domicilio                              *****
// ================================================================================/
    $scope.onDomicilio = function()
    {
        $scope.formData.otro_domicilio = $scope.formData.domicilio_fiscal.NOMBRE;
        $scope.datosContactos = $scope.formData.domicilio_fiscal.CONTACTOS;
        $scope.cantidad_contacto = $scope.formData.domicilio_fiscal.CC;
    }
// ================================================================================
// *****      Accion domicilio entrada libre                                  *****
// ================================================================================/
    $scope.otroDomicilio = function(flag){
      $scope.od=flag;
      $scope.formData.otro_domicilio = "";
      if(!flag){
          if($scope.edit == true)
          {
              $scope.formData.otro_domicilio = $scope.detalles.DOMICILIO;
          }else{
              $scope.formData.otro_domicilio =  $scope.formData.domicilio_fiscal.NOMBRE;
              $scope.error_otro_domicilio = "";
          }

      }

    }
// ================================================================================
// *****      Accion contacto entrada libre                                  *****
// ================================================================================/
    $scope.otroContacto= function(flag){
        $scope.oc=flag;
        $scope.formData.otro_contacto_nombre = "";
        $scope.formData.otro_contacto_telefono = "";
        $scope.formData.otro_contacto_email= "";

        if(!flag)
        {
            $scope.error_otro_contacto_nombre = "";
            $scope.error_otro_contacto_telefono = "";
            $scope.error_otro_contacto_email = "";

            if($scope.edit == true)
            {
                var contacto = $scope.detalles.CONTACTO.split(",");
                $scope.formData.otro_contacto_nombre = contacto[0];
                $scope.formData.otro_contacto_telefono = contacto[1];
                $scope.formData.otro_contacto_email = contacto[2];
            }else {
                $scope.formData.otro_contacto_nombre = $scope.formData.domicilio_contacto.NOMBRE_CONTACTO;
                $scope.formData.otro_contacto_telefono = ($scope.formData.domicilio_contacto.TELEFONO_FIJO ? $scope.formData.domicilio_contacto.TELEFONO_FIJO : $scope.formData.domicilio_contacto.TELEFONO_MOVIL);
                $scope.formData.otro_contacto_email = $scope.formData.domicilio_contacto.EMAIL;
            }
        }
    }
// ================================================================================
// *****      Accion rfc entrada libre                                  *****
// ================================================================================/
    $scope.otroRFC = function(flag){
        $scope.orfc=flag;
        $scope.formData.rfc_facturario = $scope.datosCliente.RFC_FACTURARIO? $scope.datosCliente.RFC_FACTURARIO:$scope.datosCliente.RFC;
        //$("#rfc_facturario").attr("readonly", true);
        if(flag == true)
        {
            $scope.formData.rfc_facturario = "";
           // $("#rfc_facturario").removeAttr("readonly");
        }

    }
// ================================================================================
// *****      Accion sede entrada libre                                  *****
// ================================================================================/
    $scope.otraSede = function(flag){
        $scope.sede=flag;
        $scope.formData.sede_curso = $scope.datosCurso.SEDE;
        //$("#sede_curso").attr("readonly", true);
        if(flag == true)
        {
            $scope.formData.sede_curso = "";
            //$("#sede_curso").removeAttr("readonly");
            //$('#sede_curso').tooltip('toggle');

        }


    }

// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
    function validar_formulario() {
        $scope.respuesta = 1;
        var setfocus = null;
        if($scope.modalidad == 'programado')
        {
            if (typeof $scope.formData.estado_visita !== "undefined") {
                if ($scope.formData.estado_visita.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_estado_visita = "Complete este campo";
                    setfocus = "estado_visita";
                } else {
                    $scope.error_estado_visita = "";
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_estado_visita = "Complete este campo";
                setfocus = "estado_visita";
            }        }
////////////////////////////////////////////////////////////////////////////////////////////////////////
        if($scope.modalidad == 'insitu')
        {

            if (typeof $scope.formData.fecha_curso !== "undefined") {
                if ($scope.formData.fecha_curso.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_fecha_curso = "Complete este campo";
                    setfocus = "fecha_curso";
                } else {
                    if($scope.validar_fecha($scope.formData.fecha_curso))
                    {
                        $scope.error_fecha_curso = "";
                    }
                    else
                    {
                        $scope.respuesta = 0;
                        $scope.error_fecha_curso = "Fecha inválida";
                        setfocus = "fecha_curso";
                    }
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_fecha_curso = "Complete este campo";
                setfocus = "fecha_curso";
            }
 //////////////////////////////////////////////////////////////////////////////////////////////////////
            if($scope.formData.select_medidas_proteccion == "SI")
            {
                if (typeof $scope.formData.medidas_proteccion !== "undefined") {
                    if ($scope.formData.medidas_proteccion.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_medidas_proteccion = "Complete este campo";
                        setfocus = "medidas_proteccion";
                    } else {
                        $scope.error_medidas_proteccion = "";
                    }
                } else {
                    $scope.respuesta = 0;
                    $scope.error_medidas_proteccion = "Complete este campo";
                    setfocus = "medidas_proteccion";
                }
            }
 ////////////////////////////////////////////////////////////////////////////
            if (typeof $scope.formData.hora_inicio !== "undefined") {
                if ($scope.formData.hora_inicio.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_horario = "Complete este campo";
                    setfocus = "hora_inicio";
                } else {
                    $scope.error_horario = "";
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_horario = "Complete este campo";
                setfocus = "hora_inicio";
            }
//////////////////////////////////////////////////////////////////////////
            if (typeof $scope.formData.sede_curso !== "undefined") {
                if ($scope.formData.sede_curso.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_sede = "Complete este campo";
                    setfocus = "sede_curso";
                } else {
                    $scope.error_sede = "";
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_sede = "Complete este campo";
                setfocus = "sede_curso";
            }


        }
///////////////////////////////////////////////////////////////////////////////////////////////
        if($scope.formData.medio)
        {
            $scope.error_razon_viaRadio = "";
        }
        else
        {
            $scope.error_razon_viaRadio = "Seleccione una opción";
            setfocus = "viaRadio1";
        }
//////////////////////////////////////////////////////////////////////////////////////////////
        if($scope.formData.facturacion == "SI")
        {
            if($scope.oc == true || $scope.edit==true || $scope.cantidad_contacto == 0)
            {

              if(typeof $scope.formData.otro_contacto_email !== "undefined") {
                    if ($scope.formData.otro_contacto_email.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_otro_contacto_email = "Complete este campo";
                        setfocus = "otro_contacto_email";
                    } else {

                        if($scope.validar_email($scope.formData.otro_contacto_email))
                        {
                            $scope.error_otro_contacto_email = "";
                        }
                        else
                        {
                            $scope.respuesta = 0;
                            $scope.error_otro_contacto_email = "Telefono inválido";
                            setfocus = "otro_contacto_email";
                        }
                    }
                }else {
                    $scope.respuesta = 0;
                    $scope.error_otro_contacto_email = "Complete este campo";
                    setfocus = "otro_contacto_email";
                }
 ////////////////////////////////////////////////////////////////////////////////////////////////
                if(typeof $scope.formData.otro_contacto_telefono !== "undefined") {
                    if ($scope.formData.otro_contacto_telefono.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_otro_contacto_telefono = "Complete este campo";
                        setfocus = "otro_contacto_telefono";
                    } else {

                        if($scope.validar_telefono($scope.formData.otro_contacto_telefono))
                        {
                            $scope.error_otro_contacto_telefono = "";
                        }
                        else
                        {
                            $scope.respuesta = 0;
                            $scope.error_otro_contacto_telefono = "Telefono inválido";
                            setfocus = "otro_contacto_telefono";

                        }
                    }
                }else {
                    $scope.respuesta = 0;
                    $scope.error_otro_contacto_telefono = "Complete este campo";
                    setfocus = "otro_contacto_telefono";
                }
 //////////////////////////////////////////////////////////////////////////////////////
                if (typeof $scope.formData.otro_contacto_nombre !== "undefined") {
                    if ($scope.formData.otro_contacto_nombre.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_otro_contacto_nombre = "Complete este campo";
                        setfocus = "otro_contacto_nombre";
                    } else {
                        $scope.error_otro_contacto_nombre = "";
                    }
                } else {
                    $scope.respuesta = 0;
                    $scope.error_otro_contacto_nombre = "Complete este campo";
                    setfocus = "otro_contacto_nombre";
                }

            }
            else
            {
                if (typeof $scope.formData.domicilio_contacto !== "undefined") {
                    if ($scope.formData.domicilio_contacto.length == 0) {
                        $scope.respuesta = 0;
                        $scope.error_domicilio_contacto = "Complete este campo";
                        setfocus = "domicilio_contacto";
                    } else {
                        $scope.error_domicilio_contacto = "";
                    }
                } else {
                    $scope.respuesta = 0;
                    $scope.error_domicilio_contacto = "Complete este campo";
                    setfocus = "domicilio_contacto";
                }
            }
//////////////////////////////////////////////////////////////////////////////////////
            if(typeof $scope.formData.rfc_facturario !== "undefined") {
                if ($scope.formData.rfc_facturario.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_rfc_facturario = "Complete este campo";
                    setfocus = "rfc_facturario";
                } else {

                    if($scope.validar_rfc($scope.formData.rfc_facturario))
                    {
                        $scope.error_rfc_facturario = "";
                    }
                    else
                    {
                        $scope.respuesta = 0;
                        $scope.error_rfc_facturario = "RFC inválido";
                        setfocus = "rfc_facturario";
                    }
                }
            }else {
                $scope.respuesta = 0;
                $scope.error_rfc_facturario = "Complete este campo";
                setfocus = "rfc_facturario";
            }
////////////////////////////////////////////////////////////////////////////////////
            if (typeof $scope.formData.otro_domicilio !== "undefined") {
                if ($scope.formData.otro_domicilio.length == 0) {
                    $scope.respuesta = 0;
                    $scope.error_otro_domicilio = "Complete este campo";
                    setfocus = "otro_domicilio";
                } else {
                    $scope.error_otro_domicilio = "";
                }
            } else {
                $scope.respuesta = 0;
                $scope.error_otro_domicilio = "Complete este campo";
                setfocus = "otro_domicilio";
            }

        }
     /////////////////////////////////////////////////////////////////////////
        if (typeof $scope.formData.necesidades !== "undefined") {
            if ($scope.formData.necesidades.length == 0) {
                $scope.respuesta = 0;
                $scope.error_necesidades = "Complete este campo";
                setfocus = "necesidades";
            } else {
                $scope.error_necesidades = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_necesidades = "Complete este campo";
            setfocus = "necesidades";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }

    }
// =======================================================================================
// *****               Función para observar el campo del formulario         		 *****
// =======================================================================================
    $scope.$watch('formData.fecha_curso',function(nuevo, anterior) {
        if(!nuevo)return;
        if(nuevo.length > 10)
            $scope.formData.fecha_curso = anterior;
    })
    // =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.submitForm = function () {
        validar_formulario();
        if($scope.respuesta == 1){
            $scope.insertar();
        }

    }
// =======================================================================================================
// *****   Funcion guardar los datos del formulario general 			 *****
// =======================================================================================================
   $scope.insertar = function () {
       $scope.enviar = true;
       var fecha = "";
       if($scope.modalidad=='insitu')
       {
           fecha = $scope.formData.fecha_curso;
           fecha = fecha.substring(6,10)+fecha.substring(3,5)+fecha.substring(0,2);
       }

        var datos = {
           ID: $scope.ID,
           NECESIDADES:$scope.formData.necesidades,
           ISFACTURACION:$scope.formData.facturacion,
           DOMICILIO:($scope.formData.facturacion=="SI"?$scope.formData.otro_domicilio:""),
           RFC_FACTURARIO:($scope.formData.facturacion=="SI"?$scope.formData.rfc_facturario:""),
           //CONTACTO:($scope.formData.facturacion=="SI"?($scope.oc == true? ($scope.formData.otro_contacto_nombre+','+$scope.formData.otro_contacto_telefono+','+$scope.formData.otro_contacto_email):($scope.formData.domicilio_contacto.NOMBRE_CONTACTO+','+($scope.formData.domicilio_contacto.TELEFONO_FIJO?$scope.formData.domicilio_contacto.TELEFONO_FIJO:$scope.formData.domicilio_contacto.TELEFONO_MOVIL),$scope.formData.domicilio_contacto.EMAIL)):''),
           CONTACTO:($scope.formData.facturacion=="SI"?($scope.formData.otro_contacto_nombre+','+$scope.formData.otro_contacto_telefono+','+$scope.formData.otro_contacto_email):''),
           MEDIO:$scope.formData.medio,
           MODALIDAD:$scope.modalidad,
           SEDE:($scope.modalidad=='insitu'?$scope.formData.sede_curso:""),
           HORA_INICIO:($scope.modalidad=='insitu'?$scope.formData.hora_inicio:""),
           HORA_FIN:($scope.modalidad=='insitu'?$("#hora_fin").val():""),
           HOSPEDAJE:($scope.modalidad=='insitu'?(typeof $scope.formData.recomendacion_hospedaje !== "undefined"?$scope.formData.recomendacion_hospedaje:''):""),
           TRASPORTE:($scope.modalidad=='insitu'?(typeof $scope.formData.recomendacion_transporte !== "undefined"?$scope.formData.recomendacion_transporte:''):""),
           TRASLADO:($scope.modalidad=='insitu'?$scope.formData.disponibilidad_traslado:""),
           ISMEDIDAS:($scope.modalidad=='insitu'?$scope.formData.select_medidas_proteccion:""),
           MEDIDAS:($scope.modalidad=='insitu'?(typeof $scope.formData.medidas_proteccion !== "undefined"?$scope.formData.medidas_proteccion:''):""),
           FECHA_CURSO:fecha,
           ESTADO:($scope.modalidad=='programado'? $scope.formData.estado_visita:""),


       }

       $.post(global_apiserver + "/insert/", JSON.stringify(datos), function (respuesta) {
           respuesta = JSON.parse(respuesta);
           if (respuesta.resultado == "ok") {
                 $scope.mensaje_success = "Los datos del formulario fueron enviados con exito";
               $scope.verificaToken();
               $("html, body").animate({ scrollTop: 0 }, "slow");

           }
           else
           {
               for(var i = 0 ; i < respuesta.mensaje.length ; i++)
               {
                       var result = respuesta.mensaje[i].split("|");
                       if (result.length == 2)
                           eval("$scope." + result[0] + "=" + result[1]);
                       else {
                           $scope.mensaje = result[0];
                       }

               }

           }
           $scope.enviar = false;
           $scope.$apply();
       });


    }
// =======================================================================================================
// *****   Funcion para limpiar las variables del formulario INSERTAR PARTICIPANTES			 *****
// =======================================================================================================
    function clear_form_participante(){
        $scope.formDataParticipante.nombre_participante = '';
        $scope.formDataParticipante.email_participante = '';
        $scope.formDataParticipante.telefono_participante = '';
        $scope.formDataParticipante.curp_participante = "";
        $scope.formDataParticipante.perfil_participante = "";

        /* $("#btnInstructor").attr("value","Selecciona un Instructor");
         $("#btnInstructor").attr("class", "form-control btn ");*/

        $scope.error_nombre_participante = "";
        $scope.error_email_participante = "";
        $scope.error_telefono_participante = "";
        $scope.error_curp_participante = "";
        $scope.error_perfil_participante = "";

    }

// =======================================================================================
// *****               Función para eliminar espacios a una cadena          		 *****
// =======================================================================================
    function eliminaEspacios(cadena)
    {
        // Funcion equivalente a trim en PHP
        var x=0, y=cadena.length-1;
        while(cadena.charAt(x)==" ") x++;
        while(cadena.charAt(y)==" ") y--;
        return cadena.substr(x, y-x+1);
    }
// =======================================================================================
// *****               Función para validar RFC        		 *****
// =======================================================================================
$scope.validar_rfc = function(input)
{
    var valor = input;
    valor = eliminaEspacios(valor);
    reg=/^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
    if(valor.length == 13)
        reg=/^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
    if(!reg.test(valor))
    {
        input = "";
       // $("#rfcParticipante").focus();
        return false;
    }
    else
        return true;
}
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_telefono = function (telefono)
    {
        var caract = new RegExp(/(^[0-9]{1,10}$)/);

        if (caract.test(telefono) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_email = function (email)
    {
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

        if (caract.test(email) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================
// *****                       Función para validar CURP                    		 *****
// =======================================================================================
    $scope.curpValida = function(input) {
        if(typeof input !== "undefined") {
            var curp = input;
            var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0\d|1[0-2])(?:[0-2]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
                validado = curp.match(re);

            if (!validado)  //Coincide con el formato general?
                return false;

            //Validar que coincida el dígito verificador
            function digitoVerificador(curp17) {
                //Fuente https://consultas.curp.gob.mx/CurpSP/
                var diccionario = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                    lngSuma = 0.0,
                    lngDigito = 0.0;
                for (var i = 0; i < 17; i++)
                    lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
                lngDigito = 10 - lngSuma % 10;
                if (lngDigito == 10)
                    return 0;
                return lngDigito;
            }

            if (validado[2] != digitoVerificador(validado[1]))
                return false;

            return true; //Validado
        }else return false;
    }
 // ================================================================================
// *****                  Funcion validar fecha                  *****
// ================================================================================
     $scope.validar_fecha = function(fecha) {
    if (typeof fecha !== "undefined") {
        if (validar_formato_fecha(fecha)) {
            if(esDespuesHoy(fecha))
            {
                if (existe_fecha(fecha)) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else
            {return false;}
        } else {

            return false;;
        }
    }else{
        return false;
    }



    }
    function validar_formato_fecha(fecha) {
        var RegExPattern = /^\d{2}\/\d{2}\/\d{4}$/;
        if ((fecha.match(RegExPattern)) && (fecha!='')) {
            return true;
        } else {
            return false;
        }

    }
    function existe_fecha(fecha){
        var fechaf = fecha.split("/");
        var d = fechaf[0];
        var m = fechaf[1];
        var y = fechaf[2];
        return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();

    }


// ================================================================================
// *****                  Funcion comparar fecha                  *****
// ================================================================================
    function esDespuesHoy(fecha) {

        var hoy = new Date();
        var partes = fecha.split("/");
        var select = new Date(partes[2],parseInt(partes[1])-1,partes[0],hoy.getHours(),hoy.getMinutes(),hoy.getSeconds(),hoy.getMilliseconds());
        if(hoy<=select){return true;}else {return false;}
    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.submitFormParticipante = function (accion) {
        validar_formulario_participante();


        if($scope.respuesta == 1){

            if(accion=='insertar')
            {
                $scope.insertaParticipantes();

            }

            if(accion=='editar')
            {
                $scope.editaParticipantes();

            }


        }

    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.insertaParticipantes= function () {

        var estado = "";
        //alert($scope.modalidad);
        if($scope.modalidad=='programado')
        {
            if (typeof $scope.formData.estado_visita !== "undefined") {
                estado = $scope.formData.estado_visita;
            }
        }


        var add = {
            NOMBRE: $scope.formDataParticipante.nombre_participante,
            EMAIL: $scope.formDataParticipante.email_participante,
            TELEFONO: $scope.formDataParticipante.telefono_participante,
            CURP: $scope.formDataParticipante.curp_participante,
            PERFIL: $scope.formDataParticipante.perfil_participante,
            MODALIDAD: $scope.modalidad,
            ID: $scope.ID,
            ID_CLIENTE:$scope.datosCliente.ID,
            ID_CURSO:$scope.datosCurso.ID_CURSO,
            ESTADO:estado
        };
         //alert(JSON.stringify(add));
        if (!$scope.existeParticipante(add)) {
            $.post(global_apiserver + "/insertParticipante/", JSON.stringify(add), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    clear_form_participante();
                    $scope.cargaParticipantes();
                    $scope.show_p = false;

                }
                else {
                        var result = respuesta.mensaje.split("|");
                        if (result.length == 2)
                        {
                            eval("$scope." + result[0] + "='" + result[1] +"'");
                            if(result[0]=="error_limite")
                            {
                                $scope.isdisabled = true;

                               // $scope.error_limite =  result[1];


                            }
                            $scope.$apply();


                        }

                        else {
                            $scope.mensaje = result[0];
                        }



                }

            });
        }
    }
// =======================================================================================================
// *****    Accion al presionar button editar participantes		 *****
// =======================================================================================================
        $scope.editaParticipantes= function () {
            var add = {
                ID:$scope.id_participante,
                NOMBRE: $scope.formDataParticipante.nombre_participante,
                EMAIL: $scope.formDataParticipante.email_participante,
                TELEFONO: $scope.formDataParticipante.telefono_participante,
                CURP: $scope.formDataParticipante.curp_participante,
                PERFIL: $scope.formDataParticipante.perfil_participante

            }
                $.post(global_apiserver + "/updateParticipante/", JSON.stringify(add), function (respuesta) {
                    respuesta = JSON.parse(respuesta);
                    if (respuesta.resultado == "ok") {
                        clear_form_participante();
                        $scope.cargaParticipantes();
                        $scope.show_p = false;
                        $scope.id_participante = "";

                    }
                    else {
                        for (var i = 0; i < respuesta.mensaje.length; i++) {
                                var result = respuesta.mensaje[i].split("|");
                                if (result.length == 2)
                                    eval("$scope." + result[0] + "=" + result[1]);
                                else {
                                    $scope.mensaje = result[0];
                                }
                            }

                        }


                });

        /*if(!$scope.existeParticipante(add))
        {
            $scope.participantes[$scope.cantidad_insertados] = add;
            $scope.cantidad_insertados++;
            $scope.show_p = false;
            clear_form_participante();
        }*/



    }
// =======================================================================================================
// *****    Accion al presionar button eliminar participantes		 *****
// =======================================================================================================
    $scope.eliminaParticipante = function()
    {
        if(confirm("¿Estás seguro que desea eliminar este participante?")) {
            var campos = {
                ID_PARTICIPANTE: $scope.id_participante,
                ID_CLIENTE:$scope.datosCliente.ID,
                ID_CURSO:$scope.datosCurso.ID_CURSO,
                MODALIDAD:$scope.modalidad,
                ID:$scope.ID
            }
            $.post(global_apiserver + "/deleteParticipante/", JSON.stringify(campos), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    clear_form_participante();
                    $scope.cargaParticipantes();
                    $scope.show_p = false;
                    $scope.id_participante = "";
                    $scope.accion = 'insertar';
                    $scope.title_form_participante = "Agregar Participante";
                }
                else {
                    for (var i = 0; i < respuesta.mensaje.length; i++) {
                        var result = respuesta.mensaje[i].split("|");
                        if (result.length == 2)
                            eval("$scope." + result[0] + "=" + result[1]);
                        else {
                            $scope.mensaje = result[0];
                        }
                    }
                }

            });
        }

    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.cargaParticipantes = function()
    {
        var campos = {
            MODALIDAD: $scope.modalidad,
            ID:$scope.ID,
            ID_CLIENTE:$scope.datosCliente.ID,
            ID_CURSO:$scope.datosCurso.ID_CURSO
        }
        //alert(JSON.stringify(campos));
        $.post(global_apiserver + "/getAllParticipantes/", JSON.stringify(campos), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            $scope.participantes = respuesta;
            $scope.cantidad_insertados = respuesta.length;
            $scope.$apply();
        });

    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.existeParticipante = function(p)
    {
        var flag = false;
        for(var i = 0 ; i < $scope.participantes.length ; i++)
        {

            if($scope.participantes[i].NOMBRE == p.NOMBRE && $scope.participantes[i].EMAIL == p.EMAIL && $scope.participantes[i].TELEFONO == p.TELEFONO && $scope.participantes[i].CURP == p.CURP)
           {
               $scope.mensaje = "Ya existe un participante con esos datos";
               flag = true;

               //$('#nombre_participante').focus();
               break;
           }

        }
        return flag;

    }

// =======================================================================================================
// *****    Accion al presionar button cancelar participantes		 *****
// =======================================================================================================
    $scope.onChangeCURP= function () {
        $scope.error_curp_participante=($scope.curpValida($scope.formDataParticipante.curp_participante)?'':'CURP Inválido');
    }
// =======================================================================================================
// *****    Accion al presionar button cancelar participantes		 *****
// =======================================================================================================
    $scope.cancelEditParticipantes= function () {
            $scope.show_p = false;
            $scope.accion  = "";
            clear_form_participante();
    }

// =======================================================================================================
// *****    Accion al presionar button editar participantes		 *****
// =======================================================================================================
    $scope.showEditParticipantes= function (pos) {
        clear_form_participante();
        if (typeof pos !== "undefined")
        {
           $scope.show_p = true;
           $scope.accion  = "editar";
            $scope.title_form_participante = "Editar Participante";
           $scope.id_participante  = $scope.participantes[pos].ID;
           $scope.formDataParticipante.nombre_participante = $scope.participantes[pos].NOMBRE;
           $scope.formDataParticipante.email_participante = $scope.participantes[pos].EMAIL;
           $scope.formDataParticipante.telefono_participante = $scope.participantes[pos].TELEFONO;
           $scope.formDataParticipante.curp_participante = $scope.participantes[pos].CURP;
           $scope.formDataParticipante.perfil_participante = $scope.participantes[pos].PERFIL;

            setTimeout(function(){
                $('#nombre_participante').focus();
            },1000); //delay is in milliseconds




        }

    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.showFormP = function()
    {
        $scope.show_p = true;
        $scope.accion  = "insertar";
        $scope.title_form_participante = "Agregar Participante";
        setTimeout(function(){
            $('#nombre_participante').focus();
        },1000); //delay is in milliseconds
    }
// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
    function validar_formulario_participante() {
        $scope.respuesta = 1;
        var setfocus = null;

        if (typeof $scope.formDataParticipante.perfil_participante !== "undefined") {
            if ($scope.formDataParticipante.perfil_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_perfil_participante = "Complete este campo";
                setfocus = "perfil_participante";
            } else {
                $scope.error_perfil_participante = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_perfil_participante = "Complete este campo";
            setfocus = "perfil_participante";
        }
////////////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.curp_participante !== "undefined") {
            if ($scope.formDataParticipante.curp_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_curp_participante = "Complete este campo";
                setfocus = "curp_participante";
            } else {

                if($scope.curpValida($scope.formDataParticipante.curp_participante))
                {
                    $scope.error_curp_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_curp_participante = "CURP inválido";
                    setfocus = "curp_participante";
                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.error_curp_participante = "Complete este campo";
            setfocus = "curp_participante";
        }
 /////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.telefono_participante !== "undefined") {
            if ($scope.formDataParticipante.telefono_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_telefono_participante = "Complete este campo";
                setfocus = "telefono_participante";
            } else {

                if($scope.validar_telefono($scope.formDataParticipante.telefono_participante))
                {
                    $scope.error_telefono_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_telefono_participante = "Telefono inválido";
                    setfocus = "telefono_participante";

                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.error_telefono_participante = "Complete este campo";
            setfocus = "telefono_participante";
        }
 /////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataParticipante.email_participante !== "undefined") {
            if ($scope.formDataParticipante.email_participante.length == 0) {
                $scope.respuesta = 0;
                $scope.error_email_participante = "Complete este campo";
                setfocus = "email_participante";
            } else {
                if($scope.validar_email($scope.formDataParticipante.email_participante))
                {
                    $scope.error_email_participante = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.error_email_participante = "Correo electrónico inválido";
                    setfocus = "email_participante";
                }

            }
        }else {
            $scope.respuesta = 0;
            $scope.error_email_participante = "Complete este campo";
            setfocus = "email_participante";
        }
////////////////////////////////////////////////////////////////////////////////////////////
        if (typeof $scope.formDataParticipante.nombre_participante !== "undefined") {
            if ($scope.formDataParticipante.nombre_participante.length == 0) {
                $scope.respuesta = 0;
                //$("#error_nombre_participante").text("Complete este campo");
                $scope.error_nombre_participante = "Complete este campo";
                setfocus = "nombre_participante";
            } else {
                $scope.error_nombre_participante = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_nombre_participante = "Complete este campo";
            setfocus = "nombre_participante";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }
    }


function loadDatePicker() {

    $('#fecha_curso').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY', minDate : new Date(), lang : 'es',time: false })
        .on('change', function(e, date)
    {
        $scope.formData.fecha_curso = date.format("DD/MM/YYYY");
    });

    /*var nowTemp = new Date();

    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate()-1, 0, 0, 0, 0);


    var checkin = $('#fecha_curso').fdatepicker({
        onRender: function (date) {
            return date.valueOf() <= now.valueOf() ? 'disabled' : '';
        },
        format: 'dd/mm/yyyy',
        language: 'es',
        disableDblClickSelection: true,
        //initialDate: new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0),
        closeButton: true,
        closeIcon: 'X',
        leftArrow: '<',
        rightArrow: '>'
    }).on('changeDate', function (ev) {
        alert(ev.date.valueOf());
        $scope.formData.fecha_curso = ev.date.valueOf();
    }).data('datepicker');*/


}
// ================================================================================
// *****                                                                      *****
// ================================================================================/
$scope.mostrarMensaje  = function (title,mensaje) {

    if(typeof title == "undefined") {
        title = "Información";
     }
        $scope.toast_title = title;
        $scope.toast_body = mensaje;
    $('#mensaje').toast('show');
}
// ================================================================================
// *****                                                                      *****
// ================================================================================/
  $(document).ready(function () {
      $scope.verificaToken();
      $scope.cargarEstados();

      setTimeout(function () {
          loadDatePicker();
      }, 1000);


      //$('[data-toggle="tooltip"]').tooltip();
      $('body').tooltip({ selector: '[data-toggle="tooltip"]'});
     // $('body').fdatepicker({ });


  });

});
// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================


