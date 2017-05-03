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
/*
 * Cancella l'ultimo Join, e se è presente solo un Join, richiama la funzione deleteJoin().
 */

function deleteLastJoin(){
  var campiSelezionati = [];
  jQuery('#rightValues :selected').each(function(i, selected){
  campiSelezionati[i] = jQuery(selected).text();
});


if (campiSelezionati.length === 0) {

    alert("E' necessario inserire i campi!");
    


}else{
    

    selTab1.pop();
    selField1.pop();
    selTab2 .pop();
    selField2.pop();
    nameDb= (document.getElementById('nameDb').value);
    if(selTab1.length===0){
        deleteJoin();    
    }
    else{
        jQuery.ajax({
            type: "POST",
            url: "index.php?module=MVCreator&action=MVCreatorAjax&file=compositoreQuery",
            data: "selTab1=" + selTab1 +"&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2 + "&nameDb=" + nameDb + "&campiSelezionati=" + campiSelezionati ,
            dataType: "html",
            success: function(msg)
            {
                jQuery("#results").html(msg); 
            },
            error: function()
            {
                alert("Chiamata fallita, si prega di riprovare...");
            }
     });
    }
   }
}

/*
* Cancella tutti i Join
*/

function deleteJoin(){
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    var txt=document.getElementById("results");
    txt.innerHTML="<b>Query Cancellata!!</b>";    
}

 /*
 * Vengono inviati i dati per comporre la query al compositoreQuery.
 */
function addJoin(action){
  var campiSelezionati = [];
  jQuery('#rightValues option').each(function() {
    campiSelezionati.push(jQuery(this).text());
});
  if (campiSelezionati.length === 0){
    alert("E' necessario inserire i campi!");
  }else{
    primaTab=document.getElementById('selTab1').value;
    secondaTab=document.getElementById('selTab2').value;
    
    joinPresente=false;
    for (i=0; i< selTab1.length;i++){
        if((selTab1[i]===primaTab && selTab2[i]===secondaTab)||(selTab1[i]===secondaTab && selTab2[i]===primaTab)){
            joinPresente=true;
        }
     }
        
      if(!joinPresente || action == "script"){  
        
        if(primaTab!==secondaTab){
            primoCampo=document.getElementById('selField1').value;
            secondoCampo=document.getElementById('selField2').value;
            if(primoCampo!=="" && secondoCampo!==""){
                if(action != "script"){
                selTab1.push(document.getElementById('selTab1').value);
                selTab2.push(document.getElementById('selTab2').value);
                selField1.push(primoCampo);
                selField2.push(secondoCampo);
                }
                if(jQuery("#condition").val() != 'undefined')
                var whereCondition = jQuery("#condition").val();
                console.log("ketu"+whereCondition);
                nameView= (document.getElementById('nameView').value);
                nameDb= (document.getElementById('nameDb').value);
                if(action == "join") url = "index.php?module=MVCreator&action=MVCreatorAjax&file=compositoreQuery&mod="+firstModule;
                else if(action == "script") url = "index.php?module=MVCreator&action=MVCreatorAjax&file=creaScript&whereCondition="+whereCondition+"&mod="+firstModule;

                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: "selTab1=" + selTab1 +"&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2 + "&nameView=" + nameView + "&nameDb=" + nameDb + "&campiSelezionati=" + campiSelezionati +"&installationID="+installationID,
                    dataType: "html",
                    success: function(msg)
                    {
                       if(action == "join") jQuery("#results").html(msg); 
                    },
                    error: function()
                    {
                        alert("Chiamata fallita, si prega di riprovare...");
                    }
                });
             }
             else{
                alert("E' necessario inserire i campi per la condizione di Join!");
             }
        }
        else{
              alert("Il join deve essere fatto su due tabelle diverse!");
        }
  }else{
      alert("Questo Join è già stato inserito");
  }
}
}


