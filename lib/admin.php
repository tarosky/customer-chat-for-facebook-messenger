<?php

namespace FB_Customer_Chat;

/**
 * Customize the list table on the admin screen.
 *
 * @package LogBook
 */
final class Admin
{
	public function __construct()
	{
	}

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new Admin();
		}
		return $instance;
	}

	public function register()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_menu() {
		add_options_page(
			'Customer Chat',
			'Customer Chat',
			'manage_options',
			'fb-customer-chat',
			array( $this, "display" )
		);
	}

	public function admin_init()
	{
		register_setting( 'fb-customer-chat-settings', 'fb-customer-chat' );

		add_settings_section( 'section-1', 'Facebook App', null, 'fb-customer-chat' );
		add_settings_section( 'section-2', 'Customer Chat Plugin', function() {
			$url = 'https://developers.facebook.com/docs/messenger-platform/reference/web-plugins/#customer_chat';
			echo 'Options for the customer chat plugin for facebook.<br />';
			echo 'For usage details, <a href="' . $url . '">see documentation</a>.';
		}, 'fb-customer-chat' );

		add_settings_field(
			'app_id',
			'App ID',
			function() {
				$app_id = esc_attr( get_app_id() );
				echo "<input type='text' name='fb-customer-chat[app_id]' value='$app_id' required />";
				echo ' <a href="https://developers.facebook.com/apps/">Create a new facebook app.</a>';
			},
			'fb-customer-chat',
			'section-1'
		);

		add_settings_field(
			'lang',
			'Language',
			function() {
				$lang = esc_attr( get_lang() );
				echo "<input type='text' name='fb-customer-chat[lang]' value='$lang' required />";
			},
			'fb-customer-chat',
			'section-1'
		);

		add_settings_field(
			'page_id',
			'page_id',
			function() {
				$page_id = esc_attr( get_page_id() );
				echo "<input type='text' name='fb-customer-chat[page_id]' value='$page_id' required /> (required)";
			},
			'fb-customer-chat',
			'section-2'
		);

		add_settings_field(
			'minimized',
			'minimized',
			function() {
				$minimized = esc_attr( is_minimized() );

				if ( 'true' === $minimized ) {
					echo "<label><input type='radio' name='fb-customer-chat[minimized]' value='auto' /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' checked /> Yes</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' /> No</label>";
				} elseif( 'false' === $minimized ) {
					echo "<label><input type='radio' name='fb-customer-chat[minimized]' value='auto' /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' /> Yes</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' checked /> No</label>";
				} else {
					echo "<label><input type='radio' name='fb-customer-chat[minimized]' value='auto' checked /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' /> Yes</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' /> No</label>";
				}
			},
			'fb-customer-chat',
			'section-2'
		);

		add_settings_field(
			'logged-in-greeting',
			'logged_in_greeting',
			function() {
				$logged_in_greeting = esc_attr( get_logged_in_greeting() );
				echo "<input type='text' name='fb-customer-chat[logged_in_greeting]' value='$logged_in_greeting' />";
			},
			'fb-customer-chat',
			'section-2'
		);

		add_settings_field(
			'logged-out-greeting',
			'logged_out_greeting',
			function() {
				$logged_out_greeting = esc_attr( get_logged_out_greeting() );
				echo "<input type='text' name='fb-customer-chat[logged_out_greeting]' value='$logged_out_greeting' />";
			},
			'fb-customer-chat',
			'section-2'
		);
	}

	public function display()
	{
		echo '<div class="wrap fb-customer-chat-settings">';
		echo '<h1 class="wp-heading-inline">Customer Chat Settings</h1>';

		$action = untrailingslashit( admin_url() ) . '/options.php';
		echo '<form action="' . esc_url( $action ) . '" method="post">';
        settings_fields('fb-customer-chat-settings');
		do_settings_sections('fb-customer-chat');
		submit_button();
		echo '</form>';

		echo '</div>';
	}
}
