<?php 
/*
Widget Name: Post Featured Image
Description: Post Featured Image
Author: Theplus
Author URI: https://posimyth.com
*/
namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use TheplusAddons\L_Theplus_Element_Load;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class L_ThePlus_Featured_Image extends Widget_Base {
		
	public function get_name() {
		return 'tp-post-featured-image';
	}

    public function get_title() {
        return esc_html__('Post Featured Image', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-file-image-o theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-builder');
    }

    protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Post Feature Image', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);		
		$this->add_control(
			'pfi_type',
			[
				'label' => esc_html__( 'Type', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pfi-default',
				'options' => [
					'pfi-default'  => esc_html__( 'Standard Image', 'tpebl' ),
					'pfi-background' => esc_html__( 'As a Background', 'tpebl' ),
				],
			]
		);
		$this->add_control(
            'bg_in', [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Location', 'tpebl'),
                'default' => 'tp-fibg-section',
                'options' => [                    
                    'tp-fibg-section' => esc_html__('Section', 'tpebl'),
                    'tp-fibg-inner-section' => esc_html__('Inner Section', 'tpebl'),
					'tp-fibg-container' => esc_html__('Container', 'tpebl'),
                    'tp-fibg-column' => esc_html__('Column', 'tpebl'),                    
                ],
				'condition' => [
					'pfi_type' => 'pfi-background',
				],
            ]
        );
        $this->add_control(
			'imageSize',
			[
				'label' => esc_html__( 'Image Size', 'tpebl' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					"full" => esc_html__("Full", 'tpebl'),
					"thumbnail" => esc_html__("Thumbnail", 'tpebl'),
					"medium" => esc_html__("Medium", 'tpebl'),
					"medium_large" => esc_html__("Medium Large", 'tpebl'),
					"large" => esc_html__("Large", 'tpebl'),
				],
				'condition' => [
					'pfi_type' => 'pfi-background',
				],
			]
		);
		$this->add_responsive_control(
            'maxWidth',
            [
                'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Maximum Width', 'tpebl'),
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2000,
						'step' => 1,
					],
				],				
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .tp-featured-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'pfi_type' => 'pfi-background',
				],
            ]
        );
		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'tpebl' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',		
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'pfi_type' => 'pfi-background',
				],		
				'separator' => 'before',	
			]
		);
		$this->end_controls_section();
		/*image style*/
		$this->start_controls_section(
            'section_img_style',
            [
                'label' => esc_html__('Standard Image', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pfi_type' => 'pfi-default',
				],
            ]
        );	
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'imageBorder',
				'label' => esc_html__( 'Border', 'tpebl' ),
				'selector' => '{{WRAPPER}} .tp-featured-image img',
			]
		);
		$this->add_responsive_control(
			'imageBorderRadius',
			[
				'label'      => esc_html__( 'Border Radius', 'tpebl' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tp-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'imageBoxShadow',
				'selector' => '{{WRAPPER}} .tp-featured-image img',				
			]
		);
		$this->end_controls_section();
		/*image style*/		
		/*image as background*/
		$this->start_controls_section(
            'section_imgbg_style',
            [
                'label' => esc_html__('Background Image', 'tpebl'),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pfi_type' => 'pfi-background',
				],
            ]
        );
		$this->add_control(
            'pfi_bg_image_position', [
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__('Image Position', 'tpebl'),
				'default' => 'center center',
				'options' => l_theplus_get_image_position_options(),
			]
        );
		$this->add_control(
            'pfi_bg_img_attach', [
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__('Attachment', 'tpebl'),
				'default' => 'scroll',
				'options' => l_theplus_get_image_attachment_options(),
			]
        );
		$this->add_control(
            'pfi_bg_img_repeat', [
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__('Repeat', 'tpebl'),
				'default' => 'repeat',
				'options' => l_theplus_get_image_reapeat_options(),
			]
        );
		$this->add_control(
            'pfi_bg_image_size', [
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__('Background Size', 'tpebl'),
				'default' => 'cover',
				'options' => l_theplus_get_image_size_options(),
			]
        );
		$this->start_controls_tabs( 'tabs_pfibgoc_style' );
		$this->start_controls_tab(
			'tab_pfibgoc_normal',
			[
				'label' => esc_html__( 'Normal', 'tpebl' ),
			]
		);
		$this->add_control(
            'pfi_bg_image_oc',
            [
                'label' => esc_html__('Overlay Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '.tp-post-image.tp-feature-image-as-bg .tp-featured-image:before' => 'background:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
			'pfi_bg_image_oc_transition',
			[
				'label' => esc_html__( 'Transition css', 'tpebl' ),
				'type' => Controls_Manager::TEXT,				
				'placeholder' => esc_html__( 'all .3s linear', 'tpebl' ),
				'selectors' => [
					'.tp-post-image.tp-feature-image-as-bg .tp-featured-image' => '-webkit-transition: {{VALUE}};-moz-transition: {{VALUE}};-o-transition: {{VALUE}};-ms-transition: {{VALUE}};'
				],
			]
		);		
		$this->add_control(
			'pfi_bg_image_oc_transform',
			[
				'label' => esc_html__( 'Transform css', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'skew(-25deg)', 'tpebl' ),
				'selectors' => [
					'.tp-post-image.tp-feature-image-as-bg .tp-featured-image' => 'transform: {{VALUE}};-ms-transform: {{VALUE}};-moz-transform: {{VALUE}};-webkit-transform: {{VALUE}};transform-style: preserve-3d;-ms-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-transform-style: preserve-3d;'
				],	
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_pfibgoc_hover',
			[
				'label' => esc_html__( 'Hover', 'tpebl' ),
			]
		);
		$this->add_control(
            'pfi_bg_image_och',
            [
                'label' => esc_html__('Overlay Color', 'tpebl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    'section.elementor-element.elementor-top-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image:before,
					.elementor-element.e-container:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image:before,
					section.elementor-element.elementor-inner-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image:before,
					.elementor-column:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image:before' => 'background:{{VALUE}};',
                ],
            ]
        );
		$this->add_control(
			'pfi_bg_image_oc_transition_h',
			[
				'label' => esc_html__( 'Transition css', 'tpebl' ),
				'type' => Controls_Manager::TEXT,				
				'placeholder' => esc_html__( 'all .3s linear', 'tpebl' ),
				'selectors' => [
					'section.elementor-element.elementor-top-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					.elementor-element.e-container:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					section.elementor-element.elementor-inner-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					.elementor-column:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image' => '-webkit-transition: {{VALUE}};-moz-transition: {{VALUE}};-o-transition: {{VALUE}};-ms-transition: {{VALUE}};'
				],
			]
		);		
		$this->add_control(
			'pfi_bg_image_oc_transform_h',
			[
				'label' => esc_html__( 'Transform css', 'tpebl' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'skew(-25deg)', 'tpebl' ),
				'selectors' => [
					'section.elementor-element.elementor-top-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					.elementor-element.e-container:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					section.elementor-element.elementor-inner-section:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image,
					.elementor-column:hover .tp-post-image.tp-feature-image-as-bg .tp-featured-image' => 'transform: {{VALUE}};-ms-transform: {{VALUE}};-moz-transform: {{VALUE}};-webkit-transform: {{VALUE}};transform-style: preserve-3d;-ms-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-transform-style: preserve-3d;'
				],	
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();		
		$this->end_controls_section();
		/*image as background*/
	}
	protected function render() {
		$settings = $this->get_settings_for_display();

	    $post_id = get_the_ID();
	    $post = get_queried_object();
	    $imageSize = (!empty($settings['imageSize'])) ? $settings['imageSize'] : 'full';
		
		$image_content =$css_rules1 = $iabg = $bg_in = '';
		if (has_post_thumbnail( $post_id ) ){
			$image_content = get_the_post_thumbnail_url($post_id,$imageSize);
		}else{
			$image_content = L_THEPLUS_URL .'/assets/images/tp-placeholder.jpg';
		}
		
		if(!empty($settings['pfi_type']) && $settings['pfi_type']=='pfi-background'){
			$iabg = ' tp-feature-image-as-bg';			
			$bg_in = ' data-tp-fi-bg-type="' . esc_attr($settings["bg_in"]) . '" ';
		}
		$output = '<div class="tp-post-image '.esc_attr($iabg).'" '.$bg_in.'>';
				
				if(!empty($settings['pfi_type']) && $settings['pfi_type']=='pfi-background'){
					if(isset($settings['pfi_bg_image_position']) && !empty($settings['pfi_bg_image_position'])){
						$css_rules1 .= ' background-position: '.esc_attr($settings['pfi_bg_image_position']).';';
					}					
					if(isset($settings['pfi_bg_img_repeat']) && !empty($settings['pfi_bg_img_repeat'])){
						$css_rules1 .= ' background-repeat: '.esc_attr($settings['pfi_bg_img_repeat']).';';
					}
					if(isset($settings['pfi_bg_image_size']) && !empty($settings['pfi_bg_image_size'])){
						$css_rules1 .= ' -webkit-background-size: '.esc_attr($settings["pfi_bg_image_size"]).';-moz-background-size: '.esc_attr($settings["pfi_bg_image_size"]).';-o-background-size: '.esc_attr($settings["pfi_bg_image_size"]).';background-size: '.esc_attr($settings["pfi_bg_image_size"]).';';
					}
					if(isset($settings['pfi_bg_img_attach']) && !empty($settings['pfi_bg_img_attach'])){
						$css_rules1 .= ' background-attachment: '.esc_attr($settings["pfi_bg_img_attach"]).';';
					}
					$output .= '<div class="tp-featured-image"
							style="background:url('.esc_url($image_content).');'.$css_rules1.'">';
					$output .= '</div>';
				}else{
					$output .= '<div class="tp-featured-image">';
					$output .= '<a href="'.esc_url(get_the_permalink()).'">';
					$output .= '<img src="'.esc_url($image_content).'" alt="'.get_the_title().'" class="tp-featured-img" />';
					$output .= '</a>';
					$output .= '</div>';
				}			
		$output .= "</div>";
		echo $output;
	}
	
    protected function content_template() {
	
    }
}