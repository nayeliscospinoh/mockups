<?php 
/*
Widget Name: Scroll Navigation
Description: navigation bar Scrolling Effect scroll event.
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;

use TheplusAddons\L_Theplus_Element_Load;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class L_ThePlus_Scroll_Navigation extends Widget_Base {
		
	public function get_name() {
		return 'tp-scroll-navigation';
	}

    public function get_title() {
        return esc_html__('Scroll Navigation', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-sort theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-creatives');
    }
	
    protected function register_controls() {
		/* Scroll Navigation Menu List Start*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Scroll Navigation', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'scroll_navigation_style',
			[
				'label' => esc_html__( 'Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1' => esc_html__( 'Style 1', 'tpebl' ),					
					'style-2' => esc_html__( 'Style 2 (Pro)', 'tpebl' ),					
					'style-3' => esc_html__( 'Style 3 (Pro)', 'tpebl' ),					
					'style-4' => esc_html__( 'Style 4 (Pro)', 'tpebl' ),					
					'style-5' => esc_html__( 'Style 5 (Pro)', 'tpebl' ),					
				],
			]
		);
		$this->add_control(
			'scroll_navigation_direction',
			[
				'label' => esc_html__( 'Direction', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [									
					'left'  => esc_html__( 'Middle Left', 'tpebl' ),
					'right'  => esc_html__( 'Middle Right', 'tpebl' ),
					'top'  => esc_html__( 'Top', 'tpebl' ),					
					'top_left'  => esc_html__( 'Top Left', 'tpebl' ),					
					'top_right'  => esc_html__( 'Top Right', 'tpebl' ),
					'bottom'  => esc_html__( 'Bottom', 'tpebl' ),	
					'bottom_left'  => esc_html__( 'Bottom Left', 'tpebl' ),	
					'bottom_right'  => esc_html__( 'Bottom Right', 'tpebl' ),	
				],
				'condition'    => [
					'scroll_navigation_style' => ['style-1'],
				],
			]
		);
		$this->add_control(
			'scroll_navigation_direction_st4',
			[
				'label' => esc_html__( 'Direction', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [									
					'left'  => esc_html__( 'Middle Left (Pro)', 'tpebl' ),
					'right'  => esc_html__( 'Middle Right (Pro)', 'tpebl' ),					
				],
				'condition'    => [
					'scroll_navigation_style' => ['style-2','style-4'],
				],
			]
		);		
		$this->add_control(
			'scroll_navigation_direction_inner',
			[
				'label' => esc_html__( 'Position', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'p_center',
				'options' => [									
					'p_left'  => esc_html__( 'Left (Pro)', 'tpebl' ),
					'p_right'  => esc_html__( 'Right (Pro)', 'tpebl' ),
					'p_center'  => esc_html__( 'Center (Pro)', 'tpebl' ),
				],
				'condition'    => [
					'scroll_navigation_direction' => ['top','bottom'],
					'scroll_navigation_style!' => ['style-2','style-4'],
				],
			]
		);
		$this->add_control(
			'scroll_navigation_display_counter',
			[
				'label' => esc_html__( 'Display Counter', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
				'condition'    => [					
					'scroll_navigation_style' => ['style-2','style-4'],
				],
				
			]
		);
		$this->add_control(
			'scroll_navigation_display_counter_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'scroll_navigation_style' => ['style-2','style-4'],
					'scroll_navigation_display_counter' => [ 'yes' ],
				],
			]
		);
		$this->add_control(
			'scroll_navigation_tooltip_display_style',
			[
				'label' => esc_html__( 'Tooltip Display Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'on-default',
				'options' => [									
					'on-hover'  => esc_html__( 'On Hover (Pro)', 'tpebl' ),
					'on-active-section'  => esc_html__( 'On Active Section (Pro)', 'tpebl' ),
					'on-default'  => esc_html__( 'Default', 'tpebl' ),
				],
				'separator' => 'before',
			]
		);
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'scroll_navigation_section_id',
			[
				'label' => esc_html__( 'Section Id', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'section-id',
			]
		);
		$repeater->add_control(
			'display_tool_tip',
			[
				'label' => esc_html__( 'Tooltip', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
				
			]
		);
		$repeater->add_control(
			'tooltip_menu_title',
			[
				'label' => esc_html__( 'Tooltip Title', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => ['active'   => true,],
				'condition' => [				
					'display_tool_tip' => 'yes',
				],
			]
		);
		
		$repeater->add_control(
			'display_tool_tip_icon',
			[
				'label' => esc_html__( 'Icon', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'loop_icon_style',
			[
				'label' => esc_html__( 'Icon Font', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'font_awesome',
				'options' => [
					'font_awesome'  => esc_html__( 'Font Awesome', 'tpebl' ),
					'font_awesome_5'  => esc_html__( 'Font Awesome 5', 'tpebl' ),
					'icon_mind' => esc_html__( 'Icons Mind (Pro)', 'tpebl' ),
				],
				'condition' => [
					'display_tool_tip_icon' => 'yes',
				],
			]
		);
		$repeater->add_control(
			'loop_icon_fontawesome',
			[
				'label' => esc_html__( 'Icon Library', 'tpebl' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-bank',
				'condition' => [
					'loop_icon_style' => 'font_awesome',					
					'display_tool_tip_icon' => 'yes',
				],	
			]
		);
		$repeater->add_control(
			'loop_icon_fontawesome_5',
			[
				'label' => esc_html__( 'Icon Library', 'tpebl' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'solid',
				],
				'condition' => [
					'loop_icon_style' => 'font_awesome_5',					
					'display_tool_tip_icon' => 'yes',
				],
			]
		);
		$repeater->add_control(
			'loop_icon_mind_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'loop_icon_style' => 'icon_mind',					
					'display_tool_tip_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'scroll_navigation_menu_list',
			[
				'label' => esc_html__( 'Scroll Navigation List', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),			
				'default' => [
					[
						'loop_image_icon' => 'icon',
						'loop_icon_style' => 'font_awesome',
						'loop_icon_fontawesome' => 'fa fa-dot-circle-o',
					],
					
				],	
				'separator' => 'before',
			]
		);
		$this->add_control(
			'pagescroll_connection',
			[
				'label' => esc_html__( 'Page Scroll Connection', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'pagescroll_connection_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'pagescroll_connection' => [ 'yes' ],
				],
			]
		);
		$this->end_controls_section();
		/* Scroll Navigation Menu List End*/
		/* Scroll Navigation Style Start*/
		/* Scroll Navigation Style start*/
		$this->start_controls_section(
            'section_navigation_styling',
            [
                'label' => esc_html__('Navigation Style', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );						
		$this->add_responsive_control(
			'navigation_icon_height_width',
			[
				'label' => esc_html__( 'Icon Height/Width', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot,{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot:hover,{{WRAPPER}} .theplus-scroll-navigation a.theplus-scroll-navigation__item._mPS2id-h.highlight .theplus-scroll-navigation__dot,
					{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot:before,{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot:hover:before,{{WRAPPER}} .theplus-scroll-navigation a.theplus-scroll-navigation__item._mPS2id-h.highlight .theplus-scroll-navigation__dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner' => 'min-width: {{SIZE}}{{UNIT}};'
				],
				'condition'    => [
				'scroll_navigation_style' => 'style-1',
				],
			]
		);	
		$this->add_responsive_control(
			'navigation_icon_spacing_other_all_margin',
			[
				'label' => esc_html__( 'Icon Spacing', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.s_n_top_left a.theplus-scroll-navigation__item,
					{{WRAPPER}} .theplus-scroll-navigation.s_n_top_right a.theplus-scroll-navigation__item,
					{{WRAPPER}} .theplus-scroll-navigation.s_n_bottom_left a.theplus-scroll-navigation__item,
					{{WRAPPER}} .theplus-scroll-navigation.s_n_bottom_right a.theplus-scroll-navigation__item,
					{{WRAPPER}} .theplus-scroll-navigation.s_n_left a.theplus-scroll-navigation__item,
					{{WRAPPER}} .theplus-scroll-navigation.s_n_right a.theplus-scroll-navigation__item' => 'margin-top: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'    => [
				'scroll_navigation_direction' => ['left','right','top_left','top_right','bottom_left','bottom_right'],
				'scroll_navigation_direction_st4' => ['left','right'],
				'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->start_controls_tabs( 'scroll_navigation_icon_style' );
		$this->start_controls_tab(
			'scroll_navigation_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'scroll_navigation_icon_color_normal',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__dot' => 'color: {{VALUE}}',
				],
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'scroll_navigation_icon_border_normal',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__dot',
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->end_controls_tab();		
		$this->start_controls_tab(
			'scroll_navigation_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'scroll_navigation_icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__dot:hover,
					{{WRAPPER}} .theplus-scroll-navigation.style-1 a.theplus-scroll-navigation__item._mPS2id-h.highlight .theplus-scroll-navigation__dot' => 'background-color: {{VALUE}}',
				],
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'scroll_navigation_icon_border_hover',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__dot:hover,
					{{WRAPPER}} .theplus-scroll-navigation.style-1 a.theplus-scroll-navigation__item._mPS2id-h.highlight .theplus-scroll-navigation__dot',
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'sc_style_pro_option',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [				
					'scroll_navigation_style!' => 'style-1',
				],
			]
		);		
		$this->end_controls_section();
		/* Scroll Navigation Style End*/
		/* Scroll Navigation Icon Background Style start*/
		$this->start_controls_section(
            'section_navigation_background_styling',
            [
                'label' => esc_html__('Navigation Background', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'section_navigation_background_styling_option',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [				
					'scroll_navigation_style!' => 'style-1',
				],
			]
		);
		$this->add_control(
			'scroll_nav_icon_background_style',
			[
				'label' => esc_html__( 'Navigation Background', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'no',
				'condition'    => [				
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->start_controls_tabs( 'scroll_nav_icon_background' );
		$this->start_controls_tab(
			'scroll_nav_icon_background_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'scroll_nav_icon_background_normal',
				'label' => esc_html__( 'Icon Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item',
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'scroll_nav_icon_background_hover',
			[
				'label' => esc_html__( 'hover', 'tpebl' ),
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'scroll_nav_icon_background_hover',
				'label' => esc_html__( 'Icon Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner a.theplus-scroll-navigation__item:hover,
				{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item.highlight',
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'scroll_nav_icon_background_border_heading',
			[
				'label' => esc_html__( 'Icon Background Border', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->start_controls_tabs( 'scroll_nav_icon_background_border' );
		$this->start_controls_tab(
			'scroll_nav_icon_background_border_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'scroll_nav_icon_background_border__normal',
				'label' => esc_html__( 'Border', 'tpebl' ),				
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item',
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
				'separator' => 'after',
			]
		);
		$this->add_responsive_control(
			'scroll_nav_icon_background_border_radious_normal',
			[
				'label' => esc_html__( 'Icon Background Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],				
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'scroll_nav_icon_background_border_hover',
			[
				'label' => esc_html__( 'hover', 'tpebl' ),
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->add_control(
			'scroll_nav_icon_background_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'tpebl' ),
				'type' => \Elementor\Controls_Manager::COLOR,					
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner a.theplus-scroll-navigation__item:hover,
				{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item.highlight' => 'border-color: {{VALUE}}',
				],	
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'scroll_nav_icon_background_border_radious_hover',
			[
				'label' => esc_html__( 'Icon Background Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner a.theplus-scroll-navigation__item:hover,
				{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item.highlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'scroll_navigation_style' => 'style-1',
					'scroll_nav_icon_background_style' => 'yes',
				],				
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_nav_icon_background_shadow',
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation.style-1 .theplus-scroll-navigation__inner .theplus-scroll-navigation__item',
				'condition' => [
					'scroll_navigation_style' => 'style-1',
				],
				
			]
		);
		$this->end_controls_section();
		/* Scroll Navigation Icon Background Style end*/
		/* Scroll Navigation Tooltip Start*/
		$this->start_controls_section(
            'section_navigation_tooltip_styling',
            [
                'label' => esc_html__('Tooltip', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'section_navigation_tooltip_styling_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'scroll_navigation_style!' => 'style-1',
				],
			]
		);
		$this->add_responsive_control(
			'navigation_tooltip_margin',
			[
				'label' => esc_html__( 'Navigation Tooltip Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner .tooltiptext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_responsive_control(
			'navigation_tooltip_padding',
			[
				'label' => esc_html__( 'Navigation Tooltip Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner .tooltiptext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],				
			]
		);
		$this->add_responsive_control(
			'scroll_navigation_tooltip_align',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'prefix_class' => 'text-%s',
				'separator' => 'after',
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_tooltip_typography',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot span.tooltiptext',
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_responsive_control(
			'navigation_tooltip_svg_icon',
			[
				'label' => esc_html__( 'Svg Icon Size', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot span.tooltiptext svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'navigation_tooltip_font_color_normal',
			[
				'label' => esc_html__( 'Font Color Normal', 'tpebl' ),
				'type' => Controls_Manager::COLOR,					
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation__dot .tooltiptext' => 'color: {{VALUE}}',
					'{{WRAPPER}} .theplus-scroll-navigation__dot .tooltiptext svg' => 'fill: {{VALUE}}',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'navigation_tooltip_font_color_hover',
			[
				'label' => esc_html__( 'Font Color Hover', 'tpebl' ),
				'type' => Controls_Manager::COLOR,					
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation__dot .tooltiptext:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .theplus-scroll-navigation__dot .tooltiptext:hover svg' => 'fill: {{VALUE}}',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'navigation_tooltip_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot .tooltiptext' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot span.tooltiptext:after' => 'border-right-color:{{VALUE}}',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_responsive_control(
			'navigation_tooltip_height',
			[
				'label' => esc_html__( 'Tooltip Height', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 35,
						'max' => 200,
						'step' => 1,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__dot span.tooltiptext' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);
		$this->add_control(
			'scroll_nav_tooltip_arrow',
			[
				'label' => esc_html__( 'Tooltip Arrow', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'yes',
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
			]
		);		
		/*tooltip shadow start*/
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_nav_tooltip_shadow',
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation__dot span.tooltiptext',
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],				
			]
		);
		/*tooltip shadow end*/
		$this->add_responsive_control(
			'scroll_nav_tooltip_border_radious',
			[
				'label' => esc_html__( 'Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation__dot .tooltiptext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'    => [
					'scroll_navigation_style' => 'style-1',
				],
				'separator' => 'after',
			]
		);
		$this->end_controls_section();
		/* Scroll Navigation Tooltip End*/
		/* Scroll Navigation Display Counter Start*/
		$this->start_controls_section(
            'section_navigation_dispaly_counter_styling',
            [
                'label' => esc_html__('Display Counter', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition'    => [
				'scroll_navigation_style' => ['style-2','style-4'],
				],
            ]
        );
		$this->add_control(
			'section_navigation_dispaly_counter_styling_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
			]
		);
		$this->end_controls_section();
		/* Scroll Navigation Display Counter Start*/
		/*background option*/
		$this->start_controls_section(
            'section_bg_option_styling',
            [
                'label' => esc_html__('Whole Background Style', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'navigation_icon_padding',
			[
				'label' => esc_html__( 'Whole Navigation Offset', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_responsive_control(
			'scroll_nav_background_padding',
			[
				'label' => esc_html__( 'Whole Navigation Inner Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		/*background background option start*/
		$this->add_control(
			'scroll_nav_background_style',
			[
				'label' => esc_html__( 'Background', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'no',				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'scroll_nav_background',
				'label' => esc_html__( 'Background', 'tpebl' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner',
				'condition' => [					
					'scroll_nav_background_style' => 'yes',
				],
			]
		);		
		/*background background option end*/
		/*background border option start*/
		$this->add_control(
			'scroll_nav_background_border',
			[
				'label' => esc_html__( 'Box Border', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),
				'default' => 'no',				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'scroll_nav_background_border',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner',
				'condition' => [					
					'scroll_nav_background_border' => 'yes',
				],
			]
			
		);
		$this->add_responsive_control(
			'scroll_nav_background_border_radious',
			[
				'label' => esc_html__( 'Border Radius', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [					
					'scroll_nav_background_border' => 'yes',
				],
				'separator' => 'after',
			]
		);
				
		/*background border option end*/
		/*background shadow start*/
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'scroll_nav_background_shadow',
				'selector' => '{{WRAPPER}} .theplus-scroll-navigation .theplus-scroll-navigation__inner',
				
			]
		);
		/*background shadow end*/
		
		$this->end_controls_section();
		/*background option end*/
		/*Extra Option Style*/
		$this->start_controls_section(
			'extra_option_style_section',
			[
				'label' => esc_html__( 'Extra Options', 'tpebl' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'show_scroll_window_offset',
			[
				'label' => esc_html__( 'Show Menu Scroll Offset', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tpebl' ),
				'label_off' => esc_html__( 'Hide', 'tpebl' ),				
				'default' => 'no',
			]
		);
		$this->add_control(
			'show_scroll_window_offset_options',
			[
				'label' => esc_html__( 'Unlock more possibilities', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => theplus_pro_ver_notice(),
				'classes' => 'plus-pro-version',
				'condition'    => [
					'show_scroll_window_offset' => [ 'yes' ],
				],
			]
		);
		$this->end_controls_section();
		/*Extra Option Style*/
		/* Scroll Navigation Style End*/
	}
	
	 protected function render() {
		$settings = $this->get_settings_for_display();			
		$scroll_navigation_style = $settings['scroll_navigation_style'];
		$scroll_navigation_direction = $settings['scroll_navigation_direction'];	
		$scroll_navigation_tooltip_display_style = $settings['scroll_navigation_tooltip_display_style'];	
		$scroll_navigation_menu_list = $settings['scroll_navigation_menu_list'];			
		
		$scroll_style='';
		if($scroll_navigation_style == 'style-1'){
			$scroll_style = 'style-1';
		}
		
		$direction_class='';
		if($scroll_navigation_direction == 'top'){
			$direction_class = 's_n_top';
		}else if($scroll_navigation_direction == 'top_left'){
			$direction_class = 's_n_top_left';
		}else if($scroll_navigation_direction == 'top_right'){
			$direction_class = 's_n_top_right';
		}else if($scroll_navigation_direction == 'bottom'){
			$direction_class = 's_n_bottom';
		}else if($scroll_navigation_direction == 'bottom_left'){
			$direction_class = 's_n_bottom_left';
		}else if($scroll_navigation_direction == 'bottom_right'){
			$direction_class = 's_n_bottom_right';
		}else if($scroll_navigation_direction == 'left'){
			$direction_class = 's_n_left';
		}else if($scroll_navigation_direction == 'right'){
			$direction_class = 's_n_right';
		}
		
		
		$display_tooltip_style_class='';
		if($scroll_navigation_tooltip_display_style == 'on-default'){
			$display_tooltip_style_class = 'on_default';
		}
		
		$tooltip_arrow='';
		if($settings['scroll_nav_tooltip_arrow'] == 'yes'){
			$tooltip_arrow = 'sn_t_a_e';
		}else if($settings['scroll_nav_tooltip_arrow'] == 'no'){
			$tooltip_arrow = 'sn_t_a_d';
		}
		
		if ( $settings['scroll_navigation_menu_list'] ) {
			$uid=uniqid('scroll');
			$scroll_navigation = '<div class="theplus-scroll-navigation '.esc_attr($uid).' '.esc_attr($scroll_style).' '.esc_attr($direction_class).' " data-uid="'.esc_attr($uid).'" >';			
			$scroll_navigation .='<div class="theplus-scroll-navigation__inner">';		
			
			foreach (  $settings['scroll_navigation_menu_list'] as $item ) {
				$scroll_navigation .= '<a href="#'.$item['scroll_navigation_section_id'].'" class="theplus-scroll-navigation__item _mPS2id-h" >';
				$tooltip_menu_title=$tooltip_title=$tooltip_icon=$icons='';
				
						$s_icon_img ='';
						if($item["loop_icon_style"]=='font_awesome'){
							$icons = $item["loop_icon_fontawesome"];
						}else if($item["loop_icon_style"]=='font_awesome_5'){
							ob_start();
							\Elementor\Icons_Manager::render_icon( $item['loop_icon_fontawesome_5'], [ 'aria-hidden' => 'true' ]);
							$icons = ob_get_contents();
							ob_end_clean();
						}else{
							$icons = '';
						}
						if(!empty($icons)){
							if(!empty($item["loop_icon_style"]) && $item["loop_icon_style"]=='font_awesome_5'){
								$s_icon_img = '<span class="scroll-tooltip-icon ">'.$icons.'</span>';
							}else{
								$s_icon_img = '<i class=" '.esc_attr($icons).' scroll-tooltip-icon "></i>';
							}
							
						}
					
					if(!empty($item["tooltip_menu_title"] || $icons)){
						$tooltip_title = '<span class="tooltiptext '.$direction_class.' '.$tooltip_arrow.' '.$settings['scroll_navigation_tooltip_align'].' '.$display_tooltip_style_class.'">'.$s_icon_img.' '.$item["tooltip_menu_title"].'</span>';
					}
				
				$scroll_navigation .= '<div class="theplus-scroll-navigation__dot">'.$tooltip_title.'</div>';
				$scroll_navigation .= '</a>';
			}			
			$scroll_navigation .= '</div>';
			$scroll_navigation .= '</div>';
			echo $scroll_navigation;
		} 
	}
	
    protected function content_template() {
		
    }

}
