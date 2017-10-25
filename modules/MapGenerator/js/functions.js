/*
 * Questi tre array vengono passati al compositoreQuery ogni qual volta l'utente
 * aggiunge nuovi JOIN, in questo modo si può vedere la query che si stà creando
 * sempre aggiornata.
 */
var selTab1 = new Array();
var selField1 = new Array();
var selTab2 = new Array();
var selField2 = new Array();
var tabelleSelezionate = new Array();
var installationID;
var nameDb;
var counter = 0;
var firstModule;
var secModule;
var sFieldRel;






$( function() {
    $( document ).tooltip();
  } );


var JSONForCOndition = [];
function addINJSON(FirstModuleJSONtxt, FirstModuleJSONval,FirstModuleJSONField, SecondModuleJSONtxt, SecondModuleJSONval,SecondModuleJSONField,labels,Valueparagrafi, JSONARRAY) {
    JSONForCOndition.push({
        idJSON: JSONForCOndition.length + 1,
        FirstModuleJSONtext: FirstModuleJSONtxt,
        FirstModuleJSONvalue: FirstModuleJSONval,
        FirstModuleJSONfield: FirstModuleJSONField,
        SecondModuleJSONtext: SecondModuleJSONtxt,
        SecondModuleJSONvalue: SecondModuleJSONval,
        SecondModuleJSONfield: SecondModuleJSONField,
        Labels:labels,
        ValuesParagraf:Valueparagrafi,

        // selectedfields: JSONARRAY,
        //selectedfields: {JSONARRAY}
    });

    jQuery.ajax({
		type : 'POST',
		data : {'fields':JSON.stringify(JSONForCOndition),'queryid':document.getElementById('queryid').value,'MapID':$('#MapID').val()},
		url : "index.php?module=MapGenerator&action=MapGeneratorAjax&file=savequeryhistory"
	}).done(function(msg) {
//console.log(msg);
                }
                );
     console.log(JSONForCOndition);
}

//FUNCTIO GET VALUE FROM SELECTED Fields
function selectHtml() {
    //var sel = jQuery('#selectableFields');
   // return sel[0].innerHTML;
     var campiSelezionati = [];
    var sel = document.getElementById("selectableFields");
    for (var n = 0; n < sel.options.length; n++) {
        if (sel.options[n].selected == true) {
            //dd=x.options[i].value;
            campiSelezionati.push(sel.options[n].value);

        }
    }
    return campiSelezionati;
}


function emptycombo(){
    var select = document.getElementById("selectableFields");
    var length = select.options.length;
    var j=0;
    while(select.options.length!=0){
        for (var i1 = 0; i1 < length; i1++) {
            select.options[i1] = null;
        }
    }
}

function openmodalrezultquery(idforquery){
          var selectedfieldsfromhistory=[];
          var queryfromselected;
          for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
             if (ii==idforquery)
             {
                 check=true;
               //   selectedfieldsfromhistory = JSONForCOndition[ii].selectedfields;
                   queryfromselected = $('#generatedjoin').text();

             }

         }

      var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=PreviewRezult";
         jQuery.ajax({
              type: "POST",
              url: url,
              async: false,
              data: "queryhistory=" + queryfromselected,
             success: function (msg) {
                 // alert(msg);
                  $('#backdropquery').addClass('slds-backdrop--open');
                  $('#modalrezultquerymodal').addClass('slds-fade-in-open');
                  jQuery("#insertintobodyrezult").html(msg);
                 //alert();
             },
             error: function () {
                 alert(mv_arr.failedcall);
             }
         });
}
$('#Previewbtn').click(function(){
		  PreviewQuery();
		});
function closeModalForRunquery() {
     //var myLength = $("#SaveasMapTextImput").val();

         $('#ErrorVAlues').text('');
         $('#modalrezultquerymodal').removeClass('slds-fade-in-open');
         $('#backdropquery').removeClass('slds-backdrop--open');


 }

  function closeModalwithoutcheckrezultquery() {
     $('#ErrorVAlues').text('');
     $('#modalrezultquerymodal').removeClass('slds-fade-in-open');
     $('#backdropquery').removeClass('slds-backdrop--open');
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
    var FrirstMOduletxt = $('#mod option:selected').text();// $('#mod').value;generatedConditions
    var SecondMOduleval = secModule;
    var SecondMOduletxt = $('#secmodule option:selected').text();
    var generatedjoin=$( "#generatedjoin" ).html();
    var generatedConditions=$( "#generatedConditions" ).html();
    var selField1 = document.getElementById('selField1').value;
    var selField2 = document.getElementById('selField2').value;
    if(SecondMOduleval==undefined){
      SecondMOduleval='';
    }
    var labels=localStorage.getItem("labels");

    //console.log(labels);
    //console.log(selField1);
    //console.log(selField2);
    //console.log(SecondMOduleval);

    addINJSON(FrirstMOduletxt, FrirstMOduleval,selField1, SecondMOduletxt, SecondMOduleval, selField2, labels, generatedjoin, selectHtml());

	// console.log(FrirstMOduletxt);
	//console.log(FrirstMOduleval);
	// console.log(SecondMOduletxt);
	//console.log(SecondMOduleval);
 //console.log(secModule);
    var check=false;
    var length_history=JSONForCOndition.length;
    //alert(length_history-1);
    for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
        var idd =ii// JSONForCOndition[ii].idJSON;
        var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
        var secmod = JSONForCOndition[ii].SecondModuleJSONtext;
        var selectedfields = JSONForCOndition[ii].ValuesParagraf;
        // console.log(idd+firmod+secmod);
        // console.log(selectedfields);
        if (ii==(length_history-1))
        {
            check=true;

        }
        else{
           check=false;
        }
        var alerstdiv = alertsdiv(idd, firmod, secmod,check);
        $('#AlertsAddDiv').append(alerstdiv);
        // generateJoin();
        // emptycombo();
    }

}


