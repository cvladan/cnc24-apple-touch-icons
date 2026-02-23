<?php
/**
 * Plugin Name:     CNC24 Apple Touch Icons
 * Plugin URI:      https://github.com/cvladan/cnc24-apple-touch-icons
 * Description:     Serves common apple-touch-icon*.png files from plugin assets to stop 404s.
 * Version:         1.0.0
 * Author:          Vladan Colovic
 * Author URI:      https://github.com/cvladan/cnc24-apple-touch-icons
 * License:         GPL-2.0-or-later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     cnc24-apple-touch-icons
 * Domain Path:     /languages
 * Update URI:      false
 * Requires PHP:    8.0
 */

if (!defined('ABSPATH')) {
    exit;
}

function cnc24_ati_match_icon_request($path) {
    if (!is_string($path)) {
        return null;
    }

    $pattern = '#^/apple-touch-icon(?:-(\d+)x(\d+))?(?:-precomposed)?\.png$#i';
    if (!preg_match($pattern, $path, $matches)) {
        return null;
    }

    $allowed_sizes = array(57, 60, 72, 76, 114, 120, 144, 152, 167, 180);
    $size = 180;

    if (!empty($matches[1]) && !empty($matches[2]) && $matches[1] === $matches[2]) {
        $requested_size = (int) $matches[1];
        if (in_array($requested_size, $allowed_sizes, true)) {
            $size = $requested_size;
        }
    }

    return $size;
}

function cnc24_ati_serve_icon_if_requested() {
    if (is_admin()) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
    $path = parse_url($request_uri, PHP_URL_PATH);
    $size = cnc24_ati_match_icon_request($path);

    if ($size === null) {
        return;
    }

    $icon_file = plugin_dir_path(__FILE__) . 'icons/apple-touch-icon-' . $size . 'x' . $size . '.png';
    if (!file_exists($icon_file)) {
        status_header(404);
        exit;
    }

    status_header(200);
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=31536000, immutable');
    header('Content-Length: ' . filesize($icon_file));
    readfile($icon_file);
    exit;
}
add_action('init', 'cnc24_ati_serve_icon_if_requested', 0);

function cnc24_ati_customize_site_icon_meta_tags($meta_tags) {
    $base = home_url('/');
    $custom_tags = array(
        '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($base . 'apple-touch-icon.png') . '" />',
        '<link rel="apple-touch-icon-precomposed" href="' . esc_url($base . 'apple-touch-icon-precomposed.png') . '" />',
    );

    if (is_array($meta_tags)) {
        $filtered_tags = array();
        foreach ($meta_tags as $meta_tag) {
            if (is_string($meta_tag) && preg_match('/rel=["\']apple-touch-icon(?:-precomposed)?["\']/i', $meta_tag)) {
                continue;
            }
            $filtered_tags[] = $meta_tag;
        }
        return array_merge($filtered_tags, $custom_tags);
    }

    if (!is_string($meta_tags)) {
        return $meta_tags;
    }

    if ($meta_tags === '') {
        return implode("\n", $custom_tags) . "\n";
    }

    $meta_tags = (string) preg_replace('/^[^\n\r]*rel=["\']apple-touch-icon(?:-precomposed)?["\'][^\n\r]*(?:\r?\n)?/mi', '', $meta_tags);
    $meta_tags = rtrim($meta_tags, "\r\n") . "\n";
    $meta_tags .= implode("\n", $custom_tags) . "\n";
    return $meta_tags;
}
add_filter('site_icon_meta_tags', 'cnc24_ati_customize_site_icon_meta_tags', 10, 1);
