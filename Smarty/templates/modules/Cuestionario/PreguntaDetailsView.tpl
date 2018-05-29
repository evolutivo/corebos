<script src="kendoui/js/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
        jQuery.noConflict();
</script>
<script src="kendoui/js/jquery.jeditable.js"></script>

<script src="kendoui/js/kendo.web.min.js"></script>
<script src="kendoui/js/cultures/kendo.culture.en-US.min.js"></script>
<script src="include/js/Inventory.js"></script>
<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<div id="example" class="k-content">
            <div id="grid"></div>

            <script>
            var record="{$ID}";
            {literal}
              var kURL = "module=Cuestionario&action=CuestionarioAjax&file=KendoContent";

                jQuery(document).ready(function () {
                      kendo.culture("en-US");
                      var  dataSource = new kendo.data.DataSource({

                            transport: {
                                read:  {
                                    url: 'index.php?'+kURL+'&kaction=retrieve&record='+record,
                                    dataType: "json"

                                },
                                update: {
                                    url: 'index.php?'+kURL+'&kaction=update&record='+record,
                                    dataType: "json"
                                },
                                destroy: {
                                    url: 'index.php?'+kURL+'&kaction=destroy',
                                    dataType: "json"
                                },
                                create: {
                                    url: 'index.php?'+kURL+'&kaction=create&record='+record,
                                    dataType: "json"
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
                                    id: "preguntasid",
                                    fields:{						cid: {type:"number", editable: false,nullable: true },

name: {type:"string" },

					si: {type:"number", editable: false,nullable: true },
                                        url: { editable: false,nullable: true },
                                        no: {type:"number", editable: false,nullable: true },
					cat: { type: "string", validation: {  min: 1} },
                                       subcat: { type: "string", validation: {  min: 1} },

                                    }
                                }
                            }

                        });
var preg1=new kendo.data.DataSource({
                                         transport: {
                                                read: {
                                                       url:'index.php?'+kURL+'&kaction=preg',
                                                       dataType: "json"
                                                       }
                                            }
                                    });


                       jQuery("#grid").kendoGrid({
                        dataSource: dataSource,
                        pageable: true,
                        groupable: true,
                        height: 500,
                                          toolbar: ['create','save','cancel'],

                        filterable:true,
                        sortable: true,
                        resizable: true,
                        columns: [                            {field: "cid", title: "Numero" , hidden: true},

                            {field:"name", title: "Domanda",template: ' <a href="#=url#" target="_blank">#= name#</a>',editor:preg },
                            {field: "si", title: "Punti sì" },
                            { field: "no", title:"Punti no"},
                            { field: "cat", title:"Categoria"},
                            { field: "subcat",title:"Subcategoria" },
                            { command: ["destroy"], title: "&nbsp;" , width: "170px"}],
                            editable: "inline"
                     });
     function preg(container, options) {
jQuery('<input name="' + options.field + '" multiple/>').appendTo(container).kendoComboBox({
                        index: 0,
                        placeholder: "Domanda",
                        autoBind: false,                                       
                        dataTextField: "preg",
                                        dataValueField: "pregid",
                                        dataSource:preg1
                                    });
                                    }
                });
             




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
                #k-grid-view
                {
                    color: #8c8c8c;
                }

                 {/literal}
                </style>
        </div>

