<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-it.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
 

<script>{literal}
  //onclick="chiudi_chiamata('sospendi');"  
    function chiudi_chiamata(str){
        var orario_inizio=document.getElementById('orario_inizio').value;
        var orario_fine=document.getElementById('orario_fine').value;
        var orario_inizio_time=document.getElementById('orario_inizio_time').value;
        var orario_fine_time=document.getElementById('orario_fine_time').value;
        var note=document.getElementById('note').value;
        var tecnico=document.getElementById("tec_selected_id").value;
        //alert(tecnico);
        var parameters='&orario_inizio='+orario_inizio+'&orario_fine='+orario_fine+'&note='+note+'&tecnico='+tecnico;
        parameters+='&orario_inizio_time='+orario_inizio_time+'&orario_fine_time='+orario_fine_time;
        
     $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=chiudi_chiamata&ajax=true&chiudi_str='+str+'&project_id='+document.getElementById('project_selected_id').value+parameters,
		{mod:''},
          function(data,status){
              if (str==='chiudi') alert('Chiamata chiusa');
              else alert('Chiamata sospesa');//{{customSelected.projectid }}
              //var dialog = $('#224').data("kendoWindow");
              //dialog.refresh('index.php?module=evvtApps&action=evvtAppsAjax&file=vtappaction&vtappaction=getContent&class=CHIUDERE_MERKUR&appid=2246&op=1');
          }); 
          } 
          
  function add_parti(){
        var prodid=document.getElementById('productid_selected_id').value;
        var fine_rip=document.getElementById('fine_rip_selected_id').value;
        
        var parameters='&prodid='+prodid+'&fine_rip='+fine_rip+'&project_id='+document.getElementById('project_selected_id').value;
        
     $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=get_products&ajax=true&kaction=add_parti'+parameters,
		{mod:''},
          function(data,status){
              alert('Parte aggiunta');//{{customSelected.projectid }}
              $scope.tableParams.reload();
          }); 
          } 
{/literal}   
</script>

<table width='100%' border='0' style='padding-top:0px;' ng-app="myApp" ng-controller="mainCtrl">
        <tr><td width ="35%" align="center" colspan="2">  
                <table>
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName" > Cerca Pratica Aperta &nbsp;</font>
                        </td>
                    <input type="hidden" name="module_selected" id="module_selected">
                    <input type="hidden" name="module_related" id="module_related">               
                {if $searchable_outside eq 'searchable_outside'}
                    <td  >
                        {literal}
                            <input type='hidden' value="{{customSelected.projectid }}" id="project_selected_id" >
                        <input type="text" ng-model="customSelected" placeholder="Type Number or Name"  
                               typeahead-min-length='5'
                               typeahead="state as state.projectname+' '+state.project_no for state in statesWithFlags | filter:$viewValue"  
                               class="form-control" style="width:400px;"
                               typeahead-on-select="onSelect($item, $model, $label)">
                        {/literal}
                        </td>
                {/if}
                    
                
            </tr></table>
                
            </td>
        
            
            </tr>
                <tr>
                    <td style='vertical-align: top;' class="alert alert-info" width="50%" style="text-align:center;" colspan="2">
                        <table width="80%" align="center" border="0">
                            <tr><td colspan="2" style="text-align:center;" >{literal}
                                    
                                    <b style="font-size: 20px;">
                                        Pratica <span >{{customSelected.project_no }}</span></b><br/> <br/>
                                </td></tr>
                            <tr><td >
                                  
                                Orario Inizio Intervento <input type="text" id="orario_inizio" placeholder="dd-mm-yyyy" name="orario_inizio" style="width:200px;"/>  <input type="text" placeholder="hh:mm" id="orario_inizio_time" name="orario_inizio_time" style="width:50px;"/>
                                
                                </td>
                            <td >Orario Fine Intervento <input type="text" id="orario_fine" placeholder="dd-mm-yyyy" style="width:200px;"/> <input type="text" placeholder="hh:mm" id="orario_fine_time" name="orario_fine_time" style="width:50px;"/>                               
                                </td></tr>
                            <tr><td >Allega Documento <input type="file" name="allega_doc" id="allega_doc">
                                </td>
                                <td >
                                    Technico del RF
                                    <input type='hidden' value="{{tec.tecnicirf }}" id="tec_selected_id" >
                                    <select ng-model="tec" id="tecnico" ng-options="t.tecnicirf as t.tecnicirf for t in tecnico">
                                    <select> 
                                    {/literal}
                                </td>
                            </tr>
                            <script>
    {literal}
