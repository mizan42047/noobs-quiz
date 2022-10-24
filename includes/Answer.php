<?php
namespace Noobsplugin\Noobsquiz;

use Noobsplugin\Noobsquiz\Answer\AnswerPostType;

defined('ABSPATH') || exit;

/**
 * Answer Class.
 * Organize other answer related class
 * @author Mijanur Rahman.
 * @since 1.0.0
 */

class Answer{
	/**
	 * init method.
	 * It's initialize the answer class.
	 * @return Answer
	 * @author Mijanur Rahman
	 * @since 1.0.0
	 */
	public function init()
	{
		$answer_post_type = new AnswerPostType();
		$answer_post_type->init();
	}
}

