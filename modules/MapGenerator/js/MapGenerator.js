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
			//$(document).ready(App.setDelayForRedirect.WaitFunction);
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
					if(select=="Mapping"){
//						$('#MapDivID').hide('slow', function(){ $('#MapDivID').remove(); });
						$('#MapDivID').include('modules/MapGenerator/createView.tpl');
//						$('#MapDivID').appendTo('{include file="modules/MapGenerator/"'+select+'".tpl"}');
					}
					
				}else{
					alert(mv_arr.ChoseMap);
				}
			}
			
		}

	};

	$(App.init);
	window.App = App;

})(window, jQuery);
