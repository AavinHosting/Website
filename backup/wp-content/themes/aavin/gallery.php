<?php
/**
 * Template Name: Gallery
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
<div id="main-content" class="main-content">

  <div class="WhiteContainerGallery">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<h1>
				Gallery.php
			</h1>
			<?php

$url= $_SERVER['REQUEST_URI'];
$galleryurl=explode('=',$url);
$attachment = get_post( $galleryurl[1] );
echo $title=$attachment->post_title;
echo'<br>';
echo $desc= $attachment->post_content;
//echo'<pre>';
//print_r($attachment);
//echo'</pre>';
 // echo'gg'. $_POST['imgaid'];
 $image_attributes = wp_get_attachment_image_src( $attachment_id = $galleryurl[1] );

if ( $image_attributes ) : ?>
    <img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
<?php endif; ?>
				
			
		</div><!-- #content -->
	</div><!-- #primary -->
    </div>
</div><!-- #main-content -->

<?php
get_footer();
