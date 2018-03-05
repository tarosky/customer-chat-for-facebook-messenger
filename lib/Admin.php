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
				if ( defined( 'FB_CUSTOMER_CHAT_APP_ID' ) && FB_CUSTOMER_CHAT_APP_ID ) {
					echo "<input type='text' name='fb-customer-chat[app_id]' value='$app_id' required disabled />";
					echo ' <a href="https://developers.facebook.com/apps/">Create a new facebook app.</a>';
				} else {
					echo "<input type='text' name='fb-customer-chat[app_id]' value='$app_id' required />";
					echo ' <a href="https://developers.facebook.com/apps/">Create a new facebook app.</a>';
				}
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
				if ( defined( 'FB_CUSTOMER_CHAT_PAGE_ID' ) && FB_CUSTOMER_CHAT_PAGE_ID ) {
					echo "<input type='text' name='fb-customer-chat[page_id]' value='$page_id' required disabled />";
				} else {
					echo "<input type='text' name='fb-customer-chat[page_id]' value='$page_id' required />";
				}
				echo "<p class='description'>Your page ID.</p>";
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
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' checked /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' /> false</label>";
				} elseif( 'false' === $minimized ) {
					echo "<label><input type='radio' name='fb-customer-chat[minimized]' value='auto' /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' checked /> false</label>";
				} else {
					echo "<label><input type='radio' name='fb-customer-chat[minimized]' value='auto' checked /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='true' /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='fb-customer-chat[minimized]' value='false' /> false</label>";
				}
				echo "<p class='description'><strong>Optional.</strong> Specifies whether the plugin should be minimized or shown.
						Defaults to <code>false</code> on desktop and <code>true</code> on mobile browsers.</p>";
			},
			'fb-customer-chat',
			'section-2'
		);

		add_settings_field(
			'theme-color',
			'theme_color',
			function() {
				$theme_color = esc_attr( get_theme_color() );
				echo "<input type='text' name='fb-customer-chat[theme_color]' value='$theme_color' />";
				echo "<p class='description'><strong>Optional.</strong> 
						Specifies a hexidecimal color code to use as a theme for the plugin, 
							including the customer chat icon and the background color of messages sent by users. 
								All colors except white are supported. 
								The color code has to start with a leading number sign, e.g. 
									<code>#0084FF.</code></p>";
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
				echo "<p class='description'><strong>Optional.</strong> 
						The greeting text that will be displayed if the user is currently logged in to Facebook. 
							Maximum 80 characters.</p>";
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
				echo "<p class='description'><strong>Optional.</strong> 
						The greeting text that will be displayed if the user is not currently logged in to Facebook. 
							Maximum 80 characters.</p>";
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
