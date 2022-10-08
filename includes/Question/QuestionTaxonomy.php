<?php
namespace Noobsplugin\Noobsquiz\Question;
defined('ABSPATH') || exit;
/**
 * QuestionTaxonomy
 * It's handle custom taxonomy for question 
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class QuestionTaxonomy{

	/**
	 * init
	 * initilize QuestionTaxonomy class
	 * @since 1.0.0
	 * @author Mijanur Rahaman
	 */
	public function init()
	{
		add_action( 'init', [$this, 'noobs_quiz_register_taxonomy'] );
	}

	function noobs_quiz_register_taxonomy() {

		/**
		 * Taxonomy: Categories.
		 * @since 1.0.0
		 * @author Mijanur Rahman
		 */
	
		$labels = [
			"name" => esc_html__( "Categories", "twentytwentytwo" ),
			"singular_name" => esc_html__( "Category", "twentytwentytwo" ),
		];
	
		
		$args = [
			"label" => esc_html__( "Categories", "twentytwentytwo" ),
			"labels" => $labels,
			"public" => true,
			"publicly_queryable" => false,
			"hierarchical" => true,
			"show_ui" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"query_var" => true,
			"rewrite" => [ 'slug' => 'question_category', 'with_front' => true, ],
			"show_admin_column" => false,
			"show_in_rest" => true,
			"show_tagcloud" => false,
			"rest_base" => "question_category",
			"rest_controller_class" => "WP_REST_Terms_Controller",
			"rest_namespace" => "wp/v2",
			"show_in_quick_edit" => false,
			"sort" => false,
			"show_in_graphql" => false,
		];
		register_taxonomy( "question_category", [ "question" ], $args );
	}
}
