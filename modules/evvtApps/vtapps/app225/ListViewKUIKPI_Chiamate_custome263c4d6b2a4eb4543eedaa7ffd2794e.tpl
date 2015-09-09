<div id="KPI_Chiamate_custome263c4d6b2a4eb4543eedaa7ffd2794e"></div>
<div id="vkendo" class="k-content" style="display:none;">
           

            <script>
                  {literal}

              jQuery(document).ready(function() {
               var cond1="{/literal}{$cond1}{literal}";
                    var kURL="module=evvtApps&action=evvtAppsAjax&file=getKPI_Chiamate_custome263c4d6b2a4eb4543eedaa7ffd2794e&condit="+cond1;


                    var element = jQuery("#KPI_Chiamate_custome263c4d6b2a4eb4543eedaa7ffd2794e").kendoGrid({        dataBound: function (e) {

                CollapseAllRows();

            }  ,
                        dataSource: new kendo.data.DataSource({  
                            type: "json",
                                    transport: {
                                    read: {
                                        url: "index.php?"+kURL,
                                        dataType: "json"
                                    },
                                     parameterMap: function(options, operation) {
                                    if (operation !== "read" && options.models) {
                                        return {models: kendo.stringify(options.models)};
                                    }
                                }

                        },
                     
                           // pageSize: 20,
                            batch:true,
      

                                schema: {
                                model: {
                                    id: "id",
                                                                                
                                    fields: {/literal}{$field}{literal},
                                    }
                                },
                                               {/literal}
                                {if $grp neq 0}
                                 {literal}
                                group: {/literal}{$group}{literal}, {/literal}
                                                                {/if}
                                {if $agg neq 0} {literal}
                                aggregate: {/literal}{$aggregate}
                                                                                                {/if}
{literal}


                        }),
                        height: 450,
                        sortable: true,
                       // pageable: true,
                       filterable:true,
                         groupable:true,
                        editable: "popup",
                        columns: {/literal}{$column}{literal},
                      });
            });
                 function nonFlatFields(container, options) {
                    jQuery("<input />")
                        .appendTo(container)
			.kendoAutoComplete({
                        minLength: 2,
                        dataSource: {
                       
                        }
                    });
                    }
                    function CollapseAllRows() {

                                var grid = $("#KPI_Chiamate_custome263c4d6b2a4eb4543eedaa7ffd2794e").data("kendoGrid");

                                grid.tbody.find(">tr.k-grouping-row").each(

                                    function (e) {

                                        grid.collapseRow(this);

                                    });

                            }
                 {/literal}
            </script>
            <style scoped="scoped">
                {literal}
                .employee-details ul
                {
                    list-style:none;
                    font-style:italic;
                    margin-bottom: 20px;
                }
		.k-autocomplete {
                	width: 135px;
                    vertical-align: middle;
                }
                .employee-details label
                {
                    display:inline-block;
                    width:90px;
                    font-style:normal;
                    font-weight:bold;
                }
                {/literal}
            </style>
        </div>

