{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
{*<link href="Smarty/templates/kendoui/styles/kendo.common.min.css" rel="stylesheet" />*}
{*<link href="Smarty/templates/kendoui/styles/kendo.silver.min.css" rel="stylesheet" />*}
<link rel="stylesheet" href="modules/BiServer/kendoui/styles/kendo.common.min.css" />
<link rel="stylesheet" href="modules/BiServer/kendoui/styles/Material/kendo.material.min.css" />
<link type="text/css" href="modules/BiServer/kendoui/styles/Material/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="modules/BiServer/js/jquery.timepicker.css" />
<link rel="stylesheet" href="modules/BiServer/kendoui/styles/Material/kendo.material.min.css" />
<script src="modules/BiServer/kendoui/js/jquery-1.10.2.js"></script>
<script src="modules/BiServer/kendoui/js/jquery-ui.js"></script>
<script src="modules/BiServer/kendoui/js/kendo.web.min.js"></script>
<script src="modules/BiServer/kendoui/js/console.js"></script>
<script src="modules/BiServer/script.js"></script>
<script type="text/javascript" src="modules/BiServer/js/jquery.timepicker.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/x-kendo-template" id="templaterefresh">
        <div class="toolbar">
        <input type="button" name="refreshbutt" id="refreshbutt" value="Refresh" class="k-button k-button-icontext"></input>
        </div>
</script>
<script id="export_csv-template" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-export" href="\#" onclick="export_script();">Export</a>
</script>

<script id="execute-template" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-execute" href="\#" onclick="execute_script();">Execute</a>
</script>

<script id="add-cron" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-addcron" href="\#" onclick="cron('add');">Add Cron</a>
</script>

<script id="remove-cron" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-removecron" href="\#" onclick="cron('remove');">Remove Cron</a>
</script>
<script id="execute-email" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-email" href="\#" onclick="send_email_now();">Send Email Now</a>
</script>
    {literal}
<style> 
 .k-edit-form-container
{
    width: 500px;
}    
.k-edit-form-container .k-edit-field  .k-dropdown
{
    width: 98%;
}  
tr.d0 td {
  background-color: #F8EFFB;
  color: black;
}
tr.d1 td {
  background-color: #EFEFFB;
  color: black;
}
tr.dheader td {
    background-color: #3f51b5;
    height: 30px;
    color: white;
    border-top:1px solid #eeeecc;
    border-left:1px solid #fafafa;
    border-right:1px solid #999988;
    border-bottom:1px solid #999988;
    font-weight:bold;
}

.k-grid-add{
	-moz-box-shadow:inset 0px 1px 0px 0px #899599;
	-webkit-box-shadow:inset 0px 1px 0px 0px #899599;
	box-shadow:inset 0px 1px 0px 0px #899599;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #bab1ba));
	background:-moz-linear-gradient(top, #ededed 5%, #bab1ba 100%);
	background:-webkit-linear-gradient(top, #ededed 5%, #bab1ba 100%);
	background:-o-linear-gradient(top, #ededed 5%, #bab1ba 100%);
	background:-ms-linear-gradient(top, #ededed 5%, #bab1ba 100%);
	background:linear-gradient(to bottom, #ededed 5%, #bab1ba 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#bab1ba',GradientType=0);
	background-color:#ededed;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #d6bcd6;
	display:inline-block;
	cursor:pointer;
	color:#3f51b5;
	font-family:Arial;
	font-size:12px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #e1e2ed;
}

.k-grid-add:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #bab1ba), color-stop(1, #ededed));
	background:-moz-linear-gradient(top, #bab1ba 5%, #ededed 100%);
	background:-webkit-linear-gradient(top, #bab1ba 5%, #ededed 100%);
	background:-o-linear-gradient(top, #bab1ba 5%, #ededed 100%);
	background:-ms-linear-gradient(top, #bab1ba 5%, #ededed 100%);
	background:linear-gradient(to bottom, #bab1ba 5%, #ededed 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bab1ba', endColorstr='#ededed',GradientType=0);
	background-color:#bab1ba;
}
.k-grid-add:active {
	position:relative;
	top:1px;
}

</style>
        {/literal}
<script>
    {literal}

var export_csv = kendo.template(jQuery("#export_csv-template").html());
var executeTemplate = kendo.template(jQuery("#execute-template").html());
var addcron = kendo.template(jQuery("#add-cron").html());
var removecron = kendo.template(jQuery("#remove-cron").html());
var sendemail = kendo.template(jQuery("#execute-email").html());
var blockURL="module=BiServer&action=BiServerAjax&file=scripts";
var blockURL_email="module=BiServer&action=BiServerAjax&file=scripts_email";
var blockURL_security="module=BiServer&action=BiServerAjax&file=scripts_security";
var is_superadmin='{/literal}{$is_superadmin}{literal}';
var check_yes='modules/BiServer/check_yes.png';
var check_no='modules/BiServer/check_no.png';
        
function choose_fields(divId){
     var reportID = document.getElementById('clientreport2').options[document.getElementById('clientreport2').selectedIndex].value;
     console.log(reportID);
     console.log(divId);
     var url = "index.php?module=BiServer&action=BiServerAjax&file=getFields";
       jQuery.ajax({
            type: "POST",
            data:"reportID="+reportID,
            url: url,
            success: function(response) {
                var res = response.split("$$");
                jQuery("#count").val(res[1]);
                jQuery("#"+divId).empty();
                jQuery("#"+divId).html('<tr class="dheader"><td width="35%"><b><input type="checkbox" name="allids" id="allids"  class="k-checkbox" onchange=\'checkvalues("fieldTab2",this.id)\'><label class="k-checkbox-label" for="allids">  Field List </label></b></td><td  width="30%"><b>Field labels</b></td><td width="35%"><b><input type="checkbox" class="k-checkbox" name="allidsanalyzed" id="allidsanalyzed"  onchange=\'checkvalues("fieldTab2",this.id)\'><label class="k-checkbox-label" for="allidsanalyzed"> Analyzed </label></b></td> </tr>');
                jQuery("#"+divId).append(res[0]);
            }
        });
}

function showMapFields(id,type){
 var mapID = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
     if(type == "index"){
       var divId = 'fieldTab2';
       var count;
        count = 'count';
       }
            else 
                {
                     divId = 'fieldTab1';
                     count = 'countlogg';
                }
     var url = "index.php?module=BiServer&action=BiServerAjax&file=getMapFields";
       jQuery.ajax({
            type: "POST",
            data:"mapID="+mapID+"&type="+type,
            url: url,
            success: function(response) {
             var res = response.split("$$");
             jQuery("#"+count).val(res[1]);
             jQuery("#"+divId).empty();
             if(type !== "index")
             jQuery("#"+divId).html('<tr class="dheader"><td width="35%"><b><input type="checkbox" class="k-checkbox" name="allidslogg" id="allidslogg"  onchange=\'checkvalues("'+divId+'",this.id)\'><label class="k-checkbox-label" for="allidslogg">  Field List </label></b></td><td  width="30%"><b>Field labels</b></td><td width="35%" ><b><input type="checkbox" class="k-checkbox" name="allidsanalyzedlogg" id="allidsanalyzedlogg"  onchange=\'checkvalues("'+divId+'",this.id)\'><label class="k-checkbox-label" for="allidsanalyzedlogg"> Analyzed </label></b></td></tr>');
             else  
              jQuery("#"+divId).html('<tr class="dheader"><td width="35%"><b><input type="checkbox" name="allids" id="allids"  class="k-checkbox" onchange=\'checkvalues("fieldTab2",this.id)\'><label class="k-checkbox-label" for="allids">  Field List </label></b></td><td  width="30%"><b>Field labels</b></td><td width="35%"><b><input type="checkbox" class="k-checkbox" name="allidsanalyzed" id="allidsanalyzed"  onchange=\'checkvalues("fieldTab2",this.id)\'><label class="k-checkbox-label" for="allidsanalyzed"> Analyzed </label></b></td> </tr>');
             jQuery("#"+divId).append(res[0]);
            }
        });
}

function checkvalues(divId,checkallid){  

var oTable = document.getElementById(divId);
iMax = oTable.rows.length;
if(checkallid == "allids" || checkallid == "allidsanalyzed")
    var count = 'count';
else var count ='countlogg';
 console.log("ketu"+checkallid);
  for(i=1;i<=document.getElementById(count).value;i++){
 if(checkallid == "allids"){   
 if(document.getElementById('checkf'+i).disabled !== true)
 document.getElementById('checkf'+i).checked = document.getElementById('allids').checked;  
 if(document.getElementById('allids').checked == true) document.getElementById('checkf'+i).value=1;
 else document.getElementById('checkf'+i).value=0;   
 }
 if(checkallid == "allidsanalyzed"){
 if(document.getElementById('checkanalyzed'+i).disabled !== true)
 document.getElementById('checkanalyzed'+i).checked = document.getElementById('allidsanalyzed').checked;  
 if(document.getElementById('allidsanalyzed').checked == true) document.getElementById('checkanalyzed'+i).value=1;
 else document.getElementById('checkanalyzed'+i).value=0;   
 }

 if(checkallid == "allidslogg"){   
     console.log("Vjen ketu");
 if(document.getElementById('checkflogg'+i).disabled !== true)
 document.getElementById('checkflogg'+i).checked = document.getElementById('allidslogg').checked;  
 if(document.getElementById('allidslogg').checked == true) document.getElementById('checkflogg'+i).value=1;
 else document.getElementById('checkflogg'+i).value=0;   
 }
 if(checkallid== "allidsanalyzedlogg"){
 if(document.getElementById('checkanalyzedlogg'+i).disabled !== true)
 document.getElementById('checkanalyzedlogg'+i).checked = document.getElementById('allidsanalyzedlogg').checked;  
 if(document.getElementById('allidsanalyzedlogg').checked == true) document.getElementById('checkanalyzedlogg'+i).value=1;
 else document.getElementById('checkanalyzedlogg'+i).value=0;   
 }
}
}

function createSqlMv(filename){
var nometab = document.getElementById("tablename2").value;
var reportId = document.getElementById("clientreport2").value;
var data = jQuery('#tabelascript2').serialize(); 
var url = "index.php?module=BiServer&action=BiServerAjax&file="+filename;
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
       jQuery.ajax({
            type: "POST",
            data :"nometab="+nometab+"&reportId="+reportId+ "&"+ data,
            url: url,
            success: function(response) {
                 if (box) box.remove();
             //add jquery window dialog box
                 jQuery("#dialog-message").dialog({
                     modal: true,
                      buttons: {
                       Ok: function() {
                         jQuery(this).dialog( "close" );
                      }
                    }
               });
            }
    });   
} 

jQuery(document).ready(function() {

        jQuery("#tabstrip").kendoTabStrip({
                        animation:  {
                            open: {
                                effects: "fadeIn"
                            }
                        }
                    });
       
        scri=jQuery("#scripts").kendoGrid({
                dataSource: {
                    transport: {
                        read: {
                                url: 'index.php?'+blockURL+'&kaction=retrieve',
                                dataType: "json"
                               },

                         update: {
                               url: 'index.php?'+blockURL+'&kaction=save',
                               dataType: "json",
                                type: "POST",
                                complete: function(e)
                                {
                                jQuery("#scripts").data("kendoGrid").dataSource.read();
                                }
                          },
                        destroy: {
                                 url: 'index.php?'+blockURL+'&kaction=delete',
                                 dataType: "json",
                                 type: "POST",
                                 complete: function(e) {
                                     alert(e.responseText);
                                 jQuery("#scripts").data("kendoGrid").dataSource.read();
                                 
                                 }
                                 },
                        create: {
                                 url: 'index.php?'+blockURL+'&kaction=add',
                                 dataType: "json",
                                 type: "POST",
		                 complete: function(e) {
			         jQuery("scripts").data("kendoGrid").dataSource.read();
                                 }
                                 },
                        parameterMap: function(options, operation) {
                        if (operation !== "read" && options.models) {
                            return {models: kendo.stringify(options.models)};
                             }
                           }
                        },
                        navigatable: true,
                        batch: true,
                        pageSize: 10,
                        group: { field: "folder" },
                        schema: {
                        model: {
                              id: "id",
                              fields: {
                                  name:{validation: { required: true }},
                                  folder: {type: "string"},
                                  period: {type: "string"},
                                  active: {type: "boolean"},
                                  export_scr: {type: "boolean"},
                                  delete_scr: {type: "boolean"},
                                  execute_scr: {type: "boolean"}
                                     }
                                 }
                            }
                        },
                       pageable: true,
                       groupable: true,
                       filterable: true,
                       resizable: true,
                       selectable: true,
                       //toolbar: ["create"],
                  columns: [
                { field: "name" , title:"Script Name", width: "25%",headerAttributes: { "class": "center-header"},editor:function(){}},
                { field: "folder" , title:"Folder" , width: "8%",editor:function(){}},
                { field: "period" , title:"Period" , width: "8%"},
                { field: "active" , title:"Active", width: "8%",
                    template: "<img  src= #= active ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { command: [{ text: 'Export', template: export_csv}], title: " ", width: "8%"},
                { command: [ "destroy"], title: " ", width: "8%" },
                { command: [{ text: 'Execute', template: executeTemplate},
                            { text: "Add Cron" , template: addcron},
                            { text: "Remove Cron" , template: removecron}
                           ], title: " ", width: "240px"}
                ],
                 editable: {
                 mode:"popup",
                 confirmation: "Are you sure you want to delete this"
                 }
            });
        jQuery('#cron_script_time').timepicker({ 'timeFormat': 'H:i' });
           
        jQuery("#scripts_security").kendoGrid({
                dataSource: {
                    transport: {
                        read: {
                                url: 'index.php?'+blockURL_security+'&kaction=retrieve',
                                dataType: "json"
                               },

                         update: {
                               url: 'index.php?'+blockURL_security+'&kaction=save',
                               dataType: "json",
                                type: "POST",
                                complete: function(e)
                                {
                                jQuery("#scripts_security").data("kendoGrid").dataSource.read();
                                jQuery("#scripts").data("kendoGrid").dataSource.read();
                                }
                          },
                        destroy: {
                                 url: 'index.php?'+blockURL_security+'&kaction=delete',
                                 dataType: "json",
                                 type: "POST",
                                 complete: function(e) {
                                 if(e.responseText==true)
                                 {alert('Script is in cron,you can not delete without removing from cron !');
                                 jQuery("#scripts_security").data("kendoGrid").dataSource.read();}
                                 else{
			         jQuery("#scripts_security").data("kendoGrid").dataSource.read();
                                 jQuery("#scripts").data("kendoGrid").dataSource.read();
                                 }
                                 }
                                 },
                        create: {
                                 url: 'index.php?'+blockURL_security+'&kaction=add',
                                 dataType: "json",
                                 type: "POST",
		                 complete: function(e) {
			         jQuery("#scripts_security").data("kendoGrid").dataSource.read();
                                 jQuery("#scripts").data("kendoGrid").dataSource.read();
                                 }
                                 },
                        parameterMap: function(options, operation) {
                        if (operation !== "read" && options.models) {
                            return {models: kendo.stringify(options.models)};
                             }
                           }
                        },
                        navigatable: true,
                        batch: true,
                        pageSize: 10,
                        group: { field: "folder" },
                        schema: {
                        model: {
                              id: "id",
                              fields: {
                                  id: {type: "number"},
                                  name:{validation: { required: true }},
                                  scriptid: {type: "string"},
                                  rolename: {type: "string"},
                                  roleid: {type: "string"},
                                  export_scr: {type: "boolean"},
                                  delete_scr: {type: "boolean"},
                                  execute_scr: {type: "boolean"},
                                  folder: {type: "string"},
                                  
                                     }
                                 }
                            }
                        },
                       pageable: true,
                       groupable: true,
                       selectable: true,
                       resizable: true,
                       filterable: true,
                       toolbar: ["create"],
                  columns: [
                { field: "scriptid" , title:"Script Name",template:'#= name # ', width: "100px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoDropDownList({
                                    dataSource:{
                                         transport: {
                                                read: {
                                                       url: 'index.php?'+blockURL_security+'&kaction=list_script',
                                                       dataType: "json"
                                                      }
                                           }
                                            },
                                        dataValueField: "id",
                                        dataTextField: "name",
                                        autobind: false
                                     });
                              }},
                { field: "roleid" , title:"Role" ,template:'#= rolename # ', width: "50px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '" />').appendTo(container).kendoDropDownList({
                                    dataSource:{
                                         transport: {
                                                read: {
                                                       url: 'index.php?'+blockURL_security+'&kaction=list_role',
                                                       dataType: "json"
                                                      }
                                           }
                                            },
                                        dataValueField: "id",
                                        dataTextField: "name",
                                        autobind: false
                                     });
                              }},
                { field: "export_scr" , title:"Export", width: "50px",
                    template:"<img  src= #= export_scr ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { field: "delete_scr" , title:"Delete", width: "50px",
                    template: "<img  src= #= delete_scr ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { field: "execute_scr" , title:"Execute", width: "50px",
                    template: "<img  src= #= execute_scr ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { command: [ "edit","destroy"], title: " ", width: "100px"},
                
                ],
                 editable: {
                 mode:"popup",
                 confirmation: "Are you sure you want to delete this"
                 }
            });
            
        jQuery("#scripts_email").kendoGrid({
                dataSource: {
                    transport: {
                        read: {
                                url: 'index.php?'+blockURL_email+'&kaction=retrieve',
                                dataType: "json"
                               },

                         update: {
                               url: 'index.php?'+blockURL_email+'&kaction=save',
                               dataType: "json",
                                type: "POST",
                                complete: function(e)
                                {
                                jQuery("#scripts_email").data("kendoGrid").dataSource.read();
                                }
                          },
                        destroy: {
                                 url: 'index.php?'+blockURL_email+'&kaction=delete',
                                 dataType: "json",
                                 type: "POST",
                                 complete: function(e) {
                                 if(e.responseText==true)
                                 {alert('Script is in cron,you can not delete without removing from cron !');
                                 jQuery("#scripts_email").data("kendoGrid").dataSource.read();}
                                 else
			         jQuery("#scripts_email").data("kendoGrid").dataSource.read();
                                 }
                                 },
                        create: {
                                 url: 'index.php?'+blockURL_email+'&kaction=add',
                                 dataType: "json",
                                 type: "POST",
		                 complete: function(e) {
			         jQuery("#scripts_email").data("kendoGrid").dataSource.read();
                                 }
                                 },
                        parameterMap: function(options, operation) {
                        if (operation !== "read" && options.models) {
                            return {models: kendo.stringify(options.models)};
                             }
                           }
                        },
                        navigatable: true,
                        batch: true,
                        pageSize: 10,
                        group: { field: "folder" },
                        schema: {
                        model: {
                              id: "id",
                              fields: {
                                  id: {type: "number"},
                                  name:{validation: { required: true },visible: true, editable:false},
                                  scriptid: {type: "number",hidden:true, editable:true},
                                  time: {type: "string"},
                                  folder: {type: "string"},
                                  frecuency: {type: "string"},
                                  last_execute: {type: "string", editable:false },
                                  execution_cron: {type: "boolean"},
                                  emails: {type: "string"},
                                  emails_temp: {type: "string"},
                                  subject: {type: "string"},
                                  cont: {type: "string"},
                                  zipped: {type: "boolean"}
                                  
                                     }
                                 }
                            }
                        },
                       pageable: true,
                       sortable: true,
                       groupable: true,
                       selectable: true,
                       resizable: true,
                       filterable: true,
                       toolbar: ["create"],
                  columns: [
                  { field: "name" ,editable:false,editor:function(){}, title:"Script Name",template:'#= name # ', width: "70px"},
                { field: "scriptid" ,hidden: true, title:"Script Name",template:'#= name # ', width: "65px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '"  style="width:300px;" />').appendTo(container).kendoDropDownList({
                                    dataSource:{
                                         transport: {
                                                read: {
                                                       url: 'index.php?'+blockURL_security+'&kaction=list_script',
                                                       dataType: "json"
                                                      }
                                           }
                                            },
                                        dataValueField: "id",
                                        dataTextField: "name",
                                        autobind: false
                                     });
                              }},
                { field: "time" , title:"Time" ,template:'#= time # ', width: "15px",editor:function(container, options){
                                    jQuery('<input class="k-input" type="text" style="width:300px;" data-bind="value: ' + options.field + '"/>').appendTo(container);

                                    }},
               // { field: "frecuency" , title:"Frecuency", width: "20px"},
                { field: "last_execute" , title:"Last<br/> Execution", width: "20px"},
                { field: "emails" , title:"Address", width: "50px",template:'#= emails_temp # ',editor:function(container, options){
                                    jQuery('<input class="k-input" type="text" style="width:300px;" data-bind="value: ' + options.field + '"/>').appendTo(container);
                        }},
                { field: "subject" , title:"Subject", width: "30px",editor:function(container, options){
                                    jQuery('<input class="k-input" type="text" style="width:300px;" data-bind="value: ' + options.field + '"/>').appendTo(container);
                          }},
                { field: "cont" , title:"Content", width: "35px",editor:function(container, options){
                    jQuery('<textarea class="k-input" style="height:100px;width:300px;" data-bind="value: ' + options.field + '"></textarea>').appendTo(container);
                    }
                },
                { field: "zipped" , title:"Zipped", width: "15px",
                    template: "<img  src= #= zipped ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { field: "execution_cron" , title:"Active", width: "15px",
                    template: "<img  src= #= execution_cron ? '"+check_yes+"' : '"+check_no+"' #  >"},
                //{ command: [{ text: 'Send Mail Now', template: sendemail}], title: " ", width: "50px"},
                { command: [ "edit","destroy"], title: " ", width: "50px"},
                
                ],
                 editable: {
                 mode:"popup",
                 confirmation: "Are you sure you want to delete this",
                 width: '800px'
                 }
            });
            
       var myurl='index.php?module=BiServer&action=BiServerAjax&file=importActions';
       var  dataSource = new kendo.data.DataSource({
        transport: {
            read:  {
                    url: myurl,                                          
                    dataType: "json"
                    }   
                },  
        batch:true,
        pageSize: 15,                      
    
        schema: {
        model: {
        id:"id",
        fields:{
        actionname: {type: "string",editable: false, },
        filelist: { type: "string"},
        }
        }
        }      
        });
    jQuery("#actions").kendoGrid({
        dataSource: dataSource,
        pageable: true,
        groupable: false,
        height: 350,
        filterable: false,
        sortable: true,
        toolbar: kendo.template(jQuery("#templaterefresh").html()),
        resizable: true,
        columns: [
            {field: "actionname", title: "Action"},
            { command: [{ text: "Run", click: execscript,name:"run-script" }], title: "Actions" , width: "400px"}
           ]

    });
    jQuery("#mvsource").kendoDropDownList();
    jQuery("#clientreport2").kendoDropDownList();
    jQuery("#mapsql").kendoDropDownList();
    jQuery("#maploggingsql").kendoDropDownList();
 });
 
        
       function execscript(e) {
            kendo.ui.progress(jQuery("#actions"), true);
            var dataItem = this.dataItem(jQuery(e.currentTarget).closest("tr"));
            id = dataItem.id;
            parameters="";
            runNewAction(id,parameters , 'Alert') 
            kendo.ui.progress(jQuery("#actions"), false);
        }
        
       function execute_script() {
           event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           var execute_scr=selectedItem.execute_scr;
           if(execute_scr || is_superadmin){
           VtigerJS_DialogBox.block();
           jQuery.ajax({
                        url:'index.php?',
                	type: 'post',
                        data:blockURL+'&kaction=execute&filename='+filename+'&folder='+folder,
		success: function(response) {
                          //alert(response.responseText);
                          
                          alert('Script executed successfully');
                          VtigerJS_DialogBox.unblock();
               	}
                }
            );}
            else alert('You don\'t have permission to execute this script');
        }
        
       function export_script() {
           event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           var export_scr=selectedItem.export_scr;
           if(export_scr || is_superadmin){
           VtigerJS_DialogBox.block();
           jQuery.ajax({
                        url:'index.php?',
                	type: 'post',
                        data:blockURL+'&kaction=export&filename='+filename+'&folder='+folder,
		success: function(response) {
                          //alert(response.responseText);
                          var resp=response;
                          if(resp.indexOf("ERROR")!==-1)
                          {
                              alert('The report does not exist.\n\
Problem in creation');
                          }
                          else
                          {
                          alert('Script exported successfully');
                          //alert(response.responseText);
                          var uriContent=response;
                          var myWindow = window.open(uriContent,"_self");
                          myWindow.focus();
                          }
                          VtigerJS_DialogBox.unblock();
                          
               	}
                }
            );
           }
           else alert('You don\'t have permission to export this script');
        }
        
       function cron(type) {
           event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           console.log(selectedItem);
           var fileid=selectedItem.id;
           var folder=selectedItem.folder;
           if(type=='add'){
           jQuery("#cron_script_exec").dialog({
                     modal: true,
                      buttons: {
                       Ok: function() {
                         var period=document.getElementById("periodicity").options[document.getElementById("periodicity").selectedIndex].value;
                         var time=document.getElementById("cron_script_time").value;
                        jQuery.ajax({
                                    url:'index.php?',
                                    type: 'post',
                                    data:blockURL+'&kaction=cron&fileid='+fileid+'&folder='+folder+'&type='+type+'&time='+time+'&period='+period,
                            success: function(response) {                         
                                      grid.dataSource.read();
                            }
                            });
                        jQuery(this).dialog( "close" );
                      }
                    }
               });
           }
           else{
               jQuery.ajax({
                            url:'index.php?',
                            type: 'post',
                            data:blockURL+'&kaction=cron&fileid='+fileid+'&folder='+folder+'&type='+type,
                    success: function(response) {                         
                              grid.dataSource.read();
                    }
                });
           }
           
        }
        
        function send_email_now()
        {        
           event.preventDefault();
           var grid = jQuery("#scripts_email").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var id=selectedItem.id;
           
           VtigerJS_DialogBox.block();
           jQuery.ajax({
                        url:'index.php?',
                	type: 'post',
                        data:blockURL_email+'&kaction=sendmail_now&actionid='+id,
		success: function(response) {
                          
                          alert(response);
                          VtigerJS_DialogBox.unblock();
               	}
                }
            );
        }
        
       function show_folder()
        {
            var val =document.getElementById('folder').value;
            if(val=='Other')
            {
               document.getElementById('txt_script_file').style.display='inline';
            }
        }
        
       function show_source(){
        var sourceID = document.getElementById('mvsource').options[document.getElementById('mvsource').selectedIndex].value;
        if(sourceID == "1")
            {
                console.log("brisi");
                //show Report
                jQuery('#reports').show();
                if(jQuery('#mapquery').is(':visible'))
                jQuery('#mapquery').hide();
                jQuery("#mvtype").val("report");
            }
         else
         {
             //show Map
                jQuery('#mapquery').show();
                if(jQuery('#reports').is(':visible'))
                jQuery('#reports').hide();
                 jQuery("#mvtype").val("map");
         }
            jQuery("#fieldTab2").empty();
       }
       
       function createElasticIndex(submitid){
        var nometab = document.getElementById("tablename2").value;
        var reportId = document.getElementById("clientreport2").value;
       if(submitid != "createindex") var data = jQuery('#tabelascript2').serialize(); 
        else  var data =jQuery('#tabelascript').serialize(); 
        var url = "index.php?module=BiServer&action=BiServerAjax&file=createIndex";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
       jQuery.ajax({
            type: "POST",
            data :"nometab="+nometab+"&reportId="+reportId+ "&"+ data+"&submitid="+submitid,
            url: url,
            success: function(response) {
               if (box) box.remove();
             //add jquery window dialog box
               jQuery("#dialog-elasticindex").dialog({
                     modal: true,
                      buttons: {
                       Ok: function() {
                         jQuery(this).dialog( "close" );
                    }
                  }
               });
            }
    }); 
  }
         
{/literal}
</script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="90%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
        <td class="showPanelBg" align="center" style="padding: 10px;" valign="top">
           <table border="0" width="90%">
                <tr>			
                        <td class=heading2 valign=bottom><b>{$MOD.LBL_BISERVER}</b></td>
                </tr>
                <tr>
                <td>
                   <br/><br/><br/>
                </td>
                </tr>
           </table>
	<div align=center>
            
            <div id="tabstrip">
                    <ul>
                        
                        <li class="k-state-active">
                           BI Server
                        </li>
                        {if $is_admin}
                        <li >
                           BI Server Security
                        </li>
                        {/if}
                        <li >
                           BI Server Email Report
                        </li>
                         <li >
                           Actions
                        </li>
                         <li >
                          {$MOD.CREATEMVINDEX}
                        </li>
                        <li>
                          {$MOD.CREATELOGGINGINDEX}
                        </li>
                    </ul>                        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_server">
                                    <table border="0" width="100%">
                                      {*  <tr >
                                        <td class="dvInnerHeader">
                                            <b> Importa file script</b> 
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="dvtCellLabel">
                                            <br/><br/>
                                            <form  name="import_script" enctype="multipart/form-data" method="Post" 
                                                   action="index.php?module=BiServer&action=scripts&kaction=import_script_file">
                                                Folder :&nbsp;&nbsp; <select  id="folder" name="folder" class="small" onchange="show_folder();">
                                                {foreach item=option from=$folders}
                                                        <option value="{$option}" >{$option}</option> 
                                                {/foreach}
                                                <option value="Other" >Other</option> 
                                            </select>
                                            <input type="text" id="txt_script_file" name="txt_script_file" style="display:none;"/>
                                            &nbsp;&nbsp;File :&nbsp;&nbsp;<input type="file" id="btn_import_script_file" name="btn_import_script_file" />
                                            <input type="submit" align="right" value="Import file script" id="btn_import_script_file2" name="btn_import_script_file2" />

                                            </form>
                                        </td>
                                    </tr>*}
                                    <tr>
                                        <td>
                                            <br/><br/>
                                            <div id="scripts"></div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>
                                    
                            {* BI Server Security *}        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_server_security">
                                    <table border="0" width="100%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                            <div id="scripts_security"></div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>{* BI Server Security ends*}  
                            
                            {* BI Server Email *}        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_server_email">
                                    <table border="0" width="100%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                            <div id="scripts_email" height="700px;"></div>
                                        </td>
                                    </tr>
                                    </table>
                                    <div id="cron_script_exec" title="Parameters of Cron Execution" style = "display:none">
                                        <table>
                                            <tr>
                                                <td>Time</td>
                                                <td><input id="cron_script_time" name="cron_script_time" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>Periodicity</td>
                                                <td>
                                                    <select id="periodicity" name="periodicity">
                                                        <option>hourly</option> 
                                                        <option>daily</option> 
                                                        <option>monthly</option> 
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                    
                            </div> <br/><br/>                 
                            </div>{* BI Server Email ends*}  
                            
                            {* Actions *}        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_server_actions">
                                    <table border="0" width="60%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                            <div id="actions" style="display:table-cell; width:50%;padding:20px;" > </div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>{* Actions ends*} 
                                                        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_create_report">
                                    <table border="0" width="100%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                             <div id="create_report">
                                             <div  name="reportContainer2" id ="reportContainer2">
                                            <form name="tabelascript2" id="tabelascript2">
                                            <input  type="hidden" id="accinsdata2"  name="accinsdata2" value=""/>
                                            <input  type="hidden" id="accinsmodule2"  name="accinsmodule2"/>
                                            <input  type="hidden" id="nr"  name="nr" value="1"/>
                                            <input  type="hidden" id="count"  name="count" value="0"/>
                                            <input  type="hidden" id="mvtype"  name="mvtype" value=""/>
                                            <table>
                                                <tr>
                                                    <td><label><b>{$MOD.TableName}</b></label> </td>
                                                        <br><br>
                                                    <td><input class="k-input"  type="text" value="" name="tablename2"  id="tablename2"> </td>
                                                    <td align="right">
                                                       <input type='checkbox' class='k-checkbox' id='checkdeleted' name='checkdeleted'>
                                                       <label class='k-checkbox-label' for='checkdeleted'>{$MOD.DELETED}</label>           
                                                    </td>
                                                </tr>
                                            <tr>
                                            <td><label><b>{$MOD.MVSource}</b></label></td>
                                            <td><select  id="mvsource" style="margin-right: 30px;" name="mvsource" onchange="show_source();">
                                                    <option value="0">--None--</option>
                                                    <option value="1">{$MOD.Report}</option>
                                                    <option value="2">{$MOD.Map}</option>
                                                </select></td>
                                            </tr>
                                            <tr id="reports" style="display: none;">
                                            <td><label><b>Report</b></label></td>
                                            <td>
                                                <select  id="clientreport2" style="margin-right: 30px;" name="clientreport2" onchange="choose_fields('fieldTab2');">
                                                {$REPORTS}
                                                </select></td>
                                            </tr>
                                            <tr id="mapquery" style="display: none;">
                                            <td><label><b>{$MOD.Map}</b></label></td>
                                            <td><select  id="mapsql" style="margin-right: 30px;" name="mapsql" onchange="showMapFields(this.id,'index');">
                                                {$MAPS}
                                                </select></td>
                                            </tr>
                                            </table>
                                                <br><br>
                                            <table width="85%"  class="small" cellspacing="1" border="0" cellpadding="0" id='fieldTab2'>
                                            </table> 
                                                <br><br>
                                                <center>
                                                 <input type="submit" name="button2" id="button2" style="margin-right: 60px;" class="k-button" value="{$MOD.CREATETABLE}" onclick="createSqlMv('reportTable');return false;">
                                                 <input type="submit" name="createindex2" id="createindex2" style="margin-right: 60px;" class="k-button" value="{$MOD.CREATEINDEX2}" onclick="createElasticIndex(this.id);return false;">
                                                </center>
                                                    <div id="screated"> </div>
                                                  <div id="dialog-message" title="Report created" style = "display:none">
                                                  <p>
                                                    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
                                                   {$MOD.MSG_MV_CREATED}
                                                  </p>
                                                </div>
                                                 <div id="dialog-elasticindex" title="Report created" style = "display:none">
                                                  <p>
                                                    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
                                                     {$MOD.MSG_INDEX_CREATED}
                                                  </p>
                                                </div>
                                                   </form>

                                            </div>
                                         </div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>
       
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_create_logging_index">
                                    <table border="0" width="100%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                         <div id="create_loggingindex">
                                         <div  name="reportContainer" id ="reportContainer">
                                        <form name="tabelascript" id="tabelascript">
                                        <input  type="hidden" id="nr"  name="nr" value="1"/>
                                        <input  type="hidden" id="countlogg"  name="countlogg" value="0"/>
                                        <table>
                                        <tr id="maploggingquery">
                                        <td><label><b>{$MOD.Map}</b></label></td>
                                        <td><select  id="maploggingsql" style="margin-right: 30px;" name="maploggingsql" onchange="showMapFields(this.id,'loggingindex');">
                                            {$LOGGINGMAPS}
                                            </select></td>
                                        </tr>
                                        </table>
                                            <br><br>
                                        <table width="85%"  class="small" cellspacing="1" border="0" cellpadding="0" id='fieldTab1'>
                                        </table> 
                                            <br><br>
                                            <center>
                                         <input type="submit" name="createindex" id="createindex" style="margin-right: 60px;" class="k-button" value="{$MOD.CREATEINDEX}" onclick="createElasticIndex(this.id);return false;">
                                          </center>
                                             <div id="dialog-elasticindex" title="Report created" style = "display:none">
                                              <p>
                                                <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
                                                 {$MOD.MSG_INDEX_CREATED}
                                              </p>
                                            </div>
                                               </form>

                                        </div>
                                         </div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>
            </div>  {* tab div *}
            
            
    </div>{* center div *}
    </td>
</tr>
</tbody>
</table>

