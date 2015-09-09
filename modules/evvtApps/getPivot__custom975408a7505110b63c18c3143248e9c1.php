<?php
    global $adb,$current_language,$current_user;
    $tit="(Hold Sermicro Numero Project_Progetto_collegato,Hold Teknema Numero Project_Progetto_collegato,Hold test Numero Project_Progetto_collegato,on hold Numero Project_Progetto_collegato per Numero *)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
$fp = fopen('_custom975408a7505110b63c18c3143248e9c1.csv', 'a');
global $adb;
    $type="";
      $nu="a";
$reportid="39";
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
                if("none"=="mv")
                    $reportquery="select * from  where 1=1 ";
                    else
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
 $fld=explode(",","vtiger_project.progetto,vtiger_project.project_id,vtiger_project.rma,vtiger_project.linktoaccountscontacts,vtiger_project.linktobuyer,vtiger_project.projectname,vtiger_crmentityProject.createdtime,vtiger_project.projectstatus,vtiger_project.startdate,vtiger_crmentityProject.modifiedtime,vtiger_project.datasospensione,aholdsermicro0,aholdteknema0,aholdtest0,aonhold0");
      $fldaggreg=explode(",","Project_Progetto_collegato,Project_ID_progetto,Project_RMA,Project_Account,Project_LBLGROUPBUYER,Project_Project_Name,Project_Created_Time,Project_Status,Project_Start_Date,Project_Modified_Time,Project_Data_sospensione,Hold Sermicro#count#vtiger_project.progetto,Hold Teknema#count#vtiger_project.progetto,Hold test#count#vtiger_project.progetto,on hold#count#vtiger_project.progetto");
                                  $fldn=explode(",","Project_Progetto_collegato,Project_ID_progetto,Project_RMA,Project_Account,Project_LBLGROUPBUYER,Project_Project_Name,Project_Created_Time,Project_Status,Project_Start_Date,Project_Modified_Time,Project_Data_sospensione,Hold Sermicro Numero Project_Progetto_collegato,Hold Teknema Numero Project_Progetto_collegato,Hold test Numero Project_Progetto_collegato,on hold Numero Project_Progetto_collegato");
                                          $fldnex=explode(","," ,Project_Progetto_collegato,Project_ID_progetto,Project_RMA,Project_Account,Project_LBLGROUPBUYER,Project_Project_Name,Project_Created_Time,Project_Status,Project_Start_Date,Project_Modified_Time,Project_Data_sospensione,Hold Sermicro Numero Project_Progetto_collegato,Hold Teknema Numero Project_Progetto_collegato,Hold test Numero Project_Progetto_collegato,on hold Numero Project_Progetto_collegato");
                                 $typef=explode(",","field,field,field,field,field,field,field,field,field,field,field,fieldaggr,fieldaggr,fieldaggr,fieldaggr");
$checkf=explode(",",",,,,,,,,,,,1,1,1,1");
                          
$dt=Array();
if("none"=="mv"){
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

		$rspots=$adb->query("SELECT REPLACE(CONCAT(),\"'\",\"\") as cols FROM $query $condd GROUP BY ");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["projectid"]=$j;
                      $coll=explode(",' %% ',","");
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
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and vtiger_project.projectstatus=\"$aggreg[0]\" $condd ");
       //$mainfld1=explode(",","vtiger_project.progetto"); 
       $fagg1=explode(",","Numero Project_Progetto_collegato");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_crmentityProject.createdtime,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_crmentityProject.createdtime,'%Y-%m-%d')<='$dttod' and vtiger_project.projectstatus=\"$cat[$m]\" $condd");
             
}
  
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Project_Status"));     


$ag=$adb->query_result($rspots1,0,"$mf");
$fie=preg_replace('/[^A-Za-z0-9]/u','',$fld[$m]);
 if(""=="1"){
$agcel=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["$fie"]=number_format($ag,2,",",".")." <br> ".number_format($agcel,2,",",".");
}
else { $content[$j]["$fie"]=number_format($ag,2,",","."); $formula[$j]["$fie"]=$ag;}
$ag1+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
$ag2+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");

                   
             }
               if($typef[$m]=="ytd" && $checkf[$m]==1){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_crmentityProject.createdtime,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_crmentityProject.createdtime,'%Y-%m-%d')<='$dttod' $condd");
   
$agy=$adb->query_result($rspots2,0,"$mf");
$content[$j]["$fie"]=number_format($agy,2,",",".");
$agy2+=$adb->query_result($rspots2,0,"$mf");       }
}
  
 
  if(""!=""){
      $f=explode("#","");
          for($ik=0;$ik<sizeof($f);$ik++){
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' $condd ");
$content[$j]["formula$ik"]=number_format($adb->query_result($rspots3,0,"form"),2,",",".");
$frm[$ik]+=$adb->query_result($rspots3,0,"form");}
}
  if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format(floatval($f1[$ik]),2,",",".");
      $fcols[$ik]+=$f1[$ik];
  }
}
    if(""==1)
         $content[$j]["totale"]=implode(" - ",$ag1);
  fputcsv($fp, $content[$j],";");
			$j++;
		}
                $content[$j]["projectid"]=$j;
                     $coll=explode(",' %% ',","");
                   
                          for($ccl=0;$ccl<sizeof($coll);$ccl++){
              $coll2=str_replace(array(".",",","_"),array("","",""),strtolower($coll[$ccl]));
           $content[$j]["$coll2"]="Totale";

                          }
          //$content[$j][""]="Totale";
 
        for($m=0;$m<sizeof($fld);$m++){
        $aggreg=explode("#",$fldaggreg[$m]);
        $mf=str_replace(array(".",",","_"),array("","",""),strtolower($aggreg[2]));
        if($typef[$m]=="fieldaggr" && $checkf[$m]==1){
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and vtiger_project.projectstatus=\"$aggreg[0]\" $condd ");

       $fagg1=explode(",","Numero Project_Progetto_collegato");
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Project_Status"));
 $fie=preg_replace('/[^A-Za-z0-9]/u','',$fld[$m]);

$ag=$adb->query_result($rspots1,0,"$mf");
$content[$j]["$fie"]=number_format($ag,2,",",".");
$formula[$j]["$fie"]=$ag;
} 
 if($typef[$m]=="ytd" && $checkf[$m]==1){
      //if(""=="1") 
          $content[$j]["$fie"]=number_format($agy2,2,",","."); }
     
if($m==sizeof($fld)-1){


   if(""!=""){    $f=explode("#","");
        for($ik=0;$ik<sizeof($f);$ik++){
       $content[$j]["formula$ik"]=number_format($frm[$ik],2,",",".");}}
       
if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format($fcols[$ik],2,",",".");
    
  }
}  
if(""==1)         
$content[$j]["totale"]=implode(" - ",$ag2); }
    }
    fputcsv($fp, $content[$j],";");
    fclose($fp);
               echo json_encode($content);}
              else echo json_encode("");

?>
