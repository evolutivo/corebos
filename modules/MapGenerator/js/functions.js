document.onkeydown = function(e) {
if(event.keyCode == 123) {
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
return false;
}
}

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
 var returnfromgeanratejoin=false;
 var rowsInViewPosrtal=new Array();
 var LocalHistoryPopup=new Array();

 $( function() {
  $( document ).tooltip();
} );



 function addjouin() {
  generateJoin();           
  if ( returnfromgeanratejoin===true)
  {
   openalertsJoin();
   hidediv('userorgroup');
 }else{
  hidediv('userorgroup');
}
}


var JSONForCOndition = [];
function addINJSON(FirstModuleJSONtxt, FirstModuleJSONval,FirstModuleJSONField, SecondModuleJSONtxt, SecondModuleJSONval,SecondModuleJSONField,labels,Valueparagrafi, JSONARRAY,returnvaluesval,returnvaluestetx) {
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
    returnvaluesval:returnvaluesval,
    returnvaluestetx:returnvaluestetx,

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

      function openmodalrezultquery(idforquery)
      {
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

    var FrirstMOduleval = $('select[name="mod"] option:selected').val();// $('#mod').value;
    var FrirstMOduletxt = $('select[name="mod"] option:selected').text();// $('#mod').value;generatedConditions
    var SecondMOduleval = secModule;
    var SecondMOduletxt = $('#secmodule option:selected').text();
    var generatedjoin=$( "#generatedjoin" ).html();
    var generatedConditions=$( "#generatedConditions" ).html();
    var selField1 = document.getElementById('selField1').value;
    var selField2 = document.getElementById('selField2').value;
    var returnvaluevalue=$('#ReturnValuesTxt').attr('name');
    var returnvaluestetx=$('#ReturnValuesTxt').val();

    // if (!returnvalues && returnvalues.length==0)
    // {
    //   alert(mv_arr.ReturnValueCheck);
    //   return false;
    // }

    if(SecondMOduleval==undefined){
      SecondMOduleval='';
    }
    var labels=localStorage.getItem("labels");

    //console.log(labels);
    //console.log(selField1);
    //console.log(selField2);
    //console.log(SecondMOduleval);

    addINJSON(FrirstMOduletxt, FrirstMOduleval,selField1, SecondMOduletxt, SecondMOduleval, selField2, labels, generatedjoin, selectHtml(),returnvaluevalue,returnvaluestetx);

	// console.log(FrirstMOduletxt);
	//console.log(FrirstMOduleval);
	// console.log(SecondMOduletxt);
	//console.log(SecondMOduleval);
 //console.log(secModule);
 var check=false;
 var length_history=JSONForCOndition.length;
    //alert(length_history-1);
    for (var ii = 0; ii <= JSONForCOndition.length-1; ii++) {
        var idd =ii;// JSONForCOndition[ii].idJSON;
        var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
        var secmod = JSONForCOndition[ii].SecondModuleJSONtext;
        var selectedfields = JSONForCOndition[ii].ValuesParagraf;
        
        // console.log(idd+firmod+secmod);
        // console.log(selectedfields);
        if (ii==(length_history-1))
        {
          check=true;
          $('#KippID').val(ii);

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
          $('#KippID').val(ii);

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
   for (var ii = 0; ii <= JSONForCOndition.length-1; ii++) {
        var idd =ii;// JSONForCOndition[ii].idJSON;
        //valuehistoryquery = JSONForCOndition[ii].ValuesParagraf;
         var idd =ii;// JSONForCOndition[ii].idJSON;
         var firmod = JSONForCOndition[ii].FirstModuleJSONtext;
         var secmod = JSONForCOndition[ii].SecondModuleJSONtext;

         //console.log(idd+firmod+secmod);
        //console.log(selectedfields);
        if (ii==id_history)
        {
          check=true;
          valuehistoryquery = JSONForCOndition[ii].ValuesParagraf;
          var returnvaluesval=JSONForCOndition[ii].returnvaluesval;
          var returnvaluestetx=JSONForCOndition[ii].returnvaluestetx;
          $( "#generatedjoin" ).html(valuehistoryquery);
          $('#ReturnValuesTxt').attr('name',returnvaluesval);
          $('#ReturnValuesTxt').val(returnvaluestetx);
          $('#KippID').val(id_history);

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
    if(App.popupJson.length==1) var leng=1; 
    else leng=App.popupJson.length-1;
    for (var ii = 0; ii <= leng; ii++) {
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

//function when you doubleclick to choose a value
function doubleclickvalue(sel)
{
 $('#ReturnValuesTxt').attr('name',sel.value);
 $('#ReturnValuesTxt').val(sel.options[sel.selectedIndex].text);
}


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
    $('#ErrorVAlues').text('{literal}{$MOD.morefivechars}{/literal}');
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
      var returnvalues=$('#ReturnValuesTxt').attr('name');
      if (!returnvalues && returnvalues.length==0)
      {
        alert(mv_arr.ReturnValueCheck);
        returnfromgeanratejoin= false;
        return false;
      }
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
             returnfromgeanratejoin= true;
           },
           error: function () {
            alert(mv_arr.failedcall);
            returnfromgeanratejoin= false;
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
    var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
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
        if (box) box.remove();
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
    var indexi = parseInt(document.getElementById('KippID').value);
    var SaveasMapTextImput = $('#SaveasMapTextImput').val();
    var valuehistoryquery = JSONForCOndition[indexi];
    var FirstModul=$('#FirstModul option:selected').val();
    var secmodule=$('#secmodule option:selected').val();
    var selField1=$('#selField1').val();
    var selField2=$('#selField2').val();
    
    var nameView = (document.getElementById('MapName').value);
        // url = "index.php?module=MVCreator&action=MapGeneratorAjax&file=compositoreQuery";
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=SaveAsMap";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});

        jQuery.ajax({
          type: "POST",
          url: url,
          async: false,
          data: {
            FirstModul: FirstModul,
            secmodule: secmodule,
            selField1:selField1,
            selTab2:selTab2,
            selField2:selField2,
            allvalues:JSON.stringify(valuehistoryquery),
            nameView: nameView,
            SaveasMapTextImput: SaveasMapTextImput,
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
    //var sel = jQuery('#selectableFields');
    var MapID = $('#MapID').val();
    var querygenerate = $('#generatedjoin').text();
    var querygeneratecondition = $('#generatedConditions').text();    
    var indexi = parseInt(document.getElementById('KippID').value);
    var SaveasMapTextImput = $('#SaveasMapTextImput').val();
    var valuehistoryquery = JSONForCOndition[indexi];
    var FirstModul=$('#FirstModul option:selected').val();
    var secmodule=$('#secmodule option:selected').val();
    var selField1=$('#selField1').val();
    var selField2=$('#selField2').val();
    
    var nameView = (document.getElementById('MapName').value);
        // url = "index.php?module=MVCreator&action=MapGeneratorAjax&file=compositoreQuery";
        var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=SaveAsMap";
        var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
        jQuery.ajax({
          type: "POST",
          url: url,
          async: false,
          data: {
            FirstModul: FirstModul,
            secmodule: secmodule,
            selField1:selField1,
            selTab2:selTab2,
            selField2:selField2,
            allvalues:JSON.stringify(valuehistoryquery),
                // nameView: nameView,
                SaveasMapTextImput: SaveasMapTextImput,
                QueryGenerate: querygenerate + querygeneratecondition,
                // MapId: MapID

              },
              dataType: "html",
              success: function (msg) {
                var splitval=msg.split(',');
                if (splitval[1])
                {
                  jQuery("#MapID").val(msg);
                  if (!$.trim(msg)) {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();
                  }
                  else {
                    alert(mv_arr.mapgensucc);
                    if (box) box.remove();
                  }
                }else
                {
                  alert(mv_arr.ReturnErrorFromMap);
                }
                
                //jQuery("#MapID").val(msg); if (box) box.remove();

              },
              error: function () {
                alert(mv_arr.failedcall);
              }
            });
        // $('#ReturnValuesTxt').attr('name','');
        // $('#ReturnValuesTxt').val('');
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
    if (dataselected.length==0)
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
  if (dataselected.length==0)
  {
    alert(mv_arr.MappingFiledValid);
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


function GenearteMasterDetail() {
  var temparray = {};

  var AppUtils=App.utils;
  temparray['DefaultText'] = "Created By";
  temparray['JsonType'] = "Default";

  temparray['FirstfieldoptionGroup'] = AppUtils.IsSelectORDropDown("FirstModule");


  temparray['FirstModule'] = AppUtils.IsSelectORDropDown("FirstModule");
  temparray['FirstModuleoptionGroup'] = undefined;

  temparray['FirstfieldID'] = AppUtils.IsSelectORDropDown("FirstfieldID");
  temparray['FirstfieldIDoptionGroup'] = "";

  temparray['Firstfield'] = AppUtils.IsSelectORDropDown("Firstfield");
  temparray['Firstfield'] = AppUtils.IsSelectORDropDown("Firstfield");
  temparray['Firstfield_Text'] = AppUtils.IsSelectORDropDownGetText("Firstfield");

  temparray['secmodule'] = AppUtils.IsSelectORDropDown("secmodule");
  temparray['secmoduleoptionGroup'] =undefined;

  temparray['SecondfieldID'] = AppUtils.IsSelectORDropDown("SecondfieldID");
    //temparray['SecondfieldID'] = "";

    temparray['sortt6ablechk'] = AppUtils.IsSelectORDropDown("sortt6ablechk");
    temparray['sortt6ablechkoptionGroup'] = "";

    temparray['editablechk'] = AppUtils.IsSelectORDropDown("editablechk");
    temparray['editablechkoptionGroup'] = "";

    temparray['mandatorychk'] = AppUtils.IsSelectORDropDown("mandatorychk");
    temparray['hiddenchkoptionGroup'] = "";

    temparray['hiddenchk'] = AppUtils.IsSelectORDropDown("hiddenchk");
    temparray['hiddenchkoptionGroup'] = "";

    App.popupJson.push({temparray});
    $('#LoadShowPopup').html('');
    if(App.popupJson.length>0){
      for (var i=0; i<= App.popupJson.length - 1; i++) {
        $('#LoadShowPopup').append(FillDivAlert(i, i, App.popupJson[i].temparray['Firstfield_Text'], 
         App.popupJson[i].temparray['sortt6ablechk'], App.popupJson[i].temparray['editablechk'],
         App.popupJson[i].temparray['mandatorychk'], App.popupJson[i].temparray['hiddenchk']));

      }
    }
  }


  function FillDivAlert( Idd, divid, firstfield, sortt6ablechk, editablechk, mandatorychk, hiddenchk){
    var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
    + '">';
    INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('
    + Idd + ',\'' + divid + '\');">&times;</span>';

    INSertAlerstJOIN += '<strong>#'+(Idd+1)+'</strong> '+firstfield;
    INSertAlerstJOIN += '<br><strong>#Sort!  '+(Idd+1)+'</strong> '+(sortt6ablechk==1?"True":"False");
    INSertAlerstJOIN += '<br><strong>#Editable!  '+(Idd+1)+'</strong> '+(editablechk==1?"True":"False");
    INSertAlerstJOIN += '<br><strong>#Mandatory!  '+(Idd+1)+'</strong> '+(mandatorychk==1?"True":"False");
    INSertAlerstJOIN += '<br><strong>#Hidden!  '+(Idd+1)+'</strong> '+(hiddenchk==1?"True":"False");

    INSertAlerstJOIN += '</div';

    return INSertAlerstJOIN;
  }


/**
 * function to show the popup in master detail
 *
 * 
 */
 function showmodalformasterdetail() {
   $('#LoadShowPopup').html('');
   if(App.popupJson.length>0){
    for (var i=0; i<= App.popupJson.length - 1; i++) {
      $('#LoadShowPopup').append(FillDivAlert(i, i, App.popupJson[i].temparray['Firstfield_Text'], 
       App.popupJson[i].temparray['sortt6ablechk'], App.popupJson[i].temparray['editablechk'],
       App.popupJson[i].temparray['mandatorychk'], App.popupJson[i].temparray['hiddenchk']));
      
    }
  }
}

function showmodalformasterdetail2(keephitoryidtoshow,keephitoryidtoshowidrelation){
   if (App.SaveHistoryPop.length>0)
    { 
        App.utils.AddtoHistory(keephitoryidtoshow,keephitoryidtoshowidrelation,'showmodalformasterdetail');
       // App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
    }
}

/**
 * SELECT * from vtiger_tab;
 *SELECT * from vtiger_field;
 */


 function Addmorevalues(elem){
  var ss=elem;
  var divtoinsert=$('#ShowmoreInput');
  var i=$('#ShowmoreInput input').size()+1;
  $(Addinput(i)).appendTo(divtoinsert);
  var allids= $('#AddToArray').attr('data-add-relation-id');
  allids=allids+',DefaultValueFirstModuleField_'+i;
  $('#AddToArray').attr('data-add-relation-id',allids);

}

function RemoveThis(argument,idinput) {
  var idtoremove='DefaultValueFirstModuleField_'+idinput;
  var allids= $('#AddToArray').attr('data-add-relation-id').split(',');
    // var idafterremove="";
    allids.forEach(function (value,index) {
      if (value===idtoremove)
      {
       allids.splice(index,1);
     }
          // else{
          //   idafterremove+=","+index;
          // }
        });
    $(argument).parent().parent().remove();
    $('#AddToArray').attr('data-add-relation-id',allids.toString());
  }

  function Addinput(idinput) {
    return '<div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">'
    +'<div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">'
    +'<div class="slds-combobox__form-element">'
    +'<input type="text" id="DefaultValueFirstModuleField_'+idinput+'" placeholder="Insert a values " id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input">'
    +'</div>'
    +'</div>'
    +'<div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">'
    +'<button class="slds-button slds-button_icon" onclick="RemoveThis(this,'+idinput+')" aria-haspopup="true" title="Remove" style="width:2.1rem;">'
    +'<img src="themes/images/clear_field.gif" style="width: 100%;">'
    +'</button>'
    +'</div>'
    +'</div>';
  }



  function addvaluestosendbutton(elem){
    var ss=elem;
    var divtoinsert=$('#ShowmoreInput');
    var i=$('#ShowmoreInput input').size()+1;
    $(Addinputsendbutton(i)).appendTo(divtoinsert);
    var allids= $('#AddToArray').attr('data-send-data-id');
    allids=allids+',DefaultValueFirstModuleField_'+i;
    $('#AddToArray').attr('data-send-data-id',allids);

  }

  function Removesendbutton(argument,idinput) {
    var idtoremove='DefaultValueFirstModuleField_'+idinput;
    var allids= $('#AddToArray').attr('data-send-data-id').split(',');
    // var idafterremove="";
    allids.forEach(function (value,index) {
      if (value===idtoremove)
      {
       allids.splice(index,1);
     }
          // else{
          //   idafterremove+=","+index;
          // }
        });
    $(argument).parent().parent().remove();
    $('#AddToArray').attr('data-send-data-id',allids.toString());
  }

  function Addinputsendbutton(idinput) {
    return '<div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">'
    +'<div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">'
    +'<div class="slds-combobox__form-element">'
    +'<input type="text" id="DefaultValueFirstModuleField_'+idinput+'" placeholder="Insert a values " onfocus="removearrayselected()" id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input">'
    +'</div>'
    +'</div>'
    +'<div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">'
    +'<button class="slds-button slds-button_icon" onclick="Removesendbutton(this,'+idinput+')" aria-haspopup="true" title="Remove" style="width:2.1rem;">'
    +'<img src="themes/images/clear_field.gif" style="width: 100%;">'
    +'</button>'
    +'</div>'
    +'</div>';
  }

  function removeselect(id)
  {
    $('#'+id+' option:selected').removeAttr('selected');
    $('#'+id).append('<option value="0" selected="selected">Select </option>');

  }


function removearrayselected(typeremove,type2="")
{

  for (var i = App.popupJson.length - 1; i >= 0; i--) {
   if (App.popupJson[i].temparray['JsonType']===typeremove || App.popupJson[i].temparray['JsonType']===type2)
   {
    App.popupJson.splice(i,1);
  }

  }

  $('#LoadShowPopup').empty();
  if (App.popupJson.length>0)
  { 
  for (var i = 0; i <= App.popupJson.length-1; i++) {
    var module=App.popupJson[i].temparray[`DefaultText`];
    var typeofppopup=App.popupJson[i].temparray['JsonType'];
    var divinsert= App.utils.DivPopup(i,"",module,"LoadShowPopup",typeofppopup);
    $('#LoadShowPopup').append(divinsert);
  } 

  }
}


/**
 * function to remove all selected if you change the funkion name 
 */
function removearrayselectedall()
{
  if (App.popupJson.length>0)
  {
    App.popupJson.length=0;
    $('#LoadShowPopup').empty();
  }
}



//check if function name is empty or not 

function checkfunctionname(elem)
{
    var valuesinput=elem.value;
   if (valuesinput && valuesinput.length>=4)
    {
      $('#Firstmodule2').removeAttr('disabled');
      $('#Firstfield2').removeAttr('disabled');
      $('#DefaultValueFirstModuleField_1').removeAttr('disabled')
    }else {
      $('#Firstmodule2').attr('disabled', 'disabled');
      $('#Firstfield2').attr('disabled', 'disabled');
      $('#DefaultValueFirstModuleField_1').attr('disabled', 'disabled');
    }
}

// $("#FunctionName").on('change', function () {
//    var element = $(this);
//    var valuesinput=element.val();
//    if (valuesinput && valuesinput.length>=5)
//     {
//       $('#Firstmodule2').removeAttr('disabled');
//       $('#Firstfield2').removeAttr('disabled');
//       $('#DefaultValueFirstModuleField_1').removeAttr('disabled')
//     }else {
//       $('#Firstmodule2').attr('disabled', 'disabled');
//       $('#Firstfield2').attr('disabled', 'disabled');
//       $('#DefaultValueFirstModuleField_1').attr('disabled', 'disabled');
//     }
// });

// function selectOnlyOne(elem) {
//    f (elem.id==="Expression" && elem.checked) {
//         for(let i=0;i<=App.popupJson.length;i++){
//           if(App.popupJson[i].temparray.JsonType==="Expression"){App.popupJson.splice(i,1);}
//         }
//       }else{
//         alert("else and Function");
//       }
//       App.showpopupmodal
// }
// // onclick="selectOnlyOne(this)
// 



//#################   Create View Portal

////  part of rows 

/**
 * function to add roows for a block 
 *
 * @param      {<type>}  event   The event
 */
function addrows(event)
{
    var elem=event;
    var Idtoget=elem.dataset.addRelationId;
    var typeofpopup=elem.dataset.addType;
    var dataDivtoShowe=elem.dataset.divShow;
    if (!Idtoget || Idtoget==='') { App.utils.ShowNotification("snackbar",4000,mv_arr.missingtheidgetValue);}
    if (!typeofpopup || typeofpopup==='') { typeofpopup="Default";}
    if (!dataDivtoShowe || dataDivtoShowe==='') { dataDivtoShowe="LoadShowPopup";}
    allfieldsval=[];
    allfieldstetx=[];

    if( $("#"+Idtoget+" option:selected").length){
     $("#"+Idtoget+" option:selected").each(function() {
      allfieldsval.push($(this).val());
    });
     $("#"+Idtoget+" option:selected").each(function() {
      allfieldstetx.push($(this).text());
    }); 
    }else
    {
    App.utils.ShowNotification("snackbar",4000,mv_arr.MissingFields);
    }
    var checkifexist={fields:allfieldsval,texts:allfieldstetx};
    if (App.utils.checkinArray(rowsInViewPosrtal,checkifexist)===true)
    {
    App.utils.ShowNotification("snackbar",4000,mv_arr.NotAllowedDopcicate);
    }else
    {
    rowsInViewPosrtal.push(checkifexist);

    }
    if (rowsInViewPosrtal.length>0)
    {
    $('#' + dataDivtoShowe + ' div').remove();
    for (var i = rowsInViewPosrtal.length - 1; i >= 0; i--) {
    var divinsert= addrowspopup(i,rowsInViewPosrtal[i],dataDivtoShowe);
    $('#'+dataDivtoShowe).append(divinsert);
    }
    }
}


/**
 * function to show the rows for a block
 *
 * @param      {string}  Idd           The idd
 * @param      {string}  BlockName     The block name
 * @param      {<type>}  alldat        The alldat
 * @param      {string}  divid         The divid
 * @param      {<type>}  typeofppopup  The typeofppopup
 * @return     {string}  { description_of_the_return_value }
 */
 function addrowspopup(Idd,alldat,divid)
 {
  var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
  + '">';
  INSertAlerstJOIN += '<span class="closebtns" onclick="closeRowpopup('
  + Idd + ',\'' + divid + '\');">&times;</span>';
  INSertAlerstJOIN += ' <strong># Row  '+(Idd+1)+'</strong>';
  if (alldat && alldat['fields'].length>0)
  {
    for (var i =0; i <= alldat['fields'].length - 1 ; i++) {      
      INSertAlerstJOIN += '<br/><strong># Field ==>'+alldat['texts'][i]+'</strong>';
    }
  }
  
  INSertAlerstJOIN += '</div';
  return INSertAlerstJOIN;
}

/**
 * function to remove the popup for rows
 *
 * @param      {(number|string)}  remuveid  The id of popup
 * @param      {string}           namediv   The the div id to replace the new data in div
 */
 function closeRowpopup(remuveid,namediv)
 {
   var check = false;
   for (var ii = 0; ii <= rowsInViewPosrtal.length-1; ii++) {
    if (ii == remuveid) {
               //JSONForCOndition.remove(remuveid);
               rowsInViewPosrtal.splice(remuveid,1);
               check = true
        //console.log(remuveid);
             // console.log(ReturnAllDataHistory());
           }
         }
         if (check) {
          var remuvediv="#alerts_"+remuveid;
          $( remuvediv).remove( );
          $('#' + namediv + ' div').remove();
          if (rowsInViewPosrtal.length>0)
          { 
           for (var i = rowsInViewPosrtal.length - 1; i >= 0; i--) {
            var divinsert= addrowspopup(i,rowsInViewPosrtal[i],namediv);
            $('#'+namediv).append(divinsert);
          } 

        }else{
          // alert(mv_arr.MappingFiledValid);
          // App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
        }
      }
      else {
          // alert(mv_arr.ReturnFromPost);
          App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
        }
      }

/// Part of Block

/**
 * function to create the popup final for generate map 
 *
 * @param      {<type>}   event   The event
 * @return     {boolean}  { description_of_the_return_value }
 */
 function showpopupCreateViewPortal(event){
  // if (event) {even.preventDefault();}
  var elem=event;
  var allid=elem.dataset.addRelationId;
  var typeofpopup=elem.dataset.addType;
  var dataDivtoShowe=elem.dataset.divShow;
  var temparray={};
  if (!allid || allid==='') { App.utils.ShowNotification("snackbar",4000,mv_arr.missingtheidgetValue);}
  if (!typeofpopup || typeofpopup==='') { typeofpopup="Default";}
  if (!dataDivtoShowe || dataDivtoShowe==='') { dataDivtoShowe="LoadShowPopup";}
  allid=allid.split(',');
  for (var i = allid.length - 1; i >= 0; i--) {
    if (App.utils.IsSelectORDropDown(allid[i]).length>0)
    {
      alldata=[];
      temparray['JsonType']=typeofpopup;
      temparray[allid[i]]=App.utils.IsSelectORDropDown(allid[i]);
      temparray[allid[i]+'Text']=App.utils.IsSelectORDropDownGetText(allid[i]);
      temparray[allid[i]+'optionGroup']=App.utils.GetSelectParent(allid[i]);
      check=true;
    }else
    {
          //alert(mv_arr.MappingFiledValid);
          check=false;
          break;

        }

      }

      allfieldsval=[];
      allfieldstetx=[];
      if (rowsInViewPosrtal.length>0)
      {
        rowsInViewPosrtal.forEach(function(element) {
          allfieldsval.push(element.fields);
          allfieldstetx.push(element.texts);
        });
      }else{
       App.utils.ShowNotification("snackbar",4000,mv_arr.MissingFields);
       return false;
     }
     temparray["rows"]={fields:allfieldsval,texts:allfieldstetx};
     if (check)
     {

      var checkvalue={temparray};
      if (App.utils.checkinArray(App.popupJson,checkvalue)===false)
      {
        App.popupJson.push({temparray});

      }else
      {
        App.utils.ShowNotification("snackbar",4000,mv_arr.NotAllowedDopcicate);
      }

    }else
    {
      App.utils.ShowNotification("snackbar",4000,mv_arr.addJoinValidation);
    }

    if (App.popupJson.length>0)
    { 
      $('#' + dataDivtoShowe + ' div').remove();
      for (var i = 0; i <= App.popupJson.length-1; i++) {
        alldat=[];
        var BlockName=App.popupJson[i].temparray[`BlockName`];
        alldat=App.popupJson[i].temparray[`rows`];
        var typeofppopup=App.popupJson[i].temparray['JsonType'];
        var divinsert= addToPopup(i,BlockName,alldat,dataDivtoShowe,typeofppopup);
        $('#'+dataDivtoShowe).append(divinsert);
      } 
    }else{
    // alert(mv_arr.MappingFiledValid);
    App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
  }
  rowsInViewPosrtal.length=0;
}

/**
 * function to genearate the html code with dynamic data 
 *
 * @param      {string}  Idd           The idd
 * @param      {string}  BlockName     The block name
 * @param      {<type>}  alldat        The alldat
 * @param      {string}  divid         The divid
 * @param      {<type>}  typeofppopup  The typeofppopup
 * @return     {string}  { description_of_the_return_value }
 */
 function addToPopup(Idd,BlockName,alldat,divid,typeofppopup)
 {
  var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
  + '">';
  INSertAlerstJOIN += '<span class="closebtns" onclick="closePopupData('
  + Idd + ',\'' + divid + '\');">&times;</span>';
  INSertAlerstJOIN += ' <strong>'+BlockName+'</strong><br/>';
  // INSertAlerstJOIN += '<br/><strong># Block Name! ==></strong>'+BlockName;
  if (alldat && alldat.texts.length>0)
  {
    for (var i = 0; i <=alldat.texts.length - 1; i++) {
      INSertAlerstJOIN += '<strong># Row  '+(i+1)+'</strong>';
      INSertAlerstJOIN += ' <ul>';
      if (alldat && alldat.texts[i].length>0)
      {
        alldat.texts[i].forEach(function(element) {
          INSertAlerstJOIN += '<li> Field ==>'+element+'</li>';
        });
      }
      INSertAlerstJOIN += '</ul>';
    }
  }
  
  INSertAlerstJOIN += '</div';
  return INSertAlerstJOIN;
}

/**
  * function to close the popup fuinal if you want ot remove one block 
 *
 * @param      {(number|string)}  remuveid  The remuveid
 * @param      {string}           namediv   The namediv
 */
 function closePopupData(remuveid,namediv) {

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
          $('#' + namediv + ' div').remove();
          if (App.popupJson.length>0)
          { 
            for (var i = 0; i <= App.popupJson.length-1; i++) {
              alldat=[];
              var BlockName=App.popupJson[i].temparray[`BlockName`];
              alldat=App.popupJson[i].temparray[`rows`];
              var typeofppopup=App.popupJson[i].temparray['JsonType'];
              var divinsert= addToPopup(i,BlockName,alldat,namediv,typeofppopup);
              $('#'+namediv).append(divinsert);
            } 

          }else{
          // alert(mv_arr.MappingFiledValid);
          // App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
        }
      }
      else {
          // alert(mv_arr.ReturnFromPost);
          App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
        }
      }



// part of local History

/**
 * function to load the histoery every time you click on save map 
 * (this functionm called in button by attribute if you do not want to use the standart modal )
 *
 * @class      SavehistoryCreateViewportal (name)
 * @param      {string}  keephitoryidtoshow            The keephitoryidtoshow
 * @param      {<type>}  keephitoryidtoshowidrelation  The keephitoryidtoshowidrelation
 */
 function SavehistoryCreateViewportal(keephitoryidtoshow,keephitoryidtoshowidrelation)
 {
   if (App.SaveHistoryPop.length>0)
   { 
     $('#'+keephitoryidtoshow+' div').remove();
     for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
      $('#'+keephitoryidtoshow).append(showLocalHistory(i,App.SaveHistoryPop[i].PopupJSON,keephitoryidtoshow,keephitoryidtoshowidrelation));
    }
  }
}

function ShowHistoryData(id,divshow)
{
 var historydata=App.SaveHistoryPop[parseInt(id)];
 App.popupJson.length=0;
 for (var i=0;i<=historydata.PopupJSON.length-1;i++){
  App.popupJson.push(historydata.PopupJSON[i]);
}
if (App.popupJson.length>0)
{ 
  $('#' + divshow + ' div').remove();
  for (var i = 0; i <= App.popupJson.length-1; i++) {
    alldat=[];
    var BlockName=App.popupJson[i].temparray[`BlockName`];
    alldat=App.popupJson[i].temparray[`rows`];
    var typeofppopup=App.popupJson[i].temparray['JsonType'];
    var divinsert= addToPopup(i,BlockName,alldat,divshow,typeofppopup);
    $('#'+divshow).append(divinsert);
  } 
}
}



/**
 * the html generate for local history 
 *
 * @param      {number}  IdLoad         The identifier load
 * @param      {string}  dataarr        The dataarr
 * @param      {<type>}  divanameLoad   The divaname load
 * @param      {string}  dividrelation  The dividrelation
 * @return     {string}  { description_of_the_return_value }
 */
 function showLocalHistory(IdLoad,dataarr,divanameLoad,dividrelation=''){
  var htmldat='<div class="Message Message"  >';
  htmldat+='<div class="Message-icon">';
        // if (avtive===false)
        // {
          htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ShowHistoryData(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;
        // }
        htmldat+='</div>';
        htmldat+='<div class="Message-body">';
        htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';
        for (var i = dataarr.length - 1; i >= 0; i--) {
          htmldat+='<p>BlockName ==>'+dataarr[i].temparray.BlockName+'</p>';
          
        }        
        htmldat+='</div>';
        // htmldat+='<button class="Message-close js-messageClose" data-history-close-modal="true" data-history-close-modal-id="'+IdLoad+'" data-history-close-modal-divname="'+divanameLoad+'"  data-history-show-modal-divname-relation="'+dividrelation+'" ><i class="fa fa-times"></i></button>';
        htmldat+='</div>';
        return htmldat;
      }



      function SavehistoryCreateViewportalIOMap(keephitoryidtoshow,keephitoryidtoshowidrelation){
       if (App.SaveHistoryPop.length>0)
       { 
         $('#'+keephitoryidtoshow+' div').remove();
         for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
          $('#'+keephitoryidtoshow).append(showLocalHistoryIOMap(i,keephitoryidtoshow,keephitoryidtoshowidrelation));

        }
      }
    }

///////  Menu Structure Functions ////////////////

/**
 * this show the history every time you click same map
 *
 * @class      ShowLocalHistoryMenuStructure (name)
 * @param      {<type>}  keephitoryidtoshow            The keephitoryidtoshow
 * @param      {<type>}  keephitoryidtoshowidrelation  The keephitoryidtoshowidrelation
 */
 function ShowLocalHistoryMenuStructure(keephitoryidtoshow,keephitoryidtoshowidrelation)
 {
  if (App.SaveHistoryPop.length>0)
  { 
   $('#'+keephitoryidtoshow+' div').remove();
   for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
    $('#'+keephitoryidtoshow).append(modalhistoryshow(i,keephitoryidtoshow,keephitoryidtoshowidrelation));

  }
}
}



/**
 * Shows the history data io map. Show history data,  only in IOMap.
 *
 * @class      ShowHistoryDataIOMap (name)
 * @param      {<type>}  id       The identifier
 * @param      {string}  divshow  The divshow
 */
function ShowHistoryDataIOMap(id,divshow)
{
     var historydata=App.SaveHistoryPop[parseInt(id)];
     App.popupJson.length=0;
     for (var i=0;i<=historydata.PopupJSON.length-1;i++){
       App.popupJson.push(historydata.PopupJSON[i]);
      }
    if (App.popupJson.length>0)

   $('#LoadShowPopupInput div').remove();
   $('#LoadShowPopup div').remove();

   for (var i = 0; i <= App.popupJson.length-1; i++) {
    var Field=App.popupJson[i].temparray[`DefaultText`].replace(/\s+/g, '');
    var moduli=App.popupJson[i].temparray[`Moduli`];
    var typeofppopup=App.popupJson[i].temparray['JsonType'];

    if(typeofppopup==="Input"){
      var divinsert= addToPopupIoMap(i,moduli,Field,"LoadShowPopup",typeofppopup,"IS");
      $('#'+"LoadShowPopup").append(divinsert);
    }else if(typeofppopup==="Output"){
      var divinsert= addToPopupIoMap(i,moduli,Field,"LoadShowPopup",typeofppopup,"IS");
      $('#'+"LoadShowPopup").append(divinsert);
    }
   } 
}






/**
 * function for popup of history
 *
 * @param      {number}  IdLoad         The identifier load

 * @param      {<type>}  divanameLoad   The divaname load
 * @param      {string}  dividrelation  The dividrelation
 * @return     {string}  { description_of_the_return_value }
 */

function showLocalHistoryIOMap(IdLoad,divanameLoad,dividrelation=''){
  var htmldat='<div class="Message Message"  >';
  htmldat+='<div class="Message-icon">';
        // if (avtive===false)
        // {
          htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ShowHistoryDataIOMap(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;

          htmldat+='</div>';
          htmldat+='<div class="Message-body">';
          htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';

          htmldat+='</div>';
        // htmldat+='<button class="Message-close js-messageClose" data-history-close-modal="true" data-history-close-modal-id="'+IdLoad+'" data-history-close-modal-divname="'+divanameLoad+'"  data-history-show-modal-divname-relation="'+dividrelation+'" ><i class="fa fa-times"></i></button>';
        htmldat+='</div>';
        return htmldat;
      }

      function modalhistoryshow(IdLoad,divanameLoad,dividrelation='')
      {
       var htmldat='<div class="Message Message"  >';
       htmldat+='<div class="Message-icon">';
        // if (avtive===false)
        // {
          htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ShowHistoryDataLocal(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;

        // }
        htmldat+='</div>';
        htmldat+='<div class="Message-body">';
        htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';

        htmldat+='</div>';
        // htmldat+='<button class="Message-close js-messageClose" data-history-close-modal="true" data-history-close-modal-id="'+IdLoad+'" data-history-close-modal-divname="'+divanameLoad+'"  data-history-show-modal-divname-relation="'+dividrelation+'" ><i class="fa fa-times"></i></button>';
        htmldat+='</div>';
        return htmldat;
      }

/**
 * Closes a popup data i/o map. Remove alerts when user click close, only for IOMap
 *
 * @param      {(number|string)}  remuveid  The remuveid
 * @param      {string}           namediv   The namediv
 */
 function closePopupDataIoMap(remuveid,namediv) {

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

          $('#LoadShowPopupInput div').remove();
          $('#LoadShowPopup div').remove();
          if (App.popupJson.length>0)
          { 
            for (var i = 0; i <= App.popupJson.length-1; i++) {
             var Field=App.popupJson[i].temparray[`DefaultText`];
             var moduli=App.popupJson[i].temparray[`Moduli`];
             var typeofppopup=App.popupJson[i].temparray['JsonType'];

             if(typeofppopup==="Input"){
              var divinsert= addToPopupIoMap(i,moduli,Field,"LoadShowPopupInput",typeofppopup,"IS");
              $('#'+"LoadShowPopup").append(divinsert);
            }else if(typeofppopup==="Output"){
              var divinsert= addToPopupIoMap(i,moduli,Field,"LoadShowPopup",typeofppopup,"IS");
              $('#'+"LoadShowPopup").append(divinsert);
            }
          } 

        }else{
          // alert(mv_arr.MappingFiledValid);
          // App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
        }
      }
      else {
          // alert(mv_arr.ReturnFromPost);
          App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
        }
      }



/**
 * Generate html alertes only for IOMap
 *
 * @param      {(number|string)}  Idd        The idd
 * @param      {string}           moduli     The moduli
 * @param      {string}           fields     The fields
 * @param      {string}           divid      The divid
 * @param      {string}           typepopup  The typepopup
 * @return     {string}           { description_of_the_return_value }
 */
 function addToPopupIoMap(Idd,moduli,fields,divid,typepopup)
 {
   var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
   + '">';
   INSertAlerstJOIN += '<span class="closebtns" onclick="closePopupDataIoMap('
   + Idd + ',\'' + divid + '\');">&times;</span>';
   if (moduli && moduli!=='')
   {
    INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+mv_arr.module+' ==>'+moduli;
    INSertAlerstJOIN += '<br/> '+mv_arr.field+'  ==> '+fields;
  } else
  {
    INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+fields;
  }

  INSertAlerstJOIN += '</div';

  return INSertAlerstJOIN;
}

/**
 * Splits pops. Create get datata from inputs, to create popups only for IOMap
 *
 * @param      {<type>}  event   The event
 */
function split_popups(event){
   var elem=event;

   var allid=elem.dataset.addRelationId;
   var showtext=elem.dataset.showId;
   var Typeofpopup=elem.dataset.addType;
   var replace=elem.dataset.addReplace;
   var modulShow=elem.dataset.showModulId;
   var divid=elem.dataset.divShow;
   var validate=elem.dataset.addButtonValidate;

   var allidarray = allid.split(",");

   if (validate)
       {
         if (validate.length>0)
         {
          validatearray=validate.split(',');
         }else{validatearray.length=0;}
       }else{validatearray.length=0;}

   $('#LoadShowPopupInput div').remove();
   $('#'+divid+' div').remove();

   App.utils.Add_to_universal_popup(allidarray,Typeofpopup,showtext, modulShow,validatearray);

   if (App.popupJson.length>0)
   { 
    for (var i = 0; i <= App.popupJson.length-1; i++) {
      var Field=App.popupJson[i].temparray[`DefaultText`];
      var moduli=App.popupJson[i].temparray[`Moduli`];
      var typeofppopup=App.popupJson[i].temparray['JsonType'];

      if(typeofppopup==="Input"){
        var divinsert= addToPopupIoMap(i,moduli,Field,divid,typeofppopup,"IS");
        $('#'+divid).append(divinsert);
      }else if(typeofppopup==="Output"){
        var divinsert= addToPopupIoMap(i,moduli,Field,divid,typeofppopup,"IS");
        $('#'+divid).append(divinsert);
      }
    } 

  }else{
            // alert(mv_arr.MappingFiledValid);
            App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
          }
}

/**
 * to show the popup for every history
 *
 * @class      ShowHistoryDataLocal (name)
 * @param      {<type>}  Idload      The idload
 * @param      {string}  divHistory  The div history
 */
function ShowHistoryDataLocal(Idload,divHistory)
{
  var historydata=App.SaveHistoryPop[parseInt(Idload)];
  App.popupJson.length=0;
  App.ModulLabel='Module';
  App.FieldLabel='Label';
  for (var i=0;i<=historydata.PopupJSON.length-1;i++){
    App.popupJson.push(historydata.PopupJSON[i]);
  }
  if (App.popupJson.length>0)
  { 
    $('#' + divHistory + ' div').remove();
    for (var i = 0; i <= App.popupJson.length-1; i++) {
      var Field=App.popupJson[i].temparray[`DefaultText`];
      var moduli=App.popupJson[i].temparray[`Moduli`];
      var typeofppopup=App.popupJson[i].temparray['JsonType'];
      var divinsert= App.utils.DivPopup(i,moduli,Field,divHistory,typeofppopup);
      $('#'+divHistory).append(divinsert);
    } 
  }

}


/**
 *When user create a block, this function crear the blockName field, and unselect the selected fields.
 * { function_description }
 */
 function resetFieldCreateViewPortal() {
  $("#BlockName").val('');
  $("#FieldsForRow").find('option:selected').removeAttr("selected");
}


///  For Record Access Control


function Removechecked(event) {
  
  var elem=event;
  var allids=elem.dataset.allId;

 if (!elem.checked)
  {
    if (allids)
    {
      allids=allids.split(',');
      for (var i = allids.length - 1; i >= 0; i--) {
        $("#"+allids[i]).removeAttr('checked');
        $("#"+allids[i]).attr("disabled", true);
      }
    }

  }else
  {
    if (allids)
    {
      allids=allids.split(',');
      for (var i = allids.length - 1; i >= 0; i--) {         
        $("#"+allids[i]).removeAttr('disabled');
        $("#"+allids[i]).prop("checked", "checked");
      }
    }
  }
}


/**
 * this function is only for Master detail 
 *
 * @class      RemovecheckedMasterDetail (name)
 * @param      {<type>}  event   The event
 */
function RemovecheckedMasterDetail(event)
{
  
    var elem=event;
    var allids=elem.dataset.allId;

   if (elem.checked)
    {
      if (allids)
      {
        allids=allids.split(',');
        for (var i = allids.length - 1; i >= 0; i--) {
          $("#"+allids[i]).removeAttr('checked');
          $("#"+allids[i]).attr("disabled", true);
        }
      }

    }else
    {
      if (allids)
      {
        allids=allids.split(',');
        for (var i = allids.length - 1; i >= 0; i--) {         
          $("#"+allids[i]).removeAttr('disabled');
          $("#"+allids[i]).prop("checked", "checked");
        }
      }
    }
}






function resetFieldGlobalSearchAuto() {

    clear_selections = function(){
        setTimeout(function(){
            if($('#LoadShowPopup').find(".alerts").length==0){
                clear_selections();
            }else{
                // $("#Firstfield").find('option:selected').removeAttr("selected");
                // $("#Firstfield2").find('option:selected').removeAttr("selected");
                $("#FirstModule").val("");
                $("#Firstfield").find('option').remove();
                $("#Firstfield2").find('option').remove();
                $("#Firstfield").find('optgroup').remove();
                $("#Firstfield2").find('optgroup').remove();
               
            }
        },50);
    }
    clear_selections();
    
}


/////////////TEMPLATE.TPL 
/**
 * { function_description }
 *
 * @param      {boolean}  isactive  The isactive
 */
function selectTab(isactive=true) {
    if(isactive){
      $("#firstTab").addClass('active');
      $("#secondTab").removeClass('active');
      $("#LoadMAps").css('display','none');
      $("#CreateMaps").css('display','block');
    }else{
      $("#firstTab").removeClass('active');
      $("#secondTab").addClass('active');
      $("#LoadMAps").css('display','block');
      $("#CreateMaps").css('display','none');
    }
   
}

////////////// Record Access Controll ////////////////////////////

function RecordAccesLocalHistroty(IdLoad,divanameLoad,dividrelation='')
{
   var htmldat='<div class="Message Message"  >';
   htmldat+='<div class="Message-icon">';
   htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ClickToshowSelectedFileds(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;
   htmldat+='</div>';
   htmldat+='<div class="Message-body">';
   htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';
   htmldat+='</div>';
   htmldat+='</div>';
   return htmldat;
}


function ShowLocalHistoryRecordAccessControll(keephitoryidtoshow,keephitoryidtoshowidrelation)
{
    if (App.SaveHistoryPop.length>0)
    { 
       $('#'+keephitoryidtoshow+' div').remove();
       for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
        $('#'+keephitoryidtoshow).append(RecordAccesLocalHistroty(i,keephitoryidtoshow,keephitoryidtoshowidrelation));

      }
    }
}


function ClickToshowSelectedFileds(Idload,divHistory)
{
    var historydata=App.SaveHistoryPop[parseInt(Idload)];
    App.popupJson.length=0;
    App.ModulLabel='Module';
    App.FieldLabel='Related';
    for (var i=0;i<=historydata.PopupJSON.length-1;i++){
      App.popupJson.push(historydata.PopupJSON[i]);
    }
    if (App.popupJson.length>0)
    { 
      $('#' + divHistory + ' div').remove();
      for (var i = 0; i <= App.popupJson.length-1; i++) {
        var Field=App.popupJson[i].temparray[`DefaultText`];
        var moduli=App.popupJson[i].temparray[`FirstModuleText`];
        var typeofppopup=App.popupJson[i].temparray['JsonType'];
        var divinsert= App.utils.DivPopup(i,moduli,Field,divHistory,typeofppopup);
        $('#'+divHistory).append(divinsert);
      } 
    }
}



/////////// Field Dependency ///////////////


function removedataafterclick()
{
  setTimeout(function(){
    var selectid=$('#PickListFields');
    var count=$('#ShowmoreInput input').size();
    for (var i = 2; i<=count; i++) {
      var idtoremove='#DefaultValueFirstModuleField_'+i;
       $(idtoremove).parent().parent().parent().remove();
    }
    $('#DefaultValueFirstModuleField_1').val("");
    $('#AddToArray').attr('data-add-relation-id','PickListFields,DefaultValueFirstModuleField_1');
  },100);
   
}





function Checkifexist()
{
  var valuefromdropdown=$('#PickListFields  option:selected').val();

  var found=App.popupJson.some(function(el){
    return el.PickListFields===valuefromdropdown;
  });

  if (!found)
  {
    console.log("EdmondiKAcaj");
  }
  // for (var i = App.popupJson.length - 1; i >= 0; i--) {
  //     if (valuefromdropdown==App.popupJson[i].temparray[]) {}

  //   App.popupJson[i]
  // }
   
}



function CheckChoise(sel)
{
  var valueselected=sel.value;
  if (valueselected==='empty' || valueselected==='not empty')
  {
     $('#divbutton').animate({
      'margin-top': '26px',
      'margin-right': '200px'
    }, 'slow');
    //  .css({
    //   'margin-top': '26px',
    //   'margin-right': '200px'
    // });
    $('#DefaultValueResponsibel').hide('slow');
    $('#labelforinputDefaultValueResponsibel').hide('slow');
    $('#DefaultValueResponsibel').val(""); 
    $('#AddbuttonFDP').attr('data-add-relation-id', 'FirstModule,Firstfield,Conditionalfield');
  }else
  {
    $('#divbutton').animate({
      'margin-top': '0px',
      'margin-right': '0px'
    }, 'slow');
    $('#DefaultValueResponsibel').show('slow');
    $('#labelforinputDefaultValueResponsibel').show('slow');
     $('#AddbuttonFDP').attr('data-add-relation-id', 'FirstModule,DefaultValueResponsibel,Firstfield,Conditionalfield');
  }

}

function checkIfAdded(event){
  var values=event.value;
  if (values)
  {
    if(App.popupJson.length>0){
       for (var i = App.popupJson.length - 1; i >= 0; i--) {
           if (App.popupJson[i].temparray.PickListFields.replace(/\s+/g, '')===values)
            {
              alert(mv_arr.NotAllowedDopcicate);
              $('option:selected', event).removeAttr('selected');

            }
       }
    }
  }
}



function allcheckbosChecked(argument) {
   $('input:checkbox').prop('checked','checked');
   $('input:checkbox').removeAttr('disabled');
}

///////////// RendicontaConfig  //////////////////////


/**
 * Generate html alertes only for RendicontaConfig
 *
 * @param      {(number|string)}  Idd        The idd
 * @param      {string}           moduli     The moduli
 * @param      {string}           fields     The fields
 * @param      {string}           divid      The divid
 * @param      {string}           typepopup  The typepopup
 * @return     {string}           { description_of_the_return_value }
 */
function addToPopupRendicontaConfig(Idd,FirstModule,statusfield,processtemp,causalefield,divid,typepopup)
 {
   var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
   + '">';
   INSertAlerstJOIN += '<span class="closebtns" onclick="closePopupDataRendicontaConfig('
   + Idd + ',\'' + divid + '\');">&times;</span>';
   if (FirstModule && FirstModule!=='')
   {
      INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+mv_arr.module+' ==>'+FirstModule;
      INSertAlerstJOIN += '<br/> status field  ==> '+statusfield;
      INSertAlerstJOIN += '<br/> process templates  ==> '+processtemp;
      if (causalefield && causalefield!=="none")
      {
        INSertAlerstJOIN += '<br/> causal field  ==> '+causalefield;
      }
    
  } else
  {
    INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+fields;
  }

  INSertAlerstJOIN += '</div';

  return INSertAlerstJOIN;
}


/**
 * Closes a popup data rendiconta configuration.
 *
 * @param      {(number|string)}  remuveid  The remuveid
 * @param      {string}           namediv   The namediv
 */
function closePopupDataRendicontaConfig(remuveid,namediv) {
      var check = false;
      for (var ii = 0; ii <= App.popupJson.length-1; ii++)
      {
        if (ii == remuveid)
        {
           //JSONForCOndition.remove(remuveid);
           App.popupJson.splice(remuveid,1);
           check = true
        
        
        }
      }
      if (check) {
          var remuvediv="#alerts_"+remuveid;
          $( remuvediv).remove( );
          $('#' + namediv + ' div').remove();
          if (App.popupJson.length>0)
          { 
             for (var i = 0; i <= App.popupJson.length-1; i++) {
               var FirstModule=App.popupJson[i].temparray[`FirstModuleText`];
               var causalefield=App.popupJson[i].temparray[`causalefield`];
               if (causalefield && causalefield!=="none")
               {
                 causalefield=App.popupJson[i].temparray[`causalefieldText`];
               }else{causalefield="";}
               var processtempText=App.popupJson[i].temparray['processtempText'];
               var statusfieldText=App.popupJson[i].temparray['statusfieldText'];
               var JsonType=App.popupJson[i].temparray['JsonType'];
               var divinsert= addToPopupRendicontaConfig(i,FirstModule,statusfieldText,processtempText,causalefield,namediv,typeofpopup);
               $('#'+divid).append(divinsert);
             }

          }else{
          // alert(mv_arr.MappingFiledValid);
          // App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
        }
      }
      else {
        // alert(mv_arr.ReturnFromPost);
        App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
      }
}



/**
 * add popup when click add
 *
 * @class      AdDPOpupRendicontaConfig (name)
 * @param      {<type>}  sel     The selected
 */
function AdDPOpupRendicontaConfig(sel) {
    if (sel)
    {
      var idrelation=sel.dataset.addRelationId;
      var typeofpopup=sel.dataset.addType;
      var divid=sel.dataset.divShow; 
       var temparray={};
       var checkif=false;
      if (idrelation)
      {
           $('#'+divid+' div').remove();
           var optionalvalues=$("#"+idrelation.split(',')[3]+" option:selected").val();
           if (!optionalvalues)
           {
             $("#"+idrelation.split(',')[3]+" option:selected").val("none");
           }
           var arrayfields=idrelation.split(',');
           for (var i = arrayfields.length - 1; i >= 0; i--) {
               if (App.utils.IsSelectORDropDown(arrayfields[i]).length>0)
               {
                  temparray[arrayfields[i]]=App.utils.IsSelectORDropDown(arrayfields[i]);
                  temparray[arrayfields[i]+'Text']=App.utils.IsSelectORDropDownGetText(arrayfields[i]);
                  checkif=true;
               }else{
                 checkif=false;
                 break;
               }
           }
            if (checkif===true)
            {
              temparray['JsonType']=typeofpopup;
              var checkvalue={temparray};
              if (App.utils.checkinArray(App.popupJson,checkvalue)===false)
              {
                App.popupJson.push({temparray});
              }else
              {
                App.utils.ShowNotification("snackbar",2000,mv_arr.NotAllowedDopcicate);
              }
            }else
            {
              App.utils.ShowNotification("snackbar",2000,mv_arr.addJoinValidation);
            }

           if (App.popupJson.length>0)
           { 
            for (var i = 0; i <= App.popupJson.length-1; i++) {
               var FirstModule=App.popupJson[i].temparray[`FirstModuleText`];
               var causalefield=App.popupJson[i].temparray[`causalefield`];
               if (causalefield && causalefield!=="none")
               {
                 causalefield=App.popupJson[i].temparray[`causalefieldText`];
               }else{causalefield="";}
               var processtempText=App.popupJson[i].temparray['processtempText'];
               var statusfieldText=App.popupJson[i].temparray['statusfieldText'];
               var JsonType=App.popupJson[i].temparray['JsonType'];
               var divinsert= addToPopupRendicontaConfig(i,FirstModule,statusfieldText,processtempText,causalefield,divid,JsonType);
               $('#'+divid).append(divinsert);
             } 

          }else{
                    // alert(mv_arr.MappingFiledValid);
            App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
          }
    }
  }
}


////// local history 

function LocalHistoryRendicontaConfig(IdLoad,divanameLoad,dividrelation='')
{
   var htmldat='<div class="Message Message"  >';
   htmldat+='<div class="Message-icon">';
   htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ShowRendicontConfig(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;
   htmldat+='</div>';
   htmldat+='<div class="Message-body">';
   htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';
   htmldat+='</div>';
   htmldat+='</div>';
   return htmldat;
}


function ShowLocalHistoryRendiConfig(keephitoryidtoshow,keephitoryidtoshowidrelation)
{
    if (App.SaveHistoryPop.length>0)
    { 
       $('#'+keephitoryidtoshow+' div').remove();
       for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {
        $('#'+keephitoryidtoshow).append(LocalHistoryRendicontaConfig(i,keephitoryidtoshow,keephitoryidtoshowidrelation));

      }
    }
}


function ShowRendicontConfig(Idload,divHistory)
{
    var historydata=App.SaveHistoryPop[parseInt(Idload)];
    App.popupJson.length=0;
    App.ModulLabel='Module';
    App.FieldLabel='Related';
    for (var i=0;i<=historydata.PopupJSON.length-1;i++){
      App.popupJson.push(historydata.PopupJSON[i]);
    }
    if (App.popupJson.length>0)
    { 
      $('#' + divHistory + ' div').remove();
      for (var i = 0; i <= App.popupJson.length-1; i++) {
               var FirstModule=App.popupJson[i].temparray[`FirstModule`];
               var causalefield=App.popupJson[i].temparray[`causalefield`];
               if (causalefield && causalefield!=="none")
               {
                 causalefield=App.popupJson[i].temparray[`causalefieldText`];
               }else{causalefield="";}
               var processtempText=App.popupJson[i].temparray['processtempText'];
               var statusfieldText=App.popupJson[i].temparray['statusfieldText'];
               var JsonType=App.popupJson[i].temparray['JsonType'];
               var divinsert= addToPopupRendicontaConfig(i,FirstModule,statusfieldText,processtempText,causalefield,divHistory,JsonType);
               $('#'+divHistory).append(divinsert);
      } 
    }
}

function Empydata(event)
{
  setTimeout(function(){
    $('#DefaultValueFirstModuleField_1').val(""); 
  },100);
}

///////// Import bussines Mapping //////////////

/**
 * Generate html alertes only for Import bussines
 *
 * @param      {(number|string)}  Idd        The idd
 * @param      {string}           moduli     The moduli
 * @param      {string}           fields     The fields
 * @param      {string}           divid      The divid
 * @param      {string}           typepopup  The typepopup
 * @return     {string}           { description_of_the_return_value }
 */
function addToPopupImportBusiness(Idd,FirstModule,FirestFields,SecondFields,divid,typepopup)
{
     var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
     + '">';
     INSertAlerstJOIN += '<span class="closebtns" onclick="closePopupDataImportBussiness('
     + Idd + ',\'' + divid + '\');">&times;</span>';
     if (FirstModule && FirstModule!=='')
     {
        INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+mv_arr.module+' ==>'+FirstModule;
        INSertAlerstJOIN += '<br/> Field  ==> '+FirestFields;
        INSertAlerstJOIN += '<br/> Match  ==> '+SecondFields;
        
    }else
    {
      INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong><br/> '+fields;
    }

    INSertAlerstJOIN += '</div';

    return INSertAlerstJOIN;
}


function AQddImportBussinessMapping(argument) {
    
    var allids=argument.dataset.addRelationId;
    var addtype=argument.dataset.addType;
    var divshow=argument.dataset.divShow;
    var shoid=argument.dataset.showId;
    var showmodulid=argument.dataset.showModulId;

     $('#'+divshow+' div').remove();

    if (allids)
    {
        var allidarray=allids.split(",");
        App.utils.Add_to_universal_popup(allidarray,addtype,shoid,showmodulid);
    }
     if (App.popupJson.length>0)
    { 
      for (var i = 0; i <= App.popupJson.length-1; i++) {
          var FirstModule=App.popupJson[i].temparray[`Moduli`];
          var FirstfieldText=App.popupJson[i].temparray[`FirstfieldText`];
          var SecondFieldText=App.popupJson[i].temparray['SecondFieldText'];
          var typeofpopup=App.popupJson[i].temparray['JsonType'];
          var divinsert= addToPopupImportBusiness(i,FirstModule,FirstfieldText,SecondFieldText,divshow,typeofpopup);
          $('#'+divshow).append(divinsert);
        } 

    }else{
      // alert(mv_arr.MappingFiledValid);
      App.utils.ShowNotification("snackbar",2000,mv_arr.MappingFiledValid);
    }
}



/**
 * Closes a popup data rendiconta configuration.
 *
 * @param      {(number|string)}  remuveid  The remuveid
 * @param      {string}           namediv   The namediv
 */
function closePopupDataImportBussiness(remuveid,namediv) {
      var check = false;
      for (var ii = 0; ii <= App.popupJson.length-1; ii++)
      {
        if (ii == remuveid)
        {
           //JSONForCOndition.remove(remuveid);
           App.popupJson.splice(remuveid,1);
           check = true
        
        
        }
      }
      if (check) {
          var remuvediv="#alerts_"+remuveid;
          $( remuvediv).remove( );
          $('#' + namediv + ' div').remove();
          if (App.popupJson.length>0)
          { 
             for (var i = 0; i <= App.popupJson.length-1; i++) {
               var FirstModule=App.popupJson[i].temparray[`Moduli`];
               var FirstfieldText=App.popupJson[i].temparray[`FirstfieldText`];
               var SecondFieldText=App.popupJson[i].temparray['SecondFieldText'];
               var typeofpopup=App.popupJson[i].temparray['JsonType'];
               var divinsert= addToPopupImportBusiness(i,FirstModule,FirstfieldText,SecondFieldText,namediv,typeofpopup);
               $('#'+namediv).append(divinsert);
             }

          }else{
          // alert(mv_arr.MappingFiledValid);
          // App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
        }
      }
      else {
        // alert(mv_arr.ReturnFromPost);
        App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
      }
}


////// local history 

function LocalHistoryImportBussiness(IdLoad,divanameLoad,dividrelation='')
{
   var htmldat='<div class="Message Message"  >';
   htmldat+='<div class="Message-icon">';
   htmldat+=`<button style="border: none;padding: 10px;background: transparent;" onclick="ShowImportBussinesMapping(${IdLoad},'${dividrelation}')"><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>`;
   htmldat+='</div>';
   htmldat+='<div class="Message-body">';
   htmldat+='<p>@HISTORY : '+(IdLoad+1)+'</p>';
   htmldat+='</div>';
   htmldat+='</div>';
   return htmldat;
}


function ShowLocalHistoryImportBussiness(keephitoryidtoshow,keephitoryidtoshowidrelation)
{
    if (App.SaveHistoryPop.length>0)
    { 
       $('#'+keephitoryidtoshow+' div').remove();
       for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {
        $('#'+keephitoryidtoshow).append(LocalHistoryImportBussiness(i,keephitoryidtoshow,keephitoryidtoshowidrelation));

      }
    }
}


function ShowImportBussinesMapping(Idload,divHistory)
{
    var historydata=App.SaveHistoryPop[parseInt(Idload)];
    App.popupJson.length=0;
    App.ModulLabel='Module';
    App.FieldLabel='Related';
    for (var i=0;i<=historydata.PopupJSON.length-1;i++){
      App.popupJson.push(historydata.PopupJSON[i]);
    }
    if (App.popupJson.length>0)
    { 
      $('#' + divHistory + ' div').remove();
      for (var i = 0; i <= App.popupJson.length-1; i++) {
          var FirstModule=App.popupJson[i].temparray[`Moduli`];
          var FirstfieldText=App.popupJson[i].temparray[`FirstfieldText`];
          var SecondFieldText=App.popupJson[i].temparray['SecondFieldText'];
          var typeofpopup=App.popupJson[i].temparray['JsonType'];
          var divinsert= addToPopupImportBusiness(i,FirstModule,FirstfieldText,SecondFieldText,divHistory,typeofpopup);
          $('#'+divHistory).append(divinsert);
      } 
    }
}

////// Modal remove after

function removemodaleverytime() {

  //if ($("#ModalDiv").length === 0){
      $("#ModalDiv div").html('');
  //}
}


function clearInput(idtoremove){
  setTimeout(function(){
    $('#'+idtoremove).val(""); 
  },100);
}
