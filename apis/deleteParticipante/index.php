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

$ID_PARTICIPANTE= $objeto->ID_PARTICIPANTE;
valida_parametro_and_die($ID_PARTICIPANTE, "Es necesario el ID_PARTICIPANTE",null);

$ID_CLIENTE= $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Es necesario el ID_CLIENTE",null);

$ID_CURSO= $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario el ID_CURSO",null);

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesario el ID_PROGRAMACION",null);

$MODALIDAD= $objeto->MODALIDAD;
valida_parametro_and_die($MODALIDAD, "Es necesario la MODALIDAD",null);

$id = $database->delete("PARTICIPANTES", ["ID"=>$ID_PARTICIPANTE]);
valida_error_medoo_and_die();
if($id != 0)
{
    if($MODALIDAD=="programado")
    $idcp = $database->delete("CURSOS_PROGRAMADOS_PARTICIPANTES", ["AND"=>["ID_PARTICIPANTE"=>$ID_PARTICIPANTE,"ID_CURSO_PROGRAMADO"=>$ID,"ID_CLIENTE"=>$ID_CLIENTE]]);
    if($MODALIDAD=="insitu")
    $idsce = $database->delete("SCE_PARTICIPANTES", ["AND"=>["ID_PARTICIPANTE"=>$ID_PARTICIPANTE,"ID_SCE"=>$ID,"ID_CURSO"=>$ID_CURSO]]);
}

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
