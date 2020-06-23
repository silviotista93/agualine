function poolservices_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof POOLSERVICES_STORAGE['googlemap_init_obj'] == 'undefined') poolservices_googlemap_init_styles();
	POOLSERVICES_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		POOLSERVICES_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: POOLSERVICES_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		poolservices_googlemap_create(id);

	} catch (e) {
		
		dcl(POOLSERVICES_STORAGE['strings']['googlemap_not_avail']);

	};
}

function poolservices_googlemap_create(id) {
	"use strict";

	// Create map
	POOLSERVICES_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(POOLSERVICES_STORAGE['googlemap_init_obj'][id].dom, POOLSERVICES_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers)
		POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	poolservices_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].map)
			POOLSERVICES_STORAGE['googlemap_init_obj'][id].map.setCenter(POOLSERVICES_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function poolservices_googlemap_add_markers(id) {
	"use strict";
	for (var i in POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (POOLSERVICES_STORAGE['googlemap_init_obj'].geocoder == '') POOLSERVICES_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			POOLSERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			POOLSERVICES_STORAGE['googlemap_init_obj'].geocoder.geocode({address: POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = POOLSERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					POOLSERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						poolservices_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(POOLSERVICES_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: POOLSERVICES_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].title;
			POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				POOLSERVICES_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				POOLSERVICES_STORAGE['googlemap_init_obj'][id].map.setCenter(POOLSERVICES_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								POOLSERVICES_STORAGE['googlemap_init_obj'][id].map,
								POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			POOLSERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function poolservices_googlemap_refresh() {
	"use strict";
	for (id in POOLSERVICES_STORAGE['googlemap_init_obj']) {
		poolservices_googlemap_create(id);
	}
}

function poolservices_googlemap_init_styles() {
	// Init Google map
	POOLSERVICES_STORAGE['googlemap_init_obj'] = {};
	POOLSERVICES_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.poolservices_theme_googlemap_styles!==undefined)
		POOLSERVICES_STORAGE['googlemap_styles'] = poolservices_theme_googlemap_styles(POOLSERVICES_STORAGE['googlemap_styles']);
}