<?php
ini_set('display_errors','on');
include_once('data/CRMEntity.php');
include_once('modules/Project/crXml.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
global $adb,$log,$current_user;

$recordid=$_REQUEST['recordid'];
$map=$_REQUEST['map'];
$map=<<<EOB
<?xml version="1.0" encoding="UTF-8"?> 
 <map> 
  <name>mymap</name> 
  <targetmodule> 
  
  <targetid></targetid> 
  <targetname>ProjectTask</targetname> 
  </targetmodule> 
  <originmodule> 
  
  <originid></originid> 
  <originname>Project</originname> 
  </originmodule> 
  <fields> 
  <field> 
  <fieldid></fieldid> 
  <fieldname>parent_id</fieldname> 
  
  <Orgfields> 
  <Orgfield> 
  <Orgfieldid></Orgfieldid> 
  <OrgfieldName>projectid</OrgfieldName> 
  <delimiter></delimiter> 
  </Orgfield> 
  <RelatedFields> 
  <relid></relid> 
  <relname></relname> 
  </RelatedFields> 
  </Orgfields> 
  </field> 
  </fields> 
 </map>
EOB;
$x = new crXml();
//$xml=  file_get_contents("modules/Project/map2.xml");
$x->loadXML($map);
$module=(string)$x->map->targetmodule[0]->targetname;
$return_module=(string)$x->map->originmodule[0]->originname;
$target_fields=array();
$index=0;
foreach($x->map->fields[0] as $k=>$v) {
    $fieldname=  (string)$v->fieldname;
    $allmergeFields=array();
    foreach($v->Orgfields[0]->Orgfield as $key=>$value) {
      // echo $fk;
       if($key=='OrgfieldName')
          $allmergeFields[]=(string)$value;
       if($key=='delimiter')
           $target_fields[$fieldname]['delimiter']=(string)$value;
    }
    $target_fields[$fieldname]['merge']=$allmergeFields;
// $index++;      
   }
//var_dump($target_fields);
//exit;

include_once ("modules/$module/$module.php");
include_once ("modules/$return_module/$return_module.php");

$focus=  CRMEntity::getInstance($module);
$focus2=CRMEntity::getInstance($return_module);
$table=$focus2->table_name;
$index=$focus2->table_index;
  $allparameters=array();
foreach ($target_fields as $field=>$elements){
    $fieldname=$field; 
   // echo $fieldname;
   // echo $fieldname." ";
  
      
    foreach ($elements as $key=>$value){
        if($key=='delimiter'){
            $delimiter=(string)$value;
            if (empty($delimiter))
                $delimiter="";
        }
        if($key=='merge'){
            $allfields= implode(",",$value);
          $foundValues=array();
           $focus2Query=$adb->pquery("SELECT $allfields 
                          FROM $table
                          INNER JOIN vtiger_crmentity ce ON ce.crmid=$table.$index
                          WHERE ce.deleted=0 AND $table.$index=?",array($recordid));
            $selfieds=explode(",",$allfields);
            for($i=0;$i<count($selfieds);$i++){
                //echo $adb->query_result($focus2Query,0,$selfieds[$i]);
                $foundValues[]=$adb->query_result($focus2Query,0,$selfieds[$i]);
            }
            
        }
    }
    
        $allparameters[] = $fieldname."=".implode("$delimiter", $foundValues);
//var_dump($foundValues);
  
    
}
$fixedparams=implode("&",$allparameters);

echo "index.php?module=$module&action=EditView&return_module=$return_module&return_action=DetailView&return_id=$recordid&$fixedparams";

?>
