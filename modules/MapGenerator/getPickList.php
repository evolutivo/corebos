<?php
//SecondModuleMasterDetail.php

include 'XmlContent.php';
include 'modfields.php';

$mm =$_REQUEST['mod'];
$arrayName = array();
echo getModFields($mm,"",$arrayName,"15,33");
?>