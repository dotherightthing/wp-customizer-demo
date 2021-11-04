<?php

/**
 * Flash child theme functions.
 *
 * Override the parent theme.
 */

add_action( 'customize_register', 'flash_child_override_parent_customizer_options', 100);
add_action( 'wp_enqueue_scripts', 'flash_parent_styles' );
add_action( 'wp_enqueue_scripts', 'flash_child_styles' );
add_action( 'wp_enqueue_scripts', 'flash_child_styles_inline', 11 );
add_action( 'wp_enqueue_scripts', 'flash_child_scripts' );

add_filter( 'flash_color_schemes', 'flash_child_color_schemes' );

/**
 * Load JavaScript from child theme
 */
function flash_child_scripts() {
	wp_enqueue_script( 'flash-child-scripts',
		get_stylesheet_directory_uri() . '/scripts.js',
		array(
			// load these registered dependencies first.
		),
        wp_get_theme()->get('Version'), // version in style.css
        'all' // media
	);

    // add config for injected CSS variable demos

    $color_schemes    = flash_child_color_schemes( array() );
    $color_scheme     = $color_schemes['default']['colors'];
    $colors_js_object = array();

    $flash_customizer_colors = array(
        'background_color',
        'link_color',
        'main_text_color',
        'secondary_text_color'
    );

    $i = 0;

    foreach ($flash_customizer_colors as $key) {
        $colors_js_object[$key] = $color_scheme[$i];

        $i++;
    }

    wp_localize_script( 'flash-child-scripts',
        'flash_child_color_scheme',
        $colors_js_object
    );
}

/**
 * Load stylesheet from parent theme
 */
function flash_parent_styles() {
    wp_enqueue_style( 'flash-styles',
        get_template_directory_uri() . '/style.css'
    );
}

/**
 * Load stylesheet from child theme
 */
function flash_child_styles() {
    wp_enqueue_style( 'flash-child-style',
        get_stylesheet_directory_uri() . '/style.css',
		array(
			// load these registered dependencies first.
		),
        wp_get_theme()->get('Version'), // version in style.css
        'all' // media
    );
}

/**
 * Expose Flash colors to front-end for use in the child theme stylesheet
 *
 * @see   wp_add_inline_style()
 */
function flash_child_styles_inline() {
	$color_scheme          = flash_get_color_scheme();
    $css                   = '';
    $css_custom_properties = array();

    // see themes/flash/inc/customizer.php
    $flash_customizer_colors = array(
        'background_color',
        'link_color',
        'main_text_color',
        'secondary_text_color'
    );

    $i = 0;

    // create a css custom property (variable) for each color
    foreach ($flash_customizer_colors as $color) {
      	$default_color = $color_scheme[$i];
        $current_color = get_theme_mod( 'color', $default_color );
        $color_prop    = 'flash-' . str_replace( '_', '-', $color );
        $color_value   = '';

        if ( $current_color === $default_color ) {
            $color_value = $default_color;
        } else {
            $color_value = $current_color;
        }

        // Convert color to rgba.
        $color_rgb = flash_hex2rgb( $color_value );

        $css_custom_properties[] = "--{$color_prop}: {$color_value};";

        // add dark and border variants
        if ( 'link_color' === $color ) {
            $color_value_border = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.8)', $color_rgb );
	        $color_value_dark   = flash_darkcolor( $color, - 20 );

            $css_custom_properties[] = "--flash-link-dark-color: {$color_value_dark};"; // TODO: #0000000000
            $css_custom_properties[] = "--flash-link-border-color: {$color_value_border};";
        } else if ( 'main_text_color' === $color ) {
            $color_value_border = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.2)', $color_rgb );

            $css_custom_properties[] = "--flash-main-text-border-color: {$color_value_border};";
        }

        $i++;
    }

    $css  = "/* usage: element { color: var(--varname); } */\r\n";
    $css .= ":root {\r\n";

    foreach ($css_custom_properties as $prop) {
        $css .= "\t{$prop}\r\n";
    }

    $css .= "}\r\n";

	wp_add_inline_style( 'flash-child-style', $css );
}

/**
 * Remove customizer options to prevent site admins from overriding theme stylesheet
 */
function flash_child_override_parent_customizer_options( $wp_customize ) {
    // Remove Flash Theme Options > Google Font Settings
    // aka - Kirki::remove_section( 'flash_google_font_section' );
    $wp_customize->remove_section( 'flash_google_font_section' );

    // Remove: Colours > Base Colour Scheme
    // Just use the default in flash_child_color_schemes()
    // $wp_customize->remove_control( 'color_scheme' );

    // This change is shown in the customizer color picker but isn't reflected in the CSS variables
    // Replaced with: flash_child_color_schemes
    //
    // $wp_customize->remove_setting('background_color');
    // $wp_customize->add_setting(
    //     'background_color',
    //     array(
    //         'default'           => '#f0c',
    //         'sanitize_callback' => 'sanitize_hex_color',
    //         'transport'         => 'postMessage',
    //     )
    // );

    // Remove: Additional CSS
    $wp_customize->remove_section( 'custom_css' );
}

/**
 * Replace the parent theme colour schemes available in: Colours > Base Color Scheme
 *
 * TODO: after updating this, have to go into Customizer, use color picker and reselect default value to update parent styles
 * TODO: CSS variables not updating when these are changed in the Customizer
 *
 * See themes/flash/inc/customizer.php
 */
function flash_child_color_schemes( $schemes ) {
    $replacement_color_schemes = array(
        'default' => array(
            'label'  => esc_html__( 'Flash Child default', 'flash' ),
            'colors' => array(
                '#fea', // background_color.
                '#f7a', // link_color.
                '#333', // main_text_color.
                '#666', // secondary_text_color.
            ),
        ),
    );

    return $replacement_color_schemes;
}
