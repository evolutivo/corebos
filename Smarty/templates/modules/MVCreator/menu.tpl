<link type="text/css" href="Smarty/templates/modules/MVCreator/jquery-ui.css" rel="stylesheet"/>
<script type="text/javascript" src="Smarty/templates/modules/MVCreator/jquery-1.10.2.js"></script>
<script type="text/javascript" src="Smarty/templates/modules/MVCreator/jquery-ui.js"></script>
<link type="text/css" href="modules/MVCreator/css/style.css" rel="stylesheet"/>
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.min.css" rel="stylesheet"/>
<script type="text/javascript" src="modules/MVCreator/language/{$currlang}.lang.js"></script>
<script type="text/javascript" src="modules/MVCreator/functions.js"></script>
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
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable">{$MOD.CreateScriptReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable2">{$MOD.CreateScriptNameReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=FSscript">{$MOD.CreazioneScriptFS}</a></li>*}
</ul>

<div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel"
     aria-labelledby="tab-default-1__item">
    {include file="modules/MVCreator/createView.tpl"}
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


<script>

    function  closeView() {
        if (confirm('Are you sure you want to close this page')) {
            location.reload();
        } else {
            // Do nothing!
        }

    }


</script>
