<?php 
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
    $grp=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

 $group=array();
  $k=0;
  $fld=explode(",","projecttaskid,projecttaskname,projecttask_no,projecttasktype,projecttaskpriority,projecttaskprogress,projecttaskhours,vtiger_projecttask_startdate,enddate,vtiger_projecttask_projectid,projecttasknumber,vtiger_projecttask_totalpaid,vtiger_projecttask_totalreceived,vtiger_projecttask_estimatedrevenue,targetbudget,vtiger_projecttask_estimatedtime,vtiger_projecttask_differencetime,order_level,status_pk,substatus,costoorario,costounit,costooffsite,costounitoffsite,status_pt,sla,workflow,operazione,tcname,substatus_pt,vtiger_project_projectid,projectname,project_no,vtiger_project_startdate,targetenddate,actualenddate,projectstatus,projectpriority,projecttype,linktoaccountscontacts,vtiger_project_totalpaid,vtiger_project_totalreceived,vtiger_project_estimatedrevenue,totaltime,vtiger_project_estimatedtime,vtiger_project_differencetime,current_level,template,brand,model_type,warranty,flag_extra_invoice,forfait_swap,refurbishing,swap_new,send_cortesy_product,send_package,customer_accept_swap,os_password,bios_password,acessories_note,external_defect_description,external_defect_incoming,serno,incoming_document_data,incoming_document_number,project_id,product_id,contact_id,account_account_id,customer_purchase_data,brand_id,brand_ref_number,link_to_project,iris_symptom_code,iris_condition_code,status,ticket_satus,ticket_severities,operation_type,warranty_type,sla_delivery,serial_number,accessoriconsegnati01,accessoriconsegnati02,accessoriconsegnati03,accessoriconsegnati04,accessoriconsegnati05,accessoriconsegnati06,accessoriconsegnati07,accessoriconsegnati08,accessoriconsegnati09,accessoriconsegnati10,swapprodottonuovo,difettiesterni,inviobox,sintomocliente,condizionicliente,alimentatore,cavoalimentazione,lettorefdd,borsa,mouse,schedapcmcia,lettorecd,telecomando,manuali,batteria,altroaccessorio,accettazionemodellosostitutivo,estimate_internal_time,on_site,spedprodcortesia,products,linktobuyer,linktocondition,linktosymptom,progetto,chiave_projectid,substatusproj,informazioniarrivo,contacts,attivata,reforder,model_number,rma,tipoaviso,difettodichiarato,dayson,lavorato,data_attivazione_corriere,prevista_consegna,note_corriere,distributor,presenza_parti,aperturasla,chiusurasla,giornisla2,verificasla,verificatosla,statopartech,statolavorazione,statuslavor,logsla,giorniapertura,datasospensione,datepartrequest,raggruppamento_giorni,ragg_giorni_wait,accessorieslist,ship_codepr,ship_citypr,dateshipped,ship_streetpr");

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
                $qr=strtolower("select $fields .id as primid FROM ".$rq[1]." group by $group2");
}
        else{
        $qr=strtolower($rq[0]." ,.id as primid FROM ".$rq[1]);}
            }
        
          else {
              $query1="show columns from mv_test";


	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;
       $fname1=str_replace("id","",$fname);
       $tab=$adb->query("select * from vtiger_tab where name like \"$fname1\"");
       if($adb->num_rows($tab)!=0)
       $md1=$adb->query_result($tab,0,"name");
}
}}

          $qr="select $fname as primid,mv_test.* from mv_test ";
            }
            if("100"!="") $lim="Limit 0,100";
        else $lim="";
              if($_REQUEST["condit"]!="") $co=$_REQUEST["condit"];
else $co="";
        $query=$adb->query($qr." and  status_pk='Open' ".$co." ".$lim);
          
        $count=$adb->num_rows($query);
