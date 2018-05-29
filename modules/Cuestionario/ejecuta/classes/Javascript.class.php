<?php
/**
 * javascript.class.php  -  Build 01/09/200603:54:36 PM
 * 
 * 
 * @package Framework MEN
 * @author alvaro
 * @version 1.0
 * 
 */


class JavaScript {

	static private $_libreria_cargada = false;
	public $code; 
	public $sin_tags;

	function __construct ($code = "",$write = true,$sin_tags = false) {
		$this->sin_tags = $sin_tags;
		if ($code != "") {
			$this->code = addcslashes(htmlentities($code,ENT_NOQUOTES),"\n\t");
			if ($write) {
				$this->write();
			}
		}
	}
	function write() {
		if (!self::$_libreria_cargada && !$this->sin_tags) {
			echo '<script language="JavaScript" src="/javascript/common.js"></script>';
			self::$_libreria_cargada = true;
		}
		$buff = (!$this->sin_tags)?'<script language="JavaScript">':'';
		$buff .= $this->code;
		$buff .= (!$this->sin_tags)?'</script>':'';
		echo $buff;
	}
	
	static function alert($msj,$sin_tags=false) {
		if (!self::$_libreria_cargada && !$sin_tags) {
			echo '<script language="JavaScript" src="/javascript/common.js"></script>';
			self::$_libreria_cargada = true;
		}
		$buff = (!$sin_tags)?'<script language="JavaScript">':'';
		$buff .= "alert('".$this->code."')";
		$buff .= (!$sin_tags)?'</script>':'';
		echo $buff;
	}
	static function isLibreriaCargada() {
		return self::$_libreria_cargada;
	}
}

?>
