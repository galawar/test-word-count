<?php
/*
 Plugin Name: Test Word Count Plugin
 Description: Test plugin.
 Vaersion: 0.1
 Author: galawar
 Authir URI:
*/

class TestWordCount {
	private const OPTIONS_PAGE = 'word-count-settings-page';
	private const OPTION_GROUP = 'wordcountplugin';

	public function __construct() {
		add_action(
			'admin_menu',
			array( $this, 'adminPage' )
		);

		add_action(
			'admin_init',
			array( $this, 'settings' )
		);
	}

	public function settings() {
		// register "Display location" field.
		add_settings_section(
			'wcp_location_section',
			null,
			null,
			self::OPTIONS_PAGE
		);

		add_settings_field(
			'wcp_location',
			'Display location',
			array( $this, 'locationHTML' ),
			self::OPTIONS_PAGE,
			'wcp_location_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_location',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '0'
			)
		);
	}

	public function locationHTML() {
		?>
		<select name="wcp_location">
			<option value="0" <?php selected( get_option( 'wcp_location' ), '0' ); ?> >Beginning of post</option>
			<option value="1" <?php selected( get_option( 'wcp_location' ), '1' ); ?> >End of post</option>
		</select>
		<?php
	}

	public function adminPage() {
		add_options_page(
			'Word Count Settings',
			'Word Count',
			'manage_options',
			self::OPTIONS_PAGE,
			array( $this, 'pageHTML' ),
		);
	}

	public function pageHTML() {
		?>
		<div class="wrap">
			<h1>Word Count settings.</h1>
			<form action="options.php" method="POST">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::OPTIONS_PAGE );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

$wordCountPlugin = new TestWordCount();