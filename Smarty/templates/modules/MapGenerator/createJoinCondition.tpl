<script type="text/javascript" src="modules/MapGenerator/bsmSelect/js/jquery.bsmselect.js"></script>
<script type="text/javascript" src="modules/MapGenerator/bsmSelect/js/jquery.bsmselect.sortable.js"></script>
<script type="text/javascript" src="modules/MapGenerator/bsmSelect/js/jquery.bsmselect.compatibility.js"></script>
<script type="text/javascript" src="modules/MapGenerator/jquery/script.js"></script>
<link rel="stylesheet" type="text/css" href="modules/MapGenerator/bsmSelect/css/jquery.bsmselect.css">
<link rel="stylesheet" type="text/css" href="modules/MapGenerator/bsmSelect/examples/example.css">
<link rel="stylesheet" type="text/css" href="kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" href="http://icono-49d6.kxcdn.com/icono.min.css">


{*<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>*}
{*<link type="text/css" href="modules/MapGenerator/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>*}

<div id="LoadingImage" style="display: none">
    <img src=""/>
</div>


<div class="subTitleDiv" id="subTitleDivJoin" style="margin-top: 1%">
    <center><b>{$MOD.CreateJoinCondition}</b></center>
</div>
<div id="contentJoinButtons">
    <div class="slds-grid slds-grid--vertical slds-navigation-list--vertical"
         style="float:left; overflow: hidden;width:20%" id="buttons">

        <ul id="LDSstyle">
            <li><a href="javascript:void(0);" id="addJoin" name="radio" onclick="showform(this);"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.AddJoin}</a></li>
        <!--    <li><a href="javascript:void(0);" id="deleteLast" name="radio" onclick="openalertsJoin();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.DeleteLastJoin}</a></li>-->
            <li><a href="javascript:void(0);" id="create" name="radio" onclick="creaVista();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateMaterializedView}</a></li>
            <li><a href="javascript:void(0);" id="createscript" name="radio" onclick="generateScript();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateScript}</a></li>
            <li><a href="javascript:void(0);" id="createmap" name="radio" onclick="SaveMap();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateMap}</a></li>
            <li><a href="javascript:void(0);" id="saveasmap" name="radio"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.SaveAsMap}</a></li>


        </ul>

    </div>
   <div class="mailClient mailClientBg" style="position: absolute; width: 350px; height:110px;z-index: 90000; display: none;" id="userorgroup" name="userorgroup">
   <center><b>{$MOD.addjoin}</b>: <select name="usergroup" id="usergroup" style="width:30%"><option value="none">None</option><option value="user">User</option><option value="group">Group</option>
   </select><br><br><b>{$MOD.addCF}</b>: <select name="CFtables" id="cf" style="width:30%"><option value="none">None</option><option value="cf">CF</option></select>
   <br><br><br><input class="crmbutton small edit" type="button" name="okbutton" id="okbutton" value="OK" onclick="generateJoin();hidediv('userorgroup');openalertsJoin();"></center></div>
   {*
    <div style="float:left; overflow: hidden;width:20%" id="buttons" >
        <div id="radio">
        <input type="radio" id="addJoin" name="radio"  onclick="generateJoin();"/>
        <label for="addJoin">{$MOD.AddJoin}</label>
        <input type="radio" id="deleteLast" name="radio"  onclick="deleteLastJoin();"/>
        <label for="deleteLast">{$MOD.DeleteLastJoin}</label>
        <input type="radio" id="delete" name="radio" />
        <label for="delete">{$MOD.DeleteQuery}</label>
        <input type="radio" id="create" name="radio"   onclick="creaVista();"/>
        <label for="create">{$MOD.CreateMaterializedView}</label>
        <input type="radio" id="createscript" name="radio"  onclick="generateScript();"/>
        <label for="createscript">{$MOD.CreateScript}</label>
        <input type="radio" id="createmap" name="radio" onclick="generateMap();"/>
        <label for="createmap">{$MOD.CreateMap}</label>
        </div>
    </div>
    *}

    <div>
        <div class="slds">

            <div class="slds-modal" aria-hidden="false" role="dialog" id="modal">
                <div class="slds-modal__container">
                    <div class="slds-modal__header">
                        <button class="slds-button slds-button--icon-inverse slds-modal__close" onclick="closeModal()">
                            <svg aria-hidden="true" class="slds-button__icon slds-button__icon--large">
                                <use xlink:href="/assets/icons/action-sprite/svg/symbols.svg#close"></use>
                            </svg>
                            <span class="slds-assistive-text">{$MOD.close}</span>
                        </button>
                        <h2 class="slds-text-heading--medium">{$MOD.mapname}</h2>
                    </div>
                    <div class="slds-modal__content slds-p-around--medium">
                        <div>
                            <div class="slds-form-element">
                                <label class="slds-form-element__label" for="input-unique-id">
                                    <abbr id="ErrorVAlues" class="slds-required" title="{$MOD.requiredstring}">*</abbr>{$MOD.required}</label>
                                <input style="width: 400px; " type="text" id="SaveasMapTextImput" required=""
                                       class="slds-input" placeholder="{$MOD.mapname}">
                                <div class="slds-form-element__control">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slds-modal__footer">
                        <button class="slds-button slds-button--neutral" onclick="closeModalwithoutcheck();">{$MOD.cancel}
                        </button>
                        <button onclick="closeModal();" class="slds-button slds-button--neutral slds-button--brand">
                            {$MOD.save}
                        </button>
                    </div>
                </div>
            </div>
            <div class="slds-backdrop" id="backdrop"></div>

            <!-- Button To Open Modal -->
            {*<button class="slds-button slds-button--brand" id="toggleBtn">Open Modal</button>*}
        </div>

    </div>


    <div id="selJoin" style="float:left; overflow: hidden;width:80%">
        <div style="float:left; overflow: hidden;width:45%" id="sel1">
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                    <div class="slds-select_container">
                        {*{if !empty($FirstSecModule)}*}
                        {*<select id="mod" name="mod" class="slds-select">*}
                        {*{foreach from=$FirstSecModule item=v}*}
                        {*<option value="{$v['FmoduleName']}">{$v['FmoduleName']}</option>*}
                        {*{/foreach}*}
                        {*</select>*}
                        {*{else}*}
                        <select data-select-autolod="true" data-select-method="GetFirstModuleCombo" id="FirstModul" name="mod" class="slds-select">
                        </select>
                        {*{/if}*}
                    </div>
                </div>
            </div>
            {*       <select class="sel" id="mod" name="mod"></select>
                   <select class="sel" id="selTab1" name="selTab1" onchange="updateSel('selTab1','selField1')">*}
            {*<option selected="selected" disabled="disabled" >Selezionare la prima tabella :</option>
            </select>*}
            {*{if !empty($FirstSecModule)}*}
            {*{foreach from=$FirstSecModule item=v}*}
            {*<input type="button" value="{$v['FmoduleID']}" class="slds-button slds-button--neutral sel"*}
            {*id="selField1" name="selField1" style="padding:0px;">*}
            {*{/foreach}*}
            {*{else}*}
            <input type="button" class="slds-button slds-button--neutral sel" id="selField1" name="selField1"
                   style="padding:0px;">
            {*{/if}*}
        </div>

        <div style="float:left; overflow: hidden;width:3%; margin-left: 2%; margin-right: 2%;" id="centerJoin"> =</div>

        <div style="float:left; overflow: hidden;width:45%" id="sel2">
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                    <div class="slds-select_container">
                        {*{if !empty($FirstSecModule)}*}
                        {*<select id="secmodule" name="secmodule" class="slds-select">*}
                        {*{foreach from=$FirstSecModule item=v}*}
                        {*<option value="{$v['SecmoduleName']}">{$v['SecmoduleName']}</option>*}
                        {*{/foreach}*}
                        {*</select>*}
                        {*{else}*}
                        <select id="secmodule" data-select-autolod="true" data-select-method="GetSecondModuleCombo" name="secmodule" class="slds-select">

                        </select>
                        {*{/if}*}
                    </div>
                </div>
            </div>
            {*       <select class="sel" id="secmodule" name="secmodule">
                   <select class="sel" id="selTab2" name="selTab2" onchange="updateSel('selTab2','selField2')">
                   <option selected="selected" disabled="disabled" >{$MOD.SelectSModule}</option>*}
            </select>
            {*{if !empty($FirstSecModule)}*}
            {*{foreach from=$FirstSecModule item=v}*}
            {*<input type="button" value="{$v['SecmoduleID']}" class="slds-button slds-button--neutral sel"*}
            {*id="selField2" name="selField2" style="padding:0px;">*}
            {*{/foreach}*}
            {*{else}*}
            <input type="button" class="slds-button slds-button--neutral sel" id="selField2" name="selField2"
                   style="padding:0px;">
            {*{/if}*}
        </div>
    </div>
    <br><br>
    <div id="contenitoreJoin">

        <div id="sectionField">

            <div>
                <div class="testoDiv">
                    <center><b>{$MOD.SelectField}</b></center>
                </div>
                <div class="slds-form-element">
                    <label class="slds-form-element__label" for="select-01">{$MOD.selectlabel}</label>
                    <div class="slds-form-element__control">
                        {*{if !empty($Fields)}*}
                        {*<select id="selectableFields" multiple="multiple" name="selectableFields[]">*}
                        {*<optgroup label="{$MOD.OptionsText}">*}
                        {*{foreach from=$Fields item=v}*}
                        {*<option value="{$v['fieldname']}">{$v['fieldname']}</option>*}
                        {*{/foreach}*}
                        {*</optgroup>*}
                        {*</select>*}
                        {*{else}*}
                        <select id="selectableFields" style="margin-left: 20px;width: 200px;height: 230px;"
                                multiple="multiple" name="selectableFields[]">
                            {*<option selected>select the module to fill this </option>*}
                        </select>
                        {*<button style="margin-top:-250px;" class="slds-button slds-button--icon-border-filled" title="Select ">*}
                        {*<svg class="slds-button__icon" aria-hidden="true">*}
                        {*<use xlink:href="/assets/icons/standard-sprite/svg/symbols.svg#case"></use>*}
                        {*</svg>*}
                        {*<span class="slds-assistive-text">select</span>*}
                        {*</button>*}
                        {*<select id="selectableFields1" style="margin-left: 10px;width: 200px;height: 230px;"*}
                        {*multiple="multiple" name="selectableFields1[]">*}
                        {*</select>*}
                        {*{/if}*}
                        <div id="AlertsAddDiv" style="float: right;margin-top: 0px;width: 50%;">
                            {*<div class="alerts">*}
                            {*<span class="closebtns"*}
                            {*onclick="this.parentElement.style.display='none';">&times;</span>*}
                            {*<strong>Danger!</strong> Indicates a dangerous or potentially negative action.*}
                            {*</div>*}

                        </div>
                    </div>
                    {*{if !empty($FirstSecModule)}*}
                    {*<input type="hidden" name="MapID" value="{$MapID}" id="MapID">*}
                    {*{else}*}
                    <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
                    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
                    <input type="hidden" name="querysequence" id="querysequence" value="">
                    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
                    {*{/if}*}


                </div>

                {*<select id="selectableFields" multiple="multiple" name="selectableFields[]"></select>
                <ol id="leftValues">
                </ol>*}

            </div>

            {*            <div class="allinea" id="center">
                            <input type="button" id="btnRight" value="&gt;&gt;" />
                            <input type="button" id="btnLeft" value="&lt;&lt;" />
                        </div>*}
            {*            <div class="allinea" id="right">
                            <div class="testoDiv"> Campi selezionati</div>
                            <select id="rightValues" size="5" multiple></select>
                        </div>*}

        </div>

    </div>
