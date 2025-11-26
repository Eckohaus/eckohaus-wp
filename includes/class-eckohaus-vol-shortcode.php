<?php

class Eckohaus_Vol_Shortcode {

    public function __construct() {
        // Register shortcode
        add_shortcode( 'eckohaus_volume', [ $this, 'render_shortcode' ] );
    }

    /**
     * Render the shortcode output.
     */
    public function render_shortcode( $atts ) {

        // Expected shortcode attribute: url=""
        $atts = shortcode_atts(
            [
                'url' => '',
            ],
            $atts
        );

        if ( empty( $atts['url'] ) ) {
            return '<p>No volumetric data URL supplied.</p>';
        }

        // Enqueue assets (registered by Eckohaus_Vol_Assets)
        wp_enqueue_script( 'eckohaus-vol-viewer' );
        wp_enqueue_style( 'eckohaus-vol-style' );

        // Pass URL to JS
        wp_localize_script(
            'eckohaus-vol-viewer',
            'EckohausVolData',
            [
                'url' => esc_url_raw( $atts['url'] ),
            ]
        );

        // Viewer container
        return '<div id="eckohaus-vol-container"></div>';
    }
}
