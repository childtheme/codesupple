<?php
/**
 * Plugin Name: Customizer Importer for Maag WordPress theme
 * Description: Allows users to import Customizer settings from multiple .dat files using One Click Demo Import.
 * Version: 1.1
 * Author: Dmitrii Chempalov
 * Text Domain: customizer-importer
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add the import options for customizer.dat files
function customizer_importer_import_files() {
    return array(
        array(
            'import_file_name'            => 'Customizer Demo Import Lifestyle',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer1.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview1.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Lifestyle Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Food',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer2.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview2.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Food Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Design and Architecture',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer3.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview3.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Design and Architecture Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Freebies',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer4.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview4.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Freebies Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Women\'s Blog',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer5.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview5.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Women\'s Blog Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Sport',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer6.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview6.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Sport Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Auto',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer7.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview7.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Auto Demo.', 'customizer-importer' ),
        ),
        array(
            'import_file_name'            => 'Customizer Demo Import Entertainment',
            'local_import_customizer_file' => plugin_dir_path( __FILE__ ) . 'demo-data/customizer8.dat',
            'import_preview_image_url'     => plugin_dir_url( __FILE__ ) . 'demo-data/preview8.jpg',
            'import_notice'                => __( 'Importing Customizer settings for Entertainment Demo.', 'customizer-importer' ),
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
