<link href='modules/evvtApps/vtapps/bs3/css/bootstrap.min.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/js/jquery-ui/jquery-ui-1.10.1.custom.min.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/css/bootstrap-reset.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/font-awesome/css/font-awesome.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/js/jvector-map/jquery-jvectormap-1.2.2.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/css/clndr.css' rel='stylesheet'>
<!--clock css-->
<link href='modules/evvtApps/vtapps/js/css3clock/css/style.css' rel='stylesheet'>
<!--Morris Chart CSS -->
<link rel='stylesheet' href='modules/evvtApps/vtapps/js/morris-chart/morris.css'>
<link rel='stylesheet' href='modules/evvtApps/vtapps/js/scripts.js'>
<!-- Custom styles for this template -->
<link href='modules/evvtApps/vtapps/css/style.css' rel='stylesheet'>
<link href='modules/evvtApps/vtapps/css/style-responsive.css' rel='stylesheet'/>

<script src="modules/evvtApps/vtapps/js/jquery.js"></script>
<script src="modules/evvtApps/vtapps/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
<script src="modules/evvtApps/vtapps/bs3/js/bootstrap.min.js"></script>
<script src="modules/evvtApps/vtapps/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="modules/evvtApps/vtapps/js/jquery.scrollTo.min.js"></script>
<script src="modules/evvtApps/vtapps/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="modules/evvtApps/vtapps/js/jquery.nicescroll.js"></script>
<!--common script init for all pages-->
<script src="modules/evvtApps/vtapps/js/scripts.js"></script>
<script type="text/javascript" charset="utf-8">
        jQuery.noConflict();
</script>

<script src="modules/evvtApps/js/jquery.min.js"></script>         
<script src="modules/evvtApps/js/kendo.web.js"></script>
<link href="modules/evvtApps/styles/kendo.common.css" rel="stylesheet" />
<link href="modules/evvtApps/styles/kendo.default.css" rel="stylesheet" />
<script type="text/javascript" charset="utf-8">
            var jQuery_auto=    jQuery.noConflict();
</script>
<script src="modules/evvtApps/vtapps/{$appid}/vtapp_form.js"></script>

            <style scoped>
                {literal}
                .k-autocomplete
                {
                    width: 250px;
                    height: 30px;
                    vertical-align: middle;
                }
                #search1{height: 25px;}
                #search2{height: 25px;}
            </style>
                <script>
                    
                    function show_block(str,id,index){
                    //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=display&id='+id+'&module_selected='+document.getElementById('module_selected').value);
                        $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=display&id='+id+'&index='+index+'&module_selected='+document.getElementById('module_selected'+index).value+'&module_related='+document.getElementById('module_related'+index).value,
                          {onsave_secondary:'{/literal}{$onsave_secondary}{literal}',
                           onsave_primary:'{/literal}{$onsave_primary}{literal}',
                           add_document:'{/literal}{$add_document}{literal}',
                           edit_this:'{/literal}{$edit_this}{literal}',
                           go_to_dtlview:'{/literal}{$go_to_dtlview}{literal}',
                           profile:{/literal}{$Profile}{literal},
                           appid:'{/literal}{$appid}{literal}'},
                          function(data,status){
                            $('#div_kendo'+index).html(data);
                          });
                          //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=add_new&type=edit&&id='+id+'&module_selected='+document.getElementById('module_selected').value);
                          //$.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=add_new&type=edit&id='+id+'&module_selected='+document.getElementById('module_selected').value,
                          //{mod:''},
                          //function(data,status){
                          //document.getElementById('div_kendo_2').style.display='block'; 
                          //  $('#div_kendo_2').html(data);
                          //});
                          }
                    if('{/literal}{$onopen_primary}{literal}' =='autoshow' && '{/literal}{$OUTSIDE_ID}{literal}' != '')
                    {
                        show_block('','{/literal}{$OUTSIDE_ID}{literal}','1');
                    }
                    if('{/literal}{$onopen_secondary}{literal}' =='autoshow' && '{/literal}{$SLAVE_ID}{literal}' != '')
                    {
                        show_block('','{/literal}{$SLAVE_ID}{literal}','2');
                    }
                    
                    //if('{/literal}{$MODULE3}{literal}' != '')
                    //{
                     //   show_block('',{/literal}{$OUTSIDE_ID}{literal},'1');
                    //}
                    function create_new(index,related_id,id){
                    //alert(str);
                        $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=add_new&type=create&related_id='+related_id+'&id='+id+'&module_selected='+document.getElementById('module_selected'+index).value+'&index='+index,
                          {onsave_secondary:'{/literal}{$onsave_secondary}{literal}',
                           onsave_primary:'{/literal}{$onsave_primary}{literal}',
                           profile:{/literal}{$Profile}{literal},
                           appid:'{/literal}{$appid}{literal}'
                            },
                          function(data,status){
                          //document.getElementById('div_kendo_2').style.display='none'; 
                            $('#div_kendo'+index).html(data);
                                                       
                          });
                          }
                          
                    if('{/literal}{$onopen_primary}{literal}' =='autocreate' )
                    {
                        create_new('1','','');
                    }
                    if('{/literal}{$onopen_secondary}{literal}' =='autocreate' && '{/literal}{$OUTSIDE_ID}{literal}' != '')
                    {
                        create_new('2','{/literal}{$OUTSIDE_ID}{literal}','');
                    }
                     

                    if('{/literal}{$onopen_primary}{literal}' =='autoedit' )
                    {
                        create_new('1','','{/literal}{$OUTSIDE_ID}{literal}');
                    }
                    
                    
                       jQuery_auto(document).ready(function () {

                        jQuery_auto('#search1').kendoAutoComplete({
                        minLength: 1,
                        dataTextField:'name',
                        template: '${ data.str } ',
                        filter: 'startswith',
                        select: function(e) {
                           var dataItem = this.dataItem(e.item.index());
                            var name = dataItem.name;
                            var id = dataItem.id;
                            show_block(name,id,'1');
                        },
                        dataSource:{
                        serverFiltering:true,
                            transport:{
                                read:{
                                    url:'index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=autosuggest',
                                    serverPaging:true,
                                    pageSize:20,
                                    contentType:'application/json; charset=utf-8',
                                    type:'GET',
                                    dataType:'json'
                                },
                                        parameterMap: function(options, operation) {
                                            return {
                                                StartsWith: options.filter.filters[0].value,
                                                module_selected: document.getElementById('module_selected1').value
                                            }
                                }
                            }
                        }
                        })
                        
                         jQuery_auto('#search2').kendoAutoComplete({
                        minLength: 1,
                        dataTextField:'name',
                        template: '${ data.str } ',
                        filter: 'startswith',
                        select: function(e) {
                           var dataItem = this.dataItem(e.item.index());
                            var name = dataItem.name;
                            var id = dataItem.id;
                            show_block(name,id,"2");
                        },
                        dataSource:{
                        serverFiltering:true,
                            transport:{
                                read:{
                                    url:'index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=autosuggest',
                                    serverPaging:true,
                                    pageSize:20,
                                    contentType:'application/json; charset=utf-8',
                                    type:'GET',
                                    dataType:'json'
                                },
                                        parameterMap: function(options, operation) {
                                            return {
                                                StartsWith: options.filter.filters[0].value,
                                                module_selected: document.getElementById('module_selected2').value
                                            }
                                }
                            }
                        }
                        })
                        
                        });
                    {/literal}     
        </script> 
        {include file='vtapp_form_inside.tpl'}
