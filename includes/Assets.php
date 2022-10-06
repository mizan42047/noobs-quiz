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
		add_action("enqueue_block_editor_assets", [$this, "noobs_quiz_assets"]);
	}

	public function noobs_quiz_assets()
	{
		wp_enqueue_script("block-scripts", NOOBSQUIZ_ASSETS . "build/index.js");
	}
}
