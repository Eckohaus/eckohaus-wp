<?php
/**
 * Plugin Name: Eckohaus Volumetric Viewer
 * Description: Renders 3D volumetric data exported from Eckohaus scientific repos.
 * Version: 0.1.0
 * Author: Eckohaus
 * License: MIT
 */

// Block direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register scripts.
 */
function eckohaus_vol_register_scripts() {

    $plugin_url = plugin_dir_url( __FILE__ );

    // Core loader (fetches JSON, basic messaging).
    wp_register_script(
        'eckohaus-vol-core',
        $plugin_url . 'assets/js/viewer-core.js',
        array(),
        '0.1.0',
        true
    );

    // Plotly renderer.
    wp_register_script(
        'eckohaus-vol-plotly',
        $plugin_url . 'assets/js/viewer-plotly.js',
        array( 'eckohaus-vol-core' ),
        '0.1.0',
        true
    );

    // Three.js renderer (stub for now).
    wp_register_script(
        'eckohaus-vol-three',
        $plugin_url . 'assets/js/viewer-three.js',
        array( 'eckohaus-vol-core' ),
        '0.1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'eckohaus_vol_register_scripts' );

/**
 * Shortcode handler.
 *
 * Usage:
 * [eckohaus_volume url="https://raw.githubusercontent.com/..." renderer="plotly"]
 */
function eckohaus_vol_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'url'       => '',
            'renderer'  => 'plotly',
            'container' => 'eckohaus-vol-container',
        ),
        $atts,
        'eckohaus_volume'
    );

    // Always enqueue the core script.
    wp_enqueue_script( 'eckohaus-vol-core' );

    // Enqueue renderer based on attribute.
    if ( $atts['renderer'] === 'three' ) {
        wp_enqueue_script( 'eckohaus-vol-three' );
    } else {
        // Default to Plotly.
        wp_enqueue_script( 'eckohaus-vol-plotly' );
    }

    // Pass data to JS.
    wp_localize_script(
        'eckohaus-vol-core',
        'EckohausVolData',
        array(
            'url'       => esc_url_raw( $atts['url'] ),
            'renderer'  => $atts['renderer'],
            'container' => $atts['container'],
        )
    );

    // Output viewer container.
    ob_start();
    ?>
    <div id="<?php echo esc_attr( $atts['container'] ); ?>" class="eckohaus-vol-container">
        <!-- Eckohaus Volumetric Viewer will render here -->
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'eckohaus_volume', 'eckohaus_vol_shortcode' );
