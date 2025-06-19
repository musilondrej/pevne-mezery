<?php
/**
 * Simple bootstrap for PHPUnit tests without WordPress
 */

// Set error reporting
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load plugin main file
require_once __DIR__ . '/../trunk/pevne-mezery.php';

// Define WordPress constants for testing
define('ABSPATH', __DIR__ . '/');
define('WP_CONTENT_DIR', __DIR__ . '/content');

// Mock WordPress functions that might be used
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Mock implementation
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Mock implementation  
        return true;
    }
}

if (!function_exists('wp_cache_get')) {
    function wp_cache_get($key, $group = '') {
        return false;
    }
}

if (!function_exists('wp_cache_set')) {
    function wp_cache_set($key, $value, $group = '', $expire = 0) {
        return true;
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return dirname($file) . '/';
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = 'default') {
        return $text;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}
