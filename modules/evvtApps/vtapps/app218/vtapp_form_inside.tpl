      <table width='100%' border='0' style='padding-top:0px;'>
        <tr><td>  
                <table>
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName" > {$MODULE1} &nbsp;</font>
                        </td>
                    <input type="hidden" name="module_selected" id="module_selected">
                    <input type="hidden" name="module_related" id="module_related">               
                {if $searchable_outside eq 'searchable_outside'}
                    <td>
                        <input type='text' name='search1' id='search1' style='width:300px;'>
                    </td>{/if}
                    
                {if $create_new_outside}
                    <td>
                    <button type="button" class="btn btn-round btn-primary" id="add_button"
                     onclick='create_new("1");'>+ Aggiungi nuovo </button>
                    </td>
                    {/if}
            </tr></table>
                
            </td>
        <td  > <table >
                    <tr>
                        <td style='vertical-align:middle;'>
                        <font class="moduleName">{$MODULE2} &nbsp;</font>
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
                <td  >                    
                <table >
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
                    <td style='vertical-align: top;' class="small lvt" >
                        <div id="div_kendo1">                    
                        </div>
                    </td>
                    <td style='vertical-align: top;' class="small lvt" >
                        <div id="div_kendo2" >                    
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
   