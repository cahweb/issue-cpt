<?php

/*
 *
 * Plugin Name: Common - Issue
 * Description: Custom post type for issues to be used in The Florida Review site
 * Author: Austin Tindle + Alessandro Vecchi
 *
 */

/* Custom Post Type ------------------- */

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Load our CSS
function issue_load_plugin_css() {
    wp_enqueue_style( 'issue-plugin-style', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action( 'admin_enqueue_scripts', 'issue_load_plugin_css' );

// Add create function to init
add_action('init', 'issue_create_type');

// Create the custom post type and register it
function issue_create_type() {
	$args = array(
	      'label' => 'Issues',
	        'public' => true,
	        'show_ui' => true,
	        'capability_type' => 'post',
	        'hierarchical' => false,
	        'rewrite' => array('slug' => 'issue'),
			'menu_icon'  => 'dashicons-book',
	        'query_var' => true,
	        'supports' => array(
	            'title',
	            'editor',
	            'excerpt',
	            'thumbnail')
	    );
	register_post_type( 'issue' , $args );
}

add_action("admin_init", "issue_init");
add_action('save_post', 'issue_save');

// Add the meta boxes to our CPT page
function issue_init() {
	add_meta_box("issue-info-meta", "Issue Information", "issue_meta_info", "issue", "normal", "high");

	add_meta_box("issue-editorial-meta", "Editorial Information", "issue_meta_editorial", "issue", "normal", "high");

}

// Meta box functions
function issue_meta_info() {
	global $post; // Get global WP post var
    $custom = get_post_custom($post->ID); // Set our custom values to an array in the global post var

    // Form markup 
    include_once('views/info.php');
}

// Editorial Information
function issue_meta_editorial() {
	global $post; // Get global WP post var
	global $settings;
    $custom = get_post_custom($post->ID); // Set our custom values to an array in the global post var

    wp_editor($custom['editorial'][0], 'editorial', $settings['md']);

}

// Save our variables
function issue_save() {
	global $post;

	update_post_meta($post->ID, "journal-title", $_POST["journal-title"]);
	update_post_meta($post->ID, "theme", $_POST["theme"]);
	update_post_meta($post->ID, "pub-date", $_POST["pub-date"]);
	update_post_meta($post->ID, "cov-date", $_POST["cov-date"]);
	update_post_meta($post->ID, "vol-num", $_POST["vol-num"]);
	update_post_meta($post->ID, "issue-num", $_POST["issue-num"]);
	update_post_meta($post->ID, "isbn", $_POST["isbn"]);
	update_post_meta($post->ID, "issn", $_POST["issn"]);
	update_post_meta($post->ID, "pur-url", $_POST["pur-url"]);
	update_post_meta($post->ID, "editorial", $_POST["editorial"]);


}

// Settings array. This is so I can retrieve predefined wp_editor() settings to keep the markup clean
$settings = array (
	'sm' => array('textarea_rows' => 3),
	'md' => array('textarea_rows' => 6),
);


?>