function ReturnAllDataHistory(){

      $('#AlertsAddDiv div').remove();
      $( "#generatedjoin" ).html("");
     var check=false;
     var valuehistoryquery;
    var length_history=JSONForCOndition.length;
    //alert(length_history-1);
    for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
        var idd =ii// JSONForCOndition[ii].idJSON;
        var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
        var secmod = JSONForCOndition[ii].SecondModuleJSONtext;
        valuehistoryquery=JSONForCOndition[ii].ValuesParagraf;
        // console.log(idd+firmod+secmod);
        // console.log(selectedfields);
        if (ii==(length_history-1))
        {
            check=true;

        }
        else{
           check=false;
        }
        var alerstdiv = alertsdiv(idd, firmod, secmod,check);
        $('#AlertsAddDiv').append(alerstdiv);

        $( "#generatedjoin" ).html(valuehistoryquery);

    }

}

function alertsdiv(Idd, Firstmodulee, secondmodule,last_check) {

    var INSertAlerstJOIN = '<div class="alerts" id="alerts_'+Idd+'">';
    INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('+Idd+');">&times;</span>';
    // INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('+Idd+');"><i class="icono-eye"></</span>';
    INSertAlerstJOIN += '<strong>#' + Idd + 'JOIN!</strong> ' + Firstmodulee + '=>' + secondmodule;
    if (last_check==true) {//icono-plusCircle
    INSertAlerstJOIN +='<span title="You are here " style="float:right;margin-top:-10px;margin-right:-46px;"><i class="icono-checkCircle"></i></span>';
    INSertAlerstJOIN +='<span  title="run the query to show the result" style="float:right;margin-top:-10px;margin-right:-86px;"><i class="icono-display" onclick="openmodalrezultquery('+Idd+');"></i></span>';
    }
    else{
        INSertAlerstJOIN +='<span onclick="show_query_History('+Idd +');" title="click here to show the Query" style="float:right;margin-top:-10px;margin-right:-46px;"><i class="icono-plusCircle"></i></span>';
    }
    INSertAlerstJOIN += '</div';
    return INSertAlerstJOIN;
}



function show_query_History(id_history){
 $('#AlertsAddDiv div').remove();
 document.getElementById('querysequence').value=id_history+1;
 for (var ii = 0; ii <= JSONForCOndition.length; ii++) {
        var idd =ii// JSONForCOndition[ii].idJSON;
        //valuehistoryquery = JSONForCOndition[ii].ValuesParagraf;
         var idd =ii// JSONForCOndition[ii].idJSON;
        var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
        var secmod = JSONForCOndition[ii].SecondModuleJSONtext;
         //console.log(idd+firmod+secmod);
        //console.log(selectedfields);
        if (ii==id_history)
        {
            check=true;
             valuehistoryquery = JSONForCOndition[ii].ValuesParagraf;
              $( "#generatedjoin" ).html(valuehistoryquery);

        }
        else{
           check=false;
        }
        var alerstdiv = alertsdiv(idd, firmod, secmod,check);
        $('#AlertsAddDiv').append(alerstdiv);



    }

}
function closeAlertsAndremoveJoin(remuveid,namediv) {

    var check = false;
      for (var ii = 0; ii <= App.popupJson.length-1; ii++) {
          if (ii == remuveid) {
               //JSONForCOndition.remove(remuveid);
            App.popupJson.splice(remuveid,1);
              check = true
        //console.log(remuveid);
             // console.log(ReturnAllDataHistory());
           }
      }
      if (check) {
        var remuvediv="#alerts_"+remuveid;
        $( remuvediv).remove( );
        App.utils.ReturnAllDataHistory2(namediv);

         // $('#selectableFields option:selected').attr("selected", null);
      }
      else {
          alert(mv_arr.ReturnFromPost);
      }


}


function closeAlertsAndremoveJoins(remuveid,namediv){
	  var check = false;
	    for (var ii = 0; ii <= App.JSONForCOndition.length; ii++) {
	        if (ii == remuveid) {
	             //JSONForCOndition.remove(remuveid);
	        	App.JSONForCOndition.splice(remuveid,1);
	            check = true
				//console.log(remuveid);
	           // console.log(ReturnAllDataHistory());
	         }
	    }
	    if (check) {
	      var remuvediv="#alerts_"+remuveid;
	      $( remuvediv).remove( );
	      App.utils.ReturnAllDataHistory(namediv);

	       // $('#selectableFields option:selected').attr("selected", null);
	    }
	    else {
	        alert(mv_arr.ReturnFromPost);
	    }
}


//function for first combo first module
function GetFirstModuleCombo(selectObject) {
    alert("Edmondi ");
    var value = selectObject.value;
    //getSecModule(value);
    //getFirstModuleFields(value);
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







/*
 * Cancella l'ultimo Join, e se è presente solo un Join, richiama la funzione deleteJoin().
 */

function deleteLastJoin() {
    var campiSelezionati = [];
    jQuery('#rightValues :selected').each(function (i, selected) {
        campiSelezionati[i] = jQuery(selected).text();
    });


    if (campiSelezionati.length === 0) {

        alert(mv_arr.inserirecampi);


    } else {


        selTab1.pop();
        selField1.pop();
        selTab2.pop();
        selField2.pop();
        nameDb = (document.getElementById('nameDb').value);
        if (selTab1.length === 0) {
            deleteJoin();
        }
        else {
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=compositoreQuery",
                data: "selTab1=" + selTab1 + "&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2 + "&nameDb=" + nameDb + "&campiSelezionati=" + campiSelezionati,
                dataType: "html",
                success: function (msg) {
                    jQuery("#results").html(msg);
                },
                error: function () {
                    alert(mv_arr.failedcall);
                }
            });
        }
    }
}

/*
 * Cancella tutti i Join
 */

function deleteJoin() {
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    var txt = document.getElementById("results");
    txt.innerHTML = "<b>Query Cancellata!!</b>";
}

/*
 * Vengono inviati i dati per comporre la query al compositoreQuery.
 */
