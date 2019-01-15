<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 15/01/2019
 * Time: 12:13
 */

include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';
include '../../jwt_imnc/common/conn-sendgrid.php';



function valida_parametro_and_die($parametro, $mensaje_error,$campo){
    $parametro = "" . $parametro;
    if ($parametro == "" or is_null($parametro)) {
        $respuesta["resultado"] = "error";
        $respuesta["mensaje"] = ($campo!=null?$campo."|":"").$mensaje_error;
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

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Complete este campo","error_nombre_participante");

$EMAIL= $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Complete este campo","error_email_participante");

$CURP= $objeto->CURP;
valida_parametro_and_die($CURP, "Complete este campo","error_curp_participante");

$PERFIL= $objeto->PERFIL;
valida_parametro_and_die($PERFIL, "Complete este campo","error_perfil_participante");

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesario el ID_PROGRAMACION",null);

$id = $database->update("PARTICIPANTES", [
    "NOMBRE" => $NOMBRE,
    "EMAIL"=>	$EMAIL,
    "CURP" => $CURP,
    "PERFIL" => $PERFIL
],["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
