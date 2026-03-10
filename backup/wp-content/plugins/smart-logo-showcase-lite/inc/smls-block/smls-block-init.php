<?php
/**
 * Handler for [smls_guten_block] shortcode
 *
 * @param $atts
 *
 * @return string
 */
function smls_block_handler($atts)
{
	$atts = shortcode_atts([
		'heading' => __('Smart Logo Block Title'),
		'heading_tag' => 'h2',
		'smart_id' => '',
	], $atts, 'smls_guten_block');

	return smls_block_renderer($atts[ 'heading' ],$atts[ 'heading_tag' ],$atts[ 'smart_id' ]);
}

/**
 * Register Shortcode
 */
add_shortcode('smls_guten_block', 'smls_block_handler');

/**
 * Handler for post title block
 * @param $atts
 *
 * @return string
 */
function smls_block_render_handler($atts)
{
	return smls_block_renderer($atts[ 'heading' ],$atts[ 'heading_tag' ],$atts[ 'smart_id' ]);
}

/**
 * Output the post title wrapped in a heading
 *
 * @param int $smart_id The post ID
 * @param string $heading Allows : h2,h3,h4 only
 *
 * @return string
 */
function smls_block_renderer($heading,$heading_tag,$smart_id)
{	
	$ret = '';
	if(!empty($heading)){
		$ret .= "<$heading_tag>$heading</$heading_tag>";
	}
	if($smart_id!=null){
		$title = do_shortcode('[smls id="'.$smart_id.'"]');
		$ret .= "$title";
	}
	return $ret;
}

/**
 * Register block
 */
add_action('init', function () {
	// Skip block registration if Gutenberg is not enabled/merged.
	if (!function_exists('register_block_type')) {
		return;
	}
	$dir = dirname(__FILE__);

	wp_enqueue_style( 'smls-frontend-style', SMLS_CSS_DIR . '/smls-frontend-style.css', false, SMLS_VERSION );
	wp_enqueue_style( 'smls-block-editor', plugins_url('smls-block.css', __FILE__), false, SMLS_VERSION );

	$index_js = 'smls-block.js';
	wp_register_script(
		'smls-block-script',
		plugins_url($index_js, __FILE__),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor'
		),
		filemtime("$dir/$index_js")
	);

	$smls_logos_array = get_smls_logos();
	wp_localize_script( 'smls-block-script', 'SMLS_logos_array', $smls_logos_array);

	register_block_type('smls-display-block/smls-widget', array(
		'editor_script' => 'smls-block-script',
		'render_callback' => 'smls_block_render_handler',
		'attributes' => [			
			'heading' => [
				'type' => 'string',
				'default' => __('Smart Logo Block Title')
			],
			'heading_tag' => [
				'type' => 'string',
				'default' => 'h2'
			],
			'smart_id' => [
				'type' => 'string',
				'default' => ''
			],
		]
	));
});

function get_smls_logos(){
	$args = array('post_type'=>'smartlogo',
		'post_status'=>'publish',
		'posts_per_page'=>'25'
	);
    // The Query
	$the_query = new WP_Query( $args );

	$smartlogo = array(array('value'=>0,'label'=>__('Select Smart Logo')));

    // The Loop
	if ( $the_query->have_posts() ) {
		while($the_query->have_posts()){
			$the_query->the_post();
			$smartlogo[] = array('value'=>get_the_ID(), 'label'=> get_the_title());
		}
	}

	return $smartlogo;
}