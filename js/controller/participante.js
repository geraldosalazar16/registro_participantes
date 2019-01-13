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
  $scope.ID = "";
  $scope.formData.facturacion = "NO";
  $scope.formData.select_medidas_proteccion = "NO";
  $scope.formData.disponibilidad_traslado = "NO";
  $scope.cantidad_domicilios = 0;
  $scope.cantidad_contacto = 0;
  $scope.cantidad_insertados = 0;
  $scope.total = 5;

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
            $scope.datosContactos = respuesta.DOMICILIOS[0].CONTACTOS;
            $scope.ID = respuesta.ID;
            $scope.cantidad_domicilios = respuesta.CD;
            $scope.cantidad_contacto = respuesta.DOMICILIOS[0].CC;

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
// ================================================================================
// *****      Accion select si facturacion                              *****
// ================================================================================/
    $scope.onFacturacion = function()
    {
       $scope.datosContactos = $scope.datosDomicilios[0].CONTACTOS;
       $scope.cantidad_contacto = $scope.datosDomicilios[0].CC;
      /* $scope.domicilio_contacto = $scope.datosContactos[0];
       alert(JSON.stringify($scope.datosContactos[0]));*/

    }

// ================================================================================
// *****      Accion select si domicilio                              *****
// ================================================================================/
    $scope.onDomicilio = function()
    {
        $scope.otro_domicilio = $scope.formData.domicilio_fiscal.NOMBRE;
        $scope.datosContactos = $scope.formData.domicilio_fiscal.CONTACTOS;
        $scope.cantidad_contacto = $scope.formData.domicilio_fiscal.CC;
    }
// ================================================================================
// *****      Accion domicilio entrada libre                                  *****
// ================================================================================/
    $scope.otroDomicilio = function(flag){
      $scope.od=flag;
      $scope.formData.otro_domicilio = "";
    }
// ================================================================================
// *****      Accion contacto entrada libre                                  *****
// ================================================================================/
    $scope.otroContacto= function(flag){
        $scope.oc=flag;
        $scope.formData.otro_contacto_nombre = "";
        $scope.formData.otro_contacto_telefono = "";
        $scope.formData.otro_contacto_email= "";
    }
// ================================================================================
// *****      Accion rfc entrada libre                                  *****
// ================================================================================/
    $scope.otroRFC = function(flag){
        $scope.orfc=flag;
        $scope.formData.rfc_facturario = $scope.datosCliente.RFC_FACTURARIO? $scope.datosCliente.RFC_FACTURARIO:$scope.datosCliente.RFC;
        $("#rfc_facturario").attr("readonly", true);
        if(flag == true)
        {
            $scope.formData.rfc_facturario = "";
            $("#rfc_facturario").removeAttr("readonly");
        }

    }
// ================================================================================
// *****      Accion sede entrada libre                                  *****
// ================================================================================/
    $scope.otraSede = function(flag){
        $scope.sede=flag;
        $scope.formData.sede_curso = $scope.datosCurso.SEDE;
        $("#sede_curso").attr("readonly", true);
        if(flag == true)
        {
            $scope.formData.sede_curso = "";
            $("#sede_curso").removeAttr("readonly");
            $('#sede_curso').tooltip('toggle')

        }


    }

// =======================================================================================
// *****               Función para validar RFC        		 *****
// =======================================================================================
$scope.validar_rfc = function()
{
    var valor = $scope.formDataParticipante.rfcParticipante;
    valor = eliminaEspacios(valor);
    reg=/^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
    if(valor.length == 13)
        reg=/^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
    if(!reg.test(valor))
    {
        $scope.formDataParticipante.rfcParticipante = "";
        $("#rfcParticipante").focus();
        return false;
    }
    else
        return true;
}
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    function validar_telefono(telefono)
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
    function validar_email(email)
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
    $scope.curpValida = function() {
        var curp = $scope.formDataParticipante.curp_participante;
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0\d|1[0-2])(?:[0-2]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
            validado = curp.match(re);

        if (!validado)  //Coincide con el formato general?
            return false;

        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma= lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if(lngDigito == 10)
                return 0;
            return lngDigito;
        }
        if (validado[2] != digitoVerificador(validado[1]))
            return false;

        return true; //Validado
    }
