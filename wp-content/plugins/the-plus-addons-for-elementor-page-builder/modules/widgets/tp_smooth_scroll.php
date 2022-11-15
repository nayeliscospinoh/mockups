<?php 
/*
Widget Name: Smooth Scroll
Description: smooth page scroll.
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

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class L_ThePlus_Smooth_Scroll extends Widget_Base {
		
	public function get_name() {
		return 'tp-smooth-scroll';
	}

    public function get_title() {
        return esc_html__('Smooth Scroll', 'tpebl');
    }

    public function get_icon() {
        return 'fa fa-hourglass-start theplus_backend_icon';
    }

    public function get_categories() {
        return array('plus-creatives');
    }

    protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Scrolling Core', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'frameRate',
			[
				'label' => esc_html__( 'Frame Rate', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'Hz' ],
				'range' => [
					'Hz' => [
						'min' => 0,
						'max' => 1000,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'Hz',
					'size' => 150,
				],
			]
		);
		$this->add_control(
			'animationTime',
			[
				'label' => esc_html__( 'Animation Time', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ms'],
				'range' => [
					'ms' => [
						'min' => 300,
						'max' => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'unit' => 'ms',
					'size' => 1000,
				],
			]
		);
		$this->add_control(
			'stepSize',
			[
				'label' => esc_html__( 'Step Size', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_pulse_section',
			[
				'label' => esc_html__( 'Pulse ratio of "tail" to "acceleration', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'pulseAlgorithm',
			[
				'label' => esc_html__( 'Plus Algorithm', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'pulseScale',
			[
				'label' => esc_html__( 'Pulse Scale', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
			]
		);
		$this->add_control(
			'pulseNormalize',
			[
				'label' => esc_html__( 'Pulse Normalize', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_acceleration_section',
			[
				'label' => esc_html__( 'Acceleration', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'accelerationDelta',
			[
				'label' => esc_html__( 'Acceleration Delta', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
			]
		);
		$this->add_control(
			'accelerationMax',
			[
				'label' => esc_html__( 'Acceleration Max', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_keyboard_settings_section',
			[
				'label' => esc_html__( 'Keyboard Settings', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'keyboardSupport',
			[
				'label' => esc_html__( 'Keyboard Support', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'arrowScroll',
			[
				'label' => esc_html__( 'Arrow Scroll', 'tpebl' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 2,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_other_section',
			[
				'label' => esc_html__( 'Other Settings', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'touchpadSupport',
			[
				'label' => esc_html__( 'Touch pad Support', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'fixedBackground',
			[
				'label' => esc_html__( 'Fixed Support', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'tpebl' ),
				'label_off' => esc_html__( 'Disable', 'tpebl' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_responsive_section',
			[
				'label' => esc_html__( 'Responsive', 'tpebl' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'tablet_off_scroll',
			[
				'label' => esc_html__( 'Tablet/Mobile Smooth Scroll', 'tpebl' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Off', 'tpebl' ),
				'label_off' => esc_html__( 'On', 'tpebl' ),				
				'default' => 'no',
			]
		);
		$this->end_controls_section();
		
	}
	 protected function render() {

        $settings = $this->get_settings_for_display();
		$frameRate = $settings['frameRate']["size"];
		$animationTime = $settings['animationTime']["size"];
		$stepSize = $settings['stepSize']["size"];
		$pulseAlgorithm = ($settings['pulseAlgorithm']=='yes') ? '1' : '0';
		$pulseScale = $settings['pulseScale']["size"];
		$pulseNormalize = $settings['pulseNormalize']["size"];
		$accelerationDelta = $settings['accelerationDelta']["size"];
		$accelerationMax = $settings['accelerationMax']["size"];
		$keyboardSupport = ($settings['keyboardSupport']=='yes') ? '1' : '0';		
		$arrowScroll = $settings['arrowScroll']["size"];
		$touchpadSupport = ($settings['touchpadSupport']=='yes') ? '1' : '0';
		$fixedBackground = ($settings['fixedBackground']=='yes') ? '1' : '0';
		
		if(!empty($settings['tablet_off_scroll']) && $settings['tablet_off_scroll']=='yes'){
			$tablet_off=' data-tablet-off="yes"';
		}else{
			$tablet_off=' data-tablet-off="no"';
		}
		echo '<div class="plus-smooth-scroll" data-frameRate="'.esc_attr($frameRate).'" data-animationTime="'.esc_attr($animationTime).'" data-stepSize="'.esc_attr($stepSize).'" data-pulseAlgorithm="'.esc_attr($pulseAlgorithm).'" data-pulseScale="'.esc_attr($pulseScale).'" data-pulseNormalize="'.esc_attr($pulseNormalize).'" data-accelerationDelta="'.esc_attr($accelerationDelta).'" data-accelerationMax="'.esc_attr($accelerationMax).'" data-keyboardSupport="'.esc_attr($keyboardSupport).'" data-arrowScroll="'.esc_attr($arrowScroll).'" data-touchpadSupport="'.esc_attr($touchpadSupport).'" data-fixedBackground="'.esc_attr($fixedBackground).'" '.$tablet_off.'></div>';
	}
	
    protected function content_template() {
	
    }

}
