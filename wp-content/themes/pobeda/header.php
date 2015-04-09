<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
	<?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?>
</title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript" language="javascript"></script>
	<script src="<?php bloginfo('url'); ?>/wp-content/plugins/easy2map/scripts/jquery.xml2json.js" type="text/javascript" language="javascript"></script>
	
	<?php wp_head(); ?>
</head>

<body>

	<div id="wrapper">
	<img src="<?php bloginfo('template_url'); ?>/images/back-left-02.png" style=" float:left; height: 100%; top:0;left:0; bottom:0; right:0; position: fixed;">
<img src="<?php bloginfo('template_url'); ?>/images/back-right-02.png" style=" float:right; height: 100%;  bottom:0; right:0; position:fixed;">
		<div id="header">
			<div class="menu">
				<ul>
					<li><a href="http://pobeda.dobrovoblago.ru/about-project/"><span>О ПРОЕКТЕ</span></a></li>
					<li><a href="http://pobeda.dobrovoblago.ru/events/"><span>СОБЫТИЯ</span></a></li>
				</ul>
			</div>
		
			<a href="<?php echo get_option('home'); ?>" class="logo"></a>
			<div class="menu">
				<ul>
					<li><span>НОВОСТИ</span></li>
					<li><span>ГАЛЕРЕЯ</span></li>
				</ul>
				
			</div>
		</div>