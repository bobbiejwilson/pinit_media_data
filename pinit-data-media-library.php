<?php
   /*
   Plugin Name: Pinit Data for Media Library
   Plugin URI: http://www.bobbiejwilson.com/pinit-data-media-library
   Description: A plugin to add custom fields for pinit data in the media library that also displays with the media in a post or page.
   Version: 0.1
   Author: Bobbie Wilson
   Author URI: http://www.bobbiejwilson.com
   License: GPL2
   */


/* Add custom fields to attachment */
function pinit_image_add_custom_fields($form_fields, $post) {
	$form_fields["pinit_source_url"] = array(
		'label' => __("Source URL"),
		'input' => "text",
		'value' => get_post_meta($post->ID, "pinit_source_url", true),
		'helps' => __("Insert the URL of the post you would like this image to link to."),
		'application' => 'image'
	);
	$form_fields["pinit_pin_description"] = array(
		'label' => __("Pin Description"),
		'input' => "text",
		'value' => get_post_meta($post->ID, "pinit_pin_description", true),
		'helps' => __("Describe the pin"),
		'application' => 'image'
	);
	$form_fields["pinit_repin_id"] = array(
		'label' => __("Repin ID"),
		'input' => "text",
		'value' => get_post_meta($post->ID, "pinit_repin_id", true),
		'helps' => __("Pin ID for repin connection"),
		'application' => 'image'
	);		
	return $form_fields;
}
add_filter("attachment_fields_to_edit", "pinit_image_add_custom_fields", null, 2);

/* Save custom fields value */
function pinit_image_save_custom_fields($post, $attachment) {
	if(isset($attachment['pinit_source_url'])) {
		update_post_meta($post['ID'], 'pinit_source_url', $attachment['pinit_source_url']);
	} else {
		delete_post_meta($post['ID'], 'pinit_source_url');
	}
if(isset($attachment['pinit_pin_description'])) {
		update_post_meta($post['ID'], 'pinit_pin_description', $attachment['pinit_pin_description']);
	} else {
		delete_post_meta($post['ID'], 'pinit_pin_description');
	}
if(isset($attachment['pinit_repin_id'])) {
		update_post_meta($post['ID'], 'pinit_repin_id', $attachment['pinit_repin_id']);
	} else {
		delete_post_meta($post['ID'], 'pinit_repin_id');
	}
	return $post;
}
add_filter("attachment_fields_to_save", "pinit_image_save_custom_fields", null , 2);

// Add a column to the edit post list
add_filter( 'manage_media_columns', 'add_new_columns');

/**
 * Add new columns to the post table
 *
 * @param Array $columns - Current columns on the list post
 */
function add_new_columns( $columns ) {
 	return array_merge($columns, 
              array('pinit_source_url' => __('Source URL'),
                    'pinit_pin_description' =>__( 'Pin Description'),
		'pinit_repin_id' =>__( 'Repin ID')));
}

// Add action to the manage post column to display the data
add_action( 'manage_media_custom_column' , 'custom_columns', null, 2 );

/**
 * Display data in new columns
 *
 * @param  $column Current column
 *
 * @return Data for the column
 */
function custom_columns( $column, $attachment ) {
	

	switch ( $column ) {
      case 'pinit_source_url':
        echo get_post_meta( $attachment, 'pinit_source_url' , true );
        break;

      case 'pinit_pin_description':
        echo get_post_meta( $attachment, 'pinit_pin_description' , true ); 
        break;

      case 'pinit_repin_id':
	echo get_post_meta( $attachment, 'pinit_repin_id', true );
	break;
    }
}
//Add the meta to the images in posts
function pinit_data( $html, $id ) {

	
    
		$pin_description = get_post_meta($id, 'pinit_pin_description', true);
		$pin_source = get_post_meta($id, 'pinit_source_url', true);
		$pin_repin = get_post_meta($id, 'pinit_repin_id', true);
		return str_replace('<img', '<img data-pin-description="' . $pin_description . '" data-pin-url="' .$pin_source.'" data-pin-id="' .$pin_repin.'"'  , $html);      

}
add_filter( 'media_send_to_editor', 'pinit_data', 15, 2 );


function pinit_load_detect() {
	wp_register_script( 'pinit-detect-script', plugins_url( '/js/pinit-loadjs.js', __FILE__ ), array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'pinit-detect-script' );
}
add_action( 'wp_enqueue_scripts', 'pinit_load_detect' );

?>