function generateJoin(){
 var campiSelezionati = [];
 var campiSelezionatiLabels = [];
 var sel =  jQuery('#selectableFields');
// console.log(sel);
 var optionsCombo = sel[0].innerHTML;
 for (var i=0, len=sel[0].options.length; i<len; i++) {
        opt = sel[0].options[i];
        if(opt.selected)
            campiSelezionati.push(opt.value);
}
if (campiSelezionati.length != 0){
var primoCampo = document.getElementById('selField1').value;
var secondoCampo = document.getElementById('selField2').value;
selField1.push(primoCampo);
selField2.push(secondoCampo);
selTab1.push(firstModule);
selTab2.push(secModule);
nameView= (document.getElementById('nameView').value);
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=compositoreQuery";

              var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    async:false,
                    data: {
                            selTab1:selTab1,
                            fmodule:firstModule,
                            smodule:secModule,
                            selField1:selField1,
                            selTab2:selTab2,
                            selField2:selField2,
                            installationID:installationID,
                            html:optionsCombo,
                            campiSelezionati:campiSelezionati,
                            nameView:nameView 
                            },
                    dataType: "html",
                    success: function(msg)
                    { 
                      jQuery("#results").html(msg); if (box) box.remove();
                      
                    },
                    error: function()
                    {
                        alert("Chiamata fallita, si prega di riprovare...");
                    }
                });
getFirstModule(selTab2);    
   }
}
/*
* Invia i dati delle <section> relative alle tabelle actions/dataUpadate.php,
* dove poi ci saranno delle funzioni che inseriranno tutti i campi delle
* rispettive tabelle nei rispettivi <section> per i campi.
*/

function generateScript(){
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
var campiSelezionati = [];
var campiSelezionatiLabels = [];
var sel =  jQuery('#selectableFields');
var optionsCombo = sel[0].innerHTML;
 for (var i=0, len=sel[0].options.length; i<len; i++) {
        opt = sel[0].options[i];
        if(opt.selected)
            campiSelezionati.push(opt.value);

}

if (campiSelezionati.length != 0){
var primoCampo = document.getElementById('selField1').value;
var secondoCampo = document.getElementById('selField2').value;
selField1.push(primoCampo);
selField2.push(secondoCampo);
selTab1.push(firstModule);
selTab2.push(secModule);
nameView= (document.getElementById('nameView').value);
if(jQuery("#whereCond").val() != 'undefined'){
    jQuery("#whereCond").trigger("change");
var whereCondition = jQuery("#whereCond").val();
}
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=creaScript";
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    async:false,
                    data: "selTab1=" + selTab1+"&fmodule="+firstModule+"&smodule="+secModule+"&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2+"&installationID="+installationID+ "&campiSelezionati=" + campiSelezionati+"&nameView=" + nameView+"&whereCondition="+whereCondition,
                    success: function(msg)
                    {
                      if (box) box.remove();
                    },
                    error: function()
                    {
                        alert("Chiamata fallita, si prega di riprovare...");
                    }
                });
}
}

function generateMap(){
	nameView= (document.getElementById('nameView').value);
	querygenerate=$('#generatedjoin').text();
	querygeneratecondition=$('#generatedConditions').text();
 var campiSelezionati = [];
  jQuery('#rightValues option').each(function() {
    campiSelezionati.push(jQuery(this).val());
});
if(jQuery("#condition").val() != 'undefined'){
jQuery("#condition").trigger("change");
var whereCondition = jQuery("#condition").val();
console.log(whereCondition);}
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=generateMap";
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    async:false,
					data:"nameView="+nameView+"&QueryGenerate="+querygenerate+querygeneratecondition,
                   /*  data: "selTab1=" + selTab1+"&fmodule="+firstModule+"&smodule="+secModule+"&selField1=" + selField1 + "&selTab2=" + selTab2 + "&selField2=" + selField2+"&installationID="+installationID+ "&campiSelezionati=" + campiSelezionati+"&nameView=" + nameView+"&whereCondition="+whereCondition, */
                    success: function(msg)
                    {
                        if (box) {
							box.remove();
							alert("La mappa viene generata");
						}
                    },
                    error: function()
                    {
                        alert("Chiamata fallita, si prega di riprovare...");
                    }
                });   
}

