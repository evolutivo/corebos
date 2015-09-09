<?php 
    include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
    $type="";
$reportid=30;
$focus1=new ReportRun($reportid);

		$columnlist = $focus1->getQueryColumnsList($reportid,$type);
		$groupslist = $focus1->getGroupingList($reportid);
		//$groupTimeList = $focus1->getGroupByTimeList($reportid);
		$stdfilterlist = $focus1->getStdFilterList($reportid);
		$columnstotallist = $focus1->getColumnsTotal($reportid);
		$advfiltersql = $focus1->getAdvFilterSql($reportid);

		//$focus1->totallist = $columnstotallist;
		global $current_user;
		$tab_id = getTabid("Adocmaster");
		//Fix for ticket #4915.
		$selectlist = $columnlist;
		//columns list
		if(isset($selectlist))
		{
			$selectedcolumns =  implode(", ",$selectlist);
			if($chartReport == true){
				$selectedcolumns .= ", count(*) AS \"groupby_count\"";
			}
		}
		//groups list
		if(isset($groupslist))
		{
			$groupsquery = implode(", ",$groupslist);
		}
		//if(isset($groupTimeList)){
           	//$groupTimeQuery = implode(", ",$groupTimeList);
       // }

		//standard list
		if(isset($stdfilterlist))
		{
			$stdfiltersql = implode(", ",$stdfilterlist);
		}
		//columns to total list
		if(isset($columnstotallist))
		{
			$columnstotalsql = implode(", ",$columnstotallist);
		}
		if($stdfiltersql != "")
		{
			$wheresql = " and ".$stdfiltersql;
		}

		if(isset($filtersql) && $filtersql !== false) {
			$advfiltersql = $filtersql;
		}
		if($advfiltersql != "") {
			$wheresql .= " and ".$advfiltersql;
		}

		$reportquery = $focus1->getReportsQuery("Adocmaster", $type);

		$allColumnsRestricted = false;

		if($type == "COLUMNSTOTOTAL")
		{
			if($columnstotalsql != "")
			{
				$reportquery = "select ".$columnstotalsql." ".$reportquery." ".$wheresql;
			}
		}else
		{
			if($selectedcolumns == "") {

				$selectedcolumns = "''";
                                $allColumnsRestricted = true;
                        }
			if(in_array("Adocmaster", array("Invoice", "Quotes",
					"SalesOrder", "PurchaseOrder"))) {
				$selectedcolumns = " distinct ". $selectedcolumns;
			}
			$reportquery = "select DISTINCT ".$selectedcolumns." ".$reportquery." ".$wheresql;
		}
		$reportquery = listQueryNonAdminChange($reportquery, "Adocmaster");

		if(trim($groupsquery) != "" && $type !== "COLUMNSTOTOTAL")
		{
            if($chartReport == true){
                $reportquery .= "group by ".$focus1->GetFirstSortByField($reportid);
            }else{
                $reportquery .= " order by ".$groupsquery;
			}
		}

		// Prasad: No columns selected so limit the number of rows directly.
		if($allColumnsRestricted) {
			$reportquery .= " limit 0";
		}

		preg_match("/&amp;/", $reportquery, $matches);
        if(!empty($matches)){
            $report=str_replace("&amp;", "&", $reportquery);
            $reportquery = $focus1->replaceSpecialChar($report);
        }
        $rq=explode("from",$reportquery);
        if(""!="") $lim="Limit 0,";
        else $lim="";
        $query=$adb->query(strtolower($rq[0]." ,vtiger_adocmaster.adocmasterid as primid FROM ".$rq[1])." ".$lim);
        $count=$adb->num_rows($query);
$fld=explode(",","Adocmaster_Document_name,Adocmaster_Account,Adocmaster_Tipo_contratto_affiliazione,Adocmaster_Superiore_di_Struttura,Adocmaster_Storico_Livelli,Adocdetail_Totale");
    
for($i=0;$i<$count;$i++){
   $content[$i]["adocmasterid"]=$adb->query_result($query,$i,"primid");
              for($k=0;$k<sizeof($fld);$k++){
$name=strtolower($fld[$k]);
              $name1=str_replace(".","",str_replace("_","",strtolower($fld[$k])));
           if(strstr($name,"Assigned_To")){ $u=$adb->pquery("select * from vtiger_users where id=?",array($adb->query_result($query,$i,"$name")));
   if($adb->num_rows($u)!=0)
$us=getUsername($adb->query_result($query,$i,"$name"));
  else {$us1=getGroupname($adb->query_result($query,$i,"$name"));
          $us=$us1[0];
  }

   $content[$i]["$name1"]=$us;}
   else{
         if($adb->query_result($query,$i,"$name")==null) $s="";
          else $s=$adb->query_result($query,$i,"$name");
           $content[$i]["$name1"]=$s;}
       }
       }


echo json_encode($content);

?>