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

		add_filter(
			'the_content',
			array( $this, 'showCounters' )
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
			array( $this, 'checkboxHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section',
			array( 'name' => 'wcp_wordcount' )
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
			array( $this, 'checkboxHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section',
			array( 'name' => 'wcp_charcount' )
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
			array( $this, 'checkboxHTML' ),
			self::OPTIONS_PAGE,
			'wcp_main_section',
			array( 'name' => 'wcp_readtime' )
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

	public function showCounters( $content ) {
		if ( is_main_query() && is_single() &&
			(
				get_option( 'wcp_wordcount', '1' ) ||
				get_option( 'wcp_charcount', '1' ) ||
				get_option( 'wcp_readtime', '1' )
			) ) {
				return $this->updateContent( $content );
		}

		return $content;
	}

	public function updateContent( $content ) {
		$html = '<h3>'. esc_html( get_option( 'wpc_headline', 'Post statistics' ) ) .'</h3><p>';

		if ( get_option( 'wcp_wordcount', '1' ) || get_option( 'wcp_readtime', '1' ) ) {
			$wordCount = str_word_count( strip_tags( $content ) );
		}

		if ( get_option( 'wcp_wordcount', '1' ) ) {
			$html .= 'This post has '. $wordCount .' words. <br>';
		}

		if ( get_option( 'wcp_charcount', '1' ) ) {
			$html .= 'This post has '. strlen( strip_tags( $content ) ) .' characters. <br>';
		}

		if ( get_option( 'wcp_readtime', '1' ) ) {
			$html .= 'This post will take about '. round( $wordCount / 225 ) .' minute(s) to read. <br>';
		}

		$html .= '</p>';

		if ( get_option( 'wcp_location', '0' ) == '0' ) {
			return $html . $content;
		}
		
		return $content . $html;
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

	public function checkboxHTML( $args ) {
		?>
			<input type="checkbox" value="1"
						 name="<?php echo $args['name'] ?>"
						 <?php checked( get_option( $args['name'] ), '1' ); ?>
			>
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