function updateSel(id, field){
    var selezionato=false;
    var table=document.getElementById(id).value;
    var tableText = document.getElementById(id).options[table].text;
//    var nameDb=document.getElementById("nameDb").value;
   
        if (in_array(table, tabelleSelezionate)){
            
            selezionato= true;
        }
        tabelleSelezionate.push(table);  
      
     jQuery.ajax({
            type: "POST",
            url: "index.php?module=MVCreator&action=MVCreatorAjax&file=dataUpdate",
            data: "table=" + tableText +"&field=" + field +"&nameDb=" + nameDb + "&selezionato=" + selezionato,
            dataType: "html",
            success: function(msg)
            {
                jQuery("#null").html(msg); 
            },
            error: function()
            {
                alert("Chiamata fallita, si prega di riprovare...");
            }
     });
    
}   

/*
* Prima controlla che la query prelevata dal <div id="results"> sia corretta,
* e poi la crea.
*/
function creaVista(){
     var stringa=((document.getElementById("results").innerHTML));
     var query=stripTag(stringa);
     var nameDb= (document.getElementById('nameDb').value);
     var nameView=((document.getElementById("nameView").value));
     jQuery.ajax({
        type: "POST",
        url: "index.php?module=MVCreator&action=MVCreatorAjax&file=creaVista",
        data: "query=" + query + "&nameDb=" +  nameDb +  "&nameView=" + nameView  ,
        dataType: "html",
        success: function(msg)
        {
            jQuery("#results").html(msg); 
        },
        error: function()
        {
            alert("Chiamata fallita, si prega di riprovare...");
        }
     });
     
}

/*
 * Prende tutte le tabelle della query di creazione della vista, e le inserisce in un'unico array.
 * Tutte le tabelle contenute in esso sono le tabelle di cui si vogliono 
 * controllare i log di mysql.
 */
