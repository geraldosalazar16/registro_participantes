<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 08/01/2019
 * Time: 12:54
 */

include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';
require_once '../../jwt_imnc/vendor/autoload.php';
use \Firebase\JWT\JWT;

function valida_parametro_and_die($parametro, $mensaje_error){
    $parametro = "" . $parametro;
    if ($parametro == "") {
        $respuesta["resultado"] = "error";
        $respuesta["mensaje"] = $mensaje_error;
        print_r(json_encode($respuesta));
        die();
    }
}

function valida_error_medoo_and_die(){
    global $database, $mailerror;
    if ($database->error()[2]) {
        $respuesta["resultado"]="error";
        $respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2];
        print_r(json_encode($respuesta));
        die();
    }
}

$respuesta = array();
$key = "imnc2018$$1";
$encrypt = array('HS256');
$duration = 60*60*24*30;

//payload
$data = [
    'ID_CLIENTE' => 436,
    'MODALIDAD' => "insitu",
    'ID_CURSO' => 1,
    'ID_PROGRAMACION' => 18
];
/*
iss = issuer, servidor que genera el token
data = payload del JWT */
$token = array(
    'iss' => $global_apiserver,
    'aud' => $global_apiserver,
    'exp' => time() + $duration,
    'data' => $data
);
//Codifica la información usando el $key definido en jwt.php
$jwt = JWT::encode($token, $key);
$respuesta["token"] = $jwt;

//valida_error_medoo_and_die();


print_r(json_encode($respuesta));