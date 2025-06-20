<?php
/*
Plugin Name: Pevné mezery
Description: Automatické doplnění pevných mezer podle českých typografických pravidel s podporou WooCommerce a ACF.
Version: 1.3.1
Requires PHP: 8.0
Author: MusilTech
Author URI: https://musiltech.com
Plugin URI: https://github.com/musiltech/pevne-mezery
Text Domain: pevne-mezery
Domain Path: /languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace MusilTech\PevneMezery;

if (! defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Ensure HOUR_IN_SECONDS is defined
if (! defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 3600);
}

// Load plugin text domain
add_action('plugins_loaded', function() {
    load_plugin_textdomain('pevne-mezery', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Include necessary classes
require_once plugin_dir_path(__FILE__) . 'includes/class-fixed-spaces.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-content-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cache-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-utils.php';    

require_once plugin_dir_path(__FILE__) . 'integrations/class-acf-support.php';
require_once plugin_dir_path(__FILE__) . 'integrations/class-woocommerce-support.php';

// Initialize the plugin
new PevneMezery();
