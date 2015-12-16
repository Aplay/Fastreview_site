<?php
/**
 * @author EnChikiben
 */
class YandexMap extends CWidget
{
	private $cs = null;

	public $htmlOptions = array();

	public $protocol = "http://";
	public $lang = "ru-RU";
	public $load = 'package.full';
	public $key = null;
	public $coordorder = 'latlong';
	public $mode = 'release';
	public $clusterIcon = '/img/clusterbig.png';


	public $id = 'yandexmap';
	public $width = 600;
	public $height = 400;
	public $zoom = 10;
	public $metro = false;
	public $center = array("ymaps.geolocation.latitude", "ymaps.geolocation.longitude");
    public $behaviors = array('default', 'scrollZoom');
	public $options = array();
    public $clustering = true;
	public $controls = array(
		'zoomControl' => true,
		'typeSelector' => true,
		'mapTools' => true,
		'smallZoomControl' => false,
		'miniMap' => true,
		'scaleLine' => true,
		'searchControl' => true,
		'trafficControl' => true
	);


	public $placemark = array();
	public $polyline = array();
    public $route = array();

	protected function connectYandexJsFile(){
		$url = array();
		$url[] = "lang=".$this->lang;
		$url[] = "load=".$this->load;
		

		$this->cs->registerScriptFile($this->protocol."api-maps.yandex.ru/2.1/?".  implode("&", $url) );
	}

	protected function initMapJsObject(){
		$state = array();

		if (is_array($this->center) && !empty($this->center) )
			$state[] = "center:[{$this->center[0]},{$this->center[1]}]";
		else
			throw new Exception('Error center map coordinat.');

		if ( $this->zoom )
			$state[] = 'zoom:'.$this->zoom;
                foreach ($this->behaviors as $key => $value) {
                    $this->behaviors[$key]="'".$value."'";
                }
                $beh=implode(",",$this->behaviors);
                $state[]='behaviors:['.$beh.']';

		$state = implode(",",$state);

		$options = $this->generateOptions($this->options);
		return "map = new ymaps.Map ('{$this->id}',{".$state."},{".$options."});";
	}

