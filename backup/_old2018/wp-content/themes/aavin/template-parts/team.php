<?php
/**
 * Template Name: Team
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
	<main id="main" class="site-main inner-page" role="main">
		<div class="container">
			<ul class="teamList">
				<?php
				$pargs = array(
					'post_type' => 'ourteam',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'order' => 'DESC'
					);
				$p_query = new WP_Query($pargs);

				if ($p_query->have_posts()) : while ($p_query->have_posts()) : $p_query->the_post();
				global $post;
				?>	
				
				<li>
					<a href="<?php the_permalink(); ?>">
						
						<?php the_post_thumbnail('full'); ?>
						<h6><?php the_title(); ?></h6>
						<span><?php the_field('sub_title'); ?></span>
					</a>
				</li>
				
				<?php
				endwhile; wp_reset_postdata(); endif;
				?>
			</ul>
		</div>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>