<?php

namespace Ada_Aba;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.adadevelopersacademy.org
 * @since             1.0.0
 * @package           Ada_Aba
 *
 * @wordpress-plugin
 * Plugin Name:       Ada Build Analytics
 * Plugin URI:        https://www.adadevelopersacademy.org
 * Description:       Adds functionality to allow learners to register to track their progress in Ada Build.
 * Version:           1.0.0
 * Author:            Ada Developers Academy
 * Author URI:        https://www.adadevelopersacademy.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ada-aba
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

# requires, etc. for pdf libraries
define('Ada_Aba\\FPDF_FONTPATH', __DIR__ . '/public/font/');
require_once('src/fpdf.php');
require_once('src/fpdi/src/autoload.php');
require_once('src/Parsedown.php');


const ADA_ABA_PLUGIN_NAME = 'ada-aba';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('ADA_ABA_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 */
function activate_ada_aba()
{
  require_once plugin_dir_path(__FILE__) . 'includes/activator.php';
  Includes\Activator::activate(ADA_ABA_PLUGIN_NAME);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 */
function deactivate_ada_aba()
{
  require_once plugin_dir_path(__FILE__) . 'includes/deactivator.php';
  Includes\Deactivator::deactivate(ADA_ABA_PLUGIN_NAME);
}

register_activation_hook(__FILE__, '\Ada_Aba\activate_ada_aba');
register_deactivation_hook(__FILE__, '\Ada_Aba\deactivate_ada_aba');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ada_aba()
{

  $plugin = new Includes\Core(ADA_ABA_PLUGIN_NAME);
  $plugin->run();
}
run_ada_aba();