function addJoin(action) {
    var campiSelezionati = [];
    jQuery('#rightValues option').each(function () {
        campiSelezionati.push(jQuery(this).text());
    });
    if (campiSelezionati.length === 0) {
        alert(mv_arr.inserirecampi);
    } else {
        primaTab = document.getElementById('selTab1').value;
        secondaTab = document.getElementById('selTab2').value;

        joinPresente = false;
        for (i = 0; i < selTab1.length; i++) {
            if ((selTab1[i] === primaTab && selTab2[i] === secondaTab) || (selTab1[i] === secondaTab && selTab2[i] === primaTab)) {
                joinPresente = true;
            }
        }

        if (!joinPresente || action == "script") {

            if (primaTab !== secondaTab) {
                primoCampo = document.getElementById('selField1').value;
                secondoCampo = document.getElementById('selField2').value;
                if (primoCampo !== "" && secondoCampo !== "") {
                    if (action != "script") {
                        selTab1.push(document.getElementById('selTab1').value);
                        selTab2.push(document.getElementById('selTab2').value);
                        selField1.push(primoCampo);
                        selField2.push(secondoCampo);
                    }
                    if (jQuery("#condition").val() != 'undefined')
                        var whereCondition = jQuery("#condition").val();
                    nameView = (document.getElementById('MapName').value);
                    nameDb = (document.getElementById('nameDb').value);
                    if (action == "join") url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=compositoreQuery&mod=" + firstModule;
                    else if (action == "script") url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creaScript&whereCondition=" + whereCondition + "&mod=" + firstModule;

                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: "selTab1=" + selTab1 + "&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2 + "&nameView=" + nameView + "&nameDb=" + nameDb + "&campiSelezionati=" + campiSelezionati + "&installationID=" + installationID,
                        dataType: "html",
                        success: function (msg) {
                            if (action == "join") jQuery("#results").html(msg);
                        },
                        error: function () {
                            alert(mv_arr.failedcall);
                        }
                    });
                }
                else {
                    alert(mv_arr.inserirecampi);
                }
            }
            else {
                alert(mv_arr.differenttabs);
            }
        } else {
            alert(mv_arr.joininserted);
        }
    }
}

function selectHtml() {
    var sel = jQuery('#selectableFields');
    return sel[0].innerHTML;
}
function emptycombo(){
    var select = document.getElementById("selectableFields");
    var length = select.options.length;
    var j=0;
    while(select.options.length!=0){
    for (var i1 = 0; i1 < length; i1++) {
        select.options[i1] = null;
    }
}
}
function posLay(obj,Lay){
	var tagName = document.getElementById(Lay);
	var leftSide = findPosX(obj);
	var topSide = findPosY(obj)-200;
	var maxW = tagName.style.width;
	var widthM = maxW.substring(0,maxW.length-2);
	var getVal = eval(leftSide) + eval(widthM);
	if(getVal > document.body.clientWidth ){
		leftSide = eval(leftSide) - eval(widthM);
		tagName.style.left = leftSide + 'px';
	}
	else
		tagName.style.left= leftSide + 'px';
	tagName.style.top= topSide + 'px';
}
function showform(form){
    fnvshobj(form,'userorgroup');
    posLay(form, "userorgroup");
}

function hidediv(divId)
{
	var id = document.getElementById(divId);
        id.style.display = 'none';
}

