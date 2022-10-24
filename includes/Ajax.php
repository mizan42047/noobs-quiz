<?php
namespace Noobsplugin\Noobsquiz;
defined('ABSPATH') || exit;

/**
 * Ajax
 * It's handle the ajax request for plugin
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class Ajax{
	/**
	 * init
	 * Initializer of the plugin
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function init()
	{
		add_action("wp_ajax_noobs_quiz_question_form", [$this, "noobs_quiz_question_form_ajax"]);
		add_action("wp_ajax_nopriv_noobs_quiz_question_form", [$this, "noobs_quiz_question_form_ajax"]);
	}

	/**
	 * noobs_quiz_question_form_ajax
	 * Handle Ajax response and validation
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function noobs_quiz_question_form_ajax()
	{
		if(!wp_verify_nonce($_REQUEST['_wpnonce'], "noobs_quiz_question")){
			wp_send_json_error([
				"message" => __("Sorry Nonce is not verified"),
			]);
		}else{
			if(!empty($_POST)){
				$total = [];
				$count = 0;
				$username = str_replace('"', '', sanitize_text_field($_POST['noobs_quiz_username']));
				foreach($_POST as $post_id => $given_answer){
					if(str_contains($post_id, "noobs-quiz")){
						$count++;
						$valid_id = (int) str_replace("noobs-quiz-","",$post_id);
						$answer = get_post_meta($valid_id, "noobs_quiz_question_answer", true);
						if($answer === $given_answer){
							$mark = get_post_meta($valid_id, "noobs_quiz_question_mark", true);
							array_push($total, $mark);
						}
					}
				}

				$post_content = "Hey {$username}! You got total : ".array_sum($total)." You given correct answer total :  ".count($total)." You given wrong answer total : ".absint($count - count($total));

				wp_insert_post([
					'post_author' => 1,
					'post_title'  => $username,
					'post_content' => do_blocks($post_content),
					'post_status' => 'publish',
					'post_type'   => 'answer',
				]);
			}

			wp_send_json_success([
				"message"      => __("Request Successful"),
				"total"        => array_sum($total),
				"right_answer" => count($total),
				"wrong_answer" => absint($count - count($total))
			]);
		}
	}


}