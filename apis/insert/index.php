<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 13/01/2019
 * Time: 21:29
 */
include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';
include '../../jwt_imnc/common/conn-sendgrid.php';

function imprime_error_and_die($mensaje,$campo){
    $respuesta['resultado'] = 'error';
    $respuesta['mensaje'][] = ($campo!=null?$campo."|":"").$mensaje;
    print_r(json_encode($respuesta));
    die();
}

function valida_parametro_and_die($parametro, $mensaje_error,$campo){
    $parametro = "" . $parametro;
    if ($parametro == "" or is_null($parametro)) {
        $respuesta["resultado"] = "error";
        $respuesta["mensaje"][] = ($campo!=null?$campo."|":"").$mensaje_error;
        print_r(json_encode($respuesta));
        die();
    }
}

function valida_error_medoo_and_die(){
    global $database, $mailerror;
    if ($database->error()[2]) {
        $respuesta["resultado"]="error";
        $respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2];
        print_r(json_encode($respuesta));
        die();
    }
}

$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);


$ID = $objeto->ID;
valida_parametro_and_die($ID, "Es necesario introducir un ID",null);

$NECESIDADES = $objeto->NECESIDADES;
valida_parametro_and_die($NECESIDADES, "Es necesario introducir las Necesidades y expectativas del servicio contratado","error_necesidades");

$ISFACTURACION = $objeto->ISFACTURACION;
valida_parametro_and_die($ISFACTURACION, "Es necesario introducir si Requiere factura",null);

$DOMICILIO = $objeto->DOMICILIO;
$RFC_FACTURARIO = $objeto->RFC_FACTURARIO;
$CONTACTO = $objeto->CONTACTO;


if($ISFACTURACION == "SI")
{
    valida_parametro_and_die($DOMICILIO, "Es necesario introducir el Domicilio Fiscal","error_otro_domicilio");
    valida_parametro_and_die($RFC_FACTURARIO, "Es necesario introducir el RFC Facturario","error_rfc_facturario");
    valida_parametro_and_die($CONTACTO, "Es necesario introducir el Contacto","error_domicilio_contacto");
}

$MEDIO = $objeto->MEDIO;
valida_parametro_and_die($MEDIO, "Es necesario introducir ¿Por qué medio se enteró nosotros?","error_razon_viaRadio");

$MODALIDAD = $objeto->MODALIDAD;
valida_parametro_and_die($MODALIDAD, "Es necesario la Modalidad del Curso",null);

$SEDE = $objeto->SEDE;

$HORA_INICIO = $objeto->HORA_INICIO;

$HORA_FIN = $objeto->HORA_FIN;

$HOSPEDAJE = $objeto->HOSPEDAJE;
$TRASPORTE = $objeto->TRASPORTE;

$TRASLADO = $objeto->TRASLADO;

$ISMEDIDAS = $objeto->ISMEDIDAS;

$MEDIDAS = $objeto->MEDIDAS;

$FECHA_CURSO = $objeto->FECHA_CURSO;

if($MODALIDAD == "insitu"){
    valida_parametro_and_die($SEDE, "Es necesario introducir la Sede del curso","error_sede");
    valida_parametro_and_die($HORA_INICIO, "Es necesario introducir la Hora de Inicio del Curso","error_horario");
    valida_parametro_and_die($HORA_FIN, "Es necesario introducir la Hora de Fin del Curso","error_horario");
    valida_parametro_and_die($TRASLADO, "Es necesario introducir la Disponibilidad para trasladar al instructor (hotel-curso-hotel)",null);
    valida_parametro_and_die($ISMEDIDAS, "Es necesario introducir lleva medidas de protección el curso",null);
    valida_parametro_and_die($FECHA_CURSO, "Es necesario introducir la fecha del curso","error_fecha_curso");

    if($MEDIDAS == "SI")
    {
        valida_parametro_and_die($MEDIDAS, "Es necesario introducir las Medidas de protección requeridas por el instructor para brindar el servicio","error_medidas_proteccion");

    }

    if (strlen($FECHA_CURSO) != 8) {
        imprime_error_and_die("Verifica el formato de la fecha del curso","error_fecha_curso");
    }

    $anhio = intval(substr($FECHA_CURSO,0,4));
    $mes = intval(substr($FECHA_CURSO,4,2));
    $dia = intval(substr($FECHA_CURSO,6,2));
    if (!checkdate($mes , $dia, $anhio)){
        imprime_error_and_die("La fecha del curso no es válida","error_fecha_curso");
    }
}

$ESTADO = $objeto->ESTADO;

if($MODALIDAD == "programado"){
    valida_parametro_and_die($ESTADO, "Es necesario introducir el Estado del que nos visita",null);
}


print_r(json_encode($PARTICIPANTES));

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));