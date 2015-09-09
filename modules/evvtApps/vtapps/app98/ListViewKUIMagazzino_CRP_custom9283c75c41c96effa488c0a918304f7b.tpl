<div id="Magazzino_CRP_custom9283c75c41c96effa488c0a918304f7b"></div>
<div id="vkendo" class="k-content" style="display:none;">
           

            <script>
                  {literal}

              jQuery(document).ready(function() {
               var cond1="{/literal}{$cond1}{literal}";
                    var kURL="module=evvtApps&action=evvtAppsAjax&file=getMagazzino_CRP_custom9283c75c41c96effa488c0a918304f7b&condit="+cond1;


                    var element = jQuery("#Magazzino_CRP_custom9283c75c41c96effa488c0a918304f7b").kendoGrid({        dataBound: function (e) {

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

                                var grid = $("#Magazzino_CRP_custom9283c75c41c96effa488c0a918304f7b").data("kendoGrid");

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

