<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

global $adb,$log;
require_once("modules/Preguntas/Preguntas.php");

$kaction=$_REQUEST['kaction'];
$log->debug('aldas ac'.$kaction);
$content=array();
if($kaction=='retrieve'){

$query=$adb->pquery("Select * from vtiger_cuestiones join vtiger_preguntas a on a.preguntasid=vtiger_cuestiones.preguntasid
                    join vtiger_crmentity ce on a.preguntasid=ce.crmid
                    where ce.deleted=0 and cuestionarioid=?",array($_REQUEST['record']));
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
   $content[$i]['preguntasid']=$adb->query_result($query,$i,'preguntasid');
      $content[$i]['cid']=$adb->query_result($query,$i,'cuestionesid');

   $content[$i]['name']=$adb->query_result($query,$i,'pregunta').'#'.$adb->query_result($query,$i,'preguntasid');
   $content[$i]['si']=$adb->query_result($query,$i,'yes_points');
   $content[$i]['no']=$adb->query_result($query,$i,'no_points');
   $content[$i]['cat']=$adb->query_result($query,$i,'categoria');
   $content[$i]['subcat']=$adb->query_result($query,$i,'subcategoria');
   $content[$i]['url']='index.php?module=Preguntas&action=DetailView&record='.$content[$i]['preguntasid'];

}
echo json_encode($content);

}
elseif($kaction=='preg'){

   $query=$adb->pquery("Select * from vtiger_preguntas join vtiger_crmentity on crmid=preguntasid where deleted=0 order by preguntasid",array());
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
    $id=$adb->query_result($query,$i,'preguntasid');
    $name=$adb->query_result($query,$i,'question');

        $content[$i]['preg']=$name.'#'.$id;
        $content[$i]['pregid']=$id;
        $content[$i]['selected']="false";

    }
    echo json_encode($content);
}
elseif($kaction=='update'){
 
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    $pregid=$mv->preguntasid;
    $id=$mv->cid;
    $cat=$mv->cat;
  
    $subcat=$mv->subcat;
    $a=$adb->query('select * from vtiger_preguntas where preguntasid='.$pregid);
$y=$adb->query_result($a,0,'yes_points');
$n=$adb->query_result($a,0,'no_points');
$q=$adb->query_result($a,0,'question');

$adb->query("update vtiger_cuestiones set pregunta='$q', preguntasid=$pregid,categoria='$cat',subcategoria='$subcat',yes_points=$y, no_points=$n where cuestionesid=$id");
}
elseif($kaction=="destroy"){
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];     
    $id=$mv->cid;
   $adb->query('delete from vtiger_cuestiones where cuestionesid='.$id);
//    $query=$adb->pquery("update vtiger_crmentity set deleted=1 where crmid=?",array($id));
//    echo $query;
}
elseif($kaction=="create"){
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
$pregid=explode("#",$mv->name);
    $cat=$mv->cat;
    $subcat=$mv->subcat;
    $cid=$_REQUEST['record'];
    if(sizeof($pregid)==2) $prid=$pregid[1];
    else $prid=$pregid[0];
    $a=$adb->query('select * from vtiger_preguntas where preguntasid='.$prid);
$y=$adb->query_result($a,0,'yes_points');
$n=$adb->query_result($a,0,'no_points');
$q=$adb->query_result($a,0,'question');
$adb->query("insert into vtiger_cuestiones (cuestionarioid,pregunta,categoria,subcategoria,yes_points,no_points,preguntasid) values ($cid,\"$q\",'$cat','$subcat',$y,$n,$prid)");
}
?>
