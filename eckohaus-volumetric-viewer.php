<?php
/**
 * Plugin Name: Eckohaus Volumetric Viewer
 * Plugin URI: https://eckohaus.blog
 * Description: Renders 3D volumetric data exported from Eckohaus scientific repos.
 * Version: 0.1.0
 * Author: Eckohaus
 * Author URI: https://eckohaus.co.uk
 *
 * Co-Author: system operator <wanda@openai.com>
 * Co-Author: system administrator <Corvin Nehal Dhali> <info@eckohaus.co.uk>
 *
 * License: MIT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register plugin scripts.
 *
 * Using `init` ensures all scripts are registered
 * before any shortcode attempts to enqueue them.
 */
function eckohaus_vol_register_scripts() {

    $plugin_url = plugin_dir_url( __FILE__ );

    wp_register_script(
        'eckohaus-vol-core',
        $plugin_url . 'assets/js/viewer-core.js',
        array(),
        '0.1.0',
        true
    );

    wp_register_script(
        'eckohaus-vol-plotly',
        $plugin_url . 'assets/js/viewer-plotly.js',
        array( 'eckohaus-vol-core' ),
        '0.1.0',
        true
    );

    wp_register_script(
        'eckohaus-vol-three',
        $plugin_url . 'assets/js/viewer-three.js',
        array( 'eckohaus-vol-core' ),
        '0.1.0',
        true
    );
}
add_action( 'init', 'eckohaus_vol_register_scripts' );

/**
 * Shortcode:
 * [eckohaus_volume url="https://..." renderer="plotly"]
 */
function eckohaus_vol_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'url'       => '',
            'renderer'  => 'plotly',
            'container' => 'eckohaus-vol-container'
        ),
        $atts,
        'eckohaus_volume'
    );

    // Always load core.
    wp_enqueue_script( 'eckohaus-vol-core' );

    // Renderer selection.
    if ( $atts['renderer'] === 'three' ) {
        wp_enqueue_script( 'eckohaus-vol-three' );
    } else {
        wp_enqueue_script( 'eckohaus-vol-plotly' );
    }

    // Localized JS data.
    wp_localize_script(
        'eckohaus-vol-core',
        'EckohausVolData',
        array(
            'url'       => esc_url_raw( $atts['url'] ),
            'renderer'  => sanitize_text_field( $atts['renderer'] ),
            'container' => sanitize_html_class( $atts['container'] )
        )
    );

    // Output container.
    ob_start();
    ?>
    <div id="<?php echo esc_attr( $atts['container'] ); ?>" class="eckohaus-vol-container">
        <!-- Eckohaus Volumetric Viewer will render here -->
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'eckohaus_volume', 'eckohaus_vol_shortcode' );
