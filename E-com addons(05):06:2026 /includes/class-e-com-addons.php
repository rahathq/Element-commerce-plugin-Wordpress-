<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main E-com Addons Plugin Loader Class
 */
final class ECMA_Addons_Plugin {

	/**
	 * Instance
	 *
	 * @var ECMA_Addons_Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Track widget registration status.
	 *
	 * @var bool
	 */
	private $widgets_registered = false;

	/**
	 * Retrieve the single instance of the class.
	 *
	 * @return ECMA_Addons_Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor. Registers actions and filters.
	 */
	private function __construct() {
		// Register widgets with Elementor based on version compatibility
		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		} else {
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets_legacy' ] );
		}

		// Register custom widgets category
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );

		// Register assets for Elementor to enqueue
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );

		// Load plugin admin dashboard
		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . 'class-ecma-admin.php';
			ECMA_Addons_Admin::instance();
		}

		// Register AJAX action hooks for Recently Viewed Products widget
		add_action( 'wp_ajax_ecma_get_recently_viewed_products', [ $this, 'ajax_get_recently_viewed_products' ] );
		add_action( 'wp_ajax_nopriv_ecma_get_recently_viewed_products', [ $this, 'ajax_get_recently_viewed_products' ] );
	}

	/**
	 * Register custom Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		if ( $this->widgets_registered ) {
			return;
		}

		$active_widgets = get_option( 'ecma_active_widgets', [
			'product-gallery-slider' => 'yes',
			'before-after-slider'    => 'yes',
			'dynamic-data-table'     => 'yes',
			'recently-viewed-products' => 'yes',
		] );

		$gallery_active      = ! isset( $active_widgets['product-gallery-slider'] ) || 'yes' === $active_widgets['product-gallery-slider'];
		$before_after_active = ! isset( $active_widgets['before-after-slider'] ) || 'yes' === $active_widgets['before-after-slider'];
		$table_active        = ! isset( $active_widgets['dynamic-data-table'] ) || 'yes' === $active_widgets['dynamic-data-table'];
		$recent_active       = ! isset( $active_widgets['recently-viewed-products'] ) || 'yes' === $active_widgets['recently-viewed-products'];

		if ( $gallery_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-product-gallery-slider.php';
			$widgets_manager->register( new \ECMA_Product_Gallery_Slider_Widget() );
		}

		if ( $before_after_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-before-after-slider.php';
			$widgets_manager->register( new \ECMA_Before_After_Slider_Widget() );
		}

		if ( $table_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-dynamic-data-table.php';
			$widgets_manager->register( new \ECMA_Dynamic_Data_Table_Widget() );
		}

		if ( $recent_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-recently-viewed-products.php';
			$widgets_manager->register( new \ECMA_Recently_Viewed_Products_Widget() );
		}

		$this->widgets_registered = true;
	}

	/**
	 * Register custom Elementor widgets (Legacy fallback for Elementor < 3.5.0).
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets_legacy( $widgets_manager ) {
		if ( $this->widgets_registered ) {
			return;
		}

		$active_widgets = get_option( 'ecma_active_widgets', [
			'product-gallery-slider' => 'yes',
			'before-after-slider'    => 'yes',
			'dynamic-data-table'     => 'yes',
			'recently-viewed-products' => 'yes',
		] );

		$gallery_active      = ! isset( $active_widgets['product-gallery-slider'] ) || 'yes' === $active_widgets['product-gallery-slider'];
		$before_after_active = ! isset( $active_widgets['before-after-slider'] ) || 'yes' === $active_widgets['before-after-slider'];
		$table_active        = ! isset( $active_widgets['dynamic-data-table'] ) || 'yes' === $active_widgets['dynamic-data-table'];
		$recent_active       = ! isset( $active_widgets['recently-viewed-products'] ) || 'yes' === $active_widgets['recently-viewed-products'];

		if ( $gallery_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-product-gallery-slider.php';
			$widgets_manager->register_widget_type( new \ECMA_Product_Gallery_Slider_Widget() );
		}

		if ( $before_after_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-before-after-slider.php';
			$widgets_manager->register_widget_type( new \ECMA_Before_After_Slider_Widget() );
		}

		if ( $table_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-dynamic-data-table.php';
			$widgets_manager->register_widget_type( new \ECMA_Dynamic_Data_Table_Widget() );
		}

		if ( $recent_active ) {
			require_once plugin_dir_path( __FILE__ ) . '../widgets/class-recently-viewed-products.php';
			$widgets_manager->register_widget_type( new \ECMA_Recently_Viewed_Products_Widget() );
		}

		$this->widgets_registered = true;
	}

	/**
	 * Register custom Elementor category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_categories( $elements_manager ) {
		$elements_manager->add_category(
			'ecma-addons',
			[
				'title' => esc_html__( 'ECMA Addons', 'e-com-addons' ),
				'icon'  => 'fa fa-shopping-cart',
			]
		);
	}

	/**
	 * Register frontend CSS and JS.
	 */
	public function enqueue_frontend_assets() {
		// Register Swiper CSS and JS from CDN
		wp_register_style(
			'ecma-swiper-css',
			'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
			[],
			'11.0.0'
		);
		wp_register_script(
			'ecma-swiper-js',
			'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
			[],
			'11.0.0',
			true
		);

		// Register Widget Style
		wp_register_style(
			'ecma-widget-style',
			plugins_url( 'assets/css/product-gallery-slider.css', dirname( __FILE__ ) ),
			['ecma-swiper-css'],
			'1.0.0'
		);

		// Register Widget Script
		wp_register_script(
			'ecma-widget-script',
			plugins_url( 'assets/js/product-gallery-slider.js', dirname( __FILE__ ) ),
			['jquery', 'ecma-swiper-js'],
			'1.0.0',
			true
		);

		// Register Before After Widget Style
		wp_register_style(
			'ecma-before-after-style',
			plugins_url( 'assets/css/before-after-slider.css', dirname( __FILE__ ) ),
			[],
			'1.0.0'
		);

		// Register Before After Widget Script
		wp_register_script(
			'ecma-before-after-script',
			plugins_url( 'assets/js/before-after-slider.js', dirname( __FILE__ ) ),
			['jquery'],
			'1.0.0',
			true
		);

		// Register Dynamic Data Table Style
		wp_register_style(
			'ecma-table-style',
			plugins_url( 'assets/css/dynamic-data-table.css', dirname( __FILE__ ) ),
			[],
			'1.0.0'
		);

		// Register Dynamic Data Table Script
		wp_register_script(
			'ecma-table-script',
			plugins_url( 'assets/js/dynamic-data-table.js', dirname( __FILE__ ) ),
			['jquery'],
			'1.0.0',
			true
		);

		// Register Recently Viewed Products Style
		wp_register_style(
			'ecma-recent-style',
			plugins_url( 'assets/css/recently-viewed-products.css', dirname( __FILE__ ) ),
			[],
			'1.0.4'
		);

		// Register Recently Viewed Products Script
		wp_register_script(
			'ecma-recent-script',
			plugins_url( 'assets/js/recently-viewed-products.js', dirname( __FILE__ ) ),
			['jquery'],
			'1.0.4',
			true
		);

		$product_id = 0;
		$is_single  = 0;
		if ( function_exists( 'is_product' ) && is_product() ) {
			$product_id = get_the_ID();
			$is_single  = 1;
		}

		wp_localize_script( 'ecma-recent-script', 'ecmaRecentParams', [
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'product_id' => $product_id,
			'is_single'  => $is_single,
		] );
	}

	/**
	 * AJAX endpoint to render recently viewed products cards loop.
	 */
	public function ajax_get_recently_viewed_products() {
		$file = plugin_dir_path( __FILE__ ) . '../widgets/class-recently-viewed-products.php';
		if ( file_exists( $file ) ) {
			require_once $file;
			if ( class_exists( 'ECMA_Recently_Viewed_Products_Widget' ) ) {
				\ECMA_Recently_Viewed_Products_Widget::render_ajax_loop();
			}
		}
		wp_die();
	}
}