function creaArray(){
    var arrayTab= [];
    for(i=0;i< selTab1.length;i++){
           if(!(in_array(selTab1[i],arrayTab))){
                arrayTab.push(selTab1[i]);
           }
       }
    for(j=0;j<selTab2.length;j++){
           if(!(in_array(selTab2[j],arrayTab))){
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
	for(i = 0; i < array_di_riferimento.length; i++) {
		if(valore_da_esaminare === array_di_riferimento[i]) {
			isValueInArray = true;
		}
	}
	return isValueInArray;
}

/*
 * Prende in ingresso una stringa contenente codice html, e restituisce la stringa
 * senza html.
 */
function stripTag(stringa){
    stringaCorretta=[];
    for(i=0;i < (stringa.length);i++){
        vero=true;
        if(ControlTextEquals(stringa[i], '<')){
            while(vero){
                i++;
                if(ControlTextEquals(stringa[i], '>')){
                    vero=false;
                }
            }
         }
         else{
            stringaCorretta=stringaCorretta+stringa[i]; 
         }
     }
     return stringaCorretta;
}

/*
 * Riceve due stringhe in ingresso, e se sono uguali restituisce true, altrimenti false.
 */
function ControlTextEquals(textA, textB){
	if (textA === textB){
		return true;
	}
	else{
		return false;
	}
}
/*
 *  Ricevendo l'id del div da visualizzare, lo mostra
 */
function visualizza(id){
  if (document.getElementById){
    if(document.getElementById(id).style.display === 'none'){
      document.getElementById(id).style.display = 'block';
    }else{
      document.getElementById(id).style.display = 'none';
    }
  }
}

/*
 *  Aggiorna la vista materializzata
 */

function updateView(){
    var nameDb= (document.getElementById('dbListViews').value);
    var nameView=document.getElementById('selViews').value;
    if(nameView!==""){
          jQuery.ajax({
            type: "POST",
                  url: "index.php?module=MVCreator&action=MVCreatorAjax&file=updateView",
            data: "nameView=" + nameView + "&nameDb=" + nameDb,
            dataType: "html",
            success: function(msg)
            {

                jQuery("#textmessage").html(msg); 
               
            },
            error: function()
            {
                alert("Chiamata fallita, si prega di riprovare...");
            }
         });
     }
     else{
         alert("E' necessario selezionare una vista!");
     }
}


function deleteView(){
    var nameDb= (document.getElementById('dbListViews').value);
    var nameView=document.getElementById('selViews').value;
    if(nameView!==""){
         jQuery.ajax({
            type: "POST",
             url: "index.php?module=MVCreator&action=MVCreatorAjax&file=deleteView",
            data: "nameView=" + nameView + "&nameDb=" + nameDb,
            dataType: "html",
            success: function(msg)
            {
               removeOptionSelected();
                jQuery("#textmessage").html(msg); 
            },
            error: function()
            {
                alert("Chiamata fallita, si prega di riprovare...");
            }
         });
     }
     else{
         alert("E' necessario selezionare una vista!");
     }
}

function removeOptionSelected()
{
     var elSel = document.getElementById('selViews');
  var i;
  for (i = elSel.length - 1; i>=0; i--) {
    if (elSel.options[i].selected) {
      elSel.remove(i);
    }
  }
}



/*
 * get Raport Values
 */

function sellist1(){
var val = document.getElementById('mod1').value;
 var url = "module=MVCreator&action=MVCreatorAjax&file=picklistmv";
      jQuery.ajax({
            type: "POST",
          data:"val="+val,
            url: 'index.php?'+url,
            success: function(response) {
             var str = response;
                        if(str!='')
                         document.getElementById('groupby1').innerHTML='<option value="None" >None</option>'+str;
             }

        });  
}

function choose_fields3(){
var val = document.getElementById('groupby1');
var v = val[val.selectedIndex].value;
var rec1 = document.getElementById('mod1');
var rec = rec1[rec1.selectedIndex].value;
console.log(rec);
      var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=fields";
       jQuery.ajax({
            type: "POST",
            data:"mod="+v+"&rec="+rec,
            url: url,
            success: function(response) {
                var res = response.split("$$");
                jQuery("#count").val(res[1]);
                jQuery("#fieldTab").empty();
                jQuery("#fieldTab").append(res[0]);
            }
        });
}

function choose_fields(reportID,divId){
      var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=getFields";
       jQuery.ajax({
            type: "POST",
            data:"reportID="+reportID+"&installationID="+installationID,
            url: url,
            success: function(response) {
                var res = response.split("$$");
                jQuery("#count").val(res[1]);
                jQuery("#"+divId).empty();
                if(divId == "fieldTab") jQuery("#"+divId).html('<tr><td width="55%" class="lvtCol"><b><input type=checkbox name="allids1" id="allids1"  onchange=\'checkvalues("fieldTab")\'>Field List</b></td><td  width="20%" class="lvtCol"  align="center"><b>Modules</b></td></tr>');
                else jQuery("#"+divId).html('<tr><td width="55%" class="lvtCol"><b><input type=checkbox name="allids" id="allids"  onchange=\'checkvalues("fieldTab2")\'>Field List</b></td><td  width="20%" class="lvtCol"  align="center"><b>Modules</b></td></tr>');
                jQuery("#"+divId).append(res[0]);
            }
        });
}

function openMenuCreaView(){
      jQuery("#crea").hide();
      jQuery("#creaRp").hide();
        jQuery.ajax({
        type: "POST",
             url: "index.php?module=MVCreator&action=MVCreatorAjax&file=creazioneVista",
        dataType: "html",
        success: function(msg)
        {
              jQuery("#content").html(msg); 
                            
        },
        error: function()
        {
            alert("Chiamata fallita, si prega di riprovare...");
        }
     });
     
}

function createFSScript(){
 jQuery("#content").empty();
 jQuery("#creaRp").hide();
 jQuery("#crea").show();
}

function createReportScript(nr){
 jQuery("#content").empty(); 
 jQuery("#crea").hide();
 jQuery("#fieldTab").empty();
 jQuery("#nr").val(nr);
 jQuery("#creaRp").show();
}

function createRaprtTable(){
var nometab = document.getElementById("nometab").value;
var reportId = document.getElementById("report").value;
var accins =  installationID;
var scriptname = document.getElementById("scriptsel").value;
var accinsmodule =  document.getElementById("accinsmodule").value;
if(scriptname == 1)
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=createFsAdocDetailTable";
else if(scriptname == 3) var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=newFS";
else if(scriptname == 4) var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=FSSpecial";
else var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=createUDTable";
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
       jQuery.ajax({
            type: "POST",
            data :"nometab="+nometab+"&reportId="+reportId+"&accins=" +accins+"&accinsmodule="+accinsmodule,
            url: url,
            success: function(response) {
                   if (box) box.remove();
            }
    });
}

function generateReportTable(filename){
var nometab = document.getElementById("tablename").value;
var reportId = document.getElementById("clientreport").value;
var data = jQuery('#tabelascript ').serialize(); 
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file="+filename;
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
       jQuery.ajax({
            type: "POST",
            data :"nometab="+nometab+"&reportId="+reportId+"&accins=" +installationID + "&"+ data,
            url: url,
            success: function(response) {
                   if (box) box.remove();
                   //add jquery window dialog box
                  jQuery( "#dialog-message" ).dialog({
                      modal: true,
                      buttons: {
                        Ok: function() {
                          jQuery( this ).dialog( "close" );
                        }
                      }
                });
            }
    });   
}

function generateReportTable2(filename){
var nometab = document.getElementById("tablename2").value;
var reportId = document.getElementById("clientreport2").value;
var data = jQuery('#tabelascript2').serialize(); 
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file="+filename;
var box = new ajaxLoader(document.body, {classOveride: 'blue-loader'});
       jQuery.ajax({
            type: "POST",
            data :"nometab="+nometab+"&reportId="+reportId+"&accins=" +installationID + "&"+ data,
            url: url,
            success: function(response) {
                   if (box) box.remove();
             //add jquery window dialog box
                  jQuery( "#dialog-message" ).dialog({
                      modal: true,
                      buttons: {
                        Ok: function() {
                          jQuery( this ).dialog( "close" );
                        }
                      }
                });
            }
    });   
}

function submitForm()
{
 jQuery.ajax
    ({
    type: "POST",
    url: "index.php?module=MVCreator&action=MVCreatorAjax&file=createRaport",
    data: jQuery('#ajaxform ').serialize(),
    cache: false,
    success: function(text)
    {
      alert(text);
    }
   });
}
/*
 * Apre il menù per l'aggiornamento delle viste
 */
//function checkall
function checkvalues(divId){  
var oTable = document.getElementById(divId);
iMax = oTable.rows.length;
for(i=1;i<=document.getElementById('count').value;i++){
if(divId == "fieldTab"){
document.getElementById('checkf'+i).checked = document.getElementById('allids1').checked;    
if(document.getElementById('allids1').checked==true) document.getElementById('checkf'+i).value=1;
else document.getElementById('checkf'+i).value=0;
}
else{
 document.getElementById('checkf'+i).checked = document.getElementById('allids').checked;   
 if(document.getElementById('allids').checked==true) document.getElementById('checkf'+i).value=1;
 else document.getElementById('checkf'+i).value=0;   
}
}
}

function openMenuManage(){
     jQuery("#crea").hide();
     jQuery("#creaRp").hide();
       jQuery.ajax({
        type: "POST",
          url: "index.php?module=MVCreator&action=MVCreatorAjax&file=gestioneViste",
        dataType: "html",
        success: function(msg)
        {
              jQuery("#content").html(msg); 
                            
        },
        error: function()
        {
            alert("Chiamata fallita, si prega di riprovare...");
        }
     });
     
}

/*
 * Controlla se l'utente ha inserito il nome della vista
 */
function isEmpty(){
  var testo=document.getElementById('nameView').value;
  empty=false;
  if(testo==="")
      {
          empty=true;
      }
      return empty;
}
/*
 * Apre il menù per la creazione dei JOIN
 */
function openMenuJoin(){
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    tabelleSelezionate = [];
    mycheck = new Array();
    allTable=new Array();
    jQuery('#myCheck:not(:checked)').each(function() {
        allTable.push(jQuery(this).val());
    });

   jQuery("#myCheck:checked").each(function(){
        mycheck.push(jQuery(this).val());
        
        //allTable.push(jQuery(this).val());
    });
    
    var presente=false;
    var nameView=((document.getElementById("nameView").value));
    for(i=0;i<allTable.length;i++){
       if(nameView===allTable[i]){
           presente=true;
       } 
    }
    
    if(!presente){
    
//   var nameDb=(document.getElementById("dbList").value);
    if(!isEmpty()){
            
            if(mycheck.length>=2){
                jQuery.ajax({
                type: "POST",
               url: "index.php?module=MVCreator&action=MVCreatorAjax&file=creazioneCondizioniJoin",
                data: "mycheck=" + mycheck + "&nameView=" + nameView + "&nameDb=" + nameDb,
                dataType: "html",
                success: function(msg)
                {
                      jQuery("#content").html(msg); 
                },
                error: function()
                {
                    alert("Chiamata fallita, si prega di riprovare...");
                }
             });
         }
         else
             {
                 alert("E' necessario selezionare almeno due tabelle!");
             }
    }
    else{
        alert("Inserire il nome della vista");
    }
    }
    else{
        alert("Il nome della vista scelto e' gia' stato utilizzato!");
    }
     
}

function openMenuJoin2(){
    jQuery("#firstStep").hide();
    selTab1 = [];
    selField1 = [];
    selTab2 = [];
    selField2 = [];
    tabelleSelezionate = [];
    mycheck = new Array();
    allTable=new Array();
    var presente=false;
    var nameView=((document.getElementById("nameView").value));
    for(i=0;i<allTable.length;i++){
       if(nameView===allTable[i]){
           presente=true;
       } 
    }
    if(!presente){
    if(!isEmpty()){
                jQuery.ajax({
                type: "POST",
                url: "index.php?module=MVCreator&action=MVCreatorAjax&file=creazioneCondizioniJoin",
                data: "mycheck=" + mycheck + "&nameView=" + nameView + "&nameDb=" + nameDb,
                dataType: "html",
                async:false,
                success: function(msg)
                {
                      jQuery("#content").html(msg); 
                },
                error: function()
                {
                    alert("Chiamata fallita, si prega di riprovare...");
                }
             });
    }
    else{
        alert("Inserire il nome della vista");
    }
    }
    else{
        alert("Il nome della vista scelto e' gia' stato utilizzato!");
    }
    getFirstModule();
}

function selDB(obj){
       var dbList = jQuery(obj).children(":selected").attr("id");     
       var dataDb = dbList.split("-");
       installationID = dataDb[0];
       nameDb = dataDb[1];
//        jQuery.ajax({
//        type: "POST",
//        url: "index.php?module=MVCreator&action=MVCreatorAjax&file=getTableDb",
//        data: "nameDb=" + nameDb,
//        dataType: "html",
//        success: function(msg)
//        {
//              jQuery("#selTab").html(msg); 
//                            
//        },
//        error: function()
//        {
//            alert("Chiamata fallita, si prega di riprovare...");
//        }
//     });
}      
   
function selDBViews(){
      
       var nameDbViews=document.getElementById('dbListViews').value; 
        jQuery.ajax({
        type: "POST",
           url: "index.php?module=MVCreator&action=MVCreatorAjax&file=getDbViews",
        data: "nameDbViews=" + nameDbViews,
        dataType: "html",
        success: function(msg)
        {
              jQuery("#selViews").html(msg); 
                            
        },
        error: function()
        {
            alert("Chiamata fallita, si prega di riprovare...");
        }
     });

}

function getFirstModule(selTab2){
if(firstModule != "" && firstModule !== undefined &&  selTab2.length != 0)
    {
        var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=firstModule&installationID="+installationID+"&firstModule="+firstModule+"&secModule="+selTab2;
    }
    else var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=firstModule&installationID="+installationID;
       jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        async:false,
        success: function(msg)
        {    
           if(msg != ''){
           jQuery('#mod').html('<option value="None">None</option>'+msg);      
           jQuery("#mod").selectmenu("refresh");
       }
        },
        error: function()
        {
            alert("error");
        }
     });
}

