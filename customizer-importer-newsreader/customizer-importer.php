<?php
/**
 * Plugin Name: Customizer Importer for Newsreader WordPress theme
 * Description: Allows users to import Customizer settings from multiple .dat files using One Click Demo Import.
 * Version: 1.1
 * Author: Dmitrii Chempalov
 * Author URL: https://github.com/childtheme/codesupple
 * Text Domain: customizer-importer
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add the import options for customizer.dat files
function customizer_importer_import_files() {
    return array(
        array(
            'import_file_name'            => 'Customizer Demo Import Gear',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/gear.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/gear.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Gear Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import News',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/news.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/news.jpg',
            'import_notice'                => __( 'Importing Customizer settings for News Demo.', 'customizer-importer' ),
        ),
        array(

            'import_file_name'            => 'Customizer Demo Startups',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/startups.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/startups.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Startups Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Sports',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/sports.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/sports.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Sports.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Auto',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/auto.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/auto.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Auto Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import City',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/city.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/city.jpg',
            'import_notice'                => __( 'Importing Customizer settings for City Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Media',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/media.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/media.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Media Demo.', 'customizer-importer' ),
        ),
    );
}
add_filter( 'ocdi/import_files', 'customizer_importer_import_files' );

// Rename the "Import Demo Data" menu item to "Import Customizer Data"
function customizer_importer_rename_menu( $args ) {
    $args['menu_title'] = __( 'Import Customizer Data', 'customizer-importer' );
    return $args;
}
add_filter( 'ocdi/plugin_page_setup', 'customizer_importer_rename_menu' );

// Add a custom notice at the top of the import page and remove the default intro text
function customizer_importer_intro_text( $default_text ) {
    // Remove the default intro text
    $custom_message = '<div style="padding: 10px; background-color: #9cf79591; border-left: 5px solid #ff9800;">';
    $custom_message .= '<p><strong>Important:</strong> You are only importing Customizer settings (.dat files) from the list below.</p>';
    $custom_message .= '<p>If you wish to import the full demo (including content, widgets, etc.), please navigate to <strong>Appearance > Themes Demos</strong>.</p>';
    $custom_message .= '</div>';

    return $custom_message; // Return only the custom message
}
add_filter( 'ocdi/plugin_intro_text', 'customizer_importer_intro_text' );

// Resize the large top preview image with CSS
function customizer_importer_resize_large_preview_css() {
    echo '<style>
        .ocdi__theme-about-screenshots img {
            max-width: 182px; /* Set a smaller width for the top preview image */
            height: auto; /* Maintain aspect ratio */
			    margin-left: auto;
				margin-top: 10px;
        }
    </style>';
}
add_action( 'admin_head', 'customizer_importer_resize_large_preview_css' );


function customizer_importer_move_text_block_css() {
    echo '<style>
       .ocdi__theme-about {
			margin-bottom: 30px;
			flex-direction: row-reverse;
		
}
        }
    </style>';
}
add_action( 'admin_head', 'customizer_importer_move_text_block_css' );

function customizer_importer_hide_tags_text_area() {
    echo '<style>
        .theme-tags { /* Replace this with the correct class or ID for the tags section */
            display: none;
        }
    </style>';
}
add_action( 'admin_head', 'customizer_importer_hide_tags_text_area' );

// After import setup (optional, can be left empty if no actions are needed after import)
function customizer_importer_after_import_setup( $selected_import ) {
    // Optional: Add any actions to perform after import
}
add_action( 'ocdi/after_import', 'customizer_importer_after_import_setup' );
