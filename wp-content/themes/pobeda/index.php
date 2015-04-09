<?php get_header(); ?>
	<a href="http://xn--70-dlcm3amfbus5h.xn--p1ai/register/"><div class="banner_uchastnik"> </div></a>
	<h1>ИНТЕРАКТИВНАЯ КАРТА</h1>
	<p>На интерактивной карте будут указаны памятные места, аллеи славы и места воинских захоронений Ветеранов Великой Отечественной войны.</p>
	<div class="karta">
	
	<?php echo do_shortcode("[easy2map id=1]"); ?>
	</div>
	<div class="info_about_project">
		<h1><a href="http://pobeda.dobrovoblago.ru/news/">НОВОСТИ</a></h1>
		<?php query_posts('showposts=6&cat=2&paged='.get_query_var('paged')); ?>
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
                                            
                                        </a>
                                        </div>
                                   </td>
                            <?php $i++; endwhile;?>
                                   </tr></table>
                            <?php endif;?>
	</div>
</div>
<?php get_footer(); ?>
