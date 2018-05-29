<?php
/**
 * error.class.php  -  Build 25/07/200604:01:30 PM 
 * 
 * 
 * @package Framework
 * @author Franco
 * @version 0.1
 * 
 */

if (!class_exists('JavaScript')) {
	require_once("Javascript.class.php");
}
 
class Error {

	const TIPO_NOTICE = 0;
	const TIPO_WARNING = 1;
	const TIPO_ERROR = 2;
	const TIPO_FATAL = 3;
	
//	private $alerta;
//	private $existe=false; 
	
	
	public function __construct ($message = false, $type = self::TIPO_WARNING, $exception = false, $dest = false, $ajax = false) {
		if ($message) {
			$this->message = $message;
		}
		else {
		 $this->message = 'Se produjo un error no esperado'.($exception)?': \n'.$exception.getMessage():'';
		}
		$js = new JavaScript('alert("'.addslashes($this->message).'");',false,$ajax);
		if ($type > self::TIPO_WARNING || $exception) {
			error_log("Error en ".$_SERVER['SCRIPT_FILENAME'].": \n".$this->message."\n".(($exception)?print_r($exception,true):''));
		}
//		if ($type==self::TIPO_FATAL) {
//			if (defined('ENTORNO_PRODUCCION')) {
//				// Data para enviar en el Mail
//				$mensaje_error= $this->message;
//				$pagina_error=$_SERVER['SCRIPT_FILENAME'];
//				$request=$_REQUEST;
//				$exep=($exception)?print_r($exception,true):'';
//					
//				//Armado del mail
//				$to = "";
//				$subject = "Error Insertia: ".$pagina_error;
//				$mensaje = "Fecha: ".date("j-m-y")."\r\n";
//				$mensaje .= "Hora: ".date("H:i:s")."\r\n";
//				$mensaje .= "Mensaje de Error: ".$mensaje_error."\r\n";
//				$mensaje .= "Exception: ".$exep."\r\n";
//				$mensaje .= "Request: ".print_r($request,true)."\r\n";
//				$mensaje .= "Session: ".print_r($_SESSION,true)."\r\n";
//				$cabeceras = "From: Error Insertia <sistema@insertia.net>\r\n";
//	
//				mail($to,$subject,$mensaje,$cabeceras);
//			}			
//		}
		
		if ($dest) {
			$js->code .= "location.href = '$dest';";
			
		}
		else {
			if ($type > self::TIPO_NOTICE) {
				$js->code .= "history.back();";
				
			}
		}
				
		$js->write();

		if ($type >= self::TIPO_WARNING) {
			die();
		}    
	}	
	
//esto en caso de que modifiquemos la clase para poder personalizar los alerts
//que necesitan el dom funcionando
 
//	function write(){
//	    
//	    $this->alerta->write();
//
//      if ($type >= self::TIPO_ERROR) {
//          die();
//      }      
//	}
	
}


?>
