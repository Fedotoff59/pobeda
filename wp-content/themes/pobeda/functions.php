<?php

function new_excerpt_length($length) {
	return 10; }
add_filter('excerpt_length', 'new_excerpt_length');

if( !is_admin()){
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"), false, '1.9.1');
	wp_enqueue_script('jquery');
}


if (function_exists('add_theme_support')) {
    add_theme_support('menus');
	add_theme_support( 'post-thumbnails' );
}


if ( function_exists('register_sidebar') ){
	register_sidebar(array('name'=>'Left Sidebar'));
	register_sidebar(array('name'=>'MainMenu Sidebar'));
	register_sidebar(array('name'=>'Footer Sidebar'));
	register_sidebar(array('name'=>'Banner Sidebar'));		
	register_sidebar(array('name'=>'MainPageContentBanner Sidebar'));	
    register_sidebar(array(
        'name' => 'Gallery Sidebar',
        'before_widget' => '<li class="widget-header">',
        'after_widget' => '</li>',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
}

function wpbsearchform( $form ) {
 
$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
 <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
 <input type="text" value="' . get_search_query() . '" name="s" id="s" />
 <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
 </div>
 </form>';
 
return $form;
}
 
add_shortcode('wpbsearch', 'wpbsearchform');
?>