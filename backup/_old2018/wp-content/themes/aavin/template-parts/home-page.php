<?php
/**
 * Template Name: Home
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Aavin
 * @since Aavin 1.0
 */

get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="main-content">
			<section class="container">
				<section class="slider">
					<ul class="slides">
                     <?php $args = array('post_type' => array('slider'), 'orderby' => 'menu_order', 'order' => 'ASC');
                            $loop = new WP_Query($args);
                            while ($loop->have_posts()) : $loop->the_post(); ?>
                     <li>
						<?php the_post_thumbnail('full'); ?>
						<section class="slider-content">
							<div class="vertical-center">
								<div class="vertical-middle">
									<h3><?php the_title(); ?></h3>
									<?php the_content(); ?>
								</div>
							</div>
						</section>
                     </li>
					<?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
					</ul>
				</section><!-- Slider -->
				<section class="content">
					<!--<div class="financing-inquiry eql-width">
						<?php dynamic_sidebar( 'financing' ); ?>
					</div>-->
					<div class="news-content eql-width">
						<h2>News Board</h2>
						<div class="content-div">
							<ul>
							 <?php $args = array('post_type' => array('news'));
									$loop = new WP_Query($args);
									while ($loop->have_posts()) : $loop->the_post(); ?>
							 <li>
								<section class="news-info">
									<span><?php the_field('date');?></span>
									<h3><?php the_title(); ?></h3>
									<?php the_content(); ?>
								</section>
							 </li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
							</ul>
						</div>
					</div>
					<div class="resources eql-width last">
						<h2>Resources</h2>
						<div class="content-div">
							<ul>
							 <?php $args = array('post_type' => array('resources'));
									$loop = new WP_Query($args);
									while ($loop->have_posts()) : $loop->the_post(); ?>
							 <li>
								<section class="news-info">
									<a href="<?php the_field('link_url');?>" target="_blank"><?php the_post_thumbnail('full'); ?></a>
									<h3><a href="#"><?php the_title(); ?></a></h3>
								</section>
							 </li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
							</ul>
						</div>
					</div>
				</section>
			</section><!-- container -->
		</section><!-- main-content -->
	</main><!-- .site-main -->
	
</div><!-- .content-area -->
<?php get_footer(); ?>
