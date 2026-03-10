<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Aavin
 * @since Aavin 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="footer" role="contentinfo">
			<div class="container">
				<?php dynamic_sidebar('footer'); ?>
			</div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>

<!-- js-flies -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/slick.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/custom.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/browser_and_os_classes.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/placeholders.min.js"></script>

<script>
$("document").ready(function() {

    //$(".rbs_gallery_button a").eq(0).trigger( "click" );

    $('.rbs-imges-container > .rbs-img').each(function(index, value){

    	if((index+1)%2){

    		//$(this).css('opacity','0');
 	   	}

    });

});


</script>
</body>
</html>
