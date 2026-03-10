/**
 * Template Name: Individual Team Member
 *
 * @package WordPress
 * @subpackage Aavin
 * @since Aavin 1.0
 */
<?php get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main inner-page" role="main">
		<div class="container">
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="OurTeam">
					<div class="ourteamMember">
						
						<div class="ourTeamDetails">
							<h1><?php the_title(); ?></h1>
							<span><?php the_field('sub_title'); ?></span>
							<p><?php the_field('banner_heading'); ?></p>
						</div>
						<div class="ourteamImage">
							<img src="<?php the_field('banner_image'); ?>" alt="single-team-image">
						</div>
					</div>
					<div class="singleTeamContent">
						<?php the_content(); ?>
					</div>
					<div class="vcardDetail">
						<span><img class="alignnone size-full wp-image-1274" src="http://wordpress.aavin.com/wp-content/uploads/2016/05/email.png" alt="email" width="29" height="20"> <?php the_field('email'); ?></span>
						<span><img class="alignnone wp-image-1284" src="http://wordpress.aavin.com/wp-content/uploads/2016/05/phone.png" alt="phone" width="29" height="26"> <?php the_field('phone'); ?></span>
						<a href="<?php the_field('vcard'); ?>" download ><img class="alignnone wp-image-1294" src="http://www.aavin.com/wp-content/uploads/2016/05/vcard.png" alt="vcard" width="29" height="22"> Vcard</a>
					</div>
				<!-- <?php //the_title(); ?>
				<?php //the_field('sub_title'); ?>
				<?php //the_field('banner_heading'); ?>
				<?php //the_field('banner_image'); ?>

				<?php //the_content(); ?>
				<?php //the_field('email'); ?>
				<?php //the_field('phone'); ?>
				<a href="<?php //the_field('vcard'); ?>" download >Vcard</a> -->

			<?php endwhile; ?>
		</div>
	</main>
</div>

<?php get_footer(); ?>