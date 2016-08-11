var greenIcon = L.icon({
    iconUrl: 'modules/OpenStreetMap/img/me-dot.png',
//   
});
L.Map = L.Map.extend({
    openPopup: function(popup) {
//                this.closePopup();  // just comment this
        this._popup = popup;
        return this.addLayer(popup).fire('popupopen', {
            popup: this._popup
        });
    }
});
var map = null;
var draw_circle = null;
var orginputshow = jQuery("#orginputshow").val();
var s = new Object(), t = new Object();
var from;
var  directionsDisplay;
initMap();

function onLocationFound(e) {
    var lat = (e.latlng.lat);
    var lng = (e.latlng.lng);
    var newLatLng = new L.LatLng(lat, lng);
    from = newLatLng;
    //Revers Geocoding 
     getAddress(newLatLng);
     baseCity = jQuery("#defaultCity").val();
     baseAddress = jQuery("#defaultCountry").val();
     defaultRadius = jQuery("#defaultRadius").val();
     baseDesc = '';
     baseDesc += '<span style="font-weight: bold; font-size: 110%">'+baseName+'</span><br/><br/>'+baseAddress+'<br/>'+baseCity+'<br/><br>';
     baseDesc += '<b>'+mod_strings.Around+'</b>&nbsp;<input id="aroundfilter" size="5" name="aroundfilter" value="'+defaultRadius+'"';
     baseDesc += 'onChange="DrawCircle(latitude,longitude,this,module);" style="width:35px">&nbsp;kms<br/>';
     baseDesc += '<a href="index.php?module=evvtMap&file=update&action=evvtMapAjax&id='+jQuery("#user").val();
     baseDesc += '&show='+module+'">'+mod_strings.Reload + '</a> / <a href="#" onclick="DrawCircle(latitude,longitude,$(\'aroundfilter\'),module);">';
     baseDesc += mod_strings.DrawCircle+'</a> / \n\<a href="#" onclick="removeCircle();return false;">'+ mod_strings.ClearCircle+'</a><br><br> \n\ ';
     addMarker(newLatLng,baseName,baseDesc,greenIcon);
//     markersArray.push(marker); 
}

function evvtMap_CenterOn(lat,lng){
	// map: an instance of GMap3
	// latlng: an array of instances of GLatLng
	latlng =  new L.LatLng(lat,lng);
        var latlngbounds = L.latLngBounds(latlng);
        latlngbounds.extend(latlng);
	map.setView(latlngbounds.getCenter());
	map.fitBounds(latlngbounds);
	map.setZoom(16);
}

function initMap() {
    var options = {center : new L.LatLng(39.0,+0), zoom : 7 };
    
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    osm = new L.TileLayer(osmUrl, {maxZoom: 18,minZoom: 1 });
    
    var mapLayer = new L.TileLayer(osmUrl);
    
    map = new L.Map('map', options).addLayer(mapLayer);
    var geocoder =  new L.Control.GeoSearch({
        provider: new L.GeoSearch.Provider.OpenStreetMap(),
        showMarker: true,
        retainZoomLevel: true,
    }).addTo(map);
    
    //Center map 
 var locationaddr = jQuery("#centerAddress").val();
//Set icon of marker in current location 
    if(!map.locate({setView: true, maxZoom: 8})){
    geocoder.geosearch(locationaddr);
    baseName = jQuery("#userOrgName").val();
    baseCity = jQuery("#baseCity").val();
    baseAddress = jQuery("#baseAddress").val();
    map.on('geosearch_showlocation',function(results){    
    marker = results.Marker;
    marker.setIcon(greenIcon);
    marker.setTitle(baseName)
    });
    from = locationaddr;
    }
    else{
    map.on('locationfound', onLocationFound);
    baseName = jQuery("#fullUserName").val();
    }
    module = jQuery("#module").val();
    setupResultMarkers(module);
}

function showFilterRec(){

}

