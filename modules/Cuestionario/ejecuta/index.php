<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Teknema</title>
	<link REL="SHORTCUT ICON" HREF="../../../themes/images/vtigercrm_icon.ico">	
	<style type="text/css">@import url("../../../themes/softed/style.css");</style>
    <script language="JavaScript" src="js/jquery.js" type="text/javascript"></script>
    
    <link rel="stylesheet" href="js/jquery.treeview.css" />
    <link rel="stylesheet" href="screen.css" />
    
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
    <script src="js/jquery.treeview.js" type="text/javascript"></script>
    <script src="js/cuestionario.js" type="text/javascript"></script>
</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small>
	<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class="hdrNameBg">
	<tr>
		<td valign=top><img src="../../../themes/softed/images/vtiger-crm.gif" alt="Prodat" title="Prodat" border=0></td>
		<td width=100% align=center>
		</td>
		<td class=small nowrap>
			<table border=0 cellspacing=0 cellpadding=0>
			 <tr>
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap> <a href="../../../index.php?module=Users&action=Logout">Salir</a></td>
			 </tr>
			</table>
		</td>
	</tr>

	</TABLE>


<?php
require_once("global.inc.php");

$idrevision = $_REQUEST['rev'];

if(!empty($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Revision'){
    $lnk_salir = '#" onclick="window.close();';
}else{
    $lnk_salir = '../../../index.php?module=Users&action=Logout';
}

if(!empty($idrevision)){
    $cuestionario_rev = Cuestionario::getCuestionariobyRev($idrevision);
    $idcuestionario = $cuestionario_rev->getCuestionarioid();
}else{
    new Error('La revisión requerida no existe', Error::TIPO_FATAL);
}


//validamos que el cuestionario exista
if(!Cuestionario::existeCuestionario($idcuestionario)){
	new Error('El cuestionario requerido no existe', Error::TIPO_FATAL);
}

//traemos la informacion del cuestionario
$cuestionario = Cuestionario::getCuestionario($idcuestionario);

//validamos que el cuestionario este vigente
if($cuestionario->getestadocuestionario() != 'Vigente'){
	new Error('El cuestionario no se encuentra vigente para responder', Error::TIPO_FATAL);
}

//si recibimos infomacion por posr de envio del formulario
if($_REQUEST['save']){

	$aPreguntas = $cuestionario->getpreguntas();
	foreach($aPreguntas as $oPregunta){
		//creamos la respuesta
		$oRespuesta = new Respuesta();
		//$oRespuesta->setrevisionid($cuestionario->getCuestionarioid());
		$oRespuesta->setrevisionid($idrevision);
		$oRespuesta->setpreguntasid($oPregunta->getpreguntasid());
		$oRespuesta->setquestion($oPregunta->getquestion());
		$oRespuesta->setcategoriapregunta($oPregunta->getcategoriapregunta());
		$oRespuesta->setsubcategoriapregunta($oPregunta->getsubcategoriapregunta());

		$respondida = ($_POST[$oPregunta->getpreguntasid()])? Respuesta::RESPONDIDA : Respuesta::SINRESPONDER;
		$oRespuesta->setrespondida($respondida);

		$oRespuesta->setyes_points($oPregunta->getyes_points());
		$oRespuesta->setno_points($oPregunta->getno_points());

		//asociamos el texto de la respuesta

		switch($_POST[$oPregunta->getpreguntasid()]){

			case 'yes':
				$oRespuesta->setrespuesta($oPregunta->getyes());
				$oRespuesta->setrespuestaid('Si');
				break;
			case 'no':
				$oRespuesta->setrespuesta($oPregunta->getno());
				$oRespuesta->setrespuestaid('No');
				break;
			case 'na':
				$oRespuesta->setrespuesta('No aplicable');
				$oRespuesta->setrespuestaid('NoA');
				break;
			case 'text':
				$oRespuesta->setrespuesta($_POST[$oPregunta->getpreguntasid().'_respuesta']);
				$oRespuesta->setrespuestaid('Text');
				break;
		}

		$aRespuestas[] = $oRespuesta;
		$oRespuesta->_save();

	}
}

//veamos si ya tiene respuestas completas
//como no tenemos un campo en la tabla de cuestionario que se pueda utilizar
//para saber si el cuestionario fue contestado o no
//calculamos si hay respuestas contestadas
$aRespuestas = Cuestionario::getRespuestasCuestionario($idrevision);
$aCategorias = $cuestionario->getCategorias();

//var_dump($aRespuestas); die();
?>

<br/>
<table width=98% align=center border="0">
<tr>
	<td valign="top"><img src="themes/softed/images/showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign="top" width="100%">
    
<div id="treecontrol" style="padding: 10px;">
    &nbsp; <a title="Collassare tutto" href="#"><img border=0 src="js/images/minus.gif" /> Collassare tutto</a>
    &nbsp; <a title="Espandere tutto" href="#"><img  border=0 src="js/images/plus.gif" /> Espandere tutto</a>
    &nbsp; <a title="Espandi/Collassa" href="#"><img  border=0 src="js/images/reload.gif" /> Espandi/Collassa</a>
    &nbsp; <a title="Salva" href="#" onclick="document.form.submit()"><img  border=0 src="js/images/save.gif" /> Salva</a>
    &nbsp; <a title="Esci" href="<?= $lnk_salir ?>"><img  border=0 src="js/images/close.gif" /> Esci</a>
</div>
<div style="padding: 10px;">
<form action="index.php?cst=<?= $idcuestionario ?>" method="post"
	id="form" name="form"><input type="hidden" name="save" value="save" />
	<input type="hidden" name="rev" value="<?php echo $idrevision; ?>" />
	<input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module']; ?>" />
<ul id="navigation" class="filetree">
<?php foreach($aCategorias as $oCategoria){
	$aSubcategorias = $oCategoria->getSubcategorias($idcuestionario);
	?>


	<li class="closed"><span class="folder"><?= $oCategoria ?></span>
	<ul>
	<?php foreach($aSubcategorias as $oSubcategoria){
		$aPreguntas = $oSubcategoria->getPreguntas($idcuestionario, $oCategoria->getNombre());
		?>
		<li class="closed"><span class="folder"><?= $oSubcategoria ?></span>
		<ul>
		<?php foreach ($aPreguntas as $oPregunta){
			$idpregunta = $oPregunta->getpreguntasid();

			if (array_key_exists($idpregunta, $aRespuestas)){
				$oRespuesta = $aRespuestas[$idpregunta];
				$seleccionado = $oRespuesta->getRespuestaid();
				$texto = $oRespuesta->getRespuesta();
				$respondida = $oRespuesta->getRespondida();
			}
			?>

			<li><span class="file">

			<div>
			    <!-- onmouseout="ocultaPregunta(this)" onmouseover="muestraPregunta(this)" -->
			<div name="<?php echo $idpregunta ?>"  id="pregunta_<?php echo $idpregunta ?>" title="<?php echo $texto ?>"><?php echo $oPregunta->getQuestion() ?> 
			<img id="ok_<?php echo $idpregunta?>" alt="Estado de la pregunta"   src="<?php echo ($respondida)? 'ok.jpg':'pending.png';?>" />
			</div>
			<div id="respuesta_<?php echo $idpregunta?>">
			<div><label><input onclick="valida(this)" type="radio" id="opcion" name="<?php echo $idpregunta ?>" value="yes"
				<?php if($seleccionado == 'Si')echo 'checked="checked"'?> />S&iacute;</label>
			<label><input id="opcion" onclick="valida(this)" name="<?php echo $idpregunta ?>" type="radio" value="no"
				<?php if($seleccionado == 'No')echo 'checked="checked"'?> />No</label>
			<label><input id="opcion" onclick="valida(this)" name="<?php echo $idpregunta ?>" type="radio" value="na"
				<?php if($seleccionado == 'NoA')echo 'checked="checked"'?> />N/A</label>
			<label><input id="opcion" name="<?php echo $idpregunta ?>" type="radio" value="text" onclick="valida(this)"
				<?php if($seleccionado == 'Text')echo 'checked="checked"'?> />Personalizado</label>
			</div>
			<div id="texto_<?php echo $idpregunta ?>" <?php if($seleccionado != 'Text'):?>style="display:none <?php endif; ?>">
			<label><textarea id="area_<?php echo $idpregunta ?>"
				name="<?php echo $idpregunta ?>_respuesta" cols=60><?php if($seleccionado == 'Text'){echo $texto;}else{echo 'Su respuesta';}?></textarea>
			</label></div>
			</div>
			
			</span></li>

			<?php } ?>

		</ul>
		</li>

		<?php } ?>
	</ul>
	</li>

	<?php } ?>

</ul>
</form>
</div>
<div id="treecontrolb" style="padding: 10px;">
    &nbsp; <a title="Collassare tutto" href="#"><img border=0 src="js/images/minus.gif" /> Collassare tutto</a>
    &nbsp; <a title="Espandere tutto" href="#"><img  border=0 src="js/images/plus.gif" /> Espandere tutto</a>
    &nbsp; <a title="Collassare/Espandare" href="#"><img  border=0 src="js/images/reload.gif" /> Collassare/Espandare</a>
    &nbsp; <a title="Salva" href="#" onclick="document.form.submit()"><img  border=0 src="js/images/save.gif" /> Salva</a>
    &nbsp; <a title="Esci" href="<?= $lnk_salir ?>"><img  border=0 src="js/images/close.gif" /> Esci</a>
</div>
</td>
</tr>
</table>
</body>
</html>