function generateJoin(SelectedValue="",History=0) {
    var JoinOptgroupWithValue = [];
    $('#selectableFields').find("option:selected").each(function () {
        //optgroup label
        var optlabel = $(this).parent().attr("label");
        // gets the value
        var ValueselectedArray = [];
        var Valueselected = $(this).val();
        var res = Valueselected.split(":");
        ValueselectedArray = ValueselectedArray.concat(res);
        JoinOptgroupWithValue.push(optlabel + ":" + ValueselectedArray[0] + ":" + ValueselectedArray[1]);


    });
    localStorage.setItem("labels", JoinOptgroupWithValue);
    var campiSelezionati = [];
    var campiSelezionatiLabels = [];
    var valuei = [];
    var texti = [];
    var userorgroup=document.getElementById('usergroup').value;
    var cftables=document.getElementById('cf').value;
    var sel = document.getElementById("selectableFields");
    for (var i = 0; i < sel.options.length; i++) {
        if (sel.options[i].selected == true) {
            //dd=x.options[i].value;
            campiSelezionati.push(sel.options[i].value);
        }


        valuei.push(sel.options[i].value+'!'+sel.options[i].text);
        //texti.push(sel.options[i].text);
  }
     //console.log(valuei);
    if (campiSelezionati.length != 0) {
        var primoCampo = document.getElementById('selField1').value;
        var secondoCampo = document.getElementById('selField2').value;
        selField1.push(primoCampo);
        selField2.push(secondoCampo);
        selTab1.push(firstModule);
        selTab2.push(secModule);

        var queryid=document.getElementById('queryid').value;
        var MapID=$('#MapID').val();

        nameView = (document.getElementById('MapName').value);
        // var sel123 =  jQuery('#selectableFields');
        // var optionsCombo = sel123[0].innerHTML;
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=compositoreQuery";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
        jQuery.ajax({
            type: "POST",
            url: url,
            async: false,
            data: {
                PRovatjeter: selectHtml(),
                selTab1: selTab1,
                fmodule: firstModule,
                smodule: secModule,
                selField1: selField1,
                selTab2: selTab2,
                selField2: selField2,
                installationID: installationID,
                JoinOV: JoinOptgroupWithValue,
                Valueli:valuei,
                userorgroup:userorgroup,
                cftables:cftables,
               // Texti:texti,
                campiSelezionati:SelectedValue.length!=0 ? SelectedValue : campiSelezionati,
                nameView: nameView,
                queryid:queryid,
                MapID:MapID
            },
            dataType: "html",
            success: function (msg) {
                document.getElementById('results').innerHTML = "";
                if (History==1)
                 {
                     document.getElementById('generatedjoin').innerHTML = "";
                 }
                jQuery("#results").html(msg);
                if (box) box.remove();

            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });

    // getFirstModule();
      emptycombo(); }
  }


/*
 * Invia i dati delle <section> relative alle tabelle actions/dataUpadate.php,
 * dove poi ci saranno delle funzioni che inseriranno tutti i campi delle
 * rispettive tabelle nei rispettivi <section> per i campi.
 */
function empty_element(elementByID){
      $(elementByID).html("");
 }

 function newValue_element(elementByID,valueinsert){
     $(elementByID).html("");
      $(elementByID).html(valueinsert);
  }
function generateScript() {
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
    var campiSelezionati = [];
    var campiSelezionatiLabels = [];
    var sel = jQuery('#selectableFields');
    var optionsCombo = sel[0].innerHTML;
    for (var i = 0, len = sel[0].options.length; i < len; i++) {
        opt = sel[0].options[i];
        if (opt.selected)
            campiSelezionati.push(opt.value);

    }

    if (campiSelezionati.length != 0) {
        var primoCampo = document.getElementById('selField1').value;
        var secondoCampo = document.getElementById('selField2').value;
        selField1.push(primoCampo);
        selField2.push(secondoCampo);
        selTab1.push(firstModule);
        selTab2.push(secModule);
        nameView = (document.getElementById('MapName').value);
        if (jQuery("#whereCond").val() != 'undefined') {
            jQuery("#whereCond").trigger("change");
            var whereCondition = jQuery("#whereCond").val();
        }
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creaScript";
        jQuery.ajax({
            type: "POST",
            url: url,
            async: false,
            data: "selTab1=" + selTab1 + "&fmodule=" + firstModule + "&smodule=" + secModule + "&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2 + "&installationID=" + installationID + "&campiSelezionati=" + campiSelezionati + "&nameView=" + nameView + "&whereCondition=" + whereCondition,
            success: function (msg) {
                if (box) box.remove();
            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });
    }
}

function generateMap() {
    nameView = (document.getElementById('MapName').value);
    querygenerate = $('#generatedjoin').text();
    querygeneratecondition = $('#generatedConditions').text();
    var campiSelezionati = [];
    jQuery('#rightValues option').each(function () {
        campiSelezionati.push(jQuery(this).val());
    });
    if (jQuery("#condition").val() != 'undefined') {
        jQuery("#condition").trigger("change");
        var whereCondition = jQuery("#condition").val();
    }
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=generateMap";
    jQuery.ajax({
        type: "POST",
        url: url,
        async: false,
        data: "nameView=" + nameView + "&QueryGenerate=" + querygenerate + querygeneratecondition,
        /*  data: "selTab1=" + selTab1+"&fmodule="+firstModule+"&smodule="+secModule+"&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2+"&installationID="+installationID+ "&campiSelezionati=" + campiSelezionati+"&nameView=" + nameView+"&whereCondition="+whereCondition, */
        success: function (msg) {
            if (box) {
                box.remove();
                alert(mv_arr.mapgenerated);
            }
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });
}

function updateSel(id, field) {
    var selezionato = false;
    var table = document.getElementById(id).value;
    var tableText = document.getElementById(id).options[table].text;
//    var nameDb=document.getElementById("nameDb").value;

    if (in_array(table, tabelleSelezionate)) {

        selezionato = true;
    }
    tabelleSelezionate.push(table);

    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=dataUpdate",
        data: "table=" + tableText + "&field=" + field + "&nameDb=" + nameDb + "&selezionato=" + selezionato,
        dataType: "html",
        success: function (msg) {
            jQuery("#null").html(msg);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}

/*
 * Prima controlla che la query prelevata dal <div id="results"> sia corretta,
 * e poi la crea.
 */
function creaVista() {
    var stringa = ((document.getElementById("results").innerHTML));
    var query = stripTag(stringa);
    var nameDb = (document.getElementById('nameDb').value);
    var nameView = ((document.getElementById("MapName").value));
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creaVista",
        data: "query=" + query + "&nameDb=" + nameDb + "&nameView=" + nameView,
        dataType: "html",
        success: function (msg) {
            jQuery("#results").html(msg);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}

/*
 * Prende tutte le tabelle della query di creazione della vista, e le inserisce in un'unico array.
 * Tutte le tabelle contenute in esso sono le tabelle di cui si vogliono
 * controllare i log di mysql.
 */
function creaArray() {
    var arrayTab = [];
    for (i = 0; i < selTab1.length; i++) {
        if (!(in_array(selTab1[i], arrayTab))) {
            arrayTab.push(selTab1[i]);
        }
    }
    for (j = 0; j < selTab2.length; j++) {
        if (!(in_array(selTab2[j], arrayTab))) {
            arrayTab.push(selTab2[j]);
        }
    }
    return arrayTab;
}
/*
 * Prende in ingresso un valore da esaminare e un array, e restituisce true quando
 * il valore e contenuto nell'array, altrimenti restituisce falso.
 */
function in_array(valore_da_esaminare, array_di_riferimento) {
    isValueInArray = false;
    for (i = 0; i < array_di_riferimento.length; i++) {
        if (valore_da_esaminare === array_di_riferimento[i]) {
            isValueInArray = true;
        }
    }
    return isValueInArray;
}

/*
 * Prende in ingresso una stringa contenente codice html, e restituisce la stringa
 * senza html.
 */
function stripTag(stringa) {
    stringaCorretta = [];
    for (i = 0; i < (stringa.length); i++) {
        vero = true;
        if (ControlTextEquals(stringa[i], '<')) {
            while (vero) {
                i++;
                if (ControlTextEquals(stringa[i], '>')) {
                    vero = false;
                }
            }
        }
        else {
            stringaCorretta = stringaCorretta + stringa[i];
        }
    }
    return stringaCorretta;
}

/*
 * Riceve due stringhe in ingresso, e se sono uguali restituisce true, altrimenti false.
 */
function ControlTextEquals(textA, textB) {
    if (textA === textB) {
        return true;
    }
    else {
        return false;
    }
}
/*
 *  Ricevendo l'id del div da visualizzare, lo mostra
 */
function visualizza(id) {
    if (document.getElementById) {
        if (document.getElementById(id).style.display === 'none') {
            document.getElementById(id).style.display = 'block';
        } else {
            document.getElementById(id).style.display = 'none';
        }
    }
}

/*
 *  Aggiorna la vista materializzata
 */

function updateView() {
    var nameDb = (document.getElementById('dbListViews').value);
    var nameView = document.getElementById('selViews').value;
    if (nameView !== "") {
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=updateView",
            data: "nameView=" + nameView + "&nameDb=" + nameDb,
            dataType: "html",
            success: function (msg) {

                jQuery("#textmessage").html(msg);

            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });
    }
    else {
        alert(mv_arr.selectview);
    }
}


function deleteView() {
    var nameDb = (document.getElementById('dbListViews').value);
    var nameView = document.getElementById('selViews').value;
    if (nameView !== "") {
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=deleteView",
            data: "nameView=" + nameView + "&nameDb=" + nameDb,
            dataType: "html",
            success: function (msg) {
                removeOptionSelected();
                jQuery("#textmessage").html(msg);
            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });
    }
    else {
        alert(mv_arr.selectview);
    }
}

function removeOptionSelected() {
    var elSel = document.getElementById('selViews');
    var i;
    for (i = elSel.length - 1; i >= 0; i--) {
        if (elSel.options[i].selected) {
            elSel.remove(i);
        }
    }
}


/*
 * get Raport Values
 */

function sellist1() {
    var val = document.getElementById('mod1').value;
    var url = "module=MapGenerator&action=MapGeneratorAjax&file=picklistmv";
    jQuery.ajax({
        type: "POST",
        data: "val=" + val,
        url: 'index.php?' + url,
        success: function (response) {
            var str = response;
            if (str != '')
                document.getElementById('groupby1').innerHTML = '<option value="None" >None</option>' + str;
        }

    });
}

function choose_fields3() {
    var val = document.getElementById('groupby1');
    var v = val[val.selectedIndex].value;
    var rec1 = document.getElementById('mod1');
    var rec = rec1[rec1.selectedIndex].value;
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=fields";
    jQuery.ajax({
        type: "POST",
        data: "mod=" + v + "&rec=" + rec,
        url: url,
        success: function (response) {
            var res = response.split("$$");
            jQuery("#count").val(res[1]);
            jQuery("#fieldTab").empty();
            jQuery("#fieldTab").append(res[0]);
        }
    });
}

function choose_fields(reportID, divId) {
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=getFields";
    jQuery.ajax({
        type: "POST",
        data: "reportID=" + reportID + "&installationID=" + installationID,
        url: url,
        success: function (response) {
            var res = response.split("$$");
            jQuery("#count").val(res[1]);
            jQuery("#" + divId).empty();
            if (divId == "fieldTab") jQuery("#" + divId).html('<tr><td width="55%" class="lvtCol"><b><input type=checkbox name="allids1" id="allids1"  onchange=\'checkvalues("fieldTab")\'>Field List</b></td><td  width="20%" class="lvtCol"  align="center"><b>Modules</b></td></tr>');
            else jQuery("#" + divId).html('<tr><td width="55%" class="lvtCol"><b><input type=checkbox name="allids" id="allids"  onchange=\'checkvalues("fieldTab2")\'>Field List</b></td><td  width="20%" class="lvtCol"  align="center"><b>Modules</b></td></tr>');
            jQuery("#" + divId).append(res[0]);
        }
    });
}

function openMenuCreaView() {
    jQuery("#crea").hide();
    jQuery("#creaRp").hide();
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creazioneVista",
        dataType: "html",
        success: function (msg) {
            jQuery("#content").html(msg);

        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}

function createFSScript() {
    jQuery("#content").empty();
    jQuery("#creaRp").hide();
    jQuery("#crea").show();
}

function createReportScript(nr) {
    jQuery("#content").empty();
    jQuery("#crea").hide();
    jQuery("#fieldTab").empty();
    jQuery("#nr").val(nr);
    jQuery("#creaRp").show();
}

function createRaprtTable() {
    var nometab = document.getElementById("nometab").value;
    var reportId = document.getElementById("report").value;
    var accins = installationID;
    var scriptname = document.getElementById("scriptsel").value;
    var accinsmodule = document.getElementById("accinsmodule").value;
    if (scriptname == 1)
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=createFsAdocDetailTable";
    else if (scriptname == 3) var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=newFS";
    else if (scriptname == 4) var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=FSSpecial";
    else var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=createUDTable";
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
    jQuery.ajax({
        type: "POST",
        data: "nometab=" + nometab + "&reportId=" + reportId + "&accins=" + accins + "&accinsmodule=" + accinsmodule,
        url: url,
        success: function (response) {
            if (box) box.remove();
        }
    });
}

function generateReportTable(filename) {
    var nometab = document.getElementById("tablename").value;
    var reportId = document.getElementById("clientreport").value;
    var data = jQuery('#tabelascript ').serialize();
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=" + filename;
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
    jQuery.ajax({
        type: "POST",
        data: "nometab=" + nometab + "&reportId=" + reportId + "&accins=" + installationID + "&" + data,
        url: url,
        success: function (response) {
            if (box) box.remove();
            //add jquery window dialog box
            jQuery("#dialog-message").dialog({
                modal: true,
                buttons: {
                    Ok: function () {
                        jQuery(this).dialog("close");
                    }
                }
            });
        }
    });
}

function generateReportTable2(filename) {
    var nometab = document.getElementById("tablename2").value;
    var reportId = document.getElementById("clientreport2").value;
    var data = jQuery('#tabelascript2').serialize();
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=" + filename;
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
    jQuery.ajax({
        type: "POST",
        data: "nometab=" + nometab + "&reportId=" + reportId + "&accins=" + installationID + "&" + data,
        url: url,
        success: function (response) {
            if (box) box.remove();
            //add jquery window dialog box
            jQuery("#dialog-message").dialog({
                modal: true,
                buttons: {
                    Ok: function () {
                        jQuery(this).dialog("close");
                    }
                }
            });
        }
    });
}

function submitForm() {
    jQuery.ajax
    ({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=createRaport",
        data: jQuery('#ajaxform ').serialize(),
        cache: false,
        success: function (text) {
            alert(text);
        }
    });
}
/*
 * Apre il menù per l'aggiornamento delle viste
 */
//function checkall
function checkvalues(divId) {
    var oTable = document.getElementById(divId);
    iMax = oTable.rows.length;
    for (i = 1; i <= document.getElementById('count').value; i++) {
        if (divId == "fieldTab") {
            document.getElementById('checkf' + i).checked = document.getElementById('allids1').checked;
            if (document.getElementById('allids1').checked == true) document.getElementById('checkf' + i).value = 1;
            else document.getElementById('checkf' + i).value = 0;
        }
        else {
            document.getElementById('checkf' + i).checked = document.getElementById('allids').checked;
            if (document.getElementById('allids').checked == true) document.getElementById('checkf' + i).value = 1;
            else document.getElementById('checkf' + i).value = 0;
        }
    }
}

function openMenuManage() {
    jQuery("#crea").hide();
    jQuery("#creaRp").hide();
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=gestioneViste",
        dataType: "html",
        success: function (msg) {
            jQuery("#content").html(msg);

        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}

/*
 * Controlla se l'utente ha inserito il nome della vista
 */
function isEmpty() {
    var testo = document.getElementById('MapName').value;
    empty = false;
    if (testo === "") {
        empty = true;
    }
    return empty;
}
/*
 * Apre il menù per la creazione dei JOIN
 */
function openMenuJoin() {
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    tabelleSelezionate = [];
    mycheck = new Array();
    allTable = new Array();
    jQuery('#myCheck:not(:checked)').each(function () {
        allTable.push(jQuery(this).val());
    });

    jQuery("#myCheck:checked").each(function () {
        mycheck.push(jQuery(this).val());

        //allTable.push(jQuery(this).val());
    });

    var presente = false;
    var nameView = ((document.getElementById("MapName").value));
    for (i = 0; i < allTable.length; i++) {
        if (nameView === allTable[i]) {
            presente = true;
        }
    }

    if (!presente) {

//   var nameDb=(document.getElementById("dbList").value);
        if (!isEmpty()) {

            if (mycheck.length >= 2) {
                jQuery.ajax({
                    type: "POST",
                    url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creazioneCondizioniJoin",
                    data: "mycheck=" + mycheck + "&nameView=" + nameView + "&nameDb=" + nameDb,
                    dataType: "html",
                    success: function (msg) {
                        jQuery("#content").html(msg);
                    },
                    error: function () {
                        alert(mv_arr.failedcall);
                    }
                });
            }
            else {
                alert(mv_arr.atleasttwo);
            }
        }
        else {
            alert(mv_arr.addviewname);
        }
    }
    else {
        alert(mv_arr.namealreadyused);
    }

}

function openMenuJoin2() {
    jQuery("#firstStep").hide();
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    tabelleSelezionate = [];
    mycheck = new Array();
    allTable = new Array();
    var presente = false;
    var nameView = ((document.getElementById("MapName").value));
    for (i = 0; i < allTable.length; i++) {
        if (nameView === allTable[i]) {
            presente = true;
        }
    }
    if (!presente) {
        if (!isEmpty()) {
            jQuery.ajax({
                type: "POST",
                url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creazioneCondizioniJoin",
                data: "mycheck=" + mycheck + "&nameView=" + nameView + "&nameDb=" + nameDb,
                dataType: "html",
                async: false,
                success: function (msg) {
                    jQuery("#content").html(msg);
                },
                error: function () {
                    alert(mv_arr.failedcall);
                }
            });
        }
        else {
            alert(mv_arr.addviewname);
            window.location.reload();
        }
    }
    else {
        alert(mv_arr.namealreadyused);
        window.location.reload();
    }
    getFirstModule();
    //getFirstModule("","");
}

function selDB(obj) {
    var dbList = jQuery(obj).children(":selected").attr("id");
    var dataDb = dbList.split("-");
    installationID = dataDb[0];
    nameDb = dataDb[1];
//        jQuery.ajax({
//        type: "POST",
//        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=getTableDb",
//        data: "nameDb=" + nameDb,
//        dataType: "html",
//        success: function(msg)
//        {
//              jQuery("#selTab").html(msg);
//
//        },
//        error: function()
//        {
//        }
//     });
}

function selDBViews() {

    var nameDbViews = document.getElementById('dbListViews').value;
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=getDbViews",
        data: "nameDbViews=" + nameDbViews,
        dataType: "html",
        success: function (msg) {
            jQuery("#selViews").html(msg);

        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}

function getFirstModule(selTab2, Mapid, queryid) {
    if (Mapid === undefined) {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=firstModule&installationID=" + installationID;
    }
    else {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=firstModule&installationID=" + installationID + '&MapID=' + Mapid + '&queryid=' +queryid;//+'&MapID=' + Mapid;
    }
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        async: false,
        success: function (msg) {
            if (msg != '') {
                jQuery('#FirstModul').html('<option value="None">None</option>' + msg);
                var SelectPicker = $("#FirstModul").val();
                if (Mapid != undefined) {
                    getSecModule(SelectPicker, Mapid,queryid);
                     getFirstModuleFields(SelectPicker, Mapid,queryid);
                }
                jQuery("#FirstModul").selectmenu("refresh");
            }

        },
        error: function () {
            alert(mv_arr.error);
        }
    });

}

function dispalyModules() {
    if (jQuery("#installmodules").is(":visible")) {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=getInstallationEntities&installationID=" + installationID;
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "html",
            success: function (str) {
                jQuery('#modscriptsel').html('<option value="None">None</option>' + str);
                jQuery("#modscriptsel").selectmenu("refresh");
            },
            error: function () {
                alert(mv_arr.error);
            }
        });
// 
    }
}

function getInstallationModules(dataItem) {
    if (dataItem == 1) {
        jQuery("#installmodules").show();
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=getInstallationEntities&installationID=" + installationID;
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "html",
            success: function (str) {
                jQuery('#modscriptsel').html('<option value="None">None</option>' + str);
                jQuery("#modscriptsel").selectmenu("refresh");
            },
            error: function () {
                alert(mv_arr.error);
            }
        });
    }
    else {
        jQuery("#installmodules").hide()
    }
}