function updateFilterCombo(elem) {
	var moduleselected = encodeURIComponent(elem.options[elem.selectedIndex].value);
	jQuery("#show").val(moduleselected);
        selviewid = jQuery("#selview").val();
        jQuery.ajax({
            type: "POST",
            url: 'index.php?module=OpenStreetMap&action=OpenStreetMapAjax&file=getFilter',
            data: {
                    modulefilter: moduleselected,
                    selviewid:selviewid
            }
            }).done(function( filters ) {
                  document.getElementById("filterContainer").innerHTML= filters + " " +  document.getElementById("userspantpl").innerHTML; 
            });
	 
}

function setupResultMarkers() {
module = jQuery("#orginputshow").val();
results =  JSON.parse(jQuery("#entitymarkers").val());
iconstoviews = JSON.parse(jQuery("#iconcustomview").val());
var exists,pos;
for( var i = 0; i < results.length; i++) {
    var result = results[i];
        var cvid = result["cvid"];
        if (cvid==0 && (orginputshow=='Events' || orginputshow=='Radius')) {
                switch (result['etype']) {
                  case 'Accounts':
                          cvid = 1;
                          break;
                  case 'Contacts':
                          cvid = 2;
                          break;
                  case 'Leads':
                  default:
                          cvid = 3;
                          break;
                }
                }
    //Get marker position
    var lat = (result["pos"][0]);
    var lng = (result["pos"][1]);  
    //Get marker Icon
    var filterIcon = L.icon({
        iconUrl:'modules/evvtMap/img/'+iconstoviews[cvid]+'.png',
    });
    
    exists = search(results,result["name"],result["pos"][0], result["pos"][1]);
    if(exists==true) {
              newLatLng = new L.LatLng(parseFloat(lat+(Math.random() *10) / 1500),parseFloat(lng+(Math.random() *10) / 1300));  
            cvid = 0;
    } else {
             newLatLng = new L.LatLng(lat,lng);
    }
    
    desc = getDescription(result['id'],newLatLng,result["name"],result["city"],result["phone"],result["cname"],result["extra"],result["cvid"],module);
    addMarker(newLatLng,result["name"],desc,filterIcon);
    
}
}

function addMarker(pos, name, desc, icon){
    marker = new L.Marker (pos,{title:name}).addTo(map);
    marker.setIcon(icon);
    marker.bindPopup(desc);
 //   markersArray.push(marker);    
}

function getDescription(id, pos, name, city, phone, cname, extra,viewid,module)
{
    defaultRadius = jQuery("#defaultRadius").val();
	var html = "";
	html += "<br/><b><a href='index.php?module="+module+"&action=DetailView&record="+id+"' target='_blank'>"+name+"</a></b>";	
	if(extra)
	{
		html += "<br/><br/>"+extra;
	}
        html += "<br/><br/>Phone: <a href='tel:"+phone+"'>"+phone+"</a>";
        html += "<br/><br/>Contacts:<br/>"+cname;
        html += "<br/><br/>";
        html += "<b>"+mod_strings.Around+"</b>&nbsp;<input id=\"aroundfilter\" size=\"5\" name=\"aroundfilter\" value=\""+defaultRadius+"\" onChange='DrawCircle(\""+pos.lat+"\",\""+pos.lng+"\",this,module);' style='width:35px;'>&nbsp;kms\n";
       	html += "<br/><a onClick='loadDirection(\""+pos.lat+"\",\""+pos.lng+"\",\""+name+"\",\""+city+"\")' href='javascript:void(0)'>"+mod_strings.Direction+"</a>";
       	html += " / <a href='index.php?module=evvtMap&file=update&action=evvtMapAjax&id="+id+"&viewid="+viewid+"&show="+module+"'>"+mod_strings.Reload+"</a>";
       	html += " / <a href='#' onclick='DrawCircle(\""+pos.lat+"\",\""+pos.lng+"\",$(\"aroundfilter\"),module);'>"+mod_strings.DrawCircle+"</a>";
       	html += " / <a href='#' onclick='removeCircle();return false;'>"+mod_strings.ClearCircle+"</a><br/><br/>";

       	html += "Direction between marks<br/><a onClick='addDirection(\""+pos.lat+"\",\""+pos.lng+"\",\""+extra+"\",true)' href='javascript:void(0)'>"+mod_strings.Start+"</a> / <a onClick='addDirection(\""+pos.lat+"\",\""+pos.lng+"\",\""+extra+"\",false)' href='javascript:void(0)'>"+mod_strings.End+"</a>";
	return html;
}

