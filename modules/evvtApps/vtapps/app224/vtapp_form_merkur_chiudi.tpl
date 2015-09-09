
<link href='modules/evvtApps/vtapps/bs3/css/bootstrap.min.css' rel='stylesheet'>


<script src="modules/evvtApps/vtapps/js/jquery.js"></script>
<!--common script init for all pages-->

<script src="modules/evvtApps/vtapps/{$appid}/vtapp_form.js"></script>
<script type="text/javascript" charset="utf-8">
        var jQuery= jQuery.noConflict();
</script>
<link rel="stylesheet" href="Smarty/templates/angular/bootstrap.min.css">
<link rel="stylesheet" href="Smarty/templates/angular/bootstrap-theme.min.css">
<link rel="stylesheet" href="Smarty/templates/angular/ng-table.css">

<script src="Smarty/templates/angular/angular.min.js"></script>
<script src="Smarty/templates/angular/ng-table.js"></script>
<script src="modules/evvtApps/vtapps/{$appid}/app.js"></script>
<script src="modules/evvtApps/vtapps/{$appid}/ui-bootstrap-tpls-0.11.0.min.js"></script>

<style>
    {literal}
    div{font-size:medium;}
    {/literal}
    </style>
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
                    //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur_chiudi&kaction=display&id='+id+'&module_selected='+document.getElementById('module_selected').value);
                        $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur_chiudi&kaction=display&id='+id+'&index='+index+'&module_selected='+document.getElementById('module_selected'+index).value+'&module_related='+document.getElementById('module_related'+index).value,
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
                          //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur_chiudi&kaction=add_new&type=edit&&id='+id+'&module_selected='+document.getElementById('module_selected').value);
                          //$.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur_chiudi&kaction=add_new&type=edit&id='+id+'&module_selected='+document.getElementById('module_selected').value,
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
                        $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur_chiudi&kaction=add_new&type=create&related_id='+related_id+'&id='+id+'&module_selected='+document.getElementById('module_selected'+index).value+'&index='+index,
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
                                        
                       
                        
                       
                    {/literal}     
        </script> 
        {include file='vtapp_form_inside_merkur_chiudi.tpl'}
