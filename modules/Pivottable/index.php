<!DOCTYPE html>
<html>
    <head>
<link rel="stylesheet" type="text/css" href="modules/Pivottable/pivottable-master/examples/pivot.css">
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/d3.v3.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery-1.8.3.min.js"></script>
       
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery.csv-0.71.min.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/pivot.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/gchart_renderers.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/d3_renderers.js"></script>
     
    
    </head>
    <style>
        * {font-family: Verdana;}
        .node {
          border: solid 1px white;
          font: 10px sans-serif;
          line-height: 12px;
          overflow: hidden;
          position: absolute;
          text-indent: 2px;
        }
    </style>
    <body>
        <?php
echo '<form method="POST" action="">
        <input type="submit" name="sub" value="Refresh">    </form>';
        if(isset($_REQUEST['sub'])){
include_once("modules/Pivottable/pivotfunc.php");
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
global $adb,$current_user,$php_max_execution_time;
//$reportid='1';
//$filtersql=false;
//
//$focus=new ReportRun($reportid);
//		global $modules,$app_strings;
//		global $mod_strings,$current_language;
//		require('user_privileges/user_privileges_'.$current_user->id.'.php');
//		$modules_selected = array();
//		$modules_selected[] = $focus->primarymodule;
//		if(!empty($focus->secondarymodule)){
//			$sec_modules = explode(":",$focus->secondarymodule);
//			for($i=0;$i<count($sec_modules);$i++){
//				$modules_selected[] = $sec_modules[$i];
//			}
//		}
//		// Update Reference fields list list
//		$referencefieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (10,101)", array());
//		if($referencefieldres) {
//			foreach($referencefieldres as $referencefieldrow) {
//				$uiType = $referencefieldrow['uitype'];
//				$modprefixedlabel = getTabModuleName($referencefieldrow['tabid']).' '.$referencefieldrow['fieldlabel'];
//				$modprefixedlabel = str_replace(' ','_',$modprefixedlabel);
//
//				if($uiType == 10 && !in_array($modprefixedlabel, $focus->ui10_fields)) {
//					$focus->ui10_fields[] = $modprefixedlabel;
//				} elseif($uiType == 101 && !in_array($modprefixedlabel, $focus->ui101_fields)) {
//					$focus->ui101_fields[] = $modprefixedlabel;
//				}
//			}
//		}
//
//	
//			$sSQL = explode("from",$focus->sGetSQLforReport($reportid,$filtersql),2);
//		 $rows = array();
//$sSQL1=explode(",",str_replace("select DISTINCT","",$sSQL[0]));
//for($i=0;$i<sizeof($sSQL1);$i++){
//if(!strstr($sSQL1[$i],"vtiger_crmentity.crmid AS '"))
//{$arr[$j]=$sSQL1[$i];
//$j++;
//}
//}
//$sSQL="select DISTINCT ".implode(",",$arr)." from ".$sSQL[1];
$indextype='adocdetailec899b8';
 $query=sqltojson($indextype,$reportid);
//echo $query;
  createjson($query);
}        ?>
        <script type="text/javascript">
                        google.load("visualization", "1", {packages:["corechart", "charteditor"]});

           $(function(){
                var derivers = $.pivotUtilities.derivers;

                $.getJSON("report.json", function(mps) {
                    $("#output").pivotUI(mps, { 
   renderers: $.extend(
                            $.pivotUtilities.renderers, 
                            $.pivotUtilities.gchart_renderers, 
                            $.pivotUtilities.d3_renderers
                            ),
//                        derivedAttributes: {
//                            "Age Bin": derivers.bin("adocdetail_price", 10),
//                            "Gender Imbalance": function(mp) {
//                                return mp["Gender"] == "Male" ? 1 : -1;
//                            }
//                        },
                        rows: ["adocdetailnameAdocdetail"],
                        //cols: ["productnameProducts1"],
cols:[""],                        
rendererName: "Table"
                    });
                });
             });
        </script>

        
        <div id="output" style="margin: 30px;"></div>

    </body>
</html>
