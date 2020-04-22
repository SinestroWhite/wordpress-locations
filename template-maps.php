<?php	

/**
 * Template Name: Maps
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>	
<?php get_header(); ?>

<style type="text/css">	
	#map {
		width: 100%;
		height: 500px;
	}	
</style>


<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		}
	}

	?>

	<div class="row" style="margin:-60px 30px 30px 30px">
		<div class="col-lg-3 col-md-6 col-sm-12">
			<p style="text-align: center;"><b>Location Filter</b></p>
			<span>Bottom Left Corner:</span>
			<div style="display: flex;">
				<input type="text" id="lat1" onkeyup="dynamicLocations()" placeholder="Enter Lat" style="width: 50%; height: 45px;" />
				<input type="text" id="lng1" onkeyup="dynamicLocations()" placeholder="Enter Lng" style="margin-left: 10px; width: 50%; height: 45px;" />
			</div>
			<br>
			<span>Top Right Corner:</span>
			<div style="display: flex;">
				<input type="text" id="lat2" onkeyup="dynamicLocations()" placeholder="Enter Lat" style="width: 50%; height: 45px;" />
				<input type="text" id="lng2" onkeyup="dynamicLocations()" placeholder="Enter Lng" style="margin-left: 10px; width: 50%; height: 45px;" />
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<p style="text-align: center;" class="small"><b>Magnitude Filter</b></p>
			<div style="display: flex;" class="fields">
				<input type="text" id="magMin" onkeyup="dynamicLocations()" placeholder="Min" style="width: 50%; height: 45px;" />
				<input type="text" id="magMax" onkeyup="dynamicLocations()" placeholder="Max" style="margin-left: 10px; width: 50%; height: 45px;" />
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<p style="text-align: center;" class="small medium"><b>Depth Filter</b></p>
			<div style="display: flex;" class="fields">
				<input type="text" id="depthMin" onkeyup="dynamicLocations()" placeholder="Min" style="width: 50%; height: 45px;" />
				<input type="text" id="depthMax" onkeyup="dynamicLocations()" placeholder="Max" style="margin-left: 10px; width: 50%; height: 45px;" />
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-12">
			<p style="text-align: center;" class="small medium"><b>Date Filter</b></p>
			<div style="display: flex;" class="fields">
				<input type="text" id="lat" name="dates" placeholder="Date Range" style="width: 100%; height: 45px;" />
			</div>
		</div>
	</div>

</main><!-- #site-content -->

<div id="map"></div><!-- #map-canvas -->

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

<script>
	var markers = [];
	var mapObject;
	var startDate;
	var endDate;

	function getLocations(lat1, lng1, lat2, lng2, magMin, magMax, depthMin, depthMax, dateBegin, dateEnd) {
		axios.get("http://localhost/wp-json/twentytwenty/v1/locations", {
			params: {
				lat1, lng1, lat2, lng2, magMin, magMax, depthMin, depthMax, dateBegin, dateEnd,
			}
		}).then((response) => {
			markers = JSON.parse(response.data);
			mapObject.clearMarkers();
			mapObject.setMarkers(markers);
		}).catch((error) => {
			console.error(error);
		});
	}

	// Initialize and add the map
	function initMap() {
		getLocations();

	  var center = {lat: 38.906583, lng: -101.486147};
	  var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 4,
		center: center,
		styles: [
		    {
			"featureType": "landscape",
			"stylers": [
			    {
				"hue": "#FFBB00"
			    },
			    {
				"saturation": 43.400000000000006
			    },
			    {
				"lightness": 37.599999999999994
			    },
			    {
				"gamma": 1
			    }
			]
		    },
		    {
			"featureType": "road.highway",
			"stylers": [
			    {
				"hue": "#FFC200"
			    },
			    {
				"saturation": -61.8
			    },
			    {
				"lightness": 45.599999999999994
			    },
			    {
				"gamma": 1
			    }
			]
		    },
		    {
			"featureType": "road.arterial",
			"stylers": [
			    {
				"hue": "#FF0300"
			    },
			    {
				"saturation": -100
			    },
			    {
				"lightness": 51.19999999999999
			    },
			    {
				"gamma": 1
			    }
			]
		    },
		    {
			"featureType": "road.local",
			"stylers": [
			    {
				"hue": "#FF0300"
			    },
			    {
				"saturation": -100
			    },
			    {
				"lightness": 52
			    },
			    {
				"gamma": 1
			    }
			]
		    },
		    {
			"featureType": "water",
			"stylers": [
			    {
				"hue": "#0078FF"
			    },
			    {
				"saturation": -13.200000000000003
			    },
			    {
				"lightness": 2.4000000000000057
			    },
			    {
				"gamma": 1
			    }
			]
		    },
		    {
			"featureType": "poi",
			"stylers": [
			    {
				"hue": "#00FF6A"
			    },
			    {
				"saturation": -1.0989010989011234
			    },
			    {
				"lightness": 11.200000000000017
			    },
			    {
				"gamma": 1
			    }
			]
		    }
		]
  
	  });

		var googleMarkers = [];

		google.maps.Map.prototype.clearMarkers = function() {
		    for(var i=0; i < googleMarkers.length; i++){
		        googleMarkers[i].setMap(null);
		    }
		    googleMarkers = new Array();
		};	

		google.maps.Map.prototype.setMarkers = function(markers) {
			for (let i = 0; i < markers.length; i++) {
				let latLng = new google.maps.LatLng(markers[i].lat,markers[i].lng);
				let marker = new google.maps.Marker({
					position: latLng,
					map: map
				});
				let time = new Date(Number(markers[i].time));
				marker.addListener('click',() => {
					new google.maps.InfoWindow({
				    	content: `<h5 style="margin-top: 0;">${markers[i].name}</h5><p>Magnitude: ${markers[i].magnitude}<br>Depth: ${markers[i].depth}km<br>Lat: ${markers[i].lat}<br>Lng: ${markers[i].lng}<br>Date: ${time}</p>`
					}).open(map, marker);
				});
				googleMarkers.push(marker);
			}
		}

		map.setMarkers(markers);

		mapObject = map;

	}

	$('input[name="dates"]').daterangepicker({
		autoUpdateInput: false,
		locale: {
			cancelLabel: 'Clear'
		},
		minDate: '01/01/2014',
        maxDate: '02/01/2014',
	}, function(start, end) {
		// console.log(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
		startDate = new Date(start.format('YYYY-MM-DD')).getTime();
		endDate = new Date(end.format('YYYY-MM-DD')).getTime();
    });

	$('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
	  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	  dynamicLocations();
	});

	$('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
	  $(this).val('');
	  startDate = "";
	  endDate = "";
	  dynamicLocations();
	});

	function dynamicLocations() {
		let lat1 = $("#lat1").val();
		let lng1 = $("#lng1").val();
		let lat2 = $("#lat2").val();
		let lng2 = $("#lng2").val(); 

		if (lng1 != '' || lng2 != '' || lat1 != '' || lat2 != '') {
			if (isNaN(lat1)) {
				$.notify("The first value of the left corner is not a number!", "error");
				return;
			}
			if (isNaN(lat2)) {
				$.notify("The first value of the right corner is not a number!", "error");
				return;
			}
			if (isNaN(lng1)) {
				$.notify("The second value of the left corner is not a number!", "error");
				return;
			}
			if (isNaN(lng2)) {
				$.notify("The second value of the right corner is not a number!", "error");
				return;
			}
		}

		let magMin = $("#magMin").val();
		let magMax = $("#magMax").val();

		if (magMin != '' || magMax != '') {
			if (isNaN(magMin)) {
				$.notify("The minimal magnitude is not a number!", "error");
				return;
			}
			if (isNaN(magMax)) {
				$.notify("The maximal magnitude is not a number!", "error");
				return;
			}
		}

		let depthMin = $("#depthMin").val();
		let depthMax = $("#depthMax").val();

		if (depthMin != '' || depthMax != '') {
			if (isNaN(depthMin)) {
				$.notify("The minimal magnitude is not a number!", "error");
				return;
			}
			if (isNaN(depthMax)) {
				$.notify("The maximal magnitude is not a number!", "error");
				return;
			}
		}

		getLocations(lat1, lng1, lat2, lng2, magMin, magMax, depthMin, depthMax, startDate, endDate);
	}
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR7K-hUQzVzDRtxzyCDXcL8GFWK2ba8js&callback=initMap">
    </script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap-grid.min.css" />

<style type="text/css">
	.fields {
		margin-top: 40px;
	}

	@media (max-width: 991px) {
		.medium {
			margin-top: 30px;
		}
	}
	@media (max-width: 776px) {
		.small {
			margin-top: 30px;
		}
		.fields {
			margin-top: 20px;
		}
	}
</style>

<?php get_footer(); ?>
