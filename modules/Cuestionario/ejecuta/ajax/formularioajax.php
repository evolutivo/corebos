<?php
require_once("../global.inc.php");

$idpregunta = $_GET['id'];
$accion = $_GET['action']; 

$oPregunta = Pregunta::getPregunta($idpregunta);
switch($accion){
	case 'no': $respuesta = $oPregunta->getNo(); break;
	case 'yes': $respuesta = $oPregunta->getYes(); break;
}

//echo $respuesta;

echo '<label><textarea id="area_'.$idpregunta.'"   name="'.$idpregunta.'_respuesta" cols=60>'.$respuesta.'</textarea></label>';

?>