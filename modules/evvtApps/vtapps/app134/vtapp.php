<?php
    require_once("modules/evvtApps/vtapps/baseapp/vtapp.php");

    class HR_SCORE__customcae3627b7f9b4c4ecaa7a404ecc10cb8 extends vtApp {

	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = true;
	var $wwidth = 900;
	var $wheight = 500;
	var $haseditsize = true;
	var $ewidth = 0;
	var $eheight = 0;
        public function getQuerypdf($co){
        global $adb;
        $q=$adb->pquery("select * from vtiger_evvtapps where evvtappsid=?",array($this->appid));
        $query=explode("limit",$adb->query_result($q,0,"vtappquery"));
        return $query[0]."  ".$co." limit ".$query[1];
}
public function getQuery($co){
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
if("mv"=="none" || "mv"=="report"){
    $type="";
      $nu="a";
$reportid="";
$focus1=new ReportRun($reportid);
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow['tabid'])." ".$currencyfieldrow['fieldlabel'];
				$modprefixedlabel = str_replace(' ','_',$modprefixedlabel);

				if($currencyfieldrow['uitype']!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
        if("0"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,");

 $group=array();
  $k=0;
  $fld=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR");

for($j=0;$j<sizeof($suba);$j++){
 $in=key(preg_grep("/\b$fld[$j]\b/i", $f));


 $ff1=explode('AS',$f[$in]);
 $ff2=explode(' as ',$ff1[0]);
 $ff=$ff2[0];

if($grp[$j]==1) {$group[$k]=$ff;
$k++;}
    if($suba[$j]!='')
        $fields.=$suba[$j].'('.$ff.') as '.str_replace(".","",$fld[$j]).',';
    else $fields.=$ff.' as '.str_replace(".","",$fld[$j]).',';
}


$group2=implode(",",$group);
                $qr=strtolower("select $fields .id as primid FROM ".$rq[1]." group by $group2");
}
        else{
        $qr=strtolower($rq[0]." ,.id as primid FROM ".$rq[1]);}
            }

          else {
              $query1="show columns from mv_soglia_hr";

   $checkf=explode(",","1,1,,1,1,1,1,1,1,1,1,1");
	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
         if($checkf[$i1-1]==1)  $fl[$i1-1]=$f." AS ".$f;
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;
       $fname1=str_replace("id","",$fname);
       $tab=$adb->query("select * from vtiger_tab where name like \"$fname1\"");
       if($adb->num_rows($tab)!=0)
       $md1=$adb->query_result($tab,0,"name");
}
}}
$fl=implode(",",$fl);
                $rq=explode("from","select $fl from mv_soglia_hr ");
              
   if("0"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,");

 $group=array();
 $fld=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR");

for($j=0;$j<sizeof($suba);$j++){
 $in=key(preg_grep("/\b$fld[$j]\b/i", $f));


 $ff1=explode('AS',$f[$in]);
 $ff2=explode(' as ',$ff1[0]);
 $ff=$ff2[0];

if($grp[$j]==1) {$group[$k]=$ff;
$k++;}
if($checkf[$j]==1){
    if($suba[$j]!='')
        $fields.=$suba[$j].'('.$ff.') as '.str_replace(".","",$fld[$j]).',';
    else $fields.=$ff.' as '.str_replace(".","",$fld[$j]).',';}
}

$group2=implode(",",$group);
                $qr=strtolower("select $fields $fname as primid FROM ".$rq[1]." group by $group2");
}
  else{
        $qr=strtolower($rq[0]." ,$fname as primid FROM ".$rq[1]);}
            }
            if(""!="") $lim="Limit 0,";
        else $lim="";

        $query=$qr."  ".$co." ".$lim;
        return $query;
}
public function getContent($lang,$condit) {
global $adb,$current_language,$current_user;
		$smarty = new vtigerCRM_Smarty;
		$smarty->template_dir = $this->apppath;

                                  $fld=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR,vis");
                                  $fldn=explode(",","CAT,SOGLIE,soglieid,Engage,Part request,Sent by Tek,Confirm,Repair,Repair no parts,Closed,Closed no parts,Visualizza");
                                          $fldnex=explode(","," ,CAT,SOGLIE,soglieid,Engage,Part request,Sent by Tek,Confirm,Repair,Repair no parts,Closed,Closed no parts,Visualizza");
                                  $checkf=explode(",","1,1,,1,1,1,1,1,1,1,1,1");
                                  $grp=explode(",",",,,,,,,,,,,");
                                  $aggr=explode(",",",,,,,,,,,,,");
                                 $suba=explode(",",",,,,,,,,,,,");
                                        $wdth=explode(",","200,120,200,80,80,80,80,80,80,80,80,150");
                                     $f=Array();
                                     $s=Array();
                                     $a=Array();
                                    $g=Array();



 $l=0;
 $m=0;
 $fp = fopen('HR_SCORE__customcae3627b7f9b4c4ecaa7a404ecc10cb8.csv', 'w');
    fputcsv($fp, $fldnex,";");
    fclose($fp);
  echo "<input type=\"button\" value=\"Export csv\" onclick=\"window.location.href='index.php?module=evvtApps&action=exportcsvHR_SCORE__customcae3627b7f9b4c4ecaa7a404ecc10cb8'\">";

 for($k=0;$k<sizeof($fld);$k++){
 if($checkf[$k]==1){
       $name=str_replace(".","",str_replace("_","",strtolower($fld[$k])));
       $name1=str_replace("_"," ",$fld[$k]);
       $funcname=$fldn[$k];
       $func=$aggr[$k];
       $funcs=$suba[$k];
       $wdth1=$wdth[$k]."px";
       if($aggr[$k]!="" || $suba[$k]!="")
                 $aggre=",\"aggregates\":[\"count\",\"sum\",\"average\",\"max\",\"min\"]";

       else $aggre="";
       if($aggr[$k]!=""){
       $agg=",\"footerTemplate\": \"$func: #=$func#\"";
$a[$l]=array("field"=>"$name","aggregate"=>"$func");
$l++;
}
else $agg="";
               if($suba[$k]!=""){
       $gr=",\"groupFooterTemplate\": \"$funcs: #=$funcs#\"";
$s[$m]=array("field"=>"$name","aggregate"=>"$funcs");
  $m++;
}
else $gr="";
if($name=="vis") $temp=" , \"template\": \"<a href='#=url  #' target='_blank'>Dettaglio</a>\"";
else $temp="";
        $f[$k]="{\"field\":\"$name\",\"title\":\"$funcname\",\"width\":\"$wdth1\" $aggre $agg $gr $temp}";
               if(($aggr[$k]!="" && $aggr[$k]!="count") || ($suba[$k]!="" && $suba[$k]!="count") )
$type=",type: \"number\"";
else $type=",type: \"string\"";
 $c[$k]="$name: { editable: false, nullable: true $type}";
}}
$d=0;
for($j=0;$j<sizeof($fld);$j++){
if($checkf[$j]==1){
       $name=str_replace("_","",strtolower($fld[$j]));
       $name1=str_replace("_"," ",$name);
       $func=$aggr[$j];
       $funcs=$suba[$j];
       
          if($grp[$j]==1){
$g[$d]=array("field"=>"$name","aggregates"=>$s);        
       $d++;
}}
       }
$f=implode(",",$f).",";
$c=implode(",",$c).",";
$fieldcolumns="[$f]";
$fieldconfiguration="{".$c."}";
$arr2=$a;
    $group=json_encode($g);
        $aggregate=json_encode($arr2);
$smarty->assign("column",$fieldcolumns);
$smarty->assign("field",$fieldconfiguration);
$smarty->assign("group",$group);
$smarty->assign("aggregate",$aggregate);
$smarty->assign("agg",$l);
$smarty->assign("grp",$m);
$smarty->assign("cond1",$condit);
                	return $smarty->display("ListViewKUIHR_SCORE__customcae3627b7f9b4c4ecaa7a404ecc10cb8.tpl");
        }

}
?>