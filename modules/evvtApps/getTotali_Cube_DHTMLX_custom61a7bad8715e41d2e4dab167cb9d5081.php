<?php 
   include_once("config.inc.php");
 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
if("report"=="none" || "report"=="report"){
    $type="";
    $nu="a";
$reportid="38";
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
       
   if("1"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",,1,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,count");

 $group=array();
 $fld=explode(",","Project_ID,Project_RMA,Project_Substatus,Project_Account,Project_LBLGROUPBUYER,Project_Progetto_collegato,Project_Project_Name,Project_Assigned_To,Project_Brand,Project_Serial_Number,Project_Model_Number,Project_Id_product,Project_total_paid,Project_total_received,Project_total_time,Project_difference_time,Project_Estimate_external_Time,Project_Estimate_internal_Time,Project_Days_on,Project_Lavorato,Project_Invio_Box,Project_Created_Time,Project_Status,Project_Refurbishing,Project_Swap_con_prodotto_nuovo,Project_Spedizione_prodotto_di_cortesia,Project_On_Site,Project_Current_level,Project_Warranty,Project_Accettazione_Modello_Sostitutivo,Project_Tipo_Avviso,Project_Difetti_esterni,Project_Password_BIOS,Project_Difetto_dichiarato,Project_description,Project_Project_No,Project_ID_progetto");

for($j=0;$j<sizeof($suba);$j++){
 $in=key(preg_grep("/\b$fld[$j]\b/i", $f));


 $ff1=explode('AS',$f[$in]);
 $ff2=explode(' as ',$ff1[0]);
 $ff=$ff2[0];

if($grp[$j]==1) {$group[$k]=$ff;
$k++;}
    if($suba[$j]!='')
        $fields.=$suba[$j].'('.$ff.') as '.$fld[$j].',';
    else $fields.=$ff.' as '.$fld[$j].',';
}

$group2=implode(",",$group);
                $qr=strtolower("select $fields vtiger_project.projectid as primid FROM ".$rq[1]." group by $group2");
}
        else{
        $qr=strtolower($rq[0]." ,vtiger_project.projectid as primid FROM ".$rq[1]);}       }
        
          else {
          $query1="show columns from  ";


	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;}
}}

          $qr="select $fname as primid,.* from  ";
            }
             header("Content-type: text/xml");
//encoding may be different in your case
echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>");

//start output of data
echo "<rows id=\"0\">";
$fld=explode(",","Project_ID,Project_RMA,Project_Substatus,Project_Account,Project_LBLGROUPBUYER,Project_Progetto_collegato,Project_Project_Name,Project_Assigned_To,Project_Brand,Project_Serial_Number,Project_Model_Number,Project_Id_product,Project_total_paid,Project_total_received,Project_total_time,Project_difference_time,Project_Estimate_external_Time,Project_Estimate_internal_Time,Project_Days_on,Project_Lavorato,Project_Invio_Box,Project_Created_Time,Project_Status,Project_Refurbishing,Project_Swap_con_prodotto_nuovo,Project_Spedizione_prodotto_di_cortesia,Project_On_Site,Project_Current_level,Project_Warranty,Project_Accettazione_Modello_Sostitutivo,Project_Tipo_Avviso,Project_Difetti_esterni,Project_Password_BIOS,Project_Difetto_dichiarato,Project_description,Project_Project_No,Project_ID_progetto");
    $checkf=explode(",",",,1,,1,1,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,1");
        if(""!="") $lim="Limit 0,";
        else $lim="";
             if($_REQUEST["condit"]!="") $co=$_REQUEST["condit"];
else $co="";
        $query=mysql_query($qr." ".$co." ".$lim);
    if($query){
	while($row=mysql_fetch_array($query)){
       $id=$row["primid"];


		echo ("<row id=\"".$id."\">");
             for($k=0;$k<sizeof($fld);$k++){
             if($checkf[$k]==1){
$name=strtolower($fld[$k]);
$namec=$fld[$k];
              $name1=str_replace("_","",strtolower($fld[$k]));
               if (in_array($namec,$focus1->ui10_fields)) {


								$type = getSalesEntityType($row["$name"]);
								$tmp =getEntityName($type,$row["$name"]);
								if (is_array($tmp)){
									foreach($tmp as $key=>$val){ if($val==null) $val="-";
                                                                               	print("<cell><![CDATA[$val]]></cell>");

										break;
									}
								}}
         else  if(!strstr($name,"Assigned_To")){$val=$row["$name"];
		print("<cell><![CDATA[$val]]></cell>");


  }
  else{            $u=mysql_query("select * from vtiger_users where id=".$row["$name"]);
if(mysql_num_rows($u)!=0)  $us=getUsername($row["$name"]);
  else {$us1=getGroupname($row["$name"]);
  $us=$us1[0];}
       print("<cell><![CDATA[$us]]></cell>");

                }
                   if("Project"!="")
       $md1="Project";
         
}
                }
       $link='<a href="index.php?module='.$md1.'&action=DetailView&record='.$id.'" target="_blank">Dettaglio</a>';

print("<cell><![CDATA[$link]]></cell>");
                		print("</row>");
                }}
                else{
//error occurs
	echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
}

echo "</rows>";

?>