// search if exists another entity in the same position with different name
function search(A,name,x,y) {
	for( var i = 0; i < results.length; i++) {
		var result = A[i];  
		if (result["pos"][0]==x && result["pos"][1]==y && result["name"]!=name) return true;
	}	
	                      false;
}

function getAddress(pos){
    jQuery.ajax({
    type: "POST",
    async: false,
    url: 'index.php?module=OpenStreetMap&action=OpenStreetMapAjax&file=getAddress',
    data: {
            lat: pos.lat,
            lng:pos.lng,
    }
    }).done(function(addr) {
        res = addr.split(";")
        jQuery("#defaultCity").val(res[0]);
        jQuery("#defaultCountry").val(res[1]);
    });
}

//Directions Function
function restore()
{
	var ddesc = document.getElementById("desc");
	ddesc.innerHTML = "";
        directionsDisplay.spliceWaypoints(0, 2); 
	var r = document.getElementById("route");
	r.innerHTML = "";
}


function addDirection(lat,lng, addr, start) {
    if (start) {
            s.lat = lat;
            s.lng = lng;
            document.getElementById("from").innerHTML = addr;
    }
    else{
            t.lat = lat;
            t.lng = lng;
            document.getElementById("to").innerHTML = addr;
    }
 }
 
function showDirections(){
    var start =  new L.LatLng(s.lat,s.lng);
    var end =  new L.LatLng(t.lat,t.lng);
    directionsDisplay = L.Routing.control({
      waypoints: [
       start,
       end
      ],
     routeWhileDragging: true
    });
    directionsDisplay._map = map;
    var controlDiv = directionsDisplay.onAdd(map);
    document.getElementById("route1").appendChild(controlDiv);
}

function loadDirection(lat,lng,name,city)
{
	to = new L.LatLng(lat, lng);
        directionsDisplay = L.Routing.control({
          waypoints: [
           from,
           to
          ],
         routeWhileDragging: true
        });
        directionsDisplay._map = map;
        var controlDiv = directionsDisplay.onAdd(map);
        document.getElementById("route").appendChild(controlDiv);
        jQuery('#rdotabs').tabs('option', 'active', 1 );
}

function newClient(e) {
    pos = e.latlng;
    jQuery.ajax({
    type: "POST",
    async: false,
    url: 'index.php?module=OpenStreetMap&action=OpenStreetMapAjax&file=reverseGeocoding',
    data: {
            lat: pos.lat,
            lng: pos.lng,
    }
    }).done(function(results) {
        url = results;
        window.open(url);
    });
 }

function getDescriptionofList(pointx,pointy,rad,module)
{
        var url = "module=OpenStreetMap&action=OpenStreetMapAjax&file=PointsInsideCircle&pointx="+pointx+"&pointy="+pointy+"&radius="+rad+"&modulename="+module;
        var latlng =  new L.LatLng(pointx,pointy);
        jQuery.ajax({
                     url:'index.php?',
                     type: 'POST',
                     data:url,
                     success: function(response)
                     {
                         var content=response;    // create popup contents
                         var infowindow = L.popup()
                         .setLatLng(latlng)
                         .setContent(content)
                         .openOn(map);
                     }

             }
                        );      
}

function DrawCircle(pointx,pointy,elem,module) {
    var rad=elem.value;
    var p =  new L.LatLng(pointx,pointy);
    rad *= 1600; // convert to meters if in miles
     if (draw_circle != undefined) 
     map.removeLayer(draw_circle);
    draw_circle = L.circle([pointx, pointy], rad, {
        color: "#FF0000",
        opacity: 0.8,
        weight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35
     }).addTo(map);
     getDescriptionofList(pointx,pointy,rad/1600,module);
}

function removeCircle() {
     if (draw_circle != undefined) 
     map.removeLayer(draw_circle);
}
