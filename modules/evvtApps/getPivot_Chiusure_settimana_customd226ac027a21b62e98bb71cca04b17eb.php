<?php
    global $adb,$current_language,$current_user;
    $tit="(Giorni di chiusura,CIN IW,CIN OOW,CIN Dealer,HR OOW,HR IW,Tek HR-PUR,PUR IW,PUR old,PUR OOW,DROP IW,DROP OOW,CRP per Numero *)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
$fp = fopen('Chiusure_settimana_customd226ac027a21b62e98bb71cca04b17eb.csv', 'a');
global $adb;
    $type="";
      $nu="a";
$reportid="176";
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
                if("report"=="mv")
                    $reportquery="select * from  where 1=1 ";
                    else
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]." AND vtiger_project.actualenddate >= ((CURDATE( ) -7)- INTERVAL weekday( (CURDATE( ) -7 ))DAY )  AND   vtiger_project.actualenddate <=
( CURDATE( ) + interval( 6 - weekday( CURDATE( ) )) DAY )  ";
$i1=0;
 $fld=explode(",","vtiger_crmentity.crmid,vtiger_project.progetto,vtiger_project.projectname,vtiger_project.project_no,vtiger_project.substatusproj,vtiger_project.rma,vtiger_project.linktobuyer,vtiger_project.project_id,vtiger_usersProject.user_name,vtiger_projectcf.macrostatus,vtiger_project.linktoaccountscontacts,vtiger_project.actualenddate,a10016140,a962210,a1517680,a21118220,a1550710,a959840,a15787750,a20541860,a1627990,a13571750,a9771730,a13565460,a1915040,a9618600");
      $fldaggreg=explode(",","Project_ID,Project_Progetto_collegato,Project_Project_Name,Project_Project_No,Project_Substatus,Project_RMA,Project_LBLGROUPBUYER,Project_ID_progetto,Project_Assigned_To,Project_Macro_status,Project_Account,Project_Actual_End_Date,1001614#count#vtiger_crmentity.crmid,96221#count#vtiger_crmentity.crmid,151768#count#vtiger_crmentity.crmid,2111822#count#vtiger_crmentity.crmid,155071#count#vtiger_crmentity.crmid,95984#count#vtiger_crmentity.crmid,1578775#count#vtiger_crmentity.crmid,2054186#count#vtiger_crmentity.crmid,162799#count#vtiger_crmentity.crmid,1357175#count#vtiger_crmentity.crmid,977173#count#vtiger_crmentity.crmid,1356546#count#vtiger_crmentity.crmid,191504#count#vtiger_crmentity.crmid,961860#count#vtiger_crmentity.crmid");
                                  $fldn=explode(",","Project_ID,Project_Progetto_collegato,Commessa,Project_Project_No,Project_Substatus,Project_RMA,Project_LBLGROUPBUYER,Project_ID_progetto,Project_Assigned_To,Project_Macro_status,Project_Account,Giorni di chiusura,sony ,CIN IW,CIN OOW,CIN Dealer,HR OOW,HR IW,Tek HR-PUR,PUR IW,PUR old,PUR OOW,DROP IW,DROP OOW,CRP,sonycin");
                                          $fldnex=explode(","," ,Project_ID,Project_Progetto_collegato,Commessa,Project_Project_No,Project_Substatus,Project_RMA,Project_LBLGROUPBUYER,Project_ID_progetto,Project_Assigned_To,Project_Macro_status,Project_Account,Giorni di chiusura,sony ,CIN IW,CIN OOW,CIN Dealer,HR OOW,HR IW,Tek HR-PUR,PUR IW,PUR old,PUR OOW,DROP IW,DROP OOW,CRP,sonycin");
                                 $typef=explode(",","field,field,field,field,field,field,field,field,field,field,field,field,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr");
$checkf=explode(",",",,,,,,,,,,,1,,1,1,1,1,1,1,1,1,1,1,1,1,");
                          
$dt=Array();
if("report"=="mv"){
    require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
require('user_privileges/user_privileges_'.$current_user->id.'.php');
 if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid("")] == 3 or $defaultOrgSharingPermission[getTabid("")] == 0))
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
                $condd="and  in (select crmid from vtiger_crmentity where smownerid in ($u) and deleted=0)";

