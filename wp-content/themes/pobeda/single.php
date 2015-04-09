
<?php get_header(); ?>
	<a href="http://pobeda.dobrovoblago.ru/anketa-volontera/" class="banner_uchastnik"> </a>
	<div class="info_about_project">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<p class="news-title"><?php the_title(); ?></p>
			<p class="news-date"><?php the_time('j F, Y'); ?></p>
			<div class="entry-content">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
				<?php wp_link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
			</div>
		
		<?php endwhile; endif; ?> 
	</div>
<?php get_footer(); ?>
