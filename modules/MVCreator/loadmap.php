<?php
require_once('Smarty_setup.php');
include 'XmlContent.php';
include('modfields.php');

global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $adb, $root_directory, $current_user,$log;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
$smarty = new vtigerCRM_Smarty();


$mid=$_POST['MapID'];
$qid=$_POST['queryid'];



$sql="SELECT query from mvqueryhistory where id=? AND active=?";
$result=$adb->pquery($sql, array($qid, 1));
$query=str_replace("query", "", $result);

function whereConditions($module)

{
    $a =getModFields($module, $acno.$dbname);
    $a=htmlentities($a);
    //$a=htmlspecialchars($a);
    $exp=explode("'", $a);
    $n=count($exp);

    for($i=0;$i<$n;$i++) {
        if ($i%2!=0) {
            $values[]=$exp[$i];
        } else {
            $values[]=preg_replace("/[^A-Za-z0-9\- ]/", "", $exp[$i]);
        }
    }

    for ($i=0; $i<$n-1; $i++) {
        if ($i%2==0) {
            $values[$i]=str_replace('gt','',$values[$i]);
            $values[$i]=substr($values[$i],0,-22);
        }
    }

    $values[$n-1]=str_replace('gt','',$values[$n-1]);
    $values[$n-1]=substr($values[$n-1],0,-18);
    array_shift($values);
    $nr=$n/2;
    for ($j = 0; $j <$nr-1; $j++) {
        $sendarray[] = array(
            'Values' => $values[2*$j],
            'Texti' => $values[2*$j+1],
        );
    }

  return $sendarray;

}
//$prv= WhereConditions('Messages');
//print_r($prv);


$sql1 = $adb->query("select* from mvqueryhistory where id='$qid'");
$sql2 = $adb->query("select sequence from mvqueryhistory where id='$qid' AND active='1'");
$seq=$adb->query_result($sql2, 0, "sequence");
$seq=$seq-1;
$FirstModule = $adb->query_result($sql1, $seq, "firstmodule");
$SecondModule = $adb->query_result($sql1, $seq, "secondmodule");
$first=whereConditions($FirstModule);
$second=whereConditions($SecondModule);
$labels=array_merge($first,$second);


$smarty->assign("MOD", $mod_strings);
$smarty->assign("FIELDLABELS", $campiSelezionatiLabels);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE", $currentModule);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("DATEFORMAT", $current_user->date_format);
$smarty->assign("QUERY", $query);
if($SecondModule=='None')
    $smarty->assign("valueli", $first);
else
    $smarty->assign("valueli", $labels);

$smarty->display("modules/MVCreator/WhereCondition.tpl");




  ?>
