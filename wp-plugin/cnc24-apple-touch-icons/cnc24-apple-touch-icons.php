<?php
/**
 * Plugin Name: CNC24 Apple Touch Icons
 * Description: Serves common apple-touch-icon*.png files from plugin assets to stop 404s.
 * Version: 1.0.0
 * Author: CNC24
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

function cnc24_ati_add_icon_links_to_head() {
    $base = home_url('/');
    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($base . 'apple-touch-icon.png') . '">' . "\n";
    echo '<link rel="apple-touch-icon-precomposed" href="' . esc_url($base . 'apple-touch-icon-precomposed.png') . '">' . "\n";
}
add_action('wp_head', 'cnc24_ati_add_icon_links_to_head', 1);