$fld=explode(",","projecttaskid,projecttaskname,projecttask_no,projecttasktype,projecttaskpriority,projecttaskprogress,projecttaskhours,vtiger_projecttask_startdate,enddate,vtiger_projecttask_projectid,projecttasknumber,vtiger_projecttask_totalpaid,vtiger_projecttask_totalreceived,vtiger_projecttask_estimatedrevenue,targetbudget,vtiger_projecttask_estimatedtime,vtiger_projecttask_differencetime,order_level,status_pk,substatus,costoorario,costounit,costooffsite,costounitoffsite,status_pt,sla,workflow,operazione,tcname,substatus_pt,vtiger_project_projectid,projectname,project_no,vtiger_project_startdate,targetenddate,actualenddate,projectstatus,projectpriority,projecttype,linktoaccountscontacts,vtiger_project_totalpaid,vtiger_project_totalreceived,vtiger_project_estimatedrevenue,totaltime,vtiger_project_estimatedtime,vtiger_project_differencetime,current_level,template,brand,model_type,warranty,flag_extra_invoice,forfait_swap,refurbishing,swap_new,send_cortesy_product,send_package,customer_accept_swap,os_password,bios_password,acessories_note,external_defect_description,external_defect_incoming,serno,incoming_document_data,incoming_document_number,project_id,product_id,contact_id,account_account_id,customer_purchase_data,brand_id,brand_ref_number,link_to_project,iris_symptom_code,iris_condition_code,status,ticket_satus,ticket_severities,operation_type,warranty_type,sla_delivery,serial_number,accessoriconsegnati01,accessoriconsegnati02,accessoriconsegnati03,accessoriconsegnati04,accessoriconsegnati05,accessoriconsegnati06,accessoriconsegnati07,accessoriconsegnati08,accessoriconsegnati09,accessoriconsegnati10,swapprodottonuovo,difettiesterni,inviobox,sintomocliente,condizionicliente,alimentatore,cavoalimentazione,lettorefdd,borsa,mouse,schedapcmcia,lettorecd,telecomando,manuali,batteria,altroaccessorio,accettazionemodellosostitutivo,estimate_internal_time,on_site,spedprodcortesia,products,linktobuyer,linktocondition,linktosymptom,progetto,chiave_projectid,substatusproj,informazioniarrivo,contacts,attivata,reforder,model_number,rma,tipoaviso,difettodichiarato,dayson,lavorato,data_attivazione_corriere,prevista_consegna,note_corriere,distributor,presenza_parti,aperturasla,chiusurasla,giornisla2,verificasla,verificatosla,statopartech,statolavorazione,statuslavor,logsla,giorniapertura,datasospensione,datepartrequest,raggruppamento_giorni,ragg_giorni_wait,accessorieslist,ship_codepr,ship_citypr,dateshipped,ship_streetpr");
    $ch=explode(",","");
    if($count!=0){
for($i=0;$i<$count;$i++){
   $content[$i]["id"]=$adb->query_result($query,$i,"primid");
              for($k=0;$k<sizeof($fld);$k++){
$name=strtolower($fld[$k]);
$namec=$fld[$k];
$j=$ch[$k];
              $name1=str_replace(".","",str_replace("_","",strtolower($fld[$k])));
           if(strstr($name,"Assigned_To")){ $u=$adb->pquery("select * from vtiger_users where id=?",array($adb->query_result($query,$i,"$name")));
   if($adb->num_rows($u)!=0)
$us=getUsername($adb->query_result($query,$i,"$name"));
  else {$us1=getGroupname($adb->query_result($query,$i,"$name"));
          $us=$us1[0];
  }

   $content[$i]["$name1"]=$us;}
      else if (in_array($namec,$focus1->ui10_fields)) {


								$type = getSalesEntityType($adb->query_result($query,$i,"$name"));
								$tmp =getEntityName($type,$adb->query_result($query,$i,"$name"));
								if (is_array($tmp)){
									foreach($tmp as $key=>$val){
                                                                       if($val==null) $val="";
										  $content[$i]["$name1"] = $val;

										break;
									}
								}}
   else{
         if($adb->query_result($query,$i,"$name")==null) $s="";
          else $s=$adb->query_result($query,$i,"$name");
           $content[$i]["$name1"]=$s;}
       }
       if(""!="")
       $md1="";
         
       $content[$i]["url"]="index.php?module=$md1&action=DetailView&record=". $content[$i]["id"];
       }


echo json_encode($content);}
else echo json_encode("");

?>