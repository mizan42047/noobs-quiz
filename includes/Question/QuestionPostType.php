<?php

namespace Noobsplugin\Noobsquiz\Question;
defined('ABSPATH') || exit;
/**
 * Question post type class
 * It will register qustion post type and handle question
 * @since 1.0.0
 * @author Mijanur Rahman  
 */
class QuestionPostType
{
	/**
	 * constructor of the QuestionPostType class
	 * @since 1.0.0
	 * @author Mijanur Rahmna 
	 */
	function __construct()
	{
		add_action("init", [$this, "register_noobs_quiz_question_post_type"]);
	}

	/**
	 * register_noobs_quiz_question_post_type method
	 * Register a Question post type for add question
	 * @since 1.0.0
	 * @author Mijanur Rahmna 
	 */
	public function register_noobs_quiz_question_post_type()
	{
		$labels = $this->question_post_type_labels();
		$args   = $this->question_post_type_args($labels);
		register_post_type("question", $args);
	}

	/**
	 * post_type_labels method
	 * It will store post type label
	 * @return labels
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function question_post_type_labels()
	{
		$labels = [
			"name" => esc_html__("Questions", "noobs-quiz"),
			"singular_name" => esc_html__("Question", "noobs-quiz"),
			"menu_name" => esc_html__("Question", "noobs-quiz"),
			"all_items" => esc_html__("All Questions", "noobs-quiz"),
			"add_new_item" => esc_html__("Add New Question", "noobs-quiz"),
			"edit_item" => esc_html__("Edit Question", "noobs-quiz"),
			"new_item" => esc_html__("New Question", "noobs-quiz"),
			"view_item" => esc_html__("View Question", "noobs-quiz"),
			"view_items" => esc_html__("View Questions", "noobs-quiz"),
			"search_items" => esc_html__("Search Question", "noobs-quiz"),
			"not_found" => esc_html__("No Question Found", "noobs-quiz"),
			"not_found_in_trash" => esc_html__("No Question Found in Trash", "noobs-quiz"),
		];

		return $labels;
	}

	/**
	 * post_type_args method
	 * It will store post type args
	 * @return args
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function question_post_type_args($labels)
	{
		$args = [
			"label" => esc_html__("Questions", "noobs-quiz"),
			"labels" => $labels,
			"description" => "Add question for quiz",
			"public" => true,
			"publicly_queryable" => false,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => true,
			"rewrite" => ["slug" => "question", "with_front" => true],
			"query_var" => true,
			"menu_icon" => "dashicons-info",
			"supports" => ["title", "editor", "custom-fields"],
			"taxonomies" => ["question_category"],
			"show_in_graphql" => false,
		];

		return $args;
	}

}
