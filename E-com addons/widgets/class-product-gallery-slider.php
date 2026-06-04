<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce Product Gallery Slider Elementor Widget.
 */
class ECMA_Product_Gallery_Slider_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ecma-product-gallery-slider';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ECMA - Product Gallery Slider', 'e-com-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
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
		return [ 'ecma-swiper-css', 'ecma-widget-style' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'ecma-swiper-js', 'ecma-widget-script' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// ==========================================
		// CONTENT TAB
		// ==========================================

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout Settings', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'thumbs_position',
			[
				'label'   => esc_html__( 'Thumbnail Position', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'below' => esc_html__( 'Below Main Image', 'e-com-addons' ),
					'left'  => esc_html__( 'Left of Main Image', 'e-com-addons' ),
					'right' => esc_html__( 'Right of Main Image', 'e-com-addons' ),
				],
			]
		);

		$this->add_responsive_control(
			'slides_per_view',
			[
				'label'   => esc_html__( 'Thumbnails Per View', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
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

		$this->add_control(
			'space_between',
			[
				'label'   => esc_html__( 'Thumbnail Spacing (px)', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
			]
		);

		$this->add_control(
			'gallery_spacing',
			[
				'label'   => esc_html__( 'Spacing between Main & Thumbs', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .ecma-product-gallery' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumb_aspect_ratio',
			[
				'label'   => esc_html__( 'Thumbnail Aspect Ratio', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '1-1',
				'options' => [
					'original' => esc_html__( 'Original', 'e-com-addons' ),
					'1-1'      => esc_html__( '1:1', 'e-com-addons' ),
					'4-3'      => esc_html__( '4:3', 'e-com-addons' ),
					'3-4'      => esc_html__( '3:4', 'e-com-addons' ),
					'16-9'     => esc_html__( '16:9', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'loop',
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
			'autoplay',
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
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 3000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'transition_effect',
			[
				'label'   => esc_html__( 'Transition Effect', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'e-com-addons' ),
					'fade'  => esc_html__( 'Fade', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'hover_zoom',
			[
				'label'        => esc_html__( 'Hover Zoom (Magnifier)', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		// Badges Section
		$this->start_controls_section(
			'section_badges',
			[
				'label' => esc_html__( 'Badges', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_stock_indicator',
			[
				'label'        => esc_html__( 'Show Stock Indicator', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'stock_in_stock_text',
			[
				'label'       => esc_html__( 'In Stock Text', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'IN STOCK · SHIPS TODAY', 'e-com-addons' ),
				'condition'   => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'stock_out_of_stock_text',
			[
				'label'       => esc_html__( 'Out of Stock Text', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'OUT OF STOCK', 'e-com-addons' ),
				'condition'   => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_category_badge',
			[
				'label'        => esc_html__( 'Show Category Badge', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'e-com-addons' ),
				'label_off'    => esc_html__( 'Hide', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB
		// ==========================================

		// Main Image Styling
		$this->start_controls_section(
			'section_style_main_image',
			[
				'label' => esc_html__( 'Main Image', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'main_img_width',
			[
				'label'      => esc_html__( 'Width', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper.ecma-main-slider' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'main_img_height',
			[
				'label'      => esc_html__( 'Height', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper.ecma-main-slider .swiper-slide img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'main_img_border',
				'selector' => '{{WRAPPER}} .swiper.ecma-main-slider .swiper-slide img',
			]
		);

		$this->add_responsive_control(
			'main_img_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper.ecma-main-slider .swiper-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .swiper.ecma-main-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'main_img_object_fit',
			[
				'label'   => esc_html__( 'Object Fit', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover'   => esc_html__( 'Cover', 'e-com-addons' ),
					'contain' => esc_html__( 'Contain', 'e-com-addons' ),
					'fill'    => esc_html__( 'Fill', 'e-com-addons' ),
					'auto'    => esc_html__( 'Auto', 'e-com-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .swiper.ecma-main-slider .swiper-slide img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'main_img_box_shadow',
				'selector' => '{{WRAPPER}} .swiper.ecma-main-slider',
			]
		);

		$this->end_controls_section();

		// Thumbnail Images Styling
		$this->start_controls_section(
			'section_style_thumbs',
			[
				'label' => esc_html__( 'Thumbnails', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => esc_html__( 'Thumbnail Height', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-gallery.layout-below .swiper.ecma-thumb-slider .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'thumbs_position' => 'below',
				],
			]
		);

		$this->add_control(
			'thumb_object_fit',
			[
				'label'   => esc_html__( 'Object Fit', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover'   => esc_html__( 'Cover', 'e-com-addons' ),
					'contain' => esc_html__( 'Contain', 'e-com-addons' ),
					'fill'    => esc_html__( 'Fill', 'e-com-addons' ),
					'auto'    => esc_html__( 'Auto', 'e-com-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'thumb_border',
				'selector' => '{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide img',
			]
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumb_active_opacity',
			[
				'label'   => esc_html__( 'Active Slide Opacity', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => [
					'' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide-thumb-active' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'thumb_inactive_opacity',
			[
				'label'   => esc_html__( 'Inactive Slide Opacity', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => [
					'' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.6,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper.ecma-thumb-slider .swiper-slide:not(.swiper-slide-thumb-active)' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();

		// Badges Styling (Stock and Category)
		$this->start_controls_section(
			'section_style_badges',
			[
				'label' => esc_html__( 'Badges Style', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Stock Indicator Styling Header
		$this->add_control(
			'heading_stock_badge_style',
			[
				'label'     => esc_html__( 'Stock Indicator Badge', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'stock_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-stock-indicator' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'stock_bg_color',
			[
				'label'     => esc_html__( 'Background Color (In Stock)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-stock-indicator' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'stock_out_bg_color',
			[
				'label'     => esc_html__( 'Background Color (Out of Stock)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-stock-indicator.ecma-out-of-stock' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'stock_typography',
				'selector'  => '{{WRAPPER}} .ecma-stock-indicator',
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'stock_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-stock-indicator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'stock_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-stock-indicator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_stock_indicator' => 'yes',
				],
			]
		);

		// Category Badge Styling Header
		$this->add_control(
			'heading_cat_badge_style',
			[
				'label'     => esc_html__( 'Category Badge', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-category-badge' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-product-category-badge' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'cat_typography',
				'selector'  => '{{WRAPPER}} .ecma-product-category-badge',
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cat_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-category-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'cat_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-product-category-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_category_badge' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Navigation Styling
		$this->start_controls_section(
			'section_style_nav',
			[
				'label' => esc_html__( 'Navigation Arrows', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-cs-nav-btn svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-cs-nav-btn' => 'background-color: {{VALUE}}; border-color: transparent;',
				],
			]
		);

		$this->add_control(
			'nav_bg_hover_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-cs-nav-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label'      => esc_html__( 'Size (px)', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 80,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-cs-nav-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-cs-nav-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-cs-nav-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		global $product;
		$gallery_product = $product;

		// Fallback for editor mode or non-product pages
		if ( ! class_exists( 'WC_Product' ) || ! is_a( $gallery_product, 'WC_Product' ) ) {
			if ( function_exists( 'wc_get_products' ) ) {
				$products = wc_get_products( [
					'limit'   => 1,
					'status'  => 'publish',
					'orderby' => 'date',
					'order'   => 'DESC',
				] );
				if ( ! empty( $products ) ) {
					$gallery_product = $products[0];
				}
			}
		}

		// If no WooCommerce product could be retrieved, show fallback placeholder representation
		if ( ! class_exists( 'WC_Product' ) || ! is_a( $gallery_product, 'WC_Product' ) ) {
			$this->render_placeholder( $settings );
			return;
		}

		$attachment_ids = $gallery_product->get_gallery_image_ids();
		$main_image_id  = $gallery_product->get_image_id();
		$stock_text     = $gallery_product->is_in_stock() ? $settings['stock_in_stock_text'] : $settings['stock_out_of_stock_text'];

		$categories = get_the_terms( $gallery_product->get_id(), 'product_cat' );
		$category_name = '';
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$category_name = $categories[0]->name;
		}

		$thumbs_position = $settings['thumbs_position'];
		$slides_per_view = $settings['slides_per_view'] ?? '4';
		$slides_per_view_tablet = $settings['slides_per_view_tablet'] ?? $slides_per_view;
		$slides_per_view_mobile = $settings['slides_per_view_mobile'] ?? $slides_per_view_tablet;
		$space_between   = $settings['space_between']['size'] ?? 8;
		$aspect_ratio    = $settings['thumb_aspect_ratio'] ?? '1-1';
		$loop            = $settings['loop'] ?? 'no';
		$autoplay        = $settings['autoplay'] ?? 'no';
		$autoplay_speed  = $settings['autoplay_speed'] ?? 3000;
		$transition      = $settings['transition_effect'] ?? 'slide';
		$hover_zoom      = $settings['hover_zoom'] ?? 'yes';
		?>

		<div class="ecma-product-gallery layout-<?php echo esc_attr( $thumbs_position ); ?> ratio-<?php echo esc_attr( $aspect_ratio ); ?>" 
			data-layout="<?php echo esc_attr( $thumbs_position ); ?>"
			data-slides-per-view="<?php echo esc_attr( $slides_per_view ); ?>"
			data-slides-per-view-tablet="<?php echo esc_attr( $slides_per_view_tablet ); ?>"
			data-slides-per-view-mobile="<?php echo esc_attr( $slides_per_view_mobile ); ?>"
			data-space-between="<?php echo esc_attr( $space_between ); ?>"
			data-aspect-ratio="<?php echo esc_attr( $aspect_ratio ); ?>"
			data-loop="<?php echo esc_attr( $loop ); ?>"
			data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
			data-effect="<?php echo esc_attr( $transition ); ?>"
			data-hover-zoom="<?php echo esc_attr( $hover_zoom ); ?>">

			<!-- MAIN SLIDER WITH ARROWS -->
			<div class="ecma-main-slider-wrapper">
				<div class="ecma-product-badges">
					<?php if ( 'yes' === $settings['show_stock_indicator'] ) : ?>
						<p class="ecma-stock-indicator <?php echo $gallery_product->is_in_stock() ? '' : 'ecma-out-of-stock'; ?>" 
							id="ecma-stock-indicator"
							data-in-stock-text="<?php echo esc_attr( $settings['stock_in_stock_text'] ); ?>"
							data-out-of-stock-text="<?php echo esc_attr( $settings['stock_out_of_stock_text'] ); ?>"
							data-default-stock="<?php echo $gallery_product->is_in_stock() ? 'yes' : 'no'; ?>">
							<?php echo esc_html( $stock_text ); ?>
						</p>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_category_badge'] && ! empty( $category_name ) ) : ?>
						<p class="ecma-product-category-badge">
							<?php echo esc_html( $category_name ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="swiper ecma-main-slider">
					<div class="swiper-wrapper">
						<?php if ( $main_image_id ) : ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_url( wp_get_attachment_url( $main_image_id ) ); ?>" alt="">
							</div>
						<?php endif; ?>

						<?php foreach ( $attachment_ids as $id ) : ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_url( wp_get_attachment_url( $id ) ); ?>" alt="">
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<!-- THUMBNAIL SLIDER -->
			<div class="ecma-thumb-wrapper">
				<div class="ecma-custom-prev ecma-cs-nav-btn">
					<svg width="22" height="15" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M21 6.36328C21.5523 6.36328 22 6.811 22 7.36328C22 7.91557 21.5523 8.36328 21 8.36328L21 7.36328L21 6.36328ZM0.292893 8.07039C-0.0976314 7.67987 -0.0976315 7.0467 0.292892 6.65618L6.65685 0.292215C7.04738 -0.0983097 7.68054 -0.0983098 8.07107 0.292215C8.46159 0.682739 8.46159 1.3159 8.07107 1.70643L2.41421 7.36328L8.07107 13.0201C8.46159 13.4107 8.46159 14.0438 8.07107 14.4344C7.68054 14.8249 7.04738 14.8249 6.65686 14.4344L0.292893 8.07039ZM21 7.36328L21 8.36328L1 8.36328L1 7.36328L1 6.36328L21 6.36328L21 7.36328Z" fill="#161212"/>
					</svg>
				</div>

				<div class="swiper ecma-thumb-slider">
					<div class="swiper-wrapper">
						<?php if ( $main_image_id ) : ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_url( wp_get_attachment_url( $main_image_id ) ); ?>" alt="">
							</div>
						<?php endif; ?>

						<?php foreach ( $attachment_ids as $id ) : ?>
							<div class="swiper-slide">
								<img src="<?php echo esc_url( wp_get_attachment_url( $id ) ); ?>" alt="">
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="ecma-custom-next ecma-cs-nav-btn">
					<svg width="22" height="15" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 6.36328C0.447715 6.36328 4.82823e-08 6.811 0 7.36328C-4.82823e-08 7.91557 0.447715 8.36328 1 8.36328L1 7.36328L1 6.36328ZM21.7071 8.07039C22.0976 7.67987 22.0976 7.0467 21.7071 6.65618L15.3431 0.292215C14.9526 -0.0983097 14.3195 -0.0983098 13.9289 0.292215C13.5384 0.682739 13.5384 1.3159 13.9289 1.70643L19.5858 7.36328L13.9289 13.0201C13.5384 13.4107 13.5384 14.0438 13.9289 14.4344C14.3195 14.8249 14.9526 14.8249 15.3431 14.4344L21.7071 8.07039ZM1 7.36328L1 8.36328L21 8.36328L21 7.36328L21 6.36328L1 6.36328L1 7.36328Z" fill="#161212"/>
					</svg>
				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Render placeholder structure for editor when WooCommerce product context is empty.
	 */
	private function render_placeholder( $settings ) {
		$stock_text      = $settings['stock_in_stock_text'];
		$category_name   = esc_html__( 'Category Name', 'e-com-addons' );
		$placeholder_img = \Elementor\Utils::get_placeholder_image_src();

		$thumbs_position = $settings['thumbs_position'];
		$slides_per_view = $settings['slides_per_view'] ?? '4';
		$slides_per_view_tablet = $settings['slides_per_view_tablet'] ?? $slides_per_view;
		$slides_per_view_mobile = $settings['slides_per_view_mobile'] ?? $slides_per_view_tablet;
		$space_between   = $settings['space_between']['size'] ?? 8;
		$aspect_ratio    = $settings['thumb_aspect_ratio'] ?? '1-1';
		$loop            = $settings['loop'] ?? 'no';
		$autoplay        = $settings['autoplay'] ?? 'no';
		$autoplay_speed  = $settings['autoplay_speed'] ?? 3000;
		$transition      = $settings['transition_effect'] ?? 'slide';
		$hover_zoom      = $settings['hover_zoom'] ?? 'yes';
		?>
		<div class="ecma-product-gallery layout-<?php echo esc_attr( $thumbs_position ); ?> ratio-<?php echo esc_attr( $aspect_ratio ); ?>" 
			data-layout="<?php echo esc_attr( $thumbs_position ); ?>"
			data-slides-per-view="<?php echo esc_attr( $slides_per_view ); ?>"
			data-slides-per-view-tablet="<?php echo esc_attr( $slides_per_view_tablet ); ?>"
			data-slides-per-view-mobile="<?php echo esc_attr( $slides_per_view_mobile ); ?>"
			data-space-between="<?php echo esc_attr( $space_between ); ?>"
			data-aspect-ratio="<?php echo esc_attr( $aspect_ratio ); ?>"
			data-loop="<?php echo esc_attr( $loop ); ?>"
			data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
			data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
			data-effect="<?php echo esc_attr( $transition ); ?>"
			data-hover-zoom="<?php echo esc_attr( $hover_zoom ); ?>">

			<div class="ecma-main-slider-wrapper">
				<div class="ecma-product-badges">
					<?php if ( 'yes' === $settings['show_stock_indicator'] ) : ?>
						<p class="ecma-stock-indicator" id="ecma-stock-indicator"><?php echo esc_html( $stock_text ); ?></p>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_category_badge'] ) : ?>
						<p class="ecma-product-category-badge"><?php echo esc_html( $category_name ); ?></p>
					<?php endif; ?>
				</div>

				<div class="swiper ecma-main-slider">
					<div class="swiper-wrapper">
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
					</div>
				</div>
			</div>

			<div class="ecma-thumb-wrapper">
				<div class="ecma-custom-prev ecma-cs-nav-btn">
					<svg width="22" height="15" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M21 6.36328C21.5523 6.36328 22 6.811 22 7.36328C22 7.91557 21.5523 8.36328 21 8.36328L21 7.36328L21 6.36328ZM0.292893 8.07039C-0.0976314 7.67987 -0.0976315 7.0467 0.292892 6.65618L6.65685 0.292215C7.04738 -0.0983097 7.68054 -0.0983098 8.07107 0.292215C8.46159 0.682739 8.46159 1.3159 8.07107 1.70643L2.41421 7.36328L8.07107 13.0201C8.46159 13.4107 8.46159 14.0438 8.07107 14.4344C7.68054 14.8249 7.04738 14.8249 6.65686 14.4344L0.292893 8.07039ZM21 7.36328L21 8.36328L1 8.36328L1 7.36328L1 6.36328L21 6.36328L21 7.36328Z" fill="#161212"/>
					</svg>
				</div>

				<div class="swiper ecma-thumb-slider">
					<div class="swiper-wrapper">
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
						<div class="swiper-slide"><img src="<?php echo esc_url( $placeholder_img ); ?>" alt=""></div>
					</div>
				</div>

				<div class="ecma-custom-next ecma-cs-nav-btn">
					<svg width="22" height="15" viewBox="0 0 22 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 6.36328C0.447715 6.36328 4.82823e-08 6.811 0 7.36328C-4.82823e-08 7.91557 0.447715 8.36328 1 8.36328L1 7.36328L1 6.36328ZM21.7071 8.07039C22.0976 7.67987 22.0976 7.0467 21.7071 6.65618L15.3431 0.292215C14.9526 -0.0983097 14.3195 -0.0983098 13.9289 0.292215C13.5384 0.682739 13.5384 1.3159 13.9289 1.70643L19.5858 7.36328L13.9289 13.0201C13.5384 13.4107 13.5384 14.0438 13.9289 14.4344C14.3195 14.8249 14.9526 14.8249 15.3431 14.4344L21.7071 8.07039ZM1 7.36328L1 8.36328L21 8.36328L21 7.36328L21 6.36328L1 6.36328L1 7.36328Z" fill="#161212"/>
					</svg>
				</div>
			</div>
		</div>
		<?php
	}
}
