
	/**
	 * Работаем с картой
	 */
$(document).ready(function(){
	
	initializeMap = function(mapLocation, mapInput, mapNoAdress, mapZoom, mapPoint, marker){
		if(mapPoint) {
		mapLocation.gmap3({

			center: mapPoint,
			marker:{
				id: "main",
				latLng: mapPoint,
				options:{
				  draggable:true,
				  icon : new google.maps.MarkerImage(marker)
				},
				events:{
				  dragend: function(marker){
					$(this).gmap3({
					  getaddress:{
						latLng: marker.getPosition(),
						callback:function(results){
							var content = results && results[0] ? results && results[0].formatted_address :  mapNoAdress;
							var getMapPoint = marker.getPosition();
							point = [getMapPoint.jb, getMapPoint.kb];
							mapInput.val(content);
						}
					  }
					});
				  }
				},
			},
			map:{
				options: {
					panControl: false,
					zoomControl: true,
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL,
					},
					scaleControl: true,
					zoom: mapZoom
				},
			events: {
				click: function(map, event){
					var lat = event.latLng.lat(),
						lng = event.latLng.lng();
						$(this).gmap3({
							getaddress:{
								latLng:event.latLng,
								callback:function(results){
									var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
									var getMapPoint = event.latLng;
									point = [event.latLng.lat(), event.latLng.lng()];
									mapInput.val(content);
								}
							},
							marker:{
								id: "main",
								latLng: [event.latLng.lat(), event.latLng.lng()],
								options:{
									draggable:true,
									icon : new google.maps.MarkerImage(marker)
								},
								events:{
									dragend: function(marker){
										$(this).gmap3({
											getaddress:{
												latLng:marker.getPosition(),
												callback:function(results){
													var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
													var getMapPoint = marker.getPosition();
													point = [getMapPoint.jb, getMapPoint.kb];
													mapInput.val(content);
												}
											}
										});
									},
								}
							}
						});
					}
				}
			},
			getaddress: {
				latLng: mapPoint,
				callback:function(results){
					var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
					mapInput.val(content);
				}
			},

		});
		} else {
			mapLocation.gmap3({
				map:{
				options: {
					panControl: false,
					zoomControl: true,
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL,
					},
					scaleControl: true,
					zoom: 4
				},
			events: {
				click: function(map, event){
					var lat = event.latLng.lat(),
						lng = event.latLng.lng();
						$(this).gmap3({
							getaddress:{
								latLng:event.latLng,
								callback:function(results){
									var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
									var getMapPoint = event.latLng;
									point = [event.latLng.lat(), event.latLng.lng()];
									mapInput.val(content);
								}
							},
							marker:{
								id: "main",
								latLng: [event.latLng.lat(), event.latLng.lng()],
								options:{
									draggable:true,
									icon : new google.maps.MarkerImage(marker)
								},
								events:{
									dragend: function(marker){
										$(this).gmap3({
											getaddress:{
												latLng:marker.getPosition(),
												callback:function(results){
													var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
													var getMapPoint = marker.getPosition();
													point = [getMapPoint.jb, getMapPoint.kb];
													mapInput.val(content);
												}
											}
										});
									},
								}
							}
						});
					}
				}
			},
			getaddress: {
				latLng: mapPoint,
				callback:function(results){
					var content = results && results[0] ? results && results[0].formatted_address : mapNoAdress;
					mapInput.val(content);
				}
			},
			});
		}

		mapInputValue = function(){
			var addr = mapInput.val();
			mapLocation.gmap3({
				center: point,
				address: addr,
				marker:{
					id: "main",
					address: addr,
					options:{
						icon : new google.maps.MarkerImage(marker)
					},
				},
				getlatlng:{
					address:  addr,
					callback: function(results){
						if ( !results ) return;
						point = [results[0].geometry.location.lat(), results[0].geometry.location.lng()];
						mapLocation.gmap3('get').setCenter(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()) );
					}
				}
			});
		}

		mapInput.change(mapInputValue);

		mapInput.keypress(function(e){
			if (e.keyCode == 13){
				mapInputValue();
				return false;
			}
		});
	}


	initializSimpleeMap = function(mapLocation, mapPoint, marker){
		mapLocation.gmap3({

			center: mapPoint,
			marker:{
				id: "main",
				latLng: mapPoint,
				options:{
				  icon : new google.maps.MarkerImage(marker)
				},
			},
			map:{
				options:{
	                zoom: 14,
	                disableDefaultUI:true,
	                zoomControl:false
           		}
			},

		});
	}


});