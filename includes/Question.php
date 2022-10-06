<?php
namespace Noobsplugin\Noobsquiz;

use Noobsplugin\Noobsquiz\Question\QuestionMetaBox;

/**
 * Question class
 * Organize the other question related classes
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class Question{

	/**
	 * Constructor of the Question class
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 * @return void
	 */
	function __construct(){}

	/**
	 * init class will create intance of the other questio related classes
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 * @return void 
	 */
	public function init_classes()
	{
		//kickoff question post type
		new Question\QuestionPostType();
		$metabox = new QuestionMetaBox();
		$metabox->init();
	}

}