function dispalyModules() {
if(jQuery("#installmodules").is(":visible")) {
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=getInstallationEntities&installationID="+installationID; 
      jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(str)
        {    
            jQuery('#modscriptsel').html('<option value="None">None</option>'+str);
            jQuery("#modscriptsel").selectmenu("refresh");
        },
        error: function()
        {
            alert("error");
        }
     });  
// 
}
}

function getInstallationModules(dataItem){
    if(dataItem == 1){ 
    jQuery("#installmodules").show();
    var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=getInstallationEntities&installationID="+installationID; 
      jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(str)
        {    
            jQuery('#modscriptsel').html('<option value="None">None</option>'+str);
            jQuery("#modscriptsel").selectmenu("refresh");
        },
        error: function()
        {
            alert("error");
        }
     });  
}
else {
      jQuery("#installmodules").hide()
}
}

function getSecModule(obj){ 
var v = obj;
firstModule = obj;
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=fillModuleRel&mod="+v+"&installationID="+installationID;   
      jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(str)
        {    
            jQuery('#secmodule').html('<option value="None">None</option>'+str);
            jQuery("#secmodule").selectmenu("refresh");
        },
        error: function()
        {
            alert("error");
        }
     });
}

function populateReport(reportSelectId){
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=populateReport&installationID="+installationID+"&selectedview="+reportSelectId; 
      jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(str)
        {    
            jQuery('#'+reportSelectId).html('<option value="None">None</option>'+str);
            jQuery("#"+reportSelectId).selectmenu("refresh");
        },
        error: function()
        {
            alert("error");
        }
     });  
}

