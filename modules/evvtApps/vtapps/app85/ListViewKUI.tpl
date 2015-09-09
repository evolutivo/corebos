
<div style="float:right"><b>Filtro: Ordini 2012 aperti</b></div>

<div id="vkendo" class="k-content" style="display:none;">
           

            <script>
                  {literal}

              jQuery(document).ready(function() {
                    var kURL="module=evvtApps&action=evvtAppsAjax&file=KendoContent";


                    var element = jQuery("#vtapp10").kendoGrid({        dataBound: function (e) {

                CollapseAllRows();

            }  ,
                        dataSource: new kendo.data.DataSource({  
                            type: "json",
                                    transport: {
                                    read: {
                                        url: 'index.php?'+kURL+'&kaction=retrieve&moduleSelect=TT',
                                        dataType: "json"
                                    },
                                    update: {
                                    url: 'index.php?'+kURL+'&moduleSelect=TT&kaction=save',
                                    dataType: "json"
                                    },
                                    destroy: {
                                    url: 'index.php?'+kURL+'&moduleSelect=TT&kaction=delete',
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
      

                                               serverSorting: true,
                                schema: {
                                model: {
                                    id: "salesordermasterid",
                                                                                
                                    fields: {/literal}{$HELPDESKCOLUMNSCONF}{literal},
                                    }
                                },
                                group: {/literal}{$group}{literal},
                                aggregate: {/literal}{$aggregate}{literal}

                        }),
                        height: 430,
                        sortable: true,
                       // pageable: true,
                         groupable:true,
                        editable: "popup",
                        columns: {/literal}{$HELPDESKCOLUMNS}{literal},
                      });
            });
                 function nonFlatFields(container, options) {
                    jQuery('<input />')
                        .appendTo(container)
			.kendoAutoComplete({
                        minLength: 2,
                        dataSource: {
                        data: {/literal} {$RELATEDTOOPTIONS}{literal}
                        }
                    });
                    }
                    function CollapseAllRows() {

                                var grid = $('#vtapp10').data('kendoGrid');

                                grid.tbody.find('>tr.k-grouping-row').each(

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


