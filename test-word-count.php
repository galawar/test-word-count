<?php
/*
 Plugin Name: Test Word Count Plugin
 Description: Test plugin.
 Vaersion: 0.1
 Author: galawar
 Authir URI:
*/

class TestWordCount {
	public function __construct() {
		add_action(
			'admin_menu',
			array( $this, 'adminPage' )
		);
	}

	public function adminPage() {
		add_options_page(
			'Word Count Settings',
			'Word Count',
			'manage_options',
			'word-count-settings-page',
			array( $this, 'pageHTML' ),
		);
	}

	public function pageHTML() {
		?>
		<div class="wrap">
			<h1>Word Count settings.</h1>
		</div>
		<?php
	}
}

$wordCountPlugin = new TestWordCount();