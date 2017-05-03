<?php

global $log;
$log->debug("every thing start from here  ");
include_once("modules/cbMap/cbMap.php");
$focust = new cbMap();
$log->debug("create a new instance for cbMap  ");
if (isset($_POST["nameView"]) && isset($_POST["QueryGenerate"])){
  try {

      $log->debug("the field from post are not empty ");
      //$stringaselField1 = $_POST['selField1'];//stringa con tutte i campi scelti in selField1
      $queryGenerate = $_POST['QueryGenerate'];//stringa con tutte i campi scelti in selField1
      $nameView = $_POST['nameView'];//nome della vista
      //echo "value are not empty";
      $focust->column_fields['assigned_user_id'] = 1;
      $focust->column_fields['mapname'] = $nameView;
      $focust->column_fields['content']=$queryGenerate;
      $focust->column_fields['description'] = $queryGenerate;
      $focust->column_fields['maptype'] = "SQL";
      //echo "know we inicialize value for insert in database";
      $log->debug("now we inicialize value for insert in database ");

      if (!$focust->saveentity("cbMap")) {
          echo "success!!! The map is created.";
          $log->debug("succes!! the map is created ");
      } else {
          echo "Error!!! something went wrong.";
          $log->debug("Error!! something went wrong");
      }
  }catch (Exception $e){
      $log->error("errori nga catch "+$e->getMessage());
    }
}
else{
    echo "value are empty";
    $log->debug("value from post are  empty");
}

?>