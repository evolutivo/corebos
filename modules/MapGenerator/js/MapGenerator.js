/**
 * 
 */

(function(global, $) {
	global.historySave=[];
	var App = {
		baseUrl : null,
		initMethods : [],
		VauefromPost : null,
		JSONForCOndition : [],
		pageInitMethods : [],
		savehistoryar:null,
		popupJson : [],
		SaveHistoryPop:[],
		MultiList:[],

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
			$(document).on('change', 'select[data-label-change-load="true"]',
					App.TypeOfMaps.LoadLabel);

						
		},




		/**
		 * function to choose the type of map 
		 *
		 * @class      TypeOfMaps (name)
		 * @return     {boolean}  { description_of_the_return_value }
		 */
		TypeOfMap : function() {
			var types = $('select[data-load-Map="true"]').attr(
					'data-type-select');
			var select = $('select[data-load-Map="true"]').find(":selected")
					.val();
			var urlpost= $('select[data-load-Map="true"]').attr('data-type-select-module');

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
					// alert(mv_arr.Choseobject);
					App.utils.ShowNotification("snackbar",4000,mv_arr.Choseobject);
				}
			} else if (types == "TypeMap") {
				if (!urlpost && urlpost===""){/*alert(mv_arr.Buttonsendajax);*/ App.utils.ShowNotification("snackbar",4000,mv_arr.Buttonsendajax); return false;}
			else{urlpost=urlpost.split(','); }
				if (select.length > 0) {
					var nameview = $('#nameView').val();
					if (nameview.length >= 5) {
						App.utils
								.PostDataHTML(
										`index.php?module=${urlpost[0]}&action=${urlpost[0]}Ajax&file=${urlpost[1]}`,
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
				var urlsend = [ urlpost[0], "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			} else if (select == "SQL") {
				getFirstModule();
			}else if (select == "MasterDetail") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}else if (select == "ListColumns") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}else if (select == "ConditionQuery") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}else if (select == "Module_Set") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "firstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}else if (select == "IOMap") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "AllFields_File" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("AllFieldsInput",
						urlsend, dat);
			}else if (select == "FieldDependency") {
				// idfieldfill,urlsend,dat
				var urlsend = [ urlpost[0], "FirstModule" ];
				var dat = "FirstModul"
				App.GetModuleForMapGenerator.GetFirstModule("FirstModule",
						urlsend, dat);
			}

		},

		LoadLabel:function(event){
			if (event) {event.preventDefault();}
			var eleme=$(this);
			var filename=eleme.attr("data-select-filename");
			var setvalue=eleme.attr("data-set-value-to");
			var valuselectedet=eleme.attr("id");
			var moduleget=eleme.attr("data-module");
			var urlsend;
			if (filename)
			{
				if (filename.length!=0)
				{
					
					if (moduleget && moduleget.length>0)
					{
						urlsendfield = [ moduleget,filename];
					 	var datfields = `${setvalue}=${App.utils.IsSelectORDropDown(valuselectedet)}`;
						App.utils.PostDataGeneric(event,urlsendfield, datfields, "");
						if (VauefromPost)
						{
							App.utils.SetValueTohtmlComponents(setvalue,VauefromPost);		
						}else
						{

						}

					} else
					{
						alert(mv_arr.MissingModuleName);
					}
					


				}else
				{
					alert(mv_arr.MissingAtrribute);
				}
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

			var SecondModuleval = $("#" + idrelation[2] + " option:selected")
					.val();// $('#mod').value;
			
			if(elem[0].nodeName === "SELECT"){
				var SecondFieldval = $(this).find('option:selected').val();
				var SecondFieldtext = $(this).find('option:selected').text();
				 var SecondFieldOptionGrup = App.utils.GetSelectParent(elem[0].id);				
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
						SecondFieldval, SecondFieldtext,SecondFieldOptionGrup);

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
            	// alert(mv_arr.addJoinValidation);
            	App.utils.ShowNotification("snackbar",4000,mv_arr.addJoinValidation);
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
			$(document).on('blur', 'input[data-controll="true"]',
					App.GetModuleForMapGenerator.checkInput);
			$(document).on('click', 'a[data-autoload-maps="true"]',
					App.GetModuleForMapGenerator.AllMapsLoad);
			$(document).on('click', 'a[data-select-map-load="true"]',
								App.GetModuleForMapGenerator.LadAllMaps);
		},

		GetFirstModule : function(idfieldfill, urlsend, dat) {
			element = $(this);
			var data = "Data=" + dat;
			var returndata = null;
			var returndata = App.utils.PostDataGeneric(null,urlsend, data);
			$("#" + idfieldfill).empty();
			$("#" + idfieldfill).append('<option value="" selected="selected">Select a value</option>');
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
				App.utils.PostDataGeneric(event,urlsendmodule, dat, "");
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
				App.utils.PostDataGeneric(event,urlsendfield, datfields, "");
				var s = VauefromPost.split(";");
				var str1 = s[0];
				var str2 = s[1];
				var str3 = s[2];
				$("#" + field).empty();
				if (firstfieldid)
				{
					$("#" + firstfieldid).val(str3);
				}

				field=field.split(',');

				for (var i = field.length - 1; i >= 0; i--) {
					$("#" + field[i]).append('<option value="">Select a value</option>');
					$("#" + field[i]).append(str1);
				}
				
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

				App.utils.PostDataGeneric(event,urlsendfield, datfields, "");
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
	 checkInput:function(event)
	 {
			if (event) {event.preventDefault();}

			var elem=$(this);
			var filecheck=elem.attr('data-controll-file');
			var valuetxt=elem.val();
			var idtxt=elem.attr('name');;
			var  idhshow=elem.attr('data-controll-idlabel');
			var idrelation=elem.attr('data-controll-id-relation');
			if (this.value.length>5)
			{
				if (filecheck)
			  {
				if (valuetxt.length<5)
				{
					$('#'+idhshow).fadeIn('fast');//.delay(1000).fadeOut('slow')
					$('#'+idhshow).html(mv_arr.NameQuery);
					//elem.focus();
				}else
				{
					var dat=`${idtxt}=${valuetxt}`;
					App.utils.PostDataGeneric(event,filecheck.split(','),dat);
					if (VauefromPost)
					{
						if (VauefromPost==="0")
						{
							// $('#'+idhshow).fadeIn('fast').delay(3000).fadeOut('slow')
							// $('#'+idhshow).html(mv_arr.MapNameNotExist);
							// if(idrelation)
							// {
							 	$('#'+idrelation).removeAttr('disabled');
							// }
							//elem.focus();
							App.utils.ShowNotification("snackbar",5000,mv_arr.MapNameNotExist);
						}else
						{
							// $('#'+idhshow).fadeIn('fast');
							// $('#'+idhshow).html(mv_arr.MapNameExist);
							// if(idrelation)
							// {
							// 	$('#'+idrelation).attr('disabled', 'true');
							// }
							//elem.focus();
							App.utils.ShowNotification("snackbar",4000,mv_arr.MapNameExist);
						}

					}else
					{
						
					}
				}


				}else
				{
					alert(mv_arr.NameOFMapMissingFile);
				}
			}else
			{
				// $('#'+idhshow).fadeIn('fast');//.delay(1000).fadeOut('slow')
				// 	$('#'+idhshow).html(mv_arr.NameQuery);
				App.utils.ShowNotification("snackbar",4000,mv_arr.NameQuery);
			}

	 },

	 AllMapsLoad:function(event)
	 {
		 	if (event) {event.preventDefault();}
		 	 var elem=$(this);
		 	 var getfile=elem.attr('data-autoload-Filename');
		 	 var getType=elem.attr('data-autoload-Type-Map');
		 	 var idtofill=elem.attr('data-autoload-id-relation');
		 	 // var thisid=elem.attr('id');
		 	 var datsend="";
		 	 if (getfile)
		 	 {
		 	 	getfile=getfile.split(",");
		 	 }else
		 	 {
		 	 	getfile=["MapGenerator","GetAllMaps"];
		 	 }

		 	 if (getType)
		 	 {
		 	 	datasend=`${getType}=${getType}`;
		 	 }

		 	 App.utils.PostDataGeneric(event,getfile,datasend);
		 	 if (VauefromPost)
		 	 {
		 	 	if (idtofill)
		 	 	{
		 	 		$('#'+idtofill).html('');
		 	 		$('#'+idtofill).append('<option value="">Select a value</option>');
		 	 		$('#'+idtofill).append(VauefromPost);
		 	 		VauefromPost=null;
		 	 	}else
		 	 	{
		 	 		// alert(mv_arr.MissingIDtoShow);
		 	 		App.utils.ShowNotification("snackbar",4000,mv_arr.MissingIDtoShow);
		 	 	}

		 	 }
		 	 else
		 	 {

		 	 }
	 },

	 /**
	  * Function to select a map from a dropdown and after to show the rezult in a specific place 
	  */
	 LadAllMaps:function(event)
	 {
		 	if (event) {event.preventDefault();}
		 	var elem=$(this);
		 	var iddropdown=elem.attr('data-select-map-load-id-relation');
		 	var urltosend=elem.attr('data-select-map-load-url');
		 	var idtoshow=elem.attr('data-select-map-load-id-to-show');

		 	if (!urltosend)
		 	{
		 		// alert(mv_arr.NameOFMapMissingFile);
		 		App.utils.ShowNotification("snackbar",4000,mv_arr.NameOFMapMissingFile);
		 		return false;
		 	}

	        if (iddropdown)
	        {
	        	var valuesfromdropdown=App.utils.IsSelectORDropDown(iddropdown);
	        	if (valuesfromdropdown && valuesfromdropdown.length>0)
	        	{
	        		var datasendto=`${iddropdown}=${valuesfromdropdown}`;
	        		App.utils.PostDataGeneric(event,urltosend.split(","),datasendto);

	        		if (!VauefromPost)
	        		{
	        			// alert(mv_arr.ReturnErrorFromMap);
	        			App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnErrorFromMap);
	        			return false;
	        		}
	        		if (idtoshow)
	        		{
	        			$('#'+idtoshow).html("");
	        			$('#'+idtoshow).html(VauefromPost);
	        			VauefromPost=null;
	        		}else
	        		{
	        			// alert(mv_arr.MissingDivID);
	        			App.utils.ShowNotification("snackbar",4000,mv_arr.MissingDivID);
	        		}


	        	}else
	        	{
	        		// alert(mv_arr.ChoseMap);
	        		App.utils.ShowNotification("snackbar",4000,mv_arr.ChoseMap);
	        	}


	        }else
	        {
	        	// alert(mv_arr.MissingIdValue);
	        	App.utils.ShowNotification("snackbar",4000,mv_arr.MissingIdValue);
	        	return false;
	        }



	 },

	};


	App.UniversalPopup={

		init:function(){
			$(document).on('click', 'button[data-add-button-popup="true"]',
					App.UniversalPopup.Add_show_Popup);
			$(document).on('click', 'button[data-click-closemodal="true"]',
								App.UniversalPopup.Add_show_Popup);
			$(document).on('click', 'button[ data-modal-saveas-close="true"]',
								App.UniversalPopup.CloseModalWithoutCheck);
			$(document).on('click', 'button[data-modal-saveas-open="true"]',
								App.UniversalPopup.OpeModalsaveAsMap);
			$(document).on('change', 'select[data-add-button-popup="true"]',
								App.UniversalPopup.Add_show_Popup);

		},

		Add_show_Popup:function(event){
			//$('#contenitoreJoin').empty();
			 $('#LoadShowPopup div').remove();
			 if (event) {event.preventDefault();}
			 var elem=$(this);
			 var allids=elem.attr("data-add-relation-id");
			 var showtext=elem.attr("data-show-id");
			 var Typeofpopup=elem.attr('data-add-type');
			 if (allids)
			 {
			 	var allidarray=allids.split(",");
			 	if (Typeofpopup)
			 	{
			 		App.utils.Add_to_universal_popup(allidarray,Typeofpopup,showtext);
			 	} else
			 	{
			 		App.utils.Add_to_universal_popup(allidarray,"Default",showtext);
			 	}
			 	 
			 	 if (App.popupJson.length>0)
			 	 	{	
			 	 		for (var i = 0; i <= App.popupJson.length-1; i++) {
			 	 				var module=App.popupJson[i].temparray[`DefaultText`];
			 	 				var typeofppopup=App.popupJson[i].temparray['JsonType'];
			 	 				var divinsert= App.utils.DivPopup(i,module,"LoadShowPopup",typeofppopup);
			 	 				$('#LoadShowPopup').append(divinsert);
			 	 			}	

			 	 	}else{
			 	 		// alert(mv_arr.MappingFiledValid);
			 	 		App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
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
			        // alert(mv_arr.ReturnFromPost);
			        App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnFromPost);
			    }
			}
		},

	   CloseModalWithoutCheck:function(event){
	   	 if (event) {event.preventDefault();}
	   	 var elem=$(this);
	   	 var closemodal=elem.attr('data-modal-close-id');
	   	 var closebackdrop=elem.attr('data-modal-close-backdrop-id');

	   	 if (!closemodal) {closemodal="modal";}
	   	 if (!closebackdrop) {closebackdrop="backdrop";}

	   	 $('#ErrorVAlues').text('');
	     $('#'+closemodal).removeClass('slds-fade-in-open');
	     $('#'+closebackdrop).removeClass('slds-backdrop--open');

	   },

	   OpeModalsaveAsMap:function(event){
	   	 if (event) {event.preventDefault();}
	   	 var elem=$(this);
	   	 var dataToCheck=elem.attr('data-modal-check-id');
	   	 var idModals=elem.attr('data-modal-id');
	   	 var idbackdrop=elem.attr('data-modal-backdrop-id');
	   	 var truorfalse=false;

	   	 if (!idModals){idModals="modal";}
		 if (!idbackdrop){idbackdrop="backdrop";}

	   	 if (dataToCheck)
	   	 {
	   	 	dataToCheck=dataToCheck.split(',');
	   	 	for (var i=0;i <= dataToCheck.length - 1; i++) {
	   	 		if (App.utils.IsSelectORDropDown(dataToCheck[i]).length>0)
	   	 		 {
	   	 		 	truorfalse=true;
	   	 		 }else
	   	 		 {
	   	 		 	truorfalse=false;
	   	 		 }
	   	 	}

	   	 	if (truorfalse===true)
	   	 	{
	   	 		$('#'+idbackdrop).addClass('slds-backdrop--open');
    	 		$('#'+idModals).addClass('slds-fade-in-open');
	   	 	} else
	   	 	{
	   	 		App.utils.ShowNotification("snackbar",4000,mv_arr.Fieldsaremepty);
	   	 	}

	   	 } else
	   	 {
	   	 	$('#'+idbackdrop).addClass('slds-backdrop--open');
    	 	$('#'+idModals).addClass('slds-fade-in-open');
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
			$(document).on('click', 'button[data-history-close-modal="true"]',
					App.FunctionSend.RemoveModalHistory);
			$(document).on('click', 'button[ data-history-show-modal="true"]',
					App.FunctionSend.ShowModalHistory);
		},

		/**
		 * function to send data for generate map
		 * @param {[type]} event [description]
		 */
		SendAjax : function(event) {
			event.preventDefault();
			var elem = $(this);
			var datatusend="";
			var inputsplit=[];
			var urlcheck = elem.attr('data-send-url').split(",");
			var dataid=elem.attr("data-send-data-id");
			var savehistory=elem.attr("data-send-savehistory");
			var sendSaveAs=elem.attr('data-send-saveas');
			var idbutton=elem.attr('data-send-saveas-id-butoni');
			var keephitory=elem.attr('data-save-history');
			var keephitoryidtoshow=elem.attr('data-save-history-show-id');
			var keephitoryidtoshowidrelation=elem.attr('data-save-history-show-id-relation');
			if(dataid != "undefined"){
				inputsplit=dataid.split(",");
			}
			
			if (urlcheck[0] == "undefined" && urlcheck[1] == "undefined") {
				// alert(mv_arr.Buttonsendajax);
				App.utils.ShowNotification("snackbar",4000,mv_arr.Buttonsendajax);
				return false;
			}
			
			if(inputsplit.length>0){
				for(index=0; index <= inputsplit.length-1; index++){
					if(inputsplit[index].toUpperCase()=="LISTDATA"){
						if(App.JSONForCOndition.length > 0 || App.popupJson.length>0){
							var datasend=App.JSONForCOndition.length>0 ? App.JSONForCOndition:App.popupJson;
							datatusend +=`ListData=${JSON.stringify(datasend)}`;
						}else{
							// alert(mv_arr.MappingFiledValid);
							App.utils.ShowNotification("snackbar",4000,mv_arr.MappingFiledValid);
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

             App.utils.PostDataGeneric(event,urlcheck,datatusend);
			if(VauefromPost){
				 var returndt=VauefromPost.split(",");
				 if(returndt[1]>0)
				 {
				 	if ((keephitory && keephitory==="true") && returndt[1]!==null)
				 	{

				 		if (App.savehistoryar===VauefromPost)
				 		{
				 			if (App.JSONForCOndition.length>0)
				 			{
				 				HistoryPopup.addtoarray(App.JSONForCOndition,"JSONCondition");
				 			}else
				 			{
				 				HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
				 			}
				 			
				 			App.savehistoryar=VauefromPost;
							// alert(mv_arr.ReturnSucessFromMap);
							App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnSucessFromMap);
							VauefromPost=null;
				 		}else
				 		{
				 			App.SaveHistoryPop.length=0;
				 			if (App.JSONForCOndition.length>0)
				 			{
				 				HistoryPopup.addtoarray(App.JSONForCOndition,"JSONCondition");
				 			}else
				 			{
				 				HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
				 			}
				 			App.savehistoryar=VauefromPost;
							// alert(mv_arr.ReturnSucessFromMap);
							App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnSucessFromMap);
							VauefromPost=null;
				 		}
				 	}else
				 	{
				 		App.savehistoryar=VauefromPost;
						// alert(mv_arr.ReturnSucessFromMap);
						App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnSucessFromMap);
						VauefromPost=null;
				 	}
	 				
				 }else
				 {
				 	// alert(mv_arr.ReturnErrorFromMap);
				 	App.utils.ShowNotification("snackbar",4000,mv_arr.ReturnErrorFromMap);
				 }
			}
			if (sendSaveAs && sendSaveAs==="true")
			{
				var ulrsaveas=[urlcheck[0],"SavenewMap"];
				dat=`data=${urlcheck}&dataid=${dataid}&savehistory=${savehistory}`;
				App.utils.PostDataGeneric(event,ulrsaveas,dat);
				if (VauefromPost)
				{
					 //document.body.innerHTML +=VauefromPost;
					 $('body').append(VauefromPost);
					 VauefromPost=null;
					 $('#'+idbutton).removeAttr('disabled')
				} else
				{

				}
              
			}
			if (keephitoryidtoshow)
			{
				App.utils.AddtoHistory(keephitoryidtoshow,keephitoryidtoshowidrelation);
			}else
			{

			}
			App.UniversalPopup.CloseModalWithoutCheck();
			},
		
		/**
		 * function to remove the history popup 
		 * @param {[type]} event [description]
		 */
		RemoveModalHistory:function(event) {
			event.preventDefault();
			 var elem=$(this);
			 var idtoremove=elem.attr('data-history-close-modal-id');
			 var keephitoryidtoshow=elem.attr('data-history-close-modal-divname');
			 var idrelationdiv=elem.attr(' data-history-show-modal-divname-relation');
			 if (idtoremove)
			 {
			 	App.SaveHistoryPop.splice(parseInt(idtoremove),1);
			 }else
			 {
			 	// alert(mv_arr.RemovedivHistory);
			 	App.utils.ShowNotification("snackbar",4000,mv_arr.RemovedivHistory);
			 }
			 if (keephitoryidtoshow)
			 {
			 	App.utils.AddtoHistory(keephitoryidtoshow,idrelationdiv);
			 }
		},
		
		/**
		 * function to show the modal history
			 * @param {[type]} event 
		 */
		ShowModalHistory:function(event) {
			event.preventDefault();
			var elem=$(this);
			var idtoshow=elem.attr('data-history-show-modal-id');
			var diwtoshow=elem.attr('data-history-show-modal-divname');
			var iddivrelation=elem.attr('data-history-show-modal-divname-relation');
			var functionToCall=elem.attr('data-history-show-modal-function');
			if (!diwtoshow)
			{
				// alert(mv_arr.MissingDivID);
				App.utils.ShowNotification("snackbar",4000,mv_arr.MissingDivID);
			}

			if (!iddivrelation) {/*alert(mv_arr.MissingIDtoShow);*/App.utils.ShowNotification("snackbar",4000,mv_arr.MissingIDtoShow);}
			else
			{
				var historydata=App.SaveHistoryPop[parseInt(idtoshow)];
				if (App.SaveHistoryPop[parseInt(idtoshow)].JSONCondition.length>0)
				{	
					App.JSONForCOndition.length=0;
					for (var i=0;i<=historydata.JSONCondition.length-1;i++){
						App.JSONForCOndition.push(historydata.JSONCondition[i]);
					}
					if (functionToCall && functionToCall!=="none")
					{
						var funcCall =functionToCall + "();";
						eval(funcCall);
					}else if (iddivrelation)
					{
						App.utils.ReturnAllDataHistory(iddivrelation);
					}else
					{
						// alert(mv_arr.MissingDivID);
						App.utils.ShowNotification("snackbar",4000,mv_arr.MissingDivID);
					}

				}else
				{
					App.popupJson.length=0;
					for (var i=0;i<=historydata.PopupJSON.length-1;i++){
						App.popupJson.push(historydata.PopupJSON[i]);
					}

					if (functionToCall && functionToCall!=="none")
					{
						var funcCall =functionToCall + "();";
						eval(funcCall);
					}else if (iddivrelation)
					{
						App.utils.ReturnAllDataHistory2(iddivrelation);
					}else
					{
						// alert(mv_arr.MissingDivID);
						App.utils.ShowNotification("snackbar",4000,mv_arr.MissingDivID);
					}

				}
			}
			for (var i = App.SaveHistoryPop.length - 1; i >= 0; i--) {
				if (i===parseInt(idtoshow))
				{
					$('#Spanid_',i).removeClass('fa fa-exclamation');
					$('#Spanid_',i).addClass('fa fa-check');
				} else
				{
					$('#Spanid_',i).removeClass('fa fa-check');
					$('#Spanid_',i).addClass('fa fa-exclamation');
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

		/**
		 * PostDataGeneric is a function to post data from ajax 
		 * @param {[type]} Urlsend the URL
		 * @param {[type]} dat     data to send 
		 */
		PostDataGeneric : function(event=null,Urlsend, dat) {
			if (event) {event.preventDefault();}
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

		/**
		 * [GetDataGeneric is a function to get data from Ajax
		 * @param {[type]} Urlsend url 
		 * @param {[type]} dat     params you pas to get 
		 */
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

		/**
		 * PostDataHTMLUrlPramas  function to post data
		 * @param {[type]} response the url with data 
		 */
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

        /**
         * [addINJSON  to insert into a Array 
         * @param {[type]} FirstModuleval        [description]
         * @param {[type]} FirstModuletxt        [description]
         * @param {[type]} FirstFieldval         [description]
         * @param {[type]} FirstFieldtxt         [description]
         * @param {[type]} SecondModuleval       [description]
         * @param {[type]} SecondModuletxt       [description]
         * @param {[type]} SecondFieldval        [description]
         * @param {[type]} SecondFieldtext       [description]
         * @param {[type]} SecondFieldOptionGrup [description]
         */
		addINJSON : function(FirstModuleval, FirstModuletxt, FirstFieldval,
				FirstFieldtxt, SecondModuleval, SecondModuletxt,
				SecondFieldval, SecondFieldtext,SecondFieldOptionGrup)
		    {
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
					SecondFieldOptionGrup : SecondFieldOptionGrup,
				// selectedfields: JSONARRAY,
				// selectedfields: {JSONARRAY}
				});
		},

		/**
		 * function to add in array 
		 * @param {Array} params   All of html element id to get the values 
		 * @param {[type]} jsonType JsonType is a flag if you want to a flag example PopUp,Related etc
		 */
		Add_to_universal_popup:function(params,jsonType,selectvalues){
			var temparray={};
			var check =false;
			for (var i =0; i <= params.length - 1; i++) {
				if (App.utils.IsSelectORDropDown(params[i]).length>0)
				{
					temparray['JsonType']=jsonType;
					temparray[params[i]]=App.utils.IsSelectORDropDown(params[i]);
					if (selectvalues && selectvalues!=="")
					{
						temparray['DefaultText']=App.utils.IsSelectORDropDownGetText(selectvalues);
					}else
					{
						temparray['DefaultText']=App.utils.IsSelectORDropDownGetText(params[2]);
					}
					
					temparray[params[i]+'optionGroup']=App.utils.GetSelectParent(params[i]);
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
				var checkvalue={temparray};
				if (App.utils.checkinArray(App.popupJson,checkvalue)===false)
				{
					App.popupJson.push({temparray});
				}else
				{
					App.utils.ShowNotification("snackbar",4000,mv_arr.NotAllowedDopcicate);
				}
				
			}
			
		},

		/**
		 * function to create a popup html 
		 * @param  {Int} Idd          the id of array (need for delete )
		 * @param  {String} Firstmodulee shof the first module 
		 * @param  {string} secondmodule second module or secont value you want to show 
		 * @param  {string} last_check   
		 * @param  {string} namediv      dhe id of div you want to insert the popup
		 * @return {string}              [description]
		 */
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


		AddtoHistory:function(divName,dividrelation='',callfunction='') {
			if (App.SaveHistoryPop.length>0)
			{	
				$('#'+divName+' div').remove();
				for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {
					if(App.SaveHistoryPop[i].JSONCondition.length>0){
						if (i==(App.SaveHistoryPop.length-1))
						{
							$('#'+divName).append(App.utils.LoadHistoryHtml(i,App.SaveHistoryPop[i].JSONCondition[App.SaveHistoryPop[i].JSONCondition.length-1].FirstModuletxt,App.SaveHistoryPop[i].JSONCondition[App.SaveHistoryPop[i].JSONCondition.length-1].SecondModuletxt,true,divName,dividrelation,callfunction));
						} else
						{
							$('#'+divName).append(App.utils.LoadHistoryHtml(i,App.SaveHistoryPop[i].JSONCondition[App.SaveHistoryPop[i].JSONCondition.length-1].FirstModuletxt,App.SaveHistoryPop[i].JSONCondition[App.SaveHistoryPop[i].JSONCondition.length-1].SecondModuletxt,false,divName,dividrelation,callfunction));
						}
        				
        			}else{
        				if (i==(App.SaveHistoryPop.length-1))
						{
							$('#'+divName).append(App.utils.LoadHistoryHtml(i,App.SaveHistoryPop[i].PopupJSON[App.SaveHistoryPop[i].PopupJSON.length-1].temparray.FirstModule,App.SaveHistoryPop[i].PopupJSON[App.SaveHistoryPop[i].PopupJSON.length-1].temparray.secmodule,true,divName,dividrelation,callfunction));
						} else
						{
							$('#'+divName).append(App.utils.LoadHistoryHtml(i,App.SaveHistoryPop[i].PopupJSON[App.SaveHistoryPop[i].PopupJSON.length-1].temparray.FirstModule,App.SaveHistoryPop[i].PopupJSON[App.SaveHistoryPop[i].PopupJSON.length-1].temparray.secmodule,false,divName,dividrelation,callfunction));
						}
        				
        			}


				}
			}else
			{

			}
		},

		/**
		 * this function is to shof the modal of history
		 * @param {[Int]} IdLoad           the id of array
		 * @param {[String]} FirstModuleLoad  the name of first module
		 * @param {[String]} SecondModuleLoad  the name of second module 
		 */
		LoadHistoryHtml:function(IdLoad,FirstModuleLoad,SecondModuleLoad,avtive=false,divanameLoad,dividrelation='',callfunction='none'){
			var htmldat='<div class="Message Message"  >';
				htmldat+='<div class="Message-icon">';
				// if (avtive===false)
				// {
					htmldat+='<button style="border: none;padding: 10px;background: transparent;" data-history-show-modal="true" data-history-show-modal-id="'+IdLoad+'" data-history-show-modal-divname="'+divanameLoad+'" data-history-show-modal-divname-relation="'+dividrelation+'" data-history-show-modal-function="'+callfunction+'" ><i id="Spanid_'+IdLoad+'" class="fa fa-eye"></i></button>';
				// }
				htmldat+='</div>';
				htmldat+='<div class="Message-body">';
				htmldat+='<p>@HISTORY : '+(IdLoad+1)+'<br/></p>';
				if (FirstModuleLoad && FirstModuleLoad!=="")
				{
					htmldat+='<p><bold>'+FirstModuleLoad+'</bold>';
				}
				
				if (SecondModuleLoad && SecondModuleLoad!=="")
				{
					htmldat+='--<bold>'+SecondModuleLoad+'</bold></p>';
				}
				htmldat+='</div>';
				htmldat+='<button class="Message-close js-messageClose" data-history-close-modal="true" data-history-close-modal-id="'+IdLoad+'" data-history-close-modal-divname="'+divanameLoad+'"  data-history-show-modal-divname-relation="'+dividrelation+'" ><i class="fa fa-times"></i></button>';
				htmldat+='</div>';
				return htmldat;
		},


		/**
		 * function to show all the popup are in array to html popup
		 * @param {string} namediv  id of div you want to put
		 */
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

		/**
		 * function to show all the popup are in array to html popup
		 * @param {string} namediv  id of div you want to put
		 */
		ReturnAllDataHistory2 : function(namediv) {
			$('#' + namediv + ' div').remove();
			var check = false;
			var valuehistoryquery;
			var length_history = App.popupJson.length;
			// alert(length_history-1);
			for (var ii = 0; ii < App.popupJson.length; ii++) {
				var idd = ii// JSONForCOndition[ii].idJSON;
				var firmod = App.popupJson[ii].temparray["DefaultText"];
				var JsonType = App.popupJson[ii].temparray["JsonType"];
				// console.log(idd+firmod+secmod);
				// console.log(selectedfields);
				if (ii == (length_history - 1)) {
					check = true;

				} else {
					check = false;
				}
				var alerstdiv = App.utils.DivPopup(idd, firmod,'',JsonType);
				$('#' + namediv).append(alerstdiv);

			}
		},


		DivPopup : function(Idd,firstmodule,divid,typepopup) {
			var INSertAlerstJOIN = '<div class="alerts" id="alerts_' + Idd
					+ '">';
			INSertAlerstJOIN += '<span class="closebtns" onclick="closeAlertsAndremoveJoin('
					+ Idd + ',\'' + divid + '\');">&times;</span>';
			INSertAlerstJOIN += '<strong># '+typepopup+' !  '+(Idd+1)+'</strong> '+firstmodule;
			
			INSertAlerstJOIN += '</div';
			return INSertAlerstJOIN;
		},

		/**
		 * IsSelectedDropdown is a function which take the id and check what type of element is and get the values 
		 * @param {String} IdType Id of html element
		 */
		IsSelectORDropDown:function(IdType){
			    if (!IdType || IdType==="") {return "";}
			    var element = document.getElementById(IdType);
			    
			    if(element.tagName === 'SELECT')
			    {
			    	if ($("#" +IdType+ " option:selected").val())
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

		/**
		 * IsSelectedDropdown is a function which take the id and check what type of element is and get the text 
		 * @param {String} IdType Id of html element
		 */
		IsSelectORDropDownGetText:function(IdType){

			if (!IdType || IdType==="") {return "";}
			    
			    var element = document.getElementById(IdType);
			    
			    if(element.tagName === 'SELECT')
			    {
			    	if ($("#" +IdType+ " option:selected").val())
			    	{
			    		return $("#" +IdType+ " option:selected").text();
			    	}else
			    	{
			    		return "";	
			    	}
			    	
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'text')
			    {
			    	return $("#" +IdType).val();//+"##"+$("#" +IdType).text();
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'hidden')
			    {
			    	return $("#" +IdType).text();
			    	
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

		/**
		 * GetParent is a function which take the id get the optgroup from a dropdown 
		 * @param {String} IdType Id of html element
		 */
		GetSelectParent:function(IdType){
			    if (!IdType || IdType==="") {return "";}
			    var element = document.getElementById(IdType);
			    
			    if(element.tagName === 'SELECT')
			    {
			    	if ($("#" +IdType+ " option:selected").val())
			    	{
			    		return $("#" +IdType+ " option:selected").closest('optgroup').attr('label');
			    	}else
			    	{
			    		return "";	
			    	}
			    	
			    	
			    }
			    return "";			
		},

		/**
		 * A function to save the values to each element of html like select input button etc
		 * @param {String} IdType  the ID of element 
		 * @param {String} valuee  the value you want  to put 
		 */
		SetValueTohtmlComponents:function(IdType,valuee){
			    
			    var element = document.getElementById(IdType);
			    
			    if(element.tagName === 'SELECT')
			    {
			    	$("#" + IdType).empty();
			    	$("#" + IdType).append(valuee);			    	
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'text')
			    {
			    	return $("#" +IdType).val(valuee);//+"##"+$("#" +IdType).text();
			    	
			    }else if(element.tagName === 'INPUT' && element.type === 'hidden')
			    {
			    	return $("#" +IdType).val(valuee);//+"##"+$("#" +IdType).text();
			    	
			    }else if($('#'+IdType).is('textarea')){
			    	
			    	return $('#'+IdType).val(valuee);

			    }else if(element.type && element.type === 'checkbox')
			    {
			    	if (valuee==="false")
			    	{
			    		  $("#"+IdType).hide();
			    	}else
			    	{
			    		$("#"+IdType).show();
			    	}
			    	
			    }else if(element.type && element.type === 'button')
			    {
			    	 $('#'+IdType).val(valuee);			    	
			    }			    			
		},


		/**
		 * funstion to generate a modal 
		 * @param {string}     flag to show or hide the modal
		 * @param {String} poenclosebackdrop flag to show and open the backdrop
		 */
		ModalParseinJavascript:function(openclosemodal,poenclosebackdrop){
			var htmls = [];
			htmls.push("<div>",
			   "        <div class=\"slds\">",
			   "",
			 "            <div class=\"slds-modal\" "+openclosemodal+" aria-hidden=\"false\" role=\"dialog\" id=\"modal\">",
			 "                <div class=\"slds-modal__container\">",
			 "                    <div class=\"slds-modal__header\">",
			 "                        <button class=\"slds-button slds-button--icon-inverse slds-modal__close\" onclick=\"closeModal()\">",
			 "                            <svg aria-hidden=\"true\" class=\"slds-button__icon slds-button__icon--large\">",
			 "                                <use xlink:href=\"/assets/icons/action-sprite/svg/symbols.svg#close\"></use>",
			 "                            </svg>",
			 "                            <span class=\"slds-assistive-text\">{$MOD.close}</span>",
			 "                        </button>",
			 "                        <h2 class=\"slds-text-heading--medium\">{$MOD.mapname}</h2>",
			 "                    </div>",
			 "                    <div class=\"slds-modal__content slds-p-around--medium\">",
			 "                        <div>",
			 "                            <div class=\"slds-form-element\">",
			 "                                <label class=\"slds-form-element__label\" for=\"input-unique-id\">",
			 "                                    <abbr id=\"ErrorVAlues\" class=\"slds-required\" title=\"{$MOD.requiredstring}\">*</abbr>{$MOD.required}</label>",
			 "                                <input style=\"width: 400px; \" type=\"text\" id=\"SaveasMapTextImput\" required=\"\"",
			 "                                       class=\"slds-input\" placeholder=\"{$MOD.mapname}\">",
			 "                                <div class=\"slds-form-element__control\">",
			 "",
			 "                                </div>",
			 "                            </div>",
			 "                        </div>",
			 "                    </div>",
			 "                    <div class=\"slds-modal__footer\">",
			 "                        <button class=\"slds-button slds-button--neutral\" onclick=\"closeModalwithoutcheck();\">{$MOD.cancel}",
			 "                        </button>",
			 "                        <button onclick=\"closeModal();\" class=\"slds-button slds-button--neutral slds-button--brand\">",
			 "                            {$MOD.save}",
			 "                        </button>",
			 "                    </div>",
			 "                </div>",
			 "            </div>",
			 "            <div class=\"slds-backdrop\" "+poenclosebackdrop+" id=\"backdrop\"></div>",
			 "",
			 "            <!-- Button To Open Modal -->",
			 "            {*<button class=\"slds-button slds-button--brand\" id=\"toggleBtn\">Open Modal</button>*}",
			 "        </div>",
			 "",
			 "    </div>");
			return htmls.join("");
		},
		
		
		executeFunctionByName : function(functionName, context /* , args */) {
			var args = [].slice.call(arguments).splice(2);
			var namespaces = functionName.split(".");
			var func = namespaces.pop();
			for (var i = 0; i < namespaces.length; i++) {
				context = context[namespaces[i]];
			}
			return context[func].apply(context, args);
		},


		/**
		 * function to see if exist a data in array
		 * @param  {Array}  array  the array you want to check 
		 * @param  {Object} object the object you want to check 
		 * @return {[type]}        return boolean if find return true if not find return false
		 */
		checkinArray:function(array=[],object={}) {
			returnvalues=false;
			array.forEach(function (temparray,index) {
				//console.log('Propertis='+temparray);
				if (JSON.stringify(temparray)===JSON.stringify(object))
				{
					returnvalues=true;
				}
				
			});
			return returnvalues;
		},



		/**
		 * function to show the notification 
		 * @param {String} idnotification the id of notification 
		 * @param {Number} timetohide     the time to stay the notification after show
		 * @param {String} message        the meessage of notification
		 */
		ShowNotification:function(idnotification="snackbar",timetohide=4000,message="Put a messsage to show") {
			var x = document.getElementById(idnotification);
			x.innerHTML=message;
			x.className = "show";
			setTimeout(function(){ x.className = x.className.replace("show", ""); }, timetohide);
		}

	};


	HistoryPopup={

		saveHistory:[],

		addtoarray:function(passadata=[],datatype='') {
			var myarray=[];
			passadata.forEach(function (element) {
				myarray.push(element);
			});

			var dataobject={};
			if (datatype==='PopupJSON')
			{
				dataobject[datatype]=myarray;
				dataobject['JSONCondition']=[];
			} else
			{
				dataobject[datatype]=myarray;
				dataobject['PopupJSON']=[];
			}
			dataobject[datatype]=myarray;

			App.SaveHistoryPop.push(dataobject);;
			
		},

		getArray:function(indexi) {
			return App.SaveHistoryPop[indexi];
		},
	};

	$(App.init);
	window.App = App;

})(window, jQuery);
