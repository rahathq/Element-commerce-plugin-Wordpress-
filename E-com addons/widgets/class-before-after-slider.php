<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Before After Image Slider Elementor Widget.
 */
class ECMA_Before_After_Slider_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ecma-before-after-slider';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ECMA - Before After Image Slider', 'e-com-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
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
		return [ 'ecma-before-after-style' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'ecma-before-after-script' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// ==========================================
		// CONTENT TAB
		// ==========================================

		// Images Section
		$this->start_controls_section(
			'section_images',
			[
				'label' => esc_html__( 'Images Settings', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'before_image',
			[
				'label'   => esc_html__( 'Before Image', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'   => esc_html__( 'After Image', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'        => esc_html__( 'Show Labels', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'e-com-addons' ),
				'label_off'    => esc_html__( 'No', 'e-com-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'before_label',
			[
				'label'       => esc_html__( 'Before Label', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before', 'e-com-addons' ),
				'condition'   => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'after_label',
			[
				'label'       => esc_html__( 'After Label', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'After', 'e-com-addons' ),
				'condition'   => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_visibility',
			[
				'label'     => esc_html__( 'Label Visibility', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'always',
				'options'   => [
					'always' => esc_html__( 'Always Show', 'e-com-addons' ),
					'hover'  => esc_html__( 'Show Only on Hover', 'e-com-addons' ),
				],
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// CSS Filters Section
		$this->start_controls_section(
			'section_css_filters',
			[
				'label' => esc_html__( 'CSS Image Filters', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Before Image Filter Type
		$this->add_control(
			'before_filter',
			[
				'label'   => esc_html__( 'Before Image Filter', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'      => esc_html__( 'None', 'e-com-addons' ),
					'blur'      => esc_html__( 'Blur', 'e-com-addons' ),
					'grayscale' => esc_html__( 'Grayscale', 'e-com-addons' ),
					'sepia'     => esc_html__( 'Sepia', 'e-com-addons' ),
					'saturate'  => esc_html__( 'Saturate', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'before_filter_blur_val',
			[
				'label'     => esc_html__( 'Blur Value (px)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 5,
				],
				'condition' => [
					'before_filter' => 'blur',
				],
			]
		);

		$this->add_control(
			'before_filter_percent_val',
			[
				'label'     => esc_html__( 'Filter Intensity (%)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'%' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => '%',
					'size' => 100,
				],
				'condition' => [
					'before_filter' => [ 'grayscale', 'sepia', 'saturate' ],
				],
			]
		);

		$this->add_control(
			'hr_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// After Image Filter Type
		$this->add_control(
			'after_filter',
			[
				'label'   => esc_html__( 'After Image Filter', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'      => esc_html__( 'None', 'e-com-addons' ),
					'blur'      => esc_html__( 'Blur', 'e-com-addons' ),
					'grayscale' => esc_html__( 'Grayscale', 'e-com-addons' ),
					'sepia'     => esc_html__( 'Sepia', 'e-com-addons' ),
					'saturate'  => esc_html__( 'Saturate', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'after_filter_blur_val',
			[
				'label'     => esc_html__( 'Blur Value (px)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 5,
				],
				'condition' => [
					'after_filter' => 'blur',
				],
			]
		);

		$this->add_control(
			'after_filter_percent_val',
			[
				'label'     => esc_html__( 'Filter Intensity (%)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'%' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default'   => [
					'unit' => '%',
					'size' => 100,
				],
				'condition' => [
					'after_filter' => [ 'grayscale', 'sepia', 'saturate' ],
				],
			]
		);

		$this->end_controls_section();

		// Slider Options Section
		$this->start_controls_section(
			'section_options',
			[
				'label' => esc_html__( 'Slider Options', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'   => esc_html__( 'Orientation', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'e-com-addons' ),
					'vertical'   => esc_html__( 'Vertical', 'e-com-addons' ),
				],
			]
		);

		$this->add_control(
			'default_offset',
			[
				'label'   => esc_html__( 'Default Offset (%)', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
			]
		);

		$this->add_control(
			'interaction_mode',
			[
				'label'   => esc_html__( 'Interaction Trigger', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'drag',
				'options' => [
					'drag'  => esc_html__( 'Drag / Swipe', 'e-com-addons' ),
					'hover' => esc_html__( 'Mouse Hover', 'e-com-addons' ),
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB
		// ==========================================

		// Container Section
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container Style', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_width',
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
					'{{WRAPPER}} .ecma-before-after-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'label'      => esc_html__( 'Height', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 150,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-before-after-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'slider_border',
				'selector' => '{{WRAPPER}} .ecma-before-after-container',
			]
		);

		$this->add_responsive_control(
			'slider_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-before-after-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'slider_box_shadow',
				'selector' => '{{WRAPPER}} .ecma-before-after-container',
			]
		);

		$this->end_controls_section();

		// Slider Handle Section
		$this->start_controls_section(
			'section_style_handle',
			[
				'label' => esc_html__( 'Slider Handle Style', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_thickness',
			[
				'label'   => esc_html__( 'Bar Thickness (px)', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .ecma-slider-handle-line' => '--ecma-handle-bar-thickness: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label'     => esc_html__( 'Bar Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-slider-handle-line' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'circle_size',
			[
				'label'   => esc_html__( 'Handle Circle Size (px)', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => 20,
						'max'  => 80,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .ecma-slider-handle-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-top: calc(-{{SIZE}}{{UNIT}}/2);',
				],
			]
		);

		$this->add_control(
			'circle_bg_color',
			[
				'label'     => esc_html__( 'Circle Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-slider-handle-circle' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'circle_border',
				'selector' => '{{WRAPPER}} .ecma-slider-handle-circle',
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .ecma-slider-handle-circle svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Labels Section
		$this->start_controls_section(
			'section_style_labels',
			[
				'label'     => esc_html__( 'Labels Style', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} .ecma-before-after-container .ecma-label',
			]
		);

		$this->add_control(
			'labels_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-before-after-container .ecma-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'labels_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.6)',
				'selectors' => [
					'{{WRAPPER}} .ecma-before-after-container .ecma-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'labels_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-before-after-container .ecma-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'labels_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-before-after-container .ecma-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$before_url = ! empty( $settings['before_image']['url'] ) ? esc_url( $settings['before_image']['url'] ) : '';
		$after_url  = ! empty( $settings['after_image']['url'] ) ? esc_url( $settings['after_image']['url'] ) : '';

		$orientation = esc_attr( $settings['orientation'] );
		$offset      = isset( $settings['default_offset']['size'] ) ? intval( $settings['default_offset']['size'] ) : 50;
		$interaction = esc_attr( $settings['interaction_mode'] );
		$show_labels = 'yes' === $settings['show_labels'];

		// Label visibility class
		$label_visibility = $settings['label_visibility'] ?? 'always';
		$container_class = 'ecma-before-after-container ecma-label-' . esc_attr( $label_visibility );

		// Before Image Filter Style
		$before_filter_style = '';
		$before_filter = $settings['before_filter'] ?? 'none';
		if ( 'none' !== $before_filter ) {
			if ( 'blur' === $before_filter ) {
				$blur_size = $settings['before_filter_blur_val']['size'] ?? 5;
				$before_filter_style = 'filter: blur(' . intval( $blur_size ) . 'px);';
			} else {
				$intensity = $settings['before_filter_percent_val']['size'] ?? 100;
				$before_filter_style = 'filter: ' . esc_attr( $before_filter ) . '(' . intval( $intensity ) . '%);';
			}
		}

		// After Image Filter Style
		$after_filter_style = '';
		$after_filter = $settings['after_filter'] ?? 'none';
		if ( 'none' !== $after_filter ) {
			if ( 'blur' === $after_filter ) {
				$blur_size = $settings['after_filter_blur_val']['size'] ?? 5;
				$after_filter_style = 'filter: blur(' . intval( $blur_size ) . 'px);';
			} else {
				$intensity = $settings['after_filter_percent_val']['size'] ?? 100;
				$after_filter_style = 'filter: ' . esc_attr( $after_filter ) . '(' . intval( $intensity ) . '%);';
			}
		}

		?>
		<div class="<?php echo $container_class; ?>" 
			data-orientation="<?php echo $orientation; ?>"
			data-offset="<?php echo $offset; ?>"
			data-interaction="<?php echo $interaction; ?>">
			
			<!-- AFTER IMAGE (Base) -->
			<div class="ecma-after-wrapper">
				<?php if ( $after_url ) : ?>
					<img class="ecma-after-img" src="<?php echo $after_url; ?>" alt="<?php echo esc_attr( $settings['after_label'] ); ?>" <?php echo $after_filter_style ? 'style="' . esc_attr( $after_filter_style ) . '"' : ''; ?>>
				<?php endif; ?>
				<?php if ( $show_labels && ! empty( $settings['after_label'] ) ) : ?>
					<span class="ecma-label ecma-label-after"><?php echo esc_html( $settings['after_label'] ); ?></span>
				<?php endif; ?>
			</div>

			<!-- BEFORE IMAGE (Overlay with Clip-Path) -->
			<div class="ecma-before-wrapper">
				<?php if ( $before_url ) : ?>
					<img class="ecma-before-img" src="<?php echo $before_url; ?>" alt="<?php echo esc_attr( $settings['before_label'] ); ?>" <?php echo $before_filter_style ? 'style="' . esc_attr( $before_filter_style ) . '"' : ''; ?>>
				<?php endif; ?>
				<?php if ( $show_labels && ! empty( $settings['before_label'] ) ) : ?>
					<span class="ecma-label ecma-label-before"><?php echo esc_html( $settings['before_label'] ); ?></span>
				<?php endif; ?>
			</div>

			<!-- SLIDER HANDLE -->
			<div class="ecma-slider-handle">
				<div class="ecma-slider-handle-line"></div>
				<div class="ecma-slider-handle-circle">
					<?php if ( 'vertical' === $orientation ) : ?>
						<!-- Vertical Arrows (Up/Down) -->
						<svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M6 0L11.1962 4.5H0.803848L6 0ZM6 18L0.803848 13.5H11.1962L6 18Z" fill="#333333"/>
						</svg>
					<?php else : ?>
						<!-- Horizontal Arrows (Left/Right) -->
						<svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M0 6L4.5 0.803848V11.1962L0 6ZM18 6L13.5 11.1962V0.803848L18 6Z" fill="#333333"/>
						</svg>
					<?php endif; ?>
				</div>
				<div class="ecma-slider-handle-line"></div>
			</div>
		</div>
		<?php
	}
}
