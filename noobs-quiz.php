<?php
/**
 * Plugin Name:       Noobs Quiz
 * Plugin URI:        https://wordpress.org/noobs-quiz
 * Description:       Noobs Quiz is a plugin that help you to make quiz syatem in your website.Supports shortcode and Elementor Widget.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Noobs Plugins
 * Author URI:        mijandev.com
 * Text Domain:       noobs-quiz
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://wordpress.org/noobs-quiz
 */

use Noobsplugin\Noobsquiz\Assets;
use \Noobsplugin\Noobsquiz\Question;

if (!defined("ABSPATH")) exit;
//Required autoload file
require_once __DIR__. "/vendor/autoload.php";

/**
 * Main class of the Noobs Quiz plugin
 * @var Class Noobs Quiz
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class NoobsQuiz
{
	/** @var constant $version Version of the plugin */
	const version = "1.0.0";

	/**
	 * Noobs Quiz Contstructor
	 * Private for Singleton
	 * @return void
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	private function __construct()
	{
		$this->helper_constnat();
		//fires after plugin activate
		register_activation_hook(NOOBSQUIZ_FILE, [$this,"noobs_quiz_activation"]);
		//plugins loaded hooks
		add_action("plugins_loaded",[$this,"noobs_quiz_plugins_loaded"]);
	}

	/**
	 * initializer of the Noobs Quiz Plugin.
	 * init method will handle singleton design pattern and return the instance of the Noobs Quiz Plugin.
	 * @return \NoobsQuiz
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 **/
	public static function init()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Helper constant method.
	 * Helps to development lifecyle with necessary constant.
	 * @return void
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 **/
	public function helper_constnat()
	{
		//plugin version
		define("NOOBSQUIZ_VERSION", self::version);
		//Root directory of the plugin
		define("NOOBSQUIZ_DIR", __DIR__);
		//Root file of the plugin
		define("NOOBSQUIZ_FILE", __FILE__);
		//root url of the plugin
		define("NOOBSQUIZ_URL", plugin_dir_url(NOOBSQUIZ_FILE));
		//assets url of the plugin
		define("NOOBSQUIZ_ASSETS", NOOBSQUIZ_URL . "assets/");
	}

	/**
	 * noobs_quiz_activation method.
	 * this method will add vesion and installed time of the plugin in options table
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 * @return void
	 */
	public function noobs_quiz_activation()
	{
		//Update veraion to the option table
		update_option("noobs_quiz_version", NOOBSQUIZ_VERSION);
		//added installed time after checking time exist or not
		if(!get_option("noobs_quiz_installed_time")){
			add_option("noobs_quiz_installed_time",time());
		}
	}

	/**
	 * noobs_quiz_plugins_loaded method.
	 * It will loads after plugins loaded.we initilize our others class is=nside this plugin.
	 * @return void
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function noobs_quiz_plugins_loaded()
	{
		$question = new Question();
		$question->init_classes();
		$assets = new Assets();
		$assets->init();
	}

}

/**
 * initialize the main plugin
 * @return \NoobsQuiz
 * @since 1.0.0
 * @author Mijanur Rahman
 */
function noobs_quiz()
{
	return NoobsQuiz::init();
}

//Kickoff the plugin
noobs_quiz();
