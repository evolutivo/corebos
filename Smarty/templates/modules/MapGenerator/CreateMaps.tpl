<div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel"
         aria-labelledby="tab-default-1__item">
         <span id="ShowErorrNameMap" class="error" style="margin-left: 227px;padding: 5px;background-color: red;width: 50%;font;font-size: 12px;border-radius: 9px;color: white;float: none;display: none;"> </span>
    <div id="DivObjectID">
       <div class="slds-text-title" id='labelNameView' style="float: left; overflow:hidden;"><h3 class="slds-section-title--divider">{$MOD.NameView}:</h3></div>
    	<div class="slds-form-element__control allinea" id='nameViewDiv'>
    	  <div class="slds-form-element"  style="width:100%;height:100%; ">
    	            <div  class="slds-form-element__control">
    	                <div class="slds-select_container">
    	                    <select  data-load-Map="true" data-type-select="TypeObject" class="slds-select">
        	                    <option value="">{$MOD.ChooseTypeOfMap}</option>
        	                    <option value="MaterializedView">{$MOD.MaterializedView}</option>
        	                    <option value="Script">{$MOD.Script}</option>
        	                    <option value="Map">{$MOD.Map}</option>
                            </select>
    	                </div>
    	            </div>
    	        </div>
    	        
    	   
    	  </div>
    </div>
    <div id="MapDivID" style="display: none;">

    <div class="map-creator-block">

        <div class="insert-name-block">

           <div class="slds-text-title" id='labelNameView' style="float: left; overflow:hidden;"><h3 class="slds-section-title--divider">{$MOD.InsertNameQuery}:</h3></div>
        	<div class="slds-form-element__control allinea" id='nameViewDiv'>
        	  <div class="slds-form-element"  style="margin:0; width:100%;height:100%; ">
        	            <div  class="slds-form-element__control">	                
        	                     <input type="text" minlength="5" id="nameView" name="nameView" data-controll="true" data-controll-idlabel="ShowErorrNameMap" data-controll-file="MapGenerator,CheckNameOfMap" data-controll-id-relation="TypeMaps" class="slds-input" name='nameView' placeholder="{$MOD.addviewname}" />	            
        	            </div>	            
        	        </div>	       
        	  </div>
        </div>

        <div class="choose-type-block">
    	  	
           <div class="slds-text-title" id='labelNameView' style="float: left; overflow:hidden;"><h3 class="slds-section-title--divider">{$MOD.TypeMapNone}:</h3></div>
        	<div class="slds-form-element__control allinea" id='nameViewDiv'>
        	  <div class="slds-form-element"  style="width:100%;height:100%; ">
        	            <div  class="slds-form-element__control">
        	                <div class="slds-select_container">
        	                    <select data-load-Map="true" data-type-select="TypeMap"  data-type-select-module="MapGenerator,ChoseeObject"  id="TypeMaps" class="slds-select" disabled>
            	                    <option value="">{$MOD.TypeMapNone}</option>
            	                    <option value="SQL">{$MOD.ConditionQuery}</option>
            	                    <option value="Mapping">{$MOD.TypeMapMapping}</option>
            	                    <option value="IOMap">{$MOD.TypeMapIOMap}</option>
            	                    <option value="FieldDependency">{$MOD.TypeMapFieldDependency}</option>
                                    <option value="FieldDependencyPortal">{$MOD.FieldDependencyPortal}</option>
                                    <option value="WS">{$MOD.TypeMapWS}</option>
                                    <option value="MasterDetail">{$MOD.MasterDetail}</option>
                                    <option value="ListColumns">{$MOD.ListColumns}</option>
                                    <option value="Module_Set">{$MOD.module_set}</option>
                                    <option value="GlobalSearchAutocomplete">{$MOD.GlobalSearchAutocompleteMapping}</option>
                                    <option value="CREATEVIEWPORTAL">{$MOD.CREATEVIEWPORTAL}</option>
                                    <option value="ConditionExpression">{$MOD.ConditionExpression}</option>
                                    <option value="DETAILVIEWBLOCKPORTAL">{$MOD.DETAILVIEWBLOCKPORTAL}</option>
                                    <option value="MENUSTRUCTURE">{$MOD.MENUSTRUCTURE}</option>
                                    <option value="RecordAccessControl">{$MOD.RecordAccessControl}</option>
                                    {* <!-- <option value="ConditionQuery">{$MOD.ConditionQuery}</option> --> *}
        	                    </select>
        	                </div>
        	            </div>
        	            
        	        </div>
        	   </div>	

           </div>

        </div>

    </div>	  
    </div>