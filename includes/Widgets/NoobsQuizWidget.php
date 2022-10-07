<?php

namespace Noobsplugin\Noobsquiz\Widgets;

use \Elementor\Widget_Base;
use WP_Query;

class NoobsQuizWidget extends Widget_Base
{
	public function get_name()
	{
		return 'noobs_quiz_questions';
	}

	public function get_title()
	{
		return esc_html__('Noobs Quiz Questions', 'noobs-quiz');
	}

	public function get_icon()
	{
		return 'eicon-preview-medium';
	}

	public function get_categories()
	{
		return ['basic'];
	}

	public function get_keywords()
	{
		return ['noobs', 'quiz', 'question'];
	}

	public function get_question_category()
	{
		$categories_query = get_categories([
			"hide_empty" => true,
			"taxonomy"   => "question_category"
		]);

		$category_list = [];

		foreach ($categories_query as $category) {
			$category_list[$category->term_id] = esc_html__($category->name, "noobs-quiz");
		}

		return $category_list;
	}

	public function get_questions()
	{
		$question_query = get_posts([
			'post_type'        => 'question',
			'posts_per_page'   => -1
		]);

		$question_list = [];
		foreach ($question_query as $question) {
			$question_list[$question->ID] = esc_html__(wp_trim_words($question->post_title, 10), 'noobs-quiz');
		}
		wp_reset_postdata();
		return $question_list;
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'noob_quiz_query_section',
			[
				'label' => esc_html__('Query', 'noobs-quiz'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'noobs_quiz_query_by',
			[
				'label' => esc_html__('Query By', 'noobs-quiz'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => [
					'date'      => esc_html__('Date', 'noobs-quiz'),
					'category'  => esc_html__('Category', 'noobs-quiz'),
					'rand'    => esc_html__('Random', 'noobs-quiz'),
					'custom'    => esc_html__('Custom Selection', 'noobs-quiz'),
				],
			]
		);

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

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		extract($settings);

		$args = [
			'post_type' => 'question'
		];
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

			case 'date':
				$args['orderby'] = "date";
				break;
			case 'rand':
				$args['orderby'] = 'rand';
				break;
		}

		$args['order'] = !empty($noobs_quiz_order) ? $noobs_quiz_order : '';

		$query = new WP_Query($args);
		if ($query->have_posts()) : ?>
			<div class="noobs-quiz">
				<div class="noobs-quiz-content">
					<form action="" method="post">
						<?php
						while ($query->have_posts()) {
							$query->the_post();
						?>
							<?php if (get_the_title()) : ?>
								<h2 class="noobs-quiz-content-title"><?php the_title(); ?></h2>
							<?php endif; ?>
							<div class="noobs-quiz-content-desc">
								<?php the_content(); ?>
							</div>
							<?php
							$options = !empty(get_post_meta(get_the_ID(), "noobs_quiz_question_options", true)) ? get_post_meta(get_the_ID(), "noobs_quiz_question_options", true) : [];
							$uniq = uniqid();
							foreach ($options as $key => $option) {
							?>
								<div class="noobs-quiz-options">
									<input name="noobs-quiz-<?php echo esc_attr(get_the_ID()); ?>" type="radio" id="<?php echo esc_attr($uniq); ?>" value="<?php esc_attr_e($option, "noobs-quiz") ?>">
									<label for="<?php echo esc_attr($uniq); ?>"><?php esc_html_e($option, 'noobs-quiz');  ?></label>
								</div>
						<?php
							}
						}
						?>
						<button class="noobs-quiz-submit" type="submit">Submit Answer</button>
					</form>
				</div>
			</div>
<?php endif;
	}
}
