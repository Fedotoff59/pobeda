<?php
/*
Template Name: news
*/
?>
<?php get_header(); ?>
	<div class="news_block">
	<?php query_posts('showposts=10&cat=2&paged='.get_query_var('paged')); ?>
                            <?php if (have_posts()) : 
                                $i = 0;
                            ?>
                            <table>
                                <tr>
                                <?php while (have_posts()): the_post(); ?>
                                    <?php if($i > 0 && $i % 2 === 0):?>
                                           </tr><tr>
                                    <?php endif;?>
                                    <td>
                                        <div class="item-post">
                                        <a href="<?php the_permalink() ?>" rel="bookmark">
                                            <span class="date"><?php the_time('j F, Y'); ?></span>
                                            <?php the_post_thumbnail(array( 170,120 ), array( 'class' => 'news-img' )); ?>
                                            <h3><?php the_title(); ?></h3>
                                            <?php the_excerpt(); ?>
                                        </a>
                                        </div>
                                   </td>
                            <?php $i++; endwhile;?>
                                   </tr></table>
                            <?php endif;?>
    	</div>
	</div>
<?php get_footer(); ?>
