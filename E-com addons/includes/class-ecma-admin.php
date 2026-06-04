<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * E-Com Addons (ECMA) Plugin Backend Dashboard Manager
 */
final class ECMA_Addons_Admin {

	/**
	 * Instance
	 *
	 * @var ECMA_Addons_Admin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Retrieve the single instance of the class.
	 *
	 * @return ECMA_Addons_Admin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor. Registers hooks.
	 */
	private function __construct() {
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
		add_action( 'wp_ajax_ecma_toggle_widget', [ $this, 'toggle_widget_callback' ] );
	}

	/**
	 * Register main menu and submenu pages.
	 */
	public function register_admin_menu() {
		add_menu_page(
			esc_html__( 'ECMA Addons', 'e-com-addons' ),
			esc_html__( 'ECMA Addons', 'e-com-addons' ),
			'manage_options',
			'ecma-addons',
			[ $this, 'render_dashboard_page' ],
			'dashicons-cart',
			59
		);

		add_submenu_page(
			'ecma-addons',
			esc_html__( 'Dashboard', 'e-com-addons' ),
			esc_html__( 'Dashboard', 'e-com-addons' ),
			'manage_options',
			'ecma-addons',
			[ $this, 'render_dashboard_page' ]
		);

		add_submenu_page(
			'ecma-addons',
			esc_html__( 'Widgets Manager', 'e-com-addons' ),
			esc_html__( 'Widgets Manager', 'e-com-addons' ),
			'manage_options',
			'ecma-widgets-manager',
			[ $this, 'render_widgets_manager_page' ]
		);
	}

