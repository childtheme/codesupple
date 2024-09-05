<?php
/**
 * Plugin Name: Add Twitter Handle to Share
 * Description: Automatically adds 'via @xxxx' to Twitter share links.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 */

// Hook to modify the Twitter share URL
function add_twitter_handle_via( $url ) {
    $twitter_handle = 'xxxx'; // Change 'xxxx' to your Twitter handle
    if (strpos($url, 'twitter.com/share') !== false) {
        $url = add_query_arg('via', $twitter_handle, $url);
    }
    return $url;
}

// Apply the filter to the Twitter sharing link
add_filter( 'sharing_permalink', 'add_twitter_handle_via', 10, 1 );