function getSecModule(obj, Mapid, queryid) {
    var v = obj;
    firstModule = obj;
    // var MapIDtext = $('#MapID').val();
    if (Mapid != undefined) {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=fillModuleRel&mod=" + v + "&MapId=" + Mapid + "&installationID=" + installationID + "&queryid="+queryid;
    } else {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=fillModuleRel&mod=" + v + "&installationID=" + installationID;
    }

    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function (str) {
            jQuery('#secmodule').html('<option value="None">None</option>' + str);
            var SelectPicker = $("#secmodule").val();
            if (Mapid != undefined) {
                getSecModuleFields(SelectPicker,Mapid,queryid);
            }
            jQuery("#secmodule").selectmenu("refresh");
        },
        error: function () {
            alert(mv_arr.error);
        }
    });
}

function populateReport(reportSelectId) {
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=populateReport&installationID=" + installationID + "&selectedview=" + reportSelectId;
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function (str) {
            jQuery('#' + reportSelectId).html('<option value="None">None</option>' + str);
            jQuery("#" + reportSelectId).selectmenu("refresh");
        },
        error: function () {
            alert(mv_arr.error);
        }
    });
}

function getFirstModuleFields(obj, Mapid, queryid) {
    var v = obj;
    if (Mapid != undefined) {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=moduleFields&mod=" + v + "&installationID=" + installationID + "&MapId=" + Mapid+ "&queryid="+queryid;
    } else {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=moduleFields&mod=" + v + "&installationID=" + installationID;
    }
    jQuery.ajax({
        type: "POST",
        url: url,
        async: false,
        dataType: "html",
        success: function (str) {
            var s = str.split(";");
            var str1 = s[0];
            var str2 = s[1];
            var str3 = s[2];
            if (jQuery('#selectableFields optgroup[label= ' + v + ']').html() == null)
                $('#selectableFields').empty();
            jQuery('#selectableFields').append(str1).change();
            jQuery('#selField1').val(str3);
            //document.getElementById('results').innerHTML=str;
            //console.log(str);
        }
    });
}

