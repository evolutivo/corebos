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



<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.silver.min.css" rel="stylesheet" />
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>
<script src="kendoui/js/console.js"></script>
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


<script id="execute-email" type="text/x-kendo-template">
   <a class="k-button k-button-icontext k-grid-email" href="\#" onclick="send_email_now();">Send Email Now</a>
</script>

<script>
    {literal}
        
function export_tables(){
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module=BiServer&action=BiServerAjax&file=export_tables&ajax=true",
			onComplete: function(response) {
                            alert('Export finished successfully ');
               	    }
                }
        );
        }
        
function import_files(){
    //alert(document.getElementById('btn_import_files').value);
        new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module=BiServer&action=BiServerAjax&file=import_files&ajax=true&file_val="+document.getElementById('btn_import_files').value,
		onComplete: function(response) {
                          alert('Import finished successfully ');
               	}
                }
        );


        }
var export_csv = kendo.template(jQuery("#export_csv-template").html());
var executeTemplate = kendo.template(jQuery("#execute-template").html());
var addcron = kendo.template(jQuery("#add-cron").html());
var removecron = kendo.template(jQuery("#remove-cron").html());
var sendemail = kendo.template(jQuery("#execute-email").html());
var blockURL="module=BiServer&action=BiServerAjax&file=scripts";
var blockURL_security="module=BiServer&action=BiServerAjax&file=scripts_security";
var blockURL_email="module=BiServer&action=BiServerAjax&file=scripts_email";
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
                                  last_exec: {type: "string"},
                                  active: {type: "boolean"},
                                  export_scr: {type: "boolean"},
                                  delete_scr: {type: "boolean"},
                                  execute_scr: {type: "boolean"}
                                     }
                                 }
                            }
                        },
                        filterable: {
                            mode: "row"
                        },
                       pageable: true,
                       groupable: true,
                       resizable: true,
                       selectable: true,
                       //toolbar: ["create"],
                  columns: [
                { field: "name" , title:"Script Name", width: "150px",editor:function(){},filterable: {
                                cell: {
                                    operator: "contains"
                                }
                            }},
                { field: "folder" , title:"Folder" , width: "50px",editor:function(){},filterable: {
                                cell: {
                                    operator: "contains"
                                }
                            }},
                { field: "last_exec" , title:"Last Execution" , width: "50px"},
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
           
           var grid_scripts_security = jQuery("#scripts_security").kendoGrid({
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
                                  name:{validation: { required: true },visible: true, editable:false },
                                  scriptid: {type: "string",hidden:true, editable:true},
                                  rolename: {type: "string", editable:false },
                                  roleid: {type: "string"},
                                  export_scr: {type: "boolean"},
                                  delete_scr: {type: "boolean"},
                                  execute_scr: {type: "boolean"},
                                  folder: {type: "string"},
                                  
                                     }
                                 }
                            }
                        },
                        filterable: true,
                       pageable: true,
                       groupable: true,
                       selectable: true,
                       resizable: true,
                       toolbar: ["create"],
                  columns: [
                      { field: "name" ,editable:false,editor:function(){}, title:"Script Name",template:'#= name # ', width: "150px"},
                { field: "scriptid" ,hidden: true, title:"Script Name",template:'#= name # ', width: "150px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '"  style="width:400px;" />').appendTo(container).kendoDropDownList({
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
                              }
                        },
                { field: "rolename" ,editable:false,editor:function(){}, title:"Role" ,template:'#= rolename # ', width: "80px"},
                         { field: "roleid" ,hidden: true, title:"Role" ,template:'#= rolename # ', width: "80px",
                    editor:function(container, options) {
                                    jQuery('<input name="' + options.field + '" style="width:400px;" />').appendTo(container).kendoDropDownList({
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
                              }
                        },
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
                 confirmation: "Are you sure you want to delete this",
                 width: '800px'
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
                                    jQuery('<input name="' + options.field + '"  style="width:400px;" />').appendTo(container).kendoDropDownList({
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
                                    jQuery('<input class="k-input" type="text" style="width:400px;" data-bind="value: ' + options.field + '"/>').appendTo(container);

                                    }},
               // { field: "frecuency" , title:"Frecuency", width: "20px"},
                { field: "last_execute" , title:"Last<br/> Execution", width: "20px"},
                { field: "emails" , title:"Address", width: "50px",template:'#= emails_temp # ',editor:function(container, options){
                                    jQuery('<input class="k-input" type="text" style="width:400px;" data-bind="value: ' + options.field + '"/>').appendTo(container);
                        }},
                { field: "subject" , title:"Subject", width: "30px",editor:function(container, options){
                                    jQuery('<input class="k-input" type="text" style="width:400px;" data-bind="value: ' + options.field + '"/>').appendTo(container);
                          }},
                { field: "cont" , title:"Content", width: "35px",editor:function(container, options){
                    jQuery('<textarea class="k-input" style="height:100px;width:400px;" data-bind="value: ' + options.field + '"></textarea>').appendTo(container);
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
                    
       });
       
                       
       function execute_script() {
           //event.preventDefault();
           var grid = jQuery("#scripts").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var filename=selectedItem.name;
           var folder=selectedItem.folder;
           var execute_scr=selectedItem.execute_scr;
           if(execute_scr || is_superadmin){
           VtigerJS_DialogBox.block();
           new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:blockURL+'&kaction=execute&filename='+filename+'&folder='+folder,
		onComplete: function(response) {
                          //alert(response.responseText);
                          var resp=response.responseText;
                          if(resp.indexOf("EXEC_OTHER")!=-1)
                          {
                              alert('The script is executing by other user');
                          }
                          else if(resp.indexOf("EXEC_MAIL")!=-1)
                          {
                              alert('The script is executing by automatic BI Mail Sender');
                          }
                          else
                          {
                          alert('Script executed successfully');
                          }
                          VtigerJS_DialogBox.unblock();
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
           VtigerJS_DialogBox.block();
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
                              alert('The report does not exist.\n\
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
                          VtigerJS_DialogBox.unblock();
                          
               	}
                }
            );
           }
           else alert('You don\'t have permission to export this script');
        }
        
        function cron(type) {
           //event.preventDefault();
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
        
        function send_email_now()
        {        
           event.preventDefault();
           var grid = jQuery("#scripts_email").data("kendoGrid");
           var selectedItem = grid.dataItem(grid.select());
           var id=selectedItem.id;
           
           VtigerJS_DialogBox.block();
           new Ajax.Request(
        	'index.php',
              {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:blockURL_email+'&kaction=sendmail_now&actionid='+id,
		onComplete: function(response) {
                          
                          alert(response.responseText);
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
         

</script>
<style>
.k-widget.k-window {
    width: 600px;
}
</style>
{/literal}
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
           <table border="0" width="100%">
                <tr>			
                        <td class=heading2 valign=bottom><b>{$MOD.LBL_TOOLBOX_COMPANION}</b></td>
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
                        
                        <li >
                           BI Server Email Report
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
                            
                            {* BI Server Email *}        
                            <div class="weather" >
                            <br/><br/>
                            <div id="bi_server_email">
                                    <table border="0" width="100%">                                     
                                    <tr>
                                        <td >
                                            <br/><br/>
                                            <div id="scripts_email"></div>
                                        </td>
                                    </tr>
                                    </table>
                    
                            </div> <br/><br/>                 
                            </div>{* BI Server Email ends*}  
                            
            </div>  {* tab div *}
            
            
    </div>{* center div *}
    </td>
</tr>
</tbody>
</table>

