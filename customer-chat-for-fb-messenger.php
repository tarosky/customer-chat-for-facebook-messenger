<?php
/**
 * Plugin Name:     Customer Chat for Facebook Messenger
 * Plugin URI:      https://github.com/tarosky/customer-chat-for-fb-messenger
 * Description:     This plugin allows you to integrate customer chat based on facebook messenger.
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Text Domain:     customer-chat-for-facebook-messenger
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         FB_Customer_Chat
 */

require_once( dirname( __FILE__ ) . '/lib/functions.php' );
require_once( dirname( __FILE__ ) . '/lib/admin.php' );

add_action( 'plugins_loaded', function() {
	if ( is_admin() ) {
		FB_Customer_Chat\Admin::get_instance()->register();
	}
} );

add_action( "wp_footer", function() {
	if ( ! FB_Customer_Chat\can_active_chat() ) {
		return;
	}

	$js = "https://connect.facebook.net/" . FB_Customer_Chat\get_lang() . "/sdk.js";

	?>
	<div
		class="fb-customerchat"
		page_id="<?php echo esc_attr( FB_Customer_Chat\get_page_id() ); ?>"
		ref="<?php echo esc_attr( FB_Customer_Chat\get_ref() ); ?>"
		minimized="<?php echo esc_attr( FB_Customer_Chat\is_minimized() ); ?>"
		theme_color="<?php echo esc_attr( FB_Customer_Chat\get_theme_color() ); ?>"
		logged_in_greeting="<?php echo esc_attr( FB_Customer_Chat\get_logged_in_greeting() ); ?>"
		logged_out_greeting="<?php echo esc_attr( FB_Customer_Chat\get_logged_out_greeting() ); ?>"
	>
	</div>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
			  appId            : '<?php echo esc_js( FB_Customer_Chat\get_app_id() ); ?>',
			  autoLogAppEvents : true,
			  xfbml            : true,
			  version          : 'v2.12'
			});
		};

		(function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "<?php echo esc_url( $js ); ?>";
		 fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
	<?php
} );
