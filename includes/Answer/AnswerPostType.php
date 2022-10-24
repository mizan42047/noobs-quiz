<?php

namespace Noobsplugin\Noobsquiz\Answer;
defined('ABSPATH') || exit;
/**
 * Answer post type class
 * It will register answer post type and handle answer
 * @since 1.0.0
 * @author Mijanur Rahman  
 */
class AnswerPostType
{
	/**
	 * initializer of the AnswerPostType class
	 * @since 1.0.0
	 * @author Mijanur Rahmna 
	 */
	function init()
	{
		add_action("init", [$this, "register_noobs_quiz_answer_post_type"]);
	}

	/**
	 * register_noobs_quiz_answer_post_type method
	 * Register a answer post type for add answer
	 * @since 1.0.0
	 * @author Mijanur Rahmna 
	 */
	public function register_noobs_quiz_answer_post_type()
	{
		$labels = $this->answer_post_type_labels();
		$args   = $this->answer_post_type_args($labels);
		register_post_type("answer", $args);
	}

	/**
	 * post_type_labels method
	 * It will store post type label
	 * @return labels
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function answer_post_type_labels()
	{
		$labels = [
			"name" => esc_html__("Answers", "noobs-quiz"),
			"singular_name" => esc_html__("Answer", "noobs-quiz"),
			"menu_name" => esc_html__("Answer", "noobs-quiz"),
			"all_items" => esc_html__("All Answers", "noobs-quiz"),
			"add_new_item" => esc_html__("Add New Answer", "noobs-quiz"),
			"edit_item" => esc_html__("Edit Answer", "noobs-quiz"),
			"new_item" => esc_html__("New Answer", "noobs-quiz"),
			"view_item" => esc_html__("View Answer", "noobs-quiz"),
			"view_items" => esc_html__("View Answers", "noobs-quiz"),
			"search_items" => esc_html__("Search Answer", "noobs-quiz"),
			"not_found" => esc_html__("No Answer Found", "noobs-quiz"),
			"not_found_in_trash" => esc_html__("No Answer Found in Trash", "noobs-quiz"),
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
	public function answer_post_type_args($labels)
	{
		$args = [
			"label" => esc_html__("Answer", "noobs-quiz"),
			"labels" => $labels,
			"description" => "Add answer for quiz",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => true,
			"capability_type" => "post",
			'capabilities' => array(
				'create_posts' => false,
			),
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => true,
			"query_var" => true,
			"menu_icon" => NOOBSQUIZ_ASSETS . "img/Success_perspective_matte.png",
			"supports" => ["title", "editor", "custom-fields"],
			"show_in_graphql" => false,
		];

		return $args;
	}

}
