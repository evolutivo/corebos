<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include ('modules/OpenStreetMap/lib/GeoCoder.inc.php');
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$gc = new GeoCoder();
$json = $gc->reverseGeocoding($lat,$lng);
$address = json_decode($json,true);
echo $address['address']['city'].';'.$address['address']['country'];
?>
