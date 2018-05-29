<?php
global $adb;

$ids = $_REQUEST['ids'];
$tree = $_REQUEST['tree'];
$result = array();
$ids_string = implode(',', $ids);
$query = "select * from vtiger_preguntas p LEFT JOIN vtiger_treesrel tr ON tr.crmid=p.preguntasid LEFT JOIN vtiger_trees t ON t.treesid=tr.parentid where tr.treesid=$tree AND preguntasid in ({$ids_string}) ORDER BY t.weight ASC, tr.weight ASC";
$res = $adb->query($query);
while ($row=$adb->getNextRow($res, false)) {
  $result[] = $row;
}
echo json_encode($result);

?>