function getSecModuleFields(obj, MapId) {
    var v1 = obj;
    var sp = v1.split(";");
    var mod = sp[0].split("(many)");
    mod0 = mod[0].split(" ");
    secModule = mod0[0];
    var invers = 0;
    if (mod.length == 1) {
        //invers join
        invers = 1;
    }
    if (sp[1] != "undefined") index = sp[1];
    if (MapId != undefined) {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=moduleFields&mod=" + secModule + "&installationID=" + installationID + "&MapId=" + MapId;
    }else {
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=moduleFields&mod=" + secModule + "&installationID=" + installationID;
    }

    jQuery.ajax({
        type: "POST",
        url: url,
        async: false,
        dataType: "html",
        success: function (str) {
            var s = str.split(";");
            var str1 = s[0];
            var str2 = s[1];
            if (jQuery('#selectableFields optgroup[label= ' + secModule + ']').html() == null)
            // $("#selectableFields").empty();
                jQuery('#selectableFields').append(str1).change();
            if (invers == 1) {
                jQuery('#selField1').val(index);
                jQuery('#selField2').val(s[2]);
            }
            else
                jQuery('#selField2').val(index);
        },
        error: function () {
            alert(mv_arr.error);
        }
    });

}


// function to send value for create new or eddit a map
function SaveMap() {
    var campiSelezionati = [];
    var campiSelezionatiLabels = [];
    //var sel = jQuery('#selectableFields');
    var MapID = $('#MapID').val();
    var querygenerate = $('#generatedjoin').text();
    var querygeneratecondition = $('#generatedConditions').text();
//    var optionsCombo = sel[0].innerHTML;
//    for (var i = 0, len = sel[0].options.length; i < len; i++) {
//        opt = sel[0].options[i];
//        if (opt.selected)
//            campiSelezionati.push(opt.value);
//    }
//    if (campiSelezionati.length != 0) {
        var querysequence=document.getElementById('querysequence').value;
        var primoCampo = document.getElementById('selField1').value;
        var secondoCampo = document.getElementById('selField2').value;
        selField1.push(primoCampo);
        selField2.push(secondoCampo);
        selTab1.push(firstModule);
        selTab2.push(secModule);
        nameView = (document.getElementById('MapName').value);
        // url = "index.php?module=MVCreator&action=MapGeneratorAjax&file=compositoreQuery";
        var url = "index.php?module=cbMap&action=cbMapAjax&file=saveasmap";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});

        jQuery.ajax({
            type: "POST",
            url: url,
            async: false,
            data: {
                selTab1: selTab1,
                fmodule: firstModule,
                smodule: secModule,
                selField1: selField1,
                selTab2: selTab2,
                selField2: selField2,
                installationID: installationID,
                queryid:document.getElementById('queryid').value,
                querysequence:document.getElementById('querysequence').value,
            //    html: optionsCombo,
             //   campiSelezionati: campiSelezionati,
                nameView: nameView,
                QueryGenerate: querygenerate + querygeneratecondition,
                MapId: MapID
            },
            dataType: "html",
            success: function (msg) {
                jQuery("#MapID").val(msg);
                if (!$.trim(msg)) {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();

                }
                else {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();
                }
                //jQuery("#MapID").val(msg);if (box) box.remove();

            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });
        // getFirstModule(selTab2);
    //}


}

