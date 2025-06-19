<?php
/**
 * PHPStan bootstrap file for WordPress functions
 */

// Define WordPress constants that PHPStan needs
if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/wordpress/');
}

if (!defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 3600);
}

// Mock WordPress functions for static analysis
if (!function_exists('wp_cache_flush')) {
    function wp_cache_flush() {}
}

if (!function_exists('get_transient')) {
    function get_transient($transient) {
        return false;
    }
}

if (!function_exists('set_transient')) {
    function set_transient($transient, $value, $expiration = 0) {
        return true;
    }
}

if (!function_exists('delete_transient')) {
    function delete_transient($transient) {
        return true;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($hook_name, $value, ...$args) {
        return $value;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {
        return true;
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return dirname($file) . '/';
    }
}

if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {
        return basename($file);
    }
}
