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

<link href="Smarty/templates/kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="Smarty/templates/kendoui/styles/kendo.silver.min.css" rel="stylesheet" />
<script src="Smarty/templates/kendoui/js/jquery.min.js"></script>
<script src="Smarty/templates/kendoui/js/kendo.web.min.js"></script>
<script src="Smarty/templates/kendoui/js/console.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>

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

<script>
    {literal}

var export_csv = kendo.template(jQuery("#export_csv-template").html());
var executeTemplate = kendo.template(jQuery("#execute-template").html());
var addcron = kendo.template(jQuery("#add-cron").html());
var removecron = kendo.template(jQuery("#remove-cron").html());
var blockURL="module=BiServer&action=BiServerAjax&file=scripts";
var blockURL_security="module=BiServer&action=BiServerAjax&file=scripts_security";
var is_superadmin='{/literal}{$is_superadmin}{literal}';
var check_yes='themes/images/check_yes.png';
var check_no='themes/images/check_no.png';
        
        
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
                              id: "name",
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
                       resizable: true,
                       selectable: true,
                       //toolbar: ["create"],
                  columns: [
                { field: "name" , title:"Script Name", width: "150px",editor:function(){}},
                { field: "folder" , title:"Folder" , width: "50px",editor:function(){}},
                { field: "period" , title:"Period" , width: "50px"},
                { field: "active" , title:"Active", width: "50px",
                    template: "<img  src= #= active ? '"+check_yes+"' : '"+check_no+"' #  >"},
                { command: [{ text: 'Export', template: export_csv}], title: " ", width: "50px"},
                { command: [ "destroy"], title: " ", width: "50px"},
                { command: [{ text: 'Execute', template: executeTemplate},
                            { text: "Add Cron" , template: addcron},
                            { text: "Remove Cron" , template: removecron}
                           ], title: " ", width: "150px"}
                ],
                 editable: {
                 mode:"popup",
                 confirmation: "Are you sure you want to delete this"
                 }
            });
           
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
                                 else
			         jQuery("#scripts_security").data("kendoGrid").dataSource.read();
                                 }
                                 },
                        create: {
                                 url: 'index.php?'+blockURL_security+'&kaction=add',
                                 dataType: "json",
                                 type: "POST",
		                 complete: function(e) {
			         jQuery("#scripts_security").data("kendoGrid").dataSource.read();
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
                       toolbar: ["create"],
                  columns: [
                { field: "scriptid" , title:"Script Name",template:'#= name # ', width: "150px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '" />').appendTo(container).kendoDropDownList({
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
                { field: "roleid" , title:"Role" ,template:'#= rolename # ', width: "80px",
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
                    
       });
       
       function execute_script() {
           event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           var execute_scr=selectedItem.execute_scr;
           if(execute_scr || is_superadmin){
           //VtigerJS_DialogBox.block();
           new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:blockURL+'&kaction=execute&filename='+filename+'&folder='+folder,
		onComplete: function(response) {
                          //alert(response.responseText);
                          
                          alert('Script executed successfully');
                          //VtigerJS_DialogBox.unblock();
               	}
                }
            );}
            else alert('You don\'t have permission to execute this script');
        }
        
        function export_script() {
           //event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           var export_scr=selectedItem.export_scr;
           if(export_scr || is_superadmin){
           //VtigerJS_DialogBox.block();
           new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:blockURL+'&kaction=export&filename='+filename+'&folder='+folder,
		onComplete: function(response) {
                          //alert(response.responseText);
                          var resp=response.responseText;
                          if(resp.indexOf("ERROR")!==-1)
                          {
                              alert('The report does not exist or is empty.\n\
Problem in creation');
                          }
                          else if(resp.indexOf("EXEC_OTHER")!==-1)
                          {
                              alert('The script is executing by other user');
                          }
                          else if(resp.indexOf("EXEC_MAIL")!==-1)
                          {
                              alert('The script is executing by automatic BI Mail Sender');
                          }
                          else
                          {
                          alert('Script exported successfully');
                          //alert(response.responseText);
                          var uriContent=resp;
                          var myWindow = window.open(uriContent,"_self");
                          myWindow.focus();
                          }
                          //VtigerJS_DialogBox.unblock();
                          
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
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:blockURL+'&kaction=cron&filename='+filename+'&folder='+folder+'&type='+type,
		onComplete: function(response) {                         
                          grid.dataSource.read();
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
         
{/literal}
</script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
           <table border="0" width="100%">
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
                            
            </div>  {* tab div *}
            
            
    </div>{* center div *}
    </td>
</tr>
</tbody>
</table>

