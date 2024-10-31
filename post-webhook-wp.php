<?php

/**
 * Plugin Name: Post Webhook
 * Plugin URI: https://jonathan-wright.com
 * Author: Jonathan Wright
 * Author URI: https://jonathan-wright.com/about-me
 * Description: Automate your content workflow by automatically sending post and page data to external services
 * Version: 1.0.0
 * Licence: GPLv2 or Later
 * Licence URL: https://www.gnu.org/licenses/gpl-3.0.txt
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This program. If not, see https://www.gnu.org/licenses/gpl-3.0.txt.
*/

// Basic Security.
defined('ABSPATH') or die('Unauthorised Access');

// Load required files
include(plugin_dir_path(__FILE__) . 'admin/settings.php');

// Hook works for all post status. Updated, deleted and publish.
add_action('wp_after_insert_post', 'wppwh_save_post_webhook', 10, 4);
add_action('publish_to_trash', 'wppwh_trash_post_webhook');

/**
 * Callback function for the hook to run the processes of sending to API URL.
 *
 * @param int   $post_id Post ID.
 * @param mixed $post    Post Object.
 *
 * @return void
 */

function wppwh_save_post_webhook($post_id, $post)
{

    // Check to see if autosaving, revision pre-save etc., if so ignore
    if ($post->post_status !== 'publish') {
        return;
    }

    // Setup the variables to use in the application.
    $message       = 'Post #' . $post_id . ' has been ' . $post->post_status .  'ed with title - ' . $post->post_title;
    $author        = get_the_author_meta('display_name', $post->post_author);
    $post_ID       = $post->ID;
    $post_title    = $post->post_title;
    $post_date     = $post->post_date;
    $post_modified = $post->post_modified;
    $post_guid     = $post->guid;
    $post_slug     = $post->post_name;
    $permalink     = get_the_permalink($post->ID);
    $post_type     = $post->post_type;
    $post_status   = $post->post_status;
    $categories    = wppwh_get_categories($post);
    $tags          = wppwh_get_tags($post);
    $word_count    = wppwh_get_wordcount($post);

    // API URL.
    $url = get_option('webhook_url_field_name');

    $args = array(
        'body' => array(
            'message'               => $message,
            'author_display_name'   => $author,
            'post_ID'               => $post_ID,
            'post_title'            => $post_title,
            'post_date'             => $post_date,
            'post_modified'         => $post_modified,
            'post_guid'             => $post_guid,
            'post_slug'             => $post_slug,
            'permalink'             => $permalink,
            'post_type'             => $post_type,
            'post_status'           => $post_status,
            'post_categories'       => $categories,
            'post_tags'             => $tags,
            'post_word_count'       => $word_count,
        )
    );

    // Send the variables to the URL.
    $sending = wp_remote_post($url, $args);
}

/**
 * Get all the post categories and make a comma seperated string out of them.
 *
 * @param mixed $post Post Object.
 *
 * @return $terms_string All categories in a comma seperated string.
 */
function wppwh_get_categories($post)
{
    $term_obj_list = get_the_terms($post->ID, 'category');
    $terms_string  = join(', ', wp_list_pluck($term_obj_list, 'name'));

    return $terms_string;
}

/**
 * Get all the post tags and make a comma seperated string out of them.
 *
 * @param mixed $post Post Object.
 *
 * @return $terms_string All tags in a comma seperated string.
 */

function wppwh_get_tags($post)
{
    $term_obj_list = get_the_tags($post->ID, 'tag');
    $terms_string  = join(', ', wp_list_pluck($term_obj_list, 'name'));

    return $terms_string;
}

/**
 * Get the post/page, remove any unnecessary tags and then perform the word count
 * 
 * @param \WP_Post $post
 * @return int
 */
function wppwh_get_wordcount($post)
{
    $wppwh_wordcount = str_word_count(strip_tags(strip_shortcodes($post->post_content)));

    return $wppwh_wordcount;
}

// Capture details for posts that were published but now deleted
function wppwh_trash_post_webhook($post)
{
    // Setup the variables to use in the application.
    $message       = 'Post #' . $post->ID . ' has been ' . $post->post_status .  'ed with title - ' . $post->post_title;
    $post_ID       = $post->ID;
    $post_modified = $post->post_modified;
    $post_guid     = $post->guid;
    $post_status   = $post->post_status;

    // API URL.
    $url = get_option('webhook_url_field_name');

    $args = array(
        'body' => array(
            'message'       => $message,
            'post_ID'       => $post_ID,
            'post_modified' => $post_modified,
            'post_guid'     => $post_guid,
            'post_status'   => $post_status,
        )
    );

    // Send the variables to the URL.
    $sending = wp_remote_post($url, $args);
}

/**
 * End of main code
 */