</div>

<div id="generatedquery">
    <div id="results" style="margin-top: 1%;"></div>
</div>
<div id="null"></div>
<div>
  <div id="queryfrommap"></div>
  <div>
         <div class="slds">
             <div class="slds-modal" aria-hidden="false" role="dialog" id="modalrezultquerymodal">
                <div class="slds-modal__container">
                     <div class="slds-modal__header">
                         <button class="slds-button slds-button--icon-inverse slds-modal__close" onclick="closemodalrezultquery()">
                             <svg aria-hidden="true" class="slds-button__icon slds-button__icon--large">
                                 <use xlink:href="/assets/icons/action-sprite/svg/symbols.svg#close"></use>
                             </svg>
                             <span class="slds-assistive-text">{$MOD.close}</span>
                         </button>
                         <h2 class="slds-text-heading--medium">The result of query</h2>
                     </div>
                     <div class="slds-modal__content slds-p-around--medium">
                         <div class="slds-scrollable">
                             <!-- <div class="slds-form-element"> -->
                                <table class="slds-table slds-table_bordered slds-table_cell-buffer" id="insertintobodyrezult">
                                 </table>
                             <!-- </div> -->
                         </div>
                     </div>
                     <div class="slds-modal__footer">
                         <button class="slds-button slds-button--neutral" onclick="closeModalwithoutcheckrezultquery();">{$MOD.cancel}
                         </button>
                         <!-- <button onclick="closemodalrezultquery();" class="slds-button slds-button--neutral slds-button--brand">
                             {$MOD.save}
                         </button> -->
                     </div>
                 </div>
             </div>
             <div class="slds-backdrop" id="backdropquery"></div>

             <!-- Button To Open Modal -->
             {*<button class="slds-button slds-button--brand" id="toggleBtn">Open Modal</button>*}
         </div>

     </div>
{literal}
    <script>
       
    </script>
    <style>

        .alerts {
            padding: 10px;
            background-color: #808080;
            margin: 20px;
            color: white;
        }

        .closebtns {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 20px;
            line-height: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtns:hover {
            color: black;
        }

        #LDSstyle {
            border: 1px solid black;
            margin-right: 0px;
            margin-top: 0px;
            width: 100%;
            height: 100%;
        }

        /*@media(width:1024px){*/
        /*#LDSstyle {*/
        /*font-size: 10*/
        /*}*/
        /*}*/

        #LDSstyle li {
            margin: 0px;
            padding: 0px;
        }

        #LDSstyle a:hover {
            background: #c3cede;
            /*margin-right: 2px;*/
        }

        .ajax_loader {
            background: url("modules/MapGenerator/image/spinner_squares_circle.gif") no-repeat center center transparent;
            width: 100%;
            height: 100%;
        }

        .blue-loader .ajax_loader {
            background: url("modules/MapGenerator/image/ajax-loader_blue.gif") no-repeat center center transparent;
        }

        #feedback {
            font-size: 1.4em;
        }

        #selectable .ui-selecting {
            background: #FECA40;
        }

        #selectable .ui-selected {
            background: #F39814;
            color: white;
        }

        #selectable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }

        #selectable li {
            margin: 3px;
            padding: 0.4em;
            font-size: 1.4em;
            height: 18px;
        }

        /*
         * The buttonset container needs a width so we can stack them vertically.
         *
         */
        #radio {
            width: 85%;
        }

        /*
         * Make each label stack on top of one another.
         *
         */
        .ui-buttonset-vertical label {
            display: block;
        }

        /*
         * Handle colliding borders. Here, we"re making the bottom border
         * of every label transparent, except for labels with the
         * ui-state-active or ui-state-hover class, or if it"s the last label.
         *
         */
        .ui-buttonset-vertical label:not(:last-of-type):not(.ui-state-hover):not(.ui-state-active) {
            border-bottom: transparent;
        }

        /*
         * For lables in the active state, we need to make the top border of the next
         * label transparent.
         *
         */
        .ui-buttonset-vertical label.ui-state-active + input + label {
            border-top: transparent;
        }

        /*
         * Oddly enough, the above style approach doesn"t work for the
         * hover state. So we define this class that"s used by our JavaScript
         * hack.
         *
         */
        .ui-buttonset-vertical label.ui-transparent-border-top {
            border-top: transparent;
        }

        select {
            width: 300px;
        }

        .overflow {
            height: 200px;
        }


        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            top: -5px;
            right: 105%;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }

    </style>
    <script>

        //        $('#selectableFields').on('change', function() {
        //            var value = $(this).val();
        //            $('#selectableFields1').val(value);
        //        });

        //        $(document).ready(function () {
        //
        //            $("#selectableFields option:selected").click(function (e) {
        //                alert('click');
        //            });
        //
        //        });
       

    </script>
{/literal}
