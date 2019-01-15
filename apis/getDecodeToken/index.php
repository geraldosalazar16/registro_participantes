<?php
include '../../jwt_imnc/common/conn-apiserver.php';
include '../../jwt_imnc/common/conn-medoo.php';
include '../../jwt_imnc/common/conn-sendgrid.php';
require_once '../../jwt_imnc/vendor/autoload.php';
use \Firebase\JWT\JWT;

function valida_parametro_and_die($parametro, $mensaje_error){
    $parametro = "" . $parametro;
    if ($parametro == "" or is_null($parametro)) {
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
        $respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2];
        print_r(json_encode($respuesta));
        die();
    }
}

$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$key = "imnc2018$$1";
$encrypt = array('HS256');




$jwt = $objeto->TOKEN; //Acá meter el token que se recibe
valida_parametro_and_die($jwt , "Es necesario introducir un token");

try{
    $decoded = JWT::decode($jwt, $key, $encrypt);
    //Validar iis = $global_apiserver
//Validar que el issuer obtenido del token sea igual al que se usó
//print_r(json_encode($decoded->iss==$global_apiserver));
    if($decoded->iss == $global_apiserver) {

        $respuesta["validez"] = 'valido';
        $cliente = $database->get("CLIENTES", ["ID", "NOMBRE", "RFC","RFC_FACTURARIO","ES_FACTURARIO"], ["ID" => $decoded->data->ID_CLIENTE]);
        valida_error_medoo_and_die();
        $respuesta["CLIENTE"] = $cliente;
        $respuesta["MODALIDAD"] = $decoded->data->MODALIDAD;

        if ($decoded->data->MODALIDAD == "programado") {
            $curso_programado = $database->get("CURSOS_PROGRAMADOS",["[><]CURSOS"=>["ID_CURSO"=>"ID_CURSO"]], ["CURSOS_PROGRAMADOS.ID","CURSOS_PROGRAMADOS.PERSONAS_MINIMO","CURSOS.NOMBRE"], ["ID" => $decoded->data->ID_CURSO]);
            valida_error_medoo_and_die();
            $etapa = $database->get("ETAPAS_PROCESO",["ETAPA"],["ID_ETAPA"=>$curso_programado["ETAPA"]]);
            $curso_programado["NOMBRE_ETAPA"] = $etapa["ETAPA"];
            $respuesta["CURSO"] = $curso_programado;
            $respuesta["ID"] = $decoded->data->ID_CURSO;

        }
        if ($decoded->data->MODALIDAD == "insitu") {
            $curso = $database->get("CURSOS", "*", ["ID_CURSO" => $decoded->data->ID_CURSO]);
            valida_error_medoo_and_die();
            $sede = $database->get("SCE_CURSOS" ,["[><]CLIENTES_DOMICILIOS"=>["ID_SITIO"=>"ID"]],["NOMBRE_DOMICILIO","CALLE","NUMERO_EXTERIOR","NUMERO_INTERIOR","COLONIA_BARRIO","DELEGACION_MUNICIPIO"],["AND"=>["ID_SCE"=>$decoded->data->ID_PROGRAMACION,"ID_CURSO"=>$decoded->data->ID_CURSO]]);
            valida_error_medoo_and_die();
            $curso["SEDE"] =$sede["NOMBRE_DOMICILIO"].($sede["CALLE"]?", Calle: ".$sede["CALLE"]:"").($sede["NUMERO_EXTERIOR"]?", # Ext.: ".$sede["NUMERO_EXTERIOR"]:"").($sede["NUMERO_INTERIOR"]?", # Int.: ".$sede["NUMERO_INTERIOR"]:"").($sede["COLONIA_BARRIO"]?", Barrio: ".$sede["COLONIA_BARRIO"]:"").($sede["DELEGACION_MUNICIPIO"]?", Municipio: ".$sede["DELEGACION_MUNICIPIO"]:"");
            $sce = $database->get("SERVICIO_CLIENTE_ETAPA",["[><]ETAPAS_PROCESO"=>["ID_ETAPA_PROCESO"=>"ID_ETAPA"]],["ETAPA"],["SERVICIO_CLIENTE_ETAPA.ID"=>$decoded->data->ID_PROGRAMACION]);
            valida_error_medoo_and_die();
            $curso["NOMBRE_ETAPA"] = $sce["ETAPA"];
            $respuesta["CURSO"] = $curso;
            $respuesta["ID"] = $decoded->data->ID_PROGRAMACION;
        }


        $domicilios = $database->select("CLIENTES_DOMICILIOS","*",["AND"=>["ID_CLIENTE"=>$decoded->data->ID_CLIENTE,"ES_FISCAL"=>"si"]]);
        valida_error_medoo_and_die();
        $domicilios_aux = [];
        for($i = 0; $i < count($domicilios); $i++)
        {
            $domicilios_aux[$i]["ID"] = $domicilios[$i]["ID"];
            $domicilios_aux[$i]["NOMBRE"] = $domicilios[$i]["NOMBRE_DOMICILIO"].($domicilios[$i]["CALLE"]?", Calle:".$domicilios[$i]["CALLE"]:"").($domicilios[$i]["NUMERO_EXTERIOR"]?", # Ext.: ".$domicilios[$i]["NUMERO_EXTERIOR"]:"").($domicilios[$i]["NUMERO_INTERIOR"]?", # Int.: ".$domicilios[$i]["NUMERO_INTERIOR"]:"").($domicilios[$i]["COLONIA_BARRIO"]?", Barrio: ".$domicilios[$i]["COLONIA_BARRIO"]:"").($domicilios[$i]["DELEGACION_MUNICIPIO"]?", Municipio: ".$domicilios[$i]["DELEGACION_MUNICIPIO"]:"");
            $contactos = $database->select("CLIENTES_CONTACTOS",["ID","NOMBRE_CONTACTO","TELEFONO_FIJO","TELEFONO_MOVIL","EMAIL"],["ID_CLIENTE_DOMICILIO"=>$domicilios[$i]["ID"]]);
            valida_error_medoo_and_die();
            for($c = 0; $c<count($contactos) ; $c++)
            {
                $contactos[$c]["TEXTO"] = $contactos[$c]["NOMBRE_CONTACTO"].($contactos[$c]["TELEFONO_FIJO"]?", Telefono: ".$contactos[$c]["TELEFONO_FIJO"]:$contactos[$c]["TELEFONO_MOVIL"]).($contactos[$c]["EMAIL"]?", email: ".$contactos[$c]["EMAIL"]:"");
            }

            $domicilios_aux[$i]["CONTACTOS"] = $contactos;
            $domicilios_aux[$i]["CC"] = count($contactos);

        }

        $respuesta["DOMICILIOS"] = $domicilios_aux;
        $respuesta["CD"] = count($domicilios_aux);
    }
    else
    {
        $respuesta["validez"] = 'invalido';
    }

}catch (Exception $e)
{

    $respuesta["validez"] = 'invalido';
}






print_r(json_encode($respuesta));


?>