	/**
	 * Enqueue admin dashboard stylesheets and scripts.
	 *
	 * @param string $hook The current admin page screen hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_ecma-addons' !== $hook && 'ecma-addons_page_ecma-widgets-manager' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'ecma-admin-style',
			plugins_url( '../assets/css/admin-dashboard.css', __FILE__ ),
			[],
			'1.0.0'
		);

		wp_enqueue_script(
			'ecma-admin-script',
			plugins_url( '../assets/js/admin-dashboard.js', __FILE__ ),
			[ 'jquery' ],
			'1.0.0',
			true
		);

		wp_localize_script( 'ecma-admin-script', 'ecmaAdmin', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'ecma_admin_nonce' ),
		] );
	}

	/**
	 * AJAX Callback to save widget activation status toggles instantly.
	 */
	public function toggle_widget_callback() {
		check_ajax_referer( 'ecma_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Unauthorized permissions.', 'e-com-addons' ) );
		}

		$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';
		$status    = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'no';

		if ( empty( $widget_id ) ) {
			wp_send_json_error( esc_html__( 'Invalid widget request ID.', 'e-com-addons' ) );
		}

		$active_widgets = get_option( 'ecma_active_widgets', [
			'product-gallery-slider' => 'yes',
			'before-after-slider'    => 'yes',
			'dynamic-data-table'     => 'yes',
		] );

		$active_widgets[ $widget_id ] = $status;
		update_option( 'ecma_active_widgets', $active_widgets );

		wp_send_json_success( [
			'message' => esc_html__( 'Widget settings updated successfully.', 'e-com-addons' ),
		] );
	}

	/**
	 * Render HTML Output for Dashboard Subpage.
	 */
	public function render_dashboard_page() {
		global $wp_version;

		// Perform Diagnostic System Check
		$php_ok        = version_compare( PHP_VERSION, '7.4.0', '>=' );
		$wp_ok         = version_compare( $wp_version, '6.0', '>=' );
		$elementor_ok  = class_exists( '\Elementor\Plugin' );
		$memory_limit  = ini_get( 'memory_limit' );
		$memory_bytes  = wp_convert_hr_to_bytes( $memory_limit );
		$memory_ok     = $memory_bytes >= 268435456; // 256M

		// Read Active Widgets counts
		$active_widgets = get_option( 'ecma_active_widgets', [
			'product-gallery-slider' => 'yes',
			'before-after-slider'    => 'yes',
			'dynamic-data-table'     => 'yes',
		] );
		$total_widgets  = 3;
		$active_count   = 0;
		$available_ids  = [ 'product-gallery-slider', 'before-after-slider', 'dynamic-data-table' ];
		foreach ( $available_ids as $id ) {
			$status = isset( $active_widgets[ $id ] ) ? $active_widgets[ $id ] : 'yes';
			if ( 'yes' === $status ) {
				$active_count++;
			}
		}
		?>
		<div class="ecma-admin-wrap">
			
			<!-- Header Banner -->
			<div class="ecma-admin-header">
				<div class="ecma-admin-header-logo">
					<span class="dashicons dashicons-cart"></span>
				</div>
				<div class="ecma-admin-header-text">
					<h1><?php esc_html_e( 'ECMA Addons', 'e-com-addons' ); ?></h1>
					<p><?php esc_html_e( 'Premium Elementor widgets customized for E-commerce websites.', 'e-com-addons' ); ?></p>
				</div>
				<div class="ecma-admin-header-badge">
					<span><?php esc_html_e( 'v1.0.0', 'e-com-addons' ); ?></span>
				</div>
			</div>

			<!-- Tabs Navigation -->
			<h2 class="nav-tab-wrapper ecma-admin-tabs">
				<a href="#" class="nav-tab nav-tab-active"><?php esc_html_e( 'Dashboard', 'e-com-addons' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ecma-widgets-manager' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Widgets Manager', 'e-com-addons' ); ?></a>
			</h2>

			<!-- Two Column Dashboard Grid -->
			<div class="ecma-admin-grid">

				<!-- Left Column -->
				<div class="ecma-admin-col ecma-admin-col-left">
					
					<!-- Overview Card -->
					<div class="ecma-admin-card">
						<h3><?php esc_html_e( 'Plugin Overview', 'e-com-addons' ); ?></h3>
						<p><?php esc_html_e( 'Welcome to E-com Addons! We design high-performance, responsive Elementor extensions optimized specifically to increase conversion rates and product presentation for online WooCommerce stores.', 'e-com-addons' ); ?></p>
						
						<div class="ecma-admin-stat-row">
							<div class="ecma-admin-stat-card">
								<h4><?php esc_html_e( 'Active Widgets', 'e-com-addons' ); ?></h4>
								<span class="ecma-stat-number"><?php echo intval( $active_count ); ?>/<?php echo intval( $total_widgets ); ?></span>
							</div>
							<div class="ecma-admin-stat-card">
								<h4><?php esc_html_e( 'Elementor Status', 'e-com-addons' ); ?></h4>
								<span class="ecma-stat-status <?php echo $elementor_ok ? 'ok' : 'err'; ?>">
									<?php echo $elementor_ok ? esc_html__( 'Active', 'e-com-addons' ) : esc_html__( 'Inactive', 'e-com-addons' ); ?>
								</span>
							</div>
						</div>
					</div>

					<!-- Quick Start Guide -->
					<div class="ecma-admin-card">
						<h3><?php esc_html_e( 'Quick Start Guide', 'e-com-addons' ); ?></h3>
						<div class="ecma-steps-list">
							<div class="ecma-step-item">
								<span class="ecma-step-num">1</span>
								<div class="ecma-step-desc">
									<strong><?php esc_html_e( 'Open Elementor Builder', 'e-com-addons' ); ?></strong>
									<p><?php esc_html_e( 'Edit any page, post, or WooCommerce product template using Elementor.', 'e-com-addons' ); ?></p>
								</div>
							</div>
							<div class="ecma-step-item">
								<span class="ecma-step-num">2</span>
								<div class="ecma-step-desc">
									<strong><?php esc_html_e( 'Search Widget Panel', 'e-com-addons' ); ?></strong>
									<p><?php esc_html_e( 'Search for "ECMA" in the element search input, or scroll to the ECMA Addons category section.', 'e-com-addons' ); ?></p>
								</div>
							</div>
							<div class="ecma-step-item">
								<span class="ecma-step-num">3</span>
								<div class="ecma-step-desc">
									<strong><?php esc_html_e( 'Drag & Customise', 'e-com-addons' ); ?></strong>
									<p><?php esc_html_e( 'Drag the slider onto the canvas page. Configure layout grids, interactive sliders, colors, and styling rules.', 'e-com-addons' ); ?></p>
								</div>
							</div>
						</div>
					</div>

				</div>

				<!-- Right Column -->
				<div class="ecma-admin-col ecma-admin-col-right">

					<!-- System Diagnostics Check -->
					<div class="ecma-admin-card">
						<h3><?php esc_html_e( 'System Diagnostics Check', 'e-com-addons' ); ?></h3>
						<p class="ecma-card-subtitle"><?php esc_html_e( 'Verifying WordPress environmental requirements are met for best slider performance.', 'e-com-addons' ); ?></p>
						
						<ul class="ecma-diag-list">
							<li class="<?php echo $php_ok ? 'pass' : 'fail'; ?>">
								<div class="ecma-diag-info">
									<strong><?php esc_html_e( 'PHP Version', 'e-com-addons' ); ?></strong>
									<span><?php echo esc_html( PHP_VERSION ); ?> (<?php esc_html_e( 'Min 7.4.0 required', 'e-com-addons' ); ?>)</span>
								</div>
								<span class="ecma-diag-badge"></span>
							</li>
							<li class="<?php echo $wp_ok ? 'pass' : 'fail'; ?>">
								<div class="ecma-diag-info">
									<strong><?php esc_html_e( 'WordPress Version', 'e-com-addons' ); ?></strong>
									<span><?php echo esc_html( $wp_version ); ?> (<?php esc_html_e( 'Min 6.0 required', 'e-com-addons' ); ?>)</span>
								</div>
								<span class="ecma-diag-badge"></span>
							</li>
							<li class="<?php echo $elementor_ok ? 'pass' : 'fail'; ?>">
								<div class="ecma-diag-info">
									<strong><?php esc_html_e( 'Elementor Plugin', 'e-com-addons' ); ?></strong>
									<span><?php echo $elementor_ok ? esc_html__( 'Installed & Active', 'e-com-addons' ) : esc_html__( 'Elementor is required!', 'e-com-addons' ); ?></span>
								</div>
								<span class="ecma-diag-badge"></span>
							</li>
							<li class="<?php echo $memory_ok ? 'pass' : 'fail'; ?>">
								<div class="ecma-diag-info">
									<strong><?php esc_html_e( 'PHP Memory Limit', 'e-com-addons' ); ?></strong>
									<span><?php echo esc_html( $memory_limit ); ?> (<?php esc_html_e( 'Rec 256M+', 'e-com-addons' ); ?>)</span>
								</div>
								<span class="ecma-diag-badge"></span>
							</li>
						</ul>
					</div>

					<!-- Documentation Card -->
					<div class="ecma-admin-card">
						<h3><?php esc_html_e( 'Documentation & Support', 'e-com-addons' ); ?></h3>
						<p><?php esc_html_e( 'Need help building your pages? Read our documentation guidelines or contact support developers to help resolve your questions.', 'e-com-addons' ); ?></p>
						
						<div class="ecma-admin-btn-row">
							<a href="#" class="button button-primary ecma-admin-btn"><?php esc_html_e( 'Read Documentation', 'e-com-addons' ); ?></a>
							<a href="#" class="button ecma-admin-btn"><?php esc_html_e( 'Get Help & Support', 'e-com-addons' ); ?></a>
						</div>
					</div>

				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Render HTML Output for Widgets Manager Subpage.
	 */
	public function render_widgets_manager_page() {
		// Read settings
		$active_widgets = get_option( 'ecma_active_widgets', [
			'product-gallery-slider' => 'yes',
			'before-after-slider'    => 'yes',
			'dynamic-data-table'     => 'yes',
		] );

		$gallery_active      = ( $active_widgets['product-gallery-slider'] ?? 'yes' ) === 'yes';
		$before_after_active = ( $active_widgets['before-after-slider'] ?? 'yes' ) === 'yes';
		$table_active        = ( $active_widgets['dynamic-data-table'] ?? 'yes' ) === 'yes';
		?>
		<div class="ecma-admin-wrap">
			
			<!-- Header Banner -->
			<div class="ecma-admin-header">
				<div class="ecma-admin-header-logo">
					<span class="dashicons dashicons-cart"></span>
				</div>
				<div class="ecma-admin-header-text">
					<h1><?php esc_html_e( 'Widgets Manager', 'e-com-addons' ); ?></h1>
					<p><?php esc_html_e( 'Enable or disable E-Com Addons widgets to optimize page performance and memory footprint.', 'e-com-addons' ); ?></p>
				</div>
			</div>

			<!-- Tabs Navigation -->
			<h2 class="nav-tab-wrapper ecma-admin-tabs">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ecma-addons' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Dashboard', 'e-com-addons' ); ?></a>
				<a href="#" class="nav-tab nav-tab-active"><?php esc_html_e( 'Widgets Manager', 'e-com-addons' ); ?></a>
			</h2>

			<!-- Toast Notification Alert Box -->
			<div class="ecma-admin-toast" id="ecma-toast">
				<span class="ecma-toast-icon dashicons dashicons-yes-alt"></span>
				<span class="ecma-toast-message"></span>
			</div>

			<!-- Widgets Control Grid -->
			<div class="ecma-widgets-grid">

				<!-- Widget 1 Card -->
				<div class="ecma-widget-card">
					<div class="ecma-widget-icon">
						<span class="dashicons dashicons-post-slider"></span>
					</div>
					<div class="ecma-widget-info">
						<h3><?php esc_html_e( 'Product Gallery Slider', 'e-com-addons' ); ?></h3>
						<p><?php esc_html_e( 'WooCommerce product image sliders featuring responsive thumbnail view counts, hover zoom magnifier, autoplay, loop and sync glassmorphic lightboxes.', 'e-com-addons' ); ?></p>
					</div>
					<div class="ecma-widget-toggle-area">
						<label class="ecma-switch">
							<input type="checkbox" class="ecma-widget-toggle" data-widget-id="product-gallery-slider" <?php checked( $gallery_active ); ?>>
							<span class="ecma-switch-slider"></span>
						</label>
					</div>
				</div>

				<!-- Widget 2 Card -->
				<div class="ecma-widget-card">
					<div class="ecma-widget-icon">
						<span class="dashicons dashicons-images-alt2"></span>
					</div>
					<div class="ecma-widget-info">
						<h3><?php esc_html_e( 'Before After Image Slider', 'e-com-addons' ); ?></h3>
						<p><?php esc_html_e( 'Interactive drag or hover comparisons of two overlapping images using premium hardware-accelerated clip-path offsets and inline CSS filter options.', 'e-com-addons' ); ?></p>
					</div>
					<div class="ecma-widget-toggle-area">
						<label class="ecma-switch">
							<input type="checkbox" class="ecma-widget-toggle" data-widget-id="before-after-slider" <?php checked( $before_after_active ); ?>>
							<span class="ecma-switch-slider"></span>
						</label>
					</div>
				</div>

				<!-- Widget 3 Card -->
				<div class="ecma-widget-card">
					<div class="ecma-widget-icon">
						<span class="dashicons dashicons-editor-table"></span>
					</div>
					<div class="ecma-widget-info">
						<h3><?php esc_html_e( 'Dynamic Data Table', 'e-com-addons' ); ?></h3>
						<p><?php esc_html_e( 'Highly customizable, responsive, and dynamic comparison and specifications tables using manual rows or dynamic WordPress post and custom field queries.', 'e-com-addons' ); ?></p>
					</div>
					<div class="ecma-widget-toggle-area">
						<label class="ecma-switch">
							<input type="checkbox" class="ecma-widget-toggle" data-widget-id="dynamic-data-table" <?php checked( $table_active ); ?>>
							<span class="ecma-switch-slider"></span>
						</label>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
