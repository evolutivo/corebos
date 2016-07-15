<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include ('modules/OpenStreetMap/lib/GeoCoder.inc.php');
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$url = 'index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=index&parenttab=Marketing';
$gc = new GeoCoder();
$json = $gc->reverseGeocoding($lat,$lng);
$json = json_decode($json,true);

$address = $json["address"];
foreach ($address as $key => $value){
$componentvalue = $value;
$componenttype = $key;
switch($componenttype){
    case 'street_number':
     $componenttype = 'bill_pobox';
     break;
    case 'road':
     $componenttype = 'bill_street';
     break;
    case 'city':
      $componenttype = 'bill_city';
     break;
    case 'state':
     $componenttype = 'bill_state';
     break;
    case 'country':
     $componenttype = 'bill_country';
     break;
    case 'postcode':
     $componenttype = 'bill_code';
     break;
}
$url .= "&".$componenttype."=".$componentvalue;
}
echo $url;
var_dump($address);
?>
