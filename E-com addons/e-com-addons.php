<?php
/**
 * Plugin Name:       E-com Addons
 * Description:       Custom premium Elementor widgets for E-commerce websites.
 * Version:           1.0.0
 * Author:            Rahat Hoque
 * Text Domain:       e-com-addons
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Automatically deactivate this plugin if Elementor is deactivated or missing.
 */
function ecma_addons_check_elementor_deactivation() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) ) {
		// Do not deactivate during the activation process (to prevent WordPress from triggering a fatal error page)
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], [ 'activate', 'error_scrape' ], true ) ) {
			return;
		}
		if ( isset( $_GET['verify-error'] ) ) {
			return;
		}

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_path = plugin_basename( __FILE__ );
			if ( is_plugin_active( $plugin_path ) ) {
				// Deactivate self
				deactivate_plugins( $plugin_path );

				// Suppress default WordPress "Plugin activated." notice
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				// Display the exact requested error notice on this screen load
				add_action( 'admin_notices', 'ecma_addons_render_failed_notice' );
			}
		}
	}
}
add_action( 'admin_init', 'ecma_addons_check_elementor_deactivation' );

/**
 * Render custom warning notice.
 */
function ecma_addons_render_failed_notice() {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'This plugin cannot be activated because required plugins(elementor) are missing or inactive.', 'e-com-addons' ) . '</p></div>';
}

/**
 * Disable activate link if Elementor is not active.
 */
function ecma_addons_plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		if ( isset( $actions['activate'] ) ) {
			$actions['activate'] = '<span style="color: #a7aaad; cursor: not-allowed;" title="' . esc_attr__( 'Elementor must be active to activate this plugin.', 'e-com-addons' ) . '">' . esc_html__( 'Activate', 'e-com-addons' ) . '</span>';
		}
	}
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ecma_addons_plugin_action_links', 10, 4 );

/**
 * Show a message under the plugin row if Elementor is not active.
 */
function ecma_addons_after_plugin_row( $plugin_file, $plugin_data, $status ) {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		$colspan = 3;
		if ( function_exists( '_get_list_table' ) ) {
			$list_table = _get_list_table( 'WP_Plugins_List_Table' );
			if ( $list_table ) {
				$colspan = $list_table->get_column_count();
			}
		}
		echo '<tr class="plugin-update-tr active dependency-missing-tr">';
		echo '<td colspan="' . $colspan . '" class="plugin-update colspanchange">';
		echo '<div class="notice inline notice-error notice-alt" style="margin: 5px 20px 15px 20px;">';
		echo '<p>' . esc_html__( 'This plugin requires Elementor to be installed and activated.', 'e-com-addons' ) . '</p>';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
}
add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'ecma_addons_after_plugin_row', 10, 3 );

/**
 * Main initializer for E-com Addons.
 * Checks for Elementor dependency and loads the plugin core.
 */
function ecma_addons_init() {
	// Check if Elementor is installed and active
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Elementor is active, load the main plugin file
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-e-com-addons.php';
	ECMA_Addons_Plugin::instance();
}
add_action( 'plugins_loaded', 'ecma_addons_init', 20 );