// function to send value for create new  map
function SaveasMap() {
    var campiSelezionati = [];
    var campiSelezionatiLabels = [];
   // var sel = jQuery('#selectableFields');
    var MapID = $('#MapID').val();
    var SaveasMapTextImput = $('#SaveasMapTextImput').val();
    var querygenerate = $('#generatedjoin').text();
    var querygeneratecondition = $('#generatedConditions').text();
//    var optionsCombo = sel[0].innerHTML;
//    for (var i = 0, len = sel[0].options.length; i < len; i++) {
//        opt = sel[0].options[i];
//        if (opt.selected)
//            campiSelezionati.push(opt.value);
//    }
//    if (campiSelezionati.length != 0) {
        var primoCampo = document.getElementById('selField1').value;
        var secondoCampo = document.getElementById('selField2').value;
        selField1.push(primoCampo);
        selField2.push(secondoCampo);
        selTab1.push(firstModule);
        selTab2.push(secModule);
        nameView = (document.getElementById('MapName').value);
        // url = "index.php?module=MVCreator&action=MapGeneratorAjax&file=compositoreQuery";
        var url = "index.php?module=cbMap&action=cbMapAjax&file=saveasmap";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
        jQuery.ajax({
            type: "POST",
            url: url,
            async: false,
            data: {
                selTab1: selTab1,
                fmodule: firstModule,
                smodule: secModule,
                selField1: selField1,
                selTab2: selTab2,
                selField2: selField2,
                SaveasMapTextImput: SaveasMapTextImput,
                installationID: installationID,
               // html: optionsCombo,
      //          campiSelezionati: campiSelezionati,
                nameView: nameView,
                QueryGenerate: querygenerate + querygeneratecondition

            },
            dataType: "html",
            success: function (msg) {
                jQuery("#MapID").val(msg);
                if (!$.trim(msg)) {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();
                }
                else {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();
                }
                //jQuery("#MapID").val(msg); if (box) box.remove();

            },
            error: function () {
                alert(mv_arr.failedcall);
            }
        });
        getFirstModule(selTab2, MapID);
    //}
}

