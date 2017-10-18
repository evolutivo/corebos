<link type="text/css" href="Smarty/templates/modules/MapGenerator/jquery-ui.css" rel="stylesheet"/>
<script type="text/javascript" src="Smarty/templates/modules/MapGenerator/jquery-1.10.2.js"></script>
<script type="text/javascript" src="Smarty/templates/modules/MapGenerator/jquery-ui.js"></script>
<link type="text/css" href="modules/MapGenerator/css/style.css" rel="stylesheet"/>
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.min.css" rel="stylesheet"/>
<script type="text/javascript" src="modules/MapGenerator/language/{$currlang}.lang.js"></script>
<script type="text/javascript" src="modules/MapGenerator/js/functions.js"></script>
<script type="text/javascript" src="modules/MapGenerator/jquery/script.js"></script>
<script type="text/javascript" src="modules/MapGenerator/js/MapGenerator.js"></script>
<script src="https://use.fontawesome.com/c74e66ed00.js"></script>

{literal}
    <style>
        #mvtitle {
            height: 50px;
            font-weight: bold;
            text-align: center;
            color: #717171;
            font-family: verdana;
            font-size: 34px;
            margin-top: 2%;
        }
    </style>
    <script>
        // jQuery(function() {
        //   jQuery( "#tabs" ).tabs();
        // });


        function selectTab(elmnt) {
            // slds-active
            $(elmnt).parent("li").siblings(".slds-active").removeClass("slds-active");
            $(elmnt).parent("li").addClass("slds-active");

            // tabindex
            $(elmnt).parent("li").siblings().children("a").attr("tabindex", -1);
            $(elmnt).attr("tabindex", 0);

            // aria-selected
            $(elmnt).parent("li").siblings().children("a").attr("aria-selected", false);
            $(elmnt).attr("aria-selected", true);

            // hiding previouly selected tab (slds-show/slds-hide)
            $(elmnt).closest(".slds-tabs--default").children("div[role='tabpanel'].slds-show").addClass("slds-hide");
            $(elmnt).closest(".slds-tabs--default").children("div[role='tabpanel'].slds-show").removeClass("slds-show");
            // displaying newly selected tab (slds-show/slds-hide)
            $(elmnt).closest(".slds-tabs--default").children("div[aria-labelledby='" + elmnt.id + "']").addClass("slds-show");
            $(elmnt).closest(".slds-tabs--default").children("div[aria-labelledby='" + elmnt.id + "']").removeClass("slds-hide");
        }


    </script>
{/literal}
<div id="mvtitle">{$MOD.MVCreator}</div>
<div /*id="tabs"*/ class="slds-tabs--default" style="width: 70%; margin-left: 20%; margin-top: 2%; height:auto">
<ul class="slds-tabs--default__nav" role="tablist">
    <li class="slds-tabs--default__item slds-text-heading--label slds-active" title="{$MOD.CreateView}"
        role="presentation">
        <a class="slds-tabs--default__link" href="" role="tab" tabindex="0" aria-selected="true"
           aria-controls="tab-default-1" id="tab-default-1__item" onclick="selectTab(this);">{$MOD.CreateView}</a>
    </li>

    <li class="slds-tabs--default__item slds-text-heading--label" title="Load  Map"
        role="presentation">
        <a class="slds-tabs--default__link" href="" role="tab" tabindex="-1" aria-selected="false"
           aria-controls="tab-default-2" id="tab-default-2__item"
           onclick="selectTab(this);LoadPickerMap();">{$MOD.LoadMap}</a>
    </li>
    <li class="slds-tabs--default__item slds-text-heading--label" style="margin-left:30%;" title="{$MOD.exit}"
        role="presentation">
        <a class="slds-tabs--default__link" href="" role="tab" tabindex="-1" aria-selected="false"
           aria-controls="tab-default-2" id="tab-default-2__item"
           onclick="closeView();"><img src="http://img.villaggiomusicale.com/avt_s/223942.jpg" alt="Close view" style="width:50px;height:42px;"></a>
    </li>
    {*<li><a href="#tabs-2">{$MOD.GestioneViste}</a></li>*}
    {*<li><a href="index.php?module=MapGenerator&action=MapGeneratorAjax&file=createView&todo=createReportTable">{$MOD.CreateScriptReport}</a></li>*}
    {*<li><a href="index.php?module=MapGenerator&action=MapGeneratorAjax&file=createView&todo=createReportTable2">{$MOD.CreateScriptNameReport}</a></li>*}
    {*<li><a href="index.php?module=MapGenerator&action=MapGeneratorAjax&file=createView&todo=FSscript">{$MOD.CreazioneScriptFS}</a></li>*}
</ul>

<div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel"
     aria-labelledby="tab-default-1__item">
<div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel"
     aria-labelledby="tab-default-1__item">
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
 <div class="slds-text-title" id='labelNameView' style="float: left; overflow:hidden;"><h3 class="slds-section-title--divider">{$MOD.InsertNameQuery}:</h3></div>
	<div class="slds-form-element__control allinea" id='nameViewDiv'>
	  <div class="slds-form-element"  style="margin:0; width:100%;height:100%; ">
	            <div  class="slds-form-element__control">	                
	                     <input type="text" id="nameView" class="slds-input" name='nameView' placeholder="{$MOD.addviewname}" />	            
	            </div>	            
	        </div>	       
	  </div>
	  	
   <div class="slds-text-title" id='labelNameView' style="float: left; overflow:hidden;"><h3 class="slds-section-title--divider">{$MOD.TypeMapNone}:</h3></div>
	<div class="slds-form-element__control allinea" id='nameViewDiv'>
	  <div class="slds-form-element"  style="width:100%;height:100%; ">
	            <div  class="slds-form-element__control">
	                <div class="slds-select_container">
	                    <select data-load-Map="true" data-type-select="TypeMap"  class="slds-select">
	                    <option value="">{$MOD.TypeMapNone}</option>
	                    <option value="SQL">{$MOD.TypeMapSQL}</option>
	                    <option value="Mapping">{$MOD.TypeMapMapping}</option>
	                    <option value="IOMap">{$MOD.TypeMapIOMap}</option>
	                    <option value="FieldDependency">{$MOD.TypeMapFieldDependency}</option>
	                    <option value="WS">{$MOD.TypeMapWS}</option>
	                    </select>
	                </div>	            
	            </div>
	            
	        </div>
	   </div>	   
</div>	  
</div>
</div>
<div id="tab-default-2" class="slds-tabs--default__content slds-hide" role="tabpanel"
     aria-labelledby="tab-default-2__item">
    <div id="LoadfromMapFirstStep">
        <div class="slds-form-element"  style="margin-left:20%;margin-right: 20%; ">

            <label class="slds-form-element__label" for="select-01">{$MOD.ChoseMapTXT}</label>

            <div class="slds-form-element__control">
                <div class="slds-select_container">
                    <select id="GetALLMaps" class="slds-select">
                    </select>
                </div>
            </div>
        </div>
        <button id="set" style="margin-left:80%;margin-top: 30px;" onclick="NextAndLoadFromMap();"
                class="slds-button slds-button--neutral">Next
        </button>
    </div>
    <div id="LoadfromMapSecondStep">
    </div>
</div>
</div>
</div>
<br/>
<br/>
<br/>

<script>

    function  closeView() {
        if (confirm('Are you sure you want to close this page')) {
            location.reload();
        } else {
            // Do nothing!
        }

    }

 
    App.baseUrl = '{$URLAPP}'+'/';
    

</script>