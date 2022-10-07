<?php

namespace Noobsplugin\Noobsquiz;

if (!defined("ABSPATH")) exit;

/**
 * Assets class
 * It will ordganize our assets
 * @since 1.0.0
 * @author Mijanur Rahman
 */
class Assets{
	/**
	 * init method
	 * Create instance of the Assets class
	 * @since 1.0.0
	 * @author Mijanur Rahman
	 */
	public function init()
	{
		add_action("wp_enqueue_scripts", [$this, "noobs_quiz_assets"]);
	}

	public function noobs_quiz_assets()
	{
		wp_register_script("widget-scripts", NOOBSQUIZ_ASSETS . "widget.js", ["jquery"], get_post_modified_time(), true);
	}

}
