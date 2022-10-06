<?php
namespace Noobsplugin\Noobsquiz\Widgets;
use Elementor\Widget_Base;
use WP_Query;

class NoobsQuizWidget extends Widget_Base{
	public function get_name() {
		return 'noobs_quiz_questions';
	}

	public function get_title() {
		return esc_html__( 'Noobs Quiz Questions', 'noobs-quiz' );
	}

	public function get_icon() {
		return 'eicon-preview-medium';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'noobs', 'quiz', 'question' ];
	}

	protected function register_controls() {}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$args = [
			'post_type' => 'question',
		];
		$query = new WP_Query($args);

		$query = new WP_Query($args);

		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post();
				the_title();
				var_dump(get_post_meta(get_the_ID(),'noobs_quiz_question_options'));
			}
		}
	}
}