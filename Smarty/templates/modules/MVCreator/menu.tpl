<link type="text/css" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link type="text/css" href="modules/MVCreator/css/style.css" rel="stylesheet" />
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet" />
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.min.css" rel="stylesheet" />
<script type="text/javascript" src="modules/MVCreator/functions.js"></script>
{literal}
<style>
#mvtitle{ 
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
            $(elmnt).closest(".slds-tabs--default").children("div[aria-labelledby='"+elmnt.id+"']").addClass("slds-show");
            $(elmnt).closest(".slds-tabs--default").children("div[aria-labelledby='"+elmnt.id+"']").removeClass("slds-hide");
        }


  </script>
{/literal}
<div id="mvtitle">{$MOD.MVCreator}</div>
<div /*id="tabs"*/ class="slds-tabs--default" style="width: 60%; margin-left: 20%; margin-top: 2%; height:auto">
  <ul class="slds-tabs--default__nav" role="tablist">
    <li class="slds-tabs--default__item slds-text-heading--label slds-active" title="{$MOD.CreateView}" role="presentation">
       <a class="slds-tabs--default__link" href="" role="tab" tabindex="0" aria-selected="true" aria-controls="tab-default-1" id="tab-default-1__item" onclick="selectTab(this);">{$MOD.CreateView}</a>
    </li>

    <li class="slds-tabs--default__item slds-text-heading--label" title="Load  Map"
              role="presentation">
              <a class="slds-tabs--default__link" href="" role="tab" tabindex="-1" aria-selected="false" aria-controls="tab-default-2" id="tab-default-2__item" onclick="selectTab(this);">Load Map</a>
    </li>

    {*<li><a href="#tabs-2">{$MOD.GestioneViste}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable">{$MOD.CreateScriptReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable2">{$MOD.CreateScriptNameReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=FSscript">{$MOD.CreazioneScriptFS}</a></li>*}
  </ul>

    <div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel" aria-labelledby="tab-default-1__item">
             {include file="modules/MVCreator/createView.tpl"}
    </div>
   <div id="tab-default-2" class="slds-tabs--default__content slds-hide" role="tabpanel" aria-labelledby="tab-default-2__item">
         <div style="margin-top: 30px;" role="status" class="slds-spinner slds-spinner--medium">
        <span class="slds-assistive-text">Loading</span>
        <div class="slds-spinner__dot-a"></div>
        <div class="slds-spinner__dot-b"></div>
      </div>
        
   </div>
  
</div>
