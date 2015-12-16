/*!
	Zoom 1.7.14
	license: MIT
	http://www.jacklmoore.com/zoom
*/
(function ($) {
	var defaults = {
		url: false,
		target: "#forzoom",
		target_right: "#forzoom_right",
		duration: 120,
		on: 'mouseover', // other options: grab, click, toggle
		touch: true, // enables a touch fallback
		onZoomIn: false,
		onZoomOut: false,
		magnify: 1,
		zoomviewposition:'right',
	};

	// Core Zoom Logic, independent of event listeners.
	$.zoom = function(target, source, img, magnify, target_right) {
		var targetHeight,
			targetWidth,
			sourceHeight,
			sourceWidth,
			xRatio,
			yRatio,
			offset,
			$target = $(target),
			position = $target.css('position'),
			$target_right = $(target_right),
			position_right = $target_right.css('position'),
			$source = $(source);

		// The parent element needs positioning so that the zoomed element can be correctly positioned within.
		$target.css('position', /(absolute|fixed)/.test(position) ? position : 'relative');
		$target.css('overflow', 'hidden');
		$target_right.css('position', /(absolute|fixed)/.test(position_right) ? position_right : 'relative');
		$target_right.css('overflow', 'hidden');

		img.style.width = img.style.height = '';

		if($(img).data('position') == 'right'){
			$(img)
			.addClass('zoomImg')
			.css({
				position: 'absolute',
				top: 0,
				left: 0,
				opacity: 0,
				width: img.width * magnify,
				height: img.height * magnify,
				border: 'none',
				maxWidth: 'none',
				maxHeight: 'none'
			})
			.appendTo(target_right);
		} else {
			$(img)
			.addClass('zoomImg')
			.css({
				position: 'absolute',
				top: 0,
				left: 0,
				opacity: 0,
				width: img.width * magnify,
				height: img.height * magnify,
				border: 'none',
				maxWidth: 'none',
				maxHeight: 'none'
			})
			.appendTo(target);
		}


		return {
			init: function() {
				targetWidth = $target.outerWidth();
				targetHeight = $target.outerHeight();

		        
				if (source === $target[0]) {
					sourceWidth = targetWidth;
					sourceHeight = targetHeight;
				} else {
					sourceWidth = $source.outerWidth();
					sourceHeight = $source.outerHeight();
				}

				xRatio = (img.width - targetWidth) / sourceWidth;
				yRatio = (img.height - targetHeight) / sourceHeight;

				offset = $source.offset();
			},
			move: function (e) {
				var left = (e.pageX - offset.left),
					top = (e.pageY - offset.top);

			/*	var targ = e.currentTarget;
				if($(targ).hasClass('odd')){ // right side
					$target_right.hide();
					$target.show();
				} else {
					$target_right.show();
					$target.hide();
				}
					*/
				top = Math.max(Math.min(top, sourceHeight), 0);
				left = Math.max(Math.min(left, sourceWidth), 0);
				
				img.style.left = (left * -xRatio) + 'px';
				img.style.top = (top * -yRatio) + 'px';
			},

		};
	};

	$.fn.zoom = function (options) {
		return this.each(function () {
			var
			settings = $.extend({}, defaults, options || {}),
			//target will display the zoomed image
			target = settings.target || this,
			target_right = settings.target_right || this,
			//source will provide zoom location info (thumbnail)
			source = this,
			$source = $(source),
			$target = $(target),
			$target_right = $(target_right),
			img = document.createElement('img'),
			$img = $(img),
			mousemove = 'mousemove.zoom',
			clicked = false,
			touched = false,
			$urlElement;

			// If a url wasn't specified, look for an image element.
			if (!settings.url) {
				// $urlElement = $source.find('img');
				var bg = $(this).css('background-image');
				bg = bg.replace('url(','');
				bg = bg.replace(')','');
				bg = bg.replace('"','');
				bg = bg.replace('"','');
					settings.url = bg;

				if($(this).hasClass('odd')){
					settings.zoomviewposition = 'left';
				} else {
					settings.zoomviewposition = 'right';
				}
					
				// if ($urlElement[0]) {
					// settings.url = $urlElement.data('src') || $urlElement.attr('src');
				// }
				if (!settings.url) {
					return;
				}
			}

			(function(){
				var position = $target.css('position');
				var overflow = $target.css('overflow');

				var position_right = $target_right.css('position');
				var overflow_right = $target_right.css('overflow');

				$source.one('zoom.destroy', function(){
					$source.off(".zoom");
					$target.css('position', position);
					$target.css('overflow', overflow);
					$target_right.css('position', position_right);
					$target_right.css('overflow', overflow_right);
					$img.remove();
				});
				
			}());

			img.onload = function () {
				var zoom = $.zoom(target, source, img, settings.magnify, target_right);

				function start(e) {
					$target.hide();
					$target_right.hide();
					zoom.init();
					zoom.move(e);

				var targ = e.currentTarget;
				if($(targ).hasClass('odd')){ // right side
					$target_right.hide();
					$target.show();
				} else {
					$target_right.show();
					$target.hide();
				}

					// Skip the fade-in for IE8 and lower since it chokes on fading-in
					// and changing position based on mousemovement at the same time.
					$img.stop()
					.fadeTo($.support.opacity ? settings.duration : 0, 1, $.isFunction(settings.onZoomIn) ? settings.onZoomIn.call(img) : false);
				}

				function stop(e) {
					$target.hide();
					$target_right.hide();		
					$img.stop()
					.fadeTo(settings.duration, 0, $.isFunction(settings.onZoomOut) ? settings.onZoomOut.call(img) : false);

				}

				// Mouse events
				if (settings.on === 'grab') {
					$source
						.on('mousedown.zoom',
							function (e) {
								if (e.which === 1) {
									$(document).one('mouseup.zoom',
										function () {
											stop();

											$(document).off(mousemove, zoom.move);
										}
									);

									start(e);

									$(document).on(mousemove, zoom.move);

									e.preventDefault();
								}
							}
						);
				} else if (settings.on === 'click') {
					$source.on('click.zoom',
						function (e) {
							if (clicked) {
								// bubble the event up to the document to trigger the unbind.
	
								return;
							} else {
								clicked = true;
								start(e);
								$(document).on(mousemove, zoom.move);
								$(document).one('click.zoom',
									function () {
										stop();
	
										clicked = false;
										$(document).off(mousemove, zoom.move);

									}
								);
								return false;
							}
						}
					);
				} else if (settings.on === 'toggle') {
					$source.on('click.zoom',
						function (e) {
							if (clicked) {
								stop();
			
							} else {
								start(e);
		
							}
							clicked = !clicked;
						}
					);
				} else if (settings.on === 'mouseover') {
					zoom.init(); // Preemptively call init because IE7 will fire the mousemove handler before the hover handler.

					$source
						.on('mouseenter.zoom', start)
						.on('mouseleave.zoom', stop)
						.on(mousemove, zoom.move);

				}
				if (settings.touch) {
					
					$source
						.on('touchstart.zoom', function (e) {
							e.preventDefault();
							e.stopPropagation();
							if (touched) {
								touched = false;
								stop();
							} else {
								touched = true;
								// start( e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] );
							}
						},start)
						.on('touchmove.zoom', function (e) {
							e.preventDefault();
							e.stopPropagation();
							zoom.move( e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] );
						})
						.on('touchend.zoom', stop);
				}
				
				if ($.isFunction(settings.callback)) {
					settings.callback.call(img);
				}
			};

			img.src = settings.url;
			$(img).attr('data-position',settings.zoomviewposition);


		});
	};

	$.fn.zoom.defaults = defaults;
}(window.jQuery));