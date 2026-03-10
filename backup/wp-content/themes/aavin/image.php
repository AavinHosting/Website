<?php
/**
 * The template for displaying image attachments
 *
 * @package WordPress
 * @subpackage Aavin
 * @since Aavin 1.0
 */

get_header(); ?>

	<div id="main-content" class="main-content">

  <div class="WhiteContainerGallery">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php

$attachment = get_post( $_POST['gallaryid'] );
$image_attributes = wp_get_attachment_image_src( $attachment_id = $_POST['gallaryid'] );
?>
<h2><?php echo $attachment->post_title; ?></h2>
<?php

if ( $attachment->guid ) : ?>
   <img src="<?php echo $attachment->guid; ?>" />
<?php endif; ?>


<p><?php echo $desc= $attachment->post_content; ?></p>
				
			
		</div><!-- #content -->
	</div><!-- #primary -->
    </div>
</div><!-- #main-content -->

<?php
get_footer();?>

