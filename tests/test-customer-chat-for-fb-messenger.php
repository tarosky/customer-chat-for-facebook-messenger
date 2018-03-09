<?php

class Customer_Chat_Test extends WP_UnitTestCase
{
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	function test_get_app_id() {
		$app_id = FB_Customer_Chat\get_app_id();
		$this->assertSame( '', $app_id );

		update_option( 'fb-customer-chat', array( 'app_id' => "9999" ) );
		$app_id = FB_Customer_Chat\get_app_id();
		$this->assertSame( "9999", $app_id );

		define( 'FB_CUSTOMER_CHAT_APP_ID', "1234" );
		$app_id = FB_Customer_Chat\get_app_id();
		$this->assertSame( "1234", $app_id );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	function test_get_page_id() {
		$page_id = FB_Customer_Chat\get_page_id();
		$this->assertSame( '', $page_id );

		update_option( 'fb-customer-chat', array( 'page_id' => "9999" ) );
		$page_id = FB_Customer_Chat\get_page_id();
		$this->assertSame( "9999", $page_id );

		define( 'FB_CUSTOMER_CHAT_PAGE_ID', "1234" );
		$page_id = FB_Customer_Chat\get_page_id();
		$this->assertSame( "1234", $page_id );
	}

	function test_can_active_chat() {
		$result = FB_Customer_Chat\can_active_chat();
		$this->assertSame( false, $result );

		update_option( 'fb-customer-chat', array(
			'activated' => true
		) );
		$result = FB_Customer_Chat\can_active_chat();
		$this->assertSame( false, $result );

		update_option( 'fb-customer-chat', array(
			'activated' => true,
			'page_id' => '9999',
			'app_id' => '9999',
		) );
		$result = FB_Customer_Chat\can_active_chat();
		$this->assertSame( true, $result );
	}
}
