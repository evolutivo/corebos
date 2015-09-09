
<table width='100%' border='0' style='padding-top:0px;' ng-app="myApp" ng-controller="mainCtrl">
        <tr><td width ="35%">  
                <table>
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName" > Carica Parte di Ricambio nel CAR KIT &nbsp;</font>
                        </td>
                    <input type="hidden" name="module_selected" id="module_selected">
                    <input type="hidden" name="module_related" id="module_related">               
                {if $searchable_outside eq 'searchable_outside'}
                    <td  >
                        {literal}
                            <input type='hidden' value="{{customSelected.productid }}" id="product_selected_id">
                        <input type="text" ng-model="customSelected" placeholder="Type Number or Name" 
                               typeahead="state as state.productname+' '+state.productsheet for state in statesWithFlags | filter:$viewValue"  class="form-control">
                        {/literal}
                        </td>
                {/if}
                    
                {if $create_new_outside}
                    <td>
                    <button type="button" class="btn btn-round btn-primary" id="add_button"
                     onclick='create_new("1");'>+ Aggiungi nuovo </button>
                    </td>
                {/if}
            </tr></table>
                
            </td>
        
            <td  width ="30%"> <table >
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName">Controlla disponibilità sul CAR KIT STOCK &nbsp;</font>
                        </td>
                    <input type="hidden" name="module_selected1" id="module_selected1" value="{$MODULE1}" >
                    <input type="hidden" name="module_related1" id="module_related1" value="{$MODULE2}">
                    
                    <input type="hidden" name="module_selected2" id="module_selected2" value="{$MODULE2}">
                    <input type="hidden" name="module_related2" id="module_related2" value="{$MODULE1}">
                {if $searchable_primary}
                    <td>
                <input type='text' name='search2' id='search2' style='width:300px;'>
                    </td>
                {/if}
                {if $create_new_primary}
                    <td>
                    <button type="button" class="btn btn-round btn-primary" id="add_button"
                     onclick='create_new("2");'>+ Aggiungi nuovo </button>
                    </td>
                {/if}
            </tr></table>
               
            </td>
            {if $MODULE3 neq ''}
                <td>                    
                <table>
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName">{$MODULE3} &nbsp;</font>
                        </td>
                    <input type="hidden" name="module_selected3" id="module_selected3" value="{$MODULE3}" >
                   
                   {if $searchable_secondary}
                        <td>
                <input type='text' name='search3' id='search3' style='width:300px;'>
                        </td>
                {/if}
 
                {if $create_new_secondary}
                    <td>
                    <button type="button" class="btn btn-round btn-primary" id="add_button"
                     onclick='create_new("3");'>+ Aggiungi nuovo </button>
                        </td>
                    {/if}
            </tr></table>
            </td>
            {/if}
            </tr>
                
                <tr>
                    <td style='vertical-align: top;' class="alert alert-info" width="50%" >
                        
                        
                            <div class="alert alert-info">
                              <input type="button" name="execute_script" id="execute_script" value="Execute" onclick="javascript:moveStock();" />
                      
                        <div id="div_kendo1">                    
                        </div>
                    </td>
                    
                    <td style='vertical-align: top;' class="alert alert-info"  width="50%">
                   
                            <div >
                                {literal}
                                <table ng-table="tableParams" show-filter="true" class="table table-striped  ">
                                        <tr ng-repeat="user in $data">
                                        <td data-title="'Nome Prodotto'" filter="{ 'productname': 'text' }">
                                            {{user.productname}}
                                        </td>
                                        <td data-title="'Descrizione prodotto'" filter="{ 'productsheet': 'text' }">
                                            {{user.productsheet}}
                                        </td>
                                    </tr>
                                    </table>     
                                {/literal}
                                </div>
                       
                    </td>
                    {if $MODULE3 neq ''}
                        <td style='vertical-align: top;' class="small lvt" >
                        <div id="div_kendo3" >                    
                        </div>
                    </td>
                        {/if}
                    
                </tr>


                </table>
                       
