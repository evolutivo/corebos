
<div id="Pivot_Tempi_medi_CAT_customb111d84d0077d6236cf3d97a0082afb4"></div>
<div id="vkendo" class="k-content" style="display:none;">
           

            <script>
                  {literal}

              jQuery(document).ready(function() {
               var cond1="{/literal}{$cond1}{literal}";
                    var kURL="module=evvtApps&action=evvtAppsAjax&file=getPivot_Tempi_medi_CAT_customb111d84d0077d6236cf3d97a0082afb4&condit="+cond1;


                    var element = jQuery("#Pivot_Tempi_medi_CAT_customb111d84d0077d6236cf3d97a0082afb4").kendoGrid({        dataBound: function (e) {

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
                                    id: "projectid",
                                                                                
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
                        height: 430,
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

                                var grid = $("#Pivot_Tempi_medi_CAT_customb111d84d0077d6236cf3d97a0082afb4").data("kendoGrid");

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

