<?php
/*
   Plugin Name: Last Post Edited Author
   Version: 1.0.0
   Description: This is a simple plugin that adds the last user/author of a page or post and the time and date of that edit to the Posts/Pages table in your Dashboard
   Plugin URI: http://www.mattroyal.co.za/plugins/last-post-edited-author/
   Author: Matt Royal
   Author URI: http://www.mattroyal.co.za/
   Requires at least: 3.8
   Tested up to: 4.0
   Text Domain: last-post-edited-author
   License: GPLv3
  */

// Thanks @tomontoast (http://thomas.milburn.info/) for patching get_the_modified_author() function to core. find him at WordPress.Org.

if ( !defined( 'ABSPATH' ) ) exit;

function royal_lpea_i18n_init() {
		$pluginDir = dirname(plugin_basename(__FILE__));
		load_plugin_textdomain('last-post-edited-author', false, $pluginDir . '/lang/');
	}

royal_lpea_i18n_init();


///////////////////////////////////////////////////////////////////
 // Add new colomn for Last Author //
//////////////////////////////////////////////////////////////////
function royal_lpea_add_last_author_colomn($columns) {
	
	$new = array();
	
  	foreach($columns as $key => $title) {
		if ($key=='author') // Change 'author' to reposition Last Edited colomn. (Example 'tags' would add it before the tags colomn)
    	$new['last_author'] = 'Last Edited'; // Our New Colomn Name
		$new[$key] = $title;
	}
	// unset($new['tags']); 
    return $new;
}
 

///////////////////////////////////////////////////////////////////
 // Show the Last Author //
//////////////////////////////////////////////////////////////////
function royal_lpea_add_last_author($column_name, $post_ID) {
    if ($column_name == 'last_author') {
    	echo '<span class="last_author_detail">' . get_the_modified_author() . '<br />'; // The last edit Author
		echo  the_modified_date('Y-m-d') .' at '; // The last edit date
		echo  the_modified_date('g:i a') . '</span>'; // the last edit time
    }
}


///////////////////////////////////////////////////////////////////
 // resize & style Last Author column //
//////////////////////////////////////////////////////////////////

function royal_lpea_last_author_column_resize() { ?>
    <style type="text/css">
        .fixed .column-last_author {
            width: 14%;
        }
		
		.last_author_detail {
			color:#999;
		}
    </style>
<?php }

// For Posts
add_filter('manage_posts_columns', 'royal_lpea_add_last_author_colomn');
add_action('manage_posts_custom_column', 'royal_lpea_add_last_author', 10, 2);

// For Pages
add_filter('manage_pages_columns', 'royal_lpea_add_last_author_colomn');
add_action('manage_pages_custom_column', 'royal_lpea_add_last_author', 10, 2);

// For Custom Post Types
// add_filter('manage_${post_type}_posts_columns', 'royal_lpea_add_last_author_colomn');
// add_action('manage_$post_type_posts_custom_column', 'royal_lpea_add_last_author', 10, 2);

add_action( 'admin_enqueue_scripts', 'royal_lpea_last_author_column_resize' );