<?php 
   include_once("config.inc.php");
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
    $grp=explode(",","1,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,");

 $group=array();
 $fld=explode(",","CAT,SOGLIE,soglieid,ValidCall,Repairing,WaitingforParts,RepairCompleted,Closed,Accept/PaymentofQuote,PendingCustomer/Vendor");

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
        $qr=strtolower($rq[0]." ,.id as primid FROM ".$rq[1]);}       }
        
          else {
          $query1="show columns from  mv_testadayson";

$checkf=explode(",","1,1,,1,1,1,1,1,1,1,1");
	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
        if($checkf[$i1-1]==1)  $fl[$i1-1]=$f." AS ".$f;
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;}
}}

       $fl=implode(",",$fl);
                $rq=explode("from","select $fl from mv_testadayson where 1=1");
              
   if("0"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",","1,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,");

 $group=array();
 $fld=explode(",","CAT,SOGLIE,soglieid,ValidCall,Repairing,WaitingforParts,RepairCompleted,Closed,Accept/PaymentofQuote,PendingCustomer/Vendor");

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
                $qr=strtolower("select $fields CAT as primid FROM ".$rq[1]." group by $group2");
}
  else{
        $qr=strtolower($rq[0]." ,CAT as primid FROM ".$rq[1]);}
            }
             header("Content-type: text/xml");
//encoding may be different in your case
echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>");

//start output of data
echo "<rows id=\"0\">";
$fld=explode(",","CAT,SOGLIE,soglieid,ValidCall,Repairing,WaitingforParts,RepairCompleted,Closed,Accept/PaymentofQuote,PendingCustomer/Vendor");
    $checkf=explode(",","1,1,,1,1,1,1,1,1,1");
        if(""!="") $lim="Limit 0,";
        else $lim="";
             if($_REQUEST["condit"]!="") $co=$_REQUEST["condit"];
else $co="";
if("mv"=="mv"){
    require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
require('user_privileges/user_privileges_'.$current_user->id.'.php');
 if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid("Accounts")] == 3 or $defaultOrgSharingPermission[getTabid("Accounts")] == 0))
		{           

			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $current_user->id,'private'), $current_user->id);
		}
		else
		{            

			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $current_user->id), $current_user->id);
		}

                 $u=Array();
                 $j=0;
                foreach($users_combo as $key_one=>$arr){
                       foreach($arr as $sel_value=>$value){
                   
                   $u[$j]=$key_one;
                   $j++;
                }} 
                $u=implode(",",$u);
                if($current_user->is_admin=='off')
                $condd="and CAT in (select crmid from vtiger_crmentity where smownerid in ($u) and deleted=0)";

else { $condd='';    } 
}
else { $condd='';  } 

        $query=mysql_query($qr."  ".$co." ".$condd." ".$lim);
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
                   if(""!="")
       $md1="";
         
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