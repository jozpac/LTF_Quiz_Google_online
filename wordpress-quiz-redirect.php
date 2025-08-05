<?php
/*
Template Name: Quiz Redirect
*/

// Get the quiz URL (you can set this as a custom field or hardcode it)
$base_quiz_url = get_field('quiz_url') ?: 'YOUR_REPLIT_URL_HERE';

// Preserve URL parameters from META ads
$current_params = $_SERVER['QUERY_STRING'] ?? '';

// Add WordPress tracking parameters
$tracking_params = array(
    'source' => 'wordpress',
    'ref' => get_permalink(),
    'utm_source' => 'viralprofits_wp',
    'utm_medium' => 'website',
    'utm_campaign' => 'youtube_quiz'
);

// Build final URL with all parameters
if ($current_params) {
    // If there are existing params from META ads, preserve them
    $quiz_url_with_params = $base_quiz_url . '?' . $current_params . '&' . http_build_query($tracking_params);
} else {
    // No existing params, just add tracking
    $quiz_url_with_params = add_query_arg($tracking_params, $base_quiz_url);
}

// Redirect to quiz
wp_redirect($quiz_url_with_params, 302);
exit;
?>