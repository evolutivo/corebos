<script type="text/javascript" src="modules/MVCreator/bsmSelect/js/jquery.bsmselect.js"></script>
<script type="text/javascript" src="modules/MVCreator/bsmSelect/js/jquery.bsmselect.sortable.js"></script>
<script type="text/javascript" src="modules/MVCreator/bsmSelect/js/jquery.bsmselect.compatibility.js"></script>
<script type="text/javascript" src="modules/MVCreator/jquery/script.js"></script>
<link rel="stylesheet" type="text/css" href="modules/MVCreator/bsmSelect/css/jquery.bsmselect.css">
<link rel="stylesheet" type="text/css" href="modules/MVCreator/bsmSelect/examples/example.css">
<link rel="stylesheet" type="text/css" href="kendoui/styles/kendo.common.min.css">

{*<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>*}
{*<link type="text/css" href="modules/MVCreator/styles/salesforce-lightning-design-system.css" rel="stylesheet"/>*}

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
            <li><a href="javascript:void(0);" id="addJoin" name="radio" onclick="generateJoin(); openalertsJoin();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.AddJoin}</a></li>
            <li><a href="javascript:void(0);" id="deleteLast" name="radio" onclick="openalertsJoin();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.DeleteLastJoin}</a></li>
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
                        <select id="mod" name="mod" onchange="GetFirstModuleCombo(this)" class="slds-select">
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
                        <select id="secmodule" name="secmodule" onchange="GetSecondModuleCombo(this)"
                                class="slds-select">

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
{literal}
    <script>
        //$ = jQuery.noConflict();

        //Modal Open
        $('#saveasmap').click(function () {
            $('#backdrop').addClass('slds-backdrop--open');
            $('#modal').addClass('slds-fade-in-open');
        });

        //Modal Close
        function closeModal() {
            var myLength = $("#SaveasMapTextImput").val();
            if (myLength.length > 5) {
                $('#ErrorVAlues').text('');
                $('#modal').removeClass('slds-fade-in-open');
                $('#backdrop').removeClass('slds-backdrop--open');
                SaveasMap();
            }
            else {
                $('#ErrorVAlues').text('{/literal}{$MOD.morefivechars}{literal}');
            }
        }
        function closeModalwithoutcheck() {
            $('#ErrorVAlues').text('');
            $('#modal').removeClass('slds-fade-in-open');
            $('#backdrop').removeClass('slds-backdrop--open');
        }
    </script>
    <style>

        .alerts {
            padding: 10px;
            background-color: #808080;
            margin: 5px;
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
            background: url("modules/MVCreator/image/spinner_squares_circle.gif") no-repeat center center transparent;
            width: 100%;
            height: 100%;
        }

        .blue-loader .ajax_loader {
            background: url("modules/MVCreator/image/ajax-loader_blue.gif") no-repeat center center transparent;
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


        var JSONForCOndition = [];
        function addINJSON(FirstModuleJSONtxt, FirstModuleJSONval, SecondModuleJSONtxt, SecondModuleJSONval, JSONARRAY) {
            JSONForCOndition.push({
                idJSON: JSONForCOndition.length + 1,
                FirstModuleJSONtext: FirstModuleJSONtxt,
                FirstModuleJSONvalue: FirstModuleJSONval,
                SecondModuleJSONtext: SecondModuleJSONtxt,
                SecondModuleJSONvalue: SecondModuleJSONval,
                selectedfields: {JSONARRAY}
            });
             console.log(JSONForCOndition);
        }
        function openalertsJoin() {
            $('#AlertsAddDiv div').remove();
           // var idJSON = 1;
            var campiSelezionati = [];
            var sel = document.getElementById("selectableFields");
            for (var n = 0; n < sel.options.length; n++) {
                if (sel.options[n].selected == true) {
                    //dd=x.options[i].value;
                    campiSelezionati.push(sel.options[n].value);

                }
            }

            var FrirstMOduleval = $('#mod option:selected').val();// $('#mod').value;
            var FrirstMOduletxt = $('#mod option:selected').text();// $('#mod').value;
            var SecondMOduleval = $('#secmodule option:selected').val();
            var SecondMOduletxt = $('#secmodule option:selected').text();
            addINJSON(FrirstMOduletxt, FrirstMOduleval, SecondMOduletxt, SecondMOduleval, campiSelezionati);
            for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
                var idd = JSONForCOndition[ii].idJSON;
                var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
                var secmod = JSONForCOndition[ii].SecondModuleJSONtext;
                //console.log(idd+firmod+secmod);
                var alerstdiv = alertsdiv(idd, firmod, secmod);
                $('#AlertsAddDiv').append(alerstdiv);
            }

        }

        function alertsdiv(Idd, Firstmodulee, secondmodule) {

            var INSertAlerstJOIN = '<div class="alerts">';
            INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('+Idd+');">&times;</span>';
            INSertAlerstJOIN += '<strong>#' + Idd + 'JOIN!</strong> ' + Firstmodulee + '=>' + secondmodule;
            INSertAlerstJOIN += '</div';
            return INSertAlerstJOIN;
        }


        function closeAlertsAndremoveJoin(remuveid) {

            var check = false;
            for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
                if (ii == remuveid) {
                    check = true
                }
            }

            if (check) {
                var localvar = JSONForCOndition[remuveid].selectedfields.JSONARRAY;
                console.log(localvar);
                $('#selectableFields option:selected').attr("selected", null);
            }
            else {
                alert("{/literal}{$MOD.conditionwrong}{literal}");
            }

        }


        //function for first combo first module
        function GetFirstModuleCombo(selectObject) {
            var value = selectObject.value;
            getSecModule(value);
            getFirstModuleFields(value);
        }
        //function for second combo second module
        function GetSecondModuleCombo(selectObject) {
            var value = selectObject.value;
            getSecModuleFields(value);
        }


        // Creates the buttonset.
        jQuery("#radio").buttonset()
        // Adds our custom CSS class which changes the orientation.
            .addClass("ui-buttonset-vertical")

            // Remove the corner classes that don"t amke sense with the new layout.
            .find("label").removeClass("ui-corner-left ui-corner-right")

        // Hack needed to adjust the top border on the next label uring hover.
            .on("mouseenter", function (e) {
                jQuery(this).next().next().addClass("ui-transparent-border-top");
            })

            // Hack needed to adjust the top border on the next label uring hover.
            .on("mouseleave", function (e) {
                jQuery(this).next().next().removeClass("ui-transparent-border-top");
            })

            // Apply proper corner styles.
            .filter(":first").addClass("ui-corner-top")
            .end()
            .filter(":last").addClass("ui-corner-bottom");

        jQuery("#btnRight").click(function () {
            var selectedItem = jQuery("#leftValues option:selected");
            jQuery("#rightValues").append(selectedItem);
        });

        jQuery("#btnLeft").click(function () {
            var selectedItem = jQuery("#rightValues option:selected");
            jQuery("#leftValues").append(selectedItem);
        });

        //        jQuery('#selectableFields').dblclick(function () {
        //                //add where conditions
        //            var txt = this.id;
        //            var box = jQuery("#condition");
        //            box.val(box.val() + txt);
        //        });
        jQuery(document).on('click', '.addWhereCond', function () {
            var txt = this.id;
            console.log(txt);
            var box = jQuery("#condition");
            box.val(box.val() + txt);
        });

        function addCondition() {
            var txt = " " + jQuery("#qoperators option:selected").val();
            var box = jQuery("#condition");
            box.val(box.val() + txt);
        }
        //        jQuery('#selectableFields').multiSelect({
        //            columns: 4,
        //            placeholder: 'Select Languages',
        //            search: true,
        //            selectAll: true
        //        });

        /// jQuery( "#mode")
        //.selectmenu({change: function( event, ui ) {
        //                                            getSecModule(ui.item);
        //                                            getFirstModuleFields(ui.item)
        //                                              }})
        // .selectmenu("menuWidget" )
        //.addClass( "overflow" );

        // jQuery( "#secmodule")
        //.selectmenu({change: function( event, ui ) {
        //                                             getSecModuleFields(ui.item);
        //                                             }})
        // .selectmenu("menuWidget" )


        jQuery("#selField1").button();
        jQuery("#selField2").button();
        //        var selectMultiple = jQuery("#selectableFields").bsmSelect({
        //            showEffect: function ($el) {
        //                $el.fadeIn();
        //            },
        //            hideEffect: function ($el) {
        //                $el.fadeOut(function () {
        //                    jQuery(this).remove();
        //                });
        //            },
        //            plugins: [jQuery.bsmSelect.plugins.sortable()],
        //            title: 'Select Fields',
        //            highlight: 'highlight',
        //            addItemTarget: 'top',
        //            removeLabel: '<strong>X</strong>',
        //            containerClass: 'bsmContainer',                // Class for container that wraps this widget
        //            listClass: 'bsmList-custom',                   // Class for the list ($ol)
        //            listItemClass: 'bsmListItem-custom',           // Class for the <li> list items
        //            listItemLabelClass: 'bsmListItemLabel-custom', // Class for the label text that appears in list items
        //            removeClass: 'bsmListItemRemove-custom',       // Class given to the "remove" link
        //            extractLabel: function ($o) {
        //
        //                if (typeof $o.parents('optgroup').attr('label') !== "undefined")
        //                    return $o.parents('optgroup').attr('label') + "&nbsp;>&nbsp;" + $o.html();
        //                else {
        //                    var optval = ($o[0].value).split(":");
        //                    var tabl = optval[0].split("_");
        //                    optgr = tabl[1].charAt(0).toUpperCase() + tabl[1].substr(1).toLowerCase();
        //                    return optgr + "&nbsp;>&nbsp;" + $o.html();
        //                }
        //            }
        //        });

    </script>
{/literal}