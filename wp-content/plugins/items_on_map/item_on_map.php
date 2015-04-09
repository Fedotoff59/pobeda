<?php
/*
Plugin Name: Item on map
Plugin URI: 
Description: Плагин, позволяющий добавлять объкты на яндекс карту
Version: 1.0
Author: Ivan Verkholantsev
*/
	$errors;
	global $wpdb;
	$table_name = $wpdb->prefix . 'items';
	//инициализация
	function install_plugin(){
		global $wpdb;
		
		global $table_name;
	
		
		if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
			$sql = 'CREATE TABLE '.$table_name.' (
				id int(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				address varchar(100)  NOT NULL,
				reference text NOT NULL,
				first_image_url varchar(100) DEFAULT \'\' NOT NULL,
				second_image_url varchar(100) DEFAULT \'\' NOT NULL,
				first_coordinate FLOAT NOT NULL,
				second_coordinate FLOAT NOT NULL,
				UNIQUE KEY id (id)
			)';
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		}
		
	}
	
	register_activation_hook(__FILE__,'install_plugin');
	//получение точек
	function get_points() {

		global $wpdb;
		global $table_name;

		$sql = 'SELECT * FROM '.$table_name;
		$results = $wpdb->get_results($sql, ARRAY_A);

		if (empty($results)) return false;
		
		return $results;

	}
	
	function getStrings(){
		$cats = get_points();
		if ($cats == 0) return;
		$terms = array();
 
	foreach($cats as $value) array_push($terms, $value);

	$output = array(
		'cats' => $terms
	);

	header("Content-type: application/json; charset=UTF-8");
	exit(json_encode($output));
	}
	
	//Вывод карты
	function show_map($atts){
		$url = home_url().'/wp-admin/admin-ajax.php';
		return '<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>
				<script type="text/javascript">
				$( document ).ready(function() {
					var perm_map;
						
					ymaps.ready(function(){
						perm_map = new ymaps.Map("perm_map", {
							center: [59.12, 56.22],
							zoom: 6
						});
						var options = $.parseJSON($.ajax({
							url: \''.$url.'\',
							data: {action: \'get_strings\'},
							async: false,
							dataType: \'jsonp\'
						}).responseText);
						var i = 0;
						var myPlacemark = new Array();
						while(i in options.cats){
							myPlacemark[i] = new ymaps.Placemark(
								[options.cats[i].first_coordinate, options.cats[i].second_coordinate], {
									balloonContentHeader: options.cats[i].name,
									balloonContentBody: \'Адрес:\'+options.cats[i].address+\'</br>\'+\'Описание:\'+options.cats[i].reference+\'</br>\',
									balloonContentFooter: \'<img width="200px" src="\'+options.cats[i].first_image_url+\'"/><img width="200px" src="\'+options.cats[i].second_image_url+\'"/>\'
								}
							);
							perm_map.geoObjects.add(myPlacemark[i]);
							i++;
						}
					});
					
				});
				</script>
				<div id="perm_map" style="width:100%; height:100%"></div>';
	}
	
	add_shortcode( 'map', 'show_map' );
	//вставка точки
	function insert(){
		if ($_REQUEST['name'] == '') return;
		global $errors;
		global $wpdb;
		global $table_name;
		$first_file = '';
		$second_file = '';
		
		//если такое имя уже есть - завершаем
		$myrows = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE name LIKE '".$_REQUEST['name']."'", ARRAY_A);
		foreach ($myrows as $row){
			if ($row['name'] == $_REQUEST['name']) return;
		}
		
		
		if ( ! function_exists( 'wp_handle_upload' ) ) 
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		$file = &$_FILES['first_image_url'];
		$overrides = array( 'test_form' => false );

		$movefile = wp_handle_upload( $file, $overrides );
		$first_file = $movefile['url'];
		$file = &$_FILES['second_image_url'];
		$overrides = array( 'test_form' => false );

		$movefile = wp_handle_upload( $file, $overrides );
		$second_file = $movefile['url'];
		
		$name = $_REQUEST['name'];
		$address = $_REQUEST['address'];
		$reference = $_REQUEST['reference'];
		$first_coordinate = $_REQUEST['first_coordinate'];
		$second_coordinate = $_REQUEST['second_coordinate'];
		$wpdb->insert(
			$table_name,
			array(
				'name' => stripslashes($name),
				'address' => stripslashes($address),
				'reference' => stripslashes($reference),
				'first_image_url' => $first_file,
				'second_image_url' => $second_file,
				'first_coordinate' => $first_coordinate,
				'second_coordinate' => $second_coordinate
			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%f',
				'%f'
			)
		);
	}
	
	//обновление данных
	function update(){
		global $errors;
		global $table_name;
		global $wpdb;
		//проверка повторной отправки формы
		$myrows = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE name LIKE '".$_REQUEST['name']."'", ARRAY_A);
		foreach ($myrows as $row){
			if ($row['name'] == $_REQUEST['name']) return;
		}
		$i=0;
		while (array_key_exists ('name_'.$i, $_REQUEST)){
			if ($_REQUEST['del_'.$i] == "on"){
				$wpdb->delete(
					$table_name,
					array( 'id' => $_REQUEST['id_'.$i] ),
					array( '%d' )
				);
			}
			else{	
				$name = $_REQUEST['name_'.$i];
				$address = $_REQUEST['address_'.$i];
				$reference = $_REQUEST['reference_'.$i];
				$wpdb->update(
					$table_name,
					array(
						'name' => stripslashes($name),
						'address' => stripslashes($address),
						'reference' => stripslashes($reference),
						'first_coordinate' => $_REQUEST['first_coordinate_'.$i],
						'second_coordinate' => $_REQUEST['second_coordinate_'.$i]
					),
					array( 'id' => $_REQUEST['id_'.$i] ),
					array(
						'%s',
						'%s',
						'%s',
						'%f',
						'%f'
					),
					array( '%d' )
				);
			}
			if ($_FILES['first_image_url_'.$i]['name']!=''){
				$result = $wpdb->get_results('select first_image_url from '.$table_name.' where id = '.$_REQUEST['id_'.$i], ARRAY_A);
				foreach ($result as $row) unlink('/var/www/'.substr($row['first_image_url'],7));
				$file = &$_FILES['first_image_url_'.$i];
				$overrides = array( 'test_form' => false );

				$movefile = wp_handle_upload( $file, $overrides );
				
				$first_file = $movefile['url'];
				$wpdb->update(
					$table_name,
					array(
						'first_image_url' => $first_file
					),
					array( 'id' => $_REQUEST['id_'.$i] ),
					array(
						'%s'
					),
					array( '%d' )
				);
			}
			if ($_FILES['second_image_url_'.$i]['name']!=''){
				$result = $wpdb->get_results('select second_image_url from '.$table_name.' where id = '.$_REQUEST['id_'.$i], ARRAY_A);
				foreach ($result as $row) unlink('/var/www/'.substr($row['second_image_url'],7));
				$file = &$_FILES['second_image_url_'.$i];
				$overrides = array( 'test_form' => false );

				$movefile = wp_handle_upload( $file, $overrides );
				$second_file = $movefile['url'];
				$wpdb->update(
					$table_name,
					array(
						'second_image_url' => $second_file
					),
					array( 'id' => $_REQUEST['id_'.$i] ),
					array(
						'%s'
					),
					array( '%d' )
				);
			}
			$i++;
		}
	}
	
	//Вывод формы в админке
	function map_form(){
		global $errors;
		update();
		insert();
		$points = get_points();
		?>
		<form method="post" id="form" enctype="multipart/form-data" action=""></form>
		<table>
		<tr bgcolor="#99FF99">
			<td>id</td>
			<td>Название</td>
			<td>Первое изображение</td>
			<td>Первая координата</td>
			<td rowspan=2>Описание</td>
		</tr>
		<tr bgcolor="#99FF99">
			<td>Удалить?</td>
			<td>Адрес</td>
			<td>Второе изображение</td>
			<td>Вторая координата</td>
		</tr>
		<?php
		$i = 0;
		foreach ($points as $point){
			?>
		<tr bgcolor="<?php if ($i%2) echo '#FFFF99'; else echo '#6699CC';?>">
			<td><?php echo $point['id'];?><input type="hidden" value="<?php echo $point['id'];?>" name="id_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo htmlspecialchars($point['name']);?>" name="name_<?php echo $i;?>" form="form" maxlength=100 size=40/></td>
			<td><img src="<?php echo $point['first_image_url'];?> " width="75"/><input type="file" name="first_image_url_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo $point['first_coordinate'];?>" class="first_coords" id="first_coordinate_<?php echo $i;?>" name="first_coordinate_<?php echo $i;?>" form="form" size=5 readonly /></td>
			<td rowspan=2><textarea class="references" rows=8 name="reference_<?php echo $i;?>" form="form" size=20><?php echo htmlspecialchars($point['reference']);?></textarea></td>
		</tr>
		<tr bgcolor="<?php if ($i%2) echo '#FFFF99'; else echo '#6699CC';?>">
			<td><input type="checkbox" name="del_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo htmlspecialchars($point['address']);?>" name="address_<?php echo $i;?>" form="form" maxlength=100 size=40/></td>
			<td><img src="<?php echo $point['second_image_url'];?> " width="75"/><input type="file" name="second_image_url_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo $point['second_coordinate'];?>" class="second_coords" id="second_coordinate_<?php echo $i;?>" name="second_coordinate_<?php echo $i;?>" form="form" size=5 readonly /></td>
		</tr>
		<?php $i++;}
		?>
		<tr bgcolor="#FFCC99">
			<td>Новая</br>точка</td>
			<td><input type="text" name="name" form="form" maxlength=100 size=40 /></td>
			<td><input type="file" name="first_image_url" form="form" /></td>
			<td><input type="text" name="first_coordinate" id="first_coord" form="form" size=5 readonly /></td>
			<td rowspan=2><textarea rows=8 name="reference" form="form" size=20></textarea></td>
		</tr>
		<tr bgcolor="#FFCC99">
			<td></td>
			<td><input type="text" name="address" form="form" maxlength=100 size=40 /></td>
			<td><input type="file" name="second_image_url" form="form" /></td>
			<td><input type="text" name="second_coordinate" id="second_coord" form="form" size=5 readonly /></td>
		</tr>
		</table>
		<input type="submit" id="ok" value="Обновить" form="form">
		<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript" language="javascript"></script>
		<script type="text/javascript">
			ymaps.ready(function(){
				$('textarea, input[type=\'text\']').bind('input propertychange', function() {
					var valid = '«»\n -–()!?%№*qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789.:"+=,ЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮёйцукенгшщзхъфывапролджэячсмитьбю\'—';
					i = 0;
					while (i < this.value.length){
						if (valid.indexOf(this.value[i])==-1){
							alert('недопустимый символ!');
							this.value = this.value.replace(this.value[i],'');
						}
						i++;
					}
					//this.value = this.value.replace('/\'/g','\\\'');
					//this.value = this.value.replace('/\"/g','\\\"');
				});
				$(".references").change(function () {
					});
				var perm_map = new ymaps.Map("perm_map", {
					center: [58.12, 56.22],
					zoom: 7
				}),
				myPlacemark = new ymaps.Placemark(
					[58.12, 56.22],
					{
						hintContent: 'Новая метка'
					},
					{
						draggable: true
					}
				);
				perm_map.geoObjects.add(myPlacemark);
				myPlacemark.events.add('dragend', function(e) {
				   // Получение ссылки на объект, который был передвинут.
				   var thisPlacemark = e.get('target');
				   // Определение координат метки
				   var coords = thisPlacemark.geometry.getCoordinates();
				   // и вывод их при щелчке на метке
				   document.getElementById('first_coord').value = coords[0].toPrecision(6);
				   document.getElementById('second_coord').value = coords[1].toPrecision(6);
				});
				var first_coords = document.getElementsByClassName('first_coords');
				var second_coords = document.getElementsByClassName('second_coords');
				var i=0;
				var Placemarks = new Array();
				while (i in first_coords){
					Placemarks[i] = new ymaps.Placemark(
						[first_coords[i].value, second_coords[i].value],
						{
							hintContent: document.getElementsByName('name_'+i)[0].value
						},
						{
							preset: 'islands#redIcon',
							draggable: true
						}
					);
					perm_map.geoObjects.add(Placemarks[i]);
					
					Placemarks[i].events.add('dragend', function(e) {
					   // Получение ссылки на объект, который был передвинут.
					   var thisPlacemark = e.get('target');
					   // Определение координат метки
					   var coords = thisPlacemark.geometry.getCoordinates();
					   // и вывод их при щелчке на метке
					   document.getElementById('first_coordinate_'+Placemarks.indexOf(e.get('target'))).value = coords[0].toPrecision(6);
					   document.getElementById('second_coordinate_'+Placemarks.indexOf(e.get('target'))).value = coords[1].toPrecision(6);
					});
					i++;
				}
				
			});
		</script>
		<div id="perm_map" style="width:100%; height:400px;"></div>
		<?php
	}
	function map_admin_menu(){
		add_options_page('Map menu', 'Map', 8, basename(__FILE__), 'map_form');
	}

	add_action('admin_menu', 'map_admin_menu');
	add_action('wp_ajax_get_strings', 'getStrings');
	add_action('wp_ajax_nopriv_get_strings', 'getStrings');
?>