//this function load a combo with all maps
function LoadPickerMap() {
    var filter = "SQL";
    var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=GetMap";
    jQuery.ajax({
        type: "POST",
        url: url,
        data: "Filter=" + filter,
        success: function (str) {
            jQuery('#GetALLMaps').html('<option value="None">None</option>' + str);
            jQuery("#GetALLMaps").selectmenu("refresh");
        },
        error: function () {
            alert(mv_arr.error);
        }
    });

}


//this function open and set value from map ia choose
function NextAndLoadFromMap() {

//var su="Leads";
    jQuery("#LoadfromMapFirstStep").hide();
    var SelectPicker = $("#GetALLMaps").val();
    var mapid=SelectPicker.split("##");
    //getFirstModuleFields(su,mapid[0],mapid[1]);

   jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=creazioneCondizioniJoin",
        data: "MapID=" + mapid[0]+"&queryid="+mapid[1],
        dataType: "html",
        async: false,
        success: function (data) {
            jQuery("#LoadfromMapSecondStep").html(data);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });
    //jQuery("#results").hide();
    jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=loadmap",
        data: "MapID=" + mapid[0]+"&queryid="+mapid[1],
        dataType: "html",
        async: false,
        success: function (msg) {
          document.getElementById('results').innerHTML="";
          jQuery("#results").html(msg);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });


    getFirstModule("", mapid[0],mapid[1]);


}


function GenerateMasterData()
{
  var datatusend="";
  var dataselected=App.popupJson;
  if (!dataselected)
  {
    alert(mv_arr.ReturnErrorFromMap);
    return 0;
  }
  var nameMap =$("#MapName").val();
  if (nameMap.length=0)
  {
     alert(mv_arr.MissingtheNameofMap);
    return 0; 
  }
  if (App.savehistoryar)
  {
    datatusend+="&savehistory="+App.savehistoryar;
  }else
  {
    datatusend+="&savehistory";
  }

   jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=SaveMasterDetail",
        data: "MapName=" + nameMap+"&alldata="+ JSON.stringify(dataselected)+datatusend,
        dataType: "html",
        async: false,
        success: function (msg) {
          if(msg){
             var returndt=msg.split(",");
             if(returndt[1]>0)
             {
              App.savehistoryar=msg;
                alert(mv_arr.ReturnSucessFromMap);
             }else
             {
              alert(mv_arr.ReturnErrorFromMap);
             }        
          }
          //document.getElementById('results').innerHTML="";
          //jQuery("#results").html(msg);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}


function GenerateListColumns()
{
  var datatusend="";
  var dataselected=App.popupJson;
  if (!dataselected)
  {
    alert(mv_arr.ReturnErrorFromMap);
    return 0;
  }
  var nameMap =$("#MapName").val();
  if (nameMap.length=0)
  {
     alert(mv_arr.MissingtheNameofMap);
    return 0; 
  }
  if (App.savehistoryar)
  {
    datatusend+="&savehistory="+App.savehistoryar;
  }else
  {
    datatusend+="&savehistory";
  }

   jQuery.ajax({
        type: "POST",
        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=SaveListColumns",
        data: "MapName=" + nameMap+"&alldata="+ JSON.stringify(dataselected)+datatusend,
        dataType: "html",
        async: false,
        success: function (msg) {
          if(msg){
             var returndt=msg.split(",");
             if(returndt[1]>0)
             {
                App.savehistoryar=msg;
                alert(mv_arr.ReturnSucessFromMap);
             }else
             {
              alert(mv_arr.ReturnErrorFromMap);
             }        
          }
          //document.getElementById('results').innerHTML="";
          //jQuery("#results").html(msg);
        },
        error: function () {
            alert(mv_arr.failedcall);
        }
    });

}
