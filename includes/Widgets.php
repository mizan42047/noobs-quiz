<?php
namespace Noobsplugin\Noobsquiz;

use Noobsplugin\Noobsquiz\Widgets\NoobsQuizWidget;
defined('ABSPATH') || exit;
/**
 * Widgets class
 * widgets class will organize all custom elementor widgets file
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class Widgets{

	public function init()
	{
		add_action( 'elementor/init', [ $this, 'noobs_quiz_elementor_init' ] );
	}

	/**
	 * noobs_quiz_elementor_init
	 * It will help to initilize elementor in plugin
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */

	 public function noobs_quiz_elementor_init()
	 {
		add_action( 'elementor/widgets/register', [ $this, 'noobs_quiz_register_widgets' ] );
	 }

	 //Register elementor widget
	 public function noobs_quiz_register_widgets($widgets_manager)
	 {
		$widgets_manager->register( new NoobsQuizWidget() );
	 }

}