<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 15/01/2019
 * Time: 12:13
 */

include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';



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

$TELEFONO= $objeto->TELEFONO;
valida_parametro_and_die($TELEFONO, "Complete este campo","error_telefono_participante");

$CURP= $objeto->CURP;
valida_parametro_and_die($CURP, "Complete este campo","error_curp_participante");

$PERFIL= $objeto->PERFIL;
valida_parametro_and_die($PERFIL, "Complete este campo","error_perfil_participante");

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesario el ID_PROGRAMACION",null);

$ID_CLIENTE= $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Es necesario el ID_CLIENTE",null);

$ID_CURSO= $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario el ID_CURSO",null);

$MODALIDAD= $objeto->MODALIDAD;
valida_parametro_and_die($MODALIDAD, "Es necesario el ID_PROGRAMACION",null);

$ESTADO= $objeto->ESTADO;

if($MODALIDAD == 'programado')
{
    $count = $database->count("CURSOS_PROGRAMADOS_PARTICIPANTES",["[><]PARTICIPANTES"=>["ID_PARTICIPANTE"=>"ID"]],["ID"],["AND"=>["CURP"=>$CURP,"ID_CURSO_PROGRAMADO"=>$ID,"ID_CLIENTE"=>$ID_CLIENTE]]);
    if($count>0)
    valida_parametro_and_die(null, "Ya existe un participante con ese CURP","error_curp_participante");
    $cantidad  = $database->get("CLIENTE_CURSOS_PROGRAMADOS",["CANTIDAD_PARTICIPANTES"],["AND"=>["ID_CURSO_PROGRAMADO"=>$ID,"ID_CLIENTE"=>$ID_CLIENTE]]);

    $total = $database->count("CURSOS_PROGRAMADOS_PARTICIPANTES",["ID_PARTICIPANTE"],["AND"=>["ID_CURSO_PROGRAMADO"=>$ID,"ID_CLIENTE"=>$ID_CLIENTE]]);

    if($total>=$cantidad["CANTIDAD_PARTICIPANTES"])
    valida_parametro_and_die(null, "No se puede agregar mas participantes a este curso","limite");
}
if($MODALIDAD == 'insitu')
{
    $count = $database->count("SCE_PARTICIPANTES",["[><]PARTICIPANTES"=>["ID_PARTICIPANTE"=>"ID"]],["ID"],["AND"=>["CURP"=>$CURP,"ID_SCE"=>$ID,"ID_CURSO"=>$ID_CURSO]]);
    if($count>0)
        valida_parametro_and_die(null, "Ya existe un participante con ese CURP","error_curp_participante");
    $cantidad  = $database->get("SERVICIO_CLIENTE_ETAPA",["CANTIDAD_PARTICIPANTES"],["ID"=>$ID]);
    $total = $database->count("SCE_PARTICIPANTES",["ID_PARTICIPANTE"],["AND"=>["ID_SCE"=>$ID,"ID_CURSO"=>$ID_CURSO]]);
    if($total>=$cantidad["CANTIDAD_PARTICIPANTES"])
        valida_parametro_and_die(null, "No se puede agregar mas participantes a este curso","error_limite");
}

$id = $database->insert("PARTICIPANTES", [
    "NOMBRE" => $NOMBRE,
    "EMAIL"=>	$EMAIL,
    "CURP" => $CURP,
    "TELEFONO" =>$TELEFONO,
    "PERFIL" => $PERFIL,
    "ID_ESTADO" => $ESTADO
]);
valida_error_medoo_and_die();

if($id	!=	0) {
    if($MODALIDAD == 'programado'){

        $id_p = $database->insert("CURSOS_PROGRAMADOS_PARTICIPANTES", [
            "ID_CURSO_PROGRAMADO" => $ID,
            "ID_PARTICIPANTE"=>	$id,
            "ID_CLIENTE"=>$ID_CLIENTE
        ]);
        valida_error_medoo_and_die();
    }

    if($MODALIDAD == 'insitu'){

        $id_p = $database->insert("SCE_PARTICIPANTES", [
            "ID_SCE" => $ID,
            "ID_PARTICIPANTE"=>	$id,
            "ID_CURSO" => $ID_CURSO
        ]);
        valida_error_medoo_and_die();
    }

}

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
