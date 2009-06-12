var map = null;
var geocoder = null;
var editingNow = false;
var polys = [];
var markers = [];

function createMarkerAt() {
    var marker = new GMarker(map.getCenter(), {
        draggable: true
    });
    GEvent.addListener(marker, 'dragend',
    function() {
        
    });
    map.addOverlay(marker);
    markers.push(marker);
}

function createPolyAt(latlng) {
    var poly = new GPolyline([latlng]);
    map.addOverlay(poly);
    poly.enableDrawing();
    editingNow = true;
    GEvent.addListener(poly, "mouseover",
    function() {
        poly.enableEditing();
    });
    GEvent.addListener(poly, "mouseout",
    function() {
        poly.disableEditing();
    });
    GEvent.addListener(poly, "lineupdated",
    function() {

});
    GEvent.addListener(poly, "endline",
    function() {
        editingNow = false;
    });
    polys.push(poly);
}

function clearMarkers() {
    for (var i = 0; i < markers.length; i++) {
        map.removeOverlay(markers[i]);
    }
    markers = [];

}

function clearPolys() {
    for (var i = 0; i < polys.length; i++) {
        map.removeOverlay(polys[i]);
    }
    polys = [];
    updateImage();
}

function updateImage() {
    var baseUrl = "http://maps.google.com/staticmap?";

    var params = [];
    params.push("center=" + map.getCenter().lat().toFixed(6) + "," + map.getCenter().lng().toFixed(6));

    var markerSize = '';
    var markerColor = 'red';
    var markerLetter = '';
    var markerParams = markerSize + markerColor + markerLetter;
    var markersArray = [];
    for (var i = 0; i < markers.length; i++) {
        markersArray.push(markers[i].getLatLng().lat().toFixed(6) + "," + markers[i].getLatLng().lng().toFixed(6) + "," + markerParams);
    }

    //	 markersArray.push(marker.getLatLng().lat().toFixed(6) + "," + marker.getLatLng().lng().toFixed(6) + "," + markerParams);
    if (markersArray.length) params.push("markers=" + markersArray.join("|"));

    var polyColor = '0000FF';
    var polyAlpha = '80';
    var polyWeight = '5';
    var polyParams = "rgba:0x" + polyColor + polyAlpha + ",weight:" + polyWeight + "|";
    for (var i = 0; i < polys.length; i++) {
        var poly = polys[i];
        var polyLatLngs = [];
        for (var j = 0; j < poly.getVertexCount(); j++) {
            polyLatLngs.push(poly.getVertex(j).lat().toFixed(5) + "," + poly.getVertex(j).lng().toFixed(5));
        }
        params.push("path=" + polyParams + polyLatLngs.join("|"));
    }

    params.push("zoom=" + map.getZoom());
    params.push("size=" + 480 + "x" + 300);

    var ret = baseUrl + params.join("&") + "&key="+InsightsSettings.insights_maps_api;

    /*  var img = document.createElement("img");
   
   img.src = baseUrl + params.join("&") + "&key=ABQIAAAAV_qfpfUu8uDt3u2UBmjZMBS7bHKUqFE5rBpovpXcTDxFZSYncxRILGNAmXOZzcdCktfOB2SX1-FqVA";
   document.getElementById("insights-image").innerHTML = "";
   document.getElementById("insights-image").appendChild(img);

   document.getElementById("insights-results").innerHTML = baseUrl + params.join("&") + "&key=YOUR_KEY_HERE";*/
    return ret;
}

function showAddress() {
    var searchField = document.getElementById("insights-search");

    var address = searchField.value;
    if (geocoder) {
        geocoder.getLatLng(address,
        function(point) {
            if (!point) {
                alert(address + " not found");
            } else {
                map.setCenter(point);
            }
        });
    }
}
function init_map() {
    if (GBrowserIsCompatible() && document.getElementById("insights-map")) {
        map = new GMap2(document.getElementById("insights-map"));
        var mapTypeControl = new GMapTypeControl();

        map.addMapType(G_PHYSICAL_MAP);
        map.addControl(new GLargeMapControl());
        //	map.addControl(mapTypeControl);
        map.setMapType(G_NORMAL_MAP);

        geocoder = new GClientGeocoder();
        geocoder.getLatLng(document.getElementById("insights-search").value,
        function(point) {
            if (!point) {
                alert(address + " not found");
            } else {
                map.setCenter(point, 15);
            }
        });
        //	marker = new GMarker(center, {draggable: true});

        GEvent.addListener(map, "click",
        function(overlay, latlng) {
            if (latlng && !editingNow) {
                createPolyAt(latlng);
            }

        });
        

        GEvent.addListener(map, "zoomend",
        function(oldLevel, newLevel) {
            
        });

        

    }
}

if (document.all && window.attachEvent) { // IE-Win
    window.attachEvent("onunload", GUnload);
} else if (window.addEventListener) { // Others
    window.addEventListener("unload", GUnload, false);
}