else { $condd='';    } 
}
else { $condd='';  } 

		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_project.actualenddate),\"'\",\"\") as cols FROM $query $condd GROUP BY vtiger_project.actualenddate");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["projectid"]=$j;
                      $coll=explode(",' %% ',","vtiger_project.actualenddate");
                      $ptcc=explode("%%",$ptc);
                          for($ccl=0;$ccl<sizeof($coll);$ccl++){
              $coll2=str_replace(array(".",",","_"),array("","",""),strtolower($coll[$ccl]));
           $content[$j]["$coll2"]=$ptcc[$ccl];

                          }
              unset($ag1);        
        for($m=0;$m<sizeof($fld);$m++){
          $aggreg=explode("#",$fldaggreg[$m]);
        $mf=str_replace(array(".",",","_"),array("","",""),strtolower($aggreg[2]));
        if($typef[$m]=="fieldaggr" && $checkf[$m]==1){
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and  REPLACE(CONCAT(vtiger_project.actualenddate),\"'\",\"\")='$ptc' and vtiger_project.progetto=\"$aggreg[0]\" $condd ");
       //$mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero Project_ID");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(vtiger_project.actualenddate),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_project.actualenddate,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_project.actualenddate,'%Y-%m-%d')<='$dttod' and vtiger_project.progetto=\"$cat[$m]\" $condd");
             
}
  
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Project_Progetto_collegato"));     


$ag=$adb->query_result($rspots1,0,"$mf");
$fie=preg_replace('/[^A-Za-z0-9]/u','',$fld[$m]);
 if(""=="1"){
$agcel=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["$fie"]=number_format($ag,0,",",".")." <br> ".number_format($agcel,0,",",".");
}
else { $content[$j]["$fie"]=number_format($ag,0,",","."); $formula[$j]["$fie"]=$ag;}
$ag1+=number_format($adb->query_result($rspots1,0,"$mf"),0,",",".");
$ag2+=number_format($adb->query_result($rspots1,0,"$mf"),0,",",".");

                   
             }
               if($typef[$m]=="ytd" && $checkf[$m]==1){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(vtiger_project.actualenddate),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_project.actualenddate,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_project.actualenddate,'%Y-%m-%d')<='$dttod' $condd");
   
$agy=$adb->query_result($rspots2,0,"$mf");
$content[$j]["$fie"]=number_format($agy,0,",",".");
$agy2+=$adb->query_result($rspots2,0,"$mf");       }
}
  
 
  if(""!=""){
      $f=explode("#","");
          for($ik=0;$ik<sizeof($f);$ik++){
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(vtiger_project.actualenddate),\"'\",\"\")='$ptc' $condd ");
$content[$j]["formula$ik"]=number_format($adb->query_result($rspots3,0,"form"),0,",",".");
$frm[$ik]+=$adb->query_result($rspots3,0,"form");}
}
  if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format(floatval($f1[$ik]),0,",",".");
      $fcols[$ik]+=$f1[$ik];
  }
}
    if("1"==1)
         $content[$j]["totale"]=implode(" - ",$ag1);
  fputcsv($fp, $content[$j],";");
			$j++;
		}
                $content[$j]["projectid"]=$j;
                     $coll=explode(",' %% ',","vtiger_project.actualenddate");
                   
                          for($ccl=0;$ccl<sizeof($coll);$ccl++){
              $coll2=str_replace(array(".",",","_"),array("","",""),strtolower($coll[$ccl]));
           $content[$j]["$coll2"]="Totale";

                          }
          //$content[$j]["vtigerprojectactualenddate"]="Totale";
 
        for($m=0;$m<sizeof($fld);$m++){
        $aggreg=explode("#",$fldaggreg[$m]);
        $mf=str_replace(array(".",",","_"),array("","",""),strtolower($aggreg[2]));
        if($typef[$m]=="fieldaggr" && $checkf[$m]==1){
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and vtiger_project.progetto=\"$aggreg[0]\" $condd ");

       $fagg1=explode(",","Numero Project_ID");
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Project_Progetto_collegato"));
 $fie=preg_replace('/[^A-Za-z0-9]/u','',$fld[$m]);

$ag=$adb->query_result($rspots1,0,"$mf");
$content[$j]["$fie"]=number_format($ag,0,",",".");
$formula[$j]["$fie"]=$ag;
} 
 if($typef[$m]=="ytd" && $checkf[$m]==1){
      //if(""=="1") 
          $content[$j]["$fie"]=number_format($agy2,0,",","."); }
     
if($m==sizeof($fld)-1){


   if(""!=""){    $f=explode("#","");
        for($ik=0;$ik<sizeof($f);$ik++){
       $content[$j]["formula$ik"]=number_format($frm[$ik],0,",",".");}}
       
if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format($fcols[$ik],0,",",".");
    
  }
}  
if("1"==1)         
$content[$j]["totale"]=implode(" - ",$ag2); }
    }
    fputcsv($fp, $content[$j],";");
    fclose($fp);
               echo json_encode($content);}
              else echo json_encode("");

?>
