/**
 * 
 */

(function(global, $) {

	var App = {
		baseUrl : null,
		initMethods : [],
		VauefromPost : null,
		JSONForCOndition : [],
		pageInitMethods : [],
		savehistoryar:null,
		popupJson : [],

		registerInit : function(initializer) {
			App.initMethods.push(initializer);
		},

		registerPageInit : function(initializer) {
			App.pageInitMethods.push(initializer);
		},
		/**
		 * load all function when this file called
		 */
		init : function() {
			App.TypeOfMaps.init();
			App.SelectModule.init();
			App.FunctionSend.init();
			App.GetModuleForMapGenerator.init();
			App.UniversalPopup.init();
			$.each(App.initMethods, function(i, initializer) {
				initializer();
			});
		},

	/*
	 * Called to load the page and every section uploaded via ajax on its
	 * content
	 */

	};

	App.TypeOfMaps = {

		init : function() {
			// data-load-show="true"
			$(document).on('change', 'select[data-load-show="true"]',
					App.TypeOfMaps.LoadShowPopup);
			$(document).on('click', 'button[data-load-show="true"]',
					App.TypeOfMaps.LoadShowPopup);
			$(document).on('change', 'select[data-load-Map="true"]',
					App.TypeOfMaps.TypeOfMap);
			
		},
		TypeOfMap : function() {
			var types = $('select[data-load-Map="true"]').attr(
					'data-type-select');
			var select = $('select[data-load-Map="true"]').find(":selected")
					.val();
			if (types == "TypeObject") {
				var divmap = $('select[data-load-Map="true"]').attr(
						'data-type-select');
				if (select.length > 0) {
					if (select == "Map") {
						$('#DivObjectID').hide('slow', function() {
							$('#DivObjectID').remove();
						});
						$('#MapDivID').css("display", "block");
					}

				} else {
					alert(mv_arr.Choseobject);
				}
			} else if (types == "TypeMap") {
				if (select.length > 0) {
					var nameview = $('#nameView').val();
					if (nameview.length >= 5) {
						App.utils
								.PostDataHTML(
										"index.php?module=MapGenerator&action=MapGeneratorAjax&file=ChoseeObject",
										"ObjectType=" + select + "&NameView="
												+ nameview);
						document.getElementById('MapDivID').innerHTML = VauefromPost;
						VauefromPost = null;

					} else {
						$('option:selected', this).removeAttr('selected');
						alert(mv_arr.NameQuery);
					}

				} else {
					alert(mv_arr.ChoseMap);
				}
			}
			if (select == "Mapping") {
				// idfieldfill,urlsend,dat
				var urlsend = [ "MapGenerator", "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			} else if (select == "SQL") {
				getFirstModule();
			}else if (select == "MasterDetail") {
				// idfieldfill,urlsend,dat
				var urlsend = [ "MapGenerator", "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}

		},

		LoadShowPopup : function(event) {
			if (event)
				event.preventDefault();//BUTTON,SELECT
			var elem = $(this);
			var methods = elem;
			var idrelation = methods.attr('data-load-show-relation').split(",");
			var divshowmodal = methods.attr('data-div-show');
			if (idrelation == undefined) {
				alert(mv_arr.attributeselect);
				return;
			}
			if (divshowmodal == undefined) {
				alert(mv_arr.divshowmodal);
				return;
			}
			$('#' + divshowmodal + ' div').remove();
			var FirstModuleval = $("#" + idrelation[0] + " option:selected")
					.val();// $('#mod').value;
			var FirstModuletxt = $("#" + idrelation[0] + " option:selected")
					.text();

			var FirstFieldval = $("#" + idrelation[1] + " option:selected")
					.val();// $('#mod').value;
			var FirstFieldtxt = $("#" + idrelation[1] + " option:selected")
					.text();

			var SecondModuleval = $("#" + idrelation[2] + " option:selected")
					.val();// $('#mod').value;
			var SecondModuletxt = $("#" + idrelation[2] + " option:selected")
					.text();

			if(elem[0].nodeName === "SELECT"){
				var SecondFieldval = $(this).find('option:selected').val();
				var SecondFieldtext = $(this).find('option:selected').text();				
			}else if(elem[0].nodeName === "BUTTON"){
				if (FirstFieldval) {
				   var SecondFieldval = $("#" + idrelation[3]).val();// $('#mod').value;
		           var SecondFieldtext = "Default-Value";
		            $("#" + idrelation[3]).val("");	
				}
				
			}

			if (FirstModuleval && FirstFieldval && SecondModuleval && SecondFieldval)
			{
				App.utils.addINJSON(FirstModuleval, FirstModuletxt, FirstFieldval,
						FirstFieldtxt, SecondModuleval, SecondModuletxt,
						SecondFieldval, SecondFieldtext);

				var check = false;
				var length_history = App.JSONForCOndition.length;
				// alert(length_history-1);
				for (var ii = 0; ii < App.JSONForCOndition.length; ii++) {
					var idd = ii;// JSONForCOndition[ii].idJSON;
					var firmod = App.JSONForCOndition[ii].FirstFieldtxt;
					var secmod = App.JSONForCOndition[ii].SecondFieldtext;
					// var selectedfields = JSONForCOndition[ii].ValuesParagraf;
					// console.log(idd+firmod+secmod);
					// console.log(selectedfields);
					if (ii == (length_history - 1)) {
						check = true;

					} else {
						check = false;
					}
					var alerstdiv = App.utils.alertsdiv(idd, firmod, secmod, check,
							divshowmodal);
					$('#' + divshowmodal).append(alerstdiv);

				}
            }else{
            	alert(mv_arr.addJoinValidation);
            }

		},

	};

	App.GetModuleForMapGenerator = {

		init : function() {
			// data-select-autolod="true"
			$(document).on('change', 'select[data-select-load="true"]',
					App.GetModuleForMapGenerator.GetSecondModuleField);
			$(document).on('change', 'select[data-second-select-load="true"]',
					App.GetModuleForMapGenerator.GetSecondField);
			$(document).on('click', 'a[data-showhide-load="true"]',
					App.GetModuleForMapGenerator.ChangeTextDropDown);
		},

		GetFirstModule : function(idfieldfill, urlsend, dat) {
			element = $(this);
			var data = "Data=" + dat;
			var returndata = null;
			var returndata = App.utils.PostDataGeneric(urlsend, data);
			$("#" + idfieldfill).empty();
			$("#" + idfieldfill).append(VauefromPost);
			VauefromPost = null;
		},
		GetSecondModuleField : function(event) {
			if (event)
				event.preventDefault();
			var elem = $(this);
			// data-second-module-id
			var urlsendfield;
			var secondmodule = elem.attr("data-second-module-id");
			var field = elem.attr("data-select-relation-field-id");
			var urlsend = elem.attr("data-module");
			var relationmodule=elem.attr("data-second-select-file");
			var secondmodulefile=elem.attr("data-second-module-file");
			var firstfieldid=elem.attr("data-select-fieldid");
			var urlsendmodule;
			var valueselected = elem.find(":selected").val();
			if (secondmodule != "undefined") {
				if (secondmodulefile)
				{
					urlsendmodule = [ urlsend, secondmodulefile ];
				}else
				{
					urlsendmodule = [ urlsend, "fillModuleRel" ];
				}
				
				var dat = "mod=" + valueselected;
				App.utils.PostDataGeneric(urlsendmodule, dat, "");
				$("#" + secondmodule).empty();
				$("#" + secondmodule)
						.append(
								'<option value="" selected="selected">Select a value</option>');
				$("#" + secondmodule).append(VauefromPost);
				VauefromPost = null;
			}
			if (field != "undefined") {
				if (relationmodule)
				 {
				 	if (relationmodule.length > 0)
				 	 {
				 	 	urlsendfield = [ urlsend, relationmodule ];
					 }else
					 {
					 	urlsendfield = [ urlsend, "moduleFields"];
					 }
				 }else
				 {
				 	urlsendfield = [ urlsend, "moduleFields"];
				 }
				
				var datfields = "mod=" + valueselected;
				App.utils.PostDataGeneric(urlsendfield, datfields, "");
				var s = VauefromPost.split(";");
				var str1 = s[0];
				var str2 = s[1];
				var str3 = s[2];
				$("#" + field).empty();
				if (firstfieldid)
				{
					$("#" + firstfieldid).val(str3);
				}
				// $("#" + field).append('<option value=""
				// selected="selected">Select a value</option>');
				$("#" + field).append(str1);
				VauefromPost = null;
			}
		},
		GetSecondField : function(event) {
			if (event)
				event.preventDefault();
			var elem = $(this);
			var relationid = elem.attr("data-second-select-relation-id");
			var modulesecondfield = elem.attr("data-module");
			var relationmodule=elem.attr("data-second-select-file");
			var firstfieldid=elem.attr("data-select-fieldid");
			var selectsecondfields = elem.find(":selected").val();
			if (relationid != "undefined") {
				var sp = selectsecondfields.split(";");
				var mod = sp[0].split("(many)");
				mod0 = mod[0].split(" ");
				secModule = mod0[0];
				var urlsendfield = [ modulesecondfield, "moduleFields" ];
				var datfields = "mod=" + secModule;
				if (relationmodule)
				 {
				 	if (relationmodule.length > 0)
				 	 {
				 	 	urlsendfield = [ modulesecondfield, relationmodule ];
					 }else
					 {
					 	urlsendfield = [ modulesecondfield, "moduleFields"];
					 }
				 }else
				 {
				 	urlsendfield = [ modulesecondfield, "moduleFields"];
				 }

				App.utils.PostDataGeneric(urlsendfield, datfields, "");
				var s = VauefromPost.split(";");
				var str1 = s[0];
				var str2 = s[1];
				var str3 = s[2];
				if (firstfieldid)
				{
					$("#" + firstfieldid).val(str3);
				}
				$("#" + relationid).empty();
				$("#" + relationid)
						.append(
								'<option value="" selected="selected">Select a value</option>');
				$("#" + relationid).append(str1);
				VauefromPost = null;
			}

		},

		ChangeTextDropDown : function() {
//			if (event)
//				event.preventDefault();
			var elem = $(this);
			var IdChange = elem.attr("data-tools-id").split(",");
//			if (IdChange.length<1) {
				//elem.click(function() {
					$("#"+IdChange[0] +",#"+IdChange[1]).slideToggle("slow");
				//});
//			}

		},

	};


	App.UniversalPopup={

		init:function(){
			$(document).on('click', 'button[data-add-button-popup="true"]',
					App.UniversalPopup.Add_show_Popup);
			$(document).on('click', 'button[data-click-closemodal="true"]',
								App.UniversalPopup.Add_show_Popup);
		},

		Add_show_Popup:function(event){
			//$('#contenitoreJoin').empty();
			$('#contenitoreJoin div').remove();
			 if (event) {event.preventDefault();}
			 var elem=$(this);
			 var allids=elem.attr("data-add-relation-id");

			 if (allids)
			 {
			 	var allidarray=allids.split(",");
			 	 App.utils.Add_to_universal_popup(allidarray);
			 	 if (App.popupJson.length>0)
			 	 	{
			 	 		for (var i = 0; i <= App.popupJson.length-1; i++) {
			 	 				var module=App.popupJson[i].temparray.Firstfield.split(":");
			 	 				var divinsert= App.utils.DivPopup(i,module[2],"contenitoreJoin");
			 	 				$('#contenitoreJoin').append(divinsert);
			 	 			}	

			 	 	}else{
			 	 		alert(mv_arr.ReturnErrorFromMap);
			 	 	}
			 }


		},

		closemodal:function(){

			if (event)
			{
				event.preventDefault();
			}

			var idtoclose=$(this).attr("data-closemodal-id");
			if (idtoclose.split(",")[0])
			{
				var check = false;
	   			 for (var ii = 0; ii <= App.popupJson.length; ii++) {
		        		if (ii == parseInt(idtoclose)) {
				             //JSONForCOndition.remove(remuveid);
				        	App.popupJson.splice(parseInt(idtoclose.split(",")[0]),1);
				            check = true
							//console.log(remuveid);
				           // console.log(ReturnAllDataHistory());
			          	}
	    			 }
			    if (check) {
			      var remuvediv="#alerts_"+parseInt(idtoclose.split(",")[0]);
			      $( remuvediv).remove( );
			      App.utils.ReturnAllDataHistory(idtoclose.split(",")[1]);

			       // $('#selectableFields option:selected').attr("selected", null);
			    }
			    else {
			        alert(mv_arr.ReturnFromPost);
			    }
			}
		},



	};

	App.SelectModule = {
		init : function() {
			$(document).on('change', 'select[data-select-autolod="true"]',
					App.SelectModule.GetModuls);
			// $(document).on('click',
			// 'select[data-load-automatic="true"]',App.SelectModule.GetModulsautomatic);
		},

		GetModuls : function(event) {
			if (event)
				event.preventDefault();
			var elem = $(this);
			var methods = elem;
			var functionstring = methods.attr('data-select-method');
			var datarelationid = methods.attr('data-select-relation-id');
			var select = methods.find(":selected").val();
			if (methods.length > 0) {
				var funcCall = "App.AllFunctions." + functionstring + "('"
						+ select + "');";
				eval(funcCall)
			}
			if (datarelationid != undefined && datarelationid.length > 0) {
				var sp = select.split(";");
				var mod = sp[0].split("(many)");
				mod0 = mod[0].split(" ");
				secModule = mod0[0];
				var invers = 0;
				if (mod.length == 1) {
					invers = 1;
				}
				if (sp[1] != "undefined")
					index = sp[1];
				App.AllFunctions.GetModuleFields(secModule, datarelationid);
				return;
			}
		},

	};

	App.FunctionSend = {

		init : function() {
			// data-send="true"
			$(document).on('click', 'button[data-send="true"]',
					App.FunctionSend.SendAjax);
		},

		SendAjax : function(event) {
			if (event)
				event.preventDefault();
			var elem = $(this);
			var datatusend="";
			var inputsplit=[];
			var urlcheck = elem.attr('data-send-url').split(",");
			var dataid=elem.attr("data-send-data-id");
			var savehistory=elem.attr("data-send-savehistory");
			if(dataid != "undefined"){
				inputsplit=dataid.split(",");
			}
			
			if (urlcheck[0] == "undefined" && urlcheck[1] == "undefined") {
				alert(mv_arr.Buttonsendajax);
			}
			
			if(inputsplit.length>0){
				for(index=0; index <= inputsplit.length-1; index++){
					if(inputsplit[index].toUpperCase()=="LISTDATA"){
						if(App.JSONForCOndition.length > 0){
							datatusend +=`ListData=${JSON.stringify(App.JSONForCOndition)}`;;
						}else{
							alert(mv_arr.MappingFiledValid);
						}
					}else{
						datatusend+= `&${inputsplit[index]}=${App.utils.IsSelectORDropDown(inputsplit[index])}`;
					}
						
				}
				
			}
			
			
            
             if (savehistory!="undefined" && savehistory=="true")
             {
             	if (App.savehistoryar)
             	{
             	 	datatusend+="&savehistory="+App.savehistoryar;
             	}else
             	{
             		datatusend+="&savehistory";
             	}
             }

             App.utils.PostDataGeneric(urlcheck,datatusend);
			if(VauefromPost){
				 var returndt=VauefromPost.split(",");
				 if(returndt[1]>0)
				 {
	 				App.savehistoryar=VauefromPost;
				    alert(mv_arr.ReturnSucessFromMap);
				    VauefromPost=null;
				 }else
				 {
				 	alert(mv_arr.ReturnErrorFromMap);
				 } 				
			}
			

		},
	};

	App.AllFunctions = {

		GetFirstModuleCombo : function(value) {
			getSecModule(value);
			getFirstModuleFields(value);
		},
		GetSecondModuleCombo : function(value) {
			getSecModuleFields(value);
		},
		GetModuleFields : function(select, datarelationid) {
			var url = "index.php?module=MapGenerator&action=MapGeneratorAjax&file=moduleFields&mod="
					+ select + "&installationID=" + installationID;
			App.utils.PostDataHTMLUrlPramas(url);
			var s = VauefromPost.split(";");
			var str1 = s[0];
			$("#" + datarelationid).empty();
			$("#" + datarelationid).append(str1);
			VauefromPost = null;
		},

	};

	App.utils = {

		PostDataHTML : function(response, dat) {
			jQuery.ajax({
				type : "POST",
				url : response,
				data : dat,
				dataType : "html",
				async : false,
				success : function(msg) {
					VauefromPost = msg;
				},
				error : function() {
					alert(mv_arr.ReturnFromPost);
				}
			});
		},
		PostDataGeneric : function(Urlsend, dat) {
			jQuery.ajax({
				type : "POST",
				url : "index.php?module=" + Urlsend[0] + "&action="
						+ Urlsend[0] + "Ajax&file=" + Urlsend[1] + "",
				dataType : "html",
				async : false,
				data : dat,
				success : function(msg) {
					VauefromPost = msg;
				},
				error : function() {
					alert(mv_arr.ReturnFromPost);
				}
			});
		},
		GetDataGeneric : function(Urlsend, dat) {

			jQuery.ajax({
				type : "GET",
				url : "index.php?module=" + Urlsend[0] + "&action="
						+ Urlsend[0] + "Ajax&file=" + Urlsend[1] + "",
				dataType : "html",
				async : false,
				data : dat,
				success : function(msg) {
					VauefromPost = msg;
				},
				error : function() {
					alert(mv_arr.ReturnFromPost);
				}
			});
		},
		PostDataHTMLUrlPramas : function(response) {
			jQuery.ajax({
				type : "POST",
				url : response,
				async : false,
				dataType : "html",
				success : function(msg) {
					VauefromPost = msg;
				},
				error : function() {
					alert(mv_arr.ReturnFromPost);
				}
			});

		},

		addINJSON : function(FirstModuleval, FirstModuletxt, FirstFieldval,
				FirstFieldtxt, SecondModuleval, SecondModuletxt,
				SecondFieldval, SecondFieldtext) {
			App.JSONForCOndition.push({
				idJSON : App.JSONForCOndition.length + 1,

				FirstModuleval : FirstModuleval,
				FirstModuletxt : FirstModuletxt,

				FirstFieldval : FirstFieldval,
				FirstFieldtxt : FirstFieldtxt,

				SecondModuleval : SecondModuleval,
				SecondModuletxt : SecondModuletxt,

				SecondFieldval : SecondFieldval,
				SecondFieldtext : SecondFieldtext,
			// selectedfields: JSONARRAY,
			// selectedfields: {JSONARRAY}
			});
		},

		Add_to_universal_popup:function(params,divid){
			var temparray={};
			var check =false;
			for (var i =0; i <= params.length - 1; i++) {
				if (App.utils.IsSelectORDropDown(params[i]).length>0)
				{
					temparray[params[i]]=App.utils.IsSelectORDropDown(params[i]);	
					check=true;
				}else
				{
					//alert(mv_arr.MappingFiledValid);
					check=false;
					break;

				}
				
			}
			if (check)
			{
				App.popupJson.push({temparray});	
			}
			
			
			

		},

		alertsdiv : function(Idd, Firstmodulee, secondmodule, last_check,
				namediv) {
			var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
					+ '">';
			INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoins('
					+ Idd + ',\'' + namediv + '\');">&times;</span>';
			// INSertAlerstJOIN += '<span class="closebtns"
			// onclick="closeAlertsAndremoveJoin('+Idd+');"><i
			// class="icono-eye"></</span>';
			INSertAlerstJOIN += '<strong># ' + Idd + ' JOIN!</strong> '
					+ Firstmodulee + '=>' + secondmodule;
			// if (last_check==true) {//icono-plusCircle
			// INSertAlerstJOIN +='<span title="You are here "
			// style="float:right;margin-top:-10px;margin-right:-46px;"><i
			// class="icono-checkCircle"></i></span>';
			// INSertAlerstJOIN +='<span title="run the query to show the
			// result"
			// style="float:right;margin-top:-10px;margin-right:-86px;"><i
			// class="icono-display"
			// onclick="openmodalrezultquery('+Idd+');"></i></span>';
			// }
			// else{
			// INSertAlerstJOIN +='<span onclick="show_query_History('+Idd +');"
			// title="click here to show the Query"
			// style="float:right;margin-top:-10px;margin-right:-46px;"><i
			// class="icono-plusCircle"></i></span>';
			// }
			INSertAlerstJOIN += '</div';
			return INSertAlerstJOIN;
		},

		ReturnAllDataHistory : function(namediv) {
			$('#' + namediv + ' div').remove();
			var check = false;
			var valuehistoryquery;
			var length_history = App.JSONForCOndition.length;
			// alert(length_history-1);
			for (var ii = 0; ii < App.JSONForCOndition.length; ii++) {
				var idd = ii// JSONForCOndition[ii].idJSON;
				var firmod = App.JSONForCOndition[ii].FirstFieldtxt;
				var secmod = App.JSONForCOndition[ii].SecondFieldtext;
				// valuehistoryquery = App.JSONForCOndition[ii].ValuesParagraf;
				// console.log(idd+firmod+secmod);
				// console.log(selectedfields);
				if (ii == (length_history - 1)) {
					check = true;

				} else {
					check = false;
				}
				var alerstdiv = App.utils.alertsdiv(idd, firmod, secmod, check,
						namediv);
				$('#' + namediv).append(alerstdiv);

			}
		},
		ReturnAllDataHistory2 : function(namediv) {
			$('#' + namediv + ' div').remove();
			var check = false;
			var valuehistoryquery;
			var length_history = App.popupJson.length;
			// alert(length_history-1);
			for (var ii = 0; ii < App.popupJson.length; ii++) {
				var idd = ii// JSONForCOndition[ii].idJSON;
				var firmod = App.popupJson[ii].temparray.Firstfield.split(":");
				
				// console.log(idd+firmod+secmod);
				// console.log(selectedfields);
				if (ii == (length_history - 1)) {
					check = true;

				} else {
					check = false;
				}
				var alerstdiv = App.utils.DivPopup(idd, firmod[2]);
				$('#' + namediv).append(alerstdiv);

			}
		},


		DivPopup : function(Idd,firstmodule,divid) {
			var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
					+ '">';
			INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('
					+ Idd + ',\'' + divid + '\');">&times;</span>';
			INSertAlerstJOIN += '<strong># Add !  '+(Idd+1)+'</strong> '+firstmodule;
			
			INSertAlerstJOIN += '</div';
			return INSertAlerstJOIN;
		},

		// closeAlertsAndremoveJoins:function(remuveid,namediv){
		// var check = false;
		// for (var ii = 0; ii <= App.JSONForCOndition.length; ii++) {
		// if (ii == remuveid) {
		// //JSONForCOndition.remove(remuveid);
		// App.JSONForCOndition.splice(remuveid,1);
		// check = true
		// //console.log(remuveid);
		// // console.log(ReturnAllDataHistory());
		// }
		// }
		// if (check) {
		// var remuvediv="#alerts_"+remuveid;
		// $( "div" ).remove( remuvediv);
		// App.utils.ReturnAllDataHistory(namediv);
		//
		// // $('#selectableFields option:selected').attr("selected", null);
		// }
		// else {
		// alert(mv_arr.ReturnFromPost);
		// }
		// },
		// call as executeFunctionByName("FormBuilder.PlainText", window,
		// "testing")
		// ;
		
		IsSelectORDropDown:function(IdType){
			    
			    var element = document.getElementById(IdType);
			    
			    if(element.tagName === 'SELECT')
			    {
			    	if ($("#" +IdType+ " option:selected").val().length>0)
			    	{
			    		return $("#" +IdType+ " option:selected").val();//+"##"+$("#" +IdType+ " option:selected").text();
			    	}else
			    	{
			    		return "";	
			    	}
			    	
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'text')
			    {
			    	return $("#" +IdType).val();//+"##"+$("#" +IdType).text();
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'hidden')
			    {
			    	return $("#" +IdType).val();//+"##"+$("#" +IdType).text();
			    	
			    }else if($('#'+IdType).is('textarea')){
			    	
			    	return $('#'+IdType).val();

			    }else if(element.type && element.type === 'checkbox')
			    {
			    	if (document.getElementById(IdType).checked==false)
			    	{
			    		return '0';
			    	}else
			    	{
			    		return '1';
			    	}
			    	
			    }else if(element.type && element.type === 'button')
			    {
			    	return document.getElementById(IdType).value;
			    	
			    }

			    return "";			
		},
		
		executeFunctionByName : function(functionName, context /* , args */) {
			var args = [].slice.call(arguments).splice(2);
			var namespaces = functionName.split(".");
			var func = namespaces.pop();
			for (var i = 0; i < namespaces.length; i++) {
				context = context[namespaces[i]];
			}
			return context[func].apply(context, args);
		}

	};

	$(App.init);
	window.App = App;

})(window, jQuery);