// =======================================================================================
// *****               Función para validar RFC        		 *****
// =======================================================================================
    $scope.validar_rfc = function()
    {
        var valor = $scope.formDataParticipante.rfcParticipante;
        valor = eliminaEspacios(valor);
        reg=/^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
        if(valor.length == 13)
            reg=/^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
        if(!reg.test(valor))
        {
            $scope.formDataParticipante.rfcParticipante = "";
            $("#rfcParticipante").focus();
            return false;
        }
        else
            return true;
    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.submitFormParticipante = function () {
        validar_formulario();
        if($scope.respuesta == 1){
            $scope.insertaParticipantes();
        }

    }
// =======================================================================================================
// *****    Accion al presionar button agregar participantes		 *****
// =======================================================================================================
    $scope.insertaParticipantes= function () {
        var add = {
            NOMBRE: $scope.formDataParticipante.nombre_participante,
            EMAIL: $scope.formDataParticipante.email_participante,
            CURP: $scope.formDataParticipante.curp_participante,
            PERFIL: $scope.formDataParticipante.perfil_participante
        }
        $scope.participantes[$scope.cantidad_insertados] = add;
        $scope.cantidad_insertados++;
    }
// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
    function validar_formulario() {
        $scope.respuesta = 1;

        if (typeof $scope.formDataParticipante.nombre_participante !== "undefined") {
            if ($scope.formDataParticipante.nombre_participante.length == 0) {
                $scope.respuesta = 0;
                $("#error_nombre_participante").text("Complete este campo");
            } else {
                $("#error_nombre_participante").text("");
            }
        } else {
            $scope.respuesta = 0;
            $("#error_nombre_participante").text("Complete este campo");
        }

        if(typeof $scope.formDataParticipante.email_participante !== "undefined") {
            if ($scope.formDataParticipante.email_participante.length == 0) {
                $scope.respuesta = 0;
                $("#error_email_participante").text("Complete este campo");
            } else {
                if(validar_email($scope.formDataParticipante.email_participante))
                {
                    $("#error_email_participante").text("");
                }
                else
                {
                    $scope.respuesta = 0;
                    $("#error_email_participante").text("Correo electrónico inválido");
                }

            }
        }else {
            $scope.respuesta = 0;
            $("#error_email_participante").text("Complete este campo");
        }

        if(typeof $scope.formDataParticipante.curp_participante !== "undefined") {
            if ($scope.formDataParticipante.curp_participante.length == 0) {
                $scope.respuesta = 0;
                $("#error_curp_participante").text("Complete este campo");
            } else {

                if($scope.curpValida())
                {
                    $("#error_curp_participante").text("");
                }
                else
                {
                    $scope.respuesta = 0;
                    $("#error_curp_participante").text("CURP inválido");
                }
            }
        }else {
            $scope.respuesta = 0;
            $("#error_curp_participante").text("Complete este campo");
        }

        if (typeof $scope.formDataParticipante.perfil_participante !== "undefined") {
            if ($scope.formDataParticipante.perfil_participante.length == 0) {
                $scope.respuesta = 0;
                $("#error_perfil_participante").text("Complete este campo");
            } else {
                $("#error_perfil_participante").text("");
            }
        } else {
            $scope.respuesta = 0;
            $("#error_perfil_participante").text("Complete este campo");
        }
    }

function mytoggle(id)
{
    $("#"+id).toggle(function(){

    },function(){

    });
}
function loadTimePicker() {


    var hora_inicio  = $('#hora_inicio').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '10',
        maxTime: '6:00pm',
        defaultTime: '11',
        startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });


}

// ================================================================================
// *****                                                                      *****
// ================================================================================/
  $(document).ready(function () {
      $scope.verificaToken();
      $scope.cargarEstados();
      loadTimePicker();

      //$('[data-toggle="tooltip"]').tooltip();
      $('body').tooltip({ selector: '[data-toggle="tooltip"]' });

  });

});
// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================


