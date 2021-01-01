<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://autobrunei.com
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Autobrunei Plugin
 * Description:       Plugin for car listing.
 * Version:           1.0.0
 * Author:            Sand Eaters
 * Author URI:        http://autobrunei.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       autobrunei-plugin
 * Domain Path:       /languages
 */

namespace Autobrunei;

use Autobrunei\Utils\Activator;
use Autobrunei\Utils\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// We load Composer's autoload file
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-auto-brunei-activator.php
 */
function activate_auto_brunei() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-auto-brunei-deactivator.php
 */
function deactivate_auto_brunei() {
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, '\Autobrunei\activate_auto_brunei' );
register_deactivation_hook( __FILE__, '\Autobrunei\deactivate_auto_brunei' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_auto_brunei() {
	$plugin = new Main();
	$plugin->run();
}
run_auto_brunei();
