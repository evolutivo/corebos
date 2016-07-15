function initialize(center,zoom,maptype) {
	directionsDisplay = new google.maps.DirectionsRenderer();
	// Create and Center a Map
    	var myOptions = {
      		zoom: zoom,
      		center: new google.maps.LatLng(center.lat(),center.lng()),  // 40, -4),
      		mapTypeId: maptype=='politico' ? google.maps.MapTypeId.ROADMAP : google.maps.MapTypeId.SATELLITE,
		streetViewControl: true
    	};
    	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        directionsDisplay.setMap(map);
}

  var countries = {
    'au': {
      center: new google.maps.LatLng(-25.3,133.8),
      zoom: 4
    },
    'br': {
      center: new google.maps.LatLng(-14.2,-51.9),
      zoom: 3
    },
    'ca': {
      center: new google.maps.LatLng(62,-110.0),
      zoom: 3
    },
    'fr': {
      center: new google.maps.LatLng(46.2,2.2),
      zoom: 5
    },      
    'de': {
      center: new google.maps.LatLng(51.2,10.4),
      zoom: 5
    },
    'mx': {
      center: new google.maps.LatLng(23.6,-102.5),
      zoom: 4
    },
    'nz': {
      center: new google.maps.LatLng(-40.9,174.9),
      zoom: 5
    },
    'it': {
      center: new google.maps.LatLng(41.9,12.6),
      zoom: 5
    },
    'za': {
      center: new google.maps.LatLng(-30.6,22.9),
      zoom: 5
    },
    'es': {
      center: new google.maps.LatLng(40.5,-3.7),
      zoom: 5
    },
    'pt':{
      center: new google.maps.LatLng(39.4,-8.2),
      zoom: 6
    },
    'us': {
      center: new google.maps.LatLng(37.1,-95.7),
      zoom: 3
    },
    'uk': {
      center: new google.maps.LatLng(54.8, -4.6),
      zoom: 5
    }
  };

  function place_changed() {
    var place = autocomplete.getPlace();
    map.panTo(place.geometry.location);
    map.setZoom(15);
    search();
  }
  
  function onPlaceChanged() {
        var place = autocomplete.getPlace();
        if (place.geometry) {
          map.panTo(place.geometry.location);
          map.setZoom(15);
        } else {
          document.getElementById('autocomplete').placeholder = 'Enter a city';
        }
   }

  function setAutocompleteCountry() {
    var country = document.getElementById('country').value;
    if (country == 'all') {
      autocomplete.setComponentRestrictions([]);
      map.setCenter(new google.maps.LatLng(15,0));
      map.setZoom(2);
    } else {
      autocomplete.setComponentRestrictions({ 'country': country });
      map.setCenter(countries[country].center);
      map.setZoom(countries[country].zoom);
    }
  }
function addMarker(pos, name, desc, icon) {
	var infowindow = new google.maps.InfoWindow({
		content: desc
	});

	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: icon,
		title: name
	});

	google.maps.event.addListener(marker, 'click', function() {
		console.log(marker);
		infowindow.open(map,marker);
	});

	markersArray.push(marker);
}

function getDescription(id, pos, name, city, phone, cname, extra,viewid,module)
{
	var html = "";
	html += "<br/><b><a href='index.php?module="+module+"&action=DetailView&record="+id+"' target='_blank'>"+name+"</a></b>";	
	if(extra)
	{
		html += "<br/><br/>"+extra;
	}
        html += "<br/><br/>Phone: <a href='tel:"+phone+"'>"+phone+"</a>";
        html += "<br/><br/>Contacts:<br/>"+cname;
        html += "<br/>";
        html += "<b>"+around_lbl+" </b>&nbsp;<input id=\"aroundfilter\" size=\"5\" name=\"aroundfilter\" value=\""+defaultRadius+"\" onChange='DrawCircle(\""+pos.lat()+"\",\""+pos.lng()+"\",this,module);' style='width:35px;'>&nbsp;kms\n";
       	html += "<br/><a onClick='loadDirection(\""+pos.lat()+","+pos.lng()+"\",\""+name+"\",\""+city+"\")' href='javascript:void(0)'>"+direction_lbl+"</a>";
       	html += " / <a href='index.php?module=evvtMap&file=update&action=evvtMapAjax&id="+id+"&viewid="+viewid+"&show="+module+"'>"+reload_lbl+"</a>";
       	html += " / <a href='#' onclick='DrawCircle(\""+pos.lat()+"\",\""+pos.lng()+"\",$(\"aroundfilter\"),module);'>"+drawcircle_lbl+"</a>";
       	html += " / <a href='#' onclick='removeCircle();return false;'>"+clearcircle_lbl+"</a><br/><br/>";

       	html += "Direction between marks<br/><a onClick='addDirection(\""+pos.lat()+","+pos.lng()+"\",\""+extra+"\",true)' href='javascript:void(0)'>"+start_lbl+"</a> / <a onClick='addDirection(\""+pos.lat()+","+pos.lng()+"\",\""+extra+"\",false)' href='javascript:void(0)'>"+end_lbl+"</a>";
	return html;
}

function getDescriptionofList(pointx,pointy,rad,module)
{
        var url = "module=evvtMap&action=evvtMapAjax&file=PointsInsideCircle&pointx="+pointx+"&pointy="+pointy+"&radius="+rad+"&modulename="+module;
        new Ajax.Request(
            'index.php',
             {
                     queue: {position: 'end', scope: 'command'},
                     method: 'post',
                     postBody:url,
                     onComplete: function(response)
                     {
                         var content=response.responseText;                         
                         var infowindow = new google.maps.InfoWindow({
                         content: content
                         });
                        infowindow.setPosition(draw_circle.getCenter());
                        infowindow.open(map);
                     }

             }
                        );      
}

// Removes the overlays from the map, but keeps them in the array
function clearOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
  }
}

// Shows any overlays currently in the array
function showOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(map);
    }
  }
}

// Deletes all markers in the array by removing references to them
function deleteOverlays() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
    markersArray.length = 0;
  }
}

function setupResultMarkers(results,module) {
	markersArray = [];
	var exists,pos;
	for (var j in results) {
		var result = results[j];
		var cvid=result["cvid"];
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
		exists=search(results,result["name"],result["pos"][0], result["pos"][1]);
		if(exists==true) {
			pos = new google.maps.LatLng(parseFloat(result["pos"][0]+(Math.random() *10) / 1500), parseFloat(result["pos"][1]+(Math.random() *10) / 1300));
			cvid = 0;
		} else {
			pos = new google.maps.LatLng(result["pos"][0],result["pos"][1]);
		}
		addMarker(pos,result["name"],getDescription(j,pos,result["name"],result["city"],result["phone"],result["cname"],result["extra"],result["cvid"],module),'modules/evvtMap/img/'+iconstoviews[cvid]+'.png');
	}
	addMarker(basePos,baseName,baseDesc,"modules/evvtMap/img/me-dot.png");
}
// search if exists another entity in the same position with different name
function search(A,name,x,y) {
	for (var j in A) {
		var result = A[j];  
		if (result["pos"][0]==x && result["pos"][1]==y && result["name"]!=name) return true;
	}	
	return false;
}

function loadDirection(location,name,city)
{
	directionsDisplay.setPanel(document.getElementById('route'));
	to = location;
	var request = {
    		origin:from, 
    		destination:to,
    		travelMode: google.maps.TravelMode.DRIVING
  	};
  	directionsService.route(request, function(response, status) {
    		if (status == google.maps.DirectionsStatus.OK) {
      			directionsDisplay.setDirections(response);
  		}
	});	
	var ddesc = document.getElementById("desc");
	ddesc.innerHTML = from_lbl+": <span style='font-weight: bold'>"+baseName+" - "+baseCity+"</span> <span style='color:grey; font-size: smaller'>("+head_lbl+")</span><br/>"+to_lbl+": &nbsp;<span style='font-weight: bold'>"+name+" - "+city+"</span>";
	jQuery('#rdotabs').tabs('option', 'active', 1 );
}

var s, t;
function addDirection(coor, addr, start) {
		if (start) {
			s = coor;
			document.getElementById("from").innerHTML = addr;
		}
		else{
			t=coor;
			document.getElementById("to").innerHTML = addr;
		}
      }

function showDirections(){
	var directionsService = new google.maps.DirectionsService;
	var directionsService = new google.maps.DirectionsService;
	directionBetweenMarks(directionsService, directionsDisplay);
}

function directionBetweenMarks(directionsService, directionsDisplay) {
	directionsDisplay.setPanel(document.getElementById('route1'));
        var start = s;
        var end = t;
        directionsService.route({
          origin: start,
          destination: end,
          travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
          if (status === google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }

function restore()
{
	var ddesc = document.getElementById("desc");
	ddesc.innerHTML = "";
	directionsDisplay.setMap(null);
	var r = document.getElementById("route");
	r.innerHTML = "";
}

function evvtMap_CenterOn(lat,lng){
	// map: an instance of GMap3
	// latlng: an array of instances of GLatLng
	latlng=new google.maps.LatLng(lat,lng);
	var latlngbounds = new google.maps.LatLngBounds();
	latlngbounds.extend(latlng);
	map.setCenter(latlngbounds.getCenter());
	map.fitBounds(latlngbounds);
	map.setZoom(16);
}
// Limit scope pollution from any deprecated API
(function() {

    var matched, browser;

// Use of jQuery.browser is frowned upon.
// More details: http://api.jquery.com/jQuery.browser
// jQuery.uaMatch maintained for back-compat
    jQuery.uaMatch = function( ua ) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
            /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
            /(msie) ([\w.]+)/.exec( ua ) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
            [];

        return {
            browser: match[ 1 ] || "",
            version: match[ 2 ] || "0"
        };
    };

    matched = jQuery.uaMatch( navigator.userAgent );
    browser = {};

    if ( matched.browser ) {
        browser[ matched.browser ] = true;
        browser.version = matched.version;
    }

// Chrome is Webkit, but Webkit is also Safari.
    if ( browser.chrome ) {
        browser.webkit = true;
    } else if ( browser.webkit ) {
        browser.safari = true;
    }

    jQuery.browser = browser;

    jQuery.sub = function() {
        function jQuerySub( selector, context ) {
            return new jQuerySub.fn.init( selector, context );
        }
        jQuery.extend( true, jQuerySub, this );
        jQuerySub.superclass = this;
        jQuerySub.fn = jQuerySub.prototype = this();
        jQuerySub.fn.constructor = jQuerySub;
        jQuerySub.sub = this.sub;
        jQuerySub.fn.init = function init( selector, context ) {
            if ( context && context instanceof jQuery && !(context instanceof jQuerySub) ) {
                context = jQuerySub( context );
            }

            return jQuery.fn.init.call( this, selector, context, rootjQuerySub );
        };
        jQuerySub.fn.init.prototype = jQuerySub.fn;
        var rootjQuerySub = jQuerySub(document);
        return jQuerySub;
    };

})();
