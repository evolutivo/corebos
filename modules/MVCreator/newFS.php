<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $adb,$log;
$tab = $_REQUEST['nometab'];
$reportid = $_REQUEST['reportId'];
$accins = $_REQUEST['accins'];
//
$accQuery = $adb->pquery("Select * from vtiger_accountinstallation
                         where accountinstallationid = ?",array($accins));

$dbname = $adb->query_result($accQuery,0,"dbname");
$acno = $adb->query_result($accQuery,0,"acin_no");
$port=$adb->query_result($accQuery,0,"port");
$ip=$adb->query_result($accQuery,0,"hostname");
$pass=$adb->query_result($accQuery,0,"password");
$us=$adb->query_result($accQuery,0,"username");
$path=$adb->query_result($accQuery,0,"vtigerpath");
$db = $dbname.$acno;
$id = str_replace(" ","",$reportid);
//generate php script

$ourFileName =$root_directory."script_report_".$id.$tab.".php";
$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file"); 

$data = "<?php \r\n";
$data.="global \$adb;\r\n";
$data.="\$current_user->id=1;\r\n";
$data.="include_once('modules/Reports/Reports.php');\r\n";
$data.="include('modules/Reports/ReportRun.php');\r\n";
$data.="include_once('include/utils/CommonUtils.php');\r\n";
$data.="\$tab='".$tab."';\r\n";
$data.="\$reportid='".$reportid."';\r\n";
//Query of the report
$data.="\$col1=Array();\r\n";
$data.="\$focus=new ReportRun(\$reportid);\r\n";
$data.="\$currencyfieldres = \$adb->pquery(\"SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)\", array());\r\n";
$data.="if(\$currencyfieldres) {\r\n";
$data.="foreach(\$currencyfieldres as \$currencyfieldrow) {\r\n";
$data.="\$modprefixedlabel = getTabModuleName(\$currencyfieldrow['tabid']).\" \".\$currencyfieldrow['fieldlabel'];\r\n";
$data.="\$modprefixedlabel = str_replace(' ','_',\$modprefixedlabel);\r\n";
$data.="if(\$currencyfieldrow['uitype']!=10){\r\n";
$data.="if(!in_array(\$modprefixedlabel, \$focus->convert_currency) && !in_array(\$modprefixedlabel, \$focus->append_currency_symbol_to_value)) {\r\n";
$data.="\$focus->convert_currency[] = \$modprefixedlabel;\r\n";
$data.="}\r\n";
$data.="} else {\r\n";
$data.="if(!in_array(\$modprefixedlabel, \$focus->ui10_fields)) {\r\n";
$data.="}\r\n";
$data.="}\r\n";
$data.="}\r\n";
$data.="}\r\n";

$data.="\$dataFromReport = \$adb->query(\$focus->sGetSQLforReport(\$reportid,\$nu));\r\n";
$data.="\$reportFields = array();\r\n";
$data.="\$reportFields = explode(\",\",\$focus->getSelectedColumnsList(\$reportid));\r\n";
$data.="for(\$j=0;\$j<count(\$reportFields);\$j++){\r\n";
$data.="\$field = \$reportFields[\$j];\r\n";
$data.="\$pos1 = strpos(\$field, \"AdocmasterNo\");\r\n";
$data.="if(\$pos1 !== false)\r\n";
$data.="{\r\n";
$data.="\$repadoccolumn = explode(\"'\", \$field);\r\n";
$data.="\$repadoclabel = \$repadoccolumn[1];\r\n";
$data.="break;\r\n";
$data.="}\r\n";
$data.="}\r\n";

//get adocMaster from the report 
$data.="\$AdocMaster = array();\r\n";
$data.="\$AdocMasterIDArr = array(); \r\n";
$data.="\$adocMaster = \$adb->query(\"select distinct \".\$repadoclabel.\" as RelatedAdocMaster  from (\".\$focus->sGetSQLforReport(\$reportid,\$nu).\") x\");\r\n";
$data.="\$nr_AdocMaster = \$adb->num_rows(\$adocMaster);\r\n";

$data.="for(\$j=0;\$j<\$nr_AdocMaster;\$j++){ \r\n";
    $data.="\$AdocMaster[\$j] = \$adb->query_result(\$adocMaster,\$j,0);\r\n";
$data.="}\r\n";

$data.="\$adocMasterNO = implode(\",\", \$AdocMaster);\r\n";
$data.="\$adoc = '\"'. implode('\",\"', explode(',', \$adocMasterNO)) .'\"';\r\n";
$data.="\$adocQuery = \$adb->query(\"Select adocmasterid from vtiger_adocmaster where adocmasterno in (\$adoc)\");\r\n";
$data.="for(\$j=0; \$j<\$adb->num_rows(\$adocQuery);\$j++){ \r\n";
$data.="\$AdocMasterIDArr[\$j] = \$adb->query_result(\$adocQuery,\$j,0);\r\n";
$data.="}\r\n";
$data.="\$AdocMasterID = implode(\",\", \$AdocMasterIDArr);\r\n";
$data.="\$col1=Array(); \r\n";
$data.="\$colonne=Array();\r\n";
//Table Columns
$data.="\$fieldsArr = array(\"productid\",\"name_of_bu\",\"productcategory\",\"quantity\",\"value\",\"prodottiperanalisi\",\"prodottiperbu\",\"directrisorsoumane\",\"indirectrisorsoumane\",\"costifunzionamento\");\r\n";
$data.="\$fieldsArrLabel = array(\"productid\",\"name_of_bu\", \"productcategory\",\"quantity\",\"value\",\"prodottiperanalisi\",\"prodottiperbu\",\"directrisorsoumane\",\"indirectrisorsoumane\",\"costifunzionamento\");\r\n";
$data.="for(\$i=0;\$i<count(\$fieldsArr);\$i++){ \r\n";
    $data.="\$colonne[\$i] =   \"\$fieldsArr[\$i] VARCHAR(250)\"; \r\n";
    $data.=" \$col1[\$i] = \$fieldsArrLabel[\$i] ; \r\n";
    $data.="} \r\n";
$data.="\$col=implode(\",\",\$colonne); \r\n";
$data.="\$colInput = implode(\",\", \$col1);\r\n";
$data.="\$adb->query(\"drop table IF EXISTS mv_\$reportid\$tab\"); \r\n";
$data.="\$adb->query(\"create table mv_\$reportid\$tab (\$col) ENGINE=InnoDB\"); \r\n";
$data.="\$totalRevenues = 0;\r\n";
$data.="\$totalHRINDIRECT = 0;\r\n";
$data.="\$BuInfo= Array(); \r\n";
$data.="\$indirectPercentage = Array();\r\n";
$data.="\$indirectCosts = Array();\r\n";

//Get all BU and all categoria text foreach BU
//$data.="\$queryResult= \$adb->query(\"Select business_unit,textanalysiscat from vtiger_adocdetail \r\n";
//                               $data.="join vtiger_crmentity crm on crm.crmid = vtiger_adocdetail.adocdetailid \r\n";
//                               $data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n";
//                               $data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster  \r\n"; 
//                               $data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
//                               $data.="join vtiger_crmentity crm2 on crm2.crmid = vtiger_products.productid \r\n";
//                               $data.="where crm.deleted = 0 and crm2.deleted = 0 and adoctomaster in (\$AdocMasterID) group by business_unit,textanalysiscat\"); \r\n";
//
//$data.="for(\$i=0; \$i < \$adb->num_rows(\$queryResult);\$i++){ \r\n";
//    $data.="\$BU = \$adb->query_result(\$queryResult,\$i,'business_unit'); \r\n";
//    $data.="\$textanalysiscat= \$adb->query_result(\$queryResult,\$i,'textanalysiscat'); \r\n";
//    $data.="\$BuArr = explode(\",\",\$BU); \r\n";
//    $data.="for(\$k=0; \$k <count(\$BuArr); \$k++){ \r\n";
//    $data.="\$BuName = \$BuArr[\$k]; \r\n";
//    $data.="if(\$BuName != \"\"){ \r\n";
//    $data.="\$BuInfo[\$BuName][\$textanalysiscat][0] =  '\"'.\$BuName.'\"'; \r\n";
//    $data.="\$BuInfo[\$BuName][\$textanalysiscat][1] = (\$textanalysiscat != \"\" ? '\"'.\$textanalysiscat.'\"' : '\"\"'); \r\n";
//    $data.="for(\$j=2; \$j < count(\$col1);\$j++)  \r\n";
//               $data.="\$BuInfo[\$BuName][\$textanalysiscat][\$j] = 0; \r\n"; 
//    $data.="} \r\n";
//    $data.="}\r\n";
//$data.="}\r\n";
//REVENUES (adocmaster Fattura Attiva)
//Get all BU and all categoria text foreach BU
 $data.="\$queryRevenueResult= \$adb->query(\"Select business_unit,textanalysiscat,productid,sum(adoc_quantity) As Quantity,sum(vtiger_adocdetail.adocdtotal) As Price from vtiger_adocdetail \r\n";
                                       $data.="join vtiger_crmentity crm on crm.crmid = vtiger_adocdetail.adocdetailid \r\n";
                                       $data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n";
                                       $data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster \r\n";
                                       $data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
                                       $data.="join vtiger_crmentity crm2 on crm2.crmid = vtiger_products.productid \r\n";
                                       $data.="where crm.deleted = 0 and crm2.deleted = 0 and  adoctomaster in (\$AdocMasterID) and doctype='Fatture attive' group by business_unit,textanalysiscat,productid\"); \r\n";
$data.="\$nr_BU = \$adb->num_rows(\$queryRevenueResult); \r\n";
$data.="\$totalPriceOfBU = array(); \r\n";
$data.="\$totalCountOfBU = array(); \r\n";
//Direct REVENUES
$data.="for(\$j=0;\$j<\$nr_BU;\$j++){ \r\n";
    $data.="\$BU = \$adb->query_result(\$queryRevenueResult,\$j,'business_unit'); \r\n";
    $data.="\$textanalysiscat= \$adb->query_result(\$queryRevenueResult,\$j,'textanalysiscat'); \r\n";
    $data.="\$procuctId = \$adb->query_result(\$queryRevenueResult,\$j,'productid'); \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][0] =  \$procuctId; \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][1] =  '\"'.\$BU.'\"'; \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][2] = (\$textanalysiscat != \"\" ? '\"'.\$textanalysiscat.'\"' : '\"\"'); \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][3] = (\$adb->query_result(\$queryRevenueResult,\$j,3)!= \"\" ? \$adb->query_result(\$queryRevenueResult,\$j,3):0); \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][4] = (\$adb->query_result(\$queryRevenueResult,\$j,4) !=\"\" ? \$adb->query_result(\$queryRevenueResult,\$j,4) : 0); \r\n"; 
    $data.="\$totalPriceOfBU[\$BU] += \$BuInfo[\$BU][\$textanalysiscat][4] ; \r\n";
    $data.="\$totalRevenues += \$BuInfo[\$BU][\$textanalysiscat][4] ;\r\n";
    $data.="\$totalCountOfBU[\$BU] += \$BuInfo[\$BU][\$textanalysiscat][3]; \r\n";  
    $data.="for(\$k=5; \$k < count(\$col1);\$k++)  \r\n";
               $data.="\$BuInfo[\$BU][\$textanalysiscat][\$k] = 0; \r\n"; 
$data.="} \r\n";

//COSTS (adocmaster Fatture Passive) Prodotti per analisi search for products of adocdetails that have products
//Prodotti per Analisi they will have only one BU and one categoria text
$data.="\$queryResultProdotiAnalisi= \$adb->query(\"Select business_unit,textanalysiscat, sum(vtiger_adocdetail.adocdtotal) As Price from vtiger_adocdetail \r\n";
                                         $data.="join vtiger_crmentity crm on crm.crmid = vtiger_adocdetail.adocdetailid \r\n";
                                         $data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n"; 
                                         $data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster   \r\n";
                                         $data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
                                         $data.="join vtiger_crmentity crm2 on crm2.crmid = vtiger_products.productid \r\n";
                                         $data.="where crm.deleted = 0 and crm2.deleted = 0  and adoctomaster in (\$AdocMasterID) and doctype='Fatture passive'and productcategory='Prodotti per analisi' \r\n";
                                         $data.="group by business_unit,textanalysiscat\"); \r\n";

//Direct COSTS
$data.="if(\$adb->num_rows(\$queryResultProdotiAnalisi) != 0){ \r\n";
$data.="for(\$j=0;\$j<\$adb->num_rows(\$queryResultProdotiAnalisi);\$j++){ \r\n";
    $data.="\$BU = \$adb->query_result(\$queryResultProdotiAnalisi,\$j,'business_unit'); \r\n";
    $data.="\$textanalysiscat= \$adb->query_result(\$queryResultProdotiAnalisi,\$j,'textanalysiscat'); \r\n";
    $data.="\$BuInfo[\$BU][\$textanalysiscat][5] = \$adb->query_result(\$queryResultProdotiAnalisi,\$j,2);\r\n";
 $data.="}\r\n";
$data.="}\r\n";
//DIRECT COSTS  HAVE ONE BU AND SPLIT BETWEEN PRODUCTCATEGORY IS DONE BASED ON QUANTITY OF EACH PRODUCT CATEGORY
 $data.="\$queryResultProdotiBu = \$adb->query(\"Select business_unit,sum(vtiger_adocdetail.adocdtotal) As Price from vtiger_adocdetail \r\n";
                                     $data.="join vtiger_crmentity crm on crm.crmid = vtiger_adocdetail.adocdetailid \r\n";
                                     $data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n";
                                     $data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster  \r\n";
                                     $data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
                                     $data.="join vtiger_crmentity crm2 on crm2.crmid = vtiger_products.productid \r\n";
                                     $data.="where crm.deleted = 0 and crm2.deleted = 0 and adoctomaster in (\$AdocMasterID) and doctype='Fatture passive' and productcategory='Prodotti per BU'  \r\n";
                                     $data.="group by business_unit\");\r\n";

 $data.="if(\$adb->num_rows(\$queryResultProdotiBu) != 0){ \r\n";
     $data.="for(\$j=0;\$j<\$adb->num_rows(\$queryResultProdotiBu) ;\$j++){ \r\n";
       $data.="\$BU = \$adb->query_result(\$queryResultProdotiBu,\$j,'business_unit'); \r\n";
       $data.="\$price = (\$adb->query_result(\$queryResultProdotiBu,\$j,1) != \"\" ? \$adb->query_result(\$queryResultProdotiBu,\$j,1) : 0);\r\n";
       //Split Between prductCategory Of BU
       $data.="foreach(\$BuInfo[\$BU] as \$key=> \$value){\r\n";
          $data.="\$BuInfo[\$BU][\$key][6] = round((\$value[3]/\$totalCountOfBU[\$BU])* \$price,3);\r\n";
       $data.="} \r\n";
     $data.="}\r\n";
  $data.="}\r\n";

$data.="\$queryResult4= \$adb->query(\"Select business_unit,vtiger_adocdetail.adocdtotal As Price,productid from vtiger_adocdetail \r\n";
$data.="join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_adocdetail.adocdetailid \r\n";
$data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n";
$data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster  \r\n";
$data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
$data.="where deleted = 0 and adoctomaster in (\$AdocMasterID) and productcategory='risorsa umana' \r\n";
$data.="\");\r\n";

$data.="if(\$adb->num_rows(\$queryResult4) != 0){ \r\n";
$data.="for(\$j=0;\$j<\$adb->num_rows(\$queryResult4);\$j++){ \r\n";
$data.="\$BuNameConcat = \$adb->query_result(\$queryResult4,\$j,'business_unit'); \r\n";
$data.="\$BuName = explode(\",\",\$BuNameConcat); \r\n";
$data.="\$totalPrice = (\$adb->query_result(\$queryResult4,\$j,1)!= \"\" ? \$adb->query_result(\$queryResult4,\$j,1) : 0); \r\n";
$data.="if(empty(\$BuNameConcat)){ \r\n";
    //COSTI FUNZIONAMENTO INDIRECT
    $data.="\$log->debug(\"Ketu BU = \$BuNameConcat \".\$totalPrice);\r\n";
    $data.="\$totalHRINDIRECT = \$totalHRINDIRECT + \$totalPrice; \r\n";
$data.="}\r\n";
$data.="else if(count(\$BuName) == 1){ \r\n";
    //Split the costs between categories based on qty since its a direct cost 
$data.="foreach(\$BuInfo[\$BuNameConcat] as \$key=> \$value){ \r\n";
$data.="\$BuInfo[\$BuNameConcat][\$key][7] += round((\$value[3]/\$totalCountOfBU[\$BuNameConcat])* \$totalPrice,2);\r\n";
$data.="} \r\n";
$data.="} \r\n";
$data.="else {\r\n";
    //Split between BU-s based on working percentage
$data.="\$prodId = \$adb->query_result(\$queryResult4,\$j,'productid'); \r\n";
$data.="\$prodDetailQuery = \$adb->pquery(\"Select workingpercentage, buproductdetail from vtiger_productdetail \r\n";
$data.="join vtiger_products on vtiger_products.productid =  vtiger_productdetail.linktoproduct \r\n";
$data.="join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_productdetail.productdetailid \r\n";
$data.="where  deleted = 0 and productid = ?\",array(\$prodId)); \r\n";
$data.="\$nrProdDetail = \$adb->num_rows(\$prodDetailQuery); \r\n";
$data.="for(\$counter = 0; \$counter < \$nrProdDetail; \$counter++){ \r\n";
$data.="\$buproductdetail = \$adb->query_result(\$prodDetailQuery,\$counter,'buproductdetail'); \r\n";
$data.="\$workingpercentage = \$adb->query_result(\$prodDetailQuery,\$counter,'workingpercentage')/100;  \r\n";
$data.="foreach(\$BuInfo[\$buproductdetail] as \$textanalysis=> \$value){ \r\n";
$data.="\$BuInfo[\$buproductdetail][\$textanalysis][7] +=  round((\$value[4]/\$totalPriceOfBU[\$buproductdetail]) * \$workingpercentage * \$totalPrice,2); \r\n";
$data.="}\r\n";
$data.="}\r\n";
$data.="}\r\n";
$data.="}\r\n";
$data.="}\r\n";
//COSTI FUNZIONAMENTO
$data.="\$queryResult3= \$adb->query(\"Select sum(vtiger_adocdetail.adocdtotal) As Price from vtiger_adocdetail \r\n";
$data.="join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_adocdetail.adocdetailid \r\n";
$data.="join vtiger_financialsettings on vtiger_financialsettings.financialsettingsid = vtiger_adocdetail.linktofinsett \r\n";
$data.="join vtiger_adocmaster on vtiger_adocmaster.adocmasterid = vtiger_adocdetail.adoctomaster  \r\n";
$data.="join vtiger_products on vtiger_products.productid = vtiger_adocdetail.adoc_product \r\n";
$data.="where deleted = 0 and adoctomaster in (\$AdocMasterID) and doctype='Fatture passive' and productcategory='Costi funzionamento'\"); \r\n";
$data.="if(\$adb->num_rows(\$queryResult3) != 0){ \r\n";
$data.="\$totalPrice = \$adb->query_result(\$queryResult3,0,0);\r\n";
$data.="}\r\n";

$data.="foreach(\$BuInfo as \$bu => \$textanalysis){\r\n";
//SPLIT COSTI FUNZIONAMENTO
    $data.="\$totalOfBU = round((\$totalPriceOfBU[\$bu]/\$totalRevenues)*\$totalPrice,3);\r\n";
    $data.="\$log->debug(\"BU  \$bu = \".\$totalOfBU .\" HAS A TOTAL REVENUE OF \$totalPriceOfBU[\$bu] AND THE TOATL OF REVENUES IS \$totalRevenues\n\"); \r\n";
    $data.="\$log->debug(\"TOTAL COSTI FUNZIONAMENTI OF BU  \$bu = \".\$totalOfBU .\"\n\"); \r\n";
    $data.="\$totalOfBUHR = round((\$totalPriceOfBU[\$bu]/\$totalRevenues)*\$totalHRINDIRECT,3); \r\n";
$data.="foreach(\$textanalysis as \$key=> \$value){  \r\n";
    $data.="\$BuInfo[\$bu][\$key][9] =  round((\$value[4]/\$totalPriceOfBU[\$bu])*\$totalOfBU,2); \r\n";
    $data.="\$BuInfo[\$bu][\$key][8] =  round((\$value[4]/\$totalPriceOfBU[\$bu])*\$totalOfBUHR,2); \r\n";
    $data.="\$log->debug( \"TOTAL COSTI FUNZIONAMENTI OF PRODUCT CATEGORY \$key OF BU  \$bu = \".\$BuInfo[\$bu][\$key][9].\" IT IS DONE BASED ON THE REVENUES OF CATEGORIE = \$value[4]\n\"); \r\n";
$data.="\$row = implode(\",\",\$BuInfo[\$bu][\$key]); \r\n";
$data.="\$insertInfo = \$adb->query(\"Insert into mv_\$reportid\$tab(\$colInput)  values(\$row)\"); \r\n";
$data.="} \r\n";
$data.="} \r\n";
$data.="\$adb->pquery(\"ALTER TABLE mv_\$reportid\$tab
                        ADD COLUMN ID INT NOT NULL AUTO_INCREMENT FIRST,
                        ADD PRIMARY KEY (ID)\");\r\n";
//Add primary key column
fwrite($ourFileHandle,$data);
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

if(!($con = ssh2_connect($ip, $port))){
  echo "fail: unable to establish connection\n";

} else { echo 'Connected';
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, $us, $pass)) {
        $msgc = $mod_strings["faila"];
    } else {
        $msgc = $mod_strings["succ"];
        $serv = $path."/modules/BiServer/Reports/script_report_".$id.$tab.".php";
        $rootPath = $path."/script_report_".$id.$tab.".php";        
        ssh2_scp_send($con,$ourFileName ,$serv , 0777);
     //ssh2_scp_send($con,$ourFileName ,$rootPath , 0777);
    //$stream = ssh2_exec($con, "cd $path; php script_report_$id$tab.php");
       stream_set_blocking($stream, true);
       $lang1=stream_get_contents($stream);
    }
}  


