<?php

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("CERTIFICANDO", getcwd(), $database->error()[2], $database->last_query(), "leovardo.quintero@dhttecno.com"); 
		die(); 
	} 
} 

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}

function verifica_fecha_valida($fecha_aaaammdd){
	valida_parametro_and_die($fecha_aaaammdd, "Es necesario capturar una fecha");
	if (strlen($fecha_aaaammdd) != 8) {
		imprime_error_and_die("Verifica el formato de la fecha de inicio");
	}
	$anhio = intval(substr($fecha_aaaammdd,0,4));
	$mes = intval(substr($fecha_aaaammdd,4,2));
	$dia = intval(substr($fecha_aaaammdd,6,2));
	if (!checkdate($mes , $dia, $anhio)){
		imprime_error_and_die("La fecha de inicio no es válida");
	}
}

?>