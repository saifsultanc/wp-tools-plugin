<?php 
/*
Plugin Name: Tools App
Plugin URI: -
Description: Some fun tools to manage your wordpress
Version: 1.0
Author: Saif Sultan
Author URI: https://www.saifsultan.co
License: GPL-2.0+
*/

// log when a new post is saved
add_action('save_post', 'log_when_saved');
function log_when_saved() {

  // ignore revised and autosaved posts
  if ( ! ( wp_is_post_revision( $post_id) ) || ! ( wp_is_post_autosave( $post_id )))
    return;

  $post_log = plugin_dir_path( __FILE__ ) . 'post_log.txt';
  $message = get_the_title( $post_id ) . ' was just saved';

  if ( file_exists( $post_log ) ) {

    $file = fopen( $post_log, 'a' );
    fwrite($file, $message."\n");
  }
  else {
    
    $file = fopen( $post_log, 'w' );
    fwrite( $file, $message."\n");
  }
}

add_action('template_redirect', 'members_only');
function members_only() {
  if ( is_page('members-only') && ! is_user_logged_in() ) {
    wp_redirect( home_url() );
    die();
  }
}

add_action( 'gform_after_create_post', 'set_post_content', 10, 3 );
function set_post_content( $post_id, $entry, $form ) {
 
    //get post
    $post = get_post( $post_id );
 
    //change post content
    $post->post_content = $post->post_content . "\n\nPost created using Gravity Forms";
 
    //update post
    wp_update_post( $post );
}

add_action( 'gform_after_create_post', 'gform_post_log', 10, 3 );
function gform_post_log( $post_id, $entry, $form ) {

  $post_log = plugin_dir_path( __FILE__ ) . 'gf_post_log.txt';

  $message = get_the_title( $post_id ) . ' was just saved';

  if ( file_exists( $post_log ) ) {

    $file = fopen( $post_log, 'a' );
    fwrite($file, $message."\n");
  }
  else {
    
    $file = fopen( $post_log, 'w' );
    fwrite( $file, "LIST OF POSTS CREATED WITH GRAVITY FORMS:\n");
    fwrite( $file, $message."\n");
  }
}
?>