google_status = "OK";
function initialize(){

	  var latlng = new google.maps.LatLng($('#latitude').val(),$('#longitude').val());
	  var options = {
	    zoom: 15,
	    center: latlng,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  };
	        
	  map = new google.maps.Map(document.getElementById("google_map"), options);

	  
	        
		//Определение геокодера
		 geocoder = new google.maps.Geocoder();
	  
	 
	        
	  marker = new google.maps.Marker({
	    map: map,
	    draggable: true
	  });

	  
	  google.maps.event.addListener(marker, 'drag', function() {
		    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
		      $('#latitude').val(marker.getPosition().lat());
		      $('#longitude').val(marker.getPosition().lng());

		    });
		  })				
	}

$(document).ready(function() {
//google карта
	if($("#google_map").length > 0)
		initialize();

	 // $("#google_map").slideToggle(300, function(){
      //    $("#my-location").fadeOut();
	 // });

	 $("#show_gmap").click(function(){
         $("#my-location").toggleClass('open');

         if($("#my-location").hasClass('open'))
             $("#my-location").fadeIn(300);
         else
             $("#my-location").fadeOut(300);

		 $("#google_map").slideToggle(300, function(){
			 var location = new google.maps.LatLng($("#latitude").val(), $("#longitude").val());
			 initialize();
		     marker.setPosition(location);
		     map.setCenter(location);

		 });



	 })   
	//------google карта

	$('#my-location').click(function(){
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (p) {
				var LatLng = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
				var mapOptions = {
					center: LatLng,
					zoom: 13,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("google_map"), mapOptions);

				document.getElementById("latitude").value = p.coords.latitude;

				document.getElementById("longitude").value = p.coords.longitude;

				var marker = new google.maps.Marker({
					position: LatLng,
					map: map,
					title: "Your location: Latitude: " + p.coords.latitude + " Longitude: " + p.coords.longitude
				});

				google.maps.event.addListener(marker, "click", function (e) {
					var infoWindow = new google.maps.InfoWindow();
					infoWindow.setContent(marker.title);
					infoWindow.open(map, marker);
				});
			});

		} else {
			alert('Geo Location feature is not supported in this browser.');
		}
	})
	});