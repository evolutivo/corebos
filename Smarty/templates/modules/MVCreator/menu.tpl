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
  jQuery(function() {
    jQuery( "#tabs" ).tabs();
  });
  </script>
{/literal}
<div id="mvtitle">{$MOD.MVCreator}</div>
<div /*id="tabs"*/ class="slds-tabs--default" style="width: 60%; margin-left: 20%; margin-top: 2%; height:auto">
  <ul class="slds-tabs--default__nav" role="tablist">
    <li class="slds-tabs--default__item slds-active" title="Item One" role="presentation">
	{*href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=querygenerator"<style>*}
	<a class="slds-tabs--default__link" role="tab" tabindex="0" aria-selected="true" aria-controls="tab-default-1" id="tab-default-1__item" href="javascript:void(0);" >{$MOD.CreateView}</a>
	</li>
    {*<li><a href="#tabs-2">{$MOD.GestioneViste}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable">{$MOD.CreateScriptReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=createReportTable2">{$MOD.CreateScriptNameReport}</a></li>*}
    {*<li><a href="index.php?module=MVCreator&action=MVCreatorAjax&file=createView&todo=FSscript">{$MOD.CreazioneScriptFS}</a></li>*}
  </ul>
    <div id="tab-default-1" class="slds-tabs--default__content slds-show" role="tabpanel" aria-labelledby="tab-default-1__item">
	{include file="modules/MVCreator/createView.tpl"}
	
	</div>
  {*<div id="tabs-2">*}
    {*<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>*}
  {*</div>*}
</div>