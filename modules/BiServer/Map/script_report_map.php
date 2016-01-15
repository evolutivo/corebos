<?php
global $adb;
$current_user->id=1 ;
include_once("include/utils/CommonUtils.php");
require_once('include/database/PearDatabase.php');  
require_once("include/utils/utils.php"); 
require_once('vtlib/Vtiger/Module.php'); 

//get map query 
$SQLforMap = $adb->pquery("Select content from vtiger_cbmap where cbmapid = ?",array(13315350));
$mapSql = str_replace('"','',html_entity_decode($adb->query_result($SQLforMap,0,"content"),ENT_QUOTES));
$fromQueryPart = explode("FROM",$mapSql);
//CREATING MV TABLE
$adb->pquery("drop table IF EXISTS  mv_map"); 
$q1 = $adb->query("create table mv_map AS Select vtiger_project_projectid,vtiger_project_annomese,vtiger_project_substatus,vtiger_project_praticaid,vtiger_project_progressiveauth,vtiger_project_valoreprodotto,vtiger_project_purchasecostdetail,vtiger_crmentity_createdtime,vtiger_processtemplate_processtemplatename,vtiger_supplier_suppliername,vtiger_vendor_vendorname,vtiger_productfamily_productfamily_name,vtiger_productscategory_de_productscategory,vtiger_productssubcategory_cd_productssubcategory,vtiger_regionalareaservicecenter_regionalascname From (SELECT vtiger_project.projectid AS vtiger_project_projectid,vtiger_project.annomese AS vtiger_project_annomese,vtiger_project.substatus AS vtiger_project_substatus,vtiger_project.praticaid AS vtiger_project_praticaid,vtiger_project.progressiveauth AS vtiger_project_progressiveauth,vtiger_project.valoreprodotto AS vtiger_project_valoreprodotto,vtiger_project.purchasecostdetail AS vtiger_project_purchasecostdetail,vtiger_crmentity.createdtime AS vtiger_crmentity_createdtime,vtiger_processtemplatecommessa.processtemplatename AS vtiger_processtemplate_processtemplatename,vtiger_supplierlinktosupplier.suppliername AS vtiger_supplier_suppliername,vtiger_vendorlinktovendor.vendorname AS vtiger_vendor_vendorname,vtiger_productfamilylintoprodfam.productfamily_name AS vtiger_productfamily_productfamily_name,vtiger_productscategorylinktopdtcateg.de_productscategory AS vtiger_productscategory_de_productscategory,vtiger_productssubcategorylinktosubcateg.cd_productssubcategory AS vtiger_productssubcategory_cd_productssubcategory,vtiger_regionalareaservicecenterlinktorasc.regionalascname AS vtiger_regionalareaservicecenter_regionalascname FROM $fromQueryPart[1]) AS mapTable");
//Adding primary key to the new created table
$adb->pquery("ALTER TABLE mv_map
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");  
