<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce Recently Viewed Products Elementor Widget.
 */
class ECMA_Recently_Viewed_Products_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ecma-recently-viewed-products';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ECMA - Recently Viewed Products', 'e-com-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-visibility';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'ecma-addons', 'general' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return [ 'ecma-swiper-css', 'ecma-recent-style' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'ecma-swiper-js', 'ecma-recent-script' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// Register custom Layout tab
		\Elementor\Plugin::instance()->controls_manager->add_tab(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'e-com-addons' ),
			]
		);

		// ==========================================
		// LAYOUT TAB: QUERY SETTINGS
		// ==========================================
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Query Options', 'e-com-addons' ),
				'tab'   => 'layout',
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Number of Products', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 10,
				'step'    => 1,
				'default' => 5,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Product Order', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => [
					'newest' => esc_html__( 'Newest Viewed First', 'e-com-addons' ),
					'oldest' => esc_html__( 'Oldest Viewed First', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'exclude_current',
			[
				'label'        => esc_html__( 'Exclude Current Product', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hide_out_of_stock',
			[
				'label'        => esc_html__( 'Hide Out of Stock Products', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hide_invisible',
			[
				'label'        => esc_html__( 'Hide Invisible Products', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_empty_state',
			[
				'label'        => esc_html__( 'Show Empty State Message', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'empty_state_text',
			[
				'label'     => esc_html__( 'Empty State Text', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'You have not viewed any products yet.', 'e-com-addons' ),
				'condition' => [
					'show_empty_state' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// LAYOUT TAB: LAYOUT SETTINGS
		// ==========================================
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout Options', 'e-com-addons' ),
				'tab'   => 'layout',
			]
		);

		$this->add_control(
			'layout_type',
			[
				'label'   => esc_html__( 'Layout Type', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'     => esc_html__( 'Grid Layout', 'e-com-addons' ),
					'carousel' => esc_html__( 'Slider / Carousel', 'e-com-addons' ),
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-layout-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ecma-layout-carousel.swiper-container' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-layout-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'layout_type' => 'grid',
				],
			]
		);

		$this->add_control(
			'equal_height',
			[
				'label'        => esc_html__( 'Equal Height Cards', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// LAYOUT TAB: PRODUCT ELEMENTS
		// ==========================================
		$this->start_controls_section(
			'section_elements',
			[
				'label' => esc_html__( 'Product Card Elements', 'e-com-addons' ),
				'tab'   => 'layout',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'        => esc_html__( 'Show Image', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'        => esc_html__( 'Show Title', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_price',
			[
				'label'        => esc_html__( 'Show Price', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_short_desc',
			[
				'label'        => esc_html__( 'Show Short Description', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_view_product',
			[
				'label'        => esc_html__( 'Show View Product Button', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'view_product_text',
			[
				'label'     => esc_html__( 'Button Text', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'View Product', 'e-com-addons' ),
				'condition' => [
					'show_view_product' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// LAYOUT TAB: IMAGE SETTINGS
		// ==========================================
		$this->start_controls_section(
			'section_image_settings',
			[
				'label'     => esc_html__( 'Image Settings', 'e-com-addons' ),
				'tab'       => 'layout',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_size',
			[
				'label'   => esc_html__( 'Image Size', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'woocommerce_thumbnail',
				'options' => [
					'thumbnail'             => esc_html__( 'Thumbnail (150x150)', 'e-com-addons' ),
					'woocommerce_thumbnail' => esc_html__( 'WooCommerce Thumbnail', 'e-com-addons' ),
					'medium'                => esc_html__( 'Medium (300x300)', 'e-com-addons' ),
					'medium_large'          => esc_html__( 'Medium Large (768x768)', 'e-com-addons' ),
					'large'                 => esc_html__( 'Large (1024x1024)', 'e-com-addons' ),
					'full'                  => esc_html__( 'Full Size', 'e-com-addons' ),
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// LAYOUT TAB: SLIDER SETTINGS (CAROUSEL ONLY)
		// ==========================================
		$this->start_controls_section(
			'section_slider_settings',
			[
				'label'     => esc_html__( 'Slider / Carousel Settings', 'e-com-addons' ),
				'tab'       => 'layout',
				'condition' => [
					'layout_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'slider_autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slider_autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 3000,
				'condition' => [
					'slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_pause_hover',
			[
				'label'        => esc_html__( 'Pause on Hover', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_navigation',
			[
				'label'        => esc_html__( 'Show Arrows', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'slider_pagination',
			[
				'label'        => esc_html__( 'Show Pagination Dots', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'slider_loop',
			[
				'label'        => esc_html__( 'Infinite Loop', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label'       => esc_html__( 'Transition Speed (ms)', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 500,
				'description' => esc_html__( 'Duration of transition between slides.', 'e-com-addons' ),
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: CARD WRAP STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_card',
			[
				'label' => esc_html__( 'Card & Wrap Container', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_card_style' );

		// Normal State
		$this->start_controls_tab(
			'tab_card_normal',
			[
				'label' => esc_html__( 'Normal', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-recent-product-card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ecma-recent-product-card',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .ecma-recent-product-card',
			]
		);

		$this->end_controls_tab();

		// Hover State
		$this->start_controls_tab(
			'tab_card_hover',
			[
				'label' => esc_html__( 'Hover', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'card_bg_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-recent-product-card:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'card_border_hover',
				'selector' => '{{WRAPPER}} .ecma-recent-product-card:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_box_shadow_hover',
				'selector' => '{{WRAPPER}} .ecma-recent-product-card:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'card_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-recent-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-recent-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label'   => esc_html__( 'Hover Entrance Animation', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'lift',
				'options' => [
					'none'   => esc_html__( 'None', 'e-com-addons' ),
					'lift'   => esc_html__( 'Lift Card Up', 'e-com-addons' ),
					'scale'  => esc_html__( 'Gently Scale Up', 'e-com-addons' ),
					'shadow' => esc_html__( 'Vibrant Box Shadow Only', 'e-com-addons' ),
					'fade'   => esc_html__( 'Background Opacity Transition', 'e-com-addons' ),
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: IMAGE STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_image',
			[
				'label'     => esc_html__( 'Image & Hover Magnify', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__( 'Left', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ecma-product-card-image-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'Custom Width', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range'      => [
					'px' => [ 'min' => 50, 'max' => 600, 'step' => 1 ],
					'%'  => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Custom Height', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 100, 'max' => 500, 'step' => 1 ],
					'%'  => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_object_fit',
			[
				'label'   => esc_html__( 'Image Object Fit', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'fill'    => esc_html__( 'Fill', 'e-com-addons' ),
					'cover'   => esc_html__( 'Cover (Proportional Crop)', 'e-com-addons' ),
					'contain' => esc_html__( 'Contain (Letterbox Fit)', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'image_zoom_hover',
			[
				'label'        => esc_html__( 'Image Hover Zoom Magnifier', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ecma-product-card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'      => esc_html__( 'Margin', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-image-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: TITLE STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Product Title', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .ecma-product-card-title, {{WRAPPER}} .ecma-product-card-title a',
			]
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-card-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => esc_html__( 'Hover', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-card-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: PRICE STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_price',
			[
				'label'     => esc_html__( 'Product Price', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_price' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .ecma-product-card-price, {{WRAPPER}} .ecma-product-card-price .amount',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Regular Price Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-card-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ecma-product-card-price del' => 'color: {{VALUE}}; opacity: 0.65;',
				],
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label'     => esc_html__( 'Sale Price Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-card-price ins .amount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ecma-product-card-price ins' => 'color: {{VALUE}}; text-decoration: none;',
				],
			]
		);

		$this->add_responsive_control(
			'price_margin',
			[
				'label'      => esc_html__( 'Margin', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// ==========================================
		// STYLE TAB: BUTTON (VIEW PRODUCT) STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => esc_html__( 'View Product Button', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_view_product' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .ecma-view-product-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal state
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-view-product-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-view-product-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .ecma-view-product-btn',
			]
		);

		$this->end_controls_tab();

		// Hover state
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'e-com-addons' ),
			]
		);

		$this->add_control(
			'button_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-view-product-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-view-product-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border_hover',
				'selector' => '{{WRAPPER}} .ecma-view-product-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-view-product-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-view-product-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-card-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();





		// ==========================================
		// STYLE TAB: SLIDER NAVIGATION STYLING (CAROUSEL ONLY)
		// ==========================================
		$this->start_controls_section(
			'section_style_slider',
			[
				'label'     => esc_html__( 'Slider Arrows & Pagination', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_type' => 'carousel',
				],
			]
		);

		$this->add_control(
			'arrows_heading',
			[
				'label'     => esc_html__( 'Navigation Arrows', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_heading',
			[
				'label'     => esc_html__( 'Pagination Dots', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label'     => esc_html__( 'Dots Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_active_color',
			[
				'label'     => esc_html__( 'Active Dot Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: EMPTY STATE STYLING
		// ==========================================
		$this->start_controls_section(
			'section_style_empty',
			[
				'label'     => esc_html__( 'Empty State Panel', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_empty_state' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'empty_state_typography',
				'selector' => '{{WRAPPER}} .ecma-recent-products-empty',
			]
		);

		$this->add_control(
			'empty_state_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-recent-products-empty' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'empty_state_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-recent-products-empty' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'empty_state_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-recent-products-empty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'empty_state_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-recent-products-empty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'empty_state_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => esc_html__( 'Left', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'e-com-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .ecma-recent-products-empty' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Frontend HTML Output on the Page.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Simplify and serialize settings to data attribute for AJAX retrieval
		$ajax_settings = [
			'posts_per_page'    => $settings['posts_per_page'] ?? 5,
			'orderby'           => $settings['orderby'] ?? 'newest',
			'exclude_current'   => $settings['exclude_current'] ?? 'yes',
			'hide_out_of_stock' => $settings['hide_out_of_stock'] ?? 'yes',
			'hide_invisible'    => $settings['hide_invisible'] ?? 'yes',
			'show_empty_state'  => $settings['show_empty_state'] ?? 'yes',
			'empty_state_text'  => $settings['empty_state_text'] ?? esc_html__( 'You have not viewed any products yet.', 'e-com-addons' ),
			'layout_type'       => $settings['layout_type'] ?? 'grid',
			'equal_height'      => $settings['equal_height'] ?? 'no',
			'show_image'        => $settings['show_image'] ?? 'yes',
			'show_title'        => $settings['show_title'] ?? 'yes',
			'show_price'        => $settings['show_price'] ?? 'yes',
			'show_short_desc'   => $settings['show_short_desc'] ?? 'yes',
			'show_view_product' => $settings['show_view_product'] ?? 'yes',
			'view_product_text' => $settings['view_product_text'] ?? esc_html__( 'View Product', 'e-com-addons' ),
			'image_size'        => $settings['image_size'] ?? 'woocommerce_thumbnail',
			'image_object_fit'  => $settings['image_object_fit'] ?? 'cover',
			'slider_autoplay'   => $settings['slider_autoplay'] ?? 'no',
			'slider_autoplay_speed' => $settings['slider_autoplay_speed'] ?? 3000,
			'slider_pause_hover'=> $settings['slider_pause_hover'] ?? 'yes',
			'slider_navigation' => $settings['slider_navigation'] ?? 'yes',
			'slider_pagination' => $settings['slider_pagination'] ?? 'yes',
			'slider_loop'       => $settings['slider_loop'] ?? 'no',
			'slider_speed'      => $settings['slider_speed'] ?? 500,
		];

		// Responsive grid column widths
		$ajax_settings['columns']        = $settings['columns'] ?? '4';
		$ajax_settings['columns_tablet'] = $settings['columns_tablet'] ?? '3';
		$ajax_settings['columns_mobile'] = $settings['columns_mobile'] ?? '2';

		$current_product_id = 0;
		if ( function_exists( 'is_product' ) && is_product() ) {
			$current_product_id = get_the_ID();
		}

		// Enqueue scripts and styles manually for security/compatibility
		wp_enqueue_style( 'ecma-recent-style' );
		wp_enqueue_script( 'ecma-recent-script' );

		?>
		<div class="ecma-recent-products-wrap" 
			 data-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>"
			 data-current-product-id="<?php echo esc_attr( $current_product_id ); ?>">
			<!-- Skeleton Loader Cards -->
			<div class="ecma-recent-products-skeleton ecma-cols-<?php echo esc_attr( $ajax_settings['columns'] ); ?> ecma-cols-tab-<?php echo esc_attr( $ajax_settings['columns_tablet'] ); ?> ecma-cols-mob-<?php echo esc_attr( $ajax_settings['columns_mobile'] ); ?>">
				<?php 
				$skeleton_count = intval( $ajax_settings['columns'] );
				for ( $i = 0; $i < $skeleton_count; $i++ ) : 
				?>
					<div class="ecma-skeleton-card">
						<div class="ecma-skeleton-image"></div>
						<div class="ecma-skeleton-title"></div>
						<div class="ecma-skeleton-price"></div>
						<div class="ecma-skeleton-button"></div>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX endpoint: Queries WooCommerce based on stored ID values and echoes layout output.
	 */
	public static function render_ajax_loop() {
		// Clean buffer just in case
		if ( ob_get_length() ) {
			ob_clean();
		}

		$product_ids = isset( $_POST['product_ids'] ) ? array_map( 'intval', $_POST['product_ids'] ) : [];
		$product_ids = array_filter( $product_ids );

		$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : [];
		if ( is_string( $settings ) ) {
			$settings = json_decode( stripslashes( $settings ), true );
		}

		if ( empty( $settings ) || ! is_array( $settings ) ) {
			$settings = [];
		}

		// Merge defaults
		$defaults = [
			'posts_per_page'    => 5,
			'orderby'           => 'newest',
			'exclude_current'   => 'yes',
			'hide_out_of_stock' => 'yes',
			'hide_invisible'    => 'yes',
			'show_empty_state'  => 'yes',
			'empty_state_text'  => esc_html__( 'You have not viewed any products yet.', 'e-com-addons' ),
			'layout_type'       => 'grid',
			'equal_height'      => 'no',
			'show_image'        => 'yes',
			'show_title'        => 'yes',
			'show_price'        => 'yes',
			'show_short_desc'   => 'yes',
			'show_view_product' => 'yes',
			'view_product_text' => esc_html__( 'View Product', 'e-com-addons' ),
			'image_size'        => 'woocommerce_thumbnail',
			'image_object_fit'  => 'cover',
			'slider_autoplay'   => 'no',
			'slider_autoplay_speed' => 3000,
			'slider_pause_hover'=> 'yes',
			'slider_navigation' => 'yes',
			'slider_pagination' => 'yes',
			'slider_loop'       => 'no',
			'slider_speed'      => 500,
			'columns'           => '4',
			'columns_tablet'    => '3',
			'columns_mobile'    => '2',
		];
		$settings = wp_parse_args( $settings, $defaults );

		// Current product ID exclusion
		$current_product_id = isset( $_POST['current_product_id'] ) ? intval( $_POST['current_product_id'] ) : 0;
		if ( 'yes' === $settings['exclude_current'] && $current_product_id > 0 ) {
			$product_ids = array_diff( $product_ids, [ $current_product_id ] );
		}

		if ( empty( $product_ids ) || ! class_exists( 'WooCommerce' ) ) {
			if ( isset( $_POST['is_editor'] ) && '1' === $_POST['is_editor'] && class_exists( 'WooCommerce' ) ) {
				$limit = intval( $settings['posts_per_page'] );
				$product_ids = get_posts( [
					'post_type'      => 'product',
					'posts_per_page' => $limit,
					'fields'         => 'ids',
				] );
			}

			if ( empty( $product_ids ) ) {
				if ( 'yes' === $settings['show_empty_state'] ) {
					echo '<div class="ecma-recent-products-empty">';
					echo esc_html( $settings['empty_state_text'] );
					echo '</div>';
				}
				wp_die();
			}
		}

		// Sort by order requested
		// localStorage stores in chronological order of visits.
		// If "Newest Viewed First", we want the last visited product first.
		if ( 'newest' === $settings['orderby'] ) {
			$product_ids = array_reverse( $product_ids );
		}

		// Slice product IDs list to target limit before querying
		$limit = intval( $settings['posts_per_page'] );
		if ( $limit > 0 ) {
			$product_ids = array_slice( $product_ids, 0, $limit );
		}

		if ( empty( $product_ids ) ) {
			if ( 'yes' === $settings['show_empty_state'] ) {
				echo '<div class="ecma-recent-products-empty">';
				echo esc_html( $settings['empty_state_text'] );
				echo '</div>';
			}
			wp_die();
		}

		// Query products
		$query_args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'post__in'       => $product_ids,
			'orderby'        => 'post__in',
			'posts_per_page' => $limit,
			'fields'         => 'ids',
		];

		if ( 'yes' === $settings['hide_out_of_stock'] ) {
			$query_args['meta_query'][] = [
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			];
		}

		if ( 'yes' === $settings['hide_invisible'] ) {
			$query_args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => [ 'exclude-from-catalog', 'exclude-from-search' ],
				'operator' => 'NOT IN',
			];
		}

		$products_query = new \WP_Query( $query_args );

		if ( ! $products_query->have_posts() ) {
			if ( 'yes' === $settings['show_empty_state'] ) {
				echo '<div class="ecma-recent-products-empty">';
				echo esc_html( $settings['empty_state_text'] );
				echo '</div>';
			}
			wp_die();
		}

		$layout_type  = $settings['layout_type'];
		$equal_height = 'yes' === $settings['equal_height'] ? ' ecma-equal-height' : '';

		// Container classes
		$container_class = 'ecma-recent-products-container ecma-layout-' . esc_attr( $layout_type ) . $equal_height;

		if ( 'carousel' === $layout_type ) {
			$container_class .= ' swiper-container';
		} else {
			$container_class .= sprintf(
				' ecma-cols-%s ecma-cols-tab-%s ecma-cols-mob-%s',
				esc_attr( $settings['columns'] ),
				esc_attr( $settings['columns_tablet'] ),
				esc_attr( $settings['columns_mobile'] )
			);
		}

		echo '<div class="' . esc_attr( $container_class ) . '">';

		if ( 'carousel' === $layout_type ) {
			echo '<div class="swiper-wrapper">';
		}

		while ( $products_query->have_posts() ) {
			$products_query->the_post();
			$product = wc_get_product( get_the_ID() );

			if ( ! $product ) {
				continue;
			}

			// Start Card
			$card_class = 'ecma-recent-product-card';
			if ( ! empty( $settings['hover_animation'] ) ) {
				$card_class .= ' ecma-hover-anim-' . esc_attr( $settings['hover_animation'] );
			}
			if ( 'carousel' === $layout_type ) {
				$card_class .= ' swiper-slide';
			}

			echo '<div class="' . esc_attr( $card_class ) . '">';

			// Image
			if ( 'yes' === $settings['show_image'] ) {
				echo '<div class="ecma-product-card-image-wrap">';

				echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
				$image_id = $product->get_image_id();
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, $settings['image_size'], false, [
						'class' => 'ecma-product-card-image fit-' . esc_attr( $settings['image_object_fit'] ),
					] );
				} else {
					echo wc_placeholder_img( $settings['image_size'], [
						'class' => 'ecma-product-card-image fit-' . esc_attr( $settings['image_object_fit'] ),
					] );
				}
				echo '</a>';

				echo '</div>'; // image wrap
			}

			// Details wrap
			echo '<div class="ecma-product-card-details">';

			// Title
			if ( 'yes' === $settings['show_title'] ) {
				echo '<h4 class="ecma-product-card-title">';
				echo '<a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a>';
				echo '</h4>';
			}

			// Description
			if ( 'yes' === $settings['show_short_desc'] ) {
				$short_desc = $product->get_short_description();
				if ( $short_desc ) {
					echo '<div class="ecma-product-card-short-desc">' . wp_kses_post( wp_trim_words( $short_desc, 12, '...' ) ) . '</div>';
				}
			}

			// Price
			if ( 'yes' === $settings['show_price'] ) {
				echo '<div class="ecma-product-card-price">';
				echo $product->get_price_html();
				echo '</div>';
			}



			// View Product Button
			if ( 'yes' === $settings['show_view_product'] ) {
				echo '<div class="ecma-product-card-action">';
				$button_url  = $product->get_permalink();
				$button_text = ! empty( $settings['view_product_text'] ) ? $settings['view_product_text'] : esc_html__( 'View Product', 'e-com-addons' );

				echo sprintf(
					'<a href="%s" class="button ecma-view-product-btn">%s</a>',
					esc_url( $button_url ),
					esc_html( $button_text )
				);
				echo '</div>';
			}

			echo '</div>'; // card-details
			echo '</div>'; // card
		}

		wp_reset_postdata();

		if ( 'carousel' === $layout_type ) {
			echo '</div>'; // swiper-wrapper

			if ( 'yes' === $settings['slider_navigation'] ) {
				echo '<div class="swiper-button-next"></div>';
				echo '<div class="swiper-button-prev"></div>';
			}

			if ( 'yes' === $settings['slider_pagination'] ) {
				echo '<div class="swiper-pagination"></div>';
			}
		}

		echo '</div>'; // container

		wp_die();
	}
}
