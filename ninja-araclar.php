<?php

/**
 *
 * @link              www.tema.ninja
 * @since             1.0.0
 * @package           Ninja_Araclar
 *
 * @wordpress-plugin
 * Plugin Name:       Ninja Araçlar
 * Plugin URI:        www.tema.ninja/ninja-araclar
 * Description:       Sitenize Süperlig Puan Durumu, Burçlar ve Döviz Kurlarını eklemennizi sağlar
 * Version:           1.0.1
 * Author:            TemaNinja
 * Author URI:        www.tema.ninja
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ninja-araclar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ninja-araclar-activator.php
 */
function activate_ninja_araclar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ninja-araclar-activator.php';
	Ninja_Araclar_Activator::activate();
	Ninja_Araclar_Activator::burclari_yukle();
	Ninja_Araclar_Activator::puantablosu_yukle();
	Ninja_Araclar_Activator::doviz_yukle();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ninja-araclar-deactivator.php
 */
function deactivate_ninja_araclar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ninja-araclar-deactivator.php';
	Ninja_Araclar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ninja_araclar' );
register_deactivation_hook( __FILE__, 'deactivate_ninja_araclar' );
register_activation_hook( __FILE__, 'jal_install' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ninja-araclar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ninja_araclar() {

	$plugin = new Ninja_Araclar();
	$plugin->run();

}


run_ninja_araclar();
