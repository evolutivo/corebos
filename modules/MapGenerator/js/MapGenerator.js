/**
 * 
 */

(function(global, $) {

	var App = {
		baseUrl : null,
		initMethods : [],

		pageInitMethods : [],

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
			// data-load-Map="true"
			// $(document).ready(App.setDelayForRedirect.WaitFunction);
			$(document).on('change', 'select[data-load-Map="true"]', App.TypeOfMaps.TypeOfMap);
		},
		TypeOfMap : function() {
			var types = $('select[data-load-Map="true"]').attr('data-type-select');
			var select = $('select[data-load-Map="true"]').find(":selected").val();
			if(types=="TypeObject"){				
				var divmap = $('select[data-load-Map="true"]').attr('data-type-select');
				if(select.length>0){
					if(select=="Map"){
						$('#DivObjectID').hide('slow', function(){ $('#DivObjectID').remove(); });
						$('#MapDivID').css("display","block");
					}
					
				}else{
					alert(mv_arr.Choseobject);
				}
			}else if(types=="TypeMap"){				
				if(select.length>0){
					var nameview = $('#nameView').val();					
					if(nameview.length>=5){
						jQuery.ajax({
					        type: "POST",
					        url: "index.php?module=MapGenerator&action=MapGeneratorAjax&file=ChoseeObject",
					        data: "ObjectType="+select+"&NameView="+nameview,
					        dataType: "html",
					        async: false,
					        success: function (msg) {
					        	// $('#MapDivID').hide('slow', function(){
								// $('#MapDivID').remove(); });
					        	document.getElementById('MapDivID').innerHTML=msg;
					          return msg;
					        },
					        error: function () {
					            alert(mv_arr.ReturnFromPost);
					        }
					    });
						
					}else{
						$('option:selected',this).removeAttr('selected');
						alert(mv_arr.NameQuery);
					}
					
				}else{
					alert(mv_arr.ChoseMap);
				}
			}
			getFirstModule();
		}

	};
	
	App.SelectModule={
		init:function(){
			$(document).on('change', 'select[data-select-autolod="true"]', App.SelectModule.GetModuls);
		},
		
		GetModuls:function(event){
			if (event) event.preventDefault();
            var elem = $(this);
			var methods = elem;
            var functionstring=methods.attr('data-select-method');
			var select = methods.find(":selected").val();
			if(methods.length>0){
				var funcCall = "App.AllFunctions."+functionstring + "('"+select+"');";
				eval(funcCall)				
			}
		}
	};
	
	
	App.AllFunctions={
			
			GetFirstModuleCombo:function(value){
				getSecModule(value);
	            getFirstModuleFields(value);
			},
			GetSecondModuleCombo:function(value){
				getSecModuleFields(value);
			},
			
	};
	
	App.utils={
			
			PostDataHTML:function(response,dat){
				jQuery.ajax({
			        type: "POST",
			        url: response,
			        data: dat,
			        dataType: "html",
			        async: false,
			        success: function (msg) {
			          return msg;
			        },
			        error: function () {
			            alert(mv_arr.ReturnFromPost);
			        }
			    });
			},
	//call as executeFunctionByName("FormBuilder.PlainText", window, "testing") ;
	executeFunctionByName:function(functionName, context /* , args */){
		var args = [].slice.call(arguments).splice(2);
		  var namespaces = functionName.split(".");
		  var func = namespaces.pop();
		  for(var i = 0; i < namespaces.length; i++) {
		    context = context[namespaces[i]];
		  }
		  return context[func].apply(context, args);	
	   }
			
			
	};

	$(App.init);
	window.App = App;

})(window, jQuery);
