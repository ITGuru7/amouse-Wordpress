<?php

// Add Shortcode
function toby_sitemap( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'count' => '-1',
			'title' => 'Pages',
			'type' => 'page', //get by post type
			'posts'	=> '',	//show specific posts by id
			'exclude' => '', //exclude posts
		),
		$atts,
		'toby_sitemap'
	);

	$additional_args = array();
	$post_ids = array();
	$exclude_post_ids = array();
	$meta_query = array();
	
	//Convert Post IDs to array
	if ( !empty( $atts['posts'] ) ) {
	
		$post_ids = explode(",", $atts['posts']);
		
		foreach($post_ids as $index => $word){
			$post_ids[$index] = trim($word);
		}
		
	}
	
	$post_ids = array_filter( $post_ids );
	
	//Convert Post IDs to array
	if ( !empty( $atts['exclude'] ) ) {
	
		$exclude_post_ids = explode(",", $atts['exclude']);
		
		foreach($exclude_post_ids as $index => $word){
			$exclude_post_ids[$index] = trim($word);
		}
		
	}
	
	$exclude_post_ids = array_filter( $exclude_post_ids );
	
	if ( $exclude_post_ids ) {
		$additional_args['post__not_in'] = $exclude_post_ids;
	}
	
	$additional_args['status'] = 'publish';
	$additional_args['orderby'] = 'title';
	
	$query_posts = toby_get_custom_posts( $atts['type'], $atts['count'], "ASC", $additional_args, $post_ids );
		
	ob_start();

		?>
			<div class="toby-sitemap-container">
				<?php
				$post_count = count( $query_posts );
				
				if ( count( $post_count ) ) {	
					echo ( $atts['title'] ? '<h3>' . $atts['title'] . ' (' . $post_count .')</h3>' : '' );
					echo '<ul class="toby-sitemap-list">';
					foreach ($query_posts as $post) {
						setup_postdata($post);
				
						$title = get_the_title( $post->ID );
						$link = get_the_permalink( $post->ID );

						echo '<li><a href="' . $link . '">' . $title . '</a></li>';
					} 
					echo '</ul>';
				}
				?>
			</div>
		<?php
		
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
	
}
add_shortcode( 'toby_sitemap', 'toby_sitemap' );