$(document).ready(function () {
    $("#allega_doc").kendoUpload({ 
  async: {
        
        saveUrl: "index.php?module=evvtApps&action=evvtAppsAjax&file=get_products&kaction=upload",
        autoUpload: true
    }

 });


});
{/literal}
</script>
                            <tr><td colspan="2" >
                                    Note <textarea  id="note"></textarea>
                                </td>
                            </tr>
                        </table>
                    </td>
                                      
                </tr>
                <tr>
                    <td style='vertical-align: top;' class="alert alert-info" width="50%" >
                        Parti della Pratica<br/>
                        <div >
                                {literal}
                                <table ng-table="tableParams" show-filter="true" class="table table-striped  ">
                                        <tr ng-repeat="user in $data">
                                        <td data-title="'Nome Prodotto'" filter="{ 'pcdescriptionname': 'text' }">
                                            {{user.pcdescriptionname}}
                                        </td>
                                        <td data-title="'Fine Riparazione'" filter="{ 'fineriparazione': 'text' }">
                                            <span ng-if="!user.$edit">{{user.fineriparazione}}</span>
                                            <div ng-if="user.$edit">
                                                <select  ng-model="user.fineriparazione" id="fineriparazione" ng-options="t.fineriparazione as t.fineriparazione for t in fine_rip">
                                                 <select>              
                                            </div>
                                            
                                        </td>
                                        <td data-title="'Quantità'" >
                                            1
                                        </td>
                                        <td data-title="''" width="50">
                <a ng-if="!user.$edit" href="" class="btn btn-primary btn-xs" ng-click="user.$edit = true;">Edit</a>               
                <a ng-if="user.$edit" href="" class="btn btn-primary btn-xs" ng-click="user.$edit = false;setEditId(user.pcdetailsid,user.fineriparazione)">Save</a>
                 <a ng-if="user.$edit" href="" class="btn btn-primary btn-xs" ng-click="user.$edit = false;">Cancel</a>
            </td> 
                                    </tr>
                                    </table>     
                                {/literal}
                                </div>
                    </td>
                    
                    <td style='vertical-align: top;' class="alert alert-info"  width="50%">
                     Dichiara altre parti
                     <table border="0">
                         <tr><td  ><br/>
                        {literal}
                            <b>Nome Prodotto</b><br/>
                            <input type='hidden' value="{{products_kitSelected.productid }}" id="productid_selected_id" >
                        <input type="text" ng-model="products_kitSelected" placeholder="Type Product Name"  
                               typeahead-min-length='2'
                               typeahead="state as state.productname+' '+state.productsheet for state in products_kit | filter:$viewValue"  
                               class="form-control">
                        {/literal}
                             </td>
                             <td style="width:10px;"  >                        
                             </td>
                         <td  ><br/>
                             {literal}<b>Fine Riparazione</b> <br/>
                            <input type='hidden' value="{{fine_ripSelected.fineriparazione }}" id="fine_rip_selected_id" >
                        <input type="text" ng-model="fine_ripSelected" placeholder="Type Name"  
                               typeahead-min-length='2'
                               typeahead="state as state.fineriparazione for state in fine_rip | filter:$viewValue"  
                               class="form-control">
                        {/literal}
                             </td>
                         <td style="width:10px;" >
                        
                             </td>
                             <td> <br/><br/>
                                 <input type="button" id="chiudi" class="btn btn-info" value="Add" ng-click="add_parti();"/>
                            </td></tr></table>
                       
                    </td>
                   
                </tr>
                <tr><td colspan="2" style='text-align: center;' class="alert alert-info" width="100%" >
                        <input type="button" id="chiudi" class="btn btn-info" value="Chiudi Chiamata" onclick="chiudi_chiamata('chiudi');"/>
                        <input type="button" id="sospendi" class="btn btn-round btn-primary" value="Sospendi Chiamata" onclick="chiudi_chiamata('sospendi');"/>
                        <input type="button" id="annulla" class="btn btn-danger " value="Annulla" onclick=""/>
                    </td></tr>
                </table>
                       
