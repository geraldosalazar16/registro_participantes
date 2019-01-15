<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 15/01/2019
 * Time: 13:40
 */

include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';
include '../../jwt_imnc/common/conn-sendgrid.php';

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

$MODALIDAD = $objeto->MODALIDAD ;
valida_parametro_and_die($MODALIDAD , "Es necesario introducir un ID",null);
$participantes = [];
if($MODALIDAD == "programado"){

    $participantes = $database->select("CURSOS_PROGRAMADOS_PARTICIPANTES",["[><]PARTICIPANTES"=>["ID_PARTICIPANTE"=>"ID"]],["ID","NOMBRE","EMAIL","CURP","PERFIL"],["ID_CURSO_PROGRAMADO"=>$ID]);
}
if($MODALIDAD == "insitu"){
    $participantes = $database->select("SCE_PARTICIPANTES",["[><]PARTICIPANTES"=>["ID_PARTICIPANTE"=>"ID"]],["ID","NOMBRE","EMAIL","CURP","PERFIL"],["ID_SCE"=>$ID]);

}


print_r(json_encode($participantes));