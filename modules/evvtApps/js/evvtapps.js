/**
 * evvtApps javascript code
 * Copyright 2012  JPL TSolucio, S.L.
 */

var evvtURLp='module=evvtApps&action=evvtAppsAjax&file=vtappaction';

function evvtappsOpenWindow(appid,classname,appinfo,editInfo) {

        //ADD VISIT INTO evvtappsuser
        jQuery.ajax({
            type: "POST",
            data: "appId="+appid,
            url: "index.php?module=evvtApps&action=evvtAppsAjax&file=addVisit",
            success: function(addVisitMessage){
            }
        })
	arrAction = new Array();
	elements=0;
	if (appinfo.hasEdit) arrAction[elements++]="Edit";
	if (appinfo.hasRefresh) arrAction[elements++]="Refresh";
	//if (appinfo.hasSize) arrAction[elements++]="Minimize";
	if (appinfo.hasSize) arrAction[elements++]="Maximize";
	if (appinfo.canClose) arrAction[elements++]="Close";
	windowname='vtapp'+appid;
	if (jQuery('#'+windowname).length==0) { if(appinfo.className==undefined) {var a='storico'; var w='600'; }
else {a=appinfo.className; w=appinfo.wWidth;}

 // doesn't exist yet, we have to create it
		jQuery('#evvtCanvas').append('<div id="'+windowname+'" class="k-content" vtappkwin="vtappkwin"></div>');
		jQuery('#'+windowname).kendoWindow({
			vtappid: appid,
			vtclassname: a,
			draggable: true,  // all windows are draggable
			resizable: appinfo.hasSize,
			visible: true,  // we are opening it!
			width: w+"px",
			height: appinfo.wHeight+"px",
			title: appinfo.appTitle,
			modal: false,  // no window is fixed
			content: 'index.php?'+evvtURLp+'&vtappaction=getContent&class='+classname+'&appid='+appid+'&op=1',
			//activate:,
			deactivate: onWindowDeactivate,
			open: onWindowOpen,
			close: onWindowClose,
			resize: onWindowResize,
			//dragend:,
			actions: arrAction
		 });
		// Position it
		appwindow=jQuery('#'+windowname).data("kendoWindow");
if(appid!=134){		
appwindow.wrapper.css('top',appinfo.wTop+'px');
		appwindow.wrapper.css('left',appinfo.wLeft+'px');
	}	// Bind edit button
		if (appinfo.hasEdit) {
			jQuery('#vtapp'+appid+'edit').click(function () { onWindowEdit(appid,editInfo) });
		}
	} else {  // we put it on top
		appwindow=jQuery('#'+windowname).data("kendoWindow");
		appwindow.toFront();
	}
}

function vtAppChangeIcon(appid,icon) {
	jQuery('#evvtapp'+appid+' img').attr("src",icon);
}

function vtAppGetIcon(appid) {
	return jQuery('#evvtapp'+appid+' img').attr("src");
}

// Eliminate window div onClose so we can open it again later
function onWindowDeactivate() {
	jQuery('#vtapp'+this.options.vtappid).remove();
}

// Informe object that it has been resized
function onWindowResize() {
	var jskWindow = jQuery('#vtapp'+this.options.vtappid).data("kendoWindow").wrapper;
	var jskWidth = jskWindow.css('width');
	var jskHeight = jskWindow.css('height');
	this.refresh('index.php?'+evvtURLp+'&vtappaction=doResize&class='+this.options.vtclassname+'&appid='+this.options.vtappid+'&appwidth='+jskWidth+'&appheight='+jskHeight);
}

//Inform object that it is being opened
function onWindowOpen() {
	jQuery.ajax({
	  type: 'POST',
	  url: 'index.php',
	  data: evvtURLp+'&vtappaction=doShow&class='+this.options.vtclassname+'&appid='+this.options.vtappid
	});
}

//Inform object that it has been closed
function onWindowClose() {
	jQuery.ajax({
	  type: 'POST',
	  url: 'index.php',
	  data: evvtURLp+'&vtappaction=doHide&class='+this.options.vtclassname+'&appid='+this.options.vtappid
	});
}

//Edit window action
function onWindowEdit(appid,editInfo) {
	windowname='vtappedit'+appid;
	jQuery('#evvtCanvas').append('<div id="'+windowname+'" class="k-content" vtappid="'+appid+'"></div>');
	jQuery('#'+windowname).kendoWindow({
		vtappid: appid,
		vtclassname: editInfo.className,
		draggable: true,  // all windows are draggable
		resizable: editInfo.hasSize,
		visible: true,  // we are opening it!
		width: editInfo.wWidth,
		height: editInfo.wHeight,
		title: editInfo.title,
		modal: true,
		content: 'index.php?'+evvtURLp+'&vtappaction=getEdit&class='+editInfo.className+'&appid='+appid,
		//activate:,
		deactivate: onEditDeactivate,
		//open: onWindowOpen,
		//close: onEditClose,
		//resize: onWindowResize,
		//dragend:,
		actions: [ 'Close' ]
	 });
	jQuery('#'+windowname).data("kendoWindow").center();
}

function onEditDeactivate() {
	jQuery('#vtapp'+this.options.vtappid).data("kendoWindow").refresh();
	jQuery('#vtappedit'+this.options.vtappid).remove();
}

function droptargetTrashApp(e) {
	removeDragVisualEffect();
	var shooter = jQuery(e.draggable);
	orgappid=shooter[0].options.vtappid;
	orgappcl=shooter[0].options.vtappclass;
	//alert('dropped '+orgappcl+'('+orgappid+') on trash');
	if (confirm(vtapps_strings.ReallyDelete+"\n"+orgappcl))
	$.ajax({
		  type: 'POST',
		  url: 'index.php',
		  data: evvtURLp+'&vtappaction=doUninstallApp&class='+orgappcl+'&appid='+orgappid,
		  async: false,
		  success: function(request){
			  if (request=='OK') {
				  jQuery('#evvtapp'+orgappid).remove();
				  alert(vtapps_strings.vtAppUninstalled+"\n"+orgappcl);
			  } else
				  alert(vtapps_strings.vtAppNotUninstalled+"\n"+orgappcl);
		  },
		  error: function(request,error) {
			  alert(vtapps_strings.vtAppNotUninstalled+"\n"+orgappcl);
		  }
	});
}
function deltargetOnDragEnter(e) {
	removeDragVisualEffect();
	jQuery('#evvtapptrash').css("opacity",1);
}
function deltargetOnDragLeave(e) {
	jQuery('#evvtapptrash').css("opacity",.5);
}
function sorttargetOnDragEnter(e) {
	removeDragVisualEffect();
	var $target = jQuery(e.target);
	$target.addClass("sortDrop");
}
function sorttargetOnDragLeave(e) {
	var $target = jQuery(e.target);
	$target.removeClass("sortDrop");
}
function sorttargetOnDrop(e) {
	removeDragVisualEffect();
	var target = jQuery(e.currentTarget);
	var shooter = jQuery(e.draggable);
	//alert(shooter[0].options.vtappid);
	orgappid=shooter[0].options.vtappid;
	orgappcl=shooter[0].options.vtappclass;
	dstappid=target[0].attributes['vtappid'].nodeValue;  //0.attributes.2.nodeValue
	dstappcl=target[0].attributes['vtappclass'].nodeValue;
	// alert('move '+orgappcl+'('+orgappid+') to '+dstappcl+'('+dstappid+')');
	$.ajax({
		  type: 'POST',
		  url: 'index.php',
		  data: evvtURLp+'&vtappaction=doReorderApps&class='+orgappcl+'&appid='+orgappid+'&dstclass='+dstappcl+'&dstappid='+dstappid,
		  async: false,
		  success: function(request){
			  jQuery('#evvtapp'+orgappid).insertBefore(jQuery('#evvtapp'+dstappid));
		  }
	});
}
function removeDragVisualEffect() {
	jQuery('.evvtappbox').each(function(index) {
		jQuery(this).removeClass("sortDrop");
	});
	jQuery('.evvtappbox img').each(function(index) {
		jQuery(this).removeClass("sortDrop");
	});
	if (jQuery('#evvtapptrash').css("opacity")!=.5) jQuery('#evvtapptrash').css("opacity",.5);
}

// Leaving vtApps, we have to save this users settings
function unloadCanvas(eventObject) {
	jQuery('div[vtappkwin=vtappkwin]').map(function() {
		if (this.id!=undefined) {
		var jskWindow = jQuery('#'+this.id).data("kendoWindow");
		if (jskWindow!=undefined) {
		var url = evvtURLp+'&vtappaction=doSaveAppPosition';
		url = url + '&class='+jskWindow.options.vtclassname;
		url = url + '&appid='+jskWindow.options.vtappid;
		url = url + '&wtop='+jskWindow.wrapper.css('top');
		url = url + '&wleft='+jskWindow.wrapper.css('left');
		url = url + '&wwidth='+jskWindow.wrapper.css('width');
		url = url + '&wheight='+jskWindow.wrapper.css('height');
		$.ajax({
		  type: 'POST',
		  url: 'index.php',
		  data: url
		});
		}}
	});
}

function dumpProps(obj, parent) {
   // Go through all the properties of the passed-in object
   for (var i in obj) {
      // if a parent (2nd parameter) was passed in, then use that to
      // build the message. Message includes i (the object's property name)
      // then the object's property value on a new line
      if (parent) {
    	  var msg = parent + "." + i + "\n" + obj[i];
      } else {
    	  var msg = i + "\n" + obj[i];
      }
      // Display the message. If the user clicks "OK", then continue. If they
      // click "CANCEL" then quit this level of recursion
      if (!confirm(msg)) { return; }
      // If this property (i) is an object, then recursively process the object
      if (typeof obj[i] == "object") {
         if (parent) {
        	 dumpProps(obj[i], parent + "." + i);
         } else {
        	 dumpProps(obj[i], i);
         }
      }
   }
}
