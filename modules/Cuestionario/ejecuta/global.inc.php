<?php

define ("APP_PATH",realpath(dirname(__FILE__))."/");

ini_set('error_log',APP_PATH.'error.log');

function __autoload($class_name) {
	$file = APP_PATH.'classes/'.$class_name.'.class.php';
	if (file_exists($file)){
		require_once $file;
	}else{
		$file = APP_PATH.'classes/db/'.$class_name.'.class.php';
		if (file_exists($file)) require_once $file;
	}
}

function exception_handler($e) {
	$mensaje = APP_MENSAJE_ERROR;
	if (!defined('ENTORNO_PRODUCCION')) {
		$mensaje .= "\n\n".$e->getMessage();
	}
	error_log('Excepcion no capturada en Sistema: \n'.print_r($e,true));
	new Error($mensaje,Error::TIPO_FATAL,$e);
}
set_exception_handler('exception_handler');


function error_handler($tipo_err, $cadena_err, $archivo_err, $linea_err) {
	$e = array('Tipo de Error' => $tipo_err , 'Mensaje del Error' => $cadena_err , 'Archivo Disparador' => $archivo_err, 'Nro. L�nea' => $linea_err);
	$mensaje = APP_MENSAJE_ERROR;
	if (!defined('ENTORNO_PRODUCCION')) {
		$mensaje .= "\n\n".$e['Mensaje del Error'];
	}
	if ($tipo_err < 8) {
		error_log('Error en Sistema: \n'.print_r($e,true));
		new Error($mensaje,Error::TIPO_FATAL,$e);
	}
}
set_error_handler('error_handler');


?>