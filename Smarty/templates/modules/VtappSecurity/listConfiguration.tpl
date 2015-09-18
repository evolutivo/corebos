<script src="modules/evvtApps/js/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
        jQuery.noConflict();
</script>
<link href="kendoui/source/styles/kendo.common.css" rel="stylesheet" />
<link href="kendoui/source/styles/kendo.default.css" rel="stylesheet" />
<script src="modules/evvtApps/js/kendo.web.js"></script>

<div id="example" class="k-content">
<div id="vertical" style="height: 502px; width: 99%;">
   
 <div id="grid2"></div>
 </div>
            <script> 
              
                 
                {literal}
                    var sel="{/literal}{$PRVPRFSELECTED}{literal}";
                    var notsel="{/literal}{$NOGIF}{literal}";  
       var record='{/literal}{$ID}{literal}';
              var kURL = "module=VtappSecurity&action=VtappSecurityAjax&file=KendoContent";
                 
               var  dataSource = new kendo.data.DataSource({
                            
                            transport: {
                                read:  {
                                    url: 'index.php?'+kURL+'&kaction=retrieve',
                                    dataType: "json"
                                 
                                },
                                update: {
                                    url: 'index.php?'+kURL+'&kaction=update',
                                    dataType: "json",
                                               cache: false,
                                     complete: function(e) {
			            jQuery("#grid2").data("kendoGrid").dataSource.read();
                                    //location.reload();
                                   }
                                },
                                destroy: {
                                    url: 'index.php?'+kURL+'&kaction=destroy',
                                    dataType: "json",
                                               cache: false,
                                     complete: function(e) {
			            jQuery("#grid2").data("kendoGrid").dataSource.read();
                                    location.reload();
                                   }
                                },
                                create: {
                                    url: 'index.php?'+kURL+'&kaction=create',
                                    dataType: "json",
                                               cache: false,
                                     complete: function(e) {
			            jQuery("#grid2").data("kendoGrid").dataSource.read();
                                    location.reload();
                                   }
                                },
                                    
                                  
                                parameterMap: function(options, operation) {
                                    if (operation !== "read" && options.models) {
                                        return {models: kendo.stringify(options.models)};
                                    }
                                    
                                }
                            },  
                       batch:true,
                        pageSize: 25,
                       
                        
                            schema: {
                                model: {
                                    id: "id",
                                    fields:{                                        					
                                        label: {type:"string" },
                                                                           
					permissions: { validation: { required: true }},
					 visible: { type: "boolean"},
                                     deleted: { type: "boolean"},
                               				widget: { type:"string"},

                                    }
                                }
                            }
                                
                        });
                      
              
                    var datasourceroles=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=roles',
                                                       dataType: "json"
                                                       }
                                            }
                                    });  
                          var datasourceev=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=ev',
                                                       dataType: "json"
                                                       }
                                            }
                                    });
                                       var datasourcewidg=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=widg',
                                                       dataType: "json"
                                                       }
                                            }
                                    });
                       jQuery("#grid2").kendoGrid({
                        dataSource: dataSource,
                        pageable: true,
                        groupable: true,
                        height: 500, 
                        toolbar: ['create'],
                        filterable:true,
                        sortable: true,
                                           
                        columns: [
                                                        
			    {field:"label", title: "App name", editor: evEd},
                     
                            { field:"permissions", title: "Ruoli/Utenti", editor: rolesEd},
                            { field: "visible", title:"Enabled",template: "<img  src= #= visible ? '"+sel+"' : '"+notsel+"' #  >",  width: "100px" },
                              { field: "deleted", title:"Delete",template: "<img  src= #= deleted ? '"+sel+"' : '"+notsel+"' #  >",  width: "100px" },
                            { field:"widget", title: "Widget", editor: widgEd},

                                           { command: ["edit", "destroy"], title: "&nbsp;", width: "210px" }],
                         editable: "inline"
                     });   
                           
                    function rolesEd(container, options) {
                                    jQuery('<input name="' + options.field + '" multiple/>').appendTo(container).kendoComboBox({
                                        dataTextField: "rolename",
                                        dataValueField: "rolename",
                                        dataSource:datasourceroles
                                    });
                                    }
                                          function evEd(container, options) {
                                    jQuery('<input name="' + options.field + '" multiple/>').appendTo(container).kendoComboBox({
                                        dataTextField: "appname",
                                        dataValueField: "appid",
                                        dataSource:datasourceev
                                    });
                                    }
              function widgEd(container, options) {
                                    jQuery('<input name="' + options.field + '" multiple/>').appendTo(container).kendoComboBox({
                                        dataTextField: "widget",
                                        dataValueField: "widget",
                                        dataSource:datasourcewidg
                                    });
                                    }
               
                      {/literal}
                          
            </script>

            <style scoped>{literal}
                #treeview {
                width: 300px;
               
            }
 
            #treeview .k-sprite {
                background-image: url("themes/softed/images/delete.png");
            }

            .rootfolder { background-position: 0 0; }
            .folder     { background-position: 0 -16px; }
            .pdf        { background-position: 0 -32px; }
            .html       { background-position: 0 -48px; }
            .image      { background-position: 0 -64px; }

            .delete-link {
                width: 16px;
                height: 16px;
                background: transparent url("themes/softed/images/delete.png") no-repeat 50% 50%;
                overflow: hidden;
                display: inline-block;
                font-size: 0;
                line-height: 0;
                vertical-align: top;
                margin: 2px 0 0 3px;
                -webkit-border-radius: 5px;
                -mox-border-radius: 5px;
                border-radius: 5px;
            }
            .delete-button {
                width: 16px;
                height: 16px;
                background: transparent url("themes/softed/images/delete.png") no-repeat 50% 50%;
                overflow: hidden;
                display: inline-block;
                font-size: 0;
                line-height: 0;
                vertical-align: top;
                margin: 2px 0 0 3px;
                -webkit-border-radius: 5px;
                -mox-border-radius: 5px;
                border-radius: 5px;
            }
            .edit-link {
                width: 16px;
                height: 16px;
                background: transparent url("themes/softed/images/arrow_up.png") no-repeat 50% 50%;
                overflow: hidden;
                display: inline-block;
                font-size: 0;
                line-height: 0;
                vertical-align: top;
                margin: 2px 0 0 3px;
                -webkit-border-radius: 5px;
                -mox-border-radius: 5px;
                border-radius: 5px;
            }
            .down-link {
                width: 16px;
                height: 16px;
                background: transparent url("themes/softed/images/arrow_down.png") no-repeat 50% 50%;
                overflow: hidden;
                display: inline-block;
                font-size: 0;
                line-height: 0;
                vertical-align: top;
                margin: 2px 0 0 3px;
                -webkit-border-radius: 5px;
                -mox-border-radius: 5px;
                border-radius: 5px;
            }
            .treeInlineEdit > input
            {
                font-size: 1.5em;
                min-width: 7em;
                min-height: 2em;
                border-radius: 5px 5px 5px 5px;
                -moz-border-radius: 5px 5px 5px 5px;
                border: 0px solid #ffffff;
            }
            
            .delete-message {                
                font-size:12px;
            }

            .delete-cancel {
                color: #000;
            }​
            {/literal}
            </style>
 </div>
         