function getFirstModuleFields(obj){
var v = obj;
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=moduleFields&mod="+v+"&installationID="+installationID;
     jQuery.ajax({
        type: "POST",
        url: url,
        async:false,
        dataType: "html",
        success: function(str)
        {  
            var s = str.split(";");
            var str1=s[0];
            var str2=s[1];
            var str3=s[2];  
//        console.log(str1);
        if(jQuery('#selectableFields optgroup[label= '+v+']').html() == null)
        jQuery('#selectableFields').append(str1).change();

          jQuery('#selField1').val(str3);
        }
     });
}

function getSecModuleFields(obj){
var v1 = obj;
var sp = v1.split(";");
var mod= sp[0].split("(many)");
mod0 = mod[0].split(" ");
secModule = mod0[0];
var invers = 0;
if(mod.length == 1) {
    //invers join 
    invers = 1;
}
if(sp[1]!= "undefined") index = sp[1];
var url = "index.php?module=MVCreator&action=MVCreatorAjax&file=moduleFields&mod="+secModule+"&installationID="+installationID;
      jQuery.ajax({
        type: "POST",
        url: url,
        async:false,
        dataType: "html",
        success: function(str)
        {   
            var s = str.split(";");
            var str1=s[0];
            var str2=s[1];
            if(jQuery('#selectableFields optgroup[label= '+secModule+']').html() == null)
            jQuery('#selectableFields').append(str1).change();
//            console.log(str1)
            if(invers == 1){
            jQuery('#selField1').val(index);
            jQuery('#selField2').val(s[2]);
            }
            else 
            jQuery('#selField2').val(index);
        },
        error: function()
        {
            alert("error");
        }
     });

}
