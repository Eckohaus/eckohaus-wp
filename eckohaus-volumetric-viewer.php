<?php
/**
 * Plugin Name:       Eckohaus Volumetric Viewer
 * Plugin URI:        https://eckohaus.blog
 * Description:       Renders 3D volumetric data exported from Eckohaus scientific repos.
 * Version:           0.1.0
 * Author:            Eckohaus Ltd
 * Author URI:        https://eckohaus.co.uk
 * Text Domain:       eckohaus-volumetric-viewer
 *
 * Co-Author: system operator <wanda@openai.com>
 * Co-Author: system administrator <Corvin Nehal Dhali> <info@eckohaus.co.uk>
 *
 * @package Eckohaus_Volumetric_Viewer
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// Plugin constants
// -----------------------------------------------------------------------------

if ( ! defined( 'ECKOHAUS_VOL_VERSION' ) ) {
    define( 'ECKOHAUS_VOL_VERSION', '0.1.0' );
}

if ( ! defined( 'ECKOHAUS_VOL_PLUGIN_DIR' ) ) {
    define( 'ECKOHAUS_VOL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ECKOHAUS_VOL_PLUGIN_URL' ) ) {
    define( 'ECKOHAUS_VOL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// -----------------------------------------------------------------------------
// Includes
// -----------------------------------------------------------------------------

require_once ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-assets.php';

if ( file_exists( ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-fetcher.php' ) ) {
    require_once ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-fetcher.php';
}

if ( file_exists( ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-renderer.php' ) ) {
    require_once ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-renderer.php';
}

if ( file_exists( ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-shortcode.php' ) ) {
    require_once ECKOHAUS_VOL_PLUGIN_DIR . 'includes/class-eckohaus-vol-shortcode.php';
}

// -----------------------------------------------------------------------------
// Bootstrap
// -----------------------------------------------------------------------------

function eckohaus_vol_bootstrap() {

    // Initialise assets
    if ( class_exists( 'Eckohaus_Vol_Assets' ) ) {
        Eckohaus_Vol_Assets::init();
    }

    // IMPORTANT FIX:
    // The shortcode class uses __construct(), so it MUST be instantiated.
    // (There is NO init() method in that class.)
    if ( class_exists( 'Eckohaus_Vol_Shortcode' ) ) {
        new Eckohaus_Vol_Shortcode();
    }

    // Initialise optional fetcher class
    if ( class_exists( 'Eckohaus_Vol_Fetcher' ) && method_exists( 'Eckohaus_Vol_Fetcher', 'init' ) ) {
        Eckohaus_Vol_Fetcher::init();
    }

    // Initialise optional renderer class
    if ( class_exists( 'Eckohaus_Vol_Renderer' ) && method_exists( 'Eckohaus_Vol_Renderer', 'init' ) ) {
        Eckohaus_Vol_Renderer::init();
    }
}

add_action( 'plugins_loaded', 'eckohaus_vol_bootstrap' );
