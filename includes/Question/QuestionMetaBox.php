<?php

namespace Noobsplugin\Noobsquiz\Question;

if (!defined("ABSPATH")) exit;
/**
 * QuestionMetaBox class.
 * It's handle queston post type custom meta.
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class QuestionMetaBox
{

	/**
	 * init method.
	 * init method kickoff the Question Meta box class
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function init()
	{
		add_action("init", [$this, "noobs_quiz_question_meta"]);
	}

	/**
	 * noobs_quiz_question_meta method
	 * It's register post meta and block type for Question
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function noobs_quiz_question_meta()
	{
		//Register Block Type for save extra meta data with question
		register_block_type( NOOBSQUIZ_DIR . "/build/question-meta" );
		//noobs_quiz_question_answer handle correct answer of the question
		register_post_meta( 'question', 'noobs_quiz_question_answer', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		) );
		//noobs_quiz_question_answer handle mark of the question
		register_post_meta( 'question', 'noobs_quiz_question_mark', array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		) );
		//noobs_quiz_question_options handle the quiz options
		register_post_meta( 'question', 'noobs_quiz_question_options', array(
			'show_in_rest' => [
				'schema' => [
					'type'  => 'array',
					'items' => [
						'type' => 'string'
					]
				]
			],
			'single' => true,
			'type' => 'array',
		) );
	}
}
