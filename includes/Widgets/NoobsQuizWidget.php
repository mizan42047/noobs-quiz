<?php

namespace Noobsplugin\Noobsquiz\Widgets;

use \Elementor\Widget_Base;
use WP_Query;

defined('ABSPATH') || exit;
/**
 * NoobsQuizWidget
 * it's handle custom widget for elementor
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class NoobsQuizWidget extends Widget_Base
{
	//slug of the widget
	public function get_name()
	{
		return 'noobs_quiz_questions';
	}

	//Title of the widget
	public function get_title()
	{
		return esc_html__('Noobs Quiz Questions', 'noobs-quiz');
	}

	//Icon of the widget
	public function get_icon()
	{
		return 'eicon-preview-medium';
	}

	/**
	 * widget show under which category
	 * TODO: Make a custom catgory for noobs quiz
	 * @since 1.0.0
	 */
	public function get_categories()
	{
		return ['basic'];
	}

	//Searching keyword for elementor
	public function get_keywords()
	{
		return ['noobs', 'quiz', 'question'];
	}

	//Style for the widget
	public function get_style_depends()
	{
		return [
			'widget-style'
		];
	}

	//Script for the widget
	public function get_script_depends()
	{
		return [
			'widget-scripts'
		];
	}

	/**
	 * get_question_category
	 * fetch all question categories and uses for select
	 * @return Array $category_list with term id and term name
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function get_question_category()
	{
		$categories_query = get_categories([
			"hide_empty" => true,
			"taxonomy"   => "question_category"
		]);
		//store term id and term name
		$category_list = [];

		foreach ($categories_query as $category) {
			$category_list[$category->term_id] = esc_html__($category->name, "noobs-quiz");
		}

		return $category_list;
	}

	/**
	 * get_questions
	 * fetch question title and question id
	 * @return Array $question_list;
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function get_questions()
	{
		$question_query = get_posts([
			'post_type'        => 'question',
			'posts_per_page'   => -1
		]);
		//Store post id and title
		$question_list = [];
		foreach ($question_query as $question) {
			$question_list[$question->ID] = esc_html__(wp_trim_words($question->post_title, 10), 'noobs-quiz');
		}
		wp_reset_postdata();
		return $question_list;
	}

	protected function register_controls()
	{
		/**
		 * Control for Query
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_query_section',
			[
				'label' => esc_html__('Query', 'noobs-quiz'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		//Query by
		$this->add_control(
			'noobs_quiz_query_by',
			[
				'label' => esc_html__('Query By', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'   => esc_html__('Default'),
					'category'  => esc_html__('Category', 'noobs-quiz'),
					'custom'    => esc_html__('Custom Selection', 'noobs-quiz'),
				],
			]
		);

		//uses get_question_category return category
		$this->add_control(
			'noobs_quiz_query_by_category',
			[
				'label'     => esc_html__('Categories', 'noobs-quiz'),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $this->get_question_category(),
				"condition" => [
					'noobs_quiz_query_by' => 'category'
				]
			]
		);

		//uses get_questions return title
		$this->add_control(
			'noobs_quiz_query_by_custom',
			[
				'label'       => esc_html__('Select Question', 'noobs-quiz'),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_questions(),
				"condition"   => [
					'noobs_quiz_query_by' => 'custom'
				]
			]
		);

		//Order by
		$this->add_control(
			'noobs_quiz_order_by',
			[
				'label' => esc_html__('Order By', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => [
					'ID'      => esc_html__('ID', 'noobs-quiz'),
					'date'      => esc_html__('Date', 'noobs-quiz'),
					'title'      => esc_html__('Title', 'noobs-quiz'),
					'rand'    => esc_html__('Random', 'noobs-quiz'),
				],
			]
		);

		$this->add_control(
			'noobs_quiz_order',
			[
				'label' => esc_html__('Order', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC'      => esc_html__('ASC', 'noobs-quiz'),
					'DESC'  => esc_html__('DESC', 'noobs-quiz'),
				],
			]
		);

		$this->add_control(
			'noobs_quiz_posts_per_page',
			[
				'label' => esc_html__('Post Per Page', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->end_controls_section(); //End of Query Section

		/**
		 * control for settings
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_settings_section',
			[
				'label' => esc_html__('Settings', 'noobs-quiz'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'noob_quiz_hide_title',
			[
				'label'        => esc_html__('Hide Question Title?', 'noobs-quiz'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Hide', 'noobs-quiz'),
				'label_off'    => esc_html__('Show', 'noobs-quiz'),
				'separator'    => 'before',
				'default'       => 'block',
				'return_value' => 'none',
				'selectors'    => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'display: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'noob_quiz_hide_description',
			[
				'label'        => esc_html__('Hide Question Description?', 'noobs-quiz'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Hide', 'noobs-quiz'),
				'label_off'    => esc_html__('Show', 'noobs-quiz'),
				'separator'    => 'before',
				'default'       => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'noob_quiz_hide_options',
			[
				'label'        => esc_html__('Hide Question Options?', 'noobs-quiz'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Hide', 'noobs-quiz'),
				'label_off'    => esc_html__('Show', 'noobs-quiz'),
				'separator'    => 'before',
				'default'       => 'block',
				'return_value' => 'none',
				'selectors'    => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'display: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section(); //end of settings

		/**
		 * Style for single wrapper
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_wrapper_style_section',
			[
				'label' => esc_html__('Wrapper', 'noobs-quiz'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'noob_quiz_wrapper_background_color',
			[
				'label'     => esc_html__('Background Color', 'noobs-quiz'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'noob_quiz_wrapper_border',
				'label' => esc_html__( 'Border', 'noobs-quiz' ),
				'selector' => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content',
			]
		);

		$this->add_responsive_control(
			'noob_quiz_wrapper_padding',
			[
				'label' => esc_html__('Padding (px)', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'noob_quiz_wrapper_margin',
			[
				'label' => esc_html__('Margin (px)', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); //End of wrapper secion

		/**
		 * Style for title
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_title_style_section',
			[
				'label' => esc_html__('Title', 'noobs-quiz'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					"noob_quiz_hide_title" => "block"
				]
			]
		);
		//Tab wrapper start
		$this->start_controls_tabs(
			'noob_quiz_title_color_tabs'
		);

		//Normal tab start
		$this->start_controls_tab(
			'noob_quiz_title_color_tab_normal',
			[
				'label' => esc_html__('Normal', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_title_color_normal',
			[
				'label'     => esc_html__('Color', 'noobs-quiz'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFF',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_background_color_normal',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of normal tab
		//Start hover tab
		$this->start_controls_tab(
			'noob_quiz_title_color_tab_hover',
			[
				'label' => esc_html__('Hover', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_title_color_hover',
			[
				'label' => esc_html__('Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_background_color_hover',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_border_color_hover',
			[
				'label' => esc_html__('Borer Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of hover tab

		$this->end_controls_tabs(); //Tab wrapper end

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'noob_quiz_title_border',
				'label' => esc_html__('Border', 'noobs-quiz'),
				'selector' => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => 0,
							'right'    => 8,
							'bottom'   => 0,
							'left'     => 8,
							'unit'     => 'px',
						],
					],
					'color'  => [
						'default' => '#0013FF'
					]
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'           => 'noob_quiz_title_typograpy',
				'exclude'        => ['font_family', 'font_style', 'letter_spacing', 'word_spacing', 'text_decoration'],
				'selector'       => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title',
				'fields_options' => [
					'typography'    => [
						'default'   => 'custom',
					],
					'font_weight'   => [
						'default'   => '500',
					],
					'font_size'     => [
						'label'     => esc_html__('Font Size (px)', 'noobs-quiz'),
						'default'   => [
							'size'  => '26',
							'unit'  => 'px'
						],
						'size_units' => ['px']
					],
					'text_transform' => [
						'default' => '',
					],
					'line_height'    => [
						'label'      => esc_html__('Line-Height (px)', 'noobs-quiz'),
						'default'    => [
							'size' => '48',
							'unit' => 'px'
						],
						'size_units' => ['px']
					]
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_padding',
			[
				'label' => esc_html__('Padding', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 5,
					'right'  => 10,
					'bottom' => 5,
					'left'   => 10,
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_border_radius',
			[
				'label' => esc_html__('Border Radius', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 0,
					'right'  => 25,
					'bottom' => 0,
					'left'   => 25,
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_title_margin',
			[
				'label'      => esc_html__('Margin', 'noobs-quiz'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 0,
					'right'  => 0,
					'bottom' => 5,
					'left'   => 0,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); //End of title style section

		/** 
		 * Style for Description
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_description_style_section',
			[
				'label'     => esc_html__('Description', 'noobs-quiz'),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					"noob_quiz_hide_description!" => "yes"
				]
			]
		);
		//Tab wrapper start
		$this->start_controls_tabs(
			'noob_quiz_description_color_tabs'
		);

		//Normal tab start
		$this->start_controls_tab(
			'noob_quiz_description_color_tab_normal',
			[
				'label' => esc_html__('Normal', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_description_color_normal',
			[
				'label'     => esc_html__('Color', 'noobs-quiz'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFF',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_background_color_normal',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default'   => '#747474',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of normal tab
		//Start hover tab
		$this->start_controls_tab(
			'noob_quiz_description_color_tab_hover',
			[
				'label' => esc_html__('Hover', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_description_color_hover',
			[
				'label' => esc_html__('Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_background_color_hover',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_border_color_hover',
			[
				'label' => esc_html__('Borer Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of hover tab

		$this->end_controls_tabs(); //Tab wrapper end

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'noob_quiz_description_border',
				'label' => esc_html__('Border', 'noobs-quiz'),
				'selector' => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => 0,
							'right'    => 6,
							'bottom'   => 0,
							'left'     => 6,
							'unit'     => 'px',
						],
					],
					'color'  => [
						'default' => '#0013FF'
					]
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'           => 'noob_quiz_description_typograpy',
				'exclude'        => ['font_family', 'font_style', 'letter_spacing', 'word_spacing', 'text_decoration'],
				'selector'       => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc',
				'fields_options' => [
					'typography'    => [
						'default'   => 'custom',
					],
					'font_weight'   => [
						'default'   => '400',
					],
					'font_size'     => [
						'label'     => esc_html__('Font Size (px)', 'noobs-quiz'),
						'default'   => [
							'size'  => '16',
							'unit'  => 'px'
						],
						'size_units' => ['px']
					],
					'line_height'    => [
						'label'      => esc_html__('Line-Height (px)', 'noobs-quiz'),
						'default'    => [
							'size' => '22',
							'unit' => 'px'
						],
						'size_units' => ['px']
					]
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_padding',
			[
				'label' => esc_html__('Padding', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_border_radius',
			[
				'label' => esc_html__('Border Radius', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'   => ['px', '%'],
				'default'      => [
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => true
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_description_margin',
			[
				'label'      => esc_html__('Margin', 'noobs-quiz'),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'default'    => [
					'top'    => 0,
					'right'  => 0,
					'bottom' => 10,
					'left'   => 0,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-content-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); //End of description style section
		/**
		 * Style for question options
		 * @since 1.0.0
		 */
		$this->start_controls_section(
			'noob_quiz_options_style_section',
			[
				'label'     => esc_html__('Options', 'noobs-quiz'),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					"noob_quiz_hide_options" => "block"
				]
			]
		);

		//Tab wrapper start
		$this->start_controls_tabs(
			'noob_quiz_options_color_tabs'
		);

		//Normal tab start
		$this->start_controls_tab(
			'noob_quiz_options_color_tab_normal',
			[
				'label' => esc_html__('Normal', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_options_color_normal',
			[
				'label'     => esc_html__('Color', 'noobs-quiz'),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFF',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_background_color_normal',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default'   => '#747474',
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of normal tab
		//Start hover tab
		$this->start_controls_tab(
			'noob_quiz_options_color_tab_hover',
			[
				'label' => esc_html__('Hover', 'noobs-quiz'),
			]
		);

		$this->add_control(
			'noob_quiz_options_color_hover',
			[
				'label' => esc_html__('Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_background_color_hover',
			[
				'label' => esc_html__('Background Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_border_color_hover',
			[
				'label' => esc_html__('Borer Color', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab(); //End of hover tab

		$this->end_controls_tabs(); //Tab wrapper end

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'noob_quiz_options_border',
				'label' => esc_html__('Border', 'noobs-quiz'),
				'selector' => '{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width'  => [
						'default' => [
							'top'      => 0,
							'right'    => 6,
							'bottom'   => 0,
							'left'     => 6,
							'unit'     => 'px',
						],
					],
					'color'  => [
						'default' => '#0013FF'
					]
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_gap',
			[
				'label' => esc_html__( 'Space Between (px)', 'noobs-quiz' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'separator'  => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_width',
			[
				'label' => esc_html__( 'Item Width (%)', 'noobs-quiz' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 49,
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'flex-basis: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'noob_quiz_options_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'noobs-quiz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => 10,
					'right'  => 10,
					'bottom' => 10,
					'left'   => 10,
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'noob_quiz_options_padding',
			[
				'label' => esc_html__( 'Item Padding', 'noobs-quiz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => 0,
					'right'  => 8,
					'bottom' => 0,
					'left'   => 8,
					'unit'   => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .noobs-quiz .noobs-quiz-content  .noobs-quiz-options' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); //End of question option style section
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		extract($settings);

		$args = [
			'post_type' => 'question'
		];
		/**
		 * Swicth for handle query condiotion
		 * @since 1.0.0
		 */
		switch ($noobs_quiz_query_by) {
			case 'category':
				$args['tax_query'] = [
					[
						'taxonomy' => 'question_category',
						'field'    => 'term_id',
						'terms'    => $noobs_quiz_query_by_category
					]
				];
				break;
			case 'custom':
				$args['post__in'] = $noobs_quiz_query_by_custom;
				break;
			case 'default':
				$args;
				break;
		}


		//Handle orderby
		$args['orderby'] = !empty($noobs_quiz_order_by) ? $noobs_quiz_order_by : '';

		//Handle order
		$args['order'] = !empty($noobs_quiz_order) ? $noobs_quiz_order : '';

		//Handle post per page
		$args['posts_per_page'] = !empty($noobs_quiz_posts_per_page) ? $noobs_quiz_posts_per_page : 1;

		$query = new WP_Query($args); //query for quertion post type

		if ($query->have_posts()) : ?>
			<div class="noobs-quiz">
				<div class="noobs-quiz-content">
					<!-- Form start -->
					<form action="" method="post">
						<?php
						while ($query->have_posts()) :
							$query->the_post(); ?>
							<?php if (get_the_title()) : ?>
								<h2 class="noobs-quiz-content-title"><?php the_title(); ?></h2>
							<?php endif; ?>

							<?php if ($noob_quiz_hide_description !== "yes") : ?>
								<p class="noobs-quiz-content-desc">
									<?php esc_html_e(wp_strip_all_tags(get_the_content()), "noobs-quiz"); ?>
								</p>
							<?php endif; ?>
							<div class="noobs-quiz-options-wrapper">
								<?php
								$options_meta = get_post_meta(get_the_ID(), "noobs_quiz_question_options", true);
								$options = !empty($options_meta) ? $options_meta : [];
								$uniq = uniqid();
								foreach ($options as $key => $option) : ?>
									<div class="noobs-quiz-options">
										<input name="noobs-quiz-<?php echo esc_attr(get_the_ID()); ?>" type="radio" id="<?php echo esc_attr($uniq . $key); ?>" value="<?php esc_attr_e($option, "noobs-quiz") ?>">
										<label for="<?php echo esc_attr($uniq . $key); ?>">
											<?php esc_html_e($option, 'noobs-quiz');  ?>
										</label>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endwhile; ?>
						<button class="noobs-quiz-submit" type="submit">Submit Answer</button>
					</form> <!-- Form end -->
				</div>
			</div>
<?php endif;
	}
}
