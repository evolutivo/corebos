
<div id="example" class="k-content">
            <div id="grid"></div>
          
            <script>   {literal}
              var kURL = "module=FilterManagement&action=FilterManagementAjax&file=KendoContent";
                     
                jQuery(document).ready(function () {
                      var sel="{/literal}{$PRVPRFSELECTED}{literal}";
                      var notsel="{/literal}{$NOGIF}{literal}";  
                      
                        var mName;
                             var roleid = jQuery("#pickRole").data("kendoDropDownList");
                             var userid = jQuery("#pickuser").data("kendoDropDownList");
                             
                 
//
                    var  dataSource2 = new kendo.data.DataSource({
                            
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
			            jQuery("#grid").data("kendoGrid").dataSource.read();
                                    location.reload();
                                   }
                                },
                                destroy: {
                                    url: 'index.php?'+kURL+'&kaction=destroy',
                                    dataType: "json"
                                },
                                create: {
                                    url: 'index.php?'+kURL+'&kaction=create',                                         
                                    dataType: "json",
                                                cache: false,
                                     complete: function(e) {
			            jQuery("#grid").data("kendoGrid").dataSource.read();
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
                        pageSize: 15,                      
                        
                            schema: {
                                model: {
                                    id: "confid",
                                    fields: {
                                        confid: { editable: false, nullable: true },
                                        roleid: { editable: true },
                                        userid: { editable: true },
                                        FilterName: { validation: { required: true } },
                                        EntityName: { validation: { required: true } },    
                                        EditFilter: { type: "boolean"},
                                        ViewFilter: { type: "boolean" },
                                        DeleteFilter: { type: "boolean" }
                                    }
                                }
                            }
                                
                        });
                      var dataSourceFirst=new kendo.data.DataSource({
                            
                            transport: {
                                read:  {
                                    url: 'index.php?'+kURL+'&kaction=retrieveFirst',                                          
                                    dataType: "json"
                                },
                                update: {
                                    url: 'index.php?'+kURL+'&kaction=saveConfiguration',
                                    dataType: "json"
                                },
                                destroy: {
                                    url: 'index.php?'+kURL+'&kaction=destroyConfiguration',
                                    dataType: "json"
                                },
                                create: {
                                    url: 'index.php?'+kURL+'&kaction=saveConfiguration',                                         
                                    dataType: "json",
                                                   cache: false,
                                     complete: function(e) {
			            jQuery("#firstgrid").data("kendoGrid").dataSource.read();
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
                        pageSize: 15,
                        serverPaging: true,
                        serverSorting: true,
                       
                            schema: {
                                model: {
                                    id: "configurationid",
                                    fields: {
                                        configurationid: {type: "number" , editable: false, nullable: true },
                                        roleid: {  editable: true},
                                        userid: {  editable: true},
                                        FilterName: {type: "string", validation: { required: true } },
                                        EntityName: {type: "string", validation: { required: true } }, 
                                        FilterNameSecond: {type: "string", validation: { required: true } }
                                    }
                                }
                            }
                                
                        });
                      var dtsource=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=filters',
                                                       dataType: "json"
                                                       }
                                            },
                                                serverFiltering: true
                                    });   
                     var datasourcefilter=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=filters',
                                                       dataType: "json"
                                                       }
                                            },
                                                serverFiltering: true
                                    });   
                    var datasourceroles=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=roles',
                                                       dataType: "json"
                                                       }
                                            },
                                                serverFiltering: true
                                    });   
                    jQuery("#pickRole").kendoComboBox({
                        dataValueField: "roleid",
                                        dataTextField: "rolename",
                                        dataSource:datasourceroles,
                                            change: function() {                                            
                                            mName = this.value();                                            
                                            datasourceusers.filter({ field: "roleid", operator: "eq", value: mName} );
                                            
                                        }
                                     
                    });
                    var datasourceusers=new kendo.data.DataSource({
                                         transport: {type: "json",
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=users',
                                                       dataType: "json"
                                                       }
                                            },
                                                serverFiltering: true
                                    });   
                    
                    jQuery("#pickmodule").kendoComboBox({
                        dataValueField: "filterlabel",
                                        dataTextField: "viewnamelabel",
                                        dataSource:{
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=modules',
                                                       dataType: "json"
                                                       }
                                            }
                                            },
                                            change: function() {                                            
                                            mName = this.value();                                            
                                            datasourcefilter.filter({ field: "EntityName", operator: "eq", value: mName} );
                                            
                                        }
                                     
                    });
                        jQuery("#pickfilter").kendoComboBox({
                        dataValueField: "filterlabel",
                                        dataTextField: "viewnamelabel",
                                        dataSource: datasourcefilter
                                     
                    });
                    var grid=jQuery("#grid").kendoGrid({
                        dataSource: dataSource2,
                        pageable: true,
                        groupable: true,
                        filterable:true,
                     
                        reorderable: true,
                        height: 430, 
                        pageSize:10,     
                        toolbar: ["create"],
                        columns: [
                             { field:"roleid", title: "Role Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    
                                        dataSource:datasourceroles,
                                        dataTextField: "rolename",
                                        dataValueField: "rolename",
                                       change: function() {                                            
                                            mName = this.value();                                            
                                            datasourceusers.filter({ field: "EntityName", operator: "eq", value: mName} );
                                            
                                        }
                                    });
                                    } },
                            { field:"userid", title: "User Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    dataSource:datasourceusers,
                                        
                                         dataValueField: "username",
                                         dataTextField: "username"
                                    });
                                    } },
                            { field:"EntityName", title: "Module Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    dataSource:{
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=modules',
                                                       dataType: "json"
                                                       }
                                            }
                                            },
                                        
                                        dataValueField: "viewnamelabel",
                                        dataTextField: "viewnamelabel",
                                       change: function() {                                            
                                            mName = this.value();                                            
                                            dtsource.filter({ field: "EntityName", operator: "eq", value: mName} );
                                            
                                        },
                                        autobind: false
                                    });
                                    } },
                                        { field:"FilterName", title: "Filter Name" , editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '" />').appendTo(container).kendoComboBox({
                                    
                                        dataValueField: "viewnamelabel",
                                        dataTextField: "viewnamelabel",
                                       dataSource: dtsource,
                                       serverFiltering: true,
                                       autobind: false

                                    });}},
                                         

                            { field: "EditFilter", title:"Edit Filter",template: "<img  src= #= EditFilter ? '"+sel+"' : '"+notsel+"' #  >",  width: "100px" },                            
                            { field: "ViewFilter", title:"View Filter",template: "<img  src= #= ViewFilter ? '"+sel+"' : '"+notsel+"' #  >", width: "100px" },
                            { field: "DeleteFilter", title:"Delete Filter",template: "<img  src= #= DeleteFilter ? '"+sel+"' : '"+notsel+"' #  >", width: "100px" },
                            { command: ["edit", "destroy"], title: "&nbsp;", width: "210px" }],
                            columnMenu: {
                            columns: false
                          },
                        editable: "popup",
                             sortable: true
                             
                                      
                            
                            
                    });
                    jQuery("#pickuser").kendoComboBox({
                                          dataValueField: "userid",
                                        dataTextField: "username",
                                            autoBind: false,
                        optionLabel: "All",
                                        dataSource:datasourceusers,
                                            change: filterDatasources
                                     
                    }); 
                        
                        
                        jQuery("#firstgrid").kendoGrid({
                        dataSource: dataSourceFirst,
                        pageable: true,
                        groupable: true,
                        filterable: true,
                        sortable: true,
                        columnMenu: {
                            columns: false
                          },   
                        reorderable: true,
                        height: 430, 
                        pageSize:10,    
                        toolbar: ["create"],
                        columns: [
                            { field:"roleid", title: "Role Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    
                                        dataSource:datasourceroles,
                                        dataTextField: "rolename",
                                        dataValueField: "rolename",
                                       change: function() {                                            
                                            mName = this.value();                                            
                                            datasourceusers.filter({ field: "EntityName", operator: "eq", value: mName} );
                                            
                                        }
                                    });
                                    } },
                            { field:"userid", title: "User Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    dataSource:datasourceusers,
                                        
                                         dataValueField: "username",
                                         dataTextField: "username"
                                    });
                                    } },
                            { field:"EntityName", title: "Module Name", editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '"/>').appendTo(container).kendoComboBox({
                                    dataSource:{
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=modules',
                                                       dataType: "json"
                                                       }
                                            }
                                            },
                                        
                                        dataValueField: "viewnamelabel",
                                        dataTextField: "viewnamelabel",
                                       change: function() {                                            
                                            mName = this.value();                                            
                                            dtsource.filter({ field: "EntityName", operator: "eq", value: mName} );
                                            
                                        },
                                        autobind: false
                                    });
                                    } },
                                        { field:"FilterName", title: "Default Primo Filter" , editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '" />').appendTo(container).kendoComboBox({                                        
                                    
                                        dataValueField: "viewnamelabel",
                                        dataTextField: "viewnamelabel",
                                       dataSource: dtsource,
                                       serverFiltering: true,
                                       autobind: false

                                    });}},
                                         

                              { field:"FilterNameSecond", title: "Default Secondo Filter" , editor: function(container, options) {
                                    jQuery('<input name="' + options.field + '" />').appendTo(container).kendoComboBox({                                        
                                    
                                        dataValueField: "viewnamelabel",
                                        dataTextField: "viewnamelabel",
                                       dataSource: dtsource,
                                       serverFiltering: true,
                                       autobind: false

                                    });}},
                                           { command: ["edit", "destroy"], title: "&nbsp;", width: "210px" }],
                        editable: "popup"
                                      
                            
                            
                    });
                        jQuery("#example").bind("kendo:skinChange", function(e) {
                      
                        dataSource2.read();
                    });
                });
                function changeModule1(){
                    jQuery("#status").css={"display":"inline"};
                    var rolePick = jQuery("#pickRole").data("kendoDropDownList");
                    var rl=rolePick.value();   
                    
                    var userPick = jQuery("#pickuser").data("kendoDropDownList");
                    var ul=userPick.value(); 
                        var grid = jQuery("#grid").data("kendoGrid");
                            var dSource=grid.dataSource;
                    dSource.filter(
                            {logic: "and",
                            filters: [
                            { field: "EntityName", operator: "eq", value: "8"},
                            { field: "userid", operator: "eq", value: ul}
                                         ]
                        } );
                            grid.refresh();
                      }  
                 function saveConfiguration(){
                    var rolePick = jQuery("#pickRole").data("kendoDropDownList");
                    var rl=rolePick.value();   
                    
                    var userPick = jQuery("#pickuser").data("kendoDropDownList");
                    var ul=userPick.value(); 
                        
                    var modulePick = jQuery("#pickmodule").data("kendoDropDownList");
                    var ml=modulePick.value();   
                    
                    var filterPick = jQuery("#pickfilter").data("kendoDropDownList");
                    var fl=filterPick.value(); 
                    
                    var cancreate=jQuery("#cancreate").is(':checked'); 
                    var url='moduleName='+ml+'&roleid='+rl+'&userid='+ul+'&kaction=saveConfiguration';
                    
                    new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=FilterManagement&action=FilterManagementAjax&file=KendoContent&'+url,
                                onComplete: function(response) {
                                    if(!response.responseText)
                                        {	}
                                }
                        }
                );   
                     
                     }
                         function filterDatasources() {
                                                                       
                                            mName = this.value();
                                                dataSource2.filter({ field: "userid", operator: "eq", value: 19});
                                              //dataSource2.read();   
                                            //jQuery("#grid").data("kendoGrid").refresh({data: {userid: mName}});    
//                                            dataSourceFirst.filter({ field: "userid", operator: "eq", value: parseInt(mName)});
                                            // grid.dataSource.data(dataSource.data());    
                                         
                                        }
                                           
    {/literal}
            </script>
            <style scoped="scoped">
                 {literal}
                #grid .k-toolbar
                {
                    min-height: 27px;
                }
                .category-label
                {
                    vertical-align: middle;
                    padding-right: .5em;
                }
                 {/literal}
                </style>
        </div>
          
        