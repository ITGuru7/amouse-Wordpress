<?php
// Register Custom Post Type
function toby_toy_packs() {

	$labels = array(
		'name'                  => _x( 'Toy Packs', 'Post Type General Name', 'tobycreative' ),
		'singular_name'         => _x( 'Toy Pack', 'Post Type Singular Name', 'tobycreative' ),
		'menu_name'             => __( 'Toy Packs', 'tobycreative' ),
		'name_admin_bar'        => __( 'Toy Pack', 'tobycreative' ),
		'archives'              => __( 'Toy Pack Archives', 'tobycreative' ),
		'attributes'            => __( 'Toy Pack Attributes', 'tobycreative' ),
		'parent_item_colon'     => __( 'Parent Item:', 'tobycreative' ),
		'all_items'             => __( 'All Toy Packs', 'tobycreative' ),
		'add_new_item'          => __( 'Add New Toy Pack', 'tobycreative' ),
		'add_new'               => __( 'Add New', 'tobycreative' ),
		'new_item'              => __( 'New Toy Pack', 'tobycreative' ),
		'edit_item'             => __( 'Edit Toy Pack', 'tobycreative' ),
		'update_item'           => __( 'Update Toy Pack', 'tobycreative' ),
		'view_item'             => __( 'View Toy Pack', 'tobycreative' ),
		'view_items'            => __( 'View Toy Packs', 'tobycreative' ),
		'search_items'          => __( 'Search Toy Pack', 'tobycreative' ),
		'not_found'             => __( 'Not found', 'tobycreative' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'tobycreative' ),
		'featured_image'        => __( 'Featured Image', 'tobycreative' ),
		'set_featured_image'    => __( 'Set featured image', 'tobycreative' ),
		'remove_featured_image' => __( 'Remove featured image', 'tobycreative' ),
		'use_featured_image'    => __( 'Use as featured image', 'tobycreative' ),
		'insert_into_item'      => __( 'Insert into item', 'tobycreative' ),
		'uploaded_to_this_item' => __( 'Uploaded to this toy pack', 'tobycreative' ),
		'items_list'            => __( 'Toy Packs list', 'tobycreative' ),
		'items_list_navigation' => __( 'Toy Packs list navigation', 'tobycreative' ),
		'filter_items_list'     => __( 'Filter toy packs list', 'tobycreative' ),
	);
	$rewrite = array(
		'slug'                  => 'toy-packs',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Toy Pack', 'tobycreative' ),
		'description'           => __( 'Toy Packs', 'tobycreative' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-screenoptions',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'toy-packs',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'toy-pack', $args );

}
add_action( 'init', 'toby_toy_packs', 0 );