	protected function initMapControl(){
		$controls = array(); $ret = '';
		
		if ( is_array($this->controls) && !empty($this->controls) )
			foreach ($this->controls as $key => $value ) {
				if ( $value === true){
					 if($key == 'zoomControl'){
					 	$ret .= "map.controls.add(
							   new ymaps.control.ZoomControl()
							);";
					 } else if($key == 'smallZoomControl'){
					 	/*$ret .= "map.controls.add(
							   new ymaps.control.ZoomControl({
								    options: {
								        size: 'small',
								        position: {
								        	top: 20, 
								        	right: 20
								        }
								        
								    }
								})
							);";*/
		$ret .= "
		// Создадим пользовательский макет ползунка масштаба.
        ZoomLayout = ymaps.templateLayoutFactory.createClass(\"<div>\" +
                \"<button id='zoom-in' class='btn btn-default btn-icon customZoom'><i class='md md-add c-green' style='font-size:22px;'></i></button><br>\" +
                \"<button id='zoom-out' class='btn btn-default btn-icon customZoom' style='margin-top:10px;'><i class='md md-remove c-green' style='font-size:22px;'></i></button>\" +
            \"</div>\", {

            // Переопределяем методы макета, чтобы выполнять дополнительные действия
            // при построении и очистке макета.
            build: function () {
                // Вызываем родительский метод build.
                ZoomLayout.superclass.build.call(this);

                // Привязываем функции-обработчики к контексту и сохраняем ссылки
                // на них, чтобы потом отписаться от событий.
                this.zoomInCallback = ymaps.util.bind(this.zoomIn, this);
                this.zoomOutCallback = ymaps.util.bind(this.zoomOut, this);

                // Начинаем слушать клики на кнопках макета.
                $('#zoom-in').bind('click', this.zoomInCallback);
                $('#zoom-out').bind('click', this.zoomOutCallback);
            },

            clear: function () {
                // Снимаем обработчики кликов.
                $('#zoom-in').unbind('click', this.zoomInCallback);
                $('#zoom-out').unbind('click', this.zoomOutCallback);

                // Вызываем родительский метод clear.
                ZoomLayout.superclass.clear.call(this);
            },

            zoomIn: function () {
                var map = this.getData().control.getMap();
                // Генерируем событие, в ответ на которое
                // элемент управления изменит коэффициент масштабирования карты.
                this.events.fire('zoomchange', {
                    oldZoom: map.getZoom(),
                    newZoom: map.getZoom() + 1
                });
            },

            zoomOut: function () {
                var map = this.getData().control.getMap();
                this.events.fire('zoomchange', {
                    oldZoom: map.getZoom(),
                    newZoom: map.getZoom() - 1
                });
            }
        }),
        zoomControl = new ymaps.control.ZoomControl({ options: { 
        	layout: ZoomLayout,
	        position: {
	        	top: 20, 
	        	right: 20
	        }
        } });

        map.controls.add(zoomControl);";

					 }
				}
			}

			
			$controls = array();
			foreach ($this->controls as $key => $value ) {
				if ( $value === true){
					// $controls[] = "add(".CJavaScript::encode($key).")";
				}
				else{
					$controls[] = "remove(".CJavaScript::encode($key).")";
				}
			}
			$ret .= "map.controls.".implode('.', $controls).';';

			return $ret;

		
	}


	protected function registerClientScript(){

		$this->connectYandexJsFile();

		$map = $this->initMapJsObject();
		$control = $this->initMapControl();
                
		$placemark = $this->placemarks();
		$polyline = $this->polylines();
        $route = $this->route();
        $dometro = $this->dometro();

		$js = <<<EQF

ymaps.ready(function(){\n 
	$map\n 
	$control\n 
    $route\n 
	$placemark
	$polyline\n 
    $dometro\n        
});\n 

EQF;

		$this->cs->registerScript($this->id,$js,CClientScript::POS_END);
	}

	protected function is_array(&$array){
		list($key) = array_keys($array);
		return is_array($array[$key]);
	}

	protected function generateOptions(&$array){
		$options = array();

		if ( !empty($array) && is_array($array) )
			foreach ($array as $key => $value ) {
				$options[] = CJavaScript::encode($key).":".CJavaScript::encode($value)."";
			}
		else
			return null;

		return implode(',', $options);
	}


	protected function placemarks(){
		$placemark = '';
	/*	$placemark .= "var MyHintContentLayout = ymaps.templateLayoutFactory.createClass(\n
        '<div class=\"ya_hint top\"><div class=\"arrow\"></div>$[properties.hintContent]</div>'\n
    );\n";*/
		$placemark .= "var BalloonTimeout = 0; var MyBalloonLayout = ymaps.templateLayoutFactory.createClass(\n
        '<div class=\"ya_hint top\"><div class=\"arrow\"></div>$[properties.content]</div>'\n
    ,{

        build: function () {
                        this.constructor.superclass.build.call(this);

                        this._\$element = $('.ya_hint', this.getParentElement());

                        var geoObject = this.getData().geoObject,
                        map = geoObject.getMap();

                        this._\$element.on('mouseenter', function(){
                        	window.clearTimeout(BalloonTimeout);
                        }).on('mouseleave', function(){
                        	BalloonTimeout = window.setTimeout(function(){
                        		geoObject.balloon.close();
                        	}, 400);
                        });

                    }
        
    });\n";
	$placemark .= " var myClusterBalloonLayout = ymaps.templateLayoutFactory.createClass([\n
        '<div class=\"ya_hint top cluster\"><div class=\"arrow\"></div>',\n 
        // Выводим в цикле список всех геообъектов.\n
                '{% for geoObject in properties.geoObjects %}',
                    '{{ geoObject.properties.content|raw }}',
                '{% endfor %}','</div>'\n
    ].join(''),{
    	build: function () {
                this.constructor.superclass.build.call(this);

                this._\$element = $('.ya_hint', this.getParentElement());

                this._\$element.on('mouseenter', function(){
                	window.clearTimeout(BalloonTimeout);
                }).on('mouseleave', function(){
                	BalloonTimeout = window.setTimeout(function(){
                		clusterer.balloon.close();
                	}, 400);
                });
            
        }
    });\n";
        if ($this->clustering){
		$placemark .= 'var myGeoObjects = [];';
		if (is_array($this->placemark) && !empty($this->placemark) ){

			if ( $this->is_array($this->placemark) ){
				foreach ($this->placemark as $key => $value) {
					$placemark .= $this->placemark($key, $value);

				}
			} else {
				$placemark .= $this->placemark('placemark', $this->placemark);
			}
		}

                $placemark.=";\n 
// Переменная с описанием двух видов иконок кластеров.\n
var clusterIcons = [\n
 {\n
     href: '".$this->clusterIcon."',\n
     size: [40, 40],\n
     // Отступ, чтобы центр картинки совпадал с центром кластера.\n
     offset: [-20, -20]\n
 },
 {\n
     href: '".$this->clusterIcon."',\n
     size: [60, 60],\n
     // Отступ, чтобы центр картинки совпадал с центром кластера.\n
     offset: [-30, -30]\n
 }];\n

 // При размере кластера до 100 будет использована картинка 'small.jpg'.\n
 // При размере кластера больше 100 будет использована 'big.png'.\n
var clusterNumbers = [100];\n
// Сделаем макет содержимого иконки кластера,\n
 // в котором цифры будут раскрашены в белый цвет.\n
 var MyIconContentLayout = ymaps.templateLayoutFactory.createClass(\n
 '<div style=\"color: #000; font-weight: bold;\">$[properties.geoObjects.length]</div>');\n
 
  
                		var	clusterer = new ymaps.Clusterer({\n
				            	preset: 'islands#invertedNightClusterIcons',\n
				            	groupByCoordinates: false,\n
				            	clusterDisableClickZoom: true,\n
				            	clusterIcons: clusterIcons,\n
    							clusterNumbers: clusterNumbers,\n
    							clusterIconContentLayout: MyIconContentLayout,\n
    							clusterHideIconOnBalloonOpen: false,\n
    							// Устанавливаем режим открытия балуна.\n 
						        // балун никогда не будет открываться в режиме панели.\n
						        clusterBalloonPanelMaxMapArea: 0,\n
						        // Устанавливаем размер макета контента балуна (в пикселях).\n
						        clusterBalloonContentLayoutWidth: 200,\n
						        clusterBalloonContentLayoutHeight: 290,\n
						        clusterBalloonLayout: myClusterBalloonLayout,\n
							    geoObjectBalloonLayout: MyBalloonLayout,\n

                			});\n
                            clusterer.add(myGeoObjects);\n
                            map.geoObjects.add(clusterer);\n";
                         

                        $placemark .= "
                        // Отключает открытие хинта при наведении мыши и открывает вместо него балун.\n
						// map.geoObjects.options.set({\n
						//    showHintOnHover: false\n
						// });\n";
						$placemark .= "
						
						clusterer.events
				        // Можно слушать сразу несколько событий, указывая их имена в массиве.
				        .add(['mouseenter', 'mouseleave', 'click'], function (e) {
				            var target = e.get('target'),
				            	position = e.get('globalPixel'),
				                type = e.get('type');
				            if (typeof target.getGeoObjects != 'undefined') {
				                // Событие произошло на кластере.
				                if (type == 'mouseenter') {
				                	clusterer.balloon.open(target);
				                	window.clearTimeout(BalloonTimeout);

				                } else if(type == 'mouseleave'){
				                    BalloonTimeout = window.setTimeout(function(){
		                        		clusterer.balloon.close();
		                        	}, 400);
				                } else {
				                   clusterer.balloon.close();
				                }
				            } else {
				                // Событие произошло на геообъекте.
				                if (type == 'mouseenter') {
				                    target.balloon.open(position);
				                    window.clearTimeout(BalloonTimeout);

				                } else if(type == 'mouseleave'){
				                	BalloonTimeout = window.setTimeout(function(){
		                        		target.balloon.close();
		                        	}, 400);
									
				                } else {
				                   // target.balloon.close();
				                	
				                }
				            }
				        });";


            } else {

	            if (is_array($this->placemark) && !empty($this->placemark) ){
					if ( $this->is_array($this->placemark) ){
						foreach ($this->placemark as $key => $value) {
							$placemark .= $this->placemark("placemark_".$key, $value);
						}
					} else {
						$placemark .= $this->placemark('placemark', $this->placemark);
					}
				}
            }

		return $placemark;
	}

	protected function placemark($name,&$value){

		if ( !isset($value['lat'],$value['lon']) ) return;

		$placemark = '';

		$properties = '';
		if ( isset($value['properties']) ){
			$properties = $this->generateOptions($value['properties']);
		}

		$options = '';
		if ( isset($value['options']) ){
			$options = $this->generateOptions($value['options']);
		}
				// Из php кода не передать переменную
                if(isset($value['options']['hintContentLayout']) && $value['options']['hintContentLayout']=='MyHintContentLayout'){
                	$options .= ",'hintContentLayout':MyHintContentLayout";
                }
                if(isset($value['options']['balloonContentLayout']) && $value['options']['balloonContentLayout']=='MyBalloonContentLayoutClass'){
                	$options .= ",'balloonContentLayout':MyBalloonContentLayoutClass";
                }
                if(isset($value['options']['balloonLayout']) && $value['options']['balloonLayout']=='MyBalloonLayout'){
                	$options .= ",'balloonLayout':MyBalloonLayout";
                }

                if ($this->clustering){
                	
                    $placemark .= "myGeoObjects[{$name}] = new ymaps.Placemark([{$value['lat']},{$value['lon']}],{".$properties."},{".$options."}); ";

                }
                else{

                    $placemark .= "var {$name} = new ymaps.Placemark([{$value['lat']},{$value['lon']}],{".$properties."},{".$options."}); ";

                    $placemark .= "map.geoObjects.add({$name}); ";


                }

		return $placemark;
	}
        protected function route(){

		if (!isset($this->route["from"]) || $this->route["from"]=="") return;

		$route = '';
                $address2 = CJavaScript::encode($this->route["from"]);
		$address1 = CJavaScript::encode($this->route["to"]);
                $success=CJavaScript::encode($this->route["success"]);
		$route.= "\n ymaps.route([".$address2.",".$address1."]).then(function (route) {\n 
                            map.geoObjects.add(route);\n 
                            var points = route.getWayPoints();\n 
                            lastPoint = points.getLength() - 1;\n 
                            $success
                            points.options.set('preset', 'twirl#redStretchyIcon');
                            // Задаем контент меток в начальной и конечной точках.
                            points.get(0).properties.set('iconContent', 'Точка отправления');
                            points.get(lastPoint).properties.set('iconContent', 'Точка прибытия');
                            map.panTo(points.get(lastPoint).geometry.getCoordinates());\n 
                         }, function (error) {\n 
                            alert('Призошла ошибка: ' + error.message);\n 
                        });\n ";

		return $route;
	}

	protected function polylines(){
		$polylines = '';
		if ( is_array($this->polyline) && !empty($this->polyline) ){

			list($key) = array_keys($this->polyline);
			if ( $this->is_array($this->polyline) && !isset($this->polyline[$key]['lat']) ){
				foreach ($this->polyline as $key => $value)
					$polylines .= $this->polyline('polyline_'.$key,$value);
			} else {
				$polylines .= $this->polyline('polyline',$this->polyline);
			}

		}
		return $polylines;
	}

	protected function polyline($name,&$value){

		$polyline = '';

		$coordinates = array();
		foreach ($value as $coordinate) {
			if ( isset($coordinate['lat'],$coordinate['lon']) )
				$coordinates[] = "[".$coordinate['lat'].",".$coordinate['lon']."]";
		}

		if ( empty($coordinates) ) return;

		$properties = '';
		if ( isset($value['properties']) ){
			$properties = $this->generateOptions($value['properties']);
		}

		$options = '';
		if ( isset($value['options']) ){
			$options = $this->generateOptions($value['options']);
		}

		$polyline .= "var $name = new ymaps.Polyline([".implode(',', $coordinates)."], {".$properties."}, {".$options."});\n";
		$polyline .= "map.geoObjects.add($name);\n";

		return $polyline;
	}

	protected function dometro(){
		if (!$this->metro) return;
		$coordinates = '';
		if($this->center && isset($this->center[0]) && isset($this->center[1])) {
				$coordinates = "[".floatval($this->center[0]).",".floatval($this->center[1])."]";
		}
		$dometro = "\n

				 ymaps.geocode(".$coordinates.", {kind: 'metro', results : 2\n			     
			     }).then(function (res) {\n
			     	if (res.geoObjects.getLength()) {\n
			     		var nearest = res.geoObjects.get(0);\n
			     		var name = nearest.properties.get('name');
			     		var m0_coords = nearest.geometry.getCoordinates();\n
			     		dist0 = ymaps.coordSystem.geo.getDistance(".$coordinates.", m0_coords);\n
			     		dist = ymaps.formatter.distance(dist0);\n
			     		console.log(name);\n
			     		console.log(dist);\n
			     		var textToPage = 'До '+ name + ' ' + dist;\n
			     		\$('.map-text').append(textToPage);
			     		\$('title').append(', '+ name);
			     		var descr = \$('.hvalues span.title').text();
			     		descr = descr + ', '+ name + '. ';
			     		descr = descr + \$('.hvalues span.part_description').text();
			     		\$('meta[name=description]').attr('content', descr);
			     		\$('meta[property=\"og:description\"]').attr('content', descr);
			     	}\n
				});\n
		";

		return $dometro;

	}


	public function init(){
		$this->cs = Yii::app()->clientScript;

		$this->registerClientScript();
	}

	public function run(){

		$this->htmlOptions['id'] = $this->id;

		if ( !isset($this->htmlOptions['style']) )
			$this->htmlOptions['style'] = "width:{$this->width}px;height:{$this->height}px;";

		echo CHtml::tag('div',$this->htmlOptions,'');
	}
}
?>