/**
 * Create a map with these points
 * 
 * @param points - max of 25 points. Note: point with latitude && longitude == 0 don't get rendered
 * @param containingElement - inserts the map into this element
 */
function Map(points, containingElement)
{
	this.points = points;
	this.map = new GMap2(containingElement);
	
	var gLatLngBounds = new GLatLngBounds();
	for(var i=0; i<points.length; i++)
	{
		if(points[i].latitude == 0 && points[i].longitude == 0)
			continue;
		
		var gLatLng = new GLatLng(points[i].latitude, points[i].longitude);
		this.addMarker(i, gLatLng);
		gLatLngBounds.extend(gLatLng);
	}
		
	var zoom = this.map.getBoundsZoomLevel(gLatLngBounds);
	zoom = zoom <= 1 ? zoom : zoom - 1;
	this.map.setCenter(gLatLngBounds.getCenter(), zoom);
	this.map.setUIToDefault();
}

/**
 * Set onMarkerClick to add a marker listener
 */
Map.prototype.addMarker = function(id, gLatLng)
{
	var icon = new GIcon(G_DEFAULT_ICON);
	icon.image = "http://www.google.com/mapfiles/marker" + String.fromCharCode(id + 1 + 64) + ".png";
	
	var marker = new GMarker(gLatLng, { icon: icon });
	marker.id = id;
	
	this.map.addOverlay(marker);
	
	var map = this;
	GEvent.addListener(marker, "click", function(){ if(map.onMarkerClick != undefined) map.onMarkerClick(this.id); });
}

Map.prototype.gotoPoint = function(number)
{
	var point = this.points[number];
	if(point.latitude == 0 && point.longitude == 0)
		return;
	
	this.map.setCenter(new GLatLng(this.points[number].latitude, this.points[number].longitude));
}