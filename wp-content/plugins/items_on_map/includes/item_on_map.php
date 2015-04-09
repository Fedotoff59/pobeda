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
				name varchar(30) NOT NULL,
				address varchar(50)  NOT NULL,
				reference text NOT NULL,
				first_image_url varchar(55) DEFAULT \'\' NOT NULL,
				second_image_url varchar(55) DEFAULT \'\' NOT NULL,
				first_coorinate FLOAT NOT NULL,
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
	
	
	//Вывод карты
	function show_map($atts){
		$url = plugin_dir_url().'items_on_map/includes/get.php';
		return '<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>
				<script type="text/javascript">
				$( document ).ready(function() {
					var perm_map;
						
					ymaps.ready(function(){
						perm_map = new ymaps.Map("perm_map", {
							center: [58.12, 56.22],
							zoom: 7
						});
					});
					$.getJSON(\''.$url.'\', function(data) {  
						alert(1); 
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
		$first_coorinate = $_REQUEST['first_coorinate'];
		$second_coordinate = $_REQUEST['second_coordinate'];
		$wpdb->insert(
			$table_name,
			array(
				'name' => $name,
				'address' => $address,
				'reference' => $reference,
				'first_image_url' => $first_file,
				'second_image_url' => $second_file,
				'first_coorinate' => $first_coorinate,
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
				$wpdb->update(
					$table_name,
					array(
						'name' => $_REQUEST['name_'.$i],
						'address' => $_REQUEST['address_'.$i],
						'reference' => $_REQUEST['reference_'.$i],
						'first_coorinate' => $_REQUEST['first_coorinate_'.$i],
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
		<div><?php echo 'Errors:'.$errors;?></div>
		<form method="post" id="form" enctype="multipart/form-data" action=""></form>
		<form method="post" id="form_change" enctype="multipart/form-data" action=""></form>
		<table border="1">
		<tr>
			<td>id</td>
			<td>name</td>
			<td>address</td>
			<td>reference</td>
			<td>first_image_url</td>
			<td>second_image_url</td>
			<td>first_coorinate</td>
			<td>second_coordinate</td>
			<td>delete</td>
		</tr>
		<?php
		$i = 0;
		foreach ($points as $point){?>
		<tr>
			<td><?php echo $point['id'];?><input type="hidden" value="<?php echo $point['id'];?>" name="id_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo $point['name'];?>" name="name_<?php echo $i;?>" form="form" maxlength=30 size=20/></td>
			<td><input type="text" value="<?php echo $point['address'];?>" name="address_<?php echo $i;?>" form="form" maxlength=50 size=20/></td>
			<td><textarea name="reference_<?php echo $i;?>" form="form" size=20><?php echo $point['reference'];?></textarea></td>
			<td><img src="<?php echo $point['first_image_url'];?> " width="75"/><input type="file" name="first_image_url_<?php echo $i;?>" form="form"/></td>
			<td><img src="<?php echo $point['second_image_url'];?> " width="75"/><input type="file" name="second_image_url_<?php echo $i;?>" form="form"/></td>
			<td><input type="text" value="<?php echo $point['first_coorinate'];?>" name="first_coorinate_<?php echo $i;?>" form="form" size=5/></td>
			<td><input type="text" value="<?php echo $point['second_coordinate'];?>" name="second_coordinate_<?php echo $i;?>" form="form" size=5/></td>
			<td><input type="checkbox" name="del_<?php echo $i;?>" form="form"/></td>
		</tr>
		<?php $i++;}
		?>
		<tr>
			<td>New</td>
			<td><input type="text" name="name" form="form" maxlength=30 size=20></td>
			<td><input type="text" name="address" form="form" maxlength=50 size=20></td>
			<td><textarea name="reference" form="form" size=20></textarea></td>
			<td><input type="file" name="first_image_url" form="form"></td>
			<td><input type="file" name="second_image_url" form="form"></td>
			<td><input type="text" name="first_coorinate" id="first_coord" form="form" size=5 readonly></td>
			<td><input type="text" name="second_coordinate" id="second_coord" form="form" size=5 readonly></td>
			<td></td>
		</tr>
		</table>
		<input type="submit" value="update" form="form">
		<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>
		<script type="text/javascript">
				
			ymaps.ready(function(){
				var perm_map = new ymaps.Map("perm_map", {
					center: [58.12, 56.22],
					zoom: 7
				}),
				myPlacemark = new ymaps.Placemark(
					[58.12, 56.22],
					{},
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
				   thisPlacemark.properties.set('balloonContent', coords);
				   document.getElementById('first_coord').value = coords[0].toPrecision(6);
				   document.getElementById('second_coord').value = coords[1].toPrecision(6);
				});
			});
		</script>
		<div id="perm_map" style="width:700px; height:400px;"></div>
		<?php
	}
	function map_admin_menu(){
		add_options_page('Map menu', 'Map', 8, basename(__FILE__), 'map_form');
	}

	add_action('admin_menu', 'map_admin_menu');
?>