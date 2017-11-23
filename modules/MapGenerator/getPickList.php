<?php
//SecondModuleMasterDetail.php

include 'XmlContent.php';
include 'All_functions.php';

$mm =$_REQUEST['mod'];
$arrayName = array();
echo GetModulRelOneTomulti($mm,$arrayName,"15,33");
?>