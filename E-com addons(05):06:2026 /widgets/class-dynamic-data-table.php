<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dynamic Data Table Elementor Widget.
 */
class ECMA_Dynamic_Data_Table_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ecma-dynamic-data-table';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ECMA - Data Table', 'e-com-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-table';
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
		return [ 'ecma-table-style' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'ecma-table-script' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {

		// ==========================================
		// CONTENT TAB
		// ==========================================

		// Table Settings Section
		$this->start_controls_section(
			'section_table_settings',
			[
				'label' => esc_html__( 'Table Settings', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'highlighted_rows',
			[
				'label'       => esc_html__( 'Highlighted Rows', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. 2, 4',
				'description' => esc_html__( 'Comma-separated list of row numbers to highlight (1-indexed).', 'e-com-addons' ),
			]
		);

		$this->end_controls_section();

		// Columns Repeater Section
		$this->start_controls_section(
			'section_columns',
			[
				'label' => esc_html__( 'Table Columns', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$columns_repeater = new \Elementor\Repeater();

		$columns_repeater->add_control(
			'col_id',
			[
				'label'   => esc_html__( 'Column ID', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'col_1',
				'description' => esc_html__( 'Unique alphanumeric key (e.g. col_1, name, price).', 'e-com-addons' ),
			]
		);

		$columns_repeater->add_control(
			'col_title',
			[
				'label'   => esc_html__( 'Column Title', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Column Heading', 'e-com-addons' ),
			]
		);

		$columns_repeater->add_control(
			'col_width',
			[
				'label' => esc_html__( 'Column Width', 'e-com-addons' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. 150px or 25%',
			]
		);

		$columns_repeater->add_control(
			'col_align',
			[
				'label'   => esc_html__( 'Alignment', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
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
				'default' => 'left',
			]
		);

		$columns_repeater->add_control(
			'highlight_col',
			[
				'label'        => esc_html__( 'Highlight Column', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$columns_repeater->add_control(
			'recommended_badge',
			[
				'label'        => esc_html__( 'Recommended Plan Badge', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$columns_repeater->add_control(
			'recommended_badge_text',
			[
				'label'     => esc_html__( 'Badge Text', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Recommended', 'e-com-addons' ),
				'condition' => [
					'recommended_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_columns',
			[
				'label'       => esc_html__( 'Define Columns', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $columns_repeater->get_controls(),
				'title_field' => '{{{ col_title }}} ({{{ col_id }}})',
				'default'     => [
					[
						'col_id'    => 'col_1',
						'col_title' => esc_html__( 'Features', 'e-com-addons' ),
						'col_width' => '40%',
					],
					[
						'col_id'    => 'col_2',
						'col_title' => esc_html__( 'Basic Plan', 'e-com-addons' ),
						'col_width' => '30%',
					],
					[
						'col_id'    => 'col_3',
						'col_title' => esc_html__( 'Pro Plan', 'e-com-addons' ),
						'col_width' => '30%',
					],
				],
			]
		);

		$this->end_controls_section();

		// Cells Repeater Section (Manual Mode Only)
		$this->start_controls_section(
			'section_cells',
			[
				'label'     => esc_html__( 'Table Cells', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$cells_repeater = new \Elementor\Repeater();

		$cells_repeater->add_control(
			'row_index',
			[
				'label'   => esc_html__( 'Row Index (e.g. 1, 2)', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
			]
		);

		$cells_repeater->add_control(
			'col_id',
			[
				'label'       => esc_html__( 'Column ID', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'col_1',
				'description' => esc_html__( 'Enter the exact ID of the column defined above.', 'e-com-addons' ),
			]
		);

		$cells_repeater->add_control(
			'cell_type',
			[
				'label'   => esc_html__( 'Cell Type', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'   => esc_html__( 'Standard Text', 'e-com-addons' ),
					'image'  => esc_html__( 'Image', 'e-com-addons' ),
					'icon'   => esc_html__( 'Icon', 'e-com-addons' ),
					'button' => esc_html__( 'Button', 'e-com-addons' ),
					'badge'  => esc_html__( 'Status Badge', 'e-com-addons' ),
				],
			]
		);

		$cells_repeater->add_control(
			'cell_text',
			[
				'label'     => esc_html__( 'Text Content', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'Cell value', 'e-com-addons' ),
				'condition' => [
					'cell_type' => 'text',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$cells_repeater->add_control(
			'cell_image',
			[
				'label'     => esc_html__( 'Cell Image', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'cell_type' => 'image',
				],
			]
		);

		$cells_repeater->add_control(
			'image_size',
			[
				'label'     => esc_html__( 'Image Size', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => [
					'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'e-com-addons' ),
					'medium'    => esc_html__( 'Medium (300x300)', 'e-com-addons' ),
					'full'      => esc_html__( 'Full Size', 'e-com-addons' ),
				],
				'condition' => [
					'cell_type' => 'image',
				],
			]
		);

		$cells_repeater->add_control(
			'cell_icon',
			[
				'label'     => esc_html__( 'Cell Icon', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-check',
					'library' => 'solid',
				],
				'condition' => [
					'cell_type' => 'icon',
				],
			]
		);

		$cells_repeater->add_control(
			'btn_text',
			[
				'label'     => esc_html__( 'Button Text', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'View Details', 'e-com-addons' ),
				'condition' => [
					'cell_type' => 'button',
				],
			]
		);

		$cells_repeater->add_control(
			'btn_url',
			[
				'label'     => esc_html__( 'Button Link URL', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'condition' => [
					'cell_type' => 'button',
				],
			]
		);

		$cells_repeater->add_control(
			'badge_text',
			[
				'label'     => esc_html__( 'Badge Text', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'New', 'e-com-addons' ),
				'condition' => [
					'cell_type' => 'badge',
				],
			]
		);

		$cells_repeater->add_control(
			'badge_style_type',
			[
				'label'     => esc_html__( 'Badge Style Type', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'popular',
				'options'   => [
					'popular'     => esc_html__( 'Popular (Indigo)', 'e-com-addons' ),
					'best-seller' => esc_html__( 'Best Seller (Orange)', 'e-com-addons' ),
					'featured'    => esc_html__( 'Featured (Emerald)', 'e-com-addons' ),
					'new'         => esc_html__( 'New (Sky Blue)', 'e-com-addons' ),
					'custom'      => esc_html__( 'Custom Styles', 'e-com-addons' ),
				],
				'condition' => [
					'cell_type' => 'badge',
				],
			]
		);

		$cells_repeater->add_control(
			'badge_bg_color',
			[
				'label'     => esc_html__( 'Badge Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'condition' => [
					'cell_type'        => 'badge',
					'badge_style_type' => 'custom',
				],
			]
		);

		$cells_repeater->add_control(
			'badge_text_color',
			[
				'label'     => esc_html__( 'Badge Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => [
					'cell_type'        => 'badge',
					'badge_style_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'table_cells',
			[
				'label'       => esc_html__( 'Populate Cells', 'e-com-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $cells_repeater->get_controls(),
				'title_field' => 'Row {{{ row_index }}} - Column {{{ col_id }}}',
				'default'     => [
					// Row 1
					[ 'row_index' => 1, 'col_id' => 'col_1', 'cell_type' => 'text', 'cell_text' => 'Projects Limit' ],
					[ 'row_index' => 1, 'col_id' => 'col_2', 'cell_type' => 'text', 'cell_text' => '5 Projects' ],
					[ 'row_index' => 1, 'col_id' => 'col_3', 'cell_type' => 'text', 'cell_text' => '50 Projects' ],
					// Row 2
					[ 'row_index' => 2, 'col_id' => 'col_1', 'cell_type' => 'text', 'cell_text' => 'Support Hours' ],
					[ 'row_index' => 2, 'col_id' => 'col_2', 'cell_type' => 'badge', 'badge_text' => 'Email Only', 'badge_style_type' => 'new' ],
					[ 'row_index' => 2, 'col_id' => 'col_3', 'cell_type' => 'badge', 'badge_text' => '24/7 Phone', 'badge_style_type' => 'featured' ],
					// Row 3
					[ 'row_index' => 3, 'col_id' => 'col_1', 'cell_type' => 'text', 'cell_text' => 'Sign Up' ],
					[ 'row_index' => 3, 'col_id' => 'col_2', 'cell_type' => 'button', 'btn_text' => 'Get Basic', 'btn_url' => [ 'url' => '#' ] ],
					[ 'row_index' => 3, 'col_id' => 'col_3', 'cell_type' => 'button', 'btn_text' => 'Go Pro', 'btn_url' => [ 'url' => '#' ] ],
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// SETTINGS / FEATURES TAB
		// ==========================================
		$this->start_controls_section(
			'section_features',
			[
				'label' => esc_html__( 'Table Features', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_search',
			[
				'label'        => esc_html__( 'Enable Search Field', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'search_placeholder',
			[
				'label'     => esc_html__( 'Search Placeholder', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Search rows...', 'e-com-addons' ),
				'condition' => [
					'show_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_sorting',
			[
				'label'        => esc_html__( 'Enable Column Sorting', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'sticky_header',
			[
				'label'        => esc_html__( 'Sticky Table Header', 'e-com-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'sticky_offset',
			[
				'label'     => esc_html__( 'Sticky Offset (px)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ecma-table-sticky thead th' => 'top: {{SIZE}}px;',
				],
				'condition' => [
					'sticky_header' => 'yes',
				],
			]
		);

		$this->add_control(
			'responsive_mode',
			[
				'label'   => esc_html__( 'Mobile Responsive Mode', 'e-com-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'scroll',
				'options' => [
					'scroll' => esc_html__( 'Horizontal Scroll', 'e-com-addons' ),
					'stack'  => esc_html__( 'Stacked Layout (Cards)', 'e-com-addons' ),
				],
			]
		);

		$this->end_controls_section();

		// ==========================================
		// STYLE TAB
		// ==========================================

		// Table Box style
		$this->start_controls_section(
			'section_style_table',
			[
				'label' => esc_html__( 'Table Layout & Border', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_width',
			[
				'label'      => esc_html__( 'Table Width', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [ 'min' => 10, 'max' => 100 ],
					'px' => [ 'min' => 100, 'max' => 1200 ],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-table-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .ecma-table-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .ecma-table-container',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} .ecma-table-container',
			]
		);

		$this->end_controls_section();

		// Header Styling
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Header Styling', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1f2937',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table thead th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table thead th, {{WRAPPER}} .ecma-data-table thead th a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .ecma-data-table thead th',
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '14',
					'right'  => '16',
					'bottom' => '14',
					'left'   => '16',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-data-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Rows Styling
		$this->start_controls_section(
			'section_style_rows',
			[
				'label' => esc_html__( 'Rows Styling', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_bg_color',
			[
				'label'     => esc_html__( 'Row Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'row_alt_bg_color',
			[
				'label'     => esc_html__( 'Zebra Striping (Alternate Row Color)', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f9fafb',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'row_hover_bg_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f3f4f6',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'row_highlight_bg_color',
			[
				'label'     => esc_html__( 'Highlighted Row Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#fef3c7',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr.ecma-row-highlight' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'row_highlight_text_color',
			[
				'label'     => esc_html__( 'Highlighted Row Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr.ecma-row-highlight td, {{WRAPPER}} .ecma-data-table tbody tr.ecma-row-highlight td a' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'row_highlight_border_color',
			[
				'label'     => esc_html__( 'Highlighted Row Cell Border Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody tr.ecma-row-highlight td' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .ecma-data-table tbody tr',
			]
		);

		$this->end_controls_section();

		// Cells Styling
		$this->start_controls_section(
			'section_style_cells',
			[
				'label' => esc_html__( 'Cell Styling', 'e-com-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cell_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#374151',
				'selectors' => [
					'{{WRAPPER}} .ecma-data-table tbody td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'cell_typography',
				'selector' => '{{WRAPPER}} .ecma-data-table tbody td',
			]
		);

		$this->add_responsive_control(
			'cell_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'    => '12',
					'right'  => '16',
					'bottom' => '12',
					'left'   => '16',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-data-table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'cell_border',
				'selector' => '{{WRAPPER}} .ecma-data-table tbody td',
			]
		);

		$this->end_controls_section();

		// Button Styling
		$this->start_controls_section(
			'section_style_button',
			[
				'label'     => esc_html__( 'Cell Button Styles', 'e-com-addons' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#2563eb',
				'selectors' => [
					'{{WRAPPER}} .ecma-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1d4ed8',
				'selectors' => [
					'{{WRAPPER}} .ecma-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'e-com-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ecma-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '4',
					'right'  => '4',
					'bottom' => '4',
					'left'   => '4',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'e-com-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'    => '8',
					'right'  => '16',
					'bottom' => '8',
					'left'   => '16',
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .ecma-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget frontend HTML view.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Read highlighted rows list
		$highlighted_rows_list = [];
		if ( ! empty( $settings['highlighted_rows'] ) ) {
			$highlighted_rows_list = array_map( 'intval', array_map( 'trim', explode( ',', $settings['highlighted_rows'] ) ) );
		}

		// Responsive layout class wrapper
		$responsive_class = 'ecma-table-' . esc_attr( $settings['responsive_mode'] );
		$sticky_class     = ( 'yes' === $settings['sticky_header'] ) ? 'ecma-table-sticky' : '';
		?>
		<div class="ecma-table-wrapper">

			<!-- Dynamic Live Search -->
			<?php if ( 'yes' === $settings['show_search'] ) : ?>
				<div class="ecma-table-search-container">
					<span class="dashicons dashicons-search ecma-search-icon"></span>
					<input type="text" class="ecma-table-search-input" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>">
				</div>
			<?php endif; ?>

			<!-- Table Element Container -->
			<div class="ecma-table-container <?php echo $responsive_class; ?> <?php echo $sticky_class; ?>">
				<table class="ecma-data-table">
					<thead>
						<tr>
							<?php foreach ( $settings['table_columns'] as $col ) :
								$col_id    = esc_attr( $col['col_id'] );
								$col_style = '';
								if ( ! empty( $col['col_width'] ) ) {
									$col_style .= 'width: ' . esc_attr( $col['col_width'] ) . ';';
								}
								if ( ! empty( $col['col_align'] ) ) {
									$col_style .= 'text-align: ' . esc_attr( $col['col_align'] ) . ';';
								}
								$th_class  = ( 'yes' === $col['highlight_col'] ) ? 'ecma-col-highlight' : '';
								$sortable  = ( 'yes' === $settings['enable_sorting'] ) ? 'data-sortable="true"' : '';
								?>
								<th class="<?php echo $th_class; ?>" style="<?php echo $col_style; ?>" <?php echo $sortable; ?> data-col-id="<?php echo $col_id; ?>">
									<div class="ecma-th-inner">
										<span class="ecma-th-text"><?php echo esc_html( $col['col_title'] ); ?></span>
										<?php if ( 'yes' === $settings['enable_sorting'] ) : ?>
											<span class="ecma-sort-trigger dashicons dashicons-sort"></span>
										<?php endif; ?>
									</div>
									<?php if ( 'yes' === $col['recommended_badge'] ) : ?>
										<span class="ecma-recommended-badge"><?php echo esc_html( $col['recommended_badge_text'] ); ?></span>
									<?php endif; ?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$this->render_manual_rows( $settings, $highlighted_rows_list );
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Render table rows using Manual repeater inputs.
	 */
	protected function render_manual_rows( $settings, $highlighted_rows_list ) {
		// Group cells by Row Index
		$rows = [];
		if ( ! empty( $settings['table_cells'] ) ) {
			foreach ( $settings['table_cells'] as $cell ) {
				$row_idx = intval( $cell['row_index'] );
				$col_id  = trim( $cell['col_id'] );
				$rows[ $row_idx ][ $col_id ] = $cell;
			}
		}

		ksort( $rows ); // Sort rows chronologically

		foreach ( $rows as $row_idx => $cells_in_row ) {
			$row_class = in_array( $row_idx, $highlighted_rows_list, true ) ? 'ecma-row-highlight' : '';
			echo '<tr class="' . esc_attr( $row_class ) . '" data-row-idx="' . intval( $row_idx ) . '">';

			foreach ( $settings['table_columns'] as $col ) {
				$col_id   = trim( $col['col_id'] );
				$col_align = ! empty( $col['col_align'] ) ? esc_attr( $col['col_align'] ) : 'left';
				$td_style = 'text-align: ' . $col_align . ';';
				$td_class = ( 'yes' === $col['highlight_col'] ) ? 'ecma-col-highlight' : '';

				echo '<td class="' . $td_class . '" style="' . $td_style . '" data-label="' . esc_attr( $col['col_title'] ) . '">';

				if ( isset( $cells_in_row[ $col_id ] ) ) {
					$cell = $cells_in_row[ $col_id ];
					$this->render_cell_content( $cell );
				} else {
					echo '&nbsp;';
				}

				echo '</td>';
			}

			echo '</tr>';
		}
	}

	/**
	 * Helper function to format and render cell contents securely.
	 */
	protected function render_cell_content( $cell, $post_id = 0 ) {
		$type = ! empty( $cell['cell_type'] ) ? $cell['cell_type'] : 'text';

		switch ( $type ) {
			case 'image':
				$img_url = ! empty( $cell['cell_image']['url'] ) ? esc_url( $cell['cell_image']['url'] ) : '';
				if ( ! empty( $img_url ) ) {
					$radius = isset( $cell['image_border_radius']['size'] ) ? intval( $cell['image_border_radius']['size'] ) : 4;
					echo '<img src="' . $img_url . '" style="border-radius: ' . $radius . 'px; max-width: 100px; height: auto;" class="ecma-cell-img" alt="" />';
				}
				break;

			case 'icon':
				if ( ! empty( $cell['cell_icon']['value'] ) ) {
					echo '<span class="ecma-cell-icon">';
					\Elementor\Icons_Manager::render_icon( $cell['cell_icon'], [ 'aria-hidden' => 'true' ] );
					echo '</span>';
				}
				break;

			case 'button':
				$btn_text   = ! empty( $cell['btn_text'] ) ? esc_html( $cell['btn_text'] ) : esc_html__( 'Click Here', 'e-com-addons' );
				$btn_url    = ! empty( $cell['btn_url']['url'] ) ? esc_url( $cell['btn_url']['url'] ) : '#';
				$btn_target = ( 'yes' === ( $cell['btn_target'] ?? 'no' ) ) ? ' target="_blank" rel="noopener noreferrer"' : '';
				echo '<a href="' . $btn_url . '" class="ecma-btn"' . $btn_target . '>' . $btn_text . '</a>';
				break;

			case 'badge':
				$badge_text = ! empty( $cell['badge_text'] ) ? esc_html( $cell['badge_text'] ) : esc_html__( 'Featured', 'e-com-addons' );
				$badge_type = ! empty( $cell['badge_style_type'] ) ? esc_attr( $cell['badge_style_type'] ) : 'popular';
				
				$badge_style = '';
				if ( 'custom' === $badge_type ) {
					$bg  = ! empty( $cell['badge_bg_color'] ) ? esc_attr( $cell['badge_bg_color'] ) : '#6366f1';
					$txt = ! empty( $cell['badge_text_color'] ) ? esc_attr( $cell['badge_text_color'] ) : '#ffffff';
					$badge_style = ' style="background-color: ' . $bg . '; color: ' . $txt . ';"';
				}
				
				echo '<span class="ecma-badge ecma-badge-' . $badge_type . '"' . $badge_style . '>' . $badge_text . '</span>';
				break;

			case 'text':
			default:
				echo wp_kses_post( $cell['cell_text'] );
				break;
		}
	}
}
