<?php 
/*
Widget Name: Post Meta
Description: Post Meta
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
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

use TheplusAddons\L_Theplus_Element_Load;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class L_ThePlus_Post_Meta extends Widget_Base {
		
	public function get_name() {
		return 'tp-post-meta';
	}

    public function get_title() {
        return esc_html__('Post Meta', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-info-circle theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-builder');
    }

    protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Post Meta', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'metaLayout',
			[
				'label' => esc_html__( 'Layout', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => [
					'layout-1'  => esc_html__( 'Layout 1', 'tpebl' ),					
					'layout-2' => esc_html__( 'Layout 2', 'tpebl' ),
				],
			]
		);
		$repeater = new \Elementor\Repeater();		
		$repeater->add_control(
			'sortfield',[
				'label' => esc_html__( 'Select Field','tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date','tpebl' ),
					'category' => esc_html__( 'Taxonomies','tpebl' ),
					'author' => esc_html__( 'Author','tpebl' ),
					'comments' => esc_html__( 'Comments','tpebl' ),			
				],
			]
		);
		$repeater->add_control(
			'category_taxonomies',
			[
				'label' => esc_html__( 'Taxonomies', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'options' => l_theplus_get_post_taxonomies(),
				'default' => 'category',
				'condition' => [
					'sortfield' => 'category',
				],
			]
		);
		$repeater->add_control(
			'category_taxonomies_load',[
				'label' => esc_html__( 'Show','tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'All','tpebl' ),
					'bypost' => esc_html__( 'Current Post','tpebl' ),
				],
				'condition' => [
					'sortfield' => 'category',
				],
			]
		);
		$repeater->add_control(
			'category_taxonomies_load_cat_tag',[
				'label' => esc_html__( 'Type','tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tpcategory',
				'options' => [
					'tpcategory' => esc_html__( 'Category','tpebl' ),
					'tptag' => esc_html__( 'Tag','tpebl' ),
				],
				'condition' => [
					'sortfield' => 'category',
					'category_taxonomies_load' => 'bypost',
				],
			]
		);
		$this->add_control(
            'metaSort',
            [
				'label' => esc_html__( 'Sortable', 'tpebl' ),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'sortfield' => 'date',
                    ],
                    [
                        'sortfield' => 'category',
                    ],						
					[
                       'sortfield' => 'author',
                    ],	
                    [
                        'sortfield' => 'comments',
                    ],

                ],
                'separator' => 'before',
				'fields' => $repeater->get_controls(),
                'title_field' => '{{{ sortfield }}}',				
            ]
        ); 
		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Box Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',		
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info' => 'justify-content: {{VALUE}};',
				],		
				'separator' => 'before',
				
			]
		);
		$this->add_responsive_control(
			'contentalignment',
			[
				'label' => esc_html__( 'Content Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',		
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'tpebl' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tpebl' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'tpebl' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info span' => 'align-items: {{VALUE}} !important;justify-content: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_section();
		/*Meta Sort Content*/
		/*Meta Info Style*/
		$this->start_controls_section(
            'section_meta_info_style',
            [
                'label' => esc_html__('Meta Info', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metaTypo',
				'label' => esc_html__( 'Label Typography', 'tpebl' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-label',
			]
		);
		$this->add_control(
			'metaColor',
			[
				'label' => esc_html__( 'Label Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'metavalueTypo',
				'label' => esc_html__( 'Value Typography', 'tpebl' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-value',
			]
		);
		$this->add_control(
			'metavalueColor',
			[
				'label' => esc_html__( 'Value Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-value' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'metatopoffset',
			[
				'label' => esc_html__( 'Top Offset', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],	
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-value,{{WRAPPER}} .tp-post-meta-info .tp-meta-category-list' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		/*Meta Info Style*/
		/*Seprator Style*/
		$this->start_controls_section(
            'section_separator_style',
            [
                'label' => esc_html__('Separator', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'metaLayout' => 'layout-1',
				],
            ]
        );		
		$this->add_control(
			'separator',
			[
				'label' => esc_html__( 'Separator', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( ',', 'tpebl' ),
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner>span:not(:last-child):after' => 'content:"{{VALUE}}";',
				],
			]
		);	
		$this->add_control(
			'sepColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner>span:not(:last-child):after' => 'color: {{VALUE}}',
				],
			]
		);	
		$this->add_responsive_control(
			'sepSize',
			[
				'label' => esc_html__( 'Size', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],	
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner>span:not(:last-child):after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'sepLeftSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Left Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],		
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner>span:not(:last-child):after' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
            ]
        );
		$this->add_responsive_control(
            'sepRightSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Right Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner>span:not(:last-child):after' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
            ]
        );		
		$this->end_controls_section();
		/*Seprator Style*/
		/*Post Date Style*/
		$this->start_controls_section(
            'section_post_date_style',
            [
                'label' => esc_html__('Post Date', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

         $this->add_control(
            'showDate',
            [
				'label' => esc_html__( 'Show Post Date', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'yes',	
			]
        );
		$this->add_control(
			'datePrefix',
			[
				'label' => esc_html__( 'Prefix Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',	
				'placeholder' => esc_html__( 'Enter Prefix', 'tpebl' ),	
				'condition' => [
					'showDate' => 'yes',
				],
			]
		);
         $this->add_control(
			'dateIcon',
			[
				'label' => esc_html__( 'Select Date Icon', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'tpebl'),
					'fas fa-clock' => esc_html__('Clock 1', 'tpebl'),
					'far fa-clock' => esc_html__('Clock 2', 'tpebl'),
					'fas fa-calendar-alt' => esc_html__('Calendar 1', 'tpebl'),
					'far fa-calendar-alt' => esc_html__('Calendar 2', 'tpebl'),
					'fas fa-calendar-day' => esc_html__('Calendar 3', 'tpebl'),
				],
				'condition' => [
					'showDate' => 'yes',
				],
			]
		);

         $this->add_responsive_control(
            'dateIconSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],			
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-date i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'showDate' => 'yes',
					'dateIcon!' => 'none',
				],	
            ]
        );	
        $this->start_controls_tabs( 'tabs_icon_style' );
		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),	
				'condition' => [
					'showDate' => 'yes',
				],			
			]
		);
		$this->add_control(
			'dateColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-date a' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showDate' => 'yes',
				],
			]
		);
		$this->add_control(
			'dateIconColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-date i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showDate' => 'yes',
				'dateIcon!' => 'none',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition' => [
					'showDate' => 'yes',
				],
			]
		);
		$this->add_control(
			'dateHoverColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-date a:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showDate' => 'yes',
				],
			]
		);
		$this->add_control(
			'dateIconHoverColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-date a:hover i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showDate' => 'yes',
				'dateIcon!' => 'none',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Date Style*/ 
		/*Post category Style*/
		$this->start_controls_section(
            'section_post_category_style',
            [
                'label' => esc_html__('Post Taxonomies', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

         $this->add_control(
            'showCategory',
            [
				'label' => esc_html__( 'Post Taxonomies', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'yes',	
			]
        );
		$this->add_control(
			'catePrefixType',
			[
				'label' => esc_html__( 'Prefix', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pttext',
				'options' => [
					'pttext' => esc_html__('Text', 'tpebl'),
					'pticon' => esc_html__('Icon', 'tpebl'),
				],
				'condition' => [
					'showCategory' => 'yes',
				],
			]
		);
		$this->add_control(
			'catePrefix',
			[
				'label' => esc_html__( 'Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'in', 'tpebl' ),
				'condition' => [
					'showCategory' => 'yes',
					'catePrefixType' => 'pttext',
				],
			]
		);
        $this->add_control(
			'catePrefixIcon',
			[
				'label' => esc_html__( 'Icon', 'tpebl' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-list',
					'library' => 'solid',
				],
				'condition' => [
					'showCategory' => 'yes',
					'catePrefixType' => 'pticon',
				],
			]
		);
        $this->add_control(
			'cateDisplayNo',
			[
				'label' => esc_html__('Taxonomy Limit', 'tpebl'),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 25,
				'step' => 1,
				'default' => 5,
				'condition' => [
				'showCategory' => 'yes',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_category_Typo',
				'label' => esc_html__( 'Typography', 'tpebl' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tp-meta-category a',
			]
		);
		$this->add_responsive_control(
			'tab_category_svg_icon',
			[
				'label' => esc_html__( 'Svg Icon Size', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],	
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category .tp-meta-category-label svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->start_controls_tabs( 'tabs_category_style' );
		$this->start_controls_tab(
			'tab_category_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'condition' => [
				'showCategory' => 'yes',
				],				
			]
		);
		$this->add_control(
			'cateColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-category a,{{WRAPPER}} .tp-post-meta-info .tp-meta-category:after,{{WRAPPER}} .tp-post-meta-info .tp-meta-category .tp-meta-category-label i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category .tp-meta-category-label svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'showCategory' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_category_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition' => [
					'showCategory' => 'yes',
				],
			]
		);
		$this->add_control(
			'cateHoverColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-category a:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showCategory' => 'yes',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'cateStyle',
			[
				'label' => esc_html__( 'Category Style', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1' => esc_html__('Style 1', 'tpebl'),
					'style-2' => esc_html__('Style 2', 'tpebl'),
					
				],
				'separator' => 'before',
				'condition' => [
				'showCategory' => 'yes',
				],
			]
		);
		 $this->add_responsive_control(
            'cateSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Category Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],			
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],	
            ]
        );	
		$this->add_responsive_control(
			'catemargin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'showCategory' => 'yes',				
				],
			]
		);
		 $this->add_responsive_control(
			'catepadding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],				
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',	
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->start_controls_tabs( 'tabs_cate_bg_style' );
		$this->start_controls_tab(
			'tab_cate_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'cateBg',
				'types'     => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a',
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cateBorder',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a',	
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->add_responsive_control(
			'cateBorderRadius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],	
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],		
			]
		);	
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cateBoxShadow',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a',
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);	
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_cate_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cateBgHover',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a:hover',
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cateBorderHover',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a:hover',
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->add_responsive_control(
			'cateBorderRadiusHover',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],		
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'cateBoxShadowHover',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-meta-category.style-2 a:hover',
				'condition' => [
				'showCategory' => 'yes',
				'cateStyle' => 'style-2',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post category Style*/
		/*Post Author Style*/
		$this->start_controls_section(
            'section_post_author_style',
            [
                'label' => esc_html__('Post Author', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'showAuthor',
            [
				'label' => esc_html__( 'Show Post Author', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'yes',	
			]
        );
          $this->add_control(
			'authorPrefix',
			[
				'label' => esc_html__( 'Prefix Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'By', 'tpebl' ),
				'condition' => [
				'showAuthor' => 'yes',
				],
			]
		);
         $this->add_control(
			'authorIcon',
			[
				'label' => esc_html__( 'Select Author Icon', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'tpebl'),
					'fas fa-user' => esc_html__('Icon 1', 'tpebl'),
					'far fa-user' => esc_html__('Icon 2', 'tpebl'),
					'fas fa-user-alt' => esc_html__('Icon 3', 'tpebl'),
					'fas fa-user-circle' => esc_html__('Icon 4', 'tpebl'),
					'fas fa-user-tie' => esc_html__('Icon 5', 'tpebl'),
					'profile' => esc_html__('Avatar', 'tpebl'),
				],
				'condition' => [
					'showAuthor' => 'yes',
				],
			]
		);
        $this->add_responsive_control(
            'authorIconSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],			
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author i,{{WRAPPER}} .tp-meta-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'showAuthor' => 'yes',
					'authorIcon!' => 'none',	
				],
            ]
        );	
        $this->add_responsive_control(
            'authorIconSize',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
						'step' => 1,
					],
				],
				'render_type' => 'ui',			
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'showAuthor' => 'yes',
					'authorIcon!' => ['none','profile'],
				],
            ]
        );
		$this->add_responsive_control(
            'authorAvtarSize',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Avatar Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 24,
				],			
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'showAuthor' => 'yes',
					'authorIcon' => 'profile',
				],
            ]
        );
		$this->start_controls_tabs( 'tabs_author_style' );
		$this->start_controls_tab(
			'tab_author_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),	
				'condition' => [
					'showAuthor' => 'yes',
				],			
			]
		);
		$this->add_control(
			'authorColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author a' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showAuthor' => 'yes',
				],
			]
		);
		$this->add_control(
			'authorIconColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showAuthor' => 'yes',
				'authorIcon!' => ['none','avatar']
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_author_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition' => [
					'showAuthor' => 'yes',
				],
			]
		);
		$this->add_control(
			'authorHoverColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author a:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showAuthor' => 'yes',
				],
			]
		);
		$this->add_control(
			'authorIconHoverColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-author a:hover i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showAuthor' => 'yes',
				'authorIcon!' => ['none','avatar']
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Author Style*/ 
		/*Post Comment Style*/ 
		$this->start_controls_section(
            'section_post_comment_style',
            [
                'label' => esc_html__('Post Comment', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'showComment',
            [
				'label' => esc_html__( 'Post Comment', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'default' => 'yes',	
			]
        );
         $this->add_control(
			'commentPrefix',
			[
				'label' => esc_html__( 'Prefix Text', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Comment', 'tpebl' ),
				'condition' => [
					'showComment' => 'yes',
				],
			]
		); 
         $this->add_control(
			'commentIcon',
			[
				'label' => esc_html__( 'Select Comment Icon', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None', 'tpebl'),
					'fas fa-comments' => esc_html__('Icon 1', 'tpebl'),
					'far fa-comments' => esc_html__('Icon 2', 'tpebl'),
					'fas fa-comment-dots' => esc_html__('Icon 3', 'tpebl'),
					'far fa-comment-dots' => esc_html__('Icon 4', 'tpebl'),
					'far fa-comment' => esc_html__('Icon 5', 'tpebl'),
					'far fa-comment-alt' => esc_html__('Icon 6', 'tpebl'),
				],
				'condition' => [
					'showComment' => 'yes',
				],
			]
		);
        $this->add_responsive_control(
            'commentIconSpace',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Space', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],			
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'showComment' => 'yes',
					'commentIcon!' => 'none',										
				],	
            ]
        );	
        $this->add_responsive_control(
            'commentIconSize',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Icon Size', 'tpebl'),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
						'step' => 1,
					],
				],
				'render_type' => 'ui',			
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'showComment' => 'yes',
					'commentIcon!' => 'none',										
				],
            ]
        );	
        $this->start_controls_tabs( 'tabs_auth_comment_style' );
		$this->start_controls_tab(
			'tab_auth_comment_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),	
				'condition' => [
				'showComment' => 'yes',
				],			
			]
		);
		$this->add_control(
			'commentColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment a' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showComment' => 'yes',
				],
			]
		);
		$this->add_control(
			'commentIconColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showComment' => 'yes',
				'commentIcon!' => 'none',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_auth_comment_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
				'condition' => [
				'showComment' => 'yes',
				],
			]
		);
		$this->add_control(
			'commentHoverColor',
			[
				'label' => esc_html__( 'Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment a:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showComment' => 'yes',
				],
			]
		);
		$this->add_control(
			'commentIconHoverColor',
			[
				'label' => esc_html__( 'Icon Color', 'tpebl' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tp-meta-comment a:hover i' => 'color: {{VALUE}}',
				],
				'condition' => [
				'showComment' => 'yes',
				'commentIcon!' => 'none',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*Post Comment Style*/ 
		/*Inner Content Background*/
		$this->start_controls_section(
            'section_incontent_bg_style',
            [
                'label' => esc_html__('Inner Content Area', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );		
		$this->add_responsive_control(
			'inpadding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'inmargin',
			[
				'label' => esc_html__( 'Margin', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',	
			]
		);
		$this->start_controls_tabs( 'tabs_incontent_bg_style' );
		$this->start_controls_tab(
			'tab_incontent_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'inboxBg',
				'types'     => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'inboxBorder',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment',	
			]
		);
		$this->add_responsive_control(
			'inboxBorderRadius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],		
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'inboxBoxShadow',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_incontent_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'inboxBgHover',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author:hover, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'inboxBorderHover',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author:hover, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment:hover',
			]
		);
		$this->add_responsive_control(
			'inboxBorderRadiusHover',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author:hover, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],			
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'inboxBoxShadowHover',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-date:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-category:hover,{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-author:hover, {{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner .tp-meta-comment:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();		
		$this->end_controls_section();
		/*inner Content Background*/
		/*Content Background*/
		$this->start_controls_section(
            'section_content_bg_style',
            [
                'label' => esc_html__('Main Content Area', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'tpebl' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',	
			]
		);
		$this->start_controls_tabs( 'tabs_content_bg_style' );
		$this->start_controls_tab(
			'tab_content_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),				
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'boxBg',
				'types'     => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'boxBorder',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner',	
			]
		);
		$this->add_responsive_control(
			'boxBorderRadius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],			
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'boxBoxShadow',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_content_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'boxBgHover',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'boxBorderHover',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner:hover',
			]
		);
		$this->add_responsive_control(
			'boxBorderRadiusHover',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],			
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'boxBoxShadowHover',
				'selector' => '{{WRAPPER}} .tp-post-meta-info .tp-post-meta-info-inner:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();		
		$this->end_controls_section();
		/*Content Background*/		
	}
		
    protected function render() {
		$settings = $this->get_settings_for_display();

	    $post_id = get_queried_object_id();
	    $post = get_queried_object();
		$showDate = (!empty($settings['showDate'])) ? $settings['showDate'] : false;
		$showCategory = (!empty($settings['showCategory'])) ? $settings['showCategory'] : false;
		$showAuthor = (!empty($settings['showAuthor'])) ? $settings['showAuthor'] : false;
		$showComment = (!empty($settings['showComment'])) ? $settings['showComment'] : false;		
		$metaLayout = (!empty($settings['metaLayout'])) ? $settings['metaLayout'] : 'layout-1';
	    $metaLayoutClass = 'tp-meta-'.$metaLayout;
	   
	    $output = '<div class="tp-post-meta-info '.esc_attr($metaLayoutClass).'" >';
		    $output .= '<div class="tp-post-meta-info-inner">';
				$loop_content=$settings["metaSort"];
				if(!empty($loop_content)) {
						 $index=0;
					foreach($loop_content as $index => $item) {				
					    	if(!empty($item['sortfield']) && $item['sortfield']=='date'){
					           if($showDate){
						         $dateIcon = '';
								 if(!empty($settings['dateIcon']) && $settings['dateIcon'] !='none'){
									 $dateIcon ='<i class="'.esc_attr($settings['dateIcon']).'"></i>';
								 }
						         $output .='<span class="tp-meta-date" ><span class="tp-meta-date-label tp-meta-label" >'.esc_html($settings["datePrefix"]).'</span><a class="tp-meta-value" href="'.esc_url(get_the_permalink()).'">'.$dateIcon.esc_html(get_the_date()).'</a></span>';
					            }
				            }
							
						  $category_taxonomies='category';
						  if(!empty($item['sortfield']) && $item['sortfield']=='category'){
							     
								if(!empty($showCategory) && $showCategory == 'yes' ){
									$catePrefix='';
									$catePrefixType=!empty($settings['catePrefixType']) ? $settings['catePrefixType'] : 'pttext';
									
									if($catePrefixType=='pttext'){
										$catePrefix = $settings["catePrefix"];
									}else if($catePrefixType=='pticon'){
										ob_start();
										\Elementor\Icons_Manager::render_icon( $settings['catePrefixIcon'], [ 'aria-hidden' => 'true' ]);
										$catePrefix = ob_get_contents();
										ob_end_clean();	
									}
									
									 $cateDisplayNo=$settings["cateDisplayNo"];
									
									$cateStyle = (!empty($settings['cateStyle'])) ? $settings['cateStyle'] : 'style-1';
									
									$category_taxonomies = !empty($item['category_taxonomies']) ? $item['category_taxonomies'] : 'category';
									
									if(!empty($item['category_taxonomies_load']) && $item['category_taxonomies_load']=='bypost'){
										if(!empty($item['category_taxonomies_load_cat_tag']) && $item['category_taxonomies_load_cat_tag'] == 'tptag'){
											$terms=get_the_tags($post_id);
										}else{
											$terms=get_the_category($post_id);
										}
									}else{
										$terms = get_terms($category_taxonomies, array(
												'orderby'    => 'count',
												'hide_empty' => 0,
												'exclude' => array( 1 ),
											));
									}
								
									$category_list ='';
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
										$i = 1;
										foreach ( $terms as $term ) {
											if($cateDisplayNo >= $i){
												$category_list .= '<a class="tp-meta-value" href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( '%s', 'tpebl' ), $term->name ) ) . '">' . $term->name . '</a>';
											}
											$i++;
										}
									}
									$output .='<span class="tp-meta-category '.esc_attr($cateStyle).'" ><span class="tp-meta-category-label tp-meta-label">'.$catePrefix.'</span><span class="tp-meta-category-list">'.$category_list.'</span></span>';
								}
						    }
					    if(!empty($item['sortfield']) && $item['sortfield']=='author'){

						    if(!empty($showAuthor) && $showAuthor == 'yes'){
						      	$authorPrefix=$settings["authorPrefix"];				   
							    $authorIcon = (!empty($settings['authorIcon'])) ? $settings['authorIcon'] : '';
							    $iconauthor = '';
							    if(!empty($authorIcon) && $authorIcon=='profile'){
								  $iconauthor = '<span>'.get_avatar( get_the_author_meta('ID'), 200).'</span>';
							    }else if(!empty($authorIcon) && $authorIcon!='none'){
								  $iconauthor = '<i class="'.esc_attr($authorIcon).'"></i>';
							    }
							  $output .='<span class="tp-meta-author" ><span class="tp-meta-author-label tp-meta-label" >'.esc_html($authorPrefix).'</span><a class="tp-meta-value" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" rel="'.esc_attr__('author','tpebl').'">'.$iconauthor.esc_html(get_the_author()).'</a></span>';
						    }
				        }					
						
						if(!empty($item['sortfield']) && $item['sortfield']=='comments'){
							if($showComment){
								$commentPrefix=$settings["commentPrefix"];
								$commentIcon = '';
								 if(!empty($settings['commentIcon']) && $settings['commentIcon'] !='none'){
									 $commentIcon ='<i class="'.$settings['commentIcon'].'"></i>';
								 }
								$comments_count = wp_count_comments($post_id);
								$count=0;
								if(!empty($comments_count)){
									$count = $comments_count->total_comments;
								}
								if($count===0){
									$comment_text = 'No Comments';
								}else if($count > 0){
									$comment_text = 'Comments('.$count.')';
								}
								$output .='<span class="tp-meta-comment"><span class="tp-meta-comment-label tp-meta-label" >'.esc_html($commentPrefix).'</span><a class="tp-meta-value" href="'.esc_url(get_the_permalink()).'#respond" rel="'.esc_attr__('comment','tpebl').'">'.$commentIcon.$comment_text.'</a></span>';
							}
						} 
				        $index++;
					}   	       
			    }			       
	        $output .= '</div>';	
	    $output .= '</div>';
	    echo $output; 
	}
	
    protected function content_template() {
    }				
}