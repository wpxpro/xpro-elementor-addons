<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Scroll_To_Top extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-scroll-to-top';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Scroll To Top', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-enter xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'scroll', 'top' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {

		return array( 'animate' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'              => esc_html__( 'Layout', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'inline',
				'options'            => array(
					'inline' => esc_html__( 'Inline', 'xpro-elementor-addons' ),
					'fixed'  => esc_html__( 'Fixed', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Scroll To Top', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => array(
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				),
				'separator'   => 'before',
				'recommended' => array(
					'fa-solid' => array(
						'arrow-up',
						'arrow-circle-up',
						'chevron-up',
					),
				),
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => __( 'Icon Position', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Before', 'xpro-elementor-addons' ),
					'right' => __( 'After', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'scroll_top_offset',
			array(
				'label'              => __( 'Show after page scroll (px)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default'            => array(
					'size' => 150,
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'layout' => 'fixed',
				),
			)
		);

		$this->add_control(
			'animated_duration',
			array(
				'label'              => __( 'Animate Speed(s)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'            => array(
					'size' => 5,
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'inline_align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'    => array(
					'layout' => 'inline',
				),
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			)
		);

		$this->add_control(
			'fixed_align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'right',
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'layout' => 'fixed',
				),
			)
		);

		$this->add_control(
			'button_css_id',
			array(
				'label'       => __( 'Button ID', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'xpro-elementor-addons' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'xpro-elementor-addons' ),
				'separator'   => 'before',

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'btn_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'btn_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .xpro-elementor-scroll-top-btn-txt',
			)
		);

		$this->add_control(
			'btn_offset_toggle',
			array(
				'label'        => __( 'Offset', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => 'fixed',
				),
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'btn_offset_x',
			array(
				'label'      => __( 'Offset Left', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-fixed.xpro-elementor-scroll-top-btn-fixed-align-left'  => ' left : {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-fixed.xpro-elementor-scroll-top-btn-fixed-align-right' => ' right : {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'btn_offset_toggle' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'btn_offset_y',
			array(
				'label'      => __( 'Offset Bottom', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-fixed.xpro-elementor-scroll-top-btn-fixed-align-left,
					{{WRAPPER}} .xpro-elementor-scroll-top-btn-fixed.xpro-elementor-scroll-top-btn-fixed-align-right' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'btn_offset_toggle' => 'yes',
				),
			)
		);
		$this->end_popover();

		$this->add_control(
			'scroll_top_animation',
			array(
				'label'              => esc_html__( 'Animation', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::ANIMATION,
				'render_type'        => 'template',
				'frontend_available' => true,
				'default'            => 'fadeIn',
				'condition'          => array(
					'layout' => 'fixed',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'btn_Icon_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-inner > i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-inner > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'btn_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-txt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-scroll-top-btn',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_hv_color',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn:hover .xpro-elementor-scroll-top-btn-inner > i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn:hover .xpro-elementor-scroll-top-btn-inner > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'btn_hv_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn:hover .xpro-elementor-scroll-top-btn-txt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'btn_bg_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-scroll-top-btn:hover',
			)
		);

		$this->add_control(
			'btn_hv_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .xpro-elementor-scroll-top-btn',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-elementor-scroll-top-btn',
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_heading_options',
			array(
				'label'     => esc_html__( 'Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'btn_icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 300,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-inner > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-inner > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
				'condition'  => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'     => __( 'Icon Spacing', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-scroll-top-btn-inner' => 'grid-gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'scroll-to-top/layout/frontend.php';
	}
}
