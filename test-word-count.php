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
		add_settings_section(
			'wcp_main_section',
			null,
			null,
			self::OPTIONS_PAGE
		);

		// register "Display location" field.
		add_settings_field(
			'wcp_location',
			'Display location',
			array( $this, 'locationHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_location',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '0'
			)
		);

		// register "Headline" field.
		add_settings_field(
			'wcp_headline',
			'Headline text',
			array( $this, 'headlineHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_headline',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => 'Post satistics'
			)
		);

		// register "Word count" field.
		add_settings_field(
			'wcp_wordcount',
			'Word count',
			array( $this, 'wordcountHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_wordcount',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '1'
			)
		);

		// register "Characters count" field.
		add_settings_field(
			'wcp_charcount',
			'Characters count',
			array( $this, 'charcountHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_charcount',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '0'
			)
		);

		// register "Read time" field.
		add_settings_field(
			'wcp_readtime',
			'Read time',
			array( $this, 'readtimeHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section'
		);

		register_setting(
			self::OPTION_GROUP,
			'wcp_readtime',
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

	public function headlineHTML() {
		?>
		<input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ); ?>">
		<?php
	}

	public function wordcountHTML() {
		?>
		<input type="checkbox" name="wcp_wordcount" value="1" <?php checked( get_option( 'wcp_wordcount' ), '1' ); ?> >
		<?php
	}

	public function charcountHTML() {
		?>
		<input type="checkbox" name="wcp_charcount" value="1" <?php checked( get_option( 'wcp_charcount' ), '1' ); ?> >
		<?php
	}

		public function readtimeHTML() {
		?>
		<input type="checkbox" name="wcp_readtime" value="1" <?php checked( get_option( 'wcp_readtime' ), '